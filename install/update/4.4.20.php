<?php
require_once __DIR__ . '/../../core/php/core.inc.php';

// Update Smart & Atlas /boot/boot.scr
if (in_array(strtolower(jeedom::getHardwareName()), ['smart', 'atlas']) && !recovery::isInstalled()) {
	recovery::install();
}
