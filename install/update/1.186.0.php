<?php
if (!file_exists('/usr/bin/node')) {
	exec('sudo ln -s /usr/bin/nodejs /usr/bin/node');
}
echo 'Installation/Mise à jour npm...';
exec('sudo apt-get install -y npm > /dev/null 2>&1');
echo "OK\n";
echo 'Installation/Mise à jour curl...';
exec('sudo apt-get install -y curl > /dev/null 2>&1');
echo "OK\n";
?>
?>