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

class repo_url {
	/*     * *************************Attributs****************************** */

	public static $_name = 'URL';

	public static $_scope = array(
		'plugin' => true,
		'backup' => false,
		'hasConfiguration' => true,
		'core' => true,
	);

	public static $_configuration = array(
		'parameters_for_add' => array(
			'url' => array(
				'name' => 'URL du fichier ZIP',
				'type' => 'input',
			),
		),
		'configuration' => array(
			'core::url' => array(
				'name' => 'URL core Jeedom',
				'type' => 'input',
			),
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function checkUpdate($_update) {

	}

	public static function downloadObject($_update) {
		$tmp_dir = '/tmp';
		$tmp = $tmp_dir . '/' . $_update->getLogicalId() . '.zip';
		if (file_exists($tmp)) {
			unlink($tmp);
		}
		if (!is_writable($tmp_dir)) {
			exec('sudo chmod 777 -R ' . $tmp);
		}
		if (!is_writable($tmp_dir)) {
			throw new Exception(__('Impossible d\'écrire dans le répertoire : ', __FILE__) . $tmp . __('. Exécuter la commande suivante en SSH : sudo chmod 777 -R ', __FILE__) . $tmp_dir);
		}
		exec('wget --no-check-certificate --progress=dot --dot=mega ' . $_update->getConfiguration('url') . ' -O ' . $tmp);
		log::add('update', 'alert', $result);
		return array('path' => $tmp, 'localVersion' => date('Y-m-d H:i:s'));
	}

	public static function deleteObjet($_update) {

	}

	public static function objectInfo($_update) {
		return array(
			'doc' => '',
			'changelog' => '',
		);
	}

	public static function downloadCore($_path) {
		exec('wget --no-check-certificate --progress=dot --dot=mega ' . config::byKey('url::core::url') . ' -O ' . $_path);
		return;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}