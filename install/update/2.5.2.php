<?php
try {
	foreach (plugin::listPlugin() as $plugin) {
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $plugin->getId() . '/doc/fr_FR/.htaccess')) {
			shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../plugins/' . $plugin->getId() . '/doc/fr_FR/.htaccess');
		}
	}
} catch (Exception $exc) {

}
?>