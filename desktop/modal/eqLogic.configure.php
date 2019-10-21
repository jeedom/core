<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$eqLogic = eqLogic::byId(init('eqLogic_id'));
if (!is_object($eqLogic)) {
	throw new Exception('EqLogic non trouvé : ' . init('eqLogic_id'));
}

sendVarToJS('eqLogicInfo', utils::o2a($eqLogic));
sendVarToJS('eqLogicInfoSearchString', urlencode(str_replace('#', '', $eqLogic->getHumanName())));
?>
<div style="display: none;" id="md_displayEqLogicConfigure"></div>
<div class="input-group pull-right" style="display:inline-flex">
	<span class="input-group-btn">
		<a class="btn btn-default roundedLeft btn-sm" id="bt_eqLogicConfigureRawObject"><i class="fas fa-info"></i> {{Informations}}</a><a class="btn btn-default btn-sm" id="bt_eqLogicConfigureLogRealTime"><i class="far fa-file"></i> {{Log}}</a><a class="btn btn-default btn-sm" id="bt_eqLogicConfigureGraph"><i class="fas fa-object-group"></i> {{Liens}}</a><a class="btn btn-success btn-sm" id="bt_eqLogicConfigureSave"><i class="far fa-check-circle"></i> {{Enregistrer}}</a><a class="btn btn-danger roundedRight btn-sm" id="bt_eqLogicConfigureRemove"><i class="fas fa-times"></i> {{Supprimer}}</a>
	</span>
</div>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#eqLogic_information" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-info-circle"></i> {{Informations}}</a></li>
	<?php if ($eqLogic->widgetPossibility('custom')) {
		?>
		<li role="presentation"><a href="#eqLogic_display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-desktop"></i> {{Affichage}}</a></li>
		<?php if ($eqLogic->widgetPossibility('custom::layout')) {
			?>
			<li role="presentation"><a href="#eqLogic_layout" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-th"></i> {{Disposition}}</a></li>
		<?php }
	}
	?>
	<li role="presentation"><a href="#eqLogic_alert" aria-controls="messages" role="tab" data-toggle="tab"><i class="fas fa-exclamation-triangle"></i> {{Alertes}}</a></li>
	<li role="presentation"><a href="#eqLogic_comment" aria-controls="messages" role="tab" data-toggle="tab" id="bt_EqLogicConfigurationTabComment"><i class="fas fa-comment-alt"></i> {{Commentaire}}</a></li>
</ul>

