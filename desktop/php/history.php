<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d'))),
	'end' => date('Y-m-d'),
);
?>
<div id="div_alertHistory"></div>

<div id="div_historyOptions" class="row">
	<br>
	<div class="col-lg-3 col-sm-4 col-xs-12">
		<div class="input-group input-group-sm">
			<input id="in_startDate" class="form-control input-sm in_datepicker roundedLeft" style="width: 90px;" value="<?php echo $date['start'] ?>"/>
			<input id="in_endDate" class="form-control input-sm in_datepicker" style="width: 90px;" value="<?php echo $date['end'] ?>"/>
			<a class="btn btn-success btn-sm roundedRight" id='bt_validChangeDate' title="{{Attention : une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher.}}">
				<i class="fas fa-check"></i>
			</a>
		</div>
	</div>

	<div class="col-lg-8 col-sm-10 col-xs-12 pull-right">
		<div class="input-group input-group-sm" style="float: right;">
			<select class="fullCorner input-sm" id="sel_groupingType" style="width: 180px;">
				<option value="">{{Aucun groupement}}</option>
				<option value="sum::hour">{{Somme par heure}}</option>
				<option value="average::hour">{{Moyenne par heure}}</option>
				<option value="low::hour">{{Minimum par heure}}</option>
				<option value="high::hour">{{Maximum par heure}}</option>
				<option value="sum::day">{{Somme par jour}}</option>
				<option value="average::day">{{Moyenne par jour}}</option>
				<option value="low::day">{{Minimum par jour}}</option>
				<option value="high::day">{{Maximum par jour}}</option>
				<option value="sum::week">{{Somme par semaine}}</option>
				<option value="average::week">{{Moyenne par semaine}}</option>
				<option value="low::week">{{Minimum par semaine}}</option>
				<option value="high::week">{{Maximum par semaine}}</option>
				<option value="sum::month">{{Somme par mois}}</option>
				<option value="average::month">{{Moyenne par mois}}</option>
				<option value="low::month">{{Minimum par mois}}</option>
				<option value="high::month">{{Maximum par mois}}</option>
				<option value="sum::year">{{Somme par année}}</option>
				<option value="average::year">{{Moyenne par année}}</option>
				<option value="low::year">{{Minimum par année}}</option>
				<option value="high::year">{{Maximum par année}}</option>
			</select>

			<select class="fullCorner input-sm" id="sel_chartType" style="width: 100px;">
				<option value="line">{{Ligne}}</option>
				<option value="area">{{Aire}}</option>
				<option value="column">{{Barre}}</option>
			</select>

			<span>{{Variation}} <input type="checkbox" id="cb_derive" /></span>
			<span>{{Escalier}} <input type="checkbox" id="cb_step" /></span>
			<span><i class="fas fa-bullseye"></i> {{Tracking}} <input type="checkbox" id="cb_tracking" checked/></span>

			<a class="btn btn-sm btn-success" id="bt_toggleYaxis"><i class="fas fa-ruler-vertical"></i> {{yAxis}}</a>
			<a class="btn btn-sm btn-success" id="bt_compare"><i class="fas fa-greater-than-equal"></i> {{Comparer}}</a>
			<a class="btn btn-sm btn-warning" id='bt_clearGraph' title="{{Vide l'affichage des courbes sur la zone.}}" >
				<i class="fas fa-times"></i> {{Affichage}}
			</a>
		</div>
	</div>
</div>

