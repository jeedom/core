<?php
try {
	if (config::byKey('market::username') != '') {
		config::save('market::enable', 1);
	}
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>

