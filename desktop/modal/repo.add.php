 <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$repos = repo::all();
?>
<div style="display: none;" id="div_repoAddAlert"></div>
 <legend>{{Source}}</legend>
 <form class="form-horizontal">
 	<fieldset>
 		<div class="form-group">
 			<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Type de source}}</label>
 			<div class="col-sm-4">
 				<select class="updateAttr form-control" data-l1key="source">
 					<?php
foreach ($repos as $key => $value) {
	if ($value['configuration'] === false) {
		continue;
	}
	echo '<option value="' . $key . '">' . $value['name'] . '</option>';
}
?>
 				</select>
 			</div>
 		</div>
 	</fieldset>
 </form>


 <legend>{{Configuration}}</legend>
 <form class="form-horizontal">
 	<fieldset>
 		<?php
foreach ($repos as $key => $value) {
	if ($value['configuration'] === false) {
		continue;
	}
	if (!isset($value['configuration']['parameters_for_add'])) {
		continue;
	}
	echo '<div class="repoSource ' . $key . '" >';
	echo '<div class="form-group">';
	echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">';
	echo '{{ID logique du plugin}}';
	echo '</label>';
	echo '<div class="col-sm-4">';
	echo '<input class="updateAttr form-control" data-l1key="logicalId" />';
	echo '</div>';
	echo '</div>';
	foreach ($value['configuration']['parameters_for_add'] as $pKey => $parameter) {
		echo '<div class="form-group">';
		echo '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">';
		echo $parameter['name'];
		echo '</label>';
		echo '<div class="col-sm-4">';
		$default = (isset($parameter['default'])) ? $parameter['default'] : '';
		switch ($parameter['type']) {
			case 'input':
				echo '<input class="updateAttr form-control" data-l1key="configuration" data-l2key="' . $pKey . '" value="' . $default . '" />';
				break;
			case 'number':
				echo '<input type="number" class="updateAttr form-control" data-l1key="configuration" data-l2key="' . $pKey . '" value="' . $default . '" />';
				break;
		}
		echo '</div>';
		echo '</div>';
	}

	echo '</div>';
}
?>
 		<a class="btn btn-success pull-right" id="bt_repoAddSaveUpdate"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
 	</fieldset>
 </form>

 <script type="text/javascript">
 	$('#bt_repoAddSaveUpdate').on('click',function(){
 		var source = $('.updateAttr[data-l1key=source]').value();
 		var update =  $('.repoSource.'+source).getValues('.updateAttr')[0];
 		update.source = source;
 		jeedom.update.save({
 			update : update,
 			error: function (error) {
 				$('#div_repoAddAlert').showAlert({message: error.message, level: 'danger'});
 			},
 			success: function () {
 				$('#div_repoAddAlert').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
 			}
 		});
 	});
 </script>