<div class="tab-content" id="div_displayEqLogicConfigure">
	<div role="tabpanel" class="tab-pane active" id="eqLogic_information">
		<br/>
		<div class="row">
			<div class="col-sm-6" >
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{ID}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="id"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Nom}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="name"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{ID logique}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="logicalId"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{ID de l'objet}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="object_id"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Création}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="createtime"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Changement de pile}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="batterytime"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Tag(s)}}</label>
							<div class="col-sm-6">
								<input class="eqLogicAttr form-control" data-l1key="tags" />
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="col-sm-6" >
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Activer}}</label>
							<div class="col-sm-1">
								<input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>
							</div>
							<label class="col-sm-2 control-label">{{Visible}}</label>
							<div class="col-sm-1">
								<input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Type}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="eqType_name"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Tentative échouée}}</label>
							<div class="col-sm-4">
								<span class="label label-primary"><?php echo $eqLogic->getStatus('numberTryWithoutSuccess', 0) ?></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Dernière communication}}</label>
							<div class="col-sm-4">
								<span class="label label-primary"><?php echo $eqLogic->getStatus('lastCommunication') ?></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-6 control-label">{{Dernière sauvegarde}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="updatetime"></span>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="col-sm-12" >
				<legend>{{Commandes}}</legend>
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th>
							<th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$display = '';
						foreach ($eqLogic->getCmd() as $cmd) {
							$display .= '<tr class="advanceCmdConfigurationCmdConfigure" data-id="' . $cmd->getId() . '">';
							$display .= '<td>' . $cmd->getHumanName() . '</td>';
							$display .= '<td>';
							$display .= '<a class="btn btn-default btn-xs pull-right cursor bt_advanceCmdConfigurationOnEqLogicConfiguration" data-id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
							$display .= '</td>';
							$display .= '</tr>';
						}
						echo $display;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php if ($eqLogic->widgetPossibility('custom')) {
		?>
		<div role="tabpanel" class="tab-pane" id="eqLogic_display">
			<br/>
			<?php if(is_array($eqLogic->widgetPossibility('parameters')) && count($eqLogic->widgetPossibility('parameters')) > 0){ ?>
				<legend><i class="fas fa-tint"></i> {{Widget}}</legend>
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th></th>
							<?php
							$display = '';
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								$display .= '<th style="width:20%">{{' . $value['name'] . '}}';
								$display .= '</th>';
							}
							echo $display;
							?>
						</tr>
					</thead>
					<tbody>
						<?php
						if (is_array($eqLogic->widgetPossibility('parameters'))) {
							$echo = '';
							foreach ($eqLogic->widgetPossibility('parameters') as $pKey => $parameter) {
								$echo .= '<tr>';
								$echo .= '<td>';
								$echo .= $parameter['name'];
								$echo .= '</td>';
								foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
									$echo .= '<td>';
									if (!isset($parameter['allow_displayType'])) {
										continue;
									}
									if (!isset($parameter['type'])) {
										continue;
									}
									if (is_array($parameter['allow_displayType']) && !in_array($key, $parameter['allow_displayType'])) {
										continue;
									}
									if ($parameter['allow_displayType'] === false) {
										continue;
									}
									$default = '';
									$display = '';
									if (isset($parameter['default'])) {
										$display = 'display:none;';
										$default = $parameter['default'];
										$echo .= '<label>{{Defaut}} <input type="checkbox" class="eqLogicAttr advanceWidgetParameterDefault" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '-default" checked /></label>';
									}
									switch ($parameter['type']) {
										case 'color':
										if ($parameter['allow_transparent']) {
											$echo .= '<span class="advanceWidgetParameter" style="' . $display . '">';
											$echo .= ' <label>{{Transparent}} <input type="checkbox" class="eqLogicAttr advanceWidgetParameterColorTransparent" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '-transparent" /></label>';
											$echo .= '<input type="color" class="eqLogicAttr pull-right inline-block advanceWidgetParameterColor" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" value="' . $default . '" />';
											$echo .= '</span>';
										} else {
											$echo .= '<input type="color" class="eqLogicAttr pull-right advanceWidgetParameter form-control inline-block input-sm" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" style="width:50%;' . $display . '" value="' . $default . '" />';
										}
										break;
										case 'input':
										$echo .= '<input class="eqLogicAttr pull-right advanceWidgetParameter form-control inline-block input-sm" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" style="width:50%;' . $display . '" value="' . $default . '" />';
										break;
										case 'number':
										$echo .= '<input type="number" class="eqLogicAttr pull-right advanceWidgetParameter form-control inline-block input-sm" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" style="width:50%;' . $display . '" value="' . $default . '" />';
										break;
									}
									$echo .= '</td>';
								}
								$echo .= '</tr>';
							}
							echo $echo;
						}
						?>
					</tbody>
				</table>
			<?php } ?>
			<?php
			if ($eqLogic->widgetPossibility('custom::optionalParameters')) {
				?>
				<legend><i class="fas fa-pencil-alt-square-o"></i> {{Paramètres optionnels sur la tuile}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fas fa-plus-circle"></i> Ajouter</a></legend>
				<table class="table table-bordered table-condensed" id="table_widgetParameters">
					<thead>
						<tr>
							<th>{{Nom}}</th>
							<th>{{Valeur}}</th>
							<th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($eqLogic->getDisplay('parameters') != '') {
							$echo = '';
							foreach ($eqLogic->getDisplay('parameters') as $key => $value) {
								$echo .= '<tr>';
								$echo .= '<td>';
								$echo .= '<input class="form-control key" value="' . $key . '" />';
								$echo .= '</td>';
								$echo .= '<td>';
								$echo .= '<input class="form-control value" value="' . $value . '" />';
								$echo .= '</td>';
								$echo .= '<td>';
								$echo .= '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fas fa-times"></i> Supprimer</a>';
								$echo .= '</td>';
								$echo .= '</tr>';
							}
							echo $echo;
						}
						?>
					</tbody>
				</table>
			<?php }
			?>
		</div>
		
	<?php }
	?>
	<div role="tabpanel" class="tab-pane" id="eqLogic_alert">
		<br/>
		<legend><i class="fas fa-info-circle"></i> {{Informations Batteries}}</legend>
		<div class="row">
			<div class="col-sm-4" >
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">{{Type}}</label>
							<div class="col-sm-6">
								<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_type"></input>
							</div>
							<div class="col-sm-3">
								<a class="btn btn-success" id="bt_resetbattery"><i class="fas fa-refresh"></i> Pile(s) changée(s)</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
		<legend><i class="icon techno-fleches"></i> {{Seuils spécifiques Batteries}}</legend>
		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">
					<label class="col-xs-3 eqLogicAttr label label-danger">{{Danger}}</label>
					<div class="col-xs-2">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_danger_threshold" />
					</input>
				</div>
				<label class="col-xs-3 eqLogicAttr label label-warning">{{Warning}}</label>
				<div class="col-xs-2">
					<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_warning_threshold" />
				</div>
				<label class="col-xs-2 eqLogicAttr label label-success">{{Ok}}</label>
			</div>
		</div>
	</div>
	<legend><i class="far fa-clock"></i> {{Alertes Communications}}</legend>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="col-xs-3 eqLogicAttr label label-danger">{{Danger (en minutes)}}</label>
				<div class="col-xs-2">
					<input class="eqLogicAttr form-control" data-l1key="timeout" />
				</div>
			</div>
		</div>
	</div>
