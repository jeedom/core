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

class eqLogic {
	/*     * *************************Attributs****************************** */

	protected $id;
	protected $name;
	protected $logicalId = '';
	protected $object_id = null;
	protected $eqType_name;
	protected $eqReal_id = null;
	protected $isVisible = 0;
	protected $isEnable = 0;
	protected $configuration;
	protected $specificCapatibilities;
	protected $timeout;
	protected $category;
	protected $display;
	protected $order;
	protected $_debug = false;
	protected $_object = null;
	private static $_templateArray = array();

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM eqLogic
        WHERE id=:id';
		return self::cast(DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__));
	}

	private static function cast($_inputs) {
		if (is_object($_inputs)) {
			if (class_exists($_inputs->getEqType_name())) {
				return cast($_inputs, $_inputs->getEqType_name());
			}
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

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__, 'el') . '
        FROM eqLogic el
        LEFT JOIN object ob ON el.object_id=ob.id
        ORDER BY ob.name,el.name';
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

	public static function byObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null) {
		$values = array();
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM eqLogic';
		if ($_object_id == null) {
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
		if ($_eqType_name != null) {
			$values['eqType_name'] = $_eqType_name;
			$sql .= ' AND eqType_name=:eqType_name';
		}
		if ($_logicalId != null) {
			$values['logicalId'] = $_logicalId;
			$sql .= ' AND logicalId=:logicalId';
		}
		$sql .= ' ORDER BY `order`,category';
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
		$values = array(
			'configuration' => '%' . $_configuration . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
        FROM eqLogic
        WHERE configuration LIKE :configuration';
		if ($_type != null) {
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
		if ($_object_id == null) {
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
				if ($cmd->getEventOnly() == 1) {
					$sendReport = true;
				}
			}
			$logicalId = 'noMessage' . $eqLogic->getId();
			if ($sendReport) {
				$noReponseTimeLimit = $eqLogic->getTimeout();
				if (count(message::byPluginLogicalId('core', $logicalId)) == 0) {
					if ($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s')) < date('Y-m-d H:i:s', strtotime('-' . $noReponseTimeLimit . ' minutes' . date('Y-m-d H:i:s')))) {
						$message = __('Attention', __FILE__) . ' ' . $eqLogic->getHumanName();
						$message .= __(' n\'a pas envoyé de message depuis plus de ', __FILE__) . $noReponseTimeLimit . __(' min (vérifier les piles)', __FILE__);
						message::add('core', $message, '', $logicalId);
						foreach ($cmds as $cmd) {
							if ($cmd->getEventOnly() == 1) {
								$cmd->event('error::timeout');
							}
						}
					}
				} else {
					if ($eqLogic->getStatus('lastCommunication', date('Y-m-d H:i:s')) > date('Y-m-d H:i:s', strtotime('-' . $noReponseTimeLimit . ' minutes' . date('Y-m-d H:i:s')))) {
						foreach (message::byPluginLogicalId('core', $logicalId) as $message) {
							$message->remove();
						}
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
			for ($i = 0; $i < count($matches[0]); $i++) {
				if (isset($matches[1][$i]) && isset($matches[2][$i])) {
					$eqLogic = self::byObjectNameEqLogicName($matches[1][$i], $matches[2][$i]);
					if (is_object($eqLogic)) {
						$text = str_replace($matches[0][$i], '#eqLogic' . $eqLogic->getId() . '#', $text);
					}
				}
			}
		}

		return $text;
	}

	/*     * *********************Méthodes d'instance************************* */

	public function copy($_name) {
		$eqLogicCopy = clone $this;
		$eqLogicCopy->setName($_name);
		$eqLogicCopy->setId('');
		$eqLogicCopy->save();
		foreach ($this->getCmd() as $cmd) {
			$cmdCopy = clone $cmd;
			$cmdCopy->setId('');
			$cmdCopy->setEqLogic_id($eqLogicCopy->getId());
			$cmdCopy->save();
		}
		return $eqLogicCopy;
	}

	public function getTableName() {
		return 'eqLogic';
	}

	public function hasOnlyEventOnlyCmd() {
		$values = array(
			'eqLogic_id' => $this->getId(),
		);
		$sql = 'SELECT count(*)
        FROM cmd
        WHERE eqLogic_id=:eqLogic_id
        AND eventOnly!=1
        AND type="info"
        AND isVisible=1
        AND isHistorized=0';
		$result = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		return ($result['count(*)'] > 0) ? false : true;
	}

	public function toHtml($_version = 'dashboard') {
		if ($_version == '') {
			throw new Exception(__('La version demandée ne peut pas être vide (mobile, dashboard ou scénario)', __FILE__));
		}
		if (!$this->hasRight('r')) {
			return '';
		}
		$hasOnlyEventOnly = $this->hasOnlyEventOnlyCmd();
		if ($hasOnlyEventOnly) {
			$sql = 'SELECT `value` FROM cache
           WHERE `key`="widgetHtml' . $_version . $this->getId() . '"';
			$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
			if ($result['value'] != '') {
				return $result['value'];
			}
		}

		$cmd_html = '';
		$version = jeedom::versionAlias($_version);
		$vcolor = 'cmdColor';
		if ($version == 'mobile') {
			$vcolor = 'mcmdColor';
		}
		if ($this->getPrimaryCategory() == '') {
			$cmdColor = '';
		} else {
			$cmdColor = jeedom::getConfiguration('eqLogic:category:' . $this->getPrimaryCategory() . ':' . $vcolor);
		}
		if ($this->getIsEnable()) {
			foreach ($this->getCmd(null, null, true) as $cmd) {
				if ($cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
					$cmd_html .= '<br/>';
				}
				$cmd_html .= $cmd->toHtml($_version, '', $cmdColor);
				if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
					$cmd_html .= '<br/>';
				}
			}
		}
		$replace = array(
			'#id#' => $this->getId(),
			'#name#' => $this->getName(),
			'#eqLink#' => $this->getLinkToConfiguration(),
			'#category#' => $this->getPrimaryCategory(),
			'#background_color#' => $this->getBackgroundColor($version),
			'#cmd#' => $cmd_html,
			'#style#' => '',
			'#max_width#' => '650px',
			'#logicalId#' => $this->getLogicalId(),
			'#battery#' => $this->getConfiguration('batteryStatus', -2),
			'#batteryDatetime#' => $this->getConfiguration('batteryStatusDatetime', __('inconnue', __FILE__)),
		);

		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotShowObjectNameOnView', 0) == 0) {
			$object = $this->getObject();
			$replace['#object_name#'] = (is_object($object)) ? '(' . $object->getName() . ')' : '';
		} else {
			$replace['#object_name#'] = '';
		}
		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotShowNameOnView') == 1) {
			$replace['#name#'] = '';
		}
		if (($_version == 'mobile' || $_version == 'dashboard') && $this->getDisplay('doNotShowNameOnDashboard') == 1) {
			$replace['#name#'] = '';
		}
		if (($_version == 'dview' || $_version == 'mview') && $this->getDisplay('doNotDisplayBatteryLevelOnView') == 1) {
			$replace['#battery#'] = -1;
		}
		if ($_version == 'dashboard' && $this->getDisplay('doNotDisplayBatteryLevelOnDashboard') == 1) {
			$replace['#battery#'] = -1;
		}

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace['#' . $key . '#'] = $value;
			}
		}
		if (!isset(self::$_templateArray[$version])) {
			self::$_templateArray[$version] = getTemplate('core', $version, 'eqLogic');
		}
		$html = template_replace($replace, self::$_templateArray[$version]);
		if ($hasOnlyEventOnly) {
			cache::set('widgetHtml' . $_version . $this->getId(), $html, 0);
		}
		return $html;
	}

	public function emptyCacheWidget() {
		$sql = 'DELETE FROM cache
    WHERE `key` LIKE "widgetHtml%' . $this->getId() . '"';
		DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
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
		return DB::remove($this);
	}

	public function save() {
		if ($this->getName() == '') {
			throw new Exception(__('Le nom de l\'équipement ne peut pas être vide : ', __FILE__) . print_r($this, true));
		}
		if ($this->getId() != '') {
			$this->emptyCacheWidget();
			$this->setConfiguration('updatetime', date('Y-m-d H:i:s'));
		} else {
			$this->setConfiguration('createtime', date('Y-m-d H:i:s'));
		}
		return DB::save($this);
	}

	public function refresh() {
		DB::refresh($this);
	}

	public function getLinkToConfiguration() {
		return 'index.php?v=d&p=' . $this->getEqType_name() . '&m=' . $this->getEqType_name() . '&id=' . $this->getId();
	}

	public function collectInProgress() {
		$values = array(
			'eqLogic_id' => $this->getId(),
		);
		$sql = 'SELECT count(*)
    FROM cmd
    WHERE eqLogic_id=:eqLogic_id
    AND collect=1
    AND eventOnly=0';
		$results = DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		if ($results['count(*)'] > 0) {
			return true;
		}
		return false;
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

	public function batteryStatus($_pourcent, $_datetime = '') {
		if ($this->getConfiguration('batteryStatus', -1) == $_pourcent) {
			return;
		}
		if ($_pourcent > 20) {
			foreach (message::byPluginLogicalId($this->getEqType_name(), 'lowBattery' . $this->getId()) as $message) {
				$message->remove();
			}
			foreach (message::byPluginLogicalId($this->getEqType_name(), 'noBattery' . $this->getId()) as $message) {
				$message->remove();
			}
		} else if ($_pourcent > 0 && $_pourcent <= 20) {
			$logicalId = 'lowBattery' . $this->getId();
			$message = 'Le module ' . $this->getEqType_name() . ' ';
			$message .= $this->getHumanName() . ' a moins de ' . $_pourcent . '% de batterie';
			message::add($this->getEqType_name(), $message, '', $logicalId);
		} else {
			$logicalId = 'noBattery' . $this->getId();
			$message = __('Le module ', __FILE__) . $this->getEqType_name() . ' ';
			$message .= $this->getHumanName() . __(' n\'a plus de batterie (', __FILE__) . $_pourcent . ' %)';
			message::add($this->getEqType_name(), $message, '', $logicalId);
		}
		$this->setConfiguration('batteryStatus', $_pourcent);
		if ($_datetime != '') {
			$this->setConfiguration('batteryStatusDatetime', $_datetime);
		} else {
			$this->setConfiguration('batteryStatusDatetime', date('Y-m-d H:i:s'));
		}

		$this->save();
	}

	public function refreshWidget() {
		nodejs::pushUpdate('eventEqLogic', $this->getId());
	}

	public function hasRight($_right, $_needAdmin = false, $_user = null) {
		if (!is_object($_user)) {
			if (session_status() != PHP_SESSION_NONE || !isset($_SESSION) || !isset($_SESSION['user'])) {
				return true;
			}
			$_user = $_SESSION['user'];
		}

		if (!is_object($_user)) {
			return false;
		}
		if (!isConnect()) {
			return false;
		}
		if (isConnect('admin')) {
			return true;
		}
		if ($_right == 'x') {
			$rights = rights::byuserIdAndEntity($_user->getId(), 'eqLogic' . $this->getId() . 'action');
		} elseif ($_right == 'r') {
			$rights = rights::byuserIdAndEntity($_user->getId(), 'eqLogic' . $this->getId() . 'view');
		}
		if (!is_object($rights)) {
			return ($_needAdmin) ? false : true;
		}
		return $rights->getRight();
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
		if ($this->_object == null) {
			$this->_object = object::byId($this->object_id);
		}
		return $this->_object;
	}

	public function getEqType_name() {
		return $this->eqType_name;
	}

	public function getIsVisible() {
		return $this->isVisible;
	}

	public function getIsEnable() {
		return $this->isEnable;
	}

	public function getCmd($_type = null, $_logicalId = null, $_visible = null, $_multiple = false) {
		if ($_logicalId != null) {
			return cmd::byEqLogicIdAndLogicalId($this->id, $_logicalId, $_multiple);
		}
		return cmd::byEqLogicId($this->id, $_type, $_visible, $this);
	}

	public function searchCmdByConfiguration($_configuration, $_type = null) {
		return cmd::searchConfigurationEqLogic($this->id, $_configuration, $_type);
	}

	public function getEqReal_id() {
		return $this->eqReal_id;
	}

	public function getEqReal() {
		return eqReal::byId($this->eqReal_id);
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setName($name) {
		$name = str_replace(array('&', '#', ']', '[', '%', "'"), '', $name);
		$this->name = $name;
	}

	public function setLogicalId($logicalId) {
		$this->logicalId = $logicalId;
	}

	public function setObject_id($object_id = null) {
		$this->object_id = (!is_numeric($object_id)) ? null : $object_id;
	}

	public function setEqType_name($eqType_name) {
		$this->eqType_name = $eqType_name;
	}

	public function setEqReal_id($eqReal_id) {
		$this->eqReal_id = $eqReal_id;
	}

	public function setIsVisible($isVisible) {
		$this->isVisible = $isVisible;
	}

	public function setIsEnable($_isEnable) {
		$this->isEnable = $_isEnable;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
	}

	public function getStatus($_key = '', $_default = '') {
		$status = cache::byKey('core::eqLogic' . $this->getId() . '::' . $_key);
		return $status->getValue($_default);
	}

	public function setStatus($_key, $_value) {
		return cache::set('core::eqLogic' . $this->getId() . '::' . $_key, $_value, 0);
	}

	public function getSpecificCapatibilities($_key = '', $_default = '') {
		return utils::getJsonAttr($this->specificCapatibilities, $_key, $_default);
	}

	public function setSpecificCapatibilities($_key, $_value) {
		$this->specificCapatibilities = utils::setJsonAttr($this->specificCapatibilities, $_key, $_value);
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function setDisplay($_key, $_value) {
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
	}

	public function getTimeout() {
		return $this->timeout;
	}

	public function setTimeout($timeout) {
		if (is_string($timeout) || is_nan(intval($timeout)) || $timeout < 1) {
			$timeout == '';
		}
		$this->timeout = $timeout;
	}

	public function getCategory($_key = '', $_default = '') {
		if ($_key == 'other' && strpos($this->category, "1") === false) {
			return 1;
		}
		return utils::getJsonAttr($this->category, $_key, $_default);
	}

	public function setCategory($_key, $_value) {
		$this->category = utils::setJsonAttr($this->category, $_key, $_value);
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

	function getOrder() {
		if ($this->order == '') {
			return 0;
		}
		return $this->order;
	}

	function setOrder($order) {
		$this->order = $order;
	}

}

?>
