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
$count = array('history' => config::getHistorizedCmdNum(), 'timeline' => config::getTimelinedCmdNum());
$cmds = cmd::all();
sendVarToJs('CMDNUM', count($cmds));

//Tablesorter parser:
include_file('3rdparty', 'jquery.tablesorter/parsers/parser-input-select.min', 'js');
//Tablesorter pager plugin:
include_file('3rdparty', 'jquery.tablesorter/extras/jquery.tablesorter.pager.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/_jeedom/pager-custom-constrols', 'js');
?>

<div style="display: none;" id="md_cmdConfigureHistory"></div>
<a class="btn btn-success btn-xs pull-right" id="bt_cmdConfigureCmdHistoryApply"><i class="fas fa-check"></i> {{Valider}}</a>
<div class="center">
  <span class="label label-info">{{Commandes :}} <?php echo ' '.count($cmds) ?> | {{Commandes historisées :}}<?php echo ' ' . $count['history'] ?> | {{Commandes timeline :}}<?php echo ' ' . $count['timeline'] ?></span>
</div>
<br/>

<table id="table_cmdConfigureHistory" class="table table-bordered table-condensed tablesorter stickyHead">
  <thead>
    <tr>
      <td colspan="9" data-sorter="false" data-filter="false">
        <div class="pager">
          <nav class="left" style="float:left;">
            <a href="#" class="current">15</a> |
            <a href="#">25</a> |
            <a href="#">50</a> |
            <a href="#">100</a>
             {{par page}}
          </nav>
          <nav class="right" style="float:right;">
            <span class="prev cursor"><i class="fas fa-arrow-left"></i></span>
            <span class="pagecount"></span>
            <span class="next cursor"><i class="fas fa-arrow-right"></i></span>
          </nav>
        </div>
      </td>
    </tr>
    <tr>
      <th>{{Nom}}</th>
      <th>{{Plugin}}</th>
      <th>{{Type}}</th>
      <th data-filter="false" data-sorter="checkbox">{{Historisé}}</th>
      <th data-filter="false" data-sorter="checkbox">{{Timeline}}
        <a class="btn btn-success btn-xs" id="bt_applytimeline" style="width:22px;"><i class="fas fa-check"></i></a>
        <a class="btn btn-danger btn-xs" id="bt_canceltimeline" style="width:22px;"><i class="fas fa-times"></i></a>
      </th>
      <th data-filter="false" data-sorter="checkbox">{{Inversée}}</th>
      <th data-sorter="select-text">{{Lissage}}</th>
      <th class="extractor-select sorter-purges">{{Purge}}</th>
      <th data-sorter="false" data-filter="false">{{Action}}</th>
    </tr>

  </thead>
  <tbody>
    <?php
    $tr = '';
    $i = 0;
    foreach ($cmds as $cmd) {
      if ($i <= 15) {
        $tr .= '<tr data-change="0" data-cmd_id="'.$cmd->getId(). '">';
      } else {
        $tr .= '<tr data-change="0" data-cmd_id="'.$cmd->getId(). '" style="display: none;">';
      }
      $i++;

      //humanName:
      $tr .= '<td>';
      $tr .= '<span class="cmdAttr" data-l1key="humanName">'.str_replace('<br/>', '', $cmd->getHumanName(true, true)).'</span>';
      $tr .= '<span class="cmdAttr" data-l1key="id" style="display:none;">'.$cmd->getId().'</span>';
      $tr .= '</td>';

      //plugin:
      $tr .= '<td>';
      if (is_object($cmd->getEqLogic())) {
        $tr .= '<span class="cmdAttr" data-l1key="plugins">'.$cmd->getEqLogic()->getEqType_name().'</span>';
      }
      $tr .= '</td>';

      //type / subType:
      $tr .= '<td>';
      $tr .= '<span class="cmdAttr">'.$cmd->getType().' / '.$cmd->getSubType().'</span>';
      $tr .= '</td>';

      //historized:
      $tr .= '<td class="center">';
      if ($cmd->getType() == 'info') {
        $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="isHistorized" '.(($cmd->getIsHistorized()) ? 'checked' : '').' />';
      }
      $tr .= '</td>';

      //timeline:
      $tr .= '<td>';
      $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" '.(($cmd->getConfiguration('timeline::enable')) ? 'checked' : '').' />';
      $tr .= ' <input class="cmdAttr input-xs form-control" data-l1key="configuration" data-l2key="timeline::folder" value="'.$cmd->getConfiguration('timeline::folder').'" style="width:80%;display:inline-block" placeholer="{{Dossier}}"/>';
      $tr .= '</td>';

      //Invert:
      $tr .= '<td class="center">';
      if ($cmd->getType() == 'info' && $cmd->getSubType() == 'binary') {
        $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"'.(($cmd->getDisplay('invertBinary') == 1) ? 'checked' : '').' />';
      }
      $tr .= '</td>';

      //historizeMode
      $tr .= '<td>';
      if ($cmd->getType() == 'info' && $cmd->getSubType() == 'numeric') {
        $confHistorized = $cmd->getConfiguration('historizeMode');
        $tr .= '<div class="form-group">';
        $tr .= '<select class="form-control cmdAttr input-xs" data-l1key="configuration" data-l2key="historizeMode">';
        $tr .= '<option value="avg" '.(($confHistorized == 'avg') ? 'selected' : '').'>{{Moyenne}}</option>';
        $tr .= '<option value="min" '.(($confHistorized == 'min') ? 'selected' : '').'>{{Minimum}}</option>';
        $tr .= '<option value="max" '.(($confHistorized == 'max') ? 'selected' : '').'>{{Maximum}}</option>';
        $tr .= '<option value="none" '.(($confHistorized == 'none') ? 'selected' : '').'>{{Aucun}}</option>';
        $tr .= '</select>';
      }
      $tr .= '</td>';

      //historyPurge
      $tr .= '<td>';
      if ($cmd->getType() == 'info') {
        $confHistoryPurge = $cmd->getConfiguration('historyPurge');
        $tr .= '<select class="form-control cmdAttr input-xs" data-l1key="configuration" data-l2key="historyPurge">';
        $tr .= '<option value="-1 day" '.(($confHistoryPurge == '-1 day') ? 'selected' : '').'>{{1 jour}}</option>';
        $tr .= '<option value="-7 days" '.(($confHistoryPurge == '-7 days') ? 'selected' : '').'>{{7 jours}}</option>';
        $tr .= '<option value="-1 month" '.(($confHistoryPurge == '-1 month') ? 'selected' : '').'>{{1 mois}}</option>';
        $tr .= '<option value="-3 month" '.(($confHistoryPurge == '-3 month') ? 'selected' : '').'>{{3 mois}}</option>';
        $tr .= '<option value="-6 month" '.(($confHistoryPurge == '-6 month') ? 'selected' : '').'>{{6 mois}}</option>';
        $tr .= '<option value="-1 year" '.(($confHistoryPurge == '-1 year') ? 'selected' : '').'>{{1 an}}</option>';
        $tr .= '<option value="-2 years" '.(($confHistoryPurge == '-2 years') ? 'selected' : '').'>{{2 ans}}</option>';
        $tr .= '<option value="-3 years" '.(($confHistoryPurge == '-3 years') ? 'selected' : '').'>{{3 ans}}</option>';
        $tr .= '<option value="" '.(($confHistoryPurge == '') ? 'selected' : '').'>{{Jamais}}</option>';
        $tr .= '</select>';
      }
      $tr .= '</td>';

      //Actions:
      $tr .= '<td>';
      $tr .= '<a class="btn btn-default btn-xs pull-right cursor bt_configureHistoryAdvanceCmdConfiguration" data-id="'  .$cmd->getId(). '" title="{{Configuration de la commande}}"><i class="fas fa-cogs"></i></a>';
      if ($cmd->getType() == 'info') {
        $tr .= '<a class="btn btn-default btn-xs pull-right cursor bt_configureHistoryExportData" data-id="' .$cmd->getId(). '" title="{{Exporter la commande}}"><i class="fas fa-share export"></i></a>';
      }
      $tr .= '</td>';
      $tr .= '</tr>';
    }
    print $tr;
    ?>
  </tbody>
