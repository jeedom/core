<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$type = init('type');
if (!class_exists($type)) {
  throw new Exception('{{Type non trouvé :}}' . ' ' . $type);
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
  for ($i=0;$i<count($data['path']);$i++) {
    $data['key'] .= 'data-l'.($i+1).'key="'.$data['path'][$i].'"';
  }
  $fields[] = $data;
}
sendVarToJs('jeephp2js.md_objectMassEdit_type',$type);
?>

<div id="md_objectMassEdit" data-modalType="md_objectMassEdit">
  <a class="btn btn-success btn-xs pull-right" id="bt_saveMassEdit"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
  <table class="table table-condensed dataTable" id="table_massEdit">
    <thead>
      <tr>
        <th style="width: 120px;">#</th>
        <?php
        if (method_exists($type,'getEqType_name')) {
          echo '  <th>{{Type}}</th>';
        }
        ?>
        <th>{{Nom}}</th>
        <?php
        foreach ($fields as $field) {
          switch ($field['type']) {
            case 'checkbox':
              $dataSorter = 'data-type="checkbox"';
              break;
            default:
              $dataSorter = 'data-type="input"';
              break;
          }
          echo '<th '.$dataSorter.'>'.$field['name'].'</th>';
        }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach (($type::all()) as $object) {
        $data_object = utils::o2a($object);
        $tr = '';
        $tr .= '<tr class="editObject" data-id="'.$object->getId().'">';
        $tr .= '<td>';
        $tr .= $object->getId();
        $tr .= '<input class="editObjectAttr" data-l1key="id" hidden value="'.$object->getId().'"></input>';
        $tr .= '</td>';
        if(method_exists($type,'getEqType_name')){
          $tr .= '  <td>'.$object->getEqType_name().'</td>';
        }
        $tr .= '<td>';
        $tr .= $object->getHumanName();
        $tr .= '</td>';
        foreach ($fields as $field) {
          $tr .= '<td>';
          $value = $data_object;
          foreach ($field['path'] as $key) {
            $value = $value[$key];
          }
          switch ($field['type']) {
            case 'number':
            $tr .= '<input type="number" class="form-control input-xs editObjectAttr" '.$field['key'].' value="'.$value.'"></input>';
            break;
            case 'checkbox':
            $tr .= '<input type="checkbox" class="form-control input-xs editObjectAttr" '.$field['key'].' value="'.$value.'"></input>';
            break;
            default:
            $tr .= '<input class="form-control input-xs editObjectAttr" '.$field['key'].' value="'.$value.'"></input>';
            break;
          }
          $tr .= '</td>';
        }
        $tr .= '</tr>';
        echo $tr;
      }
      ?>
    </tbody>
  </table>
</div>

<script>
jeedomUtils.initDataTables(false, true, '#md_objectMassEdit')

document.getElementById('bt_saveMassEdit').addEventListener('click',function() {
  jeedom.massEditSave({
    type: jeephp2js.md_objectMassEdit_type,
    objects: document.querySelectorAll('#table_massEdit .editObject').getJeeValues('.editObjectAttr'),
    error: function(error) {
      jeedomUtils.showAlert({
        attachTo: jeeDialog.get('#md_objectMassEdit', 'dialog'),
        message: error.message,
        level: 'danger'
      })
    },
    success : function(data) {
      jeedomUtils.showAlert({
        attachTo: jeeDialog.get('#md_objectMassEdit', 'dialog'),
        message: '{{Modification sauvegardée avec succès}}',
        level: 'success'
      })
    }
  })
})
</script>