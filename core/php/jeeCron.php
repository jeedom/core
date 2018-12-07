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
	header("Statut: 404 Page non trouvée");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Non trouvé</h1>";
	echo "La page que vous demandez ne peut être trouvée.";
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

if (init('cron_id') != '') {
	if (jeedom::isStarted() && config::byKey('enableCron', 'core', 1, true) == 0) {
		die(__('Tous les crons sont actuellement désactivés', __FILE__));
	}
	$datetime = date('Y-m-d H:i:s');
	$datetimeStart = strtotime('now');
	$cron = cron::byId(init('cron_id'));
	if (!is_object($cron)) {
		die();
	}

	try {
		$cron->setState('run');
		$cron->setPID(getmypid());
		$cron->setLastRun($datetime);
		$option = $cron->getOption();
		if ($cron->getClass() != '') {
			$class = $cron->getClass();
			$function = $cron->getFunction();
			if (class_exists($class) && method_exists($class, $function)) {
				if ($cron->getDeamon() == 0) {
					if ($option !== null) {
						$class::$function($option);
					} else {
						$class::$function();
					}
				} else {
					$gc = 0;
					while (true) {
						$cycleStartTime = getmicrotime();
						if ($option !== null) {
							$class::$function($option);
						} else {
							$class::$function();
						}
						$gc++;
						if ($gc > 30) {
							gc_collect_cycles();
							$gc = 0;
						}
						if ($cron->getDeamonSleepTime() > 1) {
							sleep($cron->getDeamonSleepTime());
						} else {
							$cycleDuration = getmicrotime() - $cycleStartTime;
							if ($cycleDuration < $cron->getDeamonSleepTime()) {
								usleep(round(($cron->getDeamonSleepTime() - $cycleDuration) * 1000000));
							}
						}
					}
				}
			} else {
				$cron->setState('Not found');
				$cron->setPID();
				$cron->setCache('runtime', strtotime('now') - $datetimeStart);
				log::add('cron', 'error', __('[Erreur] Classe ou fonction non trouvée ', __FILE__) . $cron->getName());
				die();
			}
		} else {

			$function = $cron->getFunction();
			if (function_exists($function)) {
				if ($cron->getDeamon() == 0) {
					if ($option !== null) {
						$function($option);
					} else {
						$function();
					}
				} else {
					$gc = 0;
					while (true) {
						$cycleStartTime = getmicrotime();
						if ($option !== null) {
							$function($option);
						} else {
							$function();
						}
						$gc++;
						if ($gc > 30) {
							gc_collect_cycles();
							$gc = 0;
						}
						$cycleDuration = getmicrotime() - $cycleStartTime;
						if ($cron->getDeamonSleepTime() > 1) {
							sleep($cron->getDeamonSleepTime());
						} else {
							if ($cycleDuration < $cron->getDeamonSleepTime()) {
								usleep(round(($cron->getDeamonSleepTime() - $cycleDuration) * 1000000));
							}
						}
					}
				}
			} else {
				$cron->setState('Not found');
				$cron->setPID();
				$cron->setCache('runtime', strtotime('now') - $datetimeStart);
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
			$cron->setCache('runtime', strtotime('now') - $datetimeStart);
		}
		die();
	} catch (Exception $e) {
		$cron->setState('error');
		$cron->setPID('');
		$cron->setCache('runtime', strtotime('now') - $datetimeStart);
		$logicalId = config::genKey();
		if ($e->getCode() != 0) {
			$logicalId = $cron->getName() . '::' . $e->getCode();
		}
		echo '[Erreur] ' . $cron->getName() . ' : ' . log::exception($e);

		if (isset($class) && $class != '') {
			log::add($class, 'error', __('Erreur sur ', __FILE__) . $cron->getName() . ' : ' . log::exception($e), $logicalId);
		} else if (isset($function) && $function != '') {
			log::add($function, 'error', __('Erreur sur ', __FILE__) . $cron->getName() . ' : ' . log::exception($e), $logicalId);
		} else {
			log::add('cron', 'error', __('Erreur sur ', __FILE__) . $cron->getName() . ' : ' . log::exception($e), $logicalId);
		}
	} catch (Error $e) {
		$cron->setState('error');
		$cron->setPID('');
		$cron->setCache('runtime', strtotime('now') - $datetimeStart);
		$logicalId = config::genKey();
		if ($e->getCode() != 0) {
			$logicalId = $cron->getName() . '::' . $e->getCode();
		}
		echo '[Erreur] ' . $cron->getName() . ' : ' . log::exception($e);
		if (isset($class) && $class != '') {
			log::add($class, 'error', __('Erreur sur ', __FILE__) . $cron->getName() . ' : ' . log::exception($e), $logicalId);
		} else if (isset($function) && $function != '') {
			log::add($function, 'error', __('Erreur sur ', __FILE__) . $cron->getName() . ' : ' . log::exception($e), $logicalId);
		} else {
			log::add('cron', 'error', __('Erreur sur ', __FILE__) . $cron->getName() . ' : ' . log::exception($e), $logicalId);
		}
	}
} else {
	if (cron::jeeCronRun()) {
		die();
	}
	$started = jeedom::isStarted();

	set_time_limit(59);
	cron::setPidFile();

	if ($started && config::byKey('enableCron', 'core', 1, true) == 0) {
		die(__('Tous les crons sont actuellement désactivés', __FILE__));
	}
	foreach (cron::all() as $cron) {
		try {
			if ($cron->getDeamon() == 1) {
				$cron->refresh();
				continue;
			}
			if (!$started && $cron->getClass() != 'jeedom' && $cron->getFunction() != 'cron') {
				continue;
			}
			if (!$cron->refresh()) {
				continue;
			}
			$duration = strtotime('now') - strtotime($cron->getLastRun());
			if ($cron->getEnable() == 1 && $cron->getState() != 'run' && $cron->getState() != 'starting' && $cron->getState() != 'stoping') {
				if ($cron->isDue()) {
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
					break;
			}
		} catch (Exception $e) {
			if ($cron->getOnce() != 1) {
				$cron->setState('error');
				$cron->setPID('');
				echo __('[Erreur master] ', __FILE__) . $cron->getName() . ' : ' . log::exception($e);
				log::add('cron', 'error', __('[Erreur master] ', __FILE__) . $cron->getName() . ' : ' . $e->getMessage());
			}
		} catch (Error $e) {
			if ($cron->getOnce() != 1) {
				$cron->setState('error');
				$cron->setPID('');
				echo __('[Erreur master] ', __FILE__) . $cron->getName() . ' : ' . log::exception($e);
				log::add('cron', 'error', __('[Erreur master] ', __FILE__) . $cron->getName() . ' : ' . $e->getMessage());
			}
		}
	}
}
