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
set_time_limit(1800);

echo "[START UPDATE]\n";
if (isset($argv)) {
    foreach ($argv as $arg) {
        $argList = explode('=', $arg);
        if (isset($argList[0]) && isset($argList[1])) {
            $_GET[$argList[0]] = $argList[1];
        }
    }
}

$update = false;
$backup_ok = false;
$update_begin = false;
try {
    require_once dirname(__FILE__) . '/../core/php/core.inc.php';
    echo __("***************Installation/Mise à jour de Jeedom " . getVersion('jeedom') . "***************\n", __FILE__);
    echo "Paramètres de la mise à jour : level : " . init('level', -1) . ", mode : " . init('mode') . " \n";

    try {
        $curentVersion = config::byKey('version');
        if ($curentVersion != '') {
            $update = true;
        }
    } catch (Exception $e) {
        
    }
    if (init('v') != '') {
        $update = true;
    }

    if ($update) {
        /*         * ************************MISE A JOUR********************************** */
        try {
            if (init('level', -1) > -1 && init('mode') != 'force') {
                echo __("Vérification des mises à jour...", __FILE__);
                update::checkAllUpdate();
                echo __("OK\n", __FILE__);
            }
        } catch (Exception $e) {
            if (init('mode') != 'force') {
                throw $e;
            } else {
                echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
            }
        }

        if (init('level', -1) < 1) {
            if (config::byKey('update::backupBefore') == 1 && init('mode') != 'force') {
                try {
                    jeedom::backup();
                } catch (Exception $e) {
                    if (init('mode') != 'force') {
                        throw $e;
                    } else {
                        echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
                    }
                }
                $backup_ok = true;
            }
            if (init('mode') == 'force') {
                echo __("/!\ Mise à jour en mode forcé /!\ \n", __FILE__);
            }
            jeedom::stop();
            if (init('v') == '') {
                try {
                    exec('rm -rf ' . dirname(__FILE__) . '/../tmp/*.zip');
                    exec('rm -rf ' . dirname(__FILE__) . '/../tmp/backup');
                    echo __('OK', __FILE__);
                } catch (Exception $e) {
                    echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
                }
                try {
                    $url = config::byKey('market::address') . "/jeedom/" . config::byKey('market::branch') . '/jeedom.zip';
                    echo __("Adresse de téléchargement : " . $url . "\n", __FILE__);
                    echo __("Téléchargement en cours...", __FILE__);
                    $tmp_dir = dirname(__FILE__) . '/../tmp';
                    $tmp = $tmp_dir . '/jeedom_update.zip';
                    if (!is_writable($tmp_dir)) {
                        throw new Exception(__('Impossible d\'écrire dans le dossier : ', __FILE__) . $tmp . __('. Exécuter la commande suivante en SSH : chmod 777 -R ', __FILE__) . $tmp_dir);
                    }
                    $url = config::byKey('market::address') . "/jeedom/" . config::byKey('market::branch') . '/jeedom.zip';
                    if (file_exists($tmp)) {
                        unlink($tmp);
                    }
                    file_put_contents($tmp, fopen($url, 'r'));
                    if (!file_exists($tmp)) {
                        throw new Exception(__('Impossible de télécharger le fichier depuis : ' . $url . '. Si l\'application est payante, l\'avez-vous achetée ?', __FILE__));
                    }
                    if (filesize($tmp) < 10) {
                        throw new Exception(__('Echec lors du téléchargement du fichier. Veuillez réessayer plus tard (taille inférieure à 10 octets)', __FILE__));
                    }
                    echo __("OK\n", __FILE__);

                    echo __("Nettoyage des dossiers en cours...", __FILE__);
                    $cibDir = dirname(__FILE__) . '/../tmp/jeedom';
                    if (file_exists($cibDir)) {
                        rrmdir($cibDir);
                    }
                    echo __("OK\n", __FILE__);
                    echo __("Nettoyage sqlbuddy en cours...", __FILE__);
                    foreach (ls(dirname(__FILE__) . '/../', 'sqlbuddy*') as $file) {
                        rrmdir(dirname(__FILE__) . '/../' . $file);
                    }
                    echo __("OK\n", __FILE__);
                    echo __("Création des dossiers temporaire...", __FILE__);
                    if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
                        throw new Exception(__('Impossible de créer le dossier  : ' . $cibDir . '. Problème de droits ?', __FILE__));
                    }
                    echo __("OK\n", __FILE__);
                    echo __("Décompression en cours...", __FILE__);
                    $zip = new ZipArchive;
                    if ($zip->open($tmp) === TRUE) {
                        if (!$zip->extractTo($cibDir)) {
                            throw new Exception(__('Impossible d\'installer la mise à jour. Les fichiers n\'ont pas pu être décompressés', __FILE__));
                        }
                        $zip->close();
                    } else {
                        throw new Exception(__('Impossible de décompresser l\'archive zip : ', __FILE__) . $tmp);
                    }
                    echo __("OK\n", __FILE__);
                    echo __("Installation en cours...", __FILE__);
                    $update_begin = true;
                    @rcopy($cibDir . '/', dirname(__FILE__) . '/../', false);
                    rrmdir($cibDir);
                    unlink($tmp);
                    echo __("OK\n", __FILE__);
                    echo __("Renommage sqlbuddy en cours...", __FILE__);
                    jeedom::renameSqlBuddyFolder();
                    echo __("OK\n", __FILE__);
                } catch (Exception $e) {
                    if (init('mode') != 'force') {
                        throw $e;
                    } else {
                        echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
                    }
                }
            }
            include dirname(__FILE__) . '/../core/config/version.config.php';

            if (init('v') != '') {
                echo __("La mise à jour ", __FILE__) . init('v') . __(" va être appliquée à nouveau. Voulez-vous continuer  ? [o/N] ", __FILE__);
                if (trim(fgets(STDIN)) !== 'o') {
                    echo __("La mise à jour forcée de Jeedom est annulée\n", __FILE__);
                    jeedom::start();
                    echo "[END UPDATE SUCCESS]\n";
                    exit(0);
                }
                $updateSql = dirname(__FILE__) . '/update/' . init('v') . '.sql';
                if (file_exists($updateSql)) {
                    try {
                        echo __("Mise à jour de la base de données en version : ", __FILE__) . init('v') . "\n";
                        $sql = file_get_contents($updateSql);
                        DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
                        echo "OK\n";
                    } catch (Exception $e) {
                        if (init('mode') != 'force') {
                            throw $e;
                        } else {
                            echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
                        }
                    }
                }
                $updateScript = dirname(__FILE__) . '/update/' . init('v') . '.php';
                if (file_exists($updateScript)) {
                    try {
                        echo __("Mise à jour du système en version : ", __FILE__) . init('v') . "\n";
                        require_once $updateScript;
                        echo __("OK\n", __FILE__);
                    } catch (Exception $e) {
                        if (init('mode') != 'force') {
                            throw $e;
                        } else {
                            echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
                        }
                    }
                }
            } else {
                while (version_compare(getVersion('jeedom'), $curentVersion, '>')) {
                    $nextVersion = incrementVersion($curentVersion);
                    $updateSql = dirname(__FILE__) . '/update/' . $nextVersion . '.sql';
                    if (file_exists($updateSql)) {
                        try {
                            echo __("Mise à jour de la base de données en version : ", __FILE__) . $nextVersion . "...";
                            $sql = file_get_contents($updateSql);
                            DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
                            echo "OK\n";
                        } catch (Exception $e) {
                            if (init('mode') != 'force') {
                                throw $e;
                            } else {
                                echo '***ERREUR*** ' . $e->getMessage();
                            }
                        }
                    }
                    $updateScript = dirname(__FILE__) . '/update/' . $nextVersion . '.php';
                    if (file_exists($updateScript)) {
                        try {
                            echo __("Mise à jour du système en version : ", __FILE__) . $nextVersion . "...";
                            require_once $updateScript;
                            echo __("OK\n", __FILE__);
                        } catch (Exception $e) {
                            if (init('mode') != 'force') {
                                throw $e;
                            } else {
                                echo __('***ERREUR*** ', __FILE__) . $e->getMessage();
                            }
                        }
                    }
                    $curentVersion = $nextVersion;
                }
            }
            try {
                echo __("Vérification de la mise à jour...", __FILE__);
                update::checkAllUpdate('core');
                config::save('version', getVersion('jeedom'));
                echo __("OK\n", __FILE__);
            } catch (Exception $ex) {
                echo __("***ERREUR*** ", __FILE__) . $ex->getMessage() . "\n";
            }
            echo __("***************Jeedom est à jour en version ", __FILE__) . getVersion('jeedom') . "***************\n";
        }
        if (init('level', -1) > -1) {
            echo __("***************Mise à jour des plugins***************\n", __FILE__);
            update::updateAll();
            echo __("***************Mise à jour des plugins réussie***************\n", __FILE__);
        }
        try {
            jeedom::start();
        } catch (Exception $ex) {
            echo __("***ERREUR*** ", __FILE__) . $ex->getMessage() . "\n";
        }
    } else {

        /*         * ***************************INSTALLATION************************** */
        if (init('mode') != 'force') {
            echo __("Jeedom va être installé. Voulez-vous continuer ? [o/N] ", __FILE__);
            if (trim(fgets(STDIN)) !== 'o') {
                echo __("L'installation de Jeedom est annulée\n", __FILE__);
                echo "[END UPDATE SUCCESS]\n";
                exit(0);
            }
        }
        echo __("\nInstallation de Jeedom ", __FILE__) . getVersion('jeedom') . "\n";
        $sql = file_get_contents(dirname(__FILE__) . '/install.sql');
        echo __("Installation de la base de données...", __FILE__);
        DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
        echo __("OK\n", __FILE__);
        echo __("Post installe...\n", __FILE__);
        nodejs::updateKey();
        config::save('api', config::genKey());
        echo __("Ajout des taches cron\n", __FILE__);
        $cron = new cron();
        $cron->setClass('history');
        $cron->setFunction('historize');
        $cron->setSchedule('*/5 * * * * *');
        $cron->setTimeout(5);
        $cron->save();
        $cron = new cron();
        $cron->setClass('scenario');
        $cron->setFunction('check');
        $cron->setSchedule('* * * * * *');
        $cron->setTimeout(5);
        $cron->save();
        $cron = new cron();
        $cron->setClass('cmd');
        $cron->setFunction('collect');
        $cron->setSchedule('*/5 * * * * *');
        $cron->setTimeout(5);
        $cron->save();
        $cron = new cron();
        $cron->setClass('plugin');
        $cron->setFunction('cronDaily');
        $cron->setSchedule('00 00 * * *');
        $cron->setTimeout(5);
        $cron->save();
        $cron = new cron();
        $cron->setClass('plugin');
        $cron->setFunction('cronHourly');
        $cron->setSchedule('00 * * * *');
        $cron->setTimeout(5);
        $cron->save();
        $cron = new cron();
        $cron->setClass('history');
        $cron->setFunction('archive');
        $cron->setSchedule('00 * * * * *');
        $cron->setTimeout(20);
        $cron->save();
        $cron = new cron();
        $cron->setClass('jeedom');
        $cron->setFunction('cron');
        $cron->setSchedule('* * * * * *');
        $cron->setTimeout(60);
        $cron->save();

        echo __("Ajout de l\'utilisateur (admin,admin)\n", __FILE__);
        $user = new user();
        $user->setLogin('admin');
        $user->setPassword(sha1('admin'));
        $user->setRights('admin', 1);
        $user->save();
        $logLevel = array('info' => 0, 'debug' => 0, 'event' => 0, 'error' => 1);
        if (init('mode') != 'force') {
            echo __("Jeedom est-il installé sur un Rasberry PI ? [o/N] ", __FILE__);
            if (trim(fgets(STDIN)) === 'o') {
                config::save('cronSleepTime', 60);
            }
        } else {
            config::save('cronSleepTime', 60);
        }
        config::save('logLevel', $logLevel);
        echo "OK\n";
    }

    config::save('version', getVersion('jeedom'));
} catch (Exception $e) {
    if ($update) {
        if ($backup_ok && $update_begin) {
            jeedom::restore();
        }
        jeedom::start();
    }
    echo __('Erreur durant l\'installation : ', __FILE__) . $e->getMessage();
    echo __('Details : ', __FILE__) . print_r($e->getTrace());
    echo "[END UPDATE ERROR]\n";
    throw $e;
}
echo "[END UPDATE SUCCESS]\n";

function incrementVersion($_version) {
    $version = explode('.', $_version);
    if ($version[2] < 200) {
        $version[2] ++;
    } else {
        if ($version[1] < 200) {
            $version[1] ++;
            $version[2] = 0;
        } else {
            $version[0] ++;
            $version[1] = 0;
            $version[2] = 0;
        }
    }
    $returnVersion = '';
    for ($j = 0, $sVersion = count($version); $j < $sVersion; $j++) {
        $returnVersion .= $version[$j] . '.';
    }
    return trim($returnVersion, '.');
}
