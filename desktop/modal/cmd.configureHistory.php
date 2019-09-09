<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$count = array('history' => 0, 'timeline' => 0);
$cmds = cmd::all();
foreach ($cmds as $cmd) {
  if ($cmd->getType() == 'info' && $cmd->getIsHistorized()) {
    $count['history']++;
  }
  if ($cmd->getConfiguration('timeline::enable')) {
    $count['timeline']++;
  }
}
?>
<div style="display: none;" id="md_cmdConfigureHistory"></div>
<a class="btn btn-success btn-sm pull-right" id="bt_cmdConfigureCmdHistoryApply"><i class="fas fa-check"></i> {{Valider}}</a>
<center>
  <span class="label label-info">{{Commande(s) historisée(s) : }}<?php echo $count['history'] ?> - {{Commande(s) timeline : }}<?php echo $count['timeline'] ?></span>
</center>

<br/>
<table class="table table-bordered table-condensed tablesorter" id="table_cmdConfigureHistory">
  <thead>
    <tr>
      <th data-filter="false" data-sorter="checkbox">{{Historisé}}</th>
      <th data-filter="false" data-sorter="checkbox">{{Timeline}}
        <a class="btn btn-success btn-xs" id="bt_applytimeline" style="width:22px;"><i class="fas fa-check"></i></a>
        <a class="btn btn-danger btn-xs" id="bt_canceltimeline" style="width:22px;"><i class="fas fa-times"></i></a>
      </th>
      <th>{{Type}}</th>
      <th>{{Nom}}</th>
      <th>{{Plugin}}</th>
      <th data-sorter="select-text">{{Mode de lissage}}</th>
      <th data-sorter="select-text">{{Purge si plus vieux}}</th>
      <th data-sorter="false" data-filter="false">{{Action}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($cmds as $cmd) {
      $tr = '';
      $tr .= '<tr data-cmd_id="'.$cmd->getId(). '">';
      $tr .= '<td style="width:95px;">';
      if ($cmd->getType() == 'info') {
        $tr .= '<center><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" '.(($cmd->getIsHistorized()) ? 'checked' : '').' /></center>';
      }
      $tr .= '</td>';
      $tr .= '<td style="width:155px;">';
      $tr .= '<center><input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" '.(($cmd->getConfiguration('timeline::enable')) ? 'checked' : '').' /></center>';
      $tr .= '</td>';
      $tr .= '<td style="width:130px;">';
      $tr .= '<span class="cmdAttr">'.$cmd->getType().' / '.$cmd->getSubType().'</span>';
      $tr .= '</td>';
      $tr .= '<td>';
      $tr .= '<span class="cmdAttr" data-l1key="id" style="display:none;">'.$cmd->getId().'</span>';
      $tr .= '<span class="cmdAttr" data-l1key="humanName">'.$cmd->getHumanName().'</span>';
      $tr .= '</td>';
      $tr .= '<td style="width:100px;">';
      if(is_object($cmd->getEqLogic())){
        $tr .= '<span class="cmdAttr" data-l1key="plugins">'.$cmd->getEqLogic()->getEqType_name().'</span>';
      }
      $tr .= '</td>';
      $tr .= '<td>';
      if ($cmd->getType() == 'info' && $cmd->getSubType() == 'numeric') {
        $tr .= '<div class="form-group">';
        $tr .= '<select class="form-control cmdAttr input-sm" data-l1key="configuration" data-l2key="historizeMode">';
        $tr .= '<option value="avg" '.(($cmd->getConfiguration('historizeMode') == 'avg') ? 'selected' : '').'>{{Moyenne}}</option>';
        $tr .= '<option value="min" '.(($cmd->getConfiguration('historizeMode') == 'min') ? 'selected' : '').'>{{Minimum}}</option>';
        $tr .= '<option value="max" '.(($cmd->getConfiguration('historizeMode') == 'max') ? 'selected' : '').'>{{Maximum}}</option>';
        $tr .= '<option value="none" '.(($cmd->getConfiguration('historizeMode') == 'none') ? 'selected' : '').'>{{Aucun}}</option>';
        $tr .= '</select>';
      }
      $tr .= '</td>';
      $tr .= '<td>';
      if ($cmd->getType() == 'info') {
        $tr .= '<select class="form-control cmdAttr input-sm" data-l1key="configuration" data-l2key="historyPurge">';
        $tr .= '<option value="" '.(($cmd->getConfiguration('historyPurge') == '') ? 'selected' : '').'>{{Jamais}}</option>';
        $tr .= '<option value="-1 day" '.(($cmd->getConfiguration('historyPurge') == '-1 day') ? 'selected' : '').'>{{1 jour}}</option>';
        $tr .= '<option value="-7 days" '.(($cmd->getConfiguration('historyPurge') == '-7 days') ? 'selected' : '').'>{{7 jours}}</option>';
        $tr .= '<option value="-1 month" '.(($cmd->getConfiguration('historyPurge') == '-1 month') ? 'selected' : '').'>{{1 mois}}</option>';
        $tr .= '<option value="-3 month" '.(($cmd->getConfiguration('historyPurge') == '-3 month') ? 'selected' : '').'>{{3 mois}}</option>';
        $tr .= '<option value="-6 month" '.(($cmd->getConfiguration('historyPurge') == '-6 month') ? 'selected' : '').'>{{6 mois}}</option>';
        $tr .= '<option value="-1 year" '.(($cmd->getConfiguration('historyPurge') == '-1 year') ? 'selected' : '').'>{{1 an}}</option>';
        $tr .= '<option value="-2 years" '.(($cmd->getConfiguration('historyPurge') == '-2 years') ? 'selected' : '').'>{{2 ans}}</option>';
        $tr .= '<option value="-3 years" '.(($cmd->getConfiguration('historyPurge') == '-3 years') ? 'selected' : '').'>{{3 ans}}</option>';
        $tr .= '</select>';
      }
      $tr .= '</td>';
      $tr .= '<td style="width:90px;">';
      if ($cmd->getType() == 'info') {
        $tr .= '<a class="btn btn-default btn-sm pull-right cursor bt_configureHistoryExportData" data-id="' .$cmd->getId(). '" title="{{Exporter la commande}}"><i class="fas fa-share export"></i></a>';
      }
      $tr .= '<a class="btn btn-default btn-sm pull-right cursor bt_configureHistoryAdvanceCmdConfiguration" data-id="'  .$cmd->getId(). '" title="{{Configuration de la commande}}"><i class="fas fa-cogs"></i></a>';
      $tr .= '</td>';
      $tr .= '</tr>';
      echo $tr;
    }
    ?>
  </tbody>
</table>

<script>
initTableSorter();
$("#table_cmdConfigureHistory").tablesorter({headers:{0:{sorter:'checkbox'}}});
$('#table_cmdConfigureHistory tbody tr').attr('data-change','0');
$("#table_cmdConfigureHistory").trigger("update");
$("#table_cmdConfigureHistory").width('100%');

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
