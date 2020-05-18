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

class plan3dHeader {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $name;
	private $configuration;
	private $_changed = false;
	
	/*     * ***********************Méthodes statiques*************************** */
	
	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3dHeader
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3dHeader';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	/**
	*
	* @param type $_type
	* @param type $_id
	* @return type
	*/
	public static function searchByUse($_type, $_id) {
		$return = array();
		$search = '#' . str_replace('cmd', '', $_type . $_id) . '#';
		$plan3ds = array_merge(plan3d::byLinkTypeLinkId($_type, $_id), plan3d::searchByConfiguration($search, 'eqLogic'));
		foreach ($plan3ds as $plan3d) {
			$plan3dHeader = $plan3d->get3dHeader();
			if(is_object($plan3dHeader)){
				$return[$plan3dHeader->getId()] = $plan3dHeader;
			}
		}
		return $return;
	}
	
	/*     * *********************Méthodes d'instance************************* */
	
	public function preSave() {
		if (trim($this->getName()) == '') {
			throw new Exception(__('Le nom du l\'objet ne peut pas être vide', __FILE__));
		}
	}
	
	public function save() {
		DB::save($this);
	}
	
	public function remove() {
		$cibDir = __DIR__ . '/../../' . $this->getConfiguration('path', '');
		if (file_exists($cibDir) && $this->getConfiguration('path', '') != '') {
			rrmdir($cibDir);
		}
		jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'plan3d'));
		DB::remove($this);
	}
	
	public function getPlan3d() {
		return plan3d::byPlan3dHeaderId($this->getId());
	}
	
	/*     * **********************Getteur Setteur*************************** */
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
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
	
	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}
	
	public function setConfiguration($_key, $_value) {
		if ($_key == 'accessCode' && $_value != '' && !is_sha512($_value)) {
			$_value = sha512($_value);
		}
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
