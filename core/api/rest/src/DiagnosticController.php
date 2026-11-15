<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DiagnosticController
{
    public function references(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        return Json::respond($response, ['items' => self::deadCommands()]);
    }

    public function rules(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $items = [];
        foreach (\eqLogic::byTimeout(1) as $equipment) {
            $target = self::target('equipment', (int) $equipment->getId());
            $items[] = self::rule('timeout', $equipment->getHumanName(), $target, minutes: (int) $equipment->getTimeout());
        }
        foreach (\cmd::all() as $cmd) {
            $target = self::target('command', (int) $cmd->getId(), (int) $cmd->getEqLogic_id());
            $label = null;
            if ($cmd->getType() === 'info') {
                foreach (['warning', 'danger'] as $level) {
                    $condition = (string) $cmd->getAlert($level . 'if', '');
                    if ($condition !== '') {
                        $items[] = self::rule('alert', $label ??= $cmd->getHumanName(), $target, $condition, (int) $cmd->getAlert($level . 'during', 0), level: $level);
                    }
                }
                $actions = self::actions($cmd, 'actionCheckCmd');
                if ($actions !== []) {
                    $condition = '#value# ' . $cmd->getConfiguration('jeedomCheckCmdOperator') . ' ' . $cmd->getConfiguration('jeedomCheckCmdTest');
                    $items[] = self::rule('stateAction', $label ??= $cmd->getHumanName(), $target, $condition, (int) $cmd->getConfiguration('jeedomCheckCmdTime', 0), $actions);
                }
                continue;
            }
            foreach (['beforeAction' => 'jeedomPreExecCmd', 'afterAction' => 'jeedomPostExecCmd'] as $kind => $key) {
                $actions = self::actions($cmd, $key);
                if ($actions !== []) {
                    $items[] = self::rule($kind, $label ??= $cmd->getHumanName(), $target, actions: $actions);
                }
            }
            $codeRequired = trim((string) $cmd->getConfiguration('actionCodeAccess', '')) !== '';
            if ($cmd->getConfiguration('actionConfirm') || $codeRequired) {
                $items[] = self::rule('confirmation', $label ??= $cmd->getHumanName(), $target, codeRequired: $codeRequired);
            }
        }
        usort($items, static fn (array $a, array $b): int => strnatcasecmp($a['name'], $b['name']) ?: strcmp($a['kind'], $b['kind']));
        return Json::respond($response, ['items' => $items]);
    }

    public function expression(Request $request, Response $response): Response
    {
        Auth::requireAdmin($request);
        $body = Json::body($request);
        $source = $body['expression'] ?? null;
        if (!is_string($source) || trim($source) === '' || strlen($source) > 4096) {
            throw ApiError::field(422, 'validation_error', 'expression', 'Une expression de 1 à 4 096 octets est attendue.');
        }
        return Json::respond($response, DiagnosticExpression::test(trim($source)));
    }

    /** Commandes manquantes et leurs utilisations. Partagé avec l’assistant de remplacement. */
    public static function deadCommands(bool $includeTargets = true): array
    {
        $found = [];
        $existing = [];
        foreach (\cmd::getDeadCmd() as $kind => $group) {
            foreach ((array) ($group['cmd'] ?? []) as $usage) {
                preg_match_all('/#([1-9]\d*)#/', (string) ($usage['who'] ?? ''), $matches);
                $detail = (string) ($usage['detail'] ?? '');
                $entry = [
                    'category' => (string) ($group['name'] ?? ''),
                    'detail' => self::plainText($detail),
                    'help' => self::plainText((string) ($usage['help'] ?? '')),
                ];
                if ($includeTargets) {
                    $entry['target'] = self::referenceTarget((string) $kind, $usage, $detail);
                }
                foreach (array_unique($matches[1]) as $id) {
                    $existing[$id] ??= is_object(\cmd::byId($id));
                    if (!$existing[$id]) {
                        $found[(int) $id][serialize($entry)] = $entry;
                    }
                }
            }
        }
        ksort($found);
        $items = [];
        foreach ($found as $id => $usedIn) {
            $items[] = ['id' => $id, 'usedIn' => array_values($usedIn)];
        }
        return $items;
    }

    private static function referenceTarget(string $kind, array $usage, string $detail): ?array
    {
        if ($kind === 'cmd' && isset($usage['cmdId'], $usage['eqLogicId'])) {
            return self::target('command', (int) $usage['cmdId'], (int) $usage['eqLogicId']);
        }
        if ($kind === 'jeeObject' && isset($usage['objectId'])) {
            return self::target('object', (int) $usage['objectId']);
        }
        // Les anciens détecteurs de scénario et de plugins fournissent un lien HTML.
        // Ne transmettre qu’une destination interne typée, sans URL ni HTML issu d’un plugin.
        if (!preg_match('~href=["\x27](/?index\.php\?[^"\x27]+)["\x27]~', $detail, $match)) {
            return null;
        }
        parse_str((string) parse_url(html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5), PHP_URL_QUERY), $query);
        $id = filter_var($query['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) {
            return null;
        }
        if ($kind === 'scenario' && ($query['p'] ?? null) === 'scenario') {
            return self::target('scenario', $id);
        }
        if (($query['m'] ?? null) === $kind && ($query['p'] ?? null) === $kind) {
            $equipment = \eqLogic::byId($id);
            if (is_object($equipment) && $equipment->getEqType_name() === $kind) {
                return self::target('equipment', $id);
            }
        }
        return null;
    }

    private static function target(string $type, int $id, ?int $equipmentId = null): array
    {
        return ['type' => $type, 'id' => $id, 'equipmentId' => $equipmentId];
    }

    private static function plainText(string $value): string
    {
        return trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    private static function actions(\cmd $cmd, string $key): array
    {
        $names = [];
        foreach ((array) $cmd->getConfiguration($key, []) as $action) {
            $reference = $action['cmd'] ?? null;
            if (is_string($reference) && trim($reference) !== '') {
                $names[] = \jeedom::toHumanReadable($reference);
            }
        }
        return $names;
    }

    private static function rule(string $kind, string $name, array $target, ?string $condition = null, int $minutes = 0, array $actions = [], ?string $level = null, bool $codeRequired = false): array
    {
        return [
            'kind' => $kind,
            'name' => $name,
            'target' => $target,
            'condition' => $condition === null ? null : \jeedom::toHumanReadable($condition),
            'minutes' => max(0, $minutes),
            'actions' => $actions,
            'level' => $level,
            'codeRequired' => $codeRequired,
        ];
    }
}
