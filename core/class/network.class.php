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

class network {

	public static function getNetworkAccess($_mode = 'auto', $_protocole = '', $_default = '') {
		self::checkConf();
		if ($_mode == 'auto') {
			if (netMatch('192.168.*.*', getClientIp()) || netMatch('10.0.*.*', getClientIp())) {
				if (!isset($_SERVER['HTTP_HOST']) || netMatch($_SERVER['HTTP_HOST'], getClientIp()) || netMatch($_SERVER['HTTP_HOST'], getClientIp())) {
					$_mode = 'internal';
				} else {
					$_mode = 'external';
				}
			} else {
				$_mode = 'external';
			}
		}
		if ($_mode == 'internal') {
			if (strpos(config::byKey('internalAddr', 'core', $_default), 'http://') != false || strpos(config::byKey('internalAddr', 'core', $_default), 'https://') !== false) {
				config::save('internalAddr', str_replace(array('http://', 'https://'), '', config::byKey('internalAddr', 'core', $_default)));
			}
			if ($_protocole == 'ip' || $_protocole == 'dns') {
				return config::byKey('internalAddr', 'core', $_default);
			}
			if ($_protocole == 'ip:port' || $_protocole == 'dns:port') {
				return config::byKey('internalAddr') . ':' . config::byKey('internalPort', 'core', 80);
			}
			if ($_protocole == 'prot:ip:port' || $_protocole == 'proto:dns:port') {
				return config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . config::byKey('internalPort', 'core', 80);
			}
			return config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . config::byKey('internalPort', 'core', 80) . config::byKey('internalComplement');

		}
		if ($_mode == 'external') {
			if (strpos(config::byKey('externalAddr', 'core', $_default), 'http://') != false || strpos(config::byKey('externalAddr', 'core', $_default), 'https://') !== false) {
				config::save('externalAddr', str_replace(array('http://', 'https://'), '', config::byKey('externalAddr', 'core', $_default)));
			}
			if ($_protocole == 'ip') {
				if (config::byKey('jeedom::url') != '') {
					return getIpFromString(config::byKey('jeedom::url'));
				}
				return getIpFromString(config::byKey('externalAddr'));
			}
			if ($_protocole == 'ip:port') {
				if (config::byKey('jeedom::url') != '') {
					$url = parse_url(config::byKey('jeedom::url'));
					if (isset($url['host'])) {
						if (isset($url['port'])) {
							return getIpFromString($url['host']) . ':' . $url['port'];
						} else {
							return getIpFromString($url['host']);
						}
					}
				}
				return config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80);
			}
			if ($_protocole == 'proto:dns:port') {
				if (config::byKey('jeedom::url') != '') {
					$url = parse_url(config::byKey('jeedom::url'));
					$return = '';
					if (isset($url['scheme'])) {
						$return = $url['scheme'] . '://';
					}
					if (isset($url['host'])) {
						if (isset($url['port'])) {
							return $return . $url['host'] . ':' . $url['port'];
						} else {
							return $return . $url['host'];
						}
					}
				}
				return config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80);
			}
			if ($_protocole == 'dns:port') {
				if (config::byKey('jeedom::url') != '') {
					$url = parse_url(config::byKey('jeedom::url'));
					if (isset($url['host'])) {
						if (isset($url['port'])) {
							return $url['host'] . ':' . $url['port'];
						} else {
							return $url['host'];
						}
					}
				}
				return config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80);
			}
			if ($_protocole == 'proto') {
				if (config::byKey('jeedom::url') != '') {
					$url = parse_url(config::byKey('jeedom::url'));
					if (isset($url['scheme'])) {
						return $url['scheme'] . '://';
					}
				}
				return config::byKey('externalProtocol');
			}
			if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && filter_var(config::byKey('externalAddr'), FILTER_VALIDATE_IP)) {
				return config::byKey('jeedom::url');
			}
			return config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80) . config::byKey('externalComplement');
		}
	}

	public static function checkConf() {
		if (config::byKey('externalComplement') == '/') {
			config::save('externalComplement', '');
		}
		if (config::byKey('internalComplement') == '/') {
			config::save('internalComplement', '');
		}
		if (!filter_var(config::byKey('internalAddr'), FILTER_VALIDATE_IP)) {
			$internalAddr = str_replace(array('http://', 'https://'), '', config::byKey('internalAddr'));
			$pos = strpos($internalAddr, '/');
			if ($pos !== false) {
				$internalAddr = substr($internalAddr, 0, $pos);
			}
			if ($internalAddr != config::byKey('internalAddr')) {
				config::save('internalAddr', $internalAddr);
			}
		}
		if (config::byKey('internalAddr') == '' || config::byKey('internalAddr') == '127.0.0.1' || config::byKey('internalAddr') == 'localhost') {
			$internalIp = getHostByName(getHostName());
			if ($internalIp != '') {
				config::save('internalAddr', $internalIp);
			}
		}

		if (!filter_var(config::byKey('externalAddr'), FILTER_VALIDATE_IP)) {
			$externalAddr = str_replace(array('http://', 'https://'), '', config::byKey('externalAddr'));
			$pos = strpos($externalAddr, '/');
			if ($pos !== false) {
				$externalAddr = substr($externalAddr, 0, $pos);
			}
			if ($externalAddr != config::byKey('externalAddr')) {
				config::save('externalAddr', $externalAddr);
			}
		}

		if (config::byKey('internalProtocol') == '') {
			config::save('internalProtocol', 'http://');
		}
		if (config::byKey('internalPort') == '') {
			config::save('internalPort', 80);
		}
		if (config::byKey('internalComplement') == '/') {
			if (file_exists('/etc/nginx/sites-available/default')) {
				$data = file_get_contents('/etc/nginx/sites-available/default');
				if (strpos($data, 'root /usr/share/nginx/www;') !== false) {
					config::save('internalComplement', '/jeedom');
				}
			}
		}
		if (config::byKey('externalComplement') == '/') {
			if (file_exists('/etc/nginx/sites-available/default')) {
				$data = file_get_contents('/etc/nginx/sites-available/default');
				if (strpos($data, 'root /usr/share/nginx/www;') !== false) {
					config::save('externalComplement', '/jeedom');
				}
			}
		}
	}

