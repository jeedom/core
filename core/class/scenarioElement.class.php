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

class scenarioElement {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $name;
	private $type;
	private $options;
	private $order = 0;
	private $_changed = false;
	private $_subelement;
	
	/*     * ***********************Méthodes statiques*************************** */
	
	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM ' . __CLASS__ . '
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function saveAjaxElement($element_ajax) {
		if (isset($element_ajax['id']) && $element_ajax['id'] != '') {
			$element_db = scenarioElement::byId($element_ajax['id']);
		} else {
			$element_db = new scenarioElement();
		}
		if (!isset($element_db) || !is_object($element_db)) {
			throw new Exception(__('Elément inconnu. Vérifiez l\'ID : ', __FILE__) . $element_ajax['id']);
		}
		utils::a2o($element_db, $element_ajax);
		$element_db->save();
		$subElement_order = 0;
		$subElement_list = $element_db->getSubElement();
		$enable_subElement = array();
		foreach ($element_ajax['subElements'] as $subElement_ajax) {
			if (isset($subElement_ajax['id']) && $subElement_ajax['id'] != '') {
				$subElement_db = scenarioSubElement::byId($subElement_ajax['id']);
			} else {
				$subElement_db = new scenarioSubElement();
			}
			if (!isset($subElement_db) || !is_object($subElement_db)) {
				throw new Exception(__('Sous-élément inconnu. Vérifiez l\'ID : ', __FILE__) . $subElement_ajax['id']);
			}
			utils::a2o($subElement_db, $subElement_ajax);
			$subElement_db->setScenarioElement_id($element_db->getId());
			$subElement_db->setOrder($subElement_order);
			$subElement_db->save();
			$subElement_order++;
			$enable_subElement[$subElement_db->getId()] = true;
			
			$expression_list = $subElement_db->getExpression();
			$expression_order = 0;
			$enable_expression = array();
			foreach ($subElement_ajax['expressions'] as &$expression_ajax) {
				if (isset($expression_ajax['scenarioSubElement_id']) && $expression_ajax['scenarioSubElement_id'] != $subElement_db->getId() && isset($expression_ajax['id']) && $expression_ajax['id'] != '') {
					$expression_ajax['id'] = '';
				}
				if (isset($expression_ajax['id']) && $expression_ajax['id'] != '') {
					$expression_db = scenarioExpression::byId($expression_ajax['id']);
				} else {
					$expression_db = new scenarioExpression();
				}
				if (!isset($expression_db) || !is_object($expression_db)) {
					throw new Exception(__('Expression inconnue. Vérifiez l\'ID : ', __FILE__) . $expression_ajax['id']);
				}
				$expression_db->emptyOptions();
				utils::a2o($expression_db, $expression_ajax);
				$expression_db->setScenarioSubElement_id($subElement_db->getId());
				if ($expression_db->getType() == 'element') {
					$expression_db->setExpression(self::saveAjaxElement($expression_ajax['element']));
				}
				$expression_db->setOrder($expression_order);
				$expression_db->save();
				$expression_order++;
				$enable_expression[$expression_db->getId()] = true;
			}
			foreach ($expression_list as $expresssion) {
				if (!isset($enable_expression[$expresssion->getId()])) {
					$expresssion->remove();
				}
			}
		}
		foreach ($subElement_list as $subElement) {
			if (!isset($enable_subElement[$subElement->getId()])) {
				$subElement->remove();
			}
		}
		return $element_db->getId();
	}
	
	/*     * *********************Méthodes d'instance************************* */
	
	public function save() {
		DB::save($this);
		return true;
	}
	
	public function remove() {
		foreach ($this->getSubElement() as $subelement) {
			$subelement->remove();
		}
		DB::remove($this);
	}
	
