<?php
global $JEEDOM_INTERNAL_CONFIG;
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$list = array();
$plugins = array();
$battery = array();
$objects = array();
foreach (object::all() as $object) {
	foreach ($object->getEqLogic() as $eqLogic) {
		$battery_type = str_replace(array('(', ')'), array('', ''), $eqLogic->getConfiguration('battery_type', ''));
		if ($eqLogic->getStatus('battery', -2) != -2) {
			array_push($list, $eqLogic);
			array_push($plugins, $eqLogic->getEqType_name());
			array_push($objects, $eqLogic->getobject()->getName());
			if ($battery_type != '') {
				if (strpos($battery_type, ' ') === false) {
					array_push($battery, $battery_type);
				} else {
					array_push($battery, substr(strrchr($battery_type, " "), 1));
				}
			}
		}
	}
}
usort($list, function ($a, $b) {
	return ($a->getStatus('battery') < $b->getStatus('battery')) ? -1 : (($a->getStatus('battery') > $b->getStatus('battery')) ? 1 : 0);
});
sort($plugins);
sort($battery);
?>
<br/>
<ul class="nav nav-tabs" role="tablist" id="ul_tabBatteryAlert">
	<li role="presentation" class="active batteries"><a href="#battery" aria-controls="battery" role="tab" data-toggle="tab"><i class="fa fa-battery-full"></i> {{Batteries}}</a></li>
	<li role="presentation" class="alerts"><a href="#alertEqlogic" aria-controls="alertEqlogic" role="tab" data-toggle="tab"><i class="fa fa-exclamation-triangle"></i> {{Modules en alerte}}</a></li>
	<li role="presentation"><a href="#actionCmd" aria-controls="actionCmd" role="tab" data-toggle="tab"><i class="fa fa-gears"></i> {{Actions définies}}</a></li>
	<li role="presentation"><a href="#alertCmd" aria-controls="actionCmd" role="tab" data-toggle="tab"><i class="fa fa-bell"></i> {{Alertes définies}}</a></li>
	<li role="presentation"><a href="#deadCmd" aria-controls="actionCmd" role="tab" data-toggle="tab"><i class="fa fa-snapchat-ghost"></i> {{Commandes orphelines}}</a></li>
</ul>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="battery">
		<br/>
		<div class="batteryListContainer">
			<?php
foreach ($list as $eqLogic) {
	$color = '#2ecc71';
	$level = 'good';
	$battery = $eqLogic->getConfiguration('battery_type', 'none');
	if (strpos($battery, ' ') !== false) {
		$battery = substr(strrchr($battery, " "), 1);
	}
	$plugins = $eqLogic->getEqType_name();
	$objets = str_replace(array(' ', '(', ')'), array('_', '', ''), $eqLogic->getobject()->getName());
	if ($eqLogic->getStatus('battery') <= $eqLogic->getConfiguration('battery_danger_threshold', config::byKey('battery::danger'))) {
		$color = '#e74c3c';
		$level = 'critical';
	} else if ($eqLogic->getStatus('battery') <= $eqLogic->getConfiguration('battery_warning_threshold', config::byKey('battery::warning'))) {
		$color = '#f1c40f';
		$level = 'warning';
	}
	$classAttr = $level . ' ' . $battery . ' ' . $plugins . ' ' . $objets;
	$idAttr = $level . '__' . $battery . '__' . $plugins . '__' . $objets;
	echo '<div class="eqLogic eqLogic-widget ' . $classAttr . '" style="min-width:80px;background-color:' . $color . '" id="' . $idAttr . '">';
	echo '<div class="widget-name" style="text-align : center;"><a href="' . $eqLogic->getLinkToConfiguration() . '" style="font-size : 1.5em;">' . $eqLogic->getName() . '</a><br/><span style="font-size: 0.95em;position:relative;top:-5px;cursor:default;">' . $eqLogic->getobject()->getName() . '</span></div>';
	echo '<div style="text-align : center;"><span style="font-size:2.2em;font-weight: bold;cursor:default;">' . $eqLogic->getStatus('battery', -2) . '</span><span>%</span></div>';
	echo '<div style="text-align : center; cursor:default;">{{Le }}' . $eqLogic->getStatus('batteryDatetime', __('inconnue', __FILE__)) . '</div>';
	if ($eqLogic->getConfiguration('battery_type', '') != '') {
		echo '<span class="pull-right" style="font-size : 0.8em;margin-bottom: 3px;margin-right: 5px;cursor:default;" title="Piles">' . $eqLogic->getConfiguration('battery_type', '') . '</span>';
	}
	echo '<span class="pull-left" style="font-size : 0.8em;margin-bottom: 3px;margin-left: 5px;cursor:default;" title="Plugin">' . ucfirst($eqLogic->getEqType_name()) . '</span>';
	if ($eqLogic->getConfiguration('battery_danger_threshold') != '' || $eqLogic->getConfiguration('battery_warning_threshold') != '') {
		echo '<i class="icon techno-fingerprint41 pull-right" style="position:absolute;bottom: 3px;right: 3px;cursor:default;" title="Seuil manuel défini"></i>';
	}
	echo '</div>';
}
echo '</div>';
?>
		</div>
		<div role="tabpanel" class="tab-pane" id="alertEqlogic">
			<br/>
			<div class="alertListContainer">
				<?php
