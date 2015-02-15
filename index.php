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
if (!isset($_GET['v'])) {
    $useragent = (isset($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : 'none';
    if (stristr($useragent, "Android") || strpos($useragent, "iPod") || strpos($useragent, "iPhone") || strpos($useragent, "Mobile") || strpos($useragent, "WebOS") || strpos($useragent, "mobile") || strpos($useragent, "hp-tablet")) {
        header("location: index.php?v=m");
    } else {
        header("location: index.php?v=d");
    }
} else {
    try {
        require_once dirname(__FILE__) . "/core/php/core.inc.php";
        if ($_SERVER['SERVER_PORT'] != 443 && config::byKey('forceHttps') == 1) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            exit();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        die();
    }
    if ($_GET['v'] == "d") {
        if (isset($_GET['modal'])) {
            include_file('core', 'authentification', 'php');
            try {
                if (!isConnect()) {
                    throw new Exception('{{401 - Accès non autorisé}}');
                }
                include_file('desktop', init('modal'), 'modal', init('plugin'));
            } catch (Exception $e) {
                $_folder = 'desktop/modal';
                if (init('plugin') != '') {
                    $_folder = 'plugins/' . init('plugin') . '/' . $_folder;
                }
                    ob_end_clean(); //Clean pile after expetion (to prevent no-traduction)
                    echo '<div class="alert alert-danger div_alert">';
                    echo translate::exec(displayExeption($e), $_folder . '/' . init('modal') . '.php');
                    echo '</div>';
                }
                die();
            } 
            if (isset($_GET['configure'])) {
                include_file('core', 'authentification', 'php');
                try {
                    if (!isConnect()) {
                        throw new Exception('{{401 - Accès non autorisé}}');
                    }
                    include_file('plugin_info', 'configuration', 'configuration', init('plugin'));
                } catch (Exception $e) {
                    $_folder = 'plugin_info';
                    if (init('plugin') != '') {
                        $_folder = 'plugins/' . init('plugin') . '/plugin_info';
                    }
                    ob_end_clean(); //Clean pile after expetion (to prevent no-traduction)
                    echo '<div class="alert alert-danger div_alert">';
                    echo translate::exec(displayExeption($e), $_folder . '/configure.php');
                    echo '</div>';
                }
                die();
            } 
            if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
                include_file('core', 'authentification', 'php');
                try {
                    if (!isConnect()) {
                        throw new Exception('{{401 - Accès non autorisé}}');
                    }
                    include_file('desktop', init('p'), 'php', init('m'));
                } catch (Exception $e) {
                    $_folder = 'desktop/php';
                    if (init('m') != '') {
                        $_folder = 'plugins/' . init('m') . '/' . $_folder;
                    }
                    ob_end_clean(); //Clean pile after expetion (to prevent no-traduction)
                    echo '<div class="alert alert-danger div_alert">';
                    echo translate::exec(displayExeption($e), $_folder . '/' . init('modal') . '.php');
                    echo '</div>';

                }
                die();
            }else {
                include_file('desktop', 'index', 'php');
                die();
            }

        } else if ($_GET['v'] == "m") {
            if (isset($_GET['modal'])) {
                include_file('mobile', init('modal'), 'modal', init('plugin'));
            } else {
                if (isset($_GET['p'])) {
                    if (isset($_GET['m'])) {
                        include_file('mobile', $_GET['p'], 'html', $_GET['m']);
                    } else {
                        include_file('mobile', $_GET['p'], 'html');
                    }
                } else {
                    include_file('mobile', 'index', 'html');
                }
            }
        } else {
            echo "Erreur veuillez contacter l'administrateur";
        }
    }
    ?>
