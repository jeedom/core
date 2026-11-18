<?php
declare(strict_types=1);

namespace Jeedom\Api\Rest;

/** Adapte les niveaux historiques à la configuration REST. */
final class LogLevels
{
    public const KEY = 'log::levels';
    public const VALUES = ['debug' => '100', 'info' => '200', 'warning' => '300', 'error' => '400', 'none' => '1000', 'default' => 'default'];

    private const PREFIX = 'log::level::';
    private const SYSTEM = ['api', 'audit', 'connection', 'event', 'interact', 'market', 'plugin', 'report', 'scenario', 'tts'];

    /** @return list<array{id: string, name: string, kind: string}> */
    private static function sources(): array
    {
        $sources = [];
        foreach (self::SYSTEM as $id) {
            $sources[] = ['id' => $id, 'name' => $id, 'kind' => 'system'];
        }
        foreach (\plugin::listPlugin(true) as $plugin) {
            $sources[] = ['id' => (string) $plugin->getId(), 'name' => (string) $plugin->getName(), 'kind' => 'plugin'];
        }
        return $sources;
    }

    /** @return list<array{id: string, name: string, kind: string, level: string}> */
    public static function read(): array
    {
        $stored = \config::getLogLevelPlugin();
        return array_map(static function (array $source) use ($stored): array {
            return $source + ['level' => self::decode($stored[self::PREFIX . $source['id']] ?? null)];
        }, self::sources());
    }

    /** @return array<string, array<int|string, int>> clés et valeurs à enregistrer dans la transaction de configuration */
    public static function changes(mixed $changes): array
    {
        if (!is_array($changes) || $changes === []) {
            throw ApiError::field(422, 'validation_error', self::KEY, 'Indiquez les niveaux à modifier par source.');
        }
        $allowed = array_fill_keys(array_column(self::sources(), 'id'), true);
        $settings = [];
        foreach ($changes as $id => $level) {
            if (!isset($allowed[$id]) || !is_string($level) || !isset(self::VALUES[$level])) {
                throw ApiError::field(422, 'validation_error', self::KEY, 'Source ou niveau de log inconnu.');
            }
            $settings[self::PREFIX . $id] = self::encode($level);
        }
        return $settings;
    }

    /** @return array<int|string, int> */
    public static function encode(string $level): array
    {
        $stored = array_fill_keys(array_values(self::VALUES), 0);
        $stored[self::VALUES[$level]] = 1;
        return $stored;
    }

    public static function decode(mixed $stored): string
    {
        if (!is_array($stored) || ($stored['default'] ?? 0) == 1) {
            return 'default';
        }
        // Comme log::getLogLevel(), retenir le premier seuil numérique actif.
        foreach ($stored as $threshold => $enabled) {
            if (is_numeric($threshold) && $enabled == 1) {
                return array_search((string) $threshold, self::VALUES, true) ?: 'default';
            }
        }
        return 'default';
    }
}
