<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class FilesController
{
    private const MAX_FILE_BYTES = 1048576;
    private const MAX_RESULT_BYTES = 8388608;

    public function roots(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return Json::respond(self::noStore($response), self::run('roots'));
    }

    public function entries(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return Json::respond(self::noStore($response), self::run('entries', self::location($request, true)));
    }

    public function read(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $file = self::run('read', self::location($request));
        return Json::respond(self::noStore($response)->withHeader('ETag', $file['etag']), ['content' => $file['content']]);
    }

    public function write(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $location = self::location($request);
        $etag = trim($request->getHeaderLine('If-Match'), " \t");
        if ($etag === '') {
            throw new ApiError(428, 'precondition_required', 'En-tête If-Match requis.');
        }
        if (preg_match('/\A"[a-f0-9]{64}"\z/', $etag) !== 1) {
            throw new ApiError(400, 'invalid_if_match', 'Un ETag fort renvoyé par la lecture du fichier est attendu.');
        }
        // Un octet de texte peut occuper jusqu’à six octets dans le JSON (\uXXXX).
        $body = Json::body($request, 6 * self::MAX_FILE_BYTES + 1024);
        $content = $body['content'] ?? null;
        if (array_keys($body) !== ['content'] || !is_string($content)) {
            throw ApiError::field(422, 'validation_error', 'content', 'Un champ texte est attendu.');
        }
        if (strlen($content) > self::MAX_FILE_BYTES) {
            throw new ApiError(413, 'file_too_large', 'Le fichier dépasse la taille maximale de 1 Mio.');
        }

        $file = self::run('write', $location + ['etag' => $etag, 'content' => $content]);
        if (str_ends_with(strtolower($location['path']), '.php') && function_exists('opcache_invalidate')) {
            @opcache_invalidate(dirname(__DIR__, 4) . '/' . $location['path'], true);
        }
        return self::noStore($response)->withHeader('ETag', $file['etag'])->withStatus(204);
    }

    private static function location(Request $request, bool $directory = false): array
    {
        $query = $request->getQueryParams();
        $root = $query['root'] ?? null;
        $path = $query['path'] ?? ($directory ? '' : null);
        if (!is_string($root) || preg_match('/\A[a-z-]{1,32}\z/', $root) !== 1) {
            throw ApiError::field(422, 'validation_error', 'root', 'Un identifiant de racine est attendu.');
        }
        if (!is_string($path) || strlen($path) > 1024 || (!$directory && $path === '') || preg_match('//u', $path) !== 1) {
            throw ApiError::field(422, 'validation_error', 'path', 'Un chemin relatif UTF-8 est attendu.');
        }
        return ['root' => $root, 'path' => $path];
    }

    private static function noStore(Response $response): Response
    {
        return $response->withHeader('Cache-Control', 'private, no-store');
    }

    private static function run(string $operation, array $input = []): array
    {
        $unavailable = new ApiError(503, 'file_explorer_unavailable', 'Explorateur de fichiers indisponible.');
        if (!function_exists('proc_open') || !is_executable('/usr/bin/python3')) {
            throw $unavailable;
        }
        $script = __DIR__ . '/../../../../resources/rest_file_explorer.py';
        $payload = json_encode((object) $input, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $process = proc_open(['/usr/bin/python3', '-I', $script, $operation], [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['file', '/dev/null', 'w'],
        ], $pipes);
        if ($process === false) {
            throw $unavailable;
        }
        try {
            $written = fwrite($pipes[0], $payload);
            fclose($pipes[0]);
            unset($pipes[0]);
            $output = stream_get_contents($pipes[1], self::MAX_RESULT_BYTES + 1);
        } finally {
            foreach ($pipes as $pipe) {
                fclose($pipe);
            }
            $exitCode = proc_close($process);
        }
        if ($written !== strlen($payload) || $output === false || strlen($output) > self::MAX_RESULT_BYTES || $exitCode !== 0) {
            throw $unavailable;
        }
        try {
            $result = json_decode($output, true, 32, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw $unavailable;
        }
        if (isset($result['error'])) {
            $error = $result['error'];
            throw new ApiError($error['status'], $error['code'], $error['message']);
        }
        return $result;
    }
}
