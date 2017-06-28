<?php
echo "La maj peut être très longue selon les systèmes plus d'une heure pour certains\n";
try {
	$sql = "ALTER TABLE `plan`
ADD `configuration` text COLLATE 'utf8_general_ci' NULL;";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
	echo $e->getMessage();
}
echo "La maj peut être très longue selon les systèmes plus d'une heure pour certains\n";
?>