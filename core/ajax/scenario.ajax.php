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

try {
    require_once(dirname(__FILE__) . '/../../core/php/core.inc.php');
    include_file('core', 'authentification', 'php');

    if (!isConnect()) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    if (init('action') == 'changeState') {
        $scenario = scenario::byId(init('id'));
        if (!is_object($scenario)) {
            throw new Exception(__('Scénario ID inconnu : ', __FILE__) . init('id'));
        }
        if (!$scenario->hasRight('x')) {
            throw new Exception(__('Vous n\'etês pas autorisé à faire cette action', __FILE__));
        }
        switch (init('state')) {
            case 'start':
                $scenario->launch(init('force', false), 'Scenario lance manuellement par l\'utilisateur');
                break;
            case 'stop':
                $scenario->stop();
                break;
            case 'deactivate':
                $scenario->setIsActive(0);
                $scenario->save();
                break;
            case 'activate':
                $scenario->setIsActive(1);
                $scenario->save();
                break;
        }
        ajax::success();
    }

    if (init('action') == 'listScenarioHtml') {
        $return = array();
        foreach (scenario::all() as $scenario) {
            if ($scenario->getIsVisible() == 1) {
                $return[] = $scenario->toHtml(init('version'));
            }
        }
        ajax::success($return);
    }

    if (init('action') == 'getTemplate') {
        ajax::success(scenario::getTemplate());
    }

    if (init('action') == 'convertToTemplate') {
        $scenario = scenario::byId(init('id'));
        if (!is_object($scenario)) {
            throw new Exception(__('Scénario ID inconnu : ', __FILE__) . init('id'));
        }
        $path = dirname(__FILE__) . '/../config/scenario';
        if (init('template') == '') {
            if ($scenario->getGroup() == '') {
                $name = config::genKey(5) . '.' . $scenario->getName() . '.json';
            } else {
                $name = config::genKey(5) . '.' . $scenario->getGroup() . '_' . $scenario->getName() . '.json';
            }
        } else {
            $name = init('template');
        }
        file_put_contents($path . '/' . $name, json_encode($scenario->export('array'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        ajax::success();
    }

    if (init('action') == 'removeTemplate') {
        $path = dirname(__FILE__) . '/../config/scenario';
        if (file_exists($path . '/' . init('template'))) {
            unlink($path . '/' . init('template'));
        }
        ajax::success();
    }

    if (init('action') == 'loadTemplateDiff') {
        $path = dirname(__FILE__) . '/../config/scenario';
        if (!file_exists($path . '/' . init('template'))) {
            throw new Exception('Fichier non trouvé : ' . $path . '/' . init('template'));
        }
        $return = array();
        foreach (preg_split("/((\r?\n)|(\r\n?))/", file_get_contents($path . '/' . init('template'))) as $line) {
            preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $line, $matches, PREG_SET_ORDER);
            if (count($matches) > 0) {
                foreach ($matches as $match) {
                    $return[$match[0]] = $match[0];
                }
            }
        }
        ajax::success($return);
    }

    if (init('action') == 'applyTemplate') {
        $path = dirname(__FILE__) . '/../config/scenario';
        if (!file_exists($path . '/' . init('template'))) {
            throw new Exception('Fichier non trouvé : ' . $path . '/' . init('template'));
        }
        foreach (json_decode(init('convert'), true) as $value) {
            if (trim($value['end']) == '') {
                throw new Exception(__('La convertion suivante ne peut être vide : ', __FILE__) . $value['begin']);
            }
            $converts[$value['begin']] = $value['end'];
        }
        $content = str_replace(array_keys($converts), $converts, file_get_contents($path . '/' . init('template')));
        $scenario_ajax = json_decode($content, true);
        if (isset($scenario_ajax['name'])) {
            unset($scenario_ajax['name']);
        }
        if (isset($scenario_ajax['group'])) {
            unset($scenario_ajax['group']);
        }
        $scenario_db = scenario::byId(init('id'));
        if (!is_object($scenario_db)) {
            throw new Exception(__('Scénario ID inconnu : ', __FILE__) . init('id'));
        }
        if (!$scenario_db->hasRight('w')) {
            throw new Exception(__('Vous n\'etês pas autorisé à faire cette action', __FILE__));
        }
        $scenario_db->setTrigger(array());
        $scenario_db->setSchedule(array());
        utils::a2o($scenario_db, $scenario_ajax);
        $scenario_db->save();
        $scenario_element_list = array();
        if (isset($scenario_ajax['elements'])) {
            foreach ($scenario_ajax['elements'] as $element_ajax) {
                $scenario_element_list[] = scenarioElement::saveAjaxElement($element_ajax);
            }
            $scenario_db->setScenarioElement($scenario_element_list);
        }
        $scenario_db->save();
        ajax::success();
    }

    if (init('action') == 'all') {
        $scenarios = scenario::all();
        $return = array();
        foreach ($scenarios as $scenario) {
            $info_scenario = utils::o2a($scenario);
            $info_scenario['humanName'] = $scenario->getHumanName();
            $return[] = $info_scenario;
        }
        ajax::success($return);
    }

    if (init('action') == 'autoCompleteGroup') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $return = array();
        foreach (scenario::listGroup(init('term')) as $group) {
            $return[] = $group['group'];
        }
        ajax::success($return);
    }

    if (init('action') == 'toHtml') {
        if (init('id') == 'all' || is_json(init('id'))) {
            if (is_json(init('id'))) {
                $scenario_ajax = json_decode(init('id'), true);
                $scenarios = array();
                foreach ($scenario_ajax as $id) {
                    $scenarios[] = scenario::byId($id);
                }
            } else {
                $scenarios = scenario::all();
            }
            $return = array();
            foreach ($scenarios as $scenario) {
                if ($scenario->getIsVisible() == 1) {
                    $return[$scenario->getId()] = $scenario->toHtml(init('version'));
                }
            }
            ajax::success($return);
        } else {
            $scenario = scenario::byId(init('id'));
            if (is_object($scenario)) {
                ajax::success($scenario->toHtml(init('version')));
            }
        }
        ajax::success();
    }

    if (init('action') == 'remove') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $scenario = scenario::byId(init('id'));
        if (!is_object($scenario)) {
            throw new Exception(__('Scénario ID inconnu', __FILE__));
        }
        if (!$scenario->hasRight('w')) {
            throw new Exception(__('Vous n\'etês pas autorisé à faire cette action', __FILE__));
        }
        $scenario->remove();
        ajax::success();
    }

    if (init('action') == 'emptyLog') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $scenario = scenario::byId(init('id'));
        if (!is_object($scenario)) {
            throw new Exception(__('Scénario ID inconnu', __FILE__));
        }
        if (!$scenario->hasRight('w')) {
            throw new Exception(__('Vous n\'etês pas autorisé à faire cette action', __FILE__));
        }
        if (file_exists(dirname(__FILE__) . '/../../log/scenarioLog/scenario' . $scenario->getId() . '.log')) {
            unlink(dirname(__FILE__) . '/../../log/scenarioLog/scenario' . $scenario->getId() . '.log');
        }
        ajax::success();
    }

    if (init('action') == 'copy') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $scenario = scenario::byId(init('id'));
        if (!is_object($scenario)) {
            throw new Exception(__('Scénario ID inconnu', __FILE__));
        }
        ajax::success(utils::o2a($scenario->copy(init('name'))));
    }

    if (init('action') == 'get') {
        $scenario = scenario::byId(init('id'));
        if (!is_object($scenario)) {
            throw new Exception(__('Scénario ID inconnu', __FILE__));
        }
        $return = utils::o2a($scenario);
        $return['trigger'] = jeedom::toHumanReadable($return['trigger']);
        $return['forecast'] = $scenario->calculateScheduleDate();
        $return['elements'] = array();
        foreach ($scenario->getElement() as $element) {
            $return['elements'][] = $element->getAjaxElement();
        }

        ajax::success($return);
    }

    if (init('action') == 'save') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $scenario_ajax = json_decode(init('scenario'), true);
        if (isset($scenario_ajax['id'])) {
            $scenario_db = scenario::byId($scenario_ajax['id']);
        }
        if (!isset($scenario_db) || !is_object($scenario_db)) {
            $scenario_db = new scenario();
        } else {
            if (!$scenario_db->hasRight('w')) {
                throw new Exception(__('Vous n\'etês pas autorisé à faire cette action', __FILE__));
            }
        }
        $scenario_db->setTrigger(array());
        $scenario_db->setSchedule(array());
        utils::a2o($scenario_db, $scenario_ajax);
        $scenario_db->save();
        $scenario_element_list = array();
        if (isset($scenario_ajax['elements'])) {
            foreach ($scenario_ajax['elements'] as $element_ajax) {
                $scenario_element_list[] = scenarioElement::saveAjaxElement($element_ajax);
            }
            $scenario_db->setScenarioElement($scenario_element_list);
        }
        $scenario_db->save();
        ajax::success(utils::o2a($scenario_db));
    }

    if (init('action') == 'actionToHtml') {
        ajax::success(scenarioExpression::getExpressionOptions(init('expression'), init('option')));
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
