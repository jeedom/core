<?php

$cron_find = array();
foreach (cron::all() as $cron) {
    if (!isset($cron_find[$cron->getClass() . '::' . $cron->getFunction()])) {
        $cron_find[$cron->getClass() . '::' . $cron->getFunction()] = 1;
    } else {
        $cron->remove();
    }
}

$sql = 'DROP INDEX `class_function` ON `cron`';
try {
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $e) {
    
}
$sql = 'ALTER TABLE `cron` 
ADD UNIQUE INDEX `class_function` (`class` ASC, `function` ASC)';
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
?>