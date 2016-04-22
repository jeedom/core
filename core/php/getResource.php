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
$file = dirname(__FILE__) . '/../../' . init('file');
$pathinfo = pathinfo($file);
if ($pathinfo['extension'] != 'js' && $pathinfo['extension'] != 'css') {
	die();
}
if (file_exists($file)) {
	switch ($pathinfo['extension']) {
		case 'js':
			$contentType = 'application/javascript';
			$md5 = init('md5');
			$etagFile = ($md5 == '') ? md5_file($file) : $md5;
			break;
		case 'css':
			$contentType = 'text/css';
			$etagFile = md5_file($file);
			break;
		default:
			die();
	}
	header('Content-Type: '. $contentType);
	$lastModified = filemtime($file);
	$ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
	$etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
	header('Etag: ' . $etagFile);
	header('Cache-Control: public');
	if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified || $etagHeader == $etagFile) {
		header('HTTP/1.1 304 Not Modified');
		exit;
	}
	if ($pathinfo['extension'] == 'js')
	{
		echo translate::exec(file_get_contents($file), init('file'), true);
	}
	if ($pathinfo['extension'] == 'css')
	{
		echo file_get_contents($file);
	}
	exit;
}
