<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$planHeader = null;
$planHeaders = planHeader::all();
$planHeadersSendToJS = array();
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
foreach ($planHeaders as $planHeader_select) {
	$planHeadersSendToJS[] = array('id' => $planHeader_select->getId(), 'name' => $planHeader_select->getName());
}
sendVarToJS('planHeader', $planHeadersSendToJS);
if (is_object($planHeader)) {
	sendVarToJS('planHeader_id', $planHeader->getId());
} else {
	sendVarToJS('planHeader_id', -1);
}
?>
<div class="container-fluid div_displayObject" style="position: relative;padding:0;user-select: none;-khtml-user-select: none;-o-user-select: none;-moz-user-select: -moz-none;-webkit-user-select: none;"></div>
<?php if (init('noControl') == '') {
	?>
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
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <div id="md_addViewData" title="Ajouter widget/graph"></div>
  <?php }
?>

  <?php include_file('desktop', 'plan', 'js');?>