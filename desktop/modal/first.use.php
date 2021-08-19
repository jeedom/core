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
?>
<?php
/* LANCEMENT DE JEEASY */
$find_jeeasy = false;
try {
	$plugin = plugin::byId('jeeasy');
	$find_jeeasy = true;
?>
	<script>
		$(document).ready(function() {
			$('#md_modal').dialog({
				title: "{{Configuration de votre}} <?php echo config::byKey('product_name'); ?>"
			}).load('index.php?v=d&plugin=jeeasy&modal=wizard').dialog('open');
		})
	</script>
<?php
} catch (Exception $e) {
?>
	<div id="div_alertFirstUse"></div>
	<center>
		{{Bienvenue dans}} <?php echo config::byKey('product_name'); ?> {{, et merci d'avoir choisi cette solution pour votre habitat connecté.}}<br />
		{{Voici 4 guides pour bien débuter avec}} <?php echo config::byKey('product_name'); ?> :
	</center>
	<br /><br /><br />

	<div style="position: relative; transform: translateY(-50%); top: calc(50% - 120px);">
		<div class="row">
			<div class="col-xs-3">
				<center>
					<a href="https://start.jeedom.com/" target="_blank">
						<i class="fas fa-image" style="font-size:40px;"></i><br />
						{{Guide de démarrage}}
					</a>
				</center>
			</div>
			<?php if (config::byKey('doc::base_url', 'core') != '') { ?>
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
			<?php } ?>
		</div>

		<br><br><br>
		<center>
			<a class="badge cursor" href="https://www.jeedom.com" target="_blank">Site</a> |
			<a class="badge cursor" href="https://blog.jeedom.com/" target="_blank">Blog</a> |
			<a class="badge cursor" href="https://community.jeedom.com/" target="_blank">Community</a> |
			<a class="badge cursor" href="https://market.jeedom.com/" target="_blank">Market</a>
		</center>

		<?php
		if (config::byKey('jeedom::firstUse') == 1 && $find_jeeasy) {
			$divButton = '<br><br>';
			$divButton .= '<div class="row">';
			$divButton .= '<div class="col-xs-12">';
			$divButton .= '<a class="btn btn-default btn-xs pull-right" id="bt_doNotDisplayFirstUse"><i class="fas fa-eye-slash"></i> {{Ne plus afficher}}</a>';
			$divButton .= '</div>';
			$divButton .= '</div>';
			echo $divButton;
		}
		?>
	</div>

	<script>
		var modalContent = $('#div_alertFirstUse').parents('.ui-dialog-content.ui-widget-content')
		var modal = modalContent.parents('.ui-dialog.ui-resizable')
		if ($(window).width() > 800) {
			width = 720
			height = 400
			modal.width(width).height(height)
			modal.position({
				my: "center",
				at: "center",
				of: window
			})
			modalContent.width(width - 26).height(height - 40)
		}

		$('#bt_doNotDisplayFirstUse').on('click', function() {
			jeedom.config.save({
				configuration: {
					'jeedom::firstUse': 0
				},
				error: function(error) {
					$('#div_alertFirstUse').showAlert({
						message: error.message,
						level: 'danger'
					})
				},
				success: function() {
					$('#div_alertFirstUse').showAlert({
						message: '{{Sauvegarde réussie}}',
						level: 'success'
					})
				}
			})
		})
	</script>
<?php } ?>