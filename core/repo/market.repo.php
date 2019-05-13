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

class repo_market {
	/*     * *************************Attributs****************************** */
	
	public static $_name = 'Market';
	
	public static $_scope = array(
		'plugin' => true,
		'backup' => true,
		'hasConfiguration' => true,
		'proxy' => true,
		'sendPlugin' => true,
		'hasStore' => true,
		'hasScenarioStore' => true,
		'test' => true,
	);
	
	public static $_configuration = array(
		'configuration' => array(
			'address' => array(
				'name' => 'Adresse',
				'type' => 'input',
			),
			'username' => array(
				'name' => 'Nom d\'utilisateur',
				'type' => 'input',
			),
			'password' => array(
				'name' => 'Mot de passe',
				'type' => 'password',
			),
			
			'cloud::backup::name' => array(
				'name' => '[Backup cloud] Nom',
				'type' => 'input',
			),
			'cloud::backup::password' => array(
				'name' => '[Backup cloud] Mot de passe',
				'type' => 'password',
			),
			'cloud::backup::fullfrequency' => array(
				'name' => '[Backup cloud] Fréquence backup full',
				'type' => 'select',
				'values' => array('1D' => 'Chaque jour', '1W' => 'Chaque semaine', '1M' => 'Chaque mois'),
			),
		),
		'parameters_for_add' => array(
			'version' => array(
				'name' => 'Version : beta, stable',
				'type' => 'input',
			),
		),
	);
	
	private $id;
	private $name;
	private $type;
	private $datetime;
	private $description;
	private $categorie;
	private $changelog;
	private $doc;
	private $version;
	private $user_id;
	private $downloaded;
	private $status;
	private $author;
	private $logicalId;
	private $rating;
	private $utilization;
	private $isAuthor;
	private $img;
	private $buyer;
	private $purchase = 0;
	private $cost = 0;
	private $realcost = 0;
	private $link;
	private $certification;
	private $language;
	private $private;
	private $updateBy;
	private $parameters;
	private $hardwareCompatibility;
	private $nbInstall;
	private $allowVersion = array();
	
	/*     * ***********************Méthodes statiques*************************** */
	
	public static function checkUpdate(&$_update) {
		if (is_array($_update)) {
			if (count($_update) < 1) {
				return;
			}
			$markets = array('logicalId' => array(), 'version' => array());
			$marketObject = array();
			foreach ($_update as $update) {
				$markets['logicalId'][] = array('logicalId' => $update->getLogicalId(), 'type' => $update->getType());
				$markets['version'][] = $update->getConfiguration('version', 'stable');
				$marketObject[$update->getType() . $update->getLogicalId()] = $update;
			}
			$markets_infos = repo_market::getInfo($markets['logicalId'], $markets['version']);
			foreach ($markets_infos as $logicalId => $market_info) {
				$update = $marketObject[$logicalId];
				if (is_object($update)) {
					$update->setStatus($market_info['status']);
					$update->setConfiguration('market', $market_info['market']);
					$update->setRemoteVersion($market_info['datetime']);
					if ($update->getConfiguration('version') == '') {
						$update->setConfiguration('version', 'stable');
					}
					$update->save();
				}
			}
			return;
		}
		$market_info = repo_market::getInfo(array('logicalId' => $_update->getLogicalId(), 'type' => $_update->getType()), $_update->getConfiguration('version', 'stable'));
		$_update->setStatus($market_info['status']);
		$_update->setConfiguration('market', $market_info['market']);
		$_update->setRemoteVersion($market_info['datetime']);
		$_update->save();
	}
	
	public static function downloadObject($_update) {
		$market = repo_market::byLogicalIdAndType($_update->getLogicalId(), $_update->getType());
		if (is_object($market)) {
			$file = $market->install($_update->getConfiguration('version', 'stable'));
		} else {
			throw new Exception(__('Objet introuvable sur le market : ', __FILE__) . $_update->getLogicalId() . '/' . $_update->getType());
		}
		return array('path' => $file, 'localVersion' => $market->getDatetime($_update->getConfiguration('version', 'stable')));
	}
	
	public static function deleteObjet($_update) {
		try {
			$market = repo_market::byLogicalIdAndType($_update->getLogicalId(), $_update->getType());
		} catch (Exception $e) {
			$market = new repo_market();
			$market->setLogicalId($_update->getLogicalId());
			$market->setType($_update->getType());
		} catch (Error $e) {
			$market = new repo_market();
			$market->setLogicalId($_update->getLogicalId());
			$market->setType($_update->getType());
		}
		try {
			if (is_object($market)) {
				$market->remove();
			}
		} catch (Exception $e) {
			
		} catch (Error $e) {
			
		}
	}
	
	public static function objectInfo($_update) {
		$url = 'https://jeedom.github.io/documentation/plugins/' . $_update->getLogicalId() . '/' . config::byKey('language', 'core', 'fr_FR') . '/index.html';
		if ($_update->getConfiguration('third_plugin', null) === null) {
			$_update->setConfiguration('third_plugin', 0);
			$header = get_headers($url);
			if (strpos($header[0], '200') === false) {
				$_update->setConfiguration('third_plugin', 1);
				$url = 'https://jeedom.github.io/documentation/third_plugin/' . $_update->getLogicalId() . '/' . config::byKey('language', 'core', 'fr_FR') . '/index.html';
			}
			$_update->save();
		} elseif ($_update->getConfiguration('third_plugin', 0) == 1) {
			$url = 'https://jeedom.github.io/documentation/third_plugin/' . $_update->getLogicalId() . '/' . config::byKey('language', 'core', 'fr_FR') . '/index.html';
		}
		return array(
			'doc' => $url,
			'changelog' => $url . '#_changelog',
			'display' => 'https://jeedom.com/market/index.php?v=d&p=market&type=plugin&plugin_id=' . $_update->getLogicalId(),
		);
	}
	
	/*     * ***********************BACKUP*************************** */
	
	public static function backup_install(){
		if (exec('which duplicity | wc -l') == 0) {
			try {
				com_shell::execute('sudo apt-get -y install duplicity');
			} catch (\Exception $e) {
				
			}
		}
	}
	
