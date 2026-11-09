<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__ . '/core.inc.php';
if (!isset($argv[1]) || !preg_match('/\A[a-f0-9]{32}\z/', $argv[1])) exit(1);
set_time_limit(0);
update::executeOperation($argv[1]);
