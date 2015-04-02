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

class update {
	/*     * *************************Attributs****************************** */

	private $id;
	private $type;
	private $logicalId;
	private $name;
	private $localVersion;
	private $remoteVersion;
	private $status;
	private $configuration;

	/*     * ***********************Méthodes statiques*************************** */

	public static function checkAllUpdate($_filter = '', $_findNewObject = true) {
		$findCore = false;
		$marketObject = array(
			'logical_id' => array(),
			'version' => array(),
		);
		if ($_findNewObject) {
			self::findNewUpdateObject();
		}
		$updates = self::all($_filter);
		if (is_array($updates)) {
			foreach (self::all($_filter) as $update) {
				if ($update->getType() == 'core') {
					if ($findCore) {
						$update->remove();
						continue;
					}
					$findCore = true;
					$update->setType('core');
					$update->setLogicalId('jeedom');
					if (method_exists('jeedom', 'version')) {
						$update->setLocalVersion(jeedom::version());
					} else {
						$update->setLocalVersion(getVersion('jeedom'));
					}
					$update->save();
					$update->checkUpdate();
				} else {
					if ($update->getStatus() != 'hold') {
						$marketObject['logical_id'][] = array('logicalId' => $update->getLogicalId(), 'type' => $update->getType());
						$marketObject['version'][] = $update->getConfiguration('version', 'stable');
						$marketObject[$update->getType() . $update->getLogicalId()] = $update;
					}
				}
			}
		}
		if (!$findCore) {
			$update = new update();
			$update->setType('core');
			$update->setLogicalId('jeedom');
			if (method_exists('jeedom', 'version')) {
				$update->setLocalVersion(jeedom::version());
			} else {
				$update->setLocalVersion(getVersion('jeedom'));
			}
			$update->save();
			$update->checkUpdate();
		}
		$markets_infos = market::getInfo($marketObject['logical_id'], $marketObject['version']);
		foreach ($markets_infos as $logicalId => $market_info) {
			$update = $marketObject[$logicalId];
			if (is_object($update)) {
				$update->setStatus($market_info['status']);
				$update->setConfiguration('market_owner', $market_info['market_owner']);
				$update->setConfiguration('market', $market_info['market']);
				$update->setRemoteVersion($market_info['datetime']);
				$update->save();
			}
		}
		config::save('update::lastCheck', date('Y-m-d H:i:s'));
	}

	public static function makeUpdateLevel($_mode = '', $_level = 1, $_system = 'no') {
		jeedom::update($_mode, $_level, $_system);
	}

