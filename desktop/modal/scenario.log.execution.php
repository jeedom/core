<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
    throw new Exception('{{Scenario introuvable}}');
}
$logs = $scenario->getHlogs();
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#last" role="tab" data-toggle="tab">{{Dernier}}</a></li>
    <?php
    for ($i = 0; $i < count($logs); $i++) {
        echo '<li><a href="#n' . $i . '" role="tab" data-toggle="tab">N-' . ($i + 1) . '</a></li>';
    }
    ?>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="last">
        <br/>
        <?php
        echo '<pre>' . trim($scenario->getLog()) . '</pre>';
        ?>
    </div>
    <?php
    for ($i = 0; $i < count($logs); $i++) {
        echo '<div class="tab-pane" id="n' . $i . '">';
        echo '<br/><pre>';
        echo trim($logs[$i]);
        echo '</pre>';
        echo '</div>';
    }
    ?>
</div>



