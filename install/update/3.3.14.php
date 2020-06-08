<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
if (exec('which at | wc -l') == 0) {
  exec('sudo apt-get install -y at');
}