	public function execute(&$_scenario = null) {
		if ($_scenario != null && !$_scenario->getDo()) {
			return;
		}
		if (!is_object($this->getSubElement($this->getType()))) {
			return;
		}
		if ($this->getType() == 'if') {
			if ($this->getSubElement('if')->getOptions('enable', 1) == 0) {
				return true;
			}
			$result = $this->getSubElement('if')->execute($_scenario);
			if (is_string($result) && strlen($result) > 1) {
				$_scenario->setLog(__('Expression non valide : ', __FILE__) . $result);
				$expresssion_str = '';
				if ($this->getSubElement('if')->getSubtype() == 'condition' && is_array($this->getSubElement('if')->getExpression())) {
					foreach ($this->getSubElement('if')->getExpression() as $expression) {
						$expresssion_str = $expression->getExpression();
					}
				}
				message::add('scenario', __('Expression non valide  [', __FILE__) . $expresssion_str . __('] trouvée dans le scénario : ', __FILE__) . $_scenario->getHumanName().__(', résultat : ',__FILE__).$result, '', 'invalidExprScenarioElement::' . $this->getId());
				return;
			}
			if ($result) {
				if ($this->getSubElement('if')->getOptions('allowRepeatCondition', 0) == 1) {
					if ($this->getSubElement('if')->getOptions('previousState', -1) != 1) {
						$this->getSubElement('if')->setOptions('previousState', 1);
						$this->getSubElement('if')->save();
					} else {
						$_scenario->setLog(__('Non exécution des actions pour cause de répétition', __FILE__));
						return;
					}
				}
				return $this->getSubElement('then')->execute($_scenario);
			}
			if (!is_object($this->getSubElement('else'))) {
				return;
			}
			if ($this->getSubElement('if')->getOptions('allowRepeatCondition', 0) == 1) {
				if ($this->getSubElement('if')->getOptions('previousState', -1) != 0) {
					$this->getSubElement('if')->setOptions('previousState', 0);
					$this->getSubElement('if')->save();
				} else {
					$_scenario->setLog(__('Non exécution des actions pour cause de répétition', __FILE__));
					return;
				}
			}
			return $this->getSubElement('else')->execute($_scenario);
			
		} else if ($this->getType() == 'action') {
			if ($this->getSubElement('action')->getOptions('enable', 1) == 0) {
				return true;
			}
			return $this->getSubElement('action')->execute($_scenario);
		} else if ($this->getType() == 'code') {
			if ($this->getSubElement('code')->getOptions('enable', 1) == 0) {
				return true;
			}
			return $this->getSubElement('code')->execute($_scenario);
		} else if ($this->getType() == 'for') {
			if ($this->getSubElement('for')->getOptions('enable', 1) == 0) {
				return true;
			}
			$limits = intval($this->getSubElement('for')->execute($_scenario));
			if (!is_numeric($limits)) {
				throw new Exception(__('La condition pour une boucle doit être numérique : ', __FILE__) . $limits);
			}
			$return = false;
			for ($i = 1; $i <= $limits; $i++) {
				$return = $this->getSubElement('do')->execute($_scenario);
			}
			return $return;
		} else if ($this->getType() == 'in') {
			if ($this->getSubElement('in')->getOptions('enable', 1) == 0) {
				return true;
			}
			$time = ceil(str_replace('.', ',', $this->getSubElement('in')->execute($_scenario)));
			if (!is_numeric($time) || $time < 0) {
				$time = 0;
			}
			if ($time == 0) {
				$cmd = __DIR__ . '/../../core/php/jeeScenario.php ';
				$cmd .= ' scenario_id=' . $_scenario->getId();
				$cmd .= ' scenarioElement_id=' . $this->getId();
				$cmd .= ' >> ' . log::getPathToLog('scenario_element_execution') . ' 2>&1 &';
				$_scenario->setLog(__('Tâche : ', __FILE__) . $this->getId() . __(' lancement immédiat ', __FILE__));
				system::php($cmd);
			} else {
				$crons = cron::searchClassAndFunction('scenario', 'doIn', '"scenarioElement_id":' . $this->getId() . ',');
				if (is_array($crons)) {
					foreach ($crons as $cron) {
						if ($cron->getState() != 'run') {
							$cron->remove();
						}
					}
				}
				$cron = new cron();
				$cron->setClass('scenario');
				$cron->setFunction('doIn');
				$cron->setOption(array('scenario_id' => intval($_scenario->getId()), 'scenarioElement_id' => intval($this->getId()), 'second' => date('s'), 'tags' => $_scenario->getTags()));
				$cron->setLastRun(date('Y-m-d H:i:s'));
				$cron->setOnce(1);
				$next = strtotime('+ ' . $time . ' min');
				$cron->setSchedule(cron::convertDateToCron($next));
				$cron->save();
				$_scenario->setLog(__('Tâche : ', __FILE__) . $this->getId() . __(' programmée à : ', __FILE__) . date('Y-m-d H:i:s', $next) . ' (+ ' . $time . ' min)');
			}
			return true;
		} else if ($this->getType() == 'at') {
			if ($this->getSubElement('at')->getOptions('enable', 1) == 0) {
				return true;
			}
			$next = $this->getSubElement('at')->execute($_scenario);
			if (!is_numeric($next) || $next < 0) {
				throw new Exception(__('Bloc type A : ', __FILE__) . $this->getId() . __(', heure programmée invalide : ', __FILE__) . $next);
			}
			if ($next <= date('Gi')) {
				$next = str_repeat('0', 4 - strlen($next)) . $next;
				$next = date('Y-m-d', strtotime('+1 day' . date('Y-m-d'))) . ' ' . substr($next, 0, 2) . ':' . substr($next, 2, 4);
			} else {
				$next = str_repeat('0', 4 - strlen($next)) . $next;
				$next = date('Y-m-d') . ' ' . substr($next, 0, 2) . ':' . substr($next, 2, 4);
			}
			$next = strtotime($next);
			if ($next < strtotime('now')) {
				throw new Exception(__('Bloc type A : ', __FILE__) . $this->getId() . __(', heure programmée invalide : ', __FILE__) . date('Y-m-d H:i:00', $next));
			}
			$crons = cron::searchClassAndFunction('scenario', 'doIn', '"scenarioElement_id":' . $this->getId() . ',');
			if (is_array($crons)) {
				foreach ($crons as $cron) {
					if ($cron->getState() != 'run') {
						$cron->remove();
					}
				}
			}
			$cron = new cron();
			$cron->setClass('scenario');
			$cron->setFunction('doIn');
			$cron->setOption(array('scenario_id' => intval($_scenario->getId()), 'scenarioElement_id' => intval($this->getId()), 'second' => 0, 'tags' => $_scenario->getTags()));
			$cron->setLastRun(date('Y-m-d H:i:s', strtotime('now')));
			$cron->setOnce(1);
			$cron->setSchedule(cron::convertDateToCron($next));
			$cron->save();
			$_scenario->setLog(__('Tâche : ', __FILE__) . $this->getId() . __(' programmée à : ', __FILE__) . date('Y-m-d H:i:00', $next));
			return true;
		}
	}
	