/*     * ****************************Nginx management*************************** */

	public static function nginx_saveRule($_rules) {
		if (!is_array($_rules)) {
			$_rules = array($_rules);
		}
		if (!file_exists('/etc/nginx/sites-available/jeedom_dynamic_rule')) {
			throw new Exception('Fichier non trouvé : /etc/nginx/sites-available/jeedom_dynamic_rule');
		}
		$nginx_conf = self::nginx_removeRule($_rules, true);

		foreach ($_rules as $rule) {
			$nginx_conf .= "\n" . $rule . "\n";
		}
		file_put_contents('/etc/nginx/sites-available/jeedom_dynamic_rule', $nginx_conf);
		shell_exec('sudo service nginx reload');
	}

	public static function nginx_removeRule($_rules, $_returnResult = false) {
		if (!is_array($_rules)) {
			$_rules = array($_rules);
		}
		if (!file_exists('/etc/nginx/sites-available/jeedom_dynamic_rule')) {
			return $_rules;
		}
		$result = '';
		$nginx_conf = trim(file_get_contents('/etc/nginx/sites-available/jeedom_dynamic_rule'));
		$accolade = 0;
		$change = false;
		foreach (explode("\n", trim($nginx_conf)) as $conf_line) {
			if ($accolade > 0 && strpos('{', $conf_line) !== false) {
				$accolade++;
			}
			foreach ($_rules as $rule) {
				$rule_line = explode("\n", trim($rule));
				if (trim($conf_line) == trim($rule_line[0])) {
					$accolade = 1;
				}
			}
			if ($accolade == 0) {
				$result .= $conf_line . "\n";
			} else {
				$change = true;
			}
			if ($accolade > 0 && strpos('}', $conf_line) !== false) {
				$accolade--;
			}
		}
		if ($_returnResult) {
			return $result;
		}
		if ($change) {
			file_put_contents('/etc/nginx/sites-available/jeedom_dynamic_rule', $result);
			shell_exec('sudo service nginx reload');
		}
	}

	public static function apache_saveRule($_rules) {
		if (!is_array($_rules)) {
			$_rules = array($_rules);
		}
		$jeedom_dynamic_rule_file = dirname(__FILE__) . '/../../core/config/apache_jeedom_dynamic_rules';
		if (!file_exists($jeedom_dynamic_rule_file)) {
			throw new Exception('Fichier non trouvé : ' . $jeedom_dynamic_rule_file);
		}
		foreach ($_rules as $rule) {
			$apache_conf .= $rule . "\n";
		}
		file_put_contents($jeedom_dynamic_rule_file, $apache_conf);
	}

	public static function apache_removeRule($_rules, $_returnResult = false) {
		if (!is_array($_rules)) {
			$_rules = array($_rules);
		}
		$jeedom_dynamic_rule_file = dirname(__FILE__) . '/../../core/config/apache_jeedom_dynamic_rules';
		if (!file_exists($jeedom_dynamic_rule_file)) {
			return $_rules;
		}
		$apache_conf = trim(file_get_contents($jeedom_dynamic_rule_file));
		$new_apache_conf = $apache_conf;
		foreach ($_rules as $rule) {
			$new_apache_conf = preg_replace($rule, "", $new_apache_conf);
		}
		$new_apache_conf = preg_replace("/\n\n*/s", "\n", $new_apache_conf);

		if ($new_apache_conf != $apache_conf) {
			file_put_contents($jeedom_dynamic_rule_file, $new_apache_conf);
		}
	}

