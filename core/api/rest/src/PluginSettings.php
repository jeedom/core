<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Configuration volontairement exposée par un plugin, dans son propre espace de noms. */
final class PluginSettings
{
    private const MAX_BYTES = 16384;
    private const PICKER_FILTERS = [
        'commandFilters' => ['type', 'subType', 'eqLogicId', 'eqType', 'generic_type', 'isHistorized'],
        'equipmentFilters' => ['eqType', 'logicalId', 'objectId', 'enabled', 'visible'],
        'scenarioFilters' => ['mode', 'group', 'objectId', 'active', 'visible'],
        'actionFilters' => ['expressions', 'scenarioActions'],
    ];

    private static function path(string $id): ?string
    {
        if (!preg_match('/\A[A-Za-z0-9_][A-Za-z0-9_-]{0,126}\z/', $id)) return null;
        $root = realpath(\plugin::getPluginPath($id));
        $path = $root === false ? false : realpath($root . '/plugin_info/ui-settings.json');
        return $path !== false && str_starts_with($path, $root . '/') && is_file($path) ? $path : null;
    }

    public static function exists(string $id): bool
    {
        try {
            return self::section($id, 'plugin') !== [];
        } catch (ApiError $e) {
            // Garde l'accès à la fenêtre pour qu'elle puisse afficher l'erreur du fichier.
            return true;
        }
    }

    public static function fields(string $id): array
    {
        $fields = self::section($id, 'plugin');
        if ($fields === []) {
            throw new ApiError(404, 'plugin_settings_not_found', 'Ce plugin ne déclare pas de configuration pour cette interface.');
        }
        return $fields;
    }

    public static function entitySchema(string $id): array
    {
        $declaration = self::declaration($id);
        $groups = $declaration['sections'] ?? [];
        return [
            'eqLogic' => (object) self::validateFields($id, 'eqLogic', $declaration['eqLogic'] ?? [], $groups),
            'cmd' => (object) self::validateFields($id, 'cmd', $declaration['cmd'] ?? [], $groups),
        ];
    }

    public static function validateEquipment(string $id, array $changes, bool $creating): array
    {
        $fields = self::section($id, 'eqLogic');
        foreach ($fields as $key => $field) {
            if ($creating && ($field['create'] ?? false) && !array_key_exists($key, $changes)) {
                if (($field['required'] ?? false) && (!($field['create'] ?? false) || $field['type'] === 'boolean')) self::invalidSchema();
            if (array_key_exists('default', $field)) $changes[$key] = $field['default'];
                elseif ($field['required'] ?? false) {
                    throw ApiError::field(422, 'validation_error', 'configuration.' . $key, 'Ce champ est obligatoire.');
                }
            }
            if (array_key_exists($key, $changes) && !self::validEntityValue($changes[$key], $field)) {
                self::invalidValue($key);
            }
        }
        foreach (self::optionFields($fields) as $key => $field) {
            if (array_key_exists($key, $changes) && !self::validOptions($changes[$key])) self::invalidValue($key);
        }
        return $changes;
    }

    public static function validateCommand(string $id, array $changes, array $command): void
    {
        $fields = self::section($id, 'cmd');
        foreach ($fields as $key => $field) {
            if (array_key_exists($key, $changes)
                && (array_diff_assoc((array) ($field['when'] ?? []), $command) !== [] || !self::validEntityValue($changes[$key], $field))) {
                self::invalidValue($key);
            }
        }
        foreach (self::optionFields($fields) as $key => $field) {
            if (array_key_exists($key, $changes)
                && (array_diff_assoc((array) ($field['when'] ?? []), $command) !== [] || !self::validOptions($changes[$key]))) {
                self::invalidValue($key);
            }
        }
    }

    public static function redactPasswords(string $id, string $section, array $configuration): array
    {
        return array_diff_key($configuration, self::passwordFields($id, $section));
    }

    public static function resolveReferences(string $id, string $section, array $body): array
    {
        if (empty($body['configuration'])) return \jeedom::fromHumanReadable($body);
        $passwords = array_intersect_key($body['configuration'], self::passwordFields($id, $section));
        if ($passwords === []) return \jeedom::fromHumanReadable($body);
        $body['configuration'] = array_diff_key($body['configuration'], $passwords);
        $body = \jeedom::fromHumanReadable($body);
        $body['configuration'] = array_replace($body['configuration'], $passwords);
        return $body;
    }

