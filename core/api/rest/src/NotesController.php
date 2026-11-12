<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Notes libres (classe note). Droits repris de note.ajax.php : administrateurs uniquement.
 */
final class NotesController
{
    private const FIELDS = ['name', 'text'];

    public function list(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $query = ListQuery::from($request);
        $items = [];
        foreach (\note::all() as $note) {
            $item = self::toArray($note);
            if ($query->matches([$item['name'], $item['text']])) {
                $items[] = $item;
            }
        }
        return Json::respond($response, $query->page($items));
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, self::toArray(self::find($args['id'])));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = self::checkBody(Json::body($request));
        if (!array_key_exists('name', $body)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Ce champ est obligatoire.');
        }
        $note = new \note();
        $note->setText('');
        self::apply($note, $body);
        return Json::respond($response, self::toArray($note), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $note = self::find($args['id']);
        $body = self::checkBody(Json::body($request));
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Au moins un champ est attendu.');
        }
        self::apply($note, $body);
        return Json::respond($response, self::toArray($note));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::find($args['id'])->remove();
        return $response->withStatus(204);
    }

    private static function checkBody(array $body): array
    {
        if ($unknown = array_diff(array_keys($body), self::FIELDS)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        foreach ($body as $field => $value) {
            if (!is_string($value)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un texte est attendu.');
            }
        }
        if (isset($body['name']) && (trim($body['name']) === '' || mb_strlen(trim($body['name'])) > 127)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Un texte non vide de 127 caractères au plus est attendu.');
        }
        return $body;
    }

    private static function apply(\note $note, array $body): void
    {
        if (isset($body['name'])) {
            $note->setName($body['name']);
        }
        if (isset($body['text'])) {
            $note->setText($body['text']);
        }
        try {
            $note->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    private static function find(string $id): \note
    {
        $note = ctype_digit($id) ? \note::byId((int) $id) : null;
        if (!is_object($note)) {
            throw new ApiError(404, 'note_not_found', 'Note inconnue.');
        }
        return $note;
    }

    private static function toArray(\note $note): array
    {
        return [
            'id' => (int) $note->getId(),
            'name' => (string) $note->getName(),
            'text' => (string) $note->getText(),
        ];
    }
}
