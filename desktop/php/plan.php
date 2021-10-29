<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$planHeader = null;
$planHeaders = planHeader::all();
$planHeadersSendToJS = array();
foreach ($planHeaders as $planHeader_select) {
	if (!$planHeader_select->hasRight('r')) {
		continue;
	}
	$planHeadersSendToJS[] = array('id' => $planHeader_select->getId(), 'name' => $planHeader_select->getName());
}
sendVarToJS('planHeader', $planHeadersSendToJS);
if (init('plan_id') == '') {
	foreach ($planHeaders as $planHeader_select) {
		if (!$planHeader_select->hasRight('r')) {
			continue;
		}
		if ($planHeader_select->getId() == $_SESSION['user']->getOptions('defaultDashboardPlan')) {
			$planHeader = $planHeader_select;
			break;
		}
	}
} else {
	foreach ($planHeaders as $planHeader_select) {
		if (!$planHeader_select->hasRight('r')) {
			continue;
		}
		if ($planHeader_select->getId() == init('plan_id')) {
			$planHeader = $planHeader_select;
			break;
		}
	}
}
if (!is_object($planHeader) && count($planHeaders) > 0) {
	$planHeader = $planHeaders[0];
}
if (!is_object($planHeader)) {
	echo '<div class="alert alert-warning">{{Aucun design n\'existe, cliquez}}' . ' <a id="bt_createNewDesign" class="cursor label alert-info">{{ici}} </a> {{pour en créer un.}}</div>';
	sendVarToJS('planHeader_id', -1);
} else {
	sendVarToJS('planHeader_id', $planHeader->getId());
}
?>

<div class="div_backgroundPlan">
	<div class="container-fluid div_displayObject"></div>
</div>

<?php
include_file('desktop/common', 'ui', 'js');
include_file('desktop', 'plan', 'js');
?>