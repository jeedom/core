<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (interactDef::all() as $interactDef) {
	$interactDef->setEnable(1);
	$interactDef->save();
}
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
	echo 'Update Jeedom repository';
	exec('sudo rm -rf /etc/apt/sources.list.d/jeedom.list');
	exec('sudo apt-add-repository "deb http://repo.jeedom.com/odroid/ dists/stable/main/binary-arm64/"');
	echo " OK\n";
	echo 'Update APT sources\n';
	exec('sudo apt-get update');
}
