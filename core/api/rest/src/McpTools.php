<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;
use stdClass;

/** Catalogue fermé : les arguments du client ne choisissent jamais une route ou un contrôleur. */
final class McpTools
{
    private const PAGE_SIZE = 100;

    private const EQUIPMENT = [
        'name' => 'string', 'eqType_name' => 'string', 'logicalId' => '?string', 'object_id' => '?integer',
        'isEnable' => 'boolean', 'isVisible' => 'boolean', 'order' => 'integer', 'timeout' => '?integer',
        'generic_type' => '?string', 'tags' => 'array', 'comment' => '?string', 'category' => 'object',
        'configuration' => 'object', 'display' => 'object',
    ];
    private const COMMAND = [
        'eqLogic_id' => 'integer', 'name' => 'string', 'type' => 'string', 'subType' => 'string',
        'logicalId' => '?string', 'generic_type' => '?string', 'order' => 'integer', 'isVisible' => 'boolean',
        'isHistorized' => 'boolean', 'unite' => '?string', 'value' => '?string', 'accessCode' => '?string',
        'configuration' => 'object', 'display' => 'object', 'template' => 'object', 'alert' => 'object',
    ];
    private const SCENARIO = [
        'name' => 'string', 'group' => '?string', 'description' => '?string', 'isActive' => 'boolean',
        'isVisible' => 'boolean', 'mode' => 'string', 'schedule' => 'array', 'trigger' => 'array',
        'timeout' => '?integer', 'object_id' => '?integer', 'order' => 'integer',
        'configuration' => 'object', 'display' => 'object', 'elements' => 'scenarioElements',
    ];

    private const SCENARIO_DEFINITIONS = [
        'block' => [
            'type' => 'object', 'required' => ['type', 'subElements'],
            'properties' => [
                'id' => ['type' => 'integer'],
                'type' => ['enum' => ['action', 'if', 'for', 'while', 'at', 'in', 'code', 'comment']],
                'options' => ['type' => 'object'],
                'subElements' => ['type' => 'array', 'items' => ['$ref' => '#/$defs/part']],
            ],
        ],
        'part' => [
            'type' => 'object', 'required' => ['type', 'subtype', 'expressions'],
            'properties' => [
                'id' => ['type' => 'integer'],
                'type' => ['enum' => ['action', 'if', 'then', 'else', 'for', 'while', 'at', 'in', 'do', 'code', 'comment']],
                'subtype' => ['enum' => ['action', 'condition', 'comment']],
                'options' => ['type' => 'object'],
                'expressions' => ['type' => 'array', 'items' => ['$ref' => '#/$defs/expression']],
            ],
        ],
        'expression' => [
            'type' => 'object', 'required' => ['type'],
            'properties' => [
                'id' => ['type' => 'integer'],
                'type' => ['enum' => ['action', 'condition', 'element', 'code', 'comment']],
                'expression' => ['type' => 'string'],
                'options' => ['type' => 'object'],
                'element' => ['$ref' => '#/$defs/block'],
            ],
        ],
    ];

