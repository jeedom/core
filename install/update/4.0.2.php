<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$replace = array(
  '<i class="fa' => '<i class="fas'
);

foreach (jeeObject::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (eqLogic::all() as $eqLogic) {
  try {
    utils::a2o($eqLogic,str_replace(array_keys($replace), $replace, utils::o2a($eqLogic)));
    $eqLogic->save();
  } catch (\Exception $e) {
    
  }
}

foreach (cmd::all() as $cmd) {
  try {
    utils::a2o($cmd,str_replace(array_keys($replace), $replace, utils::o2a($cmd)));
    $cmd->save();
  } catch (\Exception $e) {
    
  }
}

foreach (scenario::all() as $scenario) {
  try {
    utils::a2o($scenario,str_replace(array_keys($replace), $replace, utils::o2a($scenario)));
    $scenario->save();
  } catch (\Exception $e) {
    
  }
}
?>
