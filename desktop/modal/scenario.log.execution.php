<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
	throw new Exception(__('Aucun scénario ne correspondant à : ', __FILE__) . init('scenario_id'));
}
sendVarToJs('scenarioLog_scenario_id', init('scenario_id'));
?>
<div style="display: none;width : 100%" id="div_alertScenarioLog"></div>
<?php echo '<span style="font-weight: bold;font-size:1.5em;">' . $scenario->getHumanName() . '</span>'; ?>
<input class="form-control pull-right" id="in_scenarioLogSearch" style="width : 200px;margin-left:5px;" placeholder="{{Rechercher}}" />
<a class="btn btn-warning pull-right" data-state="1" id="bt_scenarioLogStopStart"><i class="fas fa-pause"></i> {{Pause}}</a>
<a class="btn btn-danger pull-right" id="bt_scenarioLogEmpty"><i class="fas fa-trash"></i> {{Vider le log}}</a>
<a class="btn btn-success pull-right" id="bt_scenarioLogDownload"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}</a>
<br/><br/>
<pre id='pre_scenariolog' style='overflow: auto; height: calc(100% - 70px);width:100%;'></pre>

<script>
jeedom.log.autoupdate({
	log : 'scenarioLog/scenario'+scenarioLog_scenario_id+'.log',
	display : $('#pre_scenariolog'),
	search : $('#in_scenarioLogSearch'),
	control : $('#bt_scenarioLogStopStart'),
});

$('#bt_scenarioLogEmpty').on('click', function () {
	jeedom.scenario.emptyLog({
		id: <?php echo init('scenario_id') ?>,
		error: function (error) {
			$('#div_alertScenarioLog').showAlert({message: error.message, level: 'danger'});
		},
		success: function () {
			$('#div_alertScenarioLog').showAlert({message: '{{Log vidé avec succès}}', level: 'success'});
			$('#pre_logScenarioDisplay').empty();
		}
	});
});

$('#bt_scenarioLogDownload').click(function() {
	window.open('core/php/downloadFile.php?pathfile=log/scenarioLog/scenario<?php echo init('scenario_id') ?>.log', "_blank", null);
});
</script>
