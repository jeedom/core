<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d'))),
	'end' => date('Y-m-d'),
);
?>
	<div class="row row-overflow">
		<div class="col-lg-3 col-md-4 col-sm-5 bs-sidebar">
			<ul class="nav nav-list bs-sidenav">
				<li>
					<div class="input-group input-group-sm" style="width: 100%">
						<textarea class="form-control input-sm" id='in_calculHistory' placeholder="{{Historique calculé}}" ></textarea>
						<span class="input-group-btn">
							<a class="btn btn-default" id="bt_findCmdCalculHistory" title="{{Sélectionner la commande}}"><i class="fa fa-list-alt"></i></a>
							<a class="btn btn-success" id="bt_displayCalculHistory"><i class="fa fa-check"></i></a>
							<a class="btn btn-default" id="bt_configureCalculHistory"><i class="fa fa-cogs"></i></a>
						</span>
					</div>
				</li>
			</ul>
			<ul id="ul_history" class="nav nav-list bs-sidenav">
				<li class="nav-header"><i class="icon techno-courbes3"></i> {{Historique}}
					<a id="bt_openCmdHistoryConfigure" class="btn btn-default btn-sm pull-right" style="position:relatif; top:-5px;padding: 5px 10px;"><i class="fa fa-cogs"></i> {{Configuration}}</a>
				</li>
				<li class="filter"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" /></li>
				<?php
$object_id = -1;
foreach (cmd::allHistoryCmd() as $cmd) {
	$eqLogic = $cmd->getEqLogic();
	if (!$eqLogic->hasRight('r')) {
		continue;
	}
	if ($object_id != $eqLogic->getObject_id()) {
		if ($object_id != -1) {
			echo '</div>';
		}
		$object = $eqLogic->getObject();
		if (is_object($object)) {
			if ($object->getDisplay('tagColor') != '') {
				echo '<span class="label cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="text-shadow : none;background-color:' . $object->getDisplay('tagColor') . ';color:' . $object->getDisplay('tagTextColor', 'white') . '">' . $object->getName() . ' <i class="fa fa-arrow-circle-right"></i></span>';
			} else {
				echo '<span class="label label-primary cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="text-shadow : none;">' . $object->getName() . ' <i class="fa fa-arrow-circle-right"></i></span>';
			}
		} else {
			echo '<span class="label label-default cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="text-shadow : none;">' . __('Aucun', __FILE__) . ' <i class="fa fa-arrow-circle-right"></i></span>';
		}
		echo '<br/>';
		echo '<div class="cmdList" data-object_id="o' . $eqLogic->getObject_id() . '" style="display:none;margin-left : 20px;">';
	}
	echo '<li class="cursor li_history" data-cmd_id="' . $cmd->getId() . '"><a class="history"><i class="fa fa-trash-o remove"></i> <i class="fa fa-share export"></i> ' . $cmd->getEqLogic()->getName() . ' - ' . $cmd->getName() . '</a></li>';
	$object_id = $eqLogic->getObject_id();
}
?>
			</ul>
		</div>

		<div class="col-lg-9 col-md-8 col-sm-7" style="border-left: solid 1px #EEE; padding-left: 25px;height: 600px;">
			<a class="btn btn-warning btn-sm" id='bt_clearGraph'><i class="fa fa-trash-o"></i> {{Vider}}</a>
			<input id="in_startDate" class="form-control input-sm in_datepicker" style="display : inline-block; width: 150px;position:relative;top:1px;" value="<?php echo $date['start'] ?>"/>
			<input id="in_endDate" class="form-control input-sm in_datepicker" style="display : inline-block; width: 150px;position:relative;top:1px;" value="<?php echo $date['end'] ?>"/>
			<a class="btn btn-success btn-sm" id='bt_validChangeDate' title="{{Attention une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher}}"><i class="fa fa-check"></i> {{Ok}}</a>

			<select class="form-control pull-right" id="sel_groupingType" style="width: 200px;">
				<option value="">{{Aucun groupement}}</option>
				<option value="sum::hour">{{Sommes par heure}}</option>
				<option value="average::hour">{{Moyenne par heure}}</option>
				<option value="low::hour">{{Minimum par heure}}</option>
				<option value="high::hour">{{Maximum par heure}}</option>
				<option value="sum::day">{{Sommes par jour}}</option>
				<option value="average::day">{{Moyenne par jour}}</option>
				<option value="low::day">{{Minimum par jour}}</option>
				<option value="high::day">{{Maximum par jour}}</option>
				<option value="sum::week">{{Sommes par semaine}}</option>
				<option value="average::week">{{Moyenne par semaine}}</option>
				<option value="low::week">{{Minimum par semaine}}</option>
				<option value="high::week">{{Maximum par semaine}}</option>
				<option value="sum::month">{{Sommes par mois}}</option>
				<option value="average::month">{{Moyenne par mois}}</option>
				<option value="low::month">{{Minimum par mois}}</option>
				<option value="high::month">{{Maximum par mois}}</option>
				<option value="sum::year">{{Sommes par année}}</option>
				<option value="average::year">{{Moyenne par année}}</option>
				<option value="low::year">{{Minimum par année}}</option>
				<option value="high::year">{{Maximum par année}}</option>
			</select>
			<select class="form-control pull-right" id="sel_chartType" style="width: 200px;">
				<option value="line">{{Ligne}}</option>
				<option value="areaspline">{{Areaspline}}</option>
				<option value="column">{{Barre}}</option>
			</select>
			<span class="pull-right">{{Variation}} <input type="checkbox" id="cb_derive" /></span>
			<span class="pull-right">{{Escalier}} <input type="checkbox" id="cb_step" /></span>
			<div id="div_graph" style="margin-top: 50px;height:calc(100% - 120px)"></div>
		</div>
	</div>

	<?php include_file("desktop", "history", "js");?>
