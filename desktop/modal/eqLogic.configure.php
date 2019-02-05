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

<a class="btn btn-success pull-right btn-sm" id="bt_eqLogicConfigureSave"><i class="far fa-check-circle"></i> {{Enregistrer}}</a>
<a class="btn btn-danger pull-right btn-sm" id="bt_eqLogicConfigureRemove"><i class="fas fa-times"></i> {{Supprimer}}</a>
<a class="btn btn-default pull-right btn-sm" id="bt_eqLogicConfigureRawObject"><i class="fas fa-info"></i> {{Informations}}</a>
<a class="btn btn-default pull-right btn-sm" id="bt_eqLogicConfigureLogRealTime"><i class="far fa-file"></i> {{Log}}</a>
<a class="btn btn-default pull-right btn-sm" id="bt_eqLogicConfigureGraph"><i class="fas fa-object-group"></i> {{Liens}}</a>

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
			<div class="col-sm-4" >
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{ID}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="id" style="font-size : 1em;"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Nom}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="name" style="font-size : 1em;"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{ID logique}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="logicalId" style="font-size : 1em;"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{ID de l'objet}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="object_id" style="font-size : 1em;"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Date de création}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="createtime" style="font-size : 1em;"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Date de changement de pile(s)}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="batterytime" style="font-size : 1em;"></span>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="col-sm-4" >
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
							<label class="col-sm-4 control-label">{{Type}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="eqType_name" style="font-size : 1em;"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Tentative échouée}}</label>
							<div class="col-sm-4">
								<span class="label label-primary" style="font-size : 1em;"><?php echo $eqLogic->getStatus('numberTryWithoutSuccess', 0) ?></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Date de dernière communication}}</label>
							<div class="col-sm-4">
								<span class="label label-primary" style="font-size : 1em;"><?php echo $eqLogic->getStatus('lastCommunication') ?></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Dernière mise à jour}}</label>
							<div class="col-sm-4">
								<span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="updatetime" style="font-size : 1em;"></span>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="col-sm-4" >
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Tag}}</label>
					<div class="col-sm-10">
						<input class="eqLogicAttr form-control" data-l1key="tags" />
					</div>
				</div>
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
						foreach ($eqLogic->getCmd() as $cmd) {
							echo '<tr class="advanceCmdConfigurationCmdConfigure" data-id="' . $cmd->getId() . '">';
							echo '<td>' . $cmd->getHumanName() . '</td>';
							echo '<td>';
							echo '<a class="btn btn-default btn-xs pull-right cursor bt_advanceCmdConfigurationOnEqLogicConfiguration" data-id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
							echo '</td>';
							echo '</tr>';
						}
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
			<legend><i class="fas fa-tint"></i> {{Widget}}</legend>
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th></th>
						<?php
						foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
							echo '<th style="width:20%">{{' . $value['name'] . '}}';
							if (in_array($key, array('plan', 'view'))) {
								echo '<i class="fas fa-eye pull-right cursor bt_displayWidget" data-version="d' . $key . '" aria-hidden="true"></i>';
							} elseif ($key == 'dashboard') {
								echo '<i class="fas fa-eye pull-right cursor bt_displayWidget" data-version="' . $key . '" aria-hidden="true"></i>';
							}
							echo '</th>';
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php if ($eqLogic->widgetPossibility('custom::visibility')) {
						?>
						<tr>
							<td>{{Visible}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::visibility::' . $key)) {
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="showOn' . $key . '" checked />';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					?>
					<?php if ($eqLogic->widgetPossibility('custom::displayName')) {
						?>
						<tr>
							<td>{{Afficher le nom}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::displayName::' . $key)) {
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="showNameOn' . $key . '" checked />';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					?>
					<?php if ($eqLogic->widgetPossibility('custom::displayObjectName')) {
						?>
						<tr>
							<td>{{Afficher le nom de l'objet}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::displayObjectName::' . $key)) {
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="showObjectNameOn' . $key . '" />';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					?>
					<?php if ($eqLogic->widgetPossibility('custom::background-color')) {
						?>
						<tr>
							<td>{{Couleur de fond}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::background-color::' . $key)) {
									echo '<label>{{Defaut}} <input type="checkbox" class="eqLogicAttr background-color-default" data-l1key="display" data-l2key="background-color-default' . $key . '" checked /></label>';
									echo '<span class="span_configureBackgroundColor" style="display:none;" >';
									echo ' <label>{{Transparent}} <input type="checkbox" class="eqLogicAttr background-color-transparent" data-l1key="display" data-l2key="background-color-transparent' . $key . '" /></label>';
									echo ' <input type="color" class="eqLogicAttr background-color" data-l1key="display" data-l2key="background-color' . $key . '" value="' . $eqLogic->getBackgroundColor($key) . '" />';
									echo '</span>';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					?>
					<?php if ($eqLogic->widgetPossibility('custom::background-opacity')) {
						?>
						<tr>
							<td>{{Opacité}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::background-opacity::' . $key)) {
									echo '<input type="numeric" class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="background-opacity' . $key . '" placeholder="{{Entre 0 et 1}}" />';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					?>
					<?php if ($eqLogic->widgetPossibility('custom::text-color')) {
						?>
						<tr>
							<td>{{Couleur du texte}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::text-color::' . $key)) {
									echo '<label>{{Defaut}} <input type="checkbox" class="eqLogicAttr color-default" data-l1key="display" data-l2key="color-default' . $key . '" checked /></label>';
									echo ' <input type="color" class="eqLogicAttr color" data-l1key="display" data-l2key="color' . $key . '" value="#ffffff" style="display:none;" />';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					?>
					<?php if ($eqLogic->widgetPossibility('custom::border')) {
						?>
						<tr>
							<td>{{Bordures}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::border::' . $key)) {
									echo '<label>{{Defaut}} <input type="checkbox" class="eqLogicAttr border-default" data-l1key="display" data-l2key="border-default' . $key . '" checked /></label>';
									echo ' <input class="eqLogicAttr border form-control inline-block pull-right input-sm" data-l1key="display" data-l2key="border' . $key . '" style="display:none;width:50%" />';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					?>
					<?php if ($eqLogic->widgetPossibility('custom::border-radius')) {
						?>
						<tr>
							<td>{{Arrondi des bordures (en px)}}</td>
							<?php
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
								if ($eqLogic->widgetPossibility('custom::border-radius::' . $key)) {
									echo '<label>{{Defaut}} <input type="checkbox" class="eqLogicAttr border-radius-default" data-l1key="display" data-l2key="border-radius-default' . $key . '" checked /></label>';
									echo ' <input type="number" class="eqLogicAttr border-radius form-control inline-block pull-right input-sm" data-l1key="display" data-l2key="border-radius' . $key . '" style="display:none;width:50%" />';
								}
								echo '</td>';
							}
							?>
						</tr>
					<?php }
					if (is_array($eqLogic->widgetPossibility('parameters'))) {
						foreach ($eqLogic->widgetPossibility('parameters') as $pKey => $parameter) {
							echo '<tr>';
							echo '<td>';
							echo $parameter['name'];
							echo '</td>';
							foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
								echo '<td>';
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
									echo '<label>{{Defaut}} <input type="checkbox" class="eqLogicAttr advanceWidgetParameterDefault" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '-default" checked /></label>';
								}
								switch ($parameter['type']) {
									case 'color':
									if ($parameter['allow_transparent']) {
										echo '<span class="advanceWidgetParameter" style="' . $display . '">';
										echo ' <label>{{Transparent}} <input type="checkbox" class="eqLogicAttr advanceWidgetParameterColorTransparent" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '-transparent" /></label>';
										echo '<input type="color" class="eqLogicAttr pull-right inline-block advanceWidgetParameterColor" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" value="' . $default . '" />';
										echo '</span>';
									} else {
										echo '<input type="color" class="eqLogicAttr pull-right advanceWidgetParameter form-control inline-block input-sm" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" style="width:50%;' . $display . '" value="' . $default . '" />';
									}
									break;
									case 'input':
									echo '<input class="eqLogicAttr pull-right advanceWidgetParameter form-control inline-block input-sm" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" style="width:50%;' . $display . '" value="' . $default . '" />';
									break;
									case 'number':
									echo '<input type="number" class="eqLogicAttr pull-right advanceWidgetParameter form-control inline-block input-sm" data-l1key="display" data-l2key="advanceWidgetParameter' . $pKey . $key . '" style="width:50%;' . $display . '" value="' . $default . '" />';
									break;
								}
								echo '</td>';
							}
							echo '</tr>';
						}
					}
					?>
				</tbody>
			</table>
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
							foreach ($eqLogic->getDisplay('parameters') as $key => $value) {
								echo '<tr>';
								echo '<td>';
								echo '<input class="form-control key" value="' . $key . '" />';
								echo '</td>';
								echo '<td>';
								echo '<input class="form-control value" value="' . $value . '" />';
								echo '</td>';
								echo '<td>';
								echo '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fas fa-times"></i> Supprimer</a>';
								echo '</td>';
								echo '</tr>';
							}
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
							<label class="col-sm-4 control-label">{{Type de batterie}}</label>
							<div class="col-sm-3">
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
					<label class="col-xs-3 eqLogicAttr label label-danger" style="font-size : 1.8em;line-height: 34px">{{Danger}}</label>
					<div class="col-xs-2">
						<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_danger_threshold" />
					</input>
				</div>
				<label class="col-xs-3 label label-warning" style="font-size : 1.8em;line-height: 34px">{{Warning}}</label>
				<div class="col-xs-2">
					<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_warning_threshold" />
				</div>
				<label class="col-xs-2 label label-success" style="font-size : 1.8em;line-height: 34px">{{Ok}}</label>
			</div>
		</div>
	</div>
	<legend><i class="fas fa-clock-o"></i> {{Alertes Communications}}</legend>
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group">
				<label class="col-xs-3 eqLogicAttr label label-danger" style="font-size : 1.8em;line-height: 34px">{{Danger (en minutes)}}</label>
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
						<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::dashboard::table::parameters" data-l3key="styletd" />
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

$('#bt_eqLogicConfigureSave').on('click', function () {
	
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
				}
			});
		}
	});
});

$('#bt_eqLogicConfigureRemove').on('click',function(){
	bootbox.confirm('{{Etes-vous sûr de vouloir supprimer cet équipement ?}}', function (result) {
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
				}
			});
		}
	});
});


</script>
