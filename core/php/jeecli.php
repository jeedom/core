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
                if (!isset($argv[3])) {
                    echo "Plugin id is mandatory";
                    die();
                }
                $update = update::byLogicalId($argv[3]);
                if (!is_object($update)) {
                    $update = new update();
                }
                $update->setLogicalId($argv[3]);
                $update->setSource('market');
                $update->setConfiguration('version', 'stable');
                $update->save();
                $update->doUpdate();
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
            default:
                echo "No action provide : list,password";
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
    echo "\t\t dependancy_end [plugin_id] : send end of dependancy install for plugin_id\n";

    echo "\n";
    echo "\t user : manage Jeedom user\n";
    echo "\t\t list : list jeedom user\n";
    echo "\t\t password [username] [password] : change password of [username] to [password]\n";
}