    private static function passwordFields(string $id, string $section): array
    {
        // Plusieurs équipements ou commandes du même plugin peuvent figurer dans une réponse.
        static $fields = [];
        if (!isset($fields[$id][$section])) {
            try {
                $declaration = self::declaration($id);
                $fields[$id][$section] = array_filter($declaration[$section] ?? [], static fn ($field) => ($field->type ?? null) === 'password');
            } catch (ApiError) {
                // Les listes d'équipements et de commandes restent disponibles si la déclaration est illisible.
                $fields[$id][$section] = [];
            }
        }
        return $fields[$id][$section];
    }

    private static function section(string $id, string $section): array
    {
        $declaration = self::declaration($id);
        return self::validateFields($id, $section, $declaration[$section] ?? [], $declaration['sections'] ?? []);
    }

    private static function declaration(string $id): array
    {
        $path = self::path($id);
        if ($path === null) return [];
        $raw = @file_get_contents($path, false, null, 0, self::MAX_BYTES + 1);
        $schema = $raw !== false && strlen($raw) <= self::MAX_BYTES ? json_decode($raw, false, 16) : null;
        if (!$schema instanceof \stdClass) self::invalidSchema();
        $sections = (array) $schema;
        if (($sections['version'] ?? null) !== 1 || array_diff(array_keys($sections), ['version', 'plugin', 'eqLogic', 'cmd', 'sections'])) {
            self::invalidSchema();
        }
        unset($sections['version']);
        foreach ($sections as &$fields) {
            if (!$fields instanceof \stdClass) self::invalidSchema();
            $fields = (array) $fields;
        }
        $sections['sections'] = self::validateSections($sections['sections'] ?? []);
        return $sections;
    }

    private static function validateSections(array $sections): array
    {
        foreach ($sections as $key => $section) {
            if (!preg_match('/\A[A-Za-z][A-Za-z0-9_-]{0,126}\z/', (string) $key)
                || !$section instanceof \stdClass
                || array_diff(array_keys((array) $section), ['label', 'icon', 'description'])
                || !is_string($section->label ?? null) || trim($section->label) === ''
                || !is_string($section->icon ?? null) || !preg_match('/\Amdi:[a-z0-9]+(?:-[a-z0-9]+)*\z/', $section->icon)
                || (property_exists($section, 'description') && !is_string($section->description))) {
                self::invalidSchema();
            }
            $sections[$key] = ['id' => $key] + (array) $section;
        }
        return $sections;
    }

