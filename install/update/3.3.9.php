<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
  echo 'Add at';
  if (exec('which rsync | wc -l') == 0) {
    exec('sudo apt-get install -y rsync');
  }
}
