<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
  $cmd = "sudo sed -i 's#deb http://repo.jeedom.com/odroid/ dists/stable/main/binary-arm64/##g' /etc/apt/sources.list ";
  exec($cmd);
}
