<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Chronologie (classe timeline). Lecture filtrée par les droits de l'utilisateur du jeton
 * (timeline::hasRight, comme timeline.ajax.php) ; vidage réservé aux administrateurs.
 * Les événements sont renvoyés structurés : getDisplay() (HTML) n'est pas repris.
 */
final class TimelineController
{
    private const MAX_PAGE = 500;

    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $folder = $query['folder'] ?? 'main';
        if (!is_string($folder) || trim($folder) === '' || mb_strlen($folder) > 127) {
            throw ApiError::field(422, 'validation_error', 'folder', 'Un texte non vide de 127 caractères au plus est attendu.');
        }
        $start = self::integer($query, 'start', 0);
        $offset = self::integer($query, 'offset', 0);
        if ($offset > self::MAX_PAGE) {
            throw ApiError::field(422, 'validation_error', 'offset', 'Un entier de 0 à ' . self::MAX_PAGE . ' est attendu.');
        }

        $user = $request->getAttribute('user');
        $events = [];
        foreach (\timeline::byFolder(trim($folder)) as $event) {
            if ($event->hasRight($user)) {
                $events[] = self::toArray($event);
            }
        }
        return Json::respond($response, [
            'items' => array_slice($events, $start, $offset === 0 ? null : $offset),
            'total' => count($events),
        ]);
    }

    public function folders(Request $request, Response $response): Response
    {
        return Json::respond($response, ['items' => array_values(\timeline::listFolder())]);
    }

    public function empty(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        \timeline::clean(true);
        return $response->withStatus(204);
    }

    public function removeFuture(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        \timeline::removeEventInFutur();
        return $response->withStatus(204);
    }

    private static function integer(array $query, string $field, int $default): int
    {
        if (!isset($query[$field])) {
            return $default;
        }
        if (!is_string($query[$field]) || !ctype_digit($query[$field]) || strlen($query[$field]) > 9) {
            throw ApiError::field(422, 'validation_error', $field, 'Un entier positif ou nul est attendu.');
        }
        return (int) $query[$field];
    }

    private static function toArray(\timeline $event): array
    {
        $options = [];
        if ($event->getType() === 'scenario') {
            $options['trigger'] = self::text($event->getOptions('trigger'));
        } elseif ($event->getSubtype() === 'info') {
            $options['value'] = (string) $event->getOptions('value');
            $options['cmdType'] = (string) $event->getOptions('cmdType');
        }
        $time = strtotime((string) $event->getDatetime());
        return [
            'id' => (int) $event->getId(),
            'type' => $event->getType(),
            'subtype' => $event->getSubtype() === null || $event->getSubtype() === '' ? null : $event->getSubtype(),
            'folder' => (string) $event->getFolder(),
            'link_id' => (int) $event->getLink_id(),
            'datetime' => $time === false ? (string) $event->getDatetime() : date('c', $time),
            'name' => self::text($event->getName()),
            'options' => (object) $options,
        ];
    }

    /** Le core garde dans `name` et `trigger` des balises (<br/>, <strong>, <span class="label">) : texte brut seulement. */
    private static function text(mixed $html): string
    {
        return trim(strip_tags((string) $html));
    }
}
