<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$objectTree = buildTreeRecursively();
function buildTreeRecursively($_return = null){
  if($_return == null){
    $_return = utils::o2a(jeeObject::rootObject(true));
  } foreach($_return as &$value){
          $object = jeeObject::byId($value['id']);
      if(!is_object($object)){
        continue;
      }
      $childs = $object->getChild();
      if(count($childs) == 0){
        continue;
      }
      $value['childs'] = buildTreeRecursively(utils::o2a($childs));
  }
  return $_return;
}

sendVarToJS([
  'jeephp2js.object_Struct' => $objectTree,
]);

?>
<div class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4">
    <div class="bs-sidebar">
      <br>
      <div id="div_treeObject">
      </div>
    </div>
  </div>

  <div class="col-lg-10 col-md-9 col-sm-8">
    <div class="div_displayEquipement posEqWidthRef"></div>
  </div>
</div>

<?php
  include_file('3rdparty', 'tree/treejs', 'css');
  include_file('3rdparty', 'tree/tree', 'js');
  include_file('desktop', 'dashboardit', 'js');
?>