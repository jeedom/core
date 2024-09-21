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

/*
Translate system scan core/template/dashboard files and set them in i18n all under "core\/template\/widgets.html" path
-> translate::exec($string, 'core/template/widgets.html');
*/

class cmd {
	/*     * *************************Attributs****************************** */

	protected $id;
	protected $logicalId;
	protected $generic_type;
	protected $eqType;
	protected $name;
	protected $order;
	protected $type;
	protected $subType;
	protected $eqLogic_id;
	protected $isHistorized = 0;
	protected $unite = '';
	protected $configuration;
	protected $template;
	protected $display;
	protected $value = null;
	protected $isVisible = 1;
	protected $alert;
	protected $_collectDate = '';
	protected $_valueDate = '';
	/** @var eqLogic */
	protected $_eqLogic = null;
	protected $_needRefreshWidget;
	protected $_needRefreshAlert;
	/** @var bool */
	protected $_changed = false;
	protected static $_templateArray = array();
	protected static $_unite_conversion = array(
		's' => array(60, 's', 'min', 'h'),
		'W' => array(1000, 'W', 'kW', 'MW'),
		'Wh' => array(1000, 'Wh', 'kWh', 'MWh'),
		'io' => array(1024, 'io', 'Kio', 'Mio', 'Gio', 'Tio'),
		'o' => array(1000, 'o', 'Ko', 'Mo', 'Go', 'To'),
		'o/s' => array(1000, 'o/s', 'Ko/s', 'Mo/s', 'Go/s', 'To/s'),
		'Bps' => array(1000, 'Bps', 'Kbps', 'Mbps', 'Gbps', 'Tbps'),
		'Hz' => array(1000, 'Hz', 'kHz', 'MHz', 'GHz'),
		'l' => array(1000, 'l', 'm<sup>3</sup>')
	);
	/*     * ***********************Méthodes statiques*************************** */

	private static function cast($_inputs, $_eqLogic = null) {
		if (is_object($_inputs) && class_exists($_inputs->getEqType() . 'Cmd')) {
			if ($_eqLogic !== null) {
				$_inputs->_eqLogic = $_eqLogic;
			}
			$return = cast($_inputs, $_inputs->getEqType() . 'Cmd');
			if (method_exists($return, 'decrypt')) {
				$return->decrypt();
			}
			return $return;
		}
		if (is_array($_inputs)) {
			$return = array();
			foreach ($_inputs as $input) {
				if ($_eqLogic !== null) {
					$input->_eqLogic = $_eqLogic;
				}
				$return[] = self::cast($input);
			}
			return $return;
		}
		return $_inputs;
	}

	/**
	 * @param int|string $_id the id of the command
	 * @return void|cmd void if $_id is not valid else the cmd
	 */
	public static function byId($_id) {
		if ($_id == '') {
			return;
		}
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE id=:id';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	/**
	 * @param array<string|int> $_ids
	 * @return void|array<cmd> void if $_ids is not valid else an array of cmd
	 */
	public static function byIds($_ids) {
		if (!is_array($_ids) || count($_ids) == 0) {
			return;
		}
		$in = trim(preg_replace('/[, ]{2,}/m', ',', implode(',', $_ids)), ',');
		if (!empty($in)) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE id IN (' . $in . ')';
			return self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		}
	}

