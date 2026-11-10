<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class DisplayController
{
    private const MAX_COMMANDS = 1000;

    public function list(Request $request, Response $response): Response
    {
        self::checkContext($request);
        $user = $request->getAttribute('user');
        $rows = \display::byDashboard();

        $eqLogicIds = [];
        $cmdIds = [];
        foreach ($rows as $row) {
            if ($row->getTargetType() === 'eqLogic') {
                $eqLogicIds[] = $row->getTargetId();
            } else {
                $cmdIds[] = $row->getTargetId();
            }
        }
        $cmds = [];
        foreach ((array) \cmd::byIds($cmdIds) as $cmd) {
            $cmds[$cmd->getId()] = $cmd;
            $eqLogicIds[] = (int) $cmd->getEqLogic_id();
        }
        $eqLogics = [];
        foreach (\eqLogic::byIds(array_values(array_unique($eqLogicIds))) as $eqLogic) {
            if ($eqLogic->hasRight('r', $user)) {
                $eqLogics[$eqLogic->getId()] = $eqLogic;
            }
        }

        $items = [];
        foreach ($rows as $row) {
            $id = $row->getTargetId();
            if ($row->getTargetType() === 'eqLogic') {
                if (isset($eqLogics[$id])) {
                    $items[] = $row->toArray();
                }
            } elseif (isset($cmds[$id], $eqLogics[$cmds[$id]->getEqLogic_id()])) {
                $cmd = $cmds[$id];
                $cmd->setEqLogic($eqLogics[$cmd->getEqLogic_id()]);
                if ($cmd->hasRight($user)) {
                    $items[] = $row->toArray();
                }
            }
        }
        return Json::respond($response, ['items' => $items, 'total' => count($items)]);
    }

    public function replace(Request $request, Response $response, array $args): Response
    {
        $eqLogicId = self::writableId($request, $args['id']);
        $body = Json::body($request, 1048576, preserveObjects: true);
        self::checkFields($body, ['equipment', 'commands'], '');
        if (!array_key_exists('equipment', $body)) {
            throw ApiError::field(422, 'validation_error', 'equipment', 'Ce champ est obligatoire.');
        }
        $equipment = $body['equipment'] === null ? null : self::settings($body['equipment'], 'equipment');

        $commands = $body['commands'] ?? null;
        if (!is_array($commands) || count($commands) > self::MAX_COMMANDS) {
            throw ApiError::field(422, 'validation_error', 'commands', 'Une liste de 1 000 commandes au plus est attendue.');
        }
        $settings = [];
        foreach ($commands as $index => $command) {
            $field = 'commands.' . $index;
            $id = $command->id ?? null;
            if (!is_int($id) || $id < 1 || $id > 2147483647 || isset($settings[$id])) {
                throw ApiError::field(422, 'validation_error', $field . '.id', 'Un identifiant de commande entier positif et distinct est attendu.');
            }
            unset($command->id);
            $settings[$id] = self::settings($command, $field, true);
        }
        self::save($eqLogicId, $equipment, $settings);
        return $response->withStatus(204);
    }

    public function reset(Request $request, Response $response, array $args): Response
    {
        self::save(self::writableId($request, $args['id']), null, []);
        return $response->withStatus(204);
    }

    private static function writableId(Request $request, string $id): int
    {
        Auth::requireAdmin($request);
        self::checkContext($request);
        return (int) EqLogicsController::find($id, $request->getAttribute('user'), 'w')->getId();
    }

    private static function checkContext(Request $request): void
    {
        $query = $request->getQueryParams();
        if (($query['context'] ?? \display::DASHBOARD) !== \display::DASHBOARD) {
            throw ApiError::field(422, 'validation_error', 'context', 'Seul le contexte dashboard est pris en charge.');
        }
        if (!in_array($query['context_id'] ?? \display::DASHBOARD_ID, [\display::DASHBOARD_ID, (string) \display::DASHBOARD_ID], true)) {
            throw ApiError::field(422, 'validation_error', 'context_id', 'Le contexte dashboard utilise l’identifiant 0.');
        }
    }

    private static function checkFields(array $data, array $allowed, string $prefix): void
    {
        $unknown = array_diff(array_keys($data), $allowed);
        if ($unknown !== []) {
            throw ApiError::field(422, 'validation_error', $prefix . reset($unknown), 'Champ inconnu.');
        }
    }

    private static function settings(mixed $input, string $field, bool $command = false): array
    {
        if (!$input instanceof \stdClass) {
            throw ApiError::field(422, 'validation_error', $field, 'Un objet JSON est attendu.');
        }
        $data = (array) $input;
        $allowed = ['template', 'template_parameters'];
        if ($command) {
            $allowed = array_merge($allowed, ['sort_order', 'visible']);
        }
        self::checkFields($data, $allowed, $field . '.');

        $template = $data['template'] ?? null;
        if ($template !== null && (!is_string($template) || trim($template) === '' || mb_strlen($template) > 127)) {
            throw ApiError::field(422, 'validation_error', $field . '.template', 'Une clé de rendu de 1 à 127 caractères est attendue.');
        }
        if (array_key_exists('template_parameters', $data) && !$data['template_parameters'] instanceof \stdClass) {
            throw ApiError::field(422, 'validation_error', $field . '.template_parameters', 'Un objet JSON est attendu.');
        }
        $order = $data['sort_order'] ?? null;
        if ($order !== null && (!is_int($order) || $order < 0 || $order > 2147483647)) {
            throw ApiError::field(422, 'validation_error', $field . '.sort_order', 'Un entier positif ou nul est attendu.');
        }
        if (isset($data['visible']) && !is_bool($data['visible'])) {
            throw ApiError::field(422, 'validation_error', $field . '.visible', 'Un booléen est attendu.');
        }
        return $data;
    }

    private static function save(int $eqLogicId, ?array $equipment, array $commands): void
    {
        try {
            \display::replaceDashboardTile($eqLogicId, $equipment, $commands);
        } catch (\OutOfBoundsException $e) {
            throw new ApiError(404, 'eqlogic_not_found', $e->getMessage());
        } catch (\DomainException $e) {
            throw ApiError::field(422, 'validation_error', 'commands', $e->getMessage());
        } catch (\JsonException) {
            throw ApiError::field(422, 'validation_error', 'template_parameters', 'Les valeurs doivent pouvoir être représentées en JSON.');
        }
    }
}
