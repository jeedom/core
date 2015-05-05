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

echo "[START CONSISTENCY]\n";
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

	$crons = cron::all();
	if (is_array($crons)) {
		if (class_exists('Cron\CronExpression')) {
			foreach ($crons as $cron) {
				$c = new Cron\CronExpression($cron->getSchedule(), new Cron\FieldFactory);
				try {
					if (!$c->isDue()) {
						$c->getNextRunDate();
					}
				} catch (Exception $ex) {
					echo "Suppression de  : " . $cron->getName() . ' car pas de lancement prévu';
					$cron->remove();
				}
			}
		}
	}

	$cron = cron::byClassAndFunction('jeedom', 'persist');
	if (is_object($cron)) {
		$cron->remove();
	}

	$cron = cron::byClassAndFunction('plugin', 'cron');
	if (is_object($cron)) {
		$cron->remove();
	}

	$cron = cron::byClassAndFunction('plugin', 'cronDaily');
	if (!is_object($cron)) {
		echo "Création de plugin::cronDaily\n";
		$cron = new cron();
		$cron->setClass('plugin');
		$cron->setFunction('cronDaily');
		$cron->setSchedule('00 00 * * * *');
		$cron->setTimeout(5);
		$cron->save();
	}

	$cron = cron::byClassAndFunction('jeedom', 'backup');
	if (!is_object($cron)) {
		echo "Création de jeedom::backup\n";
		$cron = new cron();
		$cron->setClass('jeedom');
		$cron->setFunction('backup');
		$cron->setSchedule('00 02 * * *');
		$cron->setTimeout(60);
		$cron->save();
	}

	$cron = cron::byClassAndFunction('plugin', 'cronHourly');
	if (!is_object($cron)) {
		echo "Création de plugin::cronHourly\n";
		$cron = new cron();
		$cron->setClass('plugin');
		$cron->setFunction('cronHourly');
		$cron->setSchedule('00 * * * * *');
		$cron->setTimeout(5);
		$cron->save();
	}

	$cron = cron::byClassAndFunction('history', 'historize');
	if (!is_object($cron)) {
		echo "Création de history::historize\n";
		$cron = new cron();
		$cron->setClass('history');
		$cron->setFunction('historize');
		$cron->setSchedule('*/5 * * * * *');
		$cron->setTimeout(5);
		$cron->save();
	}

	$cron = cron::byClassAndFunction('scenario', 'check');
	if (!is_object($cron)) {
		echo "Création de scenario::check\n";
		$cron = new cron();
		$cron->setClass('scenario');
		$cron->setFunction('check');
		$cron->setSchedule('* * * * * *');
		$cron->setTimeout(5);
		$cron->save();
	}

	$cron = cron::byClassAndFunction('cmd', 'collect');
	if (!is_object($cron)) {
		echo "Création de cmd::collect\n";
		$cron = new cron();
		$cron->setClass('cmd');
		$cron->setFunction('collect');
		$cron->setSchedule('*/5 * * * * *');
		$cron->setTimeout(5);
		$cron->save();
	}

	$cron = cron::byClassAndFunction('history', 'archive');
	if (!is_object($cron)) {
		echo "Création de history::archive\n";
		$cron = new cron();
		$cron->setClass('history');
		$cron->setFunction('archive');
		$cron->setSchedule('05 00 * * * *');
		$cron->setTimeout(60);
		$cron->save();
	}

	$cron = cron::byClassAndFunction('jeedom', 'cron');
	if (!is_object($cron)) {
		echo "Création de jeedom::cron\n";
		$cron = new cron();
		$cron->setClass('jeedom');
		$cron->setFunction('cron');
		$cron->setSchedule('* * * * * *');
		$cron->setTimeout(60);
		$cron->save();
	}

	$dynamic_apache_path = dirname(__FILE__) . '/../core/config/apache_jeedom_dynamic_rules';
	if (!file_exists($dynamic_apache_path)) {
		touch($dynamic_apache_path);
	}

	cache::deleteBySearch('widgetHtml');
	cache::deleteBySearch('cmdWidgetdashboard');
	cache::deleteBySearch('cmdWidgetmobile');
	cache::deleteBySearch('scenarioHtmldashboard');
} catch (Exception $e) {
	echo "Error : ";
	echo $e->getMessage();
}
echo "[END CONSISTENCY]\n";
