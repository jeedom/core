<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$scenario = scenario::byId(init('id'));
if (!is_object($scenario)) {
	throw new Exception(__('Aucun scénario ne correspondant à : ', __FILE__) . init('id'));
}
sendVarToJs('scenarioJsonEdit_scenario_id', init('id'));
?>
<div id="div_alertScenarioJsonEdit"></div>
<a class="btn btn-success btn-sm pull-right" id="bt_saveSummaryScenario"><i class="far fa-check-circle"></i> {{Enregistrer}}</a>
<br/><br/>
<textarea id="ta_scenarioJsonEdit_scenario">
	<?php
$json = array();
foreach ($scenario->getElement() as $element) {
	$json[] = $element->getAjaxElement();
}
echo json_encode($json, JSON_PRETTY_PRINT);
?>
</textarea>

<script type="text/javascript">
	fileEditor = CodeMirror.fromTextArea(document.getElementById("ta_scenarioJsonEdit_scenario"), {
		lineNumbers: true,
		mode: 'application/json',
		matchBrackets: true
	});
	fileEditor.getWrapperElement().style.height = ($('#ta_scenarioJsonEdit_scenario').closest('.ui-dialog-content').height() - 90) + 'px';
	fileEditor.refresh();

	$('#bt_saveSummaryScenario').on('click',function(){
		$.hideAlert();
		if(fileEditor == undefined){
			$('#div_alertScenarioJsonEdit').showAlert({message: '{{Erreur editeur non défini}}', level: 'danger'});
			return;
		}
		try {
			JSON.parse(fileEditor.getValue());
		} catch (e) {
			$('#div_alertScenarioJsonEdit').showAlert({message: '{{Champs json invalide}}', level: 'danger'});
			return;
		}
		var scenario = {
			id : scenarioJsonEdit_scenario_id,
			elements : json_decode(fileEditor.getValue())
		};
		jeedom.scenario.save({
			scenario: scenario,
			error: function (error) {
				$('#div_alertScenarioJsonEdit').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				$('#div_alertScenarioJsonEdit').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
				if (typeof printScenario === "function") {
					printScenario(scenarioJsonEdit_scenario_id);
				}
			}
		});
	})
</script>