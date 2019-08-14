<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$view = view::byId(init('view_id'));
if (!is_object($view)) {
	throw new Exception('Impossible de trouver la vue');
}
sendVarToJS('id', $view->getId());
sendVarToJS('view', utils::o2a($view));
?>
<div id="div_alertViewConfigure"></div>

<div id="div_viewConfigure">
	<form class="form-horizontal">
		<fieldset>
			<legend><i class="fas fa-cog"></i> {{Général}}<a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigureView'><i class="fas fa-check"></i> {{Sauvegarder}}</a></legend>
			<input type="text"  class="viewAttr form-control" data-l1key="id" style="display: none;"/>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Nom}}</label>
				<div class="col-lg-2">
					<input class="viewAttr form-control" data-l1key="name" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Icône}}</label>
				<div class="col-lg-2">
					<div class="viewAttr" data-l1key="display" data-l2key="icon" ></div>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
					<a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Image (marche uniquement avec le thème Jeedom)}}</label>
				<div class="col-lg-8">
					<span class="btn btn-default btn-file">
						<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
					</span>
					<a class="btn btn-danger" id="bt_removeBackgroundImage"><i class="fas fa-trash"></i> {{Supprimer l'image}}</a>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Afficher le nom des objets sur les widgets}}</label>
				<div class="col-lg-2">
					<input type="checkbox" class="viewAttr form-control" data-l1key="configuration" data-l2key="displayObjectName" />
				</div>
			</div>
		</fieldset>
	</form>
</div>

<script>
$('.viewAttr[data-l1key=display][data-l2key=icon]').on('dblclick',function(){
	$('.viewAttr[data-l1key=display][data-l2key=icon]').value('');
});

$('#bt_chooseIcon').on('click', function () {
	chooseIcon(function (_icon) {
		$('.viewAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
	});
});

$('#bt_uploadImage').fileupload({
	replaceFileInput: false,
	url: 'core/ajax/view.ajax.php?action=uploadImage&id=' + view.id+'&jeedom_token='+JEEDOM_AJAX_TOKEN,
	dataType: 'json',
	done: function (e, data) {
		if (data.result.state != 'ok') {
			$('#div_alertViewConfigure').showAlert({message: data.result.result, level: 'danger'});
			return;
		}
		$('#div_alertViewConfigure').showAlert({message: '{{Image ajoutée}}', level: 'success'});
	}
});

$('#bt_removeBackgroundImage').off('click').on('click', function () {
	jeedom.view.removeImage({
		id: view.id,
		error: function (error) {
			$('#div_alertViewConfigure').showAlert({message: error.message, level: 'danger'});
		},
		success: function () {
			$('#div_alertViewConfigure').showAlert({message: '{{Image supprimée}}', level: 'success'});
		},
	});
});

$('#bt_saveConfigureView').on('click', function () {
	var view =  $('#div_viewConfigure').getValues('.viewAttr')[0];
	jeedom.view.save({
		id : view.id,
		view: view,
		error: function (error) {
			$('#div_alertViewConfigure').showAlert({message: error.message, level: 'danger'});
		},
		success: function () {
			$('#div_alertViewConfigure').showAlert({message: '{{Vue sauvegardé}}', level: 'success'});
		},
	});
});

if (isset(id) && id != '') {
	$('#div_viewConfigure').setValues(view, '.viewAttr');
}
</script>
