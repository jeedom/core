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
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class dataStore {
    /*     * *************************Attributs****************************** */

    private $id;
    private $type;
    private $link_id;
    private $key;
    private $value;

    /*     * ***********************Methode static*************************** */

    public static function byId($_id) {
        $values = array(
            'id' => $_id
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM dataStore
                WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byTypeLinkIdKey($_type, $_link_id, $_key) {
        $values = array(
            'type' => $_type,
            'link_id' => $_link_id,
            'key' => $_key
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM dataStore
                WHERE `type`=:type
                    AND `link_id`=:link_id
                    AND `key`=:key';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byTypeLinkId($_type, $_link_id = '') {
        $values = array(
            'type' => $_type,
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM dataStore
                WHERE type=:type';
        if ($_link_id != '') {
            $values['link_id'] = $_link_id;
            $sql .= ' AND link_id=:link_id';
        }
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function removeByTypeLinkId($_type, $_link_id) {
        $datastores = self::byTypeLinkId($_type, $_link_id);
        foreach ($datastores as $datastore) {
            $datastore->remove();
        }
        return true;
    }

    /*     * *********************Methode d'instance************************* */

    public function preSave() {
        $allowType = array('cmd', 'object', 'eqLogic', 'scenario', 'eqReal');
        if (!in_array($this->getType(), $allowType)) {
            throw new Exception(__('Le type doit être un des suivants : ',__FILE__) . print_r($allowType, true));
        }
        if (!is_numeric($this->getLink_id())) {
            throw new Exception(__('Link_id doit être un chiffre',__FILE__));
        }
        if ($this->getKey() == '') {
            throw new Exception(__('La clef ne peut être vide',__FILE__));
        }
        if ($this->getId() == '') {
            $dataStore = self::byTypeLinkIdKey($this->getType(), $this->getLink_id(), $this->getKey());
            if (is_object($dataStore)) {
                $this->setId($dataStore->getId());
            }
        }
        return true;
    }

    public function save() {
        DB::save($this);
    }

    public function remove() {
        DB::remove($this);
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getLink_id() {
        return $this->link_id;
    }

    public function setLink_id($link_id) {
        $this->link_id = $link_id;
    }

    public function getKey() {
        return $this->key;
    }

    public function setKey($key) {
        $this->key = $key;
    }

    public function getValue($_default = '') {
        if ($this->value == '') {
            return $_default;
        }
        if (is_json($this->value)) {
            return json_decode($this->value, true);
        }
        return $this->value;
    }

    public function setValue($value) {
        if (is_object($value) || is_array($value)) {
            $this->value = json_encode($value,JSON_UNESCAPED_UNICODE);
        } else {
            $this->value = $value;
        }
    }

}

?>
