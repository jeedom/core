<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

// Renamed ports mapping
$renamed = array(
    'Odroid C2' => 'Odroid (old)',
    'Odroid ARMBIAN (Buster)' => 'Odroid',
    'Banana PI' => 'Banana Pi',
    'Orange PI' => 'Orange Pi',
    'Raspberry pi' => 'Raspberry Pi'
);

$hardware = strtolower(jeedom::getHardwareName());
if ($hardware === 'smart') {
    $renamed['Odroid ARMBIAN (Buster)'] = 'Jeedom Smart';
}
if ($hardware !== 'atlas') {
    $renamed['Jeedom Atlas'] = 'Rock Pi';
}

foreach ($renamed as $previousValue => $newValue) {
    foreach ((config::searchValue($previousValue, 'port')) as $config) {
        config::save('port', $newValue, $config['plugin']);
    }
}