    /** Définition des outils et des seules opérations REST qu'ils peuvent atteindre. */
    private const TOOLS = [
        'list_objects' => ['Liste les objets accessibles.', ObjectsController::class, 'list', ['visible' => 'boolean'], [], 'localPage'],
        'get_summary' => ['Lit un résumé global ou le résumé key d’un objet id.', ObjectsController::class, 'summary', ['id' => 'id', 'key' => 'string'], []],
        'list_equipments' => ['Recherche les équipements par objet, plugin (eqType) ou nom (q).', EqLogicsController::class, 'list', ['objectId' => 'integer', 'eqType' => 'string', 'q' => 'string', 'enabled' => 'boolean', 'visible' => 'boolean'], [], 'page'],
        'get_equipment' => ['Lit un équipement.', EqLogicsController::class, 'get', ['id' => 'id'], ['id']],
        'list_commands' => ['Recherche les commandes par équipement, type, type générique ou nom.', CmdsController::class, 'list', ['eqLogicId' => 'id', 'type' => 'string', 'generic_type' => 'string', 'q' => 'string', 'isHistorized' => 'boolean'], [], 'page'],
        'get_command_value' => ['Lit la valeur d’une commande info ou la dernière valeur d’une commande action.', CmdsController::class, 'value', ['id' => 'id'], ['id']],
        'get_command_history' => ['Lit jusqu’à 5 000 points sur sept jours. Utiliser des dates RFC 3339 et un regroupement comme avg::hour si nécessaire.', CmdsController::class, 'history', ['id' => 'id', 'start' => 'string', 'end' => 'string', 'grouping' => 'string', 'derive' => 'boolean'], ['id']],
        'list_generic_types' => ['Liste les types génériques de commandes.', CmdsController::class, 'genericTypes', [], [], 'localPage'],
        'list_scenarios' => ['Liste les scénarios accessibles.', ScenariosController::class, 'list', ['objectId' => 'id', 'group' => 'string', 'q' => 'string', 'active' => 'boolean', 'visible' => 'boolean'], [], 'page'],
        'get_scenario' => ['Lit un scénario. include=elements inclut ses blocs pour un administrateur.', ScenariosController::class, 'get', ['id' => 'id', 'include' => 'string'], ['id']],
        'get_scenario_log' => ['Lit un extrait du journal d’un scénario (administrateur).', ScenariosController::class, 'logExcerpt', ['id' => 'id', 'limit' => 'limit', 'before' => 'string', 'after' => 'string'], ['id']],
        'list_variables' => ['Recherche les variables de scénarios.', VariablesController::class, 'list', ['q' => 'string'], [], 'page'],
        'list_messages' => ['Liste les notifications de Jeedom.', MessagesController::class, 'list', ['plugin' => 'string', 'level' => 'string', 'read' => 'boolean', 'q' => 'string'], [], 'page'],
        'list_plugins' => ['Liste les plugins installés.', PluginsController::class, 'list', ['enabled' => 'boolean'], [], 'localPage'],
        'get_plugin_settings_schema' => ['Lit le schéma de configuration déclaré par le plugin (administrateur).', PluginsController::class, 'getConfigurationSchema', ['id' => 'string'], ['id']],
        'execute_command' => ['Exécute une commande action. Si une confirmation est requise, demander l’accord explicite pour cet équipement et cette action avant d’envoyer confirm=true. Si un code est requis, le demander pour cette action à chaque fois. Ne jamais le conserver ou le réutiliser.', CmdsController::class, 'exec', ['id' => 'id', 'options' => 'object', 'confirm' => 'boolean', 'codeAccess' => 'string'], ['id'], 'write'],
        'start_scenario' => ['Lance un scénario existant.', ScenariosController::class, 'start', ['id' => 'id', 'tags' => 'object'], ['id'], 'write'],
        'stop_scenario' => ['Arrête un scénario.', ScenariosController::class, 'stop', ['id' => 'id'], ['id'], 'write'],
        'create_equipment' => ['Crée un équipement (administrateur). Lire le schéma du plugin pour sa configuration.', EqLogicsController::class, 'create', self::EQUIPMENT, ['name', 'eqType_name'], 'write'],
        'update_equipment' => ['Modifie un équipement (administrateur).', EqLogicsController::class, 'update', ['id' => 'id'] + self::EQUIPMENT, ['id'], 'write'],
        'create_command' => ['Crée une commande (administrateur).', CmdsController::class, 'create', self::COMMAND, ['eqLogic_id', 'name', 'type', 'subType'], 'write'],
        'update_command' => ['Modifie une commande (administrateur).', CmdsController::class, 'update', ['id' => 'id'] + self::COMMAND, ['id'], 'write'],
        'create_scenario' => ['Crée un scénario au format natif Jeedom (administrateur). Lire un scénario avec include=elements pour voir sa structure.', ScenariosController::class, 'create', self::SCENARIO, ['name'], 'write'],
        'update_scenario' => ['Modifie ou active un scénario (administrateur).', ScenariosController::class, 'update', ['id' => 'id'] + self::SCENARIO, ['id'], 'write'],
        'set_variable' => ['Crée une variable globale avec key, ou met à jour une variable existante avec id.', VariablesController::class, 'create', ['id' => 'id', 'key' => 'string', 'value' => 'scalar'], ['value'], 'write'],
    ];

    public static function has(string $name): bool
    {
        return isset(self::TOOLS[$name]);
    }

    public static function list(): array
    {
        $tools = [];
        foreach (self::TOOLS as $name => $definition) {
            [$description, , , $fields, $required] = $definition;
            $kind = $definition[5] ?? 'read';
            $fields = self::fields($name, $fields, $kind === 'page' || $kind === 'localPage');
            $schema = [
                'type' => 'object', 'properties' => (object) array_map(self::schema(...), $fields),
                'required' => $required, 'additionalProperties' => false,
            ];
            if (isset($fields['elements'])) {
                $schema['$defs'] = self::SCENARIO_DEFINITIONS;
            }
            $tools[] = [
                'name' => $name,
                'description' => $description,
                'inputSchema' => $schema,
                'annotations' => ['readOnlyHint' => $kind !== 'write', 'destructiveHint' => $kind === 'write', 'openWorldHint' => true],
            ];
        }
        return $tools;
    }

    public static function call(Request $request, string $name, array $arguments): array
    {
        try {
            return self::dispatch($request, $name, $arguments);
        } catch (ApiError $error) {
            return self::result((object) ['error' => $error->toArray()], true);
        }
    }

