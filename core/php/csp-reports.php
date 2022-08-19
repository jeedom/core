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

require_once __DIR__ . '/../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isConnect())
	die(); // NOT POST OR NOT AUTH => close the connection

$received = file_get_contents("php://input"); // Get page full POST content
try {
	$report = json_decode($received, true); // Try to decode json or throw Exception
} catch (Exception $e) {
	log::add('security', 'error', sprintf(__('Erreur CSP: Impossible de décoder le rapport CSP provenant de la machine "%1$s" depuis la page "%2$s".', __FILE__), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_REFERER'])); // Add info into Jeedom security log
	die(); // NOT ABLE TO DECODE JSON => close the connection
}

if (!isset($report['csp-report'])) {
	log::add('security', 'info', __('Erreur CSP: Un rapport CSP mal formaté a été reçu.', __FILE__)); // Add info into Jeedom security log
	die(); // NO REPORT => close the connection
}

$csp = $report['csp-report']; // Extract CSP report from JSON return
$directive = $csp['original-policy']; // Init directive to log it
$violation = trim($csp['violated-directive']);
if ($violation != '') {
	foreach (explode(';', $csp['original-policy']) as $e) { // And try to find the specific violated directive to log it
		if (strpos(trim($e), $violation) === 0) {
			$directive = trim($e);
			break;
		}
	}
}

if ($csp['disposition'] == 'enforce') { // Error or Report regarding CSP disposition
	$level = 'error';
	$format = __('Erreur CSP: Impossible de charger la ressource "%1$s" sur la page "%2$s", car cette ressource va contre la directive de Content Security Policy: "%3$s"', __FILE__);
} else {
	$level = 'info';
	$format = __('Rapport CSP: La ressource "%1$s" a été chargée sur la page "%2$s", mais cette ressource va contre la directive de Content Security Policy: "%3$s"', __FILE__);
}

log::add('security', $level, sprintf($format, $csp['blocked-uri'], $csp['document-uri'], $directive)); // Add error/info into Jeedom security log
