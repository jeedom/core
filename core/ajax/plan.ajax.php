<?php

/** @entrypoint */
/** @ajax */

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

require_once __DIR__ . '/ajax.handler.inc.php';

ajaxHandle(function ()
{
    ajax::checkAccess('');
	if (init('action') == 'save') {
        ajax::checkAccess('admin');
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
		return '';
	}
	
	if (init('action') == 'execute') {
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		return $plan->execute();
	}
	
	if (init('action') == 'planHeader') {
		$return = array();
		foreach (plan::byPlanHeaderId(init('planHeader_id')) as $plan) {
			$result = $plan->getHtml(init('version'));
			if (is_array($result)) {
				$return[] = $result;
			}
		}
		return $return;
	}
	
	if (init('action') == 'create') {
        ajax::checkAccess('admin');
		unautorizedInDemo();
		$plan = new plan();
		utils::a2o($plan, json_decode(init('plan'), true));
		$plan->save();
		return $plan->getHtml(init('version'));
	}
	
	if (init('action') == 'copy') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		return $plan->copy()->getHtml(init('version', 'dashboard'));
	}
	
	if (init('action') == 'get') {
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		return $plan->getHtml('dashboard');
	}
	
	if (init('action') == 'remove') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$plan = plan::byId(init('id'));
		if (!is_object($plan)) {
			throw new Exception(__('Aucun plan correspondant', __FILE__));
		}
		return $plan->remove();
	}
	
	if (init('action') == 'removePlanHeader') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Objet inconnu. Vérifiez l\'ID', __FILE__));
		}
		$planHeader->remove();
		return '';
	}
	
	if (init('action') == 'allHeader') {
		$planHeaders = planHeader::all();
		$return = array();
		foreach ($planHeaders as $planHeader) {
			$info_planHeader = utils::o2a($planHeader);
			unset($info_planHeader['image']);
			$return[] = $info_planHeader;
		}
		return $return;
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
		return $return;
	}
	
	if (init('action') == 'savePlanHeader') {
		ajax::checkAccess('admin');
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
		return utils::o2a($planHeader);
	}
	
	if (init('action') == 'copyPlanHeader') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Plan header inconnu. Vérifiez l\'ID ', __FILE__) . init('id'));
		}
		return utils::o2a($planHeader->copy(init('name')));
	}
	
	if (init('action') == 'removeImageHeader') {
		ajax::checkAccess('admin');
		unautorizedInDemo();
		$planHeader = planHeader::byId(init('id'));
		if (!is_object($planHeader)) {
			throw new Exception(__('Plan header inconnu. Vérifiez l\'ID ', __FILE__) . init('id'));
		}
		$filename = 'planHeader'.$planHeader->getId().'-'.$planHeader->getImage('sha512') . '.' . $planHeader->getImage('type');
		$planHeader->setImage('sha512', '');
		$planHeader->save();
		@unlink( __DIR__ . '/../../data/plan/' . $filename);
		return '';
	}
	
	if (init('action') == 'uploadImage') {
		ajax::checkAccess('admin');
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
		$files = ls(__DIR__ . '/../../data/plan/','plan'.$planHeader->getId().'*');
		if(count($files)  > 0){
			foreach ($files as $file) {
				unlink(__DIR__ . '/../../data/plan/'.$file);
			}
		}
		$img_size = getimagesize($_FILES['file']['tmp_name']);
		$planHeader->setImage('type', str_replace('.', '', $extension));
		$planHeader->setImage('size', $img_size);
		$planHeader->setImage('sha512', sha512($planHeader->getImage('data')));
		$filename = 'planHeader'.$planHeader->getId().'-'.$planHeader->getImage('sha512') . '.' . $planHeader->getImage('type');
		$filepath = __DIR__ . '/../../data/plan/' . $filename;
		file_put_contents($filepath,file_get_contents($_FILES['file']['tmp_name']));
		if(!file_exists($filepath)){
			throw new \Exception(__('Impossible de sauvegarder l\'image',__FILE__));
		}
		$planHeader->setConfiguration('desktopSizeX', $img_size[0]);
		$planHeader->setConfiguration('desktopSizeY', $img_size[1]);
		$planHeader->save();
		return '';
	}
	
	if (init('action') == 'uploadImagePlan') {
		ajax::checkAccess('admin');
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
		return '';
	}
	
	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
});
