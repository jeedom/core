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

class planHeader {
    /*     * *************************Attributs****************************** */

    private $id;
    private $name;
    private $image;
    private $configuration;

    /*     * ***********************Methode static*************************** */

    public static function byId($_id) {
        $values = array(
            'id' => $_id
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

    /*     * *********************Methode d'instance************************* */

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
        return $planHeaderCopy;
    }

    public function preSave() {
        if (trim($this->getName()) == '') {
            throw new Exception(__('Le nom du plan ne peut être vide'));
        }
    }

    public function preInsert() {
        if ($this->setConfiguration('desktopSizeX') == '') {
            $this->setConfiguration('desktopSizeX', 500);
        }
        if ($this->setConfiguration('desktopSizeY') == '') {
            $this->setConfiguration('desktopSizeY', 500);
        }
        if ($this->setConfiguration('tabletSizeX') == '') {
            $this->setConfiguration('tabletSizeX', 500);
        }
        if ($this->setConfiguration('tabletSizeY') == '') {
            $this->setConfiguration('tabletSizeY', 500);
        }
        if ($this->setConfiguration('mobileSizeX') == '') {
            $this->setConfiguration('mobileSizeX', 500);
        }
        if ($this->setConfiguration('mobileSizeY') == '') {
            $this->setConfiguration('mobileSizeY', 500);
        }
    }

    public function save() {
        DB::save($this);
    }

    public function remove() {
        DB::remove($this);
    }

    public function displayImage() {
        $dir = dirname(__FILE__) . '/../../core/img/plan';
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        if ($this->getImage('data') == '') {
            return '';
        }
        $filename = sha1($this->getImage('data')) . '.' . $this->getImage('type');
        $filepath = $dir . '/' . $filename;

        if (!file_exists($filepath)) {
            file_put_contents($filepath, base64_decode($this->getImage('data')));
        }
        $size = $this->getImage('size');
        return '<img src="core/img/plan/' . $filename . '" data-sixe_y="' . $size[1] . '" data-sixe_x="' . $size[0] . '">';
    }

    public function getPlan() {
        return plan::byPlanHeaderId($this->getId());
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getImage($_key = '', $_default = '') {
        return utils::getJsonAttr($this->image, $_key, $_default);
    }

    public function setImage($_key, $_value) {
        $this->image = utils::setJsonAttr($this->image, $_key, $_value);
    }

    public function getConfiguration($_key = '', $_default = '') {
        return utils::getJsonAttr($this->configuration, $_key, $_default);
    }

    public function setConfiguration($_key, $_value) {
        $this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
    }

}

?>
