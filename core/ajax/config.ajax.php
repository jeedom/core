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
	require_once __DIR__ . '/../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	ajax::init(array('uploadImage'));

	if (init('action') == 'genApiKey') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		if (init('plugin') == 'core') {
			config::save('api', config::genKey());
			ajax::success(config::byKey('api'));
		} else if (init('plugin') == 'apimarket') {
			config::save('apimarket', config::genKey());
			ajax::success(config::byKey('apimarket'));
		} else if (init('plugin') == 'apipro') {
			config::save('apipro', config::genKey());
			ajax::success(config::byKey('apipro'));
		} else if (init('plugin') == 'apitts') {
			config::save('apitts', config::genKey());
			ajax::success(config::byKey('apitts'));
		} else {
			config::save('api', config::genKey(), init('plugin'));
			ajax::success(config::byKey('api', init('plugin')));
		}
	}

	if (init('action') == 'getKey') {
		$keys = init('key');
		if ($keys == '') {
			throw new Exception(__('Aucune clef demandée', __FILE__));
		}
		if (is_json($keys)) {
			$keys = json_decode($keys, true);
			$return = config::byKeys(array_keys($keys), init('plugin', 'core'));
			if (init('convertToHumanReadable', 0)) {
				$return = jeedom::toHumanReadable($return);
			}
			ajax::success($return);
		} else {
			$return = config::byKey($keys, init('plugin', 'core'));
			if (init('convertToHumanReadable', 0)) {
				$return = jeedom::toHumanReadable($return);
			}
			ajax::success($return);
		}
	}

	if (init('action') == 'addKey') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$values = json_decode(init('value'), true);
		foreach ($values as $key => $value) {
			config::save($key, jeedom::fromHumanReadable($value), init('plugin', 'core'));
		}
		ajax::success();
	}

	if (init('action') == 'removeKey') {
		unautorizedInDemo();
		$keys = init('key');
		if ($keys == '') {
			throw new Exception(__('Aucune clef demandée', __FILE__));
		}
		if (is_json($keys)) {
			$keys = json_decode($keys, true);
			$return = array();
			foreach ($keys as $key => $value) {
				config::remove($key, init('plugin', 'core'));
			}
		} else {
			config::remove(init('key'), init('plugin', 'core'));
		}
		ajax::success();
	}

	if (init('action') == 'uploadImage') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();

		$page = init('id');
		$key = 'interface::background::' . $page;
		config::save($key, config::getDefaultConfiguration('core')['core'][$key], 'core');

		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.jpg', '.png'))) {
			throw new Exception(__('Extension du fichier non valide (autorisé .jpg .png) :', __FILE__) . ' ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 5000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 5Mo)', __FILE__));
		}

		$uploaddir = realpath(__DIR__ . '/../../data/backgrounds');
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir, 0777);
		}


		$filepath = $uploaddir . '/config_' . $page . $extension;
		@unlink($filepath);
		file_put_contents($filepath, file_get_contents($_FILES['file']['tmp_name']));
		if (!file_exists($filepath)) {
			throw new \Exception(__('Impossible de sauvegarder l\'image', __FILE__));
		}

		config::save($key, '/data/backgrounds/config_' . $page . $extension);
		ajax::success();
	}

	if (init('action') == 'removeImage') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();

		$page = init('id');
		$key = 'interface::background::' . $page;
		$filepath = '../..' . config::byKey($key, 'core');

		@unlink($filepath);
		config::save($key, config::getDefaultConfiguration('core')['core'][$key], 'core');
		ajax::success();
	}

	throw new Exception(__('Aucune méthode correspondante à :', __FILE__) . ' ' . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
