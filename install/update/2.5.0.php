<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	$sql = 'DROP TABLE `jeeNetwork`;';
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>