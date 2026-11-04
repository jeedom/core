<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressource /objects (spec/openapi.yaml). Chaque route appelle les méthodes existantes
 * de jeeObject, comme core/ajax/object.ajax.php, sans dupliquer leur logique.
 */
final class ObjectsController
{
    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $user = $request->getAttribute('user');
        $objects = array_values(array_filter(self::tree(null, self::flag($query['visible'] ?? null, true)), static fn ($object) => $object->hasRight('r', $user)));
        $type = (string) ($query['withEqLogicType'] ?? '');
        if ($type !== '') {
            $children = self::flag($query['searchChildren'] ?? null, true);
            $objects = array_values(array_filter($objects, static fn ($object) => count($object->getEqLogic(true, false, $type, null, $children)) > 0));
        }
        $items = array_map(self::toArray(...), $objects);
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $object = self::find($args['id']);
        $data = self::toArray($object);
        if (in_array('eqLogics', explode(',', (string) ($request->getQueryParams()['include'] ?? '')), true)) {
            $data['eqLogics'] = self::eqLogics($object, $request->getAttribute('user'));
        }
        return Json::respond($response, $data);
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        if (!array_key_exists('name', $body)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire.');
        }
        $object = new \jeeObject();
        self::apply($object, $body);
        return Json::respond($response, self::toArray(\jeeObject::byId($object->getId())), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $object = self::find($args['id']);
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        self::apply($object, $body);
        return Json::respond($response, self::toArray(\jeeObject::byId($object->getId())));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::find($args['id'])->remove();
        return $response->withStatus(204);
    }

    public function children(Request $request, Response $response, array $args): Response
    {
        $items = array_map(self::toArray(...), self::find($args['id'])->getChild(false));
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function reorder(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $ids = Json::body($request)['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || array_filter($ids, static fn ($id) => !is_int($id)) !== []) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste d\'identifiants entiers est attendue.');
        }
        // Même règle que l'ajax setOrder : positions 1, 2, 3… ; identifiants inconnus ignorés.
        $position = 1;
        foreach ($ids as $id) {
            $object = \jeeObject::byId($id);
            if (is_object($object)) {
                $object->setPosition($position++);
                $object->save();
            }
        }
        return $response->withStatus(204);
    }

    public function orderEqLogicsByUsage(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $object = self::find($args['id']);
        $object->orderEqLogicByUsage();
        return Json::respond($response, self::toArray(\jeeObject::byId($object->getId())));
    }

    public function globalSummary(Request $request, Response $response): Response
    {
        $key = (string) ($request->getQueryParams()['key'] ?? '');
        if ($key !== '') {
            return Json::respond($response, \jeeObject::getGlobalSummary($key));
        }
        $result = [];
        foreach ((array) \config::byKey('object:summary') as $summaryKey => $definition) {
            $result[$summaryKey] = \jeeObject::getGlobalSummary($summaryKey);
        }
        return Json::respond($response, (object) $result);
    }

    public function summary(Request $request, Response $response, array $args): Response
    {
        $query = $request->getQueryParams();
        $value = self::find($args['id'])->getSummary((string) ($query['key'] ?? ''), self::flag($query['raw'] ?? null, false));
        return Json::respond($response, is_array($value) ? (object) $value : $value);
    }

