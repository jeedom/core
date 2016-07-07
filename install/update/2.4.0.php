<?php
try {
	foreach (cmd::all() as $cmd) {
		$mc = cache::byKey('cmd' . $cmd->getId());
		if ($mc->getValue(null) !== null) {
			$cmd->setCache('value', $mc->getValue());
			$cmd->setCache('collectDate', $mc->getOptions('collectDate'));
			$cmd->setCache('valueDate', $mc->getOptions('valueDate'));
			$mc->remove();
		}
	}
} catch (Exception $exc) {
	echo $exc->getMessage();
}
?>