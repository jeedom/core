<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Configuration volontairement exposée par un plugin, dans son propre espace de noms. */
final class PluginSettings
{
    private const MAX_BYTES = 16384;

    /** @return array<string, array{type: string, enum?: list<string>}> */
    public static function fields(string $id): array
    {
        $root = realpath(\plugin::getPluginPath($id));
        $path = $root === false ? false : realpath($root . '/plugin_info/ui-settings.json');
        if ($path === false || !str_starts_with($path, $root . '/') || !is_file($path)) {
            throw new ApiError(404, 'plugin_settings_not_found', 'Ce plugin ne déclare pas de configuration pour cette interface.');
        }
        $raw = @file_get_contents($path, false, null, 0, self::MAX_BYTES + 1);
        $fields = $raw !== false && strlen($raw) <= self::MAX_BYTES ? json_decode($raw, true, 8) : null;
        if (!is_array($fields) || $fields === [] || count($fields) > 64) self::invalidSchema();
        foreach ($fields as $key => $field) {
            // byKeys construit sa clause IN avec ces seules clés, jamais avec les clés de la requête.
            if (!preg_match('/\A[A-Za-z][A-Za-z0-9_:-]{0,126}\z/', (string) $key)
                || \config::shouldBeEncrypted((string) $key, $id)
                || !is_array($field) || array_diff(array_keys($field), ['type', 'enum'])
                || !in_array($field['type'] ?? null, ['boolean', 'number', 'string'], true)) {
                self::invalidSchema();
            }
            if (array_key_exists('enum', $field)
                && ($field['type'] !== 'string' || !is_array($field['enum']) || !array_is_list($field['enum'])
                    || $field['enum'] === [] || array_filter($field['enum'], static fn ($item) => !is_string($item)))) {
                self::invalidSchema();
            }
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
            'number' => (is_int($value) || is_float($value)) && is_finite($value),
            'string' => is_string($value) && (!isset($field['enum']) || in_array($value, $field['enum'], true)),
        };
    }

    private static function invalidSchema(): never
    {
        throw new ApiError(500, 'invalid_plugin_settings', 'La déclaration des réglages du plugin est invalide.');
    }
}
