<?php
$hwname = jeedom::getHardwareName();
if ($hwname == 'RPI/RPI2') {
	echo 'Ajout dépot nodejs v12 pour RPI...';
	exec('curl -sLS https://apt.adafruit.com/add | sudo bash');
	echo "OK\n";
	echo 'Mise à jour des depots';
	exec('sudo apt-get update');
	echo "OK\n";
	echo 'Mise à jour nodejs';
	exec('sudo apt-get install node');
	echo "OK\n";
} else {
	echo 'Ajout dépot nodejs v12...';
	exec("sudo sed -i '/deb http:\/\/repo.gbps.io\/BSP:\/Cubox-i\/Debian_Jessie\/ .\//d' /etc/apt/sources.list");
	exec('curl -sL https://deb.nodesource.com/setup_0.12 | sudo bash -');
	echo "OK\n";
	echo 'Mise à jour des depots';
	exec('sudo apt-get update');
	echo "OK\n";
	echo 'Mise à jour nodejs';
	exec('sudo apt-get install nodejs');
	echo "OK\n";
}
?>