	public static function backup_createFolderIsNotExist() {
		$client = new Sabre\DAV\Client(array(
			'baseUri' => 'https://' . config::byKey('market::backupServer'),
			'userName' => config::byKey('market::username'),
			'password' => config::byKey('market::backupPassword'),
		));
		$adapter = new League\Flysystem\WebDAV\WebDAVAdapter($client);
		$filesystem = new League\Flysystem\Filesystem($adapter);
		$folders = $filesystem->listContents('/remote.php/webdav/');
		$found = false;
		if (count($folders) > 0) {
			foreach ($folders as $folder) {
				if ($folder['basename'] == config::byKey('market::cloud::backup::name')) {
					$found = true;
					break;
				}
			}
		}
		if (!$found) {
			$filesystem->createDir('/remote.php/webdav/' . config::byKey('market::cloud::backup::name'));
		}
	}
	
	public static function backup_send($_path) {
		if (config::byKey('market::backupServer') == '' || config::byKey('market::backupPassword') == '') {
			throw new Exception(__('Aucun serveur de backup defini. Avez vous bien un abonnement au backup cloud ?', __FILE__));
		}
		if (config::byKey('market::cloud::backup::password') == '') {
			throw new Exception(__('Vous devez obligatoirement avoir un mot de passe pour le backup cloud', __FILE__));
		}
		self::backup_createFolderIsNotExist();
		self::backup_install();
		$base_dir = realpath(__DIR__ . '/../../');
		if(!file_exists($base_dir . '/tmp')){
			mkdir($base_dir . '/tmp');
		}
		$excludes = array(
			$base_dir . '/tmp',
			$base_dir . '/log',
			$base_dir . '/backup',
			$base_dir . '/doc',
			$base_dir . '/docs',
			$base_dir . '/plugins/*/doc',
			$base_dir . '/plugins/*/docs',
			$base_dir . '/tests',
			$base_dir . '/.git',
			$base_dir . '/.log',
			$base_dir . '/core/config/common.config.php',
			$base_dir . '/' . config::byKey('backup::path'),
		);
		if (config::byKey('recordDir', 'camera') != '') {
			$excludes[] = $base_dir . '/' . config::byKey('recordDir', 'camera');
		}
		$cmd = system::getCmdSudo() . ' PASSPHRASE="' . config::byKey('market::cloud::backup::password') . '"';
		$cmd .= ' duplicity incremental --full-if-older-than ' . config::byKey('market::cloud::backup::fullfrequency', 'core', '1M');
		foreach ($excludes as $exclude) {
			$cmd .= ' --exclude "' . $exclude . '"';
		}
		$cmd .= ' --num-retries 2';
		$cmd .= ' --ssl-no-check-certificate';
		$cmd .= ' --tempdir '.$base_dir . '/tmp';
		$cmd .= ' ' . $base_dir . '  "webdavs://' . config::byKey('market::username') . ':' . config::byKey('market::backupPassword');
		$cmd .= '@' . config::byKey('market::backupServer') . '/remote.php/webdav/' . config::byKey('market::cloud::backup::name').'"';
		try {
			com_shell::execute($cmd);
		} catch (Exception $e) {
			if (self::backup_errorAnalyzed($e->getMessage()) != null) {
				throw new Exception('[backup cloud] ' . self::backup_errorAnalyzed($e->getMessage()));
			}
			if (strpos($e->getMessage(), 'Insufficient Storage') !== false) {
				self::backup_clean();
			}
			system::kill('duplicity');
			shell_exec(system::getCmdSudo() . ' rm -rf '.$base_dir . '/tmp/duplicity*');
			shell_exec(system::getCmdSudo() . ' rm -rf ~/.cache/duplicity/*');
			com_shell::execute($cmd);
		}
	}
	
	public static function backup_errorAnalyzed($_error) {
		if (strpos($_error, 'decryption failed: Bad session key') !== false) {
			return __('Clef de chiffrement invalide. Si vous oubliez votre mot de passe aucune récupération n\'est possible. Veuillez supprimer le backup à partir de votre page profil sur le market', __FILE__);
		}
		return null;
	}
	
	public static function backup_clean($_nb = null) {
		if (config::byKey('market::backupServer') == '' || config::byKey('market::backupPassword') == '') {
			return;
		}
		if (config::byKey('market::cloud::backup::password') == '') {
			return;
		}
		self::backup_install();
		shell_exec(system::getCmdSudo() . ' rm -rf /tmp/duplicity-*-tempdir');
		if ($_nb == null) {
			$_nb = 0;
			$lists = self::backup_list();
			foreach ($lists as $name) {
				if (strpos($name, 'Full') !== false) {
					$_nb++;
				}
			}
			$_nb = ($_nb - 2 < 1) ? 1 : $_nb - 2;
		}
		$cmd = system::getCmdSudo() . ' PASSPHRASE="' . config::byKey('market::cloud::backup::password') . '"';
		$cmd .= ' duplicity remove-all-but-n-full ' . $_nb . ' --force ';
		$cmd .= ' --ssl-no-check-certificate';
		$cmd .= ' --num-retries 1';
		$cmd .= ' "webdavs://' . config::byKey('market::username') . ':' . config::byKey('market::backupPassword');
		$cmd .= '@' . config::byKey('market::backupServer') . '/remote.php/webdav/' . config::byKey('market::cloud::backup::name').'"';
		try {
			com_shell::execute($cmd);
		} catch (Exception $e) {
			if (self::backup_errorAnalyzed($e->getMessage()) != null) {
				throw new Exception('[restore cloud] ' . self::backup_errorAnalyzed($e->getMessage()));
			}
			throw new Exception('[restore cloud] ' . $e->getMessage());
		}
	}
	
