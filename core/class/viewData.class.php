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

class viewData {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $order = 0;
	private $viewZone_id;
	private $type;
	private $link_id;
	private $configuration;
	private $_changed = false;
	
	/*     * ***********************Methode static*************************** */
	
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewData';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byId($_id) {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewData
		WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byTypeLinkId($_type, $_link_id) {
		$value = array(
			'type' => $_type,
			'link_id' => $_link_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewData
		WHERE type=:type
		AND link_id=:link_id
		ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function byviewZoneId($_viewZone_id) {
		$value = array(
			'viewZone_id' => $_viewZone_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewData
		WHERE viewZone_id=:viewZone_id
		ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function searchByConfiguration($_search) {
		$value = array(
			'search' => '%' . $_search . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewData
		WHERE configuration LIKE :search';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function removeByTypeLinkId($_type, $_link_id) {
		$viewDatas = self::byTypeLinkId($_type, $_link_id);
		foreach ($viewDatas as $viewData) {
			$viewData->remove();
		}
		return true;
	}
	
	/*     * *********************Methode d'instance************************* */
	
	public function save() {
		return DB::save($this);
	}
	
	public function remove() {
		return DB::remove($this);
	}
	
	public function getviewZone() {
		return viewZone::byId($this->getviewZone_id());
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
	
	public function getOrder() {
		return $this->order;
	}
	
	public function setOrder($_order) {
		$this->_changed = utils::attrChanged($this->_changed,$this->order,$_order);
		$this->order = $_order;
		return $this;
	}
	
	public function getviewZone_id() {
		return $this->viewZone_id;
	}
	
	public function setviewZone_id($_viewZone_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->viewZone_id,$_viewZone_id);
		$this->viewZone_id = $_viewZone_id;
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
	
	public function getLink_id() {
		return $this->link_id;
	}
	
	public function setLink_id($_link_id) {
		$this->_changed = utils::attrChanged($this->_changed,$this->link_id,$_link_id);
		$this->link_id = $_link_id;
		return $this;
	}
	
	public function getLinkObject() {
		$type = $this->getType();
		if (class_exists($type)) {
			return $type::byId($this->getLink_id());
		}
		return false;
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
