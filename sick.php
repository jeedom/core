<?php

if (php_sapi_name() != 'cli' || isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['argc'])) {
    header("Status: 404 Not Found");
    header('HTTP/1.0 404 Not Found');
    $_SERVER['REDIRECT_STATUS'] = 404;
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}
echo "==================================================\n";
echo "|    JEEDOM SICK SCRIPT ".date('Y-m-d H:i:s')."    |";
echo "\n==================================================\n";

echo "\n**************************************************\n";
echo "*                 VARIABLES                      *";
echo "\n**************************************************\n";
$install_dir = dirname(__FILE__);
$processUser = posix_getpwuid(posix_geteuid());
echo "Install dir : " . $install_dir . "\n";
echo "User : " . $processUser['name'] . "\n";


echo "\n**************************************************\n";
echo "*               DIRECTORIES                      *";
echo "\n**************************************************\n";
echo "Load Jeedom environement...";
try {
    require_once dirname(__FILE__) . "/core/php/core.inc.php";
    echo "OK\n";
} catch (Exeption $e) {
    echo "ERREUR\n";
    echo "Unable to load jeedom environement : " . $e->getMessage();
    echo "\n";
    die();
}

/* Check tmp dir */
echo "Check if tmp is wirtable...";
if (!file_exists($install_dir . '/tmp')) {
    echo "not found\n";
    echo "Do : mkdir " . $install_dir . "/tmp\n";
    die();
}
if (!is_writable($install_dir . '/tmp')) {
    echo "not writable\n";
    echo "Do : chown  -R " . $processUser['name'] . ' ' . $install_dir . "/tmp\n";
    die();
}
echo "OK\n";


/* Check log dir */
echo "Check if log is wirtable...";
if (!file_exists($install_dir . '/log')) {
    echo "not found\n";
    echo "Do : mkdir " . $install_dir . "/log\n";
    die();
}
if (!is_writable($install_dir . '/log')) {
    echo "not writable\n";
    echo "Do : chown  -R " . $processUser['name'] . ' ' . $install_dir . "/log\n";
    die();
}
echo "OK\n";


echo "\n**************************************************\n";
echo "*                 USERS                          *";
echo "\n**************************************************\n";
try {
    $foundAdmin = false;
    foreach (user::all() as $user) {
        echo $user->getLogin();
        echo " => ";
        if ($user->getRights('admin') == 1) {
            $foundAdmin = true;
            echo " Admin\n";
        } else {
            echo " Regular\n";
        }
    }

    if (!$foundAdmin) {
        echo "No admin user found, create it...";
        $user = new user();
        $user->setLogin('admin');
        $user->setPassword(sha1('admin'));
        $user->setRights('admin', 1);
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
echo "Check last cron launch...";
if (!cron::ok()) {
    echo "NOK\n";
} else {
    echo "OK\n";
}
echo "Check if cron is enable...";
if (config::byKey('enableCron', 'core', 1, true) == 0) {
    echo "NOK\n";
} else {
    echo "OK\n";
}
echo "Check if scenario is enable...";
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
echo "Check if Jeedom date's is good...";
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
echo "|               ALL CHECKS COMPLET               |";
echo "\n==================================================\n";
