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
	repo_market::test();

	try {
		if (!is_object($jeeasy = update::byLogicalId('jeeasy'))) {
			$jeeasy = (new update)
				->setLogicalId('jeeasy')
				->setSource('market')
				->setConfiguration('version', 'stable');
			$jeeasy->save();
			$jeeasy->doUpdate();
		} else {
			$jeeasy->checkUpdate();
			if ($jeeasy->getStatus() == 'update') {
				$jeeasy->doUpdate();
			}
		}

		$jeeasy = plugin::byId('jeeasy');
		if (!$jeeasy->isActive()) {
			$jeeasy->setIsEnable(1);
		}
	} catch (Exception $e) {
		echo '<div class="alert alert-danger text-center">';
		echo "{{Impossible de démarrer l'assistant de configuration.}}";
		echo '</div>';
		return;
	}
?>
	<script>
		jeeDialog.get('#md_firstUse').close()

		jeeDialog.dialog({
			id: 'md_firstConfig',
			title: "{{Configuration de}} <?= config::byKey('product_name'); ?>",
			fullScreen: true,
			onClose: function() {
				jeeDialog.get('#md_firstConfig').destroy()
			},
			contentUrl: 'index.php?v=d&plugin=jeeasy&modal=welcome',
			callback: function() {
				jeeDialog.get('#md_firstConfig', 'title').querySelector('button.btClose').remove()
			}
		})
	</script>
<?php
} catch (Exception $e) {
?>
	<div class="text-center">
		<h3>{{Connexion au Market}}</h3>

		<div class="alert alert-info col-md-10 col-md-offset-1">
			<strong>{{L' assistant de configuration}} <?= config::byKey('product_name') ?> {{nécessite de pouvoir accéder au Market, merci de valider vos identifiants de connexion au Market}} <?= config::byKey('product_name') ?>.
			</strong>
			<br><br>
			<form class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-md-4">{{Utilisateur}} :</label>
					<input type="text" class="form-control col-md-8 col-xs-12" id="in_username_market" placeholder="{{Nom d'utilisateur Market}}">
				</div>
				<div class="form-group">
					<label class="control-label col-md-4">{{Mot de passe}} :</label>
					<input type="password" class="form-control col-md-8 col-xs-12" autocomplete="new-password" id="in_password_market" placeholder="{{Mot de passe Market}}">
				</div>
			</form>
			<?php
			if (config::byKey('doc::base_url', 'core') != '') {
				echo '<br>';
				echo '<a class="btn btn-xs btn-default" href="https://market.jeedom.com/index.php?v=d&p=register" target="_blank">';
				echo '<i class="fas fa-sign-out-alt"></i> {{Pas de compte Market ? En créer un !}}';
				echo '</a>';
			}
			?>
		</div>

		<button class="btn btn-success" id="bt_validate_market"><i class="fas fa-sign-in-alt"></i> {{Valider}}</button>
		<?php
		if (config::byKey('jeedom::firstUse') == 1) {
			echo '<button class="btn btn-danger" id="bt_doNotDisplayFirstUse"><i class="fas fa-times"></i> {{Ne jamais redemander}}</button>';
		}
		if (config::byKey('doc::base_url', 'core') != '') {
			echo '<br><br>';
			echo '<div>';
			echo "{{De plus amples informations sont disponibles à la lecture de}} ";
			echo '<a href="https://doc.jeedom.com/fr_FR/premiers-pas/" target="_blank">{{la documentation de démarrage}}.</a></div>';
		}
		?>
	</div>
	<script>
		document.getElementById('md_firstUse').addEventListener('click', function(event) {
			var _target = null

			if (_target = event.target.closest('#bt_doNotDisplayFirstUse')) {
				jeedom.config.save({
					configuration: {
						'jeedom::firstUse': 0
					},
					error: function(error) {
						jeedomUtils.showAlert({
							message: error.message,
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

			if (_target = event.target.closest('#bt_validate_market')) {
				username = document.getElementById('in_username_market').value.trim()
				password = document.getElementById('in_password_market').value.trim()

				if (username != '' && password != '') {
					jeedom.config.save({
						configuration: {
							'jeedom::firstUse': 1,
							'market::username': username,
							'market::password': password
						},
						error: function(error) {
							jeedomUtils.showAlert({
								message: error.message,
								level: 'danger'
							})
						},
						success: function() {
							window.location.reload()
						}
					})
				}
				return
			}
		})
	</script>
<?php
}
?>
