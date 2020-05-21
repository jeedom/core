<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
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

	<div style="position: relative; transform: translateY(-50%); top: calc(50% - 120px);">
		<div class="row">
			<div class="col-xs-3">
				<center>
					<a href="https://start.jeedom.com/" target="_blank">
						<i class="fas fa-image" style="font-size:40px;"></i><br/>
						{{Guide de démarrage}}
					</a>
				</center>
			</div>
			<div class="col-xs-3">
				<center>
					<a href="https://doc.jeedom.com/fr_FR/concept/" target="_blank">
						<i class="fas fa-cogs" style="font-size:40px;"></i><br/>
						{{Concept}}
					</a>
				</center>
			</div>
			<div class="col-xs-3">
				<center>
					<a href="https://doc.jeedom.com/fr_FR/premiers-pas/" target="_blank">
						<i class="fas fa-check-square" style="font-size:40px;"></i><br/>
						{{Documentation de démarrage}}
					</a>
				</center>
			</div>
			<div class="col-xs-3">
				<center>
					<a href="https://doc.jeedom.com/" target="_blank">
						<i class="fas fa-book" style="font-size:40px;"></i><br/>
						{{Documentation}}
					</a>
				</center>
			</div>
		</div>

		<br><br><br>
		<center>
			<a class="badge cursor" href="https://www.jeedom.com" target="_blank">Site</a> |
			<a class="badge cursor" href="https://blog.jeedom.com/" target="_blank">Blog</a> |
			<a class="badge cursor" href="https://community.jeedom.com/" target="_blank">Community</a> |
			<a class="badge cursor" href="https://market.jeedom.com/" target="_blank">Market</a>
		</center>
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
