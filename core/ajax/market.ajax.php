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

    if (init('action') == 'install') {
        $market = market::byId(init('id'));
        if (!is_object($market)) {
            throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . init('id'));
        }
        $market->install(init('version', 'stable'));
        ajax::success();
    }

    if (init('action') == 'remove') {
        $market = market::byId(init('id'));
        if (!is_object($market)) {
            throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . init('id'));
        }
        $market->remove();
        ajax::success();
    }

    if (init('action') == 'save') {
        $market_ajax = json_decode(init('market'), true);
        try {
            $market = market::byId($market_ajax['id']);
        } catch (Exception $e) {
            $market = new market();
        }
        if (isset($market_ajax['rating'])) {
            unset($market_ajax['rating']);
        }
        utils::a2o($market, $market_ajax);
        $market->save();
        ajax::success();
    }

    if (init('action') == 'getInfo') {
        ajax::success(market::getInfo(init('logicalId')));
    }

    if (init('action') == 'test') {
        ajax::success(market::test());
    }

    if (init('action') == 'setRating') {
        $market = market::byId(init('id'));
        if (!is_object($market)) {
            throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . init('id'));
        }
        $market->setRating(init('rating'));
        ajax::success();
    }

    if (init('action') == 'setComment') {
        $market = market::byId(init('id'));
        if (!is_object($market)) {
            throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . init('id'));
        }
        $market->setComment(init('comment', null), init('order', null));
        ajax::success();
    }

    if (init('action') == 'sendReportBug') {
        $ticket = json_decode(init('ticket'), true);
        market::saveTicket($ticket);
        ajax::success(array('url' => config::byKey('market::address') . '/index.php?v=d&p=ticket'));
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
