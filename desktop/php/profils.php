<?php
if (!isConnect()) {
	throw new Exception('{{Error 401 Unauthorized}}');
}
if (init('user_id') == '') {
	@session_start();
	$_SESSION['user']->refresh();
	@session_write_close();
	sendVarToJs('profils_user_id', -1);
	$user = $_SESSION['user'];
} else {
	sendVarToJs('profils_user_id', init('user_id'));
	$user = user::byId(init('user_id'));
}
$homePageDesktop = array(
	'core::dashboard' => '{{Dashboard}}',
	'core::overview' => '{{Synthèse}}',
	'core::view' => '{{Vue}}',
	'core::plan' => '{{Design}}',
	'core::plan3d' => '{{Design 3D}}',
);
$homePageMobile = array(
	'core::dashboard' => '{{Dashboard}}',
	'core::overview' => '{{Synthèse}}',
	'core::view' => '{{Vue}}',
	'core::plan' => '{{Design}}',
	'core::plan3d' => '{{Design 3D}}',
);
foreach ((plugin::listPlugin()) as $pluginList) {
	if ($pluginList->isActive() == 1 && $pluginList->getDisplay() != '' && config::byKey('displayDesktopPanel', $pluginList->getId(), 0) != 0) {
		$homePageDesktop[$pluginList->getId() . '::' . $pluginList->getDisplay()] = $pluginList->getName();
	}
	if ($pluginList->isActive() == 1 && $pluginList->getMobile() != '' && config::byKey('displayMobilePanel', $pluginList->getId(), 0) != 0) {
		$homePageMobile[$pluginList->getId() . '::' . $pluginList->getMobile()] = $pluginList->getName();
	}
}
$objectOptions = jeeObject::getUISelectList(false);
?>
<div style="display: none;" id="div_alertProfils"></div>
<div class="row row-overflow" id="div_userProfils">
	<div class="hasfloatingbar col-xs-12">
		<div class="floatingbar">
			<div>
				<a class="btn btn-sm btn-success" id="bt_saveProfils"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
			</div>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#interfacetab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-briefcase"></i> {{Préférences}}</a></li>
			<?php if (init('user_id') == '') { ?>
				<li role="presentation"><a href="#securitytab" aria-controls="profile" role="tab" data-toggle="tab"><i class="icon securite-key1"></i> {{Sécurité}}</a></li>
			<?php } ?>
		</ul>

		<div class="tab-content" style="overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="interfacetab">
				<br>
				<form class="form-horizontal">
					<fieldset>
						<legend><i class="fas fa-laptop"></i> {{Interface}}</legend>
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-12 control-label"><i class="far fa-file"></i> {{Page par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Page affichée après connexion}}"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="homePage">
									<?php
									foreach ($homePageDesktop as $key => $value) {
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="homePageMobile">
									<option value="home">{{Accueil}}</option>
									<?php
									foreach ($homePageMobile as $key => $value) {
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-12 control-label"><i class="fas fa-columns"></i> {{Objet par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Objet affiché lors de l’arrivée sur le dashboard}}"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDashboardObject">
									<?php
									echo $objectOptions;
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobileObject">
									<option value="all">{{Tout}}</option>
									<?php
									echo $objectOptions;
									?>
								</select>
							</div>
						</div>
						<hr class="hrPrimary">
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-12 control-label"><i class="fas fa-eye"></i> {{Vue par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Vue affichée lors de l’arrivée sur le dashboard}}"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDesktopView">
									<?php
									foreach ((view::all()) as $view) {
										echo '<option value="'.$view->getId().'">'.$view->getName().'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobileView">
									<?php
									foreach ((view::all()) as $view) {
										echo '<option value="'.$view->getId().'">'.$view->getName().'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-6 control-label">{{Déplier le panneau des vues}}</label>
							<div class="col-sm-1 col-xs-6">
								<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="displayViewByDefault"/>
							</div>
						</div>
						<hr class="hrPrimary">
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-12 control-label"><i class="fas fa-paint-brush"></i> {{Design par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Design affiché lors de l’arrivée sur le dashboard}}"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i> {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDashboardPlan">
									<?php
									foreach ((planHeader::all()) as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobilePlan">
									<?php
									foreach ((planHeader::all()) as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-6 control-label">{{Designs Plein écran}}</label>
							<div class="col-sm-1 col-xs-6">
								<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="defaultPlanFullScreen" />
							</div>
						</div>
						<hr class="hrPrimary">
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-12 control-label"><i class="fas fa-paint-brush"></i> {{Design 3D par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Design 3D affiché lors de l’arrivée sur le dashboard}}"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDashboardPlan3d">
									<?php
									foreach ((plan3dHeader::all()) as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobilePlan3d">
									<?php
									foreach ((plan3dHeader::all()) as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-6 control-label">{{Designs 3D Plein écran}}</label>
							<div class="col-sm-1 col-xs-6">
								<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="defaultPlan3dFullScreen" />
							</div>
						</div>

						<legend><i class="icon securite-key1"></i> {{Notifications}}</legend>
						<div class="form-group">
							<label class="col-lg-2 col-sm-4 col-xs-12 control-label">{{Commande de notification utilisateur}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Commande par défaut pour vous joindre (commande de type message).}}"></i></sup>
							</label>
							<div class="col-sm-3 col-xs-12">
								<div class="input-group">
									<input type="text" class="userAttr form-control roundedLeft" data-l1key="options" data-l2key="notification::cmd" />
									<span class="input-group-btn">
										<a class="btn btn-default cursor bt_selectWarnMeCmd roundedRight" title="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></a>
									</span>
								</div>
							</div>
						</div>
						<?php
						try {
							$plugins = plugin::listPlugin(true);
							foreach ($plugins as $plugin) {
								$specialAttributes = $plugin->getSpecialAttributes();
								if (!isset($specialAttributes['user']) || !is_array($specialAttributes['user']) || count($specialAttributes['user']) == 0) {
									continue;
								}
								$div = '<legend><i class="fas fa-users-cog"></i> {{Informations complémentaires demandées par}} '.$plugin->getName().'</legend>';
								foreach ($specialAttributes['user'] as $key => $config) {
									$div .= '<div class="form-group">';
									$div .= '<label class="col-lg-2 col-sm-4 col-xs-2 control-label">'.$config['name'][translate::getLanguage()].'</label>';
									$div .= '<div class="col-lg-2 col-sm-4 col-xs-3">';
									switch ($config['type']) {
										case 'input':
										$div .= '<input class="form-control userAttr" data-l1key="options" data-l2key="plugin::'.$plugin->getId().'::'.$key.'"/>';
										break;
										case 'number':
										$div .= '<input type="number" class="form-control userAttr" data-l1key="options" data-l2key="plugin::'.$plugin->getId().'::'.$key.'" min="'.(isset($config['min']) ? $config['min'] : '').'" max="'.(isset($config['max']) ? $config['max'] : '').'" />';
										break;
										case 'select':
										$div .= '<select class="form-control userAttr" data-l1key="options" data-l2key="plugin::'.$plugin->getId().'::'.$key.'">';
										foreach ($config['values'] as $value) {
											$div .= '<option value="'.$value['value'].'">'.$value['name'].'</option>';
										}
										$div .= '</select>';
										break;
									}
									$div .= '</div>';
									$div .= '</div>';
									echo $div;
								}
							}
						} catch (\Exception $e) {

						}
						?>
					</fieldset>
				</form>
			</div>
			<?php if (init('user_id') == '') { ?>
				<div role="tabpanel" class="tab-pane" id="securitytab">
					<br/>
					<form class="form-horizontal">
						<fieldset>
							<legend><i class="fas fa-user-secret"></i> {{Utilisateur}}</legend>
							<?php if (config::byKey('sso:allowRemoteUser') != 1) {
								?>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Authentification en 2 étapes}}</label>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
										<a class="btn btn-default btn-sm" id="bt_configureTwoFactorAuthentification"><i class="fas fa-cogs"></i> {{Configurer}}</a>
									</div>
									<?php
									if ($user->getOptions('twoFactorAuthentification', 0) == 1) {
										?>
										<label class="col-lg-1 col-md-2 col-sm-2 col-xs-2 control-label">{{Actif}}</label>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
											<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="twoFactorAuthentification" />
										</div>
									<?php }
									?>
								</div>

								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mot de passe}}</label>
									<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
										<input type="text" class="inputPassword userAttr form-control" data-l1key="password" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Retapez le mot de passe}}</label>
									<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
										<input type="text" class="inputPassword form-control" id="in_passwordCheck" />
									</div>
								</div>
							<?php }
							?>
							<div class="form-group">
								<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Hash de l'utilisateur}}</label>
								<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
									<span class="userAttr" data-l1key="hash"></span>
								</div>
								<div class="col-lg-2 col-md-3 col-sm-3">
									<a class="btn btn-default btn-sm" id="bt_genUserKeyAPI"><i class="fas fa-refresh"></i> {{Regénérer le Hash}}</a>
								</div>
							</div>
						</fieldset>
					</form>

					<form class="form-horizontal">
						<fieldset>
							<legend><i class="fas fa-house-user"></i> {{Session(s) active(s)}}</legend>
							<table class="table table-condensed table-bordered">
								<thead>
									<tr>
										<th>{{ID}}</th>
										<th>{{IP}}</th>
										<th>{{Date}}</th>
										<th style="width:80px;">{{Actions}}</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sessions = listSession();
									if (count($sessions) > 0) {
										foreach ($sessions as $id => $session) {
											if ($session['user_id'] != 	$user->getId()) {
												continue;
											}
											$tr = '';
											$tr .= '<tr data-id="' . $id . '">';
											$tr .= '<td>' . $id . '</td>';
											$tr .= '<td>' . $session['ip'] . '</td>';
											$tr .= '<td>' . $session['datetime'] . '</td>';
											$tr .= '<td><a class="btn btn-xs btn-warning bt_deleteSession"><i class="fas fa-sign-out-alt"></i> {{Déconnecter}}</a></td>';
											$tr .= '</tr>';
											echo $tr;
										}
									}
									?>
								</tbody>
							</table>
						</fieldset>
					</form>

					<form class="form-horizontal">
						<fieldset>
							<legend><i class="fas fa-laptop-house"></i> {{Périphérique(s) enregistré(s)}} <a class="btn btn-xs btn-danger pull-right" id="bt_removeAllRegisterDevice"><i class="fas fa-trash"></i> {{Supprimer tout}}</a></legend>
							<table id="tableDevices" class="table table-bordered table-condensed tablesorter">
								<thead>
									<tr>
										<th>{{Identification}}</th>
										<th>{{IP}}</th>
										<th>{{Date dernière utilisation}}</th>
										<th data-sorter="false" data-filter="false">{{Action}}</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach (($user->getOptions('registerDevice')) as $key => $value) {
										$tr = '';
										$tr .= '<tr data-key="' . $key . '">';
										$tr .= '<td title="'.$key.'">';
										$tr .= substr($key, 0, 20) . '...';
										$tr .= '</td>';
										$tr .= '<td>';
										$tr .= $value['ip'];
										$tr .= '</td>';
										$tr .= '<td>';
										$tr .= $value['datetime'];
										$tr .= '</td>';
										$tr .= '<td>';
										$tr .= '<a class="btn btn-danger btn-xs bt_removeRegisterDevice"><i class="fas fa-trash"></i> {{Supprimer}}</a>';
										$tr .= '</td>';
										$tr .= '</tr>';
										echo $tr;
									}
									?>
								</tbody>
							</table>
						</fieldset>
					</form>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php include_file("desktop", "profils", "js");?>