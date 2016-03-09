<?php

try {
	foreach (cmd::all() as $cmd) {
		foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			if ($cmd->getDisplay('hideOn' . $key, 0) == 1) {
				$cmd->setDisplay('showOn' . $key, 0);
			} else {
				$cmd->setDisplay('showOn' . $key, 1);
			}
			if ($cmd->getDisplay('doNotShowStatOn' . ucfirst($key), 1) == 1) {
				$cmd->setDisplay('showStatsOn' . $key, 0);
			} else {
				$cmd->setDisplay('showStatsOn' . $key, 1);
			}
			if ($cmd->getDisplay('doNotShowNameOn' . ucfirst($key), 0) == 1) {
				$cmd->setDisplay('showNameOn' . $key, 0);
			} else {
				$cmd->setDisplay('showNameOn' . $key, 1);
			}
		}
		$cmd->save();
	}
} catch (Exception $exc) {
	echo $exc->getMessage();
}

?>










