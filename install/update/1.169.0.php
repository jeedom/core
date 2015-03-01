<?php
$tables = DB::Prepare("SHOW TABLES", array(), DB::FETCH_TYPE_ALL);
foreach ($tables as $table) {
	$table = array_values($table);
	$table = $table[0];
	try {
		DB::Prepare('ALTER TABLE `' . $table . '` CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci', array(), DB::FETCH_TYPE_ROW);
	} catch (Exception $e) {

	}
}
?>