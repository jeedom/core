<?php

$cron = cron::byClassAndFunction('plugin', 'cron');
if (is_object($cron)) {
    $cron->remove();
}

$cron = cron::byClassAndFunction('jeedom', 'persist');
if (is_object($cron)) {
    $cron->remove();
}

$cron = cron::byClassAndFunction('cmd', 'collect');
if (is_object($cron)) {
    $cron->setSchedule('*/5 * * * * *');
    $cron->save();
}

