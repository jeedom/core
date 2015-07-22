<?php
echo 'Mise à jour de socket.io et express (peut etre long > 30min)';
echo shell_exec('cd ' . dirname(__FILE__) . '/../../core/nodeJS;rm -rf node_modules;sudo npm install socket.io;npm install express');
echo shell_exec('sudo service jeedom restart');
echo "OK\n";
?>