</div>
<div role="tabpanel" class="tab-pane" id="eqLogic_comment">
	<br/>
	<textarea data-l1key="comment" class="form-control eqLogicAttr autogrow" ></textarea>
</div>

<div role="tabpanel" class="tab-pane" id="eqLogic_layout">
	<br/>
	<form class="form-horizontal">
		<fieldset>
			<legend>{{Configuration général}}</legend>
			<div class="form-group">
				<label class="col-sm-2 control-label">{{Disposition}}</label>
				<div class="col-sm-2">
					<select class="eqLogicAttr form-control sel_layout" data-l1key="display" data-l2key="layout::dashboard">
						<option value="default">{{Défaut}}</option>
						<option value="table">{{Tableau}}</option>
					</select>
				</div>
			</div>
			<div class="widget_layout table" style="display: none;">
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Nombre de lignes}}</label>
					<div class="col-sm-2">
						<input type="number" class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::nbLine" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Nombre de colonnes}}</label>
					<div class="col-sm-2">
						<input type="number" class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::nbColumn" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Centrer dans les cases}}</label>
					<div class="col-sm-2">
						<input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="center" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Style général des cases (CSS)}}</label>
					<div class="col-sm-10">
						<input class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletd" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Style du tableau (CSS)}}</label>
					<div class="col-sm-10">
						<input class="eqLogicAttr form-control" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletable" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
	<div class="widget_layout table" style="display: none;">
		<legend>{{Configuration détaillée}}</legend>
		<table class="table table-bordered table-condensed" id="tableCmdLayoutConfiguration">
			<tbody>
				<?php
				$table = array();
				foreach ($eqLogic->getCmd(null, null, true) as $cmd) {
					$line = $eqLogic->getDisplay('layout::dashboard::table::cmd::' . $cmd->getId() . '::line', 1);
					$column = $eqLogic->getDisplay('layout::dashboard::table::cmd::' . $cmd->getId() . '::column', 1);
					if (!isset($table[$line])) {
						$table[$line] = array();
					}
					if (!isset($table[$line][$column])) {
						$table[$line][$column] = array();
					}
					$table[$line][$column][] = $cmd;
				}
				$getDisplayDasboardNbLine = $eqLogic->getDisplay('layout::dashboard::table::nbLine', 1);
				$getDisplayDasboardNbColumn = $eqLogic->getDisplay('layout::dashboard::table::nbColumn', 1);
				for ($i = 1; $i <= $getDisplayDasboardNbLine; $i++) {
					echo '<tr>';
					for ($j = 1; $j <= $getDisplayDasboardNbColumn; $j++) {
						echo '<td data-line="' . $i . '" data-column="' . $j . '">';
						$string_cmd = '<center class="cmdLayoutContainer" style="min-height:30px;">';
						if (isset($table[$i][$j]) && count($table[$i][$j]) > 0) {
							foreach ($table[$i][$j] as $cmd) {
								$string_cmd .= '<span class="label label-default cmdLayout cursor" data-cmd_id="' . $cmd->getId() . '" style="margin:2px;">' . $cmd->getName() . '</span>';
							}
						}
						echo $string_cmd . '</center>';
						echo '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="text::td::' . $i . '::' . $j . '" placeholder="{{Texte de la case}}" style="margin-top:3px;"/>';
						echo '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="style::td::' . $i . '::' . $j . '" placeholder="{{Style de la case (CSS)}}" style="margin-top:3px;"/>';
						
						echo '</td>';
					}
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>
</div>

<script>
$(function() {
	if ($('body').attr('data-page')=="eqAnalyse") {
		$('a[href="#eqLogic_alert"]').click()
	}
})

$('#tableCmdLayoutConfiguration tbody td .cmdLayoutContainer').sortable({
	connectWith: '#tableCmdLayoutConfiguration tbody td .cmdLayoutContainer',
	items: ".cmdLayout"
});

$('.sel_layout').on('change',function(){
	var type = $(this).attr('data-type');
	$('.widget_layout').hide();
	$('.widget_layout.'+$(this).value()).show();
});
$('.background-color-default').off('change').on('change',function(){
	if($(this).value() == 1){
		$(this).closest('td').find('.span_configureBackgroundColor').hide();
	}else{
		$(this).closest('td').find('.span_configureBackgroundColor').show();
	}
});
$('.background-color-transparent').off('change').on('change',function(){
	var td = $(this).closest('td');
	if($(this).value() == 1){
		td.find('.background-color').hide();
	}else{
		td.find('.background-color').show();
	}
});
$('.color-default').off('change').on('change',function(){
	var td = $(this).closest('td')
	if($(this).value() == 1){
		td.find('.color').hide();
	}else{
		td.find('.color').show();
	}
});
$('.border-default').off('change').on('change',function(){
	var td = $(this).closest('td')
	if($(this).value() == 1){
		td.find('.border').hide();
	}else{
		td.find('.border').show();
	}
});
$('.border-radius-default').off('change').on('change',function(){
	var td = $(this).closest('td')
	if($(this).value() == 1){
		td.find('.border-radius').hide();
	}else{
		td.find('.border-radius').show();
	}
});
$('.advanceWidgetParameterDefault').off('change').on('change',function(){
	if($(this).value() == 1){
		$(this).closest('td').find('.advanceWidgetParameter').hide();
	}else{
		$(this).closest('td').find('.advanceWidgetParameter').show();
	}
});
$('.advanceWidgetParameterColorTransparent').off('change').on('change',function(){
	if($(this).value() == 1){
		$(this).closest('td').find('.advanceWidgetParameterColor').hide();
	}else{
		$(this).closest('td').find('.advanceWidgetParameterColor').show();
	}
});
$('#div_displayEqLogicConfigure').setValues(eqLogicInfo, '.eqLogicAttr');

$('#bt_eqLogicConfigureGraph').on('click', function () {
	$('#md_modal2').dialog({title: "{{Graphique des liens}}"});
	$("#md_modal2").load('index.php?v=d&modal=graph.link&filter_type=eqLogic&filter_id='+eqLogicInfo.id).dialog('open');
});

$('#table_widgetParameters').on( 'click', '.removeWidgetParameter',function () {
	$(this).closest('tr').remove();
});
$('#bt_EqLogicConfigurationTabComment').on('click', function () {
	setTimeout(function(){ $('.eqLogicAttr[data-l1key=comment]').trigger('change'); }, 10);
});
$('#bt_eqLogicConfigureRawObject').off('click').on('click',function(){
	$('#md_modal2').dialog({title: "{{Informations brutes}}"});
	$("#md_modal2").load('index.php?v=d&modal=object.display&class=eqLogic&id='+eqLogicInfo.id).dialog('open');
})
$('#bt_addWidgetParameters').off().on('click', function () {
	var tr = '<tr>';
	tr += '<td>';
	tr += '<input class="form-control key" />';
	tr += '</td>';
	tr += '<td>';
	tr += '<input class="form-control value" />';
	tr += '</td>';
	tr += '<td>';
	tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fas fa-times"></i> Supprimer</a>';
	tr += '</td>';
	tr += '</tr>';
	$('#table_widgetParameters tbody').append(tr);
});

$('.bt_displayWidget').off('click').on('click',function(){
	var eqLogic = $('#div_displayEqLogicConfigure').getValues('.eqLogicAttr')[0];
	$('#md_modal2').dialog({title: "{{Widget}}"});
	$('#md_modal2').load('index.php?v=d&modal=eqLogic.displayWidget&eqLogic_id=' + eqLogic.id+'&version='+$(this).attr('data-version')).dialog('open');
});

$('#bt_eqLogicConfigureSave').on('click', function (event) {
	var eqLogic = $('#div_displayEqLogicConfigure').getValues('.eqLogicAttr')[0];
	if (!isset(eqLogic.display)) {
		eqLogic.display = {};
	}
	if (!isset(eqLogic.display.parameters)) {
		eqLogic.display.parameters = {};
	}
	$('#table_widgetParameters tbody tr').each(function () {
		eqLogic.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value();
	});
	jeedom.eqLogic.save({
		eqLogics: [eqLogic],
		type: eqLogic.eqType_name,
		error: function (error) {
			$('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'});
		},
		success: function () {
			cmds = [];
			order = 1;
			$('#tableCmdLayoutConfiguration tbody td').find('.cmdLayout').each(function(){
				cmd = {};
				cmd.id = $(this).attr('data-cmd_id');
				cmd.line = $(this).closest('td').attr('data-line');
				cmd.column = $(this).closest('td').attr('data-column');
				cmd.order = order;
				cmds.push(cmd);
				order++;
			});
			jeedom.cmd.setOrder({
				version : 'dashboard',
				cmds: cmds,
				error: function (error) {
					$('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'});
				},
				success : function(){
					$('#md_displayEqLogicConfigure').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
					if (event.ctrlKey) {
						setTimeout(function() { $('#md_modal').dialog('close') }, 500);
					}else{
						var tab = $('#md_modal > ul.nav li.active a').attr('href')
						$('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + eqLogic.id,function(){
							$('#md_modal > ul.nav a[href="' + tab + '"]').click();
						});
					}
				}
			});
		}
	});
});

