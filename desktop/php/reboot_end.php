<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (!isConnect('admin')) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
if ($_GET['shut'] == 1) {
	jeedom::haltSystem();
} else {
	jeedom::rebootSystem();
}
