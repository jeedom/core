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
		'sendPlugin' => true,
		'hasStore' => true,
		'hasScenarioStore' => true,
		'test' => true,
	);

	public static $_configuration = array(
		'configuration' => array(
			'address' => array(
				'name' => 'Adresse',
				'type' => 'input',
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
		'parameters_for_add' => array(
			'version' => array(
				'name' => 'Version : beta,release, stable',
				'type' => 'input',
			),
		),
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
	private $hardwareCompatibility;
	private $nbInstall;

	/*     * ***********************Méthodes statiques*************************** */

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
			throw new Exception(__('Objet introuvable sur le market : ', __FILE__) . $_update->getLogicalId() . '/' . $_update->getType());
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
		$url = 'https://jeedom.github.io/documentation/plugins/' . $_update->getLogicalId() . '/' . config::byKey('language', 'core', 'fr_FR') . '/index.html';
		if ($_update->getConfiguration('third_plugin', null) === null) {
			$_update->setConfiguration('third_plugin', 0);
			$header = get_headers($url);
			if (strpos($header[0], '200') === false) {
				$_update->setConfiguration('third_plugin', 1);
				$url = 'https://jeedom.github.io/documentation/third_plugin/' . $_update->getLogicalId() . '/' . config::byKey('language', 'core', 'fr_FR') . '/index.html';
			}
			$_update->save();
		} elseif ($_update->getConfiguration('third_plugin', 0) == 1) {
			$url = 'https://jeedom.github.io/documentation/third_plugin/' . $_update->getLogicalId() . '/' . config::byKey('language', 'core', 'fr_FR') . '/index.html';
		}
		return array(
			'doc' => $url,
			'changelog' => $url . '#_changelog',
			'display' => 'https://market.jeedom.fr/index.php?v=d&p=market&type=plugin&plugin_id=' . $_update->getLogicalId(),
		);
	}

	/*     * ***********************BACKUP*************************** */

	public static function sendBackup($_path) {
		$market = self::getJsonRpc();
		$file = array(
			'file' => '@' . realpath($_path),
		);
		if (!$market->sendRequest('backup::upload', array(), 7200, $file)) {
			throw new Exception($market->getError());
		}
	}

	public static function uploadChunk($path, $chunkStart, $chunkEnd, $id, $fileSize) {
		$market = self::getJsonRpc();
		$file = fopen($path, "r");
		fseek($file, $chunkStart - $fileSize, SEEK_END);
		$chunkSize = (($diff = $chunkEnd - $chunkStart) < 10240) ? 10240 : $diff;
		$chunk = fread($file, $chunkSize);
		$tmp = tmpfile();
		$path = stream_get_meta_data($tmp);
		fwrite($tmp, $chunk);
		$rangeHeader = array('chunkStart' => $chunkStart, 'chunkEnd' => $chunkEnd, 'chunkSize' => $chunkSize, 'fileSize' => $fileSize);
		if (!$market->sendRequest('backup::put', array('id' => $id, 'rangeHeader' => $rangeHeader), 7300, array('file' => '@' . $path['uri']))) {
			fclose($tmp);
			throw new Exception($market->getError());
		}
		$status = $market->getResult();
		fclose($tmp);
		return $status;
	}

	public static function sendBackupCloud($_path, $_chunksize = 1024000) {
		$market = self::getJsonRpc();
		if (!$market->sendRequest('backup::create', array('filename' => pathinfo($_path, PATHINFO_BASENAME), 'filesize' => filesize($_path), 'chunksize' => $_chunksize, 'checksum' => md5_file($_path)))) {
			throw new Exception($market->getError());
		}
		$backup = $market->getResult();
		if (isset($backup["completed_at"]) && $backup["completed_at"]) {
			log::add('backupCloud', 'info', 'le backup est déjà sur le cloud');
			return false;
		}
		if (isset($backup["retry"]) && $backup["retry"] > 5) {
			log::add('backupCloud', 'info', 'Upload impossible, nombre d\'essais : 5');
			return false;
		}
		$fileSize = filesize($_path);
		while (!$backup["completed_at"]) {
			try {
				$backup = repo_market::uploadChunk($_path, $backup["chunk_start"], $backup["chunk_end"], $backup["id"], $fileSize);
			} catch (Exception $e) {
				echo $e->getMessage() . "\n";
				repo_market::sendBackupCloud($_path);
				break;
			}

		}
		if ($backup["completed_at"] != "") {
			log::add('backupCloud', 'info', 'le backup a fini l upload');
			return true;
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
		$url = config::byKey('market::address') . "/core/php/downloadBackup.php?backup=" . $_backup . '&hwkey=' . jeedom::getHardwareKey() . '&username=' . urlencode(config::byKey('market::username')) . '&password=' . self::getPassword() . '&password_type=sha1';
		$tmp_dir = jeedom::getTmpFolder('market');
		$tmp = $tmp_dir . '/' . $_backup;
		$opts = array(
			"ssl" => array(
				"verify_peer" => false,
				"verify_peer_name" => false,
			),
		);
		file_put_contents($tmp, fopen($url, 'r', false, stream_context_create($opts)));
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

	/*     * ***********************CRON*************************** */

	public static function cronHourly() {
		sleep(rand(0, 600));
		try {
			self::test();
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
					log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
					$return['status'] = 'ok';
				} catch (Error $e) {
					log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
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
			log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
			$return['status'] = 'ok';
		} catch (Error $e) {
			log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
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
				$_ticket['user_plugin'] .= '[' . $update->getConfiguration('version', 'stable') . ',' . $update->getLocalVersion() . ']';
			}
			$_ticket['user_plugin'] .= ',';
		}
		trim($_ticket['user_plugin'], ',');
		if (isset($_ticket['options']['page'])) {
			$_ticket['options']['page'] = substr($_ticket['options']['page'], strpos($_ticket['options']['page'], 'index.php'));
		}
		$_ticket['options']['jeedom_version'] = jeedom::version();
		$_ticket['options']['uname'] = shell_exec('uname -a');
		if (!$jsonrpc->sendRequest('ticket::save', array('ticket' => $_ticket))) {
			throw new Exception($jsonrpc->getErrorMessage());
		}
		return $jsonrpc->getResult();
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
		if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
			$params = array(
				'username' => config::byKey('market::username'),
				'password' => self::getPassword(),
				'password_type' => 'sha1',
				'jeedomversion' => jeedom::version(),
				'hwkey' => jeedom::getHardwareKey(),
				'addrComplement' => config::byKey('externalComplement'),
				'information' => array(
					'nbMessage' => message::nbMessage(),
					'nbUpdate' => update::nbNeedUpdate(),
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
		$jsonrpc->setCb_class('repo_market');
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
			if (isset($_result['market::allowAllRepo']) && config::byKey('market::allowAllRepo') != $_result['market::allowAllRepo']) {
				config::save('market::allowAllRepo', $_result['market::allowAllRepo']);
			}
			if (isset($_result['register::hwkey_nok']) && $_result['register::hwkey_nok'] == 1) {
				config::save('jeedom::installKey', '');
			}
		}
	}

	public static function construct($_arrayMarket) {
		$market = new self();
		if (!isset($_arrayMarket['id'])) {
			return;
		}
		$market->setId($_arrayMarket['id']);
		$market->setName($_arrayMarket['name']);
		$market->setType($_arrayMarket['type']);
		$market->datetime = json_encode($_arrayMarket['datetime'], JSON_UNESCAPED_UNICODE);
		$market->setDescription($_arrayMarket['description']);
		$market->setDownloaded($_arrayMarket['downloaded']);
		$market->setUser_id($_arrayMarket['user_id']);
		$market->setVersion($_arrayMarket['version']);
		$market->setCategorie($_arrayMarket['categorie']);
		$market->status = json_encode($_arrayMarket['status'], JSON_UNESCAPED_UNICODE);
		$market->setAuthor($_arrayMarket['author']);
		@$market->setChangelog($_arrayMarket['changelog']);
		@$market->setDoc($_arrayMarket['doc']);
		$market->setLogicalId($_arrayMarket['logicalId']);
		@$market->setUtilization($_arrayMarket['utilization']);
		@$market->setCertification($_arrayMarket['certification']);
		$market->setPurchase($_arrayMarket['purchase']);
		$market->setCost($_arrayMarket['cost']);
		$market->rating = ($_arrayMarket['rating']);
		$market->setBuyer($_arrayMarket['buyer']);
		$market->setUpdateBy($_arrayMarket['updateBy']);
		$market->setPrivate($_arrayMarket['private']);
		$market->setNbInstall($_arrayMarket['nbInstall']);
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
			throw new Exception(__('Impossible d\'écrire dans le répertoire : ', __FILE__) . $tmp . __('. Exécuter la commande suivante en SSH : sudo chmod 777 -R ', __FILE__) . $tmp_dir);
		}

		$url = config::byKey('market::address') . "/core/php/downloadFile.php?id=" . $this->getId() . '&version=' . $_version . '&jeedomversion=' . jeedom::version() . '&hwkey=' . jeedom::getHardwareKey() . '&username=' . urlencode(config::byKey('market::username')) . '&password=' . self::getPassword() . '&password_type=sha1';
		log::add('update', 'alert', __('Téléchargement de ', __FILE__) . $this->getLogicalId() . '...');
		$opts = array(
			"ssl" => array(
				"verify_peer" => false,
				"verify_peer_name" => false,
			),
		);
		file_put_contents($tmp, fopen($url, 'r', false, stream_context_create($opts)));

		switch ($this->getType()) {
			case 'plugin':
				return $tmp;
				break;
			default:
				log::add('update', 'alert', __('Installation de du plugin,widget,scénario...', __FILE__));
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
				$exclude = array(
					'tmp',
					'.git',
					'.DStore',
				);
				if (property_exists($plugin_id, '_excludeOnSendPlugin')) {
					$exclude = array_merge($plugin_id::$_excludeOnSendPlugin);
				}
				exec('find ' . realpath(dirname(__FILE__) . '/../../plugins/' . $plugin_id) . ' -name "*.sh" -type f -exec dos2unix {} \;');
				rcopy(realpath(dirname(__FILE__) . '/../../plugins/' . $plugin_id), $cibDir, true, $exclude, true);
				if (file_exists($cibDir . '/data')) {
					rrmdir($cibDir . '/data');
				}
				$tmp = jeedom::getTmpFolder('market') . '/' . $plugin_id . '.zip';
				if (file_exists($tmp)) {
					if (!unlink($tmp)) {
						throw new Exception(__('Impossible de supprimer : ', __FILE__) . $tmp . __('. Vérifiez les droits', __FILE__));
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
					throw new Exception(__('Aucune fonction correspondante à : ', __FILE__) . $type . '::shareOnMarket');
				}
				$tmp = $type::shareOnMarket($this);
				break;
		}
		if (!file_exists($tmp)) {
			throw new Exception(__('Impossible de trouver le fichier à envoyer : ', __FILE__) . $tmp);
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

	public function getNbInstall() {
		return $this->nbInstall;
	}

	public function setNbInstall($nbInstall) {
		$this->nbInstall = $nbInstall;
		return $this;
	}

	public function getHardwareCompatibility($_key = '', $_default = '') {
		return utils::getJsonAttr($this->hardwareCompatibility, $_key, $_default);
	}

	public function setHardwareCompatibility($_key, $_value) {
		$this->hardwareCompatibility = utils::setJsonAttr($this->hardwareCompatibility, $_key, $_value);
		return $this;
	}

}
