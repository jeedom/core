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
  header("Statut: 404 Page non trouvée");
  header('HTTP/1.0 404 Not Found');
  $_SERVER['REDIRECT_STATUS'] = 404;
  echo "<h1>404 Non trouvé</h1>";
  echo "La page que vous demandez ne peut être trouvée.";
  exit();
}

if (isset($argv)) {
  foreach ($argv as $arg) {
    $argList = explode('=', $arg);
    if (isset($argList[0]) && isset($argList[1])) {
      $_GET[$argList[0]] = $argList[1];
    }
  }
}
