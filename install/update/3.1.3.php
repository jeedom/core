<?php
echo 'Add Jeedom watchdog';
$cdir = dirname(__FILE__);
if (!file_exists('/etc/cron.d/jeedom_watchdog')) {
	file_put_contents('/etc/cron.d/jeedom_watchdog', '* * * * * root /usr/bin/php ' . $cdir . '/../core/php/watchdog.php >> /dev/null');
}
if (!file_exists('/etc/cron.d/jeedom_watchdog')) {
	echo "OK\n";
} else {
	echo "NOK\n";
}
?>