/*     * *********************NGROK************************* */

	public static function ngrok_start($_proto = 'https', $_port = 80, $_name = '', $_serverAddr = 'dns.jeedom.com:4443') {
		if ($_port != 80 && $_name == '') {
			throw new Exception(__('Si le port est different de 80 le nom ne peut etre vide', __FILE__));
		}
		if (config::byKey('ngrok::addr') == '') {
			return;
		}
		if ($_name == '') {
			$_name = 'jeedom';
		}
		$config_file = '/tmp/ngrok_' . $_name;
		$logfile = log::getPathToLog('ngrok');
		$uname = posix_uname();
		if (strrpos($uname['machine'], 'arm') !== false) {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-arm';
		} else if ($uname['machine'] == 'x86_64') {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-x64';
		} else {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-x86';
		}
		exec('chmod +x ' . $cmd);
		$cmd .= ' -config=' . $config_file . ' start ' . $_name;
		if (!self::ngrok_run($_proto, $_port, $_name)) {
			$replace = array(
				'#server_addr#' => $_serverAddr,
				'#name#' => $_name,
				'#proto#' => $_proto,
				'#port#' => $_port,
				'#remote_port#' => '',
				'#token#' => config::byKey('ngrok::token'),
				'#auth#' => '',
				'#subdomain#' => 'subdomain : ' . config::byKey('ngrok::addr'),
			);
			if ($_serverAddr != 'dns.jeedom.com:4443') {
				$replace['#subdomain#'] = '';
			}
			if ($_proto == 'tcp') {
				if (config::byKey('ngrok::port') == '') {
					return '';
				}
				$remote_port = config::byKey('ngrok::port');
				if ($_port != 22) {
					$used_port = config::byKey('ngrok::remoteport');
					if (!is_array($used_port)) {
						$used_port = array();
					}
					for ($i = 1; $i < 5; $i++) {
						$remote_port++;
						if (!isset($used_port[$remote_port]) || $used_port[$remote_port] == $_name) {
							break;
						}
					}
					$used_port[$remote_port] = $_name;
					config::save('ngrok::remoteport', $used_port);
				}
				$replace['#remote_port#'] = 'remote_port: ' . $remote_port;
			}
			if (config::byKey('market::userDNS') != '' && config::byKey('market::passwordDNS') != '') {
				$replace['#auth#'] = 'auth: "' . config::byKey('market::userDNS') . ':' . config::byKey('market::passwordDNS') . '"';
			}
			if ($_port != 80) {
				$replace['#subdomain#'] .= $_name;
			}
			$config = template_replace($replace, file_get_contents(dirname(__FILE__) . '/../../script/ngrok/config'));
			if (file_exists($config_file)) {
				unlink($config_file);
			}
			file_put_contents($config_file, $config);
			log::remove('ngrok');
			log::add('ngork', 'debug', 'Lancement de ngork : ' . $cmd);
			exec($cmd . ' >> /dev/null 2>&1 &');
			sleep(2);
			if ($_proto == 'https' && $_port == 80) {
				market::test();
			}
		}

		return true;
	}

	public static function ngrok_run($_proto = 'https', $_port = 80, $_name = '') {
		if ($_port != 80 && $_name == '') {
			throw new Exception(__('Si le port est different de 80 le nom ne peut etre vide', __FILE__));
		}
		if ($_name == '') {
			$_name = 'jeedom';
		}
		$config_file = '/tmp/ngrok_' . $_name;
		$logfile = log::getPathToLog('ngrok');
		$uname = posix_uname();
		if (strrpos($uname['machine'], 'arm') !== false) {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-arm';
		} else if ($uname['machine'] == 'x86_64') {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-x64';
		} else {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-x86';
		}
		$cmd .= ' -config=' . $config_file . ' start ' . $_name;
		$pid = jeedom::retrievePidThread($cmd);
		if ($pid == null) {
			return false;
		}
		return @posix_getsid(intval($pid));
	}

	public static function ngrok_stop($_proto = 'https', $_port = 80, $_name = '') {
		if ($_port != 80 && $_name == '') {
			throw new Exception(__('Si le port est different de 80 le nom ne peut etre vide', __FILE__));
		}
		if (!self::ngrok_run($_proto, $_port, $_name)) {
			return true;
		}
		if ($_name == '') {
			$_name = 'jeedom';
		}
		$config_file = '/tmp/ngrok_' . $_name;
		$logfile = log::getPathToLog('ngrok');
		$uname = posix_uname();
		if (strrpos($uname['machine'], 'arm') !== false) {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-arm';
		} else if ($uname['machine'] == 'x86_64') {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-x64';
		} else {
			$cmd = dirname(__FILE__) . '/../../script/ngrok/ngrok-x86';
		}
		$cmd .= ' -config=' . $config_file . ' start ' . $_name;
		$pid = jeedom::retrievePidThread($cmd);
		if ($pid == null) {
			return true;
		}
		$kill = @posix_kill($pid, 15);
		if (!$kill) {
			sleep(1);
			@posix_kill($pid, 9);
		}
		return !self::ngrok_run($_proto, $_port, $_name);
	}

