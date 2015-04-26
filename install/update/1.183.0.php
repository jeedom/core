<?php
echo 'Mise à jour des depots (peut prendre jusqu\'a 30min)';
echo "\n";
shell_exec('sudo apt-get update');
echo "\nok";
echo 'Installation de network-manager';
echo "\n";
shell_exec('sudo apt-get install -y network-manager');
echo "\nok";
?>