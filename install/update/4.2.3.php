<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
echo 'Fix repo issue';
shell_exec('sudo apt-get --allow-releaseinfo-change update');
