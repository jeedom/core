<?php

function ajaxHandle($callback)
{
    try {
        require_once __DIR__ . '/../../core/php/core.inc.php';
        include_file('core', 'authentification', 'php');

        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        echo ajax::getResponse($callback());
    } catch (Exception $e) {
        echo ajax::getResponse(displayException($e), $e->getCode());
    }
}
