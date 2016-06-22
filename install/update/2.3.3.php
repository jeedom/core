<?php
try {
	if (config::byKey('market::username') != '') {
		config::save('market::enable', 1);
	}
	config::save('market::cloudUpload', config::byKey('backup::cloudUpload'));
	shell_exec('sudo rm -Rf ' . dirname(__FILE__) . '/../../script/ngrok/* > /dev/null 2&>1');
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>

