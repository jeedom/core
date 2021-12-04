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

$cache = cache::byKey(init('key'))->getValue();
if (!isset($cache['scenarioExpression'])) {
	if ($cache['scenario'] !== null) {
		$cache['scenario']->setLog(__('Lancement en arrière-plan non trouvé :', __FILE__) . ' ' . init('key'));
		$cache['scenario']->persistLog();
	}
	die();
}
if (!isset($cache['scenario'])) {
	$cache['scenario'] = null;
}
cache::byKey(init('key'))->remove();
if ($cache['scenario'] != null) {
	$cache['scenario']->clearLog();
	$cache['scenario']->setLog(__('Lancement en arrière-plan de :', __FILE__) . ' ' . init('key'));
}
$cache['scenarioExpression']->setOptions('background', 0);
$cache['scenarioExpression']->execute($cache['scenario']);
if ($cache['scenario'] != null) {
	$cache['scenario']->persistLog();
}
