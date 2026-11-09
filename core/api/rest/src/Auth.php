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
        if (!Session::present($request)) {
            throw new ApiError(401, 'unauthorized', 'Jeton absent.');
        }
        // Session de la nouvelle UI : lue puis refermée tout de suite pour ne pas bloquer les autres requêtes.
        Session::open($request);
        $user = Session::user();
        $token = $user !== null ? Session::csrfToken() : null;
        $csrfOk = $user !== null && Session::checkCsrf($request);
        Session::close();
        if ($user === null) {
            throw new ApiError(401, 'session_expired', 'Session expirée, reconnectez-vous.');
        }
        if (!in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'], true) && !$csrfOk) {
            throw new ApiError(403, 'csrf_invalid', 'Jeton CSRF absent ou invalide (en-tête X-CSRF-Token).');
        }
        return $this->handle($request->withAttribute('user', $user)
            ->withAttribute('csrfToken', $token)->withAttribute('csrfValid', $csrfOk), $handler);
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
