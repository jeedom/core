<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ServerRequestInterface as Request;

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

    public static function clientIp(Request $request): string
    {
        $peer = (string) ($request->getServerParams()['REMOTE_ADDR'] ?? '');
        if (!self::trusted($peer)) {
            return filter_var($peer, FILTER_VALIDATE_IP) ? $peer : '';
        }
        $chain = array_reverse(explode(',', $request->getHeaderLine('X-Forwarded-For')));
        foreach ($chain as $address) {
            $address = trim($address);
            if (!filter_var($address, FILTER_VALIDATE_IP)) {
                return '';
            }
            if (!self::trusted($address)) {
                return $address;
            }
        }
        return '';
    }

    public static function requireHttps(Request $request): void
    {
        if (!self::isHttps($request)) {
            throw new ApiError(403, 'https_required', 'La connexion REST nécessite HTTPS.');
        }
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
        if (self::trusted((string) ($server['REMOTE_ADDR'] ?? ''))) {
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

    private static function trusted(string $peer): bool
    {
        return $peer !== '' && in_array($peer, preg_split('/[\s,]+/', (string) getenv('JEEDOM_TRUSTED_PROXIES'), -1, PREG_SPLIT_NO_EMPTY), true);
    }
}
