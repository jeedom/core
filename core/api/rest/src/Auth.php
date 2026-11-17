<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Auth implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        [$transport, $token] = self::access($request);
        $identity = \session::resolve($token, $transport);
        if ($identity === null) {
            throw new ApiError(401, $transport === 'web' ? 'session_refresh_required' : 'invalid_access_token', 'Un accès REST valide est nécessaire.');
        }
        $session = $identity['session'];
        $user = $identity['user'];
        if (!self::accountAllowed($user, $session['mode'], $session['scope'])) {
            throw new ApiError(403, 'session_access_forbidden', 'Accès interdit depuis cette connexion.');
        }
        $csrf = $transport === 'web' ? $session['csrf_token'] : null;
        $csrfOk = $csrf !== null && hash_equals($csrf, $request->getHeaderLine('X-CSRF-Token'));
        if ($transport === 'web' && !in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'], true) && !$csrfOk) {
            throw new ApiError(403, 'csrf_invalid', 'Jeton CSRF absent ou invalide.');
        }
        self::checkScope($request, $session);
        \session::touch($session);

        $GLOBALS['_USER_GLOBAL'] = $user;
        try {
            return $handler->handle($request->withAttribute('user', $user)->withAttribute('restSessionId', $session['id'])
                ->withAttribute('daemonPluginId', $session['mode'] === 'daemon' ? $session['scope'] : null)
                ->withAttribute('webSessionId', $transport === 'web' ? $session['id'] : null)
                ->withAttribute('webSessionRemembered', $transport === 'web' && (bool) $session['remembered'])
                ->withAttribute('csrfToken', $csrf)->withAttribute('csrfValid', $csrfOk)
                ->withAttribute('sessionAuthorized', static fn (): bool => RequestSecurity::withClientIp($request, static function () use ($token, $transport, $user): bool {
                    $current = self::identity($token, $transport)['user'] ?? null;
                    return $current !== null && $current->getProfils() === $user->getProfils() && $current->getRights() === $user->getRights();
                })));
        } finally {
            unset($GLOBALS['_USER_GLOBAL']);
        }
    }

    public static function access(ServerRequestInterface $request): array
    {
        $cookie = SessionController::cookie($request);
        if (!$request->hasHeader('Authorization')) {
            return ['web', $cookie];
        }
        if ($cookie !== null || SessionController::refreshCookie($request) !== null) {
            throw new ApiError(403, 'mixed_credentials', 'Les justificatifs Web et API doivent être utilisés séparément.');
        }
        if (!preg_match('/\ABearer ([a-f0-9]{64})\z/i', $request->getHeaderLine('Authorization'), $match)) {
            throw new ApiError(401, 'invalid_access_token', 'Jeton d’accès invalide.');
        }
        return ['api', $match[1]];
    }

    public static function identity(?string $token, string $transport, bool $refresh = false): ?array
    {
        $identity = \session::resolve($token, $transport, $refresh);
        return $identity !== null && self::accountAllowed($identity['user'], $identity['session']['mode'], $identity['session']['scope'])
            ? $identity : null;
    }

    public static function accountAllowed(\user $user, string $mode, string $scope = ''): bool
    {
        if (\user::isBan()) {
            return false;
        }
        if ($mode === 'daemon') {
            return \plugin::allowsDaemonSession($user, $scope);
        }
        if ((int) $user->getOptions('localOnly', 0) === 1 && \network::getUserLocation() !== 'internal') {
            return false;
        }
        return $mode !== 'api' || ($user->getLogin() !== \supportAccess::LOGIN
            && \jeedom::apiModeResult($user->getOptions('api::mode', 'enable')));
    }

    private static function checkScope(ServerRequestInterface $request, array $session): void
    {
        $path = $request->getUri()->getPath();
        $method = $request->getMethod();
        if ($session['mode'] === 'daemon') {
            $base = '/api/v1/plugins/' . $session['scope'];
            if ($method === 'POST' && ($path === $base . '/daemon/messages'
                || preg_match('~\A' . preg_quote($base, '~') . '/daemon/commands/[1-9][0-9]*\z~', $path))) {
                return;
            }
        } elseif ($session['scope'] === '') {
            return;
        } elseif ($method === 'GET' && preg_match('~\A/api/v1/(?:objects(?:/[1-9][0-9]*)?|eqLogics(?:/[1-9][0-9]*)?|cmds(?:/[1-9][0-9]*(?:/(?:value|history|statistics))?)?)\z~', $path)
            || $session['scope'] === 'control' && $method === 'POST' && preg_match('~\A/api/v1/cmds/[1-9][0-9]*/exec\z~', $path)) {
            return;
        }
        throw new ApiError(403, 'scope_forbidden', 'Action hors du périmètre de cet accès.');
    }

    public static function requireAdmin(ServerRequestInterface $request): void
    {
        if ($request->getAttribute('user')?->getProfils() !== 'admin') {
            throw new ApiError(403, 'forbidden', 'Action réservée aux administrateurs.');
        }
    }

    /**
     * Administrateur hors session de support : comptes, menus et tickets sont des
     * effets qui survivraient à la fermeture de l'accès support temporaire.
     */
    public static function requireAccountAdmin(ServerRequestInterface $request): void
    {
        self::requireAdmin($request);
        if ($request->getAttribute('user')->getLogin() === \supportAccess::LOGIN) {
            throw new ApiError(403, 'support_session_forbidden', 'Action indisponible pendant une session de support.');
        }
    }
}
