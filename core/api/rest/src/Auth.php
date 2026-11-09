<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Point de contrôle unique de l'authentification (décision « middleware commun ») :
 * jeton `Bearer`, ou session de la nouvelle UI avec jeton CSRF sur les écritures.
 *
 * Provisoire : en attendant les jetons nommés et révocables prévus par le cahier des
 * charges, le jeton est le hash existant de l'utilisateur (`user::byHash`), déjà
 * accepté par le JSON-RPC. Le core n'est pas modifié.
 */
final class Auth implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (preg_match('/^Bearer\s+(\S+)$/', $request->getHeaderLine('Authorization'), $match)) {
            $user = \user::byHash($match[1]);
            if (!is_object($user) || (int) $user->getEnable() !== 1) {
                throw new ApiError(401, 'unauthorized', 'Jeton invalide ou révoqué.');
            }
            return $this->handle($request->withAttribute('user', $user)->withAttribute('csrfToken', null), $handler);
        }
        $cookie = SessionController::cookie($request);
        if ($cookie === null) {
            throw new ApiError(401, 'unauthorized', 'Jeton absent.');
        }
        $browser = self::browserSession($cookie);
        if ($browser === null) {
            throw new ApiError(401, 'session_expired', 'Session expirée, reconnectez-vous.');
        }
        $session = $browser['session'];
        $token = $session['csrf_token'];
        $csrfOk = hash_equals($token, $request->getHeaderLine('X-CSRF-Token'));
        if (!in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'], true) && !$csrfOk) {
            throw new ApiError(403, 'csrf_invalid', 'Jeton CSRF absent ou invalide (en-tête X-CSRF-Token).');
        }
        \session::touch($session);
        return $this->handle($request->withAttribute('user', $browser['user'])->withAttribute('webSessionId', $session['id'])
            ->withAttribute('webSessionRemembered', (bool) $session['remembered'])
            ->withAttribute('csrfToken', $token)->withAttribute('csrfValid', $csrfOk), $handler);
    }

    public static function browserSession(string $cookie): ?array
    {
        $browser = \session::resolve($cookie);
        if ($browser !== null && (int) $browser['user']->getOptions('localOnly', 0) === 1 && \network::getUserLocation() !== 'internal') {
            return null;
        }
        return $browser;
    }

    private function handle(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Les contrôles de droits existants du core (hasRight) lisent cet utilisateur.
        $GLOBALS['_USER_GLOBAL'] = $request->getAttribute('user');
        try {
            return $handler->handle($request);
        } finally {
            unset($GLOBALS['_USER_GLOBAL']);
        }
    }

    public static function requireAdmin(ServerRequestInterface $request): void
    {
        if ($request->getAttribute('user')?->getProfils() !== 'admin') {
            throw new ApiError(403, 'forbidden', 'Action réservée aux administrateurs.');
        }
    }
}
