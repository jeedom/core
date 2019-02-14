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

function databaseAnalyzer_database($_database){
  $return = array();
  foreach ($_database['tables'] as $table) {
    $return = array_merge($return,databaseAnalyzer_table($table));
  }
  return $return;
}


function databaseAnalyzer_table($_table){
  $describes = DB::Prepare('describe `'.$_table['name'].'`',array(),DB::FETCH_TYPE_ALL);
  $return =  array($_table['name'] => array('status' => 'ok','fields' => array(),'sql' => ''));
  if(count($describes) == 0){
    $return = array($_table['name'] => array(
      'status' => 'nok',
      'message' => 'Not found',
      'sql' => 'CREATE TABLE IF NOT EXISTS '.'`'.$_table['name'].'` (',
    ));
    foreach ($_table['fields'] as $field) {
      $return[$_table['name']]['sql'] .="\n". '`'.$field['name'].'``';
      if($field['null'] == 'NO'){
        $return[$_table['name']]['sql'] .= ' NOT NULL';
      }else{
        $return[$_table['name']]['sql'] .= ' NULL';
      }
      if($field['extra'] == 'auto_increment'){
        $return[$_table['name']]['sql'] .= ' AUTO_INCREMENT';
      }
      $return[$_table['name']]['sql'] .= ',';
    }
    $return[$_table['name']]['sql'] = trim($return[$_table['name']]['sql'],',');
    $return[$_table['name']]['sql'] .= ')';
    return $return;
  }
  foreach ($_table['fields'] as $field) {
    $found = false;
    foreach ($describes as $describe) {
      if($describe['Field'] != $field['name']){
        continue;
      }
      $return[$_table['name']]['fields'] = array_merge($return[$_table['name']]['fields'],databaseAnalyzer_field($field,$describe,$_table['name']));
      $found = true;
    }
    if(!$found){
      $return[$_table['name']]['fields'][$field['name']] = array(
        'status' => 'nok',
        'message' => 'Not found',
        'sql' => 'ALTER TABLE `'.$_table['name'].'` ADD `'.$field['name'].'`'
      );
      if($field['null'] == 'NO'){
        $return[$_table['name']]['fields'][$field['name']]['sql'] .= ' NOT NULL';
      }else{
        $return[$_table['name']]['fields'][$field['name']]['sql'] .= ' NULL';
      }
      if($field['extra'] == 'auto_increment'){
        $return[$_table['name']]['fields'][$field['name']]['sql'] .= ' AUTO_INCREMENT';
      }
    }
  }
  return $return;
}

function databaseAnalyzer_field($_ref_field,$_real_field,$_table_name){
  $return = array($_ref_field['name'] => array('status' => 'ok','sql' => ''));
  if($_ref_field['type'] != $_real_field['Type']){
    $return[$_ref_field['name']]['status'] = 'nok';
    $return[$_ref_field['name']]['message'] = 'Type nok';
  }
  if($_ref_field['null'] != $_real_field['Null']){
    $return[$_ref_field['name']]['status'] = 'nok';
    $return[$_ref_field['name']]['message'] = 'Null nok';
  }
  if($_ref_field['key'] != $_real_field['Key']){
    $return[$_ref_field['name']]['status'] = 'nok';
    $return[$_ref_field['name']]['message'] = 'Key nok';
  }
  if($_ref_field['default'] != $_real_field['Default']){
    $return[$_ref_field['name']]['status'] = 'nok';
    $return[$_ref_field['name']]['message'] = 'Default nok';
  }
  if($_ref_field['extra'] != $_real_field['Extra']){
    $return[$_ref_field['name']]['status'] = 'nok';
    $return[$_ref_field['name']]['message'] = 'Extra nok';
  }
  if($return[$_ref_field['name']]['status'] == 'nok'){
    $return[$_ref_field['name']]['sql'] = 'ALTER TABLE `'.$_table_name.'` MODIFY COLUMN `'.$_ref_field['name'].'` '.$_ref_field['type'];
    if($_ref_field['null'] == 'NO'){
      $return[$_ref_field['name']]['sql'] .= ' NOT NULL';
    }else{
      $return[$_ref_field['name']]['sql'] .= ' NULL';
    }
    if($_ref_field['extra'] == 'auto_increment'){
      $return[$_ref_field['name']]['sql'] .= ' AUTO_INCREMENT';
    }
    if($_ref_field['key'] == 'PRI'){
      $return[$_ref_field['name']]['sql'] .=';ALTER TABLE `'.$_table_name. '` ADD PRIMARY KEY(`'.$_ref_field['name'].'`)';
    }
  }
  return $return;
}
?>
