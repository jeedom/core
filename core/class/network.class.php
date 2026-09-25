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
require_once __DIR__ . '/../../core/php/core.inc.php';

class network {

	public static function getUserLocation(): string {
		$client_ip = self::getClientIp();
		$jeedom_ip = self::getNetworkAccess('internal', 'ip', '', false);
		if (!filter_var($jeedom_ip, FILTER_VALIDATE_IP)) {
			return 'external';
		}
		if (config::byKey('network::localip') != '') {
			$localNetworkIps = explode(';', config::byKey('network::localip'));
			foreach ($localNetworkIps as $localNetworkIp) {
				if (netMatch($localNetworkIp, $client_ip)) {
					return 'internal';
				}
			}
		}
		$jeedom_ips = explode('.', $jeedom_ip);
		if (count($jeedom_ips) != 4) {
			return 'external';
		}
		$match = $jeedom_ips[0] . '.' . $jeedom_ips[1] . '.' . $jeedom_ips[2] . '.*';
		return netMatch($match, $client_ip) ? 'internal' : 'external';
	}

	private static function extractValidIp(string $value): string {
		if (empty($value)) {
			return '';
		}

		$ip = trim($value);
		return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
	}

	/**
	 * Checks whether an IP address belongs to an IP address or CIDR network.
	 *
	 * @param string $ip IP address to check
	 * @param string $network IP address or CIDR network in IPv4 or IPv6 format
	 * @return bool True when the IP matches the network, false for invalid or non-matching values
	 *
	 * @example network::ipMatchesNetwork('192.168.1.10', '192.168.1.10')
	 * @example network::ipMatchesNetwork('192.168.1.10', '192.168.1.0/24')
	 * @example network::ipMatchesNetwork('2001:db8::10', '2001:db8::/32')
	 */
	public static function ipMatchesNetwork(string $ip, string $network): bool {
		$ipBinary = inet_pton($ip);
		if ($ipBinary === false) {
			return false;
		}

		$parts = explode('/', $network, 2);
		$networkBinary = inet_pton($parts[0]);
		if ($networkBinary === false || strlen($networkBinary) !== strlen($ipBinary)) {
			return false;
		}
		if (count($parts) === 1) {
			return $ipBinary === $networkBinary;
		}

		$prefixLength = filter_var($parts[1], FILTER_VALIDATE_INT);
		$maxPrefixLength = strlen($ipBinary) * 8;
		if ($prefixLength === false || $prefixLength < 0 || $prefixLength > $maxPrefixLength) {
			return false;
		}

		$bytesToCompare = intdiv($prefixLength, 8);
		$remainingBits = $prefixLength % 8;
		if ($bytesToCompare > 0 && substr($ipBinary, 0, $bytesToCompare) !== substr($networkBinary, 0, $bytesToCompare)) {
			return false;
		}
		if ($remainingBits > 0) {
			$mask = chr((0xFF << (8 - $remainingBits)) & 0xFF);
			return (ord($ipBinary[$bytesToCompare]) & ord($mask)) === (ord($networkBinary[$bytesToCompare]) & ord($mask));
		}
		return true;
	}

	private static function isTrustedProxy(string $ip): bool {
		if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
			return false;
		}
		$trustedProxiesConfiguration = trim(config::byKey('security::trustedProxies', 'core', ''));
		if ($trustedProxiesConfiguration === '') {
			return false;
		}
		if (strtolower($trustedProxiesConfiguration) === 'none') {
			return false;
		}

