<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Route GET /events (core/api/rest/openapi.yaml) : flux SSE des événements du core
 * (table `event`, alimentée par event::add), filtré selon les droits de l'utilisateur.
 * Remplace le long polling de core/ajax/event.ajax.php pour la nouvelle UI.
 */
final class EventsController
{
    /** Types exposés : ceux émis par le core, plus `resync` propre à l'API. */
    public const TYPES = [
        'cmd::update', 'eqLogic::update', 'scenario::update', 'jeeObject::summary::update',
        'message::refreshMessageNumber', 'update::refreshUpdateNumber', 'notify',
        'jeedom::alert', 'jeedom::alertPopup', 'jeedom::gotoplan', 'jeedom::coloredIcons',
        'checkThemechange', 'changeTheme', 'resync',
    ];
    public const MAX_STREAMS_PER_USER = 3;
    private const FILTERS = ['objectId', 'eqLogicId', 'cmdId', 'scenarioId'];

    public function stream(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $user = $request->getAttribute('user');

        // Un onglet peut avoir gardé l'ancienne session après une reconnexion dans un autre onglet.
        if ($request->hasHeader('X-CSRF-Token') && $request->getAttribute('csrfValid') === false) {
            throw new ApiError(401, 'session_expired', 'Session expirée, reconnectez-vous.');
        }

        $types = self::list($query, 'types');
        foreach ($types as $type) {
            if (!in_array($type, self::TYPES, true)) {
                throw ApiError::field(422, 'validation_error', 'types', 'Type d\'événement inconnu : ' . $type . '.');
            }
        }
        $filters = [];
        foreach (self::FILTERS as $name) {
            $ids = self::list($query, $name);
            foreach ($ids as $id) {
                if (!ctype_digit($id) || (int) $id < 1) {
                    throw ApiError::field(422, 'validation_error', $name, 'Une liste d\'entiers positifs est attendue.');
                }
            }
            $filters[$name] = array_map('intval', $ids);
        }
        $maxDuration = EventStream::MAX_DURATION;
        if (isset($query['maxDuration'])) {
            $value = (string) $query['maxDuration'];
            if (!ctype_digit($value) || (int) $value < 5 || (int) $value > EventStream::MAX_DURATION) {
                throw ApiError::field(422, 'validation_error', 'maxDuration', sprintf('Un entier entre 5 et %d est attendu.', EventStream::MAX_DURATION));
            }
            $maxDuration = (int) $value;
        }
        // Last-Event-ID (reconnexion d'EventSource) l'emporte sur `since` (première connexion).
        $cursor = $request->getHeaderLine('Last-Event-ID');
        $field = 'Last-Event-ID';
        if ($cursor === '') {
            $cursor = (string) ($query['since'] ?? '');
            $field = 'since';
        }
        if ($cursor !== '' && !preg_match('/^\d+(\.\d+)?$/', $cursor)) {
            throw ApiError::field(422, 'validation_error', $field, 'Curseur invalide.');
        }

        $slot = StreamSlots::acquire($user, $maxDuration);
        if ($slot === null) {
            return Json::error($response, new ApiError(429, 'too_many_streams', 'Trop de flux d\'événements ouverts pour cet utilisateur.'))
                ->withHeader('Retry-After', '10');
        }
        $filter = new EventFilter($user, $types, $filters);
        $cookie = $request->getAttribute('webSessionId') !== null ? SessionController::cookie($request) : null;
        $authorized = $cookie === null ? null : static fn (): bool => Auth::browserSession($cookie) !== null;
        $stream = new EventStream($filter, $cursor === '' ? null : (float) $cursor, $maxDuration, $slot, $user, $authorized);
        return $response
            ->withBody($stream)
            ->withHeader('Content-Type', 'text/event-stream; charset=utf-8')
            ->withHeader('Cache-Control', 'no-cache');
    }

    /** @return list<string> liste `a,b,c` d'un paramètre de requête, vide s'il est absent */
    private static function list(array $query, string $name): array
    {
        if (!isset($query[$name]) || $query[$name] === '') {
            return [];
        }
        if (!is_string($query[$name])) {
            throw ApiError::field(422, 'validation_error', $name, 'Une liste séparée par des virgules est attendue.');
        }
        return array_map('trim', explode(',', $query[$name]));
    }
}
