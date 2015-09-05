<?php
$hwname = jeedom::getHardwareName();
if ($hwname == 'RPI/RPI2') {
	echo 'Ajout dépot nodejs v12 pour RPI...';
	echo shell_exec('curl -sLS https://apt.adafruit.com/add | sudo bash');
	echo "OK\n";
	echo 'Mise à jour des depots';
	shell_exec('sudo apt-get update');
	echo "OK\n";
	echo 'Mise à jour nodejs';
	shell_exec('sudo apt-get -y install node');
	echo "OK\n";
} else {
	echo 'Ajout dépot nodejs v12...';
	exec("sudo sed -i '/deb http:\/\/repo.gbps.io\/BSP:\/Cubox-i\/Debian_Jessie\/ .\//d' /etc/apt/sources.list");
	echo shell_exec('curl -sL https://deb.nodesource.com/setup_0.12 | sudo bash -');
	echo "OK\n";
	echo 'Mise à jour des depots';
	echo shell_exec('sudo apt-get update');
	echo "OK\n";
	echo 'Mise à jour nodejs';
	echo shell_exec('sudo apt-get -y install nodejs');
	echo "OK\n";
}
echo 'Nettoyage des packets';
echo shell_exec('sudo apt-get -y autoremove');
echo "OK\n";
echo 'Redemarrage de nodejs';
echo shell_exec('sudo service jeedom restart');
echo "OK\n";
?>