<?php

require_once __DIR__ . '/console.php';
require_once __DIR__ . '/core.inc.php';

try {
	frp::install();
} catch (Throwable $e) {
	log::add('frp', 'error', $e->getMessage());
	exit(1);
}
