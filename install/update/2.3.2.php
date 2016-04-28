<?php
try {
	$sql = "ALTER TABLE `update`
			ADD `source` varchar(127) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'market' AFTER `status`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>

