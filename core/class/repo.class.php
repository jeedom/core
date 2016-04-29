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

class repo {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Méthodes statiques*************************** */

	public static function all() {
		$return = array();
		foreach (ls(dirname(__FILE__) . '/../repo', '*.repo.php') as $file) {
			$id = str_replace('.repo.php', '', $file);
			$class = 'repo_' . str_replace('.repo.php', '', $file);
			$return[str_replace('.repo.php', '', $file)] = array(
				'name' => str_replace('.repo.php', '', $file),
				'configuration' => $class::$_configuration,
			);
		}
		return $return;
	}

	public static function byId($_id) {
		$class = 'repo_' . $_id;
		return array(
			'name' => $_id,
			'configuration' => $class::$_configuration,
		);
	}

	/*     * *********************Méthodes d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}