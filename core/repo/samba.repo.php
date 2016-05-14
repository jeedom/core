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
			'core::path' => array(
				'name' => '[Core] Chemin',
				'type' => 'input',
			),
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function checkUpdate($_update) {
		$file = self::ls(config::byKey('samba::plugin::folder') . '/' . $_update->getConfiguration('path'), 'plugin');
		if (count($file) != 1) {
			return;
		}
		if (!isset($file[0]['datetime'])) {
			return;
		}
		$_update->setRemoteVersion($file[0]['datetime']);
		if ($file[0]['datetime'] != $_update->getLocalVersion()) {
			$_update->setStatus('update');
		} else {
			$_update->setStatus('ok');
		}
		$_update->save();
	}

	public static function deleteObjet($_update) {

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
		$cmd = 'cd ' . $tmp_dir . ';';
		$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::plugin::folder') . ';get ' . $_update->getConfiguration('path'), 'plugin');
		com_shell::execute($cmd);
		$pathinfo = pathinfo($_update->getConfiguration('path'));
		com_shell::execute('mv ' . $tmp_dir . '/' . $pathinfo['filename'] . '.' . $pathinfo['extension'] . ' ' . $tmp);
		$file = self::ls(config::byKey('samba::plugin::folder') . '/' . $_update->getConfiguration('path'), 'plugin');
		if (count($file) != 1 || !isset($file[0]['datetime'])) {
			return array('path' => $tmp, 'localVersion' => date('Y-m-d H:i:s'));
		}
		return array('path' => $tmp, 'localVersion' => $file[0]['datetime']);
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

	public static function ls($_dir = '', $_type = 'backup') {
		$cmd = repo_samba::makeSambaCommand('cd ' . $_dir . ';ls', $_type);
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
				$cmd = self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';del ' . $file['filename']);
				com_shell::execute($cmd);
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

	public static function downloadCore($_path) {
		$pathinfo = pathinfo($_path);
		$cmd = 'cd ' . $pathinfo['dirname'] . ';';
		$cmd .= self::makeSambaCommand('get ' . config::byKey('samba::core::path') . '/jeedom.zip', 'plugin');
		com_shell::execute($cmd);
		com_shell::execute('sudo chmod 777 -R ' . $_path);
		return;
	}

	public static function versionCore() {
		try {
			if (file_exists('/tmp/jeedom_version')) {
				com_shell::execute('sudo rm /tmp/jeedom_version');
			}
			$cmd = 'cd /tmp;';
			$cmd .= self::makeSambaCommand('get ' . config::byKey('samba::core::path') . '/jeedom_version', 'plugin');
			com_shell::execute($cmd);
			if (!file_exists('/tmp/jeedom_version')) {
				return null;
			}
			$version = trim(file_get_contents('/tmp/jeedom_version'));
			com_shell::execute('sudo rm /tmp/jeedom_version');
			return $version;
		} catch (Exception $e) {

		} catch (Error $e) {

		}
		return null;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}