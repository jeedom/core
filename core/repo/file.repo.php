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

	public static function doUpdate($_update) {
		log::add('update', 'info', __('Décompression de l\'archive...', __FILE__));
		$logicalId = $_update->getLogicalId();
		$cibDir = '/tmp/' . $logicalId;
		if (file_exists($cibDir)) {
			rrmdir($cibDir);
		}
		mkdir($cibDir);
		if (!file_exists($_update->getConfiguration('path'))) {
			throw new Exception(__('Impossible de trouver le fichier zip : ', __FILE__) . $_update->getConfiguration('path'));
		}
		$extension = strtolower(strrchr($_update->getConfiguration('path'), '.'));
		if (!in_array($extension, array('.zip'))) {
			throw new Exception('Extension du fichier non valide (autorisé .zip) : ' . $extension);
		}
		$zip = new ZipArchive;
		$res = $zip->open($_update->getConfiguration('path'));
		if ($res === TRUE) {
			if (!$zip->extractTo($cibDir . '/')) {
				$content = file_get_contents($_update->getConfiguration('path'));
				unlink($_update->getConfiguration('path'));
				throw new Exception(__('Impossible d\'installer le plugin. Les fichiers n\'ont pas pu être décompressés : ', __FILE__) . substr($content, 255));
			}
			unlink($_update->getConfiguration('path'));
			$zip->close();
			log::add('update', 'info', __("OK\n", __FILE__));
			log::add('update', 'info', __('Installation de l\'objet...', __FILE__));
			if (!file_exists($cibDir . '/plugin_info')) {
				$files = ls($cibDir, '*');
				if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'plugin_info')) {
					$cibDir = $cibDir . '/' . $files[0];
				}
			}
			rcopy($cibDir . '/', dirname(__FILE__) . '/../../plugins/' . $_update->getLogicalId(), false, array(), true);
			rrmdir($cibDir);
			log::add('update', 'info', __("OK\n", __FILE__));
		} else {
			throw new Exception(__('Impossible de décompresser l\'archive zip : ', __FILE__) . $tmp);
		}
		return array('localVersion' => date('Y-m-d H:i:s'));
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