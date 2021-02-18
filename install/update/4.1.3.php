<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$cmd = "sudo mkdir -p /lib/systemd/system/mariadb.service.d;";
$cmd .= "sudo echo '[Service]' > /lib/systemd/system/mariadb.service.d/override.conf;";
$cmd .= "sudo echo 'Restart=always' >> /lib/systemd/system/mariadb.service.d/override.conf;";
$cmd .= "sudo echo 'RestartSec=10' >> /lib/systemd/system/mariadb.service.d/override.conf;";
$cmd .= "sudo mkdir -p /lib/systemd/system/apache2.service.d;";
$cmd .= "sudo echo '[Service]' > /lib/systemd/system/apache2.service.d/override.conf;";
$cmd .= "sudo echo 'Restart=always' >> /lib/systemd/system/apache2.service.d/override.conf;";
$cmd .= "sudo echo 'RestartSec=10' >> /lib/systemd/system/apache2.service.d/override.conf;";
$cmd .= "sudo mkdir -p /lib/systemd/system/fail2ban.service.d;";
$cmd .= "sudo echo '[Service]' > /lib/systemd/system/fail2ban.service.d/override.conf;";
$cmd .= "sudo echo 'Restart=always' >> /lib/systemd/system/fail2ban.service.d/override.conf;";
$cmd .= "sudo echo 'RestartSec=10' >> /lib/systemd/system/fail2ban.service.d/override.conf;";
$cmd .= "sudo systemctl daemon-reload;";
exec($cmd);
