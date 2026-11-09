<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Variables du moteur de scénarios (dataStore de type scenario).
 * Droits repris de dataStore.ajax.php : tout utilisateur authentifié.
 */
final class VariablesController
{
    private const TYPE = 'scenario';
    private const GLOBAL_LINK = -1;

    public function list(Request $request, Response $response): Response
    {
        $query = ListQuery::from($request);
        $items = [];
        foreach (\dataStore::byTypeLinkId(self::TYPE) as $dataStore) {
            $item = self::toArray($dataStore);
            if ($query->matches([$item['key'], $item['value']])) {
                $items[] = $item;
            }
        }
        return Json::respond($response, $query->page($items));
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        return Json::respond($response, self::toArray(self::find($args['id'])));
    }

    public function create(Request $request, Response $response): Response
    {
        $body = self::checkBody(Json::body($request), ['key', 'value']);
        if (!is_string($body['key']) || ($key = trim($body['key'])) === '' || mb_strlen($key) > 127) {
            throw ApiError::field(422, 'validation_error', 'key', 'Un texte non vide de 127 caractères au plus est attendu.');
        }
        // preSave() réutiliserait l'id d'une variable du même nom : refuser le doublon.
        if (is_object(\dataStore::byTypeLinkIdKey(self::TYPE, self::GLOBAL_LINK, $key))) {
            throw ApiError::field(409, 'variable_key_taken', 'key', 'Une variable globale porte déjà ce nom.');
        }
        $dataStore = new \dataStore();
        $dataStore->setType(self::TYPE);
        $dataStore->setLink_id(self::GLOBAL_LINK);
        $dataStore->setKey($key);
        self::saveValue($dataStore, $body['value']);
        return Json::respond($response, self::toArray($dataStore), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $dataStore = self::find($args['id']);
        $body = self::checkBody(Json::body($request), ['value']);
        self::saveValue($dataStore, $body['value']);
        return Json::respond($response, self::toArray($dataStore));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        self::find($args['id'])->remove();
        return $response->withStatus(204);
    }

    public function usages(Request $request, Response $response, array $args): Response
    {
        return Json::respond($response, Usages::format((array) self::find($args['id'])->getUsedBy()));
    }

    /** Vérifie que le corps contient exactement les champs attendus. */
    private static function checkBody(array $body, array $fields): array
    {
        if ($unknown = array_diff(array_keys($body), $fields)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        foreach ($fields as $field) {
            if (!array_key_exists($field, $body)) {
                throw ApiError::field(422, 'validation_error', $field, 'Ce champ est obligatoire.');
            }
        }
        if (!is_string($body['value'])) {
            throw ApiError::field(422, 'validation_error', 'value', 'Un texte est attendu.');
        }
        return $body;
    }

    private static function saveValue(\dataStore $dataStore, string $value): void
    {
        try {
            $dataStore->setValue($value);
            $dataStore->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    private static function find(string $id): \dataStore
    {
        $dataStore = ctype_digit($id) ? \dataStore::byId((int) $id) : null;
        if (!is_object($dataStore) || $dataStore->getType() !== self::TYPE) {
            throw new ApiError(404, 'variable_not_found', 'Variable inconnue.');
        }
        return $dataStore;
    }

    private static function toArray(\dataStore $dataStore): array
    {
        $value = (new \ReflectionProperty(\dataStore::class, 'value'))->getValue($dataStore);
        $linkId = (int) $dataStore->getLink_id();
        return [
            'id' => (int) $dataStore->getId(),
            'key' => (string) $dataStore->getKey(),
            'value' => (string) $value,
            'scenarioId' => $linkId > 0 ? $linkId : null,
        ];
    }
}
