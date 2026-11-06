<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressource /scenarios (core/api/rest/openapi.yaml). Chaque route appelle les méthodes
 * existantes de scenario, comme core/ajax/scenario.ajax.php. Droits vérifiés ici avec
 * l'utilisateur du jeton (scenario::hasRight) : lecture filtrée, lancement avec le droit
 * d'exécution, écriture et contenu (blocs `code` en PHP) réservés aux administrateurs.
 */
final class ScenariosController
{
    private const FIELDS = ['name', 'group', 'isActive', 'isVisible', 'mode', 'schedule', 'trigger', 'timeout', 'object_id', 'order', 'description', 'display', 'configuration', 'elements'];
    private const BULK_CHANGES = ['isActive', 'isVisible', 'group'];
    private const MODES = ['provoke', 'schedule', 'all'];
    private const STATES = ['stop', 'in progress', 'starting', 'error'];
    private const MAX_BULK = 1000;
    /** Mots qui rendent un scénario dépendant de l'heure, comme l'ajax save. */
    private const TIME_KEYWORDS = ['#time#', '#seconde#', '#heure#', '#minute#', '#jour#', '#mois#', '#annee#', '#timestamp#', '#date#', '#semaine#', '#sjour#', '#njour#', '#smois#'];

    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $user = $request->getAttribute('user');
        $search = ListQuery::from($request);
        $objectId = null;
        if (isset($query['objectId'])) {
            if (!ctype_digit((string) $query['objectId']) || (int) $query['objectId'] < 1) {
                throw ApiError::field(422, 'validation_error', 'objectId', 'Un entier positif est attendu.');
            }
            $objectId = (int) $query['objectId'];
        }
        $group = $query['group'] ?? null;
        $active = isset($query['active']) ? filter_var($query['active'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : null;
        $visible = isset($query['visible']) ? filter_var($query['visible'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : null;
        $scenarios = array_values(array_filter(self::readable($user), static fn ($scenario) =>
            ($group === null || (string) $scenario->getGroup() === (string) $group)
            && ($objectId === null || (int) $scenario->getObject_id() === $objectId)
            && ($active === null || (bool) (int) $scenario->getIsActive() === $active)
            && ($visible === null || (bool) (int) $scenario->getIsVisible() === $visible)
            && $search->matches(self::searchFields($scenario))));
        usort($scenarios, static fn ($a, $b) => [(string) $a->getGroup(), (int) $a->getOrder(), $a->getName(), (int) $a->getId()]
            <=> [(string) $b->getGroup(), (int) $b->getOrder(), $b->getName(), (int) $b->getId()]);
        $page = $search->page($scenarios);
        $page['items'] = array_map(static fn ($scenario) => self::toArray($scenario, false), $page['items']);
        return Json::respond($response, $page);
    }

    public function groups(Request $request, Response $response): Response
    {
        $groups = [];
        foreach (self::readable($request->getAttribute('user')) as $scenario) {
            if ((string) $scenario->getGroup() !== '') {
                $groups[(string) $scenario->getGroup()] = true;
            }
        }
        $groups = array_keys($groups);
        sort($groups, SORT_NATURAL | SORT_FLAG_CASE);
        return Json::respond($response, ['items' => $groups]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        $withElements = in_array('elements', explode(',', (string) ($request->getQueryParams()['include'] ?? '')), true);
        if ($withElements) {
            Auth::requireAdmin($request);
        }
        return Json::respond($response, self::toArray($scenario, $withElements));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        if (!array_key_exists('name', $body)) {
            throw ApiError::field(422, 'validation_error', 'name', 'Ce champ est obligatoire.');
        }
        $scenario = new \scenario();
        $scenario->setIsActive(1);
        $scenario->setIsVisible(1);
        $scenario->setMode('provoke');
        self::apply($scenario, $body);
        return Json::respond($response, self::toArray(self::reload($scenario), true), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'w');
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        // Décision du 06/11 : un scénario en cours est arrêté, puis mis à jour.
        if ($scenario->running()) {
            $scenario->stop();
        }
        self::apply($scenario, $body);
        return Json::respond($response, self::toArray(self::reload($scenario), true));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::find($args['id'], $request->getAttribute('user'), 'w')->remove();
        return $response->withStatus(204);
    }

    public function updateMany(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        $ids = self::ids($body);
        $changes = $body['changes'] ?? null;
        if (!is_array($changes) || $changes === [] || array_is_list($changes)) {
            throw ApiError::field(422, 'validation_error', 'changes', 'Un objet JSON avec au moins un changement est attendu.');
        }
        if ($unknown = array_diff(array_keys($changes), self::BULK_CHANGES)) {
            throw ApiError::field(422, 'validation_error', 'changes.' . reset($unknown), 'Seuls isActive, isVisible et group peuvent être modifiés en groupe.');
        }
        $user = $request->getAttribute('user');
        return Json::respond($response, self::each($ids, static function (int $id) use ($user, $changes): void {
            self::apply(self::find((string) $id, $user, 'w'), $changes);
        }));
    }

    public function deleteMany(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $ids = self::ids(Json::body($request));
        $user = $request->getAttribute('user');
        return Json::respond($response, self::each($ids, static function (int $id) use ($user): void {
            self::find((string) $id, $user, 'w')->remove();
        }));
    }

    public function reorder(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $ids = Json::body($request)['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || array_filter($ids, static fn ($id) => !is_int($id)) !== []) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste d\'identifiants entiers est attendue.');
        }
        $position = 1;
        foreach ($ids as $id) {
            $scenario = \scenario::byId($id);
            if (is_object($scenario)) {
                $scenario->setOrder($position++);
                $scenario->save(true);
            }
        }
        return $response->withStatus(204);
    }

    public function copy(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        $name = Json::body($request)['name'] ?? null;
        $name = is_string($name) ? trim($name) : '';
        if ($name === '' || mb_strlen($name) > 127) {
            throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire (127 caractères au plus).');
        }
        try {
            $copy = $scenario->copy($name);
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
        return Json::respond($response, self::toArray(self::reload($copy), true), 201);
    }

    public function start(Request $request, Response $response, array $args): Response
    {
        $user = $request->getAttribute('user');
        $scenario = self::find($args['id'], $user, 'x');
        $raw = (string) $request->getBody();
        $body = $raw === '' ? [] : Json::body($request);
        if ($unknown = array_diff(array_keys($body), ['tags'])) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu.');
        }
        $tags = $body['tags'] ?? [];
        if (!is_array($tags) || (array_is_list($tags) && $tags !== []) || array_filter($tags, static fn ($value) => !is_string($value)) !== []) {
            throw ApiError::field(422, 'validation_error', 'tags', 'Un objet JSON de textes est attendu.');
        }
        if ((int) $scenario->getIsActive() !== 1) {
            throw new ApiError(409, 'scenario_disabled', 'Le scénario est désactivé.');
        }
        // Sinon scenario::launch() ne lance rien, sans le dire.
        if ((int) \config::byKey('enableScenario') !== 1) {
            throw new ApiError(409, 'scenario_engine_disabled', 'Le moteur de scénarios est désactivé dans la configuration.');
        }
        if ($scenario->getState() === 'in progress' && $scenario->running() && (int) $scenario->getConfiguration('allowMultiInstance', 0) !== 1) {
            throw new ApiError(409, 'scenario_running', 'Le scénario est déjà en cours.');
        }
        foreach ($tags as $name => $value) {
            $scenario->addTag(trim((string) $name, '#'), $value);
        }
        // Comme l'ajax changeState : le déclencheur est l'utilisateur du jeton.
        $scenario->addTag('trigger', 'user');
        $scenario->addTag('trigger_value', $user->getLogin());
        $scenario->addTag('trigger_message', $GLOBALS['JEEDOM_SCLOG_TEXT']['startManual']['txt'] ?? 'Lancement manuel');
        $scenario->launch(0);
        return Json::respond($response, self::runState(self::reload($scenario)), 202);
    }

    public function stop(Request $request, Response $response, array $args): Response
    {
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'x');
        $scenario->stop();
        return Json::respond($response, self::runState(self::reload($scenario)));
    }

    public function log(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        $lines = $request->getQueryParams()['lines'] ?? '500';
        if (!ctype_digit((string) $lines) || (int) $lines < 1 || (int) $lines > 5000) {
            throw ApiError::field(422, 'validation_error', 'lines', 'Un entier de 1 à 5000 est attendu.');
        }
        $file = self::logFile($scenario);
        $content = is_file($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
        return Json::respond($response, ['lines' => array_values(array_slice($content === false ? [] : $content, -(int) $lines))]);
    }

    public function emptyLog(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $file = self::logFile(self::find($args['id'], $request->getAttribute('user'), 'r'));
        if (is_file($file)) {
            unlink($file);
        }
        return $response->withStatus(204);
    }

    public function usages(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        $used = (array) $scenario->getUsedBy();
        // Un scénario qui s'appelle lui-même n'est pas une utilisation externe.
        $used['scenario'] = array_filter((array) ($used['scenario'] ?? []), static fn ($item) => !is_object($item) || (int) $item->getId() !== (int) $scenario->getId());
        return Json::respond($response, Usages::format($used));
    }

    /** @return list<\scenario> */
    private static function readable(\user $user): array
    {
        return array_values(array_filter((array) \scenario::all(), static fn ($scenario) => is_object($scenario) && $scenario->hasRight('r', $user)));
    }

    private static function searchFields(\scenario $scenario): array
    {
        $object = $scenario->getObject();
        return [$scenario->getName(), $scenario->getGroup(), is_object($object) ? $object->getName() : '', $scenario->getDescription()];
    }

    private static function find(string $id, \user $user, string $right): \scenario
    {
        $scenario = ctype_digit($id) ? \scenario::byId((int) $id) : null;
        if (!is_object($scenario)) {
            throw new ApiError(404, 'scenario_not_found', 'Scénario inconnu.');
        }
        if (!$scenario->hasRight($right, $user)) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur ce scénario.');
        }
        return $scenario;
    }

    private static function reload(\scenario $scenario): \scenario
    {
        $fresh = \scenario::byId($scenario->getId());
        return is_object($fresh) ? $fresh : $scenario;
    }

    private static function logFile(\scenario $scenario): string
    {
        return __DIR__ . '/../../../../log/scenarioLog/scenario' . (int) $scenario->getId() . '.log';
    }

    /** @return list<int> */
    private static function ids(array $body): array
    {
        $ids = $body['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || $ids === [] || count($ids) > self::MAX_BULK
            || array_filter($ids, static fn ($id) => !is_int($id) || $id < 1) !== [] || count(array_unique($ids)) !== count($ids)) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste de 1 à ' . self::MAX_BULK . ' identifiants entiers distincts est attendue.');
        }
        return $ids;
    }

    /** Exécute l'action pour chaque identifiant ; un échec n'arrête pas les autres. */
    private static function each(array $ids, callable $action): array
    {
        $done = [];
        $failed = [];
        foreach ($ids as $id) {
            try {
                $action($id);
                $done[] = $id;
            } catch (ApiError $e) {
                $failed[] = ['id' => $id, 'error' => ['code' => $e->errorCode, 'message' => $e->getMessage()]];
            } catch (\Throwable $e) {
                \log::add('api', 'error', $e->getMessage());
                $failed[] = ['id' => $id, 'error' => ['code' => 'internal_error', 'message' => 'Erreur interne.']];
            }
        }
        return ['done' => $done, 'failed' => $failed];
    }

    /** Valide puis enregistre, comme l'ajax save (a2o, puis blocs par saveAjaxElement). */
    private static function apply(\scenario $scenario, array $body): void
    {
        if ($unknown = array_diff(array_keys($body), self::FIELDS)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        if (array_key_exists('name', $body)) {
            $name = is_string($body['name']) ? trim($body['name']) : '';
            if ($name === '' || mb_strlen($name) > 127) {
                throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire (127 caractères au plus).');
            }
            $body['name'] = $name;
        }
        foreach (['isActive', 'isVisible'] as $field) {
            if (array_key_exists($field, $body)) {
                if (!is_bool($body[$field])) {
                    throw ApiError::field(422, 'validation_error', $field, 'Un booléen est attendu.');
                }
                $body[$field] = (int) $body[$field];
            }
        }
        if (array_key_exists('mode', $body) && !in_array($body['mode'], self::MODES, true)) {
            throw ApiError::field(422, 'validation_error', 'mode', 'Mode attendu : provoke, schedule ou all.');
        }
        foreach (['schedule', 'trigger'] as $field) {
            if (array_key_exists($field, $body) && (!is_array($body[$field]) || !array_is_list($body[$field]) || array_filter($body[$field], static fn ($item) => !is_string($item)) !== [])) {
                throw ApiError::field(422, 'validation_error', $field, 'Une liste de textes est attendue.');
            }
        }
        foreach (['group' => 127, 'description' => null] as $field => $max) {
            if (array_key_exists($field, $body) && $body[$field] !== null && (!is_string($body[$field]) || $max !== null && mb_strlen($body[$field]) > $max)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un texte ou null est attendu.');
            }
        }
        foreach (['timeout', 'object_id', 'order'] as $field) {
            if (array_key_exists($field, $body) && $body[$field] !== null && (!is_int($body[$field]) || $body[$field] < 0)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un entier positif ou null est attendu.');
            }
        }
        if (isset($body['object_id']) && !is_object(\jeeObject::byId($body['object_id']))) {
            throw ApiError::field(422, 'validation_error', 'object_id', 'Objet inconnu.');
        }
        foreach (['display', 'configuration'] as $field) {
            if (array_key_exists($field, $body) && (!is_array($body[$field]) || array_is_list($body[$field]) && $body[$field] !== [])) {
                throw ApiError::field(422, 'validation_error', $field, 'Un objet JSON est attendu.');
            }
        }
        if (array_key_exists('elements', $body) && (!is_array($body['elements']) || !array_is_list($body['elements']))) {
            throw ApiError::field(422, 'validation_error', 'elements', 'Une liste de blocs est attendue.');
        }
        $elements = $body['elements'] ?? null;
        unset($body['elements']);
        if (isset($body['trigger'])) {
            $scenario->setTrigger([]);
        }
        if (isset($body['schedule'])) {
            $scenario->setSchedule([]);
        }
        try {
            \utils::a2o($scenario, $body);
            if ($elements !== null) {
                $encoded = json_encode($elements, JSON_UNESCAPED_UNICODE);
                $scenario->setConfiguration('timeDependency', array_filter(self::TIME_KEYWORDS, static fn ($word) => str_contains((string) $encoded, $word)) !== [] ? 1 : 0);
                $scenario->setConfiguration('has_return', str_contains((string) $encoded, 'scenario_return') ? 1 : 0);
                $ids = [];
                foreach ($elements as $element) {
                    if (!is_array($element) || !isset($element['subElements']) || !is_array($element['subElements'])) {
                        throw ApiError::field(422, 'validation_error', 'elements', 'Chaque bloc doit avoir un type et des subElements.');
                    }
                    $ids[] = \scenarioElement::saveAjaxElement($element);
                }
                $scenario->setScenarioElement($ids);
            }
            $scenario->save();
        } catch (ApiError $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    private static function runState(\scenario $scenario): array
    {
        $state = (string) $scenario->getState();
        return [
            'state' => in_array($state, self::STATES, true) ? $state : 'stop',
            'lastLaunch' => self::nullableString($scenario->getLastLaunch()),
        ];
    }

    /** Convertit un scénario au schéma `Scenario` (types JSON, références lisibles). */
    private static function toArray(\scenario $scenario, bool $withElements): array
    {
        $raw = \utils::o2a($scenario);
        $schedule = $scenario->getSchedule();
        $trigger = \jeedom::toHumanReadable($scenario->getTrigger());
        $data = [
            'id' => (int) $raw['id'],
            'name' => (string) $raw['name'],
            'group' => self::nullableString($raw['group'] ?? null),
            'isActive' => (bool) (int) ($raw['isActive'] ?? 0),
            'isVisible' => (bool) (int) ($raw['isVisible'] ?? 0),
            'mode' => in_array($raw['mode'] ?? '', self::MODES, true) ? $raw['mode'] : 'provoke',
            'schedule' => array_values(array_filter(array_map('strval', is_array($schedule) ? $schedule : [$schedule]), static fn ($item) => $item !== '')),
            'trigger' => array_values(array_filter(array_map('strval', is_array($trigger) ? $trigger : [$trigger]), static fn ($item) => $item !== '')),
            'timeout' => is_numeric($raw['timeout'] ?? null) && (int) $raw['timeout'] > 0 ? (int) $raw['timeout'] : null,
            'object_id' => is_numeric($raw['object_id'] ?? null) && (int) $raw['object_id'] > 0 ? (int) $raw['object_id'] : null,
            'order' => (int) ($raw['order'] ?? 0),
            'description' => self::nullableString($raw['description'] ?? null),
            'display' => (object) (is_array($raw['display'] ?? null) ? $raw['display'] : []),
            'configuration' => (object) (is_array($raw['configuration'] ?? null) ? $raw['configuration'] : []),
        ] + self::runState($scenario);
        if ($withElements) {
            $data['elements'] = array_map(static fn ($element) => self::element($element->getAjaxElement()), (array) $scenario->getElement());
        }
        return $data;
    }

    /** Normalise un bloc de getAjaxElement : `options` toujours un objet, ids entiers. */
    private static function element(array $element): array
    {
        $element['options'] = self::object($element['options'] ?? []);
        if (isset($element['id'])) {
            $element['id'] = (int) $element['id'];
        }
        $element['subElements'] = array_map(static function ($sub) {
            $sub['options'] = self::object($sub['options'] ?? []);
            if (isset($sub['id'])) {
                $sub['id'] = (int) $sub['id'];
            }
            $sub['expressions'] = array_map(static function ($expression) {
                $expression['options'] = self::object($expression['options'] ?? []);
                $expression['expression'] = (string) ($expression['expression'] ?? '');
                if (isset($expression['id'])) {
                    $expression['id'] = (int) $expression['id'];
                }
                if (isset($expression['element']) && is_array($expression['element'])) {
                    $expression['element'] = self::element($expression['element']);
                } else {
                    unset($expression['element']);
                }
                return $expression;
            }, (array) ($sub['expressions'] ?? []));
            return $sub;
        }, (array) ($element['subElements'] ?? []));
        return $element;
    }

    private static function object(mixed $value): object
    {
        return (object) (is_array($value) ? $value : []);
    }

    private static function nullableString(mixed $value): ?string
    {
        return $value === null || $value === '' ? null : (string) $value;
    }
}
