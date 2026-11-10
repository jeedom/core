<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Stream;

final class FilesController
{
    private const MAX_FILE_BYTES = 1048576;
    private const MAX_TRANSFER_BYTES = 10 * 1048576;
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

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $location = self::location($request);
        $body = Json::body($request, 1024);
        $type = $body['type'] ?? null;
        if (array_keys($body) !== ['type'] || !in_array($type, ['file', 'directory'], true)) {
            throw ApiError::field(422, 'validation_error', 'type', 'Un fichier ou un répertoire est attendu.');
        }
        return Json::respond(self::noStore($response), self::run('create', $location + ['type' => $type]), 201);
    }

    public function delete(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        self::run('delete', self::location($request));
        return self::noStore($response)->withStatus(204);
    }

    public function upload(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $location = self::location($request);
        $type = strtolower(trim(explode(';', $request->getHeaderLine('Content-Type'), 2)[0]));
        if ($type !== 'application/octet-stream') {
            throw new ApiError(415, 'unsupported_file', 'Un corps application/octet-stream est attendu.');
        }

        $body = $request->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        $transfer = self::transferFile();
        try {
            $remaining = self::MAX_TRANSFER_BYTES;
            while (!$body->eof()) {
                $chunk = $body->read(min(65536, $remaining + 1));
                if ($chunk === '') {
                    break;
                }
                $remaining -= strlen($chunk);
                if ($remaining < 0) {
                    throw new ApiError(413, 'file_too_large', 'Le fichier dépasse la taille maximale de 10 Mio.');
                }
                if (fwrite($transfer, $chunk) !== strlen($chunk)) {
                    throw self::unavailable();
                }
            }
            rewind($transfer);
            $file = self::run('upload', $location, $transfer);
        } finally {
            fclose($transfer);
        }
        return Json::respond(self::noStore($response), $file, 201);
    }

    public function download(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $location = self::location($request);
        $transfer = self::transferFile();
        try {
            self::run('download', $location, $transfer);
            rewind($transfer);
            $body = new Stream($transfer);
        } catch (\Throwable $error) {
            fclose($transfer);
            throw $error;
        }
        $name = basename($location['path']);
        $fallback = preg_replace('/[^A-Za-z0-9_.-]/', '_', $name);
        return self::noStore($response)->withBody($body)
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Disposition', "attachment; filename=\"$fallback\"; filename*=UTF-8''" . rawurlencode($name))
            ->withHeader('X-Content-Type-Options', 'nosniff');
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

    private static function unavailable(): ApiError
    {
        return new ApiError(503, 'file_explorer_unavailable', 'Explorateur de fichiers indisponible.');
    }

    /** @return resource */
    private static function transferFile()
    {
        $transfer = tmpfile();
        if ($transfer === false) {
            throw self::unavailable();
        }
        return $transfer;
    }

    /** @param resource|null $transfer */
    private static function run(string $operation, array $input = [], $transfer = null): array
    {
        if (!function_exists('proc_open') || !is_executable('/usr/bin/python3')) {
            throw self::unavailable();
        }
        $script = __DIR__ . '/../../../../resources/rest_file_explorer.py';
        $payload = json_encode((object) $input, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['file', '/dev/null', 'w'],
        ];
        if ($transfer !== null) {
            $descriptors[3] = $transfer;
        }
        $process = proc_open(['/usr/bin/python3', '-I', $script, $operation], $descriptors, $pipes);
        if ($process === false) {
            throw self::unavailable();
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
            throw self::unavailable();
        }
        try {
            $result = json_decode($output, true, 32, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw self::unavailable();
        }
        if (isset($result['error'])) {
            $error = $result['error'];
            throw new ApiError($error['status'], $error['code'], $error['message']);
        }
        return $result;
    }
}
