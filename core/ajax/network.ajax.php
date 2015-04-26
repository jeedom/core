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
	require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'restartNgrok') {
		config::save('market::allowDNS', 1);
		if (network::ngrok_run()) {
			network::ngrok_stop();
		}
		if (network::ngrok_run('tcp', 22, 'ssh')) {
			network::ngrok_stop('tcp', 22, 'ssh');
		}
		network::ngrok_start();
		if (config::byKey('market::redirectSSH') == 1) {
			network::ngrok_start('tcp', 22, 'ssh');
		}
		ajax::success();
	}

	if (init('action') == 'stopNgrok') {
		config::save('market::allowDNS', 0);
		network::ngrok_stop();
		if (config::byKey('market::redirectSSH') == 1) {
			network::ngrok_stop('tcp', 22, 'ssh');
		}
		ajax::success();
	}

	if (init('action') == 'listWifi') {
		ajax::success(network::listWifi(init('rescan')));
	}

	if (init('action') == 'connectionState') {
		ajax::success(network::connectionState());
	}

	if (init('action') == 'connectToWireless') {
		config::save('network::wifi::enable', 1);
		ajax::success(network::connectToWireless());
	}

	if (init('action') == 'disconnectFromWireless') {
		config::save('network::wifi::enable', 0);
		ajax::success(network::disconnectFromWireless());
	}

	if (init('action') == 'setFixIP') {
		ajax::success(network::setFixIP());
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
