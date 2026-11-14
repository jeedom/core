<?php
declare(strict_types=1);

use Jeedom\Api\Rest\RestoreStatus;

require_once __DIR__ . '/restore-status.class.php';

// Ce point d'entrée doit fonctionner pendant le remplacement de la base et du Core.
header('Cache-Control: no-store');
header('Content-Type: application/json');
header('Cross-Origin-Resource-Policy: same-origin');
header('X-Content-Type-Options: nosniff');

$status = null;
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'GET'
    && !in_array($_SERVER['HTTP_SEC_FETCH_SITE'] ?? '', ['same-site', 'cross-site'], true)) {
    $status = RestoreStatus::read(
        $_GET['operation'] ?? null,
        $_COOKIE[RestoreStatus::cookieName($_SERVER)] ?? null
    );
}
if ($status === null) {
    http_response_code(404);
    return;
}
echo json_encode($status, JSON_THROW_ON_ERROR);
