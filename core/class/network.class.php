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
		if ($_mode == 'auto') {
			if (netMatch('192.168.*.*', getClientIp()) || netMatch('10.0.*.*', getClientIp())) {
				$_mode = 'internal';
			} else {
				$_mode = 'external';
			}
		}
		if ($_mode == 'internal') {
			if (config::byKey('internalAddr') == '') {
				self::internalAutoconf();
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
			if (config::byKey('jeedom::url') != '') {
				if (config::byKey('jeedom::url') != '') {
					return config::byKey('jeedom::url');
				}
			}
			return config::byKey('externalProtocol') . config::byKey('externalAddr') . ':' . config::byKey('externalPort', 'core', 80) . config::byKey('externalComplement');
		}
	}

	public static function internalAutoconf() {
		$internalIp = getHostByName(getHostName());
		if ($internalIp != '') {
			config::save('internalAddr', $internalIp);
		}
		config::save('internalProtocol', 'http://');
		config::save('internalPort', 80);
		if (file_exists('/etc/nginx/sites-available/default')) {
			$data = file_get_contents('/etc/nginx/sites-available/default');
			if (strpos($data, 'root /usr/share/nginx/www;') !== false) {
				config::save('internalComplement', '/jeedom');
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

	public static function ngrok_start($_proto = 'https', $_port = 80, $_name = '') {
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
				'#name#' => $_name,
				'#proto#' => $_proto,
				'#port#' => $_port,
				'#remote_port#' => '',
				'#token#' => config::byKey('ngrok::token'),
				'#subdomain#' => config::byKey('ngrok::addr'),
			);
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
		return posix_getsid(intval($pid));
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
		$kill = posix_kill($pid, 15);
		if (!$kill) {
			sleep(1);
			posix_kill($pid, 9);
		}
		return !self::ngrok_run($_proto, $_port, $_name);
	}

/*     * *********************Methode d'instance************************* */

/*     * **********************Getteur Setteur*************************** */
}

?>