		$trustedProxies = explode(';', $trustedProxiesConfiguration);
		foreach ($trustedProxies as $trustedProxy) {
			$trustedProxy = trim($trustedProxy);
			if ($trustedProxy === '') {
				continue;
			}
			if (self::ipMatchesNetwork($ip, $trustedProxy)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns the IP if it is a public IP, otherwise returns an empty string.
	 *
	 * @param string $ip
	 * @return string
	 */
	private static function returnIfPublicIp(string $ip): string {
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
			return $ip;
		}
		$message = sprintf(__('La configuration de vos proxys de confiance semble incorrecte: requête contenant des headers X-Forwarded-For et/ou X-Real-IP reçue depuis une IP privée (%s).', __FILE__), $ip);
		$action = '<a href="index.php?v=d&p=administration#securitytab">' . __('Configuration système > Sécurité', __FILE__) . '</a>';
		log::add('network', 'warning', $message);
		message::add('core', $message, $action);
		return '';
	}

	private static function hasTrustedProxyConfiguration(): bool {
		$trustedProxiesConfiguration = trim(config::byKey('security::trustedProxies', 'core', ''));
		$logicalId = 'trustedProxiesMigration';
		if ($trustedProxiesConfiguration !== '') {
			message::removeByPluginLogicalId('core', $logicalId);
			return true;
		}

		if (count(message::byPluginLogicalId('core', $logicalId)) === 0) {
			log::add('network', 'warning', 'Trusted proxies configuration is missing, your installation may be vulnerable.');
			$message = __('La configuration des proxys de confiance est absente. Les en-têtes de proxy sont temporairement acceptés. Votre installation est vulnérable.', __FILE__);
			$action = '<a href="index.php?v=d&p=administration#securitytab">' . __('Configuration système > Sécurité', __FILE__) . '</a>';
			message::add('core', $message, $action, $logicalId);
		}
		return false;
	}

	/**
	 * Resolves the client IP from the X-Forwarded-For header and trusted proxies.
	 *
	 * @param string $remoteIp IP address of the direct connection
	 * @return string|null Client IP, empty string for an invalid or unresolved header, or null when absent
	 */
	private static function getClientIpFromXForwardedFor(string $remoteIp): ?string {
		if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return null;
		}

		$forwardedIps = array();
		foreach (explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']) as $value) {
			$forwardedIp = self::extractValidIp($value);
			if ($forwardedIp === '') {
				log::add('network', 'warning', 'Invalid IP found in X-Forwarded-For header: ' . $value);
				return '';
			}
			$forwardedIps[] = $forwardedIp;
		}

		if (count(array_unique($forwardedIps)) === 1 && $forwardedIps[0] === $remoteIp) {
			return $remoteIp;
		}
		if (!self::hasTrustedProxyConfiguration()) {
			return $forwardedIps[0];
		}
		if (!self::isTrustedProxy($remoteIp)) {
			return self::returnIfPublicIp($remoteIp);
		}

		for ($index = count($forwardedIps) - 1; $index >= 0; $index--) {
			if (!self::isTrustedProxy($forwardedIps[$index])) {
				return $forwardedIps[$index];
			}
		}
		return '';
	}

	/**
	 * Resolves the client IP from the X-Real-IP and CF-Connecting-IP headers.
	 *
	 * @param string $remoteIp IP address of the direct connection
	 * @return string|null Client IP, empty string for an invalid header, or null when both headers are absent
	 */
	private static function getClientIpFromHeaders(string $remoteIp): ?string {
		foreach (array('HTTP_X_REAL_IP', 'HTTP_CF_CONNECTING_IP') as $source) {
			if (!empty($_SERVER[$source])) {
				$headerIp = self::extractValidIp($_SERVER[$source]);
				if ($headerIp === '') {
					log::add('network', 'warning', "Invalid IP found in {$source} header: {$_SERVER[$source]}");
					return '';
				}
				if ($headerIp === $remoteIp) {
					return $headerIp;
				}
				if (!self::hasTrustedProxyConfiguration()) {
					return $headerIp;
				}
				if (!self::isTrustedProxy($remoteIp)) {
					return self::returnIfPublicIp($remoteIp);
				}
				return $headerIp;
			}
		}
		return null;
	}

	public static function getClientIp(): string {
		static $ipAddress = null;
		if ($ipAddress !== null) {
			return $ipAddress;
		}
		$ipAddress = self::extractValidIp($_SERVER['REMOTE_ADDR'] ?? '');
		if ($ipAddress === '') {
			return $ipAddress;
		}

		$headerIp = self::getClientIpFromHeaders($ipAddress);
		if ($headerIp !== null) {
			$ipAddress = $headerIp;
			return $ipAddress;
		}

		$forwardedIp = self::getClientIpFromXForwardedFor($ipAddress);
		if ($forwardedIp !== null) {
			$ipAddress = $forwardedIp;
		}
		return $ipAddress;
	}

