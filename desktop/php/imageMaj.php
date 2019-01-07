<?php
if (!isConnect('admin')) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
?>
<div id="contenu">
	<div id="step1">
		<span class="titleStep animated slideInLeft"><i class="fas fa-hdd"></i> {{Etape 1}}</span>
		<div id="contenuWith" class="animated zoomIn">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/imageMaj_stepUn.jpg" />
			</div>
			<div id="contenuText" class="debut">
				<span id="contenuTextSpan">Insérer une clé USB de plus de 8Go<br /> dans votre Jeedom et cliquer sur <i class="fas fa-arrow-circle-right
"></i>.</span>
				<div id="nextDiv">
					<i class="next fas fa-arrow-circle-right" id="bt_next"></i>
				</div>
			</div>
			<div id="contenuText" class="usb" style="display:none;">
		</div>
		</div>
	</div>
	<div id="step2">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 2}}</span>
	</div>
	<div id="step3">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 3}}</span>
	</div>
	<div id="step4">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 4}}</span>
	</div>
</div>
<script>
$('#bt_next').on('click', function() {
	$.ajax({
        type: 'POST',
        url: 'core/ajax/migrate.ajax.php',
        data: {
            action: 'usbTry',
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result){
        	var statusUsb = result.result.statut;
        	switch(statusUsb){
	        	case 'sdaNull' :
	        		alert('{{Pas de clé USB branchée}}');
	        	break;
	        	case 'sdaSup' :
	        		alert('{{Merci de branché qu\'une seul clé USB}}');
	        	break;
	        	case 'sdaNumNull' :
	        		alert('{{Merci de formaté correctement votre clé USB}}');
	        	break;
	        	case 'sdaNumSup' :
	        		alert('{{Merci de mettre une seul partition sur votre clé USB}}');
	        	break;
	        	case 'space' :
	        		alert('{{votre clé USB à un espace trop petit }}('+result.result.space+' Mo) il faut un minimum de '+result.result.minSpace+' Mo. <br />Merci');
	        	break;
	        	case 'ok' :
	        		$('.debut').hide();
					$('.usb').show();
	        		$('.usb').append('<span id="contenuTextSpan">{{Clé USB vérifié passage à l\'étape 2 en cours}}<br /><i class="next fas fa-arrow-circle-right" id="bt_next"></i></span>');
	        		setTimeout(function(){
	        			$('#step1').hide();
	        			$('#step2').show();
	        		}, 4000);
	        	break;

        	}
        }
	});
});
</script>
<?php
include_file('desktop', 'imageMaj', 'css');
?>