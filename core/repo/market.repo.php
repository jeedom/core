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

class repo_market {
	/*     * *************************Attributs****************************** */

	public static $_name = 'Market';

	public static $_scope = array(
		'plugin' => true,
		'backup' => true,
		'hasConfiguration' => true,
		'proxy' => true,
		'hasStore' => true,
		'test' => true,
		'pullInstall' => true,
	);

	private $id;
	private $name;
	private $type;
	private $datetime;
	private $description;
	private $categorie;
	private $changelog;
	private $doc;
	private $version;
	private $user_id;
	private $downloaded;
	private $status;
	private $author;
	private $logicalId;
	private $rating;
	private $utilization;
	private $isAuthor;
	private $img;
	private $buyer;
	private $purchase = 0;
	private $cost = 0;
	private $realcost = 0;
	private $link;
	private $certification;
	private $language;
	private $private;
	private $updateBy;
	private $parameters;
	private $hardwareCompatibility;
	private $nbInstall;
	private $allowVersion = array();

	/*     * ***********************Méthodes statiques*************************** */

	public static function getConfigurationOption() {
		return array(
			'configuration' => array(
				'address' => array(
					'name' => __('Adresse', __FILE__),
					'type' => 'input',
				),
				'username' => array(
					'name' => __('Nom d\'utilisateur', __FILE__),
					'type' => 'input',
				),
				'password' => array(
					'name' => __('Mot de passe', __FILE__),
					'type' => 'password',
				),
				'no_ssl_verify' => array(
					'name' => __('Pas de validation SSL (non recommandé)', __FILE__),
					'type' => 'checkbox',
				),
				'cloud::backup::name' => array(
					'name' => __('[Backup cloud] Nom', __FILE__),
					'type' => 'input',
				),
				'cloud::backup::password' => array(
					'name' => __('[Backup cloud] Mot de passe', __FILE__),
					'type' => 'password',
				),
				'cloud::backup::password_confirmation' => array(
					'name' => __('[Backup cloud] Mot de passe (confirmation)', __FILE__),
					'type' => 'password',
				),
				'cloud::monitoring::disable' => array(
					'name' => __('[Monitoring cloud] Désactiver', __FILE__),
					'type' => 'checkbox',
				)
			),
			'parameters_for_add' => array(
				'version' => array(
					'name' => __('Version : beta, stable', __FILE__),
					'type' => 'input',
				),
			),
		);
	}

