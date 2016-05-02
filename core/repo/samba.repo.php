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
		'plugin' => false,
		'backup' => true,
		'hasConfiguration' => true,
	);

	public static $_configuration = array(
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
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

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