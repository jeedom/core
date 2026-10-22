<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
echo shell_exec('sudo cp /var/www/html/install/apache_security /etc/apache2/conf-available/security.conf;sudo a2enmod headers;echo "systemctl restart apache2" | sudo at now');