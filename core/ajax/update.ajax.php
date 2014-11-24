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
        throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
    }

    if (init('action') == 'all') {
        ajax::success(utils::o2a(update::all(init('filter'))));
    }

    if (init('action') == 'checkAllUpdate') {
        update::checkAllUpdate();
        ajax::success();
    }

    if (init('action') == 'update') {
        log::clear('update');
        $update = update::byId(init('id'));
        if (!is_object($update)) {
            throw new Exception(__('Aucune correspondance pour l\'ID : ' . init('id'), __FILE__));
        }
        try {
            if ($update->getType() != 'core') {
                log::add('update', 'update', __("[START UPDATE]\n", __FILE__));
            }
            $update->doUpdate();
            if ($update->getType() != 'core') {
                log::add('update', 'update', __("[END UPDATE SUCCESS]\n", __FILE__));
            }
        } catch (Exception $e) {
            if ($update->getType() != 'core') {
                log::add('update', 'update', $e->getMessage());
                log::add('update', 'update', __("[END UPDATE ERROR]\n", __FILE__));
            }
        }
        ajax::success();
    }

    if (init('action') == 'remove') {
        $update = update::byId(init('id'));
        if (!is_object($update)) {
            throw new Exception(__('Aucune correspondance pour l\'ID : ' . init('id'), __FILE__));
        }
        $update->deleteObjet();
        ajax::success();
    }

    if (init('action') == 'updateAll') {
        update::makeUpdateLevel(init('mode'), init('level'));
        ajax::success();
    }

    if (init('action') == 'changeState') {
        $update = update::byId(init('id'));
        if (!is_object($update)) {
            throw new Exception(__('Aucune correspondance pour l\'ID : ' . init('id'), __FILE__));
        }
        if (init('state') == '') {
            throw new Exception(__('Le status ne peut être vide', __FILE__));
        }
        if (init('state') == 'hold') {
            $update->setStatus('hold');
            $update->save();
        } else {
            $update->checkUpdate();
        }
        ajax::success();
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}