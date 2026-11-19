<?php

// The standalone REST suites do not load the Core autoloader.
require_once dirname(__DIR__, 2) . '/core/class/supportAccess.class.php';

function fixtureRestToken(string $name): string {
    return hash('sha256', 'fixture-session:' . $name);
}

abstract class FixtureMcpConfig {
    public const MCP_ENABLED_KEY = 'security::mcp::enabled';
    public static function mcpEnabled(): bool { return (bool) static::byKey(self::MCP_ENABLED_KEY); }
}

class session {
    public const MCP_SCOPE = 'mcp';

    public static function resolve(?string $token, string $transport, bool $refresh = false): ?array {
        if ($token === null || $refresh) {
            return null;
        }
        $id = match (true) {
            $transport === 'web' && $token === fixtureRestToken('browser-token') => 1,
            $transport === 'api' && $token === fixtureRestToken('admin') => 1,
            $transport === 'api' && $token === fixtureRestToken('member') => 2,
            $transport === 'api' && $token === fixtureRestToken('daemon-fixture') => 3,
            $transport === 'api' && $token === fixtureRestToken('daemon-without-handler') => 4,
            default => null,
        };
        $user = $id === null ? null : user::byId($id);
        if ($user === null || !$user->isValidAndEnabled()) {
            return null;
        }
        return ['user' => $user, 'session' => [
            'id' => $transport === 'web' ? 'browser-id' : 'api-id-' . $id,
            'csrf_token' => 'browser-csrf', 'remembered' => 0, 'mode' => $id >= 3 ? 'daemon' : $transport,
            'scope' => [3 => 'fixture', 4 => 'without-handler'][$id] ?? '',
        ]];
    }

    public static function touch(array $row): void {}

    /** Sessions de démon créées par plugin::daemonCredentialFile(), indexées par identifiant. */
    public static array $daemonSessions = [];

    public static function create(user $actor, bool $remember, string $ip, ?string $previous, ?int $expiresBefore = null,
        string $mode = 'web', string $name = 'Core UI', string $scope = '', bool $refreshOnly = false): array {
        $id = bin2hex(random_bytes(32));
        $refresh = bin2hex(random_bytes(32));
        self::$daemonSessions[$id] = ['userId' => $actor->getId(), 'refreshToken' => $refresh, 'mode' => $mode, 'scope' => $scope];
        return ['id' => $id, 'refreshToken' => $refresh];
    }

    public static function revoke(user $user, string $id): bool {
        $known = (self::$daemonSessions[$id]['userId'] ?? null) === $user->getId();
        if ($known) unset(self::$daemonSessions[$id]);
        return $known;
    }

    public static function revokeAll(int $userId): void {
        self::$daemonSessions = array_filter(self::$daemonSessions, static fn (array $row): bool => $row['userId'] !== $userId);
    }
}

/** Load the production operation methods against the REST suites' native doubles. */
function defineRestOperationsFixture(string $class, string $fixture, array $replacements = []): void {
    $heading = match ($class) {
        'plugin' => '/* Installations de plugins */',
        'update' => '/* Opérations de mise à jour */',
    };
    $directory = dirname(__DIR__, 2) . '/core/class';
    $source = file_get_contents($directory . '/' . $class . '.class.php');
    $start = $source === false ? false : strpos($source, $heading);
    if ($start === false) {
        throw new RuntimeException("Section des opérations introuvable dans $class");
    }
    $start += strlen($heading);
    $end = strpos($source, "/*     * *********************Méthodes d'instance************************* */", $start);
    if ($end === false) {
        throw new RuntimeException("Fin de la section des opérations introuvable dans $class");
    }

    $operations = strtr(substr($source, $start, $end - $start), $replacements);
    $operations = str_replace('__DIR__', var_export($directory, true), $operations);
    eval("class $class {\n$fixture\n$operations\n}");
}
