<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Point de contrôle unique de l'authentification (décision « middleware commun »).
 *
 * Provisoire : en attendant les jetons nommés et révocables prévus par le cahier des
 * charges, le jeton est le hash existant de l'utilisateur (`user::byHash`), déjà
 * accepté par le JSON-RPC. Le core n'est pas modifié.
 */
final class Auth implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!preg_match('/^Bearer\s+(\S+)$/', $request->getHeaderLine('Authorization'), $match)) {
            throw new ApiError(401, 'unauthorized', 'Jeton absent.');
        }
        $user = \user::byHash($match[1]);
        if (!is_object($user) || (int) $user->getEnable() !== 1) {
            throw new ApiError(401, 'unauthorized', 'Jeton invalide ou révoqué.');
        }
        // Les contrôles de droits existants du core (hasRight) lisent cet utilisateur.
        $GLOBALS['_USER_GLOBAL'] = $user;
        try {
            return $handler->handle($request->withAttribute('user', $user));
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