    private static function dispatch(Request $request, string $name, array $arguments): array
    {
        [, $controller, $operation, $fields, $required] = self::TOOLS[$name];
        $kind = self::TOOLS[$name][5] ?? 'read';
        $paged = $kind === 'page' || $kind === 'localPage';
        $fields = self::fields($name, $fields, $paged);
        self::validate($arguments, $fields, $required);

        $id = isset($arguments['id']) ? (string) $arguments['id'] : null;
        unset($arguments['id']);
        if ($name === 'get_summary' && $id === null) {
            $operation = 'globalSummary';
        } elseif ($name === 'set_variable') {
            if (isset($arguments['key']) === ($id !== null)) {
                throw ApiError::field(422, 'validation_error', 'key', 'Renseignez key pour créer la variable ou id pour la modifier.');
            }
            $operation = $id === null ? 'create' : 'update';
        }

        $offset = 0;
        $limit = $arguments['limit'] ?? self::PAGE_SIZE;
        if ($paged) {
            $cursor = $arguments['cursor'] ?? null;
            if ($cursor !== null && !preg_match('/\A[1-9][0-9]{0,8}\z/', $cursor)) {
                throw ApiError::field(422, 'validation_error', 'cursor', 'Curseur de pagination invalide.');
            }
            $offset = (int) $cursor;
            unset($arguments['cursor'], $arguments['limit']);
            if ($kind === 'page') {
                $arguments += ['offset' => $offset, 'limit' => $limit];
            }
        }
        if ($name === 'get_scenario_log') {
            $arguments['limit'] = $limit;
        }

        $write = $kind === 'write';
        if ($write) {
            $method = $operation === 'update' ? 'PATCH' : 'POST';
            $stream = (new StreamFactory())->createStream(json_encode((object) $arguments, JSON_THROW_ON_ERROR));
            $request = $request->withMethod($method)->withQueryParams([])->withBody($stream);
        } else {
            $request = $request->withMethod('GET')->withQueryParams(array_map(self::queryValue(...), $arguments));
        }
        if ($name === 'get_command_history') {
            $request = $request->withAttribute('mcpHistoryLimit', true);
        }

        $handler = [new $controller(), $operation];
        $response = $id === null ? $handler($request, new Response()) : $handler($request, new Response(), ['id' => $id]);
        $data = json_decode((string) $response->getBody(), false, 512, JSON_THROW_ON_ERROR);
        if ($paged) {
            if ($kind === 'localPage') {
                $data->total = count($data->items);
                $data->items = array_slice($data->items, $offset, $limit);
            }
            if ($offset + count($data->items) < $data->total) {
                $data->nextCursor = (string) ($offset + count($data->items));
            }
        }
        if ($write) {
            \log::audit('REST MCP tool executed', [
                'sessionId' => $request->getAttribute('restSessionId'),
                'userId' => $request->getAttribute('user')->getId(),
                'tool' => $name,
                'resourceId' => $id ?? ($data->id ?? null),
            ]);
        }
        return self::result($data instanceof stdClass ? $data : (object) ['value' => $data]);
    }

    private static function queryValue(mixed $value): string
    {
        return is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
    }

    private static function result(stdClass $data, bool $error = false): array
    {
        return [
            'content' => [['type' => 'text', 'text' => json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]],
            'structuredContent' => $data,
            'isError' => $error,
        ];
    }

    private static function fields(string $name, array $fields, bool $paged): array
    {
        if ($name === 'update_equipment') {
            unset($fields['eqType_name']);
        } elseif ($name === 'update_command') {
            unset($fields['eqLogic_id'], $fields['type']);
        }
        return $paged ? $fields + ['cursor' => 'string', 'limit' => 'limit'] : $fields;
    }

    private static function schema(string $type): array
    {
        if ($type === 'id' || $type === 'limit') {
            return ['type' => 'integer', 'minimum' => 1] + ($type === 'limit' ? ['maximum' => self::PAGE_SIZE] : []);
        }
        if ($type === 'scalar') {
            return ['type' => ['null', 'string', 'number', 'boolean']];
        }
        if ($type === 'scenarioElements') {
            return ['type' => 'array', 'items' => ['$ref' => '#/$defs/block']];
        }
        if ($type === 'array') {
            return ['type' => 'array', 'items' => ['type' => 'string']];
        }
        return ['type' => $type[0] === '?' ? [substr($type, 1), 'null'] : $type];
    }

    private static function validate(array $arguments, array $fields, array $required): void
    {
        foreach ($required as $field) {
            if (!array_key_exists($field, $arguments)) {
                throw ApiError::field(422, 'validation_error', $field, 'Ce champ est obligatoire.');
            }
        }
        foreach ($arguments as $field => $value) {
            if (!isset($fields[$field])) {
                throw ApiError::field(422, 'validation_error', (string) $field, 'Argument inconnu.');
            }
            $type = $fields[$field];
            if ($type[0] === '?' && $value === null) {
                continue;
            }
            $valid = match (ltrim($type, '?')) {
                'id' => is_int($value) && $value > 0,
                'limit' => is_int($value) && $value > 0 && $value <= self::PAGE_SIZE,
                'integer' => is_int($value),
                'string' => is_string($value),
                'boolean' => is_bool($value),
                'object' => $value instanceof stdClass,
                'array' => is_array($value) && array_filter($value, static fn ($item) => !is_string($item)) === [],
                'scenarioElements' => is_array($value),
                'scalar' => $value === null || is_scalar($value),
            };
            if (!$valid) {
                throw ApiError::field(422, 'validation_error', (string) $field, 'Type ou valeur de l’argument incorrect.');
            }
        }
    }
}
