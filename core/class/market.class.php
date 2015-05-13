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

class market {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $type;
	private $datetime;
	private $description;
	private $categorie;
	private $changelog;
	private $version;
	private $user_id;
	private $downloaded;
	private $status;
	private $author;
	private $logicalId;
	private $rating;
	private $utilization;
	private $api_author;
	private $img;
	private $buyer;
	private $purchase = 0;
	private $cost = 0;
	private $realcost = 0;
	private $link;
	private $certification;
	private $nbComment;
	private $language;
	private $private;
	private $change;
	private $updateBy;
	private $docOnly;

	/*     * ***********************Méthodes statiques*************************** */

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
		$market->setChangelog($_arrayMarket['changelog']);
		$market->setLogicalId($_arrayMarket['logicalId']);
		$market->setUtilization($_arrayMarket['utilization']);
		$market->setCertification($_arrayMarket['certification']);
		$market->setPurchase($_arrayMarket['purchase']);
		$market->setCost($_arrayMarket['cost']);
		$market->rating = ($_arrayMarket['rating']);
		$market->setBuyer($_arrayMarket['buyer']);
		$market->setUpdateBy($_arrayMarket['updateBy']);
		$market->setNbComment($_arrayMarket['nbComment']);
		$market->setPrivate($_arrayMarket['private']);
		$market->img = json_encode($_arrayMarket['img'], JSON_UNESCAPED_UNICODE);
		$market->link = json_encode($_arrayMarket['link'], JSON_UNESCAPED_UNICODE);
		$market->language = json_encode($_arrayMarket['language'], JSON_UNESCAPED_UNICODE);
		$market->change = '';

		$market->setRealcost($_arrayMarket['realCost']);
		if (!isset($_arrayMarket['api_author'])) {
			$_arrayMarket['api_author'] = null;
		}
		$market->setApi_author($_arrayMarket['api_author']);

