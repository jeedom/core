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
echo "[START BACKUP]\n";
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
    echo __("***************Lancement du backup de Jeedom***************\n", __FILE__);
    global $CONFIG;
    $tmp = dirname(__FILE__) . '/../tmp/backup';
    if (!file_exists($tmp)) {
        mkdir($tmp, 0770, true);
    }
    $backup_dir = calculPath(config::byKey('backup::path'));

    echo __("Verification du filesystem (corruption)...", __FILE__);
    if (jeedom::checkFilesystem()) {
        echo __("OK\n", __FILE__);
    } else {
        echo __("NOK\n", __FILE__);
    }
    if (!file_exists($backup_dir)) {
        mkdir($backup_dir, 0770, true);
    }
    if (!is_writable($backup_dir)) {
        throw new Exception(__('Le dossier des backups n\'est pas accessible en ecriture. Verifier les droits : ', __FILE__) . $backup_dir);
    }

    $bakcup_name = 'backup-' . getVersion('jeedom') . '-' . date("d-m-Y-H\hi") . '.tar.gz';

    echo __('Sauvegarde des fichiers...', __FILE__);
    rcopy(dirname(__FILE__) . '/..', $tmp, true, array('tmp', 'backup', 'log'));
    echo __("OK\n", __FILE__);

    echo __('Suppression du fichier d\'identification BDD...', __FILE__);
    if (file_exists($tmp . '/core/config/common.config.php')) {
        unlink($tmp . '/core/config/common.config.php');
    }
    echo __("OK\n", __FILE__);

    if (!file_exists($tmp . '/plugin_backup')) {
        mkdir($tmp . '/plugin_backup', 0770, true);
    }
    foreach (plugin::listPlugin(true) as $plugin) {
        $plugin_id = $plugin->getId();
        if (method_exists($plugin_id, 'backup')) {
            echo __('Sauvegarde specifique pour le plugin...' . $plugin_id . '...', __FILE__);
            if (!file_exists($tmp . '/plugin_backup/' . $plugin_id)) {
                mkdir($tmp . '/plugin_backup/' . $plugin_id, 0770, true);
            }
            $plugin_id::backup($tmp . '/plugin_backup/' . $plugin_id);
            echo __("OK\n", __FILE__);
        }
    }

    echo __("Verification de la base : \n", __FILE__);
    system("mysqlcheck --host=" . $CONFIG['db']['host'] . " --user=" . $CONFIG['db']['username'] . " --password=" . $CONFIG['db']['password'] . " " . $CONFIG['db']['dbname'] . ' --auto-repair --silent');


    echo __('Sauvegarde de la base de donnees...', __FILE__);
    system("mysqldump --host=" . $CONFIG['db']['host'] . " --user=" . $CONFIG['db']['username'] . " --password=" . $CONFIG['db']['password'] . " " . $CONFIG['db']['dbname'] . "  > " . $tmp . "/DB_backup.sql");
    echo __("OK\n", __FILE__);

    echo __('Creation de l\'archive...', __FILE__);
    system('cd ' . $tmp . '; tar cfz ' . $backup_dir . '/' . $bakcup_name . ' * > /dev/null 2>&1');
    echo __("OK\n", __FILE__);

    if (!file_exists($backup_dir . '/' . $bakcup_name)) {
        throw new Exception(__('Echec lors de la compression du backup. Backup introuvable : ', __FILE__) . $backup_dir . '/' . $bakcup_name);
    }

    echo __('Nettoyage des anciens backup...', __FILE__);
    system('find ' . $backup_dir . ' -mtime +' . config::byKey('backup::keepDays') . ' -delete');
    echo __("OK\n", __FILE__);

    if (config::byKey('backup::cloudUpload') == 1) {
        echo __('Envoie de la sauvegarde dans le cloud...', __FILE__);
        try {
            market::sendBackup($backup_dir . '/' . $bakcup_name);
        } catch (Exception $e) {
            log::add('backup', 'error', $e->getMessage());
            echo '/!\ ' . br2nl($e->getMessage()) . ' /!\\';
        }
        echo __("OK\n", __FILE__);
    }

    if (config::byKey('jeeNetwork::mode') == 'slave') {
        echo __('Envoie de la sauvegarde sur le maitre...', __FILE__);
        try {
            jeeNetwork::sendBackup($backup_dir . '/' . $bakcup_name);
        } catch (Exception $e) {
            log::add('backup', 'error', $e->getMessage());
            echo '/!\ ' . br2nl($e->getMessage()) . ' /!\\';
        }
        echo __("OK\n", __FILE__);
    }

    echo __("***************Fin du backup de Jeedom***************\n", __FILE__);
    echo "[END BACKUP SUCCESS]\n";
} catch (Exception $e) {
    echo __('Erreur durant le backup : ', __FILE__) . br2nl($e->getMessage());
    echo __('Details : ', __FILE__) . print_r($e->getTrace());
    echo "[END BACKUP ERROR]\n";
    throw $e;
}
?>
