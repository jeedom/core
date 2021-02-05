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

class viewZone {
	/*     * *************************Attributs****************************** */

	private $id;
	private $view_id;
	private $type;
	private $name;
	private $position;
	private $configuration;
	private bool $_changed = false;

	/*     * ***********************Methode static*************************** */

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public static function all(): ?array
    {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewZone';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byId($_id): ?array
    {
		$value = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewZone
		WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_view_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byView($_view_id): ?array
    {
		$value = array(
			'view_id' => $_view_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewZone
		WHERE view_id=:view_id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_view_id
     * @return array|null
     * @throws Exception
     */
    public static function removeByViewId($_view_id): ?array
    {
		$value = array(
			'view_id' => $_view_id,
		);
		$sql = 'DELETE FROM viewZone
		WHERE view_id=:view_id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW);
	}

	/*     * *********************Methode d'instance************************* */

    /**
     * @return bool
     * @throws Exception
     */
    public function save(): bool
    {
		return DB::save($this);
	}

    /**
     * @return bool
     * @throws Exception
     */
    public function remove(): bool
    {
		return DB::remove($this);
	}

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public function getviewData(): ?array
    {
		return viewData::byviewZoneId($this->getId());
	}

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public function getView(): ?array
    {
		return view::byId($this->getView_id());
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * @return mixed
     */
    public function getId() {
		return $this->id;
	}

    /**
     * @param $_id
     * @return $this
     */
    public function setId($_id): viewZone
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getView_id() {
		return $this->view_id;
	}

    /**
     * @param $_view_id
     * @return $this
     */
    public function setView_id($_view_id): viewZone
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->view_id,$_view_id);
		$this->view_id = $_view_id;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getType() {
		return $this->type;
	}

    /**
     * @param $_type
     * @return $this
     */
    public function setType($_type): viewZone
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
		$this->type = $_type;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getName() {
		return $this->name;
	}

    /**
     * @param $_name
     * @return $this
     */
    public function setName($_name): viewZone
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
		$this->name = $_name;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getPosition() {
		return $this->position;
	}

    /**
     * @param $_position
     * @return $this
     */
    public function setPosition($_position): viewZone
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->position,$_position);
		$this->position = $_position;
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
    public function setConfiguration($_key, $_value): viewZone
    {
		$configuration =  utils::setJsonAttr($this->configuration, $_key, $_value);
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
    public function setChanged($_changed): viewZone
    {
		$this->_changed = $_changed;
		return $this;
	}

}
