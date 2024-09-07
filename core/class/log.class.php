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

class log {
	/*     * *************************Constantes****************************** */

	const DEFAULT_MAX_LINE = 200;

	/*     * *************************Attributs****************************** */

	
	private static $config = null;
 	private static $level = array(
		'debug' => 100,
		'info'  => 200,
		'notice' => 250,
		'warning' => 300,
		'error' => 400,
		'critical' => 500,
		'emergency' => 600
	);

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

	public static function getLogLevel($_log) {
		if(strpos($_log,'_') !== false){
			$_log = explode('_',$_log)[0];
		}
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
		if ($_level >= 600) {
			return 'none';
		}
		foreach (self::$level as $key => $value) {
			if($value == $_level){
				return $key;
			}
		}
		return 'none';
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
		$level = (isset(self::$level[strtolower($_type)])) ? self::$level[strtolower($_type)] : 100;
		if($level < self::getLogLevel($_log)){
			return;
		}
		$fp = fopen(self::getPathToLog($_log), 'a');
		fwrite($fp,'['.date('Y-m-d H:i:s').']['.strtoupper($_type).'] '.$_message."\n");  
		fclose($fp);
		try {
            $action = '<a href="/index.php?v=d&p=log&logfile=' . $_log . '">' . __('Log', __FILE__) . ' ' . $_log . '</a>';
			if ($level == 400 && self::getConfig('addMessageForErrorLog') == 1) {
				@message::add($_log, $_message, $action, $_logicalId);
			} elseif ($level >= 500) {
				@message::add($_log, $_message, $action, $_logicalId);
			}
		} catch (Exception $e) {
			
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
			com_shell::execute(system::getCmdSudo() . 'chmod 664 ' . $path . ' > /dev/null 2>&1;cat /dev/null > ' . $path.';rm ' . $path . ' 2>&1 > /dev/null');
			return true;
		}
	}

	public static function removeAll() {
		foreach (ls(self::getPathToLog(''), '*', false, array('files')) as $log)
			self::remove($log);
		return true;
	}

