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
    /**
     * Champs lus par le moteur pendant une exécution (scenario::launch/execute) : leur
     * changement arrête un scénario en cours. Les autres (nom, groupe, objet, visibilité,
     * activation, ordre, description, affichage) ne l'arrêtent pas.
     */
    private const EXECUTION_FIELDS = ['elements', 'trigger', 'schedule', 'mode', 'timeout', 'configuration'];
    private const MODES = ['provoke', 'schedule', 'all'];
    private const STATES = ['stop', 'in progress', 'starting', 'error'];
    /** Lecture maximale du journal par appel ; la suite vient à l'appel suivant. */
    private const MAX_LOG_READ = 1_048_576;

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
        $body = self::validate($body);
        $warnings = self::validateDefinition($request->getAttribute('user'), $scenario, $body);
        self::write($scenario, $body);
        return Json::respond($response, self::saved($scenario, $warnings), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'w');
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        $body = self::validate($body);
        $warnings = self::applyUpdate($request->getAttribute('user'), $scenario, $body);
        return Json::respond($response, self::saved($scenario, $warnings));
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
        $ids = Bulk::ids($body);
        $changes = $body['changes'] ?? null;
        if (!is_array($changes) || $changes === [] || array_is_list($changes)) {
            throw ApiError::field(422, 'validation_error', 'changes', 'Un objet JSON avec au moins un changement est attendu.');
        }
        if ($unknown = array_diff(array_keys($changes), self::BULK_CHANGES)) {
            throw ApiError::field(422, 'validation_error', 'changes.' . reset($unknown), 'Seuls isActive, isVisible et group peuvent être modifiés en groupe.');
        }
        $changes = self::validate($changes);
        $user = $request->getAttribute('user');
        return Json::respond($response, Bulk::each($ids, static function (int $id) use ($user, $changes): void {
            $scenario = self::find((string) $id, $user, 'w');
            self::applyUpdate($user, $scenario, $changes);
        }));
    }

    public function deleteMany(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $ids = Bulk::ids(Json::body($request));
        $user = $request->getAttribute('user');
        return Json::respond($response, Bulk::each($ids, static function (int $id) use ($user): void {
            self::find((string) $id, $user, 'w')->remove();
        }));
    }

    public function reorder(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        Bulk::reorder(Json::body($request), static function (int $id, int $position): bool {
            $scenario = \scenario::byId($id);
            if (!is_object($scenario)) {
                return false;
            }
            $scenario->setOrder($position);
            $scenario->save(true);
            return true;
        });
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

    /**
     * Lignes complètes du journal. Sans `offset`, les `lines` dernières ; avec, la suite
     * depuis cette position, pour suivre le journal en direct tant que l'écran est ouvert.
     */
    public function log(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        $query = $request->getQueryParams();
        $lines = $query['lines'] ?? '500';
        if (!ctype_digit((string) $lines) || (int) $lines < 1 || (int) $lines > 5000) {
            throw ApiError::field(422, 'validation_error', 'lines', 'Un entier de 1 à 5000 est attendu.');
        }
        $offset = null;
        if (isset($query['offset'])) {
            if (!ctype_digit((string) $query['offset'])) {
                throw ApiError::field(422, 'validation_error', 'offset', 'Un entier positif ou nul est attendu.');
            }
            $offset = (int) $query['offset'];
        }
        $file = self::logFile($scenario);
        clearstatcache(true, $file);
        $size = is_file($file) ? (int) filesize($file) : 0;
        $reset = false;
        if ($offset !== null && $offset > $size) {
            // Journal vidé ou recréé depuis le dernier appel : on repart du début.
            $offset = 0;
            $reset = true;
        }
        $start = $offset ?? max(0, $size - self::MAX_LOG_READ);
        $content = $size > $start ? (string) file_get_contents($file, false, null, $start, self::MAX_LOG_READ) : '';
        // Seules les lignes complètes ; une ligne en cours d'écriture viendra au prochain appel.
        $end = strrpos($content, "\n");
        $next = $start + ($end === false ? 0 : $end + 1);
        $read = $end === false ? [] : explode("\n", substr($content, 0, $end));
        if ($offset === null) {
            if ($start > 0) {
                array_shift($read); // première ligne probablement coupée
            }
            $read = array_slice($read, -(int) $lines);
        }
        return Json::respond($response, [
            'lines' => array_values(array_map(static fn (string $line) => mb_convert_encoding($line, 'UTF-8', 'UTF-8'), $read)),
            'offset' => $next,
            'reset' => $reset,
        ]);
    }

    public function logExcerpt(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        return LogExcerptReader::respond($request, $response, self::logFile($scenario), allowMissing: true);
    }

    public function runs(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        return Json::respond($response, [
            'runs' => ScenarioRuns::read((int) $scenario->getId()),
            'loggingEnabled' => $scenario->getConfiguration('logmode', 'default') !== 'none',
        ]);
    }

    public function runDetail(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        $detail = preg_match('/^(?:[a-f0-9]{32}|legacy-[a-f0-9]{64})$/D', $args['runId'])
            ? ScenarioRuns::read((int) $scenario->getId(), $args['runId']) : [];
        if ($detail === []) {
            throw new ApiError(404, 'scenario_run_not_found', 'Ce lancement ne figure plus dans le journal.');
        }
        return Json::respond($response, $detail);
    }

    public function emptyLog(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $scenario = self::find($args['id'], $request->getAttribute('user'), 'r');
        \scenarioTrace::clear((int) $scenario->getId());
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
        return \scenarioTrace::path((int) $scenario->getId());
    }

    /**
     * Vrai si le corps validé change un champ d'EXECUTION_FIELDS. `elements` compte
     * toujours comme un changement (le contenu est réenregistré) ; les autres champs
     * sont appliqués à une copie, comme write(), puis comparés.
     */
    private static function changesExecution(\scenario $scenario, array $body): bool
    {
        $fields = array_intersect_key($body, array_flip(self::EXECUTION_FIELDS));
        if ($fields === []) {
            return false;
        }
        if (array_key_exists('elements', $fields)) {
            return true;
        }
        $copy = clone $scenario;
        if (isset($fields['trigger'])) {
            $copy->setTrigger([]);
        }
        if (isset($fields['schedule'])) {
            $copy->setSchedule([]);
        }
        \utils::a2o($copy, $fields);
        return $copy->getTrigger() !== $scenario->getTrigger()
            || $copy->getSchedule() !== $scenario->getSchedule()
            || $copy->getMode() !== $scenario->getMode()
            || $copy->getTimeout() !== $scenario->getTimeout()
            || $copy->getConfiguration() !== $scenario->getConfiguration();
    }

    /** Valide le corps sans rien écrire ; renvoie les valeurs normalisées pour write(). */
    private static function validate(array $body): array
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
        return $body;
    }

    private static function validateDefinition(\user $user, \scenario $scenario, array $body): array
    {
        try {
            return \scenarioValidator::validate($scenario, $body, $user);
        } catch (\scenarioValidationException $error) {
            throw ApiError::field(422, 'validation_error', $error->field, $error->getMessage());
        }
    }

    private static function applyUpdate(\user $user, \scenario $scenario, array $body): array
    {
        $executionChanged = self::changesExecution($scenario, $body);
        $warnings = [];
        if ($executionChanged || ($body['isActive'] ?? null) === 1 || array_key_exists('object_id', $body)) {
            $warnings = self::validateDefinition($user, $scenario, $body);
        }
        if ($executionChanged && $scenario->running()) {
            $scenario->stop();
        }
        self::write($scenario, $body);
        return $warnings;
    }

    private static function saved(\scenario $scenario, array $warnings): array
    {
        $data = self::toArray(self::reload($scenario), true);
        if ($warnings !== []) {
            $data['warnings'] = $warnings;
        }
        return $data;
    }

    /** Enregistre un corps validé, comme l'ajax save (a2o, puis blocs par saveAjaxElement). */
    private static function write(\scenario $scenario, array $body): void
    {
        $elements = $body['elements'] ?? null;
        unset($body['elements']);
        if (isset($body['trigger'])) {
            $scenario->setTrigger([]);
        }
        if (isset($body['schedule'])) {
            $scenario->setSchedule([]);
        }
        \DB::beginTransaction();
        try {
            \utils::a2o($scenario, $body);
            if ($elements !== null) {
                $scenario->refreshContentDependencies($elements);
                $ids = [];
                foreach ($elements as $element) {
                    $ids[] = \scenarioElement::saveAjaxElement($element);
                }
                $scenario->setScenarioElement($ids);
            }
            $scenario->save();
            \DB::commit();
        } catch (\Throwable $error) {
            if (\DB::getConnection()->inTransaction()) {
                \DB::rollBack();
            }
            if ($error instanceof ApiError || !$error instanceof \Exception) {
                throw $error;
            }
            throw new ApiError(422, 'validation_error', $error->getMessage());
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
