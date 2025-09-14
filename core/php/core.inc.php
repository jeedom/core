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
require_once __DIR__ . '/../config/common.config.php';
require_once __DIR__ . '/utils.inc.php';
include_file('core', 'jeedom', 'config');
include_file('core', 'compatibility', 'config');

try {
	$configs = config::byKeys(array('timezone', 'log::level'));
	if (isset($configs['timezone'])) {
		date_default_timezone_set($configs['timezone']);
	}
} catch (Exception $e) {
} catch (Error $e) {
}

try {
	if (isset($configs['log::level'])) {
		log::define_error_reporting($configs['log::level']);
	}
} catch (Exception $e) {
} catch (Error $e) {
}

function jeedomAutoload($_classname) {
	/* core class always in /core/class : */
	$path = __DIR__ . "/../../core/class/$_classname.class.php";
	if (file_exists($path)) {
		include_file('core', $_classname, 'class');
	} else if (substr($_classname, 0, 4) === 'com_') {
		/* class com_$1 in /core/com/$1.com.php */
		include_file('core', substr($_classname, 4), 'com');
	} else if (substr($_classname, 0, 5) === 'repo_') {
		/* class repo_$1 in /core/repo/$1.repo.php */
		include_file('core', substr($_classname, 5), 'repo');
	} else if (strpos($_classname, '\\') === false && strpos($_classname, '/') === false) {
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
			} catch (Exception $e) {
				
			} catch (Error $e) {
				
			}
		}
	}
}

spl_autoload_register('jeedomAutoload', true, true);
