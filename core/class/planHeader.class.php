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

class planHeader {
	/*     * *************************Attributs****************************** */
	
	private $id;
	private $name;
	private $image;
	private $configuration;
	private $_changed = false;
	
	/*     * ***********************Méthodes statiques*************************** */
	
	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM planHeader
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}
	
	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM planHeader';
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
		$plans = array_merge(plan::byLinkTypeLinkId($_type, $_id), plan::searchByConfiguration($search, 'eqLogic'));
		foreach ($plans as $plan) {
			$planHeader = $plan->getPlanHeader();
			if(is_object($planHeader)){
				$return[$planHeader->getId()] = $planHeader;
			}
		}
		return $return;
	}
	
	/*     * *********************Méthodes d'instance************************* */
	
	public function report($_format = 'pdf', $_parameters = array()) {
		$url = network::getNetworkAccess('internal') . '/index.php?v=d&p=plan';
		$url .= '&plan_id=' . $this->getId();
		$url .= '&report=1';
		if (isset($_parameters['arg']) && trim($_parameters['arg']) != '') {
			$url .= '&' . $_parameters['arg'];
		}
		return report::generate($url, 'plan', $this->getId(), $_format, $_parameters);
	}
	
	public function copy($_name) {
		$planHeaderCopy = clone $this;
		$planHeaderCopy->setName($_name);
		$planHeaderCopy->setId('');
		$planHeaderCopy->save();
		foreach ($this->getPlan() as $plan) {
			$planCopy = clone $plan;
			$planCopy->setId('');
			$planCopy->setPlanHeader_id($planHeaderCopy->getId());
			$planCopy->save();
		}
		$filename1 = 'planHeader'.$this->getId().'-'.$this->getImage('sha512') . '.' . $this->getImage('type');
		if(file_exists(__DIR__.'/../../data/plan/'.$filename1)){
			$filename2 = 'planHeader'.$planHeaderCopy->getId().'-'.$planHeaderCopy->getImage('sha512') . '.' . $planHeaderCopy->getImage('type');
			copy(__DIR__.'/../../data/plan/'.$filename1,__DIR__.'/../../data/plan/'.$filename2);
		}
		return $planHeaderCopy;
	}
	
	public function preSave() {
		if (trim($this->getName()) == '') {
			throw new Exception(__('Le nom du plan ne peut pas être vide', __FILE__));
		}
		if ($this->getConfiguration('desktopSizeX') == '') {
			$this->setConfiguration('desktopSizeX', 500);
		}
		if ($this->getConfiguration('desktopSizeY') == '') {
			$this->setConfiguration('desktopSizeY', 500);
		}
		if ($this->getConfiguration('backgroundTransparent') == '') {
			$this->setConfiguration('backgroundTransparent', 1);
		}
		if ($this->getConfiguration('backgroundColor') == '') {
			$this->setConfiguration('backgroundColor', '#ffffff');
		}
	}
	
	public function save() {
		DB::save($this);
	}
	
	public function remove() {
		jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'plan'));
		DB::remove($this);
	}
	
	public function displayImage() {
		if ($this->getImage('sha512') == '') {
			return '';
		}
		$size = $this->getImage('size');
		$filename = 'planHeader'.$this->getId().'-'.$this->getImage('sha512') . '.' . $this->getImage('type');
		return '<img style="z-index:997" src="data/plan/' . $filename . '" data-sixe_y="' . $size[1] . '" data-sixe_x="' . $size[0] . '">';
	}
	
	public function getPlan() {
		return plan::byPlanHeaderId($this->getId());
	}
	
	public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = 3) {
		if (isset($_data['node']['plan' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = findCodeIcon('fa-paint-brush');
		$_data['node']['plan' . $this->getId()] = array(
			'id' => 'interactDef' . $this->getId(),
			'name' => substr($this->getName(), 0, 20),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontsize' => '1.5em',
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'texty' => -14,
			'textx' => 0,
			'title' => __('Design :', __FILE__) . ' ' . $this->getName(),
			'url' => 'index.php?v=d&p=plan&view_id=' . $this->getId(),
		);
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
	
	public function getImage($_key = '', $_default = '') {
		return utils::getJsonAttr($this->image, $_key, $_default);
	}
	
	public function setImage($_key, $_value) {
		$image = utils::setJsonAttr($this->image, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->image,$image);
		$this->image = $image;
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
		$this->configuration =$configuration;
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
