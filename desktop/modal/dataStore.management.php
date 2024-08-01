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

<div id="md_datastore" data-modalType="md_datastore">
  <div class="input-group">
    <div class="input-group-btn">
      <a id="bt_dataStoreManagementAdd" class="btn btn-sm btn-success roundedRight pull-right"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
      <a id="bt_dataStoreManagementRefresh" class="btn btn-sm roundedLeft pull-right"><i class="fas fa-sync-alt"></i> {{Rafraichir}}</a>
    </div>
  </div>

  <table id="table_dataStore" class="table table-condensed stickyHead">
    <thead>
      <tr>
        <th data-type="input">{{Nom}}</th>
        <th data-type="input">{{Valeur}}</th>
        <th>{{Utilisée dans}}</th>
        <th data-filter="false" style="width:100px;">{{Action}}</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<script>
if (!jeeFrontEnd.md_datastore) {
  jeeFrontEnd.md_datastore = {
    vDataTable: null,
    init: function() {
      this.tableDataStore = document.getElementById('table_dataStore')
      this.modal = this.tableDataStore.closest('div.jeeDialogMain')
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
      jeedom.dataStore.all({
        type: jeephp2js.md_dataStoreManagement_type,
        usedBy: 1,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_datastore', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          if (jeeFrontEnd.md_datastore.vDataTable) jeeFrontEnd.md_datastore.vDataTable.destroy()
          jeeFrontEnd.md_datastore.tableDataStore.tBodies[0].empty()
          var tr = ''
          for (var i in data) {
            tr += jeeFrontEnd.md_datastore.getDatastoreTr(data[i])
          }
          jeeFrontEnd.md_datastore.tableDataStore.tBodies[0].innerHTML = tr

          jeeFrontEnd.md_datastore.vDataTable = new DataTable(jeeFrontEnd.md_datastore.tableDataStore, {
            columns: [
              { select: 0, sort: "asc" },
              { select: [2,3], sortable: false }
            ],
            paging: false,
            searchable: true,
          })
          jeedomUtils.resizableTable(document.getElementById('table_dataStore'));
        }
      })
    },
  }
}

(function() {// Self Isolation!
  jeedomUtils.hideAlert()
  var jeeM = jeeFrontEnd.md_datastore
  jeeM.init()

  jeeM.refreshDataStoreMangementTable()

  //Manage events outside parents delegations:
  document.getElementById('bt_dataStoreManagementAdd')?.addEventListener('click', function(event) {
    var tr = jeeM.getDatastoreTr()
    jeeM.tableDataStore.tBodies[0].insertAdjacentHTML('afterbegin', tr)
    if (jeeFrontEnd.md_datastore.vDataTable) jeeFrontEnd.md_datastore.vDataTable.refresh()
  })

  document.getElementById('bt_dataStoreManagementRefresh')?.addEventListener('click', function(event) {
    jeeM.refreshDataStoreMangementTable()
  })
  /*Events delegations
  */
  document.getElementById('table_dataStore')?.addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_removeDataStore')) {
      var tr = _target.closest('tr')
      if (tr.getAttribute('data-datastore_id') == '') {
        tr.remove()
        if (jeeFrontEnd.md_datastore.vDataTable) jeeFrontEnd.md_datastore.vDataTable.refresh()
        return
      }
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer la variable}}' + ' : <span style="font-weight: bold ;">' + tr.querySelector('.key').value + '</span> ?', function(result) {
        if (result) {
          jeedom.dataStore.remove({
            id: tr.getAttribute('data-dataStore_id'),
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_datastore', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_datastore', 'dialog'),
                message: '{{Dépôt de données supprimé}}',
                level: 'success'
              })
              jeeM.refreshDataStoreMangementTable()
            }
          })
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_saveDataStore')) {
      var tr = _target.closest('tr');
      jeedom.dataStore.save({
        id: tr.getAttribute('data-dataStore_id'),
        value: tr.querySelector('.value').value,
        type: jeephp2js.md_dataStoreManagement_type,
        key: tr.querySelector('.key').value,
        link_id: jeephp2js.md_dataStoreManagement_linkId,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_datastore', 'dialog'),
            message: error.message,
            level: 'danger',
            ttl: 1000
          })
        },
        success: function(data) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_datastore', 'dialog'),
            message: '{{Dépôt de données sauvegardé}}',
            level: 'success'
          })
          jeeM.refreshDataStoreMangementTable()
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_graphDataStore')) {
      var trId = _target.closest('tr').getAttribute('data-dataStore_id')
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: '{{Graphique de lien(s)}}',
        contentUrl: 'index.php?v=d&modal=graph.link&filter_type=dataStore&filter_id=' + trId
      })
      return
    }
  })
})()
</script>