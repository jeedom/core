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

    if (init('action') == 'getConf') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $plugin = plugin::byId(init('id'));

        $return = utils::o2a($plugin);
        $return['activate'] = $plugin->isActive();
        $return['configurationPath'] = $plugin->getPathToConfigurationById();
        $return['checkVersion'] = version_compare(getVersion('jeedom'), $plugin->getRequire());
        $return['status'] = market::getInfo($plugin->getId());
        ajax::success($return);
    }

    if (init('action') == 'toggle') {
        if (!isConnect('admin')) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        $plugin = plugin::byId(init('id'));
        if (!is_object($plugin)) {
            throw new Exception(__('Plugin introuvable : ', __FILE__) . init('id'));
        }
        $plugin->setIsEnable(init('state'));
        ajax::success();
    }

    if (init('action') == 'all') {
        if (!isConnect()) {
            throw new Exception(__('401 - Accès non autorisé', __FILE__));
        }
        ajax::success(utils::o2a(plugin::listPlugin()));
    }


    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
