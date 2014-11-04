<?php

$cron = cron::byClassAndFunction('history', 'archive');
if (is_object($cron)) {
    $cron->setSchedule('00 * * * * *');
    $cron->save();
}

$cron = cron::byClassAndFunction('jeedom', 'persist');
if (is_object($cron)) {
    $cron->setSchedule('*/5 * * * * *');
    $cron->save();
}

$cron = cron::byClassAndFunction('cmd', 'collect');
if (is_object($cron)) {
    $cron->setSchedule('* * * * * *');
    $cron->save();
}

