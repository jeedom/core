<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$allObject = jeeObject::buildTree(null, false);
$config_objSummary = config::byKey('object:summary');
sendVarToJS([
	'select_id' => init('id', '-1'),
	'config_objSummary' => $config_objSummary
]);

$synthToActions = array(
	'synthToDashboard' => '{{Dashboard}}',
	'synthToView' => '{{Vue}}',
	'synthToPlan' => '{{Design}}',
	'synthToPlan3d' => '{{Design 3D}}',
);

?>

<div class="row row-overflow">
	<div id="div_resumeObjectList" class="col-xs-12">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="objectListContainer <?php echo (jeedom::getThemeConfig()['theme_displayAsTable'] == 1) ? ' containerAsTable' : ''; ?>">
			<div class="cursor logoPrimary" id="bt_addObject2">
				<div class="center">
					<i class="fas fa-plus-circle"></i>
				</div>
				<span class="txtColor">{{Ajouter}}</span>
			</div>
			<div class="cursor bt_showObjectSummary logoSecondary" >
				<div class="center">
					<i class="fas fa-list"></i>
				</div>
				<span class="txtColor">{{Vue d'ensemble}}</span>
			</div>
		</div>

		<legend><i class="fas fa-image"></i>  {{Mes objets}} <sub class="itemsNumber"></sub></legend>
		<div class="input-group" style="margin-bottom:5px;">
			<input class="form-control roundedLeft" placeholder="{{Rechercher | nom | :not(nom}}" id="in_searchObject"/>
			<div class="input-group-btn">
				<a id="bt_resetObjectSearch" class="btn" style="width:30px"><i class="fas fa-times"></i>
				</a><a class="btn roundedRight" id="bt_displayAsTable" data-card=".objectDisplayCard" data-container=".objectListContainer" data-state="0"><i class="fas fa-grip-lines"></i></a>
			</div>
		</div>
		<div id="objectPanel" class="panel">
			<div class="panel-body">
				<div class="objectListContainer">
					<?php
					$echo = '';
					$class = '';
					if (jeedom::getThemeConfig()['theme_displayAsTable'] == 1) $class = ' displayAsTable';
					foreach ($allObject as $object) {
						$echo .= '<div class="objectDisplayCard cursor'.$class.'" data-object_id="' . $object->getId() . '" data-object_name="' . $object->getName() . '" data-object_icon=\'' . $object->getDisplay('icon', '<i class="far blank"></i>') . '\'>';
						$echo .= $object->getDisplay('icon', '<i class="far blank"></i>');
						$echo .= "<br/>";
						$echo .= '<span class="name" style="background:'.$object->getDisplay('tagColor').';color:'.$object->getDisplay('tagTextColor').'">';
						$echo .= '<span class="hiddenAsCard">'.str_repeat('&nbsp;&nbsp;&nbsp;', $object->getConfiguration('parentNumber')).'</span>';
						$echo .=  $object->getName() . '</span><br/>';
						$echo .=  '<span class="displayTableRight">' . $object->getHtmlSummary().'</span>';
						$echo .= '</div>';
					}
					echo $echo;
					?>
				</div>
			</div>
		</div>
	</div>

	<div id="div_conf" class="hasfloatingbar col-xs-12 object" style="display: none;">
		<div class="floatingbar">
			<div class="input-group">
				<span class="input-group-btn">
					<a class="btn btn-sm roundedLeft" id="bt_graphObject"><i class="fas fa-object-group"></i> {{Liens}}
					</a><a class="btn btn-success btn-sm" id="bt_saveObject"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
					</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeObject"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
				</span>
			</div>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#objecttab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Objet}} (ID : <span class="objectAttr" data-l1key="id" ></span>)</a></a></li>
			<li role="presentation"><a href="#summarytab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Résumé}}</a></li>
			<li role="presentation"><a href="#eqlogicsTab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Résumé par équipements}}</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="objecttab">
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-wrench"></i> {{Général}}</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Nom de l'objet}}</label>
								<div class="col-sm-7">
									<input class="form-control objectAttr" type="text" data-l1key="name" placeholder="{{Nom de l'objet}}"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Objet parent}}</label>
								<div class="col-sm-7">
									<select class="form-control objectAttr" data-l1key="father_id">
										<?php echo jeeObject::getUISelectList(); ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Options}}</label>
								<div class="col-sm-7">
									<label class="checkbox-inline"><input type="checkbox" class="objectAttr" data-l1key="isVisible" checked/>{{Visible}}
										<!-- <sup><i class="fas fa-question-circle tooltips" title="{{Rendre cet objet visible ou non.}}"></i></sup> -->
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Masquer}}</label>
								<div class="col-sm-7">
									<label class="checkbox-inline"><input class="objectAttr" type="checkbox" data-l1key="configuration" data-l2key="hideOnDashboard"/>{{Sur Dashboard}}
										<sup><i class="fas fa-question-circle tooltips" title="{{Masquer cet objet uniquement sur le Dashboard. Il restera visible, notamment dans la liste des objets.}}"></i></sup>
									</label>
									<label class="checkbox-inline"><input class="objectAttr" type="checkbox" data-l1key="configuration" data-l2key="hideOnOverview"/>{{Sur Synthèse}}
										<sup><i class="fas fa-question-circle tooltips" title="{{Masquer cet objet uniquement sur la Synthèse. Il restera visible, notamment dans la liste des objets.}}"></i></sup>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Action depuis la synthèse}}
									<sup><i class="fas fa-question-circle tooltips" title="{{Sur la synthèse, définissez l'action au clic sur la vignette.}}"></i></sup>
								</label>
								<div class="col-sm-4">
									<select class="form-control objectAttr" data-l1key="configuration" data-l2key="synthToAction">
										<?php
										foreach ($synthToActions as $key => $value) {
											echo '<option value="'.$key.'">'.$value.'</option>';
										}
										?>
									</select>
								</div>
								<div class="col-sm-3 hidden">
									<select class="form-control objectAttr" data-l1key="configuration" data-l2key="synthToView">
										<?php
										foreach ((view::all()) as $view) {
											echo '<option value="'.$view->getId().'">'.$view->getName().'</option>';
										}
										?>
									</select>
								</div>
								<div class="col-sm-3 hidden">
									<select class="form-control objectAttr" data-l1key="configuration" data-l2key="synthToPlan">
										<?php
										foreach ((planHeader::all()) as $plan) {
											echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
										}
										?>
									</select>
								</div>
								<div class="col-sm-3 hidden">
									<select class="form-control objectAttr" data-l1key="configuration" data-l2key="synthToPlan3d">
										<?php
										foreach ((plan3dHeader::all()) as $plan) {
											echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
										}
										?>
									</select>
								</div>
							</div>
							<br>
							<legend><i class="fas fa-clipboard-list"></i> {{Informations complémentaires}}</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Type}}</label>
								<div class="col-sm-7">
									<select class="form-control objectAttr" data-l1key="configuration" data-l2key="info::type">
										<option value="room">{{Pièce}}</option>
										<option value="object">{{Objet}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Orientation}}</label>
								<div class="col-sm-7">
									<select class="form-control objectAttr" data-l1key="configuration" data-l2key="info::orientation">
										<option value="0">{{Nord}}</option>
										<option value="45">{{Nord-Est}}</option>
										<option value="90">{{Est}}</option>
										<option value="135">{{Sud-Est}}</option>
										<option value="180">{{Sud}}</option>
										<option value="225">{{Sud-Ouest}}</option>
										<option value="270">{{Ouest}}</option>
										<option value="315">{{Nord-Ouest}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Superficie}} <sub>(m²)</sub></label>
								<div class="col-sm-7">
									<input class="form-control objectAttr" type="number" data-l1key="configuration" data-l2key="info::space"/>
								</div>
							</div>
							<?php
							try {
								$plugins = plugin::listPlugin(true);
								foreach ($plugins as $plugin) {
									$specialAttributes = $plugin->getSpecialAttributes();
									if(!isset($specialAttributes['object']) || !is_array($specialAttributes['object']) || count($specialAttributes['object']) == 0){
										continue;
									}
									echo '<legend><i class="fas fa-users-cog"></i> {{Informations complémentaires demandées par}} '.$plugin->getName().'</legend>';
									foreach ($specialAttributes['object'] as $key => $config) {
										echo '<div class="form-group">';
										echo '<label class="col-sm-3 control-label">'.$config['name'][translate::getLanguage()].'</label>';
										echo '<div class="col-sm-7">';
										switch ($config['type']) {
											case 'input':
											echo '<input class="form-control objectAttr" data-l1key="configuration" data-l2key="plugin::'.$plugin->getId().'::'.$key.'"/>';
											break;
											case 'number':
											echo '<input type="number" class="form-control objectAttr" data-l1key="configuration" data-l2key="plugin::'.$plugin->getId().'::'.$key.'" min="'.(isset($config['min']) ? $config['min'] : '').'" max="'.(isset($config['max']) ? $config['max'] : '').'" />';
											break;
											case 'select':
											echo '<select class="form-control objectAttr" data-l1key="configuration" data-l2key="plugin::'.$plugin->getId().'::'.$key.'">';
											foreach ($config['values'] as $value) {
												echo '<option value="'.$value['value'].'">'.$value['name'].'</option>';
											}
											echo '</select>';
											break;
										}
										echo '</div>';
										echo '</div>';
									}
								}
							} catch (\Exception $e) {

							}
							?>
						</div>

						<div class="col-lg-6">
							<legend><i class="fas fa-swatchbook"></i> {{Affichage}}</legend>
							<div class="form-group">
								<label class="col-xs-12 col-sm-3 control-label">{{Icône}}
									<sup><i class="fas fa-question-circle tooltips" title="{{Activer l'option 'Icônes widgets colorées' dans Interface si nécessaire.}}"></i></sup>
								</label>
								<div class="col-xs-2">
									<div class="objectAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
								</div>
								<div class="col-xs-2">
									<a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Couleurs personnalisées}}</label>
								<div class="col-sm-7">
									<input class="objectAttr" type="checkbox" data-l1key="configuration" data-l2key="useCustomColor"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Couleur du tag}}
									<sup><i class="fas fa-question-circle tooltips" title="{{Couleur de l’objet et des équipements qui lui sont rattachés.}}"></i></sup>
								</label>
								<div class="col-sm-4">
									<input type="color" class="objectAttr form-control" data-l1key="display" data-l2key="tagColor" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Couleur du texte du tag}}</label>
								<div class="col-sm-4">
									<input type="color" class="objectAttr form-control" data-l1key="display" data-l2key="tagTextColor" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Image de fond}}</label>
								<div class="col-sm-7">
									<label class="checkbox-inline"><input class="objectAttr" type="checkbox" data-l1key="configuration" data-l2key="useBackground"/>{{Seulement sur la synthèse}}
										<sup><i class="fas fa-question-circle tooltips" title="{{L'image de fond sera utilisée seulement sur la Synthèse.}}"></i></sup>
									</label>
								</div>
							</div>
								<div class="form-group">
								<div class="col-sm-7 col-sm-offset-3">
									<span class="btn btn-default btn-file">
										<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
									</span>
									<a class="btn btn-default" id="bt_libraryBackgroundImage"><i class="fas fa-photo-video"></i> {{Bibliotheque d'image}}</a>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-7 col-sm-offset-3 objectImg">
									<a class="btn btn-sm btn-danger" id="bt_removeBackgroundImage" style="position:absolute;bottom:0;"><i class="fas fa-trash"></i> {{Enlever l'image}}</a>
									<img class="img-responsive" src="" width="240px"/>
								</div>
							</div>
						</div>

					</fieldset>
				</form>
				<hr>
			</div>
			<div role="tabpanel" class="tab-pane" id="summarytab">
				<?php
				if (count($config_objSummary) == 0) {
					echo '<div class="alert alert-danger">{{Vous n\'avez aucun résumé de créé. Allez dans l\'administration de}} ' . config::byKey('product_name') . ' {{-> Configuration -> onglet Résumés.}}</div>';
				} else {

					?>
					<form class="form-horizontal">
						<fieldset>
							<legend style="cursor:default;"><i class="fas fa-cog"></i>  {{Configuration des résumés}}</legend>
							<table class="table">
								<thead>
									<tr>
										<th></th>
										<?php
										$echo = '';
										foreach (($config_objSummary) as $key => $value) {
											$echo .= '<th style="cursor:default;">' . $value['name'] . '</th>';
										}
										echo $echo;
										?>
									</tr>
								</thead>
								<?php
								$echo = '';
								$echo .= '<tr>';
								$echo .= '<td style="cursor:default;">';
								$echo .= '{{Remonter dans le résumé global}} <sup><i class="fas fa-question-circle" title="{{Activez les résumés qui seront pris en compte pour le résumé global, affiché sur la droite dans la barre de menu.}}"></i></sup>';
								$echo .= '</td>';
								foreach ($config_objSummary as $key => $value) {
									$echo .= '<td>';
									$echo .= '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::global::' . $key . '" />';
									$echo .= '</td>';
								}
								$echo .= '<td><a class="btn btn-xs bt_checkAll" title="{{Tous}}"><i class="fas fa-square"></i></a> <a class="btn btn-xs bt_checkNone" title="{{Aucun}}"><i class="far fa-square"></i></a></td>';
								$echo .= '</tr>';

								$echo .= '<tr>';
								$echo .= '<td style="cursor:default;">';
								$echo .= '{{Masquer en desktop}}';
								$echo .= '</td>';
								foreach ($config_objSummary as $key => $value) {
									$echo .= '<td>';
									$echo .= '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::hide::desktop::' . $key . '" />';
									$echo .= '</td>';
								}
								$echo .= '<td><a class="btn btn-xs bt_checkAll" title="{{Tous}}"><i class="fas fa-square"></i></a> <a class="btn btn-xs bt_checkNone" title="{{Aucun}}"><i class="far fa-square"></i></a></td>';
								$echo .= '</tr>';

								$echo .= '<tr>';
								$echo .= '<td>';
								$echo .= '{{Masquer en mobile}}';
								$echo .= '</td>';
								foreach ($config_objSummary as $key => $value) {
									$echo .= '<td>';
									$echo .= '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::hide::mobile::' . $key . '" />';
									$echo .= '</td>';
								}
								$echo .= '<td><a class="btn btn-xs bt_checkAll" title="{{Tous}}"><i class="fas fa-square"></i></a> <a class="btn btn-xs bt_checkNone" title="{{Aucun}}"><i class="far fa-square"></i></a></td>';
								$echo .= '</tr>';

								echo $echo;
								?>
							</table>
						</fieldset>
					</form>
					<form class="form-horizontal">
						<fieldset>
							<legend style="cursor:default;"><i class="fas fa-tachometer-alt"></i>  {{Commandes des résumés}}
								<sup><i class="fas fa-question-circle" title="{{Pour chaque type de résumé, ajoutez les commandes infos souhaitées.}}"></i></sup>
							</legend>
							<ul class="nav nav-tabs" role="tablist">
								<?php
								$active = 'active';
								$echo = '';
								foreach ($config_objSummary as $key => $value) {
									$echo .= '<li class="' . $active . '"><a href="#summarytab' . $key . '" role="tab" data-toggle="tab">' . $value['icon'] . ' ' . $value['name'] . '</i>  <span class="tabnumber summarytabnumber' . $key . '"></span></a></li>';
									$active = '';
								}
								echo $echo;
								?>
							</ul>
							<div class="tab-content">
								<?php
								$active = ' active';
								$echo = '';
								foreach ($config_objSummary as $key => $value) {
									$echo .=  '<div role="tabpanel" class="tab-pane type' . $key . $active . '" data-type="' . $key . '" id="summarytab' . $key . '">';
									$echo .=  '<a class="btn btn-sm btn-success pull-right addSummary" data-type="' . $key . '"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>';
									$echo .=  '<br/>';
									$echo .=  '<div class="div_summary" data-type="' . $key . '"></div>';
									$echo .=  '</div>';
									$active = '';
								}
								echo $echo;
								?>
							</div>
						</fieldset>
					</form>
					<?php
				}
				?>
			</div>

			<div role="tabpanel" class="tab-pane" id="eqlogicsTab" style="margin-bottom: 200px;">
				<br/>
				<div class="input-group" style="margin-bottom:5px;">
					<input class="form-control roundedLeft" placeholder="{{Rechercher un équipement de cet objet}}" id="in_searchCmds"/>
					<div class="input-group-btn">
						<a id="bt_resetCmdSearch" class="btn" style="width:30px"><i class="fas fa-times"></i>
						</a><a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i>
						</a><a class="btn roundedRight" id="bt_closeAll"><i class="fas fa-folder"></i></a>
					</div>
				</div>
				<div id="eqLogicsCmds"></div>
			</div>
		</div>
	</div>
</div>

<?php include_file("desktop", "object", "js");?>
