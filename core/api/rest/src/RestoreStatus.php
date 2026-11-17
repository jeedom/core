<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Le suivi est lu avant le bootstrap du Core : ce fichier du Core n'en dépend pas.
require_once __DIR__ . '/../../../class/restoreTracking.class.php';

/** Affichage du suivi de restauration (stocké par restoreTracking), sans bootstrap du Core ni base. */
final class RestoreStatus
{
    public const PATH = '/api/v1/backups/restore-status';

    public static function show(Request $request, Response $response): Response
    {
        $response = $response->withHeader('Cache-Control', 'no-store')
            ->withHeader('Cross-Origin-Resource-Policy', 'same-origin')
            ->withHeader('X-Content-Type-Options', 'nosniff');

        $status = null;
        if (!in_array($request->getHeaderLine('Sec-Fetch-Site'), ['same-site', 'cross-site'], true)) {
            $name = self::cookieName($request->getServerParams(), $request->getUri()->getScheme());
            $status = \restoreTracking::status($request->getQueryParams()['operation'] ?? null, $request->getCookieParams()[$name] ?? null);
        }
        return $status === null
            ? Json::error($response, new ApiError(404, 'restore_status_not_found', 'Suivi de restauration introuvable.'))
            : Json::respond($response, $status);
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
}
