<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	$sql = 'ALTER TABLE config MODIFY `value` TEXT;';
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>