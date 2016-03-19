<?php

try {
	$sql = "ALTER TABLE `view`
	ADD `order` INT NULL AFTER `display`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}

?>










