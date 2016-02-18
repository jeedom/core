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

	if (init('action') == 'useTwoFactorAuthentification') {
		$user = user::byLogin(init('login'));
		if (!is_object($user)) {
			ajax::success(0);
		}
		ajax::success($user->getOptions('twoFactorAuthentification', 0));
	}

	if (init('action') == 'login') {
		if (!isConnect() && config::byKey('sso:allowRemoteUser') == 1) {
			$user = user::byLogin($_SERVER['REMOTE_USER']);
			if (is_object($user) && $user->getEnable() == 1) {
				connection::success($user->getLogin());
				@session_start();
				$_SESSION['user'] = $user;
				@session_write_close();
				log::add('connection', 'info', __('Connexion de l\'utilisateur par REMOTE_USER : ', __FILE__) . $user->getLogin());
			}
		}
		if (!isConnect() && !login(init('username'), init('password'), init('twoFactorCode'))) {
			throw new Exception('Mot de passe ou nom d\'utilisateur incorrect');
		}
		if (init('storeConnection') == 1) {
			setcookie('registerDevice', $_SESSION['user']->getHash(), time() + 365 * 24 * 3600, "/", '', false, true);
		}
		ajax::success();
	}

	if (init('action') == 'forgotPassword') {
		log::add('user', 'info', __('Demande de récupération de mot de passe pour : ', __FILE__) . init('login'));
		$user = user::byLogin(init('login'));
		if (!is_object($user)) {
			connection::failed();
			throw new Exception('Utilisateur introuvable');
		}
		$newPassword = config::genKey();
		$oldPassword = $user->getPassword();
		$user->setPassword(sha1($newPassword));
		$cmds = explode(('&&'), config::byKey('emailAdmin'));
		$found = false;
		try {
			if (count($cmds) > 0) {
				foreach ($cmds as $id) {
					$cmd = cmd::byId(str_replace('#', '', $id));
					if (is_object($cmd)) {
						$found = true;
						$cmd->execCmd(array(
							'title' => __('[JEEDOM] Récuperation de mot de passe', __FILE__),
							'message' => 'Voici votre nouveau mot de passe pour votre installation jeedom : ' . $newPassword,
						));
					}
				}
			}
		} catch (Exception $e) {
			throw new Exception(__('Aucune commande trouvé pour envoyé le nouveau mot de passe, la demande de récupération a echouée', __FILE__));
		}
		if (!$found) {
			throw new Exception(__('Aucune commande trouvé pour envoyé le nouveau mot de passe, la demande de récupération a echouée, vous pouvez trouver une procedure <a href="https://www.jeedom.com/doc/documentation/howto/fr_FR/doc-howto-reset.password.html" target="_blank">ici</a>', __FILE__));
		}
		$user->save();
		ajax::success();
	}

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	if (init('action') == 'validateTwoFactorCode') {
		@session_start();
		$_SESSION['user']->refresh();
		$result = $_SESSION['user']->validateTwoFactorCode(init('code'));
		if ($result && init('enableTwoFactorAuthentification') == 1) {
			$_SESSION['user']->setOptions('twoFactorAuthentification', 1);
			$_SESSION['user']->save();
		}
		@session_write_close();
		ajax::success($result);
	}

	if (init('action') == 'isConnect') {
		ajax::success();
	}

	if (init('action') == 'logout') {
		logout();
		ajax::success();
	}

	if (init('action') == 'all') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$users = array();
		foreach (user::all() as $user) {
			$user_info = utils::o2a($user);
			$users[] = $user_info;
		}
		ajax::success($users);
	}

	if (init('action') == 'save') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$users = json_decode(init('users'), true);
		$user = null;
		foreach ($users as $user_json) {
			if (isset($user_json['id'])) {
				$user = user::byId($user_json['id']);
			}
			if (!is_object($user)) {
				if (config::byKey('ldap::enable') == '1') {
					throw new Exception(__('Vous devez desactiver l\'authentification LDAP pour pouvoir ajouter un utilisateur', __FILE__));
				}
				$user = new user();
			}
			utils::a2o($user, $user_json);
			if (isset($user_json['password'])) {
				if (config::byKey('ldap::enable') == '1') {
					throw new Exception(__('Vous devez desactiver l\'authentification LDAP pour pouvoir editer un utilisateur', __FILE__));
				}
				$user->setPassword(sha1($user_json['password']));
			}
			$user->save();

		}
		$_SESSION['user']->refresh();
		ajax::success();
	}

	if (init('action') == 'remove') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		if (config::byKey('ldap::enable') == '1') {
			throw new Exception(__('Vous devez desactiver l\'authentification LDAP pour pouvoir supprimer un utilisateur', __FILE__));
		}
		$user = user::byId(init('id'));
		if (!is_object($user)) {
			throw new Exception('User id inconnu');
		}
		if (count(user::searchByRight('admin')) == 1 && $user->getRights('admin') == 1) {
			throw new Exception(__('Vous ne pouvez supprimer le dernière administrateur', __FILE__));
		}
		$user->remove();
		ajax::success();
	}

	if (init('action') == 'saveProfils') {

		$user_json = json_decode(init('profils'), true);
		if (isset($user_json['id']) && $user_json['id'] != $_SESSION['user']->getId()) {
			throw new Exception('401 unautorized');
		}
		@session_start();
		$_SESSION['user']->refresh();
		$login = $_SESSION['user']->getLogin();
		$rights = $_SESSION['user']->getRights();
		$password = $_SESSION['user']->getPassword();
		utils::a2o($_SESSION['user'], $user_json);
		foreach ($rights as $right => $value) {
			$_SESSION['user']->setRights($right, $value);
		}
		$_SESSION['user']->setLogin($login);
		if ($password != $_SESSION['user']->getPassword()) {
			$_SESSION['user']->setPassword(sha1($_SESSION['user']->getPassword()));
		}
		$_SESSION['user']->save();
		@session_write_close();
		ajax::success();
	}

	if (init('action') == 'get') {
		ajax::success(utils::o2a($_SESSION['user']));
	}

	if (init('action') == 'testLdapConnection') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		$connection = user::connectToLDAP();
		if ($connection === false) {
			throw new Exception();
		}
		ajax::success();
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
