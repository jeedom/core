<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
echo 'Remove phpsysinfo and adminer';
shell_exec('sudo rm -rf ' . __DIR__ . '/../../../adminer*');
shell_exec('sudo rm -rf ' . __DIR__ . '/../../../sysinfo*');
echo "OK\n";
?>
