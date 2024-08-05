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

function jeeQueue_errorHandler($queue, $class, $function,$datetimeStart, $e) {
	$queue->setState('error');
	$queue->setPID('');
	$queue->setCache('runtime', strtotime('now') - $datetimeStart);
	$logicalId = config::genKey();
	if ($e->getCode() != 0) {
		$logicalId = $queue->getHumanName() . '::' . $e->getCode();
	}
	echo '[Erreur] ' . $queue->getHumanName() . ' : ' . log::exception($e);
	if (isset($class) && $class != '') {
		log::add($class, 'error', __('Erreur sur', __FILE__) . ' ' . $queue->getHumanName() . ' : ' . log::exception($e), $logicalId);
	} else if (isset($function) && $function != '') {
		log::add($function, 'error', __('Erreur sur', __FILE__) . ' ' . $queue->getHumanName() . ' : ' . log::exception($e), $logicalId);
	} else {
		log::add('queue', 'error', __('Erreur sur', __FILE__) . ' ' . $queue->getHumanName() . ' : ' . log::exception($e), $logicalId);
	}
}

if (jeedom::isStarted() && config::byKey('enableQueue', 'core', 1, true) == 0) {
    die(__('Tous les queues sont actuellement désactivés', __FILE__));
}
$datetime = date('Y-m-d H:i:s');
$datetimeStart = strtotime('now');
$class = null;
$function = null;
$queue = queue::byId(init('queue_id'));
if (!is_object($queue)) {
    die();
}
try {
    $queue->setState('run');
    $queue->setPID(getmypid());
    $queue->setLastRun($datetime);
    if ($queue->getClass() != '') {
        if (class_exists($queue->getClass()) && method_exists($queue->getClass(), $queue->getFunction())) {
            try {
                call_user_func_array($queue->getClass() . '::' . $queue->getFunction(), $queue->getArguments());
            } catch (\Throwable $th) {
                log::add('queue', 'error', __('[Erreur] ', __FILE__) . ' ' . $queue->getHumanName().' => '.$th->getMessage());
                $queue->setState('error');
                $queue->setPID();
                $queue->setCache('numberFailed',$queue->getCache('numberFailed',0)+1);
                die();
            }
        } else {
            $queue->setState('Not found');
            $queue->setPID();
            log::add('queue', 'error', __('[Erreur] Non trouvée', __FILE__) . ' ' . $queue->getHumanName());
            die();
        }
    } else {
        if (function_exists($queue->getFunction())) {
            try {
                call_user_func_array($queue->getFunction(), $queue->getArguments());
            } catch (\Throwable $th) {
                log::add('queue', 'error', __('[Erreur] ', __FILE__) . ' ' . $queue->getHumanName().' => '.$th->getMessage());
                $queue->setState('error');
                $queue->setPID();
                $queue->setCache('numberFailed',$queue->getCache('numberFailed',0)+1);
                die();
            }
        } else {
            $queue->setState('Not found');
            $queue->setPID();
            log::add('queue', 'error', __('[Erreur] Non trouvée', __FILE__) . ' ' . $queue->getHumanName());
            die();
        }
    }
    $queue->remove(false);
    die();
} catch (Exception $e) {
    jeeQueue_errorHandler($queue, $class, $function,$datetimeStart, $e);
} catch (Error $e) {
    jeeQueue_errorHandler($queue, $class, $function,$datetimeStart, $e);
}
