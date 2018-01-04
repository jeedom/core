<?php

if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
    throw new Exception('{{Scenario introuvable}}');
}

echo '<pre>' . $scenario->export() . '</pre>';
?>
  



