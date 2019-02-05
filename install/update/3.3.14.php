<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
  echo 'Add at';
  exec('sudo wget http://ftp.debian.org/debian/pool/main/a/at/at_3.1.16-1_arm64.deb -O /tmp/at.deb');
  exec('sudo dpkg -i /tmp/at.deb');
  exec('sudo rm /tmp/at.deb');
}
if (exec('which at | wc -l') == 0) {
  exec('sudo apt-get install -y at');
}
