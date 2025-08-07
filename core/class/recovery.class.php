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

class recovery {
	/*     * *************************Attributes****************************** */

	/*     * ***********************Static Methods*************************** */
	public static function install(bool $_force = false) {
		switch (system::getArch()) {
			case 'arm64':
				self::writeLog(__('Vérification du script de démarrage', __FILE__));
				$cmd = system::getCmdSudo() . ' /bin/bash ' . __DIR__ . '/../../resources/update_boot_script.sh';
				$cmd .= ($_force) ? ' -f' : '';
				$cmd .= ' >> ' . log::getPathToLog(__CLASS__) . ' 2>&1';
				exec($cmd, $output, $returnCode);
				if ($returnCode == 0) {
					self::writeLog(__('Le script de démarrage a été mis à jour', __FILE__), 'info');
					if (!file_exists('/etc/jeedom_board')) {
						file_put_contents('/etc/jeedom_board', ucfirst(jeedom::getHardwareName()));
					}
				}
				break;
			default:
				// Handle other architectures if needed
				break;
		}
	}

	private static function writeLog(string $_message, string $_level = 'debug') {
		exec('echo [' . date('Y-m-d H:i:s') . '][' . strtoupper($_level) . '] : ' . $_message . ' >> ' . log::getPathToLog(__CLASS__) . ' 2>&1');
	}
}