$hasAlert = '';
foreach (eqLogic::all() as $eqLogic) {
	if ($eqLogic->getAlert() == '') {
		continue;
	}
	$hasAlert = 1;
	echo $eqLogic->toHtml('dashboard');
}
if ($hasAlert == '') {
	echo '<br/><div class="alert alert-success">{{Aucun module en Alerte pour le moment}}</div>';
}
?>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="actionCmd">
			<br/>
			<div class="cmdListContainer">
				<table class="table table-condensed tablesorter" id="table_Action">
					<thead>
						<tr>
							<th>{{Equipement}}</th>
							<th>{{Commande}}</th>
							<th>{{Type}}</th>
							<th>{{Actions}}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
foreach (eqLogic::all() as $eqLogic) {
	foreach ($eqLogic->getCmd('info') as $cmd) {
		if (count($cmd->getConfiguration('actionCheckCmd', array())) > 0) {
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Action sur état}}</td>';
			echo '<td>Si ' . $cmd->getConfiguration('jeedomCheckCmdOperator') . ' ' . $cmd->getConfiguration('jeedomCheckCmdTest') . ' {{plus de}} ' . $cmd->getConfiguration('jeedomCheckCmdTime') . ' {{minutes alors}} : ';
			$actions = '';
			foreach ($cmd->getConfiguration('actionCheckCmd') as $actionCmd) {
				$actions .= scenarioExpression::humanAction($actionCmd) . '|';
			}
			echo trim($actions, '|');
			echo '</td>';
			echo '<td>';
			echo '<a class="btn btn-default btn-xs cmdAction expertModeVisible pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fa fa-cogs"></i></a>';
			echo '</td>';
			echo '</tr>';
		}
	}
	foreach ($eqLogic->getCmd('action') as $cmd) {
		if (count($cmd->getConfiguration('jeedomPreExecCmd', array())) > 0) {
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Pre exécution}}</td><td>';
			$actions = '';
			foreach ($cmd->getConfiguration('jeedomPreExecCmd') as $actionCmd) {
				$actions .= scenarioExpression::humanAction($actionCmd) . '|';
			}
			echo trim($actions, '|');
			echo '</td>';
			echo '<td>';
			echo '<a class="btn btn-default btn-xs cmdAction expertModeVisible pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fa fa-cogs"></i></a>';
			echo '</td>';
			echo '</tr>';
		}
		if (count($cmd->getConfiguration('jeedomPostExecCmd', array())) > 0) {
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Post exécution}}</td><td>';
			$actions = '';
			foreach ($cmd->getConfiguration('jeedomPostExecCmd') as $actionCmd) {
				$actions .= scenarioExpression::humanAction($actionCmd) . '|';
			}
			echo trim($actions, '|');
			echo '</td>';
			echo '<td>';
			echo '<a class="btn btn-default btn-xs cmdAction expertModeVisible pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fa fa-cogs"></i></a>';
			echo '</td>';
			echo '</tr>';
		}
		if ($cmd->getConfiguration('actionConfirm')) {
			$code ='';
			if ($cmd->getConfiguration('actionCodeAccess')){
				$code = ' avec code';
			}
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Confirmation}}' . $code . '</td><td>';
			echo 'Confirmation de l\'action' . $code;
			echo '</td>';
			echo '<td>';
			echo '<a class="btn btn-default btn-xs cmdAction expertModeVisible pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fa fa-cogs"></i></a>';
			echo '</td>';
			echo '</tr>';
		}
		if ($cmd->getConfiguration('actionCodeAccess') && !$cmd->getConfiguration('actionConfirm')) {
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Confirmation}}' . $code . '</td><td>';
			echo 'Code de confirmation de l\'action';
			echo '</td>';
			echo '<td>';
			echo '<a class="btn btn-default btn-xs cmdAction expertModeVisible pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fa fa-cogs"></i></a>';
			echo '</td>';
			echo '</tr>';
		}
	}
}
?>
					</tbody>
				</table>
			</div>
		</div>
		
			<div role="tabpanel" class="tab-pane" id="alertCmd">
			<br/>
			<div class="cmdListContainer">
				<table class="table table-condensed tablesorter" id="table_deadCmd">
					<thead>
						<tr>
							<th>{{Equipement}}</th>
							<th>{{Alertes}}</th>
							<th>{{Timeout}}</th>
							<th>{{Seuils batterie}}</th>
						</tr>
					</thead>
					<tbody>
						<?php
