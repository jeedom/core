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

class widgets {
  /*     * *************************Attributs****************************** */
  
  private $id;
  private $name;
  private $type = 'action';
  private $subtype;
  private $template;
  private $replace;
  private $test;
  private $display;
  private $_changed = false;
  
  /*     * ***********************Méthodes statiques*************************** */
  
  public static function all() {
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM widgets';
    return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
  }
  
  public static function byId($_id) {
    $values = array(
      'id' => $_id,
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM widgets
    WHERE id=:id';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
  }
  
  public static function byTypeSubtypeAndName($_type, $_subtype, $_name) {
    $values = array(
      'type' => $_type,
      'subtype' => $_subtype,
      'name' => $_name,
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM widgets
    WHERE type=:type
    AND subtype=:subtype
    AND name=:name';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
  }
  
  public static function listTemplate(){
    $return = array();
    $files = ls(__DIR__ . '/../template/dashboard', 'cmd.*', false, array('files', 'quiet'));
    foreach ($files as $file) {
      $informations = explode('.', $file);
      if(count($informations) < 4){
        continue;
      }
      if(stripos($informations[3],'tmpl') === false){
        continue;
      }
      if(!file_exists(__DIR__ . '/../template/mobile/'.$file)){
        continue;
      }
      if (!isset($return[$informations[1]])) {
        $return[$informations[1]] = array();
      }
      if (!isset($return[$informations[1]][$informations[2]])) {
        $return[$informations[1]][$informations[2]] = array();
      }
      if (isset($informations[3])) {
        $return[$informations[1]][$informations[2]][] = $informations[3];
      }
    }
    $files = ls(__DIR__ . '/../../data/customTemplates/dashboard', 'cmd.*', false, array('files', 'quiet'));
    foreach ($files as $file) {
      $informations = explode('.', $file);
      if(count($informations) < 4){
        continue;
      }
      if(stripos($informations[3],'tmpl') === false){
        continue;
      }
      if(!file_exists(__DIR__ . '/../../data/customTemplates/mobile/'.$file)){
        continue;
      }
      if (!isset($return[$informations[1]])) {
        $return[$informations[1]] = array();
      }
      if (!isset($return[$informations[1]][$informations[2]])) {
        $return[$informations[1]][$informations[2]] = array();
      }
      if (isset($informations[3])) {
        $return[$informations[1]][$informations[2]][] = $informations[3];
      }
    }
    return $return;
  }
  
  public static function getTemplateConfiguration($_template){
    $iscustom = false;
    if(!file_exists(__DIR__ . '/../template/dashboard/'.$_template.'.html')){
      $iscustom = true;
      if(!file_exists(__DIR__ . '/../../data/customTemplates/dashboard/'.$_template.'.html')){
        return;
      }
    }
    $return = array('test' => false);
    if ($iscustom) {
      $template = file_get_contents(__DIR__ . '/../../data/customTemplates/dashboard/'.$_template.'.html');
    } else {
      $template = file_get_contents(__DIR__ . '/../template/dashboard/'.$_template.'.html');
    }
    if(strpos($template,'#test#') !== false){
      $return['test'] = true;
    }
    preg_match_all("/#_([a-zA-Z_]*)_#/", $template, $matches);
    if (count($matches[1]) == 0) {
      return $return ;
    }
    $return['replace'] = array_values(array_unique($matches[1]));
    return $return;
  }
  
  public static function replacement($_version,$_replace,$_by){
    $cmds = cmd::searchTemplate($_version.'":"'.$_replace.'"');
    if(!is_array($cmds) || count($cmds) == 0){
      return 0;
    }
    $replace_number = 0;
    foreach ($cmds as $cmd) {
      if($cmd->getTemplate($_version) == $_replace){
        $cmd->setTemplate($_version,$_by);
        $cmd->save();
        $replace_number++;
      }
    }
    return $replace_number;
  }
  
  /*     * *********************Méthodes d'instance************************* */
  
  public function preSave(){
    if($this->getType() == ''){
      $this->setType('action');
    }
  }
  
  public function preUpdate(){
    $widgets = self::byId($this->getId());
    if($widgets->getName() != $this->getName()){
      $usedBy = $widgets->getUsedBy();
      if(is_array($usedBy) && count($usedBy) > 0){
        foreach ($usedBy as $cmd) {
          if($cmd->getTemplate('dashboard') == 'custom::'.$widgets->getName()){
            $cmd->setTemplate('dashboard','custom::'.$this->getName());
          }
          if($cmd->getTemplate('mobile') == 'custom::'.$widgets->getName()){
            $cmd->setTemplate('mobile','custom::'.$this->getName());
          }
          $cmd->save(true);
        }
      }
    }
    if($widgets->getType() != $this->getType() || $widgets->getSubType() != $this->getSubType()){
      $usedBy = $widgets->getUsedBy();
      if(is_array($usedBy) && count($usedBy) > 0){
        foreach ($usedBy as $cmd) {
          if($cmd->getTemplate('dashboard') == 'custom::'.$widgets->getName()){
            $cmd->setTemplate('dashboard','default');
          }
          if($cmd->getTemplate('mobile') == 'custom::'.$widgets->getName()){
            $cmd->setTemplate('mobile','default');
          }
          $cmd->save(true);
        }
      }
    }
  }
  
  public function save() {
    DB::save($this);
    return true;
  }
  
  public function postSave(){
    $usedBy = $this->getUsedBy();
    if(is_array($usedBy) && count($usedBy) > 0){
      foreach ($usedBy as $cmd) {
        $eqLogic = $cmd->getEqLogic();
        if(is_object($eqLogic)){
          $eqLogic->emptyCacheWidget();
        }
      }
    }
  }
  
  public function remove() {
    $usedBy = $this->getUsedBy();
    if(is_array($usedBy) && count($usedBy) > 0){
      foreach ($usedBy as $cmd) {
        if($cmd->getTemplate('dashboard') == 'custom::'.$this->getName()){
          $cmd->setTemplate('dashboard','default');
        }
        if($cmd->getTemplate('mobile') == 'custom::'.$this->getName()){
          $cmd->setTemplate('mobile','default');
        }
        $cmd->save(true);
      }
    }
    DB::remove($this);
  }
  
  public function getUsedBy(){
    $return = array();
    $return = array_merge(
      cmd::searchTemplate('dashboard":"custom::'.$this->getName().'"'),
      cmd::searchTemplate('mobile":"custom::'.$this->getName().'"')
    );
    return $return;
  }
  
  public function emptyTest(){
    $this->test = null;
  }
  
  /*     * **********************Getteur Setteur*************************** */
  
  public function getId() {
    return $this->id;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getType() {
    return $this->type;
  }
  
  public function getSubtype() {
    return $this->subtype;
  }
  
  public function getTemplate() {
    return $this->template;
  }
  
  public function getReplace($_key = '', $_default = '') {
    return utils::getJsonAttr($this->replace, $_key, $_default);
  }
  
  public function getTest($_key = '', $_default = '') {
    return utils::getJsonAttr($this->test, $_key, $_default);
  }
  
  public function setId($_id = '') {
    $this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
    $this->id = $_id;
    return $this;
  }
  
  public function setName($_name) {
    $_name = str_replace(array('&', '#', ']', '[', '%', "'"), '', $_name);
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
  
  public function setTemplate($_template) {
    $this->_changed = utils::attrChanged($this->_changed,$this->template,$_template);
    $this->template = $_template;
    return $this;
  }
  
  public function setReplace($_key, $_value) {
    $replace = utils::setJsonAttr($this->replace, $_key, $_value);
    $this->_changed = utils::attrChanged($this->_changed,$this->replace,$replace);
    $this->replace = utils::setJsonAttr($this->replace, $_key, $_value);
    return $this;
  }
  
  public function setTest($_key, $_value) {
    $test = utils::setJsonAttr($this->test, $_key, $_value);
    $this->_changed = utils::attrChanged($this->_changed,$this->test,$test);
    $this->test = utils::setJsonAttr($this->test, $_key, $_value);
    return $this;
  }
  
  public function getDisplay($_key = '', $_default = '') {
    return utils::getJsonAttr($this->display, $_key, $_default);
  }
  
  public function setDisplay($_key, $_value) {
    if ($this->getDisplay($_key) != $_value) {
      $this->_needRefreshWidget = true;
    }
    $display = utils::setJsonAttr($this->display, $_key, $_value);
    $this->_changed = utils::attrChanged($this->_changed,$this->display,$display);
    $this->display = $display;
  }
}
