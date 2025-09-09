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

class recovery {
	/* * *************************Constants****************************** */
	private const PROGRESS = 'jeedomRecovery';
	private const CANCEL = 'jeedomRecoveryCancellation';
	private const DEFAULT_IMGNAME = 'JeedomSystemUpdate.img.gz';

	/* * ***********************Static Methods*************************** */
	public static function isInstalled(): bool {
		switch (system::getArch()) {
			case 'arm64':
				return file_exists('/etc/jeedom_board');
			default:
				return false;
		}
	}

	public static function install(bool $_force = false): bool {
		switch (system::getArch()) {
			case 'arm64':
				self::writeLog(__('Vérification du script de démarrage', __FILE__));
				if (!$_force && self::isInstalled()) {
					self::writeLog(__('Le script de démarrage est à jour', __FILE__), 'debug');
					return true;
				}

				$cmd = system::getCmdSudo() . ' /bin/bash ' . __DIR__ . '/../../resources/update_boot_script.sh';
				$cmd .= ($_force) ? ' -f' : '';
				$cmd .= ' >> ' . log::getPathToLog(__CLASS__) . ' 2>&1';
				exec($cmd, $output, $returnCode);
				if ($returnCode == 0) {
					self::writeLog(__('Le script de démarrage a été mis à jour', __FILE__), 'debug');
					if (!self::isInstalled()) {
						shell_exec('echo ' . ucfirst(jeedom::getHardwareName()) . ' | sudo tee /etc/jeedom_board');
					}
					return true;
				}

				self::writeLog(__('Impossible de mettre à jour le script de démarrage', __FILE__), 'error');
				return false;
			default:
				return false;
		}
	}

	public static function start(string $_hardware, string $_mode = 'auto'): bool {
		cache::delete(self::CANCEL);
		cache::set(self::PROGRESS, false, 60);
		self::writeLog('-----------------------------------------------------------------------------------------', __('Restauration', __FILE__) . ' ' . $_hardware);
		self::setProgress(['step' => __('Initialisation', __FILE__) . ' (' . strtoupper($_mode) . ')', 'details' => __('Initialisation de la procédure de restauration système', __FILE__), 'progress' => 0], 2);

		try {
			switch (system::getArch()) {
				case 'arm64':
					$imgInfos = self::getImgInfos($_hardware);

					if ($_mode == 'usb') {
						$downloadPath = '/mnt/usb';
						self::prepareUsbDevice($_hardware, $downloadPath);
					} else {
						$downloadPath = realpath(__DIR__ . '/../../install/update');
					}
					if (!file_exists($downloadPath . '/' . $imgInfos['name']) && !file_exists($downloadPath . '/' . self::DEFAULT_IMGNAME)) {
						self::checkFreeSpace($downloadPath, $imgInfos['size']);
					}
					self::downloadAndValidateImage($imgInfos['url'], $downloadPath . '/' . $imgInfos['name'], $imgInfos['SHA256']);

					self::setProgress(['step' => __('Finalisation', __FILE__) . ' (' . strtoupper($_mode) . ')', 'details' => __('Finalisation de la procédure de restauration système', __FILE__), 'progress' => 98], 2);
					if ($_mode == 'usb') {
						if (!file_exists($downloadPath . '/' . self::DEFAULT_IMGNAME)) {
							self::setProgress(['details' => __('Ecriture du fichier de configuration USB', __FILE__), 'progress' => 99], 1);
							if (!file_put_contents($downloadPath . '/JeedomSystemUpdate.ini', 'update_filename="' . $imgInfos['name'] . '"')) {
								throw new Exception(__('Une erreur est survenue lors de la finalisation de la procédure de restauration système', __FILE__));
							}
						}
						shell_exec('sudo umount ' . $downloadPath);
						$message = __('Le nouveau système est prêt à être installé depuis la clé USB de restauration', __FILE__);
						$message .= "\n\n" . __('Veuillez redémarrer avec la clé USB branchée dans le port en haut à droite pour effectuer la restauration', __FILE__);
					} else {
						if (!file_exists($downloadPath . '/' . self::DEFAULT_IMGNAME)) {
							self::setProgress(['details' => __('Renommage du fichier de restauration système', __FILE__), 'progress' => 99], 1);
							if (!rename($downloadPath . '/' . $imgInfos['name'], $downloadPath . '/' . self::DEFAULT_IMGNAME)) {
								throw new Exception(__('Une erreur est survenue lors de la finalisation de la procédure de restauration système', __FILE__));
							}
						}
						$message = __('Le nouveau système est prêt à être déployé automatiquement au prochain démarrage', __FILE__);
						$message .= "\n\n" . __('Veuillez redémarrer pour effectuer la restauration', __FILE__);
					}
					self::setProgress(['step' => __("Félicitations", __FILE__), 'details' => $message, 'progress' => 100], 2);
					return true;
				default:
					throw new Exception(__('Cette fonctionnalité est uniquement disponible sur les systèmes à base ARM64', __FILE__));
			}
		} catch (Exception $e) {
			self::setProgress(['details' => $e->getMessage(), 'progress' => -1], 2);
			return false;
		}
	}

