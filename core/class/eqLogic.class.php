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

class eqLogic {
	/*     * *************************Attributs****************************** */
	const UIDDELIMITER = '__';
	protected $id;
	protected $name;
	protected $logicalId = '';
	protected $generic_type;
	protected $object_id = null;
	protected $eqType_name;
	protected $eqReal_id = null;
	protected $isVisible = 0;
	protected $isEnable = 0;
	protected $configuration;
	protected $timeout = 0;
	protected $category;
	protected $display;
	protected $order;
	protected $comment;
	protected $tags;
	protected $_debug = false;
	protected $_object = null;
	private static $_templateArray = array();
	protected $_needRefreshWidget = false;
	protected $_timeoutUpdated = false;
	protected $_batteryUpdated = false;
	protected $_cmds = array();
	
	/*     * ***********************Méthodes statiques*************************** */
	
	public static function getAllTags() {
		$values = array();
		$sql = 'SELECT tags
		FROM eqLogic
		WHERE tags IS NOT NULL
		AND tags!=""';
		$results = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		$return = array();
		foreach ($results as $result) {
			$tags = explode(',', $result['tags']);
			foreach ($tags as $tag) {
				$return[$tag] = $tag;
			}
		}
		return $return;
	}
	
	public static function byId($_id) {
		if ($_id == '') {
			return;
		}
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM eqLogic
		WHERE id=:id';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}
	
	private static function cast($_inputs) {
		if (is_object($_inputs) && class_exists($_inputs->getEqType_name())) {
			return cast($_inputs, $_inputs->getEqType_name());
		}
		if (is_array($_inputs)) {
			$return = array();
			foreach ($_inputs as $input) {
				$return[] = self::cast($input);
			}
			return $return;
		}
		return $_inputs;
	}
	
	public static function all($_onlyEnable = false) {
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'el') . '
		FROM eqLogic el
		LEFT JOIN object ob ON el.object_id=ob.id';
		if ($_onlyEnable) {
			$sql .= ' AND isEnable=1';
		}
		$sql .= ' ORDER BY ob.name,el.name';
		return self::cast(DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function byEqRealId($_eqReal_id) {
		$values = array(
			'eqReal_id' => $_eqReal_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM eqLogic
		WHERE eqReal_id=:eqReal_id';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function byObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null, $_orderByName = false) {
		$values = array();
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM eqLogic';
		if ($_object_id === null) {
			$sql .= ' WHERE object_id IS NULL';
		} else {
			$values['object_id'] = $_object_id;
			$sql .= ' WHERE object_id=:object_id';
		}
		if ($_onlyEnable) {
			$sql .= ' AND isEnable = 1';
		}
		if ($_onlyVisible) {
			$sql .= ' AND isVisible = 1';
		}
		if ($_eqType_name !== null) {
			$values['eqType_name'] = $_eqType_name;
			$sql .= ' AND eqType_name=:eqType_name';
		}
		if ($_logicalId !== null) {
			$values['logicalId'] = $_logicalId;
			$sql .= ' AND logicalId=:logicalId';
		}
		if ($_orderByName) {
			$sql .= ' ORDER BY `name`';
		} else {
			$sql .= ' ORDER BY `order`,category';
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function byLogicalId($_logicalId, $_eqType_name, $_multiple = false) {
		$values = array(
			'logicalId' => $_logicalId,
			'eqType_name' => $_eqType_name,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM eqLogic
		WHERE logicalId=:logicalId
		AND eqType_name=:eqType_name';
		if ($_multiple) {
			return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function byType($_eqType_name, $_onlyEnable = false) {
		$values = array(
			'eqType_name' => $_eqType_name,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'el') . '
		FROM eqLogic el
		LEFT JOIN object ob ON el.object_id=ob.id
		WHERE eqType_name=:eqType_name ';
		if ($_onlyEnable) {
			$sql .= ' AND isEnable=1';
		}
		$sql .= ' ORDER BY ob.name,el.name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function byCategorie($_category) {
		$values = array(
			'category' => '%"' . $_category . '":1%',
			'category2' => '%"' . $_category . '":"1"%',
		);
		
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM eqLogic
		WHERE category LIKE :category
		OR category LIKE :category2
		ORDER BY name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function byTypeAndSearhConfiguration($_eqType_name, $_configuration) {
		$values = array(
			'eqType_name' => $_eqType_name,
			'configuration' => '%' . $_configuration . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM eqLogic
		WHERE eqType_name=:eqType_name
		AND configuration LIKE :configuration
		ORDER BY name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function searchConfiguration($_configuration, $_type = null) {
		if (!is_array($_configuration)) {
			$values = array(
				'configuration' => '%' . $_configuration . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM eqLogic
			WHERE configuration LIKE :configuration';
		} else {
			$values = array(
				'configuration' => '%' . $_configuration[0] . '%',
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM eqLogic
			WHERE configuration LIKE :configuration';
			for ($i = 1; $i < count($_configuration); $i++) {
				$values['configuration' . $i] = '%' . $_configuration[$i] . '%';
				$sql .= ' OR configuration LIKE :configuration' . $i;
			}
		}
		if ($_type !== null) {
			$values['eqType_name'] = $_type;
			$sql .= ' AND eqType_name=:eqType_name ';
		}
		$sql .= ' ORDER BY name';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function listByTypeAndCmdType($_eqType_name, $_typeCmd, $subTypeCmd = '') {
		if ($subTypeCmd == '') {
			$values = array(
				'eqType_name' => $_eqType_name,
				'typeCmd' => $_typeCmd,
			);
			$sql = 'SELECT DISTINCT(el.id),el.name
			FROM eqLogic el
			INNER JOIN cmd c ON c.eqLogic_id=el.id
			WHERE eqType_name=:eqType_name
			AND c.type=:typeCmd
			ORDER BY name';
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		} else {
			$values = array(
				'eqType_name' => $_eqType_name,
				'typeCmd' => $_typeCmd,
				'subTypeCmd' => $subTypeCmd,
			);
			$sql = 'SELECT DISTINCT(el.id),el.name
			FROM eqLogic el
			INNER JOIN cmd c ON c.eqLogic_id=el.id
			WHERE eqType_name=:eqType_name
			AND c.type=:typeCmd
			AND c.subType=:subTypeCmd
			ORDER BY name';
			return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
		}
	}
	
	public static function listByObjectAndCmdType($_object_id, $_typeCmd, $subTypeCmd = '') {
		$values = array();
		$sql = 'SELECT DISTINCT(el.id),el.name
		FROM eqLogic el
		INNER JOIN cmd c ON c.eqLogic_id=el.id
		WHERE ';
		if ($_object_id === null) {
			$sql .= ' object_id IS NULL ';
		} elseif ($_object_id != '') {
			$values['object_id'] = $_object_id;
			$sql .= ' object_id=:object_id ';
		}
		if ($subTypeCmd != '') {
			$values['subTypeCmd'] = $subTypeCmd;
			$sql .= ' AND c.subType=:subTypeCmd ';
		}
		if ($_typeCmd != '' && $_typeCmd != 'all') {
			$values['type'] = $_typeCmd;
			$sql .= ' AND c.type=:type ';
		}
		$sql .= ' ORDER BY name ';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
	}
	
	public static function allType() {
		$sql = 'SELECT distinct(eqType_name) as type
		FROM eqLogic';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
	}
	
	public static function checkAlive() {
		foreach (eqLogic::byTimeout(1, true) as $eqLogic) {
			$sendReport = false;
			$cmds = $eqLogic->getCmd();
			foreach ($cmds as $cmd) {
				$sendReport = true;
			}
			$logicalId = 'noMessage' . $eqLogic->getId();
			if ($sendReport) {
				$noReponseTimeLimit = $eqLogic->getTimeout();
				if (count(message::byPluginLogicalId('core', $logicalId)) == 0) {
					if ($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s')) < date('Y-m-d H:i:s', strtotime('-' . $noReponseTimeLimit . ' minutes' . date('Y-m-d H:i:s')))) {
						$message = __('Attention', __FILE__) . ' ' . $eqLogic->getHumanName();
						$message .= __(' n\'a pas envoyé de message depuis plus de ', __FILE__) . $noReponseTimeLimit . __(' min (vérifiez les piles)', __FILE__);
						$eqLogic->setStatus('timeout', 1);
						if (config::byKey('alert::addMessageOnTimeout') == 1) {
							message::add('core', $message, '', $logicalId);
						}
						$cmds = explode(('&&'), config::byKey('alert::timeoutCmd'));
						if (count($cmds) > 0 && trim(config::byKey('alert::timeoutCmd')) != '') {
							foreach ($cmds as $id) {
								$cmd = cmd::byId(str_replace('#', '', $id));
								if (is_object($cmd)) {
									$cmd->execCmd(array(
										'title' => __('[' . config::byKey('name', 'core', 'JEEDOM') . '] ', __FILE__) . $message,
										'message' => config::byKey('name', 'core', 'JEEDOM') . ' : ' . $message,
									));
								}
							}
						}
					}
				} else {
					if ($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s')) > date('Y-m-d H:i:s', strtotime('-' . $noReponseTimeLimit . ' minutes' . date('Y-m-d H:i:s')))) {
						foreach (message::byPluginLogicalId('core', $logicalId) as $message) {
							$message->remove();
						}
						$eqLogic->setStatus('timeout', 0);
					}
				}
			}
		}
	}
	
	public static function byTimeout($_timeout = 0, $_onlyEnable = false) {
		$values = array(
			'timeout' => $_timeout,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM eqLogic
		WHERE timeout>=:timeout';
		if ($_onlyEnable) {
			$sql .= ' AND isEnable=1';
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function byObjectNameEqLogicName($_object_name, $_eqLogic_name) {
		if ($_object_name == __('Aucun', __FILE__)) {
			$values = array(
				'eqLogic_name' => $_eqLogic_name,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM eqLogic
			WHERE name=:eqLogic_name
			AND object_id IS NULL';
		} else {
			$values = array(
				'eqLogic_name' => $_eqLogic_name,
				'object_name' => $_object_name,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 'el') . '
			FROM eqLogic el
			INNER JOIN object ob ON el.object_id=ob.id
			WHERE el.name=:eqLogic_name
			AND ob.name=:object_name';
		}
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__));
	}
	
	public static function toHumanReadable($_input) {
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
				$property->setValue($_input, self::toHumanReadable($value));
				$property->setAccessible(false);
			}
			return $_input;
		}
		if (is_array($_input)) {
			foreach ($_input as $key => $value) {
				$_input[$key] = self::toHumanReadable($value);
			}
			return $_input;
		}
		$text = $_input;
		preg_match_all("/#eqLogic([0-9]*)#/", $text, $matches);
		foreach ($matches[1] as $eqLogic_id) {
			if (is_numeric($eqLogic_id)) {
				$eqLogic = self::byId($eqLogic_id);
				if (is_object($eqLogic)) {
					$text = str_replace('#eqLogic' . $eqLogic_id . '#', '#' . $eqLogic->getHumanName() . '#', $text);
				}
			}
		}
		return $text;
	}
	
	public static function fromHumanReadable($_input) {
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
				$property->setValue($_input, self::fromHumanReadable($value));
				$property->setAccessible(false);
			}
			return $_input;
		}
		if (is_array($_input)) {
			foreach ($_input as $key => $value) {
				$_input[$key] = self::fromHumanReadable($value);
			}
			if ($isJson) {
				return json_encode($_input, JSON_UNESCAPED_UNICODE);
			}
			return $_input;
		}
		$text = $_input;
		preg_match_all("/#\[(.*?)\]\[(.*?)\]#/", $text, $matches);
		if (count($matches) == 3) {
			$countMatches = count($matches[0]);
			for ($i = 0; $i < $countMatches; $i++) {
				if (isset($matches[1][$i]) && isset($matches[2][$i])) {
					$eqLogic = self::byObjectNameEqLogicName($matches[1][$i], $matches[2][$i]);
					if (isset($eqLogic[0]) && is_object($eqLogic[0])) {
						$text = str_replace($matches[0][$i], '#eqLogic' . $eqLogic[0]->getId() . '#', $text);
					}
				}
			}
		}
		return $text;
	}
	
	public static function byString($_string) {
		$eqLogic = self::byId(str_replace('#', '', self::fromHumanReadable($_string)));
		if (!is_object($eqLogic)) {
			throw new Exception(__('L\'équipement n\'a pas pu être trouvé : ', __FILE__) . $_string . __(' => ', __FILE__) . self::fromHumanReadable($_string));
		}
		return $eqLogic;
	}
	
	public static function clearCacheWidget() {
		foreach (self::all() as $eqLogic) {
			$eqLogic->emptyCacheWidget();
		}
	}
	
	public static function generateHtmlTable($_nbLine, $_nbColumn, $_options = array()) {
		$return = array('html' => '', 'replace' => array());
		if (!isset($_options['styletd'])) {
			$_options['styletd'] = '';
		}
		if (!isset($_options['center'])) {
			$_options['center'] = 0;
		}
		if (!isset($_options['styletable'])) {
			$_options['styletable'] = '';
		}
		$return['html'] .= '<table style="' . $_options['styletable'] . '" class="tableCmd" data-line="' . $_nbLine . '" data-column="' . $_nbColumn . '">';
		$return['html'] .= '<tbody>';
		for ($i = 1; $i <= $_nbLine; $i++) {
			$return['html'] .= '<tr>';
			for ($j = 1; $j <= $_nbColumn; $j++) {
				$styletd = (isset($_options['style::td::' . $i . '::' . $j]) && $_options['style::td::' . $i . '::' . $j] != '') ? $_options['style::td::' . $i . '::' . $j] : $_options['styletd'];
				$return['html'] .= '<td style="min-width:30px;height:30px;' . $styletd . '" data-line="' . $i . '" data-column="' . $j . '">';
				if ($_options['center'] == 1) {
					$return['html'] .= '<center>';
				}
				if (isset($_options['text::td::' . $i . '::' . $j])) {
					$return['html'] .= $_options['text::td::' . $i . '::' . $j];
				}
				$return['html'] .= '#cmd::' . $i . '::' . $j . '#';
				if ($_options['center'] == 1) {
					$return['html'] .= '</center>';
				}
				$return['html'] .= '</td>';
				$return['tag']['#cmd::' . $i . '::' . $j . '#'] = '';
			}
			$return['html'] .= '</tr>';
		}
		$return['html'] .= '</tbody>';
		$return['html'] .= '</table>';
		
		return $return;
	}
	
	/*     * *********************Méthodes d'instance************************* */
	
	public function batteryWidget($_version = 'dashboard') {
		$html = '';
		$color = '#2ecc71';
		$level = 'good';
		$niveau = '3';
		$battery = $this->getConfiguration('battery_type', 'none');
		$batteryTime = $this->getConfiguration('batterytime', 'NA');
		$batterySince = 'NA';
		if ($batteryTime != 'NA') {
			$batterySince = ((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d", strtotime($batteryTime)))) / 86400);
		}
		if (strpos($battery, ' ') !== false) {
			$battery = substr(strrchr($battery, " "), 1);
		}
		$plugins = $this->getEqType_name();
		$object_name = 'Aucun';
		if (is_object($this->getObject())) {
			$object_name = $this->getObject()->getName();
		}
		if ($this->getStatus('battery') <= $this->getConfiguration('battery_danger_threshold', config::byKey('battery::danger'))) {
			$color = '#e74c3c';
			$level = 'critical';
			$niveau = '0';
		} else if ($this->getStatus('battery') <= $this->getConfiguration('battery_warning_threshold', config::byKey('battery::warning'))) {
			$color = '#f1c40f';
			$level = 'warning';
			$niveau = '1';
		} else if ($this->getStatus('battery') <= 75) {
			$niveau = '2';
		}
		$classAttr = $level . ' ' . $battery . ' ' . $plugins . ' ' . $object_name;
		$idAttr = $level . '__' . $battery . '__' . $plugins . '__' . $object_name;
		$html .= '<div class="eqLogic eqLogic-widget ' . $classAttr . '" style="min-width:100px;min-height:150px;background-color:' . $color . '" id="' . $idAttr . '">';
		if ($_version == 'mobile') {
			$html .= '<div class="widget-name" style="text-align : center;"><span style="font-size : 1em;">' . $this->getName() . '</span><br/><span style="font-size: 0.95em;position:relative;top:-5px;cursor:default;">' . $object_name . '</span></div>';
		} else {
			$html .= '<div class="widget-name" style="text-align : center;"><a href="' . $this->getLinkToConfiguration() . '" style="font-size : 1em;">' . $this->getName() . '</a><br/><span style="font-size: 0.95em;position:relative;top:-5px;cursor:default;">' . $object_name . '</span></div>';
		}
		$html .= '<div style="text-align : center;font-size:2.2em;font-weight: bold;margin-top:-25px;margin-bottom:-25px"><i class="icon jeedom-batterie' . $niveau . ' tooltips" title="' . $this->getStatus('battery', -2) . '%" style="font-size :2.5em;"></i></div>';
		$html .= '<div style="text-align : center;"><span style="font-size:1.2em;font-weight: bold;cursor:default;">' . $this->getStatus('battery', -2) . '</span><span>%</span></div>';
		$html .= '<div style="text-align : center; cursor:default;">' . __('Le', __FILE__) . ' ' . date("d/m/y G:H:s", strtotime($this->getStatus('batteryDatetime', __('inconnue', __FILE__)))) . '</div>';
		if ($this->getConfiguration('battery_type', '') != '') {
			$html .= '<span class="pull-right" style="font-size : 0.8em;margin-bottom: 3px;margin-right: 5px;cursor:default;" title="Piles">' . $this->getConfiguration('battery_type', '') . '</span>';
		}
		$html .= '<span class="pull-left" style="font-size : 0.8em;margin-bottom: 3px;margin-left: 5px;cursor:default;" title="Plugin">' . ucfirst($this->getEqType_name()) . '</span>';
		if ($this->getConfiguration('battery_danger_threshold') != '' || $this->getConfiguration('battery_warning_threshold') != '') {
			$html .= '<i class="icon techno-fingerprint41 pull-right" style="position:absolute;bottom: 3px;right: 3px;cursor:default;" title="Seuil manuel défini"></i>';
		}
		if ($batteryTime != 'NA') {
			$html .= '<i class="icon divers-calendar2 pull-right" style="position:absolute;bottom: 3px;left: 3px;cursor:default;" title="Pile(s) changée(s) il y a ' . $batterySince . ' jour(s) (' . $batteryTime . ')"> ('.$batterySince.'j)</i>';
		} else {
			$html .= '<i class="icon divers-calendar2 pull-right" style="position:absolute;bottom: 3px;left: 3px;cursor:default;" title="Pas de date de changement de pile(s) renseignée"></i>';
		}
		$html .= '</div>';
		return $html;
	}
	
	public function checkAndUpdateCmd($_logicalId, $_value, $_updateTime = null) {
		if ($this->getIsEnable() == 0) {
			return false;
		}
		$cmd = is_object($_logicalId) ? $_logicalId : $this->getCmd('info', $_logicalId);
		if (!is_object($cmd)) {
			return false;
		}
		$oldValue = $cmd->execCmd();
		if (($oldValue != $cmd->formatValue($_value)) || $oldValue === '') {
			$cmd->event($_value, $_updateTime);
			return true;
		}
		if ($_updateTime !== null) {
			if (strtotime($cmd->getCollectDate()) < strtotime($_updateTime)) {
				$cmd->event($_value, $_updateTime);
				return true;
			}
		} else if ($cmd->getConfiguration('repeatEventManagement', 'auto') == 'always') {
			$cmd->event($_value, $_updateTime);
			return true;
		}
		$cmd->setCache('collectDate', date('Y-m-d H:i:s'));
		$this->setStatus(array('lastCommunication' => date('Y-m-d H:i:s'), 'timeout' => 0));
		return false;
	}
	
	public function copy($_name) {
		$eqLogicCopy = clone $this;
		$eqLogicCopy->setName($_name);
		$eqLogicCopy->setId('');
		$eqLogicCopy->save();
		foreach ($eqLogicCopy->getCmd() as $cmd) {
			$cmd->remove();
		}
		$cmd_link = array();
		foreach ($this->getCmd() as $cmd) {
			$cmdCopy = clone $cmd;
			$cmdCopy->setId('');
			$cmdCopy->setEqLogic_id($eqLogicCopy->getId());
			$cmdCopy->save();
			$cmd_link[$cmd->getId()] = $cmdCopy;
		}
		foreach ($this->getCmd() as $cmd) {
			if (!isset($cmd_link[$cmd->getId()])) {
				continue;
			}
			if ($cmd->getValue() != '' && isset($cmd_link[$cmd->getValue()])) {
				$cmd_link[$cmd->getId()]->setValue($cmd_link[$cmd->getValue()]->getId());
				$cmd_link[$cmd->getId()]->save();
			}
		}
		return $eqLogicCopy;
	}
	
	public function getTableName() {
		return 'eqLogic';
	}
	
	public function hasOnlyEventOnlyCmd() {
		return true;
	}
	
	public function preToHtml($_version = 'dashboard', $_default = array(), $_noCache = false) {
		if ($_version == '') {
			throw new Exception(__('La version demandée ne peut pas être vide (mobile, dashboard ou scénario)', __FILE__));
		}
		if (!$this->hasRight('r')) {
			return '';
		}
		if (!$this->getIsEnable()) {
			return '';
		}
		$version = jeedom::versionAlias($_version, false);
		if ($this->getDisplay('showOn' . $version, 1) == 0) {
			return '';
		}
		
		$user_id = '';
		if (isset($_SESSION) && isset($_SESSION['user']) && is_object($_SESSION['user'])) {
			$user_id = $_SESSION['user']->getId();
		}
		if (!$_noCache) {
			$mc = cache::byKey('widgetHtml' . $this->getId() . $_version . $user_id);
			if ($mc->getValue() != '') {
				return preg_replace("/" . preg_quote(self::UIDDELIMITER) . "(.*?)" . preg_quote(self::UIDDELIMITER) . "/", self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER, $mc->getValue());
			}
		}
		$tagsValue = '';
		if ($this->getTags() != null) {
			$tagsArray = explode(',', $this->getTags());
			foreach ($tagsArray as $tags) {
				if ($tags == null) {
					continue;
				}
				$tagsValue .= 'tag-' . $tags . ' ';
			}
		}
		$tagsValue = trim($tagsValue);
		$replace = array(
			'#id#' => $this->getId(),
			'#name#' => $this->getName(),
			'#name_display#' => $this->getName(),
			'#hideEqLogicName#' => '',
			'#eqLink#' => $this->getLinkToConfiguration(),
			'#category#' => $this->getPrimaryCategory(),
			'#color#' => '#ffffff',
			'#border#' => 'none',
			'#border-radius#' => '0px',
			'#style#' => '',
			'#max_width#' => '650px',
			'#logicalId#' => $this->getLogicalId(),
			'#object_name#' => '',
			'#height#' => $this->getDisplay('height', 'auto'),
			'#width#' => $this->getDisplay('width', 'auto'),
			'#uid#' => 'eqLogic' . $this->getId() . self::UIDDELIMITER . mt_rand() . self::UIDDELIMITER,
			'#refresh_id#' => '',
			'#version#' => $_version,
			'#alert_name#' => '',
			'#alert_icon#' => '',
			'#custom_layout#' => ($this->widgetPossibility('custom::layout')) ? 'allowLayout' : '',
			'#tag#' => $tagsValue,
			'#data-tags#' => $this->getTags(),
		);
		
		if ($this->getDisplay('background-color-default' . $version, 1) == 1) {
			if (isset($_default['#background-color#'])) {
				$replace['#background-color#'] = $_default['#background-color#'];
			} else {
				$replace['#background-color#'] = $this->getBackgroundColor($version);
			}
		} else {
			$replace['#background-color#'] = ($this->getDisplay('background-color-transparent' . $version, 0) == 1) ? 'transparent' : $this->getDisplay('background-color' . $version, $this->getBackgroundColor($version));
		}
		if ($this->getAlert() != '') {
			$alert = $this->getAlert();
			$replace['#alert_name#'] = $alert['name'];
			$replace['#alert_icon#'] = $alert['icon'];
			$replace['#background-color#'] = $alert['color'];
		}
		if ($this->getDisplay('color-default' . $version, 1) != 1) {
			$replace['#color#'] = $this->getDisplay('color' . $version, '#ffffff');
		}
		if ($this->getDisplay('border-default' . $version, 1) != 1) {
			$replace['#border#'] = $this->getDisplay('border' . $version, 'none');
		}
		if ($this->getDisplay('border-radius-default' . $version, 1) != 1) {
			$replace['#border-radius#'] = $this->getDisplay('border-radius' . $version, '0') . 'px';
		}
		$refresh_cmd = $this->getCmd('action', 'refresh');
		if (!is_object($refresh_cmd)) {
			foreach ($this->getCmd('action') as $cmd) {
				if ($cmd->getConfiguration('isRefreshCmd') == 1) {
					$refresh_cmd = $cmd;
				}
			}
		}
		if (is_object($refresh_cmd) && $refresh_cmd->getIsVisible() == 1 && $refresh_cmd->getDisplay('showOn' . $version, 1) == 1) {
			$replace['#refresh_id#'] = $refresh_cmd->getId();
		}
		if ($this->getDisplay('showObjectNameOn' . $version, 0) == 1) {
			$object = $this->getObject();
			$replace['#object_name#'] = (is_object($object)) ? '(' . $object->getName() . ')' : '';
		}
		if ($this->getDisplay('showNameOn' . $version, 1) == 0) {
			$replace['#hideEqLogicName#'] = 'display:none;';
		}
		$vcolor = 'cmdColor';
		if ($version == 'mobile' || $_version == 'mview') {
			$vcolor = 'mcmdColor';
		}
		$parameters = $this->getDisplay('parameters');
		$replace['#cmd-background-color#'] = ($this->getPrimaryCategory() == '') ? jeedom::getConfiguration('eqLogic:category:default:' . $vcolor) : jeedom::getConfiguration('eqLogic:category:' . $this->getPrimaryCategory() . ':' . $vcolor);
		if (is_array($parameters) && isset($parameters['cmd-background-color'])) {
			$replace['#cmd-background-color#'] = $parameters['cmd-background-color'];
		}
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}
		$replace['#style#'] = trim($replace['#style#'], ';');
		
		if (is_array($this->widgetPossibility('parameters'))) {
			foreach ($this->widgetPossibility('parameters') as $pKey => $parameter) {
				if (!isset($parameter['allow_displayType'])) {
					continue;
				}
				if (!isset($parameter['type'])) {
					continue;
				}
				if (is_array($parameter['allow_displayType']) && !in_array($version, $parameter['allow_displayType'])) {
					continue;
				}
				if ($parameter['allow_displayType'] === false) {
					continue;
				}
				$default = '';
				if (isset($parameter['default'])) {
					$default = $parameter['default'];
				}
				if ($this->getDisplay('advanceWidgetParameter' . $pKey . $version . '-default', 1) == 1) {
					$replace['#' . $pKey . '#'] = $default;
					continue;
				}
				switch ($parameter['type']) {
					case 'color':
					if ($this->getDisplay('advanceWidgetParameter' . $pKey . $version . '-transparent', 0) == 1) {
						$replace['#' . $pKey . '#'] = 'transparent';
					} else {
						$replace['#' . $pKey . '#'] = $this->getDisplay('advanceWidgetParameter' . $pKey . $version, $default);
					}
					break;
					default:
					$replace['#' . $pKey . '#'] = $this->getDisplay('advanceWidgetParameter' . $pKey . $version, $default);
					break;
				}
			}
		}
		$default_opacity = config::byKey('widget::background-opacity');
		if (isset($_SESSION) && isset($_SESSION['user']) && is_object($_SESSION['user']) && $_SESSION['user']->getOptions('widget::background-opacity::' . $version, null) !== null) {
			$default_opacity = $_SESSION['user']->getOptions('widget::background-opacity::' . $version);
		}
		$opacity = $this->getDisplay('background-opacity' . $version, $default_opacity);
		if ($replace['#background-color#'] != 'transparent' && $opacity != '' && $opacity < 1) {
			list($r, $g, $b) = sscanf($replace['#background-color#'], "#%02x%02x%02x");
			$replace['#background-color#'] = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')';
		}
		return $replace;
	}
	
	public function toHtml($_version = 'dashboard') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		
		switch ($this->getDisplay('layout::' . $version)) {
			case 'table':
			$replace['#eqLogic_class#'] = 'eqLogic_layout_table';
			$table = self::generateHtmlTable($this->getDisplay('layout::' . $version . '::table::nbLine', 1), $this->getDisplay('layout::' . $version . '::table::nbColumn', 1), $this->getDisplay('layout::' . $version . '::table::parameters'));
			$br_before = 0;
			foreach ($this->getCmd(null, null, true) as $cmd) {
				if (isset($replace['#refresh_id#']) && $cmd->getId() == $replace['#refresh_id#']) {
					continue;
				}
				$tag = '#cmd::' . $this->getDisplay('layout::' . $version . '::table::cmd::' . $cmd->getId() . '::line', 1) . '::' . $this->getDisplay('layout::' . $version . '::table::cmd::' . $cmd->getId() . '::column', 1) . '#';
				if ($br_before == 0 && $cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
					$table['tag'][$tag] .= '<br/>';
				}
				$table['tag'][$tag] .= $cmd->toHtml($_version, '', $replace['#cmd-background-color#']);
				$br_before = 0;
				if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
					$table['tag'][$tag] .= '<br/>';
					$br_before = 1;
				}
			}
			$replace['#cmd#'] = template_replace($table['tag'], $table['html']);
			break;
			default:
			$replace['#eqLogic_class#'] = 'eqLogic_layout_default';
			$cmd_html = '';
			$br_before = 0;
			foreach ($this->getCmd(null, null, true) as $cmd) {
				if (isset($replace['#refresh_id#']) && $cmd->getId() == $replace['#refresh_id#']) {
					continue;
				}
				if ($br_before == 0 && $cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
					$cmd_html .= '<br/>';
				}
				$cmd_html .= $cmd->toHtml($_version, '', $replace['#cmd-background-color#']);
				$br_before = 0;
				if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
					$cmd_html .= '<br/>';
					$br_before = 1;
				}
			}
			$replace['#cmd#'] = $cmd_html;
			break;
		}
		if (!isset(self::$_templateArray[$version])) {
			self::$_templateArray[$version] = getTemplate('core', $version, 'eqLogic');
		}
		return $this->postToHtml($_version, template_replace($replace, self::$_templateArray[$version]));
	}
	
	public function postToHtml($_version, $_html) {
		$user_id = '';
		if (isset($_SESSION) && isset($_SESSION['user']) && is_object($_SESSION['user'])) {
			$user_id = $_SESSION['user']->getId();
		}
		cache::set('widgetHtml' . $this->getId() . $_version . $user_id, $_html);
		return $_html;
	}
	
	public function emptyCacheWidget() {
		$users = user::all();
		foreach (array('dashboard', 'mobile', 'mview', 'dview', 'dplan', 'view', 'plan') as $version) {
			$mc = cache::byKey('widgetHtml' . $this->getId() . $version);
			$mc->remove();
			foreach ($users as $user) {
				$mc = cache::byKey('widgetHtml' . $this->getId() . $version . $user->getId());
				$mc->remove();
			}
		}
	}
	
	public function getAlert() {
		global $JEEDOM_INTERNAL_CONFIG;
		$hasAlert = '';
		$maxLevel = 0;
		foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $key => $data) {
			if ($this->getStatus($key, 0) != 0 && $JEEDOM_INTERNAL_CONFIG['alerts'][$key]['level'] > $maxLevel) {
				$hasAlert = $data;
				$maxLevel = $JEEDOM_INTERNAL_CONFIG['alerts'][$key]['level'];
			}
		}
		return $hasAlert;
	}
	
	public function getMaxCmdAlert() {
		$return = 'none';
		$max = 0;
		global $JEEDOM_INTERNAL_CONFIG;
		foreach ($this->getCmd('info') as $cmd) {
			$cmdLevel = $cmd->getCache('alertLevel');
			if (!isset($JEEDOM_INTERNAL_CONFIG['alerts'][$cmdLevel])) {
				continue;
			}
			if ($JEEDOM_INTERNAL_CONFIG['alerts'][$cmdLevel]['level'] > $max) {
				$return = $cmdLevel;
				$max = $JEEDOM_INTERNAL_CONFIG['alerts'][$cmdLevel]['level'];
			}
		}
		return $return;
	}
	
	public function getShowOnChild() {
		return false;
	}
	
	public function remove() {
		foreach ($this->getCmd() as $cmd) {
			$cmd->remove();
		}
		viewData::removeByTypeLinkId('eqLogic', $this->getId());
		dataStore::removeByTypeLinkId('eqLogic', $this->getId());
		$this->emptyCacheWidget();
		cache::delete('eqLogicCacheAttr' . $this->getId());
		cache::delete('eqLogicStatusAttr' . $this->getId());
		jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getHumanName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'eqLogic'));
		return DB::remove($this);
	}
	
	public function save($_direct = false) {
		if ($this->getName() == '') {
			throw new Exception(__('Le nom de l\'équipement ne peut pas être vide : ', __FILE__) . print_r($this, true));
		}
		if ($this->getId() != '') {
			$this->emptyCacheWidget();
			$this->setConfiguration('updatetime', date('Y-m-d H:i:s'));
		} else {
			$this->setConfiguration('createtime', date('Y-m-d H:i:s'));
		}
		if ($this->getDisplay('showObjectNameOnview', -1) == -1) {
			$this->setDisplay('showObjectNameOnview', 1);
		}
		if ($this->getDisplay('showObjectNameOndview', -1) == -1) {
			$this->setDisplay('showObjectNameOndview', 1);
		}
		if ($this->getDisplay('showObjectNameOnmview', -1) == -1) {
			$this->setDisplay('showObjectNameOnmview', 1);
		}
		if ($this->getDisplay('height', -1) == -1 || intval($this->getDisplay('height')) < 2) {
			$this->setDisplay('height', 'auto');
		}
		if ($this->getDisplay('width', -1) == -1 || intval($this->getDisplay('height')) < 2) {
			$this->setDisplay('width', 'auto');
		}
		foreach (array('dashboard', 'mobile') as $key) {
			if ($this->getDisplay('layout::' . $key . '::table::parameters') == '') {
				$this->setDisplay('layout::' . $key . '::table::parameters', array('center' => 1, 'styletd' => 'padding:3px;'));
			}
			if ($this->getDisplay('layout::' . $key) == 'table') {
				if ($this->getDisplay('layout::' . $key . '::table::nbLine') == '') {
					$this->setDisplay('layout::' . $key . '::table::nbLine', 1);
				}
				if ($this->getDisplay('layout::' . $key . '::table::nbColumn') == '') {
					$this->setDisplay('layout::' . $key . '::table::nbLine', 1);
				}
			}
			foreach ($this->getCmd() as $cmd) {
				if ($this->getDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::line') == '' && $cmd->getDisplay('layout::' . $key . '::table::cmd::line') != '') {
					$this->setDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::line', $cmd->getDisplay('layout::' . $key . '::table::cmd::line'));
				}
				if ($this->getDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::column') == '' && $cmd->getDisplay('layout::' . $key . '::table::cmd::column') != '') {
					$this->setDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::column', $cmd->getDisplay('layout::' . $key . '::table::cmd::column'));
				}
				if ($this->getDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::line', 1) > $this->getDisplay('layout::' . $key . '::table::nbLine', 1)) {
					$this->setDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::line', $this->getDisplay('layout::' . $key . '::table::nbLine', 1));
				}
				if ($this->getDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::column', 1) > $this->getDisplay('layout::' . $key . '::table::nbColumn', 1)) {
					$this->setDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::column', $this->getDisplay('layout::' . $key . '::table::nbColumn', 1));
				}
				if ($this->getDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::line') == '') {
					$this->setDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::line', 1);
				}
				if ($this->getDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::column') == '') {
					$this->setDisplay('layout::' . $key . '::table::cmd::' . $cmd->getId() . '::column', 1);
				}
			}
		}
		
		DB::save($this, $_direct);
		if ($this->_needRefreshWidget) {
			$this->refreshWidget();
		}
		if ($this->_batteryUpdated) {
			$this->batteryStatus();
		}
		if ($this->_timeoutUpdated) {
			if ($this->getTimeout() == null) {
				foreach (message::byPluginLogicalId('core', 'noMessage' . $this->getId()) as $message) {
					$message->remove();
				}
				$this->setStatus('timeout', 0);
			} else {
				$this->checkAlive();
			}
		}
	}
	
	public function refresh() {
		DB::refresh($this);
	}
	
	public function getLinkToConfiguration() {
		if (isset($_SESSION) && isset($_SESSION['user']) && is_object($_SESSION['user']) && !isConnect('admin')) {
			return '#';
		}
		return 'index.php?v=d&p=' . $this->getEqType_name() . '&m=' . $this->getEqType_name() . '&id=' . $this->getId();
	}
	
	public function getHumanName($_tag = false, $_prettify = false) {
		$name = '';
		$object = $this->getObject();
		if (is_object($object)) {
			if ($_tag) {
				if ($object->getDisplay('tagColor') != '') {
					$name .= '<span class="label" style="text-shadow : none;background-color:' . $object->getDisplay('tagColor') . ';color:' . $object->getDisplay('tagTextColor', 'white') . '">' . $object->getName() . '</span>';
				} else {
					$name .= '<span class="label label-primary" style="text-shadow : none;">' . $object->getName() . '</span>';
				}
			} else {
				$name .= '[' . $object->getName() . ']';
			}
		} else {
			if ($_tag) {
				$name .= '<span class="label label-default" style="text-shadow : none;">' . __('Aucun', __FILE__) . '</span>';
			} else {
				$name .= '[' . __('Aucun', __FILE__) . ']';
			}
		}
		if ($_prettify) {
			$name .= '<br/><strong>';
		}
		if ($_tag) {
			$name .= ' ' . $this->getName();
		} else {
			$name .= '[' . $this->getName() . ']';
		}
		if ($_prettify) {
			$name .= '</strong>';
		}
		return $name;
	}
	
	public function getBackgroundColor($_version = 'dashboard') {
		$vcolor = ($_version == 'mobile') ? 'mcolor' : 'color';
		$category = $this->getPrimaryCategory();
		if ($category != '') {
			return jeedom::getConfiguration('eqLogic:category:' . $category . ':' . $vcolor);
		}
		return jeedom::getConfiguration('eqLogic:category:default:' . $vcolor);
	}
	
	public function getPrimaryCategory() {
		if ($this->getCategory('security', 0) == 1) {
			return 'security';
		}
		if ($this->getCategory('heating', 0) == 1) {
			return 'heating';
		}
		if ($this->getCategory('light', 0) == 1) {
			return 'light';
		}
		if ($this->getCategory('automatism', 0) == 1) {
			return 'automatism';
		}
		if ($this->getCategory('energy', 0) == 1) {
			return 'energy';
		}
		if ($this->getCategory('multimedia', 0) == 1) {
			return 'multimedia';
		}
		return '';
	}
	
	public function displayDebug($_message) {
		if ($this->getDebug()) {
			echo $_message . "\n";
		}
	}
	
	public function batteryStatus($_pourcent = '', $_datetime = '') {
		if ($this->getConfiguration('noBatterieCheck', 0) == 1) {
			return;
		}
		if ($_pourcent == '') {
			$_pourcent = $this->getStatus('battery');
			$_datetime = $this->getStatus('batteryDatetime');
		}
		if ($_pourcent > 100) {
			$_pourcent = 100;
		}
		if ($_pourcent < 0) {
			$_pourcent = 0;
		}
		$warning_threshold = $this->getConfiguration('battery_warning_threshold', config::byKey('battery::warning'));
		$danger_threshold = $this->getConfiguration('battery_danger_threshold', config::byKey('battery::danger'));
		if ($_pourcent != '' && $_pourcent < $danger_threshold) {
			$prevStatus = $this->getStatus('batterydanger', 0);
			$logicalId = 'lowBattery' . $this->getId();
			$message = 'Le module ' . $this->getEqType_name() . ' ' . $this->getHumanName() . ' a moins de ' . $danger_threshold . '% de batterie (niveau danger avec ' . $_pourcent . '% de batterie)';
			if ($this->getConfiguration('battery_type') != '') {
				$message .= ' (' . $this->getConfiguration('battery_type') . ')';
			}
			$this->setStatus('batterydanger', 1);
			if ($prevStatus == 0) {
				if (config::byKey('alert::addMessageOnBatterydanger') == 1) {
					message::add($this->getEqType_name(), $message, '', $logicalId);
				}
				$cmds = explode(('&&'), config::byKey('alert::batterydangerCmd'));
				if (count($cmds) > 0 && trim(config::byKey('alert::batterydangerCmd')) != '') {
					foreach ($cmds as $id) {
						$cmd = cmd::byId(str_replace('#', '', $id));
						if (is_object($cmd)) {
							$cmd->execCmd(array(
								'title' => __('[' . config::byKey('name', 'core', 'JEEDOM') . '] ', __FILE__) . $message,
								'message' => config::byKey('name', 'core', 'JEEDOM') . ' : ' . $message,
							));
						}
					}
				}
			}
		} else if ($_pourcent != '' && $_pourcent < $warning_threshold) {
			$prevStatus = $this->getStatus('batterywarning', 0);
			$logicalId = 'warningBattery' . $this->getId();
			$message = 'Le module ' . $this->getEqType_name() . ' ' . $this->getHumanName() . ' a moins de ' . $warning_threshold . '% de batterie (niveau warning avec ' . $_pourcent . '% de batterie)';
			if ($this->getConfiguration('battery_type') != '') {
				$message .= ' (' . $this->getConfiguration('battery_type') . ')';
			}
			$this->setStatus('batterywarning', 1);
			$this->setStatus('batterydanger', 0);
			if ($prevStatus == 0) {
				if (config::byKey('alert::addMessageOnBatterywarning') == 1) {
					message::add($this->getEqType_name(), $message, '', $logicalId);
				}
				$cmds = explode(('&&'), config::byKey('alert::batterywarningCmd'));
				if (count($cmds) > 0 && trim(config::byKey('alert::batterywarningCmd')) != '') {
					foreach ($cmds as $id) {
						$cmd = cmd::byId(str_replace('#', '', $id));
						if (is_object($cmd)) {
							$cmd->execCmd(array(
								'title' => __('[' . config::byKey('name', 'core', 'JEEDOM') . '] ', __FILE__) . $message,
								'message' => config::byKey('name', 'core', 'JEEDOM') . ' : ' . $message,
							));
						}
					}
				}
			}
		} else {
			foreach (message::byPluginLogicalId($this->getEqType_name(), 'warningBattery' . $this->getId()) as $message) {
				$message->remove();
			}
			foreach (message::byPluginLogicalId($this->getEqType_name(), 'lowBattery' . $this->getId()) as $message) {
				$message->remove();
			}
			$this->setStatus('batterydanger', 0);
			$this->setStatus('batterywarning', 0);
		}
		
		$this->setStatus(array('battery' => $_pourcent, 'batteryDatetime' => ($_datetime != '') ? $_datetime : date('Y-m-d H:i:s')));
	}
	
	public function refreshWidget() {
		$this->_needRefreshWidget = false;
		$this->emptyCacheWidget();
		event::add('eqLogic::update', array('eqLogic_id' => $this->getId()));
	}
	
	public function hasRight($_right, $_user = null) {
		if ($_user != null) {
			if ($_user->getProfils() == 'admin' || $_user->getProfils() == 'user') {
				return true;
			}
			if (strpos($_user->getRights('eqLogic' . $this->getId()), $_right) !== false) {
				return true;
			}
			return false;
		}
		if (!isConnect()) {
			return false;
		}
		if (isConnect('admin') || isConnect('user')) {
			return true;
		}
		if (strpos($_SESSION['user']->getRights('eqLogic' . $this->getId()), $_right) !== false) {
			return true;
		}
		return false;
	}
	
	public function import($_configuration) {
		$cmdClass = $this->getEqType_name() . 'Cmd';
		if (isset($_configuration['configuration'])) {
			foreach ($_configuration['configuration'] as $key => $value) {
				$this->setConfiguration($key, $value);
			}
		}
		if (isset($_configuration['category'])) {
			foreach ($_configuration['category'] as $key => $value) {
				$this->setCategory($key, $value);
			}
		}
		$cmd_order = 0;
		$link_cmds = array();
		$link_actions = array();
		$arrayToRemove = [];
		if (isset($_configuration['commands'])) {
			foreach ($this->getCmd() as $eqLogic_cmd) {
				$exists = 0;
				foreach ($_configuration['commands'] as $command) {
					if ($command['logicalId'] == $eqLogic_cmd->getLogicalId()) {
						$exists++;
					}
				}
				if ($exists < 1) {
					$arrayToRemove[] = $eqLogic_cmd;
				}
			}
			foreach ($arrayToRemove as $cmdToRemove) {
				try {
					$cmdToRemove->remove();
				} catch (Exception $e) {
					
				}
			}
			foreach ($_configuration['commands'] as $command) {
				$cmd = null;
				foreach ($this->getCmd() as $liste_cmd) {
					if ((isset($command['logicalId']) && $liste_cmd->getLogicalId() == $command['logicalId'])
					|| (isset($command['name']) && $liste_cmd->getName() == $command['name'])) {
						$cmd = $liste_cmd;
						break;
					}
				}
				try {
					if ($cmd === null || !is_object($cmd)) {
						$cmd = new $cmdClass();
						$cmd->setOrder($cmd_order);
						$cmd->setEqLogic_id($this->getId());
					} else {
						$command['name'] = $cmd->getName();
						if (isset($command['display'])) {
							unset($command['display']);
						}
					}
					utils::a2o($cmd, $command);
					$cmd->setConfiguration('logicalId', $cmd->getLogicalId());
					$cmd->save();
					if (isset($command['value'])) {
						$link_cmds[$cmd->getId()] = $command['value'];
					}
					if (isset($command['configuration']) && isset($command['configuration']['updateCmdId'])) {
						$link_actions[$cmd->getId()] = $command['configuration']['updateCmdId'];
					}
					$cmd_order++;
				} catch (Exception $exc) {
					
				}
				$cmd->event('');
			}
		}
		if (count($link_cmds) > 0) {
			foreach ($this->getCmd() as $eqLogic_cmd) {
				foreach ($link_cmds as $cmd_id => $link_cmd) {
					if ($link_cmd == $eqLogic_cmd->getName()) {
						$cmd = cmd::byId($cmd_id);
						if (is_object($cmd)) {
							$cmd->setValue($eqLogic_cmd->getId());
							$cmd->save();
						}
					}
				}
			}
		}
		if (count($link_actions) > 0) {
			foreach ($this->getCmd() as $eqLogic_cmd) {
				foreach ($link_actions as $cmd_id => $link_action) {
					if ($link_action == $eqLogic_cmd->getName()) {
						$cmd = cmd::byId($cmd_id);
						if (is_object($cmd)) {
							$cmd->setConfiguration('updateCmdId', $eqLogic_cmd->getId());
							$cmd->save();
						}
					}
				}
			}
		}
		$this->save();
	}
	
	public function export($_withCmd = true) {
		$eqLogic = clone $this;
		$eqLogic->setId('');
		$eqLogic->setLogicalId('');
		$eqLogic->setObject_id('');
		$eqLogic->setIsEnable('');
		$eqLogic->setIsVisible('');
		$eqLogic->setTimeout('');
		$eqLogic->setOrder('');
		$eqLogic->setConfiguration('nerverFail', '');
		$eqLogic->setConfiguration('noBatterieCheck', '');
		$return = utils::o2a($eqLogic);
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
		if ($_withCmd) {
			$return['cmd'] = array();
			foreach ($this->getCmd() as $cmd) {
				$return['cmd'][] = $cmd->export();
			}
		}
		return $return;
	}
	
	public function widgetPossibility($_key = '', $_default = true) {
		$class = new ReflectionClass($this->getEqType_name());
		$method_toHtml = $class->getMethod('toHtml');
		$return = array();
		if ($method_toHtml->class == 'eqLogic') {
			$return['custom'] = true;
		} else {
			$return['custom'] = false;
		}
		$class = $this->getEqType_name();
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
				if (is_array($return) && strpos($_key, 'custom') !== false) {
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
	
	public function toArray() {
		$return = utils::o2a($this, true);
		$return['status'] = $this->getStatus();
		return $return;
	}
	
	public function getImage() {
		$plugin = plugin::byId($this->getEqType_name());
		return $plugin->getPathImgIcon();
	}
	
	public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = null) {
		if ($_drill === null) {
			$_drill = config::byKey('graphlink::eqLogic::drill');
		}
		if (isset($_data['node']['eqLogic' . $this->getId()])) {
			return;
		}
		if ($this->getIsEnable() == 0 && $_level > 0) {
			return $_data;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$_data['node']['eqLogic' . $this->getId()] = array(
			'id' => 'eqLogic' . $this->getId(),
			'name' => $this->getName(),
			'width' => 60,
			'height' => 60,
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'image' => $this->getImage(),
			'title' => $this->getHumanName(),
			'url' => $this->getLinkToConfiguration(),
		);
		$use = $this->getUse();
		$usedBy = $this->getUsedBy();
		addGraphLink($this, 'eqLogic', $this->getCmd(), 'cmd', $_data, $_level, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
		addGraphLink($this, 'eqLogic', $use['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'eqLogic', $use['scenario'], 'scenario', $_data, $_level, $_drill);
		addGraphLink($this, 'eqLogic', $use['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'eqLogic', $use['dataStore'], 'dataStore', $_data, $_level, $_drill);
		addGraphLink($this, 'eqLogic', $usedBy['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'eqLogic', $usedBy['scenario'], 'scenario', $_data, $_level, $_drill);
		addGraphLink($this, 'eqLogic', $usedBy['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'eqLogic', $usedBy['interactDef'], 'interactDef', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'eqLogic', $usedBy['plan'], 'plan', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		addGraphLink($this, 'eqLogic', $usedBy['view'], 'view', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
		if (!isset($_data['object' . $this->getObject_id()])) {
			addGraphLink($this, 'eqLogic', $this->getObject(), 'object', $_data, $_level, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
		}
		return $_data;
	}
	
	public function getUse() {
		$json = jeedom::fromHumanReadable(json_encode(utils::o2a($this)));
		return jeedom::getTypeUse($json);
	}
	
	public function getUsedBy($_array = false) {
		$return = array('cmd' => array(), 'eqLogic' => array(), 'scenario' => array(), 'plan' => array(), 'view' => array());
		$return['cmd'] = cmd::searchConfiguration('#eqLogic' . $this->getId() . '#');
		$return['eqLogic'] = eqLogic::searchConfiguration(array('#eqLogic' . $this->getId() . '#', '"eqLogic":"' . $this->getId()));
		$return['interactDef'] = interactDef::searchByUse(array('#eqLogic' . $this->getId() . '#', '"eqLogic":"' . $this->getId()));
		$return['scenario'] = scenario::searchByUse(array(
			array('action' => 'equipment', 'option' => $this->getId(), 'and' => true),
			array('action' => '#eqLogic' . $this->getId() . '#'),
		));
		$return['view'] = view::searchByUse('eqLogic', $this->getId());
		$return['plan'] = planHeader::searchByUse('eqLogic', $this->getId());
		if ($_array) {
			foreach ($return as &$value) {
				$value = utils::o2a($value);
			}
		}
		return $return;
	}
	
	/*     * **********************Getteur Setteur*************************** */
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getLogicalId() {
		return $this->logicalId;
	}
	
	public function getObject_id() {
		return $this->object_id;
	}
	
	public function getObject() {
		if ($this->_object === null) {
			$this->setObject(jeeObject::byId($this->object_id));
		}
		return $this->_object;
	}
	
	public function setObject($_object) {
		$this->_object = $_object;
		return $this;
	}
	
	public function getEqType_name() {
		return $this->eqType_name;
	}
	
	public function getIsVisible($_default = 0) {
		if ($this->isVisible == '' || !is_numeric($this->isVisible)) {
			return $_default;
		}
		return $this->isVisible;
	}
	
	public function getIsEnable($_default = 0) {
		if ($this->isEnable == '' || !is_numeric($this->isEnable)) {
			return $_default;
		}
		return $this->isEnable;
	}
	
	public function getCmd($_type = null, $_logicalId = null, $_visible = null, $_multiple = false) {
		if ($_logicalId !== null) {
			if (isset($this->_cmds[$_logicalId . '.' . $_multiple . '.' . $_type])) {
				return $this->_cmds[$_logicalId . '.' . $_multiple . '.' . $_type];
			}
			$cmds = cmd::byEqLogicIdAndLogicalId($this->id, $_logicalId, $_multiple, $_type);
		} else {
			$cmds = cmd::byEqLogicId($this->id, $_type, $_visible, $this);
		}
		if (is_array($cmds)) {
			foreach ($cmds as $cmd) {
				$cmd->setEqLogic($this);
			}
		} elseif (is_object($cmds)) {
			$cmds->setEqLogic($this);
		}
		if ($_logicalId !== null && is_object($cmds)) {
			$this->_cmds[$_logicalId . '.' . $_multiple . '.' . $_type] = $cmds;
		}
		return $cmds;
	}
	
	public function getCmdByGenericType($_type = null, $_generic_type = null, $_visible = null, $_multiple = false) {
		if ($_generic_type !== null) {
			if (isset($this->_cmds[$_generic_type . '.' . $_multiple . '.' . $_type])) {
				return $this->_cmds[$_generic_type . '.' . $_multiple . '.' . $_type];
			}
			$cmds = cmd::byEqLogicIdAndGenericType($this->id, $_generic_type, $_multiple, $_type);
		} else {
			$cmds = cmd::byEqLogicId($this->id, $_type, $_visible, $this);
		}
		if (is_array($cmds)) {
			foreach ($cmds as $cmd) {
				$cmd->setEqLogic($this);
			}
		} elseif (is_object($cmds)) {
			$cmds->setEqLogic($this);
		}
		if ($_generic_type !== null && is_object($cmds)) {
			$this->_cmds[$_generic_type . '.' . $_multiple . '.' . $_type] = $cmds;
		}
		return $cmds;
	}
	
	public function searchCmdByConfiguration($_configuration, $_type = null) {
		return cmd::searchConfigurationEqLogic($this->id, $_configuration, $_type);
	}
	
	public function getEqReal_id($_default = null) {
		if ($this->eqReal_id == '' || !is_numeric($this->eqReal_id)) {
			return $_default;
		}
		return $this->eqReal_id;
	}
	
	public function getEqReal() {
		return eqReal::byId($this->eqReal_id);
	}
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function setName($name) {
		$name = str_replace(array('&', '#', ']', '[', '%', "'", "\\", "/"), '', $name);
		$this->name = $name;
		return $this;
	}
	
	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
		return $this;
	}
	
	public function setObject_id($object_id = null) {
		$this->object_id = (!is_numeric($object_id)) ? null : $object_id;
		return $this;
	}
	
	public function setEqType_name($eqType_name) {
		$this->eqType_name = $eqType_name;
		return $this;
	}
	
	public function setEqReal_id($eqReal_id) {
		$this->eqReal_id = $eqReal_id;
		return $this;
	}
	
	public function setIsVisible($_isVisible) {
		if ($this->isVisible != $_isVisible) {
			$this->_needRefreshWidget = true;
		}
		$this->isVisible = $_isVisible;
		return $this;
	}
	
	public function setIsEnable($_isEnable) {
		if ($this->isEnable != $_isEnable) {
			$this->_needRefreshWidget = true;
			if ($_isEnable) {
				$this->setStatus(array('lastCommunication' => date('Y-m-d H:i:s'), 'timeout' => 0));
			}
		}
		$this->isEnable = $_isEnable;
		return $this;
	}
	
	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}
	
	public function setConfiguration($_key, $_value) {
		if (in_array($_key, array('battery_warning_threshold', 'battery_danger_threshold'))) {
			if ($this->getConfiguration($_key, '') != $_value) {
				$this->_batteryUpdated = True;
			}
		}
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		return $this;
	}
	
	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}
	
	public function setDisplay($_key, $_value) {
		if ($this->getDisplay($_key) != $_value) {
			$this->_needRefreshWidget = true;
		}
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
	}
	
	public function getTimeout($_default = null) {
		if ($this->timeout == '' || !is_numeric($this->timeout)) {
			return $_default;
		}
		return $this->timeout;
	}
	
	public function setTimeout($_timeout) {
		if ($_timeout == '' || is_nan(intval($_timeout)) || $_timeout < 1) {
			$_timeout = null;
		}
		if ($_timeout != $this->getTimeout()) {
			$this->_timeoutUpdated = True;
		}
		$this->timeout = $_timeout;
		return $this;
	}
	
	public function getCategory($_key = '', $_default = '') {
		if ($_key == 'other' && strpos($this->category, "1") === false) {
			return 1;
		}
		return utils::getJsonAttr($this->category, $_key, $_default);
	}
	
	public function setCategory($_key, $_value) {
		if ($this->getCategory($_key) != $_value) {
			$this->_needRefreshWidget = true;
		}
		$this->category = utils::setJsonAttr($this->category, $_key, $_value);
		return $this;
	}
	
	public function getGenericType() {
		return $this->generic_type;
	}
	
	public function setGenericType($_generic_type) {
		$this->generic_type = $_generic_type;
		return $this;
	}
	
	public function getComment() {
		return $this->comment;
	}
	
	public function setComment($_comment) {
		$this->comment = $_comment;
		return $this;
	}
	
	public function getTags() {
		return $this->tags;
	}
	
	public function setTags($_tags) {
		$this->tags = str_replace(array("'", '<', '>'), "", $_tags);
		return $this;
	}
	
	public function getDebug() {
		return $this->_debug;
	}
	
	public function setDebug($_debug) {
		if ($_debug) {
			echo "Mode debug activé\n";
		}
		$this->_debug = $_debug;
	}
	
	public function getOrder() {
		if ($this->order == '' || !is_numeric($this->order)) {
			return 0;
		}
		return $this->order;
	}
	
	public function setOrder($order) {
		$this->order = $order;
		return $this;
	}
	
	public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('eqLogicCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}
	
	public function setCache($_key, $_value = null) {
		cache::set('eqLogicCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('eqLogicCacheAttr' . $this->getId())->getValue(), $_key, $_value));
	}
	
	public function getStatus($_key = '', $_default = '') {
		$status = cache::byKey('eqLogicStatusAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($status, $_key, $_default);
	}
	
	public function setStatus($_key, $_value = null) {
		cache::set('eqLogicStatusAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('eqLogicStatusAttr' . $this->getId())->getValue(), $_key, $_value));
	}
	
}
