<?php
header ("Cache-Control: no-cache");
require_once dirname(__FILE__) . '/../../core/php/core.inc.php';
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
	<h2 style="opacity:0.8;"><span class="glyphicon glyphicon-off"></span> &Eacute;teindre</h2>
	<div class="progress">
		<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" id="progressbar_reboot" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
			<span class="sr-only"></span>
		</div>
	</div>
	<div id="div_reboot_jeedom_texte"><h6>Merci de patienter...</h6></div>
</div>

<iframe src="" id="iframe_reboot" style="display:none"></iframe>

<div id="div_reboot_jeedom" style="display:none;">
<script type="text/javascript" id="reboot_jeedom"></script>
</div>

<script type="text/javascript">
var rebooti = '0';
var testjeedom = '0';

	function reboot_jeedom(rebooti){
		$('#iframe_reboot').attr('src', 'index.php?v=d&p=reboot_end&shut=1&t='+Date.now());
		$('#div_reboot_jeedom_texte').empty().html('<h6>La Jeedom est &eacute;teinte.<br /> Pour la redémarrer, débranchez la et rebranchez la.</h6>');
		$('#progressbar_reboot').width('100%');
		$('#progressbar_reboot').addClass('progress-bar-danger').removeClass('progress-bar-success').removeClass('active');
	}
	
setTimeout('reboot_jeedom(rebooti)', 10000);
setTimeout("$('#progressbar_reboot').width('50%');", 5000);
$('#progressbar_reboot').width('5%');
</script>
