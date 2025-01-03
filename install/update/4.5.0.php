<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

try {
    echo 'Empty old cache system...';
    cache::flush();
    echo "OK\n";
    echo 'Empty DB cache table...';
    DB::prepare('TRUNCATE TABLE `cache`', array(), DB::FETCH_TYPE_ALL);
    echo "OK\n";
} catch (\Throwable $th) {
    echo 'Error on empty cache : ' . $th->getMessage();
}
echo shell_exec('php ' . __DIR__ . '/reloadCache.php');

// Renamed ports mapping
$renamed = array(
    'Orange PI' => 'Orange Pi',
    'Odroid ARMBIAN (Buster)' => 'Odroid Armbian',
    'Raspberry pi' => 'Raspberry Pi',
    'Banana PI' => 'Banana Pi',
);
foreach ($renamed as $previousValue => $newValue) {
    foreach ((config::searchValue($previousValue, 'port')) as $config) {
        config::save('port', $newValue, $config['plugin']);
    }
}
