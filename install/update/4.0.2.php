<?php
require_once __DIR__ . '/../../core/php/core.inc.php';


function replaceAndSaveFA5($objects){
  $replace = array(
    '<i class="fa ' => '<i class="fas ',
    "<i class=\'fa " => "<i class=\'fas ",
    '<i class=\"fa ' => '<i class=\"fas ',
    '-o>' => '>',
    '-o">' => '">',
    "-o'>" => "'>"
  );
  foreach ($objects as $object) {
    try {
      $json =  json_encode(utils::o2a($object));
      $json = str_replace(array_keys($replace), $replace,$json);
      utils::a2o($object,json_decode($json,true));
      $object->save();
    } catch (\Exception $e) {
      
    }
  }
}

replaceAndSaveFA5(jeeObject::all());
replaceAndSaveFA5(eqLogic::all());
replaceAndSaveFA5(cmd::all());
replaceAndSaveFA5(scenario::all());
replaceAndSaveFA5(scenarioExpression::all());
replaceAndSaveFA5(viewZone::all());
replaceAndSaveFA5(viewData::all());
replaceAndSaveFA5(view::all());
replaceAndSaveFA5(plan::all());
replaceAndSaveFA5(planHeader::all());
?>