    public function summaryEqLogics(Request $request, Response $response, array $args): Response
    {
        $query = $request->getQueryParams();
        $found = self::find($args['id'])->getEqLogicsFromSummary(
            $args['key'],
            self::flag($query['enabled'] ?? null, false),
            self::flag($query['visible'] ?? null, false),
        );
        if ($found === null) {
            throw new ApiError(404, 'summary_not_found', 'Clé de résumé inconnue.');
        }
        $items = array_map(self::eqLogicRef(...), array_values($found));
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    /**
     * Même parcours que jeeObject::buildTree, sans son filtre de droits : celui-ci lit la
     * session PHP, absente pour un appel par jeton. Les droits sont vérifiés par l'appelant
     * avec l'utilisateur du jeton.
     * @return list<\jeeObject>
     */
    private static function tree(?\jeeObject $parent, bool $visible): array
    {
        $result = [];
        foreach ((array) ($parent === null ? \jeeObject::rootObject(true, $visible) : $parent->getChild($visible)) as $object) {
            $result[] = $object;
            array_push($result, ...self::tree($object, $visible));
        }
        return $result;
    }

    private static function find(string $id): \jeeObject
    {
        $object = ctype_digit($id) ? \jeeObject::byId((int) $id) : null;
        if (!is_object($object)) {
            throw new ApiError(404, 'object_not_found', 'Objet inconnu.');
        }
        return $object;
    }

    /** Valide puis enregistre les champs modifiables, comme l'ajax save via utils::a2o. */
    private static function apply(\jeeObject $object, array $body): void
    {
        $allowed = ['name', 'father_id', 'isVisible', 'position', 'configuration', 'display'];
        if ($unknown = array_diff(array_keys($body), $allowed)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        if (array_key_exists('name', $body)) {
            $name = is_string($body['name']) ? trim($body['name']) : '';
            if ($name === '' || mb_strlen($name) > 127) {
                throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire (127 caractères au plus).');
            }
            $other = \jeeObject::byName($name);
            if (is_object($other) && (int) $other->getId() !== (int) $object->getId()) {
                throw ApiError::field(409, 'object_name_taken', 'name', 'Un objet porte déjà ce nom.');
            }
            $body['name'] = $name;
        }
        if (array_key_exists('father_id', $body) && $body['father_id'] !== null) {
            self::checkParent($object, $body['father_id']);
        }
        foreach (['configuration', 'display'] as $field) {
            if (array_key_exists($field, $body) && (!is_array($body[$field]) || array_is_list($body[$field]) && $body[$field] !== [])) {
                throw ApiError::field(422, 'validation_error', $field, 'Un objet JSON est attendu.');
            }
        }
        if (array_key_exists('isVisible', $body)) {
            if (!is_bool($body['isVisible'])) {
                throw ApiError::field(422, 'validation_error', 'isVisible', 'Un booléen est attendu.');
            }
            $body['isVisible'] = (int) $body['isVisible'];
        }
        if (array_key_exists('position', $body) && $body['position'] !== null && !is_int($body['position'])) {
            throw ApiError::field(422, 'validation_error', 'position', 'Un entier est attendu.');
        }
        \utils::a2o($object, \jeedom::fromHumanReadable($body));
        try {
            $object->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    private static function checkParent(\jeeObject $object, mixed $fatherId): void
    {
        if (!is_int($fatherId) || !is_object(\jeeObject::byId($fatherId))) {
            throw ApiError::field(422, 'validation_error', 'father_id', 'Objet parent inconnu.');
        }
        // Remonte la chaîne des parents : l'objet ne doit jamais y apparaître (pas de cycle).
        for ($id = $fatherId, $depth = 0; $id !== null && $depth < 50; $depth++) {
            if ($object->getId() !== null && (int) $id === (int) $object->getId()) {
                throw ApiError::field(422, 'validation_error', 'father_id', 'L\'objet ne peut pas être son propre parent.');
            }
            $parent = \jeeObject::byId($id);
            $id = is_object($parent) && $parent->getFather_id() !== null && $parent->getFather_id() !== '' ? (int) $parent->getFather_id() : null;
        }
    }

    /** Convertit un objet du core au schéma `Object` (types JSON, références lisibles). */
    private static function toArray(\jeeObject $object): array
    {
        $raw = \jeedom::toHumanReadable(\utils::o2a($object));
        $image = is_array($raw['image'] ?? null) ? $raw['image'] : [];
        return [
            'id' => (int) $raw['id'],
            'name' => (string) $raw['name'],
            'father_id' => isset($raw['father_id']) && $raw['father_id'] !== '' ? (int) $raw['father_id'] : null,
            'isVisible' => (bool) (int) ($raw['isVisible'] ?? 1),
            'position' => isset($raw['position']) && $raw['position'] !== '' ? (int) $raw['position'] : null,
            'configuration' => (object) (is_array($raw['configuration'] ?? null) ? $raw['configuration'] : []),
            'display' => (object) (is_array($raw['display'] ?? null) ? $raw['display'] : []),
            'image' => (object) array_intersect_key($image, ['type' => true, 'sha512' => true]),
        ];
    }

    private static function eqLogics(\jeeObject $object, \user $user): array
    {
        $result = [];
        foreach ($object->getEqLogic() as $eqLogic) {
            if (!$eqLogic->hasRight('r', $user)) {
                continue;
            }
            $item = self::eqLogicRef($eqLogic);
            $item['cmds'] = array_map(static fn ($cmd) => (object) $cmd->exportApi(), $eqLogic->getCmd());
            $result[] = $item;
        }
        return $result;
    }

    private static function eqLogicRef(mixed $eqLogic): array
    {
        $raw = is_object($eqLogic) ? \utils::o2a($eqLogic) : (array) $eqLogic;
        return [
            'id' => (int) $raw['id'],
            'name' => (string) $raw['name'],
            'eqType_name' => (string) ($raw['eqType_name'] ?? ''),
            'isEnable' => (bool) (int) ($raw['isEnable'] ?? 0),
            'isVisible' => (bool) (int) ($raw['isVisible'] ?? 0),
        ];
    }

    private static function flag(mixed $value, bool $default): bool
    {
        return $value === null ? $default : filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default;
    }
}
