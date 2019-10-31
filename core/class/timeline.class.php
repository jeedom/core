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

class timeline {
  /*     * *************************Attributs****************************** */
  
  private $id;
  private $name;
  private $type;
  private $subtype;
  private $link_id;
  private $datetime;
  private $options;
  private $_changed = false;
  
  /*     * ***********************Méthodes statiques*************************** */
  
  public static function all() {
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM timeline';
    return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
  }
  
  public static function byId($_id) {
    $values = array(
      'id' => $_id,
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM timeline
    WHERE id=:id';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
  }
  
  
  /*     * *********************Méthodes d'instance************************* */
  
  public function preSave(){
    if($this->getDatetime() == ''){
      $this->setDatetime(date('Y-m-d H:i:s'));
    }
  }
  
  public function save() {
    DB::save($this);
    return true;
  }
  
  public function remove() {
    DB::remove($this);
  }
  
  /*     * **********************Getteur Setteur*************************** */
  
  public function getId() {
    return $this->id;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getLink_id() {
    return $this->link_id;
  }
  
  public function getType() {
    return $this->type;
  }
  
  public function getSubtype() {
    return $this->subtype;
  }
  
  public function getDatetime() {
    return $this->datetime;
  }
  
  public function getOptions($_key = '', $_default = '') {
    return utils::getJsonAttr($this->options, $_key, $_default);
  }
  
  public function setId($_id = '') {
    $this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
    $this->id = $_id;
    return $this;
  }
  
  public function setName($_name) {
    $this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
    $this->name = $_name;
    return $this;
  }
  
  public function setType($_type) {
    $this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
    $this->type = $_type;
    return $this;
  }
  
  public function setSubtype($_subtype) {
    $this->_changed = utils::attrChanged($this->_changed,$this->subtype,$_subtype);
    $this->subtype = $_subtype;
    return $this;
  }
  
  public function setDatetime($_datetime) {
    $this->_changed = utils::attrChanged($this->_changed,$this->datetime,$_datetime);
    $this->datetime = $_datetime;
    return $this;
  }
  
  public function setOptions($_key, $_value = null) {
    $options = utils::setJsonAttr($this->options, $_key, $_value);
    $this->_changed = utils::attrChanged($this->_changed,$this->options,$options);
    $this->options = $options;
    return $this;
  }
  
  public function setLink_id($_link_id = '') {
    $this->_changed = utils::attrChanged($this->_changed,$this->link_id,$_link_id);
    $this->link_id = $_link_id;
    return $this;
  }
  
}
