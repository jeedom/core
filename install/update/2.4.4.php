<?php
try {
	$sql = 'ALTER TABLE config MODIFY `value` TEXT;';
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
} catch (Exception $exc) {
	echo $exc->getMessage();
}
try {
	foreach (plugin::listPlugin() as $plugin) {
		if (file_exists(dirname(__FILE__) . '/../../plugins/' . $plugin->getId() . '/doc/fr_FR/.htaccess')) {
			shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../plugins/' . $plugin->getId() . '/doc/fr_FR/.htaccess');
		}
	}
	foreach (array('fr_FR', 'en_US', 'es_ES', 'id_IS', 'de_DE', 'it_IT', 'ru_RU') as $value) {
		if (file_exists(dirname(__FILE__) . '/../../doc/' . $value . '/.htaccess')) {
			shell_exec('sudo rm -rf ' . dirname(__FILE__) . '/../../doc/' . $value . '/.htaccess');
		}
	}
} catch (Exception $exc) {

}
?>
