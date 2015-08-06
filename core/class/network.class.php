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

	public static function getUserLocation() {
		$client_ip = self::getClientIp();
		if (netMatch('192.168.*.*', $client_ip) || netMatch('10.0.*.*', $client_ip)) {
			if (!isset($_SERVER['HTTP_HOST']) || netMatch('192.168.*.*', $_SERVER['HTTP_HOST']) || netMatch('10.0.*.*', $_SERVER['HTTP_HOST'])) {
				return 'internal';
			} else {
				return 'external';
			}
		} else {
			return 'external';
		}
	}

	public static function getClientIp() {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}
		return '';
	}

	public static function getNetworkAccess($_mode = 'auto', $_protocole = '', $_default = '', $_test = true) {
		if ($_mode == 'auto') {
			$_mode = self::getUserLocation();
		}
		if ($_test && !self::test($_mode, false)) {
			self::checkConf($_mode);
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
			if ($_protocole == 'proto:ip:port' || $_protocole == 'proto:dns:port') {
				return config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . config::byKey('internalPort', 'core', 80);
			}
			if ($_protocole == 'proto:127.0.0.1:port:comp') {
				return config::byKey('internalProtocol') . '127.0.0.1:' . config::byKey('internalPort', 'core', 80) . config::byKey('internalComplement');
			}
			if ($_protocole == 'http:127.0.0.1:port:comp') {
				return 'http://127.0.0.1:' . config::byKey('internalPort', 'core', 80) . config::byKey('internalComplement');
			}
			return config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . config::byKey('internalPort', 'core', 80) . config::byKey('internalComplement');

		}
		if ($_mode == 'dnsjeedom') {
			return config::byKey('jeedom::url');
		}
		if ($_mode == 'external') {
			if ($_protocole == 'ip') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '') {
					return getIpFromString(config::byKey('jeedom::url'));
				}
				return getIpFromString(config::byKey('externalAddr'));
			}
			if ($_protocole == 'ip:port') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '') {
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
			if ($_protocole == 'proto:dns:port' || $_protocole == 'proto:ip:port') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '') {
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
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '') {
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
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '') {
					$url = parse_url(config::byKey('jeedom::url'));
					if (isset($url['scheme'])) {
						return $url['scheme'] . '://';
					}
				}
				return config::byKey('externalProtocol');
			}
			if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '') {
				return config::byKey('jeedom::url');
			}
			return config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80) . config::byKey('externalComplement');
		}
	}

	public static function checkConf($_mode = 'external') {
		if ($_mode == 'internal') {
			if (trim(config::byKey('internalComplement')) == '/') {
				config::save('internalComplement', '');
			}
			if (!filter_var(config::byKey('internalAddr'), FILTER_VALIDATE_IP)) {
				$internalAddr = str_replace(array('http://', 'https://'), '', config::byKey('internalAddr'));
				$pos = strpos($internalAddr, '/');
				if ($pos !== false) {
					$internalAddr = substr($internalAddr, 0, $pos);
				}
				if ($internalAddr != config::byKey('internalAddr') && !netMatch('127.0.*.*', $internalAddr)) {
					config::save('internalAddr', $internalAddr);
				}
			} else {
				$internalIp = getHostByName(getHostName());
				if (netMatch('127.0.*.*', $internalIp) || $internalIp == '' || !filter_var($internalIp, FILTER_VALIDATE_IP)) {
					$internalIp = self::getInterfaceIp('eth0');
				}
				if (netMatch('127.0.*.*', $internalIp) || $internalIp == '' || !filter_var($internalIp, FILTER_VALIDATE_IP)) {
					$internalIp = self::getInterfaceIp('bond0');
				}
				if (netMatch('127.0.*.*', $internalIp) || $internalIp == '' || !filter_var($internalIp, FILTER_VALIDATE_IP)) {
					$internalIp = self::getInterfaceIp('wlan0');
				}
				if (netMatch('127.0.*.*', $internalIp) || $internalIp == '' || !filter_var($internalIp, FILTER_VALIDATE_IP)) {
					$internalIp = explode(' ', shell_exec('hostname -I'));
					$internalIp = $internalIp[0];
				}
				if ($internalIp != '' && filter_var($internalIp, FILTER_VALIDATE_IP) && !netMatch('127.0.*.*', $internalIp)) {
					config::save('internalAddr', $internalIp);
				}
			}

			if (config::byKey('internalProtocol') == '') {
				config::save('internalProtocol', 'http://');
			}
			if (config::byKey('internalPort') == '') {
				config::save('internalPort', 80);
			}

			if (config::byKey('internalProtocol') == 'https://' && config::byKey('internalPort') == 80) {
				config::save('internalPort', 443);
			}

			if (config::byKey('internalProtocol') == 'http://' && config::byKey('internalPort') == 443) {
				config::save('internalPort', 80);
			}
		}
		if ($_mode == 'external') {
			if ($_mode == 'external' && trim(config::byKey('externalComplement')) == '/') {
				config::save('externalComplement', '');
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
		}

		if (file_exists('/etc/nginx/sites-available/default')) {
			$data = file_get_contents('/etc/nginx/sites-available/default');
			if (strpos($data, 'root /usr/share/nginx/www;') !== false) {
				if ($_mode == 'internal') {
					config::save('internalComplement', '/jeedom');
				}
				if ($_mode == 'external') {
					config::save('externalComplement', '/jeedom');
				}
			} else {
				if ($_mode == 'internal') {
					config::save('internalComplement', '');
				}
				if ($_mode == 'external') {
					config::save('externalComplement', '');
				}
			}
		}
	}

	public static function test($_mode = 'external', $_test = true, $_timeout = 10) {
		if ($_mode == 'internal' && netMatch('127.0.*.*', self::getNetworkAccess($_mode, 'ip', '', false))) {
			return false;
		}
		$url = trim(self::getNetworkAccess($_mode, '', '', $_test), '/') . '/here.html';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $_timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			log::add('network', 'debug', 'Erreur sur ' . $url . ' => ' . curl_errno($ch));
			curl_close($ch);
			return false;
		}
		curl_close($ch);
		if (trim($data) != 'ok') {
			log::add('network', 'debug', 'Retour NOK sur ' . $url . ' => ' . $data);
			return false;
		}
		return true;
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
		if (!file_exists('/etc/nginx/sites-available/jeedom_dynamic_rule')) {
			touch('/etc/nginx/sites-available/jeedom_dynamic_rule');
		}
		shell_exec('sudo chmod 777 /etc/nginx/sites-available/jeedom_dynamic_rule');
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
			if (!file_exists('/etc/nginx/sites-available/jeedom_dynamic_rule')) {
				touch('/etc/nginx/sites-available/jeedom_dynamic_rule');
			}
			shell_exec('sudo chmod 777 /etc/nginx/sites-available/jeedom_dynamic_rule');
			file_put_contents('/etc/nginx/sites-available/jeedom_dynamic_rule', $result);
			shell_exec('sudo service nginx reload');
		}
	}