	public static function backup_list() {
		if (config::byKey('market::backupServer') == '' || config::byKey('market::backupPassword') == '') {
			return array();
		}
		if (config::byKey('market::cloud::backup::password') == '') {
			return array();
		}
		self::backup_createFolderIsNotExist();
		self::backup_install();
		$return = array();
		$cmd = system::getCmdSudo();
		$cmd .= ' duplicity collection-status';
		$cmd .= ' --ssl-no-check-certificate';
		$cmd .= ' --num-retries 1';
		$cmd .= ' --timeout 60';
		$cmd .= ' "webdavs://' . config::byKey('market::username') . ':' . config::byKey('market::backupPassword');
		$cmd .= '@' . config::byKey('market::backupServer') . '/remote.php/webdav/' . config::byKey('market::cloud::backup::name').'"';
		try {
			$results = explode("\n", com_shell::execute($cmd));
		} catch (\Exception $e) {
			shell_exec(system::getCmdSudo() . ' rm -rf ~/.cache/duplicity/*');
			$results = explode("\n", com_shell::execute($cmd));
		}
		foreach ($results as $line) {
			if (strpos($line, 'Full') === false && strpos($line, 'Incremental') === false && strpos($line, 'Complète') === false && strpos($line, 'Incrémentale') === false) {
				continue;
			}
			$return[] = trim(substr($line, 0, -1));
		}
		return array_reverse($return);
	}
	
	public static function backup_restore($_backup) {
		$backup_dir = calculPath(config::byKey('backup::path'));
		if (!file_exists($backup_dir)) {
			mkdir($backup_dir, 0770, true);
		}
		if (!is_writable($backup_dir)) {
			throw new Exception('Impossible d\'accéder au dossier de sauvegarde. Veuillez vérifier les droits : ' . $backup_dir);
		}
		$restore_dir = '/tmp/jeedom_cloud_restore';
		if (file_exists($restore_dir)) {
			com_shell::execute(system::getCmdSudo() . ' rm -rf ' . $restore_dir);
		}
		self::backup_install();
		$base_dir =  '/usr/jeedom_duplicity';
		if(!file_exists($base_dir)){
			mkdir($base_dir);
		}
		mkdir($restore_dir);
		$timestamp = strtotime(trim(str_replace(array('Full', 'Incremental'), '', $_backup)));
		$backup_name = str_replace(' ', '_', 'backup-cloud-' . config::byKey('market::cloud::backup::name') . '-' . date("Y-m-d-H\hi", $timestamp) . '.tar.gz');
		$cmd = system::getCmdSudo() . ' PASSPHRASE="' . config::byKey('market::cloud::backup::password') . '"';
		$cmd .= ' duplicity --file-to-restore /';
		$cmd .= ' --time ' . $timestamp;
		$cmd .= ' --num-retries 1';
		$cmd .= ' --tempdir '.$base_dir;
		$cmd .= ' "webdavs://' . config::byKey('market::username') . ':' . config::byKey('market::backupPassword');
		$cmd .= '@' . config::byKey('market::backupServer') . '/remote.php/webdav/' . config::byKey('market::cloud::backup::name').'"';
		$cmd .= ' ' . $restore_dir;
		try {
			com_shell::execute($cmd);
		} catch (Exception $e) {
			if (self::backup_errorAnalyzed($e->getMessage()) != null) {
				throw new Exception('[restore cloud] ' . self::backup_errorAnalyzed($e->getMessage()));
			}
			throw new Exception('[restore cloud] ' . $e->getMessage());
		}
		shell_exec(system::getCmdSudo() . ' rm -rf '.$base_dir);
		system('cd ' . $restore_dir . ';tar cfz "' . $backup_dir . '/' . $backup_name . '" . > /dev/null');
		if (file_exists($restore_dir)) {
			com_shell::execute(system::getCmdSudo() . ' rm -rf ' . $restore_dir);
		}
		jeedom::restore($backup_dir . '/' . $backup_name, true);
	}
	
	/******************************MONITORING********************************/
	
	public static function monitoring_install() {
		if (file_exists('/etc/zabbix')) {
			return;
		}
		$logfile = log::getPathToLog('market_zabbix_installation');
		if (strpos(php_uname(), 'x86_64') !== false) {
			if (file_exists('/etc/debian_version')) {
				$deb_version = file_get_contents('/etc/debian_version');
				if (version_compare($deb_version, '9', '>=')) {
					shell_exec('cd /tmp/;' . system::getCmdSudo() . ' wget http://repo.zabbix.com/zabbix/4.0/debian/pool/main/z/zabbix-release/zabbix-release_4.0-2%2Bstretch_all.deb >> ' . $logfile . ' 2>&1;' . system::getCmdSudo() . ' dpkg -i zabbix-release_3.4-1+stretch_all.deb  >> ' . $logfile . ' 2>&1;' . system::getCmdSudo() . ' rm zabbix-release_3.4-1+stretch_all.deb  >> ' . $logfile . ' 2>&1');
				} else {
					shell_exec('cd /tmp/;' . system::getCmdSudo() . ' wget http://repo.zabbix.com/zabbix/4.0/debian/pool/main/z/zabbix-release/zabbix-release_4.0-2%2Bjessie_all.deb  >> ' . $logfile . ' 2>&1;' . system::getCmdSudo() . ' dpkg -i zabbix-release_3.4-1+jessie_all.deb  >> ' . $logfile . ' 2>&1;' . system::getCmdSudo() . ' rm zabbix-release_3.4-1+jessie_all.deb  >> ' . $logfile . ' 2>&1');
				}
			}
		}
		shell_exec(system::getCmdSudo() . ' apt-get update  >> ' . $logfile . ' 2>&1');
		shell_exec(system::getCmdSudo() . ' apt-get -y install zabbix-agent  >> ' . $logfile . ' 2>&1');
	}
	
