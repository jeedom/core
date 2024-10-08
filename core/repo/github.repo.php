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

class repo_github {
	/*     * *************************Attributs****************************** */
	
	public static $_name = 'Github';
	
	public static $_scope = array(
		'plugin' => true,
		'backup' => false,
		'hasConfiguration' => true,
		'core' => true,
	);
	
	
	/*     * ***********************Méthodes statiques*************************** */
	
	public static function getConfigurationOption(){
		return array(
			'parameters_for_add' => array(
				'user' => array(
					'name' =>  __('Utilisateur ou organisation du dépôt',__FILE__),
					'type' => 'input',
				),
				'repository' => array(
					'name' =>  __('Nom du dépôt',__FILE__),
					'type' => 'input',
				),
				'token' => array(
					'name' =>  __('Token (facultatif)',__FILE__),
					'type' => 'input'
				),
				'version' => array(
					'name' =>  __('Branche',__FILE__),
					'type' => 'input',
					'default' => 'master',
				),
			),
			'configuration' => array(
				'token' => array(
					'name' =>  __('Token (facultatif)',__FILE__),
					'type' => 'input',
				),
				'core::user' => array(
					'name' =>  __('Utilisateur ou organisation du dépôt pour le core Jeedom',__FILE__),
					'type' => 'input',
					'default' => 'jeedom',
				),
				'core::repository' => array(
					'name' =>  __('Nom du dépôt pour le core Jeedom',__FILE__),
					'type' => 'input',
					'default' => 'core',
				),
				'core::branch' => array(
					'name' =>  __('Branche pour le core Jeedom',__FILE__),
					'type' => 'input',
					'default' => 'stable',
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
		try {
			$branch = self::getBranchInfo($_update);
		} catch (Exception $e) {
			$_update->setRemoteVersion('repository not found');
			$_update->setStatus('ok');
			$_update->save();
			return;
		}
		if (!isset($branch['commit']) || !isset($branch['commit']['sha'])) {
			$_update->setRemoteVersion('error');
			$_update->setStatus('ok');
			$_update->save();
			return;
		}
		$_update->setRemoteVersion($branch['commit']['sha']);
		if ($branch['commit']['sha'] != $_update->getLocalVersion()) {
			$_update->setStatus('update');
		} else {
			$_update->setStatus('ok');
		}
		$_update->save();
	}

	public static function getBranchInfo($_update){
		$headers = array('User-agent: jeedom');
		$token = $_update->getConfiguration('token',config::byKey('github::token','core',''));
		if($token != ''){
			$headers[] = 'Authorization: Bearer '.$token;
		}
		$request_http = new com_http('https://api.github.com/repos/'.$_update->getConfiguration('user').'/'.$_update->getConfiguration('repository').'/branches/'.$_update->getConfiguration('version', 'master'));
		$request_http->setHeader($headers);
		return json_decode($request_http->exec(10, 1), true);
	}
	
	public static function downloadObject($_update) {
		$token = $_update->getConfiguration('token',config::byKey('github::token','core',''));
		$branch = self::getBranchInfo($_update);
		$tmp_dir = jeedom::getTmpFolder('github');
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
		$url = 'https://api.github.com/repos/' . $_update->getConfiguration('user') . '/' . $_update->getConfiguration('repository') . '/zipball/' . $_update->getConfiguration('version', 'master');
		log::add('update', 'alert', __('Téléchargement de', __FILE__) . ' ' . $_update->getLogicalId() . '...');
		if ($token == '') {
			$result = shell_exec('curl -s -L ' . $url . ' > ' . $tmp);
		} else {
			$result = shell_exec('curl -s -H "Authorization: token ' . $token . '" -L ' . $url . ' > ' . $tmp);
		}
		log::add('update', 'alert', $result);
		
		if (!isset($branch['commit']) || !isset($branch['commit']['sha'])) {
			return array('path' => $tmp);
		}
		return array('localVersion' => $branch['commit']['sha'], 'path' => $tmp);
	}
	
	public static function deleteObjet($_update) {
		
	}
	
	public static function objectInfo($_update) {
		return array(
			'doc' => 'https://github.com/' . $_update->getConfiguration('user') . '/' . $_update->getConfiguration('repository') . '/blob/' . $_update->getConfiguration('version', 'master') . '/doc/' . config::byKey('language', 'core', 'fr_FR') . '/index.asciidoc',
			'changelog' => 'https://github.com/' . $_update->getConfiguration('user') . '/' . $_update->getConfiguration('repository') . '/commits/' . $_update->getConfiguration('version', 'master'),
		);
	}
	
	public static function downloadCore($_path) {
		$url = 'https://api.github.com/repos/' . config::byKey('github::core::user', 'core', 'jeedom') . '/' . config::byKey('github::core::repository', 'core', 'core') . '/zipball/' . config::byKey('github::core::branch', 'core', 'stable');
		echo __('Téléchargement de', __FILE__) . ' ' . $url . '...';
		if (config::byKey('github::token') == '') {
			echo shell_exec('curl -s -L ' . $url . ' > ' . $_path);
		} else {
			echo shell_exec('curl -s -H "Authorization: token ' . config::byKey('github::token') . '" -L ' . $url . ' > ' . $_path);
		}
		return;
	}
	
	public static function versionCore() {
		$url = 'https://raw.githubusercontent.com/'.config::byKey('github::core::user', 'core', 'jeedom').'/'.config::byKey('github::core::repository', 'core', 'core').'/' . config::byKey('github::core::branch', 'core', 'stable') . '/core/config/version';
		$request_http = new com_http($url);
		return trim($request_http->exec(30));
	}
	
	/*     * *********************Methode d'instance************************* */
	
	/*     * **********************Getteur Setteur*************************** */
	
}
