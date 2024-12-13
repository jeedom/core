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

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$selectObject = (isset($_GET['object'])) ? $_GET['object'] : false;
$typeFilter = (isset($_GET['type'])) ? $_GET['type'] : 'info';
$displayNone = (isset($_GET['none'])) ? $_GET['none'] : true;

?>

<table id="table_mod_insertGenericType" class="table table-condensed">
  <thead>
    <tr>
      <th>{{Type Générique}} <?php if ($typeFilter != 'all') { echo "($typeFilter)"; } ?></th>
      <?php if ($selectObject) { echo '<th>{{Objet}}</th>'; } ?>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="mod_insertGenericType_name" style="width: auto;">
        <select class='form-control'>
          <?php
            $types = config::getGenericTypes(false);

            $display = '';
            if ($displayNone) {
              $display .= '<option value="">{{Aucun}}</option>';
            }

            $groups = array();
            foreach (($types['byType']) as $key => $info) {
              if ($typeFilter != 'all' && strtolower($typeFilter) != strtolower($info['type'])) {
                continue;
              }
              $info['key'] = $key;
              if ($typeFilter == 'all') {
                $info['name'] .= ' | ' . $info['type'];
              }
              if (!isset($groups[$info['family']])) {
                $groups[$info['family']][0] = $info;
              } else {
                array_push($groups[$info['family']], $info);
              }
            }
            ksort($groups);
            $optgroup = '';
            foreach ($groups as $group) {
              usort($group, function ($a, $b) {
                return strcmp($a['name'], $b['name']);
              });
              foreach ($group as $key => $info) {
                if ($key == 0) {
                  $optgroup .= '<optgroup label="' . $info['family'] . '">';
                }
                $name = $info['name'];
                $optgroup .= '<option value="' . $info['key'] . '">' . $name . '</option>';
              }
              $optgroup .= '</optgroup>';
            }
            if ($optgroup != '') $display .= $optgroup;
            echo $display;
          ?>
        </select>
      </td>
      <td class="mod_insertGenericType_object" style="width: auto;">
        <?php
          if ($selectObject) {
            $select = '<select class="form-control">';
            $objects = jeeObject::all();
            $select .= '<option value="-1">{{Tous}}</option>';
            foreach ($objects as $object) {
              $select .= '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
            }
            $select .= '</select>';
            echo $select;
          }
        ?>
      </td>
    </tr>
  </tbody>
</table>

<script>
(function() {// Self Isolation!
  if (window.mod_insertGenericType == undefined) {
    window.mod_insertGenericType = function() {}
  }
  mod_insertGenericType.setOptions = function(_options) {}
  mod_insertGenericType.getValue = function() {
    var genericType = document.querySelector('#table_mod_insertGenericType tbody td.mod_insertGenericType_name select')?.selectedOptions
    if (!genericType || genericType.length == 0) {
      return ''
    }
    var genericObject = document.querySelector('#table_mod_insertGenericType tbody td.mod_insertGenericType_object select')?.selectedOptions
    if (!genericObject || genericObject.length == 0) {
      return 'genericType(' + genericType[0].value + ')'
    }
    if (genericObject[0].value == '-1') {
      return 'genericType(' + genericType[0].value + ')'
    }
    return 'genericType(' + genericType[0].value + ',#[' + genericObject[0].text + ']#)'
  }

  mod_insertGenericType.getId = function() {
    return document.querySelector('#table_mod_insertGenericType tbody td.mod_insertGenericType_name select').value || null
  }
})()
</script>
