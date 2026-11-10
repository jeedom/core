<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/** Adresses utilisées par Jeedom et état du tunnel d'accès distant. */
final class NetworkAccessController
{
    public function get(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return self::respond($response);
    }

    public function update(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request, 16 * 1024, true);
        if ($body === [] || array_diff(array_keys($body), ['internal', 'external']) !== []) {
            throw new ApiError(422, 'validation_error', 'Indiquez les accès interne ou externe à modifier.');
        }

        $settings = [];
        if (array_key_exists('internal', $body)) {
            $settings += self::internalSettings($body['internal']);
        }
        $previousMode = self::externalMode();
        $externalMode = $previousMode;
        if (array_key_exists('external', $body)) {
            $externalMode = self::mode($body['external'], 'external', ['none', 'custom', 'dns']);
            if ($externalMode === 'custom') {
                $settings += self::urlSettings($body['external']->url ?? null, 'external');
            } elseif ($externalMode === 'none') {
                $settings['externalAddr'] = '';
            }
            if ($externalMode !== $previousMode && ($externalMode === 'dns' || $previousMode === 'dns')
                && \config::byKey('network::dnsComplement') === '') {
                // Preserve legacy DNS paths before writing a new custom URL.
                $path = $previousMode === 'dns'
                    ? \config::byKey('externalComplement')
                    : ($settings['internalComplement'] ?? \config::byKey('internalComplement'));
                $settings['network::dnsComplement'] = '/' . trim((string) $path, '/');
            }
        }

        $dnsChanged = ($externalMode === 'dns') !== ($previousMode === 'dns');
        if ($dnsChanged && $externalMode === 'dns' && !self::dnsAvailable()) {
            throw ApiError::field(422, 'dns_unavailable', 'external.mode', 'Le DNS Jeedom n’est pas disponible pour cette installation.');
        }

        if ($settings !== []) {
            \DB::beginTransaction();
            try {
                foreach ($settings as $key => $value) {
                    \config::save($key, $value);
                }
                \DB::commit();
            } catch (\Throwable $error) {
                \DB::rollBack();
                throw $error;
            }
        }

        if ($dnsChanged) {
            try {
                // The existing config hook starts or stops the configured transport.
                \config::save('market::allowDNS', (int) ($externalMode === 'dns'));
            } catch (\Exception $error) {
                \log::add('network', 'error', $error->getMessage());
                throw new ApiError(502, 'dns_operation_failed', 'Les réglages ont été enregistrés. Le tunnel DNS Jeedom n’a pas pu être mis à jour.');
            }
            if ($externalMode === 'dns' && \config::byKey('jeedom::url') === '') {
                try {
                    \repo_market::synchronize();
                } catch (\Exception $error) {
                    \log::add('network', 'warning', $error->getMessage());
                }
            }
        }
        return self::respond($response);
    }

    public function restartDns(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        if (self::externalMode() !== 'dns' || !self::dnsAvailable()) {
            throw new ApiError(409, 'dns_unavailable', 'Sélectionnez le DNS Jeedom et enregistrez la configuration avant de relancer le tunnel.');
        }
        try {
            \network::dns_restart();
        } catch (\Exception $error) {
            \log::add('network', 'error', $error->getMessage());
            throw new ApiError(502, 'dns_operation_failed', 'Le tunnel DNS Jeedom n’a pas pu être relancé.');
        }
        return self::respond($response);
    }

    private static function respond(Response $response): Response
    {
        $internalUrl = \network::getNetworkAccess('internal');
        $internalMode = \config::byKey('network::disableInternalAuto') == 1 ? 'custom' : 'auto';
        $externalMode = self::externalMode();
        $available = self::dnsAvailable();
        $externalUrl = match ($externalMode) {
            'custom' => \network::getNetworkAccess('external'),
            'dns' => $available && \config::byKey('jeedom::url') !== '' ? \network::getNetworkAccess('external') : null,
            default => null,
        };

        return Json::respond($response->withHeader('Cache-Control', 'private, no-store'), [
            'internal' => ['mode' => $internalMode, 'url' => \config::byKey('internalAddr') === '' ? null : $internalUrl],
            'external' => ['mode' => $externalMode, 'url' => $externalUrl],
            'dns' => ['available' => $available, 'state' => self::dnsState($externalMode, $available)],
        ]);
    }

    private static function externalMode(): string
    {
        if (\config::byKey('market::allowDNS') == 1) {
            return 'dns';
        }
        return \config::byKey('externalAddr') === '' ? 'none' : 'custom';
    }

    private static function dnsAvailable(): bool
    {
        return \config::byKey('dns::token') !== '' && \config::byKey('network::disableMangement') != 1
            && (\config::byKey('dns::mode') !== 'frp' || \frp::servers() !== []);
    }

    private static function dnsState(string $mode, bool $available): string
    {
        if ($mode !== 'dns') {
            return 'disabled';
        }
        if (!$available) {
            return 'unavailable';
        }
        try {
            if (\config::byKey('dns::mode') !== 'frp') {
                $vpn = \eqLogic::byLogicalId('dnsjeedom', 'openvpn');
                return is_callable([$vpn, 'getState']) && $vpn->getState() ? 'connected' : 'stopped';
            }
            $status = \frp::status();
            if (!empty($status['running'])) {
                return 'connected';
            }
            return match ($status['state'] ?? '') {
                'installing', 'starting', 'connecting', 'reconnecting', 'running' => 'connecting',
                'blocked', 'error' => 'error',
                default => 'stopped',
            };
        } catch (\Exception $error) {
            return 'error';
        }
    }

    private static function mode(mixed $settings, string $field, array $modes): string
    {
        if (!$settings instanceof \stdClass || !in_array($settings->mode ?? null, $modes, true)
            || array_diff(array_keys((array) $settings), $settings->mode === 'custom' ? ['mode', 'url'] : ['mode']) !== []) {
            throw ApiError::field(422, 'validation_error', $field, 'Mode d’accès ou paramètres invalides.');
        }
        return $settings->mode;
    }

    private static function internalSettings(mixed $settings): array
    {
        if (self::mode($settings, 'internal', ['auto', 'custom']) === 'custom') {
            return self::urlSettings($settings->url ?? null, 'internal') + ['network::disableInternalAuto' => 1];
        }
        // Force a fresh detection when switching back from a manual host.
        return \config::byKey('network::disableInternalAuto') == 1
            ? ['network::disableInternalAuto' => 0, 'internalAddr' => '']
            : [];
    }

    private static function urlSettings(mixed $value, string $side): array
    {
        $url = is_string($value) ? trim($value) : '';
        $parts = strlen($url) <= 2048 && !str_contains($url, '\\') && filter_var($url, FILTER_VALIDATE_URL)
            ? parse_url($url)
            : false;
        $scheme = strtolower($parts['scheme'] ?? '');
        if (!in_array($scheme, ['http', 'https'], true) || empty($parts['host']) || ($parts['port'] ?? 1) < 1
            || array_intersect(['user', 'pass', 'query', 'fragment'], array_keys($parts)) !== []) {
            throw ApiError::field(422, 'validation_error', $side . '.url', 'Saisissez une URL HTTP ou HTTPS complète, avec un port et un chemin si nécessaire.');
        }
        return [
            $side . 'Protocol' => $scheme . '://',
            $side . 'Addr' => $parts['host'],
            $side . 'Port' => $parts['port'] ?? ($scheme === 'https' ? 443 : 80),
            $side . 'Complement' => trim($parts['path'] ?? '', '/'),
        ];
    }
}
