<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	$sql = "ALTER TABLE cron  DROP INDEX class_function";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {

}
?>