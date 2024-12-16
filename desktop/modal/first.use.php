<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
try {
	$jeeasy = plugin::byId('jeeasy');
	if (!$jeeasy->isActive()) {
		$jeeasy->setIsEnable(1);
	}
	$jeeasy = $jeeasy->getUpdate();
	$jeeasy->checkUpdate();
	if ($jeeasy->getStatus() == 'update') {
		$jeeasy->doUpdate();
	}
	echo "<script>jeedomUtils.loadPage('index.php?v=d&m=jeeasy&p=wizard')</script>";
	die();
} catch (Exception $e) {
}

$productName = config::byKey('product_name');
if (in_array(strtolower(config::byKey('hardware_name')), ['smart', 'atlas', 'luna'])) {
	echo '<div class="col-md-12">';
	echo '<a href="https://start.jeedom.com/" target="_blank">';
	echo '<i class="fas fa-image"></i> {{Retrouvez le guide de démarrage}}';
	echo '</a>';
	echo ' {{de votre box officielle}} ' . $productName . '.';
	echo '</div>';
}

if (config::byKey('jeedom::firstUse') == 1) {
	echo '<button class="btn btn-xs btn-danger" id="bt_doNotDisplayFirstUse" style="position:absolute;right:15px;">';
	echo '<i class="fas fa-eye-slash"></i> {{Ne plus afficher}}';
	echo '</button>';
}
?>

<div class="text-center">
	<h3 class="first_use">{{Bienvenue dans}} <?= $productName ?></h3>
	<h3 class="market_connect hidden">{{Connexion au Market}}</h3>

	<div class="alert alert-info col-md-10 col-md-offset-1">
		<p class="first_use">
			<?= $productName ?> {{est la solution incontournable dans les domaines du bâtiment intelligent et de l'habitat connecté.}}
			<br><br>
			{{Cliquez sur le bouton "Installer l'assistant}} <?= $productName ?>" {{pour être accompagné de manière ludique et interactive dans la mise en place de votre installation.}}
		</p>
		<p class="market_connect hidden">{{L'assistant}} <?= $productName ?> {{nécessite de pouvoir accéder au Market, veuillez valider vos identifiants de connexion au Market}} <?= $productName ?>.
		</p>

		<form class="form-horizontal market_connect hidden">
			<div class="form-group">
				<label class="control-label col-md-4">{{Utilisateur}}</label>
				<input type="text" class="form-control col-md-8 col-xs-12" id="in_username_market" placeholder="{{Nom d'utilisateur Market}}">
			</div>
			<div class="form-group">
				<label class="control-label col-md-4">{{Mot de passe}}</label>
				<input type="password" class="form-control col-md-8 col-xs-12" autocomplete="new-password" id="in_password_market" placeholder="{{Mot de passe Market}}">
			</div>
		</form>
	</div>

	<a class="btn btn-default market_connect hidden" href="https://market.jeedom.com/index.php?v=d&p=register" target="_blank">
		<i class="fas fa-sign-out-alt"></i> {{Pas de compte Market? En créer un!}}
	</a>
	<button class="btn btn-success market_connect hidden" id="bt_validate_market"><i class="fas fa-check"></i> {{Valider les identifiants Market}}</button>
	<button class="btn btn-success first_use" id="bt_install_jeeasy"><i class="fas fa-sign-in-alt"></i> {{Installer l'assistant}} <?= $productName ?></button>

	<hr class="hrPrimary">
	<?php
	if (($docURl = config::byKey('doc::base_url')) != '') {
		echo '<div class="col-md-12">';
		echo '<a href="' . $docURl . '/' . config::byKey('language') . '/premiers-pas/" target="_blank">';
		echo '<i class="fas fa-book"></i> {{La documentation de démarrage}}';
		echo '</a>';
		echo ' {{détaille les étapes de mise en service votre box}} ' . $productName . '.';
		echo '</div>';
	}
	?>
</div>

<script>
	document.getElementById('md_firstUse').addEventListener('click', function(event) {
		var _target = null

		if (_target = event.target.closest('#bt_install_jeeasy')) {
			jeedom.repo.test({
				repo: 'market',
				error: function() {
					document.querySelectorAll('.first_use').unseen()
					document.querySelectorAll('.market_connect').removeClass('hidden')
				},
				success: function() {
					jeedom.update.save({
						update: {
							'logicalId': 'jeeasy',
							'source': 'market',
							'configuration': {
								'version': 'stable'
							}
						},
						error: function(_error) {
							jeedomUtils.showAlert({
								message: _error.message,
								level: 'danger'
							})
						},
						success: function(_jeeasy) {
							jeedom.update.do({
								id: _jeeasy.id,
								error: function(_error) {
									jeedomUtils.showAlert({
										message: _error.message,
										level: 'danger'
									})
								},
								success: function() {
									jeedom.plugin.toggle({
										id: 'jeeasy',
										state: 1,
										error: function(_error) {
											jeedomUtils.showAlert({
												message: _error.message,
												level: 'danger'
											})
										},
										success: function() {
											jeedomUtils.loadPage('index.php?v=d&m=jeeasy&p=wizard')
										}
									})
								}
							})
						}
					})
				}
			})
			return
		}

		if (_target = event.target.closest('#bt_validate_market')) {
			username = document.getElementById('in_username_market').value.trim()
			password = document.getElementById('in_password_market').value.trim()

			if (username != '' && password != '') {
				jeedom.config.save({
					configuration: {
						'market::username': username,
						'market::password': password
					},
					error: function(_error) {
						jeedomUtils.showAlert({
							message: _error.message,
							level: 'danger'
						})
					},
					success: function() {
						document.querySelectorAll('.market_connect').addClass('hidden')
						document.querySelectorAll('.first_use').seen()
						document.getElementById('bt_install_jeeasy').triggerEvent('click')
					}
				})
			}
			return
		}

		if (_target = event.target.closest('#bt_doNotDisplayFirstUse')) {
			jeedom.config.save({
				configuration: {
					'jeedom::firstUse': 0
				},
				error: function(_error) {
					jeedomUtils.showAlert({
						message: _error.message,
						level: 'danger'
					})
				},
				success: function() {
					jeeDialog.get('#md_firstUse').close()
					jeedomUtils.showAlert({
						message: '{{Demande enregistrée}}',
						level: 'success'
					})
				}
			})
			return
		}
	})
</script>
