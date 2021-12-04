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

class jeeObject {
	/*     * *************************Attributs****************************** */

	protected $id;
	protected $name;
	protected $father_id = null;
	protected $isVisible = 1;
	protected $position;
	protected $configuration;
	protected $display;
	protected $image;
	protected $_child = array();
	protected $_changed = false;
	protected $_summaryChanged = false;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id) {
		if ($_id == '' || $_id == -1) {
			return;
		}
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM object
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function byName($_name) {
		$values = array(
			'name' => $_name,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM object
		WHERE name=:name';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function all($_onlyVisible = false, $_byPosition = false) {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM object ';
		if ($_onlyVisible) {
			$sql .= ' WhERE isVisible = 1';
		}
		if (!$_byPosition) {
			$sql .= ' ORDER BY father_id, position, name';
		} else {
			$sql .= ' ORDER BY position, father_id, name';
		}

		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function rootObject($_all = false, $_onlyVisible = false) {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM object
		WHERE father_id IS NULL';
		if ($_onlyVisible) {
			$sql .= ' AND isVisible = 1';
		}
		$sql .= ' ORDER BY position';
		if ($_all === false) {
			$sql .= ' LIMIT 1';
			$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
			if (!is_object($result)) {
				$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
				FROM object';
				if ($_onlyVisible) {
					$sql .= ' WHERE isVisible = 1';
				}
				$sql .= ' ORDER BY position';
				$sql .= ' LIMIT 1';
				$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
			}
			return $result;
		}
		$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		if (count($result) == 0) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM object';
			if ($_onlyVisible) {
				$sql .= ' WHERE isVisible = 1';
			}
			$sql .= ' ORDER BY position';
			$result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		}
		return $result;
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
		preg_match_all("/#object([0-9]*)#/", $text, $matches);
		foreach ($matches[1] as $object_id) {
			if (is_numeric($object_id)) {
				$object = self::byId($object_id);
				if (is_object($object)) {
					$text = str_replace('#object' . $object_id . '#', '#' . $object->getHumanName() . '#', $text);
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
		preg_match_all("/#\[(.*?)\]#/", $text, $matches);
		$countMatches = count($matches[0]);
		for ($i = 0; $i < $countMatches; $i++) {
			if (isset($matches[1][$i])) {
				$object = self::byName($matches[1][$i]);
				if (is_object($object)) {
					$text = str_replace($matches[0][$i], '#object' . $object->getId() . '#', $text);
				}
			}
		}
		return $text;
	}

	public static function buildTree($_object = null, $_visible = true) {
		$return = array();
		if (!is_object($_object)) {
			$object_list = self::rootObject(true, $_visible);
		} else {
			$object_list = $_object->getChild($_visible);
		}
		if (is_array($object_list) && count($object_list) > 0) {
			foreach ($object_list as $object) {
				$return[] = $object;
				$return = array_merge($return, self::buildTree($object, $_visible));
			}
		}
		$rightReturn = array();
		foreach ($return as $object) {
			if ($object->hasRight('r')) {
				$rightReturn[] = $object;
			}
		}
		return $rightReturn;
	}

	public static function getUISelectList($_none = true) {
		$allObject = self::buildTree(null, false);
		$options = '';
		if ($_none) $options .= '<option value="">' . __('Aucun', __FILE__) . '</option>';
		foreach ($allObject as $object) {
			$decay = $object->getConfiguration('parentNumber');
			$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $decay) . $object->getName() . '</option>';
		}
		return $options;
	}

	public static function fullData($_restrict = array(), $_user = null) {
		$return = array();
		foreach (jeeObject::all(true) as $object) {
			if (!isset($_restrict['object']) || !is_array($_restrict['object']) || isset($_restrict['object'][$object->getId()])) {
				$object_return = utils::o2a($object);
				$object_return['eqLogics'] = array();
				foreach ($object->getEqLogic(true, true) as $eqLogic) {
					if (is_object($_user) && !$eqLogic->hasRight('r', $_user)) {
						continue;
					}
					if (!isset($_restrict['eqLogic']) || !is_array($_restrict['eqLogic']) || isset($_restrict['eqLogic'][$eqLogic->getId()])) {
						$eqLogic_return = utils::o2a($eqLogic);
						$eqLogic_return['cmds'] = array();
						foreach (($eqLogic->getCmd()) as $cmd) {
							if (!isset($_restrict['cmd']) || !is_array($_restrict['cmd']) || isset($_restrict['cmd'][$cmd->getId()])) {
								$cmd_return = utils::o2a($cmd);
								if ($cmd->getType() == 'info') {
									$cmd_return['state'] = $cmd->execCmd();
								}
								$eqLogic_return['cmds'][] = $cmd_return;
							}
						}
						$object_return['eqLogics'][] = $eqLogic_return;
					}
				}
				$return[] = $object_return;
			}
		}
		return $return;
	}

	public static function searchConfiguration($_search) {
		$values = array(
			'configuration' => '%' . $_search . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM object
		WHERE `configuration` LIKE :configuration';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function deadCmd() {
		$return = array();
		foreach ((jeeObject::all()) as $object) {
			$sumaries = $object->getConfiguration('summary');
			if (!is_array($sumaries) || count($sumaries) < 1) {
				continue;
			}
			foreach ($sumaries as $key => $summary) {
				foreach ($summary as $cmdInfo) {
					preg_match_all("/#([0-9]*)#/", $cmdInfo['cmd'], $matches);
					foreach ($matches[1] as $cmd_id) {
						if (!cmd::byId($cmd_id)) {
							$return[] = array('detail' => 'Résumé ' . $object->getName(), 'help' => config::byKey('object:summary')[$key]['name'], 'who' => $cmdInfo['cmd']);
						}
					}
				}
			}
		}
		return $return;
	}

	public static function checkSummaryUpdate($_cmd_id) {
		$objects = self::searchConfiguration('#' . $_cmd_id . '#');
		if (!is_array($objects) || count($objects) == 0) {
			return;
		}
		$toRefreshCmd = array();
		$global = array();
		foreach ($objects as $object) {
			$summaries = $object->getConfiguration('summary');
			if (!is_array($summaries)) {
				continue;
			}
			$event = array('object_id' => $object->getId(), 'keys' => array());
			foreach ($summaries as $key => $summary) {
				foreach ($summary as $cmd_info) {
					preg_match_all("/#([0-9]*)#/", $cmd_info['cmd'], $matches);
					foreach ($matches[1] as $cmd_id) {
						if ($cmd_id == $_cmd_id) {
							$value = $object->getSummary($key);
							$event['keys'][$key] = array('value' => $value);
							$toRefreshCmd[] = array('key' => $key, 'object' => $object, 'value' => $value);
							if ($object->getConfiguration('summary::global::' . $key, 0) == 1) {
								$global[$key] = 1;
							}
						}
					}
				}
			}
			$events[] = $event;
		}
		if (count($toRefreshCmd) > 0) {
			foreach ($toRefreshCmd as $value) {
				try {
					$value['object']->setCache('summaryHtmldashboard', '');
					$value['object']->setCache('summaryHtmlmobile', '');
					if ($value['object']->getConfiguration('summary_virtual_id') == '') {
						continue;
					}
					$virtual = eqLogic::byId($value['object']->getConfiguration('summary_virtual_id'));
					if (!is_object($virtual)) {
						$object->getConfiguration('summary_virtual_id', '');
						$object->save();
						continue;
					}
					$cmd = $virtual->getCmd('info', $value['key']);
					if (!is_object($cmd)) {
						continue;
					}
					$cmd->event($value['value']);
				} catch (Exception $e) {
				}
			}
		}
		if (count($global) > 0) {
			cache::set('globalSummaryHtmldashboard', '');
			cache::set('globalSummaryHtmlmobile', '');
			$event = array('object_id' => 'global', 'keys' => array());
			foreach ($global as $key => $value) {
				try {
					$result = jeeObject::getGlobalSummary($key);
					if ($result === null) {
						continue;
					}
					$event['keys'][$key] = array('value' => $result);
					$virtual = eqLogic::byLogicalId('summaryglobal', 'virtual');
					if (!is_object($virtual)) {
						continue;
					}
					$cmd = $virtual->getCmd('info', $key);
					if (!is_object($cmd)) {
						continue;
					}
					$cmd->event($result);
				} catch (Exception $e) {
				}
			}
			$events[] = $event;
		}
		if (count($events) > 0) {
			event::adds('jeeObject::summary::update', $events);
		}
	}

	public static function getGlobalSummary($_key) {
		if ($_key == '') {
			return null;
		}
		$def = config::byKey('object:summary');
		$objects = self::all();
		$value = array();
		foreach ($objects as $object) {
			if ($object->getConfiguration('summary::global::' . $_key, 0) == 0) {
				continue;
			}
			$result = $object->getSummary($_key, true);
			if ($result === null || !is_array($result)) {
				continue;
			}
			$value = array_merge($value, $result);
		}
		if (count($value) == 0) {
			return null;
		}
		return round(jeedom::calculStat($def[$_key]['calcul'], $value), 1);
	}

	public static function getGlobalHtmlSummary($_version = 'dashboard') {
		$virtual = eqLogic::byLogicalId('summaryglobal', 'virtual');
		$objects = self::all();
		$def = config::byKey('object:summary');
		$values = array();
		$return = '<span class="objectSummaryContainer objectSummaryglobal" data-version="' . $_version . '">';
		foreach ($def as $key => $value) {
			foreach ($objects as $object) {
				if ($object->getConfiguration('summary::global::' . $key, 0) == 0) {
					continue;
				}
				if (!isset($values[$key])) {
					$values[$key] = array();
				}
				$result = $object->getSummary($key, true);
				if ($result === null || !is_array($result)) {
					continue;
				}
				$values[$key] = array_merge($values[$key], $result);
			}
		}
		foreach ($values as $key => $value) {
			if (count($value) == 0) {
				continue;
			}
			$style = '';
			$allowDisplayZero = $def[$key]['allowDisplayZero'];
			if ($def[$key]['calcul'] == 'text') {
				$result = trim(implode(',', $value), ',');
				$allowDisplayZero = 1;
			} else {
				$result = round(jeedom::calculStat($def[$key]['calcul'], $value), 1);
			}
			if ($allowDisplayZero == 0 && $result == 0) {
				$style = 'display:none;';
			}

			$icon = $def[$key]['icon'];
			if (!isset($def[$key]['iconnul'])) {
				$def[$key]['iconnul'] = $def[$key]['icon'];
				$def[$key]['hidenumber'] = 0;
				$def[$key]['hidenulnumber'] = 0;
			} else {
				if ($result == 0) $icon = $def[$key]['iconnul'];
			}
			$classSummaryAction = '';
			if (is_object($virtual)) {
				foreach ($virtual->getCmd('action') as $cmd) {
					if ($cmd->getConfiguration('summary::key') == $key) {
						$classSummaryAction = 'objectSummaryAction';
						break;
					}
				}
			}
			$return .= '<span class="objectSummaryParent ' . $classSummaryAction . ' cursor" data-summary="' . $key . '" data-object_id="" style="' . $style . '" data-displayZeroValue="' . $allowDisplayZero . '" data-icon="' . urlencode($def[$key]['icon']) . '" data-iconnul="' . urlencode($def[$key]['iconnul']) . '" data-hidenulnumber="' . $def[$key]['hidenulnumber'] . '">';
			if ($def[$key]['hidenumber'] == 1 || ($result == 0 && $def[$key]['hidenulnumber'] == 1)) {
				$return .= $icon . ' <sup><span style="display:none;" class="objectSummary' . $key . '">' . $result . '</span> ' . $def[$key]['unit'] . '</sup></span>';
			} else {
				$return .= $icon . ' <sup><span class="objectSummary' . $key . '">' . $result . '</span> ' . $def[$key]['unit'] . '</sup></span>';
			}
		}
		$return = trim($return) . '</span>';
		cache::set('globalSummaryHtml' . $_version, $return);
		return $return;
	}

	public static function createSummaryToVirtual($_key = '') {
		if ($_key == '') {
			return;
		}
		$def = config::byKey('object:summary');
		if (!isset($def[$_key])) {
			return;
		}
		global $JEEDOM_INTERNAL_CONFIG;
		try {
			$plugin = plugin::byId('virtual');
			if (!is_object($plugin)) {
				$update = update::byLogicalId('virtual');
				if (!is_object($update)) {
					$update = new update();
				}
				$update->setLogicalId('virtual');
				$update->setSource('market');
				$update->setConfiguration('version', 'stable');
				$update->save();
				$update->doUpdate();
				sleep(2);
				$plugin = plugin::byId('virtual');
			}
		} catch (Exception $e) {
			$update = update::byLogicalId('virtual');
			if (!is_object($update)) {
				$update = new update();
			}
			$update->setLogicalId('virtual');
			$update->setSource('market');
			$update->setConfiguration('version', 'stable');
			$update->save();
			$update->doUpdate();
			sleep(2);
			$plugin = plugin::byId('virtual');
		}
		if (!$plugin->isActive()) {
			$plugin->setIsEnable(1);
		}
		if (!is_object($plugin)) {
			throw new Exception(__('Le plugin virtuel doit être installé', __FILE__));
		}
		if (!$plugin->isActive()) {
			throw new Exception(__('Le plugin virtuel doit être actif', __FILE__));
		}

		$virtualGlobal = eqLogic::byLogicalId('summaryglobal', 'virtual');
		if (!is_object($virtualGlobal)) {
			$virtualGlobal = new virtual();
			$virtualGlobal->setName(__('Résumé Global', __FILE__));
			$virtualGlobal->setIsVisible(0);
			$virtualGlobal->setIsEnable(1);
		}
		$virtualGlobal->setIsEnable(1);
		$virtualGlobal->setConfiguration('icon', 'virtual_summary_icon');
		$virtualGlobal->setLogicalId('summaryglobal');
		$virtualGlobal->setEqType_name('virtual');
		$virtualGlobal->save();
		$cmd = $virtualGlobal->getCmd('info', $_key);
		if (!is_object($cmd)) {
			$cmd = new virtualCmd();
			$cmd->setName($def[$_key]['name']);
			$cmd->setIsHistorized(1);
		}
		$cmd->setEqLogic_id($virtualGlobal->getId());
		$cmd->setLogicalId($_key);
		$cmd->setType('info');
		if ($def[$_key]['calcul'] == 'text') {
			$cmd->setSubtype('string');
		} else {
			$cmd->setSubtype('numeric');
		}
		$cmd->setUnite($def[$_key]['unit']);
		$cmd->save();

		foreach ((jeeObject::all()) as $object) {
			$summaries = $object->getConfiguration('summary');
			if (!is_array($summaries)) {
				continue;
			}
			if (!isset($summaries[$_key]) || !is_array($summaries[$_key]) || count($summaries[$_key]) == 0) {
				continue;
			}
			$virtual = eqLogic::byLogicalId('summary' . $object->getId(), 'virtual');
			if (!is_object($virtual)) {
				$virtual = new virtual();
				$virtual->setName(__('Résumé', __FILE__));
				$virtual->setIsVisible(0);
				$virtual->setIsEnable(1);
			}
			$virtual->setIsEnable(1);
			$virtual->setLogicalId('summary' . $object->getId());
			$virtual->setEqType_name('virtual');
			$virtual->setObject_id($object->getId());
			$virtual->setConfiguration('icon', 'virtual_summary_icon');
			$virtual->save();
			$object->setConfiguration('summary_virtual_id', $virtual->getId());
			$object->save();
			$cmd = $virtual->getCmd('info', $_key);
			if (!is_object($cmd)) {
				$cmd = new virtualCmd();
				$cmd->setName($def[$_key]['name']);
				$cmd->setIsHistorized(1);
			}
			$cmd->setEqLogic_id($virtual->getId());
			$cmd->setLogicalId($_key);
			$cmd->setType('info');
			if ($def[$_key]['calcul'] == 'text') {
				$cmd->setSubtype('string');
			} else {
				$cmd->setSubtype('numeric');
			}
			$cmd->setUnite($def[$_key]['unit']);
			$cmd->save();

			$eqLogics = $object->getEqLogicBySummary($_key, true, false);
			$cmd_genericType = array();
			foreach ($eqLogics as $eqLogic) {
				foreach ($eqLogic->getCmd() as $cmd) {
					if ($cmd->getGeneric_type() == '') {
						continue;
					}
					if (!isset($cmd_genericType[$cmd->getGeneric_type()])) {
						$cmd_genericType[$cmd->getGeneric_type()] = array();
					}
					$cmd_genericType[$cmd->getGeneric_type()][] = $cmd->getId();
				}
			}
			foreach ($cmd_genericType as $genericType => $cmds) {
				if (!isset($JEEDOM_INTERNAL_CONFIG['cmd']['generic_type'][$genericType]) || !isset($JEEDOM_INTERNAL_CONFIG['cmd']['generic_type'][$genericType]['summary']) || !isset($JEEDOM_INTERNAL_CONFIG['cmd']['generic_type'][$genericType]['summary']['subtype'])) {
					continue;
				}
				$cmd = $virtual->getCmd('action', $_key . '::action::' . $genericType);
				if (!is_object($cmd)) {
					$cmd = new virtualCmd();
					$cmd->setName($def[$_key]['name'] . ' ' . $JEEDOM_INTERNAL_CONFIG['cmd']['generic_type'][$genericType]['name']);
					$cmd->setIsHistorized(0);
				}
				$cmd->setEqLogic_id($virtual->getId());
				$cmd->setLogicalId($_key . '::action::' . $genericType);
				$cmd->setType('action');
				$cmd->setSubtype($JEEDOM_INTERNAL_CONFIG['cmd']['generic_type'][$genericType]['summary']['subtype']);
				$cmd->setConfiguration('infoName', 'core::jeeObject::summary::' . $_key);
				$cmd->setConfiguration('summary::object_id', $object->getId());
				$cmd->setConfiguration('summary::generic_type', $genericType);
				$cmd->setConfiguration('summary::key', $_key);
				$cmd->save();

				$cmd = $virtualGlobal->getCmd('action', $_key . '::action::' . $genericType);
				if (!is_object($cmd)) {
					$cmd = new virtualCmd();
					$cmd->setName($def[$_key]['name'] . ' ' . $JEEDOM_INTERNAL_CONFIG['cmd']['generic_type'][$genericType]['name']);
					$cmd->setIsHistorized(0);
				}
				$cmd->setEqLogic_id($virtualGlobal->getId());
				$cmd->setLogicalId($_key . '::action::' . $genericType);
				$cmd->setType('action');
				$cmd->setSubtype($JEEDOM_INTERNAL_CONFIG['cmd']['generic_type'][$genericType]['summary']['subtype']);
				$cmd->setConfiguration('infoName', 'core::jeeObject::summary::' . $_key);
				$cmd->setConfiguration('summary::object_id', 'global');
				$cmd->setConfiguration('summary::generic_type', $genericType);
				$cmd->setConfiguration('summary::key', $_key);
				$cmd->save();
			}
		}
	}

	public static function actionOnSummary($_cmd, $_options = null) {
		if ($_cmd->getConfiguration('summary::object_id') == 'global') {
			foreach ((jeeObject::all()) as $object) {
				if ($object->getConfiguration('summary::global::' . $_cmd->getConfiguration('summary::key'), 0) == 0) {
					continue;
				}
				$object->summaryAction($_cmd, $_options);
			}
		} else {
			$object = self::byId($_cmd->getConfiguration('summary::object_id'));
			if (!is_object($object)) {
				throw new Exception(__('L\'objet n\'existe pas :', __FILE__) . ' ' . $_cmd->getConfiguration('summary::object_id'));
			}
			$object->summaryAction($_cmd, $_options);
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function summaryAction($_cmd, $_options = null) {
		$eqLogics = $this->getEqLogicBySummary($_cmd->getConfiguration('summary::key'), true, false);
		foreach ($eqLogics as $eqLogic) {
			foreach ($eqLogic->getCmd() as $cmd) {
				if ($cmd->getGeneric_type() == '' || $cmd->getGeneric_type() != $_cmd->getConfiguration('summary::generic_type')) {
					continue;
				}
				try {
					$cmd->execCmd($_options);
				} catch (\Exception $e) {
					log::add('cmd', 'error', $cmd->getHumanName() . ' ' . $e->getMessage());
				}
			}
		}
	}

	public function getTableName() {
		return 'object';
	}

	public function checkTreeConsistency($_fathers = array()) {
		$father = $this->getFather();
		if (!is_object($father)) {
			$this->setFather_id(null);
			$this->save(true);
			return;
		}
		if (in_array($this->getFather_id(), $_fathers)) {
			throw new Exception(__('Problème dans l\'arbre des objets', __FILE__));
		}
		$_fathers[] = $this->getId();

		$father->checkTreeConsistency($_fathers);
	}

	public function preSave() {
		if (is_numeric($this->getFather_id()) && $this->getFather_id() == $this->getId()) {
			throw new Exception(__('L\'objet ne peut pas être son propre parent', __FILE__));
		}
		$this->checkTreeConsistency();
		$this->setConfiguration('parentNumber', $this->parentNumber());
		if ($this->getConfiguration('tagColor') == '') {
			$this->setConfiguration('tagColor', '#000000');
		}
		if ($this->getConfiguration('tagTextColor') == '') {
			$this->setConfiguration('tagTextColor', '#FFFFFF');
		}
		if ($this->getConfiguration('mobile::summaryTextColor') == '') {
			$this->setConfiguration('mobile::summaryTextColor', '');
		}
		if ($this->getDisplay('icon') == '') {
			$this->setConfiguration('icon', '<i class="far fa-lemon"></i>');
		}
	}

	public function save($_direct = false) {
		if ($this->_changed) {
			cache::set('globalSummaryHtmldashboard', '');
			cache::set('globalSummaryHtmlmobile', '');
			$this->setCache('summaryHtmldashboard', '');
			$this->setCache('summaryHtmlmobile', '');
		}
		return DB::save($this, $_direct);
	}

	public function getChild($_visible = true) {
		if (!isset($this->_child[$_visible])) {
			$values = array(
				'id' => $this->id,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM object
			WHERE father_id=:id';
			if ($_visible) {
				$sql .= ' AND isVisible=1 ';
			}
			$sql .= ' ORDER BY position';
			$this->_child[$_visible] = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
		}
		return $this->_child[$_visible];
	}

	public function getChilds() {
		$return = array();
		foreach (($this->getChild()) as $child) {
			$return[] = $child;
			$return = array_merge($return, $child->getChilds());
		}
		return $return;
	}

	public function getEqLogic($_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null, $_searchOnchild = false) {
		$eqLogics = eqLogic::byObjectId($this->getId(), $_onlyEnable, $_onlyVisible, $_eqType_name, $_logicalId);
		if (is_array($eqLogics)) {
			foreach ($eqLogics as &$eqLogic) {
				$eqLogic->setObject($this);
			}
		}
		if ($_searchOnchild) {
			$child_object = jeeObject::buildTree($this);
			if (count($child_object) > 0) {
				foreach ($child_object as $object) {
					$eqLogics = array_merge($eqLogics, $object->getEqLogic($_onlyEnable, $_onlyVisible, $_eqType_name, $_logicalId));
				}
			}
		}
		return $eqLogics;
	}

	public function getEqLogicsFromSummary($_summary = '', $_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null) {
		$def = config::byKey('object:summary');
		if ($_summary == '' || !isset($def[$_summary])) {
			return null;
		}
		$summaries = $this->getConfiguration('summary');
		if (!isset($summaries[$_summary])) {
			return array();
		}
		$eqLogics = eqLogic::byObjectId($this->getId(), $_onlyEnable, $_onlyVisible, $_eqType_name, $_logicalId);
		$return = array();
		$ids = array();
		foreach ($summaries[$_summary] as $infos) {
			if (isset($infos['enable']) && $infos['enable'] != 1) {
				continue;
			}
			$cmd = cmd::byId(str_replace('#', '', $infos['cmd']));
			if (is_object($cmd)) {
				$id = $cmd->getEqLogic_id();
				//no duplicate:
				if (in_array($id, $ids)) continue;
				array_push($ids, $id);
				$eqLogic = eqLogic::byId($id);
				if (is_object($eqLogic)) {
					if ($_onlyEnable && $eqLogic->getIsEnable() == 0) continue;
					if ($_onlyVisible && $eqLogic->getIsVisible() == 0) continue;
					$return[] = utils::o2a($eqLogic);
				}
			}
		}
		return $return;
	}

	public function getEqLogicBySummary($_summary = '', $_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null) {
		$def = config::byKey('object:summary');
		if ($_summary == '' || !isset($def[$_summary])) {
			return null;
		}
		$summaries = $this->getConfiguration('summary');
		if (!isset($summaries[$_summary])) {
			return array();
		}
		$eqLogics = eqLogic::byObjectId($this->getId(), $_onlyEnable, $_onlyVisible, $_eqType_name, $_logicalId);
		$eqLogics_id = array();
		foreach ($summaries[$_summary] as $infos) {
			if (isset($infos['enable']) && $infos['enable'] != 1) {
				continue;
			}
			$cmd = cmd::byId(str_replace('#', '', $infos['cmd']));
			if (is_object($cmd)) {
				$eqLogics_id[$cmd->getEqLogic_id()] = $cmd->getEqLogic_id();
			}
		}
		$return = array();
		if (is_array($eqLogics)) {
			foreach ($eqLogics as $eqLogic) {
				if (isset($eqLogics_id[$eqLogic->getId()])) {
					$eqLogic->setObject($this);
					$return[] = $eqLogic;
				}
			}
		}
		return $return;
	}

	public function getScenario($_onlyEnable = true, $_onlyVisible = false) {
		return scenario::byObjectId($this->getId(), $_onlyEnable, $_onlyVisible);
	}

	public function preRemove() {
		dataStore::removeByTypeLinkId('object', $this->getId());
		$values = array('object_id' => $this->getId());
		$sql = 'UPDATE eqLogic set object_id= NULL where object_id=:object_id';
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		$sql = 'UPDATE scenario set object_id= NULL where object_id=:object_id';
		DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
		$childs = $this->getChild(false);
		if (is_array($childs) && count($childs) > 0) {
			foreach ($childs as $child) {
				try {
					$child->setFather_id($this->getFather_id());
					$child->save();
				} catch (\Exception $e) {
				}
			}
		}
		$files = ls(__DIR__ . '/../../data/object/', 'object' . $this->getId() . '-*');
		if (count($files)  > 0) {
			foreach ($files as $file) {
				unlink(__DIR__ . '/../../data/object/' . $file);
			}
		}
	}

	public function remove() {
		jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'object'));
		return DB::remove($this);
	}

	public function getFather() {
		return self::byId($this->getFather_id());
	}

	public function parentNumber() {
		$father = $this->getFather();
		if (!is_object($father)) {
			return 0;
		}
		$fatherNumber = 0;
		while ($fatherNumber < 50) {
			$fatherNumber++;
			$father = $father->getFather();
			if (!is_object($father)) {
				return $fatherNumber;
			}
		}
		return 0;
	}

	public function getHumanName($_tag = false, $_prettify = false) {
		if ($_tag) {
			if ($_prettify) {
				if ($this->getConfiguration('useCustomColor') == 1) {
					return '<span class="label" style="background-color:' . $this->getDisplay('tagColor') . ' ;color:' . $this->getDisplay('tagTextColor', 'white') . '">' . $this->getDisplay('icon') . ' ' . $this->getName() . '</span>';
				} else {
					return '<span class="label labelObjectHuman">' . $this->getDisplay('icon') . ' ' . $this->getName() . '</span>';
				}
			} else {
				return $this->getDisplay('icon') . ' ' . $this->getName();
			}
		} else {
			return '[' . $this->getName() . ']';
		}
	}

	public function cleanSummary() {
		$def = config::byKey('object:summary');
		$summaries = $this->getConfiguration('summary');
		if (!is_array($summaries) || count($summaries) == 0) {
			return;
		}
		foreach ($summaries as $key => $value) {
			if (!isset($def[$key])) {
				unset($summaries[$key]);
			}
		}
		$this->setConfiguration('summary', $summaries);
		$this->save();
	}

	public function getSummary($_key = '', $_raw = false) {
		$def = config::byKey('object:summary');
		if ($_key == '' || !isset($def[$_key])) {
			return null;
		}
		$summaries = $this->getConfiguration('summary');
		if (!isset($summaries[$_key])) {
			return null;
		}
		$values = array();
		foreach ($summaries[$_key] as &$infos) {
			if (isset($infos['enable']) && $infos['enable'] == 0) {
				continue;
			}
			preg_match_all("/#([0-9]*)#/", $infos['cmd'], $matches);
			if (count($matches[1]) == 0) {
				continue;
			}
			$cmds = cmd::byIds($matches[1]);
			if (count($cmds) == 0) {
				continue;
			}
			foreach ($cmds as $cmd) {
				if ($cmd->getType() != 'info') {
					continue (2);
				}
				if (!is_object($cmd->getEqLogic()) || $cmd->getEqLogic()->getIsEnable() == 0) {
					continue (2);
				}
				$value = $cmd->execCmd();
				if (isset($def[$_key]['ignoreIfCmdOlderThan']) && $def[$_key]['ignoreIfCmdOlderThan'] != '' && $def[$_key]['ignoreIfCmdOlderThan'] > 0) {
					if ((strtotime('now') - strtotime($cmd->getCollectDate())) > ($def[$_key]['ignoreIfCmdOlderThan'] * 60)) {
						continue (2);
					}
				}
				$infos['cmd'] = str_replace('#' . $cmd->getId() . '#', $value, $infos['cmd']);
			}
			$value = evaluate($infos['cmd']);

			$value = trim($value, '"');
			if (isset($infos['invert']) && $infos['invert'] == 1) {
				$value = !$value;
			}
			if (isset($def[$_key]['count']) && $def[$_key]['count'] == 'binary' && $value > 1) {
				$value = 1;
			}
			$values[] = $value;
		}
		if (count($values) == 0) {
			return null;
		}
		if ($_raw) {
			return $values;
		}
		return round(jeedom::calculStat($def[$_key]['calcul'], $values), 1);
	}

	public function getHtmlSummary($_version = 'dashboard') {
		$virtual = eqLogic::byLogicalId('summary' . $this->getId(), 'virtual');
		$return = '<span class="objectSummaryContainer objectSummary' . $this->getId() . '" data-version="' . $_version . '">';
		$def = config::byKey('object:summary');
		foreach ($def as $key => $value) {
			if ($this->getConfiguration('summary::hide::' . $_version . '::' . $key, 0) == 1) {
				continue;
			}
			$result = $this->getSummary($key);
			if ($result !== null) {
				$style = '';
				$allowDisplayZero = $value['allowDisplayZero'];
				if ($value['calcul'] == 'text') {
					$allowDisplayZero = 1;
				}
				if ($allowDisplayZero == 0 && $result == 0) {
					$style = 'display:none;';
				}
				$icon = $value['icon'];
				if (!isset($value['iconnul'])) {
					$value['iconnul'] = $value['icon'];
					$value['hidenumber'] = 0;
					$value['hidenulnumber'] = 0;
				} else {
					if ($result == 0) $icon = $value['iconnul'];
				}
				$classSummaryAction = '';
				if (is_object($virtual)) {
					foreach ($virtual->getCmd('action') as $cmd) {
						if ($cmd->getConfiguration('summary::key') == $key) {
							$classSummaryAction = 'objectSummaryAction';
							break;
						}
					}
				}
				$return .= '<span style="' . $style . '" class="objectSummaryParent ' . $classSummaryAction . ' cursor" data-summary="' . $key . '" data-object_id="' . $this->getId() . '" data-displayZeroValue="' . $allowDisplayZero . '" data-icon="' . urlencode($value['icon']) . '" data-iconnul="' . urlencode($value['iconnul']) . '" data-hidenulnumber="' . $value['hidenulnumber'] . '">';
				if ($value['hidenumber'] == 1 || ($result == 0 && $value['hidenulnumber'] == 1)) {
					$return .= $icon . ' <sup><span style="display: none;" class="objectSummary' . $key . '">' . $result . '</span> ' . $value['unit'] . '</sup></span>';
				} else {
					$return .= $icon . ' <sup><span class="objectSummary' . $key . '">' . $result . '</span> ' . $value['unit'] . '</sup></span>';
				}
			}
		}
		$return = trim($return) . '</span>';
		$this->setCache('summaryHtml' . $_version, $return);
		return $return;
	}

	public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = null) {
		if ($_drill === null) {
			$_drill = config::byKey('graphlink::jeeObject::drill');
		}
		if (isset($_data['node']['object' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = findCodeIcon($this->getDisplay('icon'));
		$_data['node']['object' . $this->getId()] = array(
			'id' => 'object' . $this->getId(),
			'type' => __('Objet', __FILE__),
			'name' => $this->getName(),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'fontsize' => '4em',
			'texty' => -35,
			'textx' => 0,
			'title' => $this->getHumanName(),
			'url' => 'index.php?v=d&p=object&id=' . $this->getId(),
		);
		$use = $this->getUse();
		addGraphLink($this, 'object', $this->getEqLogic(), 'eqLogic', $_data, $_level, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
		addGraphLink($this, 'object', $use['cmd'], 'cmd', $_data, $_level, $_drill);
		addGraphLink($this, 'object', $use['scenario'], 'scenario', $_data, $_level, $_drill);
		addGraphLink($this, 'object', $use['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
		addGraphLink($this, 'object', $use['dataStore'], 'dataStore', $_data, $_level, $_drill);
		addGraphLink($this, 'object', $this->getChild(), 'object', $_data, $_level, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
		addGraphLink($this, 'object', $this->getScenario(false), 'scenario', $_data, $_level, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
		return $_data;
	}

	public function getUse() {
		$json = jeedom::fromHumanReadable(json_encode(utils::o2a($this)));
		return jeedom::getTypeUse($json);
	}

	public function getImgLink() {
		if ($this->getImage('sha512') == '') {
			return '';
		}
		$filename = 'object' . $this->getId() . '-' . $this->getImage('sha512') . '.' . $this->getImage('type');
		return 'data/object/' . $filename;
	}

	public function toArray() {
		$return = utils::o2a($this, true);
		$return['img'] = $this->getImgLink();
		return $return;
	}

	public function hasRight($_right, $_user = null) {
		if ($_user != null) {
			if ($_user->getProfils() == 'admin' || $_user->getProfils() == 'user') {
				return true;
			}
			if (strpos($_user->getRights('jeeObject' . $this->getId()), $_right) !== false) {
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
		if (strpos($_SESSION['user']->getRights('jeeObject' . $this->getId()), $_right) !== false) {
			return true;
		}
		return false;
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getFather_id($_default = null) {
		if ($this->father_id == '' || !is_numeric($this->father_id)) {
			return $_default;
		}
		return $this->father_id;
	}

	public function getIsVisible($_default = null) {
		if ($this->isVisible == '' || !is_numeric($this->isVisible)) {
			return $_default;
		}
		return $this->isVisible;
	}

	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed, $this->id, $_id);
		$this->id = $_id;
		return $this;
	}

	public function setName($_name) {
		$_name = substr(cleanComponanteName($_name), 0, 127);
		$this->_changed = utils::attrChanged($this->_changed, $this->name, $_name);
		$this->name = $_name;
		return $this;
	}

	public function setFather_id($_father_id = null) {
		$_father_id = ($_father_id == '') ? null : $_father_id;
		$this->_changed = utils::attrChanged($this->_changed, $this->father_id, $_father_id);
		$this->father_id = $_father_id;
		return $this;
	}

	public function setIsVisible($_isVisible) {
		$this->_changed = utils::attrChanged($this->_changed, $this->isVisible, $_isVisible);
		$this->isVisible = $_isVisible;
		return $this;
	}

	public function getPosition($_default = null) {
		if ($this->position == '' || !is_numeric($this->position)) {
			return $_default;
		}
		return $this->position;
	}

	public function setPosition($_position) {
		$this->_changed = utils::attrChanged($this->_changed, $this->position, $_position);
		$this->position = $_position;
		return $this;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		$configuration =  utils::setJsonAttr($this->configuration, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->configuration, $configuration);
		$this->configuration = $configuration;
		return $this;
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function setDisplay($_key, $_value) {
		$display = utils::setJsonAttr($this->display, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->display, $display);
		$this->display = $display;
		return $this;
	}

	public function getCache($_key = '', $_default = '') {
		$cache = cache::byKey('objectCacheAttr' . $this->getId())->getValue();
		return utils::getJsonAttr($cache, $_key, $_default);
	}

	public function setCache($_key, $_value = null) {
		cache::set('objectCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('objectCacheAttr' . $this->getId())->getValue(), $_key, $_value));
	}

	public function getImage($_key = '', $_default = '') {
		return utils::getJsonAttr($this->image, $_key, $_default);
	}

	public function setImage($_key, $_value) {
		$image = utils::setJsonAttr($this->image, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed, $this->image, $image);
		$this->image = $image;
		return $this;
	}

	public function getChanged() {
		return $this->_changed;
	}

	public function setChanged($_changed) {
		$this->_changed = $_changed;
		return $this;
	}
}
