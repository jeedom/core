<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$rootObjects = jeeObject::rootObject(true);

function buildJsTree($_object){
  $childs = $_object->getChild();
  if(count($childs) == 0){
    return;
  }
  echo '<ul>';
  foreach ($childs as $object) {
    echo '<li class="jstree-open"><a data-object_id="'.$object->getId().'" data-name="'  . $object->getName().'">' . $object->getName().'</a>';
    buildJsTree($object);
    echo '</li>';
  }
  echo '</ul>';
}
?>
<div class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4">
    <div class="bs-sidebar">
      <ul class="nav nav-list bs-sidenav">
        <li style="margin-bottom: 5px;"><input id='in_searchObject' class="filter form-control" placeholder="{{Rechercher}}" style="width: 100%"/></li>
      </ul>
      <div id="div_treeObject">
        <ul>
          <?php
          foreach ($rootObjects as $object) {
            echo '<li class="jstree-open"><a data-object_id="'.$object->getId().'" data-name="'  . $object->getName().'" >'  . $object->getName().'</a>';
            buildJsTree($object);
            echo '</li>';
          }
          ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-lg-10 col-md-9 col-sm-8">
    <div class="div_displayEquipement"></div>
  </div>
</div>

<?php
  include_file('3rdparty', 'jquery.tree/jstree.min', 'js');
  include_file('desktop', 'dashboardit', 'js');
?>
