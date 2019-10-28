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
	require_once __DIR__ . '/../php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	ajax::init();

	if (init('action') == 'uploadCloud') {
		unautorizedInDemo();
		repo_market::backup_send(init('backup'));
		ajax::success();
	}

	if (init('action') == 'restoreCloud') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		$class::backup_restore(init('backup'));
		ajax::success();
	}

	if (init('action') == 'sendReportBug') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		ajax::success($class::saveTicket(json_decode(init('ticket'), true)));
	}
	
	if (init('action') == 'pullInstall') {
		unautorizedInDemo();
		$class = 'repo_' . init('repo');
		ajax::success($class::pullInstall());
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
		ajax::success();
	}

	if (init('action') == 'test') {
		$class = 'repo_' . init('repo');
		$class::test();
		ajax::success();
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
		ajax::success();
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
		ajax::success();
	}

	if (init('action') == 'getInfo') {
		$class = 'repo_' . init('repo');
		ajax::success($class::getInfo(init('logicalId')));
	}

	if (init('action') == 'byLogicalId') {
		$class = 'repo_' . init('repo');
		if (init('noExecption', 0) == 1) {
			try {
				ajax::success(utils::o2a($class::byLogicalIdAndType(init('logicalId'), init('type'))));
			} catch (Exception $e) {
				ajax::success();
			}
		} else {
			ajax::success(utils::o2a($class::byLogicalIdAndType(init('logicalId'), init('type'))));
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
		ajax::success();
	}

	if (init('action') == 'backupList') {
		$class = 'repo_' . init('repo');
		ajax::success($class::backup_list());
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));

	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
