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
    ajax::checkAccess('admin');
	if (init('action') == 'uploadCloud') {
		unautorizedInDemo();
		repo_market::backup_send(init('backup'));
		return '';
	}
	
	if (init('action') == 'restoreCloud') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		$class::backup_restore(init('backup'));
		return '';
	}
	
	if (init('action') == 'pullInstall') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		return $class::pullInstall();
	}
	
	if (init('action') == 'sendReportBug') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		return $class::saveTicket(json_decode(init('ticket'), true));
	}
	
	if (init('action') == 'install') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		$repo = $class::byId(init('id'));
		if (!is_object($repo)) {
			throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . init('id'));
		}
		$update = update::byTypeAndLogicalId($repo->getType(), $repo->getLogicalId());
		if (!is_object($update)) {
			$update = new update();
		}
		$update->setSource(init('repo'));
		$update->setLogicalId($repo->getLogicalId());
		$update->setType($repo->getType());
		$update->setLocalVersion($repo->getDatetime(init('version', 'stable')));
		$update->setConfiguration('version', init('version', 'stable'));
		$update->save();
		$update->doUpdate();
		return '';
	}
	
	if (init('action') == 'test') {
		$class = 'repo_' . init('repo');
		$class::test();
		return '';
	}
	
	if (init('action') == 'remove') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		$repo = $class::byId(init('id'));
		if (!is_object($market)) {
			throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . init('id'));
		}
		$update = update::byTypeAndLogicalId($repo->getType(), $repo->getLogicalId());
		try {
			if (is_object($update)) {
				$update->remove();
			} else {
				$market->remove();
			}
		} catch (Exception $e) {
			if (is_object($update)) {
				$update->deleteObjet();
			}
		}
		return '';
	}
	
	if (init('action') == 'save') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		$repo_ajax = json_decode(init('market'), true);
		try {
			$repo = $class::byId($repo_ajax['id']);
		} catch (Exception $e) {
			$repo = new $class();
		}
		utils::a2o($repo, $repo_ajax);
		$repo->save();
		return '';
	}
	
	if (init('action') == 'getInfo') {
		$class = 'repo_' . init('repo');
		return $class::getInfo(init('logicalId'));
	}
	
	if (init('action') == 'byLogicalId') {
		$class = 'repo_' . init('repo');
		if (init('noExecption', 0) == 1) {
			try {
				return utils::o2a($class::byLogicalIdAndType(init('logicalId'), init('type')));
			} catch (Exception $e) {
				return '';
			}
		} else {
			return utils::o2a($class::byLogicalIdAndType(init('logicalId'), init('type')));
		}
	}
	
	if (init('action') == 'setRating') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		$repo = $class::byId(init('id'));
		if (!is_object($repo)) {
			throw new Exception(__('Impossible de trouver l\'objet associé : ', __FILE__) . init('id'));
		}
		$repo->setRating(init('rating'));
		return '';
	}
	
	if (init('action') == 'backupList') {
		$class = 'repo_' . init('repo');
		return $class::backup_list();
	}
	
	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	
	/*     * *********Catch exeption*************** */
});
