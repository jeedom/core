<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (file_exists('/media/boot/multiboot/meson64_odroidc2.dtb.linux')) {
  echo 'Update swappiness';
  exec('sudo sysctl -w vm.swappiness=10');
  exec("sudo sed -i '/vm.swappiness=/d' /etc/sysctl.d/99-sysctl.conf");
  exec("sudo echo 'vm.swappiness=10' >> /etc/sysctl.d/99-sysctl.conf");
}
