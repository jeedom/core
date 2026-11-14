<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** Suivi de la restauration, indépendant du bootstrap du Core et de la base. */
final class RestoreStatus
{
    public const PATH = '/api/v1/backups/restore-status';
    private const LIFETIME = 86400;

    public static function show(Request $request, Response $response): Response
    {
        $response = $response->withHeader('Cache-Control', 'no-store')
            ->withHeader('Cross-Origin-Resource-Policy', 'same-origin')
            ->withHeader('X-Content-Type-Options', 'nosniff');

        $status = null;
        if (!in_array($request->getHeaderLine('Sec-Fetch-Site'), ['same-site', 'cross-site'], true)) {
            $name = self::cookieName($request->getServerParams(), $request->getUri()->getScheme());
            $status = self::read($request->getQueryParams()['operation'] ?? null, $request->getCookieParams()[$name] ?? null);
        }
        return $status === null
            ? Json::error($response, new ApiError(404, 'restore_status_not_found', 'Suivi de restauration introuvable.'))
            : Json::respond($response, $status);
    }

    /** @return array{id: string, token: string, expires: int, result: resource} */
    public static function start(): array
    {
        $directory = self::directory(true);
        self::clean($directory);
        $id = bin2hex(random_bytes(16));
        $token = bin2hex(random_bytes(32));
        $expires = time() + self::LIFETIME;
        $header = json_encode(['hash' => hash('sha256', $token), 'expires' => $expires], JSON_THROW_ON_ERROR) . "\n";
        $path = $directory . '/' . $id;
        $result = @fopen($path, 'x+');
        if ($result === false) {
            throw new \RuntimeException('Impossible de créer le suivi de restauration.');
        }
        if (!flock($result, LOCK_EX | LOCK_NB) || @fwrite($result, $header) !== strlen($header) || !@fflush($result)) {
            fclose($result);
            @unlink($path);
            throw new \RuntimeException('Impossible d’enregistrer le suivi de restauration.');
        }
        // Le lanceur hérite du verrou et écrit le résultat à la suite de cet en-tête.
        return ['id' => $id, 'token' => $token, 'expires' => $expires, 'result' => $result];
    }

    public static function discard(string $id): void
    {
        $directory = self::directory();
        if ($directory !== null && preg_match('/\A[0-9a-f]{32}\z/', $id)) {
            @unlink($directory . '/' . $id);
        }
    }

    private static function cookieName(array $server, string $scheme = ''): string
    {
        $https = ($server['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'
            || (!empty($server['HTTPS']) && $server['HTTPS'] !== 'off') || $scheme === 'https';
        return $https ? '__Secure-JeedomRestore' : 'JeedomRestore';
    }

    public static function cookie(array $tracking, array $server, string $scheme): string
    {
        $name = self::cookieName($server, $scheme);
        return $name . '=' . $tracking['token'] . '; Expires=' . gmdate('D, d M Y H:i:s \G\M\T', $tracking['expires'])
            . '; Path=' . self::PATH . '; HttpOnly; SameSite=Strict'
            . (str_starts_with($name, '__Secure-') ? '; Secure' : '');
    }

    private static function read(mixed $id, mixed $token): ?array
    {
        if (!is_string($id) || !preg_match('/\A[0-9a-f]{32}\z/', $id)
            || !is_string($token) || !preg_match('/\A[0-9a-f]{64}\z/', $token)) {
            return null;
        }
        $directory = self::directory();
        if ($directory === null) {
            return null;
        }
        $result = @fopen($directory . '/' . $id, 'r');
        if ($result === false) {
            return null;
        }
        try {
            $header = fgets($result, 256);
            $metadata = $header === false ? null : json_decode($header, true);
            if (!is_int($metadata['expires'] ?? null) || $metadata['expires'] <= time()
                || !is_string($metadata['hash'] ?? null)
                || !hash_equals($metadata['hash'], hash('sha256', $token))) {
                return null;
            }
            if (!flock($result, LOCK_SH | LOCK_NB)) {
                return ['state' => 'running', 'progress' => self::progress($id)];
            }
            return ['state' => match (fread($result, 8)) {
                'success' => 'success',
                'failed' => 'failed',
                default => 'unknown',
            }];
        } finally {
            fclose($result);
        }
    }

    private static function directory(bool $create = false): ?string
    {
        if (function_exists('posix_geteuid')) {
            // Un lancement en CLI peut utiliser un autre compte que le serveur web.
            $uid = posix_geteuid();
            $installation = substr(hash('sha256', dirname(__DIR__, 4)), 0, 24);
            $path = sys_get_temp_dir() . '/jeedom-restore-' . $installation . '-' . $uid;
            if ($create && !is_dir($path)) {
                @mkdir($path, 0700);
            }
            $stat = @lstat($path);
            if ($stat !== false && ($stat['mode'] & 0177777) === 0040700 && $stat['uid'] === $uid) {
                return $path;
            }
        }
        if ($create) {
            throw new \RuntimeException('Répertoire de suivi de restauration indisponible.');
        }
        return null;
    }

    private static function clean(string $directory): void
    {
        $cutoff = time() - self::LIFETIME;
        foreach (glob($directory . '/*') ?: [] as $path) {
            $modified = @filemtime($path);
            if ($modified !== false && $modified <= $cutoff) {
                @unlink($path);
            }
        }
    }

    private static function progress(string $id): ?int
    {
        $log = @fopen(dirname(__DIR__, 4) . '/log/restore', 'rb');
        if ($log === false) {
            return null;
        }
        try {
            // Le journal est partagé. Pour cette opération, le lanceur marque sa première ligne.
            if (fgets($log, 64) !== "[RESTORE $id]\n") {
                return null;
            }
            $size = fstat($log)['size'];
            fseek($log, max(0, $size - 8192));
            $tail = fread($log, 8192);
            if ($tail !== false && preg_match_all('/^\[PROGRESS\]\[(\d{1,3})\]$/m', $tail, $matches)) {
                return min(99, (int) end($matches[1]));
            }
            return null;
        } finally {
            fclose($log);
        }
    }
}
