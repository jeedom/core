<?php

try {
	$sql = "ALTER TABLE `interactQuery`
			ADD `actions` text COLLATE 'utf8_general_ci' NULL;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
}

try {
	$sql = "ALTER TABLE `interactDef`
			ADD `actions` text COLLATE 'utf8_general_ci' NULL;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
}

try {
	$sql = "ALTER TABLE `interactDef`
			DROP `link_type`,
			DROP `link_id`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
}

try {
	$sql = "ALTER TABLE `interactQuery`
			DROP `link_type`,
			DROP `link_id`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
}

try {
	$sql = "ALTER TABLE `interactQuery`
			DROP `enable`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
}
?>










