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
require_once __DIR__ . '/utils.inc.php';

$root_dir = __DIR__ . '/../../data/fonts/';
foreach (ls($root_dir, '*') as $dir) {
	if (is_dir($root_dir . $dir) && file_exists($root_dir . $dir . '/style.css')) {
		echo '<link rel="stylesheet" href="data/fonts/' . $dir . 'style.css?md5=' . md5($root_dir . $dir . '/style.css') . '">' . "\n";
	}
}

echo '<link rel="stylesheet" href="3rdparty/font-awesome5/css/all.min.css">' . "\n";
echo '<link rel="stylesheet" href="core/css/icon/icons.css">' . "\n";
