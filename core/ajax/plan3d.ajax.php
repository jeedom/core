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
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init(array('uploadModel'));

	if (init('action') == 'save') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan3ds = json_decode(init('plan3ds'), true);
		foreach ($plan3ds as $plan3d_ajax) {
			@$plan3d = plan3d::byId($plan3d_ajax['id']);
			if (!is_object($plan3d)) {
				$plan3d = new plan3d();
			}
			utils::a2o($plan3d, jeedom::fromHumanReadable($plan3d_ajax));
			$plan3d->save();
		}
		ajax::success();
	}

	if (init('action') == 'plan3dHeader') {
		$return = array();
		foreach (plan3d::byPlan3dHeaderId(init('plan3dHeader_id')) as $plan3d) {
			$info = utils::o2a($plan3d);
			$info['additionalData'] = $plan3d->additionalData();
			$return[] = $info;
		}
		ajax::success($return);
	}

	if (init('action') == 'create') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan3d = new plan3d();
		utils::a2o($plan3d, json_decode(init('plan3d'), true));
		$plan3d->save();
		ajax::success($plan3d->getHtml(init('version')));
	}

	if (init('action') == 'get') {
		$plan3d = plan3d::byId(init('id'));
		if (!is_object($plan3d)) {
			throw new Exception(__('Aucun plan3d correspondant', __FILE__));
		}
		$return = jeedom::toHumanReadable(utils::o2a($plan3d));
		$return['additionalData'] = $plan3d->additionalData();
		ajax::success($return);
	}

	if (init('action') == 'byName') {
		$plan3d = plan3d::byName3dHeaderId(init('name'), init('plan3dHeader_id'));
		if (!is_object($plan3d)) {
			ajax::success();
		}
		ajax::success($plan3d->getHtml());
	}

	if (init('action') == 'remove') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan3d = plan3d::byId(init('id'));
		if (!is_object($plan3d)) {
			throw new Exception(__('Aucun plan3d correspondant', __FILE__));
		}
		ajax::success($plan3d->remove());
	}

	if (init('action') == 'removeplan3dHeader') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan3dHeader = plan3dHeader::byId(init('id'));
		if (!is_object($plan3dHeader)) {
			throw new Exception(__('Objet inconnu verifiez l\'id', __FILE__));
		}
		$plan3dHeader->remove();
		ajax::success();
	}

	if (init('action') == 'allHeader') {
		$plan3dHeaders = plan3dHeader::all();
		$return = array();
		foreach ($plan3dHeaders as $plan3dHeader) {
			$info_plan3dHeader = utils::o2a($plan3dHeader);
			unset($info_plan3dHeader['image']);
			$return[] = $info_plan3dHeader;
		}
		ajax::success($return);
	}

	if (init('action') == 'getplan3dHeader') {
		$plan3dHeader = plan3dHeader::byId(init('id'));
		if (!is_object($plan3dHeader)) {
			throw new Exception(__('plan3d header inconnu verifiez l\'id : ', __FILE__) . init('id'));
		}
		if (trim($plan3dHeader->getConfiguration('accessCode', '')) != '' && $plan3dHeader->getConfiguration('accessCode', '') != sha512(init('code'))) {
			throw new Exception(__('Code d\'acces invalide', __FILE__), -32005);
		}
		$return = utils::o2a($plan3dHeader);
		ajax::success($return);
	}

	if (init('action') == 'saveplan3dHeader') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan3dHeader_ajax = json_decode(init('plan3dHeader'), true);
		$plan3dHeader = null;
		if (isset($plan3dHeader_ajax['id'])) {
			$plan3dHeader = plan3dHeader::byId($plan3dHeader_ajax['id']);
		}
		if (!is_object($plan3dHeader)) {
			$plan3dHeader = new plan3dHeader();
		}
		utils::a2o($plan3dHeader, $plan3dHeader_ajax);
		$plan3dHeader->save();
		ajax::success(utils::o2a($plan3dHeader));
	}

	if (init('action') == 'uploadModel') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan3dHeader = plan3dHeader::byId(init('id'));
		if (!is_object($plan3dHeader)) {
			throw new Exception(__('Objet inconnu. Vérifiez l\'ID', __FILE__));
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.zip'))) {
			throw new Exception('Extension du fichier non valide (autorisé .zip) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 150000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 150Mo)', __FILE__));
		}
		$uploaddir = '/tmp';
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de déplacer le fichier temporaire', __FILE__));
		}
		if (!file_exists($uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de téléverser le fichier (limite du serveur web ?)', __FILE__));
		}
		if ($plan3dHeader->getConfiguration('path') == '') {
			$plan3dHeader->setConfiguration('path', 'data/3d/' . config::genKey() . '/');
		}
		$file = $uploaddir . '/' . $_FILES['file']['name'];
		$cibDir = __DIR__ . '/../../' . $plan3dHeader->getConfiguration('path');
		$zip = new ZipArchive;
		$res = $zip->open($file);
		if ($res === TRUE) {
			if (!$zip->extractTo($cibDir . '/')) {
				throw new Exception(__('Impossible de décompresser les fichiers : ', __FILE__));
			}
			$zip->close();
			unlink($tmp);
		} else {
			throw new Exception(__('Impossible de décompresser l\'archive zip : ', __FILE__) . $file . ' => ' . ZipErrorMessage($res));
		}
		$objfile = ls($cibDir, '*.obj', false, array('files'));
		if (count($objfile) != 1) {
			throw new Exception(__('Il faut 1 seul et unique fichier .obj', __FILE__));
		}
		$plan3dHeader->setConfiguration('objfile', $objfile[0]);
		$mtlfile = ls($cibDir, '*.mtl', false, array('files'));
		if (count($mtlfile) == 1) {
			$plan3dHeader->setConfiguration('mtlfile', $mtlfile[0]);
		}
		$plan3dHeader->save();
		ajax::success();
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
