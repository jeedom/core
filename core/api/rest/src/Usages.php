<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Convertit le résultat de `getUsedBy()` (équipement ou commande) au schéma `EqLogicUsages`. */
final class Usages
{
    public static function format(array $used): array
    {
        $refs = static fn (array $items): array => array_values(array_filter(array_map(self::ref(...), $items)));
        $plugins = [];
        foreach ((array) ($used['plugin'] ?? []) as $plugin => $items) {
            $plugins[(string) $plugin] = $refs((array) $items);
        }
        return [
            'cmds' => $refs((array) ($used['cmd'] ?? [])),
            'eqLogics' => $refs((array) ($used['eqLogic'] ?? [])),
            'scenarios' => $refs((array) ($used['scenario'] ?? [])),
            'plans' => $refs([...(array) ($used['plan'] ?? []), ...(array) ($used['plan3d'] ?? [])]),
            'views' => $refs((array) ($used['view'] ?? [])),
            'interactions' => $refs((array) ($used['interactDef'] ?? [])),
            'plugins' => (object) $plugins,
        ];
    }

    private static function ref(mixed $item): ?array
    {
        if (is_object($item) && method_exists($item, 'getId')) {
            $name = method_exists($item, 'getHumanName') ? $item->getHumanName() : (method_exists($item, 'getName') ? $item->getName() : '');
            return ['id' => (int) $item->getId(), 'name' => (string) $name];
        }
        if (is_array($item) && isset($item['id'])) {
            return ['id' => (int) $item['id'], 'name' => (string) ($item['humanName'] ?? $item['name'] ?? '')];
        }
        return null;
    }
}
