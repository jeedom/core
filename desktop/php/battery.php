<?php
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
		if ($eqLogic->getCache('batteryStatus', -2) != -2) {
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
	return ($a->getCache('batteryStatus') < $b->getCache('batteryStatus')) ? -1 : (($a->getCache('batteryStatus') > $b->getCache('batteryStatus')) ? 1 : 0);
});
sort($plugins);
sort($battery);
?>

<ul class="nav nav-tabs" role="tablist" id="ul_tabBatteryAlert">
	<li role="presentation" class="active"><a href="#battery" aria-controls="battery" role="tab" data-toggle="tab"><i class="fa fa-battery-full"></i> {{Batterie}}</a></li>
	<li role="presentation"><a href="#alertEqlogic" aria-controls="alertEqlogic" role="tab" data-toggle="tab"><i class="fa fa-exclamation-triangle"></i> {{Module en alerte}}</a></li>
	<li role="presentation"><a href="#actionCmd" aria-controls="actionCmd" role="tab" data-toggle="tab"><i class="fa fa-gears"></i> {{Actions définies}}</a></li>
</ul>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="battery">
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
	if ($eqLogic->getCache('batteryStatus') <= $eqLogic->getConfiguration('battery_danger_threshold', config::byKey('battery::danger'))) {
		$color = '#e74c3c';
		$level = 'critical';
	} else if ($eqLogic->getCache('batteryStatus') <= $eqLogic->getConfiguration('battery_warning_threshold', config::byKey('battery::warning'))) {
		$color = '#f1c40f';
		$level = 'warning';
	}
	$classAttr = $level . ' ' . $battery . ' ' . $plugins . ' ' . $objets;
	$idAttr = $level . '__' . $battery . '__' . $plugins . '__' . $objets;
	echo '<div class="eqLogic eqLogic-widget ' . $classAttr . '" style="min-width:80px;background-color:' . $color . '" id="' . $idAttr . '">';
	echo '<center class="widget-name"><a href="' . $eqLogic->getLinkToConfiguration() . '" style="font-size : 1.5em;">' . $eqLogic->getName() . '</a><br/><span style="font-size: 0.95em;position:relative;top:-5px;cursor:default;">' . $eqLogic->getobject()->getName() . '</span></center>';
	echo '<center><span style="font-size:2.2em;font-weight: bold;cursor:default;">' . $eqLogic->getCache('batteryStatus', -2) . '</span><span>%</span></center>';
	echo '<center style="cursor:default;">{{Le }}' . $eqLogic->getCache('batteryStatusDatetime', __('inconnue', __FILE__)) . '</center>';
	if ($eqLogic->getConfiguration('battery_type', '') != '') {
		echo '<span class="pull-right" style="font-size : 0.8em;margin-bottom: 3px;margin-right: 5px;cursor:default;" title="Piles">' . $eqLogic->getConfiguration('battery_type', '') . '</span>';
	}
	echo '<span class="pull-left" style="font-size : 0.8em;margin-bottom: 3px;margin-left: 5px;cursor:default;" title="Plugin">' . ucfirst($eqLogic->getEqType_name()) . '</span>';
	if ($eqLogic->getConfiguration('battery_danger_threshold') != '' or $eqLogic->getConfiguration('battery_warning_threshold') != '') {
		echo '<i class="icon techno-fingerprint41 pull-right" style="position:absolute;bottom: 3px;right: 3px;cursor:default;" title="Seuil manuel défini"></i>';
	}
	echo '</div>';
}
echo '</div>';
include_file('desktop', 'battery', 'js');
?>
		</div>
		<div role="tabpanel" class="tab-pane" id="alertEqlogic">
			<div class="alertListContainer">
				<?php
foreach (eqLogic::all() as $eqLogic) {
	$hasAlert = '';
	if ($eqLogic->getAlert() == '') {
		continue;
	}
	$hasAlert = 1;
	echo $eqLogic->toHtml('dashboard');
}
if ($hasAlert == ''){
	echo '<div class="alert alert-success">{{Aucun module en Alerte pour le moment}}</div>';
}
?>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="actionCmd">
			<div class="cmdListContainer">
			<table class="table table-condensed tablesorter" id="table_Action">
	<thead>
		<tr>
			<th>{{Equipement}}</th>
			<th>{{Commande}}</th>
			<th>{{Type}}</th>
			<th>{{Actions}}</th>
		</tr>
	</thead>
	<tbody>
				<?php
