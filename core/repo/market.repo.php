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
		'test' => true
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
					'type' => 'input'
				),
				'account' => array(
					'name' => __('Compte Market', __FILE__),
					'type' => 'market_connection'
				),
				'no_ssl_verify' => array(
					'name' => __('Pas de validation SSL (non recommandé)', __FILE__),
					'type' => 'checkbox'
				),
				'cloud::backup::name' => array(
					'name' => __('[Backup cloud] Nom du dossier de backup', __FILE__),
					'type' => 'input'
				),
				'cloud::backup::password' => array(
					'name' => __('[Backup cloud] Mot de passe', __FILE__),
					'type' => 'password'
				),
				'cloud::backup::password_confirmation' => array(
					'name' => __('[Backup cloud] Mot de passe (confirmation)', __FILE__),
					'type' => 'password'
				),
				'cloud::monitoring::disable' => array(
					'name' => __('[Monitoring cloud] Désactiver', __FILE__),
					'type' => 'checkbox'
				)
			),
			'parameters_for_add' => array(
				'version' => array(
					'name' => __('Version à installer', __FILE__),
					'type' => 'select',
					'options' => array(
						'stable' => __('Stable', __FILE__),
						'beta' => __('Beta', __FILE__),
					),
					'tooltip' => __("Sélectionner la version du plugin à installer", __FILE__)
				),
			),
		);
	}

	public static function pullInstall() {
		$results = marketClient::request('GET', '/boxes/{boxId}/plugin-installations');
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
		} catch (\Throwable $e) {
			$market = new repo_market();
			$market->setLogicalId($_update->getLogicalId());
			$market->setType($_update->getType());
		}
		try {
			if (is_object($market)) {
				$market->remove();
			}
		} catch (\Throwable $e) {
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

	public static function backup_createFolderIsNotExist() {
		return marketClient::backup_createFolderIsNotExist();
	}

	public static function backup_send($_path) {
		return marketClient::backup_send($_path);
	}

	public static function backup_clean($_path) {
		return marketClient::backup_clean($_path);
	}

	public static function backup_list() {
		return marketClient::backup_list();
	}

	public static function backup_restore($_backup) {
		return marketClient::backup_restore($_backup);
	}

	/*     * ***********************CRON*************************** */

	public static function cronHourly() {
		return marketClient::cronHourly();
	}

	public static function cron5() {
		return marketClient::cron5();
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
				} catch (\Throwable $e) {
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
		} catch (\Throwable $e) {
			log::add('market', 'debug', __('Erreur repo_market::getinfo :', __FILE__) . ' ' . $e->getMessage());
			$return['status'] = 'ok';
		}
		return $return;
	}

	/*     * ***********************UTILS*************************** */

	public static function saveTicket($_ticket) {
		return marketClient::saveTicket($_ticket);
	}

	public static function supportAccess($_enable = true, $_key = '') {
		return marketClient::supportAccess($_enable, $_key);
	}

	public static function test() {
		return marketClient::synchronize();
	}

	public static function connect($login, $password, $twoFactorCode = '') {
		return marketClient::connect($login, $password, $twoFactorCode);
	}

	public static function connectionStatus() {
		return marketClient::status();
	}

	public static function disconnect() {
		return marketClient::disconnect();
	}

	public static function getPurchaseInfo() {
		return marketClient::getPurchaseInfo();
	}

	public static function distinctCategorie($_type) {
		return marketClient::distinctCategorie($_type);
	}

	/* JSON-RPC compatibility for plugins. Core and cloud operations use marketClient. */

	public static function getPassword() {
		$password = config::byKey('market::password');
		if (!is_sha1($password)) {
			return sha1($password);
		}
		return $password;
	}

	public static function getJsonRpc() {
		if (config::byKey('market::username') === '' || config::byKey('market::password') === '') {
			throw new Exception(__('Connectez votre compte Market depuis la configuration de Jeedom.', __FILE__), 401);
		}
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
		marketClient::applyBoxInformation($_result);
	}

	/**
	 *
	 * @param array $_arrayMarket
	 * @return self|null
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
		try {
			return self::fromRest(marketClient::request('GET', '/catalog/items/' . rawurlencode((string) $_id)));
		} catch (Exception $exception) {
			if ($exception->getCode() === 404) {
				return null;
			}
			throw $exception;
		}
	}

	public static function byLogicalId($_logicalId) {
		$multiple = is_array($_logicalId);
		$references = $multiple ? array_values($_logicalId) : array($_logicalId);
		$items = self::lookup($references, false);
		return $multiple ? $items : ($items[$_logicalId] ?? null);
	}

	public static function byLogicalIdAndType($_logicalId, $_type = '') {
		$multiple = is_array($_logicalId);
		$references = $multiple ? array_values($_logicalId) : array(array('logicalId' => $_logicalId, 'type' => $_type));
		$items = self::lookup($references, true);
		return $multiple ? $items : ($items[$_type . $_logicalId] ?? null);
	}

	public static function byMe() {
		return self::catalogList('/me/catalog/items');
	}

	public static function byStatusAndType($_status, $_type) {
		return self::byFilter(array('status' => $_status, 'type' => $_type));
	}

	public static function byStatus($_status) {
		return self::byFilter(array('status' => $_status));
	}

	public static function byFilter($_filter) {
		$query = array_intersect_key($_filter, array_flip(array('name', 'type', 'categorie', 'certification', 'compatibility', 'cost', 'logicalId', 'author', 'timeState')));
		$query = array_filter($query, static function ($value) { return $value !== null && $value !== ''; });
		$query['branch'] = $_filter['status'] ?? 'stable';
		if ($query['branch'] === 'pro') {
			$query['branch'] = 'stable';
		}
		$limit = !empty($query['timeState']) ? 20 : null;
		return self::catalogList('/catalog/items', $query, $limit);
	}

	private static function catalogList($path, $query = array(), $maximum = null) {
		$items = array();
		$query['limit'] = $maximum === null ? 100 : min(100, $maximum);
		$query['offset'] = 0;
		do {
			$page = marketClient::request('GET', $path . '?' . http_build_query($query));
			foreach ($page['items'] as $item) {
				$items[] = self::fromRest($item);
			}
			$next = $page['nextOffset'] ?? null;
			if ($next !== null && (!is_int($next) || $next <= $query['offset'])) {
				throw new Exception(__('Pagination Market invalide.', __FILE__));
			}
			$query['offset'] = $next;
		} while ($next !== null && ($maximum === null || count($items) < $maximum));
		return $items;
	}

	private static function lookup($references, $typed) {
		$items = array();
		foreach (array_chunk($references, 100) as $batch) {
			$page = marketClient::request('POST', '/catalog/items/lookup', array('references' => $batch));
			foreach ($page['items'] as $match) {
				$reference = $batch[$match['index']];
				$key = is_array($reference) ? (($typed ? $reference['type'] : '') . $reference['logicalId']) : $reference;
				$items[$key] = self::fromRest($match['item']);
			}
		}
		return $items;
	}

	private static function fromRest($item) {
		$dates = $status = $parameters = array();
		foreach ($item['versions'] ?? array() as $branch => $version) {
			$dates[$branch] = $version['publishedAt'];
			$status[$branch] = !empty($version['published']) ? 1 : 0;
			$parameters['os' . ucfirst($branch) . 'Min'] = $version['minOsVersion'];
			$parameters['os' . ucfirst($branch) . 'Max'] = $version['maxOsVersion'];
		}
		$parameters['minJeedomVersion'] = $item['minJeedomVersion'] ?? '';
		$parameters['size'] = $item['size'] ?? '';
		return self::construct(array(
			'id' => $item['id'], 'name' => $item['name'], 'type' => $item['type'], 'logicalId' => $item['logicalId'],
			'description' => $item['description'], 'categorie' => $item['categorie'], 'author' => $item['author'],
			'cost' => $item['cost'], 'realCost' => $item['realCost'], 'purchase' => $item['purchase'],
			'certification' => $item['certification'], 'utilization' => $item['utilization'],
			'doc' => $item['documentation'], 'changelog' => $item['changelog'], 'datetime' => $dates, 'status' => $status,
			'allowVersion' => array_keys($item['versions'] ?? array()),
			'rating' => array('average' => $item['rating'], 'total' => $item['ratingCount'], 'user' => $item['userRating']),
			'img' => array('icon' => ltrim($item['iconUrl'] ?? '', '/'), 'screenshot' => array_map(static function ($path) { return ltrim($path, '/'); }, $item['screenshots'] ?? array())),
			'parameters' => $parameters, 'user_id' => $item['userId'] ?? 0, 'version' => $item['version'] ?? '',
			'downloaded' => $item['downloaded'] ?? 0, 'buyer' => 0, 'updateBy' => 'market', 'private' => $item['private'] ?? false,
			'isAuthor' => $item['isAuthor'] ?? false, 'nbInstall' => $item['nbInstall'] ?? 0,
			'link' => $item['links'] ?? array(), 'language' => $item['languages'] ?? array(),
			'hardwareCompatibility' => $item['hardwareCompatibility'] ?? array()
		));
	}

	/*     * *********************Methode d'instance************************* */

	public function setRating($_rating) {
		marketClient::request('PUT', '/catalog/items/' . rawurlencode((string) $this->getId()) . '/rating', array('rating' => (float) $_rating));
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

		$path = '/catalog/items/' . rawurlencode((string) $this->getId()) . '/download?' . http_build_query(array(
			'branch' => $_version === 'pro' ? 'stable' : $_version,
			'jeedomVersion' => jeedom::version(), 'osVersion' => system::getOsVersion()
		));
		log::add('update', 'alert', __('Téléchargement de', __FILE__) . ' ' . $this->getLogicalId() . '...');
		marketClient::request('GET', $path, null, 1200, $tmp);
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
		throw new Exception(__('La publication depuis une archive locale n’est plus disponible. Publiez votre plugin depuis son dépôt GitHub sur le Market.', __FILE__));
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
		$_key = $_key === 'pro' ? 'stable' : $_key;
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
		$_key = $_key === 'pro' ? 'stable' : $_key;
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

/** Market REST session, transport and cloud services. */
class marketClient {

	/* Cloud backups. */

	public static function backup_createFolderIsNotExist() {
		$request_http = new com_http(config::byKey('service::backup::url') . '/webdav/' . urlencode(config::byKey('market::username')), config::byKey('market::username'), config::byKey('market::password'));
		$request_http->setCURLOPT(array(
			CURLOPT_CUSTOMREQUEST => "PROPFIND"
		));
		$xml = simplexml_load_string($request_http->exec(10));
		$ns = $xml->getNamespaces(true);
		$child = $xml->children($ns['D']);
		$found = false;
		foreach ($child->response as $file) {
			if ($file->propstat->prop->getcontenttype) {
				continue;
			}
			$folder = trim(trim(str_replace('http://backup.jeedom.com/webdav/' . urlencode(config::byKey('market::username')), '', $file->href), '/'));
			$folder = trim(trim(str_replace('http://backup.jeedom.com/webdav/' . config::byKey('market::username'), '', $folder), '/'));
			if ($folder == '') {
				continue;
			}
			if ($folder == config::byKey('market::cloud::backup::name')) {
				$found = true;
				break;
			}
		}
		if (!$found) {
			$request_http = new com_http(config::byKey('service::backup::url') . '/webdav/' . urlencode(config::byKey('market::username')) . '/' . rawurldecode(config::byKey('market::cloud::backup::name')), config::byKey('market::username'), config::byKey('market::password'));
			$request_http->setCURLOPT(array(
				CURLOPT_CUSTOMREQUEST => "MKCOL"
			));
			$request_http->exec(10);
		}
	}

	public static function backup_send($_path) {
		if (!config::byKey('service::backup::enable')) {
			throw new Exception(__('Erreur d\'envoi du backup au cloud. Avez-vous bien un abonnement au backup cloud ?', __FILE__));
		}
		if (config::byKey('market::cloud::backup::password') == '') {
			throw new Exception(__('Vous devez obligatoirement avoir un mot de passe pour le backup cloud (allez dans Réglages -> Système -> Configuration puis onglet Mise à jour/Market)', __FILE__));
		}
		if (config::byKey('market::cloud::backup::password') != config::byKey('market::cloud::backup::password_confirmation')) {
			throw new Exception(__('Le mot de passe du backup cloud n\'est pas identique à la confirmation', __FILE__));
		}
		//self::backup_clean($_path);
		self::backup_createFolderIsNotExist();
		try {
			if (!file_exists('/tmp/jeedom_gnupg')) {
				mkdir('/tmp/jeedom_gnupg');
			}
			com_shell::execute('sudo chmod 777 -R /tmp/jeedom_gnupg');
			$cmd = 'echo "' . config::byKey('market::cloud::backup::password') . '" | gpg --homedir /tmp/jeedom_gnupg --batch --yes --passphrase-fd 0 -c ' . $_path;
			com_shell::execute($cmd);
			$cmd = "curl --retry 5 --retry-delay 3 --retry-max-time 60 --retry-connrefused --keepalive-time 30 --user '" . config::byKey('market::username') . ":" . config::byKey('market::password') . "' -T '" . $_path . '.gpg' . "' '" . config::byKey('service::backup::url') . '/webdav/' . urlencode(config::byKey('market::username')) . '/' . rawurldecode(config::byKey('market::cloud::backup::name')) . "/'";
			try {
				com_shell::execute($cmd);
			} catch (\Exception $e) {
				$cmd = "curl --http1.1 --retry 5 --retry-delay 3 --retry-max-time 60 --retry-connrefused --keepalive-time 30 --user '" . config::byKey('market::username') . ":" . config::byKey('market::password') . "' -T '" . $_path . '.gpg' . "' '" . config::byKey('service::backup::url') . '/webdav/' . urlencode(config::byKey('market::username')) . '/' . rawurldecode(config::byKey('market::cloud::backup::name')) . "/'";
				com_shell::execute($cmd);
			}
			unlink($_path . '.gpg');
			rrmdir('/tmp/jeedom_gnupg');
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
		$limit = 3700;
		self::backup_createFolderIsNotExist();

		$request_http = new com_http(config::byKey('service::backup::url') . '/webdav/' . urlencode(config::byKey('market::username')), config::byKey('market::username'), config::byKey('market::password'));
		$request_http->setCURLOPT(array(
			CURLOPT_CUSTOMREQUEST => "PROPFIND"
		));
		$xml = simplexml_load_string($request_http->exec(10));
		$ns = $xml->getNamespaces(true);
		$child = $xml->children($ns['D']);
		$total_size = 0;
		$files = [];
		foreach ($child->response as $file) {
			if (!$file->propstat->prop->getcontenttype) {
				continue;
			}
			$files[] = array(
				'href' => (string) $file->href,
				'name' => (string) $file->propstat->prop->displayname,
				'size' => (int) $file->propstat->prop->getcontentlength,
				'timestamp' => strtotime($file->propstat->prop->getlastmodified)
			);
			$total_size += $file->propstat->prop->getcontentlength;
		}
		if (($total_size / 1024 / 1024) < $limit - (filesize($_path) / 1024 / 1024)) {
			return;
		}
		echo __('Besoin de faire de la place sur le stockage distant', __FILE__) . "\n";
		if (!empty($files)) {
			usort($files, function ($a, $b) {
				return $a["timestamp"] - $b["timestamp"];
			});
		}
		$nb = 0;
		while (($total_size / 1024 / 1024) > $limit - (filesize($_path) / 1024 / 1024)) {
			if (count($files) == 0) {
				throw new \Exception(__('Pas assez de place et aucun backup à supprimer', __FILE__));
			}
			$file = array_shift($files);
			echo __('Supression du backup cloud :', __FILE__) . ' ' . $file['name'] . "\n";
			$request_http = new com_http($file['href'], config::byKey('market::username'), config::byKey('market::password'));
			$request_http->setCURLOPT(array(
				CURLOPT_CUSTOMREQUEST => "DELETE"
			));
			$request_http->exec(10);
			$total_size -= $file['size'];
			$nb++;
			if ($nb > 100) {
				throw new \Exception(__('Erreur lors du nettoyage des backups cloud, supression > 100', __FILE__));
			}
		}
	}

	public static function backup_list() {
		if (!config::byKey('service::backup::enable') || config::byKey('market::cloud::backup::password') == '') {
			return array();
		}
		self::backup_createFolderIsNotExist();
		$request_http = new com_http(config::byKey('service::backup::url') . '/webdav/' . urlencode(config::byKey('market::username')) . '/' . rawurldecode(config::byKey('market::cloud::backup::name')), config::byKey('market::username'), config::byKey('market::password'));
		$request_http->setCURLOPT(array(
			CURLOPT_CUSTOMREQUEST => "PROPFIND"
		));
		$xml = simplexml_load_string($request_http->exec(10));
		$ns = $xml->getNamespaces(true);
		$child = $xml->children($ns['D']);
		$files = array();
		foreach ($child->response as $file) {
			if (!$file->propstat->prop->getcontenttype) {
				continue;
			}
			$files[] = array(
				'name' => (string) $file->propstat->prop->displayname,
				'timestamp' => strtotime($file->propstat->prop->getlastmodified)
			);
		}
		usort($files, function ($a, $b) {
			return $a['timestamp'] <=> $b['timestamp'];
		});
		$result = array();
		foreach ($files as $file) {
			$result[] = $file['name'];
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

	public static function cronHourly() {
		if (!self::connected()) {
			return;
		}
		if (strtotime(config::byKey('market::lastCommunication', 'core', 0)) > (strtotime('now') - (24 * 3600))) {
			return;
		}
		sleep(rand(0, 1800));
		try {
			self::synchronize();
		} catch (Exception $e) {
		}
	}

	public static function cron5() {
		try {
			if (config::byKey('service::monitoring::enable') && config::byKey('cloud::monitoring::disable', 'core', 0) == 0) {
				sleep(rand(1, 120));
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
					if ($result == null || $result['state'] != 'ok') {
						sleep(rand(5, 45));
						$result = json_decode($request_http->exec(60, 1), true);
					}
					if ($result == null || $result['state'] != 'ok') {
						log::add('monitoring_cloud', 'debug', __('Erreur sur le monitoring cloud :', __FILE__) . ' ' . json_encode($result));
					}
				} catch (\Exception $e) {
					log::add('monitoring_cloud', 'debug', __('Erreur sur le monitoring cloud :', __FILE__) . ' ' . $e->getMessage());
				}
			}
		} catch (Exception $e) {
		}
	}

	public static function saveTicket($_ticket) {
		if (!empty($_ticket['openSupport'])) {
			user::supportAccess(true);
		}
		$messages = array_column($_ticket['messages'] ?? array(), 'message');
		self::request('POST', '/support/tickets', array(
			'type' => ($_ticket['type'] ?? 'support') === 'support' ? 'technical' : ($_ticket['type'] ?? ''),
			'subject' => (string) ($_ticket['title'] ?? ''),
			'message' => implode("\n\n", $messages) ?: (string) ($_ticket['message'] ?? ''),
			'category' => (string) ($_ticket['category'] ?? 'core')
		), 300);
		return 'ok';
	}

	public static function supportAccess($_enable = true, $_key = '') {
		$data = array('enabled' => (bool) $_enable);
		if ($_enable) {
			$data['url'] = network::getNetworkAccess('external') . '/index.php?auth=' . $_key;
		}
		self::request('PUT', '/boxes/{boxId}/support-access', $data);
	}

	public static function synchronize() {
		$internalIp = '';
		$internalUrl = '';
		try {
			$internalIp = network::getNetworkAccess('internal', 'ip');
			$internalUrl = network::getNetworkAccess('internal');
		} catch (Exception $exception) {
		}
		$data = array(
			'version' => jeedom::version(), 'localIp' => filter_var($internalIp, FILTER_VALIDATE_IP) ? $internalIp : '',
			'internalUrl' => self::boxUrl($internalUrl),
			'apiKey' => jeedom::getApiKey('apimarket'),
			'information' => array('nbMessage' => message::nbMessage(), 'nbUpdate' => update::nbNeedUpdate(),
				'hardware' => jeedom::getHardwareName(), 'uname' => (string) shell_exec('uname -a'), 'language' => config::byKey('language')),
			'plugins' => array_values(plugin::listPlugin(false, false, false, true))
		);
		$name = trim((string) config::byKey('name'));
		// Leave the Market name unchanged when the local name cannot be synchronized.
		if ($name !== '' && mb_strlen($name) <= 127 && preg_match('/[\p{Cc}<>"\x{10000}-\x{10FFFF}]/u', $name) === 0) {
			$data['name'] = $name;
		}
		if (config::byKey('market::allowDNS') != 1 || config::byKey('network::disableMangement') == 1) {
			$data['url'] = self::boxUrl(network::getNetworkAccess('external'));
		}
		$result = self::request('PUT', '/boxes/{boxId}/telemetry', $data);
		$information = ($result['dns'] ?? array()) + ($result['services'] ?? array());
		$information['register::id'] = $result['boxId'];
		$information['jeedom::url'] = $result['url'];
		if (!empty($result['broadcast'])) {
			$information['broadcast::id'] = $result['broadcast']['id'];
			$information['broadcast::message'] = $result['broadcast']['message'];
		}
		config::save('market::allowBeta', !empty($result['allowBeta']) ? 1 : 0);
		self::applyBoxInformation($information);
		return 'ok';
	}

	private static function boxUrl($url) {
		return is_string($url) && strlen($url) <= 511 && filter_var($url, FILTER_VALIDATE_URL)
			&& in_array(strtolower(parse_url($url, PHP_URL_SCHEME)), array('http', 'https'), true)
			&& parse_url($url, PHP_URL_USER) === null && parse_url($url, PHP_URL_PASS) === null
			&& strpbrk($url, '<>"\\') === false ? $url : '';
	}

	public static function getPurchaseInfo() {
		$account = self::request('GET', '/me');
		return array('user_id' => $account['id']);
	}

	public static function distinctCategorie($_type) {
		$result = self::request('GET', '/catalog/categories?' . http_build_query(array('type' => $_type)));
		return $result['items'];
	}

	public static function applyBoxInformation($_result) {
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
			if (array_key_exists('dns::frp::servers', $_result)) {
				$servers = frp::servers($_result['dns::frp::servers']);
				if (config::byKey('dns::frp::servers', 'core', array()) != $servers) {
					config::save('dns::frp::servers', $servers);
					$restart_dns = $restart_dns || config::byKey('dns::mode') == 'frp';
				}
			}
			if (isset($_result['register::vpnurl']) && config::byKey('dns::vpnurl') != $_result['register::vpnurl']) {
				config::save('dns::vpnurl', $_result['register::vpnurl']);
				$restart_dns = true;
			}
			if (isset($_result['register::vpnPort']) && config::byKey('vpn::port') != $_result['register::vpnPort']) {
				config::save('vpn::port', $_result['register::vpnPort']);
				$restart_dns = true;
			}
			if (isset($_result['register::vpnProtocol']) && config::byKey('vpn::protocol') != $_result['register::vpnProtocol']) {
				config::save('vpn::protocol', $_result['register::vpnProtocol']);
			}
			if (isset($_result['dns::remote']) && config::byKey('dns::remote') != $_result['dns::remote']) {
				config::save('dns::remote', $_result['dns::remote']);
				$restart_dns = true;
			} elseif (!isset($_result['dns::remote']) && config::byKey('dns::remote') != '') {
				config::save('dns::remote', '');
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
			if (config::byKey('market::allowDNS') == 1 && isset($_result['jeedom::url']) && config::byKey('jeedom::url') != $_result['jeedom::url']) {
				config::save('jeedom::url', $_result['jeedom::url']);
			}
			if ($restart_dns && config::byKey('market::allowDNS') == 1) {
				network::dns_start();
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

	public static function connected() {
		$session = config::byKey('market::restSession', 'core', array(), true);
		if (!is_array($session) || !is_string($session['context'] ?? null) || !is_string($session['refreshToken'] ?? null)
			|| $session['refreshToken'] === '') {
			return false;
		}
		try {
			return hash_equals($session['context'], self::context());
		} catch (Exception $exception) {
			return false;
		}
	}

	public static function status() {
		return array('connected' => self::connected(), 'login' => (string) config::byKey('market::username', 'core', '', true));
	}

	public static function disconnect() {
		return self::locked(function () {
			$stored = config::byKey('market::restSession', 'core', array(), true);
			$revoked = !is_array($stored) || empty($stored['refreshToken']);
			try {
				if (self::connected()) {
					$session = self::activeSession(null, 10);
					self::send('DELETE', '/boxes/' . $session['boxId'] . '/session', null, $session['accessToken'], 10);
					$revoked = true;
				}
			} catch (Exception $exception) {
				$revoked = $exception->getCode() === 401 || $exception->getCode() === 404;
			} finally {
				config::remove('market::restSession');
				config::remove('market::password');
				config::remove('market::allowBeta');
			}
			return self::status() + array('revoked' => $revoked);
		});
	}

	public static function connect($login, $password, $twoFactorCode = '') {
		self::openSession($login, $password, $twoFactorCode);
		try {
			self::synchronize();
		} catch (Exception $exception) {
			if (!self::connected()) {
				throw $exception;
			}
			return self::status() + array('warning' => __('Compte connecté. La synchronisation avec le Market a échoué :', __FILE__) . ' ' . $exception->getMessage());
		}
		return self::status();
	}

	private static function openSession($login, $password, $twoFactorCode) {
		if (!is_string($login) || trim($login) === '' || !is_string($password) || $password === '' || !is_string($twoFactorCode)) {
			throw new Exception(__('Renseignez votre identifiant et votre mot de passe Market.', __FILE__));
		}
		return self::locked(function () use ($login, $password, $twoFactorCode) {
			$data = array('login' => trim($login), 'password' => $password, 'hardwareKey' => jeedom::getHardwareKey());
			if ($twoFactorCode !== '') {
				$data['twoFactorCode'] = trim($twoFactorCode);
			}
			$tokens = self::send('POST', '/box-sessions', $data);
			// Keep the existing password hash for plugins and the separate cloud services.
			config::save('market::username', trim($login));
			config::save('market::password', sha1($password));
			self::store($tokens);
			return true;
		});
	}

	public static function request($method, $path, $data = null, $timeout = 30, $destination = null) {
		$session = self::session();
		for ($attempt = 0; $attempt < 2; ++$attempt) {
			try {
				$result = self::send($method, str_replace('{boxId}', (string) $session['boxId'], $path), $data,
					$session['accessToken'], $timeout, $destination);
				config::save('market::lastCommunication', date('Y-m-d H:i:s'));
				return $result;
			} catch (Exception $exception) {
				// Authentication middleware rejects the request before executing its action.
				if ($exception->getCode() !== 401 || $attempt !== 0) {
					throw $exception;
				}
				$session = self::session($session['accessToken']);
			}
		}
	}

	private static function session($rejectedToken = null) {
		return self::locked(function () use ($rejectedToken) { return self::activeSession($rejectedToken); });
	}

	private static function activeSession($rejectedToken = null, $timeout = 30) {
		// Another PHP worker may have renewed the pair while this one waited for the lock.
		$session = config::byKey('market::restSession', 'core', array(), true);
		if (!is_array($session) || !isset($session['context'], $session['refreshToken'], $session['accessToken'], $session['boxId'])
			|| !hash_equals($session['context'], self::context())) {
			throw self::reconnect();
		}
		if (($session['expiresAt'] ?? 0) > time() + 60
			&& ($rejectedToken === null || !hash_equals($session['accessToken'], $rejectedToken))) {
			return $session;
		}
		if (empty($session['refreshAttempt'])) {
			$session['refreshAttempt'] = bin2hex(random_bytes(16));
			config::save('market::restSession', $session);
		}
		try {
			$tokens = self::send('POST', '/box-sessions/refresh', array('refreshToken' => $session['refreshToken']),
				null, $timeout, null, array('Idempotency-Key: ' . $session['refreshAttempt']));
		} catch (Exception $exception) {
			if ($exception->getCode() === 401) {
				config::remove('market::restSession');
				throw self::reconnect();
			}
			// Keep the same refresh token and attempt key if the response was lost.
			throw $exception;
		}
		return self::store($tokens);
	}

	private static function store($tokens) {
		if (!is_array($tokens) || !isset($tokens['boxId'], $tokens['accessToken'], $tokens['refreshToken'], $tokens['expiresIn'])
			|| !is_int($tokens['boxId']) || $tokens['boxId'] < 1 || !is_int($tokens['expiresIn']) || $tokens['expiresIn'] < 0
			|| !is_string($tokens['accessToken']) || !is_string($tokens['refreshToken'])
			|| !preg_match('/\Abox_a_' . $tokens['boxId'] . '_[a-f0-9]{64}\z/', $tokens['accessToken'])
			|| !preg_match('/\Abox_r_' . $tokens['boxId'] . '_[a-f0-9]{64}\z/', $tokens['refreshToken'])) {
			throw new Exception(__('Réponse de connexion Market invalide.', __FILE__));
		}
		$session = array('boxId' => $tokens['boxId'], 'accessToken' => $tokens['accessToken'], 'refreshToken' => $tokens['refreshToken'],
			'expiresAt' => time() + $tokens['expiresIn'], 'context' => self::context());
		config::save('market::restSession', $session);
		config::save('register::id', $session['boxId']);
		return $session;
	}

	private static function locked($operation) {
		$lock = fopen(jeedom::getTmpFolder('market') . '/session.lock', 'c');
		if ($lock === false) {
			throw new Exception(__('Impossible de verrouiller la connexion Market.', __FILE__));
		}
		try {
			if (!flock($lock, LOCK_EX)) {
				throw new Exception(__('Impossible de verrouiller la connexion Market.', __FILE__));
			}
			return $operation();
		} finally {
			flock($lock, LOCK_UN);
			fclose($lock);
		}
	}

	private static function baseUrl() {
		$url = rtrim((string) config::byKey('market::address', 'core', '', true), '/');
		$parts = parse_url($url);
		if (!is_array($parts) || strtolower($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])
			|| isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])) {
			throw new Exception(__('L’adresse du Market doit être une URL HTTPS valide.', __FILE__));
		}
		return $url;
	}

	private static function context() {
		return hash('sha256', self::baseUrl() . "\0" . config::byKey('market::username', 'core', '', true) . "\0" . jeedom::getHardwareKey());
	}

	private static function reconnect() {
		return new Exception(__('Reconnectez votre compte Market depuis Configuration > Mises à jour/Market avec votre mot de passe et votre code de double authentification si activé.', __FILE__), 401);
	}

	private static function send($method, $path, $data = null, $token = null, $timeout = 30, $destination = null, $headers = array()) {
		$http = new com_http(self::baseUrl() . '/api/v1' . $path);
		$http->setNoSslCheck(config::byKey('market::no_ssl_verify') == 1);
		$http->setNoReportError(true);
		$headers[] = 'Accept: ' . ($destination === null ? 'application/json' : 'application/zip, application/json');
		if ($token !== null) {
			$headers[] = 'Authorization: Bearer ' . $token;
		}
		$options = array(CURLOPT_CUSTOMREQUEST => $method, CURLOPT_FOLLOWLOCATION => false, CURLOPT_CONNECTTIMEOUT => 15);
		if ($data !== null) {
			$headers[] = 'Content-Type: application/json';
			$options[CURLOPT_POSTFIELDS] = json_encode($data, JSON_THROW_ON_ERROR);
		}
		$http->setHeader($headers);
		if (config::byKey('proxyEnabled') == 1) {
			$options[CURLOPT_PROXY] = config::byKey('proxyAddress');
			$options[CURLOPT_PROXYPORT] = config::byKey('proxyPort');
			$options[CURLOPT_PROXYUSERPWD] = config::byKey('proxyLogin') . ':' . config::byKey('proxyPassword');
		}
		$file = null;
		if ($destination !== null) {
			$file = fopen($destination, 'wb');
			if ($file === false) {
				throw new Exception(__('Impossible d’écrire le téléchargement Market.', __FILE__));
			}
			$options[CURLOPT_FILE] = $file;
		}
		try {
			$http->setCURLOPT($options);
			// Session rotation and non-idempotent actions must not be retried by the transport.
			$body = $http->exec($timeout, 1);
			$status = (int) $http->getHttpCode();
			if (is_resource($file)) {
				fclose($file);
				$file = null;
			}
			if ($body === false) {
				throw new Exception(__('Le Market n’a pas répondu.', __FILE__));
			}
			if ($destination !== null && $status >= 200 && $status < 300) {
				$zip = new ZipArchive();
				if ($zip->open($destination, ZipArchive::CHECKCONS) !== true) {
					throw new Exception(__('L’archive reçue du Market est invalide.', __FILE__));
				}
				$zip->close();
				return $destination;
			}
			if ($destination !== null) {
				$body = file_get_contents($destination, false, null, 0, 65536);
			}
			$result = json_decode((string) $body, true);
			if ($status < 200 || $status >= 300) {
				$message = is_string($result['error']['message'] ?? null) ? $result['error']['message'] : __('Erreur de communication avec le Market.', __FILE__);
				throw new Exception($message, $status);
			}
			if ($status === 204) {
				return null;
			}
			if (!is_array($result)) {
				throw new Exception(__('Réponse REST du Market invalide.', __FILE__));
			}
			return $result;
		} catch (Throwable $exception) {
			if ($destination !== null && is_file($destination)) {
				unlink($destination);
			}
			throw $exception;
		} finally {
			if (is_resource($file)) {
				fclose($file);
			}
		}
	}
}
