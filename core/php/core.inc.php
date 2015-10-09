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
//error_reporting(E_ERROR);
date_default_timezone_set('Europe/Brussels');
require_once dirname(__FILE__) . '/../config/common.config.php';
require_once dirname(__FILE__) . '/../class/DB.class.php';
require_once dirname(__FILE__) . '/../class/config.class.php';
require_once dirname(__FILE__) . '/../class/jeedom.class.php';
require_once dirname(__FILE__) . '/../class/plugin.class.php';
require_once dirname(__FILE__) . '/../class/translate.class.php';
require_once dirname(__FILE__) . '/utils.inc.php';
include_file('core', 'jeedom', 'config');
include_file('core', 'compatibility', 'config');
include_file('core', 'utils', 'class');
try {
	date_default_timezone_set(config::byKey('timezone'));
} catch (Exception $e) {
	date_default_timezone_set('Europe/Brussels');
}

function jeedomCoreAutoload($classname) {
	try {
		include_file('core', $classname, 'class');
	} catch (Exception $e) {

	}
}

function jeedomComAutoload($classname) {
	try {
		include_file('core', substr($classname, 4), 'com');
	} catch (Exception $e) {

	}
}

function jeedomPluginAutoload($classname) {
	$plugin = null;
	try {
		$plugin = plugin::byId($classname);
	} catch (Exception $e) {
		if (!is_object($plugin)) {
			if (strpos($classname, 'Real') !== false) {
				$plugin = plugin::byId(substr($classname, 0, -4));
			}
			if (!is_object($plugin) && strpos($classname, 'Cmd') !== false) {
				$classname = str_replace('Cmd', '', $classname);
				try {
					$plugin = plugin::byId($classname);
				} catch (Exception $e) {
					if (strpos($classname, '_') !== false && strpos($classname, 'com_') === false) {
						$plugin = plugin::byId(substr($classname, 0, strpos($classname, '_')));
					}
				}
			}
			if (!is_object($plugin) && strpos($classname, '_') !== false && strpos($classname, 'com_') === false) {
				$plugin = plugin::byId(substr($classname, 0, strpos($classname, '_')));
			}
		}
	}
	try {
		if (is_object($plugin)) {
			if ($plugin->isActive() == 1) {
				$include = $plugin->getInclude();
				include_file('core', $include['file'], $include['type'], $plugin->getId());
			}
		}
	} catch (Exception $e) {

	}
}

spl_autoload_register('jeedomCoreAutoload', true, true);
spl_autoload_register('jeedomPluginAutoload', true, true);
spl_autoload_register('jeedomComAutoload', true, true);
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
?>
