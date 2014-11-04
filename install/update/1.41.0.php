<?php

foreach (cron::all() as $cron) {
    if ($cron->getFunction() == 'cronPlugin' && $cron->getClass() == '') {
        $cron->remove();
    }
    if ($cron->getFunction() == 'cronCore' && $cron->getClass() == '') {
        $cron->remove();
    }
    if ($cron->getFunction() == 'historize' && $cron->getClass() == 'history') {
        $cron->setTimeout(5);
        $cron->save();
    }
    if ($cron->getFunction() == 'archive' && $cron->getClass() == 'history') {
        $cron->setTimeout(20);
        $cron->save();
    }
    if ($cron->getFunction() == 'check' && $cron->getClass() == 'scenario') {
        $cron->setTimeout(5);
        $cron->save();
    }
    if ($cron->getFunction() == 'collect' && $cron->getClass() == 'cmd') {
        $cron->setTimeout(5);
        $cron->save();
    }
}

$cron = new cron();
$cron->setClass('jeedom');
$cron->setFunction('persist');
$cron->setSchedule('* * * * * *');
$cron->setTimeout(5);
$cron->save();

$cron = new cron();
$cron->setClass('jeedom');
$cron->setFunction('cron');
$cron->setSchedule('* * * * * *');
$cron->setTimeout(60);
$cron->save();

$cron = new cron();
$cron->setClass('plugin');
$cron->setFunction('cron');
$cron->setSchedule('* * * * * *');
$cron->setTimeout(5);
$cron->save();
