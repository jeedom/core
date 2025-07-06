<?php

require_once dirname(__DIR__) . '/core/config/common.config.php';
global $CONFIG;

$CONFIG['db']['dbname'] .= '_test';

$connection = new PDO(sprintf('mysql:host=%s;port=%u;charset=utf8', $CONFIG['db']['host'], $CONFIG['db']['port']), $CONFIG['db']['username'], $CONFIG['db']['password']);
$connection->query('DROP DATABASE IF EXISTS ' . $CONFIG['db']['dbname']);
$connection->query('CREATE DATABASE '.$CONFIG['db']['dbname']);
$connection->query('GRANT ALL PRIVILEGES ON '.$CONFIG['db']['dbname'].'.* TO "'.$CONFIG['db']['username'].'"@"%" IDENTIFIED BY "'.$CONFIG['db']['password'].'"');
$connection->query('FLUSH PRIVILEGES');

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
