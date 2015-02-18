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

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
  
    if (init('action') == 'remove') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('jeeNetwork inconnu verifié l\'id', __FILE__));
        }
        $jeeNetwork->remove();
        ajax::success();
    }

    if (init('action') == 'byId') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success(utils::o2a($jeeNetwork));
    }

    if (init('action') == 'haltSystem') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->halt());
    }

    if (init('action') == 'rebootSystem') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->reboot());
    }

    if (init('action') == 'update') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->update());
    }

    if (init('action') == 'checkUpdate') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->checkUpdate());
    }


    if (init('action') == 'getLog') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->getLog(init('log'), init('start', 0), init('nbLine', 3000)));
    }

    if (init('action') == 'emptyLog') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->emptyLog(init('log')));
    }

    if (init('action') == 'removeLog') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->removeLog(init('log')));
    }

    if (init('action') == 'getListLog') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->getListLog());
    }

    if (init('action') == 'removeAllMessage') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->removeAllMessage());
    }

    if (init('action') == 'getMessage') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('Objet inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->getMessage());
    }

    if (init('action') == 'all') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        ajax::success(utils::o2a(jeeNetwork::all()));
    }
    
     if (init('action') == 'restoreLocalBackup') {
        $jeeNetwork = jeeNetwork::byId(init('id'));
        if (!is_object($jeeNetwork)) {
            throw new Exception(__('JeeNetwork inconnu verifié l\'id : ', __FILE__) . init('id'));
        }
        ajax::success($jeeNetwork->restoreLocalBackup(init('backup')));
    }

    if (init('action') == 'save') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $update = true;
        $jeeNetwork_json = json_decode(init('jeeNetwork'), true);
        if (isset($jeeNetwork_json['id'])) {
            $jeeNetwork = jeeNetwork::byId($jeeNetwork_json['id']);
        }
        if (!isset($jeeNetwork) || !is_object($jeeNetwork)) {
            $update = false;
            $jeeNetwork = new jeeNetwork();
        }
        utils::a2o($jeeNetwork, $jeeNetwork_json);
        $jeeNetwork->save();
        if ($update) {
            $jeeNetwork->reload();
        }
        ajax::success(utils::o2a($jeeNetwork));
    }

    if (init('action') == 'changeMode') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        ajax::success(jeeNetwork::changeMode(init('mode')));
    }

    if (init('action') == 'listLocalSlaveBackup') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        if (substr(config::byKey('backup::path'), 0, 1) != '/') {
            $backup_dir = dirname(__FILE__) . '/../../' . config::byKey('backup::path');
        } else {
            $backup_dir = config::byKey('backup::path');
        }
        $backup_dir .= '/slave/';
        $backups = ls($backup_dir, '*.tar.gz', false, array('files', 'quiet', 'datetime_asc'));
        $return = array();
        foreach ($backups as $backup) {
            $return[$backup_dir . '/' . $backup] = $backup;
        }
        ajax::success($return);
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
