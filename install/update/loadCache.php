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
set_time_limit(1800);
require_once __DIR__ . '/../core/php/core.inc.php';
try {
    if(file_exists('/tmp/jeedom/cache.json')){
        echo "Save state cache found, load it....";
        $data = json_decode(file_get_contents('/tmp/jeedom/cache.json'),true);
        foreach ($data['cmd'] as $id => $value) {
            $cmd = cmd::byId($id);
            if(is_object($cmd)){
                $cmd->setCache($value);
            }
        }
        foreach ($data['eqLogic'] as $id => $value) {
            $eqLogic = eqLogic::byId($id);
            if(is_object($cmd)){
                $eqLogic->setCache($value);
            }
        }
        unlink('/tmp/jeedom/cache.json');
        echo "OK\n";
    } 
} catch (\Throwable $th) {
    echo 'Error on reload cache : '.$th->getMessage();
}