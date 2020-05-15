<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<?php
/* LANCEMENT DE JEEASY */
try {
	$plugin = plugin::byId('jeeasy');
	?>
	<script>
	$( document ).ready(function() {
		$('#md_modal').dialog({title: "{{Configuration de votre}} <?php echo config::byKey('product_name'); ?>"});
		$('#md_modal').load('index.php?v=d&plugin=jeeasy&modal=wizard').dialog('open');
	});
	</script>
	<?php
} catch (Exception $e) {
	?>
	<legend>
		{{Mes premiers pas dans}} <?php echo config::byKey('product_name'); ?>
		<a class='btn btn-default btn-xs pull-right' id='bt_doNotDisplayFirstUse'><i class="fas fa-eye-slash"></i> Ne plus afficher</a>
	</legend>
	<div id="div_alertFirstUse"></div>
	<center>
		{{Bienvenue dans}} <?php echo config::byKey('product_name'); ?> {{, et merci d'avoir choisi cet outil pour votre habitat connecté. Voici 3 guides pour bien débuter avec}} <?php echo config::byKey('product_name'); ?> :
	</center>
	<br/><br/>

	<div class="row">
		<div class="col-xs-4">
			<center>
				<a href="https://start.jeedom.com/" target="_blank">
					<i class="fas fa-image" style="font-size:12em;"></i><br/>
					{{Guide de démarrage}}
				</a>
			</center>
		</div>
		<div class="col-xs-4">
			<center>
				<a href="https://doc.jeedom.com/fr_FR/premiers-pas/" target="_blank">
					<i class="fas fa-check-square" style="font-size:12em;"></i><br/>
					{{Documentation de démarrage}}
				</a>
			</center>
		</div>
		<div class="col-xs-4">
			<center>
				<a href="https://doc.jeedom.com/" target="_blank">
					<i class="fas fa-book" style="font-size:12em;"></i><br/>
					{{Documentation}}
				</a>
			</center>
		</div>
	</div>
	<script>
	$('#bt_doNotDisplayFirstUse').on('click', function () {
		jeedom.config.save({
			configuration: {'jeedom::firstUse': 0},
			error: function (error) {
				$('#div_alertFirstUse').showAlert({message: error.message, level: 'danger'});
			},
			success: function () {
				$('#div_alertFirstUse').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
			}
		});
	});
</script>
<?php } ?>
