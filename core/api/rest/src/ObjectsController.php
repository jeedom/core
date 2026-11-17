<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Ressource /objects (spec/openapi.yaml). Chaque route appelle les méthodes existantes
 * de jeeObject, comme core/ajax/object.ajax.php, sans dupliquer leur logique.
 * Comme pour les équipements, un objet que l'utilisateur du jeton n'a pas le droit de
 * lire est absent des listes et renvoie 403 quand il est demandé directement.
 */
final class ObjectsController
{

    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $user = $request->getAttribute('user');
        $objects = array_values(array_filter(self::tree(null, QueryParam::bool($query, 'visible') ?? true), static fn ($object) => $object->hasRight('r', $user)));
        $type = (string) ($query['withEqLogicType'] ?? '');
        if ($type !== '') {
            $children = QueryParam::bool($query, 'searchChildren') ?? true;
            $objects = array_values(array_filter($objects, static fn ($object) => count($object->getEqLogic(true, false, $type, null, $children)) > 0));
        }
        $items = array_map(self::toArray(...), $objects);
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $user = $request->getAttribute('user');
        $object = self::findReadable($args['id'], $user);
        $data = self::toArray($object);
        if (in_array('eqLogics', explode(',', (string) ($request->getQueryParams()['include'] ?? '')), true)) {
            $data['eqLogics'] = self::eqLogics($object, $user);
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
        $user = $request->getAttribute('user');
        $children = array_filter((array) self::findReadable($args['id'], $user)->getChild(false), static fn ($child) => is_object($child) && $child->hasRight('r', $user));
        $items = array_map(self::toArray(...), array_values($children));
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function reorder(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        // Même règle que l'ajax setOrder.
        Bulk::reorder(Json::body($request), static function (int $id, int $position): bool {
            $object = \jeeObject::byId($id);
            if (!is_object($object)) {
                return false;
            }
            $object->setPosition($position);
            $object->save();
            return true;
        });
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
        $query = $request->getQueryParams();
        if (array_key_exists('key', $query)) {
            return Json::respond($response, \jeeObject::getGlobalSummary(self::summaryKey($query['key'])));
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
        $object = self::findReadable($args['id'], $request->getAttribute('user'));
        $key = self::summaryKey($query['key'] ?? null);
        $value = $object->getSummary($key, QueryParam::bool($query, 'raw') ?? false);
        return Json::respond($response, is_array($value) ? (object) $value : $value);
    }

    /** Clé `key` : texte non vide (422), clé de résumé définie (404 `summary_not_found`). */
    private static function summaryKey(mixed $key): string
    {
        if (!is_string($key) || $key === '') {
            throw ApiError::field(422, 'validation_error', 'key', 'La clé de résumé est obligatoire.');
        }
        if (!isset(((array) \config::byKey('object:summary'))[$key])) {
            throw new ApiError(404, 'summary_not_found', 'Clé de résumé inconnue.');
        }
        return $key;
    }

    public function summaryConfiguration(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, ['items' => self::summarySettings(self::find($args['id']), (array) \config::byKey('object:summary'))]);
    }

    public function updateSummaryConfiguration(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $object = self::find($args['id']);
        $body = Json::body($request);
        $items = $body['items'] ?? null;
        if (array_keys($body) !== ['items'] || !is_array($items) || !array_is_list($items) || $items === []) {
            throw ApiError::field(422, 'validation_error', 'items', 'Une liste de résumés à modifier est attendue.');
        }
        $definitions = (array) \config::byKey('object:summary');
        $summaries = (array) $object->getConfiguration('summary', []);
        $globalFlags = [];
        foreach ($items as $index => $item) {
            $field = 'items.' . $index;
            $key = $item['key'] ?? null;
            if (!is_string($key) || !isset($definitions[$key]) || array_key_exists($key, $globalFlags)) {
                throw ApiError::field(422, 'validation_error', $field . '.key', 'Type de résumé inconnu ou en double.');
            }
            $global = $item['includeInGlobal'] ?? null;
            $commands = $item['commands'] ?? null;
            if (array_diff(array_keys($item), ['key', 'includeInGlobal', 'commands']) !== [] || !is_bool($global) || !is_array($commands) || !array_is_list($commands)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un booléen et une liste de commandes sont attendus.');
            }
            $existing = [];
            foreach ((array) ($summaries[$key] ?? []) as $entry) {
                if (is_string($entry['cmd'] ?? null)) {
                    $existing[$entry['cmd']][] = $entry;
                }
            }
            $summaries[$key] = [];
            foreach ($commands as $position => $command) {
                $summaries[$key][] = self::summaryCommand($command, $existing, $field . '.commands.' . $position);
            }
            $globalFlags[$key] = $global;
        }
        foreach ($globalFlags as $key => $global) {
            $object->setConfiguration('summary::global::' . $key, (int) $global);
        }
        $object->setConfiguration('summary', $summaries);
        $object->save();
        return Json::respond($response, ['items' => self::summarySettings($object, $definitions)]);
    }

    private static function summarySettings(\jeeObject $object, array $definitions): array
    {
        $summaries = (array) $object->getConfiguration('summary', []);
        $items = [];
        foreach ($definitions as $key => $definition) {
            $commands = [];
            foreach ((array) ($summaries[$key] ?? []) as $entry) {
                $expression = $entry['cmd'] ?? null;
                if (!is_string($expression)) {
                    continue;
                }
                $commands[] = [
                    'expression' => $expression,
                    'label' => \cmd::cmdToHumanReadable($expression),
                    'enabled' => (int) ($entry['enable'] ?? 1) !== 0,
                    'inverted' => (int) ($entry['invert'] ?? 0) === 1,
                ];
            }
            $items[] = [
                'key' => (string) $key,
                'name' => (string) ($definition['name'] ?? $key),
                'icon' => (string) ($definition['icon'] ?? ''),
                'includeInGlobal' => (int) $object->getConfiguration('summary::global::' . $key, 0) === 1,
                'commands' => $commands,
            ];
        }
        return $items;
    }

    private static function summaryCommand(mixed $command, array &$existing, string $field): array
    {
        $expression = $command['expression'] ?? null;
        $enabled = $command['enabled'] ?? null;
        $inverted = $command['inverted'] ?? null;
        if (!is_string($expression) || !is_bool($enabled) || !is_bool($inverted)
            || array_diff(array_keys($command), ['expression', 'enabled', 'inverted']) !== []) {
            throw ApiError::field(422, 'validation_error', $field, 'Une commande de résumé valide est attendue.');
        }
        // Les expressions existantes sont conservées. Les ajouts référencent uniquement des commandes info.
        $entry = [];
        if (!empty($existing[$expression])) {
            $entry = array_shift($existing[$expression]);
        } else {
            $cmd = preg_match('/\A#([1-9][0-9]*)#\z/', $expression, $match) ? \cmd::byId($match[1]) : null;
            if (!is_object($cmd) || $cmd->getType() !== 'info') {
                throw ApiError::field(422, 'validation_error', $field . '.expression', 'Une commande info existante est attendue.');
            }
        }
        return array_replace($entry, ['cmd' => $expression, 'enable' => (int) $enabled, 'invert' => (int) $inverted]);
    }

    public function summaryEqLogics(Request $request, Response $response, array $args): Response
    {
        $query = $request->getQueryParams();
        $user = $request->getAttribute('user');
        $found = self::findReadable($args['id'], $user)->getEqLogicsFromSummary(
            $args['key'],
            QueryParam::bool($query, 'enabled') ?? false,
            QueryParam::bool($query, 'visible') ?? false,
        );
        if ($found === null) {
            throw new ApiError(404, 'summary_not_found', 'Clé de résumé inconnue.');
        }
        $readable = array_filter($found, static function ($raw) use ($user): bool {
            $eqLogic = \eqLogic::byId((int) ($raw['id'] ?? 0));
            return is_object($eqLogic) && $eqLogic->hasRight('r', $user);
        });
        $items = array_map(self::eqLogicRef(...), array_values($readable));
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function createSummaryVirtual(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        \jeeObject::createSummaryToVirtual(self::summaryKey($args['key']));
        return $response->withStatus(204);
    }

    public function uploadImage(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $object = self::find($args['id']);
        self::withUploadedImage($request, static fn (string $path, string $extension) => $object->saveImage($path, $extension));
        return Json::respond($response, self::toArray(\jeeObject::byId($object->getId())));
    }

    public function deleteImage(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::find($args['id'])->removeImage();
        return $response->withStatus(204);
    }

    /**
     * Valide le fichier `file` d'un envoi multipart (mêmes règles que l'ajax `uploadImage`),
     * le copie dans un fichier temporaire et le passe à $store avec son extension.
     */
    public static function withUploadedImage(Request $request, callable $store): void
    {
        $files = $request->getUploadedFiles();
        $file = $files['file'] ?? null;
        if (!$file instanceof UploadedFileInterface) {
            throw ApiError::field(422, 'validation_error', 'file', 'Un fichier image est attendu.');
        }
        if (in_array($file->getError(), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) || $file->getSize() > \utils::IMAGE_MAX_SIZE) {
            throw new ApiError(413, 'image_too_large', 'Le fichier est trop gros (maximum 5 Mo).');
        }
        $invalid = static fn () => ApiError::field(422, 'validation_error', 'file', 'Extension non valide (autorisé : ' . implode(' ', \utils::IMAGE_EXTENSIONS) . ').');
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw $invalid();
        }
        $temporary = \jeedom::getTmpFolder('rest-images') . '/' . bin2hex(random_bytes(16));
        try {
            $file->moveTo($temporary);
            try {
                $extension = \utils::checkUploadedImage((string) $file->getClientFilename(), $temporary);
            } catch (\Exception $e) {
                throw $e->getCode() === 413 ? new ApiError(413, 'image_too_large', 'Le fichier est trop gros (maximum 5 Mo).') : $invalid();
            }
            $store($temporary, $extension);
        } finally {
            @unlink($temporary);
        }
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

    private static function findReadable(string $id, \user $user): \jeeObject
    {
        $object = self::find($id);
        if (!$object->hasRight('r', $user)) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur cet objet.');
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
            $item['cmds'] = CmdsController::forEqLogic($eqLogic, $user);
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
}
