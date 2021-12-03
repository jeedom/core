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
	private static $_packageUpdateMake = false;
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

	public static function getCmdSudo() {
		if (!class_exists('jeedom')) {
			return 'sudo ';
		}
		if (!jeedom::isCapable('sudo')) {
			return '';
		}
		return 'sudo ';
	}

	public static function fuserk($_port, $_protocol = 'tcp') {
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

	public static function getArch() {
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

	public static function getInstallPackage($_type) {
		if (isset(self::$_installPackage[$_type])) {
			return self::$_installPackage[$_type];
		}
		self::$_installPackage[$_type] = array();
		switch ($_type) {
			case 'apt':
				$lines = explode("\n", shell_exec('dpkg -l | tail -n +6'));
				foreach ($lines as $line) {
					$infos = array_values(array_filter(explode(" ", $line)));
					if (!isset($infos[1])) {
						continue;
					}
					if (strpos($infos[1], ':') !== false) {
						$infos[1] = explode(':', $infos[1])[0];
					}
					self::$_installPackage[$_type][mb_strtolower($infos[1])] = array(
						'version' => $infos[2]
					);
				}
				break;
			case 'pip2':
				if (version_compare(self::getOsVersion(), '11', '>=')) {
					return self::$_installPackage[$_type];
				}
				$datas = json_decode(shell_exec('pip2 list --format=json 2>/dev/null'), true);
				foreach ($datas as $value) {
					self::$_installPackage[$_type][mb_strtolower($value['name'])] = array(
						'version' => $value['version']
					);
				}
				break;
			case 'pip3':
				$datas = json_decode(shell_exec('pip3 list --format=json 2>/dev/null'), true);
				foreach ($datas as $value) {
					self::$_installPackage[$_type][mb_strtolower($value['name'])] = array(
						'version' => $value['version']
					);
				}
				break;
			case 'npm':
				$datas = json_decode(shell_exec('npm -g ls -json -depth 1 2>/dev/null'), true);
				if (isset($datas['dependencies']['yarn'])) {
					self::$_installPackage[$_type]['yarn'] = array(
						'version' => $datas['dependencies']['yarn']['version']
					);
				}
				foreach ($datas['dependencies']['npm']['dependencies'] as $key => $value) {
					self::$_installPackage[$_type][mb_strtolower($key)] = array(
						'version' => $value['version']
					);
				}
				break;
			case 'yarn':
				$datas = json_decode(shell_exec('cat `' . self::getCmdSudo() . ' yarn global dir`/package.json 2>/dev/null'), true);
				foreach ($datas['dependencies'] as $key => $value) {
					self::$_installPackage[$_type][mb_strtolower($key)] = array(
						'version' => json_decode(shell_exec('yarn info ' . $key . ' version --json 2>/dev/null'), true)['data']
					);
				}
				break;
			case 'plugin':
				$updates = update::byType('plugin');
				foreach ($updates as $update) {
					self::$_installPackage[$_type][mb_strtolower($update->getLogicalId())] = array('version' => $update->getLocalVersion());
				}
				break;
		}
		return self::$_installPackage[$_type];
	}

	public static function os_incompatible($_type, $_package, $_info) {
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

	public static function checkAndInstall($_packages, $_fix = false, $_foreground = false, $_plugin = '') {
		$return = array();
		foreach ($_packages as $type => $value) {
			if ($type == 'post-install' || $type == 'pre-install') {
				continue;
			}
			$installPackage = self::getInstallPackage($type);

			foreach ($_packages[$type] as $package => $info) {
				$found = 0;
				$version = '';
				if (($type == 'npm' || $type == 'yarn') && strpos($package, '/') !== false) {
					if (file_exists(__DIR__ . '/../../' . $package . '/package.json')) {
						$version = json_decode(file_get_contents(__DIR__ . '/../../' . $package . '/package.json'), true)['version'];
						if ($type == 'npm') {
							exec('cd ' . __DIR__ . '/../../' . $package . ';npm list', $output, $return_var);
							if ($return_var == 0) {
								$found = 1;
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
				$return[$type . '::' . $package] = array(
					'name' => $package,
					'status' => $found,
					'version' => $version,
					'type' => $type,
					'needUpdate' => $needUpdate,
					'needVersion' => isset($info['version']) ? $info['version'] : '',
					'alternative_found' => $alternative_found,
					'optional' => isset($info['optional']) ? $info['optional'] : false,
					'reinstall' => isset($info['reinstall']) ? $info['reinstall'] : false,
					'fix' => ($found == 0) ?  self::installPackage($type, $package) : '',
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
			if (self::checkInstallationLog() != '') {
				echo shell_exec(self::checkInstallationLog() . ' 2>&1');
			}
		} else {
			$cmd .= self::checkInstallationLog();
			$count++;
			$cmd .= 'echo ' . $count . ' > ' . $progress_file . "\n";
		}
		if ($_foreground) {
			echo shell_exec(self::getCmdSudo() . " apt update 2>&1");
		} else {
			$cmd .= self::getCmdSudo() . " apt update\n";
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
			if (($info['status'] != 0 && !$info['reinstall']) || $info['optional'] || $info['status'] == 3) {
				continue;
			}
			$has_something_todo = true;
			if ($_foreground) {
				echo shell_exec(self::installPackage($info['type'], $info['name']) . ' 2>&1');
			} else {
				$cmd .= self::installPackage($info['type'], $info['name']) . "\n";
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
					$cmd .= 'echo' . $count . ' > ' . $progress_file . "\n";
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
		if ($_foreground || !$has_something_todo) {
			return;
		}
		if ($_plugin != '') {
			$cmd .= 'php ' . __DIR__ . '/../php/jeecli.php plugin dependancy_end ' . $_plugin . "\n";
		}
		$cmd .= "rm $progress_file\n";
		$cmd .= "echo '*******************End of package installation******************'\n";
		if (file_exists('/tmp/jeedom_fix_package')) {
			shell_exec(system::getCmdSudo() . ' rm /tmp/jeedom_fix_package');
		}
		file_put_contents('/tmp/jeedom_fix_package', $cmd);
		self::launchScriptPackage($_plugin);
	}

	public static function installPackageInProgress($_plugin = '') {
		if (count(self::ps('dpkg')) > 0 || count(self::ps('apt ')) > 0) {
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
		return false;
	}

	public static function launchScriptPackage($_plugin = '') {
		if (self::installPackageInProgress($_plugin)) {
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

	public static function installPackage($_type, $_package) {
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
				return self::getCmdSudo() . ' pip2 install --upgrade ' . $_package;
			case 'pip3':
				return self::getCmdSudo() . ' pip3 install --upgrade ' . $_package;
			case 'npm':
				if (strpos($_package, '/') === false) {
					return self::getCmdSudo() . ' npm install --force -g ' . $_package;
				}
				if (!file_exists(__DIR__ . '/../../' . $_package . '/package.json')) {
					return '';
				}
				return 'cd ' . __DIR__ . '/../../' . $_package . ';rm -rf node_modules;' . self::getCmdSudo() . ' npm install;chown -R www-data:www-data *';
			case 'yarn':
				if (strpos($_package, '/') === false) {
					return self::getCmdSudo() . ' yarn global add ' . $_package;
				}
				if (!file_exists(__DIR__ . '/../../' . $_package . '/package.json')) {
					return '';
				}
				return 'cd ' . __DIR__ . '/../../' . $_package . ';rm -rf node_modules;' . self::getCmdSudo() . ' yarn install;chown -R www-data:www-data *';
			case 'plugin':
				return 'php ' . __DIR__ . '/../php/jeecli.php plugin install ' . $_package;
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

	public static function checkInstallationLog() {
		if (class_exists('log')) {
			$log = log::getPathToLog('packages');
		} else {
			$log = '/tmp/jeedom_fix_package_log';
		}
		if (file_exists($log)) {
			$data = file_get_contents($log);
			if (strpos($data, 'dpkg configure -a')) {
				return "sudo dpkg --configure -a --force-confdef\n";
			}
		}
		return '';
	}
}
