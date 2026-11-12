<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Ressource /eqLogics (core/api/rest/openapi.yaml). Chaque route appelle les méthodes
 * existantes d'eqLogic, comme core/ajax/eqLogic.ajax.php, sans dupliquer leur logique.
 * Les droits sont vérifiés avec l'utilisateur du jeton : un équipement qu'il n'a pas le
 * droit de lire est absent des listes et renvoie 403 quand il est demandé directement.
 */
final class EqLogicsController
{
    private const FIELDS = ['name', 'eqType_name', 'logicalId', 'object_id', 'isEnable', 'isVisible', 'order', 'timeout', 'generic_type', 'tags', 'comment', 'category', 'configuration', 'display'];
    private const BULK_CHANGES = ['isEnable', 'isVisible', 'generic_type'];
    private const MAX_BULK = 1000;

    public function list(Request $request, Response $response): Response
    {
        $query = $request->getQueryParams();
        $user = $request->getAttribute('user');
        $search = ListQuery::from($request);
        $type = (string) ($query['eqType'] ?? '');
        $logicalId = (string) ($query['logicalId'] ?? '');
        if (isset($query['objectId'])) {
            if (!ctype_digit((string) $query['objectId'])) {
                throw ApiError::field(422, 'validation_error', 'objectId', 'Un entier positif ou nul est attendu.');
            }
            $objectId = (int) $query['objectId'];
            $eqLogics = \eqLogic::byObjectId($objectId === 0 ? null : $objectId, false, false, $type !== '' ? $type : null, $logicalId !== '' ? $logicalId : null);
        } elseif ($type !== '') {
            $eqLogics = \eqLogic::byType($type);
        } else {
            $eqLogics = \eqLogic::all();
        }
        $enabled = self::optionalFlag($query['enabled'] ?? null);
        $visible = self::optionalFlag($query['visible'] ?? null);
        $eqLogics = array_values(array_filter((array) $eqLogics, static fn ($eqLogic) => is_object($eqLogic)
            && ($logicalId === '' || (string) $eqLogic->getLogicalId() === $logicalId)
            && ($enabled === null || (bool) (int) $eqLogic->getIsEnable() === $enabled)
            && ($visible === null || (bool) (int) $eqLogic->getIsVisible() === $visible)
            && $eqLogic->hasRight('r', $user)
            && $search->matches(self::searchFields($eqLogic))));
        usort($eqLogics, static fn ($a, $b) => [(int) $a->getOrder(), $a->getName(), (int) $a->getId()] <=> [(int) $b->getOrder(), $b->getName(), (int) $b->getId()]);
        $page = $search->page($eqLogics);
        $withCmds = self::includes($request, 'cmds');
        $page['items'] = array_map(static fn ($eqLogic) => self::toArray($eqLogic, $user, $withCmds), $page['items']);
        return Json::respond($response, $page);
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $user = $request->getAttribute('user');
        $eqLogic = self::find($args['id'], $user, 'r');
        return Json::respond($response, self::toArray($eqLogic, $user, self::includes($request, 'cmds')));
    }

