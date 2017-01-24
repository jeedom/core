<?php

$distribs = array('suse', 'sles', 'redhat', 'fedora', 'debian');

$system_command['suse']   = array("cmd_check" => " rpm -qa | grep ", "cmd_install" =>"zypper in --non-interactive ", "www-uid" => "www-run", "www-gid" => "www");
$system_command['sles']   = array("cmd_check" => " rpm -qa | grep ", "cmd_install" =>"zypper in --non-interactive ", "www-uid" => "www-run", "www-gid" => "www");
$system_command['redhat'] = array("cmd_check" => " rpm -qa | grep ", "cmd_install" =>"yum install ", "www-uid" => "www-data", "www-gid" => "www");
$system_command['fedora'] = array("cmd_check" => " rpm -qa | grep ", "cmd_install" =>"dnf install ", "www-uid" => "www-data", "www-gid" => "www");
$system_command['debian'] = array("cmd_check" => " dpkg --get-selections | grep -v deinstall | grep ", "cmd_install" =>" apt-get install -y ", "www-uid" => "www-data", "www-gid" => "www");;

$os_release=shell_exec('grep CPE_NAME /etc/os-release | cut -d \'"\' -f 2 | cut -d : -f 3 ');
foreach ($distribs as $distrib) {
	if ( $os_release = $distrib ) {
		$pm_cmd_check   = $system_command[$distrib]['cmd_check'];
		$pm_cmd_install = $system_command[$distrib]['cmd_install'];
		$pm_www_user    = $system_command[$distrib]['www-uid'];
		$pm_www_group   = $system_command[$distrib]['www-gid'];
	}
}
