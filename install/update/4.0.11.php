<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (jeeObject::all() as $jeeObject) {
  if($jeeObject->getDisplay('tagColor') != '#000000' || $jeeObject->getDisplay('tagTextColor') != '#FFFFFF'){
    $jeeObject->setConfiguration('useCustomColor',1);
    $jeeObject->save();
  }
}
?>
