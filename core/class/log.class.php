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
		$formatter = new LineFormatter(str_replace('\n', "\n", self::getConfig('log::formatter')));
		self::$logger[$_log] = new Logger($_log);
		switch (self::getConfig('log::engine')) {
			case 'SyslogHandler':
			$handler = new SyslogHandler(self::getLogLevel($_log));
			break;
			case 'SyslogUdp':
			$handler = new SyslogUdpHandler(config::byKey('log::syslogudphost'), config::byKey('log::syslogudpport'), 'user', self::getLogLevel($_log));
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
	* Ajoute un message dans les log et fait en sorte qu'il n'y
	* ai jamais plus de 1000 lignes
	* @param string $_type type du message à mettre dans les log
	* @param string $_message message à mettre dans les logs
	*/
	public static function add($_log, $_type, $_message, $_logicalId = '') {
		if (trim($_message) == '') {
			return;
		}
		$logger = self::getLogger($_log);
		$action = 'add' . ucwords(strtolower($_type));
		if (method_exists($logger, $action)) {
			$logger->$action($_message);
			try {
				$level = Logger::toMonologLevel($_type);
				if ($level == Logger::ERROR && self::getConfig('addMessageForErrorLog') == 1) {
					@message::add($_log, $_message, '', $_logicalId);
				} elseif ($level > Logger::ALERT) {
					@message::add($_log, $_message, '', $_logicalId);
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
	* Autorisation de vide le fichier de log
	*/
	public static function authorizeClearLog($_log, $_subPath = '') {
		$path = self::getPathToLog($_subPath . $_log);
		return !((strpos($_log, '.htaccess') !== false)
		|| (!file_exists($path) || !is_file($path)))
		;
	}
	
	/**
	* Vide le fichier de log
	*/
	public static function clear($_log) {
		if (self::authorizeClearLog($_log)) {
			$path = self::getPathToLog($_log);
			com_shell::execute(system::getCmdSudo() . 'chmod 664 ' . $path . '> /dev/null 2>&1;cat /dev/null > ' . $path);
			return true;
		}
		return;
	}
	
	/**
	* Vide le fichier de log
	*/
	public static function remove($_log) {
		if (strpos($_log, 'nginx.error') !== false || strpos($_log, 'http.error') !== false) {
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
		foreach (array('', 'scenarioLog/') as $logPath) {
			$logs = ls(self::getPathToLog($logPath), '*');
			foreach ($logs as $log) {
				self::remove($log, $logPath);
			}
		}
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
		self::chunk($_log);
		$path = (!file_exists($_log) || !is_file($_log)) ? self::getPathToLog($_log) : $_log;
		if (!file_exists($path)) {
			return false;
		}
		$page = array();
		$log = new SplFileObject($path);
		if ($log) {
			$log->seek($_begin); //Seek to the begening of lines
			$linesRead = 0;
			while ($log->valid() && $linesRead != $_nbLines) {
				$line = trim($log->current()); //get current line
				if ($line != '') {
					if(function_exists('mb_convert_encoding')){
						array_unshift($page, mb_convert_encoding($line, 'UTF-8'));
					}else{
						array_unshift($page, $line);
					}
				}
				$log->next(); //go to next line
				$linesRead++;
			}
		}
		return $page;
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
	* Fixe le niveau de rapport d'erreurs PHP
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
