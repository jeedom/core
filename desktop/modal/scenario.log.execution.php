<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
	throw new Exception(__('Aucun scénario correspondant à : ', __FILE__) . init('scenario_id'));
}
sendVarToJs('scenarioLog_scenario_id', init('scenario_id'));
?>
<div style="display: none;width : 100%" id="div_alertScenarioLog"></div>
<a class="btn btn-danger pull-right" id="bt_scenarioLogEmpty"><i class="fa fa-trash"></i> {{Vider les logs}}</a>
<a class="btn btn-success pull-right" id="bt_scenarioLogDownload"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
<a class="btn btn-primary pull-right" id="bt_scenarioLogRefresh"><i class="fa fa-refresh"></i> {{Rafraichir}}</a>
<br/><br/>
<?php
if (file_exists(dirname(__FILE__) . '/../../log/scenarioLog/scenario' . init('scenario_id') . '.log')) {
	echo '<pre id="pre_logScenarioDisplay">' . trim(file_get_contents(dirname(__FILE__) . '/../../log/scenarioLog/scenario' . init('scenario_id') . '.log')) . '</pre>';
} else {
	echo "{{Aucun log n'existe pour votre scénario : }}" . dirname(__FILE__) . '/../../log/scenarioLog/scenario' . init('scenario_id') . '.log.';
}
?>
<script>
    $('#bt_scenarioLogEmpty').on('click', function () {
     jeedom.scenario.emptyLog({
        id: <?php echo init('scenario_id')?>,
        error: function (error) {
            $('#div_alertScenarioLog').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alertScenarioLog').showAlert({message: '{{Log vidé avec succès}}', level: 'success'});
            $('#pre_logScenarioDisplay').empty();
        }
    });
 });

    $('#bt_scenarioLogRefresh').on('click',function(){
        $('#md_modal').load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + scenarioLog_scenario_id).dialog('open');
    });


    $('#bt_scenarioLogDownload').click(function() {
        window.open('core/php/downloadFile.php?pathfile=log/scenarioLog/scenario<?php echo init('scenario_id')?>.log', "_blank", null);
    });
</script>

