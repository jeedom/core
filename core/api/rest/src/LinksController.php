<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class LinksController
{
    public function get(Request $request, Response $response, array $args): Response
    {
        Auth::requireAdmin($request);
        $id = (int) $args['id'];
        $entity = !ctype_digit($args['id']) || $id < 1 ? null : match ($args['type']) {
            'object' => \jeeObject::byId($id),
            'equipment' => \eqLogic::byId($id),
            'command' => \cmd::byId($id),
            'scenario' => \scenario::byId($id),
            'variable' => \dataStore::byId($id),
            default => null,
        };
        if (!is_object($entity) || ($entity instanceof \dataStore && $entity->getType() !== 'scenario')) {
            throw new ApiError(404, 'link_target_not_found', 'Élément inconnu.');
        }

        $links = [];
        $current = self::node($entity, true);
        $add = static function (string $relation, mixed $item) use (&$links, $current): void {
            $node = self::node($item);
            if ($node === null || ($node['type'] === $current['type'] && $node['id'] === $current['id'])) {
                return;
            }
            $links[$relation . ':' . $node['type'] . ':' . $node['id']] = ['relation' => $relation, 'target' => $node];
        };

        if ($entity instanceof \jeeObject) {
            $add('parent', \jeeObject::byId($entity->getFather_id()));
            foreach ([...(array) $entity->getChild(false), ...(array) $entity->getEqLogic(false), ...(array) $entity->getScenario(false)] as $child) {
                $add('contains', $child);
            }
        } elseif ($entity instanceof \eqLogic || $entity instanceof \scenario) {
            $add('parent', $entity->getObject());
            if ($entity instanceof \eqLogic) {
                foreach ((array) $entity->getCmd() as $command) {
                    $add('contains', $command);
                }
            }
        } elseif ($entity instanceof \cmd) {
            $add('parent', $entity->getEqLogic());
        } elseif ((int) $entity->getLink_id() > 0) {
            $add('parent', \scenario::byId($entity->getLink_id()));
        }

        // Les mêmes recherches que le graphique historique, sur l'élément courant uniquement.
        $uses = $entity instanceof \dataStore ? [] : $entity->getUse();
        $usedBy = $entity instanceof \jeeObject ? [] : $entity->getUsedBy();
        foreach (['uses' => $uses, 'usedBy' => $usedBy] as $relation => $groups) {
            foreach (['object', 'eqLogic', 'cmd', 'scenario', 'dataStore'] as $type) {
                foreach ((array) ($groups[$type] ?? []) as $item) {
                    $add($relation, $item);
                }
            }
            foreach ((array) ($groups['plugin'] ?? []) as $items) {
                foreach ((array) $items as $item) {
                    $add($relation, $item);
                }
            }
        }

        return Json::respond($response, ['node' => $current, 'links' => array_values($links)]);
    }

    private static function node(mixed $item, bool $current = false): ?array
    {
        $details = match (true) {
            $item instanceof \jeeObject => ['type' => 'object', 'visible' => (bool) (int) $item->getIsVisible()],
            $item instanceof \eqLogic => [
                'type' => 'equipment', 'plugin' => $item->getEqType_name(), 'enabled' => (bool) (int) $item->getIsEnable(),
            ],
            $item instanceof \cmd => [
                'type' => 'command', 'equipmentId' => (int) $item->getEqLogic_id(),
                'commandType' => $item->getType(), 'commandSubtype' => $item->getSubType(),
            ],
            $item instanceof \scenario => [
                'type' => 'scenario', 'enabled' => (bool) (int) $item->getIsActive(), 'group' => (string) $item->getGroup(),
                'mode' => $item->getMode(),
            ],
            $item instanceof \dataStore && $item->getType() === 'scenario' => ['type' => 'variable'],
            default => null,
        };
        if ($details === null) {
            return null;
        }
        if ($current && $item instanceof \scenario) {
            $details['triggers'] = array_values(array_map('strval', (array) \jeedom::toHumanReadable($item->getTrigger())));
            $details['schedules'] = array_values(array_map('strval', (array) $item->getSchedule()));
        }
        return [
            'id' => (int) $item->getId(),
            'name' => (string) ($item instanceof \dataStore ? $item->getKey() : $item->getName()),
        ] + $details;
    }
}
