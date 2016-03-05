<?php

try {
	$sql = "ALTER TABLE `cron`
			DROP `priority`,
			DROP `server`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}

?>