	public static function getNetworkAccess($_mode = 'auto', $_protocol = '', $_default = '', $_test = false) {
		if ($_mode == 'auto') {
			$_mode = self::getUserLocation();
		}
		if ($_mode == 'internal' && config::byKey('internalAddr', 'core', '') == '') {
			self::checkConf($_mode);
		}
		if ($_mode == 'external' && config::byKey('market::allowDNS') != 1 && config::byKey('externalAddr', 'core', '') == '') {
			self::checkConf($_mode);
		}
		if ($_test && !self::test($_mode)) {
			self::checkConf($_mode);
		}
		if ($_mode == 'internal') {
			if (strpos(config::byKey('internalAddr', 'core', $_default), 'http://') !== false || strpos(config::byKey('internalAddr', 'core', $_default), 'https://') !== false) {
				config::save('internalAddr', str_replace(array('http://', 'https://'), '', config::byKey('internalAddr', 'core', $_default)));
			}
			if ($_protocol == 'ip' || $_protocol == 'dns') {
				return config::byKey('internalAddr', 'core', $_default);
			}
			if ($_protocol == 'ip:port' || $_protocol == 'dns:port') {
				return config::byKey('internalAddr') . ':' . self::getPort($_mode);
			}
			if ($_protocol == 'proto:ip' || $_protocol == 'proto:dns') {
				return config::byKey('internalProtocol') . config::byKey('internalAddr');
			}
			if ($_protocol == 'proto:ip:port' || $_protocol == 'proto:dns:port') {
				return config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . self::getPort($_mode);
			}
			if ($_protocol == 'proto:127.0.0.1:port:comp') {
				if (jeedom::getHardwareName() == 'docker') {
					return trim(config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . self::getPort($_mode) . '/' . trim(config::byKey('internalComplement'), '/'), '/');
				}
				return trim(config::byKey('internalProtocol') . '127.0.0.1:' . self::getPort($_mode) . '/' . trim(config::byKey('internalComplement'), '/'), '/');
			}
			if ($_protocol == 'http:127.0.0.1:port:comp') {
				if (jeedom::getHardwareName() == 'docker') {
					return trim('http://' . config::byKey('internalAddr') . ':' . self::getPort($_mode, 'http') . '/' . trim(config::byKey('internalComplement'), '/'), '/');
				}
				return trim('http://127.0.0.1:' . self::getPort($_mode, 'http') . '/' . trim(config::byKey('internalComplement'), '/'), '/');
			}
			if (config::byKey('internalProtocol') == 'http://' && self::getPort($_mode) == 80) {
				return trim(config::byKey('internalProtocol') . config::byKey('internalAddr') . '/' . trim(config::byKey('internalComplement'), '/'), '/');
			}
			if (config::byKey('internalProtocol') == 'https://' && self::getPort($_mode) == 443) {
				return trim(config::byKey('internalProtocol') . config::byKey('internalAddr') . '/' . trim(config::byKey('internalComplement'), '/'), '/');
			}
			return trim(config::byKey('internalProtocol') . config::byKey('internalAddr') . ':' . self::getPort($_mode) . '/' . trim(config::byKey('internalComplement'), '/'), '/');
		}
		if ($_mode == 'dnsjeedom') {
			return config::byKey('jeedom::url');
		}
		if ($_mode == 'external') {
			if ($_protocol == 'ip') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && config::byKey('network::disableMangement') == 0) {
					return getIpFromString(config::byKey('jeedom::url'));
				}
				return getIpFromString(config::byKey('externalAddr'));
			}
			if ($_protocol == 'ip:port') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && config::byKey('network::disableMangement') == 0) {
					$url = parse_url(config::byKey('jeedom::url'));
					if (isset($url['host'])) {
						if (isset($url['port'])) {
							return getIpFromString($url['host']) . ':' . $url['port'];
						} else {
							return getIpFromString($url['host']);
						}
					}
				}
				return config::byKey('externalAddr') . ':' . self::getPort($_mode);
			}
			if ($_protocol == 'proto:dns:port' || $_protocol == 'proto:ip:port') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && config::byKey('network::disableMangement') == 0) {
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
				return config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . self::getPort($_mode);
			}
			if ($_protocol == 'proto:dns' || $_protocol == 'proto:ip') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && config::byKey('network::disableMangement') == 0) {
					$url = parse_url(config::byKey('jeedom::url'));
					$return = '';
					if (isset($url['scheme'])) {
						$return = $url['scheme'] . '://';
					}
					if (isset($url['host'])) {
						if (isset($url['port'])) {
							return $return . $url['host'];
						} else {
							return $return . $url['host'];
						}
					}
				}
				return config::byKey('externalProtocol') . config::byKey('externalAddr');
			}
			if ($_protocol == 'dns:port') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && config::byKey('network::disableMangement') == 0) {
					$url = parse_url(config::byKey('jeedom::url'));
					if (isset($url['host'])) {
						if (isset($url['port'])) {
							return $url['host'] . ':' . $url['port'];
						} else {
							return $url['host'];
						}
					}
				}
				return config::byKey('externalAddr') . ':' . self::getPort($_mode);
			}
			if ($_protocol == 'proto') {
				if (config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && config::byKey('network::disableMangement') == 0) {
					$url = parse_url(config::byKey('jeedom::url'));
					if (isset($url['scheme'])) {
						return $url['scheme'] . '://';
					}
				}
				return config::byKey('externalProtocol');
			}

			if (config::byKey('dns::token') != '' && config::byKey('market::allowDNS') == 1 && config::byKey('jeedom::url') != '' && config::byKey('network::disableMangement') == 0) {
				return trim(config::byKey('jeedom::url') . '/' . trim(config::byKey('externalComplement', 'core', ''), '/'), '/');
			}
			if (config::byKey('externalProtocol') == 'http://' && self::getPort($_mode, 'http') == 80) {
				return trim(config::byKey('externalProtocol') . config::byKey('externalAddr') . '/' . trim(config::byKey('externalComplement'), '/'), '/');
			}
			if (config::byKey('externalProtocol') == 'https://' && self::getPort($_mode, 'https') == 443) {
				return trim(config::byKey('externalProtocol') . config::byKey('externalAddr') . '/' . trim(config::byKey('externalComplement'), '/'), '/');
			}
			return trim(config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . self::getPort($_mode) . '/' . trim(config::byKey('externalComplement'), '/'), '/');
		}
	}

	private static function getPort(string $_mode = 'internal', string $_protocol = '') {
		if ($_mode != 'internal' && $_mode != 'external') {
			throw new InvalidArgumentException("Mode must be 'internal' or 'external'. Invalid mode received: {$_mode}");
		}
		if ($_protocol == '') {
			$_protocol = strtolower(trim(config::byKey("{$_mode}Protocol", 'core'), ':/ '));
		}
		if ($_protocol != 'http' && $_protocol != 'https') {
			throw new RuntimeException("Protocol must be 'http' or 'https'. Invalid protocol: {$_protocol}");
		}

		if ($_protocol == 'http') {
			$default_port = 80;
		} else {
			$default_port = 443;
		}
		return config::byKey("{$_mode}Port", 'core', $default_port);
	}

	public static function checkConf($_mode = 'external') {
		if (config::byKey($_mode . 'Protocol') == '') {
			config::save($_mode . 'Protocol', 'http://');
		}
		if (config::byKey($_mode . 'Port') == '') {
			config::save($_mode . 'Port', self::getPort($_mode));
		}
		if (config::byKey($_mode . 'Protocol') == 'https://' && self::getPort($_mode) == 80) {
			config::save($_mode . 'Port', 443);
		}
		if (config::byKey($_mode . 'Protocol') == 'http://' && self::getPort($_mode) == 443) {
			config::save($_mode . 'Port', 80);
		}
		if (trim(config::byKey($_mode . 'Complement')) == '/') {
			config::save($_mode . 'Complement', '');
		}
		if ($_mode == 'internal') {
			if (config::byKey('network::disableInternalAuto', 'core', 0) == 0) {
				foreach ((self::getInterfacesInfo()) as $interface) {
					if ($interface['ifname'] == 'lo' || !isset($interface['addr_info']) || strpos($interface['ifname'], 'docker') !== false  || strpos($interface['ifname'], 'tun') !== false || strpos($interface['ifname'], 'br') !== false) {
						continue;
					}
					if (config::byKey('network::internalAutoInterface', 'core', 'auto') != 'auto' && $interface['ifname'] != config::byKey('network::internalAutoInterface', 'core', 'auto')) {
						continue;
					}
					$ip = null;
					foreach ($interface['addr_info'] as $addr_info) {
						if (isset($addr_info['family']) && $addr_info['family'] == 'inet') {
							$ip = $addr_info['local'];
						}
					}
					if ($ip == null) {
						continue;
					}
					if (!netMatch('127.0.*.*', $ip) && !netMatch('169.*.*.*', $ip) && $ip != '' && filter_var($ip, FILTER_VALIDATE_IP)) {
						config::save('internalAddr', $ip);
						break;
					}
				}
			}
		}
	}

	public static function test($_mode = 'external', $_timeout = 15) {
		if (config::byKey('network::disableMangement') == 1 && $_mode == 'external') {
			return true;
		}
		if ($_mode == 'internal' && netMatch('127.0.*.*', self::getNetworkAccess($_mode, 'ip', '', false))) {
			return false;
		}
		$url = trim(self::getNetworkAccess($_mode, '', '', false), '/') . '/here.html';
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
			usleep(rand(1000, 10000));
			$data = curl_exec($ch);
			if (curl_errno($ch)) {
				log::add('network', 'debug', 'Erreur sur ' . $url . ' => ' . curl_error($ch));
				curl_close($ch);
				return false;
			}
		}
		unset($ch);
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
		if (!plugin::isInstalled('openvpn')) {
			$update = update::byLogicalId('openvpn');
			if (!is_object($update)) {
				$update = new update();
			}
			$update->setLogicalId('openvpn');
			$update->setSource('market');
			$update->setConfiguration('version', 'stable');
			$update->save();
			$update->doUpdate();
		}
		try {
			$plugin = plugin::byId('openvpn');
		} catch (Exception $e) {
			$plugin = null;
		}
		if (!is_object($plugin) || !class_exists('openvpn')) {
			throw new Exception(__('Le plugin OpenVPN doit être installé', __FILE__));
		}
		if (!$plugin->isActive()) {
			$plugin->setIsEnable(1);
			$plugin->dependancy_install();
		}
		if (!$plugin->isActive()) {
			throw new Exception(__('Le plugin OpenVPN doit être actif', __FILE__));
		}
		$openvpn = eqLogic::byLogicalId('dnsjeedom', 'openvpn');
		$direct = true;
		if (!is_object($openvpn)) {
			$direct = false;
			$openvpn = new openvpn();
			$openvpn->setName('DNS Jeedom');
		}
		$openvpn->setIsEnable(1);
		$openvpn->setLogicalId('dnsjeedom');
		$openvpn->setEqType_name('openvpn');
		$openvpn->setConfiguration('dev', 'tun');
		$openvpn->setConfiguration('proto', 'udp');
		if (config::byKey('dns::preferProtocol') != '' && strpos(config::byKey('dns::protocol'), config::byKey('dns::preferProtocol')) !== false) {
			$openvpn->setConfiguration('proto', config::byKey('dns::preferProtocol'));
		}
		if (config::byKey('dns::vpnurl') != '') {
			$openvpn->setConfiguration('remote_host', config::byKey('dns::vpnurl'));
		} else {
			$openvpn->setConfiguration('remote_host', 'vpn.dns' . config::byKey('dns::number', 'core', 1) . '.jeedom.com');
		}
		if (config::byKey('dns::remote') != '') {
			$openvpn->setConfiguration('remote', config::byKey('dns::remote'));
		}
		$openvpn->setConfiguration('username', jeedom::getHardwareKey());
		$openvpn->setConfiguration('password', config::byKey('dns::token'));
		$openvpn->setConfiguration('compression', 'comp-lzo');
		$openvpn->setConfiguration('remote_port', config::byKey('vpn::port', 'core', 1194));
		$openvpn->setConfiguration('auth_mode', 'password');
		$openvpn->save($direct);
		if (!file_exists(__DIR__ . '/../../plugins/openvpn/data')) {
			shell_exec('mkdir -p ' . __DIR__ . '/../../plugins/openvpn/data');
		}
		$path_ca = __DIR__ . '/../../plugins/openvpn/data/ca_' . $openvpn->getConfiguration('key') . '.crt';
		if (file_exists($path_ca)) {
			unlink($path_ca);
		}
		copy(__DIR__ . '/../../resources/ca_dns.crt', $path_ca);
		if (!file_exists($path_ca)) {
			throw new Exception(__('Impossible de créer le fichier  :', __FILE__) . ' ' . $path_ca);
		}
		return $openvpn;
	}


	public static function dns_start() {
		if (config::byKey('dns::token') == '') {
			return;
		}
		if (config::byKey('market::allowDNS') != 1) {
			return;
		}
		$vpn = self::dns_create();
		$cmd = $vpn->getCmd('action', 'start');
		if (!is_object($cmd)) {
			throw new Exception(__('La commande de démarrage du DNS est introuvable', __FILE__));
		}
		$cmd->execCmd();
	}

	public static function dns_run() {
		if (config::byKey('dns::token') == '') {
			return false;
		}
		if (config::byKey('market::allowDNS') != 1) {
			return false;
		}
		try {
			$vpn = self::dns_create();
		} catch (Exception $e) {
			return false;
		}
		$cmd = $vpn->getCmd('info', 'state');
		if (!is_object($cmd)) {
			throw new Exception(__('La commande de statut du DNS est introuvable', __FILE__));
		}
		return $cmd->execCmd();
	}

	public static function dns_stop() {
		if (config::byKey('dns::token') == '') {
			return;
		}
		$vpn = self::dns_create();
		$cmd = $vpn->getCmd('action', 'stop');
		if (!is_object($cmd)) {
			throw new Exception(__('La commande d\'arrêt du DNS est introuvable', __FILE__));
		}
		$cmd->execCmd();
	}

	/*     * *********************Network management************************* */

	public static function portOpen($host, $port) {
		$fp = @fsockopen($host, $port, $errno, $errstr, 0.1);
		if (!is_resource($fp)) {
			return false;
		}
		fclose($fp);
		return true;
	}

	public static function getInterfacesInfo() {
		return json_decode(shell_exec(system::getCmdSudo() . "ip -j a"), true);
	}

	public static function cron10() {
		if (config::byKey('dns::token') != '' && config::byKey('market::allowDNS') == 1) {
			sleep(rand(0, 240));
			if (!network::test('external')) {
				sleep(rand(20, 60));
				if (!network::test('external')) {
					log::add('network', 'warning', __('Accès externe non ok, redémarrage du dns Jeedom', __FILE__));
					self::dns_stop();
					self::dns_start();
				}
			}
		}
		if (config::byKey('network::disableMangement') == 1) {
			return;
		}
		if (!jeedom::isCapable('sudo') || jeedom::getHardwareName() == 'docker') {
			return;
		}
		exec(system::getCmdSudo() . 'ping -n -c 1 -t 255 8.8.8.8 2>&1 > /dev/null', $output, $return_val);
		if ($return_val == 0) {
			return;
		}
		$gw = shell_exec("ip route show default | awk '/default/ {print $3}'");
		if ($gw == '') {
			log::add('network', 'error', __('Souci réseau détecté, redémarrage du réseau. Aucune gateway de trouvée', __FILE__));
			exec(system::getCmdSudo() . 'service networking restart');
			return;
		}
		exec(system::getCmdSudo() . 'ping -n -c 1 -t 255 ' . $gw . ' 2>&1 > /dev/null', $output, $return_val);
		if ($return_val == 0) {
			return;
		}
		exec(system::getCmdSudo() . 'ping -n -c 1 -t 255 ' . $gw . ' 2>&1 > /dev/null', $output, $return_val);
		if ($return_val == 0) {
			return;
		}
		log::add('network', 'error', __('Souci réseau détecté, redémarrage du réseau. La gateway ne répond pas au ping :', __FILE__) . ' ' . $gw);
		exec(system::getCmdSudo() . 'service networking restart');
	}
}
