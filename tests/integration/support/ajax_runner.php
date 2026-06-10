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

$payload = json_decode(file_get_contents('php://stdin'), true);
if (!is_array($payload)) {
	fwrite(STDERR, "Invalid JSON payload on stdin\n");
	exit(1);
}

require_once __DIR__ . '/../../../core/php/core.inc.php';

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['HTTP_HOST']      = 'localhost';
$_SERVER['SERVER_NAME']    = 'localhost';
$_SERVER['REMOTE_ADDR']    = '127.0.0.1';
$_POST    = $payload['post'] ?? [];
$_GET     = $payload['get']  ?? [];
$_REQUEST = array_merge($_GET, $_POST);

if (isset($payload['user_id'])) {
	$user = user::byId($payload['user_id']);
	if (!is_object($user)) {
		fwrite(STDERR, "User {$payload['user_id']} not found\n");
		exit(1);
	}
	$sessionId = bin2hex(random_bytes(32));
	session_id($sessionId);
	@session_start();
	$_SESSION['user'] = $user;
	$_SESSION['ip']   = '127.0.0.1';
	@session_write_close();
	$_COOKIE[session_name()] = $sessionId;
}

$ajaxFile = __DIR__ . '/../../../core/ajax/' . basename($payload['file']);
if (!is_file($ajaxFile)) {
	fwrite(STDERR, "Ajax file not found: {$ajaxFile}\n");
	exit(1);
}

include $ajaxFile;
