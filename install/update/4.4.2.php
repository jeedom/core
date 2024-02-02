<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

//New rewrited widgets, default widgets:
foreach ((cmd::all()) as $cmd) {
    if ($cmd->getType() == 'info') {
        if ($cmd->getTemplate('dashboard') == 'core::line') {
            $cmd->setDisplay('forceReturnLineAfter', 1);
            $cmd->save();
        }
    }
}

//New grid steps:
if (config::byKey('widget::step::width', 'core', 0) < 80) {
    config::save('widget::step::width', 80, 'core');
}
if (config::byKey('widget::step::height', 'core', 0) < 60) {
    config::save('widget::step::height', 60, 'core');
}

//Theme config renamed:
if (config::byKey('default_bootstrap_theme', 'core', 0) !== 0) {
    $value = config::byKey('default_bootstrap_theme', 'core');
    config::save('jeedom_theme_main', $value, 'core');
}
if (config::byKey('default_bootstrap_theme_night', 'core', 0) !== 0) {
    $value = config::byKey('default_bootstrap_theme_night', 'core');
    config::save('jeedom_theme_alternate', $value, 'core');
}

//Previous bug alpha:
$negScenarios = scenario::byObjectId(-1);
foreach ($negScenarios as &$sc) {
    $sc->setObject_id(null);
    $sc->save();
}
