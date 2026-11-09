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

	public static function getConfigurationOption() {
		return array(
			'parameters_for_add' => array(
				'path' => array(
					'name' => __('Chemin du fichier ZIP', __FILE__),
					'type' => 'input',
					'tooltip' => __("Renseigner le chemin du plugin compressé", __FILE__) . ' (*.zip)'
				),
			),
			'configuration' => array(
				'backup::ip' => array(
					'name' => __('[Backup] IP', __FILE__),
					'type' => 'input'
				),
				'backup::username' => array(
					'name' => __('[Backup] Utilisateur', __FILE__),
					'type' => 'input'
				),
				'backup::password' => array(
					'name' => __('[Backup] Mot de passe', __FILE__),
					'type' => 'password'
				),
				'backup::share' => array(
					'name' => __('[Backup] Partage', __FILE__),
					'type' => 'input'
				),
				'backup::folder' => array(
					'name' => __('[Backup] Chemin', __FILE__),
					'type' => 'input'
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
			throw new Exception(__('Impossible d\'écrire dans le répertoire :', __FILE__) . ' ' . $tmp . __('. Exécuter la commande suivante en SSH : sudo chmod 777 -R', __FILE__) . ' ' . $tmp_dir);
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

	private static function makeSambaCommand(string $_cmd, string $_type = 'backup'): string {
		$authfile = self::createAuthFile($_type);
		$authfile = escapeshellarg($authfile);
		return '(' . system::getCmdSudo() . 'smbclient -t 120 ' . escapeshellarg(config::byKey('samba::' . $_type . '::share'))
			. ' -A ' . $authfile . ' -I ' . escapeshellarg(config::byKey('samba::' . $_type . '::ip')) . ' -c ' . escapeshellarg($_cmd)
			. '; ec=$?; ' . system::getCmdSudo() . 'rm -f ' . $authfile . '; exit $ec)';
	}

	/**
	 * Write username/password to a private temp file so they never appear in the process command line
	 * @param string $_type
	 * @return string path to the authentication file
	 */
	private static function createAuthFile(string $_type): string {
		$authfile = jeedom::getTmpFolder('samba') . '/.smbauth_' . bin2hex(random_bytes(16));
		$content = 'username = ' . config::byKey('samba::' . $_type . '::username') . "\n"
			. 'password = ' . config::byKey('samba::' . $_type . '::password') . "\n";
		file_put_contents($authfile, $content);
		chmod($authfile, 0600);
		return $authfile;
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
		foreach ($result as $entry) {
			if (!preg_match('/^\s*(.*?)\s{2,}\S+\s+(\d+)\s+\S+\s+(\S+\s+\d+\s+\S+\s+\d{4})\s*$/', $entry, $line)
				|| in_array($line[1], ['.', '..'], true)) {
				continue;
			}
			$file_info = array();
			$file_info['filename'] = $line[1];
			$file_info['size'] = $line[2];
			$file_info['datetime'] = date('Y-m-d H:i:s', (int) strtotime($line[3]));
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
			if ($file['filename'] == '..' || $file['filename'] == '.') {
				continue;
			}
			if ($timelimit > strtotime($file['datetime'])) {
				echo 'Delete backup too old : ' . json_encode($file);
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
		return array_column(self::backup_list_details(), 'name');
	}

	public static function backup_list_details() {
		$return = array();
		foreach (self::ls(config::byKey('samba::backup::folder')) as $file) {
			if (strpos($file['filename'], '.tar.gz') !== false) {
				$return[] = array('name' => $file['filename'], 'size' => (int) $file['size']);
			}
		}
		return $return;
	}

	public static function isBackupRestoreName(string $_name): bool {
		return jeedom::isBackupImportName($_name) && strpbrk($_name, ';"*?') === false;
	}

	public static function backup_restore($_backup) {
		if (!self::isBackupRestoreName($_backup)) {
			throw new InvalidArgumentException('Nom de sauvegarde Samba invalide.', 422);
		}
		$backup_dir = calculPath(config::byKey('backup::path'));
		if (!is_dir($backup_dir) && !@mkdir($backup_dir, 0770, true) && !is_dir($backup_dir)) {
			throw new RuntimeException('Impossible de créer le dossier de sauvegarde.');
		}
		$temporary = $backup_dir . '/.samba-' . bin2hex(random_bytes(16));
		if (!mkdir($temporary, 0700)) {
			throw new RuntimeException('Impossible de préparer le rapatriement de la sauvegarde Samba.');
		}
		try {
			$cmd = 'cd ' . escapeshellarg($temporary) . ' && ';
			$cmd .= self::makeSambaCommand('cd ' . config::byKey('samba::backup::folder') . ';get "' . $_backup . '"');
			com_shell::execute($cmd);
			$download = $temporary . '/' . $_backup;
			if (!is_file($download) || is_link($download)) {
				throw new RuntimeException('Sauvegarde Samba introuvable.', 404);
			}
			$sudo = system::getCmdSudo();
			if ($sudo !== '') {
				com_shell::execute($sudo . 'chown -- ' . posix_geteuid() . ':' . posix_getegid() . ' ' . escapeshellarg($download));
			}
			if (!chmod($download, 0600) || !rename($download, $backup_dir . '/' . $_backup)) {
				throw new RuntimeException('Impossible d’enregistrer la sauvegarde Samba.');
			}
		} finally {
			rrmdir($temporary);
		}
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
		} catch (\Throwable $e) {
		}
		return null;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