	public static function cancel(): void {
		cache::set(self::CANCEL, true, 60);
	}

	/** @return string|false */
	public static function usbConnected(string $_hardware) {
		foreach (['/dev/sda', '/dev/sdb', '/dev/sdc', '/dev/sdd'] as $device) {
			if (file_exists($device)) {
				$deviceInfos = shell_exec('udevadm info -q path -n ' . $device);
				switch ($_hardware) {
					case 'smart':
						if (strpos($deviceInfos, '/c9100000.usb/usb') !== false) {
							return $device;
						}
						break;
					case 'atlas':
						if (strpos($deviceInfos, '/fe3c0000.usb/usb') !== false) {
							return $device;
						}
						break;
					default:
						if (strpos($deviceInfos, '/usb1/1-1/') !== false) {
							return $device;
						}
						break;
				}
			}
		}
		return false;
	}

	public static function getProgress() {
		return cache::byKey(self::PROGRESS)->getValue();
	}

	private static function setProgress(array $_progress, int $_pause = null): void {
		cache::byKey(self::PROGRESS)->setValue(json_encode($_progress))->setLifetime(60)->save();

		if ($_pause) {
			$level = 'info';
			if (isset($_progress['progress'])) {
				if ($_progress['progress'] == 100) {
					$level = 'debug';
				} else if ($_progress['progress'] > 0) {
					$level = $_progress['progress'] . '%';
				} else if ($_progress['progress'] < 0) {
					$level = 'error';
					if (cache::exist(self::CANCEL)) {
						$level = 'warning';
					}
				}
			}
			$log = (isset($_progress['step']) ? $_progress['step'] . ' : ' : '') . (isset($_progress['details']) ? $_progress['details'] : '');
			self::writeLog($log, $level);
			sleep($_pause);
		}
	}

	private static function downloadAndValidateImage(string $_url, string $_filepath, string $_sha256): void {
		self::setProgress(['step' => __("Téléchargement de l'image système", __FILE__), 'details' => __('Début du téléchargement', __FILE__), 'progress' => 5], 2);
		if (file_exists($imgPath = $_filepath) || file_exists($imgPath = dirname($_filepath) . '/' . self::DEFAULT_IMGNAME)) {
			try {
				self::validateImage($imgPath, $_sha256, false);
				return;
			} catch (Exception $e) {
				if (cache::exist(self::CANCEL)) {
					throw new Exception($e->getMessage());
				} else {
					self::setProgress(['details' => __('Image système invalide, reprise du téléchargement', __FILE__), 'progress' => 5], 1);
				}
			}
		}

		$error = false;
		$ch = curl_init();
		$fp = fopen($_filepath, 'wb');

		curl_setopt_array($ch, [
			CURLOPT_URL => $_url,
			CURLOPT_HEADER => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_FILE => $fp,
			CURLOPT_PROGRESSFUNCTION => ['self', 'downloadImageProgress'],
			CURLOPT_NOPROGRESS => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_FAILONERROR => true
		]);

		curl_exec($ch);

		if (curl_errno($ch)) {
			$error = __("Erreur lors du téléchargement", __FILE__) . ' : ' . curl_error($ch);
			if (cache::exist(self::CANCEL)) {
				$error = __("Téléchargement annulé à la demande de l'utilisateur", __FILE__);
			}
		}

		curl_close($ch);
		fclose($fp);

		if ($error) {
			unlink($_filepath);
			throw new Exception($error);
		}

		self::validateImage($_filepath, $_sha256);
	}

	/** @return void|int */
	private static function downloadImageProgress($_resource, $_downloadSize, $_downloaded) {
		if (cache::exist(self::CANCEL)) {
			return 1;
		}

		if ($_downloaded > 0 && $_downloadSize > 0) {
			$percent = self::calculPercentProgress($_downloaded, $_downloadSize, 95, 5);
			$downloaded = cmd::autoValueArray($_downloaded, 2, 'o');
			$downloadSize = cmd::autoValueArray($_downloadSize, 2, 'o');
			$downloadSpeed = cmd::autoValueArray(curl_getinfo($_resource, CURLINFO_SPEED_DOWNLOAD), 2, 'o');
			self::setProgress(['details' => $downloaded[0] . $downloaded[1] . '/' . $downloadSize[0] . $downloadSize[1] . ' (' . $downloadSpeed[0] . $downloadSpeed[1] . '/s)', 'progress' => $percent]);
		}
	}

