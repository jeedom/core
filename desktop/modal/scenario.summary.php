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

<div id="div_alertScenarioSummary"></div>
<div class="input-group pull-right" style="display:inline-flex">
  <span class="input-group-btn">
    <a class="btn btn-xs roundedLeft" id="bt_refreshSummaryScenario"><i class="fas fa-refresh"></i> {{Rafraîchir}}
    </a><a class="btn btn-success btn-xs roundedRight" id="bt_saveSummaryScenario"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
  </span>
</div>
<br/><br/>
<table id="table_scenarioSummary" class="table table-bordered table-condensed tablesorter stickyHead">
  <thead>
    <tr>
      <th>{{ID}}</th>
      <th>{{Scénario}}</th>
      <th>{{Statut}}</th>
      <th>{{Dernier lancement}}</th>
      <th data-sorter="checkbox" data-filter="false">{{Actif}}</th>
      <th data-sorter="checkbox" data-filter="false">{{Visible}}</th>
      <th data-sorter="checkbox" data-filter="false">{{Multi}}</th>
      <th data-sorter="checkbox" data-filter="false">{{Sync}}</th>
      <th data-sorter="select-text">{{Log}}</th>
      <th data-sorter="checkbox" data-filter="false">{{Timeline}}</th>
      <th data-sorter="false" data-filter="false">{{Actions}}</th>
    </tr>
  </thead>
  <tbody id="tbody_scenarioSummary">

  </tbody>
</table>

<script>
var $tableScSummary = $('#table_scenarioSummary')
jeedomUtils.initTableSorter()
refreshScenarioSummary()
$tableScSummary[0].config.widgetOptions.resizable_widths = ['40px', '', '70px', '170px', '62px', '80px', '70px', '70px', '90px', '155px', '85px']
$tableScSummary.trigger('applyWidgets')
  .trigger('resizableReset')
  .trigger('sorton', [[[1,0]]])

$('#bt_refreshSummaryScenario').off().on('click', function() {
  refreshScenarioSummary()
})

$('#bt_saveSummaryScenario').off().on('click', function() {
  var scenarios = $('#table_scenarioSummary tbody .scenario').getValues('.scenarioAttr')
  jeedom.scenario.saveAll({
    scenarios : scenarios,
    error: function(error) {
      $('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'})
    },
    success : function(data) {
      synchModaleToPage()
      refreshScenarioSummary()
    }
  })
})

function synchModaleToPage() {
  $('#table_scenarioSummary tbody .scenario').each(function() {
    var scId = $(this).attr('data-id')
    var scPage = $('#scenarioThumbnailDisplay div.scenarioDisplayCard[data-scenario_id="'+scId+'"]')
    if ($(this).find('input[data-l1key="isActive"]').is(':checked')) {
      scPage.removeClass('inactive')
    } else {
      scPage.addClass('inactive')
    }
  })
}

function refreshScenarioSummary() {
  $.clearDivContent('tbody_scenarioSummary')
  jeedom.scenario.allOrderedByGroupObjectName({
    nocache: true,
    asTag: true,
    error: function (error) {
      $('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'})
    },
    success : function(data) {
      var table = []
      for(var i in data){
        var tr = '<tr class="scenario" data-id="' + init(data[i].id) + '">'
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
        tr += '<span class="scenarioAttr" data-l1key="lastLaunch"></span>'
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
        var result = $(tr)
        result.setValues(data[i], '.scenarioAttr')
        table.push(result)
      }

      $tableScSummary.find('tbody').append(table)
      $tableScSummary.trigger("update")

      jeedom.timeline.autocompleteFolder()

      $('#table_scenarioSummary .bt_summaryRemoveScenario').on('click', function(event) {
        $.hideAlert()
        var id = $(this).closest('tr').attr('data-id')
        var name = $(this).closest('tr').find('span[data-l1key="humanName"]').text()
        bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer le scénario}} <span style="font-weight: bold ;">' + name + '</span> ?', function(result) {
          if (result) {
            jeedom.scenario.remove({
              id: id,
              error: function(error) {
                $('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'})
              },
              success: function() {
                $('#table_scenarioSummary tr[data-id="'+id+'"]').remove()
                $('.scenarioDisplayCard[data-scenario_id="'+id+'"]').remove()
              }
            })
          }
        })
        return false
      })

      $('.bt_summaryViewLog').off().on('click', function() {
        var tr = $(this).closest('tr')
        $('#md_modal2').dialog({title: "{{Log d'exécution du scénario}}"}).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + tr.attr('data-id')).dialog('open')
      })

      $('.bt_summaryStopScenario').off().on('click', function() {
        var tr = $(this).closest('tr')
        jeedom.scenario.changeState({
          id: tr.attr('data-id'),
          state: 'stop',
          error: function (error) {
            $('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'})
          },
          success:function(){
            refreshScenarioSummary()
          }
        })
      })

      $('.bt_summaryLaunchScenario').off().on('click', function() {
        var tr = $(this).closest('tr')
        jeedom.scenario.changeState({
          id: tr.attr('data-id'),
          state: 'start',
          error: function (error) {
            $('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'})
          },
          success:function(){
            refreshScenarioSummary()
          }
        })
      })

      $('.bt_summaryGotoScenario').off().on('click', function() {
        var tr = $(this).closest('tr')
        window.location.href = 'index.php?v=d&p=scenario&id='+tr.attr('data-id')
      })

      setTimeout(function() {
        $tableScSummary.closest('.ui-dialog').resize()
      }, 500)
    }
  })
}
</script>