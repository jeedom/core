<?php


$cron = cron::byClassAndFunction('history', 'archive');
if (is_object($cron)) {
	$cron->setSchedule('05 00 * * * *');
	$cron->setTimeout(60);
	$cron->save();
}