	public static function updateAll($_filter = '') {
		if ($_filter == 'core') {
			foreach (self::byType($_filter) as $update) {
				$update->doUpdate();
			}
		} else {
			$error = false;
			if ($_filter == '') {
				$updates = self::all();
			} else {
				$updates = self::byType($_filter);
			}
			if (is_array($updates)) {
				foreach ($updates as $update) {
					if ($update->getStatus() != 'hold' && $update->getStatus() == 'update') {
						if ($update->getType() != 'core') {
							try {
								$update->doUpdate();
							} catch (Exception $e) {
								log::add('update', 'update', $e->getMessage());
								$error = true;
							}
						}
					}
				}
			}
			return $error;
		}
	}

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byLogicalId($_logicalId) {
		$values = array(
			'logicalId' => $_logicalId,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE logicalId=:logicalId';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byType($_type) {
		$values = array(
			'type' => $_type,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE type=:type';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byTypeAndLogicalId($_type, $_logicalId) {
		$values = array(
			'logicalId' => $_logicalId,
			'type' => $_type,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`
		WHERE logicalId=:logicalId
		AND type=:type';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	/**
	 *
	 * @return array de tous les utilisateurs
	 */
	public static function all($_filter = '') {
		$values = array();
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM `update`';
		if ($_filter != '') {
			$values['type'] = $_filter;
			$sql .= ' WHERE `type`=:type';
		}
		$sql .= ' ORDER BY FIELD( `status`, "update","ok","depreciated") ASC,FIELD( `type`,"plugin","core") DESC, `name` ASC';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function nbNeedUpdate() {
		$sql = 'SELECT count(*)
		FROM `update`
		WHERE `status`="update"';
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
		return $result['count(*)'];
	}

	public static function findNewUpdateObject() {
		foreach (plugin::listPlugin() as $plugin) {
			$plugin_id = $plugin->getId();
			$update = self::byTypeAndLogicalId('plugin', $plugin_id);
			if (!is_object($update)) {
				$update = new update();
				$update->setLogicalId($plugin_id);
				$update->setType('plugin');
				$update->setLocalVersion(date('Y-m-d H:i:s'));
				$update->save();
			}
			$find = array();
			if (method_exists($plugin_id, 'listMarketObject')) {
				foreach ($plugin_id::listMarketObject() as $logical_id) {
					$find[$logical_id] = true;
					$update = self::byTypeAndLogicalId($plugin_id, $logical_id);
					if (!is_object($update)) {
						$update = new update();
						$update->setLogicalId($logical_id);
						$update->setType($plugin_id);
						$update->setLocalVersion(date('Y-m-d H:i:s'));
						$update->save();
					}
				}
				foreach (self::byType($plugin_id) as $update) {
					if (!isset($find[$update->getLogicalId()])) {
						$update->remove();
					}
				}
			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function checkUpdate() {
		if ($this->getType() == 'core') {
			$update_info = jeedom::needUpdate(true);
			$this->setLocalVersion($update_info['version']);
			$this->setRemoteVersion($update_info['currentVersion']);
			if ($update_info['needUpdate']) {
				$this->setStatus('update');
			} else {
				$this->setStatus('ok');
			}
			$this->save();
		} else {
			try {
				$market_info = market::getInfo(array('logicalId' => $this->getLogicalId(), 'type' => $this->getType()), $this->getConfiguration('version', 'stable'));
				$this->setStatus($market_info['status']);
				$this->setConfiguration('market_owner', $market_info['market_owner']);
				$this->setConfiguration('market', $market_info['market']);
				$this->setRemoteVersion($market_info['datetime']);
				$this->save();
			} catch (Exception $ex) {

			}
		}
	}

	public function preSave() {
		if ($this->getLogicalId() == '') {
			throw new Exception(__('Le logical ID ne peut pas être vide', __FILE__));
		}
		if ($this->getLocalVersion() == '') {
			throw new Exception(__('La version locale ne peut pas être vide', __FILE__));
		}
		if ($this->getName() == '') {
			$this->setName($this->getLogicalId());
		}
	}

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
	}

	public function refresh() {
		DB::refresh($this);
	}

	public function doUpdate() {
		if ($this->getType() == 'core') {
			jeedom::update();
		} else {
			$market = market::byLogicalIdAndType($this->getLogicalId(), $this->getType());
			if (is_object($market)) {
				$market->install($this->getConfiguration('version', 'stable'));
			}
		}
		$this->refresh();
		$this->checkUpdate();
	}

	public function deleteObjet() {
		if ($this->getType() == 'core') {
			throw new Exception('Vous ne pouvez pas supprimer le core de Jeedom');
		} else {
			try {
				$market = market::byLogicalIdAndType($this->getLogicalId(), $this->getType());
			} catch (Exception $e) {
				$market = new market();
				$market->setLogicalId($this->getLogicalId());
				$market->setType($this->getType());
			}
			try {
				if (is_object($market)) {
					$market->remove();
				}
			} catch (Exception $e) {
			}
			$this->remove();
		}
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setConfiguration($_key, $_value) {
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getLocalVersion() {
		return $this->localVersion;
	}

	public function getRemoteVersion() {
		return $this->remoteVersion;
	}

	public function setLocalVersion($localVersion) {
		$this->localVersion = $localVersion;
	}

	public function setRemoteVersion($remoteVersion) {
		$this->remoteVersion = $remoteVersion;
	}

	public function getLogicalId() {
		return $this->logicalId;
	}

	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
	}

}

?>
