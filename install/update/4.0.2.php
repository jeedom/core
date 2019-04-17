<?php
require_once __DIR__ . '/../../core/php/core.inc.php';
$replace = array(
  '<i class="fa' => '<i class="fas'
);

foreach (jeeObject::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (eqLogic::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (cmd::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (scenario::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (scenarioExpression::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (viewZone::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (viewData::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (view::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (plan::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}

foreach (planHeader::all() as $object) {
  try {
    utils::a2o($object,str_replace(array_keys($replace), $replace, utils::o2a($object)));
    $object->save();
  } catch (\Exception $e) {
    
  }
}
?>
