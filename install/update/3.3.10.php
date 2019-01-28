<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (scenario::all() as $scenario) {
  try {
    $scenario->setIsVisible(0);
    $scenario->save();
  } catch (\Exception $e) {
    
  }
}
