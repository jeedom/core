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
date_default_timezone_set('Europe/Brussels');
require_once __DIR__ . '/../../vendor/autoload.php';
include_file('core', 'jeedom', 'config');

try {
	$configs = config::byKeys(array('timezone', 'log::level'));
	if (isset($configs['timezone'])) {
		date_default_timezone_set($configs['timezone']);
	}
} catch (Throwable $e) {
    log::add('jeedom', 'error', 'Log (level|timezone) configuration failed: ' . $e->getMessage());
}

try {
	if (isset($configs['log::level'])) {
		log::define_error_reporting($configs['log::level']);
	}
} catch (Throwable $e) {
    log::add('jeedom', 'error', 'Log (level|timezone) configuration failed: ' . $e->getMessage());
}

/**
 * Autoload function for specific Jeedom classes
 * this function is called after the default Composer autoloader
 * it will load specific classes such as Cmd, Real, etc.
 * for plugins that do not use namespaces or Composer autoloading
 */
function jeedomAutoload($_classname) {
	if (strpos($_classname, '\\') !== false || strpos($_classname, '/') !== false) {
	    return;
	}
	/* autoload for plugins : no namespace */
	$classname = str_replace(array('Real', 'Cmd'), '', $_classname);
	$plugin_active = config::byKey('active', $classname, null);
	if (($plugin_active === null || $plugin_active == '' || $plugin_active == 0) && strpos($classname, '_') !== false) {
		$classname = explode('_', $classname)[0];
		$plugin_active = config::byKey('active', $classname, null);
	}
	if ($plugin_active == 1) {
		try {
			include_file('core', $classname, 'class', $classname);
		} catch (Throwable $e) {
			log::add('jeedom', 'error', 'Log (level|timezone) configuration failed: ' . $e->getMessage());
		}
	}
}

spl_autoload_register('jeedomAutoload');
