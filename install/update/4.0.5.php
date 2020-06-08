<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
echo "Begin migrate plan\n";
foreach (cmd::all() as $cmd) {
  $plans =  plan::byLinkTypeLinkId('cmd',$cmd->getId());
  if(count($plans) != 0){
    foreach ($plans as $plan) {
      try {
        if($cmd->getDisplay('showNameOn') == 0){
          $plan->setDisplay('hideName',1);
        }
        $plan->save();
      } catch (\Exception $e) {
        echo 'Error on convert design : '.$e->getMessage()."\n";
      }
    }
  }
  $plans =  plan::byLinkTypeLinkId('eqLogic',$cmd->getEqLogic_id());
  if(count($plans) != 0){
    foreach ($plans as $plan) {
      try {
        if($cmd->getDisplay('showOnplan') == 0){
          $plan->setDisplay('cmdHide',array_merge(array($cmd->getId() => 1),$plan->getDisplay('cmdHide',array())));
        }
        if($cmd->getDisplay('showNameOn') == 0){
          $plan->setDisplay('cmdHideName',array_merge(array($cmd->getId() => 1),$plan->getDisplay('cmdHideName',array())));
        }
        $plan->save();
      } catch (\Exception $e) {
        echo 'Error on convert design : '.$e->getMessage()."\n";
      }
    }
  }
}
foreach (eqLogic::all() as $eqLogic) {
  $plans =  plan::byLinkTypeLinkId('eqLogic',$eqLogic->getId());
  if(count($plans) != 0){
    foreach ($plans as $plan) {
      try {
        if($eqLogic->getDisplay('background-color-defaultplan') != 1){
          $plan->setDisplay('background-defaut',0);
          $plan->setCss('background-color',$eqLogic->getDisplay('background-colorplan'));
          $plan->setDisplay('background-transparent',$eqLogic->getDisplay('background-color-transparentplan'));
        }
        if($eqLogic->getDisplay('border-defaultplan') != 1){
          $plan->setCss('border',$eqLogic->getDisplay('borderplan').'px solid black');
        }
        if($eqLogic->getDisplay('border-radius-defaultplan') != 1){
          $plan->setCss('border-radius',$eqLogic->getDisplay('border-radiusplan').'px');
        }
        if($eqLogic->getDisplay('color-defaultplan') != 1){
          $plan->setCss('color',$eqLogic->getDisplay('colorplan'));
          $plan->setDisplay('color-defaut',0);
        }else{
          $plan->setCss('color','#FFFFFF');
          $plan->setDisplay('color-defaut',0);
        }
        $plan->setDisplay('hideName',1 - $eqLogic->getDisplay('showNameOnplan'));
        $plan->setCss('opacity',$eqLogic->getDisplay('background-opacityplan'));
        $plan->save();
      } catch (\Exception $e) {
        echo 'Error on convert design : '.$e->getMessage()."\n";
      }
    }
  }
}
?>
