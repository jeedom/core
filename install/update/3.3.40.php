<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (user::all() as $user) {
  if($user->getProfils() != 'admin' || $user->getOptions('doNotRotateHash',0) == 1){
    continue;
  }
  $user->setHash('');
  $user->getHash();
  $user->setOptions('hashGenerated',date('Y-m-d H:i:s'));
  $user->save();
}
