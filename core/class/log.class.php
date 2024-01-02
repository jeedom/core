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
require_once __DIR__ . '/../../core/php/core.inc.php';

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;

class log {
	/*     * *************************Constantes****************************** */

	const DEFAULT_MAX_LINE = 200;

	/*     * *************************Attributs****************************** */

	private static $logger = array();
	private static $config = null;

	/*     * ***********************Methode static*************************** */

	public static function getConfig($_key, $_default = '') {
		if (self::$config === null) {
			self::$config = array_merge(config::getLogLevelPlugin(), config::byKeys(array('log::engine', 'log::formatter', 'log::level', 'addMessageForErrorLog', 'maxLineLog')));
		}
		if (isset(self::$config[$_key])) {
			return self::$config[$_key];
		}
		return $_default;
	}

	public static function getLogger($_log) {
		if (isset(self::$logger[$_log])) {
			return self::$logger[$_log];
		}
		$formatter = new LineFormatter(str_replace('\n', "\n", self::getConfig('log::formatter')),'Y-m-d H:i:s');
		self::$logger[$_log] = new Logger($_log);
		switch (self::getConfig('log::engine')) {
			case 'SyslogHandler':
				$handler = new SyslogHandler(self::getLogLevel($_log));
				break;
			case 'SyslogUdp':
				$handler = new SyslogUdpHandler(config::byKey('log::syslogudphost'), config::byKey('log::syslogudpport'), config::byKey('log::syslogudpfacility'), self::getLogLevel($_log),true,'jeedom');
				break;
			case 'StreamHandler':
			default:
				$handler = new StreamHandler(self::getPathToLog($_log), self::getLogLevel($_log));
				break;
		}
		$handler->setFormatter($formatter);
		self::$logger[$_log]->pushHandler($handler);
		return self::$logger[$_log];
	}

	public static function getLogLevel($_log) {
		$specific_level = self::getConfig('log::level::' . $_log);
		if (is_array($specific_level)) {
			if (isset($specific_level['default']) && $specific_level['default'] == 1) {
				return self::getConfig('log::level');
			}
			foreach ($specific_level as $key => $value) {
				if (!is_numeric($key)) {
					continue;
				}
				if ($value == 1) {
					return $key;
				}
			}
		}
		return self::getConfig('log::level');
	}

	public static function convertLogLevel($_level = 100) {
		if ($_level > logger::EMERGENCY) {
			return 'none';
		}
		try {
			return strtolower(Logger::getLevelName($_level));
		} catch (Exception $e) {
		}
	}

	/**
	 * Add message and keep it less than 1000 lines
	 * @param string $_type message type (info, debug, warning, danger)
	 * @param string $_message message added into log
	 */
	public static function add($_log, $_type, $_message, $_logicalId = '') {
		if (trim($_message) == '') {
			return;
		}
		$logger = self::getLogger($_log);
		$action = strtolower($_type);
		if (method_exists($logger, $action)) {
			$logger->$action($_message);
			try {
				$level = Logger::toMonologLevel($_type);
				$action = '<a href="/index.php?v=d&p=log&logfile=' . $_log . '">' . __('Log', __FILE__) . ' ' . $_log . '</a>';
				if ($level == Logger::ERROR && self::getConfig('addMessageForErrorLog') == 1) {
					@message::add($_log, $_message, $action, $_logicalId);
				} elseif ($level > Logger::ALERT) {
					@message::add($_log, $_message, $action, $_logicalId);
				}
			} catch (Exception $e) {
			}
		}
	}

	public static function chunk($_log = '') {
		$paths = array();
		if ($_log != '') {
			$paths = array(self::getPathToLog($_log));
		} else {
			$relativeLogPaths = array('', 'scenarioLog/');
			foreach ($relativeLogPaths as $relativeLogPath) {
				$logPath = self::getPathToLog($relativeLogPath);
				$logs = ls($logPath, '*');
				foreach ($logs as $log) {
					$paths[] = $logPath . $log;
				}
			}
		}
		foreach ($paths as $path) {
			if (is_file($path)) {
				self::chunkLog($path);
			}
		}
	}

