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
use Touki\FTP\Connection\Connection;
use Touki\FTP\FTPFactory;
use Touki\FTP\Model\Directory;

	/*     * *************************Attributs****************************** */


	public static $_scope = array(
		'plugin' => true,
		'backup' => true,
		'hasConfiguration' => true,
		'core' => true,
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
				'name' => '[Backup] IP/Host',
				'type' => 'input',
			),
			'backup::port' => array(
				'name' => '[Backup] Port',
				'type' => 'input',
			),
			'backup::passive' => array(
				'name' => '[Backup] Passif',
				'type' => 'checkbox',
			),
			'backup::ssl' => array(
				'name' => '[Backup] SSL',
				'type' => 'checkbox',
			),
			'backup::username' => array(
				'name' => '[Backup] Utilisateur',
				'type' => 'input',
			),
			'backup::password' => array(
				'name' => '[Backup] Mot de passe',
				'type' => 'password',
			),
			'backup::folder' => array(
				'name' => '[Backup] Chemin',
				'type' => 'input',
			),
			'plugin::ip' => array(
				'name' => '[Plugin] IP/Host',
				'type' => 'input',
			),
			'plugin::port' => array(
				'name' => '[Plugin] Port',
				'type' => 'input',
			),
			'plugin::passive' => array(
				'name' => '[Plugin] Passif',
				'type' => 'checkbox',
			),
			'plugin::ssl' => array(
				'name' => '[Plugin] SSL',
				'type' => 'checkbox',
			),
			'plugin::username' => array(
				'name' => '[Plugin] Utilisateur',
				'type' => 'input',
			),
			'plugin::password' => array(
				'name' => '[Plugin] Mot de passe',
				'type' => 'password',
			),
			'core::path' => array(
				'name' => '[Core] Chemin',
				'type' => 'input',
			),
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function getFtpConnection($_type = 'backup') {
		if (config::byKey('ftp::' . $_type . '::ssl') == 1) {
			$connection = new SSLConnection(config::byKey('ftp::' . $_type . '::ip'), config::byKey('ftp::' . $_type . '::user'), config::byKey('ftp::' . $_type . '::password'), config::byKey('ftp::' . $_type . '::port'), 120, config::byKey('ftp::' . $_type . '::passive', 'core', false));
		} else {
			$connection = new Connection(config::byKey('ftp::' . $_type . '::ip'), config::byKey('ftp::' . $_type . '::user'), config::byKey('ftp::' . $_type . '::password'), config::byKey('ftp::' . $_type . '::port'), 120, config::byKey('ftp::' . $_type . '::passive', 'core', false));
		}
		$connexion->open();
		return $connection;
	}

	public static function checkUpdate($_update) {
		$connexion = self::getFtpConnection('plugin');
		$factory = new FTPFactory;
		$ftp = $factory->build($connection);
		$pathinfo = pathinfo($_update->getConfiguration('path'));
		$file = $ftp->findFileByName($pathinfo['filename'] . '.' . $pathinfo['extension'], new Directory($pathinfo['dirname']));
		$connexion->close();
		if (!isset($file->mtime)) {
			return;
		}
		$date = $file->mtime->format('Y-m-d H:i:s');
		$_update->setRemoteVersion($date);
		if ($date != $_update->getLocalVersion()) {
			$_update->setStatus('update');
		} else {
			$_update->setStatus('ok');
		}
		$_update->save();
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

		$connexion = self::getFtpConnection('plugin');
		$factory = new FTPFactory;
		$ftp = $factory->build($connection);
		$pathinfo = pathinfo($_update->getConfiguration('path'));
		$file = $ftp->findFileByName($pathinfo['filename'] . '.' . $pathinfo['extension'], new Directory($pathinfo['dirname']));
		if (null === $file) {
			throw new Exception(__('Impossible de télécharger le fichier depuis : ' . $_update->getConfiguration('path') . '.', __FILE__));
		}
		$ftp->download($tmp, $file);
		$connection->close();

		if (!file_exists($tmp)) {
			throw new Exception(__('Impossible de télécharger le fichier depuis : ' . $_update->getConfiguration('path') . '.', __FILE__));
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
		$connexion = self::getFtpConnection('plugin');
		$factory = new FTPFactory;
		$ftp = $factory->build($connection);
		$pathinfo = pathinfo($_update->getConfiguration('path'));
		$file = $ftp->findFileByName($pathinfo['filename'] . '.' . $pathinfo['extension'], new Directory($pathinfo['dirname']));
		$connexion->close();
		if (!isset($file->mtime)) {
			return array('localVersion' => date('Y-m-d H:i:s'));
		}
		return array('localVersion' => $file->mtime->format('Y-m-d H:i:s'));
	}

	public static function objectInfo($_update) {
		return array(
			'doc' => '',
			'changelog' => '',
		);
	}

	public static function sendBackup($_path) {
		$connexion = self::getFtpConnection();
		$factory = new FTPFactory;
		$ftp = $factory->build($connection);
		$ftp->upload(new File($_path), '.');
		$connexion->close();
	}

	public static function listeBackup() {
		$connexion = self::getFtpConnection();
		$factory = new FTPFactory;
		$ftp = $factory->build($connection);
		$list = $ftp->findFilesystems(new Directory(config::byKey('ftp::backup::folder')));
		$connexion->close();
		$return = array();
		foreach ($list as $file) {
			$pathinfo = pathinfo($file->realpath);
			$return[] = $pathinfo['filename'] . '.' . $pathinfo['extension'];
		}
		return $return;
	}

	public static function retoreBackup($_backup) {
		$connexion = self::getFtpConnection();
		$factory = new FTPFactory;
		$ftp = $factory->build($connection);
		$pathinfo = pathinfo(config::byKey('ftp::backup::folder') . '/' . $_backup);
		$file = $ftp->findFileByName($pathinfo['filename'] . '.' . $pathinfo['extension'], new Directory($pathinfo['dirname']));
		if (null === $file) {
			throw new Exception(__('Impossible de télécharger le fichier depuis : ' . config::byKey('ftp::backup::folder') . '/' . $_backup . '.', __FILE__));
		}
		$ftp->download($backup_dir . '/' . $_backu, $file);
		$connection->close();
		com_shell::execute('sudo chmod 777 -R ' . $backup_dir . '/*');
		jeedom::restore('backup/' . $_backup, true);

	}

	public static function downloadCore($_path) {
		$connexion = self::getFtpConnection('plugin');
		$factory = new FTPFactory;
		$ftp = $factory->build($connection);
		$pathinfo = pathinfo(config::byKey('ftp::core::path'));
		$file = $ftp->findFileByName($pathinfo['filename'] . '.' . $pathinfo['extension'], new Directory($pathinfo['dirname']));
		if (null === $file) {
			throw new Exception(__('Impossible de télécharger le fichier depuis : ' . config::byKey('ftp::core::path') . '.', __FILE__));
		}
		$ftp->download($_path, $file);
		$connection->close();
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}
