<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<center>
    <select id="mod_insertScenariocValue_value" class="form-control">
        <?php
        foreach (scenario::all() as $scenario) {
            echo '<option value="#' . $scenario->getHumanName(true) . '#" data-scenario_id="' . $scenario->getId() . '">' . $scenario->getHumanName(true) . '</option>';
        }
        ?>
    </select>
</center>
<script>
    function mod_insertScenario() {
    }

    mod_insertScenario.setOptions = function(_options) {

    }

    mod_insertScenario.getId = function() {
        return $('#mod_insertScenariocValue_value option:selected').attr('data-scenario_id');
    }

    mod_insertScenario.getValue = function() {
        return $('#mod_insertScenariocValue_value').value();
    }
</script>
