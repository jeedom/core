<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Configuration volontairement exposée par un plugin, dans son propre espace de noms. */
final class PluginSettings
{
    private const MAX_BYTES = 16384;

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
        return [
            'eqLogic' => (object) self::validateFields($id, 'eqLogic', $declaration['eqLogic'] ?? []),
            'cmd' => (object) self::validateFields($id, 'cmd', $declaration['cmd'] ?? []),
        ];
    }

    public static function validateEquipment(string $id, array $changes, bool $creating): array
    {
        foreach (self::section($id, 'eqLogic') as $key => $field) {
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
        return $changes;
    }

    public static function validateCommand(string $id, array $changes, array $command): void
    {
        foreach (self::section($id, 'cmd') as $key => $field) {
            if (array_key_exists($key, $changes)
                && (array_diff_assoc((array) ($field['when'] ?? []), $command) !== [] || !self::validEntityValue($changes[$key], $field))) {
                self::invalidValue($key);
            }
        }
    }

    private static function section(string $id, string $section): array
    {
        return self::validateFields($id, $section, self::declaration($id)[$section] ?? []);
    }

    private static function declaration(string $id): array
    {
        $path = self::path($id);
        if ($path === null) return [];
        $raw = @file_get_contents($path, false, null, 0, self::MAX_BYTES + 1);
        $schema = $raw !== false && strlen($raw) <= self::MAX_BYTES ? json_decode($raw, false, 8) : null;
        if (!$schema instanceof \stdClass) self::invalidSchema();
        $sections = (array) $schema;
        // Une clé de réglage historique peut elle-même s'appeler « version ».
        if (!array_key_exists('version', $sections) || $sections['version'] instanceof \stdClass) {
            if ($sections === []) self::invalidSchema();
            return ['plugin' => $sections];
        }
        if ($sections['version'] !== 1 || array_diff(array_keys($sections), ['version', 'plugin', 'eqLogic', 'cmd'])) {
            self::invalidSchema();
        }
        unset($sections['version']);
        foreach ($sections as &$fields) {
            if (!$fields instanceof \stdClass) self::invalidSchema();
            $fields = (array) $fields;
        }
        return $sections;
    }

    private static function validateFields(string $id, string $section, array $fields): array
    {
        if (count($fields) > 64) self::invalidSchema();
        $properties = ['type', 'enum', 'label', 'description', 'min', 'max'];
        $properties = array_merge($properties, match ($section) {
            'eqLogic' => ['create', 'required', 'default'],
            'cmd' => ['when'],
            default => [],
        });
        foreach ($fields as $key => $field) {
            if (!preg_match('/\A[A-Za-z][A-Za-z0-9_:-]{0,126}\z/', (string) $key)
                || ($section === 'plugin' && \config::shouldBeEncrypted((string) $key, $id))
                || ($section === 'cmd' && $key === 'actionCodeAccess')
                || !$field instanceof \stdClass) {
                self::invalidSchema();
            }
            $field = (array) $field;
            if (array_diff(array_keys($field), $properties) || !in_array($field['type'] ?? null, ['boolean', 'number', 'string'], true)) {
                self::invalidSchema();
            }
            if (array_key_exists('enum', $field)
                && ($field['type'] !== 'string' || !is_array($field['enum']) || !array_is_list($field['enum'])
                    || $field['enum'] === [] || array_filter($field['enum'], static fn ($item) => !is_string($item)))) {
                self::invalidSchema();
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
            if (($field['required'] ?? false) && (!($field['create'] ?? false) || $field['type'] === 'boolean')) self::invalidSchema();
            if (array_key_exists('default', $field) && (!($field['create'] ?? false) || !self::valid($field['default'], $field))) {
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

    public static function read(string $id, array $fields): object
    {
        $stored = \config::byKeys(array_keys($fields), $id, null);
        $values = [];
        foreach ($fields as $key => $field) {
            $value = $stored[$key] ?? null;
            $value = match ($field['type']) {
                'boolean' => match ($value) {
                    true, 1, '1' => true,
                    false, 0, '0' => false,
                    default => null,
                },
                'number' => is_numeric($value) ? $value + 0 : null,
                'string' => $value,
            };
            $values[$key] = self::valid($value, $field) ? $value : null;
        }
        return (object) $values;
    }

    public static function update(string $id, array $fields, array $changes): void
    {
        if ($changes === []) throw new ApiError(422, 'validation_error', 'Aucun réglage à modifier.');
        foreach ($changes as $key => $value) {
            if (!isset($fields[$key]) || !self::valid($value, $fields[$key])) {
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
            'boolean' => is_bool($value),
            'number' => self::isNumber($value) && $value >= ($field['min'] ?? -INF) && $value <= ($field['max'] ?? INF),
            'string' => is_string($value) && (!($field['required'] ?? false) || trim($value) !== '')
                && (!isset($field['enum']) || in_array($value, $field['enum'], true)),
        };
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
