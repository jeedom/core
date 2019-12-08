<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

exec('sudo apt-get install -y redis-server');
exec('sudo systemctl restart redis-server > /dev/null 2>&1');
