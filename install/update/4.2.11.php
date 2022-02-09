<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
echo 'Enable apache remoteip';
shell_exec('sudo a2enmod remoteip');
