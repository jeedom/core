<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
foreach (interactDef::all() as $interactDef) {
	$interactDef->setEnable(1);
	$interactDef->save();
}
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
	echo 'add jeedom repo gpg key';
	exec('sudo wget -O - http://repo.jeedom.com/odroid/conf/jeedom.gpg.key | sudo apt-key add -');
	echo 'Update Jeedom repository';
	exec('sudo rm -rf /etc/apt/sources.list.d/jeedom.list');
	exec('sudo apt-add-repository "deb http://repo.jeedom.com/odroid/ stable main"');
	echo " OK\n";
	echo 'Update APT sources\n';
	exec('sudo apt-get update');
}
