<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/**
 * Décide si un événement du core peut être envoyé à un utilisateur : droits de lecture
 * sur l'élément concerné, puis abonnements demandés (types, objets, équipements,
 * commandes, scénarios). Un événement sur un élément non lisible n'est jamais envoyé.
 */
final class EventFilter
{
    /** Événements réservés aux administrateurs (messages et mises à jour). */
    private const ADMIN_ONLY = ['message::refreshMessageNumber', 'update::refreshUpdateNumber', 'notify'];

    /** @var array<string, ?array{object: ?int, eqLogic: ?int, cmd: ?int, scenario: ?int}> cache par élément, pour la durée du flux */
    private array $known = [];

    /**
     * @param list<string> $types types demandés, tous si vide
     * @param array<string, list<int>> $filters objectId, eqLogicId, cmdId, scenarioId
     */
    public function __construct(
        private readonly \user $user,
        private readonly array $types,
        private readonly array $filters,
    ) {
    }

    public function accepts(string $type, mixed $data): bool
    {
        if (!in_array($type, EventsController::TYPES, true)) {
            return false;
        }
        if ($this->types !== [] && !in_array($type, $this->types, true)) {
            return false;
        }
        if (in_array($type, self::ADMIN_ONLY, true)) {
            return $this->user->getProfils() === 'admin';
        }
        $target = match ($type) {
            'cmd::update' => $this->cmd($data['cmd_id'] ?? null),
            'eqLogic::update' => $this->eqLogic($data['eqLogic_id'] ?? null),
            'scenario::update' => $this->scenario($data['scenario_id'] ?? null),
            'jeeObject::summary::update' => ($data['object_id'] ?? null) === 'global' ? 'global' : $this->object($data['object_id'] ?? null),
            default => 'global',
        };
        if ($target === null) {
            return false;
        }
        if ($target === 'global') {
            return true;
        }
        $keys = ['objectId' => 'object', 'eqLogicId' => 'eqLogic', 'cmdId' => 'cmd', 'scenarioId' => 'scenario'];
        foreach ($keys as $filter => $key) {
            if (($this->filters[$filter] ?? []) !== [] && !in_array($target[$key], $this->filters[$filter], true)) {
                return false;
            }
        }
        return true;
    }

    private function cmd(mixed $id): ?array
    {
        return $this->remember('cmd', $id, function (int $id): ?array {
            $cmd = \cmd::byId($id);
            $eqLogic = is_object($cmd) ? $cmd->getEqLogic() : null;
            if (!is_object($eqLogic) || !$cmd->hasRight($this->user)) {
                return null;
            }
            return ['object' => self::int($eqLogic->getObject_id()), 'eqLogic' => (int) $eqLogic->getId(), 'cmd' => $id, 'scenario' => null];
        });
    }

    private function eqLogic(mixed $id): ?array
    {
        return $this->remember('eqLogic', $id, function (int $id): ?array {
            $eqLogic = \eqLogic::byId($id);
            if (!is_object($eqLogic) || !$eqLogic->hasRight('r', $this->user)) {
                return null;
            }
            return ['object' => self::int($eqLogic->getObject_id()), 'eqLogic' => $id, 'cmd' => null, 'scenario' => null];
        });
    }

    private function scenario(mixed $id): ?array
    {
        return $this->remember('scenario', $id, function (int $id): ?array {
            $scenario = \scenario::byId($id);
            if (!is_object($scenario) || !$scenario->hasRight('r', $this->user)) {
                return null;
            }
            return ['object' => self::int($scenario->getObject_id()), 'eqLogic' => null, 'cmd' => null, 'scenario' => $id];
        });
    }

    private function object(mixed $id): ?array
    {
        return $this->remember('object', $id, function (int $id): ?array {
            $object = \jeeObject::byId($id);
            if (!is_object($object) || !$object->hasRight('r', $this->user)) {
                return null;
            }
            return ['object' => $id, 'eqLogic' => null, 'cmd' => null, 'scenario' => null];
        });
    }

    private function remember(string $kind, mixed $id, \Closure $load): ?array
    {
        $id = self::int($id);
        if ($id === null) {
            return null;
        }
        $key = $kind . $id;
        if (!array_key_exists($key, $this->known)) {
            $this->known[$key] = $load($id);
        }
        return $this->known[$key];
    }

    private static function int(mixed $value): ?int
    {
        return is_numeric($value) && (int) $value > 0 ? (int) $value : null;
    }
}
