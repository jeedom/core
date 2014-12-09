<?php

$cron = cron::byClassAndFunction('plugin', 'cron');
if (is_object($cron)) {
    $cron->remove();
}

$crons = cron::all();
foreach ($crons as $cron) {
    $c = new Cron\CronExpression($cron->getSchedule(), new Cron\FieldFactory);
    try {
        $c->getNextRunDate();
    } catch (Exception $ex) {
        $cron->remove();
    }
}
?>
