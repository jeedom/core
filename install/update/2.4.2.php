<?php
try {
	$sql = 'ALTER TABLE `cron`
DROP `pid`,
DROP `lastrun`,
DROP `state`;';
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
	$sql = 'ALTER TABLE `scenario`
DROP `state`,
DROP `lastLaunch`,
DROP `pid`;';
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>