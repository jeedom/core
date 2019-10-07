<?php
if (!isConnect()) {
	throw new Exception('{{Error 401 Unauthorized');
	}
	@session_start();
	$_SESSION['user']->refresh();
	@session_write_close();
	$homePageDesktop = array(
		'core::dashboard' => '{{Dashboard}}',
		'core::view' => '{{Vue}}',
		'core::plan' => '{{Design}}',
		'core::plan3d' => '{{Design 3D}}',
	);
	$homePageMobile = array(
		'core::dashboard' => '{{Dashboard}}',
		'core::view' => '{{Vue}}',
		'core::plan' => '{{Design}}',
		'core::plan3d' => '{{Design 3D}}',
	);
	foreach (plugin::listPlugin() as $pluginList) {
		if ($pluginList->isActive() == 1 && $pluginList->getDisplay() != '' && config::byKey('displayDesktopPanel', $pluginList->getId(), 0) != 0) {
			$homePageDesktop[$pluginList->getId() . '::' . $pluginList->getDisplay()] = $pluginList->getName();
		}
		if ($pluginList->isActive() == 1 && $pluginList->getMobile() != '' && config::byKey('displayMobilePanel', $pluginList->getId(), 0) != 0) {
			$homePageMobile[$pluginList->getId() . '::' . $pluginList->getMobile()] = $pluginList->getName();
		}
	}
	?>
	<div style="margin-top: 5px;">
		<a class="btn btn-success pull-right btn-sm" id="bt_saveProfils"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#interfacetab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-briefcase"></i> {{Préférences}}</a></li>
			<li role="presentation"><a href="#securitytab" aria-controls="profile" role="tab" data-toggle="tab"><i class="icon securite-key1"></i> {{Sécurité}}</a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="interfacetab">
				<br>
				<form class="form-horizontal">
					<fieldset>
						<legend><i class="fas fa-laptop"></i> {{Interface}}</legend>

						<div class="form-group">
							<label class="col-sm-4 col-xs-6 control-label"><i class="fas fa-home"></i> {{Panneau des objets sur le Dashboard}}
								<sup><i class="fas fa-question-circle tooltips" title="Affiche le panneau des objets sur le dashboard"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6">
								<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="displayObjetByDefault"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 col-xs-12 control-label"><i class="far fa-file"></i> {{Page par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="Page affichée après connexion"></i></sup>
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
							<label class="col-sm-4 col-xs-12 control-label"><i class="fas fa-columns"></i> {{Objet par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="Objet affiché lors de l’arrivée sur le dashboard"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDashboardObject">
									<?php
									foreach (jeeObject::all() as $object) {
										echo '<option value="'.$object->getId().'">'.$object->getName().'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobileObject">
									<option value="all">{{Tout}}</option>
									<?php
									foreach (jeeObject::all() as $object) {
										echo '<option value="'.$object->getId().'">'.$object->getName().'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<hr class="hrPrimary">
						<div class="form-group">
							<label class="col-sm-4 col-xs-12 control-label"><i class="fas fa-eye"></i> {{Vue par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="Vue affichée lors de l’arrivée sur le dashboard"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDesktopView">
									<?php
									foreach (view::all() as $view) {
										echo '<option value="'.$view->getId().'">'.$view->getName().'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobileView">
									<?php
									foreach (view::all() as $view) {
										echo '<option value="'.$view->getId().'">'.$view->getName().'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 col-xs-6 control-label">{{Déplier le panneau des vues}}</label>
							<div class="col-sm-1 col-xs-6">
								<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="displayViewByDefault"/>
							</div>
						</div>
						<hr class="hrPrimary">
						<div class="form-group">
							<label class="col-sm-4 col-xs-12 control-label"><i class="fas fa-paint-brush"></i> {{Design par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="Design affiché lors de l’arrivée sur le dashboard"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDashboardPlan">
									<?php
									foreach (planHeader::all() as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobilePlan">
									<?php
									foreach (planHeader::all() as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 col-xs-6 control-label">{{Designs Plein écran}}</label>
							<div class="col-sm-1 col-xs-6">
								<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="defaultPlanFullScreen" />
							</div>
						</div>
						<hr class="hrPrimary">
						<div class="form-group">
							<label class="col-sm-4 col-xs-12 control-label"><i class="fas fa-paint-brush"></i> {{Design 3D par défaut}}
								<sup><i class="fas fa-question-circle tooltips" title="Design 3D affiché lors de l’arrivée sur le dashboard"></i></sup>
							</label>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-desktop"></i>  {{Desktop}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultDashboardPlan3d">
									<?php
									foreach (plan3dHeader::all() as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
							<div class="col-sm-1 col-xs-6 control-label"><i class="fas fa-tablet-alt"></i> {{Mobile}}</div>
							<div class="col-sm-2 col-xs-6">
								<select class="form-control userAttr" data-l1key="options" data-l2key="defaultMobilePlan3d">
									<?php
									foreach (plan3dHeader::all() as $plan) {
										echo '<option value="'.$plan->getId().'">'.$plan->getName().'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 col-xs-6 control-label">{{Designs 3D Plein écran}}</label>
							<div class="col-sm-1 col-xs-6">
								<input type="checkbox" class="userAttr" data-l1key="options" data-l2key="defaultPlan3dFullScreen" />
							</div>
						</div>

						<legend><i class="icon securite-key1"></i> {{Notifications}}</legend>
						<div class="form-group">
							<label class="col-sm-4 col-xs-12 control-label">{{Commande de notification utilisateur}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Commande par défaut pour vous joindre (commande de type message).}}"></i></sup>
							</label>
							<div class="col-sm-3 col-xs-12">
								<div class="input-group">
									<input type="text" class="userAttr form-control roundedLeft" data-l1key="options" data-l2key="notification::cmd" />
									<span class="input-group-btn">
										<a class="btn btn-default cursor bt_selectWarnMeCmd roundedRight" title="Rechercher une commande"><i class="fas fa-list-alt"></i></a>
									</span>
								</div>
							</div>
						</div>

						<fieldset>
						</form>
					</div>

					<div role="tabpanel" class="tab-pane" id="securitytab">
						<br/>
						<form class="form-horizontal">
							<fieldset>
								<?php if (config::byKey('sso:allowRemoteUser') != 1) {
									?>
									<div class="form-group">
										<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Authentification en 2 étapes}}</label>
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
											<a class="btn btn-default" id="bt_configureTwoFactorAuthentification"><i class="fas fa-cogs"></i> {{Configurer}}</a>
										</div>
										<?php
										if ($_SESSION['user']->getOptions('twoFactorAuthentification', 0) == 1) {
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
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label"></label>
									<div class="col-lg-2 col-md-3 col-sm-3">
										<a class="btn btn-default form-control" id="bt_genUserKeyAPI"><i class="fas fa-refresh"></i> {{Regénérer le Hash}}</a>
									</div>
								</div>
							</fieldset>
						</form>

						<form class="form-horizontal">
							<fieldset>
								<legend>{{Session(s) active(s)}}</legend>
								<table class="table table-condensed table-bordered">
									<thead>
										<tr>
											<th>{{ID}}</th><th>{{IP}}</th><th>{{Date}}</th><th>{{Actions}}</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sessions = listSession();
										if(count($sessions) > 0){
											foreach ($sessions as $id => $session) {
												if ($session['user_id'] != $_SESSION['user']->getId()) {
													continue;
												}
												echo '<tr data-id="' . $id . '">';
												echo '<td>' . $id . '</td>';
												echo '<td>' . $session['ip'] . '</td>';
												echo '<td>' . $session['datetime'] . '</td>';
												echo '<td><a class="btn btn-xs btn-warning bt_deleteSession"><i class="fas fa-sign-out-alt"></i> {{Déconnecter}}</a></td>';
												echo '</tr>';
											}
										}
										?>
									</tbody>
								</table>
							</fieldset>
						</form>

						<form class="form-horizontal">
							<fieldset>
								<legend>{{Périphérique(s) enregistré(s)}} <a class="btn btn-xs btn-danger pull-right" id="bt_removeAllRegisterDevice"><i class="fas fa-trash"></i> {{Supprimer tout}}</a></legend>
								<table class="table table-bordered table-condensed">
									<thead>
										<tr>
											<th>{{Identification}}</th>
											<th>{{IP}}</th>
											<th>{{Date dernière utilisation}}</th>
											<th>{{Action}}</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($_SESSION['user']->getOptions('registerDevice') as $key => $value) {
											echo '<tr data-key="' . $key . '">';
											echo '<td title="'.$key.'">';
											echo substr($key, 0, 10) . '...';
											echo '</td>';
											echo '<td>';
											echo $value['ip'];
											echo '</td>';
											echo '<td>';
											echo $value['datetime'];
											echo '</td>';
											echo '<td>';
											echo '<a class="btn btn-danger btn-xs bt_removeRegisterDevice"><i class="fas fa-trash"></i> {{Supprimer}}</a>';
											echo '</td>';
											echo '</tr>';
										}

										?>
									</tbody>
								</table>
							</fieldset>
						</form>
					</div>

				</div>
			</div>
			<?php include_file("desktop", "profils", "js");?>
