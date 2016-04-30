<?php
try {
	if (config::byKey('market::username') != '') {
		config::save('market::enable', 1);
	}
	config::save('core::branch', config::byKey('market::branch'));
	config::save('market::cloudUpload', config::byKey('backup::cloudUpload'));
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>

