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

class repo_samba {
	/*     * *************************Attributs****************************** */
	
	public static $_name = 'Samba';
	
	public static $_scope = array(
		'plugin' => true,
		'backup' => true,
		'hasConfiguration' => true,
		'core' => true,
		'hasRetentionDay' => true,
		'test' => true
	);
	
	/*     * ***********************Méthodes statiques*************************** */
	
	public static function getConfigurationOption(){
		return array(
			'parameters_for_add' => array(
				'path' => array(
					'name' => __('Chemin',__FILE__),
					'type' => 'input',
				),
			),
			'configuration' => array(
				'backup::ip' => array(
					'name' => __('[Backup] IP',__FILE__),
					'type' => 'input',
				),
				'backup::username' => array(
					'name' => __('[Backup] Utilisateur',__FILE__),
					'type' => 'input',
				),
				'backup::password' => array(
					'name' => __('[Backup] Mot de passe',__FILE__),
					'type' => 'password',
				),
				'backup::share' => array(
					'name' => __('[Backup] Partage',__FILE__),
					'type' => 'input',
				),
				'backup::folder' => array(
					'name' => __('[Backup] Chemin',__FILE__),
					'type' => 'input',
				),
			),
		);
	}
	
	
	public static function checkUpdate(&$_update) {
		if (is_array($_update)) {
			if (count($_update) < 1) {
				return;
			}
			foreach ($_update as $update) {
				self::checkUpdate($update);
			}
			return;
		}
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
		$tmp_dir = jeedom::getTmpFolder('samba');
		$tmp = $tmp_dir . '/' . $_update->getLogicalId() . '.zip';
		if (file_exists($tmp)) {
			unlink($tmp);
		}
		if (!is_writable($tmp_dir)) {
			exec(system::getCmdSudo() . 'chmod 777 -R ' . $tmp);
		}
		if (!is_writable($tmp_dir)) {
			throw new Exception(__('Impossible d\'écrire dans le répertoire : ', __FILE__) . $tmp . __('. Exécuter la commande suivante en SSH : sudo chmod 777 -R ', __FILE__) . $tmp_dir);
		}
		$cmd = 'cd ' . $tmp_dir . ';';
		$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::plugin::folder') . ';get ' . $_update->getConfiguration('path'), 'plugin');
		com_shell::execute($cmd);
		$pathinfo = pathinfo($_update->getConfiguration('path'));
		com_shell::execute('mv ' . $tmp_dir . '/' . $pathinfo['basename'] . ' ' . $tmp);
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
		return system::getCmdSudo() . 'smbclient ' . config::byKey('samba::' . $_type . '::share') . ' -U "' . config::byKey('samba::' . $_type . '::username') . '%' . config::byKey('samba::' . $_type . '::password') . '" -I ' . config::byKey('samba::' . $_type . '::ip') . ' -c "' . $_cmd . '"';
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
	
	public static function test() {
		$cmd = repo_samba::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';ls', 'backup');
		try {
			$result = explode("\n", com_shell::execute($cmd));
			return True;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public static function cleanBackupFolder() {
		$timelimit = strtotime('-' . config::byKey('samba::keepDays') . ' days');
		foreach (self::ls(config::byKey('samba::backup::folder')) as $file) {
			if($file['filename'] == '..' || $file['filename'] == '.'){
				continue;
			}
			if ($timelimit > strtotime($file['datetime'])) {
				echo 'Delete backup too old : '.json_encode($file);
				$cmd = self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';del ' . $file['filename']);
				com_shell::execute($cmd);
			}
		}
	}
	
	public static function backup_send($_path) {
		$pathinfo = pathinfo($_path);
		$cmd = 'cd ' . $pathinfo['dirname'] . ';';
		$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';put ' . $pathinfo['basename']);
		com_shell::execute($cmd);
		self::cleanBackupFolder();
	}
	
	public static function backup_list() {
		$return = array();
		foreach (self::ls(config::byKey('samba::backup::folder')) as $file) {
			if (strpos($file['filename'],'.tar.gz') !== false) {
				$return[] = $file['filename'];
			}
		}
		return $return;
	}
	
	public static function backup_restore($_backup) {
		$backup_dir = calculPath(config::byKey('backup::path'));
		$cmd = 'cd ' . $backup_dir . ';';
		$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';get ' . $_backup);
		com_shell::execute($cmd);
		com_shell::execute(system::getCmdSudo() . 'chmod 777 -R ' . $backup_dir . '/*');
		jeedom::restore('backup/' . $_backup, true);
	}
	
	public static function downloadCore($_path) {
		$pathinfo = pathinfo($_path);
		$cmd = 'cd ' . $pathinfo['dirname'] . ';';
		$cmd .= self::makeSambaCommand('get ' . config::byKey('samba::core::path') . '/jeedom.zip', 'plugin');
		com_shell::execute($cmd);
		com_shell::execute(system::getCmdSudo() . 'chmod 777 -R ' . $_path);
		return;
	}
	
	public static function versionCore() {
		try {
			if (file_exists(jeedom::getTmpFolder('samba') . '/version')) {
				com_shell::execute(system::getCmdSudo() . 'rm /tmp/jeedom_version');
			}
			$cmd = 'cd /tmp;';
			$cmd .= self::makeSambaCommand('get ' . config::byKey('samba::core::path') . '/jeedom_version', 'plugin');
			com_shell::execute($cmd);
			if (!file_exists(jeedom::getTmpFolder('samba') . '/version')) {
				return null;
			}
			$version = trim(file_get_contents(jeedom::getTmpFolder('samba') . '/version'));
			com_shell::execute(system::getCmdSudo() . 'rm ' . jeedom::getTmpFolder('samba') . '/version');
			return $version;
		} catch (Exception $e) {
			
		} catch (Error $e) {
			
		}
		return null;
	}
	
	/*     * *********************Methode d'instance************************* */
	
	/*     * **********************Getteur Setteur*************************** */
	
}
