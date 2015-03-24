<?php
$tables = DB::Prepare("SHOW TABLES", array(), DB::FETCH_TYPE_ALL);
echo "OK\n";
foreach ($tables as $table) {
	$table = array_values($table);
	$table = $table[0];
	DB::Prepare('ALTER TABLE `' . $table . '` CONVERT TO CHARACTER SET utf8', array(), DB::FETCH_TYPE_ROW);
}
?>