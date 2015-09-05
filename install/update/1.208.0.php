<?php
if (jeedom::isCapable('sudo')) {
	exec("sudo sed -i 's/expose_php = On/expose_php = Off/g' /etc/php5/fpm/php.ini");
}
?>