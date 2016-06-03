 <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$repos = update::listRepo();
?>
 <div style="display: none;" id="div_repoAddAlert"></div>
 <legend>{{Source}}</legend>
 <form class="form-horizontal">
 	<fieldset>
 		<div class="form-group">
 			<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Type de source}}</label>
 			<div class="col-sm-4">
 				<select class="updateAttr form-control" data-l1key="source">
 					<option value="nothing">{{Aucun}}</option>
 					<?php
foreach ($repos as $key => $value) {
	if ($value['configuration'] === false) {
		continue;
	}
	if ($value['scope']['plugin'] === false) {
		continue;
	}
	if (!isset($value['configuration']['parameters_for_add'])) {
		continue;
	}
	if (config::byKey($key . '::enable') == 0) {
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
	if ($value['scope']['plugin'] === false) {
		continue;
	}
	if (!isset($value['configuration']['parameters_for_add'])) {
		continue;
	}
	echo '<div class="repoSource repo_' . $key . '" style="display:none;">';
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
			case 'file':
				echo '<input class="updateAttr form-control" data-l1key="configuration" data-l2key="' . $pKey . '" style="display:none;" />';
				echo '<span class="btn btn-default btn-file">';
				echo '<i class="fa fa-cloud-upload"></i> {{Envoyer un plugin}}<input id="bt_uploadPlugin" data-key="' . $pKey . '" type="file" name="file" data-url="core/ajax/update.ajax.php?action=preUploadFile&jeedom_token=' . ajax::getToken() . '" style="display : inline-block;">';
				echo '</span>';
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
 	$('.updateAttr[data-l1key=source]').on('change',function(){
 		$('.repoSource').hide();
 		$('.repoSource.repo_'+$(this).value()).show();
 	});

 	$('#bt_uploadPlugin').fileupload({
 		dataType: 'json',
 		replaceFileInput: false,
 		done: function (e, data) {
 			if (data.result.state != 'ok') {
 				$('#div_repoAddAlert').showAlert({message: data.result.result, level: 'danger'});
 				return;
 			}
 			$('.updateAttr[data-l1key=configuration][data-l2key='+$('#bt_uploadPlugin').attr('data-key')+']').value(data.result.result);
 		}
 	});


 	$('#bt_repoAddSaveUpdate').on('click',function(){
 		var source = $('.updateAttr[data-l1key=source]').value();
 		var update =  $('.repoSource.repo_'+source).getValues('.updateAttr')[0];
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