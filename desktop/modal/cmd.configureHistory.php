    <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
   <div style="display: none;" id="md_cmdConfigureHistory"></div>
   <div>
     <a class="btn btn-success pull-right" id="bt_cmdConfigureCmdHistoryApply" style="color : white;" ><i class="fa fa-check"></i> {{Valider}}</a>
 </div>
 <br/>
 <br/>
 <table class="table table-bordered table-condensed tablesorter" id="table_cmdConfigureHistory" style="width:100%">
     <thead>
      <tr>
       <th>{{Historisé}}</th>
       <th>{{Nom}}</th>
       <th>{{Mode de lissage}}</th>
       <th>{{Purge de l'historique si plus vieux}}</th>
       <th>{{Action}}</th>
   </tr>
</thead>
<tbody>
  <?php
$list_cmd = array();
foreach (array_merge(cmd::byTypeSubType('info', 'numeric'), cmd::byTypeSubType('info', 'binary')) as $cmd) {
	$info_cmd = utils::o2a($cmd);
	$info_cmd['humanName'] = $cmd->getHumanName(true);
	$list_cmd[] = $info_cmd;
}
sendVarToJs('cmds_history_configure', $list_cmd);
?>
</tbody>
</table>

<script>
 initTableSorter();
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
  tr += '<input type="checkbox" class="cmdAttr" data-l1key="isHistorized" />';
  tr += '</td>';
  tr += '<td>';
  tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
  tr += '<span class="cmdAttr" data-l1key="humanName"></span>';
  tr += '</td>';
  tr += '<td>';
  tr += '<div class="form-group">';
  tr += '<select class="form-control cmdAttr input-sm" data-l1key="configuration" data-l2key="historizeMode">';
  tr += '<option value="avg">{{Moyenne}}</option>';
  tr += '<option value="min">{{Minimum}}</option>';
  tr += '<option value="max">{{Maximum}}</option>';
  tr += '<option value="none">{{Aucun}}</option>';
  tr += '</select>';
  tr += '</td>';
  tr += '<td>';
  tr += '<select class="form-control cmdAttr input-sm" data-l1key="configuration" data-l2key="historyPurge">';
  tr += '<option value="">{{Jamais}}</option>';
  tr += '<option value="-1 day">{{1 jour}}</option>';
  tr += '<option value="-7 days">{{7 jours}}</option>';
  tr += '<option value="-1 month">{{1 mois}}</option>';
  tr += '<option value="-6 month">{{6 mois}}</option>';
  tr += '</select>';
  tr += '</td>';
  tr += '<td>';
  tr += '<a class="btn btn-danger btn-sm pull-right cursor bt_configureHistoryEmptyCmdHistory" data-id="'  +_cmd.id+ '"><i class="fa fa-trash-o remove"></i></a>';
  tr += '<a class="btn btn-default btn-sm pull-right cursor bt_configureHistoryExportData" data-id="'  +_cmd.id+ '"><i class="fa fa-share export"></i></a>';
  tr += '<a class="btn btn-default btn-sm pull-right cursor bt_configureHistoryAdvanceCmdConfiguration" data-id="'  +_cmd.id+ '"><i class="fa fa-cogs"></i></a>';
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

$(".bt_configureHistoryEmptyCmdHistory").on('click', function () {
    var bt_remove = $(this);
    $.hideAlert();
    bootbox.prompt('{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de }} <span style="font-weight: bold ;">' + bt_remove.closest('.li_history').find('.history').text() + '</span> (laissez vide pour tout supprimer) ?', function (result) {
        if (result !== null) {
            $.ajax({
                type: "POST",
                url: "core/ajax/cmd.ajax.php",
                data: {
                    action: "emptyHistory",
                    id: bt_remove.closest('.li_history').attr('data-cmd_id'),
                    date: result
                },
                dataType: 'json',
                error: function (request, status, error) {
                    handleAjaxError(request, status, error,$('#md_cmdConfigureHistory'));
                },
                success: function (data) {
                    if (data.state != 'ok') {
                        $('#md_cmdConfigureHistory').showAlert({message: data.result, level: 'danger'});
                        return;
                    }
                    $('#md_cmdConfigureHistory').showAlert({message: '{{Historique supprimé avec succès}}', level: 'success'});
                }
            });
        }
    });
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
           $('#md_cmdConfigureHistory').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'});
       }
   });
});

</script>