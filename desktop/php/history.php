<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'visjs/vis.min', 'css');
include_file('3rdparty', 'visjs/vis.min', 'js');
$date = array(
	'start' => date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d'))),
	'end' => date('Y-m-d'),
);
?>

<ul class="nav nav-tabs" role="tablist" style="margin-top:4px;">
	<li role="presentation" class="active"><a href="#historytab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-chart-bar"></i> {{Historique}}</a></li>
	<li role="presentation"><a id="bt_tabTimeline" href="#timelinetab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="far fa-clock"></i> {{Timeline}}</a></li>
</ul>

<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
	<div role="tabpanel" class="tab-pane active" id="historytab">
		<br/>

		<div class="row row-overflow" data-offset="70">
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
					<li class="nav-header"><i class="icon techno-courbes3"></i> {{Historique}}
						<a id="bt_openCmdHistoryConfigure" class="btn btn-default btn-sm pull-right" style="top:-5px;padding: 5px 10px;"><i class="fas fa-cogs"></i> {{Configuration}}</a>
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
								if ($object->getConfiguration('useCustomColor') == 1) {
									echo '<span class="label cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="background-color:' . $object->getDisplay('tagColor') . ';color:' . $object->getDisplay('tagTextColor', 'white') . '">' . $object->getName() . ' <i class="fas fa-arrow-circle-right"></i></span>';
								} else {
									echo '<span class="label cursor displayObject labelObjectHuman" data-object_id="o' . $eqLogic->getObject_id() . '">' . $object->getName() . ' <i class="fas fa-arrow-circle-right"></i></span>';
								}
							} else {
								echo '<span class="label label-default cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '>' . __('Aucun', __FILE__) . ' <i class="fas fa-arrow-circle-right"></i></span>';
							}
							echo '<br/>';
							echo '<div class="cmdList" data-object_id="o' . $eqLogic->getObject_id() . '" style="display:none;margin-left : 20px;">';
						}
						echo '<li class="cursor li_history" data-cmd_id="' . $cmd->getId() . '"><a class="history"><i class="far fa-trash-alt remove"></i> ';
						if ($cmd->getSubType() == 'string') {
							echo '<i class="fas fa-share export"></i> ';
						}
						echo $cmd->getEqLogic()->getName() . ' - ' . $cmd->getName() . '</a></li>';
						$object_id = $eqLogic->getObject_id();
					}
					?>
				</ul>
			</div>

			<div class="col-lg-9 col-md-8 col-sm-7">
				<div class="row">
					<div class="col-lg-4 col-sm-12 center">
						<div class="input-group input-group-sm">
							<input id="in_startDate" class="form-control input-sm in_datepicker roundedLeft" style="width: 90px;" value="<?php echo $date['start'] ?>"/>
							<input id="in_endDate" class="form-control input-sm in_datepicker" style="width: 90px;" value="<?php echo $date['end'] ?>"/>
							<a class="btn btn-success btn-sm roundedRight" id='bt_validChangeDate' title="{{Attention : une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher.}}">
								<i class="fas fa-check"></i>
							</a>
						</div>
					</div>
					<div class="col-lg-8 col-sm-12">
						<div class="input-group input-group-sm pull-right">
							<span> {{Variation}} <input type="checkbox" id="cb_derive" /></span>
							<span>{{Escalier}} <input type="checkbox" id="cb_step" /></span>
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle roundedLeft" type="button" data-toggle="dropdown" id="sel_groupingType" style="width: 180px;">
									{{Aucun groupement}}
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="">{{Aucun groupement}}</a></li>
									<li><a href="#" data-value="sum::hour">{{Somme par heure}}</a></li>
									<li><a href="#" data-value="average::hour">{{Moyenne par heure}}</a></li>
									<li><a href="#" data-value="low::hour">{{Minimum par heure}}</a></li>
									<li><a href="#" data-value="high::hour">{{Maximum par heure}}</a></li>
									<li><a href="#" data-value="sum::day">{{Somme par jour}}</a></li>
									<li><a href="#" data-value="average::day">{{Moyenne par jour}}</a></li>
									<li><a href="#" data-value="low::day">{{Minimum par jour}}</a></li>
									<li><a href="#" data-value="high::day">{{Maximum par jour}}</a></li>
									<li><a href="#" data-value="sum::week">{{Somme par semaine}}</a></li>
									<li><a href="#" data-value="average::week">{{Moyenne par semaine}}</a></li>
									<li><a href="#" data-value="low::week">{{Minimum par semaine}}</a></li>
									<li><a href="#" data-value="high::week">{{Maximum par semaine}}</a></li>
									<li><a href="#" data-value="sum::month">{{Somme par mois}}</a></li>
									<li><a href="#" data-value="average::month">{{Moyenne par mois}}</a></li>
									<li><a href="#" data-value="low::month">{{Minimum par mois}}</a></li>
									<li><a href="#" data-value="high::month">{{Maximum par mois}}</a></li>
									<li><a href="#" data-value="sum::year">{{Somme par année}}</a></li>
									<li><a href="#" data-value="average::year">{{Moyenne par année}}</a></li>
									<li><a href="#" data-value="low::year">{{Minimum par année}}</a></li>
									<li><a href="#" data-value="high::year">{{Maximum par année}}</a></li>
								</ul>
							</div>
							<div class="dropdown dynDropdown">
								<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="sel_chartType" style="width: 100px;">
									{{Ligne}}
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-right">
									<li><a href="#" data-value="line">{{Ligne}}</a></li>
									<li><a href="#" data-value="areaspline">{{Aire}}</a></li>
									<li><a href="#" data-value="column">{{Barre}}</a></li>
								</ul>
							</div>
							<a class="btn btn-warning roundedRight" id='bt_clearGraph' title="Vide l'affichage des courbes sur la zone." >
								<i class="fas fa-times"></i> {{Affichage}}
							</a>
						</div>
					</div>
				</div>
				<div id="div_graph" style="margin-top: 50px;height:calc(100% - 130px)"></div>
			</div>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane" id="timelinetab">
        <div class="input-group input-group-sm">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-success pull-right roundedRight" id="bt_refreshTimeline"><i class="fas fa-sync"></i> {{Rafraîchir}}
				</a><a id="bt_openCmdHistoryConfigure2" class="btn btn-default btn-sm pull-right roundedLeft"><i class="fas fa-cogs"></i> {{Configuration}}</a>
            </span>
        </div>
		<table id="table_timeline" class="table table-condensed table-bordered tablesorter">
			<thead>
				<tr>
					<th data-sorter="shortDate">{{Date}}</th>
					<th>{{Type}}</th>
					<th>{{Visuel}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>

	</div>
</div>

<?php include_file("desktop", "history", "js");?>
