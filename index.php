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

if (!file_exists(dirname(__FILE__) . '/core/config/common.config.php')) {
	header("location: install/setup.php");
}

if (!isset($_GET['v'])) {
	$useragent = (isset($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : 'none';
	$getParams = (stristr($useragent, "Android") || strpos($useragent, "iPod") || strpos($useragent, "iPhone") || strpos($useragent, "Mobile") || strpos($useragent, "WebOS") || strpos($useragent, "mobile") || strpos($useragent, "hp-tablet"))
	? 'm' : 'd';
	foreach ($_GET AS $var => $value) {
		$getParams .= '&' . $var . '=' . $value;
	}
	header("location: index.php?v=" . trim($getParams, '&'));
}

require_once dirname(__FILE__) . "/core/php/core.inc.php";

function include_authenticated_file($_folder, $_fn, $_type, $_plugin, $alert_folder) {
	include_file('core', 'authentification', 'php');
	try {
		if (!isConnect()) {
			throw new Exception('{{401 - Accès non autorisé}}');
		}
		include_file($_folder, $_fn, $_type, $_plugin);
	} catch (Exception $e) {
		$_folder = 'desktop/modal';
		if (init('plugin') != '') {
			$_folder = 'plugins/' . init('plugin') . '/' . $_folder;
		}
		ob_end_clean(); //Clean pile after expetion (to prevent no-traduction)
		echo '<div class="alert alert-danger div_alert">';
		echo translate::exec(displayExeption($e), $alert_folder);
		echo '</div>';
	}
}
if (isset($_GET['v']) && $_GET['v'] == 'd') {
	if (isset($_GET['modal'])) {
		$alert_folder = 'desktop/modal/' . init('modal') . '.php';
		if (init('plugin') != '') {
			$alert_folder = 'plugins/' . init('plugin') . '/' . $alert_folder;
		}
		include_authenticated_file('desktop', init('modal'), 'modal', init('plugin'), $alert_folder);
	} elseif (isset($_GET['configure'])) {
		$alert_folder = 'plugin_info/configure.php';
		if (init('plugin') != '') {
			$alert_folder = 'plugins/' . init('plugin') . '/' . $alert_folder;
		}
		include_authenticated_file('plugin_info', 'configuration', 'configuration', init('plugin'), $alert_folder);
	} elseif (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
		$alert_folder = 'desktop/php/' . init('modal') . '.php';
		if (init('m') != '') {
			$_folder = 'plugins/' . init('m') . '/' . $_folder;
		}
		include_authenticated_file('desktop', init('p'), 'php', init('m'), $alert_folder);
	} else {
		include_file('desktop', 'index', 'php');
	}
} elseif (isset($_GET['v']) && $_GET['v'] == 'm') {
	$_folder = 'mobile';
	$_fn = 'index';
	$_type = 'html';
	$_plugin = '';
	if (isset($_GET['modal'])) {
		$_fn = init('modal');
		$_type = 'modal';
		$_plugin = init('plugin');
	} elseif (isset($_GET['p']) && isset($_GET['ajax'])) {
		$_fn = $_GET['p'];
		$_plugin = isset($_GET['m']) ? $_GET['m'] : $_plugin;
	}
	include_file($_folder, $_fn, $_type, $_plugin);
} else {
	echo "Erreur: veuillez contacter l'administrateur";
}
