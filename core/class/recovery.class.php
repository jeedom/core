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

	/* * ***********************Static Methods*************************** */
	public static function isInstalled() {
		switch (system::getArch()) {
			case 'arm64':
				return file_exists('/etc/jeedom_board');
			default:
				return false;
		}
	}

	public static function install(bool $_force = false) {
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
						file_put_contents('/etc/jeedom_board', ucfirst(jeedom::getHardwareName()));
					}
					return true;
				}

				self::writeLog(__('Impossible de mettre à jour le script de démarrage', __FILE__), 'error');
				return false;
			default:
				return false;
		}
	}

	public static function start(string $_mode) {
		cache::delete(self::CANCEL);
		cache::set(self::PROGRESS, false, 60);
		self::setProgress(['step' => __("Initialisation", __FILE__), 'details' => __('Démarrage de la procédure de restauration système en mode', __FILE__) . ' ' . strtoupper($_mode), 'progress' => 0], 3);

		try {
			switch (system::getArch()) {
				case 'arm64':
					$downloadPath = realpath(__DIR__ . '/../../install/update');
					if ($_mode == 'usb') {
						if (!$usbDevice = self::usbConnected()) {
							throw new Exception(__('Périphérique USB non détecté', __FILE__));
						}
						$downloadPath = '/mnt/usb';
						if (!file_exists($downloadPath)) {
							shell_exec('sudo mkdir ' . $downloadPath);
						}
						shell_exec('sudo mount ' . $usbDevice . '1 ' . $downloadPath);
					}

					self::setProgress(['details' => __("Collecte des informations sur l'image système", __FILE__), 'progress' => 2], 1);
					$hardware = strtolower(jeedom::getHardwareName());
					$imgInfos = self::getImgInfos($hardware);
					jeedom::cleanFileSystemRight();
					self::downloadImage($imgInfos['url'], $downloadPath . '/' . $imgInfos['name'], $imgInfos['SHA256']);

					self::setProgress(['step' => __("Finalisation", __FILE__), 'details' => '', 'progress' => 98], 2);
					if ($_mode == 'usb') {
						self::setProgress(['details' => __('Ecriture du fichier de configuration USB', __FILE__), 'progress' => 99], 1);
						file_put_contents($downloadPath . '/JeedomSystemUpdate.ini', 'update_filename="' . $imgInfos['name'] . '"');
						shell_exec('sudo umount ' . $downloadPath);
					}

					$message = __('Le nouveau système est prêt à être déployé automatiquement au prochain démarrage', __FILE__);
					$message .= "\n" . __('Veuillez redémarrer pour effectuer la restauration', __FILE__);
					if ($_mode == 'usb') {
						$message = __('Le nouveau système est prêt à être installé depuis la clé USB de restauration', __FILE__);
						$message .= "\n" . __('Veuillez redémarrer avec la clé USB branchée dans le port en haut à droite pour effectuer la restauration', __FILE__);
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

	public static function cancel() {
		cache::set(self::CANCEL, true, 60);
	}

	public static function usbConnected() {
		foreach (['/dev/sda', '/dev/sdb', '/dev/sdc', '/dev/sdd'] as $device) {
			if (file_exists($device)) {
				$deviceInfos = shell_exec('udevadm info -q path -n ' . $device);
				if (stripos($deviceInfos, '/usb1/1-1/') !== false) {
					return $device;
				}
			}
		}
		return false;
	}

	public static function getProgress() {
		return cache::byKey(self::PROGRESS)->getValue();
	}

	private static function setProgress(array $_progress, int $_pause = null) {
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
			$log = (isset($_progress['step']) ? $_progress['step'] . ' - ' : '') . (isset($_progress['details']) ? $_progress['details'] : '');
			self::writeLog($log, $level);
			sleep($_pause);
		}
	}

	private static function downloadImage(string $_url, string $_path, string $_sha256) {
		self::setProgress(['step' => __("Téléchargement de l'image système", __FILE__), 'details' => '', 'progress' => 4], 2);

		if (file_exists($_path)) {
			self::setProgress(['details' => __("Vérification de l'intégrité de l'image système trouvée sur la cible", __FILE__), 'progress' => 95], 1);
			try {
				self::validateImage($_path, $_sha256, false);
				self::setProgress(['details' => __("Image système validée avec succès", __FILE__), 'progress' => 98], 2);
				return;
			} catch (Exception $e) {
				if (cache::exist(self::CANCEL)) {
					throw new Exception($e->getMessage());
				} else {
					self::setProgress(['details' => __('Image système invalide, reprise du téléchargement', __FILE__), 'progress' => 5], 1);
				}
			}
		} else {
			self::setProgress(['details' => __("Début du téléchargement", __FILE__), 'progress' => 5], 1);
		}

		$error = false;
		$ch = curl_init();
		$fp = fopen($_path, 'wb');

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
			$error = __("Erreur lors du téléchargement", __FILE__) . ' - ' . curl_error($ch);
			if (cache::exist(self::CANCEL)) {
				$error = __("Téléchargement annulé à la demande de l'utilisateur", __FILE__);
			}
		}

		curl_close($ch);
		fclose($fp);

		if ($error) {
			unlink($_path);
			throw new Exception($error);
		}

		self::setProgress(['details' => __("Vérification de l'intégrité de l'image système téléchargée", __FILE__), 'progress' => 95], 1);
		self::validateImage($_path, $_sha256);
		self::setProgress(['details' => __("Image système téléchargée avec succès", __FILE__), 'progress' => 98], 2);
	}

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

	private static function validateImage(string $_filepath, string $_sha256, bool $_unlinkOnCancel = true) {
		$sha256 = hash_file('sha256', $_filepath);
		if (cache::exist(self::CANCEL)) {
			if ($_unlinkOnCancel) {
				unlink($_filepath);
			}
			throw new Exception(__("Vérification annulée à la demande de l'utilisateur", __FILE__));
		}
		if ($sha256 != $_sha256) {
			unlink($_filepath);
			throw new Exception(__("Erreur lors de la vérification de l'image système", __FILE__) . ' (' . $sha256 . ' != ' . $_sha256) . ')';
		}
	}

	private static function getImgInfos(string $_hardware, string $_revision = 'stable') {
		$url = 'https://images.jeedom.com/';
		$jsonUrl = $url . $_hardware . '/info.json';

		$jsonContent = @file_get_contents($jsonUrl);
		if ($jsonContent === false) {
			throw new Exception(__("Abandon, impossible de récupérer les informations sur l'image système", __FILE__) . ' (' . $jsonUrl . ')');
		}

		$imgInfos = json_decode($jsonContent, true);
		if (isset($imgInfos[$_revision]) && isset($imgInfos[$_revision]['name']) && isset($imgInfos[$_revision]['SHA256'])) {
			$imgInfos[$_revision]['url'] = $url . $_hardware . '/' . $imgInfos[$_revision]['name'];
			return $imgInfos[$_revision];
		}

		throw new Exception(__("Abandon, informations sur l'image système manquantes", __FILE__) . ' - ' . print_r($imgInfos, true));
	}

	private static function calculPercentProgress($_done, $_total, float $_max = 100, float $_base = 0) {
		$percent = round(($_done / $_total) * 100, 1);

		if ($_base > 0 && $_base < $_max) {
			$percent = round($percent * ($_base / $_max) + $_base, 1);
		}

		if ($percent < 0) {
			return 0;
		}

		if ($percent > $_max) {
			return $_max;
		}

		return $percent;
	}

	private static function writeLog(string $_message, string $_level = 'info') {
		$logLine = "[" . date('Y-m-d H:i:s') . "][" . strtoupper($_level) . "] : " . $_message . PHP_EOL;
		file_put_contents(log::getPathToLog(__CLASS__), $logLine, FILE_APPEND);
	}
}
