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

    /*     * ********************Functions static********************* */

    function __construct($_cmd) {
        $this->cmd = $_cmd;
    }

    /*     * ************* Functions ************************************ */

    function exec() {
        $output = array();
        $retval = 0;
        $return = exec($this->cmd, $output, $retval);
        if ($retval != 0) {
            throw new Exception('Error on shell exec, return value : ' . $retval . '. Details : ' . $return);
        }
        return $return;
    }

    /*     * **********************Getteur Setteur*************************** */

    public function getCmd() {
        return $this->cmd;
    }

}

?>