    private static function validateFields(string $id, string $section, array $fields, array $groups = []): array
    {
        if (count($fields) > 64) self::invalidSchema();
        $properties = ['type', 'enum', 'options', 'label', 'description', 'min', 'max', 'picker', 'commandFilters', 'fields'];
        $properties = array_merge($properties, match ($section) {
            'eqLogic' => ['create', 'required', 'default'],
            'cmd' => ['when'],
            'row' => ['required', 'default'],
            default => [],
        });
        if ($section !== 'row') $properties[] = 'section';
        $usedKeys = array_fill_keys(array_keys($fields), true);
        foreach ($fields as $key => $field) {
            if (!preg_match('/\A[A-Za-z][A-Za-z0-9_:-]{0,126}\z/', (string) $key)
                || ($section === 'cmd' && $key === 'actionCodeAccess')
                || !$field instanceof \stdClass) {
                self::invalidSchema();
            }
            $field = (array) $field;
            if (array_diff(array_keys($field), $properties)
                || !in_array($field['type'] ?? null, ['boolean', 'number', 'string', 'password', 'select', 'expression', 'cron', 'textarea', 'icon', 'time', 'date', 'datetime', 'list'], true)
                || ($section === 'row' && in_array($field['type'], ['list', 'password'], true))
                || ($section === 'plugin' && $field['type'] !== 'password' && \config::shouldBeEncrypted((string) $key, $id))) {
                self::invalidSchema();
            }
            if (array_key_exists('section', $field)) {
                $group = $field['section'];
                if (!is_string($group) || !isset($groups[$group])) self::invalidSchema();
                $field['section'] = $groups[$group];
            }
            if ($field['type'] === 'list') {
                if (!(($field['fields'] ?? null) instanceof \stdClass) || (array) $field['fields'] === []) self::invalidSchema();
                $field['fields'] = (object) self::validateFields($id, 'row', (array) $field['fields']);
            } elseif (array_key_exists('fields', $field)) {
                self::invalidSchema();
            }
            if (array_key_exists('enum', $field)
                && ($field['type'] !== 'string' || !is_array($field['enum']) || !array_is_list($field['enum'])
                    || $field['enum'] === [] || array_filter($field['enum'], static fn ($item) => !is_string($item)))) {
                self::invalidSchema();
            }
            if ($field['type'] === 'select') {
                $field['options'] = self::selectOptions($field['options'] ?? null);
            } elseif (array_key_exists('options', $field)) {
                self::invalidSchema();
            }
            if (array_key_exists('picker', $field)) {
                if ($field['type'] !== 'string' || isset($field['enum'])) self::invalidSchema();
                $field['picker'] = self::picker($field['picker'], $key);
                $optionsKey = $field['picker']->optionsKey ?? null;
                if ($optionsKey !== null) {
                    if (isset($usedKeys[$optionsKey])
                        || ($section === 'plugin' && \config::shouldBeEncrypted($optionsKey, $id))
                        || ($section === 'cmd' && $optionsKey === 'actionCodeAccess')) self::invalidSchema();
                    $usedKeys[$optionsKey] = true;
                }
            }
            if (array_key_exists('commandFilters', $field)) {
                if ($field['type'] !== 'expression') self::invalidSchema();
                $filters = $field['commandFilters'];
                self::validatePickerFilters('commandFilters', $filters);
                if (($filters->type ?? 'info') !== 'info'
                    || (isset($filters->subType) && !in_array($filters->subType, ['numeric', 'binary', 'string'], true))) {
                    self::invalidSchema();
                }
            }
            foreach (['label', 'description'] as $text) {
                if (array_key_exists($text, $field) && !is_string($field[$text])) self::invalidSchema();
            }
            foreach (['min', 'max'] as $bound) {
                if (array_key_exists($bound, $field) && ($field['type'] !== 'number' || !self::isNumber($field[$bound]))) {
                    self::invalidSchema();
                }
            }
            if (isset($field['min'], $field['max']) && $field['min'] > $field['max']) self::invalidSchema();
            foreach (['create', 'required'] as $flag) {
                if (array_key_exists($flag, $field) && !is_bool($field[$flag])) self::invalidSchema();
            }
            $initializable = $section === 'row' || ($field['create'] ?? false);
            if (($field['required'] ?? false) && (!$initializable || $field['type'] === 'boolean')) self::invalidSchema();
            if (array_key_exists('default', $field)
                && (!$initializable || $field['type'] === 'password' || !self::valid($field['default'], $field))) {
                self::invalidSchema();
            }
            if (array_key_exists('when', $field)) {
                if (!$field['when'] instanceof \stdClass) self::invalidSchema();
                $when = (array) $field['when'];
                if (array_diff(array_keys($when), ['type', 'subType', 'logicalId'])
                    || array_filter($when, static fn ($value) => !is_string($value) || $value === '')
                    || (isset($when['type']) && !in_array($when['type'], ['info', 'action'], true))) {
                    self::invalidSchema();
                }
            }
            $fields[$key] = $field;
        }
        return $fields;
    }

    private static function picker(mixed $picker, string $key): \stdClass
    {
        if (!$picker instanceof \stdClass) self::invalidSchema();
        $allowed = $picker->allowed ?? null;
        if (!is_array($allowed) || !array_is_list($allowed) || $allowed === []
            || array_filter($allowed, static fn ($kind) => !in_array($kind, ['command', 'equipment', 'scenario', 'action'], true))
            || count($allowed) !== count(array_unique($allowed))) self::invalidSchema();
        $supported = array_map(static fn ($kind) => $kind . 'Filters', $allowed);
        if (array_diff(array_keys((array) $picker), ['allowed', 'optionsKey', ...$supported])) self::invalidSchema();
        foreach ($supported as $name) {
            if (!property_exists($picker, $name)) continue;
            self::validatePickerFilters($name, $picker->$name);
        }
        if (in_array('command', $allowed, true) || in_array('action', $allowed, true)) {
            $optionKey = property_exists($picker, 'optionsKey') ? $picker->optionsKey : $key . '::options';
            if (!is_string($optionKey) || !preg_match('/\A[A-Za-z][A-Za-z0-9_:-]{0,126}\z/', $optionKey)) self::invalidSchema();
            $picker->optionsKey = $optionKey;
        } elseif (property_exists($picker, 'optionsKey')) {
            self::invalidSchema();
        }
        return $picker;
    }

