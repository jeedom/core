<?php
if (!file_exists('/usr/bin/node')) {
	exec('sudo ln -s /usr/bin/nodejs /usr/bin/node');
}
echo 'Installation/Mise à jour npm...';
exec('sudo npm install -y npm > /dev/null 2>&1');
echo "OK\n";
?>