	public static function monitoring_start() {
		preg_match_all('/(\d\.\d\.\d)/m', shell_exec(system::getCmdSudo() . ' zabbix_agentd -V'), $matches);
		self::monitoring_install();
		$cmd = system::getCmdSudo() . " chmod -R 777 /etc/zabbix;";
		$cmd .= system::getCmdSudo() . " sed -i '/ServerActive=/d' /etc/zabbix/zabbix_agentd.conf;";
		$cmd .= system::getCmdSudo() . " sed -i '/Hostname=/d' /etc/zabbix/zabbix_agentd.conf;";
		$cmd .= system::getCmdSudo() . " sed -i '/TLSConnect=/d' /etc/zabbix/zabbix_agentd.conf;";
		$cmd .= system::getCmdSudo() . " sed -i '/TLSAccept=/d' /etc/zabbix/zabbix_agentd.conf;";
		$cmd .= system::getCmdSudo() . " sed -i '/TLSPSKIdentity=/d' /etc/zabbix/zabbix_agentd.conf;";
		$cmd .= system::getCmdSudo() . " sed -i '/TLSPSKFile=/d' /etc/zabbix/zabbix_agentd.conf;";
		$cmd .= system::getCmdSudo() . ' echo "ServerActive=' . config::byKey('market::monitoringServer') . '" >> /etc/zabbix/zabbix_agentd.conf;';
		$cmd .= system::getCmdSudo() . ' echo "Hostname=' . config::byKey('market::monitoringName') . '" >> /etc/zabbix/zabbix_agentd.conf;';
		if (!file_exists('/var/log/zabbix')) {
			$cmd .= system::getCmdSudo() . ' mkdir /var/log/zabbix;';
		}
		$cmd .= system::getCmdSudo() . ' chmod 777 -R /var/log/zabbix;';
		if (!file_exists('/var/log/zabbix-agent')) {
			$cmd .= system::getCmdSudo() . ' mkdir /var/log/zabbix-agent;';
		}
		$cmd .= system::getCmdSudo() . ' chmod 777 -R /var/log/zabbix-agent;';
		if (!file_exists('/etc/zabbix/zabbix_agentd.conf.d')) {
			$cmd .= system::getCmdSudo() . ' mkdir /etc/zabbix/zabbix_agentd.conf.d;';
			$cmd .= system::getCmdSudo() . ' chmod 777 -R /etc/zabbix/zabbix_agentd.conf.d;';
		}
		$cmd .= system::getCmdSudo() . ' systemctl restart zabbix-agent;';
		$cmd .= system::getCmdSudo() . ' systemctl enable zabbix-agent;';
		shell_exec($cmd);
	}
	
	public static function monitoring_status() {
		return (count(system::ps('zabbix')) > 0);
	}
	
	public static function monitoring_stop() {
		$cmd = system::getCmdSudo() . ' systemctl stop zabbix-agent;';
		$cmd .= system::getCmdSudo() . ' systemctl disable zabbix-agent;';
		shell_exec($cmd);
	}
	
	public static function monitoring_allow() {
		if (config::byKey('market::monitoringServer') == '') {
			return false;
		}
		if (config::byKey('market::monitoringName') == '') {
			return false;
		}
		return true;
	}
	
	/*     * ***********************CRON*************************** */
	
	public static function cronHourly() {
		if (strtotime(config::byKey('market::lastCommunication', 'core', 0)) > (strtotime('now') - (24 * 3600))) {
			return;
		}
		sleep(rand(0, 1800));
		try {
			self::test();
		} catch (Exception $e) {
			
		}
	}
	
	public static function cron5() {
		try {
			$monitoring_state = self::monitoring_status();
			if (self::monitoring_allow() && !$monitoring_state) {
				self::monitoring_start();
			}
			if (!self::monitoring_allow() && $monitoring_state) {
				self::monitoring_stop();
			}
		} catch (Exception $e) {
			
		}
	}
	
	/*******************************health********************************/
	
	public static function health() {
		$return = array();
		if (config::byKey('market::monitoringServer') != '') {
			$monitoring_state = self::monitoring_status();
			$return[] = array(
				'name' => __('Cloud monitoring actif', __FILE__),
				'state' => $monitoring_state,
				'result' => ($monitoring_state) ? __('OK', __FILE__) : __('NOK', __FILE__),
				'comment' => ($monitoring_state) ? '' : __('Attendez 10 minutes si le service ne redémarre pas contacter le support', __FILE__),
			);
		}
		return $return;
	}
	
	/*     * ***********************INFO*************************** */
	
	public static function getInfo($_logicalId, $_version = 'stable') {
		$returns = array();
		if (is_array($_logicalId) && is_array($_version) && count($_logicalId) == count($_version)) {
			if (is_array(reset($_logicalId))) {
				$markets = self::byLogicalIdAndType($_logicalId);
			} else {
				$markets = self::byLogicalId($_logicalId);
			}
			
			$returns = array();
			$countLogicalId = count($_logicalId);
			for ($i = 0; $i < $countLogicalId; $i++) {
				if (is_array($_logicalId[$i])) {
					$logicalId = $_logicalId[$i]['type'] . $_logicalId[$i]['logicalId'];
				} else {
					$logicalId = $_logicalId[$i];
				}
				$return['owner'] = array();
				$return['datetime'] = '0000-01-01 00:00:00';
				if ($logicalId == '' || config::byKey('market::address') == '') {
					$return['owner']['market'] = 0;
					$return['status'] = 'ok';
					return $return;
				}
				
				if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
					$return['owner']['market'] = 1;
				} else {
					$return['owner']['market'] = 0;
				}
				$return['market'] = 0;
				
				try {
					if (isset($markets[$logicalId])) {
						$market = $markets[$logicalId];
						if (!is_object($market)) {
							$return['status'] = 'ok';
						} else {
							$return['datetime'] = $market->getDatetime($_version[$i]);
							$return['market'] = 1;
							$return['owner']['market'] = $market->getIsAuthor();
							$update = update::byTypeAndLogicalId($market->getType(), $market->getLogicalId());
							$updateDateTime = '0000-01-01 00:00:00';
							if (is_object($update)) {
								$updateDateTime = $update->getLocalVersion();
							}
							if ($updateDateTime < $market->getDatetime($_version[$i], $updateDateTime)) {
								$return['status'] = 'update';
							} else {
								$return['status'] = 'ok';
							}
						}
					} else {
						$return['status'] = 'ok';
					}
				} catch (Exception $e) {
					log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
					$return['status'] = 'ok';
				} catch (Error $e) {
					log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
					$return['status'] = 'ok';
				}
				$returns[$logicalId] = $return;
			}
			return $returns;
		}
		$return = array();
		$return['datetime'] = '0000-01-01 00:00:00';
		$return['owner'] = array();
		if (config::byKey('market::address') == '') {
			$return['market'] = 0;
			$return['owner']['market'] = 0;
			$return['status'] = 'ok';
			return $return;
		}
		
