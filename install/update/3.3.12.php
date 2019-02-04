<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
@rrmdir(__DIR__ . '/../../core/img/view');
foreach (view::all() as $view) {
  if($view->getImage('data') == ''){
    continue;
  }
  $filename = 'view'.$view->getId().'-'.$view->getImage('sha512') . '.' . $view->getImage('type');
  $filepath = __DIR__ . '/../../data/view/' . $filename;
  file_put_contents($filepath,base64_decode($view->getImage('data')));
  $view->setImage('data','');
  $view->save();
}
@rrmdir(__DIR__ . '/../../core/img/object');
foreach (jeeObject::all() as $object) {
  if($object->getImage('data') == ''){
    continue;
  }
  $filename = 'object'.$object->getId().'-'.$object->getImage('sha512') . '.' . $object->getImage('type');
  $filepath = __DIR__ . '/../../data/object/' . $filename;
  file_put_contents($filepath,base64_decode($object->getImage('data')));
  $object->setImage('data','');
  $object->save();
}
@rrmdir(__DIR__ . '/../../core/img/plan');
foreach (planHeader::all() as $plan) {
  if($plan->getImage('data') == ''){
    continue;
  }
  $filename = 'planHeader'.$plan->getId().'-'.$plan->getImage('sha512') . '.' . $plan->getImage('type');
  $filepath = __DIR__ . '/../../data/plan/' . $filename;
  file_put_contents($filepath,base64_decode($plan->getImage('data')));
  $plan->setImage('data','');
  $plan->save();
}
shell_exec('sudo cp -R '.__DIR__ . '/../../core/img/plan_* '.__DIR__ . '/../../data/plan');
