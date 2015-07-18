<?php
$hwname = jeedom::getHardwareName();
echo 'Ajout dépot nodejs v12...';
if ($hwname == 'RPI/RPI2') {

} else {
	exec("sufo sed -i '/deb http:\/\/repo.gbps.io\/BSP:\/Cubox-i\/Debian_Jessie\/ .\//d' /etc/apt/sources.list");
	exec('curl -sL https://deb.nodesource.com/setup_0.12 | sudo bash -');

}
echo "OK\n";
echo 'Mise à jour des depots';
exec('sudo apt-get update');
echo "OK\n";
echo 'Mise à jour nodejs';
exec('sudo apt-get install nodejs');
echo "OK\n";
?>
?>