	public function getSubElement($_type = '') {
		if ($_type != '') {
			if (isset($this->_subelement[$_type]) && is_object($this->_subelement[$_type])) {
				return $this->_subelement[$_type];
			}
			$this->_subelement[$_type] = scenarioSubElement::byScenarioElementId($this->getId(), $_type);
			return $this->_subelement[$_type];
		} else {
			if (isset($this->_subelement[-1]) && is_array($this->_subelement[-1]) && count($this->_subelement[-1]) > 0) {
				return $this->_subelement[-1];
			}
			$this->_subelement[-1] = scenarioSubElement::byScenarioElementId($this->getId(), $_type);
			return $this->_subelement[-1];
		}
	}
	
	public function getAjaxElement($_mode = 'ajax') {
		$return = utils::o2a($this);
		if ($_mode == 'array') {
			if (isset($return['id'])) {
				unset($return['id']);
			}
			if (isset($return['scenarioElement_id'])) {
				unset($return['scenarioElement_id']);
			}
			if (isset($return['log'])) {
				unset($return['log']);
			}
			if (isset($return['_expression'])) {
				unset($return['_expression']);
			}
		}
		$return['subElements'] = array();
		foreach ($this->getSubElement() as $subElement) {
			$subElement_ajax = utils::o2a($subElement);
			if ($_mode == 'array') {
				if (isset($subElement_ajax['id'])) {
					unset($subElement_ajax['id']);
				}
				if (isset($subElement_ajax['scenarioElement_id'])) {
					unset($subElement_ajax['scenarioElement_id']);
				}
				if (isset($subElement_ajax['log'])) {
					unset($subElement_ajax['log']);
				}
				if (isset($subElement_ajax['_expression'])) {
					unset($subElement_ajax['_expression']);
				}
			}
			$subElement_ajax['expressions'] = array();
			foreach ($subElement->getExpression() as $expression) {
				$expression_ajax = utils::o2a($expression);
				if ($_mode == 'array') {
					if (isset($expression_ajax['id'])) {
						unset($expression_ajax['id']);
					}
					if (isset($expression_ajax['scenarioSubElement_id'])) {
						unset($expression_ajax['scenarioSubElement_id']);
					}
					if (isset($expression_ajax['log'])) {
						unset($expression_ajax['log']);
					}
					if (isset($expression_ajax['_expression'])) {
						unset($expression_ajax['_expression']);
					}
				}
				if ($expression->getType() == 'element') {
					$element = self::byId($expression->getExpression());
					if (is_object($element)) {
						$expression_ajax['element'] = $element->getAjaxElement($_mode);
						if ($_mode == 'array') {
							if (isset($expression_ajax['element']['id'])) {
								unset($expression_ajax['element']['id']);
							}
							if (isset($expression_ajax['element']['scenarioElement_id'])) {
								unset($expression_ajax['element']['scenarioElement_id']);
							}
							if (isset($expression_ajax['element']['log'])) {
								unset($expression_ajax['element']['log']);
							}
							if (isset($expression_ajax['element']['_expression'])) {
								unset($expression_ajax['element']['_expression']);
							}
						}
					}
				}
				$expression_ajax = jeedom::toHumanReadable($expression_ajax);
				$subElement_ajax['expressions'][] = $expression_ajax;
			}
			$return['subElements'][] = $subElement_ajax;
		}
		return $return;
	}
	
