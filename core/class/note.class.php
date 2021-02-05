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

class note {
	/*     * *************************Attributs****************************** */

	private $id;
	private $name;
	private $text;
	private $_changed = false;

	/*     * ***********************Methode static*************************** */

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
		FROM note
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
		FROM note
		ORDER BY name';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}
	/*     * *********************Methode d'instance************************* */

    /**
     * @throws Exception
     */
    public function preSave() {
		if (trim($this->getName()) == '') {
			throw new Exception(__('Le nom de la note ne peut être vide', __FILE__));
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
     * @param $_search
     * @return array|null
     * @throws ReflectionException
     */
    public static function searchByString($_search): ?array
    {
		$values = array(
				'search' => '%'.$_search.'%'
			);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
			FROM note
			WHERE name LIKE :search or text LIKE :search';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
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
    public function getText() {
		return $this->text;
	}

    /**
     * @param $_id
     * @return $this
     */
    public function setId($_id): note
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->id,$_id);
		$this->id = $_id;
		return $this;
	}

    /**
     * @param $_name
     * @return $this
     */
    public function setName($_name): note
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->name,$_name);
		$this->name = $_name;
		return $this;
	}

    /**
     * @param $_text
     * @return $this
     */
    public function setText($_text): note
    {
		$this->_changed = utils::attrChanged($this->_changed,$this->text,$_text);
		$this->text = $_text;
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
    public function setChanged($_changed): note
    {
		$this->_changed = $_changed;
		return $this;
	}

}
