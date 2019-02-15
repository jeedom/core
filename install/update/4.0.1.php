<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$sql = "SELECT concat('ALTER TABLE ', TABLE_NAME, ' DROP FOREIGN KEY ', CONSTRAINT_NAME, ';')
FROM information_schema.key_column_usage
WHERE CONSTRAINT_SCHEMA = 'jeedom'
AND REFERENCED_TABLE_NAME IS NOT NULL;";
$result = DB::prepare($sql,array(),DB::FETCH_TYPE_ALL);
foreach ($result as $value) {
  try {
    DB::prepare(array_values($value)[0],array(),DB::FETCH_TYPE_ALL);
  } catch (\Exception $e) {
    
  }
}
?>
