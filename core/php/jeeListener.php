<?php

/** @entrypoint */
/** @console */

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

require_once __DIR__ . '/console.php';

require_once __DIR__ . "/core.inc.php";

$timelimit = 60;
if (config::byKey('maxExecTimeScript', 60) != '') {
	$timelimit = config::byKey('maxExecTimeScript', 60);
}
set_time_limit($timelimit);
if (init('listener_id') == '') {
	foreach (cmd::byValue(init('event_id'), 'info') as $cmd) {
		$cmd->event($cmd->execute(), null, 2);
	}
} else {
	try {
		$listener_id = init('listener_id');
		if ($listener_id == '') {
			throw new Exception(__('Le listener ID ne peut être vide', __FILE__));
		}
		$listener = listener::byId($listener_id);
		if (!is_object($listener)) {
			throw new Exception(__('Listener non trouvé :', __FILE__) . ' ' . $listener_id);
		}
	} catch (Exception $e) {
		log::add(init('plugin_id', 'plugin'), 'error', log::exception($e));
		die(log::exception($e));
	}
	$listener->execute(init('event_id'), trim(init('value'), "'"), trim(init('datetime'), "'"));
}
