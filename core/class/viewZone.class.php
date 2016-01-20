<?php

/**
 * Description of viewZone
 *
 * @author Gevrey Loïc 
 */
/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class viewZone {
    /*     * *************************Attributs****************************** */

    private $id;
    private $view_id;
    private $type;
    private $name;
    private $position;
    private $configuration;

    /*     * ***********************Methode static*************************** */

    public static function all() {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewZone';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byId($_id) {
        $value = array(
            'id' => $_id,
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewZone
                WHERE id=:id';
        return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byView($_view_id) {
        $value = array(
            'view_id' => $_view_id,
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM viewZone
                WHERE view_id=:view_id';
        return DB::Prepare($sql, $value, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function removeByViewId($_view_id) {
        $value = array(
            'view_id' => $_view_id,
        );
        $sql = 'DELETE FROM viewZone
                WHERE view_id=:view_id';
        return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW);
    }

    /*     * *********************Methode d'instance************************* */

    public function save() {
        return DB::save($this);
    }

    public function remove() {
        return DB::remove($this);
    }

    public function getviewData() {
        return viewData::byviewZoneId($this->getId());
    }

    public function getView() {
        return view::byId($this->getView_id());
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getView_id() {
        return $this->view_id;
    }

    public function setView_id($view_id) {
        $this->view_id = $view_id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function getConfiguration($_key = '', $_default = '') {
        if ($this->configuration == '') {
            return $_default;
        }
        if (@json_decode($this->configuration, true)) {
            if ($_key == '') {
                return json_decode($this->configuration, true);
            }
            $options = json_decode($this->configuration, true);
            return (isset($options[$_key])) ? $options[$_key] : $_default;
        }
        return $_default;
    }

    public function setConfiguration($_key, $_value) {
        if ($this->configuration == '' || !@json_decode($this->configuration, true)) {
            $this->configuration = json_encode(array($_key => $_value));
        } else {
            $options = json_decode($this->configuration, true);
            $options[$_key] = $_value;
            $this->configuration = json_encode($options);
        }
    }

}

?>