    private static function validatePickerFilters(string $group, mixed $filters): void
    {
        if (!$filters instanceof \stdClass) self::invalidSchema();
        foreach ((array) $filters as $key => $value) {
            if (!in_array($key, self::PICKER_FILTERS[$group], true) || !self::validFilter($group, $key, $value)) {
                self::invalidSchema();
            }
        }
    }

    private static function validFilter(string $group, string $key, mixed $value): bool
    {
        if ($group === 'commandFilters' && in_array($key, ['eqType', 'generic_type'], true)) {
            $values = is_array($value) ? $value : [$value];
            return array_is_list($values) && $values !== []
                && !array_filter($values, static fn ($item) => !is_string($item) || trim($item) === '' || str_contains($item, ','));
        }
        return match ($key) {
            'type' => in_array($value, ['info', 'action'], true),
            'subType' => in_array($value, ['numeric', 'binary', 'string', 'other', 'slider', 'message', 'color', 'select'], true),
            'mode' => in_array($value, ['provoke', 'schedule', 'all'], true),
            'eqLogicId' => is_int($value) && $value > 0,
            'objectId' => is_int($value) && $value >= ($group === 'equipmentFilters' ? 0 : 1),
            'isHistorized', 'enabled', 'visible', 'active', 'scenarioActions' => is_bool($value),
            'expressions' => is_array($value) && array_is_list($value)
                && !array_filter($value, static fn ($expression) => !is_string($expression) || $expression === ''),
            'group' => is_string($value),
            default => is_string($value) && $value !== '',
        };
    }

    private static function optionFields(array $fields): array
    {
        $options = [];
        foreach ($fields as $field) {
            if (isset($field['picker']->optionsKey)) $options[$field['picker']->optionsKey] = $field;
        }
        return $options;
    }

    private static function validOptions(mixed $value): bool
    {
        return is_array($value) && ($value === [] || !array_is_list($value))
            && !array_filter($value, static fn ($item) => !is_bool($item) && !is_string($item) && !self::isNumber($item));
    }

    private static function selectOptions(mixed $options): array
    {
        if (!is_array($options) || !array_is_list($options) || $options === []) self::invalidSchema();
        $seen = [];
        foreach ($options as $option) {
            if (!$option instanceof \stdClass
                || array_diff(array_keys((array) $option), ['value', 'label'])
                || !is_string($option->value ?? null) || !is_string($option->label ?? null)
                || isset($seen[$option->value])) {
                self::invalidSchema();
            }
            $seen[$option->value] = true;
        }
        return $options;
    }

    public static function read(string $id, array $fields): object
    {
        $visibleFields = array_filter($fields, static fn ($field) => $field['type'] !== 'password');
        $optionFields = self::optionFields($fields);
        $stored = \config::byKeys(array_keys($visibleFields + $optionFields), $id, null);
        $values = [];
        foreach ($fields as $key => $field) {
            $value = $stored[$key] ?? null;
            if ($field['type'] === 'list') {
                // Les entrées des plugins existants restent visibles pour pouvoir les corriger dans le formulaire.
                $values[$key] = self::readList($value);
                continue;
            }
            $value = match ($field['type']) {
                'boolean' => match ($value) {
                    true, 1, '1' => true,
                    false, 0, '0' => false,
                    default => null,
                },
                'number' => is_numeric($value) ? $value + 0 : null,
                'select', 'string', 'expression', 'cron', 'textarea', 'icon', 'time', 'date', 'datetime' => $value,
                'password' => null,
            };
            $values[$key] = self::valid($value, $field) ? $value : null;
        }
        foreach ($optionFields as $key => $field) {
            $options = $stored[$key] ?? [];
            $values[$key] = (object) (self::validOptions($options) ? $options : []);
        }
        return (object) $values;
    }