	public static function pullInstall() {
		$market = self::getJsonRpc();
		if (!$market->sendRequest('register::pluginToInstall')) {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
		$results = $market->getResult();
		if (!is_array($results) || count($results) == 0 || !is_array($results['plugins']) || count($results['plugins']) == 0) {
			return array('number' => 0);
		}
		$nbInstall = 0;
		$lastInstallDate = config::byKey('market::lastDatetimePluginInstall', 'core', 0);
		foreach ($results['plugins'] as &$plugin) {
			if ($plugin['datetime'] < $lastInstallDate) {
				continue;
			}
			$plugin['version'] = isset($plugin['version']) ? $plugin['version'] : 'stable';
			try {
				$repo = self::byId($plugin['id']);
				if (!is_object($repo)) {
					continue;
				}
				log::add('market', 'debug', __('Lancement de l\'installation de', __FILE__) . ' ' . $repo->getLogicalId() . ' ' . __('en version', __FILE__) . ' ' . $plugin['version']);
				$update = update::byTypeAndLogicalId($repo->getType(), $repo->getLogicalId());
				if (!is_object($update)) {
					$update = new update();
				}
				$update->setSource('market');
				$update->setLogicalId($repo->getLogicalId());
				$update->setType($repo->getType());
				$update->setLocalVersion($repo->getDatetime($plugin['version']));
				$update->setConfiguration('version', $plugin['version']);
				$update->save();
				$update->doUpdate();
				$nbInstall++;
			} catch (\Exception $e) {
			}
		}
		config::save('market::lastDatetimePluginInstall', $results['datetime']);
		return array('number' => $nbInstall);
	}

	public static function checkUpdate(&$_update) {
		if (is_array($_update)) {
			if (count($_update) < 1) {
				return;
			}
			$markets = array('logicalId' => array(), 'version' => array());
			$marketObject = array();
			foreach ($_update as $update) {
				$markets['logicalId'][] = array('logicalId' => $update->getLogicalId(), 'type' => $update->getType());
				$markets['version'][] = $update->getConfiguration('version', 'stable');
				$marketObject[$update->getType() . $update->getLogicalId()] = $update;
			}
			$markets_infos = repo_market::getInfo($markets['logicalId'], $markets['version']);
			foreach ($markets_infos as $logicalId => $market_info) {
				$update = $marketObject[$logicalId];
				if (is_object($update)) {
					$update->setStatus($market_info['status']);
					$update->setConfiguration('market', $market_info['market']);
					$update->setRemoteVersion($market_info['datetime']);
					if ($update->getConfiguration('version') == '') {
						$update->setConfiguration('version', 'stable');
					}
					$update->save();
				}
			}
			return;
		}
		$market_info = repo_market::getInfo(array('logicalId' => $_update->getLogicalId(), 'type' => $_update->getType()), $_update->getConfiguration('version', 'stable'));
		$_update->setStatus($market_info['status']);
		$_update->setConfiguration('market', $market_info['market']);
		$_update->setRemoteVersion($market_info['datetime']);
		$_update->save();
	}

	public static function downloadObject($_update) {
		$market = repo_market::byLogicalIdAndType($_update->getLogicalId(), $_update->getType());
		if (is_object($market)) {
			$file = $market->install($_update->getConfiguration('version', 'stable'));
		} else {
			throw new Exception(__('Objet introuvable sur le market :', __FILE__) . ' ' . $_update->getLogicalId() . '/' . $_update->getType());
		}
		return array('path' => $file, 'localVersion' => $market->getDatetime($_update->getConfiguration('version', 'stable')));
	}

	public static function deleteObjet($_update) {
		try {
			$market = repo_market::byLogicalIdAndType($_update->getLogicalId(), $_update->getType());
		} catch (Exception $e) {
			$market = new repo_market();
			$market->setLogicalId($_update->getLogicalId());
			$market->setType($_update->getType());
		} catch (Error $e) {
			$market = new repo_market();
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
			'doc' => 'https://doc.jeedom.com',
			'changelog' => 'https://doc.jeedom.com',
			'display' => 'https://market.jeedom.com/index.php?v=d&p=market&type=plugin&plugin_id=' . $_update->getLogicalId(),
		);
	}

	/*     * ***********************BACKUP*************************** */

	public static function backup_flysystem() {
		if (config::byKey('market::cloud::backup::password') != config::byKey('market::cloud::backup::password_confirmation')) {
			throw new Exception(__('Le mot de passe du backup cloud n\'est pas identique à la confirmation', __FILE__));
		}
		$client = new Sabre\DAV\Client(array(
			'baseUri' => config::byKey('service::backup::url'),
			'userName' => config::byKey('market::username'),
			'password' => config::byKey('market::password'),
			'authType' => Sabre\DAV\Client::AUTH_BASIC,
		));
		$adapter = new League\Flysystem\WebDAV\WebDAVAdapter($client);
		return new League\Flysystem\Filesystem($adapter);
	}

	public static function backup_createFolderIsNotExist() {
		$filesystem = self::backup_flysystem();
		$folders = $filesystem->getAdapter()->listContents('/webdav/' . config::byKey('market::username'));
		$found = false;
		if (count($folders) > 0) {
			foreach ($folders as $folder) {
				if (basename($folder['path']) == config::byKey('market::cloud::backup::name')) {
					$found = true;
					break;
				}
			}
		}
		if (!$found) {
			$filesystem->createDir('/webdav/' . config::byKey('market::username') . '/' . rawurldecode(config::byKey('market::cloud::backup::name')));
		}
	}

	public static function backup_send($_path) {
		if (!config::byKey('service::backup::enable')) {
			throw new Exception(__('Aucun serveur de backup defini. Avez-vous bien un abonnement au backup cloud ?', __FILE__));
		}
		if (config::byKey('market::cloud::backup::password') == '') {
			throw new Exception(__('Vous devez obligatoirement avoir un mot de passe pour le backup cloud (allez dans Réglages -> Système -> Configuration puis onglet Mise à jour/Market)', __FILE__));
		}
		if (config::byKey('market::cloud::backup::password') != config::byKey('market::cloud::backup::password_confirmation')) {
			throw new Exception(__('Le mot de passe du backup cloud n\'est pas identique à la confirmation', __FILE__));
		}
		self::backup_clean($_path);
		self::backup_createFolderIsNotExist();
		try {
			if (!file_exists('/tmp/jeedom_gnupg')) {
				mkdir('/tmp/jeedom_gnupg');
			}
			com_shell::execute('sudo chmod 777 -R /tmp/jeedom_gnupg');
			$cmd = 'echo "' . config::byKey('market::cloud::backup::password') . '" | gpg --homedir /tmp/jeedom_gnupg --batch --yes --passphrase-fd 0 -c ' . $_path;
			com_shell::execute($cmd);
			$filesystem = self::backup_flysystem();
			$stream = fopen($_path . '.gpg', 'r+');
			$response = $filesystem->writeStream('/webdav/' . config::byKey('market::username') . '/' . rawurldecode(config::byKey('market::cloud::backup::name')) . '/' . basename($_path) . '.gpg', $stream);
			unlink($_path . '.gpg');
			rrmdir('/tmp/jeedom_gnupg');
			if (!$response) {
				throw new \Exception(__('Impossible d\'envoyer le backup au cloud. Le soucis est surement du à un backup trop gros ou à un temps de transfert trop long', __FILE__));
			}
		} catch (\Exception $e) {
			unlink($_path . '.gpg');
			rrmdir('/tmp/jeedom_gnupg');
			throw $e;
		}
	}

	public static function backup_clean($_path) {
		if (!config::byKey('service::backup::enable') || config::byKey('market::cloud::backup::password') == '') {
			return;
		}
		if (config::byKey('market::cloud::backup::password') != config::byKey('market::cloud::backup::password_confirmation')) {
			throw new Exception(__('Le mot de passe du backup cloud n\'est pas identique à la confirmation', __FILE__));
		}
		$limit = 3900;
		self::backup_createFolderIsNotExist();
		$filesystem = self::backup_flysystem();
		$folders = $filesystem->getAdapter()->listContents('/webdav/' . config::byKey('market::username'));
		$files = array();
		foreach ($folders as $folder) {
			$files += $filesystem->getAdapter()->listContents('/webdav/' . config::byKey('market::username') . '/' . basename($folder['path']) . '/');
		}
		$total_size = 0;
		foreach ($files as $file) {
			if ($file['type'] == 'dir') {
				continue;
			}
			$total_size += $file['size'];
		}
		if (($total_size / 1024 / 1024) < $limit - (filesize($_path) / 1024 / 1024)) {
			return;
		}
		echo __('Besoin de faire de la place sur le stockage distant', __FILE__) . "\n";
		usort($files, function ($a, $b) {
			return $a["timestamp"] - $b["timestamp"];
		});
		$nb = 0;
		while (($total_size / 1024 / 1024) > $limit - (filesize($_path) / 1024 / 1024)) {
			if (count($files) == 0) {
				throw new \Exception(__('Pas assez de place et aucun backup à supprimer', __FILE__));
			}
			$file = array_shift($files);
			$filename = basename($file['path']);
			$path = basename(str_replace($filename, '', $file['path'])) . '/' . $filename;
			echo __('Supression du backup cloud :', __FILE__) . ' ' . $path . "\n";
			$filesystem->delete('/webdav/' . config::byKey('market::username') . '/' . $path);
			$total_size -= $file['size'];
			$nb++;
			if ($nb > 10) {
				throw new \Exception(__('Erreur lors du nettoyage des backups cloud, supression > 10', __FILE__));
			}
		}
	}


	public static function backup_list() {
		if (!config::byKey('service::backup::enable') || config::byKey('market::cloud::backup::password') == '') {
			return array();
		}
		if (config::byKey('market::cloud::backup::password') != config::byKey('market::cloud::backup::password_confirmation')) {
			throw new Exception(__('Le mot de passe du backup cloud n\'est pas identique à la confirmation', __FILE__));
		}
		self::backup_createFolderIsNotExist();
		$filesystem = self::backup_flysystem();
		$folders = $filesystem->getAdapter()->listContents('/webdav/' . config::byKey('market::username') . '/' . rawurldecode(config::byKey('market::cloud::backup::name')));
		$result = array();
		foreach ($folders as $folder) {
			$result[] = basename($folder['path']);
		}
		return array_reverse($result);
	}

	public static function backup_restore($_backup) {
		if (config::byKey('market::cloud::backup::password') != config::byKey('market::cloud::backup::password_confirmation')) {
			throw new Exception(__('Le mot de passe du backup cloud n\'est pas identique à la confirmation', __FILE__));
		}
		$backup_dir = calculPath(config::byKey('backup::path'));
		if (!file_exists($backup_dir)) {
			mkdir($backup_dir, 0770, true);
		}
		if (!is_writable($backup_dir)) {
			throw new Exception('Impossible d\'accéder au dossier de sauvegarde. Veuillez vérifier les droits : ' . $backup_dir);
		}
		$path = $backup_dir . '/' . $_backup;
		if (file_exists($path)) {
			unlink($path);
		}
		if (!file_exists('/tmp/jeedom_gnupg')) {
			mkdir('/tmp/jeedom_gnupg');
		}
		com_shell::execute('sudo chmod 777 -R /tmp/jeedom_gnupg');
		$cmd = 'cd ' . $backup_dir . ';wget "https://' . rawurlencode(config::byKey('market::username')) . ':' . rawurlencode(config::byKey('market::password')) . '@' . str_replace('https://', '', config::byKey('service::backup::url')) . '/webdav/' . rawurlencode(config::byKey('market::username')) . '/' . rawurlencode(config::byKey('market::cloud::backup::name')) . '/' . $_backup . '"';
		com_shell::execute($cmd);
		$cmd = 'echo "' . config::byKey('market::cloud::backup::password') . '" | gpg --homedir /tmp/jeedom_gnupg --batch --yes --passphrase-fd 0 --output ' . $backup_dir . '/cloud-' . str_replace('.gpg', '', $_backup) . ' -d ' . $backup_dir . '/' . $_backup;
		com_shell::execute($cmd);
		unlink($backup_dir . '/' . $_backup);
		rrmdir('/tmp/jeedom_gnupg');
	}

	/*     * ***********************CRON*************************** */

	public static function cronHourly() {
		if (strtotime(config::byKey('market::lastCommunication', 'core', 0)) > (strtotime('now') - (24 * 3600))) {
			return;
		}
		sleep(rand(0, 1800));
		try {
			self::test();
		} catch (Exception $e) {
		}
	}

	public static function cron5() {
		try {
			if (config::byKey('service::monitoring::enable') && config::byKey('cloud::monitoring::disable', 0) == 0) {
				sleep(rand(1, 60));
				$data = array(
					'health' => jeedom::health(),
					'name' => config::byKey('name'),
					'hwkey' => jeedom::getHardwareKey(),
					'language' => config::byKey('language')
				);
				$url = config::byKey('service::monitoring::url') . '/service/monitoring';
				$request_http = new com_http($url);
				$request_http->setHeader(array(
					'Content-Type: application/json',
					'Autorization: ' . sha512(mb_strtolower(config::byKey('market::username')) . ':' . config::byKey('market::password'))
				));
				$request_http->setPost(json_encode($data));
				try {
					$result = json_decode($request_http->exec(60, 1), true);
					if ($result['state'] != 'ok') {
						log::add('monitoring_cloud', 'debug', __('Erreur sur le monitoring cloud :', __FILE__) . ' ' . json_encode($result));
					}
				} catch (\Exception $e) {
					log::add('monitoring_cloud', 'debug', __('Erreur sur le monitoring cloud :', __FILE__) . ' ' . $e->getMessage());
				}
			}
		} catch (Exception $e) {
		}
	}

	/*     * ***********************INFO*************************** */

	public static function getInfo($_logicalId, $_version = 'stable') {
		$returns = array();
		if (is_array($_logicalId) && is_array($_version) && count($_logicalId) == count($_version)) {
			if (is_array(reset($_logicalId))) {
				$markets = self::byLogicalIdAndType($_logicalId);
			} else {
				$markets = self::byLogicalId($_logicalId);
			}

			$returns = array();
			$countLogicalId = count($_logicalId);
			for ($i = 0; $i < $countLogicalId; $i++) {
				if (is_array($_logicalId[$i])) {
					$logicalId = $_logicalId[$i]['type'] . $_logicalId[$i]['logicalId'];
				} else {
					$logicalId = $_logicalId[$i];
				}
				$return['owner'] = array();
				$return['datetime'] = '0000-01-01 00:00:00';
				if ($logicalId == '' || config::byKey('market::address') == '') {
					$return['owner']['market'] = 0;
					$return['status'] = 'ok';
					return $return;
				}

				if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
					$return['owner']['market'] = 1;
				} else {
					$return['owner']['market'] = 0;
				}
				$return['market'] = 0;

				try {
					if (isset($markets[$logicalId])) {
						$market = $markets[$logicalId];
						if (!is_object($market)) {
							$return['status'] = 'ok';
						} else {
							$return['datetime'] = $market->getDatetime($_version[$i]);
							$return['market'] = 1;
							$return['owner']['market'] = $market->getIsAuthor();
							$update = update::byTypeAndLogicalId($market->getType(), $market->getLogicalId());
							$updateDateTime = '0000-01-01 00:00:00';
							if (is_object($update)) {
								$updateDateTime = $update->getLocalVersion();
							}
							if ($updateDateTime < $market->getDatetime($_version[$i], $updateDateTime)) {
								$return['status'] = 'update';
							} else {
								$return['status'] = 'ok';
							}
						}
					} else {
						$return['status'] = 'ok';
					}
				} catch (Exception $e) {
					log::add('market', 'debug', __('Erreur repo_market::getinfo :', __FILE__) . ' ' . $e->getMessage());
					$return['status'] = 'ok';
				} catch (Error $e) {
					log::add('market', 'debug', __('Erreur repo_market::getinfo :', __FILE__) . ' ' . $e->getMessage());
					$return['status'] = 'ok';
				}
				$returns[$logicalId] = $return;
			}
			return $returns;
		}
		$return = array();
		$return['datetime'] = '0000-01-01 00:00:00';
		$return['owner'] = array();
		if (config::byKey('market::address') == '') {
			$return['market'] = 0;
			$return['owner']['market'] = 0;
			$return['status'] = 'ok';
			return $return;
		}

		if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
			$return['owner']['market'] = 1;
		} else {
			$return['owner']['market'] = 0;
		}
		$return['market'] = 0;

