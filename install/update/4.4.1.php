<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach ((cmd::all()) as $cmd) {
    if ($cmd->getType() == 'info') {
        if ($cmd->getTemplate('dashboard') == 'core::line') {
            $cmd->setDisplay('forceReturnLineAfter', 1);
            $cmd->save();
        }
    }
}
