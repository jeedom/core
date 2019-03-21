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
require_once __DIR__ . '/../core/config/common.config.php';
require_once __DIR__ . '/../core/class/DB.class.php';
echo "[START CHECK AND FIX DB]\n";
try {
  DB::compareAndFix(json_decode(file_get_contents(__DIR__.'/database.json'),true),'all',true);
} catch (\Exception $e) {
  echo "***ERREUR*** " . $ex->getMessage() . "\n";
}
echo "[END CHECK AND FIX DB]\n";
?>
