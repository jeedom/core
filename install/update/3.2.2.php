<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {

  echo 'Remove old jeedom repository';
  exec('sudo rm -rf /etc/apt/sources.list.d/jeedom.list');
  echo " OK\n";

  echo 'add new jeedom repository';
  exec('sudo echo "deb http://repo.jeedom.com/odroid/ stable main" > /etc/apt/sources.list.d/jeedom.list');
  echo " OK\n";

  echo 'add jeedom repo gpg key';
  exec('sudo wget -O - http://repo.jeedom.com/odroid/conf/jeedom.gpg.key | sudo apt-key add -');
  echo " OK\n";

	echo 'Update APT sources\n';

	exec('sudo apt-get update');
  
}