	private static function validateImage(string $_filepath, string $_sha256, bool $_downloaded = true): void {
		if ($_downloaded) {
			$message = __("Vérification de l'intégrité de l'image système téléchargée", __FILE__);
		} else {
			$message = __("Vérification de l'intégrité de l'image système trouvée sur la cible", __FILE__);
		}
		self::setProgress(['details' => $message, 'progress' => 95], 1);

		$sha256 = hash_file('sha256', $_filepath);
		if (cache::exist(self::CANCEL)) {
			if ($_downloaded) {
				unlink($_filepath);
			}
			throw new Exception(__("Vérification annulée à la demande de l'utilisateur", __FILE__));
		}
		if ($sha256 != $_sha256) {
			unlink($_filepath);
			throw new Exception(__("Erreur lors de la vérification de l'image système", __FILE__) . ' (' . $sha256 . ' != ' . $_sha256) . ')';
		}
	}

	/** @return array|void */
	private static function getImgInfos(string $_hardware) {
		self::setProgress(['details' => __("Collecte des informations concernant l'image système", __FILE__) . ' ' . ucfirst($_hardware), 'progress' => 2], 1);
		$url = 'https://images.jeedom.com/';
		$jsonUrl = $url . $_hardware . '/info.json';

		$jsonContent = @file_get_contents($jsonUrl);
		if ($jsonContent === false) {
			throw new Exception(__("Impossible de récupérer les informations relatives aux images systèmes", __FILE__) . ' (' . $jsonUrl . ')');
		}

		$imgInfos = json_decode($jsonContent, true);
		$minOsVersion = config::byKey('os::min');
		$currentOsVersion = trim(shell_exec('lsb_release -rs'));
		$osVersion = ((int) $currentOsVersion > (int) $minOsVersion) ? $currentOsVersion : $minOsVersion;
		if (isset($imgInfos[$osVersion]) && isset($imgInfos[$osVersion]['name']) && isset($imgInfos[$osVersion]['SHA256'])) {
			$imgInfos[$osVersion]['url'] = $url . $_hardware . '/' . $imgInfos[$osVersion]['name'];
			$imgInfos[$osVersion]['size'] = ceil((int) trim(shell_exec("curl -sI " . $imgInfos[$osVersion]['url'] . " | grep content-length | awk '{print $2}'")) / 1024);
			return $imgInfos[$osVersion];
		}

		throw new Exception(__("Impossible de trouver les informations requises concernant l'image système", __FILE__) . ' ' . ucfirst($_hardware) .  ' ' . __('en version', __FILE__) . ' ' . $osVersion);
	}

	private static function prepareUsbDevice(string $_hardware, string $_mountPath = '/mnt/usb'): void {
		self::setProgress(['details' => __('Vérification du périphérique USB', __FILE__), 'progress' => 3], 1);
		if (!$usbDevice = self::usbConnected($_hardware)) {
			throw new Exception(__('Périphérique USB non détecté', __FILE__));
		}

		$partition = $usbDevice . '1';
		$fsType = trim(shell_exec('sudo blkid -s TYPE -o value ' . $partition));
		if ($fsType !== 'vfat') {
			throw new Exception(__("Le système de fichiers de la 1ère partition du périphérique USB n'est pas de type FAT", __FILE__) . ' (' . $fsType . ')');
		}

		if (!file_exists($_mountPath)) {
			shell_exec('sudo mkdir ' . $_mountPath);
		} else if (!empty(shell_exec('mount | grep ' . $_mountPath))) {
			shell_exec('sudo umount ' . $_mountPath);
		}
		exec('sudo mount -o rw,uid=www-data,gid=www-data ' . $partition . ' ' . $_mountPath, $output, $returnCode);
		if ($returnCode !== 0 || empty(shell_exec('mount | grep ' . $_mountPath))) {
			throw new Exception(__("Impossible d'accéder au périphérique USB, vérifier les logs http.error", __FILE__) . ' (' . $partition . ')');
		}
	}

	private static function checkFreeSpace(string $_path, int $_imgSize = 1500000): void {
		self::setProgress(['details' => __("Vérification de l'espace disque disponible", __FILE__), 'progress' => 4], 1);
		$available = (int) trim(shell_exec("sudo df --output=avail -k $_path | tail -1"));
		if ($available < $_imgSize) {
			throw new Exception(__('Espace disque disponible insuffisant', __FILE__) . ' : ' . $available . 'Ko < ' . $_imgSize . 'Ko (' . $_path . ')');
		}
	}

	private static function calculPercentProgress($_done, $_total, float $_max = 100, float $_base = 0): float {
		$rawPercent = $_done / $_total;
		$mappedPercent = $_base + ($rawPercent * ($_max - $_base));
		$percent = round($mappedPercent, 1);
		// return min(max($percent, $_base), $_max);
		return $percent;
	}

	private static function writeLog(string $_message, string $_level = 'info'): void {
		$logLine = "[" . date('Y-m-d H:i:s') . "][" . strtoupper($_level) . "] " . $_message . PHP_EOL;
		file_put_contents(log::getPathToLog(__CLASS__), $logLine, FILE_APPEND);
	}
}
