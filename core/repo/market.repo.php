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

class repo_market {
	/*     * *************************Attributs****************************** */

	public static $_name = 'Market';

	public static $_scope = array(
		'plugin' => true,
		'backup' => true,
		'hasConfiguration' => true,
		'proxy' => true,
	);

	public static $_configuration = array(
		'configuration' => array(
			'address' => array(
				'name' => 'Adresse',
				'type' => 'input',
				'default' => 'https://market.jeedom.fr',
			),
			'username' => array(
				'name' => 'Nom d\'utilisateur',
				'type' => 'input',
			),
			'password' => array(
				'name' => 'Mot de passe',
				'type' => 'password',
			),
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function checkUpdate($_update) {
		$market_info = market::getInfo(array('logicalId' => $_update->getLogicalId(), 'type' => $_update->getType()), $_update->getConfiguration('version', 'stable'));
		$_update->setStatus($market_info['status']);
		$_update->setConfiguration('market_owner', $market_info['market_owner']);
		$_update->setConfiguration('market', $market_info['market']);
		$_update->setRemoteVersion($market_info['datetime']);
		$_update->save();
	}

	public static function doUpdate($_update) {
		$market = market::byLogicalIdAndType($_update->getLogicalId(), $_update->getType());
		if (is_object($market)) {
			$market->install($_update->getConfiguration('version', 'stable'));
		}
		return array('localVersion' => $market->getDatetime($_update->getConfiguration('version', 'stable')));
	}

	public static function deleteObjet($_update) {
		try {
			$market = market::byLogicalIdAndType($_update->getLogicalId(), $_update->getType());
		} catch (Exception $e) {
			$market = new market();
			$market->setLogicalId($_update->getLogicalId());
			$market->setType($_update->getType());
		} catch (Error $e) {
			$market = new market();
			$market->setLogicalId($_update->getLogicalId());
			$market->setType($_update->getType());
		}
		try {
			if (is_object($market)) {
				$market->remove();
			}
		} catch (Exception $e) {

		} catch (Error $e) {

		}
	}

	public static function objectInfo($_update) {
		return array(
			'doc' => 'https://jeedom.com/doc/documentation/plugins/' . $_update->getLogicalId() . '/' . config::byKey('language', 'core', 'fr_FR') . '/' . $_update->getLogicalId() . '.html',
			'changelog' => '',
		);
	}

	public static function sendBackup($_path) {
		$market = self::getJsonRpc();
		$file = array(
			'file' => '@' . realpath($_path),
		);
		if (!$market->sendRequest('backup::upload', array(), 3600, $file)) {
			throw new Exception($market->getError());
		}
	}

	public static function listeBackup() {
		$market = self::getJsonRpc();
		if (!$market->sendRequest('backup::liste', array())) {
			throw new Exception($market->getError());
		}
		return $market->getResult();
	}

	public static function retoreBackup($_backup) {
		$url = config::byKey('market::address') . "/core/php/downloadBackup.php?backup=" . $_backup . '&hwkey=' . jeedom::getHardwareKey() . '&username=' . urlencode(config::byKey('market::username')) . '&password=' . config::byKey('market::password') . '&password_type=sha1';
		$tmp_dir = dirname(__FILE__) . '/../../tmp';
		$tmp = $tmp_dir . '/' . $_backup;
		file_put_contents($tmp, fopen($url, 'r'));
		if (!file_exists($tmp)) {
			throw new Exception(__('Impossible de télécharger la sauvegarde : ', __FILE__) . $url . '.');
		}
		if (!file_exists(dirname(__FILE__) . '/../../backup/')) {
			mkdir(dirname(__FILE__) . '/../../backup/');
		}
		$backup_path = dirname(__FILE__) . '/../../backup/' . $_backup;
		if (!copy($tmp, $backup_path)) {
			throw new Exception(__('Impossible de copier le fichier de  : ', __FILE__) . $tmp . '.');
		}
		if (!file_exists($backup_path)) {
			throw new Exception(__('Impossible de trouver le fichier : ', __FILE__) . $backup_path . '.');
		}
		jeedom::restore('backup/' . $_backup, true);
	}

	public static function getJsonRpc() {
		if (config::byKey('market::address') == '') {
			throw new Exception(__('Aucune adresse n\'est renseignée pour le market', __FILE__));
		}
		$internalIp = '';
		try {
			$internalIp = network::getNetworkAccess('internal', 'ip');
		} catch (Exception $e) {

		}
		if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
			$params = array(
				'username' => config::byKey('market::username'),
				'password' => config::byKey('market::password'),
				'password_type' => 'sha1',
				'jeedomversion' => jeedom::version(),
				'hwkey' => jeedom::getHardwareKey(),
				'addrComplement' => config::byKey('externalComplement'),
				'information' => array(
					'nbMessage' => message::nbMessage(),
					'jeeNetwork::mode' => config::byKey('jeeNetwork::mode'),
					'hardware' => (method_exists('jeedom', 'getHardwareName')) ? jeedom::getHardwareName() : '',
				),
				'localIp' => $internalIp,
				'jeedom_name' => config::byKey('name'),
				'plugin_install_list' => plugin::listPlugin(true, false, false, true),
			);
			if (config::byKey('market::allowDNS') != 1) {
				$params['addr'] = config::byKey('externalAddr');
				$params['addrProtocol'] = config::byKey('externalProtocol');
				$params['addrPort'] = config::byKey('externalPort');
			}
			$jsonrpc = new jsonrpcClient(config::byKey('market::address') . '/core/api/api.php', '', $params);
		} else {
			$jsonrpc = new jsonrpcClient(config::byKey('market::address') . '/core/api/api.php', '', array(
				'jeedomversion' => jeedom::version(),
				'hwkey' => jeedom::getHardwareKey(),
				'localIp' => $internalIp,
				'jeedom_name' => config::byKey('name'),
				'plugin_install_list' => plugin::listPlugin(true, false, false, true),
			));
		}
		$jsonrpc->setCb_class('market');
		$jsonrpc->setCb_function('postJsonRpc');
		$jsonrpc->setNoSslCheck(true);
		return $jsonrpc;
	}

	public static function postJsonRpc(&$_result) {
		if (is_array($_result)) {
			$restart_dns = false;
			if (isset($_result['register::dnsToken']) && config::byKey('dns::token') != $_result['register::dnsToken']) {
				config::save('dns::token', $_result['register::dnsToken']);
				$restart_dns = true;
			}
			if (isset($_result['register::dnsNumber']) && config::byKey('dns::number') != $_result['register::dnsNumber']) {
				config::save('dns::number', $_result['register::dnsNumber']);
				$restart_dns = true;
			}
			if ($restart_dns && config::byKey('market::allowDNS') == 1) {
				network::dns_start();
			}
			if (config::byKey('market::allowDNS') == 1) {
				if (isset($_result['jeedom::url']) && config::byKey('jeedom::url') != $_result['jeedom::url']) {
					config::save('jeedom::url', $_result['jeedom::url']);
				}
			}
			if (isset($_result['market::allowBeta']) && config::byKey('market::allowBeta') != $_result['market::allowBeta']) {
				config::save('market::allowBeta', $_result['market::allowBeta']);
			}
			if (isset($_result['register::ngrokAddr'])) {
				unset($_result['register::ngrokAddr']);
			}
			if (isset($_result['register::ngrokPort'])) {
				unset($_result['register::ngrokPort']);
			}
			if (isset($_result['register::ngrokToken'])) {
				unset($_result['register::ngrokToken']);
			}
			if (isset($_result['register::dnsNumber'])) {
				unset($_result['register::dnsNumber']);
			}
			if (isset($_result['register::dnsToken'])) {
				unset($_result['register::dnsToken']);
			}
			if (isset($_result['jeedom::url'])) {
				unset($_result['jeedom::url']);
			}
			if (isset($_result['market::allowBeta'])) {
				unset($_result['market::allowBeta']);
			}
			if (isset($_result['register::dnsToken'])) {
				unset($_result['register::dnsToken']);
			}
			if (isset($_result['register::dnsNumber'])) {
				unset($_result['register::dnsNumber']);
			}
			if (isset($_result['register::hwkey_nok']) && $_result['register::hwkey_nok'] == 1) {
				config::save('jeedom::installKey', '');
			}
		}
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}