<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class distrib {

	/*     * *************************Attributs****************************** */
	private static $_distrib = null;
	private static $_command = array(
		'suse' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' zypper in --non-interactive ', 'www-uid' => 'wwwrun', 'www-gid' => 'www'),
		'sles' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' zypper in --non-interactive ', 'www-uid' => 'wwwrun', 'www-gid' => 'www'),
		'redhat' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' yum install ', 'www-uid' => 'www-data', 'www-gid' => 'www-data'),
		'fedora' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' dnf install ', 'www-uid' => 'www-data', 'www-gid' => 'www-data'),
		'debian' => array('cmd_check' => ' dpkg --get-selections | grep -v deinstall | grep ', 'cmd_install' => ' apt-get install -y ', 'www-uid' => 'www-data', 'www-gid' => 'www-data'),
	);

	/*     * ***********************Methode static*************************** */

	public static function getDistrib() {
		if (self::$_distrib == null) {
			self::$_distrib = trim(shell_exec('sudo grep CPE_NAME /etc/os-release | cut -d \'"\' -f 2 | cut -d : -f 3 '));
			if (self::$_distrib == '') {
				self::$_distrib = trim(shell_exec('sudo  grep -e "^ID" /etc/os-release | cut -d \'=\' -f 2'));
			}
		}
		if (self::$_distrib == '' || !isset(self::$_command[self::$_distrib])) {
			self::$_distrib = 'debian';
		}
		return self::$_distrib;
	}

	public static function getCmdCheck() {
		return self::$_command[self::getDistrib()]['cmd_check'];
	}

	public static function getCmdInstall() {
		return self::$_command[self::getDistrib()]['cmd_install'];
		return $this->pm_cmd_install;
	}

	public static function getWWWUid() {
		return self::$_command[self::getDistrib()]['www-uid'];
		return $this->pm_www_user;
	}

	public static function getWWWGid() {
		return self::$_command[self::getDistrib()]['www-gid'];
		return $this->pm_www_group;
	}

	/*     * **********************Getteur Setteur*************************** */

}
