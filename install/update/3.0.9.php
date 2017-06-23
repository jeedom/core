<?php
echo 'Remove phpsysinfo and adminer';
shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../adminer*');
shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../../sysinfo*');
echo "OK\n";
?>