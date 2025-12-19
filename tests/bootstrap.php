<?php

require_once dirname(__DIR__) . '/core/config/common.config.php';
global $CONFIG;

$rootUser = $CONFIG['db']['root_user'] ?? 'root';
$rootPassword = $CONFIG['db']['root_password'] ?? null;

$CONFIG['db']['dbname'] .= '_test';
$CONFIG['db']['username'] .= '_test';
$CONFIG['db']['password'] .= md5(random_bytes(10));

try {
    $connection = new PDO(sprintf('mysql:host=%s;port=%u;charset=utf8', $CONFIG['db']['host'], $CONFIG['db']['port']), $rootUser, $rootPassword);
    $connection->query('DROP DATABASE IF EXISTS ' . $CONFIG['db']['dbname']);
    $connection->query('CREATE DATABASE ' . $CONFIG['db']['dbname']);
    $connection->query('GRANT ALL PRIVILEGES ON ' . $CONFIG['db']['dbname'] . '.* TO "' . $CONFIG['db']['username'] . '"@"%" IDENTIFIED BY "' . $CONFIG['db']['password'] . '"');
    $connection->query('FLUSH PRIVILEGES');
} catch (\Throwable $exception) {
    throw new RuntimeException(sprintf("Cannot create database: %s\nPlease check your MySQL server configuration\n%s", $exception->getMessage(), json_encode($CONFIG['db'])), 0, $exception);
}

ob_start();
require_once dirname(__DIR__).'/install/database.php';

require_once dirname(__DIR__).'/core/class/config.class.php';
config::save('api', config::genKey());

$user = new user();
$user->setLogin('admin');
$user->setPassword(sha512('admin'));
$user->setProfils('admin');
$user->save();

ob_end_clean();

require_once dirname(__DIR__).'/core/php/core.inc.php';

function dd(...$vars)
{
    foreach ($vars as $var) {
        var_dump($var);
    }
    exit;
}
