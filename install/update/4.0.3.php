<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$widget_dir = __DIR__.'/../../plugins/widget/core/template';
if(file_exists($widget_dir)){
  echo "Copy widget of plugin widget to jeedom custom widget dir...\n";
  $cib_dir = __DIR__.'/../../data/customTemplates/';
  if(!file_exists($cib_dir)){
    mkdir($cib_dir);
  }
  exec('cp -R '.$widget_dir.'/* '.$cib_dir);
  if(file_exists($cib_dir.'dashboard/empty')){
    unlink($cib_dir.'dashboard/empty');
  }
  if(file_exists($cib_dir.'mobile/empty')){
    unlink($cib_dir.'mobile/empty');
  }
}
?>
