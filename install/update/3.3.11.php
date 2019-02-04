<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
  echo 'Update Jeedom repository';
  exec('sudo wget --quiet -O - http://repo.jeedom.com/odroid/conf/jeedom.gpg.key | sudo apt-key add -');
  exec('sudo rm -rf /etc/apt/sources.list.d/jeedom.list*');
  exec('sudo apt-add-repository "deb http://repo.jeedom.com/odroid/ stable main"');
}
