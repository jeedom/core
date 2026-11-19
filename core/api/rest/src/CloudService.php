<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

final class CloudService
{
    public const LOGIN = 'Jeedom Cloud Service';
    public const SCOPE = 'cloud-service';
    private const ACCOUNT_OPTION = 'cloudService::technical';

    public static function isLogin(string $login): bool
    {
        return strcasecmp(trim($login), self::LOGIN) === 0;
    }

    public static function isAccount(\user $user): bool
    {
        return self::isLogin($user->getLogin()) && (int) $user->getOptions(self::ACCOUNT_OPTION, 0) === 1;
    }

    public static function allows(Request $request): bool
    {
        return in_array($request->getMethod(), ['GET', 'POST'], true)
            && preg_match('~\A/api/v1/plugins/[A-Za-z0-9_][A-Za-z0-9_-]{0,126}/api/cloud-service\z~', $request->getUri()->getPath()) === 1;
    }

    public static function acceptsUrl(string $url): bool
    {
        $parts = parse_url($url);
        $host = $parts['host'] ?? '';
        return strlen($url) <= 2048 && filter_var($url, FILTER_VALIDATE_URL) !== false
            && strtolower($parts['scheme'] ?? '') === 'https'
            && !isset($parts['user']) && !isset($parts['pass']) && !isset($parts['query']) && !isset($parts['fragment'])
            && str_contains($host, '.') && preg_match('/(?:^|[.])(?:[0-9]+|0x[0-9a-f]*)$/iD', $host) !== 1
            && filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false
            && preg_match('#\A[A-Za-z0-9_./~-]*\z#', $parts['path'] ?? '') === 1;
    }

    public static function requireSession(Request $request): string
    {
        if ($request->getAttribute('cloudServiceSession') !== true) {
            throw new ApiError(403, 'cloud_service_required', 'Une session Jeedom Cloud Service est nécessaire.');
        }
        return $request->getAttribute('restSessionId');
    }

    public static function register(RouteCollectorProxy $routes, string $service, callable $dispatch, ?callable $available = null): void
    {
        $routes->map(['GET', 'POST'], '/cloud-service', static function (Request $request, Response $response) use ($service, $dispatch, $available): Response {
            $id = self::requireSession($request);
            if ($available !== null && !$available()) {
                throw new ApiError(403, 'cloud_service_disabled', 'Le service Cloud est désactivé sur ce plugin.');
            }
            $result = $request->getMethod() === 'GET'
                ? ['service' => $service, 'sessionId' => $id]
                : $dispatch(Json::body($request, 1048576));
            return Json::respond($response->withHeader('Cache-Control', 'no-store'), $result);
        })->setName(self::SCOPE);
    }

    /** Called by a plugin after an administrator confirms the association. */
    public static function associate(string $service, string $url): void
    {
        if (!self::acceptsUrl($url)) {
            throw new \InvalidArgumentException('Un nom de domaine externe en HTTPS est nécessaire.');
        }
        $lock = fopen(\jeedom::getTmpFolder() . '/cloud-service.lock', 'c');
        if ($lock === false) {
            throw new \RuntimeException('Impossible de verrouiller la session Jeedom Cloud Service.');
        }
        try {
            if (!flock($lock, LOCK_EX)) {
                throw new \RuntimeException('Impossible de verrouiller la session Jeedom Cloud Service.');
            }
            $payload = ['service' => $service, 'url' => $url];
            $result = \repo_market::associateCloudService($payload);
            if (($result['state'] ?? '') === 'credentials_required') {
                $account = self::account();
                $session = \session::create($account, false, '', null, null, 'api', self::LOGIN, self::SCOPE, true);
                try {
                    $result = \repo_market::associateCloudService($payload + [
                        'sessionId' => $session['id'], 'refreshToken' => $session['refreshToken'],
                    ]);
                } catch (\Exception $error) {
                    // A definitive refusal leaves no association. A lost reply can follow a successful commit.
                    if (in_array($error->getCode(), [400, 401, 403, 422], true)) {
                        \session::revoke($account, $session['id']);
                    }
                    throw $error;
                }
            }
            $id = $result['sessionId'] ?? null;
            if (($result['state'] ?? '') !== 'ok' || !is_string($id) || preg_match('/^[a-f0-9]{64}$/D', $id) !== 1) {
                throw new \RuntimeException('Impossible de valider l’accès Jeedom Cloud Service.');
            }
            $account ??= self::account();
            $connections = array_column(\session::connections($account), null, 'id');
            if (($connections[$id]['scope'] ?? null) !== self::SCOPE) {
                throw new \RuntimeException('La session Jeedom Cloud Service confirmée est inconnue.');
            }
            foreach ($connections as $connection) {
                if ($connection['id'] !== $id && $connection['scope'] === self::SCOPE) {
                    \session::revoke($account, $connection['id']);
                }
            }
        } finally {
            fclose($lock);
        }
    }

    private static function account(): \user
    {
        $account = \user::byLogin(self::LOGIN);
        if (!$account instanceof \user) {
            $account = (new \user())->setLogin(self::LOGIN)->setPassword(bin2hex(random_bytes(32)))
                ->setProfils('restrict')->setEnable(1)->setOptions('api::mode', 'disable')
                ->setOptions(self::ACCOUNT_OPTION, 1);
            $account->save();
        }
        if (!self::isAccount($account) || !$account->isValidAndEnabled()) {
            throw new \RuntimeException('Le compte Jeedom Cloud Service est indisponible.');
        }
        return $account;
    }
}
