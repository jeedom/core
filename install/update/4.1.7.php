<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
  echo 'Update Jeedom repository';
  shell_exec('sudo apt-key del 97C2C7CC093A8022');
  shell_exec('sudo wget --quiet -O - http://repo.jeedom.com/odroid/conf/jeedom.gpg.key | sudo apt-key add -');
  shell_exec('sudo apt-get update');
}
