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
	private int $order = 9999;
	private bool $_changed = false;

	/*     * ***********************Méthodes statiques*************************** */

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
		FROM plan3dHeader
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public static function all(): ?array
    {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM plan3dHeader
		ORDER BY `order`';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     *
     * @param $_type
     * @param $_id
     * @return array
     * @throws ReflectionException
     */
	public static function searchByUse($_type, $_id): array
    {
		$return = array();
		$search = '#' . str_replace('cmd', '', $_type . $_id) . '#';
		$plan3ds = array_merge(plan3d::byLinkTypeLinkId($_type, $_id), plan3d::searchByConfiguration($search, 'eqLogic'));
		foreach ($plan3ds as $plan3d) {
			$plan3dHeader = $plan3d->get3dHeader();
			if(!is_object($plan3dHeader)){
				continue;
			}
			$return[$plan3dHeader->getId()] = $plan3dHeader;
		}
		return $return;
	}

	/*     * *********************Méthodes d'instance************************* */

    /**
     * @throws Exception
     */
    public function preSave() {
		if (trim($this->getName()) == '') {
			throw new Exception(__('Le nom du l\'objet ne peut pas être vide', __FILE__));
		}
	}

    /**
     * @throws Exception
     */
    public function save() {
		DB::save($this);
	}

    /**
     * @throws Exception
     */
    public function remove() {
		$cibDir = __DIR__ . '/../../' . $this->getConfiguration('path', '');
		if (file_exists($cibDir) && $this->getConfiguration('path', '') != '') {
			rrmdir($cibDir);
		}
		jeedom::addRemoveHistory(array('id' => $this->getId(), 'name' => $this->getName(), 'date' => date('Y-m-d H:i:s'), 'type' => 'plan3d'));
		DB::remove($this);
	}

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public function getPlan3d(): ?array
    {
		return plan3d::byPlan3dHeaderId($this->getId());
	}

    /**
     * @param array[] $_data
     * @param int $_level
     * @param int $_drill
     * @return array|array[]|void
     */
    public function getLinkData(&$_data = array('node' => array(), 'link' => array()), $_level = 0, $_drill = 3): array
    {
		if (isset($_data['node']['plan3d' . $this->getId()])) {
			return;
		}
		$_level++;
		if ($_level > $_drill) {
			return $_data;
		}
		$icon = findCodeIcon($this->getConfiguration('icon','<i class="fas fa-paint-brush"></i>'));
		$_data['node']['plan3d' . $this->getId()] = array(
			'id' => 'plan3d' . $this->getId(),
			'type' => __('Design 3d',__FILE__),
			'name' => substr($this->getName(), 0, 20),
			'icon' => $icon['icon'],
			'fontfamily' => $icon['fontfamily'],
			'fontsize' => '1.5em',
			'fontweight' => ($_level == 1) ? 'bold' : 'normal',
			'texty' => -14,
			'textx' => 0,
			'title' => __('Design 3d :', __FILE__) . ' ' . $this->getName(),
			'url' => 'index.php?v=d&p=plan3d&plan3d_id=' . $this->getId(),
		);
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
     * @return int|string
     */
    public function getOrder() {
		if ($this->order == '' || !is_numeric($this->order)) {
			return 0;
		}
		return $this->order;
	}

    /**
     * @param $_id
     * @return $this
     */
    public function setId($_id): plan3dHeader
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    /**
     * @param $_name
     * @return $this
     */
    public function setName($_name): plan3dHeader
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
		$this->name = $_name;
		return $this;
	}

    /**
     * @param $_order
     * @return $this
     */
    public function setOrder($_order): plan3dHeader
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->order,$_order);
		$this->order = $_order;
		return $this;
	}

    /**
     * @param string $_key
     * @param string $_default
     * @return array|bool|mixed|string
     */
    public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

    /**
     * @param $_key
     * @param $_value
     * @return $this
     */
    public function setConfiguration($_key, $_value): plan3dHeader
    {
		if ($_key == 'accessCode' && $_value != '' && !is_sha512($_value)) {
			$_value = sha512($_value);
		}
		$configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
		$this->_changed = utils::attrChanged($this->_changed,$this->configuration,$configuration);
		$this->configuration = $configuration;
		return $this;
	}

    /**
     * @return bool
     */
    public function getChanged(): bool
    {
		return $this->_changed;
	}

    /**
     * @param $_changed
     * @return $this
     */
    public function setChanged($_changed): plan3dHeader
    {
		$this->_changed = $_changed;
		return $this;
	}

}
