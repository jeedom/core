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
    header("Status: 404 Not Found");
    header('HTTP/1.0 404 Not Found');
    $_SERVER['REDIRECT_STATUS'] = 404;
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();
}
echo "[START RESTORE]\n";
if (isset($argv)) {
    foreach ($argv as $arg) {
        $argList = explode('=', $arg);
        if (isset($argList[0]) && isset($argList[1])) {
            $_GET[$argList[0]] = $argList[1];
        }
    }
}

try {
    require_once dirname(__FILE__) . '/../core/php/core.inc.php';
    echo __("***************Lancement de la restauration de Jeedom***************\n", __FILE__);
    global $CONFIG;
    global $BACKUP_FILE;
    if (isset($BACKUP_FILE)) {
        $_GET['backup'] = $BACKUP_FILE;
    }
    if (!isset($_GET['backup']) || $_GET['backup'] == '') {
        if (substr(config::byKey('backup::path'), 0, 1) != '/') {
            $backup_dir = dirname(__FILE__) . '/../' . config::byKey('backup::path');
        } else {
            $backup_dir = config::byKey('backup::path');
        }
        if (!file_exists($backup_dir)) {
            mkdir($backup_dir, 0770, true);
        }
        $backup = null;
        $mtime = null;
        foreach (scandir($backup_dir) as $file) {
            if ($file != "." && $file != "..") {
                $s = stat($backup_dir . '/' . $file);
                if ($backup == null || $mtime == null) {
                    $backup = $backup_dir . '/' . $file;
                    $mtime = $s['mtime'];
                }
                if ($mtime < $s['mtime']) {
                    $backup = $backup_dir . '/' . $file;
                    $mtime = $s['mtime'];
                }
            }
        }
    } else {
        $backup = $_GET['backup'];
    }
    if (substr($backup, 0, 1) != '/') {
        $backup = dirname(__FILE__) . '/../' . $backup;
    }

    if (!file_exists($backup)) {
        throw new Exception(__('Backup non trouve.', __FILE__) . $backup);
    }
    $jeedom_dir = realpath(dirname(__FILE__) . '/../');

    echo __("Restauration de Jeedom avec le fichier : ", __FILE__) . $backup . "\n";

    echo __("Nettoyage des anciens fichiers...", __FILE__);
    $tmp = dirname(__FILE__) . '/../tmp/backup';
    rrmdir($tmp);
    echo __("OK\n", __FILE__);
    if (!file_exists($tmp)) {
        mkdir($tmp, 0770, true);
    }
    echo __("Decompression du backup...", __FILE__);
    $return_var = 0;
    $output = array();
    exec('cd ' . $tmp . '; tar xfz ' . $backup . ' ', $output, $return_var);
    if ($return_var != 0) {
        throw new Exception(__('Impossible de decompresser l\'archive', __FILE__));
    }
    echo "OK\n";
    if (!file_exists($tmp . "/DB_backup.sql")) {
        throw new Exception(__('Impossible de trouver le fichier de backup de la BDD dans l\'archive : DB_backup.sql', __FILE__));
    }
    jeedom::stop();
    echo __("Suppression de toutes les tables", __FILE__);
    $tables = DB::Prepare("SHOW TABLES", array(), DB::FETCH_TYPE_ALL);
    echo __("Desactivation des contraintes...", __FILE__);
    DB::Prepare("SET foreign_key_checks = 0", array(), DB::FETCH_TYPE_ROW);
    echo __("OK\n", __FILE__);
    foreach ($tables as $table) {
        $table = array_values($table);
        $table = $table[0];
        echo __("Suppression de la table : ", __FILE__) . $table . ' ...';
        DB::Prepare('DROP TABLE IF EXISTS `' . $table . '`', array(), DB::FETCH_TYPE_ROW);
        echo __("OK\n", __FILE__);
    }
    echo __("Reactivation des contraintes...", __FILE__);
    DB::Prepare("SET foreign_key_checks = 1", array(), DB::FETCH_TYPE_ROW);
    echo __("OK\n", __FILE__);

    echo __("Restauration de la base de donnees...", __FILE__);
    system("mysql --user=" . $CONFIG['db']['username'] . " --password=" . $CONFIG['db']['password'] . " " . $CONFIG['db']['dbname'] . "  < " . $tmp . "/DB_backup.sql");
    echo __("OK\n", __FILE__);

    echo __("Restauration des fichiers...", __FILE__);
    rcopy($tmp, dirname(__FILE__) . '/..', false);
    rcopy($tmp . '/plugins', dirname(__FILE__) . '/../plugins', false);
    echo __("OK\n", __FILE__);

    if (!file_exists($jeedom_dir . '/install')) {
        mkdir($jeedom_dir . '/install');
        exec('cd ' . $jeedom_dir . '/install;wget http://git.jeedom.fr/jeedom/core/raw/stable/install/backup.php;wget http://git.jeedom.fr/jeedom/core/raw/stable/install/install.php;wget http://git.jeedom.fr/jeedom/core/raw/stable/install/restore.php');
    }

    foreach (plugin::listPlugin(true) as $plugin) {
        $plugin_id = $plugin->getId();
        if (method_exists($plugin_id, 'restore')) {
            echo __('Restauration specifique du plugin...' . $plugin_id . '...', __FILE__);
            if (file_exists($tmp . '/plugin_backup/' . $plugin_id)) {
                $plugin_id::restore($tmp . '/plugin_backup/' . $plugin_id);
            }
            echo __("OK\n", __FILE__);
        }
    }

    jeedom::start();
    echo __("***************Fin de la restoration de Jeedom***************\n", __FILE__);
    echo "[END RESTORE SUCCESS]\n";
} catch (Exception $e) {
    echo __('Erreur durant le backup : ', __FILE__) . $e->getMessage();
    echo __('Details : ', __FILE__) . print_r($e->getTrace());
    echo "[END RESTORE ERROR]\n";
    jeedom::start();
    throw $e;
}
?>
