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

require_once dirname(__DIR__) . '/core/php/console.php';
require_once dirname(__DIR__) . '/core/config/common.config.php';
require_once dirname(__DIR__) . '/core/class/DB.class.php';

echo '[START CHECK AND FIX DB]'.PHP_EOL;
try {
    DB::compareAndFix(json_decode(file_get_contents(__DIR__.'/database.json'),true),'all',true);
} catch (\Exception $exception) {
    echo '***ERREUR*** ' . $exception->getMessage() . "\n";
}
echo '[END CHECK AND FIX DB]'.PHP_EOL;
