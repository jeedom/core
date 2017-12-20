<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$planHeader = null;
$planHeaders = planHeader::all();
$planHeadersSendToJS = array();
foreach ($planHeaders as $planHeader_select) {
	$planHeadersSendToJS[] = array('id' => $planHeader_select->getId(), 'name' => $planHeader_select->getName());
}
sendVarToJS('planHeader', $planHeadersSendToJS);
if (init('plan_id') == '') {
	foreach ($planHeaders as $planHeader_select) {
		if ($planHeader_select->getId() == $_SESSION['user']->getOptions('defaultDashboardPlan')) {
			$planHeader = $planHeader_select;
			break;
		}
	}
} else {
	foreach ($planHeaders as $planHeader_select) {
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
	echo '<div class="alert alert-danger">{{Aucun design n\'existe, cliquez <a id="bt_createNewDesign" class="cursor">ici</a> pour en créer une.}}</div>';
	sendVarToJS('planHeader_id', -1);
} else {
	sendVarToJS('planHeader_id', $planHeader->getId());
}
?>
<style>
.div_grid {
  z-index : 998;
  background-size: 15px 15px;
  background-image:
  -webkit-repeating-linear-gradient(90deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px),
  -webkit-repeating-linear-gradient(0deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px);
  background-image:
  -moz-repeating-linear-gradient(90deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px),
  -moz-repeating-linear-gradient(0deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px);
  background-image:
  -o-repeating-linear-gradient(90deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px),
  -o-repeating-linear-gradient(0deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px);
  background-image:
  repeating-linear-gradient(90deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px),
  repeating-linear-gradient(0deg, rgba(0, 191, 255, .5), rgba(0, 191, 255, .5) 1px, transparent 1px, transparent 20px);
}
.contextMenu_select {
  box-shadow: 0 0 2em red !important;
}
.widget-shadow-edit{
  box-shadow: 0 0 2em #96C927 !important;
}
</style>
<div class="div_backgroundPlan">
  <div class="container-fluid div_displayObject" style="position: relative;padding:0;user-select: none;-khtml-user-select: none;-o-user-select: none;-moz-user-select: -moz-none;-webkit-user-select: none;"></div>
</div>
<?php include_file('desktop', 'plan', 'js');?>