</table>

<script>
var $tableCmdConfigureHistory = $("#table_cmdConfigureHistory")

$('#bt_cmdConfigureCmdHistoryApply').on('click',function() {
  var cmds = []
  $tableCmdConfigureHistory.find('tbody tr').each(function() {
    if ($(this).attr('data-change') == '1') {
      cmds.push($(this).getValues('.cmdAttr')[0])
    }
  })

  jeedom.cmd.multiSave({
    cmds : cmds,
    error: function(error) {
      $('#md_cmdConfigureHistory').showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      $tableCmdConfigureHistory.trigger("update")
      $('#md_cmdConfigureHistory').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'})
    }
  })
})

$('.bt_configureHistoryAdvanceCmdConfiguration').off('click').on('click', function() {
  $('#md_modal2').dialog({title: "{{Configuration de la commande}}"}).load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open')
})

$(".bt_configureHistoryExportData").on('click', function() {
  window.open('core/php/export.php?type=cmdHistory&id=' + $(this).attr('data-id'), "_blank", null)
})

$('.cmdAttr').on('change click', function() {
  $(this).closest('tr').attr('data-change', '1')
})

$('#bt_canceltimeline').on('click', function() {
  $('.cmdAttr[data-l1key=configuration][data-l2key="timeline::enable"]:visible').each(function() {
    $(this).prop('checked', false)
    $(this).closest('tr').attr('data-change','1')
  })
})

