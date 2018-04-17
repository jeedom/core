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
require_once dirname(__FILE__) . '/../php/utils.inc.php';
$includes_js = array();
$includes_js[] = array('folder' => 'core', 'fn' => 'jeedom');
$includes_js[] = array('folder' => 'core', 'fn' => 'jeedom');
$includes_js[] = array('folder' => 'core', 'fn' => 'private');
$includes_js[] = array('folder' => 'core', 'fn' => 'eqLogic');
$includes_js[] = array('folder' => 'core', 'fn' => 'cmd');
$includes_js[] = array('folder' => 'core', 'fn' => 'object');
$includes_js[] = array('folder' => 'core', 'fn' => 'scenario');
$includes_js[] = array('folder' => 'core', 'fn' => 'plugin');
$includes_js[] = array('folder' => 'core', 'fn' => 'message');
$includes_js[] = array('folder' => 'core', 'fn' => 'view');
$includes_js[] = array('folder' => 'core', 'fn' => 'config');
$includes_js[] = array('folder' => 'core', 'fn' => 'history');
$includes_js[] = array('folder' => 'core', 'fn' => 'cron');
$includes_js[] = array('folder' => 'core', 'fn' => 'security');
$includes_js[] = array('folder' => 'core', 'fn' => 'update');
$includes_js[] = array('folder' => 'core', 'fn' => 'user');
$includes_js[] = array('folder' => 'core', 'fn' => 'backup');
$includes_js[] = array('folder' => 'core', 'fn' => 'interact');
$includes_js[] = array('folder' => 'core', 'fn' => 'plan');
$includes_js[] = array('folder' => 'core', 'fn' => 'plan3d');
$includes_js[] = array('folder' => 'core', 'fn' => 'log');
$includes_js[] = array('folder' => 'core', 'fn' => 'repo');
$includes_js[] = array('folder' => 'core', 'fn' => 'network');
$includes_js[] = array('folder' => 'core', 'fn' => 'dataStore');
$includes_js[] = array('folder' => 'core', 'fn' => 'cache');
$includes_js[] = array('folder' => 'core', 'fn' => 'report');
$includes_js[] = array('folder' => 'core', 'fn' => 'note');
include_file($includes_js, null, 'class.js');
