<?php
echo 'Add Jeedom watchdog';
$cdir = dirname(__FILE__);
if (!file_exists('/etc/cron.d/jeedom_watchdog')) {
	file_put_contents('/tmp/jeedom_watchdog', '* * * * * root /usr/bin/php ' . $cdir . '/../../core/php/watchdog.php >> /dev/null' . "\n");
	exec('sudo mv /tmp/jeedom_watchdog /etc/cron.d/jeedom_watchdog;sudo chown root:root /etc/cron.d/jeedom_watchdog');
}
if (file_exists('/etc/cron.d/jeedom_watchdog')) {
	echo " OK\n";
} else {
	echo " NOK\n";
}
?>