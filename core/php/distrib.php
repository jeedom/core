<?php

class Distrib {
	private $pm_cmd_check;
	private $pm_cmd_install;
	private $pm_www_uid;
	private $pm_www_gid;

	function __construct() {
		$distribs = array('suse', 'sles', 'redhat', 'fedora', 'debian');

		$system_command['suse']   = array("cmd_check" => " rpm -qa | grep ", "cmd_install" => " zypper in --non-interactive ", "www-uid" => "wwwrun", "www-gid" => "www");
		$system_command['sles']   = array("cmd_check" => " rpm -qa | grep ", "cmd_install" => " zypper in --non-interactive ", "www-uid" => "wwwrun", "www-gid" => "www");
		$system_command['redhat'] = array("cmd_check" => " rpm -qa | grep ", "cmd_install" => " yum install ", "www-uid" => "www-data", "www-gid" => "www-data");
		$system_command['fedora'] = array("cmd_check" => " rpm -qa | grep ", "cmd_install" => " dnf install ", "www-uid" => "www-data", "www-gid" => "www-data");
		$system_command['debian'] = array("cmd_check" => " dpkg --get-selections | grep -v deinstall | grep ", "cmd_install" =>" apt-get install -y ", "www-uid" => "www-data", "www-gid" => "www-data");

		$os_release=trim(shell_exec('sudo grep CPE_NAME /etc/os-release | cut -d \'"\' -f 2 | cut -d : -f 3 '));
		foreach ($distribs as $distrib) {
			if ( $os_release == $distrib ) {
				$this->pm_cmd_check   = $system_command[$distrib]['cmd_check'];
				$this->pm_cmd_install = $system_command[$distrib]['cmd_install'];
				$this->pm_www_user    = $system_command[$distrib]['www-uid'];
				$this->pm_www_group   = $system_command[$distrib]['www-gid'];
			}
		}
	}

	function getCmdCheck() {
		return $this->pm_cmd_check;
	}

	function getCmdInstall() {
		return $this->pm_cmd_install;
	}

	function getWWWUid() {
		return $this->pm_www_user;
	}

	function getWWWGid() {
		return $this->pm_www_group;
	}
}

global $distrib;
$distrib = new Distrib;
