<?php
$basePath = dirname(__DIR__);
$configFile = $basePath.'/core/config/common.config.php';
$configFile = str_replace('/', DIRECTORY_SEPARATOR, $configFile);

if (!is_readable($configFile)) {
    echo 'Veuillez créer, configurer et rendre accessible le fichier ' . $configFile . ' avant de lancer les tests.';
    exit(1);
}

require_once $basePath.'/core/php/core.inc.php';
