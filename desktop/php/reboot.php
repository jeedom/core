<?php
if (!isConnect('admin')) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
?>

<style>
#div_reboot_jeedom_texte{
	width: 400px;
	margin: auto;
	text-align: center;
}
#contenu{
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

<script type="text/javascript">
var rebooti = '0';
var testjeedom = '0';

bootbox.confirm('{{Êtes-vous sûr de vouloir redémarrer le système ?}}', function (result) {
	if (result) {
		jeedom.rebootSystem();
		setTimeout('reboot_jeedom(rebooti)', 10000);
		$('#progressbar_reboot').width('5%');
	}else{
		loadPage('index.php?v=d&p=dashboard');
	}
});

function refresh() {
	$.ajax({
		url: "desktop/js/rebootjs.js?t="+Date.now(),
		success:function(retour){
			$('reboot_jeedom').html(retour);
		}
	});
}

function page_rebootjs(rebooti){
	refresh();
	if(rebooti=='1'){
		$('#div_reboot_jeedom_texte').empty().html('<h6><?php echo config::byKey("product_name"); ?> {{est de nouveau opérationnel. Vous allez être redirigé vers votre dashboard.}}</h6>');
		$('#progressbar_reboot').addClass('progress-bar-success').removeClass('progress-bar-danger');
		$('#progressbar_reboot').width('75%');
		setTimeout("$('#progressbar_reboot').width('100%');", 3500);
		setTimeout('window.location.replace("index.php?v=d&p=dashboard")', 4000);
	}else{
		testjeedom++;
		if(testjeedom > '15'){
			$('#progressbar_reboot').addClass('progress-bar-danger').removeClass('progress-bar-success');
			$('#div_reboot_jeedom_texte').empty().html('<h6><?php echo config::byKey("product_name"); ?> {{n\'a pas encore redémarré, nous continuons cependant de tester son retour.}}<br />{{N\'hésitez pas à la débrancher/rebrancher électriquement.}}</h6>');
		}
	}
}

function reboot_jeedom(rebooti){
	$('#div_reboot_jeedom_texte').empty().html('<h6>{{Merci de patienter...}}<br /><?php echo config::byKey("product_name"); ?> {{est en cours de redémarrage.}}</h6>');
	$('#progressbar_reboot').width('25%');
	setInterval('page_rebootjs(rebooti)', 15000);
}


</script>
