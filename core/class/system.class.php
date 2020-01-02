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

/**
 * Various operating system interfaces
 *
 * This class provides portable ways to call system dependent
 * functionalities.
 */
class system {

	private static $_distrib = null;
	private static $_command = array(
		'suse' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' zypper in --non-interactive ', 'www-uid' => 'wwwrun', 'www-gid' => 'www', 'type' => 'zypper'),
		'sles' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' zypper in --non-interactive ', 'www-uid' => 'wwwrun', 'www-gid' => 'www', 'type' => 'zypper'),
		'redhat' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' yum install ', 'www-uid' => 'www-data', 'www-gid' => 'www-data', 'type' => 'yum'),
		'fedora' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' dnf install ', 'www-uid' => 'www-data', 'www-gid' => 'www-data', 'type' => 'dnf'),
		'debian' => array('cmd_check' => ' dpkg --get-selections | grep -v deinstall | grep ', 'cmd_install' => ' apt-get install -y ', 'www-uid' => 'www-data', 'www-gid' => 'www-data', 'type' => 'apt'),
	);

	/*     * ***********************Methode static*************************** */

	/**
	 * Load predefined distribution-specific data from configuration file
	 *
	 * This method updates internal data from settings in the
	 * `core/config/system_cmd.json` file. This file does not currently
	 * exist in the core Jeedom distribution. It can be used as a
	 * customization layer by a specific installation.
	 *
	 * The `system` class already contains sensible default values for
	 * common distributions.
	 *
	 * @return array Loaded settings
	 */
	public static function loadCommand() {
		if (file_exists(__DIR__ . '/../../config/system_cmd.json')) {
			$content = file_get_contents(__DIR__ . '/../../config/system_cmd.json');
			if (is_json($content)) {
				self::$_command['custom'] = json_decode($content, true);
			}
		}
		return self::$_command;
	}

	/**
	 * Find the host distribution name
	 *
	 * This methods tries to guess the Linux distribution name which
	 * runs the current instance of Jeedom. If no reliable guess is
	 * possible, the distribution name defaults to `'debian`'.
	 *
	 * Current registered distribution names include `'suse'`, `'sles'`,
	 * `'redhat'`, `'fedora'`, `'debian'`.
	 *
	 * @return string name of the distribution
	 */
	public static function getDistrib() {
		self::loadCommand();
		if (isset(self::$_command['custom'])) {
			return 'custom';
		}
		if (self::$_distrib === null) {
			self::$_distrib = trim(shell_exec('grep CPE_NAME /etc/os-release | cut -d \'"\' -f 2 | cut -d : -f 3 '));
			if (self::$_distrib == '') {
				self::$_distrib = trim(shell_exec('grep -e "^ID" /etc/os-release | cut -d \'=\' -f 2'));
			}
			if (self::$_distrib == '' || !isset(self::$_command[self::$_distrib])) {
				self::$_distrib = 'debian';
			}
		}
		return self::$_distrib;
	}

	/**
	 * Fetch a distribution-specific setting from a given key
	 *
	 * Already registered keys include:
	 * + `'cmd_check'`: shell command to find if a given package is installed,
	 * + `'cmd_install'`: shell command to install a package using the distribution package manager,
	 * + '`www-uid'`: username running the web server,
	 * + '`www-gid'`: group name running the web server,
	 * + '`type'`: generic type of the package manager (e.g. `'apt'`, `'yum'`, * `'dnf'`, `'zypper'`).
	 *
	 * @param $_key string Name of the requested setting
	 * @return mixed Value of the requested setting
	 */
	public static function get($_key = '') {
		$return = '';
		if (isset(self::$_command[self::getDistrib()]) && isset(self::$_command[self::getDistrib()][$_key])) {
			$return = self::$_command[self::getDistrib()][$_key];
		}
		if ($return == '') {
			if ($_key == 'www-uid') {
				$processUser = posix_getpwuid(posix_geteuid());
				$return = $processUser['name'];
			}
			if ($_key == 'www-gid') {
				$processGroup = posix_getgrgid(posix_getegid());
				$return = $processGroup['name'];
			}
		}
		return $return;
	}

	/**
	 * Return a suitable command to gain superuser access
	 *
	 * This method returns a command which can be prefixed to a regular
	 * shell call in order to run this call with superuser (root)
	 * rights. It is typically `'sudo'` if such a call is allowed by the
	 * system configuration. This method may return the empty string if
	 * we are not allowed to call sudo.
	 *
	 * @return string
	 */
	public static function getCmdSudo() {
		if (!jeedom::isCapable('sudo')) {
			return '';
		}
		return 'sudo ';
	}

	/**
	 * Kill a process accessing a given file or network port.
	 *
	 * This methods runs the Unix command '`fuser -k'` in order to kill
	 * a process listening locally on a given file or network port.
	 *
	 * @param $_port mixed Filename or network port
	 * @param $_protocol string Network protocol (tcp or udp)
	 */
	public static function fuserk($_port, $_protocol = 'tcp') {
		if (file_exists($_port)) {
			exec(system::getCmdSudo() . 'fuser -k ' . $_port . ' > /dev/null 2>&1');
		} else {
			exec(system::getCmdSudo() . 'fuser -k ' . $_port . '/' . $_protocol . ' > /dev/null 2>&1');
		}
	}

	/**
	 * Find a running process, given part of its command-line
	 *
	 * @param $_find string String to be searched in the process list
	 * @param $_without string|null Items matching this string will be excluded from the result
	 * @return array
	 */
	public static function ps($_find, $_without = null) {
		$return = array();
		$cmd = '(ps ax || ps w) | grep -ie "' . $_find . '" | grep -v "grep"';
		if ($_without != null) {
			if (!is_array($_without)) {
				$_without = array($_without);
			}
			foreach ($_without as $value) {
				$cmd .= ' | grep -v "' . $value . '"';
			}
		}
		$results = explode("\n", trim(shell_exec($cmd)));
		if (!is_array($results) || count($results) == 0) {
			return $return;
		}
		$order = array('pid', 'tty', 'stat', 'time', 'command');
		foreach ($results as $result) {
			if (trim($result) == '') {
				continue;
			}
			$explodes = explode(" ", $result);
			$info = array();
			$i = 0;
			foreach ($explodes as $value) {
				if (trim($value) == '') {
					continue;
				}
				if (isset($order[$i])) {
					$info[$order[$i]] = trim($value);
				} else {
					$info[end($order)] = $info[end($order)] . ' ' . trim($value);

				}
				$i++;
			}
			$return[] = $info;
		}
		return $return;
	}

	/**
	 * Kill a process from its PID or a part of its command-line
	 *
	 * This method sends a SIGTERM to a process in order to terminate
	 * it. If this call fails, it may try to send a SIGKILL signal to
	 * the process, using if necessary superuser rights.
	 *
	 * @param integer|string $_find PID or name of the process which should be killed
	 * @param boolean $_kill9 Use a SIGKILL if a regular SIGTERM fails
	 * @return boolean Boolean indicating whether process was successfully terminated
	 */
	public static function kill($_find = '', $_kill9 = true) {
		if (trim($_find) == '') {
			return;
		}
		if (is_numeric($_find)) {
			$kill = posix_kill($_find, 15);
			if ($kill) {
				return true;
			}
			if ($_kill9) {
				usleep(100);
				$kill = posix_kill($_find, 9);
				if ($kill) {
					return true;
				}
				usleep(100);
				exec(system::getCmdSudo() . 'kill -9 ' . $_find);
			} else {
				$kill = posix_kill($_find, 15);
			}
			return;
		}
		if ($_kill9) {
			$cmd = "(ps ax || ps w) | grep -ie '" . $_find . "' | grep -v grep | awk '{print $1}' | xargs " . system::getCmdSudo() . "kill -9 > /dev/null 2>&1";
		} else {
			$cmd = "(ps ax || ps w) | grep -ie '" . $_find . "' | grep -v grep | awk '{print $1}' | xargs " . system::getCmdSudo() . "kill > /dev/null 2>&1";
		}
		exec($cmd);
	}

	/**
	 * Command-line call to run the PHP interpretor
	 *
	 * @param $arguments string Arguments passed to the PHP interpretor
	 * @param $_sudo boolean Whether to run with superuser rights or not
	 * @return string Last output line of the command
	 */
	public static function php($arguments, $_sudo = false) {
		if ($_sudo) {
			return exec(self::getCmdSudo() . ' php ' . $arguments);
		}
		return exec('php ' . $arguments);
	}
}
