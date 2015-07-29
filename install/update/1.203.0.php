<?php
if (jeedom::getHardwareName() == 'Jeedomboard') {
	echo '**************Mise à jour du système (peut etre très long)**************';
	echo shell_exec('sudo touch /var/log/auth.log');
	echo 'Mise à jour des sources';
	echo shell_exec('sudo apt-get update');
	echo "OK\n";
	echo 'Mise à jour des paquets';
	echo shell_exec('sudo apt-get dist-upgrade');
	echo "OK\n";
	echo 'Redemarrage fail2ban';
	echo shell_exec('sudo service fail2ban restart');
	echo "OK\n";
}
?>