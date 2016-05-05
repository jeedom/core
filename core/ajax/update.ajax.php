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

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	if (init('action') == 'all') {
		$return = array();
		foreach (update::all(init('filter')) as $update) {
			$infos = utils::o2a($update);
			$infos['info'] = $update->getInfo();
			$return[] = $infos;
		}
		ajax::success($return);
	}

	if (init('action') == 'checkAllUpdate') {
		update::checkAllUpdate();
		ajax::success();
	}

	if (init('action') == 'update') {
		log::clear('update');
		$update = update::byId(init('id'));
		if (!is_object($update)) {
			throw new Exception(__('Aucune correspondance pour l\'ID : ' . init('id'), __FILE__));
		}
		try {
			if ($update->getType() != 'core') {
				log::add('update', 'alert', __("[START UPDATE]\n", __FILE__));
			}
			$update->doUpdate();
			if ($update->getType() != 'core') {
				log::add('update', 'alert', __("[END UPDATE SUCCESS]\n", __FILE__));
			}
		} catch (Exception $e) {
			if ($update->getType() != 'core') {
				log::add('update', 'alert', $e->getMessage());
				log::add('update', 'alert', __("[END UPDATE ERROR]\n", __FILE__));
			}
		}
		ajax::success();
	}

	if (init('action') == 'remove') {
		$update = update::byId(init('id'));
		if (!is_object($update)) {
			$update = update::byLogicalId(init('id'));
		}
		if (!is_object($update)) {
			throw new Exception(__('Aucune correspondance pour l\'ID : ' . init('id'), __FILE__));
		}
		$update->deleteObjet();
		ajax::success();
	}

	if (init('action') == 'checkUpdate') {
		$update = update::byId(init('id'));
		if (!is_object($update)) {
			$update = update::byLogicalId(init('id'));
		}
		if (!is_object($update)) {
			throw new Exception(__('Aucune correspondance pour l\'ID : ' . init('id'), __FILE__));
		}
		$update->checkUpdate();
		ajax::success();
	}

	if (init('action') == 'updateAll') {
		update::makeUpdateLevel(init('mode'), init('level'), init('version', ''), init('onlyThisVersion', ''));
		ajax::success();
	}

	if (init('action') == 'save') {
		$update_json = json_decode(init('update'), true);
		if (isset($update_json['id'])) {
			$update = update::byId($update_json['id']);
		}
		if (isset($update_json['logicalId'])) {
			$update = update::byLogicalId($update_json['logicalId']);
		}
		if (!isset($update) || !is_object($update)) {
			$update = new update();
		}
		utils::a2o($update, $update_json);
		$update->save();
		$update->doUpdate();
		ajax::success(utils::o2a($update));
	}

	if (init('action') == 'saves') {
		utils::processJsonObject('update', init('updates'));
		ajax::success();
	}

	if (init('action') == 'preUploadFile') {
		$uploaddir = '/tmp';
		if (!file_exists($uploaddir)) {
			throw new Exception(__('Répertoire d\'upload non trouvé : ', __FILE__) . $uploaddir);
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifié parametre PHP (post size limit)', __FILE__));
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
		ajax::success($uploaddir . '/' . $filename);
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}