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

class plan3d {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $name;
	private $plan3dHeader_id;
	private $link_type;
	private $link_id;
	private $position;
	private $display;
	private $configuration;
	private $_changed = false;
	
	/*     * ***********************Methode static*************************** */
	
	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byPlan3dHeaderId($_plan3dHeader_id) {
		$values = array(
			'plan3dHeader_id' => $_plan3dHeader_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d
		WHERE plan3dHeader_id=:plan3dHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byLinkTypeLinkId($_link_type, $_link_id) {
		$values = array(
			'link_type' => $_link_type,
			'link_id' => $_link_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d
		WHERE link_type=:link_type
		AND link_id=:link_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byName3dHeaderId($_name, $_plan3dHeader_id) {
		$values = array(
			'name' => $_name,
			'plan3dHeader_id' => $_plan3dHeader_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d
		WHERE name=:name
		AND plan3dHeader_id=:plan3dHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byLinkTypeLinkId3dHeaderId($_link_type, $_link_id, $_plan3dHeader_id) {
		$values = array(
			'link_type' => $_link_type,
			'link_id' => $_link_id,
			'plan3dHeader_id' => $_plan3dHeader_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d
		WHERE link_type=:link_type
		AND link_id=:link_id
		AND plan3dHeader_id=:plan3dHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function removeByLinkTypeLinkId3dHeaderId($_link_type, $_link_id, $_plan3dHeader_id) {
		$values = array(
			'link_type' => $_link_type,
			'link_id' => $_link_id,
			'plan3dHeader_id' => $_plan3dHeader_id,
		);
		$sql = 'DELETE FROM plan3d
		WHERE link_type=:link_type
		AND link_id=:link_id
		AND plan3dHeader_id=:plan3dHeader_id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function searchByDisplay($_search) {
		$value = array(
			'search' => '%' . $_search . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d
		WHERE display LIKE :search';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function searchByConfiguration($_search, $_not = '') {
		$value = array(
			'search' => '%' . $_search . '%',
			'not' => $_not,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3d
		WHERE configuration LIKE :search
		AND link_type !=:not';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	/*     * *********************Methode d'instance************************* */
	
	public function preInsert() {
		if (in_array($this->getLink_type(), array('eqLogic', 'cmd', 'scenario'))) {
			self::removeByLinkTypeLinkId3dHeaderId($this->getLink_type(), $this->getLink_id(), $this->getPlan3dHeader_id());
		}
	}
	
	public function preSave() {
		$default = array(
			'3d::widget::light::power' => 6,
			'3d::widget::text::fontsize' => 24,
			'3d::widget::text::backgroundcolor' => '#ff6464',
			'3d::widget::text::backgroundtransparency' => 0.8,
			'3d::widget::text::bordercolor' => '#ff0000',
			'3d::widget::text::bordertransparency' => 1,
			'3d::widget::text::textcolor' => '#000000',
			'3d::widget::text::texttransparency' => 1,
			'3d::widget::text::space::z' => 10,
			'3d::widget::door::shutterclose' => '#0000ff',
			'3d::widget::door::windowclose' => '#ff0000',
			'3d::widget::door::windowopen' => '#00ff00',
			'3d::widget::door::rotate::0' => 'left',
			'3d::widget::door::rotate::1' => 'front',
			'3d::widget::door::rotate::way' => 1,
			'3d::widget::door::rotate' => 0,
			'3d::widget::door::windowopen::enableColor' => 0,
			'3d::widget::door::windowclose::enableColor' => 0,
			'3d::widget::door::shutterclose::enableColor' => 0,
			'3d::widget::door::translate' => 0,
			'3d::widget::door::translate::repeat' => 1,
		);
		foreach ($default as $key => $value) {
			$this->setConfiguration($key, $this->getConfiguration($key, $value));
		}
	}
	
	public function save() {
		DB::save($this);
	}
	
	public function remove() {
		DB::remove($this);
	}
	
	public function getLink() {
		if ($this->getLink_type() == 'eqLogic') {
			$eqLogic = eqLogic::byId(str_replace(array('#', 'eqLogic'), '', $this->getLink_id()));
			return $eqLogic;
		} else if ($this->getLink_type() == 'scenario') {
			$scenario = scenario::byId($this->getLink_id());
			return $scenario;
		} else if ($this->getLink_type() == 'cmd') {
			$cmd = cmd::byId($this->getLink_id());
			return $cmd;
		} else if ($this->getLink_type() == 'summary') {
			$object = jeeObject::byId($this->getLink_id());
			return $object;
		}
		return null;
	}
	
	public function getHtml($_version = 'dashboard') {
		if (in_array($this->getLink_type(), array('eqLogic', 'cmd', 'scenario'))) {
			$link = $this->getLink();
			if (!is_object($link)) {
				return;
			}
			return array(
				'3d' => utils::o2a($this),
				'html' => $link->toHtml($_version),
			);
		}
	}
	
	public function additionalData() {
		$return = array();
		$return['cmd_id'] = str_replace('#', '', $this->getConfiguration('cmd::state'));
		$cmd = cmd::byId($return['cmd_id']);
		if (is_object($cmd) && $cmd->getType() == 'info') {
			$return['state'] = $cmd->execCmd();
			$return['subType'] = $cmd->getSubType();
		}
		if ($this->getLink_type() == 'eqLogic') {
			if ($this->getConfiguration('3d::widget') == 'text') {
				$return['text'] = scenarioExpression::setTags($this->getConfiguration('3d::widget::text::text'));
				preg_match_all("/#([0-9]*)#/", $this->getConfiguration('3d::widget::text::text'), $matches);
				$return['cmds'] = $matches[1];
			}
			if ($this->getConfiguration('3d::widget') == 'door') {
				$return['cmds'] = array(str_replace('#', '', $this->getConfiguration('3d::widget::door::window')), str_replace('#', '', $this->getConfiguration('3d::widget::door::shutter')));
				$return['state'] = 0;
				$cmd = cmd::byId(str_replace('#', '', $this->getConfiguration('3d::widget::door::window')));
				if (is_object($cmd) && $cmd->getType() == 'info') {
					$cmd_value = $cmd->execCmd();
					if ($cmd->getSubType() == 'binary' && $cmd->getDisplay('invertBinary') == 1) {
						$cmd_value = ($cmd_value == 1) ? 0 : 1;
					}
					$return['state'] = $cmd_value;
				}
				if ($return['state'] > 0) {
					$cmd = cmd::byId(str_replace('#', '', $this->getConfiguration('3d::widget::door::shutter')));
					if (is_object($cmd) && $cmd->getType() == 'info') {
						$cmd_value = $cmd->execCmd();
						if ($cmd->getSubType() == 'binary' && $cmd->getDisplay('invertBinary') == 1) {
							$cmd_value = ($cmd_value == 1) ? 0 : 1;
						}
						if ($cmd_value) {
							$return['state'] = 2;
						}
					}
				}
			}
			if ($this->getConfiguration('3d::widget') == 'conditionalColor') {
				$return['color'] = '';
				$return['cmds'] = array();
				$conditions = $this->getConfiguration('3d::widget::conditionalColor::condition');
				if (!is_array($conditions) || count($conditions) == 0) {
					return $return;
				}
				foreach ($conditions as $condition) {
					if (!isset($condition['color'])) {
						continue;
					}
					if (!isset($condition['cmd'])) {
						continue;
					}
					preg_match_all("/#([0-9]*)#/", $condition['cmd'], $matches);
					foreach ($matches[1] as $cmd_id) {
						$return['cmds'][] = $cmd_id;
					}
				}
				foreach ($conditions as $condition) {
					if (!isset($condition['color'])) {
						continue;
					}
					if (!isset($condition['cmd'])) {
						continue;
					}
					if (jeedom::evaluateExpression($condition['cmd'])) {
						$return['color'] = $condition['color'];
						return $return;
					}
				}
			}
			if ($this->getConfiguration('3d::widget') == 'conditionalShow') {
				$return['show'] = true;
				$return['cmds'] = array();
				$conditions = $this->getConfiguration('3d::widget::conditionalShow::condition');
				if (!is_array($conditions) || count($conditions) == 0) {
					return $return;
				}
				foreach ($conditions as $condition) {
					if (!isset($condition['cmd'])) {
						continue;
					}
					preg_match_all("/#([0-9]*)#/", $condition['cmd'], $matches);
					foreach ($matches[1] as $cmd_id) {
						$return['cmds'][] = $cmd_id;
					}
				}
				foreach ($conditions as $condition) {
					if (!isset($condition['cmd'])) {
						continue;
					}
					if (jeedom::evaluateExpression($condition['cmd'])) {
						$return['show'] = false;
						return $return;
					}
				}
			}
		} else if ($this->getLink_type() == 'scenario') {
			
		} else if ($this->getLink_type() == 'cmd') {
			
		} else if ($this->getLink_type() == 'summary') {
			
		}
		return $return;
	}
	
	public function getPlan3dHeader() {
		return plan3dHeader::byId($this->getPlan3dHeader_id());
	}
	
	/*     * **********************Getteur Setteur*************************** */
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getLink_type() {
		return $this->link_type;
	}
	
	public function getLink_id() {
		return $this->link_id;
	}
	
	public function getPosition($_key = '', $_default = '') {
		return utils::getJsonAttr($this->position, $_key, $_default);
	}
	
	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}
	
	public function getCss($_key = '', $_default = '') {
		return utils::getJsonAttr($this->css, $_key, $_default);
	}
	
	public function setId($_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}
	
	public function setName($_name) {
		$this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
		$this->name = $_name;
		return $this;
	}
	
	public function setLink_type($_link_type) {
		$this->_changed = utils::attrChanged($this->_changed,$this->link_type,$_link_type);
		$this->link_type = $_link_type;
		return $this;
	}
	
	public function setLink_id($_link_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->link_id,$_link_id);
		$this->link_id = $_link_id;
		return $this;
	}
	
	public function setPosition($_key, $_value) {
		$position = utils::setJsonAttr($this->position, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->position,$position);
		$this->position = $position;
		return $this;
	}
	
	public function setDisplay($_key, $_value) {
		$display = utils::setJsonAttr($this->display, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->display,$display);
		$this->display = $display;
		return $this;
	}
	
	public function setCss($_key, $_value) {
		$css = utils::setJsonAttr($this->css, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->css,$css);
		$this->css = $css;
		return $this;
	}
	
	public function getPlan3dHeader_id() {
		return $this->plan3dHeader_id;
	}
	
	public function setPlan3dHeader_id($_plan3dHeader_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->plan3dHeader_id,$_plan3dHeader_id);
		$this->plan3dHeader_id = $_plan3dHeader_id;
		return $this;
	}
	
	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}
	
	public function setConfiguration($_key, $_value) {
		$configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->configuration,$configuration);
		$this->configuration = $configuration;
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
