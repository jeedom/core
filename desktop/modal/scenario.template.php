<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
	throw new Exception('Scénario non trouvé : ' . init('scenario_id'));
}
sendVarToJS('scenario_template_id', init('scenario_id'));
?>
<div style="display: none;" id="md_scenarioTemplate"></div>
<div class="row row-overflow" id='div_scenarioTemplate'>
	<div class="col-lg-2 col-md-3 col-sm-5" id="div_listScenario" style="z-index:999">
		<div class="bs-sidebar nav nav-list bs-sidenav">
			<div class="form-group">
				<span class="btn btn-default btn-file" style="width:100%;">
					<i class="fas fa-file-download"></i> {{Charger un template}}<input id="bt_uploadScenarioTemplate" type="file" name="file" data-url="core/ajax/scenario.ajax.php?action=templateupload&jeedom_token=<?php echo ajax::getToken(); ?>" style="display : inline-block;width:100%;">
				</span>
			</div>
			<div class="form-group">
				<div class="input-group">
					<input class='form-control roundedLeft' id='in_newTemplateName' placeholder="{{Nom du template}}" />
					<span class="input-group-btn">
						<a class="btn btn-default roundedRight" id="bt_scenarioTemplateConvert" style="color:white;"><i class="fas fa-plus-circle"></i></a>
					</span>
				</div>
			</div>
			<legend>{{Template}}</legend>
			<ul id="ul_scenarioTemplateList" class="nav nav-list bs-sidenav"></ul>
		</div>
	</div>

	<div class="col-lg-10 col-md-9 col-sm-7" id="div_listScenarioTemplate" style="display:none;">
		<form class="form-horizontal">
			<legend><i class="fas fa-home"></i> {{Général}}</legend>
			<div class="form-group">
				<label class="col-xs-2 control-label">{{Gérer}}</label>
				<div class="col-xs-6">
					<a class='btn btn-danger' id='bt_scenarioTemplateRemove'><i class="fas fa-times"></i> {{Supprimer}}</a>
					<a class="btn btn-primary" id="bt_scenarioTemplateDownload"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}</a>
				</div>
			</div>
			<div id='div_scenarioTemplateParametreConfiguration' style='display:none;'>
				<legend><i class="fas fa-tools"></i> {{Paramètres du scénario}}<a class='btn btn-success btn-xs pull-right' id='bt_scenarioTemplateApply'><i class="far fa-check-circle"></i> {{Appliquer}}</a></legend>
				<div id='div_scenarioTemplateParametreList'></div>
			</div>
		</form>
	</div>
	<div class="col-lg-10 col-md-9 col-sm-7" id="div_marketScenarioTemplate" style="display:none;"></div>
</div>

