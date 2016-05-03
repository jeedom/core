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

class repo_file {
	/*     * *************************Attributs****************************** */

	public static $_name = 'Fichier';

	public static $_scope = array(
		'plugin' => true,
		'backup' => false,
		'hasConfiguration' => false,
	);

	public static $_configuration = array(
		'parameters_for_add' => array(
			'path' => array(
				'name' => 'Chemin',
				'type' => 'file',
			),
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function checkUpdate($_update) {

	}

	public static function downloadObject($_update) {
		return array('localVersion' => date('Y-m-d H:i:s'), 'path' => $_update->getConfiguration('path'));
	}

	public static function deleteObjet($_update) {

	}

	public static function objectInfo($_update) {
		return array(
			'doc' => 'plugins/' . $_update->getLogicalId() . '/doc/' . config::byKey('language', 'core', 'fr_FR') . '/index.asciidoc',
			'changelog' => '',
		);
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}