    public function create(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        foreach (['name', 'eqType_name'] as $required) {
            if (!array_key_exists($required, $body)) {
                throw ApiError::field(422, 'validation_error', $required, 'Ce champ est obligatoire.');
            }
        }
        $type = $body['eqType_name'];
        // Même exigence que l'ajax save : la classe du plugin doit être chargée.
        if (!is_string($type) || $type === '' || !class_exists($type) || !is_subclass_of($type, \eqLogic::class)) {
            throw ApiError::field(422, 'validation_error', 'eqType_name', 'Plugin inconnu ou non installé.');
        }
        $eqLogic = new $type();
        $eqLogic->setEqType_name($type);
        self::apply($eqLogic, $body);
        $user = $request->getAttribute('user');
        return Json::respond($response, self::toArray(self::reload($eqLogic), $user, false), 201);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $user = $request->getAttribute('user');
        $eqLogic = self::find($args['id'], $user, 'w');
        $body = Json::body($request);
        if ($body === []) {
            throw new ApiError(422, 'validation_error', 'Aucun champ à modifier.');
        }
        if (array_key_exists('eqType_name', $body)) {
            throw ApiError::field(422, 'validation_error', 'eqType_name', 'Le plugin d\'un équipement ne peut pas changer.');
        }
        self::apply($eqLogic, $body);
        return Json::respond($response, self::toArray(self::reload($eqLogic), $user, false));
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        self::remove(self::find($args['id'], $request->getAttribute('user'), 'w'));
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
            throw ApiError::field(422, 'validation_error', 'changes.' . reset($unknown), 'Seuls isEnable, isVisible et generic_type peuvent être modifiés en groupe.');
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
            self::remove(self::find((string) $id, $user, 'w'));
        }));
    }

    public function reorder(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $ids = Json::body($request)['ids'] ?? null;
        if (!is_array($ids) || !array_is_list($ids) || array_filter($ids, static fn ($id) => !is_int($id)) !== []) {
            throw ApiError::field(422, 'validation_error', 'ids', 'Une liste d\'identifiants entiers est attendue.');
        }
        // Seul l'ordre change, contrairement à l'ajax setOrder qui accepte tous les champs.
        $position = 1;
        foreach ($ids as $id) {
            $eqLogic = \eqLogic::byId($id);
            if (is_object($eqLogic)) {
                $eqLogic->setOrder($position++);
                $eqLogic->save(true);
            }
        }
        return $response->withStatus(204);
    }

    public function copy(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $user = $request->getAttribute('user');
        $eqLogic = self::find($args['id'], $user, 'r');
        $name = Json::body($request)['name'] ?? null;
        $name = is_string($name) ? trim($name) : '';
        if ($name === '' || mb_strlen($name) > 127) {
            throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire (127 caractères au plus).');
        }
        self::checkNameFree($name, self::objectId($eqLogic->getObject_id()), null);
        try {
            $copy = $eqLogic->copy($name);
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
        return Json::respond($response, self::toArray(self::reload($copy), $user, false), 201);
    }

    public function usages(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $eqLogic = self::find($args['id'], $request->getAttribute('user'), 'r');
        return Json::respond($response, Usages::format(self::usedBy($eqLogic)));
    }

    /**
     * Ce qui utilise l'équipement ou l'une de ses commandes (comme l'ajax getUseBeforeRemove),
     * sans l'équipement lui-même ni ses propres commandes, chaque élément une seule fois.
     *
     * @return array<string, array<mixed>>
     */
    private static function usedBy(\eqLogic $eqLogic): array
    {
        $own = ['eqLogic' => [(int) $eqLogic->getId() => true], 'cmd' => []];
        $cmds = $eqLogic->getCmd();
        foreach ($cmds as $cmd) {
            $own['cmd'][(int) $cmd->getId()] = true;
        }
        $result = [];
        $seen = [];
        $add = static function (array $used) use (&$result, &$seen, $own): void {
            foreach ($used as $type => $items) {
                $result[$type] ??= [];
                if ($type === 'plugin') {
                    foreach ((array) $items as $plugin => $refs) {
                        $result['plugin'][$plugin] = [...($result['plugin'][$plugin] ?? []), ...(array) $refs];
                    }
                    continue;
                }
                foreach ((array) $items as $item) {
                    $id = is_object($item) && method_exists($item, 'getId') ? (int) $item->getId() : null;
                    if ($id !== null && (isset($own[$type][$id]) || isset($seen[$type][$id]))) {
                        continue;
                    }
                    if ($id !== null) {
                        $seen[$type][$id] = true;
                    }
                    $result[$type][] = $item;
                }
            }
        };
        $add((array) $eqLogic->getUsedBy());
        foreach ($cmds as $cmd) {
            $add((array) $cmd->getUsedBy());
        }
        return $result;
    }

    public function replacePreview(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        [$target, $source, $body] = self::replaceInput($request, $args, false);
        $targetCmds = $target->getCmd();
        $requested = isset($body['cmds']) ? self::replaceCmds($body['cmds'], $source, $target) : null;
        $matches = [];
        $used = [];
        if ($source !== null) {
            foreach ($source->getCmd() as $cmd) {
                $match = self::matchCmd($cmd, $targetCmds, $requested);
                $matches[] = [
                    'sourceId' => (int) $cmd->getId(), 'sourceName' => (string) $cmd->getName(), 'type' => (string) $cmd->getType(),
                    'targetId' => $match[0], 'matchedBy' => $match[1],
                ];
                if ($match[0] !== null) {
                    $used[] = $cmd;
                }
            }
        } else {
            foreach ((array) $requested as $sourceId => $targetId) {
                $matches[] = ['sourceId' => $sourceId, 'sourceName' => null, 'type' => null, 'targetId' => $targetId, 'matchedBy' => 'request'];
            }
        }
        return Json::respond($response, [
            'cmds' => $matches,
            'deadCmds' => $source === null ? self::deadCmds() : [],
            'targetCmds' => array_map(static fn (\cmd $cmd): array => [
                'id' => (int) $cmd->getId(), 'name' => (string) $cmd->getName(), 'type' => (string) $cmd->getType(),
                'genericType' => $cmd->getGeneric_type() ?: null,
            ], $targetCmds),
            'impacts' => $source === null ? [] : self::replaceImpacts($source, $used, !empty($body['copyHistory'])),
        ]);
    }

    public function replace(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        [$target, $source, $body] = self::replaceInput($request, $args, true);
        $cmds = self::replaceCmds($body['cmds'], $source, $target);
        if ($source === null) {
            foreach ($cmds as $sourceId => $targetId) {
                \jeedom::replaceTag(['#' . $sourceId . '#' => '#' . $targetId . '#']);
            }
            return Json::respond($response, ['eqLogics' => 0, 'cmds' => count($cmds)]);
        }
        $flag = static fn (string $key): string => !empty($body[$key]) ? 'true' : 'false';
        $result = \jeedom::massReplace([
            'mode' => 'replace',
            'copyEqProperties' => $flag('copyConfiguration'),
            'copyCmdProperties' => $flag('copyConfiguration'),
            'copyCmdHistory' => $flag('copyHistory'),
            'hideEqs' => $flag('retireSource'),
            'disableEqs' => $flag('retireSource'),
            'removeCmdHistory' => 'false',
        ], [(int) $source->getId() => (int) $target->getId()], $cmds);
        return Json::respond($response, ['eqLogics' => (int) $result['eqlogics'], 'cmds' => (int) $result['cmds']]);
    }

    /** @return array{\eqLogic, ?\eqLogic, array<string, mixed>} cible, ancien équipement s'il existe encore, corps */
    private static function replaceInput(Request $request, array $args, bool $cmdsRequired): array
    {
        $user = $request->getAttribute('user');
        $target = self::find($args['id'], $user, 'w');
        $body = Json::body($request);
        $unknown = array_diff(array_keys($body), ['sourceId', 'cmds', 'copyConfiguration', 'copyHistory', 'retireSource']);
        if ($unknown !== []) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu.');
        }
        $source = null;
        if (array_key_exists('sourceId', $body)) {
            $sourceId = $body['sourceId'];
            if (!is_int($sourceId) || $sourceId <= 0 || $sourceId === (int) $target->getId()) {
                throw ApiError::field(422, 'validation_error', 'sourceId', 'Un équipement différent de la cible est attendu.');
            }
            $source = \eqLogic::byId($sourceId);
            if (!is_object($source)) {
                throw ApiError::field(422, 'validation_error', 'sourceId', 'Équipement inconnu : sans sourceId pour un équipement supprimé.');
            }
            if (!$source->hasRight('w', $user)) {
                throw new ApiError(403, 'forbidden', 'Droits insuffisants sur l\'ancien équipement.');
            }
        }
        foreach (['copyConfiguration', 'copyHistory', 'retireSource'] as $flag) {
            if (array_key_exists($flag, $body) && !is_bool($body[$flag])) {
                throw ApiError::field(422, 'validation_error', $flag, 'Un booléen est attendu.');
            }
            if ($source === null && !empty($body[$flag])) {
                throw ApiError::field(422, 'validation_error', $flag, 'Option possible seulement avec sourceId.');
            }
        }
        if ($cmdsRequired && !isset($body['cmds'])) {
            throw ApiError::field(422, 'validation_error', 'cmds', 'La correspondance des commandes est obligatoire.');
        }
        return [$target, $source, $body];
    }

    /** Vérifie la correspondance demandée ; renvoie [id source => id cible]. */
    private static function replaceCmds(mixed $cmds, ?\eqLogic $source, \eqLogic $target): array
    {
        if (!is_array($cmds) || ($cmds !== [] && array_is_list($cmds))) {
            throw ApiError::field(422, 'validation_error', 'cmds', 'Un objet JSON (commande source → commande cible) est attendu.');
        }
        $return = [];
        foreach ($cmds as $sourceId => $targetId) {
            $field = 'cmds.' . $sourceId;
            if (!ctype_digit((string) $sourceId) || (int) $sourceId <= 0) {
                throw ApiError::field(422, 'validation_error', $field, 'Identifiant de commande attendu.');
            }
            $sourceCmd = \cmd::byId((int) $sourceId);
            $targetCmd = is_int($targetId) ? \cmd::byId($targetId) : null;
            if (!is_object($targetCmd) || (int) $targetCmd->getEqLogic_id() !== (int) $target->getId()) {
                throw ApiError::field(422, 'validation_error', $field, 'Commande inconnue sur l\'équipement cible.');
            }
            if ($source === null) {
                if (is_object($sourceCmd)) {
                    throw ApiError::field(422, 'validation_error', $field, 'Cette commande existe encore : donnez sourceId.');
                }
            } else {
                if (!is_object($sourceCmd) || (int) $sourceCmd->getEqLogic_id() !== (int) $source->getId()) {
                    throw ApiError::field(422, 'validation_error', $field, 'Commande inconnue sur l\'ancien équipement.');
                }
                if ($sourceCmd->getType() !== $targetCmd->getType()) {
                    throw ApiError::field(422, 'validation_error', $field, 'Les deux commandes doivent être du même type (info ou action).');
                }
            }
            $return[(int) $sourceId] = $targetId;
        }
        return $return;
    }

    /** Commandes supprimées encore référencées (cmd::getDeadCmd), regroupées par identifiant. */
    private static function deadCmds(): array
    {
        $found = [];
        foreach (\cmd::getDeadCmd() as $group) {
            foreach ((array) ($group['cmd'] ?? []) as $dead) {
                preg_match_all('/#(\d+)#/', (string) ($dead['who'] ?? ''), $matches);
                foreach (array_unique($matches[1]) as $id) {
                    if (is_object(\cmd::byId((int) $id))) {
                        continue;
                    }
                    $found[(int) $id][] = [
                        'category' => (string) ($group['name'] ?? ''),
                        'detail' => strip_tags((string) ($dead['detail'] ?? '')),
                        'help' => strip_tags((string) ($dead['help'] ?? '')),
                    ];
                }
            }
        }
        ksort($found);
        $return = [];
        foreach ($found as $id => $usedIn) {
            $return[] = ['id' => $id, 'usedIn' => $usedIn];
        }
        return $return;
    }

    /** @return array{?int, ?string} identifiant de la commande cible et règle qui l'a trouvée */
    private static function matchCmd(\cmd $cmd, array $targetCmds, ?array $requested): array
    {
        if ($requested !== null) {
            return isset($requested[(int) $cmd->getId()]) ? [$requested[(int) $cmd->getId()], 'request'] : [null, null];
        }
        $candidates = array_values(array_filter($targetCmds, static fn (\cmd $t): bool => $t->getType() === $cmd->getType()));
        $rules = [
            'genericType' => static fn (\cmd $t): bool => (string) $cmd->getGeneric_type() !== '' && $t->getGeneric_type() === $cmd->getGeneric_type(),
            'name' => static fn (\cmd $t): bool => mb_strtolower(trim((string) $t->getName())) === mb_strtolower(trim((string) $cmd->getName())),
            'logicalId' => static fn (\cmd $t): bool => (string) $cmd->getLogicalId() !== '' && $t->getLogicalId() === $cmd->getLogicalId(),
        ];
        foreach ($rules as $rule => $test) {
            $found = array_values(array_filter($candidates, $test));
            if (count($found) === 1) {
                return [(int) $found[0]->getId(), $rule];
            }
        }
        return [null, null];
    }

    /** Éléments qui font référence à l'équipement ou aux commandes remplacées. */
    private static function replaceImpacts(\eqLogic $source, array $cmds, bool $withHistory): array
    {
        $types = ['scenario' => 'scenario', 'view' => 'view', 'plan' => 'plan', 'plan3d' => 'plan3d', 'eqLogic' => 'eqLogic', 'cmd' => 'cmd', 'interactDef' => 'interact'];
        $found = [];
        foreach ([$source, ...$cmds] as $item) {
            foreach ((array) $item->getUsedBy() as $key => $items) {
                if (!isset($types[$key])) {
                    continue;
                }
                foreach ((array) $items as $used) {
                    if (is_object($used) && method_exists($used, 'getId')) {
                        $name = method_exists($used, 'getHumanName') ? $used->getHumanName() : (method_exists($used, 'getName') ? $used->getName() : '');
                        $found[$types[$key]][(int) $used->getId()] = ['id' => (int) $used->getId(), 'name' => (string) $name];
                    }
                }
            }
        }
        if ($withHistory) {
            foreach ($cmds as $cmd) {
                if ($cmd->getType() === 'info' && (int) $cmd->getIsHistorized() === 1) {
                    $found['history'][(int) $cmd->getId()] = ['id' => (int) $cmd->getId(), 'name' => (string) $cmd->getName()];
                }
            }
        }
        $return = [];
        foreach ($found as $type => $items) {
            $return[] = ['type' => $type, 'count' => count($items), 'items' => array_values($items)];
        }
        return $return;
    }

    /** Exécute l'action pour chaque identifiant ; un échec n'arrête pas les autres. */
    public function uploadImage(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $user = $request->getAttribute('user');
        $eqLogic = self::find($args['id'], $user, 'w');
        ObjectsController::withUploadedImage($request, static fn (string $path, string $extension) => $eqLogic->saveImage($path, $extension));
        return Json::respond($response, self::toArray(self::reload($eqLogic), $user, false));
    }

    public function deleteImage(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $eqLogic = self::find($args['id'], $request->getAttribute('user'), 'w');
        $eqLogic->removeImage();
        // removeImage ne vide pas la configuration `image::*` : on l'efface ici, comme la spec le prévoit.
        $eqLogic = self::reload($eqLogic);
        $eqLogic->setConfiguration('image::type', '');
        $eqLogic->setConfiguration('image::sha512', '');
        $eqLogic->save(true);
        return $response->withStatus(204);
    }

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

    public static function find(string $id, \user $user, string $right): \eqLogic
    {
        $eqLogic = ctype_digit($id) ? \eqLogic::byId((int) $id) : null;
        if (!is_object($eqLogic)) {
            throw new ApiError(404, 'eqlogic_not_found', 'Équipement inconnu.');
        }
        if (!$eqLogic->hasRight($right, $user)) {
            throw new ApiError(403, 'forbidden', 'Droits insuffisants sur cet équipement.');
        }
        return $eqLogic;
    }

    private static function reload(\eqLogic $eqLogic): \eqLogic
    {
        $fresh = \eqLogic::byId($eqLogic->getId());
        return is_object($fresh) ? $fresh : $eqLogic;
    }

    private static function remove(\eqLogic $eqLogic): void
    {
        try {
            $removed = $eqLogic->remove();
        } catch (\Exception $e) {
            throw new ApiError(409, 'eqlogic_remove_refused', $e->getMessage());
        }
        if ($removed === false || is_object(\eqLogic::byId($eqLogic->getId()))) {
            throw new ApiError(409, 'eqlogic_remove_refused', 'Le plugin a refusé la suppression.');
        }
    }

    /** Valide puis enregistre les champs modifiables, comme l'ajax save via utils::a2o. */
    private static function apply(\eqLogic $eqLogic, array $body): void
    {
        if ($unknown = array_diff(array_keys($body), self::FIELDS)) {
            throw ApiError::field(422, 'validation_error', (string) reset($unknown), 'Champ inconnu ou non modifiable.');
        }
        unset($body['eqType_name']);
        $objectId = array_key_exists('object_id', $body) ? $body['object_id'] : self::objectId($eqLogic->getObject_id());
        if (array_key_exists('object_id', $body) && $objectId !== null
            && (!is_int($objectId) || !is_object(\jeeObject::byId($objectId)))) {
            throw ApiError::field(422, 'validation_error', 'object_id', 'Objet inconnu.');
        }
        if (array_key_exists('name', $body)) {
            $name = is_string($body['name']) ? trim($body['name']) : '';
            if ($name === '' || mb_strlen($name) > 127) {
                throw ApiError::field(422, 'validation_error', 'name', 'Le nom est obligatoire (127 caractères au plus).');
            }
            $body['name'] = $name;
        }
        if (array_key_exists('name', $body) || array_key_exists('object_id', $body)) {
            self::checkNameFree($body['name'] ?? (string) $eqLogic->getName(), $objectId, $eqLogic->getId());
        }
        foreach (['isEnable', 'isVisible'] as $field) {
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
        if (array_key_exists('timeout', $body) && $body['timeout'] !== null && (!is_int($body['timeout']) || $body['timeout'] < 1)) {
            throw ApiError::field(422, 'validation_error', 'timeout', 'Un nombre de minutes (1 ou plus) ou null est attendu.');
        }
        foreach (['logicalId' => 127, 'generic_type' => 255, 'comment' => null] as $field => $max) {
            if (array_key_exists($field, $body) && $body[$field] !== null
                && (!is_string($body[$field]) || $max !== null && mb_strlen($body[$field]) > $max)) {
                throw ApiError::field(422, 'validation_error', $field, 'Un texte' . ($max !== null ? " de {$max} caractères au plus" : '') . ' ou null est attendu.');
            }
        }
        if (array_key_exists('tags', $body)) {
            if (!is_array($body['tags']) || !array_is_list($body['tags']) || array_filter($body['tags'], static fn ($tag) => !is_string($tag) || str_contains($tag, ',')) !== []) {
                throw ApiError::field(422, 'validation_error', 'tags', 'Une liste de textes sans virgule est attendue.');
            }
            $body['tags'] = implode(',', array_filter(array_map('trim', $body['tags']), static fn ($tag) => $tag !== ''));
        }
        foreach (['category', 'configuration', 'display'] as $field) {
            if (array_key_exists($field, $body) && (!is_array($body[$field]) || array_is_list($body[$field]) && $body[$field] !== [])) {
                throw ApiError::field(422, 'validation_error', $field, 'Un objet JSON est attendu.');
            }
        }
        $creating = !$eqLogic->getId();
        if ($creating || isset($body['configuration'])) {
            $settings = PluginSettings::validateEquipment($eqLogic->getEqType_name(), $body['configuration'] ?? [], $creating);
            if ($settings !== []) $body['configuration'] = $settings;
        }
        if (array_key_exists('category', $body)) {
            foreach ($body['category'] as $key => $value) {
                if (!is_bool($value)) {
                    throw ApiError::field(422, 'validation_error', 'category.' . $key, 'Un booléen est attendu.');
                }
                $body['category'][$key] = (int) $value;
            }
        }
        \utils::a2o($eqLogic, \jeedom::fromHumanReadable($body));
        try {
            $eqLogic->save();
        } catch (\Exception $e) {
            throw new ApiError(422, 'validation_error', $e->getMessage());
        }
    }

    /** Le nom est unique par objet (index unique name + object_id de la table eqLogic). */
    private static function checkNameFree(string $name, ?int $objectId, mixed $selfId): void
    {
        if ($objectId === null) {
            return;
        }
        foreach ((array) \eqLogic::byObjectId($objectId, false, false) as $other) {
            if (is_object($other) && mb_strtolower((string) $other->getName()) === mb_strtolower($name) && (int) $other->getId() !== (int) $selfId) {
                throw ApiError::field(409, 'eqlogic_name_taken', 'name', 'Un équipement porte déjà ce nom dans cet objet.');
            }
        }
    }

    /** Convertit un équipement du core au schéma `EqLogic` (types JSON, références lisibles). */
    private static function toArray(\eqLogic $eqLogic, \user $user, bool $withCmds): array
    {
        $raw = \jeedom::toHumanReadable(\utils::o2a($eqLogic));
        $configuration = is_array($raw['configuration'] ?? null) ? $raw['configuration'] : [];
        $data = [
            'id' => (int) $raw['id'],
            'name' => (string) $raw['name'],
            'eqType_name' => (string) $raw['eqType_name'],
            'logicalId' => self::nullableString($raw['logicalId'] ?? null),
            'object_id' => self::objectId($raw['object_id'] ?? null),
            'isEnable' => (bool) (int) ($raw['isEnable'] ?? 0),
            'isVisible' => (bool) (int) ($raw['isVisible'] ?? 0),
            'order' => (int) ($raw['order'] ?? 0),
            'timeout' => is_numeric($raw['timeout'] ?? null) && (int) $raw['timeout'] > 0 ? (int) $raw['timeout'] : null,
            'generic_type' => self::nullableString($raw['generic_type'] ?? null),
            'tags' => array_values(array_filter(array_map('trim', explode(',', (string) ($raw['tags'] ?? ''))), static fn ($tag) => $tag !== '')),
            'comment' => self::nullableString($raw['comment'] ?? null),
            'category' => (object) array_map(static fn ($value) => (bool) (int) $value, is_array($raw['category'] ?? null) ? $raw['category'] : []),
            'display' => (object) (is_array($raw['display'] ?? null) ? $raw['display'] : []),
            'status' => (object) self::status($eqLogic),
            'image' => isset($configuration['image::sha512'], $configuration['image::type']) && $configuration['image::sha512'] !== ''
                ? ['type' => (string) $configuration['image::type'], 'sha512' => (string) $configuration['image::sha512']]
                : null,
        ];
        // Les plugins rangent parfois des identifiants dans la configuration (décision du 05/11).
        if ($user->getProfils() === 'admin') {
            $data['configuration'] = (object) $configuration;
        }
        if ($withCmds) {
            $data['cmds'] = CmdsController::forEqLogic($eqLogic, $user);
        }
        return $data;
    }

    /** État en cache, avec les compteurs convertis en nombres. */
    private static function status(\eqLogic $eqLogic): array
    {
        $status = $eqLogic->getStatus();
        $result = [];
        foreach (is_array($status) ? $status : [] as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $result[$key] = in_array($key, ['battery', 'timeout', 'warning', 'danger'], true) && is_numeric($value) ? (int) $value : $value;
        }
        return $result;
    }

    /** Champs de la recherche libre : nom, objet, plugin, étiquettes, commentaire. */
    private static function searchFields(\eqLogic $eqLogic): array
    {
        $object = $eqLogic->getObject();
        return [$eqLogic->getName(), is_object($object) ? $object->getName() : '', $eqLogic->getEqType_name(), $eqLogic->getTags(), $eqLogic->getComment()];
    }

    private static function includes(Request $request, string $what): bool
    {
        return in_array($what, explode(',', (string) ($request->getQueryParams()['include'] ?? '')), true);
    }

    private static function optionalFlag(mixed $value): ?bool
    {
        return $value === null ? null : filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
    }

    private static function objectId(mixed $value): ?int
    {
        return is_numeric($value) && (int) $value > 0 ? (int) $value : null;
    }

    private static function nullableString(mixed $value): ?string
    {
        return $value === null || $value === '' ? null : (string) $value;
    }
}
