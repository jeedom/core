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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class log {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/**
	 * Ajoute un message dans les log et fait en sorte qu'il n'y
	 * ai jamais plus de 1000 lignes
	 * @param string $_type type du message à mettre dans les log
	 * @param string $_message message à mettre dans les logs
	 */
	public static function add($_log, $_type, $_message, $_logicalId = '') {
		$logger = new Logger($_log);
		$logger->pushHandler(new StreamHandler(self::getPathToLog($_log), config::byKey('log::level')));
		switch (strtolower($_type)) {
			case 'debug':
				$logger->addDebug($_message);
				break;
			case 'info':
				$logger->addInfo($_message);
				break;
			case 'notice':
				$logger->addNotice($_message);
				break;
			case 'warning':
				$logger->addWarning($_message);
				break;
			case 'error':
				$logger->addError($_message);
				if (config::byKey('addMessageForErrorLog') == 1) {
					@message::add($_log, $_message, '', $_logicalId);
				}
				break;
		}
	}

	public static function chunk($_log = '') {
		$maxLineLog = config::byKey('maxLineLog');
		if ($maxLineLog < 200) {
			$maxLineLog = 200;
		}
		if ($_log != '') {
			self::chunkLog(self::getPathToLog($_log), $maxLineLog);
		} else {
			$logs = ls(dirname(__FILE__) . '/../../log/', '*');
			foreach ($logs as $log) {
				$path = dirname(__FILE__) . '/../../log/' . $log;
				if (is_file($path)) {
					self::chunkLog($path, $maxLineLog);
				}
			}
			$logs = ls(dirname(__FILE__) . '/../../log/scenarioLog', '*');
			foreach ($logs as $log) {
				$path = dirname(__FILE__) . '/../../log/scenarioLog/' . $log;
				if (is_file($path)) {
					self::chunkLog($path, $maxLineLog);
				}
			}
		}
	}

	public static function chunkLog($_path, $maxLineLog = 500) {
		shell_exec('echo "$(head -n ' . $maxLineLog . ' ' . $_path . ')" > ' . $_path);
		@chown($_path, 'www-data');
		@chgrp($_path, 'www-data');
		@chmod($_path, 0777);
	}

	public static function getPathToLog($_log = 'core') {
		return dirname(__FILE__) . '/../../log/' . $_log;
	}

	/**
	 * Vide le fichier de log
	 */
	public static function clear($_log) {
		$path = self::getPathToLog($_log);
		if (file_exists($path) && strpos($_log, 'nginx.error') === false) {
			$log = fopen($path, "w");
			ftruncate($log, 0);
			fclose($log);
		}
		if (strpos($_log, 'nginx.error') !== false) {
			shell_exec('cat /dev/null > ' . $path);
		}
		return true;
	}

	/**
	 * Vide le fichier de log
	 */
	public static function remove($_log) {
		$path = self::getPathToLog($_log);
		if (file_exists($path) && strpos($_log, 'nginx.error') === false) {
			unlink($path);
		}
		if (strpos($_log, 'nginx.error') !== false) {
			shell_exec('cat /dev/null > ' . $path);
		}
		return true;
	}

	public static function removeAll() {
		$logs = ls(dirname(__FILE__) . '/../../log/', '*');
		foreach ($logs as $log) {
			$path = dirname(__FILE__) . '/../../log/' . $log;
			if (strpos($log, 'nginx.error') === false && !is_dir($path)) {
				unlink($path);
			} else {
				shell_exec('cat /dev/null > ' . $path);
			}
		}
		return true;
	}

	/**
	 * Renvoi les x derniere ligne du fichier de log
	 * @param int $_maxLigne nombre de ligne voulu
	 * @return string Ligne du fichier de log
	 */
	public static function get($_log = 'core', $_begin, $_nbLines) {
		$replace = array(
			'&gt;' => '>',
			'&apos;' => '',
		);
		self::chunk($_log);
		$page = array();
		if (!file_exists($_log) || !is_file($_log)) {
			$path = self::getPathToLog($_log);
			if (!file_exists($path)) {
				return false;
			}
		} else {
			$path = $_log;
		}
		$log = new SplFileObject($path);
		if ($log) {
			$log->seek($_begin); //Seek to the begening of lines
			$linesRead = 0;
			while ($log->valid() && $linesRead != $_nbLines) {
				$line = trim($log->current()); //get current line
				if ($line != '') {
					array_unshift($page, $line);
				}
				$log->next(); //go to next line
				$linesRead++;
			}
		}
		return $page;
	}

	public static function nbLine($_log = 'core') {
		$path = self::getPathToLog($_log);
		if (!file_exists($path)) {
			return 0;
		}
		$log_file = file($path);
		return count($log_file);
	}

	public static function liste() {
		$return = array();
		foreach (ls(dirname(__FILE__) . '/../../log/', '*') as $log) {
			if (!is_dir(dirname(__FILE__) . '/../../log/' . $log)) {
				$return[] = $log;
			}
		}
		return $return;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

?>
