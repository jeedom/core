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
require_once __DIR__ . '/core.inc.php';

$configs = config::byKeys(array('session_lifetime', 'sso:allowRemoteUser', 'sso:remoteUserHeader'));

if (session_status() == PHP_SESSION_DISABLED || !isset($_SESSION)) {
	$session_lifetime = $configs['session_lifetime'];
	if (!is_numeric($session_lifetime)) {
		$session_lifetime = 24;
	}
	ini_set('session.gc_maxlifetime', $session_lifetime * 3600);
	ini_set('session.cookie_lifetime', $session_lifetime * 3600);
	ini_set('session.use_cookies', 1);
	ini_set('session.cookie_httponly', 1);
	ini_set('session.use_only_cookies', 1);
	ini_set('session.sid_length', 64);
	ini_set('session.hash_function', 'sha256');
	ini_set('session.cookie_samesite', 'Strict');
	ini_set('session.use_strict_mode', 1);
	if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		ini_set('session.cookie_secure', 1);
		session_name('__Host-PHPSESSID');
	}
}
@session_start();
$_SESSION['ip'] = getClientIp();
@session_write_close();
if (user::isBan()) {
	header("Statut: 404 Page non trouvée");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Non trouvé</h1>";
	echo "La page que vous demandez ne peut être trouvée.";
	die();
}

if (!isConnect() && isset($_COOKIE['registerDevice']) && !loginByHash($_COOKIE['registerDevice'])) {
	if (version_compare(PHP_VERSION, '7.3') >= 0) {
		setcookie('registerDevice', '', ['expires' => time() + 365 * 24 * 3600, 'samesite' => 'Strict', 'httponly' => true, 'path' => '/', 'secure' => (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')]);
	} else {
		setcookie('registerDevice', '', time() + 365 * 24 * 3600, "/; samesite=Strict", '', (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'), true);
	}
}

if (!isConnect() && $configs['sso:allowRemoteUser'] == 1) {
	$header_value = ($configs['sso:remoteUserHeader'] != '') ? $_SERVER[$configs['sso:remoteUserHeader']] : $_SERVER['REMOTE_USER'];
	$user = user::byLogin($header_value);
	if (is_object($user) && $user->getEnable() == 1) {
		@session_start();
		$_SESSION['user'] = $user;
		@session_write_close();
		log::add('connection', 'info', __('Connexion de l\'utilisateur par REMOTE_USER :', __FILE__) . ' ' . $user->getLogin());
	}
}

if (!isConnect() && init('auth') != '') {
	loginByHash(init('auth'));
}

if (init('logout') == 1) {
	logout();
	echo '<script type="text/javascript">';
	echo "window.location.href='index.php';";
	echo '</script>';
}

/* * **************************Definition des function************************** */

function login($_login, $_password, $_twoFactor = null) {
	$user = user::connect($_login, $_password);
	if (!is_object($user) || $user->getEnable() == 0) {
		user::failedLogin();
		sleep(5);
		return false;
	}
	if ($user->getOptions('localOnly', 0) == 1 && network::getUserLocation() != 'internal') {
		user::failedLogin();
		sleep(5);
		return false;
	}
	$sMdp = (!is_sha512($_password)) ? sha512($_password) : $_password;
	if (network::getUserLocation() != 'internal' && $user->getOptions('twoFactorAuthentification', 0) == 1 && $user->getOptions('twoFactorAuthentificationSecret') != '') {
		if (trim($_twoFactor) == '' || $_twoFactor === null || !$user->validateTwoFactorCode($_twoFactor)) {
			user::failedLogin();
			sleep(5);
			return false;
		}
	}
	@session_start();
	$_SESSION['user'] = $user;
	session_regenerate_id(true);
	@session_write_close();
	log::add('connection', 'info', __('Connexion de l\'utilisateur :', __FILE__) . ' ' . $_login);
	return true;
}

function loginByHash($_key) {
	$key = explode('-', $_key);
	$user = user::byHash($key[0]);
	if (!is_object($user) || $user->getEnable() == 0) {
		user::failedLogin();
		sleep(5);
		return false;
	}
	if ($user->getOptions('localOnly', 0) == 1 && network::getUserLocation() != 'internal') {
		user::failedLogin();
		sleep(5);
		return false;
	}
	if (!isset($key[1])) {
		user::failedLogin();
		sleep(5);
		return false;
	}
	$rdk = sha512($key[1]);
	$registerDevice = $user->getOptions('registerDevice', array());
	if (!is_array($registerDevice) || !isset($registerDevice[$rdk])) {
		user::failedLogin();
		sleep(5);
		return false;
	}
	$registerDevice[$rdk] = array(
		'datetime' => date('Y-m-d H:i:s'),
		'ip' => getClientIp(),
		'session_id' => session_id(),
	);
	$user->setOptions('registerDevice', $registerDevice);
	$user->save();
	@session_start();
	$_SESSION['user'] = $user;
	@session_write_close();
	log::add('connection', 'info', __('Connexion de l\'utilisateur par clef :', __FILE__) . ' ' . $user->getLogin());
	return true;
}

function logout() {
	@session_start();
	if (version_compare(PHP_VERSION, '7.3') >= 0) {
		setcookie('registerDevice', '', ['expires' => time() + 365 * 24 * 3600, 'samesite' => 'Strict', 'httponly' => true, 'path' => '/', 'secure' => (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')]);
		setcookie('__Host-PHPSESSID', '', ['expires' => time() + 365 * 24 * 3600, 'samesite' => 'Strict', 'httponly' => true, 'path' => '/', 'secure' => (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')]);
	} else {
		setcookie('registerDevice', '', time() + 365 * 24 * 3600, "/; samesite=Strict", '', (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'), true);
		setcookie('__Host-PHPSESSID', '', time() + 365 * 24 * 3600, "/; samesite=Strict", '', (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'), true);
	}
	session_unset();
	session_destroy();
	return;
}
