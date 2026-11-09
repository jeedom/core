<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Centre de notifications du core, d'après core/ajax/message.ajax.php :
 * lecture pour tout utilisateur connecté, suppression réservée aux administrateurs.
 */
final class MessagesController
{
    public function list(Request $request, Response $response): Response
    {
        $plugin = self::plugin($request);
        $query = ListQuery::from($request);
        $messages = $plugin === '' ? \message::all() : \message::byPlugin($plugin);
        $items = [];
        foreach ($messages as $message) {
            if ($query->matches([$message->getPlugin(), $message->getLogicalId(), $message->getMessage()])) {
                $items[] = self::toArray($message);
            }
        }
        return Json::respond($response, $query->page($items));
    }

    public function count(Request $request, Response $response): Response
    {
        return Json::respond($response, ['count' => (int) \message::nbMessage()]);
    }

    public function clear(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        \message::removeAll(self::plugin($request));
        return $response->withStatus(204);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $message = ctype_digit($args['id']) ? \message::byId((int) $args['id']) : null;
        if (!is_object($message)) {
            throw new ApiError(404, 'message_not_found', 'Message inconnu.');
        }
        $message->remove();
        return $response->withStatus(204);
    }

    private static function plugin(Request $request): string
    {
        $plugin = $request->getQueryParams()['plugin'] ?? '';
        if (!is_string($plugin) || mb_strlen($plugin) > 127) {
            throw ApiError::field(422, 'validation_error', 'plugin', 'Un texte de 127 caractères au plus est attendu.');
        }
        return $plugin;
    }

    private static function toArray(\message $message): array
    {
        $data = $message->toArray();
        return [
            'id' => (int) $data['id'],
            'date' => (string) $data['date'],
            'plugin' => (string) $data['plugin'],
            'logicalId' => (string) $data['logicalId'],
            'message' => MessageHtml::sanitize((string) $data['message']),
            'action' => MessageHtml::sanitize((string) $data['action']),
            'occurrences' => max(1, (int) ($data['occurrences'] ?? 1)),
        ];
    }
}
