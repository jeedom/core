<?php
require_once __DIR__ . '/../core/config/common.config.php';
require_once __DIR__ . '/../core/class/DB.class.php';
/*
$tables = DB::Prepare('show tables;',array(),DB::FETCH_TYPE_ALL);
$database = array();
$database['tables'] = array();
foreach ($tables as $table) {
$describes = DB::Prepare('describe `'.$table['Tables_in_jeedom'].'`',array(),DB::FETCH_TYPE_ALL);
$fields = array();
foreach ($describes as $describe) {
$fields[] = array(
'name' => $describe['Field'],
"type"  => $describe['Type'],
"null"  => $describe['Null'],
"key"  => $describe['Key'],
"default"  => $describe['Default'],
"extra"  => $describe['Extra'],
);
}
$database['tables'][] = array(
'name' => $table['Tables_in_jeedom'],
'fields' => $fields,
);
}
echo json_encode($database, JSON_PRETTY_PRINT);
*/


?>
