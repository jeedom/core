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
			<th style="width: 130px;">{{Objet}}</th>
			<th style="width: 150px;">{{Nom}}</th>
			<th data-sorter="false" data-filter="false">{{Affichage}}</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$html = '';
		foreach (cmd::isHistorized() as $cmd) {
			$eqLogic = $cmd->getEqLogic();
			if (!is_object($eqLogic)) {
				continue;
			}
			$object = $cmd->getEqLogic()->getObject();
			$html .= '<tr data-link_id="' . $cmd->getId() . '" data-type="graph" data-viewDataType="cmd">';
			$html .= '<td style="width: 50px;">';
			$html .= '<input type="checkbox" class="enable" />';
			$html .= '<input class="graphDataOption" data-l1key="link_id" value="' . $cmd->getId() . '" hidden/>';
			$html .= '</td>';
			$html .= '<td class="object_name">';
			$html .= '<input class="graphDataOption" data-l1key="type" value="cmd" hidden/>';
			if (is_object($object)) {
				$html .= $object->getName();
			}
			$html .= '</td>';
			$html .= '<td class="name">';
			$html .= '[' . $eqLogic->getName() . '][';
			$html .= $cmd->getName() . ']';
			$html .= '</td>';
			$html .= '<td class="display">';
			$html .= '<div class="option">';
			$html .= '<form class="form-inline">';
			$html .= '<div class="form-group">';
			$html .= '<input type="color" class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphColor" style="width : 110px;" />';
			$html .= '</div> ';
			$html .= '<div class="form-group">';
			$html .= ' <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphType" style="width : 70px;">';
			$html .= '<option value="line">{{Ligne}}</option>';
			$html .= '<option value="area">{{Aire}}</option>';
			$html .= '<option value="column">{{Colonne}}</option>';
			$html .= '<option value="pie">{{Camembert}}</option>';
			$html .= '</select> ';
			$html .= '</div> ';
			$html .= '<div class="form-group">';
			$html .= ' <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="groupingType" style="width : 140px;">';
			$html .= '<option value="">{{Aucun groupement}}</option>';
			$html .= '<option value="sum::hour">{{Somme par heure}}</option>';
			$html .= '<option value="average::hour">{{Moyenne par heure}}</option>';
			$html .= '<option value="low::hour">{{Minimum par heure}}</option>';
			$html .= '<option value="high::hour">{{Maximum par heure}}</option>';
			$html .= '<option value="sum::day">{{Somme par jour}}</option>';
			$html .= '<option value="average::day">{{Moyenne par jour}}</option>';
			$html .= '<option value="low::day">{{Minimum par jour}}</option>';
			$html .= '<option value="high::day">{{Maximum par jour}}</option>';
			$html .= '<option value="sum::week">{{Somme par semaine}}</option>';
			$html .= '<option value="average::week">{{Moyenne par semaine}}</option>';
			$html .= '<option value="low::week">{{Minimum par semaine}}</option>';
			$html .= '<option value="high::week">{{Maximum par semaine}}</option>';
			$html .= '<option value="sum::month">{{Somme par mois}}</option>';
			$html .= '<option value="average::month">{{Moyenne par mois}}</option>';
			$html .= '<option value="low::month">{{Minimum par mois}}</option>';
			$html .= '<option value="high::month">{{Maximum par mois}}</option>';
			$html .= '<option value="sum::year">{{Somme par année}}</option>';
			$html .= '<option value="average::year">{{Moyenne par année}}</option>';
			$html .= '<option value="low::year">{{Minimum par année}}</option>';
			$html .= '<option value="high::year">{{Maximum par année}}</option>';
			$html .= '</select> ';
			$html .= '</div> ';
			$html .= '<div class="form-group">';
			$html .= '<label>{{Escalier}} :</label> <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="graphStep"> ';
			$html .= '<label>{{Empiler}} :</label> <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="graphStack"> ';
			$html .= '<label>{{Variation}} :</label> <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="derive"> ';
			$html .= '<label>{{Echelle}} :</label> <select class="graphDataOption form-control input-sm" data-l1key="configuration" data-l2key="graphScale" style="width : 80px;">';
			$html .= '<option value="0">Droite</option>';
			$html .= '<option value="1">Gauche</option>';
			$html .= '</select>';
			$html .= '</div>';
			$html .= '</form>';
			$html .= '</div>';
			$html .= '</td>';
			$html .= '</tr>';
		}
		echo $html;
		?>
	</tbody>
</table>

<script>
initTableSorter();
</script>
