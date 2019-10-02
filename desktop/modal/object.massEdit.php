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
?>

<table class="table table-condensed tablesorter">
  <thead>
    <tr>
      <th>#</th>
      <th>{{Nom}}</th>
      <?php
      foreach ($fields as $field) {
        echo '<th>'.$field['name'].'</th>';
      }
      ?>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($type::all() as $object) {
      $data_object = utils::o2a($object);
      echo '<tr data-id="'.$object->getId().'">';
      echo '<td>';
      echo $object->getId();
      echo '</td>';
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
          echo '<input type="number" class="form-control input-xs editAttr" '.$field['key'].' value="'.$value.'"></input>';
          break;
          case 'checkbox':
          echo '<input type="checkbox" class="form-control input-xs editAttr" '.$field['key'].' value="'.$value.'"></input>';
          break;
          default:
          echo '<input class="form-control input-xs editAttr" '.$field['key'].' value="'.$value.'"></input>';
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

</script>
