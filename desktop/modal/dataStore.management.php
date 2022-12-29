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
  'jeephp2js.md_dataStoreManagement_type' => init('type'),
  'jeephp2js.md_dataStoreManagement_linkId' => init('link_id', -1)
]);
?>

<div style="display: none;" id="div_dataStoreManagementAlert" data-modalType="md_datastore"></div>

<div class="input-group">
  <div class="input-group-btn">
    <a id="bt_dataStoreManagementAdd" class="btn btn-sm btn-success roundedRight pull-right"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
    <a id="bt_dataStoreManagementRefresh" class="btn btn-sm roundedLeft pull-right"><i class="fas fa-sync-alt"></i> {{Rafraichir}}</a>
  </div>
</div>

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

if (!jeeFrontEnd.md_datastore) {
  jeeFrontEnd.md_datastore = {
    init: function() {
      this.$tableDataStore = $('#table_dataStore')
      this.$modal = $('#md_modal')
      this.refreshCount = 0
    },
    getDatastoreTr: function (_datastore = false) {
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
    },
    refreshDataStoreMangementTable: function() {
      self = this
      jeedom.dataStore.all({
        type: jeephp2js.md_dataStoreManagement_type,
        usedBy: 1,
        error: function(error) {
          $('#div_dataStoreManagementAlert').showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          self.$tableDataStore.find('tbody').empty()
          var tr = ''
          for (var i in data) {
            tr += self.getDatastoreTr(data[i])
          }
          if (self.refreshCount == 0 && jeedom.getPageType() != 'modaldisplay') {
            var varHeight = data.length * 31 + 220
            self.$modal.dialog('open')
            if (varHeight < self.$modal.height()) {
              self.$modal.dialog({
                height: varHeight
              })
            }
          }

          self.$tableDataStore.find('tbody').append(tr)
          self.$tableDataStore.trigger("update")
          self.refreshCount += 1
        }
      })
    },
  }
}

(function() {
  jeedomUtils.hideAlert()
  var jeeM = jeeFrontEnd.md_datastore
  jeeM.init()

  jeedomUtils.initTableSorter()
  jeeM.refreshDataStoreMangementTable()
  jeeM.$tableDataStore[0].config.widgetOptions.resizable_widths = ['150px', '150px', '', '90px']
  jeeM.$tableDataStore.trigger('applyWidgets')
    .trigger('resizableReset')
    .trigger('sorton', [
      [
        [0, 0]
      ]
    ])

  jeeM.$tableDataStore.on({
    'click': function(event) {
      var tr = this.closest('tr')
      if (tr.getAttribute('data-datastore_id') == '') {
        tr.remove()
        return
      }
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer la variable}}' + ' : <span style="font-weight: bold ;">' + tr.querySelector('.key').value + '</span> ?', function(result) {
        if (result) {
          jeedom.dataStore.remove({
            id: tr.getAttribute('data-dataStore_id'),
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
              jeeM.refreshDataStoreMangementTable()
            }
          })
        }
      })
    }
  }, '.bt_removeDataStore')

  jeeM.$tableDataStore.on({
    'click': function(event) {
      var tr = this.closest('tr');
      jeedom.dataStore.save({
        id: tr.getAttribute('data-dataStore_id'),
        value: tr.querySelector('.value').value,
        type: jeephp2js.md_dataStoreManagement_type,
        key: tr.querySelector('.key').value,
        link_id: jeephp2js.md_dataStoreManagement_linkId,
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
          jeeM.refreshDataStoreMangementTable()
        }
      })
    }
  }, '.bt_saveDataStore')

  jeeM.$tableDataStore.on({
    'click': function(event) {
      var tr = $(this).closest('tr')
      $('#md_modal2').dialog({
        title: "{{Graphique de lien(s)}}"
      }).load('index.php?v=d&modal=graph.link&filter_type=dataStore&filter_id=' + tr.attr('data-dataStore_id')).dialog('open')
    }
  }, '.bt_graphDataStore')

  $('#bt_dataStoreManagementAdd').on('click', function() {
    var tr = jeeM.getDatastoreTr()
    jeeM.$tableDataStore.find('tbody').prepend(tr)
    jeeM.$tableDataStore.trigger("update")
  })

  $('#bt_dataStoreManagementRefresh').off('click').on('click', function() {
    jeeM.refreshDataStoreMangementTable();
  })
})()
</script>