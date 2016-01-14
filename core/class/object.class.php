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

class object {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $father_id = null;
	private $isVisible = 1;
	private $position;
	private $configuration;
	private $display;

	/*     * ***********************Méthodes statiques*************************** */

	public static function byId($_id) {
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

	public static function all($_onlyVisible = false) {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM object ';
		if ($_onlyVisible) {
			$sql .= ' WhERE isVisible = 1';
		}
		$sql .= ' ORDER BY name,father_id,position';
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
			return DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
		}
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function buildTree($_object = null, $_visible = true) {
		$return = array();
		if (!is_object($_object)) {
			$object_list = self::rootObject(true, $_visible);
		} else {
			$object_list = $_object->getChild($_visible);
		}
		foreach ($object_list as $object) {
			$return[] = $object;
			$return = array_merge($return, self::buildTree($object, $_visible));
		}
		return $return;
	}

	public static function fullData($_restrict = array()) {
		$return = array();
		foreach (object::all(true) as $object) {
			if (!is_array($_restrict['object']) || isset($_restrict['object'][$object->getId()])) {
				$object_return = utils::o2a($object);
				$object_return['eqLogics'] = array();
				foreach ($object->getEqLogic(true, true) as $eqLogic) {
					if (!isset($_restrict['eqLogic']) || !is_array($_restrict['eqLogic']) || isset($_restrict['eqLogic'][$eqLogic->getId()])) {
						$eqLogic_return = utils::o2a($eqLogic);
						$eqLogic_return['cmds'] = array();
						foreach ($eqLogic->getCmd() as $cmd) {
							if (!isset($_restrict['cmd']) || !is_array($_restrict['cmd']) || isset($_restrict['cmd'][$cmd->getId()])) {
								$cmd_return = utils::o2a($cmd);
								if ($cmd->getType() == 'info') {
									$cmd_return['state'] = $cmd->execCmd(null, 2);
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
		cache::set('api::object::full', json_encode($return), 0);
		return $return;
	}

	/*     * *********************Méthodes d'instance************************* */

	public function preSave() {
		if (is_numeric($this->getFather_id()) && $this->getFather_id() == $this->getId()) {
			throw new Exception(__('L\'objet ne peut pas être son propre père', __FILE__));
		}
		$this->checkTreeConsistency();
	}

	public function checkTreeConsistency($_fathers = array()) {
		$father = $this->getFather();
		if (!is_object($father)) {
			return;
		}
		if (in_array($this->getFather_id(), $_fathers)) {
			throw new Exception(__('Problème dans l\'arbre des objets', __FILE__));
		}
		$_fathers[] = $this->getId();

		$father->checkTreeConsistency($_fathers);
	}

	public function save() {
		return DB::save($this);
	}

	public function getChild($_visible = true) {
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
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public function getChilds() {
		$return = array();
		foreach ($this->getChild() as $child) {
			$return[] = $child;
			$return = array_merge($return, $child->getChilds());
		}
		return $return;
	}

	public function getEqLogic($_onlyEnable = true, $_onlyVisible = false, $_eqType_name = null, $_logicalId = null) {
		return eqLogic::byObjectId($this->getId(), $_onlyEnable, $_onlyVisible, $_eqType_name, $_logicalId);
	}

	public function getScenario($_onlyEnable = true, $_onlyVisible = false) {
		return scenario::byObjectId($this->getId(), $_onlyEnable, $_onlyVisible);
	}

	public function preRemove() {
		dataStore::removeByTypeLinkId('object', $this->getId());
	}

	public function remove() {
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
				if ($this->getDisplay('tagColor') != '') {
					return '<span class="label" style="text-shadow : none;background-color:' . $this->getDisplay('tagColor') . ';color:' . $this->getDisplay('tagTextColor', 'white') . '">' . $this->getDisplay('icon') . ' ' . $this->getName() . '</span>';
				} else {
					return '<span class="label label-primary" style="text-shadow : none;">' . $this->getDisplay('icon') . ' ' . $this->getName() . '</span>';
				}
			} else {
				return $this->getDisplay('icon') . ' ' . $this->getName();
			}
		} else {
			return $this->getName();
		}

	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getFather_id() {
		return $this->father_id;
	}

	public function getIsVisible() {
		return $this->isVisible;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setName($name) {
		$name = str_replace(array('&', '#', ']', '[', '%'), '', $name);
		$this->name = $name;
	}

	public function setFather_id($father_id = null) {
		$this->father_id = ($father_id == '') ? null : $father_id;
	}

	public function setIsVisible($isVisible) {
		$this->isVisible = $isVisible;
	}

	public function getPosition() {
		return $this->position;
	}

	public function setPosition($position) {
		$this->position = $position;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
	}

	public function getDisplay($_key = '', $_default = '') {
		return utils::getJsonAttr($this->display, $_key, $_default);
	}

	public function setDisplay($_key, $_value) {
		$this->display = utils::setJsonAttr($this->display, $_key, $_value);
	}

}

?>