		return $market;
	}

	public static function getPromo() {
		try {
			$market = self::getJsonRpc();
			if ($market->sendRequest('market::getPromotion')) {
				return $market->getResult();
			} else {

			}
		} catch (Exception $e) {

		}
	}

	public static function test() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::test')) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function sendUserMessage($_title, $_message) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('user::sendMessage', array('title' => $_title, 'message' => $_message))) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
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
		$options = array('logicalId' => $_logicalId);
		if (is_array($_logicalId)) {
			$options = $_logicalId;
		}
		if ($market->sendRequest('market::byLogicalId', $options)) {
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

	public static function getMultiChangelog($_params) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::changelog', $_params)) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	public static function byLogicalIdAndType($_logicalId, $_type = '') {
		$market = self::getJsonRpc();
		$options = array('logicalId' => $_logicalId, 'type' => $_type);
		if (is_array($_logicalId)) {
			$options = $_logicalId;
		}
		if ($market->sendRequest('market::byLogicalIdAndType', $options)) {
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

	public static function getPurchaseInfo() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('purchase::getInfo')) {
			return $market->getResult();
		}
	}

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
		jeedom::sick();
		$cibDir = realpath(dirname(__FILE__) . '/../../log');
		if (file_exists('/var/log/messages')) {
			@copy('/var/log/messages', realpath(dirname(__FILE__) . '/../../log/dmesg_messages'));
		}
		@exec('dmesg >> ' . dirname(__FILE__) . '/../../log/dmesg');
		$tmp = dirname(__FILE__) . '/../../tmp/log.zip';
		if (file_exists($tmp)) {
			if (!unlink($tmp)) {
				throw new Exception(__('Impossible de supprimer : ', __FILE__) . $tmp . __('. Vérifiez les droits', __FILE__));
			}
		}
		if (!create_zip($cibDir, $tmp)) {
			throw new Exception(__('Echec de création de l\'archive zip', __FILE__));
		}
		if (isset($_ticket['options']['page'])) {
			$_ticket['options']['page'] = substr($_ticket['options']['page'], strpos($_ticket['options']['page'], 'index.php'));
		}
		$file = array(
			'file' => '@' . realpath($tmp),
		);
		if (isset($_ticket['allowRemoteAccess']) && $_ticket['allowRemoteAccess'] == 1) {
			$user = user::createTemporary(72);
			$_ticket['options']['remoteAccess'] = 'Http : ' . $user->getDirectUrlAccess();
			if (config::byKey('market::allowDNS') == 1 && config::byKey('market::redirectSSH') == 1 && config::byKey('ngrok::port') != '') {
				$_ticket['options']['remoteAccess'] .= ' | SSH : dns.jeedom.com:' . config::byKey('ngrok::port');
			}
		}

		$_ticket['options']['jeedom_version'] = jeedom::version();
		if (!$jsonrpc->sendRequest('ticket::save', array('ticket' => $_ticket), 600, $file)) {
			throw new Exception($jsonrpc->getErrorMessage());
		}
		return $jsonrpc->getResult();
	}

	public static function getJeedomCurrentVersion($_refresh = false) {
		try {
			$cache = cache::byKey('jeedom::lastVersion');
			if (!$_refresh && $cache->getValue('') != '') {
				return $cache->getValue();
			}
			$jsonrpc = self::getJsonRpc();
			if ($jsonrpc->sendRequest('jeedom::getCurrentVersion', array('branch' => config::byKey('market::branch')))) {
				$version = trim($jsonrpc->getResult());
				cache::set('jeedom::lastVersion', $version, 86400);
				return $version;
			} else {
				log::add('market', 'error', $jsonrpc->getErrorMessage());
			}
		} catch (Exception $e) {

		}
		return null;
	}

	public static function getJsonRpc() {
		if (config::byKey('market::address') == '') {
			throw new Exception(__('Aucune addresse n\'est renseignée pour le market', __FILE__));
		}
		if (config::byKey('market::jeedom_apikey') == '') {
			config::save('market::jeedom_apikey', config::genKey(255));
		}
		if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
			$jsonrpc = new jsonrpcClient(config::byKey('market::address') . '/core/api/api.php', '', array(
				'username' => config::byKey('market::username'),
				'password' => config::byKey('market::password'),
				'password_type' => 'sha1',
				'jeedomversion' => (method_exists('jeedom', 'version')) ? jeedom::version() : getVersion('jeedom'),
				'hwkey' => jeedom::getHardwareKey(),
				'addr' => config::byKey('externalAddr'),
				'addrProtocol' => config::byKey('externalProtocol'),
				'addrPort' => config::byKey('externalPort'),
				'addrComplement' => config::byKey('externalComplement'),
				'marketkey' => config::byKey('market::jeedom_apikey'),
				'nbMessage' => message::nbMessage(),
			));
		} else {
			$jsonrpc = new jsonrpcClient(config::byKey('market::address') . '/core/api/api.php', '', array(
				'jeedomversion' => (method_exists('jeedom', 'version')) ? jeedom::version() : getVersion('jeedom'),
				'hwkey' => jeedom::getHardwareKey(),
			));
		}
		$jsonrpc->setCb_class('market');
		$jsonrpc->setCb_function('postJsonRpc');
		return $jsonrpc;
	}

	public static function postJsonRpc(&$_result) {
		if (is_array($_result)) {
			if (isset($_result['register::datetime'])) {
				config::save('register::datetime', $_result['register::datetime']);
			}
			if (config::byKey('market::allowDNS') == 1) {
				if (isset($_result['client::ip']) && (filter_var(config::byKey('externalAddr'), FILTER_VALIDATE_IP) || config::byKey('externalAddr') == '')) {
					config::save('externalAddr', $_result['client::ip']);
				}

				if (isset($_result['register::ngrokAddr']) && config::byKey('ngrok::addr') != $_result['register::ngrokAddr']) {
					config::save('ngrok::addr', $_result['register::ngrokAddr']);
					if (network::ngrok_run()) {
						network::ngrok_stop();
					}
					if (network::ngrok_run('tcp', 22, 'ssh')) {
						network::ngrok_stop('tcp', 22, 'ssh');
					}
					if (config::byKey('market::allowDNS') == 1) {
						network::ngrok_start();
						if (config::byKey('market::redirectSSH') == 1) {
							network::ngrok_start('tcp', 22, 'ssh');
						}
					}
				}

				if (isset($_result['register::ngrokToken']) && config::byKey('ngrok::token') != $_result['register::ngrokToken']) {
					config::save('ngrok::token', $_result['register::ngrokToken']);
					if (network::ngrok_run()) {
						network::ngrok_stop();
					}
					if (network::ngrok_run('tcp', 22, 'ssh')) {
						network::ngrok_stop('tcp', 22, 'ssh');
					}
					if (config::byKey('market::allowDNS') == 1) {
						network::ngrok_start();
						if (config::byKey('market::redirectSSH') == 1) {
							network::ngrok_start('tcp', 22, 'ssh');
						}
					}
				}
				if (isset($_result['register::ngrokPort']) && config::byKey('ngrok::port') != $_result['register::ngrokPort']) {
					config::save('ngrok::port', $_result['register::ngrokPort']);
					if (network::ngrok_run('tcp', 22, 'ssh')) {
						network::ngrok_stop('tcp', 22, 'ssh');
					}
					if (config::byKey('market::allowDNS') == 1) {
						if (config::byKey('market::redirectSSH') == 1) {
							network::ngrok_start('tcp', 22, 'ssh');
						}
					}
				}
				if (isset($_result['jeedom::url']) && config::byKey('jeedom::url') != $_result['jeedom::url']) {
					config::save('jeedom::url', $_result['jeedom::url']);
				}
			}
			if (isset($_result['register::datetime'])) {
				unset($_result['register::datetime']);
			}
			if (isset($_result['licence'])) {
				unset($_result['licence']);
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
			if (isset($_result['jeedom::url'])) {
				unset($_result['jeedom::url']);
			}
			if (isset($_result['client::ip'])) {
				unset($_result['client::ip']);
			}
		}
	}

	public static function getInfo($_logicalId, $_version = 'stable') {
		$returns = array();
		if (is_array($_logicalId) && is_array($_version) && count($_logicalId) == count($_version)) {
			if (is_array(reset($_logicalId))) {
				$markets = market::byLogicalIdAndType($_logicalId);
			} else {
				$markets = market::byLogicalId($_logicalId);
			}

			$returns = array();
			for ($i = 0; $i < count($_logicalId); $i++) {
				if (is_array($_logicalId[$i])) {
					$logicalId = $_logicalId[$i]['type'] . $_logicalId[$i]['logicalId'];
				} else {
					$logicalId = $_logicalId[$i];
				}
				$return['datetime'] = '0000-01-01 00:00:00';
				if ($logicalId == '' || config::byKey('market::address') == '') {
					$return['market'] = 0;
					$return['market_owner'] = 0;
					$return['status'] = 'ok';
					return $return;
				}

				if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
					$return['market_owner'] = 1;
				} else {
					$return['market_owner'] = 0;
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
							if ($market->getApi_author() != '') {
								$return['market_owner'] = 1;
							} else {
								$return['market_owner'] = 0;
							}
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
					log::add('market', 'debug', __('Erreur market::getinfo : ', __FILE__) . $e->getMessage());
					$return['status'] = 'ok';
				}
				$returns[$logicalId] = $return;
			}
			return $returns;
		}
		$return = array();
		$return['datetime'] = '0000-01-01 00:00:00';
		if (config::byKey('market::address') == '') {
			$return['market'] = 0;
			$return['market_owner'] = 0;
			$return['status'] = 'ok';
			return $return;
		}

		if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
			$return['market_owner'] = 1;
		} else {
			$return['market_owner'] = 0;
		}
		$return['market'] = 0;

		try {
			if (is_array($_logicalId)) {
				$market = market::byLogicalIdAndType($_logicalId['logicalId'], $_logicalId['type']);
			} else {
				$market = market::byLogicalId($_logicalId);
			}
			if (!is_object($market)) {
				$return['status'] = 'depreciated';
			} else {
				$return['datetime'] = $market->getDatetime($_version);
				$return['market'] = 1;
				if ($market->getApi_author() != '') {
					$return['market_owner'] = 1;
				} else {
					$return['market_owner'] = 0;
				}

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
			log::add('market', 'debug', __('Erreur market::getinfo : ', __FILE__) . $e->getMessage());
			$return['status'] = 'ok';
		}
		return $return;
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

	public static function checkPayment($_id) {
		try {
			$market = self::byLogicalId($_id);
		} catch (Exception $e) {
			if ($e->getCode() == 3456) {
				throw new Exception(__('Veuillez vous connecter à internet pour activer le plugin. Cette opération n\'est à effectuer qu\'une seule fois lors de l\'activation', __FILE__));
			} else {
				if (strpos($e->getMessage(), '-32026') === false) {
					throw new Exception($e->getMessage());
				}
			}
		}
		if (is_object($market) && $market->getPurchase() == 0) {
			throw new Exception(__('Vous devez acheter cet article avant de pouvoir l\'activer', __FILE__));
		}
	}

	public static function validateTicket($_ticket) {
		if (config::byKey('market::jeedom_apikey') == '') {
			config::save('market::jeedom_apikey', config::genKey(255));
		}
		$market = self::getJsonRpc();
		$params = array(
			'marketkey' => config::byKey('market::jeedom_apikey'),
			'ticket' => $_ticket,
		);
		if (!$market->sendRequest('jeedom::checkTicket', $params)) {
			throw new Exception($market->getError());
		}
		return true;
	}

	public static function distinctCategorie($_type) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::distinctCategorie', array('type' => $_type))) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}

	/*     * *********************Methode d'instance************************* */

	public function getComment() {
		$market = self::getJsonRpc();
		if (!$market->sendRequest('market::getComment', array('id' => $this->getId()))) {
			throw new Exception($market->getError());
		}
		return $market->getResult();
	}

	public function setComment($_comment = null, $_order = null) {
		$market = self::getJsonRpc();
		if (!$market->sendRequest('market::setComment', array('id' => $this->getId(), 'comment' => $_comment, 'order' => $_order))) {
			throw new Exception($market->getError());
		}
	}

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
		log::add('update', 'update', __('Début de la mise à jour de : ', __FILE__) . $this->getLogicalId() . "\n");
		$tmp_dir = dirname(__FILE__) . '/../../tmp';
		$tmp = $tmp_dir . '/' . $this->getLogicalId() . '.zip';
		if (file_exists($tmp)) {
			unlink($tmp);
		}
		if (!is_writable($tmp_dir)) {
			throw new Exception(__('Impossible d\'écrire dans le répertoire : ', __FILE__) . $tmp . __('. Exécuter la commande suivante en SSH : chmod 777 -R ', __FILE__) . $tmp_dir);
		}
		$url = config::byKey('market::address') . "/core/php/downloadFile.php?id=" . $this->getId() . '&version=' . $_version . '&hwkey=' . jeedom::getHardwareKey() . '&username=' . urlencode(config::byKey('market::username')) . '&password=' . config::byKey('market::password') . '&password_type=sha1';
		log::add('update', 'update', __('Téléchargement de l\'objet...', __FILE__));
		file_put_contents($tmp, fopen($url, 'r'));
		if (!file_exists($tmp)) {
			throw new Exception(__('Impossible de télécharger le fichier depuis : ' . $url . '. Si l\'application est payante, l\'avez-vous achetée ?', __FILE__));
		}
		log::add('update', 'update', __("OK\n", __FILE__));
		switch ($this->getType()) {
			case 'plugin':
				$cibDir = dirname(__FILE__) . '/../../plugins/' . $this->getLogicalId();
				if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
					throw new Exception(__('Impossible de créer le dossier  : ' . $cibDir . '. Problème de droits ?', __FILE__));
				}
				log::add('update', 'update', __('Décompression de l\'archive...', __FILE__));
				$zip = new ZipArchive;
				$res = $zip->open($tmp);
				if ($res === TRUE) {
					if (!$zip->extractTo($cibDir . '/')) {
						$content = file_get_contents($tmp);
						throw new Exception(__('Impossible d\'installer le plugin. Les fichiers n\'ont pas pu être décompressés : ', __FILE__) . substr($content, 255));
					}
					$zip->close();
					log::add('update', 'update', __("OK\n", __FILE__));
					log::add('update', 'update', __('Installation de l\'objet...', __FILE__));
					try {
						$plugin = plugin::byId($this->getLogicalId());
					} catch (Exception $e) {
						$this->remove();
						throw new Exception(__('Impossible d\'installer le plugin. Le nom du plugin est différent de l\'ID ou le plugin n\'est pas correctement formé. Veuillez contacter l\'auteur.', __FILE__));
					}
					log::add('update', 'update', __("OK\n", __FILE__));
					$update = update::byTypeAndLogicalId($this->getType(), $this->getLogicalId());
					if (is_object($plugin) && $plugin->isActive()) {
						$plugin->setIsEnable(1);
					}
				} else {
					switch ($res) {
					case ZipArchive::ER_EXISTS:
							$ErrMsg = "Le fichier existe déjà.";
							break;
					case ZipArchive::ER_INCONS:
							$ErrMsg = "L'archive zip est inconsistente.";
							break;
					case ZipArchive::ER_MEMORY:
							$ErrMsg = "Erreur mémoire.";
							break;
					case ZipArchive::ER_NOENT:
							$ErrMsg = "Ce fichier n\'existe pas.";
							break;
					case ZipArchive::ER_NOZIP:
							$ErrMsg = "Ceci n\'est pas une archive zip.";
							break;
					case ZipArchive::ER_OPEN:
							$ErrMsg = "Le fichier ne peut pas être ouvert.";
							break;
					case ZipArchive::ER_READ:
							$ErrMsg = "Erreur de lecture.";
							break;
					case ZipArchive::ER_SEEK:
							$ErrMsg = "Erreur de recherche.";
							break;
					default:
							$ErrMsg = "Erreur inconnue (Code $res)";
							break;
					}
					throw new Exception(__('Impossible de décompresser l\'archive zip : ', __FILE__) . $tmp . __('. Erreur : ', __FILE__) . $ErrMsg . '. Si l\'application est payante, l\'avez-vous achetée ?');
				}
				break;
			default:
				log::add('update', 'update', __('Installation de l\'objet...', __FILE__));
				$type = $this->getType();
				if (class_exists($type) && method_exists($type, 'getFromMarket')) {
					$type::getFromMarket($this, $tmp);
				}
				log::add('update', 'update', __("OK\n", __FILE__));
				break;
		}
		$update = update::byTypeAndLogicalId($this->getType(), $this->getLogicalId());
		if (!is_object($update)) {
			$update = new update();
			$update->setLogicalId($this->getLogicalId());
			$update->setType($this->getType());
		}
		$update->setLocalVersion($this->getDatetime($_version));
		$update->setConfiguration('version', $_version);
		$update->save();
		$update->checkUpdate();
	}

	public function remove() {
		$cache = cache::byKey('market::info::' . $this->getLogicalId());
		if (is_object($cache)) {
			$cache->remove();
		}
		switch ($this->getType()) {
			case 'plugin':
				try {
					$plugin = plugin::byId($this->getLogicalId());
					if (is_object($plugin)) {
						$plugin->setIsEnable(0);
						foreach (eqLogic::byType($this->getLogicalId()) as $eqLogic) {
							try {
								$eqLogic->remove();
							} catch (Exception $e) {

							}
						}
					}
					config::remove('*', $this->getLogicalId());
				} catch (Exception $e) {

				}
				try {
					$cibDir = dirname(__FILE__) . '/../../plugins/' . $this->getLogicalId();
					if (file_exists($cibDir)) {
						rrmdir($cibDir);
					}
				} catch (Exception $e) {

				}
				break;
			default:
				$type = $this->getType();
				if (class_exists($type) && method_exists($type, 'removeFromMarket')) {
					$type::removeFromMarket($this);
				}
				break;
		}
		$update = update::byTypeAndLogicalId($this->getType(), $this->getLogicalId());
		if (is_object($update)) {
			$update->remove();
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
				$cibDir = dirname(__FILE__) . '/../../tmp/' . $this->getLogicalId();
				if (file_exists($cibDir)) {
					rrmdir($cibDir);
				}
				mkdir($cibDir);
				rcopy(realpath(dirname(__FILE__) . '/../../plugins/' . $this->getLogicalId()), $cibDir);
				if (file_exists($cibDir . '/core/config/devices') && $this->getLogicalId() == 'zwave') {
					rrmdir($cibDir . '/core/config/devices');
					mkdir($cibDir . '/core/config/devices');
				}
				$tmp = dirname(__FILE__) . '/../../tmp/' . $this->getLogicalId() . '.zip';
				if (file_exists($tmp)) {
					if (!unlink($tmp)) {
						throw new Exception(__('Impossible de supprimer : ', __FILE__) . $tmp . __('. Vérifiez les droits', __FILE__));
					}
				}
				if (!create_zip($cibDir, $tmp)) {
					throw new Exception(__('Echec de création de l\'archive zip', __FILE__));
				}
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
		$update = update::byTypeAndLogicalId($this->getType(), $this->getLogicalId());
		if (!is_object($update)) {
			$update = new update();
			$update->setLogicalId($this->getLogicalId());
			$update->setType($this->getType());
		}
		$update->setConfiguration('version', 'beta');
		$update->setLocalVersion(date('Y-m-d H:i:s', strtotime('+5 minute' . date('Y-m-d H:i:s'))));
		$update->save();
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
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setCategorie($categorie) {
		$this->categorie = $categorie;
	}

	public function setVersion($version) {
		$this->version = $version;
	}

	public function setUser_id($user_id) {
		$this->user_id = $user_id;
	}

	public function setDownloaded($downloaded) {
		$this->downloaded = $downloaded;
	}

	public function getStatus($_key = '', $_default = '') {
		return utils::getJsonAttr($this->status, $_key, $_default);
	}

	public function setStatus($_key, $_value) {
		$this->status = utils::setJsonAttr($this->status, $_key, $_value);
	}

	public function getLink($_key = '', $_default = '') {
		return utils::getJsonAttr($this->link, $_key, $_default);
	}

	public function setLink($_key, $_value) {
		$this->link = utils::setJsonAttr($this->link, $_key, $_value);
	}

	public function getLanguage($_key = '', $_default = '') {
		return utils::getJsonAttr($this->language, $_key, $_default);
	}

	public function setLanguage($_key, $_value) {
		$this->language = utils::setJsonAttr($this->language, $_key, $_value);
	}

	public function getImg($_key = '', $_default = '') {
		return utils::getJsonAttr($this->img, $_key, $_default);
	}

	public function getAuthor() {
		return $this->author;
	}

	public function setAuthor($author) {
		$this->author = $author;
	}

	public function getChangelog() {
		return $this->changelog;
	}

	public function setChangelog($changelog) {
		$this->changelog = $changelog;
	}

	public function getLogicalId() {
		return $this->logicalId;
	}

	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
	}

	public function getPrivate() {
		return $this->private;
	}

	public function setPrivate($private) {
		$this->private = $private;
	}

	public function getApi_author() {
		return $this->api_author;
	}

	public function setApi_author($api_author) {
		$this->api_author = $api_author;
	}

	public function getUtilization() {
		return $this->utilization;
	}

	public function setUtilization($utilization) {
		$this->utilization = $utilization;
	}

	public function getPurchase() {
		return $this->purchase;
	}

	public function setPurchase($purchase) {
		$this->purchase = $purchase;
	}

	public function getCost() {
		return $this->cost;
	}

	public function setCost($cost) {
		$this->cost = $cost;
	}

	public function getRealcost() {
		return $this->realcost;
	}

	public function setRealcost($realcost) {
		$this->realcost = $realcost;
	}

	public function getBuyer() {
		return $this->buyer;
	}

	public function setBuyer($buyer) {
		$this->buyer = $buyer;
	}

	public function getCertification() {
		return $this->certification;
	}

	public function setCertification($certification) {
		$this->certification = $certification;
	}

	public function getNbComment() {
		return $this->nbComment;
	}

	public function setNbComment($nbComment) {
		$this->nbComment = $nbComment;
	}

	public function getChange() {
		return $this->change;
	}

	public function setChange($change) {
		$this->change = $change;
	}

	public function getDocOnly() {
		return $this->docOnly;
	}

	public function setDocOnly($docOnly) {
		$this->docOnly = $docOnly;
	}

	public function getUpdateBy() {
		return $this->updateBy;
	}

	public function setUpdateBy($updateBy) {
		$this->updateBy = $updateBy;
	}

}

?>