	public static function chunkLog($_path) {
		if (strpos($_path, '.htaccess') !== false) {
			return;
		}
		$maxLineLog = self::getConfig('maxLineLog');
		if ($maxLineLog < self::DEFAULT_MAX_LINE) {
			$maxLineLog = self::DEFAULT_MAX_LINE;
		}
		try {
			com_shell::execute(system::getCmdSudo() . 'chmod 664 ' . $_path . ' > /dev/null 2>&1;echo "$(tail -n ' . $maxLineLog . ' ' . $_path . ')" > ' . $_path);
		} catch (\Exception $e) {
		}
		@chown($_path, system::get('www-uid'));
		@chgrp($_path, system::get('www-gid'));
		if (filesize($_path) > (1024 * 1024 * 10)) {
			com_shell::execute(system::getCmdSudo() . 'truncate -s 0 ' . $_path);
		}
		if (filesize($_path) > (1024 * 1024 * 10)) {
			com_shell::execute(system::getCmdSudo() . 'cat /dev/null > ' . $_path);
		}
		if (filesize($_path) > (1024 * 1024 * 10)) {
			com_shell::execute(system::getCmdSudo() . ' rm -f ' . $_path);
		}
	}

	public static function getPathToLog($_log = 'core') {
		return __DIR__ . '/../../log/' . $_log;
	}

	/**
	 * Check authorisation to emptying log file
	 */
	public static function authorizeClearLog($_log, $_subPath = '') {
		$path = self::getPathToLog($_subPath . $_log);
		return !((strpos($_log, '.htaccess') !== false)
			|| (!file_exists($path) || !is_file($path)));
	}

	/**
	 * Empty log file
	 */
	public static function clear($_log) {
		if (self::authorizeClearLog($_log)) {
			$path = self::getPathToLog($_log);
			com_shell::execute(system::getCmdSudo() . 'chmod 664 ' . $path . '> /dev/null 2>&1;cat /dev/null > ' . $path);
			return true;
		}
		return;
	}

	public static function clearAll() {
		foreach (ls(self::getPathToLog(''), '*', false, array('files')) as $log)
			self::clear($log);
		return true;
	}

	/**
	 * Delete log file
	 */
	public static function remove($_log) {
		if (strpos($_log, 'nginx.error') !== false || strpos($_log, 'http.error') !== false) {
			self::clear($_log);
			return;
		}
		if (endsWith($_log, 'd') || endsWith($_log, 'd_1') || endsWith($_log, 'd_2') || endsWith($_log, 'd_3')) {
			self::clear($_log);
			return;
		}
		if (self::authorizeClearLog($_log)) {
			$path = self::getPathToLog($_log);
			com_shell::execute(system::getCmdSudo() . 'chmod 664 ' . $path . ' > /dev/null 2>&1; rm ' . $path . ' 2>&1 > /dev/null');
			return true;
		}
	}

	public static function removeAll() {
		foreach (ls(self::getPathToLog(''), '*', false, array('files')) as $log)
			self::remove($log);
		return true;
	}

	/*
	*
	* @param string $_log
	* @param int $_begin
	* @param int $_nbLines
	* @return boolean|array
	*/
	public static function get($_log = 'core', $_begin, $_nbLines) {
		$path = (!file_exists($_log) || !is_file($_log)) ? self::getPathToLog($_log) : $_log;
		if (!file_exists($path)) {
			return false;
		}
		self::chunkLog($path);
		$page = array();
		$log = new SplFileObject($path);
		if ($log) {
			$log->seek($_begin); //Seek to the begening of lines
			$linesRead = 0;
			while ($log->valid() && $linesRead != $_nbLines) {
				$line = trim($log->current()); //get current line
				if ($line != '') {
					if (function_exists('mb_convert_encoding')) {
						array_unshift($page, mb_convert_encoding($line, 'UTF-8'));
					} else {
						array_unshift($page, $line);
					}
				}
				$log->next();
				$linesRead++;
			}
		}
		return $page;
	}

