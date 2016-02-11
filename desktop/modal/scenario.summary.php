<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="div_alertScenarioSummary"></div>
<a class="btn btn-success btn-sm pull-right" id="bt_saveSummaryScenario"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
<a class="btn btn-default btn-sm pull-right" id="bt_refreshSummaryScenario"><i class="fa fa-refresh"></i> {{Rafrîchir}}</a>
<br/><br/>
<table class="table table-bordered table-condensed" id="table_scenarioSummary">
	<thead>
		<tr>
			<th>{{Scénario}}</th>
			<th>{{Statut}}</th>
			<th>{{Dernier lancement}}</th>
			<th>{{Actif}}</th>
			<th>{{Visible}}</th>
			<th>{{Enchainer les commandes}}</th>
			<th>{{Pas de log}}</th>
			<th>{{Actions}}</th>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>

<script>
	refreshScenarioSummary();

	$('#bt_refreshSummaryScenario').off().on('click',function(){
		refreshScenarioSummary();
	});

	function refreshScenarioSummary(){
		jeedom.scenario.all({
			nocache : true,
			error: function (error) {
				$('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'});
			},
			success : function(data){
				$('#table_scenarioSummary tbody').empty();
				var table = [];
				for(var i in data){
					var tr = '<tr class="scenario" data-id="' + init(data[i].id) + '">';
					tr += '<td>';
					tr += '<span class="scenarioAttr" data-l1key="id" style="display:none;"></span>';
					tr += '<span class="scenarioAttr cursor bt_summaryGotoScenario" data-l1key="humanName"></span>';
					tr += '</td>';
					tr += '<td>';
					switch (data[i].state) {
						case 'error' :
						tr += '<span class="label label-warning" style="font-size : 1em;">{{Erreur}}</span>';
						break;
						case 'on' :
						tr += '<span class="label label-info" style="font-size : 1em;">{{Actif}}</span>';
						break;
						case 'in_progress' :
						tr += '<span class="label label-success" style="font-size : 1em;">{{En cours}}</span>';
						break;
						case 'stop' :
						tr += '<span class="label label-danger" style="font-size : 1em;">{{Arrêté}}</span>';
						break;
					}
					tr += '</td>';
					tr += '<td>';
					tr += '<span class="scenarioAttr" data-l1key="lastLaunch"></span>';
					tr += '</td>';
					tr += '<td>';
					tr += '<input type="checkbox" class="scenarioAttr bootstrapSwitch" data-label-text="{{Actif}}" data-size="mini" data-l1key="isActive">';
					tr += '</td>';
					tr += '<td>';
					tr += '<input type="checkbox" class="scenarioAttr bootstrapSwitch" data-label-text="{{Visible}}" data-size="mini" data-l1key="isVisible">';
					tr += '</td>';
					tr += '<td>';
					tr += '<input type="checkbox" class="scenarioAttr bootstrapSwitch" data-l1key="configuration" data-l2key="cmdNoWait" data-size="mini">';
					tr += '</td>';
					tr += '<td>';
					tr += '<input type="checkbox" class="scenarioAttr bootstrapSwitch" data-l1key="configuration" data-l2key="noLog" data-size="mini">';
					tr += '</td>';
					tr += '<td>';
					tr += '<a class="btn btn-default tooltips btn-xs bt_summarayViewLog" title="{{Voir les logs}}"><i class="fa fa-file-o"></i></a> ';
					if(data[i].state == 'in_progress'){
						tr += '<a class="btn btn-danger tooltips btn-xs bt_sumarrayStopScenario" title="{{Exécuter}}"><i class="fa fa-stop"></i></a>';
					}else{
						tr += '<a class="btn btn-success tooltips btn-xs bt_summarayLaunchScenario" title="{{Exécuter}}"><i class="fa fa-play"></i></a>';
					}
					tr += '</td>';
					tr += '</tr>';
					var result = $(tr);
					result.setValues(data[i], '.scenarioAttr');
					table.push(result);
				}
				$('#table_scenarioSummary tbody').append(table);
				initCheckBox();

				$('.bt_summarayViewLog').off().on('click',function(){
					var tr = $(this).closest('tr');
					$('#md_modal2').dialog({title: "{{Log d'exécution du scénario}}"});
					$("#md_modal2").load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + tr.attr('data-id')).dialog('open');
				});

				$('.bt_sumarrayStopScenario').off().on('click',function(){
					var tr = $(this).closest('tr');
					jeedom.scenario.changeState({
						id: tr.attr('data-id'),
						state: 'stop',
						error: function (error) {
							$('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'});
						},
						success:function(){
							refreshScenarioSummary();
						}
					});
				});

				$('.bt_summarayLaunchScenario').off().on('click',function(){
					var tr = $(this).closest('tr');
					jeedom.scenario.changeState({
						id: tr.attr('data-id'),
						state: 'start',
						error: function (error) {
							$('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'});
						},
						success:function(){
							refreshScenarioSummary();
						}
					});
				});

				$('.bt_summaryGotoScenario').off().on('click',function(){
					var tr = $(this).closest('tr');
					window.location.href = 'index.php?v=d&p=scenarioAssist&id='+tr.attr('data-id');
				});
			}
		});
	}


	$('#bt_saveSummaryScenario').off().on('click',function(){
		var scenarios = $('#table_scenarioSummary tbody .scenario').getValues('.scenarioAttr');
		jeedom.scenario.saveAll({
			scenarios : scenarios,
			error: function (error) {
				$('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'});
			},
			success : function(data){
				refreshScenarioSummary();
			}
		});
	})
</script>