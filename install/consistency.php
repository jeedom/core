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

require_once dirname(__DIR__) . '/core/php/console.php';

set_time_limit(1800);

try {
	if (function_exists('opcache_reset')) {
		opcache_reset();
	}
} catch (\Exception $e) {
}

echo "[START CONSISTENCY]\n";
try {
	if (file_exists(__DIR__ . '/database.php')) {
		$output = shell_exec('php ' . __DIR__ . '/database.php');
		echo $output;
	}
} catch (Exception $ex) {
	echo "***ERREUR*** " . $ex->getMessage() . "\n";
}
try {
	require_once __DIR__ . '/../core/php/core.inc.php';

	if (method_exists('system', 'checkAndInstall')) {
		try {
			echo "Check jeedom package...";
			system::checkAndInstall(json_decode(file_get_contents(__DIR__ . '/packages.json'), true), true);
			echo "OK\n";
		} catch (Exception $ex) {
			echo "***ERREUR*** " . $ex->getMessage() . "\n";
		}
	}

	if (method_exists('DB', 'compareAndFix')) {
		try {
			echo "Check jeedom database...";
			DB::compareAndFix(json_decode(file_get_contents(__DIR__ . '/database.json'), true), 'all', true);
			echo "OK\n";
		} catch (Exception $ex) {
			echo "***ERREUR*** " . $ex->getMessage() . "\n";
		}
	}
	if (config::byKey('object:summary') == '' || !is_array(config::byKey('object:summary'))) {
		config::save(
			'object:summary',
			array(
				'security' => array('key' => 'security', 'name' => 'Alerte', 'calcul' => 'sum', 'icon' => '<i class="icon jeedom-alerte2"></i>', 'unit' => '', 'count' => 'binary', 'allowDisplayZero' => false),
				'motion' => array('key' => 'motion', 'name' => 'Mouvement', 'calcul' => 'sum', 'icon' => '<i class="icon jeedom-mouvement"></i>', 'unit' => '', 'count' => 'binary', 'allowDisplayZero' => false),
				'door' => array('key' => 'door', 'name' => 'Porte', 'calcul' => 'sum', 'icon' => '<i class="icon jeedom-porte-ouverte"></i>', 'unit' => '', 'count' => 'binary', 'allowDisplayZero' => false),
				'windows' => array('key' => 'windows', 'name' => 'Fenêtre', 'calcul' => 'sum', 'icon' => '<i class="icon jeedom-fenetre-ouverte"></i>', 'unit' => '', 'count' => 'binary', 'allowDisplayZero' => false),
				'shutter' => array('key' => 'shutter', 'name' => 'Volet', 'calcul' => 'sum', 'icon' => '<i class="icon jeedom-volet-ouvert"></i>', 'unit' => '', 'count' => 'binary', 'allowDisplayZero' => false),
				'light' => array('key' => 'light', 'name' => 'Lumière', 'calcul' => 'sum', 'icon' => '<i class="icon jeedom-lumiere-on"></i>', 'unit' => '', 'count' => 'binary', 'allowDisplayZero' => false),
				'outlet' => array('key' => 'outlet', 'name' => 'Prise', 'calcul' => 'sum', 'icon' => '<i class="icon jeedom-prise"></i>', 'unit' => '', 'count' => 'binary', 'allowDisplayZero' => false),
				'temperature' => array('key' => 'temperature', 'name' => 'Température', 'calcul' => 'avg', 'icon' => '<i class="icon divers-thermometer31"></i>', 'unit' => '°C', 'allowDisplayZero' => true),
				'humidity' => array('key' => 'humidity', 'name' => 'Humidité', 'calcul' => 'avg', 'icon' => '<i class="fa fa-tint"></i>', 'unit' => '%', 'allowDisplayZero' => true),
				'luminosity' => array('key' => 'luminosity', 'name' => 'Luminosité', 'calcul' => 'avg', 'icon' => '<i class="icon meteo-soleil"></i>', 'unit' => 'lx', 'allowDisplayZero' => false),
				'power' => array('key' => 'power', 'name' => 'Puissance', 'calcul' => 'sum', 'icon' => '<i class="fa fa-bolt"></i>', 'unit' => 'W', 'allowDisplayZero' => false),
			)
		);
	}

	$crons = cron::all();
	if (is_array($crons)) {
		if (class_exists('Cron\CronExpression')) {
			foreach ($crons as $cron) {
				$c = new Cron\CronExpression(checkAndFixCron($cron->getSchedule()), new Cron\FieldFactory);
				try {
					if (!$c->isDue()) {
						$c->getNextRunDate();
					}
				} catch (Exception $ex) {
					echo "Suppression de  : " . $cron->getName() . ' car il n\'y a pas de lancement prévu' . "\n";
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
	if (method_exists('utils', 'attrChanged')) {
		$cron = cron::byClassAndFunction('plugin', 'cronDaily');
		if (!is_object($cron)) {
			echo "Create plugin::cronDaily\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('cronDaily');
		$cron->setSchedule('00 00 * * *');
		$cron->setTimeout(240);
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('jeedom', 'backup');
		if (!is_object($cron)) {
			echo "Create jeedom::backup\n";
			$cron = new cron();
			$cron->setClass('jeedom');
			$cron->setFunction('backup');
			$cron->setSchedule(rand(10, 59) . ' 0' . rand(0, 7) . ' * * *');
			$cron->setEnable(1);
			$cron->setDeamon(0);
			$cron->setTimeout(60);
			$cron->save();
		}

		$cron = cron::byClassAndFunction('plugin', 'cronHourly');
		if (!is_object($cron)) {
			echo "Create plugin::cronHourly\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('cronHourly');
		$cron->setSchedule('00 * * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(60);
		$cron->save();

		$cron = cron::byClassAndFunction('scenario', 'check');
		if (!is_object($cron)) {
			echo "Create scenario::check\n";
			$cron = new cron();
		}
		$cron->setClass('scenario');
		$cron->setFunction('check');
		$cron->setSchedule('* * * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(30);
		$cron->save();

		$cron = cron::byClassAndFunction('scenario', 'control');
		if (!is_object($cron)) {
			echo "Create scenario::control\n";
			$cron = new cron();
		}
		$cron->setClass('scenario');
		$cron->setFunction('control');
		$cron->setSchedule('* * * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(30);
		$cron->save();

		$cron = cron::byClassAndFunction('jeedom', 'cronDaily');
		if (!is_object($cron)) {
			echo "Create jeedom::cronDaily\n";
			$cron = new cron();
		}
		$cron->setClass('jeedom');
		$cron->setFunction('cronDaily');
		$cron->setSchedule(rand(0, 59) . ' ' . rand(0, 3) . ' * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(240);
		$cron->save();

		$cron = cron::byClassAndFunction('jeedom', 'cronHourly');
		if (!is_object($cron)) {
			echo "Create jeedom::cronHourly\n";
			$cron = new cron();
		}
		$cron->setClass('jeedom');
		$cron->setFunction('cronHourly');
		$cron->setSchedule(rand(0, 59) . ' * * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(60);
		$cron->save();

		$cron = cron::byClassAndFunction('jeedom', 'cron5');
		if (!is_object($cron)) {
			echo "Create jeedom::cron5\n";
			$cron = new cron();
		}
		$cron->setClass('jeedom');
		$cron->setFunction('cron5');
		$cron->setSchedule('*/5 * * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(5);
		$cron->save();

		$cron = cron::byClassAndFunction('jeedom', 'cron10');
		if (!is_object($cron)) {
			echo "Create jeedom::cron10\n";
			$cron = new cron();
		}
		$cron->setClass('jeedom');
		$cron->setFunction('cron10');
		$cron->setSchedule('*/10 * * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(10);
		$cron->save();

		$cron = cron::byClassAndFunction('jeedom', 'cron');
		if (!is_object($cron)) {
			echo "Create jeedom::cron\n";
			$cron = new cron();
		}
		$cron->setClass('jeedom');
		$cron->setFunction('cron');
		$cron->setSchedule('* * * * *');
		$cron->setTimeout(2);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('plugin', 'cron');
		if (!is_object($cron)) {
			echo "Create plugin::cron\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('cron');
		$cron->setSchedule('* * * * *');
		$cron->setTimeout(2);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('plugin', 'cron5');
		if (!is_object($cron)) {
			echo "Create plugin::cron5\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('cron5');
		$cron->setSchedule('*/5 * * * *');
		$cron->setTimeout(5);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('plugin', 'cron10');
		if (!is_object($cron)) {
			echo "Create plugin::cron10\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('cron10');
		$cron->setSchedule('*/10 * * * *');
		$cron->setTimeout(10);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('plugin', 'cron15');
		if (!is_object($cron)) {
			echo "Create plugin::cron15\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('cron15');
		$cron->setSchedule('*/15 * * * *');
		$cron->setTimeout(15);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('plugin', 'cron30');
		if (!is_object($cron)) {
			echo "Create plugin::cron30\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('cron30');
		$cron->setSchedule('*/30 * * * *');
		$cron->setTimeout(30);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('plugin', 'checkDeamon');
		if (!is_object($cron)) {
			echo "Create plugin::checkDeamon\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('checkDeamon');
		$cron->setSchedule('*/5 * * * *');
		$cron->setTimeout(5);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('cache', 'persist');
		if (!is_object($cron)) {
			echo "Create cache::persist\n";
			$cron = new cron();
		}
		$cron->setClass('cache');
		$cron->setFunction('persist');
		$cron->setSchedule('*/30 * * * *');
		$cron->setTimeout(30);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('history', 'archive');
		if (!is_object($cron)) {
			echo "Create history::archive\n";
			$cron = new cron();
		}
		$cron->setClass('history');
		$cron->setFunction('archive');
		$cron->setSchedule('00 5 * * *');
		$cron->setTimeout(240);
		$cron->setDeamon(0);
		$cron->save();

		$cron = cron::byClassAndFunction('plugin', 'heartbeat');
		if (!is_object($cron)) {
			echo "Create plugin::heartbeat\n";
			$cron = new cron();
		}
		$cron->setClass('plugin');
		$cron->setFunction('heartbeat');
		$cron->setSchedule('*/5 * * * *');
		$cron->setEnable(1);
		$cron->setDeamon(0);
		$cron->setTimeout(10);
		$cron->save();

		if (!file_exists(__DIR__ . '/../plugins')) {
			mkdir(__DIR__ . '/../plugins');
		}
		try {
			echo "\nCheck filesystem right...";
			jeedom::cleanFileSystemRight();
			echo "OK\n";
		} catch (Exception $e) {
			echo "NOK\n";
		}

		config::save('hardware_name', '');
		if (config::byKey('api') == '') {
			config::save('api', config::genKey());
		}
		if (file_exists(__DIR__ . '/../core/nodeJS')) {
			shell_exec(system::getCmdSudo() . 'rm -rf ' . __DIR__ . '/../core/nodeJS');
		}
		if (file_exists(__DIR__ . '/../script/ngrok')) {
			shell_exec(system::getCmdSudo() . 'rm -rf ' . __DIR__ . '/../script/ngrok');
		}
		try {
			if (method_exists('cache', 'flushWidget')) {
				cache::flushWidget();
			}
		} catch (Exception $e) {
		} catch (Error $e) {
		}

		foreach (jeeObject::all() as $object) {
			try {
				$object->save();
			} catch (Exception $exc) {
			}
		}

		foreach (cmd::all() as $cmd) {
			try {
				$changed = false;
				if ($cmd->getConfiguration('jeedomCheckCmdCmdActionId') != '') {
					$cmd->setConfiguration('jeedomCheckCmdCmdActionId', '');
					$changed = true;
				}
				if (trim($cmd->getTemplate('dashboard')) != '' && strpos($cmd->getTemplate('dashboard'), '::') === false) {
					$cmd->setTemplate('dashboard', 'core::' . $cmd->getTemplate('dashboard'));
					$changed = true;
				}
				if (trim($cmd->getTemplate('mobile')) != '' && strpos($cmd->getTemplate('mobile'), '::') === false) {
					$cmd->setTemplate('mobile', 'core::' . $cmd->getTemplate('mobile'));
					$changed = true;
				}
				if ($changed) {
					$cmd->save(true);
				}
			} catch (Exception $exc) {
			}
		}
	}
	if (!file_exists(__DIR__ . '/../data/php/user.function.class.php')) {
		copy(__DIR__ . '/../data/php/user.function.class.sample.php', __DIR__ . '/../data/php/user.function.class.php');
	}
	if (!file_exists('/etc/systemd/system/mariadb.service.d/jeedom.conf')) {
		$cmd = '';
		if (!file_exists('/etc/systemd/system/mariadb.service.d')) {
			$cmd .= 'sudo mkdir /etc/systemd/system/mariadb.service.d;';
		}
		$cmd .= 'sudo chmod 777 -R /etc/systemd/system/mariadb.service.d;';
		$cmd .= 'sudo echo "[Service]" > /etc/systemd/system/mariadb.service.d/jeedom.conf;';
		$cmd .= 'sudo echo "Restart=always" >> /etc/systemd/system/mariadb.service.d/jeedom.conf;';
		$cmd .= 'sudo systemctl daemon-reload;';
		exec($cmd);
	}

	if (!file_exists(__DIR__ . '/../.htaccess_custom')) {
		shell_exec('sudo rm ' . __DIR__ . '/../.htaccess');
		shell_exec('sudo cp ' . __DIR__ . '/../.htaccess_custom ' . __DIR__ . '/../.htaccess');
	}

	cache::set('hour', strtotime('UTC'));
} catch (Exception $e) {
	echo "\nError : ";
	echo $e->getMessage();
}
echo "[END CONSISTENCY]\n";
