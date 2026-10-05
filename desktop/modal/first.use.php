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
	throw new Exception('401 - {{Accès non autorisé}}');
}

if (plugin::isInstalled('jeeasy')) {
	try {
		$jeeasy = plugin::byId('jeeasy');
		$update = $jeeasy->getUpdate();
		if (is_object($update)) {
			$update->checkUpdate();
			if ($update->getStatus() == 'update') {
				$update->doUpdate();
			}
		}
		if (!$jeeasy->isActive()) {
			$jeeasy->setIsEnable(1);
		}
		echo "<script>jeedomUtils.loadPage('index.php?v=d&m=jeeasy&p=wizard&noFirstUse=1&step=welcome')</script>";
		die();
	} catch (\Throwable $e) {
		log::add('jeeasy', 'error', log::exception($e));
	}
}

if (config::byKey('jeedom::firstUse') == 1) {
	echo '<a class="btn btn-xs btn-danger" id="bt_doNotDisplayFirstUse">';
	echo '<i class="fas fa-eye-slash"></i> {{Ne plus afficher}}';
	echo '</a>';
}
?>

<div id="first_use">
	<h3>{{Bienvenue dans}} <?= config::byKey('product_name') ?></h3>
	<p>
		{{Comment souhaitez-vous commencer ?}}
	</p>

	<div class="col-sm-6">
		<a class="btn btn-success" id="bt_install_jeeasy">
			<i class="fas fa-hat-wizard"></i> {{Installer l'assistant}}
		</a>
		<p>
			{{Le plugin Jeeasy est l'assistant de configuration officiel. Une fois installé, il vous accompagne pas à pas, de manière ludique et interactive, dans la prise en main de votre habitat connecté.}}
		</p>
	</div>
	<div class="col-sm-6">
		<a class="btn btn-primary" id="bt_restore_save" href="index.php?v=d&p=backup">
			<i class="fas fa-save"></i> {{Restaurer une sauvegarde}}
		</a>
		<p>
			{{Vous disposez d'une sauvegarde d'une précédente installation ? La page de gestion des sauvegardes permet de la restaurer pour retrouver vos équipements, scénarios et plugins.}}
		</p>
	</div>
</div>

<div class="hidden" id="market_connect">
	<h3>{{Connexion au Market}}</h3>
	<p>
		{{L'installation et l'utilisation de l'assistant de configuration nécessitent un accès au Market.}}
		<br>
		{{Veuillez renseigner vos identifiants de connexion.}}
	</p>

	<form class="form-horizontal">
		<div class="form-group">
			<label class="control-label col-md-5">{{Utilisateur}}</label>
			<input type="text" class="form-control col-md-4" id="in_username_market" value="<?= htmlspecialchars(config::byKey('market::username')) ?>" placeholder="{{Nom d'utilisateur Market}}">
		</div>
		<div class="form-group">
			<label class="control-label col-md-5">{{Mot de passe}}</label>
			<input type="password" class="form-control col-md-4" autocomplete="new-password" id="in_password_market" placeholder="{{Mot de passe Market}}">
		</div>
	</form>

	<a class="btn btn-sm btn-default" href="<?= config::byKey('market::address') ?>/index.php?v=d&p=register" target="_blank">
		<i class="fas fa-sign-out-alt"></i> {{Créer un compte Market}}
	</a>
	<a class="btn btn-success" id="bt_validate_market">
		<i class="fas fa-check"></i> {{Valider les identifiants Market}}
	</a>
</div>

<footer>
	<?php
	if (($docUrl = config::byKey('doc::base_url')) != '') {
		echo '<hr class="hrPrimary">';
		$lang = config::byKey('language');
		$docLang = in_array($lang, ['fr_FR', 'en_US', 'es_ES', 'de_DE']) ? $lang : 'en_US';
		echo "{{Besoin d'aide ?}} ";
		echo '<a href="' . $docUrl . '/premiers-pas/' . $docLang . '/#market" target="_blank">';
		echo '<i class="fas fa-book"></i> {{Consultez la documentation Premiers pas}}';
		echo '</a>';
	}
	?>
</footer>

<script>
	(function() { // Self Isolation!
		const modal = jeeDialog.get('#md_firstUse', 'dialog')

		function marketErrorMessage(_error) {
			// -32026: Market rejected the username or password
			return _error.code == -32026 ? "{{Nom d'utilisateur ou mot de passe Market incorrect}}" : _error.message
		}

		function installJeeasy() {
			jeedom.update.save({
				update: {
					'logicalId': 'jeeasy',
					'source': 'market',
					'configuration': {
						// Use Jeeasy beta version only for testing purpose. Must be set to stable for release.
						'version': 'beta'
						// 'version': 'stable'
					}
				},
				error: function(_error) {
					jeedomUtils.showAlert({
						attachTo: modal,
						emptyBefore: true,
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
								attachTo: modal,
								emptyBefore: true,
								message: _error.message,
								level: 'danger'
							})
						},
						success: function() {
							jeedomUtils.loadPage('index.php?v=d&m=jeeasy&p=wizard&noFirstUse=1&step=welcome')
						}
					})
				}
			})
		}

		modal.addEventListener('click', function(event) {
			let _target = null

			if (_target = event.target.closest('#bt_install_jeeasy')) {
				jeedom.repo.test({
					repo: 'market',
					error: function(_error) {
						// -32699: no Market credentials saved yet, -32026: saved ones rejected
						if (_error.code == -32699 || _error.code == -32026) {
							document.getElementById('first_use').unseen()
							document.getElementById('market_connect').seen()
						}
						if (_error.code != -32699) {
							jeedomUtils.showAlert({
								attachTo: modal,
								emptyBefore: true,
								message: marketErrorMessage(_error),
								level: 'danger'
							})
						}
					},
					success: installJeeasy
				})
				return
			}

			if (_target = event.target.closest('#bt_validate_market')) {
				const username = document.getElementById('in_username_market').value.trim()
				const password = document.getElementById('in_password_market').value

				if (username == '' || password == '') {
					jeedomUtils.showAlert({
						attachTo: modal,
						emptyBefore: true,
						message: "{{Veuillez renseigner votre nom d'utilisateur et votre mot de passe Market}}",
						level: 'warning'
					})
					return
				}
				jeedom.config.save({
					configuration: {
						'market::username': username,
						'market::password': password
					},
					error: function(_error) {
						jeedomUtils.showAlert({
							attachTo: modal,
							emptyBefore: true,
							message: _error.message,
							level: 'danger'
						})
					},
					success: function() {
						jeedom.repo.test({
							repo: 'market',
							error: function(_error) {
								jeedomUtils.showAlert({
									attachTo: modal,
									emptyBefore: true,
									message: marketErrorMessage(_error),
									level: 'danger'
								})
							},
							success: installJeeasy
						})
					}
				})
				return
			}

			if (_target = event.target.closest('#bt_doNotDisplayFirstUse')) {
				jeedom.config.save({
					configuration: {
						'jeedom::firstUse': 0
					},
					error: function(_error) {
						jeedomUtils.showAlert({
							attachTo: modal,
							emptyBefore: true,
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
	})()
</script>