    public static function update(string $id, array $fields, array $changes): void
    {
        if ($changes === []) throw new ApiError(422, 'validation_error', 'Aucun réglage à modifier.');
        $optionFields = self::optionFields($fields);
        foreach ($changes as $key => $value) {
            $valid = isset($optionFields[$key]) ? self::validOptions($value)
                : isset($fields[$key]) && self::valid($value, $fields[$key]);
            if (!$valid) {
                throw ApiError::field(422, 'validation_error', (string) $key, 'Réglage inconnu ou valeur invalide.');
            }
        }
        \DB::beginTransaction();
        try {
            foreach ($changes as $key => $value) {
                \config::save($key, is_bool($value) ? (int) $value : $value, $id);
            }
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    private static function valid(mixed $value, array $field): bool
    {
        return match ($field['type']) {
            'list' => self::validList($value, $field),
            'boolean' => is_bool($value),
            'number' => self::isNumber($value) && $value >= ($field['min'] ?? -INF) && $value <= ($field['max'] ?? INF),
            'password' => is_string($value) && $value !== '',
            'select' => is_string($value) && (!($field['required'] ?? false) || trim($value) !== '')
                && in_array($value, array_column($field['options'], 'value'), true),
            'cron', 'time', 'date', 'datetime' => is_string($value) && ($value === ''
                ? !($field['required'] ?? false)
                : self::validFormattedValue($value, $field['type'])),
            'string', 'expression', 'textarea', 'icon' => is_string($value) && (!($field['required'] ?? false) || trim($value) !== '')
                && (!isset($field['enum']) || in_array($value, $field['enum'], true)),
        };
    }

    private static function readList(mixed $value): ?array
    {
        if (!is_array($value) || !array_is_list($value)) return null;
        $rows = [];
        foreach ($value as $row) {
            if (!$row instanceof \stdClass && (!is_array($row) || ($row !== [] && array_is_list($row)))) return null;
            $rows[] = (object) $row;
        }
        return $rows;
    }

    private static function validList(mixed $value, array $field): bool
    {
        $rows = self::readList($value);
        if ($rows === null || ($rows === [] && ($field['required'] ?? false))) return false;
        $fields = (array) $field['fields'];
        $options = self::optionFields($fields);
        foreach ($rows as $row) {
            $values = (array) $row;
            foreach ($fields as $key => $definition) {
                $value = $values[$key] ?? null;
                // Afficher et déplacer les lignes historiques sans convertir leurs valeurs stockées.
                $normalized = match ($definition['type']) {
                    'boolean' => in_array($value, [0, 1, '0', '1'], true) ? (bool) (int) $value : $value,
                    'number' => is_string($value) && is_numeric($value) ? $value + 0 : $value,
                    'string', 'expression', 'textarea' => !isset($definition['enum']) && self::isNumber($value) ? (string) $value : $value,
                    default => $value,
                };
                if (!self::validEntityValue($normalized, $definition)) return false;
            }
            foreach ($options as $key => $definition) {
                if (!array_key_exists($key, $values)) continue;
                $saved = $values[$key];
                if ($saved instanceof \stdClass) $saved = (array) $saved;
                if (!self::validOptions($saved)) return false;
            }
        }
        return true;
    }

    private static function validFormattedValue(string $value, string $type): bool
    {
        return match ($type) {
            'cron' => self::validSchedule($value),
            'time' => self::validTime($value),
            'date' => self::validDate($value),
            'datetime' => preg_match('/\A(\d{4}-\d{2}-\d{2})[ T](.+)\z/', $value, $parts)
                && self::validDate($parts[1]) && self::validTime($parts[2]),
        };
    }

    private static function validSchedule(string $value): bool
    {
        try {
            return \Cron\CronExpression::isValidExpression(\checkAndFixCron($value));
        } catch (\Throwable) {
            return false;
        }
    }

    private static function validDate(string $value): bool
    {
        return preg_match('/\A(\d{4})-(\d{2})-(\d{2})\z/', $value, $parts)
            && checkdate((int) $parts[2], (int) $parts[3], (int) $parts[1]);
    }

    private static function validTime(string $value): bool
    {
        return preg_match('/\A(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?\z/', $value) === 1;
    }

    private static function validEntityValue(mixed $value, array $field): bool
    {
        // null supprime une clé facultative dans les configurations d'équipement et de commande.
        return $value === null ? !($field['required'] ?? false) : self::valid($value, $field);
    }

    private static function isNumber(mixed $value): bool
    {
        return (is_int($value) || is_float($value)) && is_finite($value);
    }

    private static function invalidSchema(): never
    {
        throw new ApiError(500, 'invalid_plugin_settings', 'La déclaration des réglages du plugin est invalide.');
    }

    private static function invalidValue(string $key): never
    {
        throw ApiError::field(422, 'validation_error', 'configuration.' . $key, 'Réglage indisponible ou valeur invalide.');
    }
}