	/**
	* Get $_nbLines from a $_log from $_begin position
	* @param string $_log
	* @param int $_begin
	* @param int $_nbLines
	* @return boolean|array
	* @deprecated v4.4
	* => removed in v4.6 (use log::getDelta() instead)
	*
	* Note that log::get($_log, $_begin, $_nbLines) is equivalent to:
	*    $path = (!file_exists($_log) || !is_file($_log)) ? self::getPathToLog($_log) : $_log;
	*    if (!file_exists($path)) {
	*      return false;
	*    }
	*    $delta = self::getDelta($_log, $_begin, '', false, false, 0, $_nbLines);
	*    $arr = explode("\n", $delta['logText']);
	*    unset($arr[count($arr) - 1]);
	*    $res = array_reverse($arr);
	*/
	public static function get($_log, $_begin, $_nbLines) {
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
					array_unshift($page, mb_convert_encoding($line, 'UTF-8'));

				}
				$log->next();
				$linesRead++;
			}
		}
		return $page;
	}

	/**
	* Get the log delta from $_position to the end of the file
	* New position is stored in $_position when eof is reached
	*
	* @param string $_log Log filename (default 'core')
	* @param int $_position Bytes representing position from the begining of the file (default 0)
	* @param string $_search Text to find in log file (default '')
	* @param int $_colored Should lines be colored (default false)
	* @param boolean $_numbered Should lines be numbered (default true)
	* @param int $_numStart At what number should lines number start (default 0)
	* @param int $_max Max number of returned lines (default is config value "maxLineLog")
	* @return array Array containing log to append to buffer and new position for next call
	*/
	public static function getDelta($_log = 'core', $_position = 0, $_search = '', $_colored = false, $_numbered = true, $_numStart = 0, $_max = -1) {
		// Add path to file if needed
		$filename = (file_exists($_log) && is_file($_log)) ? $_log : self::getPathToLog($_log);
		// Check if log file exists and is readable
		if (!file_exists($filename) || !$fp = fopen($filename, 'r'))
			return array('position' => 0, 'line' => 0, 'logText' => '');
		// Locate EOF
		fseek($fp, 0, SEEK_END);
		// If log file has been truncated/altered (EOF is smaller than requested position)
		// Then we restart from the start of the file
		if (ftell($fp) < $_position) {
			$_position = 0;
			$_numStart = 0;
		}
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

		// Display only the last maxLines
		$logText = '';

		$nbLogs = count($logs);
		if ($nbLogs == 0) {
			return array('position' => $_position, 'line' => $_numStart, 'logText' => $logText);
		}
		// $_max default value is configured value of "maxLineLog"
		$_max = ($_max < 0) ? self::getConfig('maxLineLog') : $_max;
		// $_max value is always more than DEFAULT_MAX_LINE
		$_max = max($_max, self::DEFAULT_MAX_LINE);
		if ($nbLogs > $_max) {
			// If logs must be TRUNCATED, then add a message
			$logText .= "-------------------- TRUNCATED LOG --------------------\n";
			$logs = array_slice($logs, -$_max, $_max);
		}
		// Merge all lignes
		$logText .= implode('', $logs);

		// Apply color in logs
		if ($_colored) {
			// Clear logs from HTML only in colored view
			$logText = htmlspecialchars($logText, ENT_QUOTES | ENT_HTML5, 'UTF-8');

			// Highlight searched text first (when more than 3 chars)
			if (strlen($_search) > 2) {
				$srch = preg_quote($_search, '/');
				$logText = preg_replace('/(' . $srch . ')/i', '<mark>$1</mark>', $logText);
			}

			$search = array();              $replace = array();
			$search[] = '[DEBUG]';          $replace[] = '<span class="label label-xs label-success">&nbsp;D<&>EBUG&nbsp;</span>';
			$search[] = '[INFO]';           $replace[] = '<span class="label label-xs label-info">&nbsp;I<&>NFO&nbsp;</span>';
			$search[] = '[NOTICE]';         $replace[] = '<span class="label label-xs label-info">N<&>OTICE&nbsp;</span>';
			$search[] = '[WARNING]';        $replace[] = '<span class="label label-xs label-warning">W<&>ARNING</span>';
			$search[] = '[ERROR]';          $replace[] = '<span class="label label-xs label-danger">&nbsp;E<&>RROR&nbsp;</span>';
			$search[] = '[CRITICAL]';       $replace[] = '<span class="label label-xs label-danger">&nbsp;C<&>RITI&nbsp;</span>';
			$search[] = '[ALERT]';          $replace[] = '<span class="label label-xs label-danger">&nbsp;A<&>LERT&nbsp;</span>';
			$search[] = '[EMERGENCY]';      $replace[] = '<span class="label label-xs label-danger">&nbsp;E<&>MERG&nbsp;</span>';

			$search[] = '[  OK  ]';         $replace[] = '<span class="label label-xs label-success">[&nbsp;&nbsp;O<&>K&nbsp;&nbsp;]</span>';
			$search[] = '[  KO  ]';         $replace[] = '<span class="label label-xs label-danger">[&nbsp;&nbsp;K<&>O&nbsp;&nbsp;]</span>';
			$search[] = ' OK ';             $replace[] = '<span class="label label-xs label-success"> O<&>K </span>';
			$search[] = ' KO ';             $replace[] = '<span class="label label-xs label-danger"> K<&>O </span>';
			$search[] = 'ERROR';            $replace[] = '<span class="label label-xs label-danger">E<&>RROR</span>';
			$search[] = 'PHP Notice:';      $replace[] = '<span class="warning">PHP N<&>otice:</span>';
			$search[] = 'PHP Warning:';     $replace[] = '<span class="warning">PHP War<&>ning:</span>';
			$search[] = 'PHP Stack trace:'; $replace[] = '<span class="danger">PHP S<&>tack trace:</span>';

			$search[] = ':br:';             $replace[] = '<br>';
			$search[] = ':bg-success:';     $replace[] = '<span class="label label-success">';
			$search[] = ':bg-info:';        $replace[] = '<span class="label label-info">';
			$search[] = ':bg-warning:';     $replace[] = '<span class="label label-warning">';
			$search[] = ':bg-danger:';      $replace[] = '<span class="label label-danger">';
			$search[] = ':/bg:';            $replace[] = '</span>';
			$search[] = ':fg-success:';     $replace[] = '<span class="success">';
			$search[] = ':fg-info:';        $replace[] = '<span class="info">';
			$search[] = ':fg-warning:';     $replace[] = '<span class="warning">';
			$search[] = ':fg-danger:';      $replace[] = '<span class="danger">';
			$search[] = ':/fg:';            $replace[] = '</span>';
			$search[] = ':b:';              $replace[] = '<b>';
			$search[] = ':/b:';             $replace[] = '</b>';
			$search[] = ':s:';              $replace[] = '<strong>';
			$search[] = ':/s:';             $replace[] = '</strong>';
			$search[] = ':i:';              $replace[] = '<i>';
			$search[] = ':/i:';             $replace[] = '</i>';
			$search[] = ':hide:';           $replace[] = '<!--';
			$search[] = ':/hide:';          $replace[] = '-->';

			foreach($GLOBALS['JEEDOM_SCLOG_TEXT'] as $item) {
				$search[] = $item['txt'];
				// Insert a marker into subject string to avoid replacing it multiple times
				$subject = $item['txt'][0] . '<&>' . substr($item['txt'], 1);
				$replace[] = str_replace('::', $subject, $item['replace']);
			}

			$replacables = array(
				array('txt' => 'WARNING:', 'replace' => '<span class="warning">::</span>'),
				array('txt' => 'Erreur',   'replace' => '<span class="danger">::</span>'),
				array('txt' => 'OK',       'replace' => '<strong>::</strong>'),
				array('txt' => 'Log :',    'replace' => '<span class="success">&nbsp;&nbsp;&nbsp;::</span>'),
				array('txt' => '-------------------- TRUNCATED LOG --------------------', 'replace' => '<span class="label label-xl label-danger">::</span>')
			);

			foreach($replacables as $item) {
				if (strlen($item['txt']) >= 2) {
					$search[] = $item['txt'];
					// Insert a marker into subject string to avoid replacing it multiple times
					$subject = $item['txt'][0] . '<&>' . substr($item['txt'], 1);
					$replace[] = str_replace('::', $subject, $item['replace']);
				}
			}
			// Replace everything in log
			$logText = str_replace($search, $replace, $logText);
			// Remove all inserted markers
			$logText = str_replace('<&>', '', $logText);
		}

		// Return the lines to the end of the file, the new position and line number
		return array('position' => $_position, 'line' => $_numStart, 'logText' => $logText);
	}

	/**
	* Efficiently get the last line of a file
	* @param string $_log Log filename
	* @return string The last non-empty line of the file (or '')
	*/
	public static function getLastLine($_log) {
		// Add path to file if needed
		$filename = (file_exists($_log) && is_file($_log)) ? $_log : self::getPathToLog($_log);
		// Check if log file exists and is readable
		if (!file_exists($filename) || !$fp = fopen($filename, 'r'))
			return '';
		// Init line and cursor
		$line = '';
		$cursor = -1;
		// Locate EOF
		fseek($fp, $cursor, SEEK_END);
		$char = fgetc($fp);
		// Trim trailing newline chars of the file
		while ($char === "\n" || $char === "\r") {
			fseek($fp, $cursor--, SEEK_END);
			$char = fgetc($fp);
		}
		// Read until the start of file or first newline char
		while ($char !== false && $char !== "\n" && $char !== "\r") {
			// Prepend the new char
			$line = $char . $line;
			fseek($fp, $cursor--, SEEK_END);
			$char = fgetc($fp);
		}
		// Colse file and return
		fclose($fp);
		return $line;
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
			case 100:
			case 200:
			case 250:
				error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
				break;
			case 300:
				error_reporting(E_ERROR | E_WARNING | E_PARSE);
				break;
			case 400:
				error_reporting(E_ERROR | E_PARSE);
				break;
			case 500:
				error_reporting(E_ERROR | E_PARSE);
				break;
			case 600:
				error_reporting(E_ERROR | E_PARSE);
				break;
			default:
				error_reporting(E_ERROR | E_PARSE);
		}
	}

	public static function exception($e) {
		if (self::getConfig('log::level') > 100) {
			return $e->getMessage();
		} else {
			return $e->getMessage()."\n".$e->getTraceAsString();
		}
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}