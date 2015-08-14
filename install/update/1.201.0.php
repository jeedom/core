<?php
echo 'Mise à jour/installation curl';
echo shell_exec('sudo apt-get -y install curl');
echo "OK\n";
?>