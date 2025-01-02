<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

// Renamed USB mapping
$renamed = array(
    'Orange PI' => 'Orange Pi',
    'Odroid ARMBIAN (Buster)' => 'Odroid Armbian',
    'Raspberry pi' => 'Raspberry Pi',
    'Banana PI' => 'Banana Pi',
);
foreach ($renamed as $previousValue => $newValue) {
    foreach ((config::searchValue($previousValue)) as $config) {
        config::save($config['key'], $newValue, $config['plugin']);
    }
}
