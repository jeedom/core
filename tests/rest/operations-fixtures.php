<?php

// The standalone REST suites do not load the Core autoloader.
require_once dirname(__DIR__, 2) . '/core/class/supportAccess.class.php';
require_once dirname(__DIR__, 2) . '/core/class/daemonSession.class.php';

function fixtureRestToken(string $name): string {
    return hash('sha256', 'fixture-session:' . $name);
}

class session {
    public static function resolve(?string $token, string $transport, bool $refresh = false): ?array {
        if ($token === null || $refresh) {
            return null;
        }
        $id = match (true) {
            $transport === 'web' && $token === fixtureRestToken('browser-token') => 1,
            $transport === 'api' && $token === fixtureRestToken('admin') => 1,
            $transport === 'api' && $token === fixtureRestToken('member') => 2,
            default => null,
        };
        $user = $id === null ? null : user::byId($id);
        if ($user === null || !$user->isValidAndEnabled()) {
            return null;
        }
        return ['user' => $user, 'session' => [
            'id' => $transport === 'web' ? 'browser-id' : 'api-id-' . $id,
            'csrf_token' => 'browser-csrf', 'remembered' => 0, 'mode' => $transport, 'scope' => '',
        ]];
    }

    public static function touch(array $row): void {}
    public static function revoke(user $user, string $id): bool { return false; }
}

/** Load the production operation methods against the REST suites' native doubles. */
function defineRestOperationsFixture(string $class, string $fixture): void {
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

    $operations = substr($source, $start, $end - $start);
    $operations = str_replace('__DIR__', var_export($directory, true), $operations);
    eval("class $class {\n$fixture\n$operations\n}");
}
