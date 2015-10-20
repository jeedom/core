<?php
$sql = 'SELECT *
        FROM cache';
$caches = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, 'cache');
foreach ($caches as $cache) {
	$cache->save();
}
?>