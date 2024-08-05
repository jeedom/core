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

$pluginJeeEasy = false;
try {
	if(is_object(plugin::byId('jeeasy'))) {
		$pluginJeeEasy = true;
	}
} catch (\Throwable $th) {
	
}


$showDoc = false;
if (config::byKey('doc::base_url', 'core') != '') {
	$showDoc = true;
}

$showButton = false;
if (config::byKey('jeedom::firstUse') == 1) {
	$showButton = true;
}

sendVarToJS([
  'jeephp2js.md_firstuse_pluginJeeEasy' => $pluginJeeEasy,
  'jeephp2js.md_firstuse_showDoc' => $showDoc,
  'jeephp2js.md_firstuse_showButton' => $showButton
]);


?>


<div id="md_firstuse" data-modalType="md_firstuse">
	<center>
		{{Bienvenue dans}} <?php echo config::byKey('product_name'); ?> {{, merci d'avoir choisi cette solution pour votre habitat connecté.}}<br />
		{{Voici 4 guides pour bien débuter avec}} <?php echo config::byKey('product_name'); ?> :
	</center>
	<br/><br/><br/>

	<div class="row row-overflow">
		<div class="col-xs-3">
			<center>
				<a href="https://start.jeedom.com/" target="_blank">
					<i class="fas fa-image" style="font-size:40px;"></i><br />
					{{Guide de démarrage}}
				</a>
			</center>
		</div>
		<div id="divDoc" style="display: none;">
			<div class="col-xs-3">
				<center>
					<a href="<?php echo config::byKey('doc::base_url', 'core'); ?>/fr_FR/concept/" target="_blank">
						<i class="fas fa-cogs" style="font-size:40px;"></i><br />
						{{Concept}}
					</a>
				</center>
			</div>

			<div class="col-xs-3">
				<center>
					<a href="<?php echo config::byKey('doc::base_url', 'core'); ?>/fr_FR/premiers-pas/" target="_blank">
						<i class="fas fa-check-square" style="font-size:40px;"></i><br />
						{{Documentation de démarrage}}
					</a>
				</center>
			</div>
			<div class="col-xs-3">
				<center>
					<a href="<?php echo config::byKey('doc::base_url', 'core'); ?>" target="_blank">
						<i class="fas fa-book" style="font-size:40px;"></i><br />
						{{Documentation}}
					</a>
				</center>
			</div>
		</div>
	</div>

	<br><br><br>
	<center>
		<a class="badge cursor" href="https://www.jeedom.com" target="_blank">Site</a> |
		<a class="badge cursor" href="https://blog.jeedom.com/" target="_blank">Blog</a> |
		<a class="badge cursor" href="https://community.jeedom.com/" target="_blank">Community</a> |
		<a class="badge cursor" href="https://market.jeedom.com/" target="_blank">Market</a>
	</center>

	<div id="divButton" style="display: none;">
		<br><br>
		<div class="row">
			<a class="btn btn-default btn-xs pull-right" id="bt_doNotDisplayFirstUse"><i class="fas fa-eye-slash"></i> {{Ne plus afficher}}</a>
		</div>
	</div>
</div>

<script>
(function() {// Self Isolation!
  	if (jeephp2js.md_firstuse_showDoc == "1") {
  		document.querySelector('#md_firstuse #divDoc').seen()
  	}

  	if (jeephp2js.md_firstuse_showButton == "1") {
  		document.querySelector('#md_firstuse #divButton').seen()
  	}

  	if (jeephp2js.md_firstuse_pluginJeeEasy != "") {
		jeeDialog.dialog({
			id: 'md_firstConfig',
			title: "{{Configuration de votre}} <?php echo config::byKey('product_name'); ?>",
			fullScreen: true,
			onClose: function() {
		    	jeeDialog.get('#md_firstConfig').destroy()
		    },
			contentUrl: 'index.php?v=d&plugin=jeeasy&modal=wizard',
			callback: function() {
		    	jeeDialog.get('#md_firstConfig', 'title').querySelector('button.btClose').remove()
		    }
		})
	}

	document.getElementById('bt_doNotDisplayFirstUse')?.addEventListener('click', function() {
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
				jeedomUtils.showAlert({
					message: '{{Option enregistrée}}',
					level: 'success'
				})
			}
		})
	})
})()
</script>
