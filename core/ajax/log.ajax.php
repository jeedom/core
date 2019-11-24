<?php

/** @entrypoint */
/** @ajax */

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

require_once __DIR__ . '/ajax.handler.inc.php';

ajaxHandle(function ()
{
    ajax::checkAccess('admin');
	if (init('action') == 'clear') {
		unautorizedInDemo();
		log::clear(init('log'));
		return '';
	}

	if (init('action') == 'remove') {
		unautorizedInDemo();
		log::remove(init('log'));
		return '';
	}

	if (init('action') == 'list') {
		return log::liste();
	}

	if (init('action') == 'removeAll') {
		unautorizedInDemo();
		log::removeAll();
		return '';
	}

	if (init('action') == 'get') {
		return log::get(init('log'), init('start', 0), init('nbLine', 99999));
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
});
