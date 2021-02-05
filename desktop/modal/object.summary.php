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
?>

<div id="div_alertObjectSummary"></div>
<a class="btn btn-success pull-right btn-sm" id="bt_saveSummaryObject"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
<table id="table_ObjectSummary" class="table table-bordered table-condensed tablesorter stickyHead">
  <thead>
    <tr>
      <th>{{ID}}</th>
      <th>{{Objet}}</th>
      <th>{{Parent}}</th>
      <th data-sorter="false" data-filter="false">{{Visible}}</th>
      <th data-sorter="false" data-filter="false">{{Masquer}}<br>{{sur le Dashboard}}</th>
      <th data-sorter="false" data-filter="false">{{Masquer}}<br>{{sur la Synthèse}}</th>
      <th data-sorter="false" data-filter="false">{{Résumé Défini}} <sup><i class="fas fa-question-circle tooltips" title="{{Si grisé, alors il n'est pas remonté en résumé global}}"></i></sup></th>
      <th data-sorter="false" data-filter="false">{{Résumé}}<br>{{Dashboard masqué}}</th>
      <th data-sorter="false" data-filter="false">{{Résumé}}<br>{{Mobile masqué}}</th>
      <th data-sorter="false" data-filter="false">{{Options}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $allObject = jeeObject::buildTree(null, false);
    foreach ($allObject as $object) {
      $html = '';
      $html .= '<tr class="tr_object" data-object_id="' . $object->getId() . '" data-object_name="' . $object->getHumanName() . '"><td style="width:40px;"><span class="label label-info">' . $object->getId() . '</span></td>';

      $html .= '<td style="width:auto;">';

      $html .= str_repeat('&nbsp;&nbsp;&nbsp;', $object->getConfiguration('parentNumber'));
      $html .= '<input class="objectAttr hidden" data-l1key="id" value="'.$object->getId().'"/>';
      $html .= '<span>'.$object->getHumanName(true, true).'</span>';
      $html .= '</td>';
      $father = $object->getFather();
      if ($father) {
        $html .= '<td style="width:50px;"><span>' . $father->getHumanName(true, true) . '</span></td>';
      } else {
        $html .= '<td><span class="label label-info"></span></td>';
      }

      if ($object->getIsVisible()) {
        $html .= '<td align="center" style="width:65px;"><input type="checkbox" class="objectAttr" checked data-l1key="isVisible" /></td>';
      } else {
        $html .= '<td align="center" style="width:75px;"><input type="checkbox" class="objectAttr" data-l1key="isVisible" /></td>';
      }
      if ($object->getConfiguration("hideOnDashboard", 0) == 1) {
        $html .= '<td align="center" style="width:140px;"><input type="checkbox" class="objectAttr" checked data-l1key="configuration" data-l2key="hideOnDashboard" /></td>';
      } else {
        $html .= '<td align="center" style="width:140px;"><input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="hideOnDashboard" /></td>';
      }
      if ($object->getConfiguration("hideOnOverview", 0) == 1) {
        $html .= '<td align="center" style="width:140px;"><input type="checkbox" class="objectAttr" checked data-l1key="configuration" data-l2key="hideOnOverview" /></td>';
      } else {
        $html .= '<td align="center" style="width:140px;"><input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="hideOnOverview" /></td>';
      }
      $html .= '<td>';
      foreach ((config::byKey('object:summary')) as $key => $value) {
        $title = '';
        if (!isset($object->getConfiguration('summary')[$key]) || !is_array($object->getConfiguration('summary')[$key]) || count($object->getConfiguration('summary')[$key]) == 0) {
          continue;
        }
        foreach (($object->getConfiguration('summary')[$key]) as $summary) {
          if (cmd::byId(str_replace('#', '', $summary['cmd']))) {
            $title .= '&#10;' . cmd::byId(str_replace('#', '', $summary['cmd']))->getHumanName();
          } else {
            $title .= '&#10;' . $summary['cmd'];
          }
        }
        if (count($object->getConfiguration('summary')[$key]) > 0) {
          if ($object->getConfiguration('summary::global::' . $key) == 1) {
            $html .= '<a title="' . $value['name'] . $title . '">' . $value['icon'] . '<sup> ' . count($object->getConfiguration('summary')[$key]) . '</sup></a>  ';
          } else {
            $html .= '<a class="disabled" title="' . $value['name'] . $title . '">' . $value['icon'] . '<sup> ' . count($object->getConfiguration('summary')[$key]) . '</sup></a>  ';
          }
        }
      }
      $html .= '</td>';

      $html .= '<td>';
      foreach ((config::byKey('object:summary')) as $key => $value) {
        if ($object->getConfiguration('summary::hide::desktop::' . $key) == 1) {
          $html .= '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '</a>  ';
        }
      }
      $html .= '</td>';

      $html .= '<td>';
      foreach ((config::byKey('object:summary')) as $key => $value) {
        if ($object->getConfiguration('summary::hide::mobile::' . $key) == 1) {
          $html .= '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '</a>  ';
        }
      }
      $html .= '</td>';

      $html .= '<td>';
      $html .= '<a class="btn btn-danger pull-right btn-xs bt_removeObject"><i class="far fa-trash-alt"></i></a>';
      $html .= '</td>';
      echo $html;
    }
    ?>
  </tbody>
</table>

<script>
jeedomUtils.initTableSorter()

$('#table_ObjectSummary .bt_removeObject').on('click', function(event) {
  $.hideAlert()
  var id = $(this).closest('tr.tr_object').attr('data-object_id')
  var name = $(this).closest('tr.tr_object').attr('data-object_name')
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer l\'objet}} <span style="font-weight: bold ;">' + name + '</span> ?', function(result) {
    if (result) {
      jeedom.object.remove({
        id: id,
        error: function(error) {
          $('#div_alertObjectSummary').showAlert({message: error.message, level: 'danger'})
        },
        success: function() {
          $('#table_ObjectSummary tr.tr_object[data-object_id="'+id+'"]').remove()
          $('.objectDisplayCard[data-object_id="'+id+'"]').remove()
        }
      })
    }
  })
  return false
})

$("#table_ObjectSummary").sortable({
  axis: "y",
  cursor: "move",
  items: ".tr_object",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true,
  stop: function(event, ui) {
    var objects = [];
    $('#table_ObjectSummary .tr_object').each(function () {
      objects.push($(this).attr('data-object_id'))
    });
    jeedom.object.setOrder({
      objects: objects,
      error: function(error) {
        $('#div_alertObjectSummary').showAlert({message: error.message, level: 'danger'})
      }
    })
  }
})

$('#bt_saveSummaryObject').off('click').on('click',function() {
  jeedom.massEditSave({
    type : 'jeeObject',
    objects : $('#table_ObjectSummary .tr_object').getValues('.objectAttr'),
    error: function(error) {
      $('#div_alertObjectSummary').showAlert({message: error.message, level: 'danger'})
    },
    success : function(data) {
      $('#div_alertObjectSummary').showAlert({message: '{{Modification sauvegardées avec succès}}', level: 'success'})

      //update object page list:
      var $objectContainer = $('#objectPanel .objectListContainer')
      var objectId
      $('#table_ObjectSummary tr.tr_object').each(function() {
        objectId = $(this).attr('data-object_id')
        $objectContainer.append($objectContainer.find('.objectDisplayCard[data-object_id="'+objectId+'"]'))
      })
    }
  })
})
</script>