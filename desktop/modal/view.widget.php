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
			<th style="width: 150px;">{{Type}}</th>
			<th style="width: 150px;">{{Objet}}</th>
			<th style="width: 150px;">{{Nom}}</th>
			<th>Affichage</th>
		</tr>
	</thead>
	<tbody>
		<?php
foreach (cmd::all() as $cmd) {
	$eqLogic = $cmd->getEqLogic();
	if (!is_object($eqLogic)) {
		continue;
	}
	if ($cmd->getIsHistorized() == 1) {
		$object = $cmd->getEqLogic()->getObject();
		echo '<tr data-link_id="' . $cmd->getId() . '" data-type="graph" data-viewDataType="cmd">';
		echo '<td>';
		echo '<input type="checkbox" data-size="mini" class="enable bootstrapSwitch" />';
		echo '<input class="viewDataOption" data-l1key="link_id" value="' . $cmd->getId() . '" hidden/>';
		echo '</td>';
		echo '<td class="type">';
		echo 'Commande';
		echo '<input class="viewDataOption" data-l1key="type" value="cmd" hidden/>';
		echo '</td>';
		echo '<td class="object_name">';
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
		echo '<label>Couleur :</label> <input type="color" class="viewDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphColor" style="width : 110px;" />';
		echo '</div> ';
		echo '<div class="form-group">';
		echo ' <label>Type :</label> <select class="viewDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphType" style="width : 100px;">';
		echo '<option value="line">{{Ligne}}</option>';
		echo '<option value="area">{{Aire}}</option>';
		echo '<option value="column">{{Colonne}}</option>';
		echo '</select> ';
		echo '</div> ';
		echo '<div class="form-group">';
		echo ' <label>Echelle :</label> <select class="viewDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphScale" style="width : 90px;">';
		echo '<option value="0">Droite</option>';
		echo '<option value="1">Gauche</option>';
		echo '</select>';
		echo '</div>';
		echo '<div class="form-group">';
		echo ' <label><input type="checkbox" data-label-text="{{Escalier}}" data-size="mini" class="viewDataOption bootstrapSwitch" data-l1key="configuration" data-l2key="graphStep">';
		echo '</label>';
		echo ' <label><input type="checkbox" data-label-text="{{Empiler}}" data-size="mini" class="viewDataOption bootstrapSwitch" data-l1key="configuration" data-l2key="graphStack">';
		echo '</label>';
		echo ' <label><input type="checkbox" data-label-text="{{Variation}}" data-size="mini" class="viewDataOption bootstrapSwitch" data-l1key="configuration" data-l2key="derive">';
		echo '</label>';
		echo '</div>';
		echo '</form>';
		echo '</div>';
		echo '</td>';
		echo '</tr>';
	}
}

foreach (eqLogic::all() as $eqLogic) {
	$object = $eqLogic->getObject();
	echo '<tr data-link_id="' . $eqLogic->getId() . '" data-type="widget" data-viewDataType="eqLogic">';
	echo '<td>';
	echo '<input type="checkbox" data-size="mini" class="enable bootstrapSwitch" />';
	echo '<input class="viewDataOption" data-l1key="type" value="eqLogic" hidden/>';
	echo '<input class="viewDataOption" data-l1key="link_id" value="' . $eqLogic->getId() . '" hidden/>';
	echo '</td>';
	echo '<td class="type">';
	echo 'Equipement';
	echo '</td>';
	echo '<td class="object_name">';
	if (is_object($object)) {
		echo $object->getName();
	}
	echo '</td>';
	echo '<td class="name">';
	echo $eqLogic->getName();
	echo '</td>';
	echo '<td></td>';
	echo '</tr>';
}
foreach (scenario::all() as $scenario) {
	echo '<tr data-link_id="' . $scenario->getId() . '" data-type="widget" data-viewDataType="scenario">';
	echo '<td>';
	echo '<input type="checkbox" data-size="mini" class="enable bootstrapSwitch" />';
	echo '<input class="viewDataOption" data-l1key="type" value="scenario" hidden/>';
	echo '<input class="viewDataOption" data-l1key="link_id" value="' . $scenario->getId() . '" hidden/>';
	echo '</td>';
	echo '<td class="type">';
	echo 'Scénario';
	echo '</td>';
	echo '<td class="object_name">';
	$object = $scenario->getObject();
	if (is_object($object)) {
		echo $object->getName();
	} else {
		echo '{{Aucun}}';
	}
	echo '</td>';
	echo '<td class="name">';
	echo $scenario->getName();
	echo '</td>';
	echo '<td></td>';
	echo '</tr>';
}
?>
	</tbody>
</table>

<script>
	initTableSorter();
	initCheckBox();
</script>