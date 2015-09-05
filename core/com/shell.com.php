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

	private $cmd;
	private $background = false;

	/*     * ********************Functions static********************* */

	function __construct($_cmd) {
		$this->cmd = $_cmd;
	}

	/*     * ************* Functions ************************************ */

	function exec() {
		$output = array();
		$retval = 0;
		if ($this->getBackground()) {
			exec($this->cmd . ' >> /dev/null 2>&1 &');
			return;
		} else {
			$return = exec($this->cmd, $output, $retval);
		}

		if ($retval != 0) {
			throw new Exception('Error on shell exec, return value : ' . $retval . '. Details : ' . $return);
		}
		return $return;
	}

	function commandExist($_cmd) {
		$fp = popen("which " . $_cmd, "r");
		$value = fgets($fp, 255);
		$exists = !empty($value);
		pclose($fp);
		return $exists;
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getCmd() {
		return $this->cmd;
	}

	public function setBackground($background) {
		$this->background = $background;
	}

	public function getBackground() {
		return $this->background;
	}

}

?>
