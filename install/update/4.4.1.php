<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
shell_exec('sudo rm /etc/fail2ban/jail.d/jeedom.conf');
shell_exec('sudo cp ' . __DIR__ . '/../../install/fail2ban.jeedom.conf /etc/fail2ban/jail.d/jeedom.conf');
shell_exec('sudo systemctl restart fail2ban');