/*     * *********************WICD************************* */

	public static function listWifi() {
		$results = shell_exec('sudo ifconfig wlan0 up;sudo iwlist scan | grep ESSID 2> /dev/null');
		$results = explode("\n", $results);
		$return = array();
		foreach ($results as $result) {
			if (strpos($result, 'ESSID') !== false) {
				$essid = trim(str_replace(array('ESSID', ':', '"'), '', $result));
				if ($essid != '' && !isset($return[$essid])) {
					$return[$essid] = $essid;
				}
			}
		}
		return $return;

	}

	public static function canManageNetwork() {
		if (shell_exec('sudo dpkg --get-selections | grep ifenslave | wc -l') == 0) {
			return false;
		}
		if (shell_exec('sudo lsmod | grep bonding | wc -l') == 0) {
			return false;
		}
		return true;
	}

	public static function signalStrength() {
		if (config::byKey('network::wifi::enable') != 1 || config::byKey('network::wifi::ssid') == '' || config::byKey('network::wifi::password') == '') {
			$return = -1;
		}
		return str_replace('.', '', shell_exec("tail -n +3 /proc/net/wireless | awk '{ print $3 }'"));
	}

	public static function ehtIsUp() {
		return (trim(shell_exec("cat /sys/class/net/eth0/operstate")) == 'up') ? true : false;
	}

	public static function wlanIsUp() {
		return (trim(shell_exec("cat /sys/class/net/wlan0/operstate")) == 'up') ? true : false;
	}

	public static function writeInterfaceFile() {
		if (!self::canManageNetwork()) {
			return;
		}

		$interface = 'auto lo
	iface lo inet loopback';
		$interface .= "\n\n";
		$interface .= 'auto eth0
	iface eth0 inet manual
	bond-master bond0
	bond-primary eth0
	bond-mode active-backup';
		$interface .= "\n\n";

		if (config::byKey('network::wifi::enable') == 1 && config::byKey('network::wifi::ssid') != '' && config::byKey('network::wifi::password') != '') {
			$interface .= 'auto wlan0
	iface wlan0 inet manual
	wpa-ssid ' . config::byKey('network::wifi::ssid') . '
	wpa-psk ' . config::byKey('network::wifi::password') . '
	bond-master bond0
	bond-primary eth0
	bond-mode active-backup';
		}
		$interface .= "\n\n";

		if (config::byKey('network::fixip::enable') == 1 && config::byKey('internalAddr') != '' && filter_var(config::byKey('internalAddr'), FILTER_VALIDATE_IP) && config::byKey('network::fixip::gateway') != '' && filter_var(config::byKey('network::fixip::gateway'), FILTER_VALIDATE_IP) && config::byKey('network::fixip::netmask') != '' && filter_var(config::byKey('network::fixip::netmask'), FILTER_VALIDATE_IP)) {
			$interface .= 'auto bond0
	iface bond0 inet static
	address ' . config::byKey('internalAddr') . '
	gateway ' . config::byKey('network::fixip::gateway') . '
	netmask ' . config::byKey('network::fixip::netmask') . '
    bond-slaves none
	bond-primary eth0
	bond-mode active-backup
	bond-miimon 100';
		} else {
			$interface .= 'auto bond0
	iface bond0 inet dhcp
	bond-slaves none
	bond-primary eth0
	bond-mode active-backup
	bond-miimon 100';
		}
		$interface .= "\n";
		file_put_contents('/tmp/interfaces', $interface);
		$filepath = '/etc/network/interfaces';
		if (!file_exists($filepath . '.save')) {
			exec('sudo cp ' . $filepath . ' ' . $filepath . '.save');
		}
		exec('sudo rm -rf ' . $filepath . '; sudo mv /tmp/interfaces ' . $filepath . ';sudo chown root:root ' . $filepath . ';sudo chmod 644 ' . $filepath);
	}

	public static function getInterfaceIp($_interface) {
		$results = trim(shell_exec('sudo ip addr show ' . $_interface . '| grep inet | head -1'));
		$results = explode(' ', $results);
		$result = $results[1];
		$ip = substr($result, 0, strrpos($result, '/'));
		if (filter_var($ip, FILTER_VALIDATE_IP)) {
			return $ip;
		}
		return false;
	}

	public static function getRoute() {
		$return = array();
		$results = trim(shell_exec('sudo route -n'));
		$results = explode("\n", $results);
		unset($results[0]);
		unset($results[1]);
		foreach ($results as $result) {
			$info = explode(' ', $result);
			$destination = null;
			$gw = null;
			$iface = $info[count($info) - 1];
			for ($i = 0; $i < count($info); $i++) {
				if ($info[$i] != '' && filter_var($info[$i], FILTER_VALIDATE_IP)) {
					if ($destination == null) {
						$destination = $info[$i];
					} else if ($gw == null) {
						$gw = $info[$i];
					}
				}
			}
			if (isset($return[$iface])) {
				if ($destination != '0.0.0.0') {
					$return[$iface]['destination'] = $destination;
				}
				if ($gw != '0.0.0.0') {
					$return[$iface]['gateway'] = $gw;
				}
			} else {
				$return[$iface] = array('destination' => $destination, 'gateway' => $gw, 'iface' => $iface);
			}
		}
		return $return;
	}

	public static function checkGw() {
		$return = array();
		foreach (self::getRoute() as $route) {
			$return[$route['iface']] = array('destination' => $route['destination'], 'gateway' => $route['gateway'], 'iface' => $route['iface']);
			$output = array();
			$return_val = -1;
			if ($route['gateway'] != '0.0.0.0' && $route['gateway'] != '127.0.0.1') {
				exec('sudo ping -c 1 ' . $route['gateway'] . ' > /dev/null 2> /dev/null', $output, $return_val);
				$return[$route['iface']]['ping'] = ($return_val == 0) ? 'ok' : 'nok';
			} else {
				$return[$route['iface']]['ping'] = 'nok';
			}
		}
		return $return;
	}

	public static function cron() {
		$gws = self::checkGw();
		if (count($gws) < 1) {
			return;
		}
		foreach ($gws as $gw) {
			if ($gw['ping'] == 'ok') {
				if (config::byKey('network::lastNoGw', 'core', -1) != -1) {
					config::save('network::lastNoGw', -1);
				}
				if (config::byKey('network::failedNumber', 'core', 0) != 0) {
					config::save('network::failedNumber', 0);
				}
				return;
			}
		}
		$filepath = '/etc/network/interfaces';
		if (config::byKey('network::failedNumber', 'core', 0) > 2 && file_exists($filepath . '.save') && self::ehtIsUp()) {
			log::add('network', 'error', __('Aucune gateway trouvée depuis plus de 30min. Remise par defaut du fichier interface', __FILE__));
			exec('sudo cp ' . $filepath . '.save ' . $filepath . '; sudo rm ' . $filepath . '.save ');
			config::save('network::failedNumber', 0);
			jeedom::rebootSystem();
		}
		$lastNoOk = config::byKey('network::lastNoGw', 'core', -1);
		if ($lastNoOk < 0) {
			config::save('network::lastNoGw', strtotime('now'));
			return;
		}
		if ((strtotime('now') - $lastNoOk) < 600) {
			return;
		}
		log::add('network', 'error', __('Il y a un probleme de connectivité réseaux. Aucune gateway d\'accessible. J\'essaye de corriger : ', __FILE__) . config::byKey('network::failedNumber', 'core', 0));
		if (config::byKey('network::fixip::enable') == 1) {
			log::add('network', 'error', __('Aucune gateway trouvée, la configuration IP fixe est surement invalide. Désactivation de celle-ci et redemarrage', __FILE__));
			config::save('network::fixip::enable', 0);
			config::save('network::lastNoGw', -1);
			config::save('network::failedNumber', config::byKey('network::failedNumber', 'core', 0) + 1);
			self::writeInterfaceFile();
			jeedom::rebootSystem();
			return;
		}
		if (config::byKey('network::wifi::enable') == 1 && config::byKey('network::wifi::ssid') != '' && config::byKey('network::wifi::password') != '') {
			log::add('network', 'error', __('Aucune gateway trouvée, redemarrage de l\'interface wifi', __FILE__));
			config::save('network::lastNoGw', -1);
			exec('sudo ifdown wlan0');
			sleep(5);
			exec('sudo ifup --force wlan0');
			config::save('network::failedNumber', config::byKey('network::failedNumber', 'core', 0) + 1);
			return;
		}
		log::add('network', 'error', __('Aucune gateway trouvée, redemarrage de l\'interface filaire', __FILE__));
		config::save('network::lastNoGw', -1);
		config::save('network::failedNumber', config::byKey('network::failedNumber', 'core', 0) + 1);
		exec('sudo ifdown eth0');
		sleep(5);
		exec('sudo ifup --force eth0');
	}

}

?>