foreach (eqLogic::all() as $eqLogic) {
	foreach($eqLogic->getCmd('info') as $cmd) {
		if (count($cmd->getConfiguration('actionCheckCmd',array()))>0){
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>Action sur état</td>';
			echo '<td>Si ' . $cmd->getConfiguration('jeedomCheckCmdOperator') . ' ' . $cmd->getConfiguration('jeedomCheckCmdTest') . ' plus de ' . $cmd->getConfiguration('jeedomCheckCmdTime') . ' minutes alors : ';
			foreach ($cmd->getConfiguration('actionCheckCmd') as $actionCmd){
				if ($actionCmd['cmd'] == 'scenario'){
					$name =  scenario::byId($actionCmd['options']['scenario_id'])->getName();
					$action = $actionCmd['options']['action'];
					echo 'Scénario : ' . $name . ' <i class="fa fa-arrow-right"></i> ' . $action . '|';
				} else if ($actionCmd['cmd'] == 'variable') {
					$name = $actionCmd['options']['name'];
					$value = $actionCmd['options']['value'];
					echo 'Variable : ' . $name . ' <i class="fa fa-arrow-right"></i> ' . $value . '|';
				} else if (is_object(cmd::byId(str_replace('#','',$actionCmd['cmd'])))) {
					$cmdEx =  cmd::byId(str_replace('#','',$actionCmd['cmd']));
					$eqEx = $cmdEx->getEqLogic();
					echo $eqEx->getHumanName(true) . ' ' . $cmdEx->getName() . '|';
				}
			}
			echo '</td></tr>';
		}
	}
	foreach($eqLogic->getCmd('action') as $cmd) {
		if (count($cmd->getConfiguration('jeedomPreExecCmd',array()))>0){
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>Pre exécution</td><td>';
			foreach ($cmd->getConfiguration('jeedomPreExecCmd') as $actionCmd){
				if ($actionCmd['cmd'] == 'scenario'){
					$name =  scenario::byId($actionCmd['options']['scenario_id'])->getName();
					$action = $actionCmd['options']['action'];
					echo 'Scénario : ' . $name . ' <i class="fa fa-arrow-right"></i> ' . $action . '|';
				} else if ($actionCmd['cmd'] == 'variable') {
					$name = $actionCmd['options']['name'];
					$value = $actionCmd['options']['value'];
					echo 'Variable : ' . $name . ' <i class="fa fa-arrow-right"></i> ' . $value . '|';
				} else if (is_object(cmd::byId(str_replace('#','',$actionCmd['cmd'])))) {
					$cmdEx =  cmd::byId(str_replace('#','',$actionCmd['cmd']));
					$eqEx = $cmdEx->getEqLogic();
					echo $eqEx->getHumanName(true) . ' ' . $cmdEx->getName() . '|';
				}
			}
			echo '</td></tr>';
		}
		if (count($cmd->getConfiguration('jeedomPostExecCmd',array()))>0){
			echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>Post exécution</td><td>';
			foreach ($cmd->getConfiguration('jeedomPostExecCmd') as $actionCmd){
				if ($actionCmd['cmd'] == 'scenario'){
					$name =  scenario::byId($actionCmd['options']['scenario_id'])->getName();
					$action = $actionCmd['options']['action'];
					echo 'Scénario : ' . $name . ' <i class="fa fa-arrow-right"></i> ' . $action . '|';
				} else if ($actionCmd['cmd'] == 'variable') {
					$name = $actionCmd['options']['name'];
					$value = $actionCmd['options']['value'];
					echo 'Variable : ' . $name . ' <i class="fa fa-arrow-right"></i> ' . $value . '|';
				} else if (is_object(cmd::byId(str_replace('#','',$actionCmd['cmd'])))) {
					$cmdEx =  cmd::byId(str_replace('#','',$actionCmd['cmd']));
					$eqEx = $cmdEx->getEqLogic();
					echo $eqEx->getHumanName(true) . ' ' . $cmdEx->getName() . '|';
				}
			}
			echo '</td></tr>';
		}
	}
}
?>
	</tbody>
</table>
			</div>
		</div>
	</div>
