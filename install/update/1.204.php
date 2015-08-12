<?php
if (jeedom::getHardwareName() == 'Jeedomboard' && jeedom::isCapable('sudo')) {
	echo "Revert nodejs version";
	exec('sudo rm /etc/apt/sources.list.d/nodesource.list');
	echo "Update depot";
	exec('sudo apt-get update');
	echo "Install nodejs 0.10.38";
	exec('sudo apt-get -y install nodejs=0.10.38~dfsg-1');
}
?>