<?php

/** @entrypoint */
/** @console */

require_once __DIR__.'/core/php/console.php';

echo "==================================================\n";
echo "|    JEEDOM SICK SCRIPT " . date('Y-m-d H:i:s') . "    |";
echo "\n==================================================\n";

echo "\n**************************************************\n";
echo "*                   VARIABLES                    *";
echo "\n**************************************************\n";
$install_dir = __DIR__;
$processUser = posix_getpwuid(posix_geteuid());
echo "Install folder : " . $install_dir . "\n";
echo "User : " . $processUser['name'] . "\n";
if (trim(exec('sudo cat /etc/sudoers')) != "") {
	echo "Sudo : YES\n";
} else {
	echo "Sudo : NO\n";
}

echo "\n**************************************************\n";
echo "*                    FOLDERS                     *";
echo "\n**************************************************\n";
echo "Load Jeedom environment...";
try {
	require_once __DIR__ . "/core/php/core.inc.php";
	echo "OK\n";
} catch (Exception $e) {
	echo "ERROR\n";
	echo "Cannot load Jeedom environment : " . $e->getMessage();
	exit(1);
}

/* Check log dir */
echo "Check write mode on log files ...";
if (!file_exists($install_dir . '/log')) {
	echo "unfound /log folder\n";
	echo "Required command : mkdir " . $install_dir . "/log";
	exit(1);
}
if (!is_writable($install_dir . '/log')) {
	echo "Cannot write\n";
	echo "Required command : chown  -R " . $processUser['name'] . ' ' . $install_dir . "/log";
	exit(1);
}
echo "OK\n";

echo "
**************************************************
*               DATABASE CONNECTION              *
**************************************************
";

// check that mysql exists as available driver
if( !in_array( 'mysql', PDO::getAvailableDrivers())){
  echo  "Driver mysql non installé !";
  exit(1);
}else{
  echo "Driver mysql disponible.\n";
}
// check database configuration
if(!file_exists(__DIR__ . '/core/config/common.config.php')){
  echo 'Configuration manquante ! core/config/common.config.php non généré.';
  exit(1);
}

require_once __DIR__ . '/core/config/common.config.php';

// check local socket if localhost is configured
if(isset($CONFIG['db']['unix_socket']) || (isset($CONFIG['db']['host']) && $CONFIG['db']['host'] == 'localhost')) {

  // check default socket configuration for mysql
  $default_socket = ini_get('pdo_mysql.default_socket');
  if(empty($default_socket)){
    echo "pdo_mysql.default_socket = VIDE !
	 vérifier /usr/local/etc/php/php.ini
	 et ajouter dans la section [Pdo_mysql]
	 pdo_mysql.default_socket=/var/run/mysqld/mysqld.sock";
	 exit(1);
  } else {
	if(!file_exists($default_socket)){
		echo  "Pas de socker: $default_socket";
		exit(1);
	}
	if(!is_readable($default_socket)){
		echo  "Fichier inaccessible en lecture ! $default_socket";
		exit(1);
	}
	if(!is_writeable($default_socket)){
		echo  "Fichier inaccessible en écriture ! $default_socket";
		exit(1);
	}
  }

}

// TODO: check env var MYSQL_HOST ?

if (isset($CONFIG['db']['unix_socket'])) {
  try {
    $connection = new PDO('mysql:unix_socket=' . $CONFIG['db']['unix_socket'] . ';dbname=' . $CONFIG['db']['dbname'], $CONFIG['db']['username'], $CONFIG['db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci', PDO::ATTR_PERSISTENT => true));
  } catch (Exception $e) {
    echo $e->getMessage();
	exit(1);  
  }
} else {
  try {
    $connection = new PDO('mysql:host=' . $CONFIG['db']['host'] . ';port=' . $CONFIG['db']['port'] . ';dbname=' . $CONFIG['db']['dbname'], $CONFIG['db']['username'], $CONFIG['db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci', PDO::ATTR_PERSISTENT => true));
  } catch (Exception $e) {
    echo $e->getMessage();
	exit(1);  
  }
}

if( !isset($connection)) {
  echo 'ECHEC ! impossible de se connecter à la bdd mariadb';
  exit(1);
}else{
  $result = $connection->query('SHOW TABLES;');
  $arr = $result->fetchAll();
  if(count($arr) == 0){
    echo "Aucune table ! relancer l'installation: php instal.php --force";
	exit(1);
  }else{
    echo "Connection OK - " . count($arr) . " tables.\n";
  }
}

if(empty(session_save_path())) {
	echo "Paramètre session.save_path manquant dans /usr/local/etc/php/php.ini
	[Session]
	session.save_path = \"/tmp/jeedom\"\n";
}


echo "\n**************************************************\n";
echo "*                     USERS                      *";
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
		echo "No admin user found, creating one...";
		$user = (new \user())
			->setLogin('admin')
			->setPassword(sha512('admin'))
			->setProfils('admin', 1);
		$user->save();
		echo "OK (admin/admin)\n";
	}
} catch (Exception $e) {
	echo "ERROR\n";
	echo "Description : " . $e->getMessage()."\n";
	exit(1);
}

echo "\n**************************************************\n";
echo "*                     CRON                       *";
echo "\n**************************************************\n";
echo "Check active cron engine ...";
if (config::byKey('enableCron', 'core', 1, true) == 0) {
	echo "NOK\n";
} else {
	echo "OK\n";
}
echo "Check active scenario engine ...";
if (config::byKey('enableScenario') == 0) {
	echo "NOK\n";
} else {
	echo "OK\n";
}
echo "\n";
echo "NAME | STATE | SCHEDULE | DAEMON | ONCE | LAST RUN\n";
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
echo "*                     DATE                       *";
echo "\n**************************************************\n";
echo "Check Jeedom correct date ...";
if (jeedom::isDateOk()) {
	echo "OK";
} else {
	echo "NOK";
}
$cache = cache::byKey('jeedom::lastDate');
echo " (" . $cache->getValue() . ")\n";

echo "\n**************************************************\n";
echo "*                    MESSAGE                     *";
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
echo "*                      PLUGIN                    *";
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
echo "|                   All check done                |";
echo "\n==================================================\n";
