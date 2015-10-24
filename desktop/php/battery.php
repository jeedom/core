<?php

if (!hasRight('batteryview')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
echo '<div class="div_displayEquipement" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
foreach (object::all() as $object) {
	foreach ($object->getEqLogic() as $eqLogic) {
		if ($eqLogic->getConfiguration('batteryStatus', -2) != -2) {
			$color = '#e74c3c';
			if ($eqLogic->getConfiguration('batteryStatus') > 25) {
				$color = '#e67e22';
			}
			if ($eqLogic->getConfiguration('batteryStatus') > 50) {
				$color = '#f1c40f';
			}
			if ($eqLogic->getConfiguration('batteryStatus') > 75) {
				$color = '#2ecc71';
			}
			echo '<div class="eqLogic eqLogic-widget" style="min-width:80px;background-color:' . $color . '">';
			echo '<center class="widget-name"><span style="font-size : 1.5em;">' . $eqLogic->getName() . '</span><br/><span style="font-size: 0.95em;position:relative;top:-5px;">' . $object->getName() . '</span></center>';
			echo '<center><span style="font-size:2.2em;font-weight: bold;">' . $eqLogic->getConfiguration('batteryStatus', -2) . '</span><span>%</span></center>';
			echo '<center>{{Le }}' . $eqLogic->getConfiguration('batteryStatusDatetime', __('inconnue', __FILE__)) . '</center>';
			if ($eqLogic->getConfiguration('battery_type', '') != '') {
				echo '<center>{{Type : }}' . $eqLogic->getConfiguration('battery_type', '') . '</center>';
			}
			echo '</div>';
		}
	}

}
echo '</div>';
echo '</div>';
?>

<?php include_file('desktop', 'battery', 'js');?>