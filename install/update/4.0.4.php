<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
foreach (cmd::all() as $cmd) {
  foreach (array('dashboard','mobile') as $version) {
    if($cmd->getTemplate($version,'') == 'default'){
      continue;
    }else if($cmd->getTemplate($version,'') == ''){
      $cmd->setTemplate($version,'default');
      continue;
    }else{
      if(strpos($cmd->getTemplate($version, 'default'),'::') !== false){
        continue;
      }else{
        $template_name = 'cmd.' . $cmd->getType() . '.' . $cmd->getSubType() . '.' . $cmd->getTemplate($version, 'default');
        if(file_exists(__DIR__ . '/../../core/template/' . $version . '/'. $template_name .'.html')){
          $cmd->setTemplate($version,'core::'.$cmd->getTemplate($version, 'default'));
        }elseif(file_exists(__DIR__ . '/../../data/customTemplates/' . $version . '/'. $template_name .'.html')){
          $cmd->setTemplate($version,'custom::'.$cmd->getTemplate($version, 'default'));
        }
      }
    }
  }
  $cmd->save(true);
}

?>
