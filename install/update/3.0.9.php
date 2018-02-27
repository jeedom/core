<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
echo 'Remove phpsysinfo and adminer';
shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../adminer*');
shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../sysinfo*');
echo "OK\n";
?>
