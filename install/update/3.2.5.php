<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (interactDef::all() as $interactDef) {
	$interactDef->setEnable(1);
	$interactDef->save();
}
