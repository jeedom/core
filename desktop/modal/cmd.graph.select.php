<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<table id="table_addViewDataHidden" style="display: none;">
    <tbody></tbody>
</table>
<table class="table table-condensed table-bordered table-striped tablesorter" id="table_addViewData">
    <thead>
        <tr>
            <th style="width: 50px;">#</th>
            <th style="width: 150px;">{{Objet}}</th>
            <th style="width: 150px;">{{Nom}}</th>
            <th>{{Affichage}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach (cmd::all() as $cmd) {
	$eqLogic = $cmd->getEqLogic();
	if (!is_object($eqLogic)) {
		continue;
	}
	if ($eqLogic->getIsVisible() == 1 && $cmd->getIsHistorized() == 1) {
		$object = $cmd->getEqLogic()->getObject();
		echo '<tr data-link_id="' . $cmd->getId() . '" data-type="graph" data-viewDataType="cmd">';
		echo '<td>';
		echo '<input type="checkbox" class="enable bootstrapSwitch" data-size="mini" />';
		echo '<input class="graphDataOption" data-l1key="link_id" value="' . $cmd->getId() . '" hidden/>';
		echo '</td>';
		echo '<td class="object_name">';
		echo '<input class="graphDataOption" data-l1key="type" value="cmd" hidden/>';
		if (is_object($object)) {
			echo $object->getName();
		}
		echo '</td>';
		echo '<td class="name">';
		echo '[' . $eqLogic->getName() . '][';
		echo $cmd->getName() . ']';
		echo '</td>';
		echo '<td class="display">';
		echo '<div class="option">';
		echo '<form class="form-inline">';
		echo '<div class="form-group">';
		echo '<label>Couleur :</label> <input type="color" class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphColor" style="width : 110px;" />';
		echo '</div> ';
		echo '<div class="form-group">';
		echo ' <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphType" style="width : 100px;">';
		echo '<option value="line">{{Ligne}}</option>';
		echo '<option value="area">{{Aire}}</option>';
		echo '<option value="column">{{Colonne}}</option>';
		echo '<option value="pie">{{Camembert}}</option>';
		echo '</select> ';
		echo '</div> ';
		echo '<div class="form-group">';
		echo ' <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="groupingType" style="width : 100px;">';
		echo '<option value="">{{Aucun groupement}}</option>';
		echo '<option value="sum::day">{{Sommes par jour}}</option>';
		echo '<option value="average::day">{{Moyenne par jour}}</option>';
		echo '<option value="low::day">{{Minimum par jour}}</option>';
		echo '<option value="high::day">{{Maximum par jour}}</option>';
		echo '<option value="sum::week">{{Sommes par semaine}}</option>';
		echo '<option value="average::week">{{Moyenne par semaine}}</option>';
		echo '<option value="low::week">{{Minimum par semaine}}</option>';
		echo '<option value="high::week">{{Maximum par semaine}}</option>';
		echo '<option value="sum::month">{{Sommes par mois}}</option>';
		echo '<option value="average::month">{{Moyenne par mois}}</option>';
		echo '<option value="low::month">{{Minimum par mois}}</option>';
		echo '<option value="high::month">{{Maximum par mois}}</option>';
		echo '</select> ';
		echo '</div> ';
		echo '<div class="form-group">';
		echo ' <label><input type="checkbox" class="graphDataOption bootstrapSwitch" data-label-text="{{Escalier}}" data-size="mini" data-l1key="configuration" data-l2key="graphStep">';
		echo '</label> ';
		echo ' <label><input type="checkbox" class="graphDataOption bootstrapSwitch" data-label-text="{{Empiler}}" data-size="mini" data-l1key="configuration" data-l2key="graphStack">';
		echo '</label> ';
		echo ' <label><input type="checkbox" class="graphDataOption bootstrapSwitch" data-label-text="{{Variation}}" data-size="mini" data-l1key="configuration" data-l2key="derive">';
		echo '</label> ';
		echo ' <label>Echelle :</label> <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphScale" style="width : 60px;">';
		echo '<option value="0">Droite</option>';
		echo '<option value="1">Gauche</option>';
		echo '</select>';

		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</td>';
		echo '</tr>';
	}
}
?>
    </tbody>
</table>

<script>
    initTableSorter();
    initCheckBox();
</script>