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

//Theme config renamed & legacy removed:
foreach (['default_bootstrap_theme' => 'jeedom_theme_main', 'default_bootstrap_theme_night' => 'jeedom_theme_alternate'] as $oldTheme => $newTheme) {
    if (($value = config::byKey($oldTheme, 'core')) !== '') {
        if (stripos($value, 'legacy') !== false) {
            $value = 'core2019_Light';
        }
        config::save($newTheme, $value, 'core');
    }
}

foreach (['mobile_theme_color', 'mobile_theme_color_night'] as $mobileTheme) {
    if (stripos(config::byKey($mobileTheme, 'core'), 'legacy') !== false) {
        config::save($mobileTheme, 'core2019_Light', 'core');
    }
}

//Previous bug alpha:
$negScenarios = scenario::byObjectId(-1);
foreach ($negScenarios as &$sc) {
    $sc->setObject_id(null);
    $sc->save();
}
