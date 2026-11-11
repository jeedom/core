<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressource /cmds (core/api/rest/openapi.yaml). Chaque route appelle les méthodes
 * existantes de cmd, comme core/ajax/cmd.ajax.php, sans dupliquer leur logique.
 * Une commande est lisible si l'utilisateur peut lire (info) ou exécuter (action) son
 * équipement (`cmd::hasRight`) ; l'écriture demande un administrateur ayant le droit
 * d'écriture sur l'équipement.
 */
final class CmdsController
{
    private const SUB_TYPES = [
        'info' => ['numeric', 'binary', 'string'],
        'action' => ['other', 'slider', 'message', 'color', 'select'],
    ];
    private const FIELDS = ['eqLogic_id', 'name', 'type', 'subType', 'logicalId', 'generic_type', 'order', 'isVisible', 'isHistorized', 'unite', 'value', 'accessCode', 'configuration', 'display', 'template', 'alert'];
    private const BULK_CHANGES = ['isVisible', 'isHistorized', 'generic_type'];
    private const EXEC_OPTIONS = ['slider', 'color', 'select', 'title', 'message'];
    private const MAX_BULK = 1000;
    private const DATE = 'Y-m-d H:i:s';

    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $user = $request->getAttribute('user');
        $search = ListQuery::from($request);
        if (isset($query['eqLogicId'])) {
            if (!ctype_digit((string) $query['eqLogicId']) || (int) $query['eqLogicId'] < 1) {
                throw ApiError::field(422, 'validation_error', 'eqLogicId', 'Un entier positif est attendu.');
            }
            $cmds = \cmd::byEqLogicId((int) $query['eqLogicId']);
        } else {
            $cmds = \cmd::all();
        }
        $type = (string) ($query['type'] ?? '');
        $subType = (string) ($query['subType'] ?? '');
        $genericType = (string) ($query['generic_type'] ?? '');
        $historized = isset($query['isHistorized']) ? filter_var($query['isHistorized'], FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) : null;
        $cmds = array_values(array_filter((array) $cmds, static fn ($cmd) => is_object($cmd)
            && ($type === '' || $cmd->getType() === $type)
            && ($subType === '' || $cmd->getSubType() === $subType)
            && ($genericType === '' || (string) $cmd->getGeneric_type() === $genericType)
            && ($historized === null || (bool) (int) $cmd->getIsHistorized() === $historized)
            && self::canRead($cmd, $user)
            && $search->matches([$cmd->getName(), $cmd->getEqLogic()->getName(), $cmd->getGeneric_type()])));
        usort($cmds, static fn ($a, $b) => [(int) $a->getOrder(), $a->getName(), (int) $a->getId()] <=> [(int) $b->getOrder(), $b->getName(), (int) $b->getId()]);
        $page = $search->page($cmds);
        $page['items'] = array_map(self::toArray(...), $page['items']);
        return Json::respond($response, $page);
    }

    public function genericTypes(Request $request, Response $response): Response
    {
        $items = [];
        foreach (\config::getGenericTypes()['byType'] as $id => $definition) {
            $type = strtolower((string) $definition['type']);
            if (!isset(self::SUB_TYPES[$type])) {
                continue;
            }
            $item = [
                'id' => (string) $id,
                'name' => (string) $definition['name'],
                'family' => (string) $definition['family'],
                'type' => $type,
            ];
            if (isset($definition['subtype'])) {
                $item['subTypes'] = array_values(array_intersect(self::SUB_TYPES[$type], (array) $definition['subtype']));
            }
            $items[] = $item;
        }
        return Json::respond($response, ['items' => $items]);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        return Json::respond($response, self::toArray(self::findReadable($args['id'], $request->getAttribute('user'))));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        foreach (['eqLogic_id', 'name', 'type', 'subType'] as $required) {
            if (!array_key_exists($required, $body)) {
                throw ApiError::field(422, 'validation_error', $required, 'Ce champ est obligatoire.');
            }
        }
        $eqLogic = is_int($body['eqLogic_id']) ? \eqLogic::byId($body['eqLogic_id']) : null;
        if (!is_object($eqLogic)) {
            throw ApiError::field(422, 'validation_error', 'eqLogic_id', 'Équipement inconnu.');
        }
        if (!$eqLogic->hasRight('w', $request->getAttribute('user'))) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur cet équipement.');
        }
        if (!is_string($body['type']) || !isset(self::SUB_TYPES[$body['type']])) {
            throw ApiError::field(422, 'validation_error', 'type', 'Type attendu : info ou action.');
        }
        // Même classe que celle du plugin quand il en a une, comme cmd::byId.
        $class = $eqLogic->getEqType_name() . 'Cmd';
        $cmd = class_exists($class) && is_subclass_of($class, \cmd::class) ? new $class() : new \cmd();
        $cmd->setEqLogic_id($eqLogic->getId());
        $cmd->setEqLogic($eqLogic);
        $cmd->setEqType($eqLogic->getEqType_name());
        $cmd->setType($body['type']);
        unset($body['eqLogic_id'], $body['type']);
        self::apply($cmd, $body + ['isVisible' => true]);
        return Json::respond($response, self::toArray(self::reload($cmd)), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $cmd = self::findWritable($args['id'], $request->getAttribute('user'));
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        foreach (['eqLogic_id', 'type'] as $locked) {
            if (array_key_exists($locked, $body)) {
                throw ApiError::field(422, 'validation_error', $locked, 'Ce champ ne peut pas changer.');
            }
        }
        self::apply($cmd, $body);
        return Json::respond($response, self::toArray(self::reload($cmd)));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::findWritable($args['id'], $request->getAttribute('user'))->remove();
        return $response->withStatus(204);
    }

    public function updateMany(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        $ids = $body['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || $ids === [] || count($ids) > self::MAX_BULK
            || array_filter($ids, static fn ($id) => !is_int($id) || $id < 1) !== [] || count(array_unique($ids)) !== count($ids)) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste de 1 à ' . self::MAX_BULK . ' identifiants entiers distincts est attendue.');
        }
        $changes = $body['changes'] ?? null;
        if (!is_array($changes) || $changes === [] || array_is_list($changes)) {
            throw ApiError::field(422, 'validation_error', 'changes', 'Un objet JSON avec au moins un changement est attendu.');
        }
        if ($unknown = array_diff(array_keys($changes), self::BULK_CHANGES)) {
            throw ApiError::field(422, 'validation_error', 'changes.' . reset($unknown), 'Seuls isVisible, isHistorized et generic_type peuvent être modifiés en groupe.');
        }
        $user = $request->getAttribute('user');
        $done = [];
        $failed = [];
        foreach ($ids as $id) {
            try {
                self::apply(self::findWritable((string) $id, $user), $changes);
                $done[] = $id;
            } catch (ApiError $e) {
                $failed[] = ['id' => $id, 'error' => ['code' => $e->errorCode, 'message' => $e->getMessage()]];
            } catch (\Throwable $e) {
                \log::add('api', 'error', $e->getMessage());
                $failed[] = ['id' => $id, 'error' => ['code' => 'internal_error', 'message' => 'Erreur interne.']];
            }
        }
        return Json::respond($response, ['done' => $done, 'failed' => $failed]);
    }

    public function reorder(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $ids = Json::body($request)['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || array_filter($ids, static fn ($id) => !is_int($id)) !== []) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste d\'identifiants entiers est attendue.');
        }
        // Seul l'ordre change, contrairement à l'ajax setOrder qui touche aussi la disposition.
        $position = 1;
        foreach ($ids as $id) {
            $cmd = \cmd::byId($id);
            if (is_object($cmd)) {
                $cmd->setOrder($position++);
                $cmd->save(true);
            }
        }
        return $response->withStatus(204);
    }

    public function exec(Request $request, Response $response, array $args): Response
    {
        $user = $request->getAttribute('user');
        $cmd = self::find($args['id']);
        if ($cmd->getType() !== 'action') {
            throw new ApiError(422, 'validation_error', 'Seule une commande action s\'exécute ; la valeur d\'une info se lit avec /cmds/{id}/value.');
        }
        if (!self::canRead($cmd, $user)) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur cet équipement.');
        }
        $raw = (string) $request->getBody();
        $body = $raw === '' ? [] : Json::body($request);
        if ($unknown = array_diff(array_keys($body), ['options', 'codeAccess', 'confirm'])) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu.');
        }
        $options = $body['options'] ?? [];
        if (!is_array($options) || array_is_list($options) && $options !== []) {
            throw ApiError::field(422, 'validation_error', 'options', 'Un objet JSON est attendu.');
        }
        if ($unknown = array_diff(array_keys($options), self::EXEC_OPTIONS)) {
            throw ApiError::field(422, 'validation_error', 'options.' . reset($unknown), 'Option inconnue.');
        }
        if (!$cmd->checkAccessCode((string) ($body['codeAccess'] ?? ''))) {
            throw new ApiError(403, 'access_code_required', 'Cette action nécessite un code d\'accès.');
        }
        if ((int) $cmd->getConfiguration('actionConfirm') === 1 && ($body['confirm'] ?? false) !== true) {
            throw new ApiError(403, 'confirmation_required', 'Cette action nécessite une confirmation.');
        }
        // L'utilisateur vient du jeton, jamais du client (l'ajax accepte user_login/user_id du navigateur).
        $options['user_login'] = $user->getLogin();
        $options['user_id'] = $user->getId();
        try {
            $result = $cmd->execCmd($options);
        } catch (\Exception $e) {
            throw new ApiError(409, 'cmd_exec_failed', $e->getMessage());
        }
        return Json::respond($response, ['result' => is_scalar($result) ? $result : null]);
    }

    public function value(Request $request, Response $response, array $args): Response
    {
        $cmd = self::findReadable($args['id'], $request->getAttribute('user'));
        if ($cmd->getType() !== 'info') {
            return Json::respond($response, ['value' => self::scalar($cmd->getConfiguration('lastCmdValue', null)), 'collectDate' => null, 'valueDate' => null]);
        }
        $value = $cmd->execCmd();
        return Json::respond($response, [
            'value' => self::scalar($value),
            'collectDate' => self::nullableString($cmd->getCollectDate()),
            'valueDate' => self::nullableString($cmd->getValueDate()),
        ]);
    }

    public function history(Request $request, Response $response, array $args): Response
    {
        $cmd = self::findReadableInfo($args['id'], $request->getAttribute('user'));
        [$start, $end] = self::period($request);
        $grouping = $request->getQueryParams()['grouping'] ?? null;
        if ($grouping !== null && !preg_match('/^(avg|max|high|min|low|sum)::(minute|hour|day|week|month|year)$/', (string) $grouping)) {
            throw ApiError::field(422, 'validation_error', 'grouping', 'Format attendu : fonction::période (ex. avg::hour).');
        }
        $derive = self::bool($request->getQueryParams()['derive'] ?? null, 'derive') ?? (bool) (int) $cmd->getDisplay('graphDerive');
        if ($derive) {
            $grouping = null;
        }
        $items = [];
        $previous = null;
        foreach ((array) \history::all($cmd->getId(), $start, $end, $grouping) as $point) {
            $value = $point->getValue();
            $value = is_numeric($value) ? $value + 0 : self::nullableString($value);
            if ($derive) {
                // Comme l'option derive de l'ajax getHistory : écart avec le point précédent.
                $current = is_numeric($value) ? (float) $value : null;
                $value = $current !== null && $previous !== null ? $current - $previous : null;
                $previous = $current;
            }
            $items[] = ['datetime' => (string) $point->getDatetime(), 'value' => $value];
        }
        return Json::respond($response, ['items' => $items]);
    }

    public function updateHistoryPoint(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $cmd = self::findWritable($args['id'], $request->getAttribute('user'));
        $body = Json::body($request);
        if ($unknown = array_diff(array_keys($body), ['datetime', 'value', 'oldValue'])) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu.');
        }
        if (!array_key_exists('datetime', $body) || !array_key_exists('value', $body)) {
            throw ApiError::field(422, 'validation_error', array_key_exists('datetime', $body) ? 'value' : 'datetime', 'Ce champ est obligatoire.');
        }
        $datetime = self::date($body['datetime'], 'datetime')?->format(self::DATE);
        if ($datetime === null) {
            throw ApiError::field(422, 'validation_error', 'datetime', 'Ce champ est obligatoire.');
        }
        foreach (['value', 'oldValue'] as $field) {
            if (isset($body[$field]) && !is_int($body[$field]) && !is_float($body[$field]) && !is_string($body[$field])) {
                throw ApiError::field(422, 'validation_error', $field, 'Un nombre ou une chaîne est attendu.');
            }
        }
        // Sans oldValue, les plages suivantes ne filtrent pas la valeur : seule la date exacte est cherchée.
        $history = isset($body['oldValue'])
            ? \history::byCmdIdDatetimeOrNext($cmd->getId(), $datetime, (string) $body['oldValue'])
            : \history::byCmdIdDatetime($cmd->getId(), $datetime);
        if (!is_object($history)) {
            throw new ApiError(404, 'history_point_not_found', 'Aucun point à cette date.');
        }
        if ($body['value'] === null) {
            $history->remove();
        } else {
            $history->setValue((string) $body['value']);
            $history->save(null, true);
        }
        return $response->withStatus(204);
    }

    public function emptyHistory(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $cmd = self::findWritable($args['id'], $request->getAttribute('user'));
        $body = (string) $request->getBody() === '' ? [] : Json::body($request);
        if ($unknown = array_diff(array_keys($body), ['before'])) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu.');
        }
        $before = self::date($body['before'] ?? null, 'before');
        $cmd->emptyHistory($before === null ? '' : $before->format(self::DATE));
        return $response->withStatus(204);
    }

    public function copyHistory(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $user = $request->getAttribute('user');
        $source = self::findWritable($args['id'], $user);
        $body = Json::body($request);
        if ($unknown = array_diff(array_keys($body), ['targetId'])) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu.');
        }
        if (!is_int($body['targetId'] ?? null) || $body['targetId'] < 1) {
            throw ApiError::field(422, 'validation_error', 'targetId', 'Un entier positif est attendu.');
        }
        $target = \cmd::byId($body['targetId']);
        $eqLogic = is_object($target) ? $target->getEqLogic() : null;
        if (!is_object($eqLogic) || !$eqLogic->hasRight('w', $user)) {
            throw ApiError::field(422, 'validation_error', 'targetId', 'Commande cible inconnue.');
        }
        try {
            \history::copyHistoryToCmd($source->getId(), $target->getId());
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
        return $response->withStatus(204);
    }

    public function removeFutureHistory(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        \history::removeHistoryInFutur();
        return $response->withStatus(204);
    }

    public function calculatedHistory(Request $request, Response $response): Response
    {
        // Une formule peut lire n'importe quelle commande, même illisible pour l'utilisateur.
        Auth::requireAdmin($request);
        $query = $request->getQueryParams();
        $formula = $query['formula'] ?? '';
        if (!is_string($formula) || trim($formula) === '') {
            throw ApiError::field(422, 'validation_error', 'formula', 'Ce champ est obligatoire.');
        }
        [$start, $end] = self::period($request);
        $grouping = $query['grouping'] ?? null;
        if ($grouping !== null && !preg_match('/^(avg|max|high|min|low|sum)::(minute|hour|day|week|month|year)$/', (string) $grouping)) {
            throw ApiError::field(422, 'validation_error', 'grouping', 'Format attendu : fonction::période (ex. avg::hour).');
        }
        $items = [];
        $points = \history::getHistoryFromCalcul(\jeedom::fromHumanReadable($formula), $start, $end, false, $grouping);
        foreach (is_array($points) ? $points : [] as $timestamp => $value) {
            // Les clés sont des dates du core lues comme UTC (history::getHistoryFromCalcul).
            $items[] = ['datetime' => gmdate(self::DATE, (int) $timestamp), 'value' => $value === null ? null : (float) $value];
        }
        return Json::respond($response, ['items' => $items]);
    }

    public function statistics(Request $request, Response $response, array $args): Response
    {
        $cmd = self::findReadableInfo($args['id'], $request->getAttribute('user'));
        [$start, $end] = self::period($request);
        $stats = (array) $cmd->getStatistique($start, $end);
        $result = [];
        foreach (['avg', 'min', 'max', 'sum', 'std', 'variance'] as $key) {
            $result[$key] = is_numeric($stats[$key] ?? null) ? (float) $stats[$key] : null;
        }
        $result['count'] = (int) ($stats['count'] ?? 0);
        $last = $stats['last'] ?? null;
        $result['last'] = is_numeric($last) ? $last + 0 : self::nullableString($last);
        $result['trend'] = (float) $cmd->getTendance($start, $end);
        return Json::respond($response, $result);
    }

    public function usages(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, Usages::format((array) self::findReadable($args['id'], $request->getAttribute('user'))->getUsedBy()));
    }

    /**
     * Commandes d'un équipement lisibles par l'utilisateur, au schéma `Cmd` (sans code
     * d'accès), triées comme /cmds. Utilisé par `include=cmds` des autres ressources.
     * @return list<array<string, mixed>>
     */
    public static function forEqLogic(\eqLogic $eqLogic, \user $user): array
    {
        $cmds = array_values(array_filter((array) $eqLogic->getCmd(), static fn ($cmd) => is_object($cmd) && self::canRead($cmd, $user)));
        usort($cmds, static fn ($a, $b) => [(int) $a->getOrder(), $a->getName()] <=> [(int) $b->getOrder(), $b->getName()]);
        return array_map(self::toArray(...), $cmds);
    }

    private static function find(string $id): \cmd
    {
        $cmd = ctype_digit($id) ? \cmd::byId((int) $id) : null;
        if (!is_object($cmd)) {
            throw new ApiError(404, 'cmd_not_found', 'Commande inconnue.');
        }
        return $cmd;
    }

    private static function canRead(\cmd $cmd, \user $user): bool
    {
        return is_object($cmd->getEqLogic()) && $cmd->hasRight($user);
    }

    private static function findReadable(string $id, \user $user): \cmd
    {
        $cmd = self::find($id);
        if (!self::canRead($cmd, $user)) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur cet équipement.');
        }
        return $cmd;
    }

    private static function findReadableInfo(string $id, \user $user): \cmd
    {
        $cmd = self::findReadable($id, $user);
        if ($cmd->getType() !== 'info') {
            throw new ApiError(422, 'validation_error', 'Disponible seulement pour une commande info.');
        }
        return $cmd;
    }

    private static function findWritable(string $id, \user $user): \cmd
    {
        $cmd = self::find($id);
        $eqLogic = $cmd->getEqLogic();
        if (!is_object($eqLogic) || !$eqLogic->hasRight('w', $user)) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur cet équipement.');
        }
        return $cmd;
    }

    private static function reload(\cmd $cmd): \cmd
    {
        $fresh = \cmd::byId($cmd->getId());
        return is_object($fresh) ? $fresh : $cmd;
    }

    /** @return array{0: string, 1: string} début et fin au format du core */
    private static function period(Request $request): array
    {
        $query = $request->getQueryParams();
        $end = self::date($query['end'] ?? null, 'end') ?? new \DateTimeImmutable();
        $start = self::date($query['start'] ?? null, 'start') ?? $end->modify('-1 day');
        if ($start > $end) {
            throw ApiError::field(422, 'validation_error', 'start', 'Le début doit précéder la fin.');
        }
        return [$start->format(self::DATE), $end->format(self::DATE)];
    }

    private static function bool(mixed $value, string $field): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }
        $bool = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        if ($bool === null) {
            throw ApiError::field(422, 'validation_error', $field, 'true ou false est attendu.');
        }
        return $bool;
    }

    private static function date(mixed $value, string $field): ?\DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }
        // RFC 3339 (format date-time de la spec) : fractions de seconde facultatives, comme toISOString() en JavaScript.
        $date = false;
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d{1,6})?(Z|[+-]\d{2}:\d{2})$/', $value)) {
            $date = \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, preg_replace('/\.\d{1,6}(?=Z|[+-])/', '', $value));
            // Refuse les dates impossibles (2026-13-40) que PHP reporterait sur le mois suivant.
            $errors = \DateTimeImmutable::getLastErrors();
            if ($errors !== false && $errors['warning_count'] > 0) {
                $date = false;
            }
        }
        if ($date === false) {
            throw ApiError::field(422, 'validation_error', $field, 'Une date ISO 8601 est attendue (ex. 2026-11-05T10:00:00+01:00).');
        }
        return $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
    }

    /** Valide puis enregistre les champs modifiables, comme l'ajax save via utils::a2o. */
    private static function apply(\cmd $cmd, array $body): void
    {
        if ($unknown = array_diff(array_keys($body), self::FIELDS)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        if (array_key_exists('subType', $body) && !in_array($body['subType'], self::SUB_TYPES[$cmd->getType()] ?? [], true)) {
            throw ApiError::field(422, 'validation_error', 'subType', 'Sous-type attendu pour une ' . $cmd->getType() . ' : ' . implode(', ', self::SUB_TYPES[$cmd->getType()] ?? []) . '.');
        }
        if (array_key_exists('name', $body)) {
            $name = is_string($body['name']) ? trim($body['name']) : '';
            if ($name === '' || mb_strlen($name) > 127) {
                throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire (127 caractères au plus).');
            }
            $body['name'] = $name;
            self::checkNameFree($name, (int) $cmd->getEqLogic_id(), $cmd->getId());
        }
        foreach (['isVisible', 'isHistorized'] as $field) {
            if (array_key_exists($field, $body)) {
                if (!is_bool($body[$field])) {
                    throw ApiError::field(422, 'validation_error', $field, 'Un booléen est attendu.');
                }
                $body[$field] = (int) $body[$field];
            }
        }
        if (array_key_exists('order', $body) && !is_int($body['order'])) {
            throw ApiError::field(422, 'validation_error', 'order', 'Un entier est attendu.');
        }
        foreach (['logicalId' => 1023, 'generic_type' => 255, 'unite' => 45, 'value' => null] as $field => $max) {
            if (array_key_exists($field, $body) && $body[$field] !== null
                && (!is_string($body[$field]) || $max !== null && mb_strlen($body[$field]) > $max)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un texte' . ($max !== null ? " de {$max} caractères au plus" : '') . ' ou null est attendu.');
            }
        }
        foreach (['configuration', 'display', 'template', 'alert'] as $field) {
            if (array_key_exists($field, $body) && (!is_array($body[$field]) || array_is_list($body[$field]) && $body[$field] !== [])) {
                throw ApiError::field(422, 'validation_error', $field, 'Un objet JSON est attendu.');
            }
        }
        if (isset($body['configuration']) && array_key_exists('actionCodeAccess', $body['configuration'])) {
            throw ApiError::field(422, 'validation_error', 'configuration.actionCodeAccess', 'Utilisez le champ accessCode pour modifier le code d\'accès.');
        }
        if (!empty($body['configuration'])) {
            PluginSettings::validateCommand($cmd->getEqLogic()->getEqType_name(), $body['configuration'], [
                'type' => $cmd->getType(),
                'subType' => $body['subType'] ?? $cmd->getSubType(),
                'logicalId' => array_key_exists('logicalId', $body) ? $body['logicalId'] : $cmd->getLogicalId(),
            ]);
        }
        $changeAccessCode = array_key_exists('accessCode', $body);
        $accessCode = $body['accessCode'] ?? null;
        if ($changeAccessCode) {
            if ($cmd->getType() !== 'action') {
                throw ApiError::field(422, 'validation_error', 'accessCode', 'Le code d\'accès est réservé aux commandes action.');
            }
            if ($accessCode !== null && (!is_string($accessCode) || trim($accessCode) === '')) {
                throw ApiError::field(422, 'validation_error', 'accessCode', 'Saisissez un code non vide ou null pour le supprimer.');
            }
            unset($body['accessCode']);
        }

        \utils::a2o($cmd, \jeedom::fromHumanReadable($body));
        if ($changeAccessCode) {
            // Toujours hacher le texte fourni, même s'il a la forme d'un hash déjà calculé.
            $cmd->setConfiguration('actionCodeAccess', $accessCode === null ? null : \sha512($accessCode));
        }
        try {
            $cmd->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    /** Le nom est unique par équipement (index unique eqLogic_id + name de la table cmd). */
    private static function checkNameFree(string $name, int $eqLogicId, mixed $selfId): void
    {
        foreach ((array) \cmd::byEqLogicId($eqLogicId) as $other) {
            if (is_object($other) && mb_strtolower((string) $other->getName()) === mb_strtolower($name) && (int) $other->getId() !== (int) $selfId) {
                throw ApiError::field(409, 'cmd_name_taken', 'name', 'Une commande porte déjà ce nom dans cet équipement.');
            }
        }
    }

    /** Convertit une commande du core au schéma `Cmd` (types JSON, références lisibles). */
    private static function toArray(\cmd $cmd): array
    {
        $raw = \jeedom::toHumanReadable(\utils::o2a($cmd));
        $configuration = is_array($raw['configuration'] ?? null) ? $raw['configuration'] : [];
        unset($configuration['actionCodeAccess']);
        $current = $cmd->getType() === 'action' ? $cmd->getConfiguration('lastCmdValue', null) : $cmd->execCmd();
        return [
            'id' => (int) $raw['id'],
            'eqLogic_id' => (int) $raw['eqLogic_id'],
            'eqType' => (string) ($raw['eqType'] ?? ''),
            'logicalId' => self::nullableString($raw['logicalId'] ?? null),
            'name' => (string) $raw['name'],
            'type' => (string) $raw['type'],
            'subType' => (string) $raw['subType'],
            'generic_type' => self::nullableString($raw['generic_type'] ?? null),
            'order' => (int) ($raw['order'] ?? 0),
            'isVisible' => (bool) (int) ($raw['isVisible'] ?? 0),
            'isHistorized' => (bool) (int) ($raw['isHistorized'] ?? 0),
            'unite' => self::nullableString($raw['unite'] ?? null),
            'value' => self::nullableString($raw['value'] ?? null),
            'hasAccessCode' => $cmd->getType() === 'action' && trim((string) $cmd->getConfiguration('actionCodeAccess')) !== '',
            'configuration' => (object) $configuration,
            'display' => (object) (is_array($raw['display'] ?? null) ? $raw['display'] : []),
            'template' => (object) (is_array($raw['template'] ?? null) ? $raw['template'] : []),
            'alert' => (object) (is_array($raw['alert'] ?? null) ? $raw['alert'] : []),
            'currentValue' => self::scalar($current),
        ];
    }

    private static function scalar(mixed $value): string|int|float|bool|null
    {
        return is_scalar($value) ? $value : null;
    }

    private static function nullableString(mixed $value): ?string
    {
        return $value === null || $value === '' ? null : (string) $value;
    }
}