		try {
			if (is_array($_logicalId)) {
				$market = repo_market::byLogicalIdAndType($_logicalId['logicalId'], $_logicalId['type']);
			} else {
				$market = repo_market::byLogicalId($_logicalId);
			}
			if (!is_object($market)) {
				$return['status'] = 'depreciated';
			} else {
				$return['datetime'] = $market->getDatetime($_version);
				$return['market'] = 1;
				$return['owner']['market'] = $market->getIsAuthor();
				$update = update::byTypeAndLogicalId($market->getType(), $market->getLogicalId());
				$updateDateTime = '0000-01-01 00:00:00';
				if (is_object($update)) {
					$updateDateTime = $update->getLocalVersion();
				}
				if ($updateDateTime < $market->getDatetime($_version, $updateDateTime)) {
					$return['status'] = 'update';
				} else {
					$return['status'] = 'ok';
				}
			}
		} catch (Exception $e) {
			log::add('market', 'debug', __('Erreur repo_market::getinfo :', __FILE__) . ' ' . $e->getMessage());
			$return['status'] = 'ok';
		} catch (Error $e) {
			log::add('market', 'debug', __('Erreur repo_market::getinfo :', __FILE__) . ' ' . $e->getMessage());
			$return['status'] = 'ok';
		}
		return $return;
	}

	/*     * ***********************UTILS*************************** */

	public static function saveTicket($_ticket) {
		$jsonrpc = self::getJsonRpc();
		$_ticket['user_plugin'] = '';
		foreach (plugin::listPlugin() as $plugin) {
			$_ticket['user_plugin'] .= $plugin->getId();
			$update = $plugin->getUpdate();
			if (is_object($update)) {
				$_ticket['user_plugin'] .= '[' . $update->getConfiguration('version', 'stable') . ',' . $update->getSource() . ',' . $update->getLocalVersion() . ']';
			}
			$_ticket['user_plugin'] .= ',';
		}
		trim($_ticket['user_plugin'], ',');
		if (isset($_ticket['options']['page'])) {
			$_ticket['options']['page'] = substr($_ticket['options']['page'], strpos($_ticket['options']['page'], 'index.php'));
		}
		$_ticket['options']['jeedom_version'] = jeedom::version();
		$_ticket['options']['uname'] = shell_exec('uname -a');
		if (!$jsonrpc->sendRequest('ticket::save', array('ticket' => $_ticket), 300)) {
			throw new Exception($jsonrpc->getErrorMessage());
		}
		if ($_ticket['openSupport'] == 1) {
			user::supportAccess(true);
		}
		return $jsonrpc->getResult();
	}

	public static function supportAccess($_enable = true, $_key = '') {
		$jsonrpc = self::getJsonRpc();
		$url = network::getNetworkAccess('external') . '/index.php?auth=' . $_key;
		if (!$jsonrpc->sendRequest('register::supportAccess', array('enable' => $_enable, 'urlSupport' => $url))) {
			throw new Exception($jsonrpc->getErrorMessage());
		}
	}

	public static function getPassword() {
		$password = config::byKey('market::password');
		if (!is_sha1($password)) {
			return sha1($password);
		}
		return $password;
	}

	public static function test() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::test')) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function getPurchaseInfo() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('purchase::getInfo')) {
			return $market->getResult();
		}
	}

	public static function distinctCategorie($_type) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::distinctCategorie', array('type' => $_type))) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function getJsonRpc() {
		$internalIp = '';
		try {
			$internalIp = network::getNetworkAccess('internal', 'ip');
		} catch (Exception $e) {
		}
		$uname = shell_exec('uname -a');
		$params = array(
			'username' => config::byKey('market::username'),
			'password' => self::getPassword(),
			'password_type' => 'sha1',
			'jeedomversion' => jeedom::version(),
			'hwkey' => jeedom::getHardwareKey(),
			'information' => array(
				'nbMessage' => message::nbMessage(),
				'nbUpdate' => update::nbNeedUpdate(),
				'hardware' => (method_exists('jeedom', 'getHardwareName')) ? jeedom::getHardwareName() : '',
				'uname' => $uname,
				'language' => config::byKey('language'),
			),
			'market_api_key' => jeedom::getApiKey('apimarket'),
			'localIp' => $internalIp,
			'jeedom_name' => config::byKey('name'),
			'plugin_install_list' => plugin::listPlugin(false, false, false, true),
		);
		if (config::byKey('market::allowDNS') != 1 || config::byKey('network::disableMangement') == 1) {
			$params['url'] = network::getNetworkAccess('external');
		}
		$jsonrpc = new jsonrpcClient(config::byKey('market::address') . '/core/api/api.php', '', $params);
		$jsonrpc->setCb_class('repo_market');
		$jsonrpc->setCb_function('postJsonRpc');
		if (config::byKey('market::no_ssl_verify') == 1) {
			$jsonrpc->setNoSslCheck(true);
		}
		return $jsonrpc;
	}

	public static function postJsonRpc(&$_result) {
		config::save('market::lastCommunication', date('Y-m-d H:i:s'));
		if (is_array($_result)) {
			$restart_dns = false;
			$restart_monitoring = false;
			if (isset($_result['register::dnsToken']) && config::byKey('dns::token') != $_result['register::dnsToken']) {
				config::save('dns::token', $_result['register::dnsToken']);
				$restart_dns = true;
			}
			if (isset($_result['register::dnsNumber']) && config::byKey('dns::number') != $_result['register::dnsNumber']) {
				config::save('dns::number', $_result['register::dnsNumber']);
				$restart_dns = true;
			}
			if (isset($_result['register::vpnurl']) && config::byKey('dns::vpnurl') != $_result['register::vpnurl']) {
				config::save('dns::vpnurl', $_result['register::vpnurl']);
				$restart_dns = true;
			}
			if (isset($_result['register::vpnPort']) && config::byKey('vpn::port') != $_result['register::vpnPort']) {
				config::save('vpn::port', $_result['register::vpnPort']);
				$restart_dns = true;
			}
			if (isset($_result['dns::remote']) && config::byKey('dns::remote') != $_result['dns::remote']) {
				config::save('dns::remote', $_result['dns::remote']);
				$restart_dns = true;
			}
			if (isset($_result['service::monitoring::enable']) && config::byKey('service::monitoring::enable') != $_result['service::monitoring::enable']) {
				config::save('service::monitoring::enable', $_result['service::monitoring::enable']);
			}
			if (isset($_result['service::backup::enable']) && config::byKey('service::backup::enable') != $_result['service::backup::enable']) {
				config::save('service::backup::enable', $_result['service::backup::enable']);
			}
			if (isset($_result['register::id']) && config::byKey('register::id') != $_result['register::id']) {
				config::save('register::id', $_result['register::id']);
			}
			if (isset($_result['username']) && config::byKey('market::username') != $_result['username']) {
				config::save('market::username', $_result['username']);
			}
			if ($restart_dns && config::byKey('market::allowDNS') == 1) {
				network::dns_start();
			}
			if (config::byKey('market::allowDNS') == 1 && isset($_result['jeedom::url']) && config::byKey('jeedom::url') != $_result['jeedom::url']) {
				config::save('jeedom::url', $_result['jeedom::url']);
			}
			if (isset($_result['register::hwkey_nok']) && $_result['register::hwkey_nok'] == 1) {
				config::save('jeedom::installKey', '');
			}
			if (isset($_result['broadcast::id']) && isset($_result['broadcast::message']) && $_result['broadcast::id'] != '' && $_result['broadcast::message'] != '' && $_result['broadcast::id'] != config::byKey('market::boradcast::id')) {
				config::save('market::boradcast::id', $_result['broadcast::id']);
				message::add('Jeedom SAS', $_result['broadcast::message']);
			}
		}
	}
	/**
	 *
	 * @param array $_arrayMarket
	 * @return \self
	 */
	public static function construct(array $_arrayMarket) {
		$market = new self();
		if (!isset($_arrayMarket['id'])) {
			return;
		}
		$market->setId($_arrayMarket['id'])
			->setName($_arrayMarket['name'])
			->setType($_arrayMarket['type']);
		$market->datetime = json_encode($_arrayMarket['datetime'], JSON_UNESCAPED_UNICODE);
		$market->setDescription($_arrayMarket['description'])
			->setDownloaded($_arrayMarket['downloaded'])
			->setUser_id($_arrayMarket['user_id'])
			->setVersion($_arrayMarket['version'])
			->setCategorie($_arrayMarket['categorie']);
		$market->status = json_encode($_arrayMarket['status'], JSON_UNESCAPED_UNICODE);
		$market->setAuthor($_arrayMarket['author']);
		if (isset($_arrayMarket['changelog'])) {
			$market->setChangelog($_arrayMarket['changelog']);
		}
		if (isset($_arrayMarket['doc'])) {
			$market->setDoc($_arrayMarket['doc']);
		}
		$market->setLogicalId($_arrayMarket['logicalId']);
		if (isset($_arrayMarket['utilization'])) {
			$market->setUtilization($_arrayMarket['utilization']);
		}
		if (isset($_arrayMarket['certification'])) {
			$market->setCertification($_arrayMarket['certification']);
		}
		if (isset($_arrayMarket['allowVersion'])) {
			$market->setAllowVersion($_arrayMarket['allowVersion']);
		}
		if (isset($_arrayMarket['nbInstall'])) {
			$market->setNbInstall($_arrayMarket['nbInstall']);
		}
		$market->setPurchase($_arrayMarket['purchase'])
			->setCost($_arrayMarket['cost']);
		$market->rating = ($_arrayMarket['rating']);
		$market->setBuyer($_arrayMarket['buyer'])
			->setUpdateBy($_arrayMarket['updateBy'])
			->setPrivate($_arrayMarket['private']);
		$market->img = json_encode($_arrayMarket['img'], JSON_UNESCAPED_UNICODE);
		$market->link = json_encode($_arrayMarket['link'], JSON_UNESCAPED_UNICODE);
		$market->language = json_encode($_arrayMarket['language'], JSON_UNESCAPED_UNICODE);
		if (isset($_arrayMarket['hardwareCompatibility'])) {
			$market->hardwareCompatibility = json_encode($_arrayMarket['hardwareCompatibility'], JSON_UNESCAPED_UNICODE);
		}

		$market->setRealcost($_arrayMarket['realCost']);
		if (!isset($_arrayMarket['isAuthor'])) {
			$_arrayMarket['isAuthor'] = true;
		}
		$market->setIsAuthor($_arrayMarket['isAuthor']);

		if (isset($_arrayMarket['parameters']) && is_array($_arrayMarket['parameters'])) {
			foreach ($_arrayMarket['parameters'] as $key => $value) {
				$market->setParameters($key, $value);
			}
		}
		return $market;
	}

	public static function byId($_id) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byId', array('id' => $_id))) {
			return self::construct($market->getResult());
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function byLogicalId($_logicalId) {
		$market = self::getJsonRpc();

		if (is_array($_logicalId)) {
			$options = $_logicalId;
			$timeout = 240;
		} else {
			$options = array('logicalId' => $_logicalId);
			$timeout = 10;
		}
		if ($market->sendRequest('market::byLogicalId', $options, $timeout, null, 1)) {
			if (is_array($_logicalId)) {
				$return = array();
				foreach ($market->getResult() as $logicalId => $result) {
					if (isset($result['id'])) {
						$return[$logicalId] = self::construct($result);
					}
				}
				return $return;
			}
			return self::construct($market->getResult());
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function byLogicalIdAndType($_logicalId, $_type = '') {
		$market = self::getJsonRpc();
		if (is_array($_logicalId)) {
			$options = $_logicalId;
			$timeout = 240;
		} else {
			$options = array('logicalId' => $_logicalId, 'type' => $_type);
			$timeout = 10;
		}
		if ($market->sendRequest('market::byLogicalIdAndType', $options, $timeout, null, 1)) {
			if (is_array($_logicalId)) {
				$return = array();
				foreach ($market->getResult() as $logicalId => $result) {
					if (isset($result['id'])) {
						$return[$logicalId] = self::construct($result);
					}
				}
				return $return;
			}
			return self::construct($market->getResult());
		} else {
			log::add('market', 'debug', print_r($market, true));
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function byMe() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byAuthor', array())) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function byStatusAndType($_status, $_type) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byStatusAndType', array('status' => $_status, 'type' => $_type))) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			log::add('market', 'debug', print_r($market, true));
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function byStatus($_status) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byStatus', array('status' => $_status))) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function byFilter($_filter) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byFilter', $_filter)) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	/*     * *********************Methode d'instance************************* */

	public function setRating($_rating) {
		$market = self::getJsonRpc();
		if (!$market->sendRequest('market::setRating', array('rating' => $_rating, 'id' => $this->getId()))) {
			throw new Exception($market->getError());
		}
	}

	public function getRating($_key = 'average') {
		$rating = $this->rating;
		if (isset($rating[$_key])) {
			return $rating[$_key];
		}
		return 0;
	}

	public function install($_version = 'stable') {
		$tmp_dir = jeedom::getTmpFolder('market');
		$tmp = $tmp_dir . '/' . $this->getLogicalId() . '.zip';
		if (file_exists($tmp)) {
			unlink($tmp);
		}
		if (!is_writable($tmp_dir)) {
			exec(system::getCmdSudo() . 'chmod 777 -R ' . $tmp);
		}
		if (!is_writable($tmp_dir)) {
			throw new Exception(__('Impossible d\'écrire dans le répertoire :', __FILE__) . ' ' . $tmp . __('. Exécuter la commande suivante en SSH : sudo chmod 777 -R', __FILE__) . ' ' . $tmp_dir);
		}

		$url = config::byKey('market::address') . "/core/php/downloadFile.php?id=" . $this->getId() . '&version=' . $_version . '&jeedomversion=' . jeedom::version() . '&hwkey=' . jeedom::getHardwareKey() . '&username=' . urlencode(config::byKey('market::username')) . '&password=' . self::getPassword() . '&password_type=sha1';
		log::add('update', 'alert', __('Téléchargement de', __FILE__) . ' ' . $this->getLogicalId() . '...');
		log::add('update', 'alert', __('URL', __FILE__) . ' ' . $url);
		exec('wget "' . $url . '" -O ' . $tmp . ' >> ' . log::getPathToLog('update') . ' 2>&1');
		switch ($this->getType()) {
			case 'plugin':
				return $tmp;
				break;
			default:
				log::add('update', 'alert', __('Installation des plugin, widget, scénario...', __FILE__));
				$type = $this->getType();
				if (class_exists($type) && method_exists($type, 'getFromMarket')) {
					$type::getFromMarket($this, $tmp);
				}
				log::add('update', 'alert', __("OK\n", __FILE__));
				break;
		}
		return false;
	}

	public function remove() {
		$cache = cache::byKey('market::info::' . $this->getLogicalId());
		if (is_object($cache)) {
			$cache->remove();
		}
		switch ($this->getType()) {
			case 'plugin':

				break;
			default:
				$type = $this->getType();
				if (class_exists($type) && method_exists($type, 'removeFromMarket')) {
					$type::removeFromMarket($this);
				}
				break;
		}
	}

	public function save() {
		$cache = cache::byKey('market::info::' . $this->getLogicalId());
		if (is_object($cache)) {
			$cache->remove();
		}
		$market = self::getJsonRpc();
		$params = utils::o2a($this);
		if (isset($params['changelog'])) {
			unset($params['changelog']);
		}
		switch ($this->getType()) {
			case 'plugin':
				$plugin_id = $this->getLogicalId();
				$cibDir = jeedom::getTmpFolder('market') . '/' . $plugin_id;
				if (file_exists($cibDir)) {
					rrmdir($cibDir);
				}
				mkdir($cibDir);
				$exclude = array('tmp', '.git', '.DStore');
				if (property_exists($plugin_id, '_excludeOnSendPlugin')) {
					$exclude = array_merge($plugin_id::$_excludeOnSendPlugin);
				}
				exec('find ' . realpath(__DIR__ . '/../../plugins/' . $plugin_id) . ' -name "*.sh" -type f -exec dos2unix {} \;');
				rcopy(realpath(__DIR__ . '/../../plugins/' . $plugin_id), $cibDir, true, $exclude, true);
				if (file_exists($cibDir . '/data')) {
					rrmdir($cibDir . '/data');
				}
				$tmp = jeedom::getTmpFolder('market') . '/' . $plugin_id . '.zip';
				if (file_exists($tmp)) {
					if (!unlink($tmp)) {
						throw new Exception(__('Impossible de supprimer :', __FILE__) . ' ' . $tmp . __('. Vérifiez les droits', __FILE__));
					}
				}
				if (!create_zip($cibDir, $tmp)) {
					throw new Exception(__('Echec de création de l\'archive zip', __FILE__));
				}
				rrmdir($cibDir);
				break;
			default:
				$type = $this->getType();
				if (!class_exists($type) || !method_exists($type, 'shareOnMarket')) {
					throw new Exception(__('Aucune fonction correspondante à :', __FILE__) . ' ' . $type . '::shareOnMarket');
				}
				$tmp = $type::shareOnMarket($this);
				break;
		}
		if (!file_exists($tmp)) {
			throw new Exception(__('Impossible de trouver le fichier à envoyer :', __FILE__) . ' ' . $tmp);
		}
		$file = array(
			'file' => '@' . realpath($tmp),
		);
		if (!$market->sendRequest('market::save', $params, 30, $file)) {
			throw new Exception($market->getError());
		}
		unlink($tmp);
		$update = update::byTypeAndLogicalId($this->getType(), $this->getLogicalId());
		if (!is_object($update)) {
			$update = new update();
			$update->setLogicalId($this->getLogicalId());
			$update->setType($this->getType());
		}
		if ($update->getSource() == 'market') {
			$update->setConfiguration('version', 'beta');
			$update->setLocalVersion(date('Y-m-d H:i:s', strtotime('+10 minute' . date('Y-m-d H:i:s'))));
			$update->save();
		}
		$update->checkUpdate();
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getType() {
		return $this->type;
	}

	public function getDatetime($_key = '', $_default = '') {
		return utils::getJsonAttr($this->datetime, $_key, $_default);
	}

	public function setDatetime($_key, $_value) {
		$this->datetime = utils::setJsonAttr($this->datetime, $_key, $_value);
		return $this;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getCategorie() {
		return $this->categorie;
	}

	public function getVersion() {
		return $this->version;
	}

	public function getUser_id() {
		return $this->user_id;
	}

	public function getDownloaded() {
		return $this->downloaded;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	public function setCategorie($categorie) {
		$this->categorie = $categorie;
		return $this;
	}

	public function setVersion($version) {
		$this->version = $version;
		return $this;
	}

	public function setUser_id($user_id) {
		$this->user_id = $user_id;
		return $this;
	}

	public function setDownloaded($downloaded) {
		$this->downloaded = $downloaded;
		return $this;
	}

	public function getStatus($_key = '', $_default = '') {
		return utils::getJsonAttr($this->status, $_key, $_default);
	}

	public function setStatus($_key, $_value) {
		$this->status = utils::setJsonAttr($this->status, $_key, $_value);
		return $this;
	}

	public function getLink($_key = '', $_default = '') {
		return utils::getJsonAttr($this->link, $_key, $_default);
	}

	public function setLink($_key, $_value) {
		$this->link = utils::setJsonAttr($this->link, $_key, $_value);
		return $this;
	}

	public function getLanguage($_key = '', $_default = '') {
		return utils::getJsonAttr($this->language, $_key, $_default);
	}

	public function setLanguage($_key, $_value) {
		$this->language = utils::setJsonAttr($this->language, $_key, $_value);
		return $this;
	}

	public function getImg($_key = '', $_default = '') {
		return utils::getJsonAttr($this->img, $_key, $_default);
	}

	public function getAuthor() {
		return $this->author;
	}

	public function setAuthor($author) {
		$this->author = $author;
		return $this;
	}

	public function getChangelog() {
		return $this->changelog;
	}

	public function setChangelog($changelog) {
		$this->changelog = $changelog;
		return $this;
	}

	public function getNbInstall() {
		return $this->nbInstall;
	}

	public function setNbInstall($nbInstall) {
		$this->nbInstall = $nbInstall;
		return $this;
	}

	public function getLogicalId() {
		return $this->logicalId;
	}

	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
		return $this;
	}

	public function getPrivate() {
		return $this->private;
	}

	public function setPrivate($private) {
		$this->private = $private;
		return $this;
	}

	public function getIsAuthor() {
		return $this->isAuthor;
	}

	public function setIsAuthor($isAuthor) {
		$this->isAuthor = $isAuthor;
		return $this;
	}

	public function getUtilization() {
		return $this->utilization;
	}

	public function setUtilization($utilization) {
		$this->utilization = $utilization;
		return $this;
	}

	public function getPurchase() {
		return $this->purchase;
	}

	public function setPurchase($purchase) {
		$this->purchase = $purchase;
		return $this;
	}

	public function getCost() {
		return $this->cost;
	}

	public function setCost($cost) {
		$this->cost = $cost;
		return $this;
	}

	public function getRealcost() {
		return $this->realcost;
	}

	public function setRealcost($realcost) {
		$this->realcost = $realcost;
		return $this;
	}

	public function getBuyer() {
		return $this->buyer;
	}

	public function setBuyer($buyer) {
		$this->buyer = $buyer;
		return $this;
	}

	public function getCertification() {
		return $this->certification;
	}

	public function setCertification($certification) {
		$this->certification = $certification;
		return $this;
	}

	public function getDoc() {
		return $this->doc;
	}

	public function setDoc($doc) {
		$this->doc = $doc;
		return $this;
	}

	public function getUpdateBy() {
		return $this->updateBy;
	}

	public function setUpdateBy($updateBy) {
		$this->updateBy = $updateBy;
		return $this;
	}

	public function getAllowVersion() {
		return $this->allowVersion;
	}

	public function setAllowVersion($allowVersion) {
		$this->allowVersion = $allowVersion;
		return $allowVersion;
	}

	public function getHardwareCompatibility($_key = '', $_default = '') {
		return utils::getJsonAttr($this->hardwareCompatibility, $_key, $_default);
	}

	public function setHardwareCompatibility($_key, $_value) {
		$this->hardwareCompatibility = utils::setJsonAttr($this->hardwareCompatibility, $_key, $_value);
		return $this;
	}

	public function getParameters($_key = '', $_default = '') {
		return utils::getJsonAttr($this->parameters, $_key, $_default);
	}

	public function setParameters($_key, $_value) {
		$this->parameters = utils::setJsonAttr($this->parameters, $_key, $_value);
		return $this;
	}
}
