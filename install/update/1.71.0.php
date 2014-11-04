<?php
$cron = new cron();
$cron->setClass('plugin');
$cron->setFunction('cronDaily');
$cron->setSchedule('00 00 * * * *');
$cron->setTimeout(5);
$cron->save();

$cron = new cron();
$cron->setClass('plugin');
$cron->setFunction('cronHourly');
$cron->setSchedule('00 * * * * *');
$cron->setTimeout(5);
$cron->save();