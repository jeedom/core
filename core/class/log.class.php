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
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;

class log {
	/*     * *************************Constantes****************************** */
	const LEVEL_DEBUG = 100;
	const LEVEL_INFO = 200;
	const LEVEL_NOTICE = 250;
	const LEVEL_WARNING = 300;
	const LEVEL_ERROR = 400;
	
	const DEFAULT_MAX_LINE = 200;
	
	/*     * *************************Attributs****************************** */
	private static $logger = array();
	/*     * ***********************Methode static*************************** */

	public static function getLogger($_log) {
		if (isset(self::$logger[$_log])) {
			return self::$logger[$_log];
		}
		$output = "[%datetime%][%channel%][%level_name%] : %message%\n";
		$formatter = new LineFormatter($output);
		self::$logger[$_log] = new Logger($_log);
		switch (config::byKey('log::engine')) {
			case 'SyslogHandler':
				$handler = new SyslogHandler(config::byKey('log::level'));
				break;
			case 'SyslogUdp':
				$handler = new SyslogUdpHandler(config::byKey('log::syslogudphost'), config::byKey('log::syslogudpport'));
				break;
			case 'StreamHandler':
			default:
				$handler = new StreamHandler(self::getPathToLog($_log), config::byKey('log::level'));
				break;
		}
		$handler->setFormatter($formatter);
		self::$logger[$_log]->pushHandler($handler);
		return self::$logger[$_log];
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
		$action = 'add'.ucword(strtolower($_type));
		if (method_exists($logger, $action)) {
			$logger->$action($_message);
			if ($action == 'addError' && config::byKey('addMessageForErrorLog') == 1) {
				@message::add($_log, $_message, '', $_logicalId);
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
			if (is_file($_path)) {
				self::chunkLog($path);
			}
		}
	}

	public static function chunkLog($_path) {	
		if (strpos($_path, '.htaccess') !== false) {
			return;
		}
		$maxLineLog = config::byKey('maxLineLog');
		if ($maxLineLog < self::DEFAULT_MAX_LINE) {
			$maxLineLog = self::DEFAULT_MAX_LINE;
		}
		shell_exec('sudo chmod 777 ' . $_path . ' ;echo "$(tail -n ' . $maxLineLog . ' ' . $_path . ')" > ' . $_path);
		@chown($_path, 'www-data');
		@chgrp($_path, 'www-data');
		@chmod($_path, 0777);
	}

	public static function getPathToLog($_log = 'core') {
		return dirname(__FILE__) . '/../../log/' . $_log;
	}

	/**
	 * Autorisation de vide le fichier de log
	 */
	public static function authorizeClearLog($_log, $_subPath = '')
	{
		$path = self::getPathToLog($_subPath.$_log);
		return !((!self::isStreamHandlerEngine())
				||  (strpos($_log, '.htaccess') !== false)
				||  (!file_exists($path) || !is_file($path)))
		;
	}
	
	/**
	 * Vide le fichier de log
	 */
	public static function clear($_log) {
		if (self::authorizeClearLog($_log))
		{
			$path = self::getPathToLog($_log);
			shell_exec('sudo chmod 777 ' . $path . ';cat /dev/null > ' . $path);
			
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
		if (self::authorizeClearLog($_log))
		{
			shell_exec('sudo chmod 777 ' . $path);
			unlink($path);
			return true;
		}
	}

	public static function removeAll() {
		if (!self::isStreamHandlerEngine()) {
			return;
		}
		foreach (array('', 'scenarioLog/') as $logPath) {
			$logs = ls(self::getPathToLog($logPath), '*');
			foreach ($logs as $log) {
				self::remove($log, $logPath);
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
		$replace = array(
			'&gt;' => '>',
			'&apos;' => '',
		);
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
					array_unshift($page, $line);
				}
				$log->next(); //go to next line
				$linesRead++;
			}
		}
		return $page;
	}

	public static function liste() {
		if (!self::isStreamHandlerEngine()) {
			return array();
		}
		$return = array();
		foreach (ls(self::getPathToLog(''), '*') as $log) {
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
	public static function define_error_reporting($log_level)
	{
		switch ($log_level) {
			case self::LEVEL_DEBUG :
			case self::LEVEL_INFO :
			case self::LEVEL_NOTICE :
				error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
				break;
			case self::LEVEL_WARNING :
				error_reporting(E_ERROR | E_WARNING | E_PARSE);
				break;
			case self::LEVEL_ERROR :
				error_reporting(E_ERROR | E_PARSE);
				break;
			default:
				throw new Exception('log::level invalide ("'.$log_level.'")');
		}
	}

	public static function isStreamHandlerEngine()
	{
		return config::byKey('log::engine') == 'StreamHandler';
	}
	
	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
