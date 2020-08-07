<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (cmd::all() as $cmd) {
  if($cmd->getType() != 'info'){
    continue;
  }
  if($cmd->getDisplay('invertBinary') != 1){
    continue;
  }
  $cmd->setDisplay('invertBinary',0);
  $parameters = $cmd->getDisplay('parameters',array());
  $parameters['invert'] = 1;
  $cmd->setDisplay('parameters',$parameters);
  $cmd->save();
}
