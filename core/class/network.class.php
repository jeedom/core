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
		} elseif (isset($_SERVER['HTTP_X_REAL_IP'])) {
			return $_SERVER['HTTP_X_REAL_IP'];
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
				return trim(config::byKey('internalProtocol') . '127.0.0.1:' . config::byKey('internalPort', 'core', 80) . '/' . trim(config::byKey('internalComplement'), '/'), '/');
			}
			if ($_protocole == 'http:127.0.0.1:port:comp') {
				return trim('http://127.0.0.1:' . config::byKey('internalPort', 'core', 80) . '/' . trim(config::byKey('internalComplement'), '/'), '/');
			}
			return trim(config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . config::byKey('internalPort', 'core', 80) . '/' . trim(config::byKey('internalComplement'), '/'), '/');

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
				return trim(config::byKey('jeedom::url') . '/' . trim(config::byKey('externalComplement', 'core', ''), '/'), '/');
			}
			return trim(config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80) . '/' . trim(config::byKey('externalComplement'), '/'), '/');
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
					$internalIp = gethostbyname(trim(exec("hostname")));
				}
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
		if (config::byKey('network::disableMangement') == 1) {
			return true;
		}
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
		if ($_mode == 'external') {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
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

/*     * *********************DNS************************* */

	public static function dns_create() {
		if (config::byKey('dns::token') == '') {
			return;
		}
		if (config::byKey('market::allowDNS') != 1) {
			return;
		}
		try {
			$plugin = plugin::byId('openvpn');
			if (!is_object($plugin)) {
				$plugin = market::byLogicalIdAndType('openvpn', 'plugin');
				$plugin->install('stable');
				$plugin = plugin::byId('openvpn');
			}
		} catch (Exception $e) {
			$plugin = market::byLogicalIdAndType('openvpn', 'plugin');
			$plugin->install('stable');
			$plugin = plugin::byId('openvpn');
		}
		if (!$plugin->isActive()) {
			$plugin->setIsEnable(1);
		}
		if (!is_object($plugin)) {
			throw new Exception(__('Le plugin openvpn doit être installé', __FILE__));
		}
		if (!$plugin->isActive()) {
			throw new Exception(__('Le plugin openvpn doit être actif', __FILE__));
		}
		$openvpn = eqLogic::byLogicalId('dnsjeedom', 'openvpn');
		if (!is_object($openvpn)) {
			$openvpn = new openvpn();
			$openvpn->setName('DNS Jeedom');
		}
		$openvpn->setIsEnable(1);
		$openvpn->setLogicalId('dnsjeedom');
		$openvpn->setEqType_name('openvpn');
		$openvpn->setConfiguration('dev', 'tun');
		$openvpn->setConfiguration('proto', 'udp');
		$openvpn->setConfiguration('remote_host', 'vpn' . config::byKey('dns::number', 'core', 1) . '.jeedom.com');
		$openvpn->setConfiguration('username', jeedom::getHardwareKey());
		$openvpn->setConfiguration('password', config::byKey('dns::token'));
		$openvpn->setConfiguration('compression', 'comp-lzo');
		$openvpn->setConfiguration('remote_port', 1194);
		$openvpn->setConfiguration('auth_mode', 'password');
		$openvpn->save();
		if (!file_exists(dirname(__FILE__) . '/../../plugins/openvpn/data')) {
			shell_exec('mkdir -p ' . dirname(__FILE__) . '/../../plugins/openvpn/data');
		}
		copy(dirname(__FILE__) . '/../../script/ca_dns.crt', dirname(__FILE__) . '/../../plugins/openvpn/data/ca_' . $openvpn->getConfiguration('key') . '.crt');
		return $openvpn;
	}

	public static function dns_start() {
		if (config::byKey('dns::token') == '') {
			return;
		}
		if (config::byKey('market::allowDNS') != 1) {
			return;
		}
		$openvpn = self::dns_create();
		$cmd = $openvpn->getCmd('action', 'start');
		if (!is_object($cmd)) {
			throw new Exception(__('La commande de start du DNS est introuvable', __FILE__));
		}
		$cmd->execCmd();
		$interface = $openvpn->getInterfaceName();
		if ($interface != null && $interface != '' && $interface !== false) {
			shell_exec('sudo iptables -A INPUT -i ' . $interface . ' -p tcp  --destination-port 80 -j ACCEPT');
			shell_exec('sudo iptables -A INPUT -i ' . $interface . ' -j DROP');
		}
	}

	public static function dns_run() {
		if (config::byKey('dns::token') == '') {
			return false;
		}
		if (config::byKey('market::allowDNS') != 1) {
			return false;
		}
		try {
			$openvpn = self::dns_create();
		} catch (Exception $e) {
			return false;
		}
		$cmd = $openvpn->getCmd('info', 'state');
		if (!is_object($cmd)) {
			throw new Exception(__('La commande de statut du DNS est introuvable', __FILE__));
		}
		return $cmd->execCmd();
	}

	public static function dns_stop() {
		if (config::byKey('dns::token') == '') {
			return;
		}
		if (config::byKey('market::allowDNS') != 1) {
			return;
		}
		$openvpn = self::dns_create();
		$cmd = $openvpn->getCmd('action', 'stop');
		if (!is_object($cmd)) {
			throw new Exception(__('La commande de stop du DNS est introuvable', __FILE__));
		}
		$cmd->execCmd();
	}

/*     * *********************Network management************************* */

	public static function getInterfaceIp($_interface) {
		$results = trim(shell_exec('sudo ip addr show ' . $_interface . '| grep inet | head -1 2>&1'));
		$results = explode(' ', $results);
		if (!isset($results[1])) {
			return false;
		}
		$result = $results[1];
		$ip = substr($result, 0, strrpos($result, '/'));
		if (filter_var($ip, FILTER_VALIDATE_IP)) {
			return $ip;
		}
		return false;
	}

	public static function getInterfaceMac($_interface) {
		$valid_mac = "([0-9A-F]{2}[:-]){5}([0-9A-F]{2})";
		$results = trim(shell_exec('sudo ip addr show ' . $_interface . '| grep ether | head -1 2>&1'));
		$results = explode(' ', $results);
		if (!isset($results[1])) {
			return false;
		}
		$result = $results[1];
		if (preg_match("/" . $valid_mac . "/i", $result)) {
			return $result;
		}
		return false;
	}

	public static function getRoute() {
		$return = array();
		$results = trim(shell_exec('sudo route -n 2>&1'));
		if (strpos($results, 'command not found') !== false) {
			throw new Exception('Command route not found');
		}
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
				exec('sudo ping -n -c 1 -t 255 ' . $route['gateway'] . ' 2>&1 > /dev/null', $output, $return_val);
				$return[$route['iface']]['ping'] = ($return_val == 0) ? 'ok' : 'nok';
				if ($return[$route['iface']]['ping'] == 'nok') {
					exec('sudo ping -n -c 1 -t 255 ' . $route['gateway'] . ' 2>&1 > /dev/null', $output, $return_val);
					$return[$route['iface']]['ping'] = ($return_val == 0) ? 'ok' : 'nok';
				}
			} else {
				$return[$route['iface']]['ping'] = 'ok';
			}
		}
		return $return;
	}

	public static function cron() {
		if (config::byKey('network::disableMangement') == 1) {
			return;
		}
		if (!jeedom::isCapable('sudo') || jeedom::getHardwareName() == 'docker') {
			return;
		}
		try {
			$gws = self::checkGw();
			if (count($gws) == 0) {
				log::add('network', 'error', __('Aucune interface réseau trouvée, je redemarre tous les réseaux', __FILE__));
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
					log::add('network', 'error', __('La passerelle distante de l\'interface ', __FILE__) . $iface . __(' est injoignable, je la redemarre pour essayer de corriger', __FILE__));
					exec('sudo ifdown ' . $iface);
					sleep(5);
					exec('sudo ifup --force ' . $iface);
				}
			}
		} catch (Exception $e) {

		} catch (Error $e) {

		}
	}
}

?>
