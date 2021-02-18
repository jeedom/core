<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (planHeader::all() as $planHeader) {
  foreach ($planHeader->getPlan() as $plan) {
    try {
      $plan->setPosition('top',($plan->getPosition('top') * $planHeader->getConfiguration('desktopSizeY'))/100);
      $plan->setPosition('left',($plan->getPosition('left') * $planHeader->getConfiguration('desktopSizeX'))/100);
      $plan->save();
    } catch (\Exception $e) {
      
    }
  }
}