/*     * *********************NGROK************************* */

	public static function dns_start() {
		log::add('dns_jeedom', 'debug', 'Redemarrage du service DNS');
		$cmd = '/usr/bin/nodejs ' . dirname(__FILE__) . '/../../script/localtunnel/bin/client';
		$cmd .= ' --host http://dns.jeedom.fr --port 80 --authentification ' . config::byKey('ngrok::token') . ' --subdomain ' . config::byKey('ngrok::addr');
		exec($cmd . ' >> ' . log::getPathToLog('dns_jeedom') . ' 2>&1 &');
		return true;
	}

	public static function dns_run() {
		return (shell_exec('ps ax | grep -ie "localtunnel/bin/client" | grep -v grep | wc -l') > 0);
	}

	public static function dns_stop() {
		exec("ps aux | grep -ie \"localtunnel/bin/client\" | grep -v grep | awk '{print $2}' | xargs kill -9 > /dev/null 2>&1");
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

	public static function getMac($_interface = 'eth0') {
		return shell_exec("ip addr show $_interface | grep -i 'link/ether' | grep -o -E '([[:xdigit:]]{1,2}:){5}[[:xdigit:]]{1,2}' | sed -n 1p");
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
		if (!file_exists("/sys/class/net/eth0/operstate")) {
			return false;
		}
		return (trim(file_get_contents("/sys/class/net/eth0/operstate")) == 'up') ? true : false;
	}

	public static function wlanIsUp() {
		if (!file_exists("/sys/class/net/wlan0/operstate")) {
			return false;
		}
		return (trim(file_get_contents("/sys/class/net/wlan0/operstate")) == 'up') ? true : false;
	}

	public static function writeInterfaceFile() {
		if (!self::canManageNetwork()) {
			return;
		}
		if (!jeedom::isCapable('wifi') || !jeedom::isCapable('ipfix')) {
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
				if ($return[$route['iface']]['ping'] == 'nok') {
					sleep(5);
					exec('sudo ping -c 1 ' . $route['gateway'] . ' > /dev/null 2> /dev/null', $output, $return_val);
					$return[$route['iface']]['ping'] = ($return_val == 0) ? 'ok' : 'nok';
				}
			} else {
				$return[$route['iface']]['ping'] = 'ok';
			}
		}
		return $return;
	}

	public static function cron() {
		if (!jeedom::isCapable('sudo')) {
			return;
		}
		$gws = self::checkGw();
		if (count($gws) == 0) {
			log::add('network', 'error', __('Aucune interface réseau trouvée, je redemarre tous le réseaux', __FILE__));
			exec('sudo service networking restart');
			return;
		}
		foreach ($gws as $iface => $gw) {
			if ($gw['ping'] != 'ok') {
				if (strpos($iface, 'tun') !== false) {
					continue;
				}
				if (strpos($iface, 'br0') !== false) {
					continue;
				}
				log::add('network', 'error', __('La passerelle distance de l\'interface ', __FILE__) . $iface . __(' est injoignable je la redemarre pour essayer de corriger', __FILE__));
				exec('sudo ifdown ' . $iface);
				sleep(5);
				exec('sudo ifup --force ' . $iface);
			}
		}
	}

}

?>
