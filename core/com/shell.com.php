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

/* ------------------------------------------------------------ Inclusions */
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';

class com_shell {
	/*     * ***********************Attributs************************* */

	private static $instance;

	private $cmds = array();
	private $background;
	private $cache = array();
	private $history = array();

	/*     * ********************Functions static********************* */

	function __construct($_cmd, $_background = false) {
		$this->setBackground($_background);
		$this->addCmd($_cmd);
	}

	/**
	 * Get the instance of com_shell
	 * @return com_shell
	 */
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Execute a command
	 * @param string $_cmd
	 * @param bool $_background
	 */
	public static function execute($_cmd, $_background = false) {
		$shell = self::$instance;
		$shell->clear();
		$shell->addCmd($_cmd, $_background);
		return $shell->exec();
	}

	/**
	 * Test if a command exists
	 * @param string $_cmd
	 * @return boolean
	 */
	public static function commandExists($_cmd) {
		$fp = popen("which " . $_cmd, "r");
		$value = fgets($fp, 255);
		$exists = !empty($value);
		pclose($fp);
		return $exists;
	}

	/*     * ************* Functions ************************************ */

	/**
	 * Execute commands
	 * @throws Exception
	 * @return string
	 */
	public function exec() {
		$output = array();
		$retval = 0;
		$return = array();
		foreach ($this->cmds as $cmd) {
			exec($cmd, $output, $retval);
			$return[] = implode("\n", $output);
			if ($retval != 0) {
				throw new Exception('Error on shell exec, return value : ' . $retval . '. Details : ' . $return);
			}
			$this->history[] = $cmd;
		}
		$this->cmds = $this->cache;
		$this->cache = array();

		return implode("\n", $return);
	}

	/**
	 * @deprecated Replaced by com_shell::commandExists
	 * @param string $_cmd
	 * @return boolean
	 */
	public function commandExist($_cmd) {
		return self::commandExists($_cmd);
	}

	public function clear() {
		$this->cache = array_merge($this->cache, $this->cmds);
		$this->cmds = array();
	}

	public function clearHistory() {
		$this->history = array();
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getCmd() {
		return implode("\n", $this->cmds);
	}

	public function addCmd($_cmd, $_background = null) {
		if (!self::commandExists($_cmd)) {
			return false;
		}
		$bg = ($_background === null) ? $this->getBackground() : $_background;
		$add = $bg ? ' >> /dev/null 2>&1 &' : '';
		$this->cmds[] = $_cmd . $add;

		return true;
	}

	public function setBackground($background) {
		$this->background = $background;
	}

	public function getBackground() {
		return $this->background;
	}

	/**
	 * Get the history of commands
	 * @return array
	 */
	public function getHistory() {
		return $this->history;
	}
}
