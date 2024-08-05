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

class system {

	private static $_hasExec = array();
	private static $_os_version = null;
	private static $_installPackage = array();
	private static $_distrib = null;
	private static $_command = array(
		'suse' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' zypper in --non-interactive ', 'www-uid' => 'wwwrun', 'www-gid' => 'www', 'type' => 'zypper'),
		'sles' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' zypper in --non-interactive ', 'www-uid' => 'wwwrun', 'www-gid' => 'www', 'type' => 'zypper'),
		'redhat' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' yum install ', 'www-uid' => 'www-data', 'www-gid' => 'www-data', 'type' => 'yum'),
		'fedora' => array('cmd_check' => ' rpm -qa | grep ', 'cmd_install' => ' dnf install ', 'www-uid' => 'www-data', 'www-gid' => 'www-data', 'type' => 'dnf'),
		'debian' => array('cmd_check' => ' dpkg --get-selections | grep -v deinstall | grep ', 'cmd_install' => ' apt-get install -y ', 'www-uid' => 'www-data', 'www-gid' => 'www-data', 'type' => 'apt'),
	);

	/*     * ***********************Methode static*************************** */

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
	 *
	 * @return string/object self::
	 */
	public static function getDistrib() {
		self::loadCommand();
		if (isset(self::$_command['custom'])) {
			return 'custom';
		}
		if (self::$_distrib === null) {
			self::$_distrib = trim(shell_exec('grep CPE_NAME /etc/os-release | cut -d \'"\' -f 2 | cut -d : -f 3 ') ?? '');
			if (self::$_distrib == '') {
				self::$_distrib = trim(shell_exec('grep -e "^ID" /etc/os-release | cut -d \'=\' -f 2'));
			}
			if (self::$_distrib == '' || !isset(self::$_command[self::$_distrib])) {
				self::$_distrib = 'debian';
			}
		}
		return self::$_distrib;
	}

	public static function get($_key = '') {
		$return = '';
		if (isset(self::$_command[self::getDistrib()][$_key])) {
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

	public static function getCmdSudo(): string {
		if (!class_exists('jeedom')) {
			return 'sudo ';
		}
		if (!jeedom::isCapable('sudo')) {
			return '';
		}
		return 'sudo ';
	}

	public static function fuserk($_port, $_protocol = 'tcp'): void {
		if (!is_string($_port)) return;

		if (file_exists($_port)) {
			exec(system::getCmdSudo() . 'fuser -k ' . $_port . ' > /dev/null 2>&1');
		} else {
			exec(system::getCmdSudo() . 'fuser -k ' . $_port . '/' . $_protocol . ' > /dev/null 2>&1');
		}
	}

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

	public static function kill($_find = '', $_kill9 = true) {
		if (trim($_find) == '') {
			return;
		}
		if (is_numeric($_find)) {
			if ($_find === 0) {
				return;
			}
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

	public static function php($arguments, $_sudo = false) {
		if ($_sudo) {
			return exec(self::getCmdSudo() . ' php ' . $arguments);
		}
		return exec('php ' . $arguments);
	}

	public static function getArch(): string {
		$arch = php_uname('m');
		if ($arch == 'x86_64') {
			return 'amd64';
		}
		if ($arch == 'aarch64') {
			return 'arm64';
		}
		if ($arch == 'armv7l' || $arch == 'armv6l') {
			return 'arm';
		}
		return $arch;
	}

	public static function getUpgradablePackage($_type, $_forceRefresh = false) {
		$return = array($_type => array());
		switch ($_type) {
			case 'apt':
				if ($_forceRefresh) {
					shell_exec(system::getCmdSudo() . ' apt update 2>/dev/null');
				}
				$lines = explode("\n", shell_exec(system::getCmdSudo() . ' apt list --upgradable 2>/dev/null'));
				foreach ($lines as $line) {
					if (strpos($line, '/') === false) {
						continue;
					}
					$infos = array_values(array_filter(explode(" ", $line)));
					$name = explode("/", $infos[0])[0];
					$return[$_type][$name] = array(
						'name' => $name,
						'type' => 'apt',
						'platform' => $infos[2],
						'current_version' => trim($infos[5], ']'),
						'new_version' => $infos[1],
					);
				}
				break;
			case 'pip3':
				if (version_compare(self::getOsVersion(), '12', '>=')) {
					return array();
				}
				$ignore_package = array('dbus-python', 'gpg', 'pycairo', 'pycurl', 'PyGObject');
				$ignore_arg = '';
				foreach ($ignore_package as $package) {
					$ignore_arg .= " --exclude {$package}";
				}
				$datas = json_decode(shell_exec(system::getCmdSudo() . " pip3 list {$ignore_arg} --outdated --format=json 2>/dev/null"), true);
				if (count($datas) > 0) {
					foreach ($datas as $value) {
						$return[$_type][$value['name']] = array(
							'name' => $value['name'],
							'type' => 'pip3',
							'current_version' => $value['version'],
							'new_version' => $value['latest_version'],
						);
					}
				}
				break;
			case 'pip2':
				if (self::os_incompatible('pip2', '', '')) {
					return array();
				}
				$datas = json_decode(shell_exec(system::getCmdSudo() . ' pip list --outdated --format=json 2>/dev/null'), true);
				if (count($datas) > 0) {
					foreach ($datas as $value) {
						$return[$_type][$value['name']] = array(
							'name' => $value['name'],
							'type' => 'pip2',
							'current_version' => $value['version'],
							'new_version' => $value['latest_version'],
						);
					}
				}
				break;
		}
		return $return;
	}

	public static function upgradePackage($_type, $_package = null) {
		$cmd = "set -x\n";
		$cmd .= "echo '*******************Begin of package upgrade type " . $_type . "******************'\n";
		switch ($_type) {
			case 'apt':
				if ($_package == null) {
					$cmd .= system::getCmdSudo() . " apt update\n";
					$cmd .= system::getCmdSudo() . ' apt -o Dpkg::Options::="--force-confdef" -y upgrade' . "\n";
				} else {
					$cmd .= self::installPackage($_type, $_package);
				}
				break;
			case 'pip3':
				if (version_compare(self::getOsVersion(), '12', '>=')) {
					return;
				}
				if ($_package == null) {
					$packages = self::getUpgradablePackage($_type);
					if (count($packages) == '') {
						return;
					}
					foreach ($packages[$_type] as $package) {
						$cmd .= self::installPackage($_type, $package['name']) . "\n";
					}
				} else {
					$cmd .= self::installPackage($_type, $_package) . "\n";
				}
				break;
			case 'pip2':
				if (self::os_incompatible('pip2', '', '')) {
					return;
				}
				if ($_package == null) {
					$packages = self::getUpgradablePackage($_type);
					if (count($packages) == '') {
						return '';
					}
					foreach ($packages[$_type] as $package) {
						$cmd .= self::installPackage($_type, $package['name']) . "\n";
					}
				} else {
					$cmd .= self::installPackage($_type, $_package) . "\n";
				}
				break;
		}
		$cmd .= "echo '*******************End of package installation******************'\n";
		if (file_exists('/tmp/jeedom_fix_package')) {
			shell_exec(system::getCmdSudo() . ' rm /tmp/jeedom_fix_package');
		}
		file_put_contents('/tmp/jeedom_fix_package', $cmd);
		self::launchScriptPackage();
	}

	private static function getPython3VenvDir($_plugin) {
		if ($_plugin == '') return '';
		return __DIR__ . "/../../plugins/{$_plugin}/resources/python_venv";
	}

	public static function getCmdPython3($_plugin) {
		if ($_plugin == '') return 'python3 ';

		if (version_compare(self::getOsVersion(), '12', '<')) {
			return 'python3 ';
		} else {
			return self::getPython3VenvDir($_plugin) . '/bin/python3 ';
		}
	}

	private static function splitpackageByPlugin($_type, $_plugin = '') {
		if (version_compare(self::getOsVersion(), '12', '>=') && in_array($_type, ['pip3']) && $_plugin != '') {
			return true;
		} else {
			return false;
		}
	}

	public static function getInstallPackage($_type, $_plugin) {
		if (self::splitpackageByPlugin($_type, $_plugin)) {
			$type_key = $_type . '::' . $_plugin;
		} else {
			$type_key = $_type;
		}

		if (isset(self::$_installPackage[$type_key])) {
			return self::$_installPackage[$type_key];
		}
		self::$_installPackage[$type_key] = array();
		switch ($_type) {
			case 'apt':
				$lines = explode("\n", shell_exec('dpkg -l | grep "^ii"'));
				foreach ($lines as $line) {
					$infos = array_values(array_filter(explode(" ", $line)));
					if (!isset($infos[1])) {
						continue;
					}
					if (strpos($infos[1], ':') !== false) {
						$infos[1] = explode(':', $infos[1])[0];
					}
					self::$_installPackage[$type_key][mb_strtolower($infos[1])] = array(
						'version' => $infos[2]
					);
				}
				$npm = shell_exec('npm -v 2>/dev/null');
				if ($npm != '') {
					self::$_installPackage[$type_key]['npm'] = array(
						'version' => $npm
					);
				}
				break;
			case 'pip2':
				if (version_compare(self::getOsVersion(), '11', '>=')) {
					return self::$_installPackage[$type_key];
				}
				$datas = json_decode(shell_exec(self::getCmdSudo() . ' pip2 list --format=json 2>/dev/null'), true);
				foreach ($datas as $value) {
					self::$_installPackage[$type_key][mb_strtolower($value['name'])] = array(
						'version' => $value['version']
					);
				}
				break;
			case 'pip3':
				// exclude gpg because python3-gpg is on version '1.14.0-unknown' on debian 11 and pip>24.1 raise error with non-standard version format
				$datas = json_decode(shell_exec(self::getCmdSudo() . self::getCmdPython3($_plugin) . ' -m pip list --exclude gpg --format=json 2>/dev/null'), true);
				if (!is_array($datas)) {
					break;
				}
				foreach ($datas as $value) {
					self::$_installPackage[$type_key][mb_strtolower($value['name'])] = array(
						'version' => $value['version']
					);
				}
				break;
			case 'npm':
				$datas = json_decode(shell_exec(self::getCmdSudo() . ' npm -g ls -json -depth 1 2>/dev/null'), true);
				if (isset($datas['dependencies']['yarn'])) {
					self::$_installPackage[$type_key]['yarn'] = array(
						'version' => $datas['dependencies']['yarn']['version']
					);
				}
				if (isset($datas['dependencies']) && is_array($datas['dependencies']) && count($datas['dependencies']) > 0) {
					foreach ($datas['dependencies'] as $key => $value) {
						self::$_installPackage[$type_key][mb_strtolower($key)] = array(
							'version' => $value['version']
						);
						if (isset($value['dependencies'])) {
							foreach ($value['dependencies'] as $key2 => $value2) {
								self::$_installPackage[$type_key][mb_strtolower($key2)] = array(
									'version' => $value2['version']
								);
							}
						}
					}
				}
				break;
			case 'yarn':
				$datas = json_decode(shell_exec('cat `' . self::getCmdSudo() . ' yarn global dir`/package.json 2>/dev/null'), true);
				foreach ($datas['dependencies'] as $key => $value) {
					self::$_installPackage[$type_key][mb_strtolower($key)] = array(
						'version' => json_decode(shell_exec('yarn info ' . $key . ' version --json 2>/dev/null'), true)['data']
					);
				}
				break;
			case 'composer':
				$datas = json_decode(shell_exec(self::getCmdSudo() . ' composer show -f json 2>/dev/null'), true);
				foreach ($datas['installed'] as $value) {
					self::$_installPackage[$type_key][mb_strtolower($value['name'])] = array('version' => $value['version']);
				}
				break;
			case 'plugin':
				$updates = update::byType('plugin');
				foreach ($updates as $update) {
					self::$_installPackage[$type_key][mb_strtolower($update->getLogicalId())] = array('version' => $update->getLocalVersion());
				}
				break;
		}
		return self::$_installPackage[$type_key];
	}

	public static function os_incompatible($_type, $_package, $_info): bool {
		if (isset($_info['denyDebianHigherEqual']) && self::getDistrib() == 'debian' && version_compare(self::getOsVersion(), $_info['denyDebianHigherEqual'], '>=')) {
			return true;
		}
		if (isset($_info['denyDebianLower']) && self::getDistrib() == 'debian' && version_compare(self::getOsVersion(), $_info['denyDebianLower'], '<')) {
			return true;
		}
		if (version_compare(self::getOsVersion(), '11', '>=')) {
			if ($_type == 'pip2') {
				return true;
			}
			if ($_type == 'apt' && strpos($_package, 'python-') !== false) {
				return true;
			}
		}
		return false;
	}

	public static function checkAndInstall($_packages, $_fix = false, $_foreground = false, $_plugin = '', $_force = false) {
		$return = array();
		foreach ($_packages as $type => $value) {
			if ($type == 'post-install' || $type == 'pre-install') {
				continue;
			}
			$installPackage = self::getInstallPackage($type, $_plugin);

			foreach ($_packages[$type] as $package => $info) {
				$found = 0;
				$version = '';
				if (($type == 'npm' || $type == 'yarn') && strpos($package, '/') !== false) {
					if (file_exists(__DIR__ . '/../../' . $package . '/package.json')) {
						$version = json_decode(file_get_contents(__DIR__ . '/../../' . $package . '/package.json'), true)['version'];
						if ($type == 'npm') {
							if (file_exists(__DIR__ . '/../../' . $package . '/node_modules') && isset(scandir(__DIR__ . '/../../' . $package . '/node_modules', SCANDIR_SORT_NONE)[2])) {
								exec('cd ' . __DIR__ . '/../../' . $package . ';' . self::getCmdSudo() . ' npm ls', $output, $return_var);
								if ($return_var == 0) {
									$found = 1;
								}
							}
						} else {
							exec('cd ' . __DIR__ . '/../../' . $package . ';' . self::getCmdSudo() . ' yarn check', $output, $return_var);
							if ($return_var == 0) {
								$found = 1;
							}
						}
					} else {
						$version = __('Erreur', __FILE__);
					}
					$return[$type . '::' . $package] = array(
						'name' => $package,
						'status' => $found,
						'version' => empty($version) ? 'N/A' : $version,
						'type' => $type,
						'needUpdate' => '',
						'needVersion' => '',
						'alternative_found' => '',
						'optional' => isset($info['optional']) ? $info['optional'] : false,
						'reinstall' => isset($info['reinstall']) ? $info['reinstall'] : false,
						'fix' => ($found == 0) ?  self::installPackage($type, $package) : '',
						'remark' => isset($info['remark']) ? __($info['remark'], 'install/packages.json') : '',
					);
					continue;
				}
				if ($type == 'composer' && strpos($package, '/') !== false) {
					if (file_exists(__DIR__ . '/../../' . $package . '/composer.json')) {
						$version = json_decode(file_get_contents(__DIR__ . '/../../' . $package . '/package.json'), true)['version'];
						$output = shell_exec('cd ' . __DIR__ . '/../../' . $package . ';' . self::getCmdSudo() . ' composer install --dry-run 2>&1 | grep Required | grep present | wc -l');
						if ($output == 0) {
							$found = 1;
						}
					} else {
						$version = __('Erreur', __FILE__);
					}
					$return[$type . '::' . $package] = array(
						'name' => $package,
						'status' => $found,
						'version' => empty($version) ? 'N/A' : $version,
						'type' => $type,
						'needUpdate' => '',
						'needVersion' => '',
						'alternative_found' => '',
						'optional' => isset($info['optional']) ? $info['optional'] : false,
						'reinstall' => isset($info['reinstall']) ? $info['reinstall'] : false,
						'fix' => ($found == 0) ?  self::installPackage($type, $package) : '',
						'remark' => isset($info['remark']) ? __($info['remark'], 'install/composer.json') : '',
					);
					continue;
				}
				if (self::os_incompatible($type, $package, $info)) {
					$return[$type . '::' . $package] = array(
						'name' => $package,
						'status' => 3,
						'version' => 'N/A',
						'type' => $type,
						'needUpdate' => '',
						'needVersion' => '',
						'alternative_found' => '',
						'optional' => isset($info['optional']) ? $info['optional'] : false,
						'reinstall' => isset($info['reinstall']) ? $info['reinstall'] : false,
						'fix' => '',
						'remark' => '',
					);
					continue;
				}
				$alternative_found = '';
				if (isset($installPackage[mb_strtolower($package)])) {
					$found = 1;
					$version = $installPackage[mb_strtolower($package)]['version'];
				} elseif (isset($info['alternative'])) {
					foreach ($info['alternative'] as $alternative) {
						if (isset($installPackage[mb_strtolower($alternative)])) {
							$found = 2;
							$alternative_found = $alternative;
							break;
						}
						$keys = array_values(preg_grep(mb_strtolower($alternative), array_keys($installPackage)));
						if (is_array($keys) && count($keys) > 0) {
							$found = 2;
							$alternative_found = $keys[0];
							break;
						}
					}
				}
				$needUpdate = false;
				if (isset($info['version']) && version_compare($version, $info['version']) < 0) {
					$found = 0;
					$needUpdate = true;
				}
				$return[$type . '::' . $package . '::' . (self::splitpackageByPlugin($type, $_plugin) ? $_plugin : '') . '::' . (isset($info['version']) ? $info['version'] : '')] = array(
					'name' => $package,
					'status' => $found,
					'version' => $version,
					'type' => $type,
					'needUpdate' => $needUpdate,
					'needVersion' => isset($info['version']) ? $info['version'] : '',
					'alternative_found' => $alternative_found,
					'optional' => isset($info['optional']) ? $info['optional'] : false,
					'reinstall' => isset($info['reinstall']) ? $info['reinstall'] : false,
					'fix' => ($found == 0) ? self::installPackage($type, $package, isset($info['version']) ? $info['version'] : '', $_plugin) : '',
					'remark' => isset($info['remark']) ? __($info['remark'], 'install/packages.json') : '',
				);
			}
		}
		if (!$_fix) {
			return $return;
		}
		$count = 0;
		$cmd = "set -x\n";
		$cmd .= "echo '*******************Begin of package installation******************'\n";
		$progress_file = '/tmp/jeedom_install_in_progress';
		if ($_plugin != '') {
			$progress_file .= "_$_plugin";
		}
		$cmd .= "touch $progress_file\n";
		$count++;
		$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
		if ($_foreground) {
			if (self::checkInstallationLog($_plugin) != '') {
				echo shell_exec(self::checkInstallationLog($_plugin) . ' 2>&1');
			}
		} else {
			$cmd .= self::checkInstallationLog($_plugin);
			$count++;
			$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
		}
		if (isset($_packages['pre-install'])) {
			if (isset($_packages['pre-install']['script'])) {
				if ($_foreground) {
					echo shell_exec('sudo chmod +x ' . __DIR__ . '/../../' . $_packages['pre-install']['script'] . ' 2>&1');
					echo shell_exec('sudo ' . __DIR__ . '/../../' . $_packages['pre-install']['script'] . ' 2>&1');
				} else {
					$cmd .= "sudo chmod +x " . __DIR__ . "/../../" . $_packages['pre-install']['script'] . "\n";
					$cmd .= "sudo " . __DIR__ . "/../../" . $_packages['pre-install']['script'] . "\n";
					$count++;
					$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
				}
			}
		}
		$has_something_todo = false;
		foreach ($return as $package => $info) {
			if ((($info['status'] != 0 && !$info['reinstall']) || $info['status'] == 3) && !$_force) {
				continue;
			}
			$has_something_todo = true;
			if (!isset($first_type[$info['type']]) || $first_type[$info['type']] == true) {
				$first_type[$info['type']] = false;
				switch ($info['type']) {
					case 'apt':
						if ($_foreground) {
							echo shell_exec(self::getCmdSudo() . " killall apt apt-get unattended-upgr 2>&1");
							echo shell_exec(self::getCmdSudo() . " rm /var/lib/apt/lists/lock 2>&1");
							echo shell_exec(self::getCmdSudo() . " rm /var/cache/apt/archives/lock 2>&1");
							echo shell_exec(self::getCmdSudo() . " rm /var/lib/dpkg/lock* 2>&1");
							echo shell_exec(self::getCmdSudo() . " sudo dpkg --configure -a --force-confdef 2>&1");
							echo shell_exec(self::getCmdSudo() . " apt update 2>&1");
						} else {
							$cmd .= self::getCmdSudo() . " killall apt apt-get unattended-upgr\n";
							$cmd .= self::getCmdSudo() . " rm /var/lib/apt/lists/lock\n";
							$cmd .= self::getCmdSudo() . " rm /var/cache/apt/archives/lock\n";
							$cmd .= self::getCmdSudo() . " rm /var/lib/dpkg/lock*\n";
							$cmd .= self::getCmdSudo() . " sudo dpkg --configure -a --force-confdef\n";
							$cmd .= self::getCmdSudo() . " apt update\n";
							$count++;
							$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
						}
						break;
					case 'pip3':
						if (version_compare(self::getOsVersion(), '12', '<')) {
							$cmd_cleaning_pip =  'RESULTTODELETE=$(find /usr/local/lib/python*/dist-packages/ -mindepth 1 -maxdepth 1 -type d -exec du -ks {} + | awk \'$1 <= 4\' | cut -f 2-);';
							$cmd_cleaning_pip .= 'RESULTTODELETE2=$(echo "$RESULTTODELETE" | sed \'s, ,\\ ,g\'); echo "$RESULTTODELETE2" | xargs rm -rf';
							if ($_foreground) {
								echo shell_exec($cmd_cleaning_pip . " 2>&1");
								echo shell_exec(self::getCmdSudo() . " pip3 cache purge 2>&1");
								echo shell_exec(self::getCmdSudo() . " pip3 install --upgrade pip 2>&1");
							} else {
								$cmd .= $cmd_cleaning_pip . "\n";
								$count++;
								$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
								$cmd .= self::getCmdSudo() . " pip3 cache purge\n";
								$count++;
								$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
								$cmd .= self::getCmdSudo() . " pip3 install --upgrade pip\n";
								$count++;
								$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
							}
						} else {
							if ($_foreground) {
								echo shell_exec(self::getCmdSudo() . ' apt update;' . self::getCmdSudo() . ' apt-get install -y python3 python3-pip python3-dev python3-venv');
								echo shell_exec(self::getCmdSudo() . ' python3 -m venv --upgrade-deps ' . self::getPython3VenvDir($_plugin));
								echo shell_exec(self::getCmdSudo() . self::getCmdPython3($_plugin) . ' -m pip install --upgrade pip wheel');
							} else {
								$cmd .= self::getCmdSudo() . " apt update;\n" . self::getCmdSudo() . " apt-get install -y python3 python3-pip python3-dev python3-venv\n";
								$count++;
								$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
								$cmd .= self::getCmdSudo() . 'python3 -m venv --upgrade-deps ' . self::getPython3VenvDir($_plugin) . "\n";
								$count++;
								$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
								$cmd .= self::getCmdSudo() . self::getCmdPython3($_plugin) . " -m pip install --upgrade pip wheel\n";
								$count++;
								$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
							}
						}
						break;
					case 'npm':
						if ($_foreground) {
							echo shell_exec(self::getCmdSudo() . ' chmod +x ' . __DIR__ . '/../../resources/install_nodejs.sh;' . self::getCmdSudo() . ' ' . __DIR__ . '/../../resources/install_nodejs.sh');
						} else {
							$cmd .= self::getCmdSudo() . ' chmod +x ' . __DIR__ . '/../../resources/install_nodejs.sh;' . self::getCmdSudo() . ' ' . __DIR__ . '/../../resources/install_nodejs.sh' . "\n";
							$count++;
							$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
						}
					case 'yarn':
						if ($_foreground) {
							echo shell_exec(self::getCmdSudo() . ' chmod +x ' . __DIR__ . '/../../resources/install_nodejs.sh;' . self::getCmdSudo() . ' ' . __DIR__ . '/../../resources/install_nodejs.sh');
						} else {
							$cmd .= self::getCmdSudo() . ' chmod +x ' . __DIR__ . '/../../resources/install_nodejs.sh;' . self::getCmdSudo() . ' ' . __DIR__ . '/../../resources/install_nodejs.sh' . "\n";
							$count++;
							$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
						}
					case 'composer':
						if ($_foreground) {
							echo shell_exec(self::getCmdSudo() . ' chmod +x ' . __DIR__ . '/../../resources/install_composer.sh;' . self::getCmdSudo() . ' ' . __DIR__ . '/../../resources/install_composer.sh');
						} else {
							$cmd .= self::getCmdSudo() . ' chmod +x ' . __DIR__ . '/../../resources/install_composer.sh;' . self::getCmdSudo() . ' ' . __DIR__ . '/../../resources/install_composer.sh' . "\n";
							$count++;
							$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
						}
				}
			}
			if ($_foreground) {
				echo shell_exec(self::installPackage($info['type'], $info['name'], $info['needVersion'], $_plugin) . ' 2>&1');
			} else {
				$cmd .= self::installPackage($info['type'], $info['name'], $info['needVersion'], $_plugin) . "\n";
				$count++;
				$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
			}
		}
		if (isset($_packages['post-install'])) {
			if (isset($_packages['post-install']['restart_apache']) && $_packages['post-install']['restart_apache'] && $has_something_todo) {
				if ($_foreground) {
					echo shell_exec('sudo systemctl restart apache2 2>&1');
				} else {
					$cmd .= "sudo systemctl restart apache2\n";
					$count++;
					$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
				}
			}
			if (isset($_packages['post-install']['script'])) {
				$has_something_todo = true;
				if ($_foreground) {
					echo shell_exec('sudo chmod +x ' . __DIR__ . '/../../' . $_packages['post-install']['script'] . ' 2>&1');
					echo shell_exec('sudo ' . __DIR__ . '/../../' . $_packages['post-install']['script'] . ' 2>&1');
				} else {
					$cmd .= "sudo chmod +x " . __DIR__ . "/../../" . $_packages['post-install']['script'] . "\n";
					$cmd .= "sudo " . __DIR__ . "/../../" . $_packages['post-install']['script'] . "\n";
					$count++;
					$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
				}
			}
		}
		if ($_plugin != '') {
			if ($_foreground) {
				echo shell_exec('php ' . __DIR__ . '/../php/jeecli.php plugin dependancy_end ' . $_plugin . ' 2>&1');
			} else {
				$cmd .= 'php ' . __DIR__ . '/../php/jeecli.php plugin dependancy_end ' . $_plugin . "\n";
				$count++;
				$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
			}
		}
		if ($_foreground || !$has_something_todo) {
			return;
		}
		$cmd .= "rm $progress_file\n";
		$cmd .= "echo '*******************End of package installation******************'\n";
		if (file_exists('/tmp/jeedom_fix_package')) {
			shell_exec(system::getCmdSudo() . ' rm /tmp/jeedom_fix_package');
		}
		file_put_contents('/tmp/jeedom_fix_package', $cmd);
		self::launchScriptPackage($_plugin, $_force);
	}

	public static function installPackageInProgress($_plugin = ''): bool {
		if (count(self::ps('^dpkg ')) > 0 || count(self::ps('^apt ')) > 0) {
			return true;
		}
		$progress_file = '/tmp/jeedom_install_in_progress';
		if ($_plugin != '') {
			$progress_file .= "_$_plugin";
		}
		if (file_exists($progress_file)) {
			$mtime = filemtime($progress_file);
			if ($mtime > strtotime('now')) {
				shell_exec(self::getCmdSudo() . ' rm ' . $progress_file);
				return false;
			}
			if (strtotime('now') - $mtime > (30 * 60)) {
				shell_exec(self::getCmdSudo() . ' rm ' . $progress_file);
				return false;
			}
			return true;
		}
		if (shell_exec('ls /tmp/jeedom_install_in_progress* | wc -l') > 0) {
			return true;
		}
		return false;
	}

	public static function launchScriptPackage($_plugin = '', $_force = false) {
		if (!$_force && self::installPackageInProgress($_plugin)) {
			throw new \Exception(__('Installation de package impossible car il y a déjà une installation en cours', __FILE__));
		}
		shell_exec(system::getCmdSudo() . ' chmod +x /tmp/jeedom_fix_package');
		if (class_exists('log')) {
			if ($_plugin != '') {
				$log = log::getPathToLog($_plugin . '_packages');
				log::clear($_plugin . '_packages');
			} else {
				$log = log::getPathToLog('packages');
				log::clear('packages');
			}
		} else {
			$log = '/tmp/jeedom_fix_package_log';
		}
		if (!self::checkHasExec('at')) {
			exec(system::getCmdSudo() . '/bin/bash /tmp/jeedom_fix_package >> ' . $log . ' 2>&1 &');
		} else {
			if (!file_exists($log)) {
				touch($log);
			}
			exec('echo "/bin/bash /tmp/jeedom_fix_package >> ' . $log . ' 2>&1" | ' . system::getCmdSudo() . ' at now');
		}
	}

	public static function installPackage($_type, $_package, $_version = '', $_plugin = '') {
		switch ($_type) {
			case 'apt':
				if ($_package == 'node' || $_package == 'nodejs' || $_package == 'npm') {
					return self::getCmdSudo() . ' chmod +x ' . __DIR__ . '/../../resources/install_nodejs.sh;' . self::getCmdSudo() . ' ' . __DIR__ . '/../../resources/install_nodejs.sh';
				}
				return self::getCmdSudo() . ' apt install -o Dpkg::Options::="--force-confdef" -y ' . $_package;
			case 'pip2':
				if (version_compare(self::getOsVersion(), '11', '>=')) {
					return '';
				}
				return self::getCmdSudo() . ' pip2 install --force-reinstall --upgrade ' . $_package;
			case 'pip3':
				if ($_version != '') {
					$_package .= '==' . $_version;
				}
				return self::getCmdSudo() . self::getCmdPython3($_plugin) . ' -m pip install --force-reinstall --upgrade ' . $_package;
			case 'npm':
				if (strpos($_package, '/') === false) {
					return self::getCmdSudo() . ' NODE_OPTIONS=--dns-result-order=ipv4first npm install --force -g ' . $_package;
				}
				if (!file_exists(__DIR__ . '/../../' . $_package . '/package.json')) {
					return '';
				}
				return 'cd ' . __DIR__ . '/../../' . $_package . ';rm -rf node_modules;' . self::getCmdSudo() . ' NODE_OPTIONS=--dns-result-order=ipv4first npm install;' . self::getCmdSudo() . ' chown -R www-data:www-data *';
			case 'yarn':
				if (strpos($_package, '/') === false) {
					return self::getCmdSudo() . ' yarn global add ' . $_package;
				}
				if (!file_exists(__DIR__ . '/../../' . $_package . '/package.json')) {
					return '';
				}
				return 'cd ' . __DIR__ . '/../../' . $_package . ';rm -rf node_modules;' . self::getCmdSudo() . ' yarn install;' . self::getCmdSudo() . ' chown -R www-data:www-data *';
			case 'plugin':
				return 'php ' . __DIR__ . '/../php/jeecli.php plugin install ' . $_package;
			case 'composer':
				if (strpos($_package, '/') === false) {
					return self::getCmdSudo() . ' composer require --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader ' . $_package;
				}
				if (!file_exists(__DIR__ . '/../../' . $_package . '/composer.json')) {
					return '';
				}
				return 'cd ' . __DIR__ . '/../../' . $_package . ';rm -rf vendor;' . self::getCmdSudo() . ' composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader;' . self::getCmdSudo() . ' chown -R www-data:www-data *';
		}
	}

	public static function checkHasExec($_exec) {
		if (isset(self::$_hasExec[$_exec])) {
			return self::$_hasExec[$_exec];
		}
		self::$_hasExec[$_exec] = (exec('which ' . $_exec . ' | wc -l') != 0);
		return self::$_hasExec[$_exec];
	}

	public static function getOsVersion() {
		if (self::getDistrib() != 'debian') {
			return 0;
		}
		if (isset(self::$_os_version)) {
			return self::$_os_version;
		}
		self::$_os_version = exec('cat /etc/debian_version');
		return self::$_os_version;
	}

	public static function checkInstallationLog($_plugin = ''): string {
		if (class_exists('log')) {
			if ($_plugin != '') {
				$log = log::getPathToLog($_plugin . '_packages');
			} else {
				$log = log::getPathToLog('packages');
			}
		} else {
			$log = '/tmp/jeedom_fix_package_log';
		}
		if (file_exists($log)) {
			$fix = '';
			$data = file_get_contents($log);
			if (strpos($data, 'dpkg configure -a')) {
				$fix .= "sudo dpkg --configure -a --force-confdef\n";
			}
			if (strpos($data, 'oldstable')) {
				$fix .= "sudo apt-get --allow-releaseinfo-change\n";
			}
			return $fix;
		}
		return '';
	}
}
