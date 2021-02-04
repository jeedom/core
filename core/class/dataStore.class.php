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

class dataStore {
	/*     * *************************Attributs****************************** */

	private $id;
	private $type;
	private $link_id;
	private $key;
	private $value;
	private $_changed;

	/*     * ***********************Méthodes statiques*************************** */

    /**
     * @param $_id
     * @return array|null
     * @throws Exception
     */
    public static function byId($_id): ?array
    {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM dataStore
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_type
     * @param $_link_id
     * @param $_key
     * @return array|null
     * @throws Exception
     */
    public static function byTypeLinkIdKey($_type, $_link_id, $_key): ?array
    {
		$values = array(
			'type' => $_type,
			'link_id' => $_link_id,
			'key' => $_key,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM dataStore
		WHERE `type`=:type
		AND `link_id`=:link_id
		AND `key`=:key
		ORDER BY `key`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_type
     * @param string $_link_id
     * @return array|null
     * @throws Exception
     */
    public static function byTypeLinkId($_type, $_link_id = ''): ?array
    {
		$values = array(
			'type' => $_type,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM dataStore
		WHERE type=:type';
		if ($_link_id != '') {
			$values['link_id'] = $_link_id;
			$sql .= ' AND link_id=:link_id';
		}
		$sql .= ' ORDER BY `key`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_type
     * @param $_link_id
     * @return bool
     * @throws Exception
     */
    public static function removeByTypeLinkId($_type, $_link_id): bool
    {
		$datastores = self::byTypeLinkId($_type, $_link_id);
		foreach ($datastores as $datastore) {
			$datastore->remove();
		}
		return true;
	}

	/*     * *********************Méthodes d'instance************************* */

    /**
     * @return bool
     * @throws Exception
     */
    public function preSave(): bool
    {
		$allowType = array('cmd', 'object', 'eqLogic', 'scenario');
		if (!in_array($this->getType(), $allowType)) {
			throw new Exception(__('Le type doit être un des suivants : ', __FILE__) . print_r($allowType, true));
		}
		if (!is_numeric($this->getLink_id())) {
			throw new Exception(__('Link_id doit être un chiffre', __FILE__));
		}
		if ($this->getKey() == '') {
			throw new Exception(__('La clef ne peut pas être vide', __FILE__));
		}
		if ($this->getId() == '') {
			$dataStore = self::byTypeLinkIdKey($this->getType(), $this->getLink_id(), $this->getKey());
			if (is_object($dataStore)) {
				$this->setId($dataStore->getId());
			}
		}
		return true;
	}

    /**
     * @return bool
     */
    public function save(): bool
    {
		DB::save($this);
		return true;
	}

    /**
     * @throws Exception
     */
    public function postSave() {
		scenario::check('variable(' . $this->getKey().')');
		$value_cmd =	cmd::byValue('variable(' . $this->getKey(), null, true);
		if (is_array($value_cmd)) {
			foreach ($value_cmd as $cmd) {
				if ($cmd->getType() != 'info') {
					continue;
				}
				$cmd->event($cmd->execute());
			}
		}
	}

	public function remove() {
		DB::remove($this);
	}

    /**
     * @param array[] $_data
     * @param int $_level
     * @param null $_drill
     * @return array[]
     * @throws Exception
     */
    public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = null): array
    {
		if ($_drill == null) {
			$_drill = config::byKey('graphlink::dataStore::drill');
		}
		if (isset($_data['node']['dataStore' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = findCodeIcon('fa-code');
		$_data['node']['dataStore' . $this->getId()] = array(
			'id' => 'dataStore' . $this->getId(),
			'type' => __('Variable',__FILE__),
			'name' => $this->getKey(),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontsize' => '1.5em',
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'texty' => -14,
			'textx' => 0,
			'title' => __('Variable :', __FILE__) . ' ' . $this->getKey(),
		);
		$usedBy = $this->getUsedBy();
		addGraphLink($this, 'dataStore', $usedBy['scenario'], 'scenario', $_data, $_level, $_drill);
		addGraphLink($this, 'dataStore', $usedBy['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'dataStore', $usedBy['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'dataStore', $usedBy['interactDef'], 'interactDef', $_data, $_level, $_drill);
		return $_data;
	}

    /**
     * @param false $_array
     * @return array[]
     * @throws Exception
     */
    public function getUsedBy($_array = false): array
    {
		$return = array('cmd' => array(), 'eqLogic' => array(), 'interactDef' => array(), 'scenario' => array());
		$return['cmd'] = cmd::searchConfiguration(array('"cmd":"variable"%"name":"' . $this->getKey() . '"', 'variable(' . $this->getKey() . ')', 'variable(' . $this->getKey() . ',', '"name":"' . $this->getKey() . '"%"cmd":"variable"'));
		$return['eqLogic'] = eqLogic::searchConfiguration(array('"cmd":"variable"%"name":"' . $this->getKey() . '"', 'variable(' . $this->getKey() . ')', 'variable(' . $this->getKey() . ',', '"name":"' . $this->getKey() . '"%"cmd":"variable"'));
		$return['interactDef'] = interactDef::searchByUse(array('"cmd":"variable"%"name":"' . $this->getKey() . '"', 'variable(' . $this->getKey() . ')', 'variable(' . $this->getKey() . ',', '"name":"' . $this->getKey() . '"%"cmd":"variable"'));
		$return['scenario'] = scenario::searchByUse(array(
			array('action' => 'variable(' . $this->getKey() . ')', 'option' => 'variable(' . $this->getKey() . ')'),
			array('action' => 'variable(' . $this->getKey() . ',', 'option' => 'variable(' . $this->getKey() . ','),
			array('action' => 'variable', 'option' => $this->getKey(), 'and' => true),
			array('action' => 'ask', 'option' => $this->getKey(), 'and' => true),
		));
		if ($_array) {
			foreach ($return as &$value) {
				$value = utils::o2a($value);
			}
		}
		return $return;
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * @return mixed
     */
    public function getId() {
		return $this->id;
	}

    /**
     * @param $_id
     * @return $this
     */
    public function setId($_id): dataStore
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getType() {
		return $this->type;
	}

    /**
     * @param $_type
     * @return $this
     */
    public function setType($_type): dataStore
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
		$this->type = $_type;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getLink_id() {
		return $this->link_id;
	}

    /**
     * @param $_link_id
     * @return $this
     */
    public function setLink_id($_link_id): dataStore
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->link_id,$_link_id);
		$this->link_id = $_link_id;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getKey() {
		return $this->key;
	}

    /**
     * @param $_key
     * @return $this
     */
    public function setKey($_key): dataStore
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->key,$_key);
		$this->key = $_key;
		return $this;
	}

    /**
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getValue($_default = '') {
		if ($this->value === '') {
			return $_default;
		}
		return is_json($this->value, $this->value);
	}

    /**
     * @param $_value
     * @return $this
     */
    public function setValue($_value): dataStore
    {
		if (is_object($_value) || is_array($_value)) {
			$_value = json_encode($_value, JSON_UNESCAPED_UNICODE);
		}
		$this->_changed = utils::attrChanged($this->_changed,$this->value,$_value);
		$this->value = $_value;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getChanged() {
		return $this->_changed;
	}

    /**
     * @param $_changed
     * @return $this
     */
    public function setChanged($_changed): dataStore
    {
		$this->_changed = $_changed;
		return $this;
	}

}
