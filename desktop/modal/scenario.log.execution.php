<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div style="display: none;width : 100%" id="div_alertScenarioLog"></div>
<a class="btn btn-danger pull-right" id="bt_scenarioLogEmpty"><i class="fa fa-trash"></i> Vider les logs</a>
<br/><br/>
<?php
if (file_exists(dirname(__FILE__) . '/../../log/scenarioLog/scenario' . init('scenario_id') . '.log')) {
    echo '<pre id="pre_logScenarioDisplay">' . trim(file_get_contents(dirname(__FILE__) . '/../../log/scenarioLog/scenario' . init('scenario_id') . '.log')) . '</pre>';
} else {
    echo "Aucun log n'éxiste pour votre scénario : " . dirname(__FILE__) . '/../../log/scenarioLog/scenario' . init('scenario_id') . '.log.';
}
?>

<script>
    $('#bt_scenarioLogEmpty').on('click', function () {
        jeedom.scenario.emptyLog({
            id: <?php echo init('scenario_id') ?>,
            error: function (error) {
                $('#div_alertScenarioLog').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $('#div_alertScenarioLog').showAlert({message: 'Log vidé avec succès', level: 'success'});
                $('#pre_logScenarioDisplay').empty();
            }
        });
    });
</script>

