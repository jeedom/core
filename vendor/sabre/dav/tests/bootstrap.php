<?php

declare(strict_types=1);

set_include_path(__DIR__.'/../lib/'.PATH_SEPARATOR.__DIR__.PATH_SEPARATOR.get_include_path());

$autoLoader = include __DIR__.'/../vendor/autoload.php';

// SabreDAV tests auto loading
$autoLoader->add('Sabre\\', __DIR__);
// VObject tests auto loading
$autoLoader->addPsr4('Sabre\\VObject\\', __DIR__.'/../vendor/sabre/vobject/tests/VObject');
$autoLoader->addPsr4('Sabre\\Xml\\', __DIR__.'/../vendor/sabre/xml/tests/Sabre/Xml');

date_default_timezone_set('UTC');

if ('TRUE' === getenv('RUN_TEST_WITH_STREAMING_PROPFIND')) {
    echo 'Running unit tests with \Sabre\DAV\Server::$streamMultiStatus = true';
    \Sabre\DAV\Server::$streamMultiStatus = true;
}

// List of variables that can be set by the environment
$environmentVars = [
    'SABRE_MYSQLUSER',
    'SABRE_MYSQLPASS',
    'SABRE_MYSQLDSN',
    'SABRE_PGSQLDSN',
];
foreach ($environmentVars as $var) {
    if ($value = getenv($var)) {
        define($var, $value);
    }
}

$config = [
    'SABRE_TEMPDIR' => dirname(__FILE__).'/temp/',
    'SABRE_HASSQLITE' => in_array('sqlite', PDO::getAvailableDrivers()),
    'SABRE_HASMYSQL' => in_array('mysql', PDO::getAvailableDrivers()),
    'SABRE_HASPGSQL' => in_array('pgsql', PDO::getAvailableDrivers()),
    'SABRE_MYSQLDSN' => 'mysql:host=127.0.0.1;dbname=sabredav_test',
    'SABRE_MYSQLUSER' => 'sabredav',
    'SABRE_MYSQLPASS' => '',
    'SABRE_PGSQLDSN' => 'pgsql:host=localhost;dbname=sabredav_test;user=sabredav;password=sabredav',
];

if (file_exists(__DIR__.'/config.user.php')) {
    include __DIR__.'/config.user.php';
    foreach ($userConfig as $key => $value) {
        $config[$key] = $value;
    }
}

foreach ($config as $key => $value) {
    if (!defined($key)) {
        define($key, $value);
    }
}

if (!file_exists(SABRE_TEMPDIR)) {
    mkdir(SABRE_TEMPDIR);
}
if (file_exists('.sabredav')) {
    unlink('.sabredav');
}
