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

class eqReal {
    /*     * *************************Attributs****************************** */

    protected $id;
    protected $logicalId = '';
    protected $name;
    protected $type;
    protected $cat;
    protected $configuration;

    /*     * ***********************Méthodes statiques*************************** */

    public function getTableName() {
        return 'eqReal';
    }

    private static function getClass($_id) {
        if (get_called_class() != __CLASS__) {
            return get_called_class();
        }
        $values = array(
            'id' => $_id
        );
        $sql = 'SELECT plugin,isEnable
                FROM eqLogic
                WHERE eqReal_id=:id';
        $result = \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW);
        $eqTyme_name = $result['plugin'];
        if ($result['isEnable'] == 0) {
            try {
                $plugin = null;
                if ($eqTyme_name != '') {
                    $plugin = \plugin::byId($eqTyme_name);
                }
                if (!is_object($plugin) || $plugin->isActive() == 0) {
                    return __CLASS__;
                }
            } catch (\Exception $e) {
                return __CLASS__;
            }
        }
        if (class_exists($eqTyme_name)) {
            if (method_exists($eqTyme_name, 'getClassCmd')) {
                return $eqTyme_name::getClassCmd();
            }
        }
        if (class_exists($eqTyme_name . 'Real')) {
            return $eqTyme_name . 'Real';
        }
        return __CLASS__;
    }

    public static function byId($_id) {
        $values = array(
            'id' => $_id
        );
        $sql = 'SELECT ' . \DB::buildField(__CLASS__) . '
                FROM eqReal
                WHERE id=:id';
        $class = self::getClass($_id);
        return \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, $class);
    }

    public static function byLogicalId($_logicalId, $_cat) {
        $values = array(
            'logicalId' => $_logicalId,
            'cat' => $_cat
        );
        $sql = 'SELECT id
                FROM eqReal
                WHERE logicalId=:logicalId
                    AND cat=:cat';
        $results = \DB::Prepare($sql, $values, \DB::FETCH_TYPE_ALL);
        $return = array();
        foreach ($results as $result) {
            $return[] = self::byId($result['id']);
        }
        return $return;
    }

    /*     * *********************Méthodes d'instance************************* */

    public function remove() {
        foreach ($this->getEqLogic() as $eqLogic) {
            $eqLogic->remove();
        }
        \dataStore::removeByTypeLinkId('eqReal', $this->getId());
        return \DB::remove($this);
    }

    public function save() {
        if ($this->getName() == '') {
            throw new \Exception(__('Le nom de l\'équipement réel ne peut pas être vide', __FILE__));
        }
        return \DB::save($this);
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getEqLogic() {
        return eqLogic::byEqRealId($this->id);
    }

    public function getId() {
        return $this->id;
    }

    public function getLogicalId() {
        return $this->logicalId;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getCat() {
        return $this->cat;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setLogicalId($logicalId) {
        $this->logicalId = $logicalId;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setCat($cat) {
        $this->cat = $cat;
        return $this;
    }

    public function getConfiguration($_key = '', $_default = '') {
        return utils::getJsonAttr($this->configuration, $_key, $_default);
    }

    public function setConfiguration($_key, $_value) {
        $this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
    }

}

?>
