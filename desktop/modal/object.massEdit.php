<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$type = init('type');
if(!class_exists($type)){
  throw new Exception('{{Type non trouvé : }}'.$type);
}
$fields = array();
foreach (explode('|',init('fields')) as &$field) {
  $field = explode(',',$field);
  $data = array(
    'path' => explode('::',$field[0]),
    'name' => (!isset($field[1]) || $field[1] == '') ? ucfirst($field[0]): $field[1],
    'type' => (!isset($field[2]) || $field[2]== '') ? 'input': $field[2],
    'key' => ''
  );
  for($i=0;$i<count($data['path']);$i++){
    $data['key'] .= 'data-l'.($i+1).'key="'.$data['path'][$i].'"';
  }
  $fields[] = $data;
}
sendVarToJs('edit_type',$type);
?>
<div id="div_alertMassEdit"></div>
<a class="btn btn-success btn-xs pull-right" id="bt_saveMassEdit"><i class="fas fa-check"></i> {{Sauvegarder}}</a>
<table class="table table-condensed tablesorter" id="table_massEdit">
  <thead>
    <tr>
      <th>#</th>
      <?php
      if(method_exists($type,'getEqType_name')){
        echo '  <th>{{Type}}</th>';
      }
      ?>
      <th>{{Nom}}</th>
      <?php
      foreach ($fields as $field) {
        switch ($field['type']) {
          case 'checkbox':
          $dataSorter = 'data-sorter="checkbox"';
          break;
          default:
          $dataSorter = 'data-sorter="input"';
          break;
        }
        echo '<th '.$dataSorter.'>'.$field['name'].'</th>';
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($type::all() as $object) {
      $data_object = utils::o2a($object);
      echo '<tr class="editObject" data-id="'.$object->getId().'">';
      echo '<td>';
      echo '<input class="editObjectAttr" data-l1key="id" hidden value="'.$object->getId().'"></input>';
      echo $object->getId();
      echo '</td>';
      if(method_exists($type,'getEqType_name')){
        echo '  <td>'.$object->getEqType_name().'</td>';
      }
      echo '<td>';
      echo $object->getHumanName();
      echo '</td>';
      foreach ($fields as $field) {
        echo '<td>';
        $value = $data_object;
        foreach ($field['path'] as $key) {
          $value = $value[$key];
        }
        switch ($field['type']) {
          case 'number':
          echo '<input type="number" class="form-control input-xs editObjectAttr" '.$field['key'].' value="'.$value.'"></input>';
          break;
          case 'checkbox':
          echo '<input type="checkbox" class="form-control input-xs editObjectAttr" '.$field['key'].' value="'.$value.'"></input>';
          break;
          default:
          echo '<input class="form-control input-xs editObjectAttr" '.$field['key'].' value="'.$value.'"></input>';
          break;
        }
        echo '</td>';
      }
      echo '</tr>';
    }
    ?>
  </tbody>
</table>

<script>
initTableSorter();

$('#bt_saveMassEdit').off('click').on('click',function(){
  jeedom.massEditSave({
    type : edit_type,
    objects : $('#table_massEdit .editObject').getValues('.editObjectAttr'),
    error: function (error) {
      $('#div_alertMassEdit').showAlert({message: error.message, level: 'danger'})
    },
    success : function(data){
      $('#div_alertMassEdit').showAlert({message: '{{Modification sauvegardées avec succès}}', level: 'success'})
    }
  })
});
</script>
