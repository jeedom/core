<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
try {
	$sql = "ALTER TABLE `object` RENAME `jeeObject`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
	echo $e->getMessage();
}
try {
	$sql = "ALTER TABLE `eqLogic` CHANGE object_id jeeObject_id INT;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
	echo $e->getMessage();
}
try {
	$sql = "ALTER TABLE `scenario` CHANGE object_id jeeObject_id INT;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
	echo $e->getMessage();
}