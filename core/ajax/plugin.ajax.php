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

try {
	require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'getConf') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin = plugin::byId(init('id'));
		$return = utils::o2a($plugin);
		$return['activate'] = $plugin->isActive();
		$return['configurationPath'] = $plugin->getPathToConfigurationById();
		$return['checkVersion'] = version_compare(jeedom::version(), $plugin->getRequire());
		$return['status'] = market::getInfo(array('logicalId' => $plugin->getId(), 'type' => 'plugin'));
		$return['update'] = utils::o2a(update::byLogicalId($plugin->getId()));
		ajax::success($return);
	}

	if (init('action') == 'toggle') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin = plugin::byId(init('id'));
		if (!is_object($plugin)) {
			throw new Exception(__('Plugin introuvable : ', __FILE__) . init('id'));
		}
		$plugin->setIsEnable(init('state'));
		ajax::success();
	}

	if (init('action') == 'all') {
		if (!isConnect()) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		ajax::success(utils::o2a(plugin::listPlugin()));
	}

	if (init('action') == 'pluginupload') {
		$uploaddir = dirname(__FILE__) . '/../../tmp';
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir);
		}
		if (!file_exists($uploaddir)) {
			throw new Exception(__('Répertoire d\'upload non trouvé : ', __FILE__) . $uploaddir);
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifié parametre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.zip'))) {
			throw new Exception('Extension du fichier non valide (autorisé .zip) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 100000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 100mo)', __FILE__));
		}
		$filename = str_replace(array(' ', '(', ')'), '', $_FILES['file']['name']);
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . '/' . $filename)) {
			throw new Exception(__('Impossible de déplacer le fichier temporaire', __FILE__));
		}
		if (!file_exists($uploaddir . '/' . $filename)) {
			throw new Exception(__('Impossible d\'uploader le fichier (limite du serveur web ?)', __FILE__));
		}
		$logicalId = str_replace(array('.zip'), '', $filename);
		$tmp = $uploaddir . '/' . $logicalId . '.zip';
		$tmp_dir = $uploaddir . '/' . $logicalId;
		if (file_exists($tmp_dir)) {
			rrmdir($tmp_dir);
		}
		mkdir($tmp_dir);

		log::add('update', 'update', __('Décompression de l\'archive...', __FILE__));
		$zip = new ZipArchive;
		$res = $zip->open($tmp);
		if ($res === TRUE) {
			if (!$zip->extractTo($tmp_dir . '/')) {
				$content = file_get_contents($tmp);
				throw new Exception(__('Impossible d\'installer le plugin. Les fichiers n\'ont pas pu être décompressés : ', __FILE__) . substr($content, 255));
			}
			$zip->close();
			log::add('update', 'update', __("OK\n", __FILE__));
			log::add('update', 'update', __('Installation de l\'objet...', __FILE__));
			if (!file_exists($tmp_dir . '/plugin_info')) {
				log::add('update', 'update', __('Plugin info non trouvé, je le recherche...', __FILE__));
				$files = ls($tmp_dir, '*');
				if (count($files) == 1 && file_exists($tmp_dir . '/' . $files[0] . 'plugin_info')) {
					shell_exec('mv ' . $tmp_dir . '/' . $files[0] . '* ' . $tmp_dir);
					rmdir($tmp_dir . '/' . $files[0]);
					log::add('update', 'update', __('OK', __FILE__));
				}
			}
			if (file_exists($tmp_dir . '/plugin_info/info.xml')) {
				log::add('update', 'update', __('Fichier info.xml trouvé', __FILE__));
				libxml_use_internal_errors(true);
				$plugin_xml = @simplexml_load_file($tmp_dir . '/plugin_info/info.xml');
				$logicalId = $plugin_xml->id;
			}
			$cibDir = dirname(__FILE__) . '/../../plugins/' . $logicalId;
			if (!file_exists($cibDir) && !mkdir($cibDir, 0775, true)) {
				throw new Exception(__('Impossible de créer le dossier  : ' . $cibDir . '. Problème de droits ?', __FILE__));
			}
			rcopy($tmp_dir, $cibDir);
			rrmdir($tmp_dir);
			try {
				$plugin = plugin::byId($logicalId);
			} catch (Exception $e) {
				throw new Exception(__('Impossible d\'installer le plugin. Le nom du plugin est différent de l\'ID ou le plugin n\'est pas correctement formé. Veuillez contacter l\'auteur.', __FILE__));
			}
			log::add('update', 'update', __("OK\n", __FILE__));
			if (is_object($plugin) && $plugin->isActive()) {
				$plugin->setIsEnable(1);
			}
		} else {
			switch ($res) {
				case ZipArchive::ER_EXISTS:
					$ErrMsg = "Le fichier existe déjà.";
					break;
				case ZipArchive::ER_INCONS:
					$ErrMsg = "L'archive zip est inconsistente.";
					break;
				case ZipArchive::ER_MEMORY:
					$ErrMsg = "Erreur mémoire.";
					break;
				case ZipArchive::ER_NOENT:
					$ErrMsg = "Ce fichier n\'existe pas.";
					break;
				case ZipArchive::ER_NOZIP:
					$ErrMsg = "Ceci n\'est pas une archive zip.";
					break;
				case ZipArchive::ER_OPEN:
					$ErrMsg = "Le fichier ne peut pas être ouvert.";
					break;
				case ZipArchive::ER_READ:
					$ErrMsg = "Erreur de lecture.";
					break;
				case ZipArchive::ER_SEEK:
					$ErrMsg = "Erreur de recherche.";
					break;
				default:
					$ErrMsg = "Erreur inconnue (Code $res)";
					break;
			}
			throw new Exception(__('Impossible de décompresser l\'archive zip : ', __FILE__) . $tmp . __('. Erreur : ', __FILE__) . $ErrMsg . '.');
		}
		ajax::success();
	}

	if (init('action') == 'getDependancyInfo') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			ajax::success($plugin->dependancy_info());
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			ajax::success($jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => init('id'))));
		}
	}

	if (init('action') == 'dependancyInstall') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			ajax::success($plugin->dependancy_install());
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			ajax::success($jeeNetwork->sendRawRequest('plugin::dependancyInstall', array('plugin_id' => init('id'))));
		}
	}

	if (init('action') == 'getDeamonInfo') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin_id = init('id');
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			ajax::success($plugin->deamon_info());
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			ajax::success($jeeNetwork->sendRawRequest('plugin::deamonInfo', array('plugin_id' => $plugin_id)));
		}
	}

	if (init('action') == 'deamonStart') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin_id = init('id');
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			ajax::success($plugin->deamon_start(init('debug', 0), init('forceRestart', 0)));
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			ajax::success($jeeNetwork->sendRawRequest('plugin::deamonStart', array('plugin_id' => $plugin_id, 'debug' => init('debug', 0), 'forceRestart' => init('forceRestart', 0))));
		}
	}

	if (init('action') == 'deamonStop') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$plugin_id = init('id');
		if (init('slave_id', 0) == 0) {
			$plugin = plugin::byId(init('id'));
			ajax::success($plugin->deamon_stop());
		} else {
			$jeeNetwork = jeeNetwork::byId(init('slave_id'));
			ajax::success($jeeNetwork->sendRawRequest('plugin::deamonStop', array('plugin_id' => $plugin_id)));
		}
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
