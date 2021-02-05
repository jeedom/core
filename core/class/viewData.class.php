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
	private int $order = 0;
	private $viewZone_id;
	private $type;
	private $link_id;
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
		FROM viewData';
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
		FROM viewData
		WHERE id=:id';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_type
     * @param $_link_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byTypeLinkId($_type, $_link_id): ?array
    {
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

    /**
     * @param $_viewZone_id
     * @return array|null
     * @throws ReflectionException
     */
    public static function byviewZoneId($_viewZone_id): ?array
    {
		$value = array(
			'viewZone_id' => $_viewZone_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewData
		WHERE viewZone_id=:viewZone_id
		ORDER BY `order`';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_search
     * @return array|null
     * @throws ReflectionException
     */
    public static function searchByConfiguration($_search): ?array
    {
		$value = array(
			'search' => '%' . $_search . '%',
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM viewData
		WHERE configuration LIKE :search';
		return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

    /**
     * @param $_type
     * @param $_link_id
     * @return bool
     * @throws ReflectionException
     */
    public static function removeByTypeLinkId($_type, $_link_id): bool
    {
		$viewDatas = self::byTypeLinkId($_type, $_link_id);
		foreach ($viewDatas as $viewData) {
			$viewData->remove();
		}
		return true;
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
    public function getviewZone(): ?array
    {
		return viewZone::byId($this->getviewZone_id());
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
    public function setId($_id): viewData
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    /**
     * @return int
     */
    public function getOrder(): int
    {
		return $this->order;
	}

    /**
     * @param $_order
     * @return $this
     */
    public function setOrder($_order): viewData
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->order,$_order);
		$this->order = $_order;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getviewZone_id() {
		return $this->viewZone_id;
	}

    /**
     * @param $_viewZone_id
     * @return $this
     */
    public function setviewZone_id($_viewZone_id): viewData
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->viewZone_id,$_viewZone_id);
		$this->viewZone_id = $_viewZone_id;
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
    public function setType($_type): viewData
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->type,$_type);
		$this->type = $_type;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getLink_id() {
		return $this->link_id;
	}

    /**
     * @param $_link_id
     * @return $this
     */
    public function setLink_id($_link_id): viewData
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->link_id,$_link_id);
		$this->link_id = $_link_id;
		return $this;
	}

    /**
     * @return bool
     */
    public function getLinkObject(): bool
    {
		$type = $this->getType();
		if (class_exists($type)) {
			return $type::byId($this->getLink_id());
		}
		return false;
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
    public function setConfiguration($_key, $_value): viewData
    {
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
    public function setChanged($_changed): viewData
    {
		$this->_changed = $_changed;
		return $this;
	}

}
