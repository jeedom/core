<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../../../class/trustedProxy.class.php';

final class RequestSecurity
{
    public static function withClientIp(Request $request, callable $operation): mixed
    {
        $previous = $GLOBALS['_JEEDOM_REST_CLIENT_IP'] ?? null;
        $GLOBALS['_JEEDOM_REST_CLIENT_IP'] = self::clientIp($request);
        try {
            return $operation();
        } finally {
            if ($previous === null) {
                unset($GLOBALS['_JEEDOM_REST_CLIENT_IP']);
            } else {
                $GLOBALS['_JEEDOM_REST_CLIENT_IP'] = $previous;
            }
        }
    }

    /** Adresse du client résolue par le Core (voir trustedProxy::clientIp). */
    public static function clientIp(Request $request): string
    {
        $server = ['REMOTE_ADDR' => (string) ($request->getServerParams()['REMOTE_ADDR'] ?? '')];
        foreach (['CF-Connecting-IP', 'X-Real-IP', 'X-Forwarded-For', 'Client-IP'] as $header) {
            if ($request->hasHeader($header)) {
                $server['HTTP_' . strtoupper(str_replace('-', '_', $header))] = $request->getHeaderLine($header);
            }
        }
        return \trustedProxy::clientIp($server);
    }

    public static function isHttps(Request $request): bool
    {
        return str_starts_with(self::origin($request), 'https://');
    }

    public static function requireOrigin(Request $request): void
    {
        if ($request->getHeaderLine('Origin') !== self::origin($request)) {
            throw new ApiError(403, 'origin_invalid', 'Origine de la requête non autorisée.');
        }
        self::requireSameOriginFetch($request);
    }

    public static function requireSameOriginFetch(Request $request): void
    {
        $site = $request->getHeaderLine('Sec-Fetch-Site');
        if ($site !== '' && !in_array($site, ['same-origin', 'none'], true)) {
            throw new ApiError(403, 'origin_invalid', 'Origine de la requête non autorisée.');
        }
    }

    private static function origin(Request $request): string
    {
        $server = $request->getServerParams();
        $uri = $request->getUri()->withUserInfo('');
        if (!empty($server['HTTPS']) && $server['HTTPS'] !== 'off') {
            $uri = $uri->withScheme('https');
        }
        if (\trustedProxy::isTrusted((string) ($server['REMOTE_ADDR'] ?? ''))) {
            $scheme = $request->getHeaderLine('X-Forwarded-Proto');
            $host = $request->getHeaderLine('X-Forwarded-Host');
            if (in_array($scheme, ['http', 'https'], true)) {
                $uri = $uri->withScheme($scheme)->withPort($uri->getPort());
            }
            if ($host !== '' && preg_match('/\A(?:[a-z0-9.-]+|\[[a-f0-9:]+\])(?::[0-9]{1,5})?\z/i', $host)) {
                $authority = parse_url($uri->getScheme() . '://' . $host);
                if (isset($authority['host'])) {
                    $uri = $uri->withHost($authority['host'])->withPort($authority['port'] ?? null);
                }
            }
        }
        return $uri->getScheme() . '://' . $uri->getAuthority();
    }

    /**
     * Valide une liste de proxys (IP ou CIDR IPv4/IPv6, séparés par « ; », « , » ou des espaces)
     * et la renvoie normalisée, séparée par « ; ».
     *
     * @throws \InvalidArgumentException sur la première entrée invalide
     */
    public static function normalizeTrustedProxies(string $value): string
    {
        return \trustedProxy::normalizeList($value);
    }
}
