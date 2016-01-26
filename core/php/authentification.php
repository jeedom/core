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

require_once dirname(__FILE__) . '/core.inc.php';
$session_lifetime = config::byKey('session_lifetime', 24);
if (!is_numeric($session_lifetime)) {
	$session_lifetime = 24;
}
ini_set('session.gc_maxlifetime', $session_lifetime * 3600);
ini_set('session.use_cookies', 1);
ini_set('session.cookie_httponly', 1);
if (isset($_COOKIE['sess_id'])) {
	session_id($_COOKIE['sess_id']);
}
@session_start();
if (!headers_sent()) {
	setcookie('sess_id', session_id(), time() + 24 * 3600, "/", '', false, true);
}
@session_write_close();

if (!isConnect() && isset($_COOKIE['registerDevice'])) {
	if (loginByHash($_COOKIE['registerDevice'])) {
		setcookie('registerDevice', $_COOKIE['registerDevice'], time() + 365 * 24 * 3600, "/", '', false, true);
	} else {
		setcookie('registerDevice', '', time() - 3600, "/", '', false, true);
	}
}

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

if (init('logout') == 1) {
	logout();
}

/* * *******************Securité anti piratage**************************** */
try {
	if (config::byKey('security::enable') == 1) {
		$connection = connection::byIp(getClientIp());
		if (is_object($connection) && $connection->getStatus() == 'Ban') {
			if (!headers_sent()) {
				header("Status: 404 Not Found");
				header('HTTP/1.0 404 Not Found');
			}
			$_SERVER['REDIRECT_STATUS'] = 404;
			echo "<h1>404 Not Found</h1>";
			echo "The page that you have requested could not be found.";
			exit();
		}
	}
} catch (Exception $e) {

}

/* * **************************Definition des function************************** */

function login($_login, $_password, $_twoFactor = null) {
	$user = user::connect($_login, $_password);
	if (!is_object($user) || $user->getEnable() == 0) {
		connection::failed();
		sleep(5);
		return false;
	}
	if ($user->getOptions('twoFactorAuthentification', 0) == 1 && $user->getOptions('twoFactorAuthentificationSecret') != '') {
		if (trim($_twoFactor) == '' || $_twoFactor == null || !$user->validateTwoFactorCode($_twoFactor)) {
			connection::failed();
			sleep(5);
			return false;
		}
	}
	connection::success($user->getLogin());
	@session_start();
	$_SESSION['user'] = $user;
	if (init('v') == 'd' && init('registerDevice') == 'on') {
		setcookie('registerDevice', $_SESSION['user']->getHash(), time() + 365 * 24 * 3600, "/", '', false, true);
	}
	@session_write_close();
	log::add('connection', 'info', __('Connexion de l\'utilisateur : ', __FILE__) . $_login);
	return true;
}

function loginByHash($_key) {
	$user = user::byHash($_key);
	if (!is_object($user) || $user->getEnable() == 0) {
		connection::failed();
		sleep(5);
		return false;
	}
	connection::success($user->getLogin());
	@session_start();
	$_SESSION['user'] = $user;
	@session_write_close();
	setcookie('registerDevice', $_key, time() + 365 * 24 * 3600, "/", '', false, true);
	log::add('connection', 'info', __('Connexion de l\'utilisateur par clef : ', __FILE__) . $user->getLogin());
	unset($_GET['auth']);
	return true;
}

function logout() {
	@session_start();
	setcookie('sess_id', '', time() - 3600, "/", '', false, true);
	setcookie('registerDevice', '', time() - 3600, "/", '', false, true);
	session_unset();
	session_destroy();
	return;
}

function isConnect($_right = '') {
	if (isset($_SESSION['user']) && is_object($_SESSION['user']) && $_SESSION['user']->is_Connected()) {
		if ($_right != '') {
			return ($_SESSION['user']->getRights($_right) == 1) ? true : false;
		}
		return true;
	}
	return false;
}

function hasRight($_right, $_needAdmin = false) {
	if (!isConnect()) {
		return false;
	}
	if (isConnect('admin')) {
		return true;
	}
	if (config::byKey('rights::enable') != 0) {
		return !$_needAdmin;
	}
	$rights = rights::byuserIdAndEntity($_SESSION['user']->getId(), $_right);
	if (!is_object($rights)) {
		return !$_needAdmin;
	}
	return $rights->getRight();
}

?>
