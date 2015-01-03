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

class view {
    /*     * *************************Attributs****************************** */

    private $id;
    private $name;

    /*     * ***********************Méthodes statiques*************************** */

    public static function all() {
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM view';
        return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
    }

    public static function byId($_id) {
        $value = array(
            'id' => $_id,
        );
        $sql = 'SELECT ' . DB::buildField(__CLASS__) . '
                FROM view
                WHERE id=:id';
        return DB::Prepare($sql, $value, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
    }

    /*     * *********************Méthodes d'instance************************* */

    public function presave() {
        if ($this->getName() == '') {
            throw new Exception('Le nom de la vue ne peut pas être vide');
        }
    }

    public function save() {
        return DB::save($this);
    }

    public function remove() {
        return DB::remove($this);
    }

    public function getviewZone() {
        return viewZone::byView($this->getId());
    }

    public function removeviewZone() {
        return viewZone::removeByViewId($this->getId());
    }

    public function toAjax() {
        $return = utils::o2a($this);
        $return['viewZone'] = array();
        foreach ($this->getViewZone() as $viewZone) {
            $viewZone_info = utils::o2a($viewZone);
            $viewZone_info['viewData'] = array();
            foreach ($viewZone->getViewData() as $viewData) {
                $viewData_info = utils::o2a($viewData);
                $viewData_info['name'] = '';
                switch ($viewData->getType()) {
                    case 'cmd':
                        $cmd = $viewData->getLinkObject();
                        if (is_object($cmd)) {
                            $viewData_info['type'] = 'cmd';
                            $viewData_info['name'] = $cmd->getHumanName();
                            $viewData_info['id'] = $cmd->getId();
                            $viewData_info['html'] = $cmd->toHtml(init('version', 'dashboard'));
                        }
                        break;
                    case 'eqLogic':
                        $eqLogic = $viewData->getLinkObject();
                        if (is_object($eqLogic)) {
                            $viewData_info['type'] = 'eqLogic';
                            $viewData_info['name'] = $eqLogic->getHumanName();
                            $viewData_info['id'] = $eqLogic->getId();
                            $viewData_info['html'] = $eqLogic->toHtml(init('version', 'dashboard'));
                        }
                        break;
                    case 'scenario':
                        $scenario = $viewData->getLinkObject();
                        if (is_object($scenario)) {
                            $viewData_info['type'] = 'scenario';
                            $viewData_info['name'] = $scenario->getHumanName();
                            $viewData_info['id'] = $scenario->getId();
                            $viewData_info['html'] = $scenario->toHtml(init('version', 'dashboard'));
                        }
                        break;
                }
                $viewZone_info['viewData'][] = $viewData_info;
            }
            $return['viewZone'][] = $viewZone_info;
        }
        return $return;
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

}

?>
