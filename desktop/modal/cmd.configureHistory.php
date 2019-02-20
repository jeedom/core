<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$count = array('history' => 0, 'timeline' => 0);
$list_cmd = array();
foreach (cmd::all() as $cmd) {
  $info_cmd = utils::o2a($cmd);
  $info_cmd['humanName'] = $cmd->getHumanName(true);
  $eqLogic = $cmd->getEqLogic();
  $info_cmd['plugins'] = $eqLogic->getEqType_name();
  $list_cmd[] = $info_cmd;
  if ($cmd->getIsHistorized() == 1) {
    $count['history']++;
  }
  if ($cmd->getConfiguration('timeline::enable') == 1) {
    $count['timeline']++;
  }
}
sendVarToJs('cmds_history_configure', $list_cmd);
?>
<div style="display: none;" id="md_cmdConfigureHistory"></div>
<a class="btn btn-success btn-sm pull-right" id="bt_cmdConfigureCmdHistoryApply" style="color : white;" ><i class="fas fa-check"></i> {{Valider}}</a>
<center><span class="label label-info" style="font-size: 1em;">{{Commande(s) historisée(s) : }}<?php echo $count['history'] ?> - {{Commande(s) timeline : }}<?php echo $count['timeline'] ?></span></center>

<br/>
<table class="table table-bordered table-condensed tablesorter" id="table_cmdConfigureHistory" style="width:100%">
  <thead>
    <tr>
      <th data-filter="false" data-sorter="checkbox">{{Historisé}}</th>
      <th data-filter="false" data-sorter="checkbox">{{Timeline}} <a class="btn btn-danger btn-xs pull-right" id="bt_canceltimeline" style="color : white;" ><i class="fas fa-times"></i></a><a class="btn btn-success btn-xs pull-right" id="bt_applytimeline" style="color : white;" ><i class="fas fa-check"></i></a></th>
      <th>{{Type}}</th>
      <th>{{Nom}}</th>
      <th>{{Plugin}}</th>
      <th data-sorter="select-text">{{Mode de lissage}}</th>
      <th data-sorter="select-text">{{Purge de l'historique si plus vieux}}</th>
      <th data-sorter="false" data-filter="false">{{Action}}</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

<script>
initTableSorter();
$("#table_cmdConfigureHistory").tablesorter({headers:{0:{sorter:'checkbox'}}});
table_history = [];
for (var i in cmds_history_configure) {
  table_history.push(addCommandHistory(cmds_history_configure[i]));
}
$('#table_cmdConfigureHistory tbody').empty().append(table_history);
$('#table_cmdConfigureHistory tbody tr').attr('data-change','0');
$("#table_cmdConfigureHistory").trigger("update");
$("#table_cmdConfigureHistory").width('100%');

function addCommandHistory(_cmd){
  var tr = '<tr data-cmd_id="' +_cmd.id+ '">';
  tr += '<td>';
  if(_cmd.type == 'info'){
    tr += '<input type="checkbox" class="cmdAttr" data-l1key="isHistorized" />';
  }
  tr += '</td>';
  tr += '<td style="width:160px;">';
  tr += '<input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" />';
  tr += '</td>';
  tr += '<td>';
  tr += '<span class="cmdAttr">'+_cmd.type+' / '+_cmd.subType+'</span>';
  tr += '</td>';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
  tr += '<span class="cmdAttr" data-l1key="humanName"></span>';
  tr += '</td>';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="plugins"></span>';
  tr += '</td>';
  tr += '<td>';
  if(_cmd.type == 'info' && _cmd.subType == 'numeric'){
    tr += '<div class="form-group">';
    tr += '<select class="form-control cmdAttr input-sm" data-l1key="configuration" data-l2key="historizeMode">';
    tr += '<option value="avg">{{Moyenne}}</option>';
    tr += '<option value="min">{{Minimum}}</option>';
    tr += '<option value="max">{{Maximum}}</option>';
    tr += '<option value="none">{{Aucun}}</option>';
    tr += '</select>';
  }
  tr += '</td>';
  tr += '<td>';
  if(_cmd.type == 'info'){
    tr += '<select class="form-control cmdAttr input-sm" data-l1key="configuration" data-l2key="historyPurge">';
    tr += '<option value="">{{Jamais}}</option>';
    tr += '<option value="-1 day">{{1 jour}}</option>';
    tr += '<option value="-7 days">{{7 jours}}</option>';
    tr += '<option value="-1 month">{{1 mois}}</option>';
    tr += '<option value="-3 month">{{3 mois}}</option>';
    tr += '<option value="-6 month">{{6 mois}}</option>';
    tr += '<option value="-1 year">{{1 an}}</option>';
    tr += '</select>';
  }
  tr += '</td>';
  tr += '<td>';
  if(_cmd.type == 'info'){
    tr += '<a class="btn btn-default btn-sm pull-right cursor bt_configureHistoryExportData" data-id="'  +_cmd.id+ '"><i class="fas fa-share export"></i></a>';
  }
  tr += '<a class="btn btn-default btn-sm pull-right cursor bt_configureHistoryAdvanceCmdConfiguration" data-id="'  +_cmd.id+ '"><i class="fas fa-cogs"></i></a>';
  tr += '</td>';
  tr += '</tr>';
  var result = $(tr);
  result.setValues(_cmd, '.cmdAttr');
  return result;
}


$('.bt_configureHistoryAdvanceCmdConfiguration').off('click').on('click', function () {
  $('#md_modal2').dialog({title: "{{Configuration de la commande}}"});
  $('#md_modal2').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open');
});

$(".bt_configureHistoryExportData").on('click', function () {
  window.open('core/php/export.php?type=cmdHistory&id=' + $(this).attr('data-id'), "_blank", null);
});

$('.cmdAttr').on('change click',function(){
  $(this).closest('tr').attr('data-change','1');
});

$('#bt_cmdConfigureCmdHistoryApply').on('click',function(){
  var cmds = [];
  $('#table_cmdConfigureHistory tbody tr').each(function(){
    if($(this).attr('data-change') == '1'){
      cmds.push($(this).getValues('.cmdAttr')[0]);
    }
  })
  jeedom.cmd.multiSave({
    cmds : cmds,
    error: function (error) {
      $('#md_cmdConfigureHistory').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $("#table_cmdConfigureHistory").trigger("update");
      $('#md_cmdConfigureHistory').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'});
    }
  });
});

$('#bt_canceltimeline').on('click',function(){
  $('.cmdAttr[data-l1key=configuration][data-l2key="timeline::enable"]:visible').each(function(){
    $(this).prop('checked', false);
    $(this).closest('tr').attr('data-change','1');
  });
});

$('#bt_applytimeline').on('click',function(){
  $('.cmdAttr[data-l1key=configuration][data-l2key="timeline::enable"]:visible').each(function(){
    $(this).prop('checked', true);
    $(this).closest('tr').attr('data-change','1');
  });
});

</script>
