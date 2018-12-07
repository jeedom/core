<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
foreach (interactDef::all() as $interactDef) {
	$interactDef->setEnable(1);
	$interactDef->save();
}
