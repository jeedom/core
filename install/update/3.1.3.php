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
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
	echo 'Remove deb-multimedia repository';
	exec('sudo rm -rf /etc/apt/sources.list.d/deb-multimedia.list');
	echo " OK\n";
	echo 'Update APT sources\n';
	exec('sudo apt-get update');
}
if (config::byKey('core::branch') == 'beta') {
	config::save('core::branch', 'master', 'core');
}
?>
