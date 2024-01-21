<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => init('startDate', date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d')))),
	'end' => init('endDate', date('Y-m-d')),
);
?>
<div id="div_historyOptions" class="row">
	<br>
	<div class="col-lg-3 col-sm-4 col-xs-12">
		<div class="input-group input-group-sm">
			<input id="in_startDate" class="form-control input-sm in_datepicker roundedLeft" style="width: 90px;" value="<?php echo $date['start'] ?>" />
			<input id="in_endDate" class="form-control input-sm in_datepicker" style="width: 90px;" value="<?php echo $date['end'] ?>" />
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
			<a id="bt_compare" class="btn btn-sm btn-success disabled roundedLeft"><i class="fas fa-greater-than-equal"></i> {{Comparer}}
			</a><a id="bt_clearGraph" class="btn btn-sm btn-warning roundedRight" title="{{Vide l'affichage des courbes sur la zone.}}">
				<i class="fas fa-times"></i> {{Affichage}}
			</a>
		</div>
	</div>
</div>

<div id="pageContainer" class="row row-overflow">
	<div id="sidebar" class="col-lg-3 col-md-4 col-sm-5 bs-sidebar" style="height: calc(100vh - 110px);">
		<ul class="nav nav-list bs-sidenav">
			<li>
				<i class="fas fa-square-root-alt"></i> {{Historique calculé}}
				<a id="bt_configureCalculHistory" class="btn btn-default btn-sm pull-right" style="top: -5px; padding: 5px 10px; margin-right: 0;" title="{{Configuration des formules de calcul}}"><i class="fas fa-cogs"></i> {{Configuration}}</a>
			</li>
			<li>
				<div class="input-group input-group-sm" style="margin-top: 10px;">
					<textarea id="in_calculHistory" class="form-control roundedLeft" placeholder="{{Historique calculé}}" style="height: 17px; font-size: 12px!important;"></textarea>
					<span class="input-group-btn">
						<a id="bt_findCmdCalculHistory" class="btn btn-default" title="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i>
						</a><a id="bt_displayCalculHistory" class="btn btn-success roundedRight" title="{{Afficher le graphique calculé}}"><i class="fas fa-check"></i></a>
					</span>
				</div>
			</li>
			<div id="historyCalculs"></div>
		</ul>

		<ul id="ul_history" class="nav nav-list bs-sidenav">
			<li>
				<i class="icon techno-courbes3"></i> {{Commandes}}
				<a id="bt_openCmdHistoryConfigure" class="btn btn-default btn-sm pull-right" style="top: -5px; padding: 5px 10px; margin-right: 0;" title="{{Configuration de l'historique des commandes}}"><i class="fas fa-cogs"></i> {{Configuration}}</a>
			</li>
			<li class="filter input-group input-group-sm" style="margin-top: 10px; /*width: 98%;*/">
				<input id="in_searchHistory" class="filter form-control input-sm roundedLeft" style="width: calc(100% - 28px);" placeholder="{{Rechercher}}" autocomplete="off" />
				<span class="input-group-btn ">
					<a id="bt_resetSearch" class="btn btn-default roundedRight" title="{{Vider le champ de recherche}}"><i class="fas fa-times"></i></a>
				</span>
			</li>
			<?php
			//cmds by objects:
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
				$_echo .= '<li class="cursor li_history" data-cmd_id="' . $cmd->getId() . '"><a class="' . $class . '"><i class="far fa-trash-alt remove" title="{{Supprimer tout ou partie de cet historique.}}"></i> ';
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
		<div id="div_graph" class="chartContainer"></div>
	</div>
</div>

<!--Compare Modal -->
<div id="md_getCompareRange-template" class="hidden" style="overflow-x: hidden;">
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group">
				<div class="form-group">
					<label class="col-xs-3 control-label">{{Comparer la période}}</label>
					<div class="col-xs-3">
						<input id="in_compareStart1" class="form-control input-sm in_datepicker" value="<?php echo $date['start'] ?>" />
					</div>
					<div class="col-xs-3">
						<input id="in_compareEnd1" class="form-control input-sm in_datepicker" value="<?php echo $date['end'] ?>" />
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
						<input id="in_compareStart2" class="form-control input-sm in_datepicker" />
					</div>
					<div class="col-xs-3">
						<input id="in_compareEnd2" class="form-control input-sm in_datepicker" />
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

					<div class="form-group">
						<div class="col-xs-12">
							<span class="spanCompareDiffResult"></span><br />
							<span class="spanCompareDiff warning" style="display: none;">{{Attention: les deux périodes ne sont pas identiques (tous les mois n'ont pas le même nombre de jours)}}</span>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
	<br>
</div>
<?php
include_file("desktop", "history", "js");
?>
