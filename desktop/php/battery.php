<?php
if (!hasRight('batteryview')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
echo '<div class="div_displayEquipement" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
$list = array();
foreach (object::all() as $object) {
	foreach ($object->getEqLogic() as $eqLogic) {
		if ($eqLogic->getConfiguration('batteryStatus', -2) != -2) {
			array_push($list, $eqLogic);
		}
	}
}
usort($list, function ($a, $b) {
	return ($a->getConfiguration('batteryStatus') < $b->getConfiguration('batteryStatus')) ? -1 : (($a->getConfiguration('batteryStatus') > $b->getConfiguration('batteryStatus')) ? 1 : 0);
});
foreach ($list as $eqLogic) {
	$color = '#2ecc71';
	if ($eqLogic->getConfiguration('batteryStatus') <= $eqLogic->getConfiguration('battery_danger_threshold', config::byKey('battery::danger'))) {
		$color = '#e74c3c';
	} else if ($eqLogic->getConfiguration('batteryStatus') <= $eqLogic->getConfiguration('battery_warning_threshold', config::byKey('battery::warning'))) {
		$color = '#f1c40f';
	}
	echo '<div class="eqLogic eqLogic-widget" style="min-width:80px;background-color:' . $color . '">';
	echo '<center class="widget-name"><a href="' . $eqLogic->getLinkToConfiguration() . '" style="font-size : 1.5em;">' . $eqLogic->getName() . '</a><br/><span style="font-size: 0.95em;position:relative;top:-5px;cursor:default;">' . $object->getName() . '</span></center>';
	echo '<center><span style="font-size:2.2em;font-weight: bold;cursor:default;">' . $eqLogic->getConfiguration('batteryStatus', -2) . '</span><span>%</span></center>';
	echo '<center style="cursor:default;">{{Le }}' . $eqLogic->getConfiguration('batteryStatusDatetime', __('inconnue', __FILE__)) . '</center>';
	if ($eqLogic->getConfiguration('battery_type', '') != '') {
		echo '<span class="pull-right" style="font-size : 0.8em;margin-bottom: 3px;margin-right: 5px;cursor:default;" title="Piles">' . $eqLogic->getConfiguration('battery_type', '') . '</span>';
	}
	echo '<span class="pull-left" style="font-size : 0.8em;margin-bottom: 3px;margin-left: 5px;cursor:default;" title="Plugin">' . ucfirst($eqLogic->getEqType_name()) . '</span>';
	if ($eqLogic->getConfiguration('battery_danger_threshold') != '' or $eqLogic->getConfiguration('battery_warning_threshold') != '') {
		echo '</br><i class="icon techno-fingerprint41 pull-right tooltips" style="margin-top: 8px;margin-right: 8px;cursor:default;" title="Seuil manuel défini"></i>';
	}
	echo '</div>';
}
echo '</div>';
?>
<?php include_file('desktop', 'battery', 'js');?>