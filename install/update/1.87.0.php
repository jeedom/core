<?php

$cron = cron::byClassAndFunction('plugin', 'cronDaily');
if (!is_object($cron)) {
    $cron = new cron();
    $cron->setClass('plugin');
    $cron->setFunction('cronDaily');
    $cron->setSchedule('00 00 * * * *');
    $cron->setTimeout(5);
    $cron->save();
}



$cron = cron::byClassAndFunction('plugin', 'cronHourly');
if (!is_object($cron)) {
    $cron = new cron();
    $cron->setClass('plugin');
    $cron->setFunction('cronHourly');
    $cron->setSchedule('00 * * * * *');
    $cron->setTimeout(5);
    $cron->save();
}
