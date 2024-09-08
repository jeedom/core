<?php

$envData = [] + readEnv('.env.local') + readEnv('.env');

$databaseDsn = $envData['DATABASE_DSN'] ?? null;
if (null === $databaseDsn) {
    $configFile = __DIR__ . '/common.config.php';
    if (!is_readable($configFile)) {
        throw new \RuntimeException('Missing database configuration. Please set DATABASE_DSN in .env file.');
    }
    trigger_error(sprintf('The "%s" file is deprecated. Please use .env instead.', $configFile), E_USER_DEPRECATED);
    require_once $configFile;
    return;
}

define('DEBUG', (int) ($envData['DEBUG'] ?? false));
$databaseConfig = parse_url($databaseDsn);
foreach (['scheme', 'host', 'path', 'user', 'pass'] as $key) {
    if (!isset($databaseConfig[$key])) {
        throw new \RuntimeException(sprintf('Incomplete database configuration, %s is missing.', $key));
    }
}

if ($databaseConfig['scheme'] !== 'mysql') {
    throw new \RuntimeException(sprintf('Unsupported database configuration, %s is not supported. Please use mysql instead.', $databaseConfig['scheme']));
}

global $CONFIG;
$CONFIG = [
    'db' => [
        'host' => $databaseConfig['host'],
        'port' => (string) ($databaseConfig['port'] ?? 3306),
        'dbname' => ltrim($databaseConfig['path'], '/'),
        'username' => $databaseConfig['user'],
        'password' => $databaseConfig['pass'],
    ],
];

function readEnv(string $name): array
{
    $dotenv = dirname(__DIR__, 2) . '/'. $name;
    if (!is_readable($dotenv)) {
        return [];
    }

    return parse_ini_file($dotenv, false) ?: [];
}