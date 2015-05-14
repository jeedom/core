<?php
echo 'Installation/Mise à jour npm...';
exec('sudo npm install -y npm > /dev/null 2>&1');
echo "OK\n";
?>