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

if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_GET[$argList[0]] = $argList[1];
		}
	}
}

require_once dirname(__FILE__) . "/core.inc.php";

$startTime = getmicrotime();

declare (ticks = 1);

global $SIGKILL;
$SIGKILL = false;

// gestionnaire de signaux système
function sig_handler($signo) {
	global $SIGKILL;
	$SIGKILL = true;
}

// Installation des gestionnaires de signaux
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");
pcntl_signal(SIGUSR1, "sig_handler");

if (init('cron_id') != '') {
	if (jeedom::isStarted() && config::byKey('enableCron', 'core', 1, true) == 0) {
		die(__('Tous les crons sont actuellement désactivés', __FILE__));
	}
	$datetime = date('Y-m-d H:i:s');
	$datetimeStart = strtotime('now');
	$cron = cron::byId(init('cron_id'));
	if (!is_object($cron)) {
		echo 'Cron non trouvé';
		die();
	}
	try {
		$cron->setState('run');
		$cron->setDuration('0s');
		$cron->setPID(getmypid());
		$cron->setLastRun($datetime);
		$cron->save();
		$option = $cron->getOption();
		if ($cron->getClass() != '') {
			$class = $cron->getClass();
			$function = $cron->getFunction();
			if (class_exists($class) && method_exists($class, $function)) {
				if ($cron->getDeamon() == 0) {
					$class::$function($option);
				} else {
					while (true) {
						$cycleStartTime = getmicrotime();
						$class::$function($option);
						if ($cron->getDeamonSleepTime() > 1) {
							sleep($cron->getDeamonSleepTime());
						} else {
							$cycleDuration = getmicrotime() - $cycleStartTime;
							if ($cycleDuration < $cron->getDeamonSleepTime()) {
								usleep(round(($cron->getDeamonSleepTime() - $cycleDuration) * 1000000));
							}
						}
						if ($SIGKILL) {
							die();
						}
					}
				}
			} else {
				$cron->setState('Not found');
				$cron->setPID();
				$cron->setServer('');
				$cron->save();
				log::add('cron', 'error', __('[Erreur] Classe ou fonction non trouvée ', __FILE__) . $cron->getName());
				die();
			}
		} else {
			$function = $cron->getFunction();
			if (function_exists($function)) {
				if ($cron->getDeamon() == 0) {
					$function($option);
				} else {
					while (true) {
						$cycleStartTime = getmicrotime();
						$function($option);
						$cycleDuration = getmicrotime() - $cycleStartTime;
						if ($cron->getDeamonSleepTime() > 1) {
							sleep($cron->getDeamonSleepTime());
						} else {
							if ($cycleDuration < $cron->getDeamonSleepTime()) {
								usleep(round(($cron->getDeamonSleepTime() - $cycleDuration) * 1000000));
							}
						}
						if ($SIGKILL) {
							die();
						}
					}
				}
			} else {
				$cron->setState('Not found');
				$cron->setPID();
				$cron->setServer('');
				$cron->save();
				$cron->setEnable(0);
				log::add('cron', 'error', __('[Erreur] Non trouvée ', __FILE__) . $cron->getName());
				die();
			}
		}
		if ($cron->getOnce() == 1) {
			$cron->remove(false);
		} else {
			if (!$cron->refresh()) {
				die();
			}
			$cron->setState('stop');
			$cron->setPID();
			$cron->setServer('');
			$cron->setDuration(convertDuration(strtotime('now') - $datetimeStart));
			$cron->save();
		}
		die();
	} catch (Exception $e) {
		$cron->setState('error');
		$cron->setPID('');
		$cron->setServer('');
		$cron->setDuration(-1);
		$cron->save();
		$logicalId = config::genKey();
		if ($e->getCode() != 0) {
			$logicalId = $cron->getName() . '::' . $e->getCode();
		}
		echo '[Erreur] ' . $cron->getName() . ' : ' . print_r($e, true);
		log::add('cron', 'error', __('Erreur sur ', __FILE__) . $cron->getName() . ' : ' . print_r($e, true), $logicalId);
	}
} else {
	if (cron::jeeCronRun()) {
		die();
	}
	$sleepTime = config::byKey('cronSleepTime');
	$started = jeedom::isStarted();

	set_time_limit(59);
	cron::setPidFile();
	while (true) {
		if ($started && config::byKey('enableCron', 'core', 1, true) == 0) {
			die(__('Tous les crons sont actuellement désactivés', __FILE__));
		}
		foreach (cron::all() as $cron) {
			try {
				if (!$started && $cron->getClass() != 'jeedom' && $cron->getFunction() != 'cron') {
					continue;
				}

				if (!$cron->refresh()) {
					continue;
				}
				$duration = strtotime('now') - strtotime($cron->getLastRun());
				if ($cron->getEnable() == 1 && $cron->getState() != 'run' && $cron->getState() != 'starting' && $cron->getState() != 'stoping') {
					if ($cron->getDeamon() == 0) {
						if ($cron->isDue()) {
							$cron->start();
						}
					} else {
						$cron->halt();
						$cron->start();
					}
				}
				if ($cron->getState() == 'run' && ($duration / 60) >= $cron->getTimeout()) {
					$cron->stop();
				}

				switch ($cron->getState()) {
					case 'starting':
						$cron->run();
						break;
					case 'stoping':
						$cron->halt();
						if ($cron->getEnable() == 1 && $cron->getDeamon() == 1 && !$cron->running()) {
							$cron->run();
						}
						break;
				}
			} catch (Exception $e) {
				if ($cron->getOnce() != 1) {
					$cron->setState('error');
					$cron->setPID('');
					$cron->setServer('');
					$cron->setDuration(-1);
					$cron->save();
					echo __('[Erreur master] ', __FILE__) . $cron->getName() . ' : ' . print_r($e, true);
					log::add('cron', 'error', __('[Erreur master] ', __FILE__) . $cron->getName() . ' : ' . $e->getMessage());
				}
			}
		}
		if ($sleepTime > 59) {
			die();
		}
		sleep($sleepTime);
		if ((getmicrotime() - $startTime) > 59) {
			die();
		}
	}
}
?>
