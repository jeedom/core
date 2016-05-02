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

class repo_samba {
	/*     * *************************Attributs****************************** */

	public static $_name = 'Samba';

	public static $_scope = array(
		'plugin' => true,
		'backup' => true,
		'hasConfiguration' => true,
	);

	public static $_configuration = array(
		'parameters_for_add' => array(
			'path' => array(
				'name' => 'Chemin',
				'type' => 'input',
			),
		),
		'configuration' => array(
			'backup::ip' => array(
				'name' => '[Backup] IP',
				'type' => 'input',
			),
			'backup::username' => array(
				'name' => '[Backup] Utilisateur',
				'type' => 'input',
			),
			'backup::password' => array(
				'name' => '[Backup] Mot de passe',
				'type' => 'password',
			),
			'backup::share' => array(
				'name' => '[Backup] Partage',
				'type' => 'input',
			),
			'backup::folder' => array(
				'name' => '[Backup] Chemin',
				'type' => 'input',
			),
			'plugin::ip' => array(
				'name' => '[Plugin] IP',
				'type' => 'input',
			),
			'plugin::username' => array(
				'name' => '[Plugin] Utilisateur',
				'type' => 'input',
			),
			'plugin::password' => array(
				'name' => '[Plugin] Mot de passe',
				'type' => 'password',
			),
			'plugin::share' => array(
				'name' => '[Plugin] Partage',
				'type' => 'input',
			),
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function checkUpdate($_update) {

	}

	public static function deleteObjet($_update) {

	}

	public static function doUpdate($_update) {
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

		$cmd = 'cd ' . $tmp_dir . ';';
		$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::plugin::folder') . ';get ' . $_update->getConfiguration('path'), 'plugin');
		com_shell::execute($cmd);

		$pathinfo = pathinfo($_update->getConfiguration('path'));
		com_shell::execute('mv ' . $tmp_dir . '/' . $pathinfo['filename'] . '.' . $pathinfo['extension'] . ' ' . $tmp);

		log::add('update', 'alert', $result);
		if (!file_exists($tmp)) {
			throw new Exception(__('Impossible de télécharger le fichier depuis : ' . $url . '.', __FILE__));
		}
		if (filesize($tmp) < 100) {
			throw new Exception(__('Echec lors du téléchargement du fichier. Veuillez réessayer plus tard (taille inférieure à 100 octets)', __FILE__));
		}
		log::add('update', 'alert', __("OK\n", __FILE__));
		$cibDir = '/tmp/jeedom_' . $_update->getLogicalId();
		if (file_exists($cibDir)) {
			rrmdir($cibDir);
		}
		if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
			throw new Exception(__('Impossible de créer le dossier  : ' . $cibDir . '. Problème de droits ?', __FILE__));
		}
		log::add('update', 'alert', __('Décompression du zip...', __FILE__));
		$zip = new ZipArchive;
		$res = $zip->open($tmp);
		if ($res === TRUE) {
			if (!$zip->extractTo($cibDir . '/')) {
				$content = file_get_contents($tmp);
				throw new Exception(__('Impossible d\'installer le plugin. Les fichiers n\'ont pas pu être décompressés : ', __FILE__) . substr($content, 255));
			}
			$zip->close();
			unlink($tmp);
			if (!file_exists($cibDir . '/plugin_info')) {
				$files = ls($cibDir, '*');
				if (count($files) == 1 && file_exists($cibDir . '/' . $files[0] . 'plugin_info')) {
					$cibDir = $cibDir . '/' . $files[0];
				}
			}
			rcopy($cibDir . '/', dirname(__FILE__) . '/../../plugins/' . $_update->getLogicalId(), false, array(), true);
			rrmdir($cibDir);
			$cibDir = '/tmp/jeedom_' . $_update->getLogicalId();
			if (file_exists($cibDir)) {
				rrmdir($cibDir);
			}
			log::add('update', 'alert', __("OK\n", __FILE__));
		} else {
			throw new Exception(__('Impossible de décompresser l\'archive zip : ', __FILE__) . $tmp);
		}
		return array('localVersion' => date('Y-m-d H:i:s'));
	}

	public static function objectInfo($_update) {
		return array(
			'doc' => '',
			'changelog' => '',
		);
	}

	public static function makeSambaCommand($_cmd, $_type = 'backup') {
		return 'sudo smbclient ' . config::byKey('samba::' . $_type . '::share') . ' -U ' . config::byKey('samba::' . $_type . '::username') . '%' . config::byKey('samba::' . $_type . '::password') . ' -I ' . config::byKey('samba::' . $_type . '::ip') . ' -c "' . $_cmd . '"';
	}

	public static function sortByDatetime($a, $b) {
		if (strtotime($a['datetime']) == strtotime($b['datetime'])) {
			return 0;
		}
		return (strtotime($a['datetime']) < strtotime($b['datetime'])) ? -1 : 1;
	}

	public static function ls($_dir = '', $type = 'backup') {
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';ls', $type);
		$result = explode("\n", com_shell::execute($cmd));
		$return = array();
		for ($i = 2; $i < count($result) - 2; $i++) {
			$line = array();
			foreach (explode(" ", $result[$i]) as $value) {
				if (trim($value) == '') {
					continue;
				}
				$line[] = $value;
			}
			$file_info = array();
			$file_info['filename'] = $line[0];
			$file_info['size'] = $line[2];
			$file_info['datetime'] = date('Y-m-d H:i:s', strtotime($line[5] . ' ' . $line[4] . ' ' . $line[7] . ' ' . $line[6]));
			$return[] = $file_info;
		}
		usort($return, 'repo_samba::sortByDatetime');
		return array_reverse($return);
	}

	public static function cleanBackupFolder() {
		$timelimit = strtotime('-' . config::byKey('backup::keepDays') . ' days');
		foreach (self::ls(config::byKey('samba::backup::folder')) as $file) {
			if ($timelimit > strtotime($file['datetime'])) {
				$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';del ' . $file['filename']);
			}
		}
	}

	public static function sendBackup($_path) {
		$pathinfo = pathinfo($_path);
		$cmd = 'cd ' . $pathinfo['dirname'] . ';';
		$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';put ' . $pathinfo['filename'] . '.' . $pathinfo['extension']);
		com_shell::execute($cmd);
		self::cleanBackupFolder();
	}

	public static function listeBackup() {
		$return = array();
		foreach (self::ls(config::byKey('samba::backup::folder')) as $file) {
			$return[] = $file['filename'];
		}
		return $return;
	}

	public static function retoreBackup($_backup) {
		$backup_dir = calculPath(config::byKey('backup::path'));
		$cmd = 'cd ' . $backup_dir . ';';
		$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';get ' . $_backup);
		com_shell::execute($cmd);
		com_shell::execute('sudo chmod 777 -R ' . $backup_dir . '/*');
		jeedom::restore('backup/' . $_backup, true);
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}