<script>
function refreshScenarioTemplateList() {
	jeedom.scenario.getTemplate({
		error: function (error) {
			$('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			$('#ul_scenarioTemplateList').empty();
			li = '';
			for (var i in data) {
				li += "<li class='cursor li_scenarioTemplate' data-template='" + data[i] + "'><a>" + data[i].replace(".json", "") + "</a></li>";
			}
			$('#ul_scenarioTemplateList').html(li);
		}
	});
}

function refreshListAfterMarketObjectInstall(){
	refreshScenarioTemplateList();
}

refreshScenarioTemplateList();

$('#bt_scenarioTemplateConvert').on('click', function () {
	jeedom.scenario.convertToTemplate({
		id: scenario_template_id,
		template: $('#in_newTemplateName').value()+'.json',
		error: function (error) {
			$('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			refreshScenarioTemplateList();
			$('#md_scenarioTemplate').showAlert({message: 'Création du template réussie', level: 'success'});
		}
	});
});

$('#bt_scenarioTemplateRemove').on('click', function () {
	if($('#ul_scenarioTemplateList li.active').attr('data-template') == undefined){
		$('#md_scenarioTemplate').showAlert({message: 'Vous devez d\'abord sélectionner un template', level: 'danger'});
		return;
	}
	jeedom.scenario.removeTemplate({
		template: $('#ul_scenarioTemplateList li.active').attr('data-template'),
		error: function (error) {
			$('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			refreshScenarioTemplateList();
			$('#md_scenarioTemplate').showAlert({message: 'Suppression du template réussie', level: 'success'});
		}
	});
});

$('#bt_scenarioTemplateApply').on('click', function () {
	bootbox.confirm('{{Êtes-vous sûr de vouloir appliquer le template ? Cela écrasera votre scénario}}', function (result) {
		if (result) {
			var convert = $('.templateScenario').getValues('.templateScenarioAttr');
			jeedom.scenario.applyTemplate({
				template:$('#ul_scenarioTemplateList li.active').attr('data-template'),
				id: scenario_template_id,
				convert: json_encode(convert),
				error: function (error) {
					$('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
				},
				success: function (data) {
					$('#md_scenarioTemplate').showAlert({message: 'Template appliqué avec succès', level: 'success'});
					$('.li_scenario[data-scenario_id='+scenario_template_id+']').click();
				}
			});
		}
	});
});

$('#ul_scenarioTemplateList').delegate('.li_scenarioTemplate','click', function () {
	$('#div_listScenarioTemplate').show();
	$('#div_marketScenarioTemplate').hide();
	$('#ul_scenarioTemplateList .li_scenarioTemplate').removeClass('active');
	$(this).addClass('active');
	jeedom.scenario.loadTemplateDiff({
		template: $(this).attr('data-template'),
		id: scenario_template_id,
		error: function (error) {
			$('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			var html = '';
			for (var i in data) {
				html += '<div class="form-group templateScenario">';
				html += '<label class="col-xs-4 control-label">' + i + ' <i class="fas fa-arrow-right"></i></label>';
				html += '<div class="col-xs-4">';
				html += '<span class="templateScenarioAttr" data-l1key="begin" style="display : none;" >' + i + '</span>';
				html += '<div class="input-group">';
				html += '<input class="form-control templateScenarioAttr" data-l1key="end" value="'+data[i]+'"/>';
				html += '<span class="input-group-btn">';
				html += '<a class="btn btn-default cursor bt_scenarioTemplateSelectCmd"><i class="fas fa-list-alt"></i></a>';
				html += '</span>';
				html += '</div>';
				html += '</div>';
				html += '</div>';
			}
			$('#div_scenarioTemplateParametreList').empty().html(html);
			$('#div_scenarioTemplateParametreConfiguration').show();
		}
	});

});

$('#bt_scenarioTemplateDownload').on('click',function(){
	if($('#ul_scenarioTemplateList li.active').attr('data-template') == undefined){
		$('#md_scenarioTemplate').showAlert({message: 'Vous devez d\'abord sélectionner un template', level: 'danger'});
		return;
	}
	var path = 'data/scenario/' + $('#ul_scenarioTemplateList li.active').attr('data-template')
	window.open('core/php/downloadFile.php?pathfile=' + path, "_blank", null);
});

$('#div_scenarioTemplate').delegate('.bt_scenarioTemplateSelectCmd', 'click', function () {
	var el = $(this);
	jeedom.cmd.getSelectModal({}, function (result) {
		el.closest('.templateScenario').find('.templateScenarioAttr[data-l1key=end]').value(result.human);
	});
});


$('#bt_uploadScenarioTemplate').fileupload({
	dataType: 'json',
	replaceFileInput: false,
	done: function (e, data) {
		if (data.result.state != 'ok') {
			$('#md_scenarioTemplate').showAlert({message: data.result.result, level: 'danger'});
			return;
		}
		$('#md_scenarioTemplate').showAlert({message: '{{Template ajouté avec succès}}', level: 'success'});
		refreshScenarioTemplateList();
	}
});
</script>
