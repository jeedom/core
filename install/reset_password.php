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

if (php_sapi_name() != 'cli' || isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['argc'])) {
	header("Statut: 404 Page non trouvée");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Non trouvé</h1>";
	echo "La page que vous demandez ne peut être trouvée.";
	exit();
}
require_once __DIR__ . '/../core/php/core.inc.php';

echo "Reset user password\n";
echo "List of user : \n";
foreach (user::all() as $user) {
  if($user->getLogin() == 'internal_report' || $user->getLogin() == 'jeedom_support'){
    continue;
  }
  echo '- '.$user->getLogin()."\n";
}
echo "Please type login : \n";
$username = trim(fgets(STDIN));
$user = user::byLogin($username);
if(!is_object($user)){
  echo "User $username not found\n";
  die();
}
$password = config::genKey();
$user->setPassword(sha512($password));
$user->save();
echo "Operation successfull, your new password for user ".$user->getLogin()." is ".$password."\n";
die();
