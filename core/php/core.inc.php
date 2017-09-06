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
require_once dirname(__FILE__) . '/../../vendor/autoload.php';
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
include_file('core', 'log', 'class');

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

try {
	$configs = config::byKeys(array('timezone', 'log::level'));
	if (isset($configs['timezone'])) {
		date_default_timezone_set($configs['timezone']);
	} else {
		date_default_timezone_set('Europe/Brussels');
	}
} catch (Exception $e) {

}

try {
	if (isset($configs['log::level'])) {
		log::define_error_reporting($configs['log::level']);
	}
} catch (Exception $e) {

}

function jeedomCoreAutoload($classname) {
	try {
		include_file('core', $classname, 'class');
	} catch (Exception $e) {

	}
}

function jeedomPluginAutoload($_classname) {
	$classname = str_replace(array('Real', 'Cmd'), '', $_classname);
	$plugin_active = config::byKey('active', $classname, null);
	if ($plugin_active === null || $plugin_active == '') {
		$classname = explode('_', $classname)[0];
		$plugin_active = config::byKey('active', $classname, null);
	}
	try {
		if ($plugin_active == 1) {
			include_file('core', $classname, 'class', $classname);
		}
	} catch (Exception $e) {
	}
}

function jeedomOtherAutoload($classname) {
	try {
		include_file('core', substr($classname, 4), 'com');
		return;
	} catch (Exception $e) {

	}
	try {
		include_file('core', substr($classname, 5), 'repo');
		return;
	} catch (Exception $e) {

	}
}
spl_autoload_register('jeedomOtherAutoload', true, true);
spl_autoload_register('jeedomPluginAutoload', true, true);
spl_autoload_register('jeedomCoreAutoload', true, true);
