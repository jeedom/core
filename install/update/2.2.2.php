<?php

try {
	foreach (eqLogic::all() as $eqLogic) {
		foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			if ($eqLogic->getDisplay('hideOn' . $key, 0) == 1) {
				$eqLogic->setDisplay('showOn' . $key, 0);
			} else {
				$eqLogic->setDisplay('showOn' . $key, 1);
			}
			if ($eqLogic->getDisplay('doNotShowObjectNameOn' . ucfirst($key), 1) == 1) {
				$eqLogic->setDisplay('showObjectNameOn' . $key, 0);
			} else {
				$eqLogic->setDisplay('showObjectNameOn' . $key, 1);
			}
			if ($eqLogic->getDisplay('doNotShowNameOn' . ucfirst($key), 0) == 1) {
				$eqLogic->setDisplay('showNameOn' . $key, 0);
			} else {
				$eqLogic->setDisplay('showNameOn' . $key, 1);
			}
		}
		$eqLogic->save();
	}
} catch (Exception $exc) {
	echo $exc->getMessage();
}

?>










