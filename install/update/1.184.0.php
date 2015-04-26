<?php
echo "\n";
echo '------------------------------------------------------------';
echo "\n";
echo 'Mise à jour des depots (peut prendre jusqu\'a 15min)';
echo "\n";
shell_exec('sudo apt-get update');
echo "\nok";
echo 'Mise à jour des packets (peut prendre jusqu\'a 30min)';
echo "\n";
shell_exec('sudo apt-get upgrade');
echo "\nok";
echo 'Installation de network-manager';
echo "\n";
shell_exec('sudo apt-get install -y network-manager');
echo "\nok";
echo "\n";
echo '------------------------------------------------------------';
echo "\n";
?>