<?php

require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
$configRemove = array();
$sql = 'SELECT * 
        FROM config 
        WHERE `key` LIKE "%::installVersionDate%"';
$values = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
foreach ($values as $value) {
    $update = new update();
    $name = explode('::', $value['key']);
    $update->setLogicalId($name[0]);
    $update->setLocalVersion($value['value']);
    if ($value['plugin'] == 'core') {
        $update->setType('plugin');
        $configRemove[] = array('key' => $value['key']);
    } else {
        $update->setType($value['plugin']);
        $configRemove[] = array('key' => $value['key'], 'plugin' => $value['plugin']);
    }
    try {
        $update->save();
    } catch (Exception $ex) {
        
    }
}

$sql = 'SELECT * 
        FROM config 
        WHERE `key` = "installVersionDate"';
$values = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
foreach ($values as $value) {
    $update = new update();
    if ($value['plugin'] != '') {
        $update->setLogicalId($value['plugin']);
        $update->setLocalVersion($value['value']);
        $update->setType('plugin');
        try {
            $update->save();
            $configRemove[] = array('key' => "installVersionDate", 'plugin' => $value['plugin']);
        } catch (Exception $ex) {
            
        }
    }
}

foreach ($configRemove as $remove) {
    if (isset($remove['plugin'])) {
        config::remove($remove['key'], $remove['plugin']);
    } else {
        config::remove($remove['key']);
    }
}