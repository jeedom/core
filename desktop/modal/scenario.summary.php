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

<div id="md_scenarioSummary" data-modalType="md_scenarioSummary">
  <div class="input-group pull-right" style="display:inline-flex">
    <span class="input-group-btn">
      <a class="btn btn-xs roundedLeft" id="bt_refreshSummaryScenario"><i class="fas fa-sync"></i> {{Rafraîchir}}
      </a><a class="btn btn-success btn-xs roundedRight" id="bt_saveSummaryScenario"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
    </span>
  </div>
  <br/><br/>
  <table id="table_scenarioSummary" class="table table-condensed dataTable stickyHead">
    <thead>
      <tr>
        <th>{{ID}}</th>
        <th>{{Scénario}}</th>
        <th>{{Statut}}</th>
        <th data-type="date" data-format="YYYY-MM-DD hh:mm:ss">{{Dernier lancement}}</th>
        <th data-type="checkbox" data-filter="false">{{Actif}}</th>
        <th data-type="checkbox" data-filter="false">{{Visible}}</th>
        <th data-type="checkbox" data-filter="false">{{Multi}}</th>
        <th data-type="checkbox" data-filter="false">{{Sync}}</th>
        <th data-type="select-text">{{Log}}</th>
        <th data-type="checkbox" data-filter="false">{{Timeline}}</th>
        <th data-sortable="false" data-filter="false" style="width:120px">{{Actions}}</th>
      </tr>
    </thead>
    <tbody id="tbody_scenarioSummary"></tbody>
  </table>
</div>

<script>
if (!jeeFrontEnd.md_scenarioSummary) {
  jeeFrontEnd.md_scenarioSummary = {
    scDataTable: null,
    init: function() {
      this.tableScSummary = document.getElementById('table_scenarioSummary')
      jeeFrontEnd.md_scenarioSummary.scDataTable = new DataTable(this.tableScSummary, {
        columns: [
          { select: 3, sort: "desc" }
        ],
        paging: false,
        searchable: true,
      })

      this.refreshScenarioSummary()

      this.modal = this.tableScSummary.closest('div.jeeDialogMain')
    },
    synchModaleToPage: function() {
      document.querySelectorAll('#table_scenarioSummary tbody .scenario').forEach(_sc => {
        var scId = _sc.getAttribute('data-id')
        var scPage = document.querySelector('#scenarioThumbnailDisplay div.scenarioDisplayCard[data-scenario_id="' + scId + '"]')
        if (_sc.querySelector('input[data-l1key="isActive"]').checked) {
          scPage.removeClass('inactive')
        } else {
          scPage.addClass('inactive')
        }
      })
    },
    refreshScenarioSummary: function() {
      document.emptyById('tbody_scenarioSummary')
      jeedom.scenario.allOrderedByGroupObjectName({
        nocache: true,
        asTag: true,
        error: function (error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioSummary', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success : function(data) {
          var table = []
          for(var i in data){
            var tr = '<tr>'
            tr += '<td>'
            tr += '<span class="label label-info scenarioAttr" data-l1key="id"></span>'
            tr += '</td>'
            tr += '<td>'
            tr += '<span class="scenarioAttr cursor bt_summaryGotoScenario" data-l1key="humanName"></span>'
            tr += '</td>'
            tr += '<td>'
            switch (data[i].state) {
              case 'error' :
              tr += '<span class="label label-warning">{{Erreur}}</span>'
              break
              case 'on' :
              tr += '<span class="label label-info">{{Actif}}</span>'
              break
              case 'in progress' :
              tr += '<span class="label label-success">{{En cours}}</span>'
              break
              case 'stop' :
              tr += '<span class="label label-danger">{{Arrêté}}</span>'
              break
            }
            tr += '</td>'
            tr += '<td>'
            tr += data[i].lastLaunch + '<span class="scenarioAttr" data-l1key="lastLaunch" style="display:none;"></span>'
            tr += '</td>'
            tr += '<td class="center">'
            tr += '<input type="checkbox" class="scenarioAttr" data-label-text="{{Actif}}" data-l1key="isActive">'
            tr += '</td>'
            tr += '<td class="center">'
            tr += '<input type="checkbox" class="scenarioAttr" data-label-text="{{Visible}}" data-l1key="isVisible">'
            tr += '</td>'
            tr += '<td class="center">'
            tr += '<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="allowMultiInstance">'
            tr += '</td>'
            tr += '<td class="center">'
            tr += '<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="syncmode">'
            tr += '</td>'
            tr += '<td>'
            tr += '<select class="scenarioAttr form-control input-sm" data-l1key="configuration" data-l2key="logmode">'
            tr += '<option value="default">{{Défaut}}</option>'
            tr += '<option value="none">{{Aucun}}</option>'
            tr += '<option value="realtime">{{Temps réel}}</option>'
            tr += '</select>'
            tr += '</td>'
            tr += '<td>'
            tr += '<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="timeline::enable"> '
            tr += ' <input class="scenarioAttr input-sm form-control" data-l1key="configuration" data-l2key="timeline::folder" style="width:80%;display:inline-block" placeholer="{{Dossier}}"/>';
            tr += '</td>'
            tr += '<td>'
            tr += '<a class="btn btn-default tooltips btn-xs bt_summaryViewLog" title="{{Voir les logs}}"><i class="far fa-file"></i></a> '
            if (data[i].state == 'in_progress') {
              tr += '<a class="btn btn-danger tooltips btn-xs bt_summaryStopScenario" title="{{Exécuter}}"><i class="fas fa-stop"></i></a> '
            } else {
              tr += '<a class="btn btn-success tooltips btn-xs bt_summaryLaunchScenario" title="{{Exécuter}}"><i class="fas fa-play"></i></a> '
            }
            tr += '<a class="btn btn-danger tooltips btn-xs bt_summaryRemoveScenario" title="{{Supprimer ce scénario}}"><i class="far fa-trash-alt"></i></a> '
            tr += '</td>'
            tr += '</tr>'
            let newRow = document.createElement('tr')
            newRow.innerHTML = tr
            newRow.addClass('scenario')
            newRow.setAttribute('data-id', init(data[i].id))
            newRow.setJeeValues(data[i], '.scenarioAttr')
            table.push(newRow)
          }

          document.getElementById('table_scenarioSummary').querySelector('tbody').append(...table)
          jeeFrontEnd.md_scenarioSummary.scDataTable.refresh()
          jeeFrontEnd.md_scenarioSummary.scDataTable.columns().sort(3, 'desc')

          jeedom.timeline.autocompleteFolder()
        }
      })
    },
  }
}