	/*
	* Get the log delta from $_position to the end of the file
	* New position is stored in $_position when eof is reached
	*
	* @param string $_log Log filename (default 'core')
	* @param int $_position Bytes representing position from the begining of the file (default 0)
	* @param string $_search Text to find in log file (default '')
	* @param int $_colored Should lines be colored (default 0) [0: no ; 1: global log colors ; 2: scenario colors]
	* @param boolean $_numbered Should lines be numbered (default true)
	* @param int $_numStart At what number should lines number start (default 0)
	* @param int $_max Max number of returned lines (default 4000)
	* @return array Array containing log to append to buffer and new position for next call
	*/
	public static function getDelta($_log = 'core', $_position = 0, $_search = '', $_colored = 0, $_numbered = true, $_numStart = 0, $_max = 4000) {
		// Add path to file if needed
		$filename = (file_exists($_log) && is_file($_log)) ? $_log : self::getPathToLog($_log);
		// Check if log file exists and is readable
		if (!file_exists($filename) || !$fp = fopen($filename, 'r'))
			return array('position' => 0, 'line' => 0, 'logText' => '');
		// Locate EOF
		fseek($fp, 0, SEEK_END);
		$_position = min($_position, ftell($fp));
		// Set file pointer at requested position or at EOF
		fseek($fp, $_position);
		// Iterate the file
		$logs = array();
		while (!feof($fp)) {
			// Get a new line
			$line = mb_convert_encoding(trim(fgets($fp)), 'UTF-8');
			if ($line == '')
				continue;
			// Append line to array if not empty
			if ($_search === '' || mb_stripos($line, $_search) !== false) {
				if ($_numbered) {
					array_push($logs, str_pad($_numStart, 4, '0', STR_PAD_LEFT) . '|' . trim($line) . "\n");
				} else {
					array_push($logs, trim($line) . "\n");
				}
			}
			$_numStart++;
		}
		// Store new file position in $_position
		$_position = ftell($fp);
		fclose($fp);

		// Display only the last jeedom.log.maxLines
		$logText = '';
		$nbLogs = count($logs);
		// If logs are TRUNCATED, then add a message
		if (count($logs) > $_max) {
			$logText .= "-------------------- TRUNCATED LOG --------------------\n";
			$logs = array_slice($logs, -$_max, $_max);
		}
		// Merge all lignes
		$logText .= implode('', $logs);

		// Apply color in system logs
		if ($_colored == 1) {
			$search = array(
				'<',
				'>',
				'WARNING:',
				'Erreur',
				'OK',
				'[DEBUG]',
				'[INFO]',
				'[NOTICE]',
				'[WARNING]',
				'[ERROR]',
				'[CRITICAL]',
				'[ALERT]',
				'[EMERGENCY]',
				'-------------------- TRUNCATED LOG --------------------'
			);
			$replace = array(
				'&lt;',
				'&gt;',
				'<span class="warning">WARNING</span>',
				'<span class="danger">Erreur</span>',
				'<strong>OK</strong>',
				'<span class="label label-xs label-success">DEBUG</span>',
				'<span class="label label-xs label-info">INFO</span>',
				'<span class="label label-xs label-info">NOTICE</span>',
				'<span class="label label-xs label-warning">WARNING</span>',
				'<span class="label label-xs label-danger">ERROR</span>',
				'<span class="label label-xs label-danger">CRITICAL</span>',
				'<span class="label label-xs label-danger">ALERT</span>',
				'<span class="label label-xs label-danger">EMERGENCY</span>',
				'<span class="label label-xl label-danger">-------------------- TRUNCATED LOG --------------------</span>'
			);
			$logText = str_replace($search, $replace, $logText);
		}
		// Apply color in scenario logs
		elseif ($_colored == 2) {
			$search = array();
			$replace = array();
			foreach($GLOBALS['JEEDOM_SCLOG_TEXT'] as $item) {
				$search[] = $item['txt'];
				$replace[] = str_replace('::', $item['txt'], $item['replace']);
			}
			$search[] = ' Start : ';
			$replace[] = '<strong> -- Start : </strong>';
			$search[] = 'Log :';
			$replace[] = '<span class="success">&ensp;&ensp;&ensp;Log :</span>';
			$logText = str_replace($search, $replace, $logText);
		}

		// Return the lines to the end of the file, the new position and line number
		return array('position' => $_position, 'line' => $_numStart, 'logText' => $logText);
	}

	public static function liste($_filtre = null) {
		$return = array();
		foreach (ls(self::getPathToLog(''), '*') as $log) {
			if ($_filtre !== null && strpos($log, $_filtre) === false) {
				continue;
			}
			if (!is_dir(self::getPathToLog($log))) {
				$return[] = $log;
			}
		}
		return $return;
	}

	/**
	 * Set php error level
	 * @param int $log_level
	 * @since 2.1.4
	 * @author KwiZeR <kwizer@kw12er.com>
	 */
	public static function define_error_reporting($log_level) {
		switch ($log_level) {
			case logger::DEBUG:
			case logger::INFO:
			case logger::NOTICE:
				error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
				break;
			case logger::WARNING:
				error_reporting(E_ERROR | E_WARNING | E_PARSE);
				break;
			case logger::ERROR:
				error_reporting(E_ERROR | E_PARSE);
				break;
			case logger::CRITICAL:
				error_reporting(E_ERROR | E_PARSE);
				break;
			case logger::ALERT:
				error_reporting(E_ERROR | E_PARSE);
				break;
			case logger::EMERGENCY:
				error_reporting(E_ERROR | E_PARSE);
				break;
			default:
				throw new Exception('log::level invalide ("' . $log_level . '")');
		}
	}

	public static function exception($e) {
		if (self::getConfig('log::level') > 100) {
			return $e->getMessage();
		} else {
			return print_r($e, true);
		}
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
