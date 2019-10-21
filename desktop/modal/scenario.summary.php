<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="div_alertScenarioSummary"></div>
<div class="input-group pull-right" style="display:inline-flex">
	<span class="input-group-btn">
		<a class="btn btn-sm roundedLeft" id="bt_refreshSummaryScenario"><i class="fas fa-refresh"></i> {{Rafraîchir}}</a><a class="btn btn-success btn-sm roundedRight" id="bt_saveSummaryScenario"><i class="far fa-check-circle"></i> {{Enregistrer}}</a>
	</span>
</div>
<br/><br/>
<table class="table table-bordered table-condensed tablesorter" id="table_scenarioSummary">
	<thead>
		<tr>
			<th>{{ID}}</th>
			<th>{{Scénario}}</th>
			<th>{{Statut}}</th>
			<th>{{Dernier lancement}}</th>
			<th data-sorter="checkbox" data-filter="false">{{Actif}}</th>
			<th data-sorter="checkbox" data-filter="false">{{Visible}}</th>
			<th data-sorter="checkbox" data-filter="false">{{Multi lancement}}</th>
			<th data-sorter="checkbox" data-filter="false">{{Mode synchrone}}</th>
			<th data-sorter="select-text">{{Log}}</th>
			<th data-sorter="checkbox" data-filter="false">{{Timeline}}</th>
			<th data-sorter="false" data-filter="false">{{Actions}}</th>
		</tr>
	</thead>
	<tbody>

	</tbody>
</table>

<script>
	initTableSorter()
	refreshScenarioSummary()
	var tableScSummary = $('#table_scenarioSummary')
	tableScSummary[0].config.widgetOptions.resizable_widths = ['40px', '', '60px', '', '50px', '60px', '130px', '130px', '', '80px', '60px']
	tableScSummary.trigger('applyWidgets')
	tableScSummary.trigger('resizableReset')
	tableScSummary.trigger('sorton', [[[1,0]]])

	$('#bt_refreshSummaryScenario').off().on('click',function() {
		refreshScenarioSummary()
	})

	function refreshScenarioSummary() {
		jeedom.scenario.all({
			nocache : true,
			error: function (error) {
				$('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'})
			},
			success : function(data){
				$('#table_scenarioSummary tbody').empty()
				var table = []
				for(var i in data){
					var tr = '<tr class="scenario" data-id="' + init(data[i].id) + '">'
					tr += '<td>'
					tr += '<span class="label label-info scenarioAttr" data-l1key="id"></span>'
					tr += '</td>'
					tr += '<td>'
					tr += '<span class="scenarioAttr cursor bt_summaryGotoScenario" data-l1key="humanName"></span>'
					tr += '</td>'
					tr += '<td style="min-width:75px">'
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
					tr += '<td style="min-width:155px">'
					tr += '<span class="scenarioAttr" data-l1key="lastLaunch"></span>'
					tr += '</td>'
					tr += '<td style="min-width:70px">'
					tr += '<center><input type="checkbox" class="scenarioAttr" data-label-text="{{Actif}}" data-l1key="isActive"></center>'
					tr += '</td>'
					tr += '<td style="min-width:78px">'
					tr += '<center><input type="checkbox" class="scenarioAttr" data-label-text="{{Visible}}" data-l1key="isVisible"></center>'
					tr += '</td>'
					tr += '<td style="min-width:140px">'
					tr += '<center><input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="allowMultiInstance"></center>'
					tr += '</td>'
					tr += '<td style="min-width:145px">'
					tr += '<center><input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="syncmode"></center>'
					tr += '</td>'
					tr += '<td style="min-width:60px">'
					tr += '<select class="scenarioAttr form-control input-sm" data-l1key="configuration" data-l2key="logmode">'
					tr += '<option value="default">{{Défaut}}</option>'
					tr += '<option value="none">{{Aucun}}</option>'
					tr += '<option value="realtime">{{Temps réel}}</option>'
					tr += '</select>'
					tr += '</td>'
					tr += '<td style="min-width:90px">'
					tr += '<center><input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="timeline::enable"></center>'
					tr += '</td>'
					tr += '<td>'
					tr += '<a class="btn btn-default tooltips btn-xs bt_summarayViewLog" title="{{Voir les logs}}"><i class="far fa-file"></i></a> '
					if(data[i].state == 'in_progress'){
						tr += '<a class="btn btn-danger tooltips btn-xs bt_sumarrayStopScenario" title="{{Exécuter}}"><i class="fas fa-stop"></i></a>'
					}else{
						tr += '<a class="btn btn-success tooltips btn-xs bt_summarayLaunchScenario" title="{{Exécuter}}"><i class="fas fa-play"></i></a>'
					}
					tr += '</td>'
					tr += '</tr>'
					var result = $(tr)
					result.setValues(data[i], '.scenarioAttr')
					table.push(result)
				}
				$('#table_scenarioSummary tbody').append(table)
				$("#table_scenarioSummary").trigger("update")

				$('.bt_summarayViewLog').off().on('click',function() {
					var tr = $(this).closest('tr')
					$('#md_modal2').dialog({title: "{{Log d'exécution du scénario}}"})
					$("#md_modal2").load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + tr.attr('data-id')).dialog('open')
				})

				$('.bt_sumarrayStopScenario').off().on('click',function() {
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

				$('.bt_summarayLaunchScenario').off().on('click',function() {
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

				$('.bt_summaryGotoScenario').off().on('click',function() {
					var tr = $(this).closest('tr')
					window.location.href = 'index.php?v=d&p=scenario&id='+tr.attr('data-id')
				})
			}
		})
	}

	$('#bt_saveSummaryScenario').off().on('click',function() {
		var scenarios = $('#table_scenarioSummary tbody .scenario').getValues('.scenarioAttr')
		jeedom.scenario.saveAll({
			scenarios : scenarios,
			error: function (error) {
				$('#div_alertScenarioSummary').showAlert({message: error.message, level: 'danger'})
			},
			success : function(data){
				refreshScenarioSummary()
			}
		})
	})
</script>