(function() {// Self Isolation!

  jeeFrontEnd.md_scenarioSummary.init()


  //Manage events outside parents delegations:
  document.getElementById('bt_refreshSummaryScenario')?.addEventListener('click', function(event) {
    jeeFrontEnd.md_scenarioSummary.refreshScenarioSummary()
  })

  document.getElementById('bt_saveSummaryScenario')?.addEventListener('click', function(event) {
    var scenarios = document.querySelector('#table_scenarioSummary tbody .scenario').getJeeValues('.scenarioAttr')
    jeedom.scenario.saveAll({
      scenarios : scenarios,
      error: function(error) {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_scenarioSummary', 'dialog'),
          message: error.message,
          level: 'danger'
        })
      },
      success : function(data) {
        jeeFrontEnd.md_scenarioSummary.synchModaleToPage()
        jeeFrontEnd.md_scenarioSummary.refreshScenarioSummary()
      }
    })
  })
  /*Events delegations
  */
  document.getElementById('md_scenarioSummary')?.addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_summaryRemoveScenario')) {
      jeedomUtils.hideAlert()
      var id = _target.closest('tr').attr('data-id')
      var name = _target.closest('tr').querySelector('span[data-l1key="humanName"]').textContent
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer le scénario}} <span style="font-weight: bold ;">' + name + '</span> ?', function(result) {
        if (result) {
          jeedom.scenario.remove({
            id: id,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_scenarioSummary', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function() {
              document.querySelector('#table_scenarioSummary tr[data-id="' + id + '"]').remove()
              document.querySelector('.scenarioDisplayCard[data-scenario_id="' + id + '"]').remove()
            }
          })
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_summaryViewLog')) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: "{{Log d'exécution du scénario}}",
        contentUrl: 'index.php?v=d&modal=scenario.log.execution&scenario_id=' + _target.closest('tr').getAttribute('data-id')
      })
      return
    }

    if (_target = event.target.closest('.bt_summaryStopScenario')) {
      jeedom.scenario.changeState({
        id: _target.closest('tr').getAttribute('data-id'),
        state: 'stop',
        error: function (error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioSummary', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success:function(){
          jeeFrontEnd.md_scenarioSummary.refreshScenarioSummary()
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_summaryLaunchScenario')) {
      jeedom.scenario.changeState({
        id: _target.closest('tr').attr('data-id'),
        state: 'start',
        error: function (error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioSummary', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success:function() {
          jeeFrontEnd.md_scenarioSummary.refreshScenarioSummary()
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_summaryGotoScenario')) {
      window.location.href = 'index.php?v=d&p=scenario&id=' + _target.closest('tr').getAttribute('data-id')
      return
    }
  })

})()
</script>