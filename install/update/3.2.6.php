<?php
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
foreach (scenarioExpression::all() as $scenarioExpression) {
	if ($scenarioExpression->getExpression() == 'equipment') {
		try {
			$scenarioExpression->setExpression('equipement');
			$scenarioExpression->save();
		} catch (Exception $e) {

		}
	}
}
