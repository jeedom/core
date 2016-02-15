<?php

try {
	$sql = "ALTER TABLE `cmd`
	DROP `cache`,
	DROP `eventOnly`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}

try {
	$sql = "ALTER TABLE `cmd`
	ADD `html` mediumtext NULL AFTER `eqLogic_id`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>










