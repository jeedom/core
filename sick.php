<?php

/** @entrypoint */
/** @console */

require_once __DIR__.'/core/php/console.php';

echo "==================================================\n";
echo "|    JEEDOM SICK SCRIPT " . date('Y-m-d H:i:s') . "    |";
echo "\n==================================================\n";

echo "\n**************************************************\n";
echo "*                 VARIABLES                      *";
echo "\n**************************************************\n";
$install_dir = __DIR__;
$processUser = posix_getpwuid(posix_geteuid());
echo "Dossier d'installation : " . $install_dir . "\n";
echo "User : " . $processUser['name'] . "\n";
if (trim(exec('sudo cat /etc/sudoers')) != "") {
	echo "Sudo : OUI\n";
} else {
	echo "Sudo : NON\n";
}

echo "\n**************************************************\n";
echo "*               DOSSIERS                         *";
echo "\n**************************************************\n";
echo "Charge l'environnement de Jeedom...";
try {
	require_once __DIR__ . "/core/php/core.inc.php";
	echo "OK\n";
} catch (Exeption $e) {
	echo "ERREUR\n";
	echo "Impossible de charger l'environnement de Jeedom : " . $e->getMessage();
	echo "\n";
	die();
}

/* Check log dir */
echo "Vérifie si les log sont en écriture...";
if (!file_exists($install_dir . '/log')) {
	echo "introuvable\n";
	echo "Faites : mkdir " . $install_dir . "/log\n";
	die();
}
if (!is_writable($install_dir . '/log')) {
	echo "Impossible d'écrire\n";
	echo "Faites : chown  -R " . $processUser['name'] . ' ' . $install_dir . "/log\n";
	die();
}
echo "OK\n";

echo "\n**************************************************\n";
echo "*              UTILISATEURS                      *";
echo "\n**************************************************\n";
try {
	$foundAdmin = false;
	foreach (user::all() as $user) {
		echo $user->getLogin();
		echo " => ";
		if ($user->getProfils() == 'admin') {
			$foundAdmin = true;
			echo " Admin\n";
		} else {
			echo " Regular\n";
		}
	}

	if (!$foundAdmin) {
		echo "Aucun utilisateur admin trouvé, veuillez en créer un...";
		$user = (new \user())
			->setLogin('admin')
			->setPassword(sha512('admin'))
			->setProfils('admin', 1);
		$user->save();
		echo "OK (admin/admin)\n";
	}
} catch (Exeption $e) {
	echo "ERREUR\n";
	echo "Description : " . $e->getMessage();
	echo "\n";
	die();
}

echo "\n**************************************************\n";
echo "*                 CRON                           *";
echo "\n**************************************************\n";
echo "Vérifie si cron est actif...";
if (config::byKey('enableCron', 'core', 1, true) == 0) {
	echo "NOK\n";
} else {
	echo "OK\n";
}
echo "Vérifie si scénario est actif...";
if (config::byKey('enableScenario') == 0) {
	echo "NOK\n";
} else {
	echo "OK\n";
}
echo "\n";
echo "NAME | STATE | SCHEDULE | DEAMON | ONCE | LAST RUN\n";
foreach (cron::all() as $cron) {
	echo $cron->getName();
	echo " | ";
	echo $cron->getState();
	echo " | ";
	echo $cron->getSchedule();
	echo " | ";
	echo $cron->getDeamon();
	echo " | ";
	echo $cron->getOnce();
	echo " | ";
	echo $cron->getLastRun();
	echo "\n";
}

echo "\n**************************************************\n";
echo "*                 DATE                           *";
echo "\n**************************************************\n";
echo "Vérifie si la date de Jeedom est correcte...";
if (jeedom::isDateOk()) {
	echo "OK";
} else {
	echo "NOK";
}
$cache = cache::byKey('jeedom::lastDate');
echo " (" . $cache->getValue() . ")\n";

echo "\n**************************************************\n";
echo "*                 MESSAGE                        *";
echo "\n**************************************************\n";
echo "DATE | PLUGIN | LOGICALID | MESSAGE\n";
foreach (message::all() as $message) {
	echo $message->getDate();
	echo " | ";
	echo $message->getPlugin();
	echo " | ";
	echo $message->getLogicalId();
	echo " | ";
	echo $message->getMessage();
	echo "\n";
}

echo "\n**************************************************\n";
echo "*                 PLUGIN                         *";
echo "\n**************************************************\n";
echo "ID | NAME | STATE\n";
foreach (plugin::listPlugin() as $plugin) {
	echo $plugin->getId();
	echo " | ";
	echo $plugin->getName();
	echo " | ";
	echo $plugin->isActive();
	echo "\n";
}

foreach (plugin::listPlugin() as $plugin) {
	if (method_exists($plugin->getId(), 'sick')) {
		echo "\n**************************************************\n";
		echo "*          SICK  " . $plugin->getId() . "         *";
		echo "\n**************************************************\n";
		$plugin_id = $plugin->getId();
		$plugin_id::sick();
	}
}

echo "\n\n";
echo "\n==================================================\n";
echo "|         TOUTES LES VERIFICATIONS SONT FAITES    |";
echo "\n==================================================\n";
