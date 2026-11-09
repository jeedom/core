<?php

/** Load the production operation methods against the REST suites' native doubles. */
function defineRestOperationsFixture(string $class, string $fixture): void {
    $heading = match ($class) {
        'plugin' => '/* Installations de plugins */',
        'update' => '/* Opérations de mise à jour */',
    };
    $directory = dirname(__DIR__, 2) . '/core/class';
    $source = file_get_contents($directory . '/' . $class . '.class.php');
    $start = $source === false ? false : strpos($source, $heading);
    if ($start === false) {
        throw new RuntimeException("Section des opérations introuvable dans $class");
    }
    $start += strlen($heading);
    $end = strpos($source, "/*     * *********************Méthodes d'instance************************* */", $start);
    if ($end === false) {
        throw new RuntimeException("Fin de la section des opérations introuvable dans $class");
    }

    $operations = substr($source, $start, $end - $start);
    $operations = str_replace('__DIR__', var_export($directory, true), $operations);
    eval("class $class {\n$fixture\n$operations\n}");
}
