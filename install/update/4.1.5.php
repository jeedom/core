<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if(file_exists('/etc/zabbix/zabbix_agentd.conf') && exec('grep "jeedom.com" /etc/zabbix/zabbix_agentd.conf | grep -v "zabbix.jeedom.com" | wc -l') != 0){
  $cmd = system::getCmdSudo() . ' systemctl restart zabbix-agent;';
  $cmd .= system::getCmdSudo() . ' systemctl enable zabbix-agent;';
  $cmd .= system::getCmdSudo() . ' apt-get -y remove zabbix-agent';
  shell_exec($cmd);
}