	public function getAllId() {
		$return = array(
			'element' => array($this->getId()),
			'subelement' => array(),
			'expression' => array(),
		);
		foreach ($this->getSubElement() as $subelement) {
			$result = $subelement->getAllId();
			$return['element'] = array_merge($return['element'], $result['element']);
			$return['subelement'] = array_merge($return['subelement'], $result['subelement']);
			$return['expression'] = array_merge($return['expression'], $result['expression']);
		}
		return $return;
	}
	
	public function resetRepeatIfStatus() {
		foreach ($this->getSubElement() as $subElement) {
			if ($subElement->getType() == 'if') {
				$subElement->setOptions('previousState', -1);
				$subElement->save();
			}
			foreach ($subElement->getExpression() as $expression) {
				$expression->resetRepeatIfStatus();
			}
		}
	}
	
	public function export() {
		$return = '';
		foreach ($this->getSubElement() as $subElement) {
			$return .= "\n";
			switch ($subElement->getType()) {
				case 'if':
					$return .= __('SI', __FILE__);
					break;
					case 'then':
					$return .= __('ALORS', __FILE__);
					break;
					case 'else':
						$return .= __('SINON', __FILE__);
						break;
						case 'for':
							$return .= __('POUR', __FILE__);
							break;
							case 'do':
							$return .= __('FAIRE', __FILE__);
							break;
							case 'code':
							$return .= __('CODE', __FILE__);
							break;
							case 'action':
							$return .= __('ACTION', __FILE__);
							break;
							case 'in':
							$return .= __('DANS', __FILE__);
							break;
							case 'at':
							$return .= __('A', __FILE__);
							break;
							default:
							$return .= $subElement->getType();
							break;
						}
						
						foreach ($subElement->getExpression() as $expression) {
							$export = $expression->export();
							if ($expression->getType() != 'condition' && trim($export) != '') {
								$return .= "\n";
							}
							if (trim($export) != '') {
								$return .= ' ' . $expression->export();
							}
						}
					}
					return $return;
				}
				
				public function copy() {
					$elementCopy = clone $this;
					$elementCopy->setId('');
					$elementCopy->save();
					foreach ($this->getSubElement() as $subelement) {
						$subelement->copy($elementCopy->getId());
					}
					return $elementCopy->getId();
				}
				
				public function getScenario() {
					$scenario = scenario::byElement($this->getId());
					if (is_object($scenario)) {
						return $scenario;
					}
					$expression = scenarioExpression::byElement($this->getId());
					if (is_object($expression)) {
						return $expression->getSubElement()->getElement()->getScenario();
					}
					return null;
				}
				
				/*     * **********************Getteur Setteur*************************** */
				
				public function getId() {
					return $this->id;
				}
				
				public function setId($_id) {
					$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
					$this->id = $_id;
					return $this;
				}
				
				public function getName() {
					return $this->name;
				}
				
				public function setName($_name) {
					$this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
					$this->name = $_name;
					return $this;
				}
				
				public function getType() {
					return $this->type;
				}
				
				public function setType($_type) {
					$this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
					$this->type = $_type;
					return $this;
				}
				
				public function getOptions($_key = '', $_default = '') {
					return utils::getJsonAttr($this->options, $_key, $_default);
				}
				
				public function setOptions($_key, $_value) {
					$options =  utils::setJsonAttr($this->options, $_key, $_value);
					$this->_changed = utils::attrChanged($this->_changed,$this->options,$options);
					$this->options =$options;
					return $this;
				}
				
				public function getOrder() {
					return $this->order;
				}
				
				public function setOrder($_order) {
					$this->_changed = utils::attrChanged($this->_changed,$this->order,$_order);
					$this->order = $_order;
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
			