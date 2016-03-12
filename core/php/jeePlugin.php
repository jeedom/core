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

if (php_sapi_name() != 'cli' || isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['argc'])) {
	header("Status: 404 Not Found");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}

require_once dirname(__FILE__) . "/core.inc.php";

if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_GET[$argList[0]] = $argList[1];
		}
	}
}
try {
	set_time_limit(config::byKey('maxExecTimeScript', 10));

	$plugin_id = init('plugin_id');
	if ($plugin_id == '') {
		throw new Exception(__('Le plugin ID ne peut être vide', __FILE__));
	}
	$plugin = plugin::byId($plugin_id);
	if (!is_object($plugin)) {
		throw new Exception(__('Plugin non trouvé : ', __FILE__) . init('plugin_id'));
	}
	$function = init('function');
	if ($function == '') {
		throw new Exception(__('La fonction ne peut être vide', __FILE__));
	}
	if (init('callInstallFunction', 0) == 1) {
		$plugin->callInstallFunction($function, true);
	} else {
		if (!class_exists($plugin_id) || !method_exists($plugin_id, $function)) {
			throw new Exception(__('Il n\'existe aucune méthode : ', __FILE__) . $plugin_id . '::' . $function);
		}
		$plugin_id::$function();
	}
} catch (Exception $e) {
	log::add(init('plugin_id', 'plugin'), 'error', $e->getMessage());
	die($e->getMessage());
}
?>


