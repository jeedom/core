<?php
if (!isConnect('admin')) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
?>

<style>
	#div_reboot_jeedom_texte {
		width: 400px;
		margin: auto;
		text-align: center;
	}
	#contenu {
		width: 400px;
		margin: auto;
	}
</style>

<div id="contenu">
	<h2 style="opacity:0.8;"><span class="glyphicon glyphicon-refresh"></span> {{Redémarrage}}</h2>
	<div class="progress">
		<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" id="progressbar_reboot" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
			<span class="sr-only"></span>
		</div>
	</div>
	<div id="div_reboot_jeedom_texte"><h6>{{Merci de patienter...}}</h6></div>
</div>

<iframe src="" id="iframe_reboot" style="display:none"></iframe>

<div id="div_reboot_jeedom" style="display:none;">
	<script type="text/javascript" id="reboot_jeedom"></script>
</div>

<?php include_file("desktop", "reboot", "js");?>
