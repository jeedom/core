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
set_time_limit(1800);
echo "[START INSTALL]\n";
$starttime = strtotime('now');
if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_GET[$argList[0]] = $argList[1];
		}
	}
}

try {
	require_once __DIR__ . '/../core/php/core.inc.php';
	if (count(system::ps('install/install.php', 'sudo')) > 1) {
		echo "Une mise à jour/installation est déjà en cours. Vous devez attendre qu'elle soit finie avant d'en relancer une\n";
		print_r(system::ps('install/install.php', 'sudo'));
		echo "[END INSTALL]\n";
		die();
	}
	echo "****Install jeedom from " . jeedom::version() . " (" . date('Y-m-d H:i:s') . ")****\n";
	/*         * ***************************INSTALLATION************************** */
	if (version_compare(PHP_VERSION, '5.6.0', '<')) {
		throw new Exception('Jeedom nécessite PHP 5.6 ou plus (actuellement : ' . PHP_VERSION . ')');
	}
	echo "\nInstallation de Jeedom " . jeedom::version() . "\n";
	$sql = file_get_contents(__DIR__ . '/install.sql');
	echo "Installation de la base de données...";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
	echo "OK\n";
	echo "Post installation...\n";
	config::save('api', config::genKey());
	require_once __DIR__ . '/consistency.php';
	echo "Ajout de l'utilisateur (admin,admin)\n";
	$user = new user();
	$user->setLogin('admin');
	$user->setPassword(sha512('admin'));
	$user->setProfils('admin');
	$user->save();
	config::save('log::level', 400);
	echo "OK\n";
	config::save('version', jeedom::version());
} catch (Exception $e) {
	echo 'Erreur durant l\'installation : ' . $e->getMessage();
	echo 'Détails : ' . print_r($e->getTrace(), true);
	echo "[END INSTALL ERROR]\n";
	throw $e;
}

echo "Temps d'installation : " . (strtotime('now') - $starttime) . "s\n";
echo "[END INSTALL SUCCESS]\n";
