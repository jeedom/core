<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$widget_dir = __DIR__.'/../../plugins/widget/core/template';
if(file_exist($widget_dir)){
  echo "Copy widget of plugin widget to jeedom custom widget dir";
  $cib_dir = _DIR__.'/../../data/customTemplates/';
  if(!file_exist($cib_dir)){
    mkdir($cib_dir);
  }
  exec('cp -R '.$widget_dir.'/* '.$cib_dir);
}
?>
