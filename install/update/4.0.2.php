<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$replace = array(
  '<i class="fa' => '<i class="fas'
);


$objects = jeeObject::all();
foreach ($objects as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

$eqLogics = eqLogic::all();
foreach ($eqLogics as $eqLogic) {
  try {
    utils::a2o($eqLogic,str_replace(array_keys($replace), $replace, utils::o2a($eqLogic)));
    $eqLogic->save();
  } catch (\Exception $e) {
    
  }
}

$cmds = cmd::all();
foreach ($cmds as $cmd) {
  try {
    utils::a2o($cmd,str_replace(array_keys($replace), $replace, utils::o2a($cmd)));
    $cmd->save();
  } catch (\Exception $e) {
    
  }
}
?>
