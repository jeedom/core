<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** Journaux du core : contenu brut paginé, opérations et protections de log::*. */
final class LogsController
{
    private const MAX_LINE_BYTES = 65536;
    private const MAX_PAGE_BYTES = 1048576;
    private const MAX_SCAN_BYTES = 16777216;

    public function list(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $items = self::names();
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $path = self::find($args['name']);
        $query = $request->getQueryParams();
        $start = self::integer($query['start'] ?? '0', 'start', 0, 1000000);
        $limit = self::integer($query['limit'] ?? '300', 'limit', 1, 1000);
        $handle = @fopen($path, 'rb');
        if ($handle === false) {
            throw new ApiError(403, 'log_not_readable', 'Le journal ne peut pas être lu.');
        }
        $lines = [];
        $number = 0;
        $scanned = 0;
        $bytes = 0;
        $more = false;
        try {
            while ($number < $start || count($lines) < $limit) {
                $line = fgets($handle, self::MAX_LINE_BYTES + 2);
                if ($line === false) {
                    break;
                }
                $scanned += strlen($line);
                if (strlen($line) > self::MAX_LINE_BYTES) {
                    throw new ApiError(413, 'log_line_too_long', 'Une ligne du journal dépasse 64 Kio.');
                }
                if ($number < $start) {
                    if ($scanned > self::MAX_SCAN_BYTES) {
                        throw new ApiError(413, 'log_scan_limit', 'La pagination dépasse la limite de lecture de 16 Mio.');
                    }
                    $number++;
                    continue;
                }
                if ($bytes + strlen($line) > self::MAX_PAGE_BYTES) {
                    $more = true;
                    break;
                }
                $bytes += strlen($line);
                $number++;
                // Conserver indentation, lignes vides et dernier fragment sans saut de ligne.
                $text = str_ends_with($line, "\n") ? substr($line, 0, -1) : $line;
                $text = str_ends_with($text, "\r") ? substr($text, 0, -1) : $text;
                $lines[] = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            }
            $more = $more || fgetc($handle) !== false;
        } finally {
            fclose($handle);
        }
        return Json::respond($response, [
            'name' => $args['name'], 'start' => $start, 'lines' => $lines,
            'nextStart' => $more ? $start + count($lines) : null,
        ]);
    }

    public function add(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $name = $args['name'];
        if (!self::validName($name)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Nom de journal invalide.');
        }
        $path = \log::getPathToLog($name);
        // Autoriser les journaux existants et la création d’un nom simple ou suffixé .log.
        $canCreate = !file_exists($path) && preg_match('/\A[\w-]+(?:\.log)?\z/D', $name);
        if (is_link($path) || (!is_file($path) && !$canCreate)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Ce nom ne peut pas recevoir de journal.');
        }
        $body = Json::body($request, 65536);
        if ($unknown = array_diff(array_keys($body), ['level', 'message', 'logicalId'])) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ de journal inconnu.');
        }
        $level = Json::textField($body, 'level', 9);
        if (!\log::isValidLevel($level)) {
            throw ApiError::field(422, 'validation_error', 'level', 'Niveau de journal invalide.');
        }
        \log::add($name, $level, Json::textField($body, 'message', 8192), Json::textField($body, 'logicalId', 127, ''));
        return $response->withStatus(204);
    }

    public function excerpt(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return LogExcerptReader::respond($request, $response, self::find($args['name']));
    }

    public function clear(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::find($args['name']);
        if (\log::clear($args['name']) !== true) {
            throw new ApiError(409, 'log_not_clearable', 'Le journal ne peut pas être vidé.');
        }
        return $response->withStatus(204);
    }

    public function download(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $path = self::find($args['name']);
        $resolved = realpath($path);
        if ($resolved === false || dirname($resolved) !== realpath(dirname($path))) {
            throw new ApiError(404, 'log_not_found', 'Journal inconnu.');
        }
        $stream = @fopen($resolved, 'rb');
        if ($stream === false) {
            throw new ApiError(403, 'log_not_readable', 'Le journal ne peut pas être lu.');
        }
        $name = $args['name'];
        $filename = str_ends_with(strtolower($name), '.log') ? $name : $name . '.log';
        return $response->withBody(new \Slim\Psr7\Stream($stream))
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('Cache-Control', 'private, no-store');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::find($args['name']);
        return Json::respond($response, ['removed' => self::remove($args['name'])]);
    }

    public function deleteAll(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $removed = [];
        $retained = [];
        foreach (self::names() as $name) {
            if (self::remove($name)) {
                $removed[] = $name;
            } else {
                $retained[] = $name;
            }
        }
        return Json::respond($response, ['removed' => $removed, 'retained' => $retained]);
    }

    private static function remove(string $name): bool
    {
        if (\log::remove($name, false) === true) {
            return true;
        }
        $path = \log::getPathToLog($name);
        clearstatcache(true, $path);
        return !is_file($path);
    }

    private static function validName(string $name): bool
    {
        // Ces noms sont aussi transmis aux helpers historiques qui exécutent des commandes shell.
        return preg_match('/\A[A-Za-z0-9][A-Za-z0-9_.-]{0,254}\z/D', $name) === 1;
    }

    /** @return list<string> */
    private static function names(): array
    {
        $items = array_values(array_filter(\log::liste(), static fn ($name) => is_string($name) && self::validName($name) && is_file(\log::getPathToLog($name))));
        natcasesort($items);
        return array_values($items);
    }

    private static function find(string $name): string
    {
        $path = \log::getPathToLog($name);
        if (!self::validName($name) || !is_file($path)) {
            throw new ApiError(404, 'log_not_found', 'Journal inconnu.');
        }
        return $path;
    }

    private static function integer(mixed $value, string $field, int $min, int $max): int
    {
        if ((!is_string($value) && !is_int($value)) || !ctype_digit((string) $value) || (float) $value < $min || (float) $value > $max) {
            throw ApiError::field(422, 'validation_error', $field, 'Un entier de ' . $min . ' à ' . $max . ' est attendu.');
        }
        return (int) $value;
    }
}
