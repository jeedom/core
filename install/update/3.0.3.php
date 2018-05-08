<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	$sql = "ALTER TABLE `viewData`
	CHANGE `link_id` `link_id` int(11) NULL AFTER `type`;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
	echo $e->getMessage();
}
?>