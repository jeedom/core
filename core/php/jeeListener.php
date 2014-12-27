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

require_once dirname(__FILE__) . "/core.inc.php";

if (isset($argv)) {
    foreach ($argv as $arg) {
        $argList = explode('=', $arg);
        if (isset($argList[0]) && isset($argList[1])) {
            $_GET[$argList[0]] = $argList[1];
        }
    }
}
if (init('listener_id') == '') {
    foreach (cmd::byValue(init('event_id'), 'info') as $cmd) {
        $cmd->event($cmd->execute(), init('loop', 0));
    }
} else {
    try {
        set_time_limit(config::byKey('maxExecTimeScript', 60));

        $listener_id = init('listener_id');
        if ($listener_id == '') {
            throw new Exception(__('Le listener ID ne peut être vide', __FILE__));
        }
        $listener = listener::byId($listener_id);
        if (!is_object($listener)) {
            throw new Exception(__('Listener non trouvé : ', __FILE__) . $listener_id);
        }
        $option = array();
        if (count($listener->getOption()) > 0) {
            $option = $listener->getOption();
        }
        $option['event_id'] = init('event_id');
        $option['value'] = init('value');
        if ($listener->getClass() != '') {
            $class = $listener->getClass();
            $function = $listener->getFunction();
            if (class_exists($class) && method_exists($class, $function)) {
                $class::$function($option);
            } else {
                log::add('listener', 'error', __('[Erreur] Classe ou fonction non trouvée ', __FILE__) . $listener->getName());
                die();
            }
        } else {
            $function = $listener->getFunction();
            if (function_exists($function)) {
                $function($option);
            } else {
                log::add('listener', 'error', __('[Erreur] Non trouvée ', __FILE__) . $listener->getName());
                die();
            }
        }
    } catch (Exception $e) {
        log::add(init('plugin_id', 'plugin'), 'error', $e->getMessage());
        die($e->getMessage());
    }
}
?>

