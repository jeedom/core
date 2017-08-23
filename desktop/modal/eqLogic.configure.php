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

<a class="btn btn-success pull-right btn-sm" id="bt_eqLogicConfigureSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
<a class="btn btn-danger pull-right btn-sm" id="bt_eqLogicConfigureRemove"><i class="fa fa-times"></i> {{Supprimer}}</a>
<a class="btn btn-default pull-right btn-sm" id="bt_eqLogicConfigureRawObject"><i class="fa fa-info"></i> {{Informations}}</a>
<a class="btn btn-default pull-right btn-sm" id="bt_eqLogicConfigureLogRealTime"><i class="fa fa-file"></i> {{Log}}</a>
<a class="btn btn-default pull-right btn-sm" id="bt_eqLogicConfigureGraph"><i class="fa fa-object-group"></i> {{Liens}}</a>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#eqLogic_information" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i> {{Informations}}</a></li>
	<?php if ($eqLogic->widgetPossibility('custom')) {
	?>
		<li role="presentation"><a href="#eqLogic_display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-desktop"></i> {{Affichage}}</a></li>
		<?php }
?>
		<li role="presentation"><a href="#eqLogic_alert" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-exclamation-triangle"></i> {{Alertes}}</a></li>
		<li role="presentation"><a href="#eqLogic_comment" aria-controls="messages" role="tab" data-toggle="tab" id="bt_EqLogicConfigurationTabComment"><i class="fa fa-commenting-o"></i> {{Commentaire}}</a></li>
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
	echo '<tr>';
	echo '<td>' . $cmd->getHumanName() . '</td>';
	echo '<td>';
	echo '<a class="btn btn-default btn-xs pull-right cursor bt_advanceCmdConfigurationOnEqLogicConfiguration" data-id="' . $cmd->getId() . '"><i class="fa fa-cogs"></i></a>';
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
				<legend><i class="fa fa-tint"></i> {{Widget}}</legend>
				<table class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th></th>
							<?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
		echo '<th style="width:20%">{{' . $value['name'] . '}}';
		if (in_array($key, array('plan', 'view'))) {
			echo '<i class="fa fa-eye pull-right cursor bt_displayWidget" data-version="d' . $key . '" aria-hidden="true"></i>';
		} elseif ($key == 'dashboard') {
			echo '<i class="fa fa-eye pull-right cursor bt_displayWidget" data-version="' . $key . '" aria-hidden="true"></i>';
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
															<td>{{Arrondit des bordures (en px)}}</td>
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
				if ($parameter['allow_displayType'] == false) {
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
												<?php if ($eqLogic->widgetPossibility('custom::layout')) {
		?>
													<legend><i class="fa fa-table"></i> {{Disposition}}</legend>


													<table class="table table-bordered table-condensed">
														<thead>
															<tr>
																<th></th>
																<?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			echo '<th style="width:20%">{{' . $value['name'] . '}}';
			echo '</th>';
		}
		?>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{{Disposition}}</td>
																<?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			echo '<td>';
			echo '<select class="eqLogicAttr form-control sel_layout" data-l1key="display" data-l2key="layout::' . $key . '" data-type="' . $key . '">';
			echo '<option value="default">{{Defaut}}</option>';
			echo '<option value="table">{{Tableau}}</option>';
			echo '</select>';
			echo '</td>';
		}
		?>
															</tr>
															<tr>
																<td>{{Parametres}}</td>
																<?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			echo '<td>';
			echo '<div class="widget_layout default" data-type="' . $key . '">';
			echo '</div>';
			echo '<div class="widget_layout table" data-type="' . $key . '" style="display:none;">';
			echo '<input type="number" class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::' . $key . '::table::nbLine" style="display:inline-block;width:60px;" /> x ';
			echo '<input type="number" class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::' . $key . '::table::nbColumn" style="display:inline-block;width:60px;" /> ';
			echo '{{Centrer}} : <input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="layout::' . $key . '::table::parameters" data-l3key="center" /><br/>';
			echo '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::' . $key . '::table::parameters" data-l3key="styletd" style="display:inline-block;width:49%;margin-top:3px;" placeholder="{{Style case}}" /> ';
			echo '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::' . $key . '::table::parameters" data-l3key="styletable" style="display:inline-block;width:49%;margin-top:3px;" placeholder="{{Style table}}" /><br/>';
			echo '</div>';
			echo '</td>';
		}
		?>
															</tr>
															<tr>
																<td>{{Options}}</td>
																<?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			echo '<td>';
			echo '<div class="widget_layout default" data-type="' . $key . '">';
			echo '</div>';
			echo '<div class="widget_layout table" data-type="' . $key . '" style="display:none;">';
			echo '<table class="table table-condensed table-bordered">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>';
			echo '{{Commande}}';
			echo '</th>';
			echo '<th>';
			echo '{{Ligne}}';
			echo '</th>';
			echo '<th>';
			echo '{{Colonne}}';
			echo '</th>';
			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
			foreach ($eqLogic->getCmd() as $cmd) {
				if ($cmd->getIsVisible() == 0) {
					continue;
				}
				echo '<tr>';
				echo '<td>';
				echo $cmd->getName();
				echo '</td>';
				echo '<td>';
				echo '<input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::' . $key . '::table::cmd::' . $cmd->getId() . '::line" style="display:inline-block;width:60px;margin-top:3px;" />';
				echo '</td>';
				echo '<td>';
				echo ' <input class="eqLogicAttr form-control input-sm" data-l1key="display" data-l2key="layout::' . $key . '::table::cmd::' . $cmd->getId() . '::column" style="display:inline-block;width:60px;margin-top:3px;" />';
				echo '</td>';

				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '</div>';
			echo '</td>';
		}
		?>
															</tr>
														</tbody>
													</table>
													<script type="text/javascript">
														$('.sel_layout').on('change',function(){
															var type = $(this).attr('data-type');
															$('.widget_layout[data-type='+type+']').hide();
															$('.widget_layout.'+$(this).value()+'[data-type='+type+']').show();
														});
													</script>
													<?php }
	if ($eqLogic->widgetPossibility('custom::optionalParameters')) {
		?>
														<legend><i class="fa fa-pencil-square-o"></i> {{Paramètres optionnels sur la tuile}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>
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
				echo '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fa fa-times"></i> Supprimer</a>';
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
														<legend><i class="fa fa-info-circle"></i> {{Informations Batteries}}</legend>
														<div class="row">
															<div class="col-sm-4" >
																<form class="form-horizontal">
																	<fieldset>
																		<div class="form-group">
																			<label class="col-sm-4 control-label">{{Type de batterie}}</label>
																			<div class="col-sm-4">
																				<span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="battery_type" style="font-size : 1em;"></span>
																			</div>
																		</div>
																	</fieldset>
																</form>
															</div>
														</div>
														<legend><i class="icon techno-fleches"></i> {{Seuils spécifiques Batteries}}</legend>
														<div class="form-group">
															<label class="col-xs-2 eqLogicAttr label label-danger" style="font-size : 1.8em">{{Danger}}</label>
															<div class="col-xs-2">
																<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_danger_threshold" />
															</input>
														</div>
														<label class="col-xs-2 label label-warning" style="font-size : 1.8em">{{Warning}}</label>
														<div class="col-xs-2">
															<input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_warning_threshold" />
														</div>
														<label class="col-xs-2 label label-success" style="font-size : 1.8em">{{Ok}}</label>
													</div>
													<legend><i class="fa fa-clock-o"></i> {{Alertes Communications}}</legend>
													<div class="form-group">
														<label class="col-xs-2 eqLogicAttr label label-danger" style="font-size : 1.8em">{{Danger}}</label>
														<div class="col-xs-2">
															<input class="eqLogicAttr form-control" data-l1key="timeout"/>
														</input>{{(en minute)}}
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="eqLogic_comment">
												<br/>
												<textarea data-l1key="comment" class="form-control eqLogicAttr autogrow" ></textarea>
											</div>
										</div>
										<script>
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
												tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fa fa-times"></i> Supprimer</a>';
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
														$('#md_displayEqLogicConfigure').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
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

											$('#bt_eqLogicConfigureLogRealTime').off('click').on('click', function () {
												$('#md_modal2').dialog({title: "{{Logs}}"});
												$('#md_modal2').load('index.php?v=d&modal=log.display&log=event&search=' + eqLogicInfoSearchString).dialog('open');
											});



										</script>