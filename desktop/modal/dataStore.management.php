<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('dataStore_type', init('type'));
sendVarToJS('dataStore_link_id', init('link_id', -1));
?>
<div style="display: none;" id="div_dataStoreManagementAlert"></div>
<a class="btn btn-sm" id="bt_dataStoreManagementAdd" style="margin-bottom: 5px;"><i class="fas fa-plus"></i> {{Ajouter}}</a>
<table id="table_dataStore" class="table table-condensed table-bordered tablesorter" style="width:100%; min-width:100%">
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
$(function() {
	initTableSorter();
	refreshDataStoreMangementTable();
	$('#table_dataStore').delegate('.bt_removeDataStore', 'click', function() {
		var tr = $(this).closest('tr');
      	if (tr.attr('data-datastore_id') == '') {
          tr.remove()
          return
        }
		bootbox.confirm('Êtes-vous sûr de vouloir supprimer la variable <span style="font-weight: bold ;">' + tr.find('.key').value() + '</span> ?', function(result) {
			if (result) {
				jeedom.dataStore.remove({
					id: tr.attr('data-dataStore_id'),
					error: function (error) {
						$('#div_dataStoreManagementAlert').showAlert({message: error.message, level: 'danger'});
					},
					success: function (data) {
						$('#div_dataStoreManagementAlert').showAlert({message: '{{Dépôt de données supprimé}}', level: 'success'});
						refreshDataStoreMangementTable();
					}
				});
			}
		});
	});

	$('#table_dataStore').delegate('.bt_saveDataStore', 'click', function() {
		var tr = $(this).closest('tr');
		jeedom.dataStore.save({
			id: tr.attr('data-dataStore_id'),
			value: tr.find('.value').value(),
			type: dataStore_type,
			key: tr.find('.key').value(),
			link_id: dataStore_link_id,
			error: function (error) {
				$('#div_dataStoreManagementAlert').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				$('#div_dataStoreManagementAlert').showAlert({message: '{{Dépôt de données sauvegardé}}', level: 'success'});
				refreshDataStoreMangementTable();
			}
		});
	});

	$('#table_dataStore').delegate('.bt_graphDataStore', 'click', function() {
		var tr = $(this).closest('tr');
		$('#md_modal2').dialog({title: "{{Graphique de lien(s)}}"});
		$("#md_modal2").load('index.php?v=d&modal=graph.link&filter_type=dataStore&filter_id='+tr.attr('data-dataStore_id')).dialog('open');
	});

	$('#bt_dataStoreManagementAdd').on('click', function() {
		var tr = '<tr data-dataStore_id="">';
		tr += '<td>';
		tr += '<input class="form-control input-sm key" value="" />';
		tr += '</td>';
		tr += '<td>';
		tr += '<input class="form-control input-sm value" value="" />';
		tr += '</td>';
		tr += '<td>';
		tr += '</td>';
		tr += '<td>';
		tr += '<a class="btn btn-success pull-right btn-sm bt_saveDataStore"><i class="fas fa-check"></i></a>';
		tr += '<a class="btn btn-danger pull-right btn-sm bt_removeDataStore"><i class="far fa-trash-alt"></i></a>';
		tr += '<a class="btn btn-default pull-right btn-sm bt_graphDataStore"><i class="fas fa-object-group"></i></a>';
		tr += '</td>';
		tr += '</tr>';
		$('#table_dataStore tbody').prepend(tr);
		$("#table_dataStore").trigger("update");
	});

	function refreshDataStoreMangementTable() {
		jeedom.dataStore.all({
			type: dataStore_type,
			usedBy : 1,
			error: function (error) {
				$('#div_dataStoreManagementAlert').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				$('#table_dataStore tbody').empty();
				var tr = '';
				for (var i in data) {
					tr += '<tr data-dataStore_id="' + data[i].id + '">';
					tr += '<td style="min-width:55px;">';
					tr += '<span style="display : none;">' + data[i].key + '</span><input class="form-control input-sm key" value="' + data[i].key + '" readonly />';
					tr += '</td>';
					tr += '<td style="min-width:90px;">';
					tr += '<span style="display : none;">' + data[i].value + '</span><input class="form-control input-sm value" value="' + data[i].value + '" />';
					tr += '</td>';
					tr += '<td>';
					for(var j in data[i].usedBy.scenario){
						tr += '<span class="label label-primary">'+data[i].usedBy.scenario[j]+'</span> ';
					}
					for(var j in data[i].usedBy.eqLogic){
						tr += '<span class="label label-primary">'+data[i].usedBy.eqLogic[j]+'</span> ';
					}
					for(var j in data[i].usedBy.cmd){
						tr += '<span class="label label-primary">'+data[i].usedBy.cmd[j]+'</span> ';
					}
					for(var j in data[i].usedBy.interactDef){
						tr += '<span class="label label-primary">'+data[i].usedBy.interactDef[j]+'</span> ';
					}
					tr += '</td>';
					tr += '<td style="min-width:120px; width:120px;">';
					tr += '<a class="btn btn-success pull-right btn-sm bt_saveDataStore"><i class="fas fa-check"></i></a>';
					tr += '<a class="btn btn-danger pull-right btn-sm bt_removeDataStore"><i class="far fa-trash-alt"></i></a>';
					tr += '<a class="btn pull-right btn-sm bt_graphDataStore"><i class="fas fa-object-group"></i></a>';
					tr += '</td>';
					tr += '</tr>';
				}
				$('#table_dataStore tbody').append(tr);
				$("#table_dataStore").trigger("update");
			}
		});
	}
});
</script>
