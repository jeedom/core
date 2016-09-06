<?php
try {
	$sql = 'DROP TABLE `jeeNetwork`;';
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>