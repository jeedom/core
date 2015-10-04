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

class scenarioSubElement {
    /*     * *************************Attributs****************************** */

    private $id;
    private $name;
    private $scenarioElement_id;
    private $type;
    private $subtype;
    private $options;
    private $order;
    private $_expression;

    /*     * ***********************Methode static*************************** */

    public static function byId($_id) {
        $values = array(
            'id' => $_id
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '  
                FROM ' . __CLASS__ . ' 
                WHERE id=:id';
        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byScenarioElementId($_scenarioElementId, $_type = '') {
        $values = array(
            'scenarioElement_id' => $_scenarioElementId
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '  
                FROM ' . __CLASS__ . ' 
                WHERE scenarioElement_id=:scenarioElement_id ';
        if ($_type != '') {
            $values['type'] = $_type;
            $sql .= ' AND type=:type ';
            return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
        }
        $sql .= ' ORDER BY `order`';

        return DB::Prepare($sql, $values, DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    /*     * *********************Methode d'instance************************* */

    public function execute(&$_scenario) {
        if ($this->getSubtype() == 'action') {
            $_scenario->setLog(__('Exécution du sous-élément de type [action] : ', __FILE__) . $this->getType());
            $return = true;
            foreach ($this->getExpression() as $expression) {
                $return = $expression->execute($_scenario);
            }
            return $return;
        }
        if ($this->getSubtype() == 'condition') {
            $_scenario->setLog(__('Exécution du sous-élément de type [condition] : ', __FILE__) . $this->getType());
            foreach ($this->getExpression() as $expression) {
                return $expression->execute($_scenario);
            }
        }
    }

    public function save() {
        DB::save($this);
    }

    public function remove() {
        foreach ($this->getExpression() as $expression) {
            $expression->remove();
        }
        DB::remove($this);
    }

    public function getExpression() {
        if (count($this->_expression) > 0) {
            return $this->_expression;
        }
        $this->_expression = scenarioExpression::byscenarioSubElementId($this->getId());
        return $this->_expression;
    }

    public function getAllId() {
        $return = array(
            'element' => array(),
            'subelement' => array($this->getId()),
            'expression' => array(),
        );
        foreach ($this->getExpression() as $expression) {
            $result = $expression->getAllId();
            $return['element'] = array_merge($return['element'], $result['element']);
            $return['subelement'] = array_merge($return['subelement'], $result['subelement']);
            $return['expression'] = array_merge($return['expression'], $result['expression']);
        }
        return $return;
    }

    public function copy($_scenarioElement_id) {
        $subElementCopy = clone $this;
        $subElementCopy->setId('');
        $subElementCopy->setScenarioElement_id($_scenarioElement_id);
        $subElementCopy->save();
        foreach ($this->getExpression() as $expression) {
            $expression->copy($subElementCopy->getId());
        }
        return $subElementCopy->getId();
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        if (trim($id) == '') {
            $id = null;
        }
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getScenarioElement_id() {
        return $this->scenarioElement_id;
    }

    public function getElement() {
        return scenarioElement::byId($this->getScenarioElement_id());
    }

    public function setScenarioElement_id($scenarioElement_id) {
        $this->scenarioElement_id = $scenarioElement_id;
    }

    public function getOptions($_key = '', $_default = '') {
        return utils::getJsonAttr($this->options, $_key, $_default);
    }

    public function setOptions($_key, $_value) {
        $this->options = utils::setJsonAttr($this->options, $_key, $_value);
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }

    public function getSubtype() {
        return $this->subtype;
    }

    public function setSubtype($subtype) {
        $this->subtype = $subtype;
    }

}

?>
