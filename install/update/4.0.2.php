<?php
require_once __DIR__ . '/../../core/php/core.inc.php';


function replaceAndSaveFA5($objects){
  $replace = array(
    '<i class="fa ' => '<i class="fas ',
    "<i class=\'fa " => "<i class=\'fas ",
    '<i class=\"fa ' => '<i class=\"fas ',
    '-o>' => '>',
    '-o">' => '">',
    "-o'>" => "'>",
    "fa-video-camera" => "fa-video"
  );
  foreach ($objects as $object) {
    try {
      $json1 =  json_encode(utils::o2a($object));
      $json = str_replace(array_keys($replace), $replace,$json1);
      if($json1 == $json){
        continue;
      }
      utils::a2o($object,json_decode($json,true));
      $object->save(true);
    } catch (\Exception $e) {
      
    }
  }
}
ob_start();
replaceAndSaveFA5(jeeObject::all());
replaceAndSaveFA5(eqLogic::all());
replaceAndSaveFA5(cmd::all());
replaceAndSaveFA5(scenario::all());
replaceAndSaveFA5(scenarioExpression::all());
replaceAndSaveFA5(viewZone::all());
replaceAndSaveFA5(viewData::all());
replaceAndSaveFA5(view::all());
ob_end_clean();
?>
