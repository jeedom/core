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

class rights {
    /*     * *************************Attributs****************************** */

    private $id;
    private $user_id;
    private $entity;
    private $right;
    private $options;

    /*     * ***********************Methode static*************************** */

    public static function all() {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM `rights`';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byId($_id) {
        $values = array(
            'id' => $_id
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM `rights`
                WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byUserId($_user_id) {
        $values = array(
            'user_id' => $_user_id
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM `rights`
                WHERE user_id=:user_id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byEntity($_entity) {
        $values = array(
            'entity' => $_entity
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM `rights`
                WHERE entity=:entity';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byuserIdAndEntity($_user_id, $_entity) {
        $values = array(
            'user_id' => $_user_id,
            'entity' => $_entity
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM `rights`
                WHERE entity=:entity
                    AND user_id=:user_id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    /*     * *********************Methode d'instance************************* */

    public function remove() {
        DB::remove($this);
    }

    public function save() {
        DB::save($this);
    }

    /*     * **********************Getteur Setteur*************************** */

    function getId() {
        return $this->id;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function getEntity() {
        return $this->entity;
    }

    function getRight() {
        return $this->right;
    }

    function setId($id) {
        if (trim($id) == '') {
            $id = null;
        }
        $this->id = $id;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }

    function setEntity($entity) {
        $this->entity = $entity;
    }

    function setRight($right) {
        $this->right = $right;
    }

    public function getOptions($_key = '', $_default = '') {
        return utils::getJsonAttr($this->options, $_key, $_default);
    }

    public function setOptions($_key, $_value) {
        $this->options = utils::setJsonAttr($this->options, $_key, $_value);
    }

}
?>