<div class="row row-overflow">
	<div class="col-lg-3 col-md-4 col-sm-5 bs-sidebar">
		<ul class="nav nav-list bs-sidenav">
			<li>
				<div class="input-group input-group-sm">
					<textarea class="form-control roundedLeft" id='in_calculHistory' placeholder="{{Historique calculé}}" ></textarea>
					<span class="input-group-btn">
						<a class="btn btn-default" id="bt_findCmdCalculHistory" title="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i>
						</a><a class="btn btn-success" id="bt_displayCalculHistory" title="{{Afficher le graphique calculé}}"><i class="fas fa-check"></i>
						</a><a class="btn btn-default roundedRight" id="bt_configureCalculHistory" title="{{Configuration des formules de calcul}}"><i class="fas fa-cogs"></i></a>
					</span>
				</div>
			</li>
		</ul>
		<ul id="ul_history" class="nav nav-list bs-sidenav">
			<li>
				<i class="icon techno-courbes3"></i> {{Historique}}
				<a id="bt_openCmdHistoryConfigure" class="btn btn-default btn-sm pull-right" style="top:-5px;padding: 5px 10px;margin-right: 3px;"><i class="fas fa-cogs"></i> {{Configuration}}</a>
			</li>
			<li class="filter">
				<input class="filter form-control input-sm" placeholder="{{Rechercher}}" />
			</li>
			<?php
			$object_id = -1;
			$historyAll = cmd::allHistoryCmd();
			foreach ($historyAll as $cmd) {
				$eqLogic = $cmd->getEqLogic();
				if (!$eqLogic->hasRight('r')) {
					continue;
				}
				$_echo = '';
				if ($object_id != $eqLogic->getObject_id()) {
					if ($object_id != -1) {
						$_echo .= '</div>';
					}
					$object = $eqLogic->getObject();
					if (is_object($object)) {
						if ($object->getConfiguration('useCustomColor') == 1) {
							$_echo .= '<span class="label cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="background-color:' . $object->getDisplay('tagColor') . ';color:' . $object->getDisplay('tagTextColor', 'white') . '">' . $object->getName() . ' <i class="fas fa-arrow-circle-right"></i></span>';
						} else {
							$_echo .= '<span class="label cursor displayObject labelObjectHuman" data-object_id="o' . $eqLogic->getObject_id() . '">' . $object->getName() . ' <i class="fas fa-arrow-circle-right"></i></span>';
						}
					} else {
						$_echo .= '<span class="label label-default cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '>' . __('Aucun', __FILE__) . ' <i class="fas fa-arrow-circle-right"></i></span>';
					}
					$_echo .= '<br/>';
					$_echo .= '<div class="cmdList" data-object_id="o' . $eqLogic->getObject_id() . '" style="display:none;margin-left : 20px;">';
				}
				$class = 'history';
				if (!$eqLogic->getIsEnable()) $class = 'history disabled';
				$_echo .= '<li class="cursor li_history" data-cmd_id="' . $cmd->getId() . '"><a class="'.$class.'"><i class="far fa-trash-alt remove" title="{{Supprimer tout ou partie de cet historique.}}"></i> ';
				if ($cmd->getSubType() == 'string') {
					$_echo .= '<i class="fas fa-share export" title="{{Exporter cet historique.}}"></i> ';
				}
				$_echo .= $cmd->getEqLogic()->getName() . ' - ' . $cmd->getName() . '</a></li>';
				echo $_echo;
				$object_id = $eqLogic->getObject_id();
			}
			?>
		</ul>
	</div>

	<div class="col-lg-9 col-md-8 col-sm-7">
		<div id="div_graph"></div>
	</div>
</div>

<!--Compare Modal -->
<div id="md_getCompareRange" class="cleanableModal hidden" style="overflow-x: hidden;">
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<div class="form-group">
					<label class="col-xs-3 control-label">{{Comparer la période}}</label>
					<div class="col-xs-3">
						<input id="in_compareStart1" class="form-control input-sm in_datepicker" value="<?php echo $date['start'] ?>"/>
					</div>
					<div class="col-xs-3">
						<input id="in_compareEnd1" class="form-control input-sm in_datepicker" value="<?php echo $date['end'] ?>"/>
					</div>
					<div class="col-xs-3">
						<select id="sel_setPeriod" class="form-control">
							<option value="1.week">{{1 semaine}}</option>
							<option value="2.week">{{2 semaines}}</option>
							<option value="1.months">{{1 mois}}</option>
							<option value="3.months">{{3 mois}}</option>
							<option value="6.months">{{6 mois}}</option>
							<option value="1.years">{{1 an}}</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-3 control-label">{{Avec la période}}</label>
					<div class="col-xs-3">
						<input id="in_compareStart2" class="form-control input-sm in_datepicker"/>
					</div>
					<div class="col-xs-3">
						<input id="in_compareEnd2" class="form-control input-sm in_datepicker"/>
					</div>
					<div class="col-xs-3">
						<select id="sel_comparePeriod" class="form-control">
							<option value="1.week">{{1 semaine}}</option>
							<option value="2.week">{{2 semaines}}</option>
							<option value="3.week">{{3 semaines}}</option>
							<option value="4.week">{{4 semaines}}</option>
							<option value="1.months">{{1 mois}}</option>
							<option value="2.months">{{2 mois}}</option>
							<option value="3.months">{{3 mois}}</option>
							<option value="6.months">{{6 mois}}</option>
							<option value="1.years">{{1 an}}</option>
						</select>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
	<br>
	<a class="btn btn-success pull-right" id="bt_doCompare"><i class="fas fa-check"></i> {{Comparer}}</a>
</div>
<?php
include_file("desktop", "history", "js");
?>