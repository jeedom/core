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
sendVarToJS([
  'dataStore_type' => init('type'),
  'dataStore_link_id' => init('link_id', -1)
]);
?>

<div style="display: none;" id="div_dataStoreManagementAlert"></div>

<a class="btn btn-xs btn-success pull-right" id="bt_dataStoreManagementAdd" style="margin-bottom: 5px;"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
<a class="btn btn-xs pull-right" id="bt_dataStoreManagementRefresh" style="margin-bottom: 5px;"><i class="fas fa-sync-alt"></i> {{Rafraichir}}</a>
<table id="table_dataStore" class="table table-condensed table-bordered tablesorter stickyHead" style="width:100%; min-width:100%">
  <thead>
    <tr>
      <th data-sorter="input">{{Nom}}</th>
      <th data-sorter="input">{{Valeur}}</th>
      <th>{{Utilisée dans}}</th>
      <th data-sorter="false" data-filter="false">{{Action}}</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script>
  "use strict"

  var $tableDataStore = $('#table_dataStore')
  var $modal = $('#md_modal')

  $(function() {
    jeedomUtils.initTableSorter()
    refreshDataStoreMangementTable()
    $tableDataStore[0].config.widgetOptions.resizable_widths = ['150px', '150px', '', '90px']
    $tableDataStore.trigger('applyWidgets')
      .trigger('resizableReset')
      .trigger('sorton', [
        [
          [0, 0]
        ]
      ])
  })


  $tableDataStore.on({
    'click': function(event) {
      var tr = $(this).closest('tr')
      if (tr.attr('data-datastore_id') == '') {
        tr.remove()
        return
      }
      bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer la variable}}' + ' : <span style="font-weight: bold ;">' + tr.find('.key').value() + '</span> ?', function(result) {
        if (result) {
          jeedom.dataStore.remove({
            id: tr.attr('data-dataStore_id'),
            error: function(error) {
              $('#div_dataStoreManagementAlert').showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              $('#div_dataStoreManagementAlert').showAlert({
                message: '{{Dépôt de données supprimé}}',
                level: 'success'
              })
              refreshDataStoreMangementTable()
            }
          })
        }
      })
    }
  }, '.bt_removeDataStore')

  $tableDataStore.on({
    'click': function(event) {
      var tr = $(this).closest('tr');
      jeedom.dataStore.save({
        id: tr.attr('data-dataStore_id'),
        value: tr.find('.value').value(),
        type: dataStore_type,
        key: tr.find('.key').value(),
        link_id: dataStore_link_id,
        error: function(error) {
          $('#div_dataStoreManagementAlert').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#div_dataStoreManagementAlert').showAlert({
            message: '{{Dépôt de données sauvegardé}}',
            level: 'success'
          })
          refreshDataStoreMangementTable()
        }
      })
    }
  }, '.bt_saveDataStore')

  $tableDataStore.on({
    'click': function(event) {
      var tr = $(this).closest('tr')
      $('#md_modal2').dialog({
        title: "{{Graphique de lien(s)}}"
      }).load('index.php?v=d&modal=graph.link&filter_type=dataStore&filter_id=' + tr.attr('data-dataStore_id')).dialog('open')
    }
  }, '.bt_graphDataStore')

  $('#bt_dataStoreManagementAdd').on('click', function() {
    var tr = getDatastoreTr()
    $tableDataStore.find('tbody').prepend(tr)
    $tableDataStore.trigger("update")
  })

  function getDatastoreTr(_datastore = false) {
    var thisTr = ''
    thisTr += '<tr data-dataStore_id="' + (_datastore ? _datastore.id : '') + '">'
    thisTr += '<td>'
    if (_datastore) {
      thisTr += '<span style="display : none;">' + _datastore.key + '</span><input class="form-control input-sm key" value="' + _datastore.key + '" readonly />'
    } else {
      thisTr += '<input class="form-control input-sm key" value="" />'
    }
    thisTr += '</td>'

    thisTr += '<td>'
    if (_datastore) {
      try {
        thisTr += '<span style="display : none;">' + _datastore.value + '</span><input class="form-control input-sm value" value="' + _datastore.value.replaceAll('\"', '&quot;') + '" />'
      } catch {
        thisTr += '<span style="display : none;">' + _datastore.value + '</span><input class="form-control input-sm value" value="' + _datastore.value + '" />'
      }

    } else {
      thisTr += '<input class="form-control input-sm value" value="" />'
    }
    thisTr += '</td>'

    thisTr += '<td>'
    if (_datastore) {
      for (var j in _datastore.usedBy.scenario) {
        thisTr += ' <a href="' + _datastore.usedBy.scenario[j]['link'] + '&search=' + encodeURI(_datastore.key) + '" class="btn btn-xs btn-primary">' + _datastore.usedBy.scenario[j]['humanName'] + '</a>'
      }
      for (var j in _datastore.usedBy.eqLogic) {
        thisTr += ' <a href="' + _datastore.usedBy.eqLogic[j]['link'] + '" class="btn btn-xs btn-primary">' + _datastore.usedBy.eqLogic[j]['humanName'] + '</a>'
      }
      for (var j in _datastore.usedBy.cmd) {
        thisTr += ' <a href="' + _datastore.usedBy.cmd[j]['link'] + '" class="btn btn-xs btn-primary">' + _datastore.usedBy.cmd[j]['humanName'] + '</a>'
      }
      for (var j in _datastore.usedBy.interactDef) {
        thisTr += ' <a href="' + _datastore.usedBy.interactDef[j]['link'] + '" class="btn btn-xs btn-primary">' + _datastore.usedBy.interactDef[j]['humanName'] + '</a>'
      }
    }
    thisTr += '</td>'

    thisTr += '<td>'
    thisTr += '<a class="btn btn-info btn-xs bt_graphDataStore"><i class="fas fa-object-group"></i></a> '
    thisTr += '<a class="btn btn-success btn-xs bt_saveDataStore"><i class="fas fa-check"></i></a> '
    thisTr += '<a class="btn btn-danger btn-xs bt_removeDataStore"><i class="far fa-trash-alt"></i></a> '
    thisTr += '</td>'
    thisTr += '</tr>'
    return thisTr
  }

  $('#bt_dataStoreManagementRefresh').off('click').on('click', function() {
    refreshDataStoreMangementTable();
  });

  var refreshCount = 0

  function refreshDataStoreMangementTable() {
    jeedom.dataStore.all({
      type: dataStore_type,
      usedBy: 1,
      error: function(error) {
        $('#div_dataStoreManagementAlert').showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        $tableDataStore.find('tbody').empty()
        var tr = ''
        for (var i in data) {
          tr += getDatastoreTr(data[i])
        }
        if (refreshCount == 0) {
          var varHeight = data.length * 31 + 170
          $modal.dialog('open')
          if (varHeight < $modal.height()) {
            $modal.dialog({
              height: varHeight
            })
          }
        }

        $tableDataStore.find('tbody').append(tr)
        $tableDataStore.trigger("update")

        refreshCount += 1
      }
    })
  }
</script>