$('#bt_eqLogicConfigureRemove').on('click',function(){
	bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer cet équipement ?}}', function (result) {
		if (result) {
			var eqLogic = $('#div_displayEqLogicConfigure').getValues('.eqLogicAttr')[0];
			jeedom.eqLogic.remove({
				id : eqLogic.id,
				type : eqLogic.eqType_name,
				error: function (error) {
					$('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'});
				},
				success: function (data) {
					$('#md_displayEqLogicConfigure').showAlert({message: '{{Suppression réalisée avec succès}}', level: 'success'});
				}
			});
		}
	});
});

$('.bt_advanceCmdConfigurationOnEqLogicConfiguration').off('click').on('click', function () {
	$('#md_modal2').dialog({title: "{{Configuration de la commande}}"});
	$('#md_modal2').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open');
});

$('.advanceCmdConfigurationCmdConfigure').off('dblclick').on('dblclick', function () {
	$('#md_modal2').dialog({title: "{{Configuration de la commande}}"});
	$('#md_modal2').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open');
});

$('#bt_eqLogicConfigureLogRealTime').off('click').on('click', function () {
	$('#md_modal2').dialog({title: "{{Logs}}"});
	$('#md_modal2').load('index.php?v=d&modal=log.display&log=event&search=' + eqLogicInfoSearchString).dialog('open');
});

$('#bt_resetbattery').on('click',function(){
	bootbox.confirm('{{Avez vous changé les piles ? Cette action mettra la date de changement de piles à aujourd\'hui}}', function (result) {
		if (result) {
			var eqLogic = {};
			eqLogic['id']=eqLogicInfo.id
			eqLogic['configuration']={};
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1;
			var hh = today.getHours();
			var MM = today.getMinutes();
			var ss = today.getSeconds();
			var yyyy = today.getFullYear();
			eqLogic['configuration']['batterytime'] = yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss;
			console.log(eqLogic);
			jeedom.eqLogic.simpleSave({
				eqLogic : eqLogic,
				error: function (error) {
					$('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'});
				},
				success: function (data) {
					$('#md_displayEqLogicConfigure').showAlert({message: '{{Changement de pile(s) pris en compte}}', level: 'success'});
					$('.eqLogicAttr[data-l1key=configuration][data-l2key=batterytime]').value(yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss);
				}
			});
		}
	});
});


</script>
