<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
try {
	foreach (plugin::listPlugin() as $plugin) {
		if (file_exists(__DIR__ . '/../../plugins/' . $plugin->getId() . '/doc/fr_FR/.htaccess')) {
			shell_exec('sudo rm -rf ' . __DIR__ . '/../../plugins/' . $plugin->getId() . '/doc/fr_FR/.htaccess');
		}
	}
	foreach (array('fr_FR', 'en_US', 'es_ES', 'id_ID', 'de_DE', 'it_IT', 'ru_RU') as $value) {
		if (file_exists(__DIR__ . '/../../doc/' . $value . '/.htaccess')) {
			shell_exec('sudo rm -rf ' . __DIR__ . '/../../doc/' . $value . '/.htaccess');
		}
	}
} catch (Exception $exc) {

}
?>
