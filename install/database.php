<?php
require_once __DIR__ . '/../core/config/common.config.php';
require_once __DIR__ . '/../core/class/DB.class.php';

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
  $indexes = DB::Prepare('show index from `'.$table['Tables_in_jeedom'].'`',array(),DB::FETCH_TYPE_ALL);
  $index_def = array();
  foreach ($indexes as $index) {
    $index_def[] = array(
      "Key_name"  => $index['Key_name'],
      'Non_unique' => $index['Non_unique'],
      "Seq_in_index"  => $index['Seq_in_index'],
      "Column_name"  => $index['Column_name']
    );
  }
  $database['tables'][] = array(
    'name' => $table['Tables_in_jeedom'],
    'fields' => $fields,
    'indexes' => $index_def,
  );
}
echo json_encode($database, JSON_PRETTY_PRINT);
?>