$('#bt_applytimeline').on('click', function() {
  $('.cmdAttr[data-l1key=configuration][data-l2key="timeline::enable"]:visible').each(function() {
    $(this).prop('checked', true)
    $(this).closest('tr').attr('data-change','1')
  })
})

$('select[data-l2key="historyPurge"]').on('change', function(){
  $tableCmdConfigureHistory.trigger('updateCell', [$(this).parent()])
})

function setTableParser() {
  $.tablesorter.addParser({
    id: 'purges',
    is: function() {
      return false
    },
    format: function(s) {
      if (s == '') return 100000
      if (s == '-1 day') return 1
      if (s == '-7 days') return 7
      if (s == '-1 month') return 30
      if (s == '-3 month') return 90
      if (s == '-6 month') return 180
      if (s == '-1 year') return 365
      if (s == '-2 years') return 730
      if (s == '-3 years') return 1095
    },
    type: 'numeric'
  })
}

$(function() {
  jeedomUtils.initTableSorter()
  $tableCmdConfigureHistory[0].config.widgetOptions.resizable_widths = ['', '120px', '115px', '120px', '160px', '90px', '120px', '130px', '95px']
  $tableCmdConfigureHistory.trigger('resizableReset')
  $tableCmdConfigureHistory.width('100%')
  setTableParser()

  // initialize pager:
  var $pager = $('.pager')
  $.tablesorter.customPagerControls({
    table          : $tableCmdConfigureHistory,   // point at correct table (string or jQuery object)
    pager          : $pager,                      // pager wrapper (string or jQuery object)
    pageSize       : '.left a',                   // container for page sizes
    currentPage    : '.right a',                  // container for page selectors
    ends           : 2,                           // number of pages to show of either end
    aroundCurrent  : 1,                           // number of pages surrounding the current page
    link           : '<a href="#">{page}</a>',    // page element; use {page} to include the page number
    currentClass   : 'current',                   // current page class name
    adjacentSpacer : '<span> | </span>',          // spacer for page numbers next to each other
    distanceSpacer : '<span> &#133; <span>',      // spacer for page numbers away from each other (ellipsis = &#133;)
    addKeyboard    : true,                        // use left,right,up,down,pageUp,pageDown,home, or end to change current page
    pageKeyStep    : 10,                          // page step to use for pageUp and pageDown
  })
  $tableCmdConfigureHistory.tablesorterPager({
    container: $pager,
    size: 15,
    savePages: false,
    page: 0,
    pageReset: 0,
    removeRows: false,
    countChildRows: false,
    output: 'showing: {startRow} to {endRow} ({filteredRows})'
  })


  if (CMDNUM < 500) {
    jeedomUtils.initTooltips($tableCmdConfigureHistory)
  }
  if (CMDNUM < 1000) {
    jeedom.timeline.autocompleteFolder()
  }
  setTimeout(function() {
    $tableCmdConfigureHistory.closest('.ui-dialog').resize()
  }, 500)
})

</script>