foreach (eqLogic::all() as $eqLogic) {
	$hasSomeAlerts = 0;
	$listCmds = array();
	foreach ($eqLogic->getCmd('info') as $cmd) {
		foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
			if (!$value['check']) {
				continue;
			}
			if ($cmd->getAlert($level . 'if','') != ''){
				$hasSomeAlerts += 1;
				if (!in_array($cmd, $listCmds)){
					$listCmds[]=$cmd;
				}
			}
		}
	}
	if ($eqLogic->getConfiguration('battery_warning_threshold','') != ''){
		$hasSomeAlerts += 1;
	}
	if ($eqLogic->getConfiguration('battery_danger_threshold','') != ''){
		$hasSomeAlerts += 1;
	}
	if ($eqLogic->getTimeout('')){
		$hasSomeAlerts += 1;
	}
	if ($hasSomeAlerts != 0){
		echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
		echo '<td>';
		foreach ($listCmds as $cmdalert){
			foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
				if (!$value['check']) {
					continue;
				}
				if ($cmdalert->getAlert($level . 'if','') != ''){
					$during = $cmdalert->getAlert($level . 'during','') == '' ? ' effet immédiat' : ' pendant plus de ' .$cmdalert->getAlert($level . 'during','') . ' minute(s)';
					echo ucfirst($level) . ' si ' . jeedom::toHumanReadable(str_replace('#value#', '<b>' . $cmdalert->getName() . ' (' . $cmdalert->getId() . ') </b>',$cmdalert->getAlert($level . 'if',''))) . $during . '</br>';
				}
			}
		}
		echo '</td>';
		echo '<td>';
		if ($eqLogic->getTimeout('') != ''){
			echo $eqLogic->getTimeout('') . ' minute(s)';
		}
		echo '</td>';
		echo '<td>';
		if ($eqLogic->getConfiguration('battery_danger_threshold','') != ''){
			echo '<label class="col-xs-6 eqLogicAttr label label-danger" style="font-size : 0.8em">{{Danger}} ' .$eqLogic->getConfiguration('battery_danger_threshold','') . ' % </label>';
		}
		if ($eqLogic->getConfiguration('battery_warning_threshold','') != ''){
			echo '<label class="col-xs-6 label label-warning" style="font-size : 0.8em;">{{Warning}} ' .$eqLogic->getConfiguration('battery_warning_threshold','') . ' % </label>';
		}
		echo '</td></tr>';
	}
}
?>
					</tbody>
				</table>
			</div>
		</div>
	
			<div role="tabpanel" class="tab-pane" id="deadCmd">
			<br/>
			<div class="cmdListContainer">
				<table class="table table-condensed tablesorter" id="table_deadCmd">
					<thead>
						<tr>
							<th>{{Type}}</th>
							<th>{{Détail}}</th>
							<th>{{Commande}}</th>
							<th>{{Utilisation}}</th>
						</tr>
					</thead>
					<tbody>
						<?php

foreach (jeedom::deadCmd() as $datas) {
	echo '<tr>';
	echo '<td>Core</td>';
	echo '<td>' . $datas['detail'] . '</td>';
	echo '<td>' . $datas['who'] . '</td>';
	echo '<td>' . $datas['help'] . '</td>';
	echo '</tr>';
}
foreach (cmd::deadCmd() as $datas) {
	echo '<tr>';
	echo '<td>Commande</td>';
	echo '<td>' . $datas['detail'] . '</td>';
	echo '<td>' . $datas['who'] . '</td>';
	echo '<td>' . $datas['help'] . '</td>';
	echo '</tr>';
}
foreach (object::deadCmd() as $datas) {
	echo '<tr>';
	echo '<td>Résumé</td>';
	echo '<td>' . $datas['detail'] . '</td>';
	echo '<td>' . $datas['who'] . '</td>';
	echo '<td>' . $datas['help'] . '</td>';
	echo '</tr>';
}
foreach (scenario::consystencyCheck(true) as $datas) {
	echo '<tr>';
	echo '<td>Scénario</td>';
	echo '<td>' . $datas['detail'] . '</td>';
	echo '<td>' . $datas['who'] . '</td>';
	echo '<td>' . $datas['help'] . '</td>';
	echo '</tr>';
}
foreach (interactDef::deadCmd() as $datas) {
	echo '<tr>';
	echo '<td>Interaction</td>';
	echo '<td>' . $datas['detail'] . '</td>';
	echo '<td>' . $datas['who'] . '</td>';
	echo '<td>' . $datas['help'] . '</td>';
	echo '</tr>';
}
#vues/designs
foreach (plugin::listPlugin(true) as $plugin) {
	$plugin_id = $plugin->getId();
	if (method_exists($plugin_id, 'deadCmd')) {
		foreach ($plugin_id::deadCmd() as $datas) {
			echo '<tr>';
			echo '<td>Plugin ' . $plugin->getName() . '</td>';
			echo '<td>' . $datas['detail'] . '</td>';
			echo '<td>' . $datas['who'] . '</td>';
			echo '<td>' . $datas['help'] . '</td>';
			echo '</tr>';
		}
	}
}
?>
					</tbody>
				</table>
			</div>
		</div>
		</div>

<?php include_file('desktop', 'eqAnalyse', 'js');?>
