<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$cmd = "sudo sed -i 's#deb http://repo.jeedom.com/odroid/ dists/stable/main/binary-arm64/##g' /etc/apt/sources.list ";
exec($cmd);
