<?php
echo 'Remove phpsysinfo and adminer';
shell_exec(system::getCmdSudo() . 'rm -rf ' . dirname(__FILE__) . '/../../../adminer*');
shell_exec(system::getCmdSudo() . 'rm -rf ' . dirname(__FILE__) . '/../../../sysinfo*');
echo "OK\n";
?>