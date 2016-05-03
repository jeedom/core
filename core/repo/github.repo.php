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

class repo_github {
	/*     * *************************Attributs****************************** */

	public static $_name = 'Github';

	public static $_scope = array(
		'plugin' => true,
		'backup' => false,
		'hasConfiguration' => true,
		'core' => true,
	);

	public static $_configuration = array(
		'parameters_for_add' => array(
			'user' => array(
				'name' => 'Utilisateur ou organisation du dépot',
				'type' => 'input',
			),
			'repository' => array(
				'name' => 'Nom du dépôt',
				'type' => 'input',
			),
			'version' => array(
				'name' => 'Branche',
				'type' => 'input',
				'default' => 'master',
			),
		),
		'configuration' => array(
			'token' => array(
				'name' => 'Token (faculatatif)',
				'type' => 'input',
			),
			'core::user' => array(
				'name' => 'Utilisateur ou organisation du dépot pour le core Jeedom',
				'type' => 'input',
				'default' => 'jeedom',
			),
			'core::repository' => array(
				'name' => 'Nom du dépôt pour le core Jeedom',
				'type' => 'input',
				'default' => 'core',
			),
			'core::branch' => array(
				'name' => 'Branche pour le core Jeedom',
				'type' => 'input',
				'default' => 'stable',
			),
		),
	);

	/*     * ***********************Méthodes statiques*************************** */

	public static function getGithubClient() {
		$client = new \Github\Client(
			new \Github\HttpClient\CachedHttpClient(array('cache_dir' => '/tmp/jeedom-github-api-cache'))
		);
		if (config::byKey('github::token') != '') {
			$client->authenticate(config::byKey('github::token'), '', Github\Client::AUTH_URL_TOKEN);
		}
		return $client;
	}

	public static function checkUpdate($_update) {
		$client = self::getGithubClient();
		try {
			$branch = $client->api('repo')->branches($_update->getConfiguration('user'), $_update->getConfiguration('repository'), $_update->getConfiguration('version', 'master'));
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

	public static function doUpdate($_update) {
		$client = self::getGithubClient();
		try {
			$branch = $client->api('repo')->branches($_update->getConfiguration('user'), $_update->getConfiguration('repository'), $_update->getConfiguration('version', 'master'));
		} catch (Exception $e) {
			throw new Exception(__('Dépot github non trouvé : ', __FILE__) . $_update->getConfiguration('user') . '/' . $_update->getConfiguration('repository') . '/' . $_update->getConfiguration('version', 'master'));
		}
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

		$url = 'https://api.github.com/repos/' . $_update->getConfiguration('user') . '/' . $_update->getConfiguration('repository') . '/zipball/' . $_update->getConfiguration('version', 'master');
		log::add('update', 'alert', __('Téléchargement de ', __FILE__) . $_update->getLogicalId() . '...');
		if (config::byKey('github::token') == '') {
			$result = shell_exec('curl -s -L ' . $url . ' > ' . $tmp);
		} else {
			$result = shell_exec('curl -s -H "Authorization: token ' . config::byKey('github::token') . '" -L ' . $url . ' > ' . $tmp);
		}
		log::add('update', 'alert', $result);
		if (!file_exists($tmp)) {
			throw new Exception(__('Impossible de télécharger le fichier depuis : ' . $url . '.', __FILE__));
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
		if (!isset($branch['commit']) || !isset($branch['commit']['sha'])) {
			return array();
		}
		return array('localVersion' => $branch['commit']['sha']);
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
		$client = self::getGithubClient();
		try {
			$branch = $client->api('repo')->branches(config::byKey('github::core::user', 'core', 'jeedom'), config::byKey('github::core::repository', 'core', 'core'), config::byKey('github::core::branch', 'core', 'stable'));
		} catch (Exception $e) {
			throw new Exception(__('Dépot github non trouvé : ', __FILE__) . config::byKey('github::core::user', 'core', 'jeedom') . '/' . config::byKey('github::core::repository', 'core', 'core') . '/' . config::byKey('github::core::branch', 'core', 'stable'));
		}
		$url = 'https://api.github.com/repos/' . config::byKey('github::core::user', 'core', 'jeedom') . '/' . config::byKey('github::core::repository', 'core', 'core') . '/zipball/' . config::byKey('github::core::branch', 'core', 'stable');
		echo __('Téléchargement de ', __FILE__) . $url . '...';
		if (config::byKey('github::token') == '') {
			echo shell_exec('curl -s -L ' . $url . ' > ' . $_path);
		} else {
			echo shell_exec('curl -s -H "Authorization: token ' . config::byKey('github::token') . '" -L ' . $url . ' > ' . $_path);
		}
		return;
	}

	/*     * *********************Methode d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */

}