	/**
	 * @return array<cmd>
	 */
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		ORDER BY id';
		return self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function isHistorized($_state = true) {
		$values = array(
			'isHistorized' => ($_state) ? 1 : 0
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE isHistorized=:isHistorized
		ORDER BY id';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function allHistoryCmd() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		INNER JOIN object ob ON el.object_id=ob.id
		WHERE isHistorized=1
		AND type=\'info\'';
		$sql .= ' ORDER BY ob.position,ob.name,el.name,c.name';
		$result1 = self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
		FROM cmd c
		INNER JOIN eqLogic el ON c.eqLogic_id=el.id
		WHERE el.object_id IS NULL
		AND isHistorized=1
		AND type=\'info\'';
		$sql .= ' ORDER BY el.name,c.name';
		$result2 = self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		return array_merge($result1, $result2);
	}

	/**
	 *
	 * @param int|array<int> $_eqLogic_id
	 * @param string $_type ['action'|'info']
	 * @param bool $_visible
	 * @param eqLogic $_eqLogic
	 * @param bool $_has_generic_type
	 * @return array<cmd>
	 */
	public static function byEqLogicId($_eqLogic_id, $_type = null, $_visible = null, $_eqLogic = null, $_has_generic_type = null) {
		$values = array();
		if (is_array($_eqLogic_id)) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE eqLogic_id IN (' . trim(preg_replace('/[, ]{2,}/m', ',', implode(',', $_eqLogic_id)), ',') . ')';
		} else {
			$values = array(
				'eqLogic_id' => $_eqLogic_id,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE eqLogic_id=:eqLogic_id';
		}
		if ($_type !== null) {
			$values['type'] = $_type;
			$sql .= ' AND `type`=:type';
		}
		if ($_visible !== null) {
			$sql .= ' AND `isVisible`=1';
		}
		if ($_has_generic_type) {
			$sql .= ' AND `generic_type` IS NOT NULL';
		}
		$sql .= ' ORDER BY `order`,`name`';
		if (is_object($_eqLogic)  && class_exists($_eqLogic->getEqType_name() . 'Cmd')) {
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, $_eqLogic->getEqType_name() . 'Cmd');
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__), $_eqLogic);
	}

	/**
	 *
	 * @param string $_logical_id
	 * @param string $_type ['action'|'info']
	 * @return array<cmd>
	 */
	public static function byLogicalId($_logical_id, $_type = null) {
		$values = array(
			'logicalId' => $_logical_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE logicalId=:logicalId';
		if ($_type !== null) {
			$values['type'] = $_type;
			$sql .= ' AND `type`=:type';
		}
		$sql .= ' ORDER BY `order`';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	/**
	 *
	 * @param string|array<string> $_generic_type
	 * @param int $_eqLogic_id
	 * @param boolean $_one
	 * @return cmd|array<cmd> first cmd if $_one is true otherwise an array of all cmd
	 */
	public static function byGenericType($_generic_type, $_eqLogic_id = null, $_one = false) {
		if (is_array($_generic_type)) {
			$in = '';
			foreach ($_generic_type as $value) {
				$in .= "'" . $value . "',";
			}
			$values = array();
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE generic_type IN (' . trim(preg_replace('/[, ]{2,}/m', ',', $in), ',') . ')';
		} else {
			$values = array(
				'generic_type' => $_generic_type,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE generic_type=:generic_type';
		}
		if ($_eqLogic_id !== null) {
			$values['eqLogic_id'] = $_eqLogic_id;
			$sql .= ' AND `eqLogic_id`=:eqLogic_id';
		}
		$sql .= ' ORDER BY `order`';
		if ($_one) {
			return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	/**
	 * Search a command on eqType, logicalId, generic_type or name
	 *
	 * @param string $_search the needle
	 * @return array<cmd>
	 */
	public static function searchByString($_search) {
		$values = array(
			'search' => '%' . $_search . '%'
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE eqType LIKE :search or logicalId LIKE :search or generic_type LIKE :search or name LIKE :search';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	/**
	 *
	 * @param string|array<string> $_configuration
	 * @param string $_eqType
	 * @return array<cmd>
	 */
	public static function searchConfiguration($_configuration, $_eqType = null) {
		if (!is_array($_configuration)) {
			$values = array(
				'configuration' => '%' . $_configuration . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE configuration LIKE :configuration';
		} else {
			$values = array(
				'configuration' => '%' . $_configuration[0] . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE configuration LIKE :configuration';
			for ($i = 1; $i < count($_configuration); $i++) {
				$values['configuration' . $i] = '%' . $_configuration[$i] . '%';
				$sql .= ' OR configuration LIKE :configuration' . $i;
			}
		}
		if ($_eqType !== null) {
			$values['eqType'] = $_eqType;
			$sql .= ' AND eqType=:eqType ';
		}
		$sql .= ' ORDER BY name';
		if ($_eqType != null  && class_exists($_eqType . 'Cmd')) {
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, $_eqType . 'Cmd');
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function searchDisplay($_display, $_eqType = null) {
		if (!is_array($_display)) {
			$values = array(
				'display' => '%' . $_display . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE display LIKE :display';
		} else {
			$values = array(
				'display' => '%' . $_display[0] . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE display LIKE :display';
			for ($i = 1; $i < count($_display); $i++) {
				$values['display' . $i] = '%' . $_display[$i] . '%';
				$sql .= ' OR display LIKE :display' . $i;
			}
		}
		if ($_eqType !== null) {
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
		if ($_type !== null) {
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
		if ($_eqType !== null) {
			$values['eqType'] = $_eqType;
			$sql .= ' AND eqType=:eqType ';
		}
		if ($_type !== null) {
			$values['type'] = $_type;
			$sql .= ' AND type=:type ';
		}
		if ($_subtype !== null) {
			$values['subType'] = $_subtype;
			$sql .= ' AND subType=:subType ';
		}
		$sql .= ' ORDER BY name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byEqLogicIdAndLogicalId($_eqLogic_id, $_logicalId, $_multiple = false, $_type = null, $_eqLogic = null) {
		$values = array(
			'eqLogic_id' => $_eqLogic_id,
			'logicalId' => $_logicalId,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND logicalId=:logicalId';
		if ($_type !== null) {
			$values['type'] = $_type;
			$sql .= ' AND type=:type';
		}
		$sql .= ' ORDER BY `order`';
		if ($_multiple) {
			if (is_object($_eqLogic)  && class_exists($_eqLogic->getEqType_name() . 'Cmd')) {
				return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, $_eqLogic->getEqType_name() . 'Cmd');
			}
			return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		}
		if (is_object($_eqLogic)  && class_exists($_eqLogic->getEqType_name() . 'Cmd')) {
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, $_eqLogic->getEqType_name() . 'Cmd');
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byEqLogicIdAndGenericType($_eqLogic_id, $_generic_type, $_multiple = false, $_type = null, $_eqLogic = null) {
		$values = array(
			'eqLogic_id' => $_eqLogic_id,
			'generic_type' => $_generic_type,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE eqLogic_id=:eqLogic_id
		AND generic_type=:generic_type';
		if ($_type !== null) {
			$values['type'] = $_type;
			$sql .= ' AND type=:type';
		}
		if ($_multiple) {
			if (is_object($_eqLogic)  && class_exists($_eqLogic->getEqType_name() . 'Cmd')) {
				return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, $_eqLogic->getEqType_name() . 'Cmd');
			}
			return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		}
		if (is_object($_eqLogic)  && class_exists($_eqLogic->getEqType_name() . 'Cmd')) {
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, $_eqLogic->getEqType_name() . 'Cmd');
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byGenericTypeObjectId($_generic_type, $_object_id = null, $_type = null) {
		$values = array(
			'generic_type' => $_generic_type
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM cmd
		WHERE generic_type=:generic_type';

		if ($_object_id && $_object_id != '-1') {
			if (!is_numeric($_object_id)) {
				$object = jeeObject::byName($_object_id);
				if (!is_object($object)) return array();
				$_object_id = $object->getId();
			}
			$eqLogics = eqLogic::byObjectId($_object_id);
			$eqLogicIds = array();
			foreach ($eqLogics as $eqLogic) {
				array_push($eqLogicIds, $eqLogic->getId());
			}
			$eqLogicIds = implode(',', $eqLogicIds);
			if (empty($eqLogicIds)) {
				return;
			}
			$sql .= ' AND eqLogic_id IN (' . $eqLogicIds . ')';
		}

		if ($_type !== null) {
			$values['type'] = $_type;
			$sql .= ' AND type=:type';
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}

	public static function byValue($_value, $_type = null, $_onlyEnable = false) {
		$values = array(
			'value' => $_value,
			'search' => '%#' . $_value . '#%',
		);
		if (strpos($_value, 'variable(') !== false) {
			$values['search'] = '%#' . $_value . '%';
		}
		if ($_onlyEnable) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 'c') . '
			FROM cmd c
			INNER JOIN eqLogic el ON c.eqLogic_id=el.id
			WHERE ( value=:value OR value LIKE :search)
			AND el.isEnable=1
			AND c.id!=:value';
			if ($_type !== null) {
				$values['type'] = $_type;
				$sql .= ' AND c.type=:type ';
			}
		} else {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM cmd
			WHERE ( value=:value OR value LIKE :search)
			AND id!=:value';
			if ($_type !== null) {
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
		if ($_eqType_name != null  && class_exists($_eqType_name . 'Cmd')) {
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, $_eqType_name . 'Cmd');
		}
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
			'cmd_name' => (html_entity_decode($_cmd_name) != '') ? html_entity_decode($_cmd_name) : $_cmd_name,
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
			return json_decode(self::cmdToHumanReadable(json_encode($_input)), true);
		}
		$replace = array();
		preg_match_all("/#([0-9]*)#/", $_input, $matches);
		if (count($matches[1]) == 0) {
			return $_input;
		}
		$cmds = self::byIds($matches[1]);
		if (is_array($cmds) && count($cmds) > 0) {
			foreach ($cmds as $cmd) {
				if (isset($replace['#' . $cmd->getId() . '#'])) {
					continue;
				}
				$replace['#' . $cmd->getId() . '#'] = '#' . $cmd->getHumanName() . '#';
			}
		}
		return str_replace(array_keys($replace), $replace, $_input);
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
		if (is_int($_input) || is_bool($_input) || is_null($_input)) {
			return $_input;
		}
		$replace = array();
		preg_match_all("/#\[([^#]*)\]\[([^#]*)\]\[([^#]*)\]#/", $_input, $matches);
		if (count($matches) == 4) {
			$countMatches = count($matches[0]);
			for ($i = 0; $i < $countMatches; $i++) {
				if (isset($replace[$matches[0][$i]])) {
					continue;
				}
				if (isset($matches[1][$i]) && isset($matches[2][$i]) && isset($matches[3][$i])) {
					$cmd = self::byObjectNameEqLogicNameCmdName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
					if (is_object($cmd)) {
						$replace[$matches[0][$i]] = '#' . $cmd->getId() . '#';
					}
				}
			}
		}
		return str_replace(array_keys($replace), $replace, $_input);
	}

	public static function byString($_string) {
		$cmd = self::byId(str_replace('#', '', self::humanReadableToCmd($_string)));
		if (!is_object($cmd)) {
			throw new Exception($GLOBALS['JEEDOM_SCLOG_TEXT']['unfoundCmd']['txt'] . ' : ' . $_string . ' => ' . self::humanReadableToCmd($_string));
		}
		return $cmd;
	}

	public static function cmdToValue($_input, $_quote = false) {
		if (config::byKey('expression::autoQuote', 'core', 1) == 0) {
			$_quote = false;
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
		$json = is_json($_input);
		$replace = array();
		preg_match_all("/#([0-9]*)#/", $_input, $matches);
		foreach ($matches[1] as $cmd_id) {
			if (isset($replace['#' . $cmd_id . '#'])) {
				continue;
			}
			$cache = cache::byKey('cmdCacheAttr' . $cmd_id)->getValue();
			if (utils::getJsonAttr($cache, 'value', null) !== null) {
				$collectDate = utils::getJsonAttr($cache, 'collectDate', date('Y-m-d H:i:s'));
				$valueDate = utils::getJsonAttr($cache, 'valueDate', date('Y-m-d H:i:s'));
				$cmd_value = utils::getJsonAttr($cache, 'value', '');
			} else {
				$cmd = self::byId($cmd_id);
				if (!is_object($cmd) || $cmd->getType() != 'info') {
					continue;
				}
				$cmd_value = $cmd->execCmd(null, true, $_quote);
				$collectDate = $cmd->getCollectDate();
				$valueDate = $cmd->getValueDate();
			}
			if ($_quote && (substr_count($cmd_value, '.') > 1 || strpos($cmd_value, ' ') !== false || preg_match("/[a-zA-Z#]/", $cmd_value) || $cmd_value === '')) {
				$cmd_value = '"' . trim($cmd_value, '"') . '"';
			}
			if (!$json) {
				$replace['"#' . $cmd_id . '#"'] = $cmd_value;
				$replace['#' . $cmd_id . '#'] = $cmd_value;
				$replace['#collectDate' . $cmd_id . '#'] = $collectDate;
				$replace['#valueDate' . $cmd_id . '#'] = $valueDate;
			} else {
				$replace['#' . $cmd_id . '#'] = trim(json_encode($cmd_value), '"');
				$replace['#valueDate' . $cmd_id . '#'] = trim(json_encode($valueDate), '"');
				$replace['#collectDate' . $cmd_id . '#'] = trim(json_encode($collectDate), '"');
			}
		}
		return str_replace(array_keys($replace), $replace, $_input);
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
		global $JEEDOM_INTERNAL_CONFIG;
		$return = array();
		$path = __DIR__ . '/../../data/customTemplates/' . $_version;
		if (file_exists($path)) {
			$files = ls($path, 'cmd.*', false, array('files', 'quiet'));
			foreach ($files as $file) {
				$informations = explode('.', $file);
				if (count($informations) < 4) {
					continue;
				}
				if (stripos($informations[3], 'tmpl') !== false) {
					continue;
				}
				if (!isset($return[$informations[1]])) {
					$return[$informations[1]] = array();
				}
				if (!isset($return[$informations[1]][$informations[2]])) {
					$return[$informations[1]][$informations[2]] = array();
				}
				if (isset($informations[3])) {
					$return[$informations[1]][$informations[2]][$informations[3]] = array('name' => $informations[3], 'location' => 'customtemp', 'type' => 'custom');
				}
			}
		}
		foreach ($JEEDOM_INTERNAL_CONFIG['cmd']['widgets'] as $type => $data1) {
			foreach ($data1 as $subtype => $data2) {
				foreach ($data2 as $name => $data3) {
					if (!isset($return[$type])) {
						$return[$type] = array();
					}
					if (!isset($return[$type][$subtype])) {
						$return[$type][$subtype] = array();
					}
					$return[$type][$subtype][$name] = array('name' => $name, 'location' => 'core', 'type' => 'template');
				}
			}
		}
		foreach (plugin::listPlugin(true, false, false) as $plugin) {
			$path = __DIR__ . '/../../plugins/' . $plugin->getId() . '/core/template/' . $_version;
			if (file_exists($path)) {
				$files = ls($path, 'cmd.*', false, array('files', 'quiet'));
				foreach ($files as $file) {
					$informations = explode('.', $file);
					if (count($informations) < 4) {
						continue;
					}
					if (stripos($informations[3], 'tmpl') !== false) {
						continue;
					}
					if (!isset($return[$informations[1]])) {
						$return[$informations[1]] = array();
					}
					if (!isset($return[$informations[1]][$informations[2]])) {
						$return[$informations[1]][$informations[2]] = array();
					}
					if (isset($informations[3])) {
						$return[$informations[1]][$informations[2]][$informations[3]] = array('name' => $informations[3], 'location' => $plugin->getId(), 'type' => $plugin->getId());
					}
				}
			}
			if (!method_exists($plugin->getId(), 'templateWidget')) {
				continue;
			}
			$plugin_id = $plugin->getId();
			foreach (($plugin_id::templateWidget()) as $type => $data1) {
				foreach ($data1 as $subtype => $data2) {
					foreach ($data2 as $name => $data3) {
						if (!isset($return[$type])) {
							$return[$type] = array();
						}
						if (!isset($return[$type][$subtype])) {
							$return[$type][$subtype] = array();
						}
						$return[$type][$subtype][$plugin->getId() . '::' . $name] = array('name' => $name, 'location' => $plugin->getId(), 'type' => 'plugin');
					}
				}
			}
		}
		foreach ((widgets::all()) as $widgets) {
			if (!isset($return[$widgets->getType()])) {
				$return[$widgets->getType()] = array();
			}
			if (!isset($return[$widgets->getType()][$widgets->getSubtype()])) {
				$return[$widgets->getType()][$widgets->getSubtype()] = array();
			}
			$return[$widgets->getType()][$widgets->getSubtype()][$widgets->getName()] = array('name' => $widgets->getName(), 'location' => 'custom', 'type' => 'custom');
		}
		$path = __DIR__ . '/../template/' . $_version;
		$files = ls($path, 'cmd.*', false, array('files', 'quiet'));
		foreach ($files as $file) {
			$informations = explode('.', $file);
			if (count($informations) < 4) {
				continue;
			}
			if (stripos($informations[3], 'tmpl') !== false) {
				continue;
			}
			if (!isset($return[$informations[1]])) {
				$return[$informations[1]] = array();
			}
			if (!isset($return[$informations[1]][$informations[2]])) {
				$return[$informations[1]][$informations[2]] = array();
			}
			if (isset($informations[3])) {
				$return[$informations[1]][$informations[2]][$informations[3]] = array('name' => $informations[3], 'location' => 'core', 'type' => 'core');
			}
		}
		return $return;
	}

	public static function getSelectOptionsByTypeAndSubtype($_type = false, $_subtype = false, $_version = 'dashboard', $_availWidgets = false) {
		if ($_type === false || $_subtype === false) {
			throw new Exception(__('Type ou sous-type de commande invalide', __FILE__));
		}
		if (!$_availWidgets) {
			$_availWidgets = self::availableWidget($_version);
		}
		$display = '';
		if (is_array($_availWidgets[$_type]) && is_array($_availWidgets[$_type][$_subtype]) && count($_availWidgets[$_type][$_subtype]) > 0) {
			$types = array();
			foreach ($_availWidgets[$_type][$_subtype] as $key => $info) {
				if (isset($info['type'])) {
					$info['key'] = $key;
					if (!isset($types[$info['type']])) {
						$types[$info['type']][0] = $info;
					} else {
						array_push($types[$info['type']], $info);
					}
				}
			}

			ksort($types);
			foreach ($types as $type) {
				usort($type, function ($a, $b) {
					return strcmp($a['name'], $b['name']);
				});
				foreach ($type as $key => $widget) {
					if ($widget['name'] == 'default' || $widget['name'] == 'core::default') {
						continue;
					}
					if ($key == 0) {
						$display .= '<optgroup label="' . ucfirst($widget['type']) . '">';
					}
					if (isset($widget['location']) && $widget['location'] != 'core' && $widget['location'] != 'custom') {
						$display .= '<option value="' . $widget['location'] . '::' . $widget['name'] . '">' . ucfirst($widget['location']) . '/' . ucfirst($widget['name']) . '</option>';
					} else {
						$display .= '<option value="' . $widget['location'] . '::' . $widget['name'] . '">' . ucfirst($widget['name']) . '</option>';
					}
				}
				$display .= '</optgroup>';
			}
			return $display;
		}
	}

	public static function returnState($_options) {
		$cmd = cmd::byId($_options['cmd_id']);
		if (is_object($cmd)) {
			$cmd->event($cmd->getConfiguration('returnStateValue', 0));
		}
	}

	public static function deadCmd() {
		$return = array();
		foreach ((cmd::all()) as $cmd) {
			if($cmd->getConfiguration('core::cmd::noDeadAnalyze',0) == 1){
				continue;
			}
			if (is_array($cmd->getConfiguration('actionCheckCmd', ''))) {
				foreach ($cmd->getConfiguration('actionCheckCmd', '') as $actionCmd) {
					if ($actionCmd['cmd'] != '' && strpos($actionCmd['cmd'], '#') !== false) {
						if (!cmd::byId(str_replace('#', '', $actionCmd['cmd']))) {
							$return[] = array('detail' => 'Commande ' . $cmd->getName() . ' de ' . $cmd->getEqLogic()->getName() . ' (' . $cmd->getEqLogic()->getEqType_name() . ')', 'help' => 'Action sur valeur', 'who' => $actionCmd['cmd']);
						}
					}
				}
			}
			if (is_array($cmd->getConfiguration('jeedomPostExecCmd', ''))) {
				foreach ($cmd->getConfiguration('jeedomPostExecCmd', '') as $actionCmd) {
					if ($actionCmd['cmd'] != '' && strpos($actionCmd['cmd'], '#') !== false) {
						if (!cmd::byId(str_replace('#', '', $actionCmd['cmd']))) {
							$return[] = array('detail' => 'Commande ' . $cmd->getName() . ' de ' . $cmd->getEqLogic()->getName() . ' (' . $cmd->getEqLogic()->getEqType_name() . ')', 'help' => 'Post Exécution', 'who' => $actionCmd['cmd']);
						}
					}
				}
			}
			if (is_array($cmd->getConfiguration('jeedomPreExecCmd', ''))) {
				foreach ($cmd->getConfiguration('jeedomPreExecCmd', '') as $actionCmd) {
					if ($actionCmd['cmd'] != '' && strpos($actionCmd['cmd'], '#') !== false) {
						if (!cmd::byId(str_replace('#', '', $actionCmd['cmd']))) {
							$return[] = array('detail' => 'Commande ' . $cmd->getName() . ' de ' . $cmd->getEqLogic()->getName() . ' (' . $cmd->getEqLogic()->getEqType_name() . ')', 'help' => 'Pré Exécution', 'who' => $actionCmd['cmd']);
						}
					}
				}
			}
		}
		return $return;
	}

	public static function cmdAlert($_options) {
		$cmd = cmd::byId($_options['cmd_id']);
		if (!is_object($cmd)) {
			return;
		}
		$cmd->executeAlertCmdAction();
	}

	/*     * *********************Méthodes d'instance************************* */
	public function formatValue($_value, $_quote = false) {
		if (is_array($_value) || is_object($_value)) {
			return '';
		}
		if ($_value === null) {
			$_value = 0;
		}
		if (trim($_value) == '' && $_value !== false && $_value !== 0) {
			return '';
		}
		$_value = trim(trim($_value), '"');
		if (@strpos(strtolower($_value), 'error::') !== false) {
			return $_value;
		}
		if ($this->getType() == 'info') {
			if ($this->getSubType() == 'numeric') { // Handle comma instead of period in a float value
				$_value = floatval(str_replace(',', '.', $_value));
			}
			$calc = $this->getConfiguration('calculValueOffset');
			if ($calc != '') {
				try {
					if (preg_match("/[a-zA-Z#]/", $_value)) { // Value is not just a number
						$calc = str_replace('#value#', '"' . $_value . '"', str_replace('\'#value#\'', '#value#', str_replace('"#value#"', '#value#', $calc)));
					} else { // Value is a number
						$calc = str_replace('#value#', $_value, $calc);
					}
					$_value = jeedom::evaluateExpression($calc);
				} catch (Exception $ex) {
				} catch (Error $ex) {
				}
			}
			switch ($this->getSubType()) {
				case 'string':
				case 'other':
					if ($_quote) {
						return '"' . $_value . '"';
					}
					return $_value;
				case 'binary':
					if ($_value === true || $_value === 1) { // Handle literal values
						$binary = true;
					} elseif ((is_numeric(intval($_value)) && intval($_value) >= 1)) { // Handle number and numeric string
						$binary = true;
					} elseif (in_array(strtolower($_value), array('on', 'true', 'high', 'enable', 'enabled','online'))) { // Handle common string boolean values
						$binary = true;
					} else { // Handle everything else as false
						$binary = false;
					}
					// Return int value negated according to invertBinary configuration
					return intval($binary xor boolval($this->getConfiguration('invertBinary', false)));
				case 'numeric':
					if ($this->getConfiguration('historizeRound') !== '' && is_numeric($this->getConfiguration('historizeRound')) && $this->getConfiguration('historizeRound') >= 0) {
						$_value = round($_value, $this->getConfiguration('historizeRound'));
					}
					if ($_value > $this->getConfiguration('maxValue', $_value)) {
						$_value = $this->getConfiguration('maxValue', $_value);
					}
					if ($_value < $this->getConfiguration('minValue', $_value)) {
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

	public function save($_direct = false) {
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
			throw new Exception($this->getHumanName() . ' ' . __('La valeur minimum de la commande ne peut être supérieure à la valeur maximum', __FILE__));
		}
		if ($this->getEqType() == '') {
			$this->setEqType($this->getEqLogic()->getEqType_name());
		}
		if ($this->getDisplay('generic_type') != '' && $this->getGeneric_type() == '') {
			$this->setGeneric_type($this->getDisplay('generic_type'));
			$this->setDisplay('generic_type', null);
		}
		if ($this->getTemplate('dashboard', '') == '') {
			$this->setTemplate('dashboard', 'core::default');
		}
		if ($this->getTemplate('mobile', '') == '') {
			$this->setTemplate('mobile', 'core::default');
		}
		if ($this->getType() == 'action' && $this->getIsHistorized() == 1) {
			$this->setIsHistorized(0);
		}
		if ($this->getIsHistorized() == 0 && $this->getType() == 'info' && $this->getSubType() != 'string') {
			$this->setDisplay('showStatsOnmobile', 0);
			$this->setDisplay('showStatsOndashboard', 0);
		}
		if ($this->getType() == 'action') {
			$this->setDisplay('showStatsOnmobile', null);
			$this->setDisplay('showStatsOndashboard', null);
		}
		DB::save($this, $_direct);
		if ($this->_needRefreshWidget) {
			$this->_needRefreshWidget = false;
			$eqLogic = $this->getEqLogic();
			if (is_object($eqLogic)) {
				$eqLogic->refreshWidget();
			}
		}
		if ($this->_needRefreshAlert && $this->getType() == 'info') {
			$value = $this->execCmd();
			$level = $this->checkAlertLevel($value);
			if ($level != $this->getCache('alertLevel')) {
				$this->actionAlertLevel($level, $value);
			}
		}
		return true;
	}

	public function refresh() {
		DB::refresh($this);
	}

	public function remove() {
		viewData::removeByTypeLinkId('cmd', $this->getId());
		dataStore::removeByTypeLinkId('cmd', $this->getId());
		$eqLogic = $this->getEqLogic();
		$eqLogic->setStatus(array(
			'warning' => 0,
			'danger' => 0,
		));
		$this->emptyHistory();
		cache::delete('cmdCacheAttr' . $this->getId());
		cache::delete('cmd' . $this->getId());
		jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getHumanName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'cmd'));
		return DB::remove($this);
	}

	public function execute($_options = array()) {
		return false;
	}

	private function pre_postExecCmd($_values = array(), $_type = 'jeedomPreExecCmd') {
		
		if (!is_array($this->getConfiguration($_type)) || count($this->getConfiguration($_type)) == 0) {
			return;
		}
		$message = '';
		switch ($_type) {
			case 'jeedomPreExecCmd':
				$message = '. ' . __('Sur preExec de la commande', __FILE__);
				break;
			case 'jeedomPostExecCmd':
				$message = '. ' . __('Sur postExec de la commande', __FILE__);
				break;
		}

		foreach ($this->getConfiguration($_type) as $action) {
			try {
				$options = array();
				if (isset($action['options'])) {
					$options = $action['options'];
				}
				if (is_array($_values) && count($_values) > 0) {
					foreach ($_values as $key => $value) {
						foreach ($options as &$option) {
							if (!is_array($option)) {
								$option = str_replace('#' . $key . '#', $value, $option);
							}
						}
					}
					$options['source'] = $this->getHumanName();
				}
				scenarioExpression::createAndExec('action', $action['cmd'], $options);
			} catch (Exception $e) {
				log::add('cmd', 'error', __('Erreur lors de l\'exécution de', __FILE__) . ' ' . $action['cmd'] . ': ' . $message . '. ' . $this->getHumanName() . __('Détails :', __FILE__) . ' ' . $e->getMessage());
			}
		}
	}

	public function preExecCmd($_values = array()) {
		if (isset($_values['user_login'])) {
			$this->setCache('lastExecutionUser', $_values['user_login']);
		} else {
			$this->setCache('lastExecutionUser', 'system');
		}
		$this->pre_postExecCmd($_values, 'jeedomPreExecCmd');
	}

	public function postExecCmd($_values = array()) {
		$this->pre_postExecCmd($_values, 'jeedomPostExecCmd');
	}

	public function isAlreadyInStateAllow() {
		if ($this->getConfiguration('alreadyInState') == 'deny') {
			return false;
		}
		if ($this->getConfiguration('alreadyInState') == '' && config::byKey('cmd::allowCheckState') == 0) {
			return false;
		}
		if ($this->getType() != 'action') {
			return false;
		}
		$cmdValue = $this->getCmdValue();
		if (!is_object($cmdValue)) {
			return false;
		}
		if ($cmdValue->getCache('lastAction') != '' && strtotime($cmdValue->getCache('lastAction')) > strtotime($cmdValue->getCache('valueDate'))) {
			return false;
		}
		return true;
	}

	public function alreadyInState($_options) {
		if ($this->getSubType() == 'message') {
			return false;
		}
		$cmdValue = $this->getCmdValue();
		$value =  $cmdValue->execCmd();
		switch ($this->getSubType()) {
			case 'other':
				switch ($cmdValue->getSubtype()) {
					case 'binary':
						if (strtolower($this->getName()) == 'on' && $value == 1) {
							return true;
						}
						if (strtolower($this->getName()) == 'off' && $value == 0) {
							return true;
						}
						break;
					case 'string':
						if (strtolower($this->getName()) == $value) {
							return true;
						}
						break;
				}
				break;
			case 'slider':
				if ($_options['slider'] == $value) {
					return true;
				}
		}
		return false;
	}

	/**
	 *
	 * @param null|string $_options
	 * @param bool $_sendNodeJsEvent
	 * @param bool $_quote
	 * @return void|string
	 * @throws Exception
	 */
	public function execCmd($_options = null, $_sendNodeJsEvent = false, $_quote = false) {
		if ($this->getType() == 'info') {
			$state = $this->getCache(array('collectDate', 'valueDate', 'value', 'usage'));
			if (isset($state['collectDate'])) {
				$this->setCollectDate($state['collectDate']);
			} else {
				$this->setCollectDate(date('Y-m-d H:i:s'));
			}
			if (isset($state['valueDate'])) {
				$this->setValueDate($state['valueDate']);
			} else {
				$this->setValueDate($this->getCollectDate());
			}
			return $state['value'];
		}
		$eqLogic = $this->getEqLogic();
		if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
			throw new Exception($GLOBALS['JEEDOM_SCLOG_TEXT']['disableEqNoExecCmd']['txt'] . $this->getHumanName());
		}
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
			if ($this->getSubType() == 'slider' && isset($options['slider']) && $this->getConfiguration('calculValueOffset') != '') {
				$options['slider'] = jeedom::evaluateExpression(str_replace('#value#', $options['slider'], $this->getConfiguration('calculValueOffset')));
			}
			if ($this->getConfiguration('timeline::enable')) {
				$timeline = new timeline();
				$timeline->setType('cmd');
				$timeline->setSubtype('action');
				$timeline->setLink_id($this->getId());
				$timeline->setFolder($this->getConfiguration('timeline::folder'));
				$timeline->setName($this->getHumanName(true, true));
				$timeline->save();
			}
			if ($this->isAlreadyInStateAllow() && $this->alreadyInState($options)) {
				if (is_array($options) && ((count($options) > 1 && isset($options['uid'])) || count($options) > 0)) {
					log::add('event', 'info', $GLOBALS['JEEDOM_SCLOG_TEXT']['execCmd']['txt'] . $this->getHumanName() . ' ' . __('avec les paramètres', __FILE__) . ' ' . json_encode($options) . ' ' . __('(ignorée)', __FILE__));
				} else {
					log::add('event', 'info', $GLOBALS['JEEDOM_SCLOG_TEXT']['execCmd']['txt'] . $this->getHumanName() . ' ' . __('(ignorée)', __FILE__));
				}
				return;
			}
			if (is_array($options) && ((count($options) > 1 && isset($options['uid'])) || count($options) > 0)) {
				log::add('event', 'info', $GLOBALS['JEEDOM_SCLOG_TEXT']['execCmd']['txt'] . $this->getHumanName() . ' ' . __('avec les paramètres', __FILE__) . ' ' . json_encode($options));
			} else {
				log::add('event', 'info', $GLOBALS['JEEDOM_SCLOG_TEXT']['execCmd']['txt'] . $this->getHumanName());
			}


			$this->preExecCmd($options);
			$value = $this->formatValue($this->execute($options), $_quote);
			$cmdValue = $this->getCmdValue();
			if (is_object($cmdValue)) {
				$cmdValue->setCache('lastAction', date('Y-m-d H:i:s'));
			}
			$this->postExecCmd($options);
			$usage = $this->getCache(array('usage::automation', 'usage::ui'));
			if (isset($_options['user_login'])) {
				$usage['usage::ui'] = ($usage['usage::ui'] === '') ? 0 : $usage['usage::ui'];
				$this->setCache('usage::ui', $usage['usage::ui'] + 1);
			} else {
				$usage['usage::automation'] = ($usage['usage::automation'] === '') ? 0 : $usage['usage::automation'];
				$this->setCache('usage::automation', $usage['usage::automation'] + 1);
			}
		} catch (Exception $e) {
			$type = $eqLogic->getEqType_name();
			if (config::byKey('numberOfTryBeforeEqLogicDisable') > 0 && $eqLogic->getConfiguration('nerverFail') != 1) {
				$numberTryWithoutSuccess = $eqLogic->getStatus('numberTryWithoutSuccess', 0);
				$eqLogic->setStatus('numberTryWithoutSuccess', $numberTryWithoutSuccess);
				if ($numberTryWithoutSuccess >= config::byKey('numberOfTryBeforeEqLogicDisable')) {
					$message = __('Désactivation de', __FILE__) . ' <a href="' . $eqLogic->getLinkToConfiguration() . '"> ' . $eqLogic->getName() . '</a> ' . __('car il n\'a pas répondu ou mal répondu lors des 3 derniers essais', __FILE__);
					$action = '<a href="/' . $eqLogic->getLinkToConfiguration() . '">' . __('Equipement', __FILE__) . '</a>';
					message::add($type, $message, $action);
					$eqLogic->setIsEnable(0);
					$eqLogic->save();
				}
			}
			log::add($type, 'error', __('Erreur exécution de la commande', __FILE__) . ' ' . $this->getHumanName() . ' : ' . $e->getMessage());
			throw $e;
		}
		if ($options !== null && $this->getValue() == '') {
			if (isset($options['slider'])) {
				$this->setConfiguration('lastCmdValue', $options['slider']);
				$this->save();
			}
			if (isset($options['color'])) {
				$this->setConfiguration('lastCmdValue', $options['color']);
				$this->save();
			}
		}
		if ($this->getConfiguration('updateCmdId') != '') {
			$cmd = cmd::byId($this->getConfiguration('updateCmdId'));
			if (is_object($cmd)) {
				$value = $this->getConfiguration('updateCmdToValue');
				switch ($this->getSubType()) {
					case 'slider':
						$value = str_replace('#slider#', $options['slider'], $value);
						break;
					case 'color':
						$value = str_replace('#color#', $options['color'], $value);
						break;
					case 'select':
						$value = str_replace('#select#', $options['select'], $value);
						break;
					case 'message':
						$value = str_replace('#message#', $options['message'], $value);
						break;
				}
				$cmd->event($value);
			}
		}
		return $value;
	}

	// Used by modals eqLogic.dashboard.edit and cmd.configure
	public function getWidgetsSelectOptions($_version = 'dashboard', $_availWidgets = false) {
		if (!$_availWidgets) {
			$_availWidgets = self::availableWidget($_version);
		}
		if ($this->getTemplate($_version) == 'default') {
			$this->setTemplate($_version, 'core::default');
			$this->save(true);
		}
		$display = '<option value="core::default">' . __('Défaut', __FILE__) . '</option>';
		return $display .= self::getSelectOptionsByTypeAndSubtype($this->getType(), $this->getSubType(), $_version, $_availWidgets);
	}

	public function getGenericTypeSelectOptions() {
		$display = '<option value="">{{Aucun}}</option>';
		$groups = array();
		foreach ((config::getGenericTypes(false)['byType']) as $key => $info) {
			if (strtolower($this->getType()) != strtolower($info['type'])) {
				continue;
			}
			if (isset($info['subtype']) && !in_array($this->getSubType(), $info['subtype'])) {
				continue;
			}
			$info['key'] = $key;
			if (!isset($groups[$info['family']])) {
				$groups[$info['family']][0] = $info;
			} else {
				array_push($groups[$info['family']], $info);
			}
		}
		ksort($groups);
		$optgroup = '';
		foreach ($groups as $group) {
			usort($group, function ($a, $b) {
				return strcmp($a['name'], $b['name']);
			});
			foreach ($group as $key => $info) {
				if ($key == 0) {
					$optgroup .= '<optgroup label="' . $info['family'] . '">';
				}
				$name = $info['name'];
				$optgroup .= '<option value="' . $info['key'] . '">' . $name . '</option>';
			}
			$optgroup .= '</optgroup>';
		}
		if ($optgroup != '') $display .= $optgroup;
		return $display;
	}

	public function getWidgetHelp($_version = 'dashboard', $_widgetName = '') {
		$widget = $this->getWidgetTemplateCode($_version, false, $_widgetName);
		$widgetCode = $widget['template'];
		$isCorewidget = $widget['isCoreWidget'];

		if (strpos($widgetCode, '</template>') !== false) {
			$widgetHelp = explode('</template>', $widgetCode)[0];
			$widgetHelp = explode('<template>', $widgetHelp)[1];
			if ($widgetHelp == '') {
				return '<em>' . __('Aucun paramètre optionnel disponible.', __FILE__) . '</em>';
			} else {
				$widgetHelp = strip_tags($widgetHelp, '<div>');
				if ($isCorewidget) {
					return translate::exec($widgetHelp, 'core/template/widgets.html');
				} else {
					return translate::exec($widgetHelp, $widget['widgetName']);
				}
			}
		} else {
			return '<em>' . __('Aucune description trouvée pour ce Widget.', __FILE__) . '</em>';
		}
	}

	public function cleanWidgetCode($_template) {
		$_template = preg_replace('/<template>[\s\S]+?<\/template>/', '', $_template);
		$_template = str_replace(array('<template>', '</template>'), '', $_template);
		return $_template;
	}

	public function getWidgetTemplateCode($_version = 'dashboard', $_clean = true, $_widgetName = '') {
		global $JEEDOM_INTERNAL_CONFIG;
		$_version = jeedom::versionAlias($_version);
		$replace = null;
		$widget_template = $JEEDOM_INTERNAL_CONFIG['cmd']['widgets'];
		$widget_name = ($_widgetName == '') ? $this->getTemplate($_version, config::byKey('widget::default::cmd::' . $this->getType() . '::' . $this->getSubType())) : $_widgetName;
		if ($widget_name == 'default' || $widget_name == 'core::default') {
			$widget_name = config::byKey('widget::default::cmd::' . $this->getType() . '::' . $this->getSubType());
		}

		if (strpos($widget_name, '::') !== false) {
			$name = explode('::', $widget_name);
			$widget_name = $name[1];
			if ($name[0] == 'custom') {
				$widget = widgets::byTypeSubtypeAndName($this->getType(), $this->getSubType(), $name[1]);
				if (is_object($widget)) {
					$widget_template = array(
						$this->getType() => array(
							$this->getSubType() => array(
								$name[1] => array(
									'replace' => $widget->getReplace(),
									'test' => $widget->getTest(),
									'template' => $widget->getTemplate()
								)
							)
						)
					);
				}
			} elseif ($name[0] == 'customtemp') {
				$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.' . $widget_name;
				$path = __DIR__ . '/../../data/customTemplates/' . $_version . '/' . $template_name;
				if (file_exists($path . '.html')) {
					$template = file_get_contents($path . '.html');
					if ($_clean) {
						$template = $this->cleanWidgetCode($template);
					}
					//return widgetName key for translate:
					return array('template' => $template, 'isCoreWidget' => false, 'widgetName' => 'customtemp::' . $template_name);
				}
			} elseif ($name[0] != 'core') {
				$plugin_id  = $name[0];
				if (method_exists($plugin_id, 'templateWidget')) {
					$widget_template = $plugin_id::templateWidget();
				}
			}
		}
		$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.' . $widget_name;
		if (isset($widget_template[$this->getType()][$this->getSubType()][$widget_name])) {
			$template_conf = $widget_template[$this->getType()][$this->getSubType()][$widget_name];
			$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.' . $template_conf['template'];
			if (isset($template_conf['replace']) && is_array($template_conf['replace']) && count($template_conf['replace']) > 0) {
				$replace = $template_conf['replace'];
				foreach ($replace as &$value) {
					$value = str_replace('#value#', '"+_options.display_value+"', str_replace('"', "'", $value));
				}
			} else {
				$replace = array();
			}


			$replace['#test#'] = '';
			if (isset($template_conf['test']) && is_array($template_conf['test']) && count($template_conf['test']) > 0) {
				$i = 0;
				$replace['#change_theme#'] = '';
				foreach ($template_conf['test'] as &$test) {
					if (!isset($test['operation'])) {
						continue;
					}
					if (!isset($test['state_light'])) {
						$test['state_light'] = '';
					}
					if (!isset($test['state_dark'])) {
						$test['state_dark'] = '';
					}
					$test['state_light'] = str_replace(array('#value#', '#state#', '#unite#', '#raw_unite#'), array('"+_options.value+"', '"+_options.display_value+"', '"+_options.unit+"', '"+_options.raw_unit+"'), str_replace('"', "'", $test['state_light']));
					$test['state_dark'] = str_replace(array('#value#', '#state#', '#unite#', '#raw_unite#'), array('"+_options.value+"', '"+_options.display_value+"', '"+_options.unit+"', '"+_options.raw_unit+"'), str_replace('"', "'", $test['state_dark']));
					$test['operation'] = str_replace('"', "'", str_replace('#value#', '_options.value', $test['operation']));

					//ltrim avoid js variable starting with # error
					if ($_version == 'dashboard') {
						$replace['#test#'] .= 'var cmdjs = isElement_jQuery(cmd) ? cmd[0] : cmd' . "\n";
						$replace['#test#'] .= 'if (' . ltrim($test['operation'], '#') . ') {' . "\n";
						$replace['#test#'] .= 'cmdjs.setAttribute("data-state", ' . $i . ')' . "\n";
						$replace['#test#'] .= 'state = jeedom.widgets.getThemeImg("' . $test['state_light'] . '", "' . $test['state_dark'] . '")' . "\n";
						$replace['#test#'] .= "}\n";

						$replace['#change_theme#'] .= 'var cmdjs = isElement_jQuery(cmd) ? cmd[0] : cmd' . "\n";
						$replace['#change_theme#'] .= 'if (cmdjs.getAttribute("data-state") == ' . $i . ') {' . "\n";
						$replace['#change_theme#'] .= 'state = jeedom.widgets.getThemeImg("' . $test['state_light'] . '", "' . $test['state_dark'] . '")' . "\n";
						$replace['#change_theme#'] .= "}\n";
					} else {  //Deprecated, keep for mobile during transition
						$replace['#test#'] .= 'if (' . ltrim($test['operation'], '#') . ') {' . "\n";
						$replace['#test#'] .= 'cmd.attr("data-state", ' . $i . ')' . "\n";
						$replace['#test#'] .= 'state = jeedom.widgets.getThemeImg("' . $test['state_light'] . '", "' . $test['state_dark'] . '")' . "\n";
						$replace['#test#'] .= "}\n";

						$replace['#change_theme#'] .= 'if (cmd.attr("data-state") == ' . $i . ') {' . "\n";
						$replace['#change_theme#'] .= 'state = jeedom.widgets.getThemeImg("' . $test['state_light'] . '", "' . $test['state_dark'] . '")' . "\n";
						$replace['#change_theme#'] .= "}\n";
					}

					$i++;
				}
			}
		}
		$template = '';
		if (!isset(self::$_templateArray[$_version . '::' . $template_name])) {
			$template = getTemplate('core', $_version, $template_name);
			if ($template == '') {
				if (config::byKey('active', 'widget') == 1) {
					$template = getTemplate('core', $_version, $template_name, 'widget');
				}
				$template = getTemplate('core', $_version, $template_name, $this->getEqType());
				if ($template == '') {
					foreach (plugin::listPlugin(true) as $plugin) {
						$template = getTemplate('core', $_version, $template_name, $plugin->getId());
						if ($template != '') {
							break;
						}
					}
				}
				if ($template == '') {
					if ($_version == 'scenario') {
						$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.default';
					} else {
						$defaultConfiguration = config::getDefaultConfiguration();
						$template_name = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.' . explode('::', $defaultConfiguration['core']['widget::default::cmd::' . $this->getType() . '::' . $this->getSubType()])[1];
					}
					$template = getTemplate('core', $_version, $template_name);
				}
			}
			self::$_templateArray[$_version . '::' . $template_name] = $template;
		} else {
			$template = self::$_templateArray[$_version . '::' . $template_name];
		}
		if ($replace != null && is_array($replace)) {
			$template = str_replace(array_keys($replace), $replace, $template);
		}

		if ($_clean) {
			$template = $this->cleanWidgetCode($template);
		}
		return array('template' => $template, 'isCoreWidget' => true);
	}

	public static function autoValueArray($_value, $_decimal = 99, $_unit = '', $_space = False) {
		$_unit = str_replace("\"", "", $_unit);
		$_unit = str_replace("\'", "", $_unit);
		if (isset(self::$_unite_conversion[$_unit])) {
			$mod = self::$_unite_conversion[$_unit][0];
			$prefix = array_slice(self::$_unite_conversion[$_unit], 1);
			$myval = self::autoValueFormat($_value, $mod, count($prefix) - 1);
			return array(round($myval[0], $_decimal), ($_space ? ' ' : '') . $prefix[$myval[1]]);
		} else {
			return array(round($_value, $_decimal), $_unit);
		}
	}

	private static function autoValueFormat($_value, $_mod = 1000, $_maxdiv = 10) {
		if ($_value < 0) {
			$val = floatval(-$_value);
		} else {
			$val = floatval($_value);
		}

		$div = 0;
		while ($val > $_mod && $div < $_maxdiv) {
			$val = floatval($val / $_mod);
			$div++;
		}
		if ($_value < 0) {
			$val = -$val;
		}
		return array($val, $div);
	}

	public function toHtml($_version = 'dashboard', $_options = '') {
		$_version = jeedom::versionAlias($_version);
		$html = '';
		$replace = array(
			'#id#' => $this->getId(),
			'#name#' => $this->getName(),
			'#name_display#' => ($this->getDisplay('icon') != '') ? $this->getDisplay('icon') : $this->getName(),
			'#history#' => '',
			'#hide_history#' => 'hidden',
			'#unite#' => $this->getUnite(),
			'#raw_unite#' => $this->getUnite(),
			'#minValue#' => $this->getConfiguration('minValue', 0),
			'#maxValue#' => $this->getConfiguration('maxValue', 100),
			'#logicalId#' => $this->getLogicalId(),
			'#uid#' => 'cmd' . $this->getId() . eqLogic::UIDDELIMITER . mt_rand() . eqLogic::UIDDELIMITER,
			'#version#' => $_version,
			'#eqLogic_id#' => $this->getEqLogic_id(),
			'#generic_type#' => $this->getGeneric_type(),
			'#hide_name#' => '',
			'#value_history#' => ''
		);
		if ($this->getConfiguration('listValue', '') != '') {
			$listOption = '';
			$elements = explode(';', $this->getConfiguration('listValue', ''));
			$foundSelect = false;
			foreach ($elements as $element) {
				$coupleArray = explode('|', $element);
				$cmdValue = $this->getCmdValue();
				if (is_object($cmdValue) && $cmdValue->getType() == 'info') {
					if ($cmdValue->execCmd() == $coupleArray[0] || $cmdValue->execCmd() == $coupleArray[1]) {
						$listOption .= '<option value="' . $coupleArray[0] . '" selected>' . $coupleArray[1] . '</option>';
						$foundSelect = true;
					} else {
						$listOption .= '<option value="' . $coupleArray[0] . '">' . $coupleArray[1] . '</option>';
					}
				} else {
					if (isset($coupleArray[1])) {
						$listOption .= '<option value="' . $coupleArray[0] . '">' . $coupleArray[1] . '</option>';
					} else {
						$listOption .= '<option value="' . $coupleArray[0] . '">' . $coupleArray[0] . '</option>';
					}
				}
			}
			if (!$foundSelect) {
				$listOption = '<option value="">Aucun</option>' . $listOption;
			}
			$replace['#listValue#'] = $listOption;
		}
		if ($this->getDisplay('showNameOn' . $_version, 1) == 0) {
			$replace['#hide_name#'] = 'hidden';
		}
		if ($this->getDisplay('showIconAndName' . $_version, 0) == 1) {
			$replace['#name_display#'] = $this->getDisplay('icon') . ' ' . $this->getName();
		}
		$widget = $this->getWidgetTemplateCode($_version);
		$template = $widget['template'];
		$isCorewidget = $widget['isCoreWidget'];
		if ($_version == 'scenario' && $isCorewidget) {
			$widget['widgetName'] = 'cmd.' . $this->getType() . '.' . $this->getSubType() . '.default';
		}

		if ($_options != '') {
			$options = jeedom::toHumanReadable($_options);
			$options = is_json($options, $options);
			if (is_array($options)) {
				foreach ($options as $key => $value) {
					$replace['#' . $key . '#'] = $value;
				}
			}
		}
		if ($this->getType() == 'info') {
			$replace['#value#'] = '';
			$replace['#tendance#'] = '';
			$replace['#value#'] = $this->execCmd();
			if ($this->getSubType() == 'binary' && $this->getDisplay('invertBinary') == 1) {
				$replace['#value#'] = ($replace['#value#'] == 1) ? 0 : 1;
			}
			if ($this->getSubType() == 'numeric' && trim($replace['#value#']) === '') {
				$replace['#value#'] = 0;
			}
			if ($this->getSubType() == 'numeric' && trim($replace['#unite#']) != '') {
				if ($this->getConfiguration('historizeRound') !== '' && is_numeric($this->getConfiguration('historizeRound')) && $this->getConfiguration('historizeRound') >= 0) {
					$round = $this->getConfiguration('historizeRound');
				} else {
					$round = 99;
				}
				$valueInfo = self::autoValueArray($replace['#value#'], $round, $replace['#unite#']);
				$replace['#state#'] = $valueInfo[0];
				$replace['#unite#'] = $valueInfo[1];
			}
			if (!isset($replace['#state#'])) {
				$replace['#state#'] = $replace['#value#'];
			}
			if ($this->getSubType() == 'string') {
				$replace['#value#'] = str_replace("\n", '<br/>', addslashes($replace['#value#']));
			}
			if (method_exists($this, 'formatValueWidget')) {
				$replace['#state#'] = $this->formatValueWidget($replace['#state#']);
			}

			$replace['#state#'] = str_replace(array("\'", "'", "\n"), array("'", "\'", '<br/>'), $replace['#state#']);
			$replace['#collectDate#'] = $this->getCollectDate();
			$replace['#valueDate#'] = $this->getValueDate();
			$replace['#alertLevel#'] = $this->getCache('alertLevel', 'none');
			if ($this->getIsHistorized() == 1) {
				$replace['#history#'] = 'history cursor';
				if (config::byKey('displayStatsWidget') == 1 && strpos($template, '#hide_history#') !== false && $this->getDisplay('showStatsOn' . $_version, 1) == 1) {
					$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . config::byKey('historyCalculPeriod') . ' hour'));
					$replace['#hide_history#'] = '';
					$historyStatistique = $this->getStatistique($startHist, date('Y-m-d H:i:s'));
					if ($historyStatistique['avg'] == 0 && $historyStatistique['min'] == 0 && $historyStatistique['max'] == 0) {
						$replace['#averageHistoryValue#'] = round(intval($replace['#state#']), 1);
						$replace['#minHistoryValue#'] = round(intval($replace['#state#']), 1);
						$replace['#maxHistoryValue#'] = round(intval($replace['#state#']), 1);
					} else {
						$replace['#averageHistoryValue#'] = round($historyStatistique['avg'], 1);
						$replace['#minHistoryValue#'] = round($historyStatistique['min'], 1);
						$replace['#maxHistoryValue#'] = round($historyStatistique['max'], 1);
					}
					$startHist = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -' . config::byKey('historyCalculTendance') . ' hour'));
					$tendance = $this->getTendance($startHist, date('Y-m-d H:i:s'));
					if ($tendance > config::byKey('historyCalculTendanceThresholddMax')) {
						$replace['#tendance#'] = 'fas fa-arrow-up';
					} else if ($tendance < config::byKey('historyCalculTendanceThresholddMin')) {
						$replace['#tendance#'] = 'fas fa-arrow-down';
					} else {
						$replace['#tendance#'] = 'fas fa-minus';
					}
				}
			}
			$parameters = $this->getDisplay('parameters');
			if (is_array($parameters)) {
				foreach ($parameters as $key => $value) {
					$replace['#' . $key . '#'] = $value;
				}
			}
		}

		if ($this->getType() == 'action') {
			$cmdValue = $this->getCmdValue();
			if (is_object($cmdValue) && $cmdValue->getType() == 'info') {
				$replace['#value_id#'] = $cmdValue->getId();
				$replace['#state#'] = $cmdValue->execCmd();
				$replace['#valueName#'] = $cmdValue->getName();
				$replace['#unite#'] = $cmdValue->getUnite();
				$replace['#collectDate#'] = $cmdValue->getCollectDate();
				$replace['#valueDate#'] = $cmdValue->getValueDate();
				$replace['#value_history#'] = ($cmdValue->getIsHistorized() == 1) ? 'history cursor' : '';
				$replace['#alertLevel#'] = $cmdValue->getCache('alertLevel', 'none');
				if (trim($replace['#state#']) === '' && ($cmdValue->getSubtype() == 'binary' || $cmdValue->getSubtype() == 'numeric')) {
					$replace['#state#'] = 0;
				}
				if ($cmdValue->getSubType() == 'binary' && $cmdValue->getDisplay('invertBinary') == 1) {
					$replace['#state#'] = ($replace['#state#'] == 1) ? 0 : 1;
				}
			} else {
				$replace['#state#'] = ($this->getLastValue() !== null) ? $this->getLastValue() : '';
				$replace['#valueName#'] = $this->getName();
				$replace['#unite#'] = $this->getUnite();
			}
			$replace['#state#'] = str_replace(array("\'", "'"), array("'", "\'"), $replace['#state#']);

			$html .= template_replace($replace, $template);
			if (trim($html) == '') {
				return $html;
			}

			$replace['#title_placeholder#'] = $this->getDisplay('title_placeholder', __('Titre', __FILE__));
			$replace['#message_placeholder#'] = $this->getDisplay('message_placeholder', __('Message', __FILE__));
			$replace['#message_cmd_type#'] = $this->getDisplay('message_cmd_type', 'info');
			$replace['#message_cmd_subtype#'] = $this->getDisplay('message_cmd_subtype', '');
			$replace['#message_disable#'] = $this->getDisplay('message_disable', 0);
			$replace['#title_disable#'] = $this->getDisplay('title_disable', 0);
			$replace['#title_color#'] = $this->getDisplay('title_color', 0);
			$replace['#title_possibility_list#'] = str_replace("'", "\'", $this->getDisplay('title_possibility_list', ''));
			$replace['#slider_placeholder#'] = $this->getDisplay('slider_placeholder', __('Valeur', __FILE__));
			$replace['#other_tooltips#'] = ($replace['#name#'] != $this->getName()) ? $this->getName() : '';

			$parameters = $this->getDisplay('parameters');
			if (is_array($parameters)) {
				foreach ($parameters as $key => $value) {
					$replace['#' . $key . '#'] = $value;
				}
			}

			if (!isset($replace['#title#'])) {
				$replace['#title#'] = '';
			} else {
				$replace['#title#'] = htmlspecialchars($replace['#title#']);
			}
			if (!isset($replace['#message#'])) {
				$replace['#message#'] = '';
			}
			if (!isset($replace['#slider#'])) {
				$replace['#slider#'] = '';
			}
			if (!isset($replace['#color#'])) {
				$replace['#color#'] = '';
			}
		}

		$template = template_replace($replace, $template);
		if ($isCorewidget && $_version == 'scenario') {
			return translate::exec($template, 'core/template/scenario/' . $widget['widgetName'] . '.html');
		}
		if ($isCorewidget) {
			return translate::exec($template, 'core/template/widgets.html');
		}
		if (isset($widget['widgetName'])) {
			return translate::exec($template, $widget['widgetName']);
		}
		return $template;
	}

	public function event($_value, $_datetime = null, $_loop = 1) {
		if ($_loop > 4 || $this->getType() != 'info') {
			return;
		}
		$eqLogic = $this->getEqLogic();
		$object = $eqLogic->getObject();
		if (!is_object($eqLogic) || $eqLogic->getIsEnable() == 0) {
			return;
		}
		$value = $this->formatValue($_value);

		if ($this->getSubType() == 'numeric' && ($value > $this->getConfiguration('maxValue', $value) || $value < $this->getConfiguration('minValue', $value)) && strpos($value, 'error') === false) {
			log::add('cmd', 'info', __('La commande n\'est pas dans la plage de valeur autorisée :', __FILE__) . ' ' . $this->getHumanName() . ' => ' . $value);
			return;
		}
		if ($this->getConfiguration('denyValues') != '' && in_array($value, explode(';', $this->getConfiguration('denyValues')))) {
			return;
		}
		$oldValue = $this->execCmd();
		$repeat = ($oldValue === $value && $oldValue !== '' && $oldValue !== null);
		$this->setCollectDate(($_datetime !== null && $_datetime !== false) ? $_datetime : date('Y-m-d H:i:s'));
		$this->setCache('collectDate', $this->getCollectDate());
		$this->setValueDate(($repeat) ? $this->getValueDate() : $this->getCollectDate());
		$eqLogic->setStatus(array('lastCommunication' => $this->getCollectDate(), 'timeout' => 0));
		$unit = $this->getUnite();
		$raw_unit = $this->getUnite();
		$display_value = $value;
		if ($this->getSubType() == 'binary' && $this->getDisplay('invertBinary') == 1) {
			$display_value = ($display_value == 1) ? 0 : 1;
		} else if ($this->getSubType() == 'numeric' && trim($value) === '') {
			$display_value = 0;
		} else if ($this->getSubType() == 'binary' && trim($value) === '') {
			$display_value = 0;
		}
		if ($this->getSubType() == 'numeric') {
			$valueInfo = self::autoValueArray($display_value, $this->getConfiguration('historizeRound', 99), $this->getUnite());
			$display_value = $valueInfo[0];
			$unit = $valueInfo[1];
		}
		if (method_exists($this, 'formatValueWidget')) {
			$display_value = $this->formatValueWidget($display_value);
		}
		if ($repeat && $this->getConfiguration('repeatEventManagement', 'never') == 'never') {
			$this->addHistoryValue($value, $this->getCollectDate());
			event::adds('cmd::update', array(array('cmd_id' => $this->getId(), 'value' => (strlen($value) < 3096) ? $value : substr($value,0,3096), 'display_value' => (strlen($display_value) < 3096) ? $display_value :  substr($display_value,0,3096), 'unit' => $unit, 'raw_unit' => $raw_unit, 'valueDate' => $this->getValueDate(), 'collectDate' => $this->getCollectDate())));
			return;
		}
		$_loop++;
		if ($repeat && $this->getConfiguration('repeatEventManagement', 'never') == 'always') {
			$repeat = false;
		}
		$message = __('Evènement sur la commande', __FILE__) . ' ' . $this->getHumanName() . ' ' . __('valeur :', __FILE__) . ' ' . $value . $this->getUnite();
		if ($repeat) {
			$message .= ' (répétition)';
		}
		log::add('event', 'info', $message);
		$events = array();
		if (!$repeat) {
			$this->setCache(array('value' => $value, 'valueDate' => $this->getValueDate()));
			scenario::check($this, false, $this->getGeneric_type(), $object, $value);
			$level = $this->checkAlertLevel($value);
			$events[] = array('cmd_id' => $this->getId(), 'value' => (strlen($value) < 3096) ? $value : substr($value,0,3096), 'display_value' => (strlen($display_value) < 3096) ? $display_value :  substr($display_value,0,3096), 'unit' => $unit, 'raw_unit' => $raw_unit, 'valueDate' => $this->getValueDate(), 'collectDate' => $this->getCollectDate(), 'alertLevel' => $level);
			$foundInfo = false;
			$value_cmd = self::byValue($this->getId(), null, true);
			if (is_array($value_cmd) && count($value_cmd) > 0) {
				foreach ($value_cmd as $cmd) {
					if ($cmd->getType() == 'action') {
						$events[] = array('cmd_id' => $cmd->getId(), 'value' => (strlen($value) < 3096) ? $value : substr($value,0,3096), 'display_value' => (strlen($display_value) < 3096) ? $display_value :  substr($display_value,0,3096), 'valueDate' => $this->getValueDate(), 'collectDate' => $this->getCollectDate(), 'unit' => $unit);
					} else {
						if ($_loop > 1) {
							$cmd->event($cmd->execute(), null, $_loop);
						} else {
							$foundInfo = true;
						}
					}
				}
			}
			if ($foundInfo) {
				listener::backgroundCalculDependencyCmd($this->getId());
			}
		} else {
			$events[] = array('cmd_id' => $this->getId(), 'value' => (strlen($value) < 3096) ? $value : substr($value,0,3096), 'display_value' => (strlen($display_value) < 3096) ? $display_value :  substr($display_value,0,3096), 'unit' => $unit, 'raw_unit' => $raw_unit, 'valueDate' => $this->getValueDate(), 'collectDate' => $this->getCollectDate());
		}
		if (count($events) > 0) {
			event::adds('cmd::update', $events);
		}
		if (!$repeat) {
			listener::check($this->getId(), $value, $this->getCollectDate(),$this);
			jeeObject::checkSummaryUpdate($this->getId());
		}
		$this->addHistoryValue($value, $this->getCollectDate());
		$this->checkReturnState($value);
		if (!$repeat) {
			$this->checkCmdAlert($value);
			if (isset($level) && $level != $this->getCache('alertLevel')) {
				$this->actionAlertLevel($level, $value);
			}
			if ($this->getConfiguration('timeline::enable')) {
				$timeline = new timeline();
				$timeline->setType('cmd');
				$timeline->setSubtype('info');
				$timeline->setLink_id($this->getId());
				$timeline->setName($this->getHumanName(true, true));
				$timeline->setFolder($this->getConfiguration('timeline::folder'));
				$timeline->setDatetime($this->getValueDate());
				$timeline->setOptions(array('value' => $value . $this->getUnite(), 'cmdType' => $this->getSubType()));
				$timeline->save();
			}
			$this->pushUrl($value);
			$this->pushInflux($value);
			if($this->getGeneric_type() == 'BATTERY' && $this->getUnite() == '%'){
				$this->batteryStatus($value);
			}
		}
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
			$cron->setSchedule(cron::convertDateToCron($next));
			$cron->setLastRun(date('Y-m-d H:i:s'));
			$cron->save();
		}
	}

	public function checkCmdAlert($_value) {
		if ($this->getConfiguration('jeedomCheckCmdOperator') == '' || $this->getConfiguration('jeedomCheckCmdTest') == '' || !is_numeric($this->getConfiguration('jeedomCheckCmdTime', 0))) {
			return;
		}
		$checkCmdValue = $this->getConfiguration('jeedomCheckCmdTest');
		if ($this->getSubType() == 'string') {
			$checkCmdValue = '"' . trim($checkCmdValue, '"\'') . '"';
			$_value = '"' . trim($_value, '"\'') . '"';
		}
		$check = jeedom::evaluateExpression($_value . $this->getConfiguration('jeedomCheckCmdOperator') . $checkCmdValue);
		if ($check == 1 || $check || $check == '1') {
			if ($this->getConfiguration('jeedomCheckCmdTime', 0) == 0) {
				$this->executeAlertCmdAction();
				return;
			}
			$next = strtotime('+ ' . ($this->getConfiguration('jeedomCheckCmdTime') + 1) . ' minutes ' . date('Y-m-d H:i:s'));
			$cron = cron::byClassAndFunction('cmd', 'cmdAlert', array('cmd_id' => intval($this->getId())));
			if (!is_object($cron)) {
				$cron = new cron();
			} else {
				$nextRun = $cron->getNextRunDate();
				if ($nextRun !== false && $next > strtotime($nextRun) && strtotime($nextRun) > strtotime('now')) {
					return;
				}
			}
			$cron->setClass('cmd');
			$cron->setFunction('cmdAlert');
			$cron->setOnce(1);
			$cron->setOption(array('cmd_id' => intval($this->getId())));
			$cron->setSchedule(cron::convertDateToCron($next));
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
		if (!is_array($this->getConfiguration('actionCheckCmd'))) {
			return;
		}
		foreach ($this->getConfiguration('actionCheckCmd') as $action) {
			try {
				$options = array();
				if (isset($action['options'])) {
					$options = $action['options'];
				}
				$options['source'] = $this->getHumanName();
				scenarioExpression::createAndExec('action', $action['cmd'], $options);
			} catch (Exception $e) {
				log::add('cmd', 'error', __('Erreur lors de l\'exécution de', __FILE__) . ' ' . $action['cmd'] . __('. Détails :', __FILE__) . ' ' . $e->getMessage());
			}
		}
	}

	public function checkAlertLevel($_value, $_allowDuring = true, $_checkLevel = 'none') {
		if ($this->getType() != 'info' || ($this->getAlert('warningif') == '' && $this->getAlert('dangerif') == '')) {
			return 'none';
		}
		global $JEEDOM_INTERNAL_CONFIG;
		$currentLevel = 'none';
		$returnLevel = 'none';
		foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
			if ($this->getAlert($level . 'if') != '') {
				$check = jeedom::evaluateExpression(str_replace('#value#', $_value, $this->getAlert($level . 'if')));
				if ($check == 1 || $check || $check == '1') {
					$currentLevel = $level;
					if ($_allowDuring && $currentLevel != 'none' && $this->getAlert($currentLevel . 'during') != '' && $this->getAlert($currentLevel . 'during') > 0) {
						$cron = cron::byClassAndFunction('cmd', 'duringAlertLevel', array('cmd_id' => intval($this->getId()), 'level' => $currentLevel));
						$next = strtotime('+ ' . $this->getAlert($currentLevel . 'during', 1) . ' minutes ' . date('Y-m-d H:i:s'));
						if ($currentLevel != $this->getCache('alertLevel')) {
							if (!is_object($cron)) {
								if (!($currentLevel == 'warning' && $this->getCache('alertLevel') == 'danger')) {
									$cron = new cron();
									$cron->setClass('cmd');
									$cron->setFunction('duringAlertLevel');
									$cron->setOnce(1);
									$cron->setOption(array('cmd_id' => intval($this->getId()), 'level' => $currentLevel));
									$cron->setSchedule(cron::convertDateToCron($next));
									$cron->setLastRun(date('Y-m-d H:i:s'));
									$cron->save();
								} else { //Warning condition, cron doesn't exit but was danger, cron may expired
									$returnLevel = $currentLevel;
								}
							}
						} else { //No cron but was at this level
							$returnLevel = $this->getCache('alertLevel');
						}
					}
					if (!($_allowDuring  && $this->getAlert($currentLevel . 'during') != '' && $this->getAlert($currentLevel . 'during') > 0)) { //Alert without delay or cron executing
						if ($_checkLevel == $currentLevel || $_checkLevel == 'none') { //If was a cron, only check asked level
							if (!($_checkLevel == 'warning' && $this->getCache('alertLevel') == 'danger')) {
								$returnLevel = $currentLevel;
							} else { //Cron ask warning, but ever in danger
								$returnLevel = $this->getCache('alertLevel');
							}
						}
					}
				} else { //Not in condition, delete cron
					$cron = cron::byClassAndFunction('cmd', 'duringAlertLevel', array('cmd_id' => intval($this->getId()), 'level' => $level));
					if (is_object($cron)) {
						$cron->remove(false);
					}
				}
			}
		}
		return $returnLevel;
	}

	public static function duringAlertLevel($_options) {
		$cmd = cmd::byId($_options['cmd_id']);
		if (!is_object($cmd)) {
			return;
		}
		if ($cmd->getType() != 'info') {
			return;
		}
		if (!is_object($cmd->getEqLogic()) || $cmd->getEqLogic()->getIsEnable() == 0) {
			return;
		}
		$value = $cmd->execCmd();
		$level = $cmd->checkAlertLevel($value, false, $_options['level']);
		if ($level != 'none') {
			$cmd->actionAlertLevel($level, $value);
		}
	}

	public function actionAlertLevel($_level, $_value) {
		if ($this->getType() != 'info') {
			return;
		}
		if ($_level == $this->getCache('alertLevel')) {
			return;
		}
		global $JEEDOM_INTERNAL_CONFIG;
		$this->setCache('alertLevel', $_level);
		$eqLogic = $this->getEqLogic();
		if ($eqLogic->getIsEnable() == 0) {
			return;
		}
		if ($this->getAlert($_level . 'during') != '' && $this->getAlert($_level . 'during') > 0 && $eqLogic->getStatus('enableDatime') != '' && strtotime($eqLogic->getStatus('enableDatime') . '+ ' . $this->getAlert($_level . 'during')) > strtotime('now')) {
			return;
		}
		$maxAlert = $eqLogic->getMaxCmdAlert();
		$prevAlert = $eqLogic->getAlert();
		if (!$_value) {
			$_value = $this->execCmd();
		}
		if ($_level != 'none') {
			$message = __('Alerte sur la commande', __FILE__) . ' ' . $this->getHumanName() . ' ' . __('niveau', __FILE__) . ' ' . $_level . ' ' . __('valeur :', __FILE__) . ' ' . $_value . trim(' ' . $this->getUnite());
			if ($this->getAlert($_level . 'during') != '' && $this->getAlert($_level . 'during') > 0) {
				$message .= ' ' . __('pendant plus de', __FILE__) . ' ' . $this->getAlert($_level . 'during') . ' ' . __('minute(s)', __FILE__);
			}
			$message .= ' => ' . jeedom::toHumanReadable(str_replace('#value#', $_value, $this->getAlert($_level . 'if')));
			log::add('event', 'info', $message);
			if (config::byKey('alert::addMessageOn' . ucfirst($_level)) == 1) {
				$action = '<a href="/' . $eqLogic->getLinkToConfiguration() . '">' . __('Equipement', __FILE__) . '</a>';
				message::add($eqLogic->getEqType_name(), $message, $action, 'alert_' . $this->getId() . '_' . strtotime('now') . '_' . rand(0, 999), true, 'alerting');
			}
			$cmds = explode(('&&'), config::byKey('alert::' . $_level . 'Cmd'));
			if (count($cmds) > 0 && trim(config::byKey('alert::' . $_level . 'Cmd')) != '') {
				foreach ($cmds as $id) {
					$cmd = cmd::byId(str_replace('#', '', $id));
					if (is_object($cmd)) {
						try {
							$cmd->execCmd(array(
								'title' => '[' . config::byKey('name', 'core', 'JEEDOM') . '] : ' . $message,
								'message' => config::byKey('name', 'core', 'JEEDOM') . ' : ' . $message,
							));
						} catch (Exception $e) {
							log::add('jeedomAlert', 'error', __('Erreur lors de l\'envoi de l\'alerte : ', __FILE__) . ' ' . $cmd->getHumanName() . '  => ' . $e->getMessage());
						}
					}
				}
			}
		} elseif ($this->getConfiguration('alert::messageReturnBack') == 1) {
			$message = __('Retour à la normale de ', __FILE__) . ' ' . $this->getHumanName() . ' ' . __('valeur :', __FILE__) . ' ' . $_value . trim(' ' . $this->getUnite());
			log::add('event', 'info', $message);
			$action = '<a href="/' . $this->getEqLogic()->getLinkToConfiguration() . '">' . __('Equipement', __FILE__) . '</a>';
			message::add($this->getEqLogic()->getEqType_name(), $message, $action, 'alertReturnBack_' . $this->getId() . '_' . strtotime('now') . '_' . rand(0, 999), true, 'alertingReturnBack');
		}

		if ($prevAlert != $maxAlert) {
			$status = array(
				'warning' => 0,
				'danger' => 0,
			);
			if ($maxAlert != 'none' && isset($JEEDOM_INTERNAL_CONFIG['alerts'][$maxAlert])) {
				$status[$maxAlert] = 1;
			}
			$eqLogic->setStatus($status);
			$eqLogic->refreshWidget();
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
			'#value#' => urlencode($_value),
			'#cmd_name#' => urlencode($this->getName()),
			'#cmd_id#' => $this->getId(),
			'#humanname#' => urlencode($this->getHumanName()),
			'#eq_name#' => urlencode($this->getEqLogic()->getName()),
			'"' => ''
		);
		$url = str_replace(array_keys($replace), $replace, scenarioExpression::setTags($url));
		log::add('event', 'info', __('Appels de l\'URL de push pour la commande', __FILE__) . ' ' . $this->getHumanName() . ' : ' . $url);
		$http = new com_http($url);
		$http->setLogError(false);
		try {
			$http->exec();
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur push sur :', __FILE__) . ' ' . $url . ' commande : ' . $this->getHumanName() . ' => ' . $e->getMessage());
		} catch (Error $e) {
			log::add('cmd', 'error', __('Erreur push sur :', __FILE__) . ' ' . $url . ' commande : ' . $this->getHumanName() . ' => ' . $e->getMessage());
		}
	}

	public function computeInfluxData($_value, $_timestamp = '') {
		$point = '';
		try {
			$cmdname = $this->getHumanName();
			$name = $this->getName();
			$eqLogic = $this->getEqLogic();
			$eqLogicName = $eqLogic->getName();
			$object = $eqLogic->getObject()->getName();
			$plugin = $eqLogic->getEqType_name();
			if ($this->getConfiguration('influx::namecmd', '') != '') {
				$name = $this->getConfiguration('influx::namecmd');
			}
			if ($this->getConfiguration('influx::nameEq', '') != '') {
				$eqLogicName = $this->getConfiguration('influx::nameEq');
			}
			$valName = $this->getConfiguration('influx::nameVal', '');
			$cleanName = str_replace(',', '\,', str_replace(' ', '\ ', $name));
			$genericType = $this->getGeneric_type();
			$genericName = 'Aucun';
			if ($genericType != '') {
				$genericName = jeedom::getConfiguration('cmd::generic_type')[$this->getGeneric_type()]['name'];
			}
			$subtype = $this->getSubType();
			if ($subtype == 'numeric') {
				$value = floatval($_value);
			} else if ($subtype == 'binary') {
				$value = intval($_value);
			} else {
				$value = $_value;
			}
			$tagArray = array(
				'box' => config::byKey('name', 'core'),
				'location' => $object,
				'equipement' => $eqLogicName,
				'plugin' => $plugin,
				'cmd' => $cmdname,
				'cmdId' => $this->getId(),
				'cmdname' => $this->getName(),
				'genericType' => $genericName
			);
			$valueArray = [];
			if ($valName != '') {
				$valueArray[$valName] = $value;
				$value = null;
			}
			if ($_timestamp == '') {
				$point = new InfluxDB\Point($cleanName, $value, $tagArray, $valueArray);
			} else {
				$point = new InfluxDB\Point($cleanName, $value, $tagArray, $valueArray, $_timestamp);
			}
			log::add('cmd', 'debug', 'Push influx for ' . $this->getHumanName() . ' : ' .  json_encode($tagArray, true));
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur computing influx sur :', __FILE__) . ' ' . ' commande : ' . $this->getHumanName() . ' => ' . $e->getMessage());
		}
		return $point;
	}

	public function getInflux($_cmdId = null) {
		try {
			if ($_cmdId) {
				$cmd = cmd::byId($_cmdId);
				$enabled = $cmd->getConfiguration('influx::enable');
				if (!$enabled) {
					return;
				}
			}
			$url = config::byKey('cmdInfluxURL');
			$port = config::byKey('cmdInfluxPort');
			$base = config::byKey('cmdInfluxTable');
			$user = config::byKey('cmdInfluxUser', 'core', '');
			$pass = config::byKey('cmdInfluxPass', 'core', '');
			if ($url == '' || $port == '') {
				return;
			}
			if ($base == '') {
				$base = 'Jeedom';
			}
			if ($user == '') {
				$client = new InfluxDB\Client($url, $port);
			} else {
				$client = new InfluxDB\Client($url, $port, $user, $pass);
			}
			$database = $client->selectDB($base);
			if (!$database->exists()) {
				$database->create();
			}
			return $database;
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur get influx database :', __FILE__) . ' ' . ' => ' . $e->getMessage());
		}
		return '';
	}

	public function pushInflux($_value = null) {
		try {
			$database = cmd::getInflux($this->getId());
			if ($database == '') {
				return;
			}
			if ($_value === null) {
				$_value = $this->execCmd();
			}
			$point = $this->computeInfluxData($_value);
			$result = $database->writePoints(array($point), 's');
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur push influx sur :', __FILE__) . ' ' . ' commande : ' . $this->getHumanName() . ' => ' . $e->getMessage());
		}
		return;
	}

	public function dropInfluxDatabase() {
		try {
			$database = cmd::getInflux();
			if ($database == '') {
				return;
			}
			$database->drop();
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur delete influx sur :', __FILE__) . ' ' . ' => ' . $e->getMessage());
		}
		return;
	}

	public function dropInflux() {
		try {
			$database = cmd::getInflux($this->getId());
			if ($database == '') {
				return;
			}
			$query = 'DROP SERIES WHERE cmdId=\'' . $this->getId() . '\'';
			$result = $database->query($query);
			log::add('cmd', 'debug', 'Delete influx for ' . $this->getHumanName());
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur delete influx sur :', __FILE__) . ' ' . ' commande : ' . $this->getHumanName() . ' => ' . $e->getMessage());
		}
		return;
	}

	public function historyInfluxAll() {
		cmd::historyInflux('all');
	}

	public function sendHistoryInflux($_params) {
		$cmds = array();
		if ($_params['cmd_id'] == 'all') {
			foreach (cmd::byTypeSubType('info') as $cmd) {
				if ($cmd->getConfiguration('influx::enable', false)) {
					$cmds[] = $cmd;
				}
			}
		} else {
			$cmds[] = cmd::byId($_params['cmd_id']);
		}
		try {
			foreach ($cmds as $cmd) {
				log::add('cmd', 'info', __('Envoie de l\'historique à influx :', __FILE__) . ' ' . ' commande : ' . $cmd->getHumanName());
				$database = cmd::getInflux($cmd->getId());
				if ($database == '') {
					return;
				}
				$oldest = $cmd->getOldest();
				$begin = date('Y-m-d H:i:s', strtotime('-60 days'));
				$now = date('Y-m-d H:i:s');
				if (count($oldest) > 0) {
					$begin = date('Y-m-d H:i:s', strtotime($oldest[0]->getDatetime()));
				}
				$end = $begin;
				while ($end < date('Y-m-d H:i:s', strtotime($now . ' +60 days'))) {
					$points = array();
					$history = $cmd->getHistory($begin, $end);
					foreach ($history as $point) {
						$value = $point->getValue();
						$timestamp = strtotime($point->getDatetime());
						$points[] = $cmd->computeInfluxData($value, $timestamp);
					}
					$array_points = array_chunk($points, 10000);
					foreach ($array_points as $point) {
						$database->writePoints($point, 's');
					}
					$begin = $end;
					$end = date('Y-m-d H:i:s', strtotime($begin . ' +60 days'));
				}
			}
		} catch (Exception $e) {
			log::add('cmd', 'error', __('Erreur history influx sur :', __FILE__) . ' ' . ' commande : ' . $cmd->getHumanName() . ' => ' . $e->getMessage());
		}
	}

	public function historyInflux($_type = '') {
		$cron = new cron();
		$cron->setClass('cmd');
		$cron->setFunction('sendHistoryInflux');
		if ($_type == 'all') {
			$cron->setOption(array('cmd_id' => 'all'));
		} else {
			$cron->setOption(array('cmd_id' => intval($this->getId())));
		}
		$cron->setLastRun(date('Y-m-d H:i:s'));
		$cron->setOnce(1);
		$cron->setSchedule(cron::convertDateToCron(strtotime("now") + 60));
		$cron->save();
		return;
	}

	public function generateAskResponseLink($_response, $_plugin = 'core', $_network = 'external') {
		if ($this->getCache('ask::token') == null || $this->getCache('ask::token') == '' || strlen($this->getCache('ask::token')) < 60) {
			$this->setCache('ask::token', config::genKey());
		}
		$return = network::getNetworkAccess($_network) . '/core/api/jeeApi.php?';
		$return .= 'type=ask';
		$return .= '&token=' . $this->getCache('ask::token');
		$return .= '&response=' . urlencode($_response);
		$return .= '&cmd_id=' . $this->getId();
		return $return;
	}

	public function askResponse($_response) {
		if ($this->getCache('ask::variable', 'none') == 'none') {
			return false;
		}
		$askEndTime = $this->getCache('ask::endtime', null);
		if ($askEndTime === null || $askEndTime < strtotime('now')) {
			return false;
		}
		if (!in_array($_response, $this->getCache('ask::answer')) && !in_array('*', $this->getCache('ask::answer'))) {
			return false;
		}
		$dataStore = new dataStore();
		$dataStore->setType('scenario');
		$dataStore->setKey($this->getCache('ask::variable', 'none'));
		$dataStore->setValue($_response);
		$dataStore->setLink_id(-1);
		$dataStore->save();
		$this->setCache(array('ask::variable' => 'none', 'ask::token' => config::genKey(), 'ask::endtime' => null));
		return true;
	}

	public function emptyHistory($_date = '') {
		if ($_date == '-1') {
			$_date = '';
		}
		return history::emptyHistory($this->getId(), $_date);
	}

	public function addHistoryValue($_value, $_datetime = '') {
		if ($this->getIsHistorized() == 1 && ($_value === null || ($_value !== '' && $this->getType() == 'info' && $_value <= $this->getConfiguration('maxValue', $_value) && $_value >= $this->getConfiguration('minValue', $_value)))) {
			$history = new history();
			$history->setCmd_id($this->getId());
			$history->setValue($_value);
			$history->setDatetime($_datetime);
			return $history->save($this);
		}
	}

	public function getStatistique($_startTime, $_endTime) {
		if ($this->getType() != 'info' || $this->getType() == 'string') {
			return array();
		}
		return history::getStatistique($this->getId(), $_startTime, $_endTime);
	}

	public function getTemporalAvg($_startTime, $_endTime) {
		if ($this->getType() != 'info' || $this->getType() == 'string') {
			return array();
		}
		return history::getTemporalAvg($this->getId(), $_startTime, $_endTime);
	}

	public function getTendance($_startTime, $_endTime) {
		return history::getTendance($this->getId(), $_startTime, $_endTime);
	}

	public function getCmdValue() {
		if (is_object($cmd = cmd::byId($this->getValue()))) {
			return $cmd;
		}
		preg_match_all("/#([0-9]*)#/", $this->getValue(), $matches);
		if (count($matches[1]) == 1) {
			$cmd = self::byId(str_replace('#', '', $matches[1][0]));
			if (!is_object($cmd)) {
				return false;
			}
			return $cmd;
		}
		$cmds = array();
		foreach ($matches[1] as $cmd_id) {
			if (is_object($cmd = self::byId(str_replace('#', '', $cmd_id)))) {
				$cmds[] = $cmd;
			}
		}
		if (!empty($cmds)) {
			return $cmds;
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

	public function getHistory($_dateStart = null, $_dateEnd = null, $_groupingType = null, $_addFirstPreviousValue = false) {
		return history::all($this->id, $_dateStart, $_dateEnd, $_groupingType, $_addFirstPreviousValue);
	}

	public function getLastHistory($_time, $_previous = true) {
		$value = 0;
		if ($this->getIsHistorized() == 1) {
			if (!$this->getConfiguration('isHistorizedCalc', 0)) {
				$result = history::byCmdIdAtDatetime($this->getId(), $_time, $_previous);
				if ($result) {
					$value = $result->getValue();
				}
			} else {
				$value = history::byCmdIdAtDatetimeFromCalcul(jeedom::fromHumanReadable($this->getConfiguration('calcul')), $_time, $_previous);
			}
		}
		return (array('value' => $value, 'unite' => $this->getUnite()));
	}

	public function getOldest() {
		return history::getOldestValue($this->id);
	}

	public function getPluralityHistory($_dateStart = null, $_dateEnd = null, $_period = 'day', $_offset = 0) {
		return history::getPlurality($this->id, $_dateStart, $_dateEnd, $_period, $_offset);
	}

	public function widgetPossibility($_key = '', $_default = true) {
		$class = new ReflectionClass($this->getEqType_name());
		$method_toHtml = $class->getMethod('toHtml');
		$return = array();
		if ($method_toHtml->class == 'eqLogic' || $this->getEqLogic()->getDisplay('widgetTmpl', 1) == 0) {
			if (strpos($_key, 'custom') !== false) {
				return true;
			}
			$return['custom'] = true;
		} else {
			$return['custom'] = false;
		}
		$class = new ReflectionClass($this->getEqType_name() . 'Cmd');
		$method_toHtml = $class->getMethod('toHtml');
		if ($method_toHtml->class == 'cmd') {
			$return['custom'] = true;
		} else {
			$return['custom'] = false;
		}
		$class = $this->getEqType_name() . 'Cmd';
		if (property_exists($class, '_widgetPossibility')) {
			$return = $class::$_widgetPossibility;
			if ($_key != '') {
				if (isset($return[$_key])) {
					return $return[$_key];
				}
				$keys = explode('::', $_key);
				foreach ($keys as $k) {
					if (!isset($return[$k])) {
						return false;
					}
					if (is_array($return[$k])) {
						$return = $return[$k];
					} else {
						return $return[$k];
					}
				}
				if (is_array($return)) {
					return $_default;
				}
				return $return;
			}
		}

		if ($_key != '') {
			if (isset($return['custom']) && !isset($return[$_key])) {
				return $return['custom'];
			}
			return (isset($return[$_key])) ? $return[$_key] : $_default;
		}
		return $return;
	}

	public static function migrateCmd($_sourceId, $_targetId) {
		$sourceCmd = cmd::byId($_sourceId);
		if (!is_object($sourceCmd)) {
			throw new Exception(__('La commande source n\'existe pas', __FILE__));
		}
		$targetCmd = cmd::byId($_targetId);
		if (!is_object($targetCmd)) {
			throw new Exception(__('La commande cible n\'existe pas', __FILE__));
		}

		$migrateDisplayValues = [
			'parameters' => array(),
			'showNameOndashboard' => '',
			'showNameOnmobile' => '',
			'showIconAndNamedashboard' => '',
			'showIconAndNamemobile' => '',
			'showStatsOndashboard' => '',
			'showStatsOnmobile' => '',
			'forceReturnLineBefore' => '',
			'forceReturnLineAfter' => '',
			'invertBinary' => '',
			'icon' => '',
		];

		$migrateConfigurationValues = [
			'jeedomPreExecCmd' => array(),
			'jeedomPostExecCmd' => array(),
			'actionCheckCmd' => array(),
			'timeline::enable' => '',
			'timeline::folder' => '',
			'repeatEventManagement' => '',
			'historizeMode' => '',
			'historyPurge' => '',
			'historizeRound' => '',
			'calcul' => '',
			'calculValueOffset' => '',
			'denyValues' => '',
			'returnStateValue' => '',
			'returnStateTime' => '',
			'jeedomPushUrl' => '',
			'invertBinary' => '',
			'minValue' => '',
			'maxValue' => '',
			'jeedomCheckCmdOperator' => '',
			'jeedomCheckCmdTest' => '',
			'jeedomCheckCmdTime' => '',
			'actionConfirm' => '',
			'actionCodeAccess' => '',
			'alert::messageReturnBack' => '',
			'interact::auto::disable' => '',
			'influx::enable' => '',
			'influx::namecmd' => '',
			'influx::nameEq' => '',
			'influx::nameVal' => '',
		];

		$migrateAlertValues = [
			'warningif' => '',
			'warningduring' => '',
			'dangerif' => '',
			'dangerduring' => '',
		];

		try {
			//properties:
			$targetCmd->setGeneric_type($sourceCmd->getGeneric_type());
			$targetCmd->setIsVisible($sourceCmd->getIsVisible());
			$targetCmd->setOrder($sourceCmd->getOrder());
			$targetCmd->setIsHistorized($sourceCmd->getIsHistorized());
			$targetCmd->setTemplate('dashboard', $sourceCmd->getTemplate('dashboard'));
			$targetCmd->setTemplate('mobile', $sourceCmd->getTemplate('mobile'));

			//display:
			foreach ($migrateDisplayValues as $key => $value) {
				if (is_array($value)) {
					if (count($sourceCmd->getDisplay($key, $value)) > 0) {
						$targetCmd->setDisplay($key, $sourceCmd->getDisplay($key, $value));
					} else {
						$targetCmd->setDisplay($key, null);
					}
				}
				if (is_string($value)) {
					if ($sourceCmd->getDisplay($key) != $value) {
						$targetCmd->setDisplay($key, $sourceCmd->getDisplay($key, $value));
					} else {
						$targetCmd->setDisplay($key, null);
					}
				}
			}

			//configuration:
			foreach ($migrateConfigurationValues as $key => $value) {
				if (is_array($value)) {
					if (count($sourceCmd->getConfiguration($key, $value)) > 0) {
						$targetCmd->setConfiguration($key, $sourceCmd->getConfiguration($key, $value));
					} else {
						$targetCmd->setConfiguration($key, null);
					}
				}
				if (is_string($value)) {
					if ($sourceCmd->getConfiguration($key, $value) != $value) {
						$targetCmd->setConfiguration($key, $sourceCmd->getConfiguration($key, $value));
					} else {
						$targetCmd->setConfiguration($key, null);
					}
				}
			}

			//alert:
			foreach ($migrateAlertValues as $key => $value) {
				if (is_array($value)) {
					if (count($sourceCmd->getAlert($key, $value)) > 0) {
						$targetCmd->setAlert($key, $sourceCmd->getAlert($key, $value));
					} else {
						$targetCmd->setAlert($key, null);
					}
				}
				if (is_string($value)) {
					if ($sourceCmd->getAlert($key, $value) != $value) {
						$targetCmd->setAlert($key, $sourceCmd->getAlert($key, $value));
					} else {
						$targetCmd->setAlert($key, null);
					}
				}
			}

			$targetCmd->save();
			return $targetCmd;
		} catch (Exception $e) {
			throw new Exception(__('Erreur lors de la migration de commande', __FILE__) . ' : ' . $e->getMessage());
		}
	}


	public function export() {
		$cmd = clone $this;
		$cmd->setId('');
		$cmd->setOrder('');
		$cmd->setEqLogic_id('');
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
				case 'select':
					$url .= '&select=value';
					break;
			}
		}
		return network::getNetworkAccess('external') . $url;
	}

	public function checkAccessCode($_code) {
		if ($this->getType() != 'action' || trim($this->getConfiguration('actionCodeAccess')) == '') {
			return true;
		}
		if (sha512($_code) == $this->getConfiguration('actionCodeAccess')) {
			return true;
		}
		return false;
	}

	public function exportApi() {
		$return = utils::o2a($this);
		$return['currentValue'] = ($this->getType() !== 'action') ? $this->execCmd() : $this->getConfiguration('lastCmdValue', null);
		return $return;
	}

	public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = null) {
		if ($_drill === null) {
			$_drill = config::byKey('graphlink::cmd::drill');
		}
		if (isset($_data['node']['cmd' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = ($this->getType() == 'info') ? findCodeIcon('fa-eye') : findCodeIcon('fa-hand-paper');
		$_data['node']['cmd' . $this->getId()] = array(
			'id' => 'cmd' . $this->getId(),
			'name' => $this->getName(),
			'type' => __('Commande', __FILE__),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontsize' => '1.5em',
			'texty' => -14,
			'textx' => 0,
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'title' => $this->getHumanName(),
			'url' => $this->getEqLogic()->getLinkToConfiguration(),
		);
		$usedBy = $this->getUsedBy();
		$use = $this->getUse();
		addGraphLink($this, 'cmd', $usedBy['scenario'], 'scenario', $_data, $_level, $_drill);
		foreach ($usedBy['plugin'] as $key => $value) {
			addGraphLink($this, 'cmd', $value, $key, $_data, $_level, $_drill);
		}
		addGraphLink($this, 'cmd', $usedBy['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $usedBy['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $usedBy['interactDef'], 'interactDef', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'cmd', $usedBy['plan'], 'plan', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'cmd', $usedBy['plan3d'], 'plan3d', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'cmd', $usedBy['view'], 'view', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'cmd', $use['scenario'], 'scenario', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $use['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $use['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $use['dataStore'], 'dataStore', $_data, $_level, $_drill);
		addGraphLink($this, 'cmd', $this->getEqLogic(), 'eqLogic', $_data, $_level, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
		return $_data;
	}

	public function getUsedBy($_array = false) {
		$return = array('cmd' => array(), 'eqLogic' => array(), 'scenario' => array(), 'plan' => array(), 'view' => array());
		$return['cmd'] = array_merge(self::searchConfiguration('#' . $this->getId() . '#'), cmd::byValue($this->getId()));
		$return['eqLogic'] = eqLogic::searchConfiguration('#' . $this->getId() . '#');
		$return['object'] = jeeObject::searchConfiguration('#' . $this->getId() . '#');
		$return['scenario'] = scenario::searchByUse(array(array('action' => '#' . $this->getId() . '#')));
		$return['interactDef'] = interactDef::searchByUse('#' . $this->getId() . '#');
		$return['view'] = view::searchByUse('cmd', $this->getId());
		$return['plan'] = planHeader::searchByUse('cmd', $this->getId());
		$return['plan3d'] = plan3dHeader::searchByUse('cmd', $this->getId());
		$return['plugin'] = array();
		foreach (plugin::listPlugin(true, false, true, true) as $plugin) {
			if (method_exists($plugin, 'customUsedBy')) {
				$return['plugin'][$plugin] = $plugin::customUsedBy('cmd', $this->getId());
			}
		}
		if ($_array) {
			foreach ($return as &$value) {
				$value = utils::o2a($value);
			}
		}
		return $return;
	}

	public function getUse() {
		return jeedom::getTypeUse(jeedom::fromHumanReadable(json_encode(utils::o2a($this))));
	}

	public function hasRight($_user = null) {
		if ($this->getType() == 'action') {
			return $this->getEqLogic()->hasRight('x', $_user);
		} else {
			return $this->getEqLogic()->hasRight('r', $_user);
		}
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getGeneric_type() {
		return $this->generic_type;
	}

	public function setGeneric_type($_generic_type) {
		$this->_changed = utils::attrChanged($this->_changed, $this->generic_type, $_generic_type);
		$this->generic_type = $_generic_type;
		return $this;
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
		if ($this->_eqLogic === null) {
			$this->setEqLogic(eqLogic::byId($this->eqLogic_id));
		}
		return $this->_eqLogic;
	}

	/**
	 * @param eqLogic $_eqLogic
	 */
	public function setEqLogic($_eqLogic) {
		$this->_eqLogic = $_eqLogic;
		return $this;
	}

	public function getEventOnly() {
		return 1;
	}

	public function setId($_id = '') {
		$this->_changed = utils::attrChanged($this->_changed, $this->id, $_id);
		$this->id = $_id;
		return $this;
	}

	/**
	 *
	 * @param string $_name
	 * @return $this
	 */
	public function setName($_name) {
		$_name = substr(cleanComponanteName($_name), 0, 127);
		$_name = trim($_name);
		if ($this->name != $_name) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->name = $_name;
		return $this;
	}

	/**
	 * @param string $_type
	 */
	public function setType($_type) {
		if ($this->type != $_type) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->type = $_type;
		return $this;
	}

	/**
	 * @param string $_subType
	 */
	public function setSubType($_subType) {
		if ($this->subType != $_subType) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->subType = $_subType;
		return $this;
	}

	public function setEqLogic_id($_eqLogic_id) {
		$this->_changed = utils::attrChanged($this->_changed, $this->eqLogic_id, $_eqLogic_id);
		$this->eqLogic_id = $_eqLogic_id;
		return $this;
	}

	public function setIsHistorized($_isHistorized) {
		$this->_changed = utils::attrChanged($this->_changed, $this->isHistorized, $_isHistorized);
		$this->isHistorized = $_isHistorized;
		return $this;
	}

	public function setUnite($_unite) {
		if ($this->unite != $_unite) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->unite = $_unite;
		return $this;
	}

	public function getTemplate($_key = '', $_default = '') {
		return utils::getJsonAttr($this->template, $_key, $_default);
	}

	public function setTemplate($_key, $_value) {
		if (($_key == 'dashboard' || $_key == 'mobile') && strpos($_value, '::') === false) {
			$_value = 'core::' . $_value;
		}
		if ($this->getTemplate($_key) !== $_value) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->template = utils::setJsonAttr($this->template, $_key, $_value);
		return $this;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		if ($_key == 'actionCodeAccess' && $_value != '') {
			if (!is_sha1($_value) && !is_sha512($_value)) {
				$_value = sha512($_value);
			}
		}
		$configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->configuration, $configuration);
		$this->configuration = $configuration;
		return $this;
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function setDisplay($_key, $_value) {
		if ($this->getDisplay($_key) !== $_value) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
		return $this;
	}

	public function getAlert($_key = '', $_default = '') {
		return utils::getJsonAttr($this->alert, $_key, $_default);
	}

	public function setAlert($_key, $_value) {
		$alert = utils::setJsonAttr($this->alert, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->alert, $alert);
		$this->alert = $alert;
		$this->_needRefreshAlert = true;
		return $this;
	}

	public function getCollectDate() {
		if ($this->_collectDate == '' && $this->getType() == 'info') {
			$this->execCmd();
		}
		return $this->_collectDate;
	}

	public function setCollectDate($_collectDate) {
		$this->_collectDate = $_collectDate;
		return $this;
	}

	public function getValueDate() {
		if ($this->_valueDate == '' && $this->getType() == 'info') {
			$this->execCmd();
		}
		return $this->_valueDate;
	}

	public function setValueDate($_valueDate) {
		$this->_valueDate = $_valueDate;
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($_value) {
		$this->_changed = utils::attrChanged($this->_changed, $this->value, $_value);
		$this->value = $_value;
		return $this;
	}

	public function getIsVisible() {
		return $this->isVisible;
	}

	public function setIsVisible($isVisible) {
		if ($this->isVisible != $isVisible) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->isVisible = $isVisible;
		return $this;
	}

	public function getOrder() {
		if ($this->order == '') {
			return 0;
		}
		return $this->order;
	}

	public function setOrder($order) {
		if ($this->order != $order) {
			$this->_needRefreshWidget = true;
			$this->_changed = true;
		}
		$this->order = $order;
		return $this;
	}

	public function getLogicalId() {
		return $this->logicalId;
	}

	public function setLogicalId($_logicalId) {
		$this->_changed = utils::attrChanged($this->_changed, $this->logicalId, $_logicalId);
		$this->logicalId = $_logicalId;
		return $this;
	}

	public function getEqType() {
		return $this->eqType;
	}

	public function setEqType($_eqType) {
		$this->_changed = utils::attrChanged($this->_changed, $this->eqType, $_eqType);
		$this->eqType = $_eqType;
		return $this;
	}

	public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('cmdCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}

	public function setCache($_key, $_value = null) {
		cache::set('cmdCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('cmdCacheAttr' . $this->getId())->getValue(), $_key, $_value));
		return $this;
	}

	public function getChanged() {
		return $this->_changed;
	}

	/**
	 * @param bool $_changed
	 */
	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}
}
