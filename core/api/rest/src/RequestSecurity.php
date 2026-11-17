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

    /**
     * Adresse du client : en partant du pair, X-Forwarded-For est remonté de droite à gauche
     * tant que l'adresse est un proxy de confiance. Une entrée illisible arrête la remontée
     * sur la dernière adresse valide, pour ne jamais renvoyer une adresse vide derrière un proxy.
     */
    public static function clientIp(Request $request): string
    {
        $proxies = self::trustedProxies();
        $client = self::normalizeIp((string) ($request->getServerParams()['REMOTE_ADDR'] ?? ''));
        if ($client === null) {
            return '';
        }
        if (!self::matches($client, $proxies)) {
            return $client;
        }
        $chain = array_reverse(explode(',', $request->getHeaderLine('X-Forwarded-For')));
        foreach ($chain as $address) {
            $address = self::normalizeIp(trim($address));
            if ($address === null) {
                break;
            }
            $client = $address;
            if (!self::matches($client, $proxies)) {
                break;
            }
        }
        return $client;
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
        $peer = self::normalizeIp((string) ($server['REMOTE_ADDR'] ?? ''));
        if ($peer !== null && self::matches($peer, self::trustedProxies())) {
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
        $entries = [];
        foreach (preg_split('/[\s;,]+/', $value, -1, PREG_SPLIT_NO_EMPTY) as $entry) {
            if (self::parseRange($entry) === null) {
                throw new \InvalidArgumentException($entry);
            }
            $entries[] = $entry;
        }
        return implode(';', array_values(array_unique($entries)));
    }

    /**
     * Plages de confiance : JEEDOM_TRUSTED_PROXIES si la variable est définie, sinon la clé
     * network::trustedProxies. Avant le bootstrap du Core, aucun proxy n'est de confiance.
     * Les entrées invalides sont ignorées.
     *
     * @return list<array{string, int}>
     */
    private static function trustedProxies(): array
    {
        $value = getenv('JEEDOM_TRUSTED_PROXIES');
        if ($value === false || trim($value) === '') {
            $value = class_exists(\config::class, false) ? \config::byKey('network::trustedProxies') : '';
        }
        $ranges = [];
        foreach (preg_split('/[\s;,]+/', is_string($value) ? $value : '', -1, PREG_SPLIT_NO_EMPTY) as $entry) {
            $range = self::parseRange($entry);
            if ($range !== null) {
                $ranges[] = $range;
            }
        }
        return $ranges;
    }

    /** @return array{string, int}|null adresse binaire et longueur du préfixe */
    private static function parseRange(string $entry): ?array
    {
        $parts = explode('/', $entry, 2);
        $address = self::normalizeIp($parts[0]);
        if ($address === null) {
            return null;
        }
        $binary = (string) inet_pton($address);
        $bits = strlen($binary) * 8;
        if (!isset($parts[1])) {
            return [$binary, $bits];
        }
        if (!preg_match('/\A[0-9]{1,3}\z/', $parts[1])) {
            return null;
        }
        $prefix = (int) $parts[1];
        // Un préfixe ::ffff:a.b.c.d/n désigne une plage IPv4.
        if ($bits === 32 && str_contains($parts[0], ':')) {
            $prefix -= 96;
        }
        return $prefix >= 0 && $prefix <= $bits ? [$binary, $prefix] : null;
    }

    /** @param list<array{string, int}> $ranges */
    private static function matches(string $address, array $ranges): bool
    {
        $binary = (string) inet_pton($address);
        foreach ($ranges as [$network, $prefix]) {
            if (strlen($network) !== strlen($binary)) {
                continue;
            }
            $bytes = intdiv($prefix, 8);
            if (substr($binary, 0, $bytes) !== substr($network, 0, $bytes)) {
                continue;
            }
            $rest = $prefix % 8;
            if ($rest === 0) {
                return true;
            }
            $mask = (0xff << (8 - $rest)) & 0xff;
            if ((ord($binary[$bytes]) & $mask) === (ord($network[$bytes]) & $mask)) {
                return true;
            }
        }
        return false;
    }

    /** Adresse IP canonique (IPv4 mappée en IPv6 ramenée en IPv4), ou null si invalide. */
    private static function normalizeIp(string $address): ?string
    {
        if (!filter_var($address, FILTER_VALIDATE_IP)) {
            return null;
        }
        $binary = inet_pton($address);
        if ($binary === false) {
            return null;
        }
        if (strlen($binary) === 16 && str_starts_with($binary, str_repeat("\0", 10) . "\xff\xff")) {
            $binary = substr($binary, 12);
        }
        return (string) inet_ntop($binary);
    }
}
