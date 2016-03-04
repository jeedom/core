    <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
    <div style="display: none;" id="md_cmdConfigureSelectMultipleAlert"></div>
    <div>
    	<a class="btn btn-success pull-right" id="bt_cmdConfigureSelectMultipleAlertApply" style="color : white;" ><i class="fa fa-check"></i> {{Valider}}</a>
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
    	$("#table_cmdConfigureHistory").trigger("update");
    	$("#table_cmdConfigureHistory").width('100%');

    	function addCommandHistory(_cmd){
    		var tr = '<tr data-cmd_id="' +_cmd.id+ '">';
    		tr += '<td>';
    		tr += '<input type="checkbox" class="cmdAttr" data-l1key="isHistorized" />';
    		tr += '</td>';
    		tr += '<td>';
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
    		tr += '</tr>';
    		var result = $(tr);
    		result.setValues(_cmd, '.cmdAttr');
    		return result;
    	}

    </script>