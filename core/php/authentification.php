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

$configs = config::byKeys(array('session_lifetime', 'sso:allowRemoteUser'));

$session_lifetime = $configs['session_lifetime'];
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
		if (isset($_COOKIE['jeedom_token'])) {
			@session_start();
			$_SESSION['jeedom_token'] = $_COOKIE['jeedom_token'];
			@session_write_close();
		}
	} else {
		setcookie('registerDevice', '', time() - 3600, "/", '', false, true);
	}
}

if (!isConnect() && $configs['sso:allowRemoteUser'] == 1) {
	$user = user::byLogin($_SERVER['REMOTE_USER']);
	if (is_object($user) && $user->getEnable() == 1) {
		@session_start();
		$_SESSION['user'] = $user;
		@session_write_close();
		log::add('connection', 'info', __('Connexion de l\'utilisateur par REMOTE_USER : ', __FILE__) . $user->getLogin());
	}
}

if (!isConnect() && init('auth') != '') {
	loginByHash(init('auth'));
}

if (init('logout') == 1) {
	logout();
}

/* * **************************Definition des function************************** */

function login($_login, $_password, $_twoFactor = null) {
	$user = user::connect($_login, $_password);
	if (!is_object($user) || $user->getEnable() == 0) {
		sleep(5);
		return false;
	}
	if ($user->getOptions('localOnly', 0) == 1 && network::getUserLocation() != 'internal') {
		sleep(5);
		return false;
	}
	if (network::getUserLocation() != 'internal' && $user->getOptions('twoFactorAuthentification', 0) == 1 && $user->getOptions('twoFactorAuthentificationSecret') != '') {
		if (trim($_twoFactor) == '' || $_twoFactor === null || !$user->validateTwoFactorCode($_twoFactor)) {
			sleep(5);
			return false;
		}
	}

	@session_start();
	$_SESSION['user'] = $user;
	if (init('v') == 'd' && init('registerDevice') == 'on') {
		setcookie('registerDevice', $_SESSION['user']->getHash(), time() + 365 * 24 * 3600, "/", '', false, true);
		if (!isset($_COOKIE['jeedom_token'])) {
			setcookie('jeedom_token', ajax::getToken(), time() + 365 * 24 * 3600, "/", '', false, true);
		}
	}
	@session_write_close();
	log::add('connection', 'info', __('Connexion de l\'utilisateur : ', __FILE__) . $_login);
	return true;
}

function loginByHash($_key) {
	$user = user::byHash($_key);
	if (!is_object($user) || $user->getEnable() == 0) {
		sleep(5);
		return false;
	}
	if ($user->getOptions('localOnly', 0) == 1 && network::getUserLocation() != 'internal') {
		sleep(5);
		return false;
	}

	@session_start();
	$_SESSION['user'] = $user;
	@session_write_close();
	setcookie('registerDevice', $_key, time() + 365 * 24 * 3600, "/", '', false, true);
	if (!isset($_COOKIE['jeedom_token'])) {
		setcookie('jeedom_token', ajax::getToken(), time() + 365 * 24 * 3600, "/", '', false, true);
	}
	log::add('connection', 'info', __('Connexion de l\'utilisateur par clef : ', __FILE__) . $user->getLogin());
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

?>
