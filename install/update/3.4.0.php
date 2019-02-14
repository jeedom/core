<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$folders = ls(__DIR__.'/../../core/themes/','*');
foreach ($folders as $folder) {
  if(strpos($folder,'core2019') !== false){
    continue;
  }
  echo 'Suppression theme '.__DIR__.'/../../core/themes/'.$folder;
  rrmdir(__DIR__.'/../../core/themes/'.$folder);
}

foreach (user::all()  as $user) {
  $user->setOptions('bootstrap_theme','core2019_Light');
  $user->setOptions('bootstrap_theme_night','core2019_Dark');
  $user->setOptions('mobile_theme_color','core2019_Light');
  $user->setOptions('mobile_theme_color_night','core2019_Dark');
  $user->setOptions('theme_start_day_hour','08:00');
  $user->setOptions('theme_end_day_hour','20:00');
  $user->save();
}
