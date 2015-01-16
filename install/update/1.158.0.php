<?php
try {
	foreach(scenarioExpression::all() as $expression){
		if($expression->getType() == 'condition'){
			$value = $expression->getExpression();
			$value = str_replace('==', '=', $value);
			$value = str_replace('=', '==', $value);
			$expression->setExpression($value);
			$expression->save();
		}
	}
} catch (Exception $e) {
	
}
?>