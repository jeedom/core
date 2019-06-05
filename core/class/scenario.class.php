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

class scenario {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $name;
	private $isActive = 1;
	private $group = '';
	private $mode;
	private $schedule;
	private $scenarioElement;
	private $trigger;
	private $_log;
	private $timeout = 0;
	private $object_id = null;
	private $isVisible = 0;
	private $display;
	private $order = 9999;
	private $description;
	private $configuration;
	private $type = 'expert';
	private static $_templateArray;
	private $_elements = array();
	private $_changeState = false;
	private $_realTrigger = '';
	private $_return = true;
	private $_tags = array();
	private $_do = true;
	private $_changed = false;
	
	/*     * ***********************Méthodes statiques*************************** */
	
	/**
	* Renvoie un objet scenario
	* @param int  $_id id du scenario voulu
	* @return scenario object scenario
	*/
	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byString($_string) {
		$scenario = self::byId(str_replace('#scenario', '', self::fromHumanReadable($_string)));
		if (!is_object($scenario)) {
			throw new Exception(__('La commande n\'a pas pu être trouvée : ', __FILE__) . $_string . __(' => ', __FILE__) . self::fromHumanReadable($_string));
		}
		return $scenario;
	}
	
	/**
	* Renvoie tous les objets scenario
	* @return [] scenario object scenario
	*/
	public static function all($_group = '', $_type = null) {
		$values = array();
		if ($_group === '') {
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id';
			if ($_type !== null) {
				$values['type'] = $_type;
				$sql .= ' WHERE `type`=:type';
			}
			$sql .= ' ORDER BY ob.name,s.group, s.name';
			$result1 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			if (!is_array($result1)) {
				$result1 = array();
			}
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE s.object_id IS NULL';
			if ($_type !== null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY s.group, s.name';
			$result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			return array_merge($result1, $result2);
		} elseif ($_group === null) {
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE (`group` IS NULL OR `group` = "")';
			if ($_type !== null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY s.group, s.name';
			$result1 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			if (!is_array($result1)) {
				$result1 = array();
			}
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE (`group` IS NULL OR `group` = "")
			AND s.object_id IS NULL';
			if ($_type !== null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY  s.name';
			$result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			return array_merge($result1, $result2);
		} else {
			$values = array(
				'group' => $_group,
			);
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			INNER JOIN object ob ON s.object_id=ob.id
			WHERE `group`=:group';
			if ($_type !== null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY ob.name,s.group, s.name';
			$result1 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
			FROM scenario s
			WHERE `group`=:group
			AND s.object_id IS NULL';
			if ($_type !== null) {
				$values['type'] = $_type;
				$sql .= ' AND `type`=:type';
			}
			$sql .= ' ORDER BY s.group, s.name';
			$result2 = DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
			return array_merge($result1, $result2);
		}
	}
	/**
	*
	* @return type
	*/
	public static function schedule() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE `mode` != "provoke"
		AND `mode` != ""
		AND `schedule` != ""
		AND isActive=1';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	/**
	*
	* @param type $_group
	* @return type
	*/
	public static function listGroup($_group = null) {
		$values = array();
		$sql = 'SELECT DISTINCT(`group`)
		FROM scenario';
		if ($_group !== null) {
			$values['group'] = '%' . $_group . '%';
			$sql .= ' WHERE `group` LIKE :group';
		}
		$sql .= ' ORDER BY `group`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL);
	}
	/**
	*
	* @param type $_cmd_id
	* @return type
	*/
	public static function byTrigger($_cmd_id, $_onlyEnable = true) {
		$values = array(
			'cmd_id' => '%#' . $_cmd_id . '#%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE mode != "schedule"';
		if ($_onlyEnable) {
			$sql .= ' AND isActive=1';
		}
		$sql .= ' AND `trigger` LIKE :cmd_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	/**
	*
	* @param type $_element_id
	* @return type
	*/
	public static function byElement($_element_id) {
		$values = array(
			'element_id' => '%"' . $_element_id . '"%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario
		WHERE `scenarioElement` LIKE :element_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	/**
	*
	* @param type $_object_id
	* @param type $_onlyEnable
	* @param type $_onlyVisible
	* @return type
	*/
	public static function byObjectId($_object_id, $_onlyEnable = true, $_onlyVisible = false) {
		$values = array();
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM scenario';
		if ($_object_id === null) {
			$sql .= ' WHERE object_id IS NULL';
		} else {
			$values['object_id'] = $_object_id;
			$sql .= ' WHERE object_id=:object_id';
		}
		if ($_onlyEnable) {
			$sql .= ' AND isActive = 1';
		}
		if ($_onlyVisible) {
			$sql .= ' AND isVisible = 1';
		}
		$sql .= ' ORDER BY `order`';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	/**
	*
	* @param type $_event
	* @param type $_forceSyncMode
	* @return boolean
	*/
	public static function check($_event = null, $_forceSyncMode = false) {
		$message = '';
		if ($_event !== null) {
			$scenarios = array();
			if (is_object($_event)) {
				$scenarios1 = self::byTrigger($_event->getId());
				$trigger = '#' . $_event->getId() . '#';
				$message = __('Scénario exécuté automatiquement sur événement venant de : ', __FILE__) . $_event->getHumanName();
			} else {
				$scenarios1 = self::byTrigger($_event);
				$trigger = $_event;
				$message = __('Scénario exécuté sur événement : #', __FILE__) . $_event . '#';
			}
			if (is_array($scenarios1) && count($scenarios1) > 0) {
				foreach ($scenarios1 as $scenario) {
					if ($scenario->testTrigger($trigger)) {
						$scenarios[] = $scenario;
					}
				}
			}
		} else {
			$message = __('Scénario exécuté automatiquement sur programmation', __FILE__);
			$scenarios = scenario::schedule();
			$trigger = 'schedule';
			if (jeedom::isDateOk()) {
				foreach ($scenarios as $key => &$scenario) {
					if ($scenario->getState() != 'in progress' || $scenario->getConfiguration('allowMultiInstance',0) == 1) {
						if (!$scenario->isDue()) {
							unset($scenarios[$key]);
						}
					} else {
						unset($scenarios[$key]);
					}
				}
			}
		}
		if (count($scenarios) > 0) {
			foreach ($scenarios as $scenario_) {
				$scenario_->launch($trigger, $message, $_forceSyncMode);
			}
		}
		return true;
	}
	
	public static function control() {
		foreach (scenario::all() as $scenario) {
			if ($scenario->getState() != 'in progress') {
				continue;
			}
			if (!$scenario->running()) {
				$scenario->setState('error');
				continue;
			}
			$runtime = strtotime('now') - strtotime($scenario->getLastLaunch());
			if (is_numeric($scenario->getTimeout()) && $scenario->getTimeout() != '' && $scenario->getTimeout() != 0 && $runtime > $scenario->getTimeout()) {
				$scenario->stop();
				$scenario->setLog(__('Arret du scénario car il a dépassé son temps de timeout : ', __FILE__) . $scenario->getTimeout() . 's');
				$scenario->persistLog();
			}
		}
	}
	
	/**
	*
	* @param array $_options
	* @return type
	*/
	public static function doIn($_options) {
		$scenario = self::byId($_options['scenario_id']);
		if (!is_object($scenario)) {
			return;
		}
		if ($scenario->getIsActive() == 0) {
			$scenario->setLog(__('Scénario désactivé non lancement de la sous tâche', __FILE__));
			$scenario->persistLog();
			return;
		}
		$scenarioElement = scenarioElement::byId($_options['scenarioElement_id']);
		$scenario->setLog(__('************Lancement sous tâche**************', __FILE__));
		if (isset($_options['tags']) && is_array($_options['tags']) && count($_options['tags']) > 0) {
			$scenario->setTags($_options['tags']);
			$scenario->setLog(__('Tags : ', __FILE__) . json_encode($scenario->getTags()));
		}
		if (!is_object($scenarioElement) || !is_object($scenario)) {
			return;
		}
		if (is_numeric($_options['second']) && $_options['second'] > intval(date('s'))) {
			sleep($_options['second'] - intval(date('s')));
		}
		$scenarioElement->getSubElement('do')->execute($scenario);
		$scenario->setLog(__('************FIN sous tâche**************', __FILE__));
		$scenario->persistLog();
	}
	/**
	*
	*/
	public static function cleanTable() {
		$ids = array(
			'element' => array(),
			'subelement' => array(),
			'expression' => array(),
		);
		foreach (scenario::all() as $scenario) {
			foreach ($scenario->getElement() as $element) {
				$result = $element->getAllId();
				$ids['element'] = array_merge($ids['element'], $result['element']);
				$ids['subelement'] = array_merge($ids['subelement'], $result['subelement']);
				$ids['expression'] = array_merge($ids['expression'], $result['expression']);
			}
		}
		
		$sql = 'DELETE FROM scenarioExpression WHERE id NOT IN (-1';
			foreach ($ids['expression'] as $expression_id) {
			$sql .= ',' . $expression_id;
			}
			$sql .= ')';
			DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
			
			$sql = 'DELETE FROM scenarioSubElement WHERE id NOT IN (-1';
				foreach ($ids['subelement'] as $subelement_id) {
				$sql .= ',' . $subelement_id;
				}
				$sql .= ')';
				DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
				
				$sql = 'DELETE FROM scenarioElement WHERE id NOT IN (-1';
					foreach ($ids['element'] as $element_id) {
					$sql .= ',' . $element_id;
					}
					$sql .= ')';
					DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
				}
				
				public static function consystencyCheck($_needsReturn = false) {
					$return = array();
					foreach (self::all() as $scenario) {
						if ($scenario->getGroup() == '') {
							$group = 'aucun';
						} else {
							$group = $scenario->getGroup();
						}
						if ($scenario->getIsActive() != 1) {
							if (!$_needsReturn) {
								continue;
							}
						}
						if ($scenario->getMode() == 'provoke' || $scenario->getMode() == 'all') {
							$trigger_list = '';
							foreach ($scenario->getTrigger() as $trigger) {
								$trigger_list .= cmd::cmdToHumanReadable($trigger) . '_';
							}
							preg_match_all("/#([0-9]*)#/", $trigger_list, $matches);foreach ($matches[1] as $cmd_id) {
								if (is_numeric($cmd_id)) {
									if ($_needsReturn) {
										$return[] = array('detail' => 'Scénario ' . $scenario->getName() . ' du groupe ' . $group, 'help' => 'Déclencheur du scénario', 'who' => '#' . $cmd_id . '#');
									} else {
										log::add('scenario', 'error', __('Un déclencheur du scénario : ', __FILE__) . $scenario->getHumanName() . __(' est introuvable', __FILE__));
									}
								}
							}
						}
						$expression_list = '';
						foreach ($scenario->getElement() as $element) {
							$expression_list .= cmd::cmdToHumanReadable(json_encode($element->getAjaxElement()));
						}
						preg_match_all("/#([0-9]*)#/", $expression_list, $matches);
						foreach ($matches[1] as $cmd_id) {
							if (is_numeric($cmd_id)) {
								if ($_needsReturn) {
									$return[] = array('detail' => 'Scénario ' . $scenario->getHumanName(), 'help' => 'Utilisé dans le scénario', 'who' => '#' . $cmd_id . '#');
								} else {
									log::add('scenario', 'error', __('Une commande du scénario : ', __FILE__) . $scenario->getHumanName() . __(' est introuvable', __FILE__));
								}
							}
						}
					}
					if ($_needsReturn) {
						return $return;
					}
				}
				
				/**
				* @name byObjectNameGroupNameScenarioName()
				* @param object $_object_name
				* @param type $_group_name
				* @param type $_scenario_name
				* @return type
				*/
				public static function byObjectNameGroupNameScenarioName($_object_name, $_group_name, $_scenario_name) {
					$values = array(
						'scenario_name' => html_entity_decode($_scenario_name),
					);
					
					if ($_object_name == __('Aucun', __FILE__)) {
						if ($_group_name == __('Aucun', __FILE__)) {
							$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
							FROM scenario s
							WHERE s.name=:scenario_name
							AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")
							AND s.object_id IS NULL';
						} else {
							$values['group_name'] = $_group_name;
							$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
							FROM scenario s
							WHERE s.name=:scenario_name
							AND s.object_id IS NULL
							AND `group`=:group_name';
						}
					} else {
						$values['object_name'] = $_object_name;
						if ($_group_name == __('Aucun', __FILE__)) {
							$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
							FROM scenario s
							INNER JOIN object ob ON s.object_id=ob.id
							WHERE s.name=:scenario_name
							AND ob.name=:object_name
							AND (`group` IS NULL OR `group`=""  OR `group`="Aucun" OR `group`="None")';
						} else {
							$values['group_name'] = $_group_name;
							$sql = 'SELECT ' . DB::buildField(__CLASS__, 's') . '
							FROM scenario s
							INNER JOIN object ob ON s.object_id=ob.id
							WHERE s.name=:scenario_name
							AND ob.name=:object_name
							AND `group`=:group_name';
						}
					}
					return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
				}
				
				/**
				* @name toHumanReadable()
				* @param object $_input
				* @return string
				*/
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
					preg_match_all("/#scenario([0-9]*)#/", $text, $matches);
					foreach ($matches[1] as $scenario_id) {
						if (is_numeric($scenario_id)) {
							$scenario = self::byId($scenario_id);
							if (is_object($scenario)) {
								$text = str_replace('#scenario' . $scenario_id . '#', '#' . $scenario->getHumanName(true) . '#', $text);
							}
						}
					}
					return $text;
				}
				/**
				*
				* @param type $_input
				* @return type
				*/
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
					
					preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $text, $matches);
					if (count($matches) == 4) {
						$countMatches = count($matches[0]);
						for ($i = 0; $i < $countMatches; $i++) {
							if (isset($matches[1][$i]) && isset($matches[2][$i]) && isset($matches[3][$i])) {
								$scenario = self::byObjectNameGroupNameScenarioName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
								if (is_object($scenario)) {
									$text = str_replace($matches[0][$i], '#scenario' . $scenario->getId() . '#', $text);
								}
							}
						}
					}
					
					return $text;
				}
				/**
				*
				* @param type $searchs
				* @return type
				*/
				public static function searchByUse($searchs) {
					$return = array();
					$expressions = array();
					$scenarios = array();
					foreach ($searchs as $search) {
						$_cmd_id = str_replace('#', '', $search['action']);
						$return = array_merge($return, self::byTrigger($_cmd_id, false));
						if (!isset($search['and'])) {
							$search['and'] = false;
						}
						if (!isset($search['option'])) {
							$search['option'] = $search['action'];
						}
						$expressions = array_merge($expressions, scenarioExpression::searchExpression($search['action'], $search['option'], $search['and']));
					}
					if (is_array($expressions) && count($expressions) > 0) {
						foreach ($expressions as $expression) {
							$scenarios[] = $expression->getSubElement()->getElement()->getScenario();
						}
					}
					if (is_array($scenarios) && count($scenarios) > 0) {
						foreach ($scenarios as $scenario) {
							if (is_object($scenario)) {
								$find = false;
								foreach ($return as $existScenario) {
									if ($scenario->getId() == $existScenario->getId()) {
										$find = true;
										break;
									}
								}
								if (!$find) {
									$return[] = $scenario;
								}
							}
						}
					}
					return $return;
				}
				/**
				*
				* @param type $_template
				* @return type
				*/
				public static function getTemplate($_template = '') {
					$path = __DIR__ . '/../config/scenario';
					if (isset($_template) && $_template != '') {
						
					}
					return ls($path, '*.json', false, array('files', 'quiet'));
				}
				
				/*     * *************************MARKET**************************************** */
				
				public static function shareOnMarket(&$market) {
					$moduleFile = __DIR__ . '/../config/scenario/' . $market->getLogicalId() . '.json';
					if (!file_exists($moduleFile)) {
						throw new Exception('Impossible de trouver le fichier de configuration ' . $moduleFile);
					}
					$tmp = jeedom::getTmpFolder('market') . '/' . $market->getLogicalId() . '.zip';
					if (file_exists($tmp)) {
						if (!unlink($tmp)) {
							throw new Exception(__('Impossible de supprimer : ', __FILE__) . $tmp . __('. Vérifiez les droits', __FILE__));
						}
					}
					if (!create_zip($moduleFile, $tmp)) {
						throw new Exception(__('Echec de création du zip. Répertoire source : ', __FILE__) . $moduleFile . __(' / Répertoire cible : ', __FILE__) . $tmp);
					}
					return $tmp;
				}
				/**
				*
				* @param type $market
				* @param type $_path
				* @throws Exception
				*/
				public static function getFromMarket(&$market, $_path) {
					$cibDir = __DIR__ . '/../config/scenario/';
					if (!file_exists($cibDir)) {
						mkdir($cibDir);
					}
					$zip = new ZipArchive;
					if ($zip->open($_path) === true) {
						$zip->extractTo($cibDir . '/');
						$zip->close();
					} else {
						throw new Exception('Impossible de décompresser l\'archive zip : ' . $_path);
					}
				}
				
				public static function removeFromMarket(&$market) {
					trigger_error('This method is deprecated', E_USER_DEPRECATED);
				}
				
				public static function listMarketObject() {
					return array();
				}
				
				public static function timelineDisplay($_event) {
					$return = array();
					$return['date'] = $_event['datetime'];
					$return['group'] = 'scenario';
					$return['type'] = $_event['type'];
					$scenario = scenario::byId($_event['id']);
					if (!is_object($scenario)) {
						return null;
					}
					$object = $scenario->getObject();
					$return['object'] = is_object($object) ? $object->getId() : 'aucun';
					$return['html'] = '<div class="scenario" data-id="' . $_event['id'] . '">'
					. '<div style="background-color:#e7e7e7;padding:1px;font-size:0.9em;font-weight: bold;cursor:help;">' . $_event['name'] . ' <i class="fa fa-file-text-o pull-right cursor bt_scenarioLog"></i> <i class="fa fa-share pull-right cursor bt_gotoScenario"></i></div>'
					. '<div style="background-color:white;padding:1px;font-size:0.8em;cursor:default;">Déclenché par ' . $_event['trigger'] . '<div/>'
					. '</div>';
					return $return;
				}
				
				/*     * *********************Méthodes d'instance************************* */
				/**
				*
				* @param type $_event
				* @return boolean
				*/
				public function testTrigger($_event) {
					foreach ($this->getTrigger() as $trigger) {
						$trigger = str_replace(array('#variable(', ')#'), array('variable(', ')'), $trigger);
						if ($trigger == $_event) {
							return true;
						} elseif (strpos($trigger, $_event) !== false && jeedom::evaluateExpression($trigger)) {
							return true;
						}
					}
					return false;
				}
				/**
				*
				* @param type $_trigger
				* @param type $_message
				* @param type $_forceSyncMode
				* @return boolean
				*/
				public function launch($_trigger = '', $_message = '', $_forceSyncMode = false) {
					if (config::byKey('enableScenario') != 1 || $this->getIsActive() != 1) {
						return false;
					}
					$state = $this->getState();
					if($state == 'starting'){
						if($this->getConfiguration('allowMultiInstance',0) == 0){
							return false;
						}
						if(($this->getCache('startingTime')+2)>strtotime('now')){
							$i = 0;
							while($state == 'starting'){
								sleep(1);
								$state = $this->getState();
								$i++;
								if($i>5){
									break;
								}
							}
						}
					}
					if($state == 'in progress'){
						if($this->getConfiguration('allowMultiInstance',0) == 0){
							return false;
						}
					}
					$this->setCache(array('startingTime' =>strtotime('now'),'state' => 'starting'));
					if ($this->getConfiguration('syncmode') == 1 || $_forceSyncMode) {
						$this->setLog(__('Lancement du scénario en mode synchrone', __FILE__));
						return $this->execute($_trigger, $_message);
					} else {
						if (count($this->getTags()) != '') {
							$this->setCache('tags', $this->getTags());
						}
						$cmd = __DIR__ . '/../../core/php/jeeScenario.php ';
						$cmd .= ' scenario_id=' . $this->getId();
						$cmd .= ' trigger=' . escapeshellarg($_trigger);
						$cmd .= ' "message=' . escapeshellarg(sanitizeAccent($_message)) . '"';
						$cmd .= ' >> ' . log::getPathToLog('scenario_execution') . ' 2>&1 &';
						system::php($cmd);
					}
					return true;
				}
				/**
				*
				* @param type $_trigger
				* @param type $_message
				* @return type
				*/
				public function execute($_trigger = '', $_message = '') {
					$tags = $this->getCache('tags');
					if ($tags != '') {
						$this->setTags($tags);
						$this->setCache('tags', '');
					}
					if ($this->getIsActive() != 1) {
						$this->setLog(__('Impossible d\'exécuter le scénario : ', __FILE__) . $this->getHumanName() . __(' sur : ', __FILE__) . $_message . __(' car il est désactivé', __FILE__));
						$this->setState('stop');
						$this->setPID();
						$this->persistLog();
						return;
					}
					if ($this->getConfiguration('timeDependency', 0) == 1 && !jeedom::isDateOk()) {
						$this->setLog(__('Lancement du scénario : ', __FILE__) . $this->getHumanName() . __(' annulé car il utilise une condition de type temporelle et que la date système n\'est pas OK', __FILE__));
						$this->setState('stop');
						$this->setPID();
						$this->persistLog();
						return;
					}
					
					$cmd = cmd::byId(str_replace('#', '', $_trigger));
					if (is_object($cmd)) {
						log::add('event', 'info', __('Exécution du scénario ', __FILE__) . $this->getHumanName() . __(' déclenché par : ', __FILE__) . $cmd->getHumanName());
						if ($this->getConfiguration('timeline::enable')) {
							jeedom::addTimelineEvent(array('type' => 'scenario', 'id' => $this->getId(), 'name' => $this->getHumanName(true), 'datetime' => date('Y-m-d H:i:s'), 'trigger' => $cmd->getHumanName(true)));
						}
					} else {
						log::add('event', 'info', __('Exécution du scénario ', __FILE__) . $this->getHumanName() . __(' déclenché par : ', __FILE__) . $_trigger);
						if ($this->getConfiguration('timeline::enable')) {
							jeedom::addTimelineEvent(array('type' => 'scenario', 'id' => $this->getId(), 'name' => $this->getHumanName(true), 'datetime' => date('Y-m-d H:i:s'), 'trigger' => ($_trigger == 'schedule') ? 'programmation' : $_trigger));
						}
					}
					if ($this->getState() == 'in progress' && $this->getConfiguration('allowMultiInstance', 0) == 0) {
						return;
					}
					if (count($this->getTags()) == 0) {
						$this->setLog('Start : ' . trim($_message, "'") . '.');
					} else {
						$this->setLog('Start : ' . trim($_message, "'") . '. Tags : ' . json_encode($this->getTags()));
					}
					$this->setLastLaunch(date('Y-m-d H:i:s'));
					$this->setState('in progress');
					$this->setPID(getmypid());
					$this->setRealTrigger($_trigger);
					foreach ($this->getElement() as $element) {
						if (!$this->getDo()) {
							break;
						}
						$element->execute($this);
					}
					$this->setState('stop');
					$this->setPID();
					$this->setLog(__('Fin correcte du scénario', __FILE__));
					$this->persistLog();
					return $this->getReturn();
				}
				/**
				*
				* @param type $_name
				* @return \scenario
				*/
				public function copy($_name) {
					$scenarioCopy = clone $this;
					$scenarioCopy->setName($_name);
					$scenarioCopy->setId('');
					$scenario_element_list = array();
					foreach ($this->getElement() as $element) {
						$scenario_element_list[] = $element->copy();
					}
					$scenarioCopy->setScenarioElement($scenario_element_list);
					$scenarioCopy->setLog('');
					$scenarioCopy->save();
					if (file_exists(__DIR__ . '/../../log/scenarioLog/scenario' . $scenarioCopy->getId() . '.log')) {
						unlink(__DIR__ . '/../../log/scenarioLog/scenario' . $scenarioCopy->getId() . '.log');
					}
					return $scenarioCopy;
				}
				/**
				*
				* @param type $_version
				* @return string
				*/
				public function toHtml($_version) {
					if (!$this->hasRight('r')) {
						return '';
					}
					$mc = cache::byKey('scenarioHtml' . $_version . $this->getId());
					if ($mc->getValue() != '') {
						return $mc->getValue();
					}
					$version = jeedom::versionAlias($_version);
					$replace = array(
						'#id#' => $this->getId(),
						'#state#' => $this->getState(),
						'#isActive#' => $this->getIsActive(),
						'#name#' => ($this->getDisplay('name') != '') ? $this->getDisplay('name') : $this->getHumanName(),
						'#shortname#' => ($this->getDisplay('name') != '') ? $this->getDisplay('name') : $this->getName(),
						'#treename#' => $this->getHumanName(false, false, false, false, true),
						'#icon#' => $this->getIcon(),
						'#lastLaunch#' => $this->getLastLaunch(),
						'#lastLaunch#' => $this->getLastLaunch(),
						'#scenarioLink#' => $this->getLinkToConfiguration(),
						'#version#' => $_version,
						'#height#' => $this->getDisplay('height', 'auto'),
						'#width#' => $this->getDisplay('width', 'auto'),
					);
					if (!isset(self::$_templateArray)) {
						self::$_templateArray = array();
					}
					if (!isset(self::$_templateArray[$version])) {
						self::$_templateArray[$version] = getTemplate('core', $version, 'scenario');
					}
					$html = template_replace($replace, self::$_templateArray[$version]);
					cache::set('scenarioHtml' . $version . $this->getId(), $html);
					return $html;
				}
				/**
				*
				*/
				public function emptyCacheWidget() {
					$mc = cache::byKey('scenarioHtmldashboard' . $this->getId());
					$mc->remove();
					$mc = cache::byKey('scenarioHtmlmobile' . $this->getId());
					$mc->remove();
					$mc = cache::byKey('scenarioHtmlmview' . $this->getId());
					$mc->remove();
					$mc = cache::byKey('scenarioHtmldview' . $this->getId());
					$mc->remove();
				}
				/**
				*
				* @param type $_only_class
				* @return string
				*/
				public function getIcon($_only_class = false) {
					if ($_only_class) {
						switch ($this->getState()) {
							case 'starting':
							return 'fas fa-hourglass-start';
							case 'in progress':
							return 'fa fa-spinner fa-spin';
							case 'error':
							return 'fa fa-exclamation-triangle';
							default:
							if (strpos($this->getDisplay('icon'), '<i') === 0) {
								return str_replace(array('<i', 'class=', '"', '/>'), '', $this->getDisplay('icon'));
							}
							return 'fa fa-check';
						}
						return 'fa fa-times';
					}
					switch ($this->getState()) {
						case 'starting':
						return '	<i class="fas fa-hourglass-start"></i>';
						case 'in progress':
						return '<i class="fa fa-spinner fa-spin"></i>';
						case 'error':
						return '<i class="fa fa-exclamation-triangle"></i>';
						default:
						if ($this->getDisplay('icon') != '') {
							return $this->getDisplay('icon');
						}
						return '<i class="fa fa-check"></i>';
					}
					return '<i class="fa fa-times"></i>';
				}
				/**
				*
				* @return type
				*/
				public function getLinkToConfiguration() {
					return 'index.php?v=d&p=scenario&id=' . $this->getId();
				}
				/**
				*
				* @throws Exception
				*/
				public function preSave() {
					if ($this->getTimeout() == '' || !is_numeric($this->getTimeout())) {
						$this->setTimeout(0);
					}
					if ($this->getName() == '') {
						throw new Exception('Le nom du scénario ne peut pas être vide.');
					}
					if (($this->getMode() == 'schedule' || $this->getMode() == 'all') && $this->getSchedule() == '') {
						throw new Exception(__('Le scénario est de type programmé mais la programmation est vide', __FILE__));
					}
					if ($this->getConfiguration('has_return', 0) == 1) {
						$this->setConfiguration('syncmode', 1);
					}
					if ($this->getConfiguration('logmode') == '') {
						$this->setConfiguration('logmode', 'default');
					}
				}
				/**
				*
				*/
				public function postInsert() {
					$this->setState('stop');
					$this->setPID();
				}
				/**
				*
				*/
				public function save() {
					if ($this->getLastLaunch() == '' && ($this->getMode() == 'schedule' || $this->getMode() == 'all')) {
						$calculateScheduleDate = $this->calculateScheduleDate();
						$this->setLastLaunch($calculateScheduleDate['prevDate']);
					}
					DB::save($this);
					$this->emptyCacheWidget();
					if ($this->_changeState) {
						$this->_changeState = false;
						event::add('scenario::update', array('scenario_id' => $this->getId(), 'isActive' => $this->getIsActive(), 'state' => $this->getState(), 'lastLaunch' => $this->getLastLaunch(), 'icon' => $this->getIcon()));
					}
					return true;
				}
				/**
				*
				*/
				public function refresh() {
					DB::refresh($this);
				}
				/**
				*
				* @return type
				*/
				public function remove() {
					viewData::removeByTypeLinkId('scenario', $this->getId());
					dataStore::removeByTypeLinkId('scenario', $this->getId());
					foreach ($this->getElement() as $element) {
						$element->remove();
					}
					$this->emptyCacheWidget();
					if (file_exists(__DIR__ . '/../../log/scenarioLog/scenario' . $this->getId() . '.log')) {
						unlink(__DIR__ . '/../../log/scenarioLog/scenario' . $this->getId() . '.log');
					}
					cache::delete('scenarioCacheAttr' . $this->getId());
					jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getHumanName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'scenario'));
					return DB::remove($this);
				}
				/**
				*
				* @param type $_key
				* @param type $_private
				* @return boolean
				*/
				public function removeData($_key, $_private = false) {
					if ($_private) {
						$dataStore = dataStore::byTypeLinkIdKey('scenario', $this->getId(), $_key);
					} else {
						$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $_key);
					}
					if (is_object($dataStore)) {
						return $dataStore->remove();
					}
					return true;
				}
				/**
				*
				* @param type $_key
				* @param type $_value
				* @param bool $_private
				* @return boolean
				*/
				public function setData($_key, $_value, $_private = false) {
					$dataStore = new dataStore();
					$dataStore->setType('scenario');
					$dataStore->setKey($_key);
					$dataStore->setValue($_value);
					if ($_private) {
						$dataStore->setLink_id($this->getId());
					} else {
						$dataStore->setLink_id(-1);
					}
					$dataStore->save();
					return true;
				}
				
				public function getData($_key, $_private = false, $_default = '') {
					if ($_private) {
						$dataStore = dataStore::byTypeLinkIdKey('scenario', $this->getId(), $_key);
					} else {
						$dataStore = dataStore::byTypeLinkIdKey('scenario', -1, $_key);
					}
					if (is_object($dataStore)) {
						return $dataStore->getValue($_default);
					}
					return $_default;
				}
				/**
				*
				* @return type
				*/
				public function calculateScheduleDate() {
					$calculatedDate = array('prevDate' => '', 'nextDate' => '');
					if (is_array($this->getSchedule())) {
						$calculatedDate_tmp = array('prevDate' => '', 'nextDate' => '');
						foreach ($this->getSchedule() as $schedule) {
							try {
								$c = new Cron\CronExpression(checkAndFixCron($schedule), new Cron\FieldFactory);
								$calculatedDate_tmp['prevDate'] = $c->getPreviousRunDate()->format('Y-m-d H:i:s');
								$calculatedDate_tmp['nextDate'] = $c->getNextRunDate()->format('Y-m-d H:i:s');
							} catch (Exception $exc) {
								
							} catch (Error $exc) {
								
							}
							if ($calculatedDate['prevDate'] == '' || strtotime($calculatedDate['prevDate']) < strtotime($calculatedDate_tmp['prevDate'])) {
								$calculatedDate['prevDate'] = $calculatedDate_tmp['prevDate'];
							}
							if ($calculatedDate['nextDate'] == '' || strtotime($calculatedDate['nextDate']) > strtotime($calculatedDate_tmp['nextDate'])) {
								$calculatedDate['nextDate'] = $calculatedDate_tmp['nextDate'];
							}
						}
					} else {
						try {
							$c = new Cron\CronExpression(checkAndFixCron($this->getSchedule()), new Cron\FieldFactory);
							$calculatedDate['prevDate'] = $c->getPreviousRunDate()->format('Y-m-d H:i:s');
							$calculatedDate['nextDate'] = $c->getNextRunDate()->format('Y-m-d H:i:s');
						} catch (Exception $exc) {
							
						} catch (Error $exc) {
							
						}
					}
					return $calculatedDate;
				}
				/**
				*
				* @return boolean
				*/
				public function isDue() {
					$last = strtotime($this->getLastLaunch());
					$now = time();
					$now = ($now - $now % 60);
					$last = ($last - $last % 60);
					if ($now == $last) {
						return false;
					}
					if (is_array($this->getSchedule())) {
						foreach ($this->getSchedule() as $schedule) {
							try {
								$c = new Cron\CronExpression(checkAndFixCron($schedule), new Cron\FieldFactory);
								try {
									if ($c->isDue()) {
										return true;
									}
								} catch (Exception $e) {
									
								} catch (Error $e) {
									
								}
								try {
									$prev = $c->getPreviousRunDate()->getTimestamp();
								} catch (Exception $e) {
									continue;
								} catch (Error $e) {
									continue;
								}
								$lastCheck = strtotime($this->getLastLaunch());
								$diff = abs((strtotime('now') - $prev) / 60);
								if ($lastCheck <= $prev && $diff <= config::byKey('maxCatchAllow') || config::byKey('maxCatchAllow') == -1) {
									return true;
								}
							} catch (Exception $e) {
								
							} catch (Error $e) {
								
							}
						}
					} else {
						try {
							$c = new Cron\CronExpression(checkAndFixCron($this->getSchedule()), new Cron\FieldFactory);
							try {
								if ($c->isDue()) {
									return true;
								}
							} catch (Exception $e) {
								
							} catch (Error $e) {
								
							}
							try {
								$prev = $c->getPreviousRunDate()->getTimestamp();
							} catch (Exception $e) {
								return false;
							} catch (Error $e) {
								return false;
							}
							$lastCheck = strtotime($this->getLastLaunch());
							$diff = abs((strtotime('now') - $prev) / 60);
							if ($lastCheck <= $prev && $diff <= config::byKey('maxCatchAllow') || config::byKey('maxCatchAllow') == -1) {
								return true;
							}
						} catch (Exception $exc) {
							
						} catch (Error $exc) {
							
						}
					}
					return false;
				}
				/**
				*
				* @return boolean
				*/
				public function running() {
					if (intval($this->getPID()) > 0 && posix_getsid(intval($this->getPID())) && (!file_exists('/proc/' . $this->getPID() . '/cmdline') || strpos(file_get_contents('/proc/' . $this->getPID() . '/cmdline'), 'scenario_id=' . $this->getId()) !== false)) {
						return true;
					}
					if (count(system::ps('scenario_id=' . $this->getId() . ' ', array(getmypid()))) > 0) {
						return true;
					}
					return false;
				}
				/**
				*
				* @return boolean
				* @throws Exception
				*/
				public function stop() {
					$crons = cron::searchClassAndFunction('scenario', 'doIn', '"scenario_id":' . $this->getId());
					if (is_array($crons)) {
						foreach ($crons as $cron) {
							if ($cron->getState() == 'run') {
								try {
									$cron->halt();
									$cron->remove();
								} catch (Exception $e) {
									log::add('scenario', 'info', __('Can not stop subtask : ') . print_r($cron->getOption(), true));
								}
							}
						}
					}
					if ($this->running()) {
						if ($this->getPID() > 0) {
							system::kill($this->getPID());
							$retry = 0;
							while ($this->running() && $retry < 10) {
								sleep(1);
								system::kill($this->getPID());
								$retry++;
							}
						}
						
						if ($this->running()) {
							system::kill("scenario_id=" . $this->getId() . ' ');
							sleep(1);
							if ($this->running()) {
								system::kill("scenario_id=" . $this->getId() . ' ');
								sleep(1);
							}
						}
						if ($this->running()) {
							throw new Exception(__('Impossible d\'arrêter le scénario : ', __FILE__) . $this->getHumanName() . __('. PID : ', __FILE__) . $this->getPID());
						}
					}
					$this->setState('stop');
					return true;
				}
				/**
				*
				* @return type
				*/
				public function getElement() {
					if (count($this->_elements) > 0) {
						return $this->_elements;
					}
					$return = array();
					$elements = $this->getScenarioElement();
					if (is_array($elements)) {
						foreach ($this->getScenarioElement() as $element_id) {
							$element = scenarioElement::byId($element_id);
							if (is_object($element)) {
								$return[] = $element;
							}
						}
						$this->_elements = $return;
						return $return;
					}
					if ($elements != '') {
						$element = scenarioElement::byId($element_id);
						if (is_object($element)) {
							$return[] = $element;
							$this->_elements = $return;
							return $return;
						}
					}
					return array();
				}
				/**
				*
				* @param type $_mode
				* @return type
				*/
				public function export($_mode = 'text') {
					if ($_mode == 'text') {
						$return = '';
						$return .= '- Nom du scénario : ' . $this->getName() . "\n";
						if (is_numeric($this->getObject_id())) {
							$return .= '- Objet parent : ' . $this->getObject()->getName() . "\n";
						}
						$return .= '- Mode du scénario : ' . $this->getMode() . "\n";
						$schedules = $this->getSchedule();
						if ($this->getMode() == 'schedule' || $this->getMode() == 'all') {
							if (is_array($schedules)) {
								foreach ($schedules as $schedule) {
									$return .= '    - Programmation : ' . $schedule . "\n";
								}
							} else {
								if ($schedules != '') {
									$return .= '    - Programmation : ' . $schedules . "\n";
								}
							}
						}
						if ($this->getMode() == 'provoke' || $this->getMode() == 'all') {
							foreach ($this->getTrigger() as $trigger) {
								$return .= '    - Evènement : ' . jeedom::toHumanReadable($trigger) . "\n";
							}
						}
						$return .= "\n";
						$return .= $this->getDescription();
						$return .= "\n\n";
						foreach ($this->getElement() as $element) {
							$exports = explode("\n", $element->export());
							foreach ($exports as $export) {
								$return .= "    " . $export . "\n";
							}
						}
					}
					if ($_mode == 'array') {
						$return = utils::o2a($this);
						$return['trigger'] = jeedom::toHumanReadable($return['trigger']);
						$return['elements'] = array();
						foreach ($this->getElement() as $element) {
							$return['elements'][] = $element->getAjaxElement('array');
						}
						if (isset($return['id'])) {
							unset($return['id']);
						}
						if (isset($return['lastLaunch'])) {
							unset($return['lastLaunch']);
						}
						if (isset($return['log'])) {
							unset($return['log']);
						}
						if (isset($return['hlogs'])) {
							unset($return['hlogs']);
						}
						if (isset($return['object_id'])) {
							unset($return['object_id']);
						}
						if (isset($return['pid'])) {
							unset($return['pid']);
						}
						if (isset($return['scenarioElement'])) {
							unset($return['scenarioElement']);
						}
						if (isset($return['_templateArray'])) {
							unset($return['_templateArray']);
						}
						if (isset($return['_templateArray'])) {
							unset($return['_templateArray']);
						}
						if (isset($return['_changeState'])) {
							unset($return['_changeState']);
						}
						if (isset($return['_realTrigger'])) {
							unset($return['_realTrigger']);
						}
						if (isset($return['_templateArray'])) {
							unset($return['_templateArray']);
						}
						if (isset($return['_elements'])) {
							unset($return['_elements']);
						}
					}
					return $return;
				}
				/**
				*
				* @return object
				*/
				public function getObject() {
					return jeeObject::byId($this->object_id);
				}
				/**
				*
				* @param type $_complete
				* @param type $_noGroup
				* @param type $_tag
				* @param type $_prettify
				* @param type $_withoutScenarioName
				* @return string
				*/
				public function getHumanName($_complete = false, $_noGroup = false, $_tag = false, $_prettify = false, $_withoutScenarioName = false,$_object_name = true) {
					$name = '';
					if ($_object_name && is_numeric($this->getObject_id()) && is_object($this->getObject())) {
						$object = $this->getObject();
						if ($_tag) {
							if ($object->getDisplay('tagColor') != '') {
								$name .= '<span class="label" style="text-shadow : none;background-color:' . $object->getDisplay('tagColor') . ' !important;color:' . $object->getDisplay('tagTextColor', 'white') . ' !important">' . $object->getName() . '</span>';
							} else {
								$name .= '<span class="label label-primary" style="text-shadow : none;">' . $object->getName() . '</span>';
							}
						} else {
							$name .= '[' . $object->getName() . ']';
						}
					} else {
						if ($_complete) {
							if ($_tag) {
								$name .= '<span class="label label-default" style="text-shadow : none;">' . __('Aucun', __FILE__) . '</span>';
							} else {
								$name .= '[' . __('Aucun', __FILE__) . ']';
							}
						}
					}
					if (!$_noGroup) {
						if ($this->getGroup() != '') {
							$name .= '[' . $this->getGroup() . ']';
						} else {
							if ($_complete) {
								$name .= '[' . __('Aucun', __FILE__) . ']';
							}
						}
					}
					if ($_prettify) {
						$name .= '<br/><strong>';
					}
					if (!$_withoutScenarioName) {
						if ($_tag) {
							$name .= ' ' . $this->getName();
						} else {
							$name .= '[' . $this->getName() . ']';
						}
					}
					if ($_prettify) {
						$name .= '</strong>';
					}
					return $name;
				}
				/**
				*
				* @param type $_right
				* @return boolean
				*/
				public function hasRight($_right, $_user = null) {
					if ($_user != null) {
						if ($_user->getProfils() == 'admin' || $_user->getProfils() == 'user') {
							return true;
						}
						if (strpos($_user->getRights('scenario' . $this->getId()), $_right) !== false) {
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
					if (strpos($_SESSION['user']->getRights('scenario' . $this->getId()), $_right) !== false) {
						return true;
					}
					return false;
				}
				/**
				*
				* @param type $_partial
				* @return type
				*/
				public function persistLog($_partial = false) {
					if ($this->getConfiguration('logmode', 'default') == 'none') {
						return;
					}
					$path = __DIR__ . '/../../log/scenarioLog';
					if (!file_exists($path)) {
						mkdir($path);
					}
					$path .= '/scenario' . $this->getId() . '.log';
					if ($_partial) {
						file_put_contents($path, $this->getLog(), FILE_APPEND);
					} else {
						file_put_contents($path, "------------------------------------\n" . $this->getLog(), FILE_APPEND);
					}
				}
				/**
				*
				* @return type
				*/
				public function toArray() {
					$return = utils::o2a($this, true);
					$cache = $this->getCache(array('state', 'lastLaunch'));
					$return['state'] = $cache['state'];
					$return['lastLaunch'] = $cache['lastLaunch'];
					return $return;
				}
				/**
				*
				* @param type $_data
				* @param type $_level
				* @param type $_drill
				* @return string
				*/
				public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = null) {
					if ($_drill === null) {
						$_drill = config::byKey('graphlink::scenario::drill');
					}
					if (isset($_data['node']['scenario' . $this->getId()])) {
						return;
					}
					if ($_level > 1) {
						return $_data;
					}
					$_level++;
					if ($_level > $_drill) {
						return $_data;
					}
					$_data['node']['scenario' . $this->getId()] = array(
						'id' => 'scenario' . $this->getId(),
						'name' => $this->getName(),
						'fontweight' => ($_level == 1) ? 'bold' : 'normal',
						'shape' => 'rect',
						'width' => 40,
						'height' => 40,
						'color' => 'green',
						'image' => 'core/img/scenario.png',
						'isActive' => $this->getIsActive(),
						'title' => $this->getHumanName(),
						'url' => 'index.php?v=d&p=scenario&id=' . $this->getId(),
					);
					$use = $this->getUse();
					$usedBy = $this->getUsedBy();
					addGraphLink($this, 'scenario', $this->getObject(), 'object', $_data, $_level + 1, $_drill, array('dashvalue' => '1,0', 'lengthfactor' => 0.6));
					addGraphLink($this, 'scenario', $use['cmd'], 'cmd', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $use['scenario'], 'scenario', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $use['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $use['dataStore'], 'dataStore', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $use['view'], 'view', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $use['plan'], 'plan', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $usedBy['cmd'], 'cmd', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $usedBy['scenario'], 'scenario', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $usedBy['eqLogic'], 'eqLogic', $_data, $_level, $_drill);
					addGraphLink($this, 'scenario', $usedBy['interactDef'], 'interactDef', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
					addGraphLink($this, 'scenario', $usedBy['plan'], 'plan', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
					addGraphLink($this, 'scenario', $usedBy['view'], 'view', $_data, $_level, $_drill, array('dashvalue' => '2,6', 'lengthfactor' => 0.6));
					return $_data;
				}
				/**
				*
				* @return type
				*/
				public function getUse() {
					$json = jeedom::fromHumanReadable(json_encode($this->export('array')));
					return jeedom::getTypeUse($json);
				}
				/**
				*
				* @param array $_array
				* @return type
				*/
				public function getUsedBy($_array = false) {
					$return = array('cmd' => array(), 'eqLogic' => array(), 'scenario' => array(), 'plan' => array(), 'view' => array());
					$return['cmd'] = cmd::searchConfiguration('#scenario' . $this->getId() . '#');
					$return['eqLogic'] = eqLogic::searchConfiguration(array('#scenario' . $this->getId() . '#', '"scenario_id":"' . $this->getId()));
					$return['interactDef'] = interactDef::searchByUse(array('#scenario' . $this->getId() . '#', '"scenario_id":"' . $this->getId()));
					$return['scenario'] = scenario::searchByUse(array(
						array('action' => 'scenario', 'option' => $this->getId(), 'and' => true),
						array('action' => '#scenario' . $this->getId() . '#'),
					));
					$return['view'] = view::searchByUse('scenario', $this->getId());
					$return['plan'] = planHeader::searchByUse('scenario', $this->getId());
					if ($_array) {
						foreach ($return as &$value) {
							$value = utils::o2a($value);
						}
					}
					return $return;
				}
				
				public function clearLog() {
					$this->_log = '';
				}
				
				public function resetRepeatIfStatus() {
					foreach ($this->getElement() as $element) {
						$element->resetRepeatIfStatus();
					}
				}
				
				/*     * **********************Getteur Setteur*************************** */
				/**
				*
				* @return int
				*/
				public function getId() {
					return $this->id;
				}
				/**
				*
				* @return string
				*/
				public function getName() {
					return $this->name;
				}
				/**
				*
				* @return type
				*/
				public function getState() {
					return $this->getCache('state');
				}
				/**
				*
				* @return bool
				*/
				public function getIsActive() {
					return $this->isActive;
				}
				/**
				*
				* @return type
				*/
				public function getGroup() {
					return $this->group;
				}
				/**
				*
				* @return type
				*/
				public function getLastLaunch() {
					return $this->getCache('lastLaunch');
				}
				/**
				*
				* @param int $id
				* @return $this
				*/
				public function setId($_id) {
					$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
					$this->id = $_id;
					return $this;
				}
				/**
				*
				* @param type $name
				* @return $this
				*/
				public function setName($_name) {
					$_name = cleanComponanteName($_name);
					if ($_name != $this->getName()) {
						$this->_changeState = true;
						$this->_changed = true;
					}
					$this->name = $_name;
					return $this;
				}
				/**
				*
				* @param type $isActive
				* @return $this
				*/
				public function setIsActive($_isActive) {
					if ($_isActive != $this->getIsActive()) {
						$this->_changeState = true;
						$this->_changed = true;
					}
					$this->isActive = $_isActive;
					return $this;
				}
				/**
				*
				* @param type $group
				* @return $this
				*/
				public function setGroup($_group) {
					if ($_group != $this->getGroup()) {
						$this->_changeState = true;
						$this->_changed = true;
					}
					$this->group = $_group;
					return $this;
				}
				/**
				*
				* @param type $state
				*/
				public function setState($state) {
					if ($this->getCache('state') != $state) {
						$this->emptyCacheWidget();
						event::add('scenario::update', array('scenario_id' => $this->getId(), 'state' => $state, 'lastLaunch' => $this->getLastLaunch()));
					}
					$this->setCache('state', $state);
				}
				/**
				*
				* @param type $lastLaunch
				*/
				public function setLastLaunch($lastLaunch) {
					$this->setCache('lastLaunch', $lastLaunch);
				}
				/**
				*
				* @return type
				*/
				public function getType() {
					return $this->type;
				}
				/**
				*
				* @param type $type
				* @return $this
				*/
				public function setType($_type) {
					$this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
					$this->type = $_type;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getMode() {
					return $this->mode;
				}
				/**
				*
				* @param type $mode
				* @return $this
				*/
				public function setMode($_mode) {
					$this->_changed = utils::attrChanged($this->_changed,$this->mode,$_mode);
					$this->mode = $_mode;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getOrder() {
					return $this->order;
				}
				/**
				*
				* @param type $mode
				* @return $this
				*/
				public function setOrder($_order) {
					$this->_changed = utils::attrChanged($this->_changed,$this->order,$_order);
					$this->order = $_order;
					return $this;
				}
				/**
				*
				* @return string/object
				*/
				public function getSchedule() {
					return is_json($this->schedule, $this->schedule);
				}
				/**
				*
				* @param type $schedule
				* @return $this
				*/
				public function setSchedule($_schedule) {
					if (is_array($_schedule)) {
						$_schedule = json_encode($_schedule, JSON_UNESCAPED_UNICODE);
					}
					$this->_changed = utils::attrChanged($this->_changed,$this->schedule,$_schedule);
					$this->schedule = $_schedule;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getPID() {
					return $this->getCache('pid');
				}
				/**
				*
				* @param type $pid
				*/
				public function setPID($pid = '') {
					$this->setCache('pid', $pid);
				}
				/**
				*
				* @return type
				*/
				public function getScenarioElement() {
					return is_json($this->scenarioElement, $this->scenarioElement);
				}
				/**
				*
				* @param type $scenarioElement
				* @return $this
				*/
				public function setScenarioElement($_scenarioElement) {
					if (is_array($_scenarioElement)) {
						$_scenarioElement = json_encode($_scenarioElement, JSON_UNESCAPED_UNICODE);
					}
					$this->_changed = utils::attrChanged($this->_changed,$this->scenarioElement,$_scenarioElement);
					$this->scenarioElement = $_scenarioElement;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getTrigger() {
					return is_json($this->trigger, array($this->trigger));
				}
				/**
				*
				* @param type $trigger
				* @return $this
				*/
				public function setTrigger($_trigger) {
					if (is_array($_trigger)) {
						$_trigger = json_encode($_trigger, JSON_UNESCAPED_UNICODE);
					}
					$_trigger = cmd::humanReadableToCmd($_trigger);
					$this->_changed = utils::attrChanged($this->_changed,$this->trigger,$_trigger);
					$this->trigger = $_trigger;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getLog() {
					return $this->_log;
				}
				/**
				*
				* @param type $log
				*/
				public function setLog($log) {
					$this->_log .= '[' . date('Y-m-d H:i:s') . '][SCENARIO] ' . $log . "\n";
					if ($this->getConfiguration('logmode', 'default') == 'realtime') {
						$this->persistLog(true);
						$this->_log = '';
					}
				}
				/**
				*
				* @param type $_default
				* @return type
				*/
				public function getTimeout($_default = 0) {
					return $this->timeout;
				}
				/**
				*
				* @param string $timeout
				* @return $this
				*/
				public function setTimeout($_timeout) {
					if ($_timeout === '' || is_nan(intval($_timeout)) || $_timeout < 1) {
						$_timeout = 0;
					}
					$this->_changed = utils::attrChanged($this->_changed,$this->timeout,$_timeout);
					$this->timeout = $_timeout;
					return $this;
				}
				/**
				*
				* @param type $_default
				* @return type
				*/
				public function getObject_id($_default = null) {
					if ($this->object_id == '' || !is_numeric($this->object_id)) {
						return $_default;
					}
					return $this->object_id;
				}
				/**
				*
				* @param type $_default
				* @return type
				*/
				public function getIsVisible($_default = 0) {
					if ($this->isVisible == '' || !is_numeric($this->isVisible)) {
						return $_default;
					}
					return $this->isVisible;
				}
				/**
				*
				* @param type $object_id
				* @return $this
				*/
				public function setObject_id($object_id = null) {
					if ($object_id != $this->getObject_id()) {
						$this->_changeState = true;
						$this->_changed = true;
					}
					$this->object_id = (!is_numeric($object_id)) ? null : $object_id;
					return $this;
				}
				/**
				*
				* @param type $isVisible
				* @return $this
				*/
				public function setIsVisible($_isVisible) {
					$this->_changed = utils::attrChanged($this->_changed,$this->isVisible,$_isVisible);
					$this->isVisible = $_isVisible;
					return $this;
				}
				/**
				*
				* @param type $_key
				* @param type $_default
				* @return type
				*/
				public function getDisplay($_key = '', $_default = '') {
					return utils::getJsonAttr($this->display, $_key, $_default);
				}
				/**
				*
				* @param type $_key
				* @param type $_value
				* @return $this
				*/
				public function setDisplay($_key, $_value) {
					$display = utils::setJsonAttr($this->display, $_key, $_value);
					$this->_changed = utils::attrChanged($this->_changed,$this->display,$display);
					$this->display = $display;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getDescription() {
					return $this->description;
				}
				/**
				*
				* @param type $description
				* @return $this
				*/
				public function setDescription($_description) {
					$this->_changed = utils::attrChanged($this->_changed,$this->description,$_description);
					$this->description = $_description;
					return $this;
				}
				/**
				*
				* @param type $_key
				* @param type $_default
				* @return type
				*/
				public function getConfiguration($_key = '', $_default = '') {
					return utils::getJsonAttr($this->configuration, $_key, $_default);
				}
				/**
				*
				* @param type $_key
				* @param type $_value
				* @return $this
				*/
				public function setConfiguration($_key, $_value) {
					$configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
					$this->_changed = utils::attrChanged($this->_changed,$this->configuration,$configuration);
					$this->configuration = $configuration;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getRealTrigger() {
					return $this->_realTrigger;
				}
				/**
				*
				* @param type $_realTrigger
				* @return $this
				*/
				public function setRealTrigger($_realTrigger) {
					$this->_realTrigger = $_realTrigger;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getReturn() {
					return $this->_return;
				}
				/**
				*
				* @param type $_return
				* @return $this
				*/
				public function setReturn($_return) {
					$this->_return = $_return;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getTags() {
					return $this->_tags;
				}
				/**
				*
				* @param type $_tags
				* @return $this
				*/
				public function setTags($_tags) {
					$this->_tags = $_tags;
					return $this;
				}
				/**
				*
				* @return type
				*/
				public function getDo() {
					return $this->_do;
				}
				/**
				*
				* @param type $_do
				* @return $this
				*/
				public function setDo($_do) {
					$this->_do = $_do;
					return $this;
				}
				/**
				*
				* @param type $_key
				* @param type $_default
				* @return type
				*/
				public function getCache($_key = '', $_default = '') {
					$cache = cache::byKey('scenarioCacheAttr' . $this->getId())->getValue();
					return utils::getJsonAttr($cache, $_key, $_default);
				}
				/**
				*
				* @param type $_key
				* @param type $_value
				*/
				public function setCache($_key, $_value = null) {
					cache::set('scenarioCacheAttr' . $this->getId(), utils::setJsonAttr(cache::byKey('scenarioCacheAttr' . $this->getId())->getValue(), $_key, $_value));
				}
				
				public function getChanged() {
					return $this->_changed;
				}
				
				public function setChanged($_changed) {
					$this->_changed = $_changed;
					return $this;
				}
				
			}
			
