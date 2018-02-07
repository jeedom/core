<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
foreach (cmd::all() as $cmd) {
	if ($cmd->getDisplay('generic_type') == '') {
		continue;
	}
	$cmd->setGenericType($cmd->getDisplay('generic_type'));
	$cmd->save();
}
