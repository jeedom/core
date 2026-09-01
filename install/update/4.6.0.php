<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

// Migrate time* widget templates to their non-time equivalent and set display[parameters][time] = duration if absent.
$cmds = cmd::searchTemplate('core::time');

foreach ($cmds as $cmd) {
    foreach (['dashboard', 'mobile'] as $version) {
        $template = $cmd->getTemplate($version, '');
        if (strpos($template, 'core::time') !== 0) {
            continue;
        }
        $cmd->setTemplate($version, 'core::' . lcfirst(str_replace('core::time', '', $template)));
    }
    $parameters = (array) $cmd->getDisplay('parameters', array());
    if (!isset($parameters['time'])) {
        $parameters['time'] = 'duration';
        $cmd->setDisplay('parameters', $parameters);
    }
    $cmd->save(true);
}
