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

	$cron = cron::byClassAndFunction('history', 'historize');
	if (is_object($cron)) {
		$cron->remove();
	}
	$cron = cron::byClassAndFunction('cmd', 'collect');
	if (is_object($cron)) {
		$cron->remove();
	}

	$cron = cron::byClassAndFunction('jeedom', 'updateSystem');
	if (is_object($cron)) {
		$cron->remove();
	}

	$cron = cron::byClassAndFunction('jeedom', 'checkAndCollect');
	if (is_object($cron)) {
		$cron->remove();
	}

	$cron = cron::byClassAndFunction('DB', 'optimize');
	if (is_object($cron)) {
		$cron->remove();
	}

	$cron = cron::byClassAndFunction('plugin', 'cronDaily');
	if (!is_object($cron)) {
		echo "Création de plugin::cronDaily\n";
		$cron = new cron();
	}
	$cron->setClass('plugin');
	$cron->setFunction('cronDaily');
	$cron->setSchedule('00 00 * * * *');
	$cron->setTimeout(240);
	$cron->setEnable(1);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('jeedom', 'backup');
	if (!is_object($cron)) {
		echo "Création de jeedom::backup\n";
		$cron = new cron();
	}
	$cron->setClass('jeedom');
	$cron->setFunction('backup');
	$cron->setSchedule('00 02 * * *');
	$cron->setEnable(1);
	$cron->setDeamon(0);
	$cron->setTimeout(60);
	$cron->save();

	$cron = cron::byClassAndFunction('plugin', 'cronHourly');
	if (!is_object($cron)) {
		echo "Création de plugin::cronHourly\n";
		$cron = new cron();
	}
	$cron->setClass('plugin');
	$cron->setFunction('cronHourly');
	$cron->setSchedule('00 * * * * *');
	$cron->setEnable(1);
	$cron->setDeamon(0);
	$cron->setTimeout(60);
	$cron->save();

	$cron = cron::byClassAndFunction('scenario', 'check');
	if (!is_object($cron)) {
		echo "Création de scenario::check\n";
		$cron = new cron();
	}
	$cron->setClass('scenario');
	$cron->setFunction('check');
	$cron->setSchedule('* * * * * *');
	$cron->setEnable(1);
	$cron->setDeamon(0);
	$cron->setTimeout(30);
	$cron->save();

	$cron = cron::byClassAndFunction('jeedom', 'cronDaily');
	if (!is_object($cron)) {
		echo "Création de jeedom::cronDaily\n";
		$cron = new cron();
	}
	$cron->setClass('jeedom');
	$cron->setFunction('cronDaily');
	$cron->setSchedule('00 00 * * * *');
	$cron->setEnable(1);
	$cron->setDeamon(0);
	$cron->setTimeout(240);
	$cron->save();

	$cron = cron::byClassAndFunction('jeedom', 'cron5');
	if (!is_object($cron)) {
		echo "Création de jeedom::cron5\n";
		$cron = new cron();
	}
	$cron->setClass('jeedom');
	$cron->setFunction('cron5');
	$cron->setSchedule('*/5 * * * * *');
	$cron->setEnable(1);
	$cron->setDeamon(0);
	$cron->setTimeout(5);
	$cron->save();

	$cron = cron::byClassAndFunction('jeedom', 'cron');
	if (!is_object($cron)) {
		echo "Création de jeedom::cron\n";
		$cron = new cron();
	}
	$cron->setClass('jeedom');
	$cron->setFunction('cron');
	$cron->setSchedule('* * * * * *');
	$cron->setTimeout(2);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('plugin', 'cron');
	if (!is_object($cron)) {
		echo "Création de plugin::cron\n";
		$cron = new cron();
	}
	$cron->setClass('plugin');
	$cron->setFunction('cron');
	$cron->setSchedule('* * * * * *');
	$cron->setTimeout(2);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('plugin', 'cron5');
	if (!is_object($cron)) {
		echo "Création de plugin::cron5\n";
		$cron = new cron();
	}
	$cron->setClass('plugin');
	$cron->setFunction('cron5');
	$cron->setSchedule('*/5 * * * * *');
	$cron->setTimeout(5);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('plugin', 'cron15');
	if (!is_object($cron)) {
		echo "Création de plugin::cron15\n";
		$cron = new cron();
	}
	$cron->setClass('plugin');
	$cron->setFunction('cron15');
	$cron->setSchedule('*/15 * * * * *');
	$cron->setTimeout(15);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('plugin', 'cron30');
	if (!is_object($cron)) {
		echo "Création de plugin::cron30\n";
		$cron = new cron();
	}
	$cron->setClass('plugin');
	$cron->setFunction('cron30');
	$cron->setSchedule('*/30 * * * * *');
	$cron->setTimeout(30);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('plugin', 'checkDeamon');
	if (!is_object($cron)) {
		echo "Création de plugin::checkDeamon\n";
		$cron = new cron();
	}
	$cron->setClass('plugin');
	$cron->setFunction('checkDeamon');
	$cron->setSchedule('*/5 * * * * *');
	$cron->setTimeout(5);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('cache', 'persist');
	if (!is_object($cron)) {
		echo "Création de cache::persist\n";
		$cron = new cron();
	}
	$cron->setClass('cache');
	$cron->setFunction('persist');
	$cron->setSchedule('*/30 * * * * *');
	$cron->setTimeout(30);
	$cron->setDeamon(0);
	$cron->save();

	$cron = cron::byClassAndFunction('history', 'archive');
	if (!is_object($cron)) {
		echo "Création de history::archive\n";
		$cron = new cron();
	}
	$cron->setClass('history');
	$cron->setFunction('archive');
	$cron->setSchedule('00 5 * * * *');
	$cron->setTimeout(240);
	$cron->setDeamon(0);
	$cron->save();

	if (!file_exists('/usr/local/share/ca-certificates/root_market.crt') && file_exists('/usr/local/share/ca-certificates')) {
		echo 'Ajout du certificat du market...';
		shell_exec('sudo cp ' . dirname(__FILE__) . '/../script/root_market.crt /usr/local/share/ca-certificates 2>&1 > /dev/null');
		shell_exec('sudo update-ca-certificates 2>&1 > /dev/null');
		echo "OK\n";
	}

	if (!file_exists(dirname(__FILE__) . '/../plugins')) {
		mkdir(dirname(__FILE__) . '/../plugins');
		@chown(dirname(__FILE__) . '/../plugins', 'www-data');
		@chgrp(dirname(__FILE__) . '/../plugins', 'www-data');
		@chmod(dirname(__FILE__) . '/../plugins', 0775);
	}

	config::save('hardware_name', '');

	if (config::byKey('api') == '') {
		config::save('api', config::genKey());
	}

	if (file_exists(dirname(__FILE__) . '/../../core/nodeJS')) {
		shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../core/nodeJS');
	}

	try {
		foreach (eqLogic::all() as $eqLogic) {
			$eqLogic->emptyCacheWidget();
		}
	} catch (Exception $e) {

	}
} catch (Exception $e) {
	echo "Error : ";
	echo $e->getMessage();
}
echo "[END CONSISTENCY]\n";
