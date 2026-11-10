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
		'plugin' => false,
		'backup' => true,
		'hasConfiguration' => true,
		'core' => false,
		'hasRetentionDay' => true,
		'test' => true
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function getConfigurationOption() {
		return array(
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


	private const BACKUP_CONNECTION_KEYS = ['ip', 'share', 'folder', 'username', 'password'];

	public static function backupEnabled(): bool {
		$flags = config::byKeys(['samba::enable', 'samba::cloudUpload']);
		return (string) $flags['samba::enable'] === '1' && (string) $flags['samba::cloudUpload'] === '1';
	}

	public static function validBackupSetting(string $_key, string $_value): bool {
		if (!in_array($_key, self::BACKUP_CONNECTION_KEYS, true) || strlen($_value) > 1024 || preg_match('/[\x00-\x1f\x7f]/', $_value)) {
			return false;
		}
		return $_value === '' || match ($_key) {
			'ip' => filter_var($_value, FILTER_VALIDATE_IP) !== false,
			'share' => preg_match('~\A//[^/]+/[^/]+/?\z~', str_replace('\\', '/', $_value)) === 1,
			default => true,
		};
	}

	private static function backupSettings(array $_overrides = []): array {
		$keys = array_map(static fn ($key) => 'samba::backup::' . $key, self::BACKUP_CONNECTION_KEYS);
		$stored = config::byKeys([...$keys, 'samba::keepDays']);
		$settings = [];
		foreach (self::BACKUP_CONNECTION_KEYS as $key) {
			$value = $_overrides[$key] ?? (string) $stored['samba::backup::' . $key];
			if (!self::validBackupSetting($key, $value)) {
				throw new InvalidArgumentException('Paramètre Samba invalide.', 422);
			}
			$settings[$key] = $value;
		}
		if ($settings['ip'] === '' || $settings['share'] === '' || trim($settings['username']) === '') {
			throw new InvalidArgumentException('Configuration Samba incomplète.', 422);
		}
		$settings['keepDays'] = (string) $stored['samba::keepDays'];
		return $settings;
	}

	private static function executeBackupCommand(string $_command, array $settings, ?string $_directory = null, int $_timeout = 120): string {
		$authfile = tempnam(jeedom::getTmpFolder('samba'), '.smbauth_');
		if ($authfile === false) {
			throw new RuntimeException('Impossible de préparer l’authentification Samba.');
		}
		try {
			$content = 'username = ' . $settings['username'] . "\npassword = " . $settings['password'] . "\n";
			if (!chmod($authfile, 0600) || file_put_contents($authfile, $content) !== strlen($content)) {
				throw new RuntimeException('Impossible de préparer l’authentification Samba.');
			}
			$cmd = $_directory === null ? '' : 'cd ' . escapeshellarg($_directory) . ' && ';
			$cmd .= system::getCmdSudo() . 'smbclient -t ' . $_timeout . ' ' . escapeshellarg(str_replace('\\', '/', $settings['share']))
				. ' -A ' . escapeshellarg($authfile) . ' -I ' . escapeshellarg($settings['ip']);
			if ($settings['folder'] !== '') {
				$cmd .= ' -D ' . escapeshellarg($settings['folder']);
			}
			return com_shell::execute($cmd . ' -c ' . escapeshellarg($_command));
		} finally {
			unlink($authfile);
		}
	}

	public static function sortByDatetime($a, $b) {
		if (strtotime($a['datetime']) == strtotime($b['datetime'])) {
			return 0;
		}
		return (strtotime($a['datetime']) < strtotime($b['datetime'])) ? -1 : 1;
	}

	private static function ls(array $settings) {
		$result = explode("\n", self::executeBackupCommand('ls', $settings));
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

	public static function test(array $_overrides = []): bool {
		self::executeBackupCommand('ls', self::backupSettings($_overrides), null, 10);
		return true;
	}

	private static function cleanBackupFolder(string $_keep, array $settings) {
		$days = $settings['keepDays'];
		if (!ctype_digit($days) || ($timelimit = strtotime('-' . $days . ' days')) === false) {
			return;
		}
		foreach (self::ls($settings) as $file) {
			$name = $file['filename'];
			$date = strtotime($file['datetime']);
			if ($name !== $_keep && self::isBackupRestoreName($name) && $date !== false && $date < $timelimit) {
				echo 'Delete backup too old : ' . json_encode($file);
				self::executeBackupCommand('del "' . $name . '"', $settings);
			}
		}
	}

	public static function backup_send($_path) {
		$name = basename($_path);
		if (!self::isBackupRestoreName($name)) {
			throw new InvalidArgumentException('Nom de sauvegarde Samba invalide.', 422);
		}
		$settings = self::backupSettings();
		self::executeBackupCommand('put "' . $name . '"', $settings, dirname($_path));
		self::cleanBackupFolder($name, $settings);
	}

	public static function backup_list() {
		return array_column(self::backup_list_details(), 'name');
	}

	public static function backup_list_details() {
		$return = array();
		foreach (self::ls(self::backupSettings()) as $file) {
			if (self::isBackupRestoreName($file['filename'])) {
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
			self::executeBackupCommand('get "' . $_backup . '"', self::backupSettings(), $temporary);
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

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}
