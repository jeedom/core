<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
echo 'Move cache and tmp jeedom to new folder (/tmp/jeedom). It can take some times....';
jeedom::stop();
shell_exec('sudo mkdir -p /tmp/jeedom');
shell_exec('sudo rm -rf  /tmp/jeedom/cache;sudo mv /tmp/jeedom-cache /tmp/jeedom/cache');
shell_exec('sudo touch /tmp/jeedom/started');
shell_exec('sudo chmod 777 -R /tmp/jeedom');
jeedom::start();
echo "OK\n";
?>