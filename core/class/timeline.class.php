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
  private string $folder = 'main';
  private $subtype;
  private $link_id;
  private $datetime;
  private $options;
  private bool $_changed = false;

  /*     * ***********************Méthodes statiques*************************** */

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public static function all(): ?array
  {
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM timeline';
    return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
  }

    /**
     * @param $_startTime
     * @param null $_endTime
     * @return array|null
     * @throws ReflectionException
     */
    public static function byDateRange($_startTime, $_endTime=null): ?array
  {
    if ($_endTime == null) {
      $_endTime = $_startTime;
    }
    $values = array(
      'startTime' => $_startTime,
      'endTime' => $_endTime
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM timeline
    WHERE `datetime`>=:startTime
    AND `datetime`<=:endTime';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
  }

    /**
     * @param string $_folder
     * @return array|null
     * @throws ReflectionException
     */
    public static function byFolder($_folder = 'main'): ?array
  {
    self::cleaning();
    if($_folder == 'main'){
      $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
      FROM timeline';
      return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }
    $values = array(
      'folder' => '(^|,)'.$_folder.'($|,)',
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM timeline
    WHERE folder REGEXP :folder';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
  }

    /**
     * @param $_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byId($_id): ?array
  {
    $values = array(
      'id' => $_id,
    );
    $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
    FROM timeline
    WHERE id=:id';
    return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
  }

    /**
     * @param false $_all
     * @throws Exception
     */
    public static function cleaning($_all = false) {
    //reset:
    if ($_all) {
      $sql = 'DELETE FROM timeline';
      DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
      return;
    }
    //ensure no duplicates:
    $sql = 'DELETE t1 FROM timeline t1 INNER JOIN timeline t2 WHERE ';
    $sql .= 't1.id < t2.id AND ';
    $sql .= 't1.type = t2.type AND ';
    $sql .= 't1.subtype = t2.subtype AND ';
    $sql .= 't1.datetime = t2.datetime AND ';
    $sql .= 't1.options = t2.options AND ';
    $sql .= 't1.folder = t2.folder AND ';
    $sql .= 't1.link_id = t2.link_id';
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);

    //clean:
    $sql = 'SELECT count(id) as number FROM timeline';
    $result = DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
    $delete_number = $result['number'] - config::byKey('timeline::maxevent');
    if($delete_number <= 0){
      return;
    }
    $values = array(
      'number' => $delete_number,
    );
    $sql = 'DELETE FROM timeline ORDER BY `datetime` ASC LIMIT '.$delete_number;
    DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW);
  }

    /**
     * @return string[]
     * @throws Exception
     */
    public static function listFolder(): array
  {
    $sql = 'SELECT DISTINCT(folder) as folder
    FROM timeline';
    $sql .= ' ORDER BY folder';
    $results = DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL);
    $return = array('main');
    foreach ($results as $result) {
      $infos = explode(',',$result['folder']);
      foreach ($infos as $info) {
        if($info == ''){
          continue;
        }
        if(!in_array($info,$return)){
          $return[] = $info;
        }
      }
    }
    return $return;
  }


  /*     * *********************Méthodes d'instance************************* */

  public function preSave(){
    if($this->getDatetime() == ''){
      $this->setDatetime(date('Y-m-d H:i:s'));
    }
    if(trim($this->getFolder()) == ''){
      $this->setFolder('main');
    }
  }

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
  {
    DB::save($this);
    return true;
  }

    /**
     * @throws Exception
     */
    public function remove() {
    DB::remove($this);
  }

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public function getDisplay(): ?array
  {
    $return = array();
    $return['date'] = $this->getDatetime();
    $return['type'] = $this->getType();
    $return['group'] = $this->getSubtype();
    $return['folder'] = $this->getFolder();

    switch ($this->getType()) {
      case 'cmd':
        $cmd = cmd::byId($this->getLink_id());
        if (!is_object($cmd)) {
          return null;
        }
        $eqLogic = $cmd->getEqLogic();
        $object = $eqLogic->getObject();
        $return['object'] = is_object($object) ? $object->getId() : 'aucun';
        $return['plugins'] = $eqLogic->getEqType_name();
        $return['category'] = $eqLogic->getCategory();

        $name = str_replace(array('<br/><strong>','</strong>'), '',  $this->getName());
        $name = str_replace('<span class="label"', '<span class="label-sm"',  $name);
        if ($cmd->getType() == 'action') {
          $return['html'] = '<div class="tml-cmd" data-id="' . $this->getLink_id() . '">';
          $return['html'] .= '<span>' . $name . ' <i class="fas fa-cogs pull-right cursor bt_configureCmd" title="'.__('Configuration de la commande',__FILE__).'"></i></span>';
          $return['html'] .= '</div>';
        } else {
          $class = 'info';
          if ($this->getOptions('cmdType') == 'binary') {
            $class = ($this->getOptions('value') == 0 ? 'success' : 'warning');
          }
          $return['html'] = '<div class="tml-cmd" data-id="' .$this->getLink_id() . '">';
          $return['html'] .= ' <i class="fas fa-cogs pull-right cursor bt_configureCmd" title="'.__('Configuration de la commande',__FILE__).'"></i>';
          $return['html'] .= '<span>' . $name . '<i class="fas fa-chart-line pull-right cursor bt_historicCmd" title="'.__('Historique',__FILE__).'"></i>';
          $return['html'] .= ' <span class="label-sm label-'.$class.'">' .$this->getOptions('value') . '</span>';
          if ($return['folder'] != 'main') $return['html'] .= ' <span class="tml-folder pull-right">' . $return['folder'] . '</span>';
          $return['html'] .= '</span>';
          $return['html'] .= '</div>';
        }
        break;
      case 'scenario':
        $scenario = scenario::byId($this->getLink_id());
        if (!is_object($scenario)) {
          return null;
        }
        $object = $scenario->getObject();
        $return['object'] = is_object($object) ? $object->getId() : 'aucun';
        $name = str_replace(array('<br/><strong>','</strong>'), '',  $this->getName());
        $name = str_replace('<span class="label"', '<span class="label-sm"',  $name);
        $return['html'] = '<div class="tml-scenario" data-id="' . $this->getLink_id() . '">';
        $return['html'] .= '<div>' . $name;
        $return['html'] .= ' <span class="label-sm label-info" title="'.__('Scénario déclenché par',__FILE__).'">' . $this->getOptions('trigger'). '</span>';
        $return['html'] .= ' <i class="fas fa-share pull-right cursor bt_gotoScenario" title="'.__('Aller au scénario',__FILE__).'"></i> ';
        $return['html'] .= ' <i class="fas fa-file-alt pull-right cursor bt_scenarioLog" title="'.__('Log du scénario',__FILE__).'"></i> ';
        if ($return['folder'] != 'main') $return['html'] .= ' <span class="tml-folder pull-right">' . $return['folder'] . '</span>';
        $return['html'] .= '</div>';
        $return['html'] .= '</div>';
        break;
    }
    return $return;
  }

  /*     * **********************Getteur Setteur*************************** */

    /**
     * @return mixed
     */
    public function getId() {
    return $this->id;
  }

    /**
     * @return mixed
     */
    public function getName() {
    return $this->name;
  }

    /**
     * @return mixed
     */
    public function getLink_id() {
    return $this->link_id;
  }

    /**
     * @return mixed
     */
    public function getType() {
    return $this->type;
  }

    /**
     * @return string
     */
    public function getFolder(): string
  {
    return $this->folder;
  }

    /**
     * @return mixed
     */
    public function getSubtype() {
    return $this->subtype;
  }

    /**
     * @return mixed
     */
    public function getDatetime() {
    return $this->datetime;
  }

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getOptions($_key = '', $_default = '') {
    return utils::getJsonAttr($this->options, $_key, $_default);
  }

    /**
     * @param string $_id
     * @return $this
     */
    public function setId($_id = ''): timeline
  {
    $this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
    $this->id = $_id;
    return $this;
  }

    /**
     * @param $_name
     * @return $this
     */
    public function setName($_name): timeline
  {
    $this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
    $this->name = $_name;
    return $this;
  }

    /**
     * @param $_type
     * @return $this
     */
    public function setType($_type): timeline
  {
    $this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
    $this->type = $_type;
    return $this;
  }

    /**
     * @param $_folder
     * @return $this
     */
    public function setFolder($_folder): timeline
  {
    $_folder = trim(trim(trim($_folder),','));
    $this->_changed = utils::attrChanged($this->_changed,$this->folder,$_folder);
    $this->folder = $_folder;
    return $this;
  }

    /**
     * @param $_subtype
     * @return $this
     */
    public function setSubtype($_subtype): timeline
  {
    $this->_changed = utils::attrChanged($this->_changed,$this->subtype,$_subtype);
    $this->subtype = $_subtype;
    return $this;
  }

    /**
     * @param $_datetime
     * @return $this
     */
    public function setDatetime($_datetime): timeline
  {
    $this->_changed = utils::attrChanged($this->_changed,$this->datetime,$_datetime);
    $this->datetime = $_datetime;
    return $this;
  }

    /**
     * @param $_key
     * @param null $_value
     * @return $this
     */
    public function setOptions($_key, $_value = null): timeline
  {
    $options = utils::setJsonAttr($this->options, $_key, $_value);
    $this->_changed = utils::attrChanged($this->_changed,$this->options,$options);
    $this->options = $options;
    return $this;
  }

    /**
     * @param string $_link_id
     * @return $this
     */
    public function setLink_id($_link_id = ''): timeline
  {
    $this->_changed = utils::attrChanged($this->_changed,$this->link_id,$_link_id);
    $this->link_id = $_link_id;
    return $this;
  }

}
