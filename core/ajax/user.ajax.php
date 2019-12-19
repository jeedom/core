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
	ajax::init(false);

	if (init('action') == 'useTwoFactorAuthentification') {
		$user = user::byLogin(init('login'));
		if (!is_object($user)) {
			ajax::success(0);
		}
		if (network::getUserLocation() == 'internal') {
			ajax::success(0);
		}
		ajax::success($user->getOptions('twoFactorAuthentification', 0));
	}

	if (init('action') == 'login') {
		if(!file_exists(session_save_path())){
			try {
				com_shell::execute(system::getCmdSudo() . ' mkdir ' .session_save_path().';'.system::getCmdSudo() . ' chmod 777 -R ' .session_save_path());
			} catch (\Exception $e) {
				
			}
		}
		try {
			if(com_shell::execute(system::getCmdSudo() . ' ls ' . session_save_path().' | wc -l') > 500){
				com_shell::execute(system::getCmdSudo() .'/usr/lib/php/sessionclean');
			}
		} catch (\Exception $e) {
			
		}
		if (!isConnect()) {
			if (config::byKey('sso:allowRemoteUser') == 1) {
				$user = user::byLogin($_SERVER['REMOTE_USER']);
				if (is_object($user) && $user->getEnable() == 1) {
					@session_start();
					$_SESSION['user'] = $user;
					@session_write_close();
					log::add('connection', 'info', __('Connexion de l\'utilisateur par REMOTE_USER : ', __FILE__) . $user->getLogin());
				}
			}
			if (!login(init('username'), init('password'), init('twoFactorCode'))) {
				throw new Exception('Mot de passe ou nom d\'utilisateur incorrect');
			}
		}

		if (init('storeConnection') == 1) {
			$rdk = config::genKey();
			$registerDevice = $_SESSION['user']->getOptions('registerDevice', array());
			if (!is_array($registerDevice)) {
				$registerDevice = array();
			}
			$registerDevice[sha512($rdk)] = array();
			$registerDevice[sha512($rdk)]['datetime'] = date('Y-m-d H:i:s');
			$registerDevice[sha512($rdk)]['ip'] = getClientIp();
			$registerDevice[sha512($rdk)]['session_id'] = session_id();
			setcookie('registerDevice', $_SESSION['user']->getHash() . '-' . $rdk, time() + 365 * 24 * 3600, "/", '', false, true);
			@session_start();
			$_SESSION['user']->refresh();
			$_SESSION['user']->setOptions('registerDevice', $registerDevice);
			$_SESSION['user']->save();
			@session_write_close();
			if (!isset($_COOKIE['jeedom_token'])) {
				setcookie('jeedom_token', ajax::getToken(), time() + 365 * 24 * 3600, "/", '', false, true);
			}
		}
		ajax::success();
	}

	if (init('action') == 'getApikey') {
		if (!login(init('username'), init('password'), init('twoFactorCode'))) {
			throw new Exception('Mot de passe ou nom d\'utilisateur incorrect');
		}
		ajax::success($_SESSION['user']->getHash());
	}

	if (!isConnect()) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	ajax::init();

	if (init('action') == 'validateTwoFactorCode') {
		unautorizedInDemo();
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

	if (init('action') == 'removeTwoFactorCode') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$user = user::byId(init('id'));
		if (!is_object($user)) {
			throw new Exception('User ID inconnu');
		}
		$user->setOptions('twoFactorAuthentification', 0);
		$user->save();
		ajax::success($result);
	}

	if (init('action') == 'isConnect') {
		ajax::success();
	}

	if (init('action') == 'refresh') {
		@session_start();
		$_SESSION['user']->refresh();
		@session_write_close();
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
		unautorizedInDemo();
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
		unautorizedInDemo();
		$users = json_decode(init('users'), true);
		$user = null;
		foreach ($users as &$user_json) {
			if (isset($user_json['id'])) {
				$user = user::byId($user_json['id']);
			}
			if (!is_object($user)) {
				if (config::byKey('ldap::enable') == '1') {
					throw new Exception(__('Vous devez désactiver l\'authentification LDAP pour pouvoir ajouter un utilisateur', __FILE__));
				}
				$user = new user();
			}
			utils::a2o($user, $user_json);
			$user->save();
		}
		@session_start();
		$_SESSION['user']->refresh();
		@session_write_close();
		ajax::success();
	}

	if (init('action') == 'remove') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		if (config::byKey('ldap::enable') == '1') {
			throw new Exception(__('Vous devez désactiver l\'authentification LDAP pour pouvoir supprimer un utilisateur', __FILE__));
		}
		if (init('id') == $_SESSION['user']->getId()) {
			throw new Exception(__('Vous ne pouvez pas supprimer le compte avec lequel vous êtes connecté', __FILE__));
		}
		$user = user::byId(init('id'));
		if (!is_object($user)) {
			throw new Exception('User ID inconnu');
		}
		$user->remove();
		ajax::success();
	}

	if (init('action') == 'saveProfils') {
		unautorizedInDemo();
		$user_json = jeedom::fromHumanReadable(json_decode(init('profils'), true));
		if (isset($user_json['id']) && $user_json['id'] != $_SESSION['user']->getId()) {
			throw new Exception('401 - Accès non autorisé');
		}
		@session_start();
		$_SESSION['user']->refresh();
		$login = $_SESSION['user']->getLogin();
		$rights = $_SESSION['user']->getRights();
		utils::a2o($_SESSION['user'], $user_json);
		foreach ($rights as $right => $value) {
			$_SESSION['user']->setRights($right, $value);
		}
		$_SESSION['user']->setLogin($login);
		$_SESSION['user']->save();
		@session_write_close();
		eqLogic::clearCacheWidget();
		ajax::success();
	}

	if (init('action') == 'get') {
		ajax::success(jeedom::toHumanReadable(utils::o2a($_SESSION['user'])));
	}

	if (init('action') == 'removeRegisterDevice') {
		unautorizedInDemo();
		if (init('key') == '' && init('user_id') == '') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
			}
			foreach (user::all() as $user) {
				if ($user->getId() == $_SESSION['user']->getId()) {
					@session_start();
					$_SESSION['user']->refresh();
					$_SESSION['user']->setOptions('registerDevice', array());
					$_SESSION['user']->save();
					@session_write_close();
				} else {
					$user->setOptions('registerDevice', array());
					$user->save();
				}
			}
			ajax::success();
		}
		if (init('user_id') != '') {
			if (!isConnect('admin')) {
				throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
			}
			$user = user::byId(init('user_id'));
			if (!is_object($user)) {
				throw new Exception(__('Utilisateur non trouvé : ', __FILE__) . init('user_id'));
			}
			$registerDevice = $user->getOptions('registerDevice', array());
		} else {
			$registerDevice = $_SESSION['user']->getOptions('registerDevice', array());
		}

		if (init('key') == '') {
			$registerDevice = array();
		} elseif (isset($registerDevice[init('key')])) {
			unset($registerDevice[init('key')]);
		}
		if (init('user_id') != '') {
			$user->setOptions('registerDevice', $registerDevice);
			$user->save();
		} else {
			@session_start();
			$_SESSION['user']->refresh();
			$_SESSION['user']->setOptions('registerDevice', $registerDevice);
			$_SESSION['user']->save();
			@session_write_close();
		}
		ajax::success();
	}

	if (init('action') == 'deleteSession') {
		unautorizedInDemo();
		$sessions = listSession();
		if (isset($sessions[init('id')])) {
			$user = user::byId($sessions[init('id')]['user_id']);
			if (is_object($user)) {
				$registerDevice = $user->getOptions('registerDevice', array());
				foreach ($user->getOptions('registerDevice', array()) as $key => $value) {
					if ($value['session_id'] == init('id')) {
						unset($registerDevice[$key]);
					}
				}
				$user->setOptions('registerDevice', $registerDevice);
				$user->save();
			}
		}
		deleteSession(init('id'));
		ajax::success();
	}

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__), -1234);
	}

	if (init('action') == 'testLdapConnection') {
		if (!isConnect('admin')) {
			throw new Exception(__('401 - Accès non autorisé', __FILE__));
		}
		unautorizedInDemo();
		$connection = user::connectToLDAP();
		if ($connection === false) {
			throw new Exception();
		}
		ajax::success();
	}

	if (init('action') == 'removeBanIp') {
		unautorizedInDemo();
		ajax::success(user::removeBanIp());
	}

	if (init('action') == 'supportAccess') {
		unautorizedInDemo();
		ajax::success(user::supportAccess(init('enable')));
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}
?>
