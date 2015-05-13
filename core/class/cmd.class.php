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

class cmd {
	/*     * *************************Attributs****************************** */

	protected $id;
	protected $logicalId;
	protected $eqType;
	protected $name;
	protected $order;
	protected $type;
	protected $subType;
	protected $eqLogic_id;
	protected $isHistorized = 0;
	protected $unite = '';
	protected $cache;
	protected $eventOnly = 0;
	protected $configuration;
	protected $template;
	protected $display;
	protected $_collectDate = '';
	protected $value = null;
	protected $isVisible = 1;
	protected $_eqLogic = null;
	private static $_templateArray = array();

	/*     * ***********************Méthodes statiques*************************** */

	private static function cast($_inputs, $_eqLogic = null) {
		if (is_object($_inputs) && class_exists($_inputs->getEqType() . 'Cmd')) {
			if ($_eqLogic != null) {
				$_inputs->_eqLogic = $_eqLogic;
			}
			return cast($_inputs, $_inputs->getEqType() . 'Cmd');
		}
		if (is_array($_inputs)) {
			$return = array();
			foreach ($_inputs as $input) {
				if ($_eqLogic != null) {
					$input->_eqLogic = $_eqLogic;
				}
				$return[] = self::cast($input);
			}
			return $return;
		}
		return $_inputs;
	}

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE id=:id';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		ORDER BY id';
		$results = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
		$return = array();
		foreach ($results as $result) {
			$return[] = self::byId($result['id']);
		}
		return $return;
	}

	public static function allHistoryCmd($_notEventOnly = false) {
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE isHistorized=1
		AND type=\'info\'';
		if ($_notEventOnly) {
			$sql .= ' AND eventOnly=0';
		}
		$sql .= ' ORDER BY ob.name,el.name,c.name';
		$result1 = self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE el.object_id IS NULL
		AND isHistorized=1
		AND type=\'info\'';
		if ($_notEventOnly) {
			$sql .= ' AND eventOnly=0';
		}
		$sql .= ' ORDER BY el.name,c.name';
		$result2 = self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		return array_merge($result1, $result2);
	}

	public static function byEqLogicId($_eqLogic_id, $_type = null, $_visible = null, $_eqLogic = null) {
		$values = array(
			'eqLogic_id' => $_eqLogic_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id';
		if ($_type != null) {
			$values['type'] = $_type;
			$sql .= ' AND `type`=:type';
		}
		if ($_visible != null) {
			$sql .= ' AND `isVisible`=1';
		}
		$sql .= ' ORDER BY `order`,`name`';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__), $_eqLogic);
	}

	public static function byLogicalId($_logical_id, $_type = null) {
		$values = array(
			'logicalId' => $_logical_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE logicalId=:logicalId';
		if ($_type != null) {
			$values['type'] = $_type;
			$sql .= ' AND `type`=:type';
		}
		$sql .= ' ORDER BY `order`';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function searchConfiguration($_configuration, $_eqType = null) {
		$values = array(
			'configuration' => '%' . $_configuration . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE configuration LIKE :configuration';
		if ($_eqType != null) {
			$values['eqType'] = $_eqType;
			$sql .= ' AND eqType=:eqType ';
		}
		$sql .= ' ORDER BY name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function searchConfigurationEqLogic($_eqLogic_id, $_configuration, $_type = null) {
		$values = array(
			'configuration' => '%' . $_configuration . '%',
			'eqLogic_id' => $_eqLogic_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id';
		if ($_type != null) {
			$values['type'] = $_type;
			$sql .= ' AND type=:type ';
		}
		$sql .= ' AND configuration LIKE :configuration';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function searchTemplate($_template, $_eqType = null, $_type = null, $_subtype = null) {
		$values = array(
			'template' => '%' . $_template . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE template LIKE :template';
		if ($_eqType != null) {
			$values['eqType'] = $_eqType;
			$sql .= ' AND eqType=:eqType ';
		}
		if ($_type != null) {
			$values['type'] = $_type;
			$sql .= ' AND type=:type ';
		}
		if ($_subtype != null) {
			$values['subType'] = $_subtype;
			$sql .= ' AND subType=:subType ';
		}
		$sql .= ' ORDER BY name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_multiple = false) {
		$values = array(
			'eqLogic_id' => $_eqLogic_id,
			'logicalId' => $_logicalId,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND logicalId=:logicalId';
		if ($_multiple) {
			return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byValue($_value, $_type = null, $_onlyEnable = false) {
		$values = array(
			'value' => $_value,
			'search' => '%#' . $_value . '#%',
		);

		if ($_onlyEnable) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE ( value=:value OR value LIKE :search)
			AND el.isEnable=1
			AND c.id!=:value';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' AND c.type=:type ';
			}
		} else {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE ( value=:value OR value LIKE :search)
			AND id!=:value';
			if ($_type != null) {
				$values['type'] = $_type;
				$sql .= ' AND type=:type ';
			}
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byTypeEqLogicNameCmdName($_eqType_name, $_eqLogic_name, $_cmd_name) {
		$values = array(
			'eqType_name' => $_eqType_name,
			'eqLogic_name' => $_eqLogic_name,
			'cmd_name' => $_cmd_name,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE c.name=:cmd_name
		AND el.name=:eqLogic_name
		AND el.eqType_name=:eqType_name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byEqLogicIdCmdName($_eqLogic_id, $_cmd_name) {
		$values = array(
			'eqLogic_id' => $_eqLogic_id,
			'cmd_name' => $_cmd_name,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		WHERE c.name=:cmd_name
		AND c.eqLogic_id=:eqLogic_id';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byObjectNameEqLogicNameCmdName($_object_name, $_eqLogic_name, $_cmd_name) {
		$values = array(
			'eqLogic_name' => $_eqLogic_name,
			'cmd_name' => html_entity_decode($_cmd_name),
		);

		if ($_object_name == __('Aucun', __FILE__)) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND el.object_id IS NULL';
		} else {
			$values['object_name'] = $_object_name;
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			INNER JOIN object ob ON el.object_id=ob.id
			WHERE c.name=:cmd_name
			AND el.name=:eqLogic_name
			AND ob.name=:object_name';
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byObjectNameCmdName($_object_name, $_cmd_name) {
		$values = array(
			'object_name' => $_object_name,
			'cmd_name' => $_cmd_name,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE c.name=:cmd_name
		AND ob.name=:object_name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byTypeSubType($_type, $_subType = '') {
		$values = array(
			'type' => $_type,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		WHERE c.type=:type';
		if ($_subType != '') {
			$values['subtype'] = $_subType;
			$sql .= ' AND c.subtype=:subtype';
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function collect() {
		$cmd = null;
		foreach (cache::search('collect') as $cache) {
			$cmd = self::byId($cache->getValue());
			if (is_object($cmd) && $cmd->getEqLogic()->getIsEnable() == 1 && $cmd->getEventOnly() == 0) {
				$cmd->execCmd(null, 0);
			}
		}
	}

	public static function cmdToHumanReadable($_input) {
		if (is_object($_input)) {
			$reflections = array();
			$uuid = spl_object_hash($_input);
			if (!isset($reflections[$uuid])) {
				$reflections[$uuid] = new ReflectionClass($_input);
			}
			$reflection = $reflections[$uuid];
			$properties = $reflection->getProperties();
			foreach ($properties as $property) {
				$property->setAccessible(true);
				$value = $property->getValue($_input);
				$property->setValue($_input, self::cmdToHumanReadable($value));
				$property->setAccessible(false);
			}
			return $_input;
		}
		if (is_array($_input)) {
			foreach ($_input as $key => $value) {
				$_input[$key] = self::cmdToHumanReadable($value);
			}
			return $_input;
		}
		$text = $_input;
		preg_match_all("/#([0-9]*)#/", $text, $matches);
		foreach ($matches[1] as $cmd_id) {
			if (is_numeric($cmd_id)) {
				$cmd = self::byId($cmd_id);
				if (is_object($cmd)) {
					$text = str_replace('#' . $cmd_id . '#', '#' . $cmd->getHumanName() . '#', $text);
				}
			}
		}
		return $text;
	}

	public static function humanReadableToCmd($_input) {
		$isJson = false;
		if (is_json($_input)) {
			$isJson = true;
			$_input = json_decode($_input, true);
		}
		if (is_object($_input)) {
			$reflections = array();
			$uuid = spl_object_hash($_input);
			if (!isset($reflections[$uuid])) {
				$reflections[$uuid] = new ReflectionClass($_input);
			}
			$reflection = $reflections[$uuid];
			$properties = $reflection->getProperties();
			foreach ($properties as $property) {
				$property->setAccessible(true);
				$value = $property->getValue($_input);
				$property->setValue($_input, self::humanReadableToCmd($value));
				$property->setAccessible(false);
			}
			return $_input;
		}
		if (is_array($_input)) {
			foreach ($_input as $key => $value) {
				$_input[$key] = self::humanReadableToCmd($value);
			}
			if ($isJson) {
				return json_encode($_input, JSON_UNESCAPED_UNICODE);
			}
			return $_input;
		}
		$text = $_input;

		preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $text, $matches);
		if (count($matches) == 4) {
			for ($i = 0; $i < count($matches[0]); $i++) {
				if (isset($matches[1][$i]) && isset($matches[2][$i]) && isset($matches[3][$i])) {
					$cmd = self::byObjectNameEqLogicNameCmdName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
					if (is_object($cmd)) {
						$text = str_replace($matches[0][$i], '#' . $cmd->getId() . '#', $text);
					}
				}
			}
		}

		return $text;
	}

	public static function byString($_string) {
		$cmd = self::byId(str_replace('#', '', self::humanReadableToCmd($_string)));
		if (!is_object($cmd)) {
			throw new Exception(__('La commande n\'a pas pu être trouvée : ', __FILE__) . $_string . __(' => ', __FILE__) . self::humanReadableToCmd($_string));
		}
		return $cmd;
	}

	public static function cmdToValue($_input, $_quote = false) {
		if (is_object($_input)) {
			$reflections = array();
			$uuid = spl_object_hash($_input);
			if (!isset($reflections[$uuid])) {
				$reflections[$uuid] = new ReflectionClass($_input);
			}
			$reflection = $reflections[$uuid];
			$properties = $reflection->getProperties();
			foreach ($properties as $property) {
				$property->setAccessible(true);
				$value = $property->getValue($_input);
				$property->setValue($_input, self::cmdToValue($value, $_quote));
				$property->setAccessible(false);
			}
			return $_input;
		}
		if (is_array($_input)) {
			foreach ($_input as $key => $value) {
				$_input[$key] = self::cmdToValue($value, $_quote);
			}
			return $_input;
		}
		$text = $_input;
		preg_match_all("/#([0-9]*)#/", $text, $matches);
		foreach ($matches[1] as $cmd_id) {
			if (is_numeric($cmd_id)) {
				$cmd = self::byId($cmd_id);
				if (is_object($cmd) && $cmd->getType() == 'info') {
					$cmd_value = $cmd->execCmd(null, 1, true, $_quote);
					if ($cmd->getSubtype() == "string" && substr($cmd_value, 0, 1) != '"' && substr($cmd_value, -1) != '"') {
						$cmd_value = '"' . $cmd_value . '"';
					}
					$text = str_replace('#' . $cmd_id . '#', $cmd_value, $text);
				}
			}
		}
		return $text;
	}

	public static function allType() {
		$sql = 'SELECT distinct(type) as type
		FROM cmd';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
	}

	public static function allSubType($_type = '') {
		$values = array();
		$sql = 'SELECT distinct(subType) as subtype';
		if ($_type != '') {
			$values['type'] = $_type;
			$sql .= ' WHERE type=:type';
		}
		$sql .= ' FROM cmd';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
	}

	public static function allUnite() {
		$sql = 'SELECT distinct(unite) as unite
		FROM cmd';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
	}

	public static function convertColor($_color) {
		$colors = config::byKey('convertColor');
		if (isset($colors[$_color])) {
			return $colors[$_color];
		}
		throw new Exception(__('Impossible de traduire la couleur en code hexadécimal :', __FILE__) . $_color);
	}

	public static function availableWidget($_version) {
		$path = dirname(__FILE__) . '/../template/' . $_version;
		$files = ls($path, 'cmd.*', false, array('files', 'quiet'));
		$return = array();
		foreach ($files as $file) {
			$informations = explode('.', $file);
			if (!isset($return[$informations[1]])) {
				$return[$informations[1]] = array();
			}
			if (!isset($return[$informations[1]][$informations[2]])) {
				$return[$informations[1]][$informations[2]] = array();
			}
			if (isset($informations[3])) {
				$return[$informations[1]][$informations[2]][] = array('name' => $informations[3]);
			}
		}
		foreach (plugin::listPlugin(true) as $plugin) {
			$path = dirname(__FILE__) . '/../../plugins/' . $plugin->getId() . '/core/template/' . $_version;
			$files = ls($path, 'cmd.*', false, array('files', 'quiet'));
			foreach ($files as $file) {
				$informations = explode('.', $file);
				if (count($informations) > 3) {
					if (!isset($return[$informations[1]])) {
						$return[$informations[1]] = array();
					}
					if (!isset($return[$informations[1]][$informations[2]])) {
						$return[$informations[1]][$informations[2]] = array();
					}
					$return[$informations[1]][$informations[2]][] = array('name' => $informations[3]);
				}
			}
		}
		return $return;
	}

	public static function returnState($_options) {
		$cmd = cmd::byId($_options['cmd_id']);
		if (is_object($cmd)) {
			$cmd->event($cmd->getConfiguration('returnStateValue', 0));
		}
	}

	public static function cmdAlert($_options) {
		$cmd = cmd::byId($_options['cmd_id']);
		if (!is_object($cmd)) {
			return;
		}
		$value = $cmd->execCmd();
		$check = jeedom::evaluateExpression($value . $cmd->getConfiguration('jeedomCheckCmdOperator') . $cmd->getConfiguration('jeedomCheckCmdTest'));
		if ($check == 1 || $check || $check == '1') {
			$cmd->executeAlertCmdAction();
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function formatValue($_value, $_quote = false) {
		if (trim($_value) == '' && $_value !== false && $_value !== 0) {
			return '';
		}

		$_value = trim(trim($_value), '"');
		if (@strpos(strtolower($_value), 'error::') !== false) {
			return $_value;
		}
		if ($this->getType() == 'info') {
			switch ($this->getSubType()) {
				case 'string':
					if ($_quote) {
						return '"' . $_value . '"';
					}
					return $_value;
				case 'other':
					if ($_quote) {
						return '"' . $_value . '"';
					}
					return $_value;
				case 'binary':
					if ($this->getConfiguration('calculValueOffset') != '') {
						try {
							$_value = evaluate(str_replace('#value#', $_value, $this->getConfiguration('calculValueOffset')));
						} catch (Exception $ex) {

						}
					}
					$value = strtolower($_value);
					if ($value == 'on' || $value == 'high' || $value == 'true' || $value === true) {
						return 1;
					}
					if ($value == 'off' || $value == 'low' || $value == 'false' || $value === false) {
						return 0;
					}
					if ((is_numeric(intval($_value)) && intval($_value) > 1) || $_value || $_value == 1) {
						return 1;
					}
					return 0;
				case 'numeric':
					if ($this->getConfiguration('calculValueOffset') != '') {
						try {
							$_value = evaluate(str_replace('#value#', $_value, $this->getConfiguration('calculValueOffset')));
						} catch (Exception $ex) {

						}
					}
					if ($this->getConfiguration('historizeRound') !== '' && is_numeric($this->getConfiguration('historizeRound')) && $this->getConfiguration('historizeRound') >= 0) {
						$_value = round($_value, $this->getConfiguration('historizeRound'));
					}
					if ($_value > $this->getConfiguration('maxValue', $_value) && $this->getConfiguration('maxValueReplace') == 1) {
						$_value = $this->getConfiguration('maxValue', $_value);
					}
					if ($_value < $this->getConfiguration('minValue', $_value) && $this->getConfiguration('minValueReplace') == 1) {
						$_value = $this->getConfiguration('minValue', $_value);
					}
					return floatval($_value);
			}
		}
		return $_value;
	}

	public function getLastValue() {
		return $this->getConfiguration('lastCmdValue', null);
	}

	public function dontRemoveCmd() {
		return false;
	}

	public function getTableName() {
		return 'cmd';
	}

	public function save() {
		if ($this->getName() == '') {
			throw new Exception(__('Le nom de la commande ne peut pas être vide :', __FILE__) . print_r($this, true));
		}
		if ($this->getType() == '') {
			throw new Exception($this->getHumanName() . ' ' . __('Le type de la commande ne peut pas être vide :', __FILE__) . print_r($this, true));
		}
		if ($this->getSubType() == '') {
			throw new Exception($this->getHumanName() . ' ' . __('Le sous-type de la commande ne peut pas être vide :', __FILE__) . print_r($this, true));
		}
		if ($this->getEqLogic_id() == '') {
			throw new Exception($this->getHumanName() . ' ' . __('Vous ne pouvez pas créer une commande sans la rattacher à un équipement', __FILE__));
		}
		if ($this->getConfiguration('maxValue') != '' && $this->getConfiguration('minValue') != '' && $this->getConfiguration('minValue') > $this->getConfiguration('maxValue')) {
			throw new Exception($this->getHumanName() . ' ' . __('La valeur minimum de la commande ne peut etre supérieur à la valeur maximum', __FILE__));
		}
		if ($this->getEqType() == '') {
			$this->setEqType($this->getEqLogic()->getEqType_name());
		}
		DB::save($this);
		$mc = cache::byKey('cmd' . $this->getId());
		if ($mc->getLifetime() != $this->getCacheLifetime()) {
			$mc->remove();
		}
		$this->getEqLogic()->emptyCacheWidget();
		return true;
	}

	public function refresh() {
		DB::refresh($this);
	}

	public function remove() {
		viewData::removeByTypeLinkId('cmd', $this->getId());
		dataStore::removeByTypeLinkId('cmd', $this->getId());
		$this->getEqLogic()->emptyCacheWidget();
		$this->emptyHistory();
		return DB::remove($this);
	}

	public function execute($_options = array()) {
		return false;
	}

	/**
	 *
	 * @param type $_options
	 * @param type $cache 0 = ignorer le cache , 1 = mode normal, 2 = cache utilisé même si expiré (puis marqué à recollecter)
	 * @return command result
	 * @throws Exception
	 */
	public function execCmd($_options = null, $cache = 1, $_sendNodeJsEvent = true, $_quote = false) {
		if ($this->getEventOnly() == 1) {
			$cache = 2;
		}
		if ($this->getType() == 'info' && $cache != 0) {
			$mc = cache::byKey('cmd' . $this->getId(), ($cache == 2) ? true : false);
			if ($cache == 2 || !$mc->hasExpired()) {
				if ($mc->hasExpired()) {
					$this->setCollect(1);
				}
				$this->setCollectDate($mc->getOptions('collectDate', $mc->getDatetime()));
				return $mc->getValue();
			}
		}
		$eqLogic = $this->getEqLogic();
		if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
			throw new Exception(__('Equipement désactivé - impossible d\'exécuter la commande : ' . $this->getHumanName(), __FILE__));
		}
		$eqLogic->emptyCacheWidget();
		try {
			if ($_options !== null && $_options !== '') {
				$options = self::cmdToValue($_options);
				if (is_json($_options)) {
					$options = json_decode($_options, true);
				}
			} else {
				$options = null;
			}
			if (isset($options['color'])) {
				$options['color'] = str_replace('"', '', $options['color']);
			}
			if ($this->getSubType() == 'color' && isset($options['color']) && substr($options['color'], 0, 1) != '#') {
				$options['color'] = cmd::convertColor($options['color']);
			}
			if ($this->getType() == 'action') {
				log::add('event', 'event', __('Execution de la commande ', __FILE__) . $this->getHumanName() . __(' avec les paramètres ', __FILE__) . str_replace(array("\n", '  ', 'Array'), '', print_r($options, true)));
			}
			$value = $this->formatValue($this->execute($options), $_quote);
		} catch (Exception $e) {
			//Si impossible de contacter l'équipement
			$type = $eqLogic->getEqType_name();
			if ($eqLogic->getConfiguration('nerverFail') != 1) {
				$numberTryWithoutSuccess = $eqLogic->getStatus('numberTryWithoutSuccess', 0);
				$eqLogic->setStatus('numberTryWithoutSuccess', $numberTryWithoutSuccess);
				if ($numberTryWithoutSuccess >= config::byKey('numberOfTryBeforeEqLogicDisable')) {
					$message = 'Désactivation de <a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getName();
					$message .= '</a> car il n\'a pas répondu ou mal répondu lors des 3 derniers essais';
					message::add($type, $message);
					$eqLogic->setIsEnable(0);
					$eqLogic->save();
				}
			}
			log::add($type, 'error', __('Erreur sur ', __FILE__) . $eqLogic->getName() . ' : ' . $e->getMessage());
			throw $e;
		}
		if ($this->getType() == 'info' && $value !== false) {
			if ($this->getCollectDate() == '') {
				$this->setCollectDate(date('Y-m-d H:i:s'));
			}
			cache::set('cmd' . $this->getId(), $value, $this->getCacheLifetime(), array('collectDate' => $this->getCollectDate()));
			$this->setCollect(0);
			$nodeJs = array(
				array(
					'cmd_id' => $this->getId(),
				),
			);
			foreach (self::byValue($this->getId()) as $cmd) {
				$nodeJs[] = array('cmd_id' => $cmd->getId());
			}
			nodejs::pushUpdate('eventCmd', $nodeJs);
		}
		if ($this->getType() != 'action' && !is_array($value) && strpos($value, 'error') === false) {
			if ($eqLogic->getStatus('numberTryWithoutSuccess') != 0) {
				$eqLogic->setStatus('numberTryWithoutSuccess', 0);
			}
			$eqLogic->setStatus('lastCommunication', date('Y-m-d H:i:s'));
		}
		if ($this->getType() == 'action' && $options !== null && $this->getValue() == '') {
			if (isset($options['slider'])) {
				$this->setConfiguration('lastCmdValue', $options['slider']);
				$this->save();
			}
			if (isset($options['color'])) {
				$this->setConfiguration('lastCmdValue', $options['color']);
				$this->save();
			}
		}
		if ($this->getType() == 'action' && $this->getConfiguration('updateCmdId') != '') {
			$cmd = cmd::byId($this->getConfiguration('updateCmdId'));
			if (is_object($cmd)) {
				$value = $this->getConfiguration('updateCmdToValue');
				switch ($this->getSubType()) {
					case 'slider':
						$value = str_replace('#slider#', $_options['slider'], $value);
						break;
					case 'color':
						$value = str_replace('#color#', $_options['color'], $value);
						break;
				}
				$cmd->event($value);
			}
		}
		return $value;
	}

	public function toHtml($_version = 'dashboard', $options = '', $_cmdColor = null, $_cache = 2) {
		$version = jeedom::versionAlias($_version);
		$html = '';
		$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.' . $this->getTemplate($version, 'default');
		$template = '';
		if (!isset(self::$_templateArray[$version . '::' . $template_name])) {
			if ($this->getTemplate($version, 'default') != 'default') {
				if (config::byKey('active', 'widget') == 1) {
					$template = getTemplate('core', $version, $template_name, 'widget');
				}
				if ($template == '') {
					foreach (plugin::listPlugin(true) as $plugin) {
						$template = getTemplate('core', $version, $template_name, $plugin->getId());
						if ($template != '') {
							break;
						}
					}
				}
				if ($template == '' && config::byKey('active', 'widget') == 1 && config::byKey('market::autoInstallMissingWidget') == 1) {
					try {
						$market = market::byLogicalIdAndType(str_replace('cmd.', '', $version . '.' . $template_name), 'widget');
						if (is_object($market)) {
							$market->install();
							$template = getTemplate('core', $version, $template_name, 'widget');
						}
					} catch (Exception $e) {
						$this->setTemplate($version, 'default');
						$this->save();
					}
				}
				if ($template == '') {
					$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.default';
					$template = getTemplate('core', $version, $template_name);
				}
			} else {
				$template = getTemplate('core', $version, $template_name);
			}
			self::$_templateArray[$version . '::' . $template_name] = $template;
		} else {
			$template = self::$_templateArray[$version . '::' . $template_name];
		}
		$replace = array(
			'#id#' => $this->getId(),
			'#name#' => ($this->getDisplay('icon') != '') ? $this->getDisplay('icon') : $this->getName(),
			'#name_display#' => ($this->getDisplay('icon') != '') ? $this->getDisplay('icon') : $this->getName(),
			'#history#' => '',
			'#displayHistory#' => 'display : none;',
			'#unite#' => $this->getUnite(),
			'#minValue#' => $this->getConfiguration('minValue', 0),
			'#maxValue#' => $this->getConfiguration('maxValue', 100),
			'#logicalId#' => $this->getLogicalId(),
		);
		if ($_cmdColor == null && $version != 'scenario') {
			$eqLogic = $this->getEqLogic();
			$vcolor = ($version == 'mobile') ? 'mcmdColor' : 'cmdColor';
			if ($eqLogic->getPrimaryCategory() == '') {
				$replace['#cmdColor#'] = jeedom::getConfiguration('eqLogic:category:default:' . $vcolor);
			} else {
				$replace['#cmdColor#'] = jeedom::getConfiguration('eqLogic:category:' . $eqLogic->getPrimaryCategory() . ':' . $vcolor);
			}
		} else {
			$replace['#cmdColor#'] = $_cmdColor;
		}
		if ($this->getDisplay('doNotShowNameOnView') == 1 && ($_version == 'dview' || $_version == 'mview')) {
			$replace['#name_display#'] = '';
			$replace['#name#'] = '';
		} else if ($this->getDisplay('doNotShowNameOnDashboard') == 1 && ($_version == 'mobile' || $_version == 'dashboard')) {
			$replace['#name_display#'] = '';
			$replace['#name#'] = '';
		} else {
			$replace['#name_display#'] .= '<br/>';
		}
		if ($this->getType() == 'info') {
			$replace['#state#'] = '';
			$replace['#tendance#'] = '';
			$replace['#state#'] = $this->execCmd(null, $_cache);
			if (strpos($replace['#state#'], 'error::') !== false) {
				$template = getTemplate('core', $version, 'cmd.error');
				$replace['#state#'] = str_replace('error::', '', $replace['#state#']);
			} else {
				if ($this->getSubType() == 'binary' && $this->getDisplay('invertBinary') == 1) {
					$replace['#state#'] = ($replace['#state#'] == 1) ? 0 : 1;
				}
			}
			$replace['#collectDate#'] = $this->getCollectDate();
			if ($this->getIsHistorized() == 1) {
				$replace['#history#'] = 'history cursor';

				if (config::byKey('displayStatsWidget') == 1 && strpos($template, '#displayHistory#') !== false) {
					$showStat = true;
					if ($this->getDisplay('doNotShowStatOnDashboard') == 1 && $_version == 'dashboard') {
						$showStat = false;
					}
					if ($this->getDisplay('doNotShowStatOnView') == 1 && ($_version == 'dview' || $_version == 'mview')) {
						$showStat = false;
					}
					if ($this->getDisplay('doNotShowStatOnMobile') == 1 && $_version == 'mobile') {
						$showStat = false;
					}
					if ($showStat) {
						$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . config::byKey('historyCalculPeriod') . ' hour'));
						$replace['#displayHistory#'] = '';
						$historyStatistique = $this->getStatistique($startHist, date('Y-m-d H:i:s'));
						$replace['#averageHistoryValue#'] = round($historyStatistique['avg'], 1);
						$replace['#minHistoryValue#'] = round($historyStatistique['min'], 1);
						$replace['#maxHistoryValue#'] = round($historyStatistique['max'], 1);
						$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . config::byKey('historyCalculTendance') . ' hour'));
						$tendance = $this->getTendance($startHist, date('Y-m-d H:i:s'));
						if ($tendance > config::byKey('historyCalculTendanceThresholddMax')) {
							$replace['#tendance#'] = 'fa fa-arrow-up';
						} else if ($tendance < config::byKey('historyCalculTendanceThresholddMin')) {
							$replace['#tendance#'] = 'fa fa-arrow-down';
						} else {
							$replace['#tendance#'] = 'fa fa-minus';
						}
					}
				}
			}
			$parameters = $this->getDisplay('parameters');
			if (is_array($parameters)) {
				foreach ($parameters as $key => $value) {
					$replace['#' . $key . '#'] = $value;
				}
			}
			return template_replace($replace, $template);
		} else {
			$cmdValue = $this->getCmdValue();
			if (is_object($cmdValue) && $cmdValue->getType() == 'info') {
				$replace['#state#'] = $cmdValue->execCmd(null, 2);
				$replace['#valueName#'] = $cmdValue->getName();
				$replace['#unite#'] = $cmdValue->getUnite();
			} else {
				$replace['#state#'] = ($this->getLastValue() != null) ? $this->getLastValue() : '';
				$replace['#valueName#'] = $this->getName();
				$replace['#unite#'] = $this->getUnite();
			}
			$parameters = $this->getDisplay('parameters');
			if (is_array($parameters)) {
				foreach ($parameters as $key => $value) {
					$replace['#' . $key . '#'] = $value;
				}
			}
			$replace['#valueName#'] .= '<br/>';
			$html .= template_replace($replace, $template);
			if (trim($html) == '') {
				return $html;
			}
			if ($options != '') {
				$options = self::cmdToHumanReadable($options);
				if (is_json($options)) {
					$options = json_decode($options, true);
				}
				if (is_array($options)) {
					foreach ($options as $key => $value) {
						$replace['#' . $key . '#'] = $value;
					}
					$html = template_replace($replace, $html);
				}
			}
			return $html;
		}
	}

	public function event($_value, $_loop = 1) {
		if (trim($_value) === '' || $_loop > 4 || $this->getType() != 'info') {
			return;
		}
		$collectDate = ($this->getCollectDate() != '') ? $this->getCollectDate() : date('Y-m-d H:i:s');
		$eqLogic = $this->getEqLogic();
		if (!is_object($eqLogic) || $eqLogic->getIsEnable() == 0) {
			return;
		}
		$_loop++;
		$value = $this->formatValue($_value);
		if ($this->getConfiguration('onlyChangeEvent', 0) == 1 && $this->getEventOnly() == 1 && $value == $this->execCmd(null, 2)) {
			return;
		}
		$this->setCollectDate($collectDate);
		log::add('event', 'event', __('Evenement sur la commande ', __FILE__) . $this->getHumanName() . __(' valeur : ', __FILE__) . $_value);
		cache::set('cmd' . $this->getId(), $value, $this->getCacheLifetime(), array('collectDate' => $this->getCollectDate()));
		scenario::check($this);
		$this->setCollect(0);
		$eqLogic->emptyCacheWidget();
		$nodeJs = array(array('cmd_id' => $this->getId()));
		$foundInfo = false;
		foreach (self::byValue($this->getId(), null, true) as $cmd) {
			if ($cmd->getType() == 'action') {
				$nodeJs[] = array('cmd_id' => $cmd->getId());
			} else {
				if ($_loop > 1) {
					$cmd->event($cmd->execute(), $_loop);
				} else {
					$foundInfo = true;
				}
			}
		}
		nodejs::pushUpdate('eventCmd', $nodeJs);
		if ($foundInfo) {
			listener::backgroundCalculDependencyCmd($this->getId());
		}
		listener::check($this->getId(), $value);
		if (strpos($_value, 'error') === false) {
			$eqLogic->setStatus('lastCommunication', $this->getCollectDate());
			$this->addHistoryValue($value, $this->getCollectDate());
		}
		$this->checkReturnState($value);
		$this->checkCmdAlert($_value);
		$this->pushUrl($_value);
	}

	public function checkReturnState($_value) {
		if (is_numeric($this->getConfiguration('returnStateTime')) && $this->getConfiguration('returnStateTime') > 0 && $_value != $this->getConfiguration('returnStateValue') && trim($this->getConfiguration('returnStateValue')) != '') {
			$cron = cron::byClassAndFunction('cmd', 'returnState', array('cmd_id' => intval($this->getId())));
			if (!is_object($cron)) {
				$cron = new cron();
			}
			$cron->setClass('cmd');
			$cron->setFunction('returnState');
			$cron->setOnce(1);
			$cron->setOption(array('cmd_id' => intval($this->getId())));
			$next = strtotime('+ ' . ($this->getConfiguration('returnStateTime') + 1) . ' minutes ' . date('Y-m-d H:i:s'));
			$schedule = date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next);
			$cron->setSchedule($schedule);
			$cron->setLastRun(date('Y-m-d H:i:s'));
			$cron->save();
		}
	}

	public function checkCmdAlert($_value) {
		if ($this->getConfiguration('jeedomCheckCmdOperator') == '' || $this->getConfiguration('jeedomCheckCmdTest') == '' || $this->getConfiguration('jeedomCheckCmdTime') == '' || is_nan($this->getConfiguration('jeedomCheckCmdTime'))) {
			return;
		}
		$check = jeedom::evaluateExpression($_value . $this->getConfiguration('jeedomCheckCmdOperator') . $this->getConfiguration('jeedomCheckCmdTest'));
		if ($check == 1 || $check || $check == '1') {
			if ($this->getConfiguration('jeedomCheckCmdTime') == 0) {
				$this->executeAlertCmdAction();
				return;
			}
			$cron = cron::byClassAndFunction('cmd', 'cmdAlert', array('cmd_id' => intval($this->getId())));
			if (!is_object($cron)) {
				$cron = new cron();
			}
			$cron->setClass('cmd');
			$cron->setFunction('cmdAlert');
			$cron->setOnce(1);
			$cron->setOption(array('cmd_id' => intval($this->getId())));
			$next = strtotime('+ ' . ($this->getConfiguration('jeedomCheckCmdTime') + 1) . ' minutes ' . date('Y-m-d H:i:s'));
			$schedule = date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next);
			$cron->setSchedule($schedule);
			$cron->setLastRun(date('Y-m-d H:i:s'));
			$cron->save();
		} else {
			$cron = cron::byClassAndFunction('cmd', 'cmdAlert', array('cmd_id' => intval($this->getId())));
			if (is_object($cron)) {
				$cron->remove();
			}
		}
	}

	public function executeAlertCmdAction() {
		if ($this->getConfiguration('jeedomCheckCmdActionType') == 'cmd') {
			$cmd = cmd::byId(str_replace('#', '', $this->getConfiguration('jeedomCheckCmdCmdActionId')));
			if (!is_object($cmd)) {
				return;
			}
			$cmd->execCmd($this->getConfiguration('jeedomCheckCmdCmdActionOption'));
		}
		if ($this->getConfiguration('jeedomCheckCmdActionType') == 'scenario') {
			$scenario = scenario::byId($this->getConfiguration('jeedomCheckCmdScenarioActionId'));
			if (!is_object($scenario)) {
				return;
			}
			switch ($this->getOptions('jeedomCheckCmdScenarioActionMode')) {
				case 'start':
					$scenario->launch(false, __('Lancement direct provoqué par le scénario  : ', __FILE__) . $this->getHumanName());
					break;
				case 'stop':
					$scenario->stop();
					break;
				case 'deactivate':
					$scenario->setIsActive(0);
					$scenario->save();
					break;
				case 'activate':
					$scenario->setIsActive(1);
					$scenario->save();
					break;
			}
		}
	}

	public function pushUrl($_value) {
		$url = $this->getConfiguration('jeedomPushUrl');
		if ($url == '') {
			$url = config::byKey('cmdPushUrl');
		}
		if ($url == '') {
			return;
		}
		$replace = array(
			'#value#' => $_value,
			'#cmd_name#' => $this->getName(),
			'#cmd_id#' => $this->getId(),
			'#humanname#' => $this->getHumanName(),
		);
		$url = str_replace(array_keys($replace), $replace, $url);
		log::add('event', 'event', __('Appels de l\'url de push pour la commande ', __FILE__) . $this->getHumanName() . ' : ' . $url);
		$http = new com_http($url);
		$http->setLogError(false);
		try {
			$http->exec();
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur push sur : ', __FILE__) . $url . ' => ' . $e->getMessage());
		}
	}

	public function invalidCache() {
		$mc = cache::byKey('cmd' . $this->getId());
		$mc->invalid();
	}

	public function emptyHistory($_date = '') {
		return history::emptyHistory($this->getId(), $_date);
	}

	public function addHistoryValue($_value, $_datetime = '') {
		if ($_value !== '' && $this->getIsHistorized() == 1 && $this->getType() == 'info' && $_value <= $this->getConfiguration('maxValue', $_value) && $_value >= $this->getConfiguration('minValue', $_value)) {
			$hitory = new history();
			$hitory->setCmd_id($this->getId());
			$hitory->setValue($_value);
			$hitory->setDatetime($_datetime);
			return $hitory->save($this);
		}
	}

	public function getUsedBy() {
		$return = array();
		$return['cmd'] = self::searchConfiguration('#' . $this->getId() . '#');
		$return['eqLogic'] = eqLogic::searchConfiguration('#' . $this->getId() . '#');
		$return['scenario'] = scenario::byUsedCommand($this->getId());
		$return['interact'] = interactDef::byUsedCommand($this->getId());
		return $return;
	}

	public function getStatistique($_startTime, $_endTime) {
		return history::getStatistique($this->getId(), $_startTime, $_endTime);
	}

	public function getTendance($_startTime, $_endTime) {
		return history::getTendance($this->getId(), $_startTime, $_endTime);
	}

	public function getCacheLifetime() {
		if ($this->getEventOnly() == 1) {
			return 0;
		}
		if ($this->getCache('enable', 0) == 0 && $this->getCache('lifetime') == '') {
			return 10;
		}
		$lifetime = $this->getCache('lifetime', config::byKey('lifeTimeMemCache'));
		return ($lifetime < 10) ? 10 : $lifetime;
	}

	public function getCmdValue() {
		$cmd = self::byId(str_replace('#', '', $this->getValue()));
		if (is_object($cmd)) {
			return $cmd;
		}
		return false;
	}

	public function getHumanName($_tag = false, $_prettify = false) {
		$name = '';
		$eqLogic = $this->getEqLogic();
		if (is_object($eqLogic)) {
			$name .= $eqLogic->getHumanName($_tag, $_prettify);
		}
		if ($_tag) {
			$name .= ' - ' . $this->getName();
		} else {
			$name .= '[' . $this->getName() . ']';
		}
		return $name;
	}

	public function getHistory($_dateStart = null, $_dateEnd = null) {
		return history::all($this->id, $_dateStart, $_dateEnd);
	}

	public function getPluralityHistory($_dateStart = null, $_dateEnd = null, $_period = 'day', $_offset = 0) {
		return history::getPlurality($this->id, $_dateStart, $_dateEnd, $_period, $_offset);
	}

	public function setCollect($collect) {
		if ($collect == 1) {
			cache::set('collect' . $this->getId(), $this->getId());
		} else {
			$cache = cache::byKey('collect' . $this->getId());
			$cache->remove();
		}
	}

	public function export() {
		$cmd = clone $this;
		$cmd->setId('');
		$cmd->setOrder('');
		$cmd->setEqLogic_id('');
		$cmd->cache = '';
		$cmd->setDisplay('graphType', '');
		$cmdValue = $cmd->getCmdValue();
		if (is_object($cmdValue)) {
			$cmd->setValue($cmdValue->getName());
		} else {
			$cmd->setValue('');
		}
		$return = utils::o2a($cmd);
		foreach ($return as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $key2 => $value2) {
					if ($value2 == '') {
						unset($return[$key][$key2]);
					}
				}
			} else {
				if ($value == '') {
					unset($return[$key]);
				}
			}
		}
		if (isset($return['configuration']) && count($return['configuration']) == 0) {
			unset($return['configuration']);
		}
		if (isset($return['display']) && count($return['display']) == 0) {
			unset($return['display']);
		}
		return $return;
	}

	public function getDirectUrlAccess() {
		$url = '/core/api/jeeApi.php?apikey=' . config::byKey('api') . '&type=cmd&id=' . $this->getId();
		if ($this->getType() == 'action') {
			switch ($this->getSubType()) {
				case 'slider':
					$url .= '&slider=50';
					break;
				case 'color':
					$url .= '&color=#123456';
					break;
				case 'message':
					$url .= '&title=montitre&message=monmessage';
					break;
			}
		}
		return network::getNetworkAccess('external') . $url;
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

	public function getSubType() {
		return $this->subType;
	}

	public function getEqType_name() {
		return $this->eqType;
	}

	public function getEqLogic_id() {
		return $this->eqLogic_id;
	}

	public function getIsHistorized() {
		return $this->isHistorized;
	}

	public function getUnite() {
		return $this->unite;
	}

	public function getEqLogic() {
		if ($this->_eqLogic == null) {
			$this->_eqLogic = eqLogic::byId($this->eqLogic_id);
		}
		return $this->_eqLogic;
	}

	public function getEventOnly() {
		return $this->eventOnly;
	}

	public function setId($id = '') {
		$this->id = $id;
	}

	public function setName($name) {
		$name = str_replace(array('&', '#', ']', '[', '%', "'"), '', $name);
		$this->name = $name;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setSubType($subType) {
		$this->subType = $subType;
	}

	public function setEqLogic_id($eqLogic_id) {
		$this->eqLogic_id = $eqLogic_id;
	}

	public function setIsHistorized($isHistorized) {
		$this->isHistorized = $isHistorized;
	}

	public function setUnite($unite) {
		$this->unite = $unite;
	}

	public function setEventOnly($eventOnly) {
		$this->eventOnly = $eventOnly;
	}

	public function getCache($_key = '', $_default = '') {
		return utils::getJsonAttr($this->cache, $_key, $_default);
	}

	public function setCache($_key, $_value) {
		$this->cache = utils::setJsonAttr($this->cache, $_key, $_value);
	}

	public function getTemplate($_key = '', $_default = '') {
		return utils::getJsonAttr($this->template, $_key, $_default);
	}

	public function setTemplate($_key, $_value) {
		$this->template = utils::setJsonAttr($this->template, $_key, $_value);
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		if ($_key == 'actionCodeAccess' && !preg_match('/^[0-9a-f]{40}$/i', $_value) && $_value != '') {
			$_value = sha1($_value);
		}
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function setDisplay($_key, $_value) {
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
	}

	public function getCollectDate() {
		return $this->_collectDate;
	}

	public function setCollectDate($_collectDate) {
		$this->_collectDate = $_collectDate;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function getIsVisible() {
		return $this->isVisible;
	}

	public function setIsVisible($isVisible) {
		$this->isVisible = $isVisible;
	}

	public function getOrder() {
		return $this->order;
	}

	public function setOrder($order) {
		$this->order = $order;
	}

	public function getLogicalId() {
		return $this->logicalId;
	}

	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
	}

	public function getEqType() {
		return $this->eqType;
	}

	public function setEqType($eqType) {
		$this->eqType = $eqType;
	}

}

?>
