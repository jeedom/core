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

class log {
	/*     * *************************Attributs****************************** */

	private static $logLevel;

	/*     * ***********************Methode static*************************** */

	/**
	 * Ajoute un message dans les log et fait en sorte qu'il n'y
	 * ai jamais plus de 1000 lignes
	 * @param string $_type type du message à mettre dans les log
	 * @param string $_message message à mettre dans les logs
	 */
	public static function add($_log, $_type, $_message, $_logicalId = '') {
		$_type = strtolower($_type);
		if ($_log != '' && $_type != '' && trim($_message) != '' && self::isTypeLog($_type)) {
			$_message = str_replace(";", ',', str_replace("\n", '<br/>', $_message));
			$path = self::getPathToLog($_log);
			$message = date("d-m-Y H:i:s") . ' | ' . $_type . ' | ' . $_message . "\r\n";
			$log = fopen($path, "a+");
			fputs($log, $message);
			fclose($log);
			if ($_type == 'error' && config::byKey('addMessageForErrorLog') == 1) {
				@message::add($_log, $_message, '', $_logicalId);
			}
		}
	}

	public static function chunk($_log = '') {
		$maxLineLog = config::byKey('maxLineLog');
		if ($maxLineLog < 200) {
			$maxLineLog = 200;
		}
		if ($_log != '') {
			$path = self::getPathToLog($_log);
			shell_exec('echo "$(tail -n ' . $maxLineLog . ' ' . $path . ')" > ' . $path);
			@chown($path, 'www-data');
			@chgrp($path, 'www-data');
			@chmod($path, 0777);
		} else {
			$logs = ls(dirname(__FILE__) . '/../../log/', '*');
			foreach ($logs as $log) {
				$path = dirname(__FILE__) . '/../../log/' . $log;
				if (is_file($path)) {
					shell_exec('echo "$(tail -n ' . $maxLineLog . ' ' . $path . ')" > ' . $path);
					@chown($path, 'www-data');
					@chgrp($path, 'www-data');
					@chmod($path, 0777);
				}
			}
			$logs = ls(dirname(__FILE__) . '/../../log/scenarioLog', '*');
			foreach ($logs as $log) {
				$path = dirname(__FILE__) . '/../../log/scenarioLog/' . $log;
				if (is_file($path)) {
					shell_exec('echo "$(head -n ' . $maxLineLog . ' ' . $path . ')" > ' . $path);
					@chown($path, 'www-data');
					@chgrp($path, 'www-data');
					@chmod($path, 0777);
				}
			}
		}
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
				$line = $log->current(); //get current line
				$explode = explode("|", $line);
				if (count($explode) == 3) {
					//$explode[2] = htmlspecialchars($explode[2], ENT_QUOTES, 'UTF-8');
					array_unshift($page, array_map('trim', $explode));
				} else {
					if (trim($line) != '') {
						$lineread = array();
						$lineread[0] = '';
						$lineread[1] = '';
						//$lineread[2] = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
						$lineread[2] = $line;
						array_unshift($page, $lineread);
					}
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

	private static function isTypeLog($_type) {
		if (!is_array(self::$logLevel)) {
			self::$logLevel = config::byKey('logLevel');
		}
		if (!isset(self::$logLevel[$_type]) || self::$logLevel[$_type] == 1) {
			return true;
		}
		return false;
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
