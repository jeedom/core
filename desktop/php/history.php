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
<style>
div.vis-item,div.vis-item-content {
	background-color: white;
	border-color: #D7D7D7;
	padding:0px !important;
	margin:0px !important;
}
</style>

<ul class="nav nav-tabs" role="tablist" style="margin-top:4px;">
	<li role="presentation" class="active"><a href="#historytab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-bar-chart-o"></i> {{Historique}}</a></li>
	<li role="presentation"><a id="bt_tabTimeline" href="#timelinetab" aria-controls="profile" role="tab" data-toggle="tab" style="padding:10px 5px !important"><i class="fas fa-clock-o"></i> {{Timeline}}</a></li>
</ul>

<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
	<div role="tabpanel" class="tab-pane active" id="historytab">
		<br/>
		
		<div class="row row-overflow" data-offset="70">
			<div class="col-lg-3 col-md-4 col-sm-5 bs-sidebar">
				<ul class="nav nav-list bs-sidenav">
					<li>
						<div class="input-group input-group-sm" style="width: 100%">
							<textarea class="form-control input-sm" id='in_calculHistory' placeholder="{{Historique calculé}}" ></textarea>
							<span class="input-group-btn">
								<a class="btn btn-default" id="bt_findCmdCalculHistory" title="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i></a>
								<a class="btn btn-success" id="bt_displayCalculHistory"><i class="fas fa-check"></i></a>
								<a class="btn btn-default" id="bt_configureCalculHistory"><i class="fas fa-cogs"></i></a>
							</span>
						</div>
					</li>
				</ul>
				<ul id="ul_history" class="nav nav-list bs-sidenav">
					<li class="nav-header"><i class="icon techno-courbes3"></i> {{Historique}}
						<a id="bt_openCmdHistoryConfigure" class="btn btn-default btn-sm pull-right" style="position:relatif; top:-5px;padding: 5px 10px;"><i class="fas fa-cogs"></i> {{Configuration}}</a>
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
									echo '<span class="label cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="text-shadow : none;background-color:' . $object->getDisplay('tagColor') . ';color:' . $object->getDisplay('tagTextColor', 'white') . '">' . $object->getName() . ' <i class="fas fa-arrow-circle-right"></i></span>';
								} else {
									echo '<span class="label label-primary cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="text-shadow : none;">' . $object->getName() . ' <i class="fas fa-arrow-circle-right"></i></span>';
								}
							} else {
								echo '<span class="label label-default cursor displayObject" data-object_id="o' . $eqLogic->getObject_id() . '" style="text-shadow : none;">' . __('Aucun', __FILE__) . ' <i class="fas fa-arrow-circle-right"></i></span>';
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
			
			<div class="col-lg-9 col-md-8 col-sm-7" style="border-left: solid 1px #EEE; padding-left: 25px;">
				<div class="row">
					<div class="col-lg-4 col-sm-12" style="margin-top:2px;">
						<center>
							<a class="btn btn-warning btn-sm" id='bt_clearGraph'><i class="far fa-trash-alt"></i> {{Vider}}</a>
							<input id="in_startDate" class="form-control input-sm in_datepicker" style="display : inline-block; width: 100px;position:relative;top:1px;" value="<?php echo $date['start'] ?>"/>
							<input id="in_endDate" class="form-control input-sm in_datepicker" style="display : inline-block; width: 100px;position:relative;top:1px;" value="<?php echo $date['end'] ?>"/>
							<a class="btn btn-success btn-sm" id='bt_validChangeDate' title="{{Attention : une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher.}}"><i class="fas fa-check"></i> {{Ok}}</a>
						</center>
					</div>
					<div class="col-lg-3 col-sm-12"  style="margin-top:2px;">
						<select class="form-control" id="sel_groupingType">
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
					</div>
					<div class="col-lg-2 col-sm-12" style="margin-top:2px;">
						<select class="form-control" id="sel_chartType">
							<option value="line">{{Ligne}}</option>
							<option value="areaspline">{{Areaspline}}</option>
							<option value="column">{{Barre}}</option>
						</select>
					</div>
					<div class="col-lg-3 col-sm-12" style="margin-top:2px;">
						<center>
							<span>{{Variation}} <input type="checkbox" id="cb_derive" /></span>
							<span>{{Escalier}} <input type="checkbox" id="cb_step" /></span>
						</center>
					</div>
				</div>
				<div id="div_graph" style="margin-top: 50px;height:calc(100% - 130px)"></div>
			</div>
		</div>
		
	</div>
	<div role="tabpanel" class="tab-pane" id="timelinetab">
		<br/>
		<div class="row form-group">
			<div class="col-sm-12">
				<div class="btn-group" role="group" aria-label="...">
					<a class="btn btn-sm btn-default bt_timelineZoom" data-zoom="h">H</a>
					<a class="btn btn-sm btn-default bt_timelineZoom" data-zoom="d">J</a>
					<a class="btn btn-sm btn-default bt_timelineZoom" data-zoom="w">S</a>
					<a class="btn btn-sm btn-default bt_timelineZoom" data-zoom="m">M</a>
					<a class="btn btn-sm btn-default bt_timelineZoom" data-zoom="y">A</a>
					<a class="btn btn-sm btn-default bt_timelineZoom" data-zoom="all">{{Tous}}</a>
				</div>
				<a class="btn btn-sm btn-success pull-right" id="bt_refreshTimeline"><i class="fas fa-refresh"></i> {{Rafraîchir}}</a>
				<a class="btn btn-sm btn-default pull-right" id="bt_configureTimelineCommand"><i class="fas fa-cogs"></i> {{Commandes}}</a>
				<a class="btn btn-sm btn-default pull-right" id="bt_configureTimelineScenario"><i class="fas fa-cogs"></i> {{Scénarios}}</a>
				<select class="form-control pull-right" id="sel_pluginsTimeline" style="width: 140px;">
					<option value="all">{{Tous (Plugins)}}</option>
					<?php
					foreach (plugin::listPlugin() as $plugin) {
						echo '<option value="' . $plugin->getId() . '">' . $plugin->getName() . '</option>';
					}
					?>
				</select>
				<select class="form-control pull-right" id="sel_categoryTimeline" style="width: 170px;">
					<option value="all">{{Tous (Catégories)}}</option>
					<?php
					foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
						echo '<option value="' . $key . '">' . $value['name'] . '</option>';
					}
					?>
				</select>
				<select class="form-control pull-right" id="sel_objectsTimeline" style="width: 140px;">
					<option value="all">{{Tous (Objets)}}</option>
					<?php
					foreach (jeeObject::all() as $object) {
						echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
					}
					?>
				</select>
				<select class="form-control pull-right" id="sel_typesTimeline" style="width: 140px;">
					<option value="all">{{Tous (Types)}}</option>
					<option value="cmd">{{Commandes}}</option>
					<option value="scenario">{{Scénarios}}</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div id="div_visualization"></div>
			</div>
		</div>
		
	</div>
</div>

<?php include_file("desktop", "history", "js");?>
