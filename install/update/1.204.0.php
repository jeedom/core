<?php
if (jeedom::getHardwareName() == 'Jeedomboard' && jeedom::isCapable('sudo')) {
	echo "Revert nodejs version";
	exec('sudo rm /etc/apt/sources.list.d/nodesource.list');
	echo "Update depot";
	exec('sudo apt-get update');
	echo "Install nodejs 0.10.XX";
	exec('sudo apt-get --reinstall install -y --force-yes nodejs=$(apt-cache madison nodejs | head -n 1 | cut -f 2 -d "|" | xargs)');
	exec('sudo apt-get --reinstall install -y --force-yes npm');
}
?>