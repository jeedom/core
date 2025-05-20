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

require_once __DIR__ . '/console.php';
require_once __DIR__ . "/core.inc.php";
if (!isset($argv[1])) {
    $argv[1] = '';
}
if (!isset($argv[2])) {
    $argv[2] = '';
}

switch ($argv[1]) {
    case 'plugin':
        switch ($argv[2]) {
            case 'install':
                if (isset($argv[4])) { 
                    // install by github source
                    // jeecli.php plugin install [id] [user] [repository=id] [branch=master]
                    echo "Install Plugin $argv[4] / $argv[3] from github\n";
                    config::save('github::enable', 1);
                    $update = new update();
                    $update->setLogicalId($argv[3]);
                    $update->setConfiguration('user', $argv[4]);
                    $update->setSource('github');
                    // if arg(5) is set, it is the repository configuration, else the repo name is the same as the plugin id
                    $update->setConfiguration('repository', $argv[5] ?? $argv[3]);
                    // if arg(6) is set, it is the version configuration i.e. branch name
                    $update->setConfiguration('version', $argv[6] ?? 'master');
                } else if (!isset($argv[3])) {
                    echo "Plugin id is mandatory";
                    die();
                } else {
                    $update = update::byLogicalId($argv[3]);
                    if (!is_object($update)) {
                        $update = new update();
                    }
                    $update->setLogicalId($argv[3]);
                    $update->setSource('market');
                    $update->setConfiguration('version', 'stable');
                }
                $update->save();
                $update->doUpdate();
                $plugin = plugin::byId($argv[3]);
                if (!is_object($plugin)) {
                    echo "Error plugin not found";
                    die();
                }
                $plugin->setIsEnable(1,true,true);
                jeedom::cleanFileSystemRight();
                break;
            case 'dependancy_end':
                if (!isset($argv[3])) {
                    echo "Plugin id is mandatory";
                    die();
                }
                if (method_exists($argv[3], 'dependancy_end')) {
                    $argv[3]::dependancy_end();
                }
                break;
            default:
                echo "No action provide : install";
                die();
        }
        break;
    case 'user':
        switch ($argv[2]) {
            case 'list':
                foreach (user::all() as $user) {
                    echo $user->getLogin() . "\n";
                }
                break;
            case 'password':
                if (!isset($argv[3]) || !isset($argv[4])) {
                    echo "Username and password are mandatory";
                    break;
                }
                if (strlen($argv[4]) < 8) {
                    echo "Password need 8 characteres length";
                    break;
                }
                $user = user::byLogin($argv[3]);
                if (!is_object($user)) {
                    echo "User " . $argv[3] . " not found";
                    die();
                }
                $user->setPassword(sha512($argv[4]));
                $user->save();
                break;
            case 'add':
                if (!isset($argv[3]) || !isset($argv[4])) {
                    echo "Username and password are mandatory";
                    break;
                }
                if (strlen($argv[4]) < 8) {
                    echo "Password need 8 characteres length";
                    break;
                }
                $user = user::byLogin($argv[3]);
                if (is_object($user)) {
                    echo "User " . $argv[3] . " already exist";
                    die();
                }
                $user = new user();
        		$user->setLogin($argv[3]);
        		$user->setPassword(sha512($argv[4]));
        		$user->setProfils('admin');
        		$user->save();
                break;
            default:
                echo "No action provide : list,password";
                die();
        }
        break;
    case 'message':
        switch ($argv[2]) {
            case 'add':
                if (!isset($argv[3])) {
                    echo "No type provide";
                    break;
                }
                if (!isset($argv[4])) {
                    echo "No message provide";
                    break;
                }
                message::add($argv[3], $argv[4]);
                break;
            default:
                echo "No action provide : add";
                die();
        }
        break;
    case 'backup':
        switch ($argv[2]) {
            case 'restore':
                if (!isset($argv[3])) {
                    echo "No backup file provide";
                    break;
                }

                $backupPath = ''; 
                $i = 0;
                foreach (jeedom::listBackup() as $key => $backup) {
                    $i++;
                    if($i == $argv[3]) {
                        $backupPath = $key;
                        break;
                    }
                }

                if (!is_file($backupPath)) {
                    echo "Backup file not found";
                    break;
                }
                jeedom::restore($backupPath, true);
    
                $cheminFichier = log::getPathToLog('restore');
                if (!file_exists($cheminFichier)) {
                    echo "Le fichier n'existe pas.\n";
                    break;
                }
    
                $handle = fopen($cheminFichier, "r");
                if ($handle === false) {
                    echo "Impossible d'ouvrir le fichier.\n";
                    break;
                }
    
                fseek($handle, 0, SEEK_END);
    
                while (true) {
                    while (($line = fgets($handle)) !== false) {
                        echo $line;
                    }
                    clearstatcache();
                    $fileSize = filesize($cheminFichier);
                    if (ftell($handle) < $fileSize) {
                        fseek($handle, ftell($handle));
                    } else {
                        sleep(1); 
                    }            
                }
                fclose($handle);

                break;
            case 'list':
                $i = 0;
                foreach (jeedom::listBackup() as $backup) {
                    $i++;
                    echo "[$i] - ". $backup . "\n";
                }
                break;
            default:
                echo "No action provide : backup";
                die();
        }
        break;
    default:
        help();
        break;
}


function help() {
    echo "Usage:  jeecli.php [OPTIONS] COMMAND\n\n";
    echo "jeecli allow you to do some action on jeedom from command line\n\n";
    echo "Options : \n";

    echo "\n\n";
    echo "Commands : \n";
    echo "\t plugin : manage Jeedom plugin\n";
    echo "\t\t install [plugin_id] : install plugin_id from market\n";
    echo "\t\t install [plugin_id] [user] [repository=plugin_id] [branch=master] : install plugin_id from github\n";
    echo "\t\t dependancy_end [plugin_id] : send end of dependancy install for plugin_id\n";

    echo "\n";
    echo "\t user : manage Jeedom user\n";
    echo "\t\t list : list jeedom user\n";
    echo "\t\t password [username] [password] : change password of [username] to [password]\n";

    echo "Available actions for 'backup':\n";
    echo "  restore [n] - Restore the nth backup file.\n";
    echo "  list - List all available backups.\n";
}
