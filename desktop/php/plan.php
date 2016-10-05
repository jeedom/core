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
	echo '<div class="alert alert-danger">{{Aucun design n\'existe, cliquez <a id="bt_createNewDesign" class="cursor">ici</a> pour en créer une}}</div>';
	sendVarToJS('planHeader_id', -1);
} else {
	sendVarToJS('planHeader_id', $planHeader->getId());
}
?>
<style>
  .div_grid {
    z-index : 999;
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
    background:rgba(255,255,255,0.5) !important;
  }
</style>
<div class="container-fluid div_displayObject" style="position: relative;padding:0;user-select: none;-khtml-user-select: none;-o-user-select: none;-moz-user-select: -moz-none;-webkit-user-select: none;"></div>
<div class="modal fade" id="md_selectLink">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">{{Sélectionnez un lien}}</h4>
      </div>
      <div class="modal-body">
        <select class="form-control linkType">
          <option value="plan">Design</option>
          <option value="view">Vue</option>
        </select>
        <br/>
        <div class="linkplan linkOption">
          <select class="form-control linkId">
            <?php
foreach ($planHeaders as $planHeader_select) {
	echo '<option value="' . $planHeader_select->getId() . '">' . $planHeader_select->getName() . '</option>';
}
?>
          </select>
        </div>
        <div class="linkview linkOption" style="display: none;">
          <select class="form-control linkId">
            <?php
foreach (view::all() as $views) {
	echo '<option value="' . $views->getId() . '">' . $views->getName() . '</option>';
}
?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{Annuler}}</button>
        <button type="button" class="btn btn-primary validate">{{Valider}}</button>
      </div>
    </div>
  </div>
</div>
<?php include_file('desktop', 'plan', 'js');?>