		if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
			$return['owner']['market'] = 1;
		} else {
			$return['owner']['market'] = 0;
		}
		$return['market'] = 0;
		
		try {
			if (is_array($_logicalId)) {
				$market = repo_market::byLogicalIdAndType($_logicalId['logicalId'], $_logicalId['type']);
			} else {
				$market = repo_market::byLogicalId($_logicalId);
			}
			if (!is_object($market)) {
				$return['status'] = 'depreciated';
			} else {
				$return['datetime'] = $market->getDatetime($_version);
				$return['market'] = 1;
				$return['owner']['market'] = $market->getIsAuthor();
				$update = update::byTypeAndLogicalId($market->getType(), $market->getLogicalId());
				$updateDateTime = '0000-01-01 00:00:00';
				if (is_object($update)) {
					$updateDateTime = $update->getLocalVersion();
				}
				if ($updateDateTime < $market->getDatetime($_version, $updateDateTime)) {
					$return['status'] = 'update';
				} else {
					$return['status'] = 'ok';
				}
			}
		} catch (Exception $e) {
			log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
			$return['status'] = 'ok';
		} catch (Error $e) {
			log::add('market', 'debug', __('Erreur repo_market::getinfo : ', __FILE__) . $e->getMessage());
			$return['status'] = 'ok';
		}
		return $return;
	}
	
	/*     * ***********************UTILS*************************** */
	
	public static function saveTicket($_ticket) {
		$jsonrpc = self::getJsonRpc();
		$_ticket['user_plugin'] = '';
		foreach (plugin::listPlugin() as $plugin) {
			$_ticket['user_plugin'] .= $plugin->getId();
			$update = $plugin->getUpdate();
			if (is_object($update)) {
				$_ticket['user_plugin'] .= '[' . $update->getConfiguration('version', 'stable') . ',' . $update->getSource() . ',' . $update->getLocalVersion() . ']';
			}
			$_ticket['user_plugin'] .= ',';
		}
		trim($_ticket['user_plugin'], ',');
		if (isset($_ticket['options']['page'])) {
			$_ticket['options']['page'] = substr($_ticket['options']['page'], strpos($_ticket['options']['page'], 'index.php'));
		}
		$_ticket['options']['jeedom_version'] = jeedom::version();
		$_ticket['options']['uname'] = shell_exec('uname -a');
		if (!$jsonrpc->sendRequest('ticket::save', array('ticket' => $_ticket), 300)) {
			throw new Exception($jsonrpc->getErrorMessage());
		}
		if ($_ticket['openSupport'] == 1) {
			user::supportAccess(true);
		}
		return $jsonrpc->getResult();
	}
	
	public static function supportAccess($_enable = true, $_key = '') {
		$jsonrpc = self::getJsonRpc();
		$url = network::getNetworkAccess('external') . '/index.php?auth=' . $_key;
		if (!$jsonrpc->sendRequest('register::supportAccess', array('enable' => $_enable, 'urlSupport' => $url))) {
			throw new Exception($jsonrpc->getErrorMessage());
		}
	}
	
	public static function getPassword() {
		$password = config::byKey('market::password');
		if (!is_sha1($password)) {
			return sha1($password);
		}
		return $password;
	}
	
	public static function test() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::test')) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function getPurchaseInfo() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('purchase::getInfo')) {
			return $market->getResult();
		}
	}
	
	public static function distinctCategorie($_type) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::distinctCategorie', array('type' => $_type))) {
			return $market->getResult();
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function getJsonRpc() {
		$internalIp = '';
		try {
			$internalIp = network::getNetworkAccess('internal', 'ip');
		} catch (Exception $e) {
			
		}
		$uname = shell_exec('uname -a');
		if (config::byKey('market::username') != '' && config::byKey('market::password') != '') {
			$params = array(
				'username' => config::byKey('market::username'),
				'password' => self::getPassword(),
				'password_type' => 'sha1',
				'jeedomversion' => jeedom::version(),
				'hwkey' => jeedom::getHardwareKey(),
				'information' => array(
					'nbMessage' => message::nbMessage(),
					'nbUpdate' => update::nbNeedUpdate(),
					'hardware' => (method_exists('jeedom', 'getHardwareName')) ? jeedom::getHardwareName() : '',
					'uname' => $uname,
				),
				'market_api_key' => jeedom::getApiKey('apimarket'),
				'localIp' => $internalIp,
				'jeedom_name' => config::byKey('name'),
				'plugin_install_list' => plugin::listPlugin(true, false, false, true),
			);
			if (config::byKey('market::allowDNS') != 1 || config::byKey('network::disableMangement') == 1) {
				$params['url'] = network::getNetworkAccess('external');
			}
			$jsonrpc = new jsonrpcClient(config::byKey('market::address') . '/core/api/api.php', '', $params);
		} else {
			$jsonrpc = new jsonrpcClient(config::byKey('market::address') . '/core/api/api.php', '', array(
				'jeedomversion' => jeedom::version(),
				'hwkey' => jeedom::getHardwareKey(),
				'localIp' => $internalIp,
				'jeedom_name' => config::byKey('name'),
				'plugin_install_list' => plugin::listPlugin(true, false, false, true),
				'information' => array(
					'nbMessage' => message::nbMessage(),
					'nbUpdate' => update::nbNeedUpdate(),
					'hardware' => (method_exists('jeedom', 'getHardwareName')) ? jeedom::getHardwareName() : '',
					'uname' => $uname,
				),
			));
		}
		$jsonrpc->setCb_class('repo_market');
		$jsonrpc->setCb_function('postJsonRpc');
		$jsonrpc->setNoSslCheck(true);
		return $jsonrpc;
	}
	
	public static function postJsonRpc(&$_result) {
		config::save('market::lastCommunication', date('Y-m-d H:i:s'));
		if (is_array($_result)) {
			$restart_dns = false;
			$restart_monitoring = false;
			if (isset($_result['register::dnsToken']) && config::byKey('dns::token') != $_result['register::dnsToken']) {
				config::save('dns::token', $_result['register::dnsToken']);
				$restart_dns = true;
			}
			if (isset($_result['register::dnsNumber']) && config::byKey('dns::number') != $_result['register::dnsNumber']) {
				config::save('dns::number', $_result['register::dnsNumber']);
				$restart_dns = true;
			}
			if (isset($_result['register::vpnPort']) && config::byKey('vpn::port') != $_result['register::vpnPort']) {
				config::save('vpn::port', $_result['register::vpnPort']);
				$restart_dns = true;
			}
			if (isset($_result['user::backupServer']) && config::byKey('market::backupServer') != $_result['user::backupServer']) {
				config::save('market::backupServer', $_result['user::backupServer']);
				$restart_monitoring = true;
			}
			if (isset($_result['user::backupPassword']) && config::byKey('market::backupPassword') != $_result['user::backupPassword']) {
				config::save('market::backupPassword', $_result['user::backupPassword']);
				$restart_monitoring = true;
			}
			if (isset($_result['user::monitoringServer']) && config::byKey('market::monitoringServer') != $_result['user::monitoringServer']) {
				config::save('market::monitoringServer', $_result['user::monitoringServer']);
				$restart_monitoring = true;
			}
			if (isset($_result['register::monitoringPsk']) && config::byKey('market::monitoringPsk') != $_result['register::monitoringPsk']) {
				config::save('market::monitoringPsk', $_result['register::monitoringPsk']);
				$restart_monitoring = true;
			}
			if (isset($_result['register::monitoringPskIdentity']) && config::byKey('market::monitoringPskIdentity') != $_result['register::monitoringPskIdentity']) {
				config::save('market::monitoringPskIdentity', $_result['register::monitoringPskIdentity']);
				$restart_monitoring = true;
			}
			if (isset($_result['register::monitoringName']) && config::byKey('market::monitoringName') != $_result['register::monitoringName']) {
				config::save('market::monitoringName', $_result['register::monitoringName']);
				$restart_monitoring = true;
			}
			if ($restart_monitoring) {
				self::monitoring_stop();
			}
			if ($restart_dns && config::byKey('market::allowDNS') == 1) {
				network::dns_start();
			}
			if (config::byKey('market::allowDNS') == 1 && isset($_result['jeedom::url']) && config::byKey('jeedom::url') != $_result['jeedom::url']) {
				config::save('jeedom::url', $_result['jeedom::url']);
			}
			if (isset($_result['register::hwkey_nok']) && $_result['register::hwkey_nok'] == 1) {
				config::save('jeedom::installKey', '');
			}
		}
	}
	/**
	*
	* @param array $_arrayMarket
	* @return \self
	*/
	public static function construct(array $_arrayMarket) {
		$market = new self();
		if (!isset($_arrayMarket['id'])) {
			return;
		}
		$market->setId($_arrayMarket['id'])
		->setName($_arrayMarket['name'])
		->setType($_arrayMarket['type']);
		$market->datetime = json_encode($_arrayMarket['datetime'], JSON_UNESCAPED_UNICODE);
		$market->setDescription($_arrayMarket['description'])
		->setDownloaded($_arrayMarket['downloaded'])
		->setUser_id($_arrayMarket['user_id'])
		->setVersion($_arrayMarket['version'])
		->setCategorie($_arrayMarket['categorie']);
		$market->status = json_encode($_arrayMarket['status'], JSON_UNESCAPED_UNICODE);
		$market->setAuthor($_arrayMarket['author']);
		if (isset($_arrayMarket['changelog'])) {
			$market->setChangelog($_arrayMarket['changelog']);
		}
		if (isset($_arrayMarket['doc'])) {
			$market->setDoc($_arrayMarket['doc']);
		}
		$market->setLogicalId($_arrayMarket['logicalId']);
		if (isset($_arrayMarket['utilization'])) {
			$market->setUtilization($_arrayMarket['utilization']);
		}
		if (isset($_arrayMarket['certification'])) {
			$market->setCertification($_arrayMarket['certification']);
		}
		if (isset($_arrayMarket['allowVersion'])) {
			$market->setAllowVersion($_arrayMarket['allowVersion']);
		}
		if (isset($_arrayMarket['nbInstall'])) {
			$market->setNbInstall($_arrayMarket['nbInstall']);
		}
		$market->setPurchase($_arrayMarket['purchase'])
		->setCost($_arrayMarket['cost']);
		$market->rating = ($_arrayMarket['rating']);
		$market->setBuyer($_arrayMarket['buyer'])
		->setUpdateBy($_arrayMarket['updateBy'])
		->setPrivate($_arrayMarket['private']);
		$market->img = json_encode($_arrayMarket['img'], JSON_UNESCAPED_UNICODE);
		$market->link = json_encode($_arrayMarket['link'], JSON_UNESCAPED_UNICODE);
		$market->language = json_encode($_arrayMarket['language'], JSON_UNESCAPED_UNICODE);
		if (isset($_arrayMarket['hardwareCompatibility'])) {
			$market->hardwareCompatibility = json_encode($_arrayMarket['hardwareCompatibility'], JSON_UNESCAPED_UNICODE);
		}
		
		$market->setRealcost($_arrayMarket['realCost']);
		if (!isset($_arrayMarket['isAuthor'])) {
			$_arrayMarket['isAuthor'] = true;
		}
		$market->setIsAuthor($_arrayMarket['isAuthor']);
		
		if (isset($_arrayMarket['parameters']) && is_array($_arrayMarket['parameters'])) {
			foreach ($_arrayMarket['parameters'] as $key => $value) {
				$market->setParameters($key, $value);
			}
		}
		return $market;
	}
	
	public static function byId($_id) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byId', array('id' => $_id))) {
			return self::construct($market->getResult());
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function byLogicalId($_logicalId) {
		$market = self::getJsonRpc();
		
		if (is_array($_logicalId)) {
			$options = $_logicalId;
			$timeout = 240;
		} else {
			$options = array('logicalId' => $_logicalId);
			$timeout = 10;
		}
		if ($market->sendRequest('market::byLogicalId', $options, $timeout, null, 1)) {
			if (is_array($_logicalId)) {
				$return = array();
				foreach ($market->getResult() as $logicalId => $result) {
					if (isset($result['id'])) {
						$return[$logicalId] = self::construct($result);
					}
				}
				return $return;
			}
			return self::construct($market->getResult());
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function byLogicalIdAndType($_logicalId, $_type = '') {
		$market = self::getJsonRpc();
		if (is_array($_logicalId)) {
			$options = $_logicalId;
			$timeout = 240;
		} else {
			$options = array('logicalId' => $_logicalId, 'type' => $_type);
			$timeout = 10;
		}
		if ($market->sendRequest('market::byLogicalIdAndType', $options, $timeout, null, 1)) {
			if (is_array($_logicalId)) {
				$return = array();
				foreach ($market->getResult() as $logicalId => $result) {
					if (isset($result['id'])) {
						$return[$logicalId] = self::construct($result);
					}
				}
				return $return;
			}
			return self::construct($market->getResult());
		} else {
			log::add('market', 'debug', print_r($market, true));
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function byMe() {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byAuthor', array())) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function byStatusAndType($_status, $_type) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byStatusAndType', array('status' => $_status, 'type' => $_type))) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			log::add('market', 'debug', print_r($market, true));
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function byStatus($_status) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byStatus', array('status' => $_status))) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	public static function byFilter($_filter) {
		$market = self::getJsonRpc();
		if ($market->sendRequest('market::byFilter', $_filter)) {
			$return = array();
			foreach ($market->getResult() as $result) {
				if (isset($result['id'])) {
					$return[] = self::construct($result);
				}
			}
			return $return;
		} else {
			throw new Exception($market->getError(), $market->getErrorCode());
		}
	}
	
	/*     * *********************Methode d'instance************************* */
	
	public function setRating($_rating) {
		$market = self::getJsonRpc();
		if (!$market->sendRequest('market::setRating', array('rating' => $_rating, 'id' => $this->getId()))) {
			throw new Exception($market->getError());
		}
	}
	
	public function getRating($_key = 'average') {
		$rating = $this->rating;
		if (isset($rating[$_key])) {
			return $rating[$_key];
		}
		return 0;
	}
	
	public function install($_version = 'stable') {
		$tmp_dir = jeedom::getTmpFolder('market');
		$tmp = $tmp_dir . '/' . $this->getLogicalId() . '.zip';
		if (file_exists($tmp)) {
			unlink($tmp);
		}
		if (!is_writable($tmp_dir)) {
			exec(system::getCmdSudo() . 'chmod 777 -R ' . $tmp);
		}
		if (!is_writable($tmp_dir)) {
			throw new Exception(__('Impossible d\'écrire dans le répertoire : ', __FILE__) . $tmp . __('. Exécuter la commande suivante en SSH : sudo chmod 777 -R ', __FILE__) . $tmp_dir);
		}
		
		$url = config::byKey('market::address') . "/core/php/downloadFile.php?id=" . $this->getId() . '&version=' . $_version . '&jeedomversion=' . jeedom::version() . '&hwkey=' . jeedom::getHardwareKey() . '&username=' . urlencode(config::byKey('market::username')) . '&password=' . self::getPassword() . '&password_type=sha1';
		log::add('update', 'alert', __('Téléchargement de ', __FILE__) . $this->getLogicalId() . '...');
		log::add('update', 'alert', __('URL ', __FILE__) . $url);
		exec('wget --no-check-certificate "' . $url . '" -O ' . $tmp . ' >> ' . log::getPathToLog('update').' 2>&1');
		switch ($this->getType()) {
			case 'plugin':
			return $tmp;
			break;
			default:
			log::add('update', 'alert', __('Installation des plugin, widget, scénario...', __FILE__));
			$type = $this->getType();
			if (class_exists($type) && method_exists($type, 'getFromMarket')) {
				$type::getFromMarket($this, $tmp);
			}
			log::add('update', 'alert', __("OK\n", __FILE__));
			break;
		}
		return false;
	}
	
	public function remove() {
		$cache = cache::byKey('market::info::' . $this->getLogicalId());
		if (is_object($cache)) {
			$cache->remove();
		}
		switch ($this->getType()) {
			case 'plugin':
			
			break;
			default:
			$type = $this->getType();
			if (class_exists($type) && method_exists($type, 'removeFromMarket')) {
				$type::removeFromMarket($this);
			}
			break;
		}
	}
	
	public function save() {
		$cache = cache::byKey('market::info::' . $this->getLogicalId());
		if (is_object($cache)) {
			$cache->remove();
		}
		$market = self::getJsonRpc();
		$params = utils::o2a($this);
		if (isset($params['changelog'])) {
			unset($params['changelog']);
		}
		switch ($this->getType()) {
			case 'plugin':
			$plugin_id = $this->getLogicalId();
			$cibDir = jeedom::getTmpFolder('market') . '/' . $plugin_id;
			if (file_exists($cibDir)) {
				rrmdir($cibDir);
			}
			mkdir($cibDir);
			$exclude = array('tmp', '.git', '.DStore');
			if (property_exists($plugin_id, '_excludeOnSendPlugin')) {
				$exclude = array_merge($plugin_id::$_excludeOnSendPlugin);
			}
			exec('find ' . realpath(__DIR__ . '/../../plugins/' . $plugin_id) . ' -name "*.sh" -type f -exec dos2unix {} \;');
				rcopy(realpath(__DIR__ . '/../../plugins/' . $plugin_id), $cibDir, true, $exclude, true);
				if (file_exists($cibDir . '/data')) {
					rrmdir($cibDir . '/data');
				}
				$tmp = jeedom::getTmpFolder('market') . '/' . $plugin_id . '.zip';
				if (file_exists($tmp)) {
					if (!unlink($tmp)) {
						throw new Exception(__('Impossible de supprimer : ', __FILE__) . $tmp . __('. Vérifiez les droits', __FILE__));
					}
				}
				if (!create_zip($cibDir, $tmp)) {
					throw new Exception(__('Echec de création de l\'archive zip', __FILE__));
				}
				rrmdir($cibDir);
				break;
				default:
				$type = $this->getType();
				if (!class_exists($type) || !method_exists($type, 'shareOnMarket')) {
					throw new Exception(__('Aucune fonction correspondante à : ', __FILE__) . $type . '::shareOnMarket');
				}
				$tmp = $type::shareOnMarket($this);
				break;
			}
			if (!file_exists($tmp)) {
				throw new Exception(__('Impossible de trouver le fichier à envoyer : ', __FILE__) . $tmp);
			}
			$file = array(
				'file' => '@' . realpath($tmp),
			);
			if (!$market->sendRequest('market::save', $params, 30, $file)) {
				throw new Exception($market->getError());
			}
			unlink($tmp);
			$update = update::byTypeAndLogicalId($this->getType(), $this->getLogicalId());
			if (!is_object($update)) {
				$update = new update();
				$update->setLogicalId($this->getLogicalId());
				$update->setType($this->getType());
			}
			if ($update->getSource() == 'market') {
				$update->setConfiguration('version', 'beta');
				$update->setLocalVersion(date('Y-m-d H:i:s', strtotime('+10 minute' . date('Y-m-d H:i:s'))));
				$update->save();
			}
			$update->checkUpdate();
		}
		
		/*     * **********************Getteur Setteur*************************** */
		
		public function getId() {
			return $this->id;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function getType() {
			return $this->type;
		}
		
		public function getDatetime($_key = '', $_default = '') {
			return utils::getJsonAttr($this->datetime, $_key, $_default);
		}
		
		public function setDatetime($_key, $_value) {
			$this->datetime = utils::setJsonAttr($this->datetime, $_key, $_value);
			return $this;
		}
		
		public function getDescription() {
			return $this->description;
		}
		
		public function getCategorie() {
			return $this->categorie;
		}
		
		public function getVersion() {
			return $this->version;
		}
		
		public function getUser_id() {
			return $this->user_id;
		}
		
		public function getDownloaded() {
			return $this->downloaded;
		}
		
		public function setId($id) {
			$this->id = $id;
			return $this;
		}
		
		public function setName($name) {
			$this->name = $name;
			return $this;
		}
		
		public function setType($type) {
			$this->type = $type;
			return $this;
		}
		
		public function setDescription($description) {
			$this->description = $description;
			return $this;
		}
		
		public function setCategorie($categorie) {
			$this->categorie = $categorie;
			return $this;
		}
		
		public function setVersion($version) {
			$this->version = $version;
			return $this;
		}
		
		public function setUser_id($user_id) {
			$this->user_id = $user_id;
			return $this;
		}
		
		public function setDownloaded($downloaded) {
			$this->downloaded = $downloaded;
			return $this;
		}
		
		public function getStatus($_key = '', $_default = '') {
			return utils::getJsonAttr($this->status, $_key, $_default);
		}
		
		public function setStatus($_key, $_value) {
			$this->status = utils::setJsonAttr($this->status, $_key, $_value);
			return $this;
		}
		
		public function getLink($_key = '', $_default = '') {
			return utils::getJsonAttr($this->link, $_key, $_default);
		}
		
		public function setLink($_key, $_value) {
			$this->link = utils::setJsonAttr($this->link, $_key, $_value);
			return $this;
		}
		
		public function getLanguage($_key = '', $_default = '') {
			return utils::getJsonAttr($this->language, $_key, $_default);
		}
		
		public function setLanguage($_key, $_value) {
			$this->language = utils::setJsonAttr($this->language, $_key, $_value);
			return $this;
		}
		
		public function getImg($_key = '', $_default = '') {
			return utils::getJsonAttr($this->img, $_key, $_default);
		}
		
		public function getAuthor() {
			return $this->author;
		}
		
		public function setAuthor($author) {
			$this->author = $author;
			return $this;
		}
		
		public function getChangelog() {
			return $this->changelog;
		}
		
		public function setChangelog($changelog) {
			$this->changelog = $changelog;
			return $this;
		}
		
		public function getNbInstall() {
			return $this->nbInstall;
		}
		
		public function setNbInstall($nbInstall) {
			$this->nbInstall = $nbInstall;
			return $this;
		}
		
		public function getLogicalId() {
			return $this->logicalId;
		}
		
		public function setLogicalId($logicalId) {
			$this->logicalId = $logicalId;
			return $this;
		}
		
		public function getPrivate() {
			return $this->private;
		}
		
		public function setPrivate($private) {
			$this->private = $private;
			return $this;
		}
		
		public function getIsAuthor() {
			return $this->isAuthor;
		}
		
		public function setIsAuthor($isAuthor) {
			$this->isAuthor = $isAuthor;
			return $this;
		}
		
		public function getUtilization() {
			return $this->utilization;
		}
		
		public function setUtilization($utilization) {
			$this->utilization = $utilization;
			return $this;
		}
		
		public function getPurchase() {
			return $this->purchase;
		}
		
		public function setPurchase($purchase) {
			$this->purchase = $purchase;
			return $this;
		}
		
		public function getCost() {
			return $this->cost;
		}
		
		public function setCost($cost) {
			$this->cost = $cost;
			return $this;
		}
		
		public function getRealcost() {
			return $this->realcost;
		}
		
		public function setRealcost($realcost) {
			$this->realcost = $realcost;
			return $this;
		}
		
		public function getBuyer() {
			return $this->buyer;
		}
		
		public function setBuyer($buyer) {
			$this->buyer = $buyer;
			return $this;
		}
		
		public function getCertification() {
			return $this->certification;
		}
		
		public function setCertification($certification) {
			$this->certification = $certification;
			return $this;
		}
		
		public function getDoc() {
			return $this->doc;
		}
		
		public function setDoc($doc) {
			$this->doc = $doc;
			return $this;
		}
		
		public function getUpdateBy() {
			return $this->updateBy;
		}
		
		public function setUpdateBy($updateBy) {
			$this->updateBy = $updateBy;
			return $this;
		}
		
		public function getAllowVersion() {
			return $this->allowVersion;
		}
		
		public function setAllowVersion($allowVersion) {
			$this->allowVersion = $allowVersion;
			return $allowVersion;
		}
		
		public function getHardwareCompatibility($_key = '', $_default = '') {
			return utils::getJsonAttr($this->hardwareCompatibility, $_key, $_default);
		}
		
		public function setHardwareCompatibility($_key, $_value) {
			$this->hardwareCompatibility = utils::setJsonAttr($this->hardwareCompatibility, $_key, $_value);
			return $this;
		}
		
		public function getParameters($_key = '', $_default = '') {
			return utils::getJsonAttr($this->parameters, $_key, $_default);
		}
		
		public function setParameters($_key, $_value) {
			$this->parameters = utils::setJsonAttr($this->parameters, $_key, $_value);
			return $this;
		}
		
	}
	
