<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DaemonController
{
    public function provision(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $id = $args['id'];
        if (!preg_match('/\A[a-zA-Z0-9_-]{1,80}\z/', $id)) {
            throw new ApiError(404, 'plugin_not_found', 'Plugin inconnu.');
        }
        try {
            $plugin = \plugin::byId($id);
        } catch (\Exception) {
            throw new ApiError(404, 'plugin_not_found', 'Plugin inconnu.');
        }
        if (!$plugin instanceof \plugin || $plugin->getHasOwnDeamon() != 1) {
            throw new ApiError(409, 'daemon_rest_unavailable', 'Le démon du plugin ne prend pas en charge cet accès.');
        }
        try {
            \plugin::daemonCredentialFile($id, true);
        } catch (\DomainException $error) {
            throw new ApiError(409, 'daemon_rest_unavailable', $error->getMessage());
        }
        return $response->withStatus(204)->withHeader('Cache-Control', 'no-store');
    }

    public function updateCommand(Request $request, Response $response, array $args): Response
    {
        $user = $request->getAttribute('user');
        if (!\plugin::allowsDaemonSession($user, $args['id']) || $request->getAttribute('webSessionId') !== null) {
            throw new ApiError(403, 'scope_forbidden', 'Callback réservé au démon de ce plugin.');
        }
        $cmd = ctype_digit($args['commandId']) ? \cmd::byId($args['commandId']) : null;
        if (!$cmd instanceof \cmd || $cmd->getType() !== 'info' || $cmd->getEqLogic()?->getEqType_name() !== $args['id']) {
            throw new ApiError(404, 'command_not_found', 'Commande du plugin introuvable.');
        }
        $body = Json::body($request, 65536);
        if (!isset($body['value']) || !is_scalar($body['value']) || count($body) !== 1) {
            throw ApiError::field(422, 'validation_error', 'value', 'Une valeur simple est attendue.');
        }
        $cmd->event($body['value']);
        return $response->withStatus(204);
    }

    public function receiveMessages(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'];
        if ($request->getAttribute('daemonPluginId') !== $id) {
            throw new ApiError(403, 'scope_forbidden', 'Envoi réservé au démon de ce plugin.');
        }
        if (!method_exists($id, 'handleDaemonMessages') || !is_callable([$id, 'handleDaemonMessages'])) {
            throw new ApiError(404, 'daemon_handler_not_found', 'Ce plugin ne traite pas de lots de messages.');
        }
        $body = Json::body($request, 5 * 1024 * 1024);
        if (count($body) !== 1 || !is_array($body['messages'] ?? null)) {
            throw ApiError::field(422, 'validation_error', 'messages', 'Un lot de messages est attendu.');
        }
        $id::handleDaemonMessages($body['messages']);
        return $response->withStatus(204)->withHeader('Cache-Control', 'no-store');
    }
}
