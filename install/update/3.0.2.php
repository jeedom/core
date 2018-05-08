<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	$sql = "ALTER TABLE `cmd`
			ADD `alert` text COLLATE 'utf8_general_ci' NULL;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
	echo $e->getMessage();
}
?>