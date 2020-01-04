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
	
	ajax::init();
	
	if (init('action') == 'save') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plans = json_decode(init('plans'), true);
		foreach ($plans as $plan_ajax) {
			@$plan = plan::byId($plan_ajax['id']);
			if (!is_object($plan)) {
				$plan = new plan();
			}
			utils::a2o($plan, jeedom::fromHumanReadable($plan_ajax));
			$plan->save();
		}
		ajax::success();
	}
	
	if (init('action') == 'execute') {
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		ajax::success($plan->execute());
	}
	
	if (init('action') == 'planHeader') {
		$return = array();
		foreach (plan::byPlanHeaderId(init('planHeader_id')) as $plan) {
			$result = $plan->getHtml(init('version'));
			if (is_array($result)) {
				$return[] = $result;
			}
		}
		ajax::success($return);
	}
	
	if (init('action') == 'create') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan = new plan();
		utils::a2o($plan, json_decode(init('plan'), true));
		$plan->save();
		ajax::success($plan->getHtml(init('version')));
	}
	
	if (init('action') == 'copy') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		ajax::success($plan->copy()->getHtml(init('version', 'dashboard')));
	}
	
	if (init('action') == 'get') {
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		ajax::success($plan->getHtml('dashboard'));
	}
	
	if (init('action') == 'remove') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		ajax::success($plan->remove());
	}
	
	if (init('action') == 'removePlanHeader') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Objet inconnu. Vérifiez l\'ID', __FILE__));
		}
		$planHeader->remove();
		ajax::success();
	}
	
	if (init('action') == 'allHeader') {
		$planHeaders = planHeader::all();
		$return = array();
		foreach ($planHeaders as $planHeader) {
			$info_planHeader = utils::o2a($planHeader);
			unset($info_planHeader['image']);
			$return[] = $info_planHeader;
		}
		ajax::success($return);
	}
	
	if (init('action') == 'getPlanHeader') {
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Plan header inconnu. Vérifiez l\'ID ', __FILE__) . init('id'));
		}
		if (trim($planHeader->getConfiguration('accessCode', '')) != '' && $planHeader->getConfiguration('accessCode', '') != sha512(init('code'))) {
			throw new Exception(__('Code d\'acces invalide', __FILE__), -32005);
		}
		$return = utils::o2a($planHeader);
		$return['image'] = $planHeader->displayImage();
		ajax::success($return);
	}
	
	if (init('action') == 'savePlanHeader') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$planHeader_ajax = json_decode(init('planHeader'), true);
		$planHeader = null;
		if (isset($planHeader_ajax['id'])) {
			$planHeader = planHeader::byId($planHeader_ajax['id']);
		}
		if (!is_object($planHeader)) {
			$planHeader = new planHeader();
		}
		utils::a2o($planHeader, $planHeader_ajax);
		$planHeader->save();
		ajax::success(utils::o2a($planHeader));
	}
	
	if (init('action') == 'copyPlanHeader') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Plan header inconnu. Vérifiez l\'ID ', __FILE__) . init('id'));
		}
		ajax::success(utils::o2a($planHeader->copy(init('name'))));
	}
	
	if (init('action') == 'removeImageHeader') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Plan header inconnu. Vérifiez l\'ID ', __FILE__) . init('id'));
		}
		$filename = 'planHeader'.$planHeader->getId().'-'.$planHeader->getImage('sha512') . '.' . $planHeader->getImage('type');
		$planHeader->setImage('sha512', '');
		$planHeader->save();
		@unlink( __DIR__ . '/../../data/plan/' . $filename);
		ajax::success();
	}
	
	if (init('action') == 'uploadImage') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Objet inconnu. Vérifiez l\'ID', __FILE__));
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.jpg', '.png'))) {
			throw new Exception('Extension du fichier non valide (autorisé .jpg .png) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 5000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 5Mo)', __FILE__));
		}
		$files = ls(__DIR__ . '/../../data/plan/','planHeader'.$planHeader->getId().'*');
		if(count($files)  > 0){
			foreach ($files as $file) {
				unlink(__DIR__ . '/../../data/plan/'.$file);
			}
		}
		$img_size = getimagesize($_FILES['file']['tmp_name']);
		$planHeader->setImage('type', str_replace('.', '', $extension));
		$planHeader->setImage('size', $img_size);
		$planHeader->setImage('sha512', sha512($_FILES['file']['tmp_name']));
		$filename = 'planHeader'.$planHeader->getId().'-'.$planHeader->getImage('sha512') . '.' . $planHeader->getImage('type');
		$filepath = __DIR__ . '/../../data/plan/' . $filename;
		file_put_contents($filepath,file_get_contents($_FILES['file']['tmp_name']));
		if(!file_exists($filepath)){
			throw new \Exception(__('Impossible de sauvegarder l\'image',__FILE__));
		}
		$planHeader->setConfiguration('desktopSizeX', $img_size[0]);
		$planHeader->setConfiguration('desktopSizeY', $img_size[1]);
		$planHeader->save();
		ajax::success();
	}
	
	if (init('action') == 'uploadImagePlan') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Objet inconnu. Vérifiez l\'ID', __FILE__));
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifiez le paramètre PHP (post size limit)', __FILE__));
		}
		$extension = strtolower(strrchr($_FILES['file']['name'], '.'));
		if (!in_array($extension, array('.jpg', '.png'))) {
			throw new Exception('Extension du fichier non valide (autorisé .jpg .png) : ' . $extension);
		}
		if (filesize($_FILES['file']['tmp_name']) > 5000000) {
			throw new Exception(__('Le fichier est trop gros (maximum 5Mo)', __FILE__));
		}
		$uploaddir = __DIR__ . '/../../data/plan/plan_' . $plan->getId();
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir, 0777);
		}
		shell_exec('rm -rf ' . $uploaddir . '/*');
		$name = sha512(base64_encode(file_get_contents($_FILES['file']['tmp_name']))) . $extension;
		$img_size = getimagesize($_FILES['file']['tmp_name']);
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . '/' . $name)) {
			throw new Exception(__('Impossible de déplacer le fichier temporaire dans : ', __FILE__) . $uploaddir . '/' . $name);
		}
		$plan->setDisplay('width', $img_size[0]);
		$plan->setDisplay('height', $img_size[1]);
		$plan->setDisplay('path', 'data/plan/plan_' . $plan->getId() . '/' . $name);
		$plan->save();
		ajax::success();
	}
	
	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
