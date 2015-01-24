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

    if (init('action') == 'toHtml') {
        $cmd = cmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd inconnu - Vérifiez l\'id', __FILE__));
        }
        $info_cmd = array();
        $info_cmd['id'] = $cmd->getId();
        $info_cmd['html'] = $cmd->toHtml(init('version'), init('option'), init('cmdColor', null), init('cache', 2));
        ajax::success($info_cmd);
    }

    if (init('action') == 'execCmd') {
        $cmd = cmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd ID inconnu : ', __FILE__) . init('id'));
        }
        $eqLogic = $cmd->getEqLogic();
        if ($cmd->getType() == 'action' && !$eqLogic->hasRight('x')) {
            throw new Exception(__('Vous n\'êtes pas autorisé à faire cette action', __FILE__));
        }
        ajax::success($cmd->execCmd(init('value', null), init('cache', 1)));
    }

    if (init('action') == 'getByObjectNameEqNameCmdName') {
        $cmd = cmd::byObjectNameEqLogicNameCmdName(init('object_name'), init('eqLogic_name'), init('cmd_name'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd inconnu : ', __FILE__) . init('object_name') . '/' . init('eqLogic_name') . '/' . init('cmd_name'));
        }
        ajax::success($cmd->getId());
    }

    if (init('action') == 'getByObjectNameCmdName') {
        $cmd = cmd::byObjectNameCmdName(init('object_name'), init('cmd_name'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd inconnu : ', __FILE__) . init('object_name') . '/' . init('cmd_name'), 9999);
        }
        ajax::success(utils::o2a($cmd));
    }

    if (init('action') == 'byId') {
        $cmd = cmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd inconnu : ', __FILE__) . init('id'), 9999);
        }
        ajax::success(utils::o2a($cmd));
    }

    if (init('action') == 'byHumanName') {
        $cmd_id = cmd::humanReadableToCmd(init('humanName'));
        $cmd = cmd::byId(str_replace('#', '', $cmd_id));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd inconnu : ', __FILE__) . init('humanName'), 9999);
        }
        ajax::success(utils::o2a($cmd));
    }

    if (init('action') == 'usedBy') {
        $cmd = cmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd inconnu : ', __FILE__) . init('id'), 9999);
        }
        $result = $cmd->getUsedBy();
        $return = array('cmd' => array(), 'eqLogic' => array(), 'scenario' => array(), 'interact' => array());
        foreach ($result['cmd'] as $cmd) {
            $info = utils::o2a($cmd);
            $info['humanName'] = $cmd->getHumanName();
            $info['link'] = $cmd->getEqLogic()->getLinkToConfiguration();
            $return['cmd'][] = $info;
        }
        foreach ($result['eqLogic'] as $eqLogic) {
            $info = utils::o2a($eqLogic);
            $info['humanName'] = $eqLogic->getHumanName();
            $info['link'] = $eqLogic->getLinkToConfiguration();
            $return['eqLogic'][] = $info;
        }
        foreach ($result['scenario'] as $scenario) {
            $info = utils::o2a($cmd);
            $info['humanName'] = $scenario->getHumanName();
            $info['link'] = $scenario->getLinkToConfiguration();
            $return['scenario'][] = $info;
        }
        foreach ($result['interact'] as $interact) {
            $info = utils::o2a($interact);
            $info['humanName'] = $interact->getQuery();
            $info['link'] = $interact->getLinkToConfiguration();
            $return['interact'][] = $info;
        }
        ajax::success($return);
    }

    if (init('action') == 'getHumanCmdName') {
        ajax::success(cmd::cmdToHumanReadable('#' . init('id') . '#'));
    }

    if (init('action') == 'byEqLogic') {
        ajax::success(utils::o2a(cmd::byEqLogicId(init('eqLogic_id'))));
    }

    if (init('action') == 'getCmd') {
        $cmd = cmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd ID inconnu : ', __FILE__) . init('id'));
        }
        $return = utils::o2a($cmd);
        $eqLogic = $cmd->getEqLogic();
        $return['eqLogic_name'] = $eqLogic->getName();
        $return['plugin'] = $eqLogic->getEqType_Name();
        if ($eqLogic->getObject_id() > 0) {
            $return['object_name'] = $eqLogic->getObject()->getName();
        }
        ajax::success($return);
    }

    if (init('action') == 'save') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $cmd_ajax = json_decode(init('cmd'), true);
        $cmd = cmd::byId($cmd_ajax['id']);
        if (!is_object($cmd)) {
            $cmd = new cmd();
        }
        utils::a2o($cmd, $cmd_ajax);
        $cmd->save();
        ajax::success();
    }

    if (init('action') == 'changeHistoryPoint') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $history = history::byCmdIdDatetime(init('cmd_id'), init('datetime'));
        if (!is_object($history)) {
            throw new Exception(__('Aucun point ne correspond pour l\'historique : ', __FILE__) . init('cmd_id') . ' - ' . init('datetime'));
        }
        $value = (init('value', null) == '') ? null : init('value', null);
        $history->setValue($value);
        $history->save();
        ajax::success();
    }

    if (init('action') == 'getHistory') {
        $return = array();
        $data = array();
        $dateStart = null;
        $dateEnd = null;
        if (init('dateRange') != '' && init('dateRange') != 'all') {
            if (is_json(init('dateRange'))) {
                $dateRange = json_decode(init('dateRange'), true);
                if (isset($dateRange['start'])) {
                    $dateStart = $dateRange['start'];
                }
                if (isset($dateRange['end'])) {
                    $dateEnd = $dateRange['end'];
                }
            } else {
                $dateEnd = date('Y-m-d H:i:s');
                $dateStart = date('Y-m-d H:i:s', strtotime('- ' . init('dateRange') . ' ' . $dateEnd));
            }
        }
        if (init('dateStart') != '') {
            $dateStart = init('dateStart');
        }
        if (init('dateEnd') != '') {
            $dateEnd = init('dateEnd');
        }
        $return['maxValue'] = '';
        $return['minValue'] = '';
        if ($dateStart == null) {
            $return['dateStart'] = '';
        } else {
            $return['dateStart'] = $dateStart;
        }
        if ($dateEnd == null) {
            $return['dateEnd'] = '';
        } else {
            $return['dateEnd'] = $dateEnd;
        }

        if (is_numeric(init('id'))) {
            $cmd = cmd::byId(init('id'));
            if (!is_object($cmd)) {
                throw new Exception(__('Cmd ID inconnu : ', __FILE__) . init('id'));
            }
            $histories = $cmd->getHistory($dateStart, $dateEnd);
            $return['cmd_name'] = $cmd->getName();
            $return['history_name'] = $cmd->getHumanName();
            $return['unite'] = $cmd->getUnite();
            $return['cmd'] = utils::o2a($cmd);
            $return['eqLogic'] = utils::o2a($cmd->getEqLogic());
            $previsousValue = null;
            $derive = init('derive', $cmd->getDisplay('graphDerive'));
            if (trim($derive) == '') {
                $derive = $cmd->getDisplay('graphDerive');
            }
            foreach ($histories as $history) {
                $info_history = array();
                $info_history[] = floatval(strtotime($history->getDatetime() . " UTC")) * 1000;
                $value = ($history->getValue() === null ) ? null : floatval($history->getValue());
                if ($derive == 1 || $derive == '1') {
                    if ($value !== null && $previsousValue != null) {
                        $value = $value - $previsousValue;
                    } else {
                        $value = null;
                    }
                    $previsousValue = ($history->getValue() === null ) ? null : floatval($history->getValue());
                }
                $info_history[] = $value;
                if (($value != null && $value > $return['maxValue']) || $return['maxValue'] == '') {
                    $return['maxValue'] = $value;
                }
                if (($value != null && $value < $return['minValue']) || $return['minValue'] == '') {
                    $return['minValue'] = $value;
                }
                $data[] = $info_history;
            }
        } else {
            $histories = history::getHistoryFromCalcul(init('id'), $dateStart, $dateEnd, init('allowZero', true));
            if (is_array($histories)) {
                foreach ($histories as $datetime => $value) {
                    $info_history = array();
                    $info_history[] = floatval($datetime) * 1000;
                    $info_history[] = ($value === null ) ? null : floatval($value);
                    if ($value > $return['maxValue'] || $return['maxValue'] == '') {
                        $return['maxValue'] = $value;
                    }
                    if ($value < $return['minValue'] || $return['minValue'] == '') {
                        $return['minValue'] = $value;
                    }
                    $data[] = $info_history;
                }
            }
            $return['cmd_name'] = init('name');
            $return['history_name'] = init('name');
            $return['unite'] = init('unite');
        }
        $last = end($data);
        if ($last[0] < (strtotime('now UTC') * 1000)) {
            $data[] = array((strtotime('now UTC') * 1000), $last[1]);
        }
        $return['data'] = $data;
        ajax::success($return);
    }

    if (init('action') == 'emptyHistory') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
        }
        $cmd = cmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception(__('Cmd ID inconnu : ', __FILE__) . init('id'));
        }
        $cmd->emptyHistory(init('date'));
        ajax::success();
    }


    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
