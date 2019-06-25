<?php

if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
    throw new Exception('{{Scénario introuvable}}');
}

echo '<textarea style="height:100%;width:100%" disabled>' . $scenario->export() . '</textarea>';
?>
