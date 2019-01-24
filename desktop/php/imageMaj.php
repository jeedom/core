<?php
if (!isConnect('admin')) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}
?>
<div id="contenu">
	<div id="step1">
		<span class="titleStep animated slideInLeft"><i class="fas fa-hdd"></i> {{Etape 1}}</span>
		<div id="contenuWithStepOne" class="animated zoomIn contenuWith">
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
		<div id="contenuWithStepTwo" class="zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/imageMaj_stepDeux.jpg" />
			</div>
			<div id="contenuText" class="backup">
				<span id="contenuTextSpan" class="TextBackup">Backup lancé merci de patienter...</span>
			<div id="contenuTextSpan" class="progress">
  <div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
		</div>
		</div>
		</div>
	</div>
	<div id="step3">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 3}}</span>
		<div id="contenuWithStepTree" class="zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/imageMaj_stepTrois.jpg" />
			</div>
			<div id="contenuText" class="imageUp">
				<span id="contenuTextSpan" class="TextImage">Téléchargement de l'image Jeedom.</span>
			<div id="contenuTextSpan" class="progress">
			<div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
		</div>
		</div>
	</div>
	<div id="step4">
		<span class="titleStep"><i class="fas fa-hdd"></i> {{Etape 4}}</span>
		<div id="contenuWithStepFor" class="zoomIn contenuWith">
			<div id="contenuImage">
				<img id="contenuImageSrc" src="/core/img/imageMaj_stepQuatre.jpg" />
			</div>
			<div id="contenuText" class="imageUp">
				<span id="contenuTextSpan" class="TextMigrate">Migration de votre Jeedom</span>
			<div id="contenuTextSpan" class="progress">
			<div class="progress-bar progress-bar-striped progress-bar-animated active" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
</div>
		</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="modalReloadStep">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{Reprendre la restauration}}</h4>
      </div>
      <div class="modal-body">
        <p>{{Pour reprendre votre restauration cliquez sur "Reprendre".}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="bt_close" data-dismiss="modal">{{Fermer}}</button>
        <button type="button" class="btn btn-primary" id="bt_reprendre">{{Reprendre}}</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="div_reboot_jeedom" style="display:none;">
	<script type="text/javascript" id="reboot_jeedom"></script>
</div>

<script>
var rebooti = '0';
var testjeedom = '0';

returnStep();

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
	        		$('.usb').append('<span id="contenuTextSpan">{{Clé USB vérifié passage à l\'étape 2 en cours}}<br /><i class="next fa fa-refresh" id="bt_next"></i></span>');
	        		setTimeout(function(){
	        			$('#step1').hide();
	        			$('#step2').show();
	        			$('#contenuWithStepTwo').addClass('animated');
	        			stepTwo();
	        		}, 4000);
	        	break;

        	}
        }
	});
});


/* VARIABLE */

var persiste = 0;
var netoyage = 0;
var migrateGo = 0;
var pourcentageBar = 0;
var stepReload = null;

/* FONCTION */

function stepTwo(){
	setStep('2');
	/* Lancement du backup */
	jeedom.backup.backup({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            verifBackup();
        }
    });
}

function verifBackup(){
	$('.progress-bar').width('10%');
	$('.progress-bar').text('10%');
	getJeedomLog(1, 'backup');
}

function getJeedomLog(_autoUpdate, _log) {
    $.ajax({
        type: 'POST',
        url: 'core/ajax/log.ajax.php',
        data: {
            action: 'get',
            log: _log,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            setTimeout(function () {
                getJeedomLog(_autoUpdate, _log)
            }, 1000);
        },
        success: function (data) {
            if (data.state != 'ok') {
                setTimeout(function () {
                    getJeedomLog(_autoUpdate, _log)
                }, 1000);
                return;
            }
            var log = '';
            if($.isArray(data.result)){
                for (var i in data.result.reverse()) {
                    log += data.result[i]+"\n";
                    if(data.result[i].indexOf('[END ' + _log.toUpperCase() + ' SUCCESS]') != -1){
                        $('.TextBackup').text('Backup Fini, copie en cours sur la clé USB, ne pas debrancher celle-ci...');
                    	$('.progress-bar').width('75%');
						$('.progress-bar').text('75%');
						backupToUsb();
                        _autoUpdate = 0;
                    }else if(data.result[i].indexOf('[END ' + _log.toUpperCase() + ' ERROR]') != -1){
                        $('#div_alert').showAlert({message: '{{L\'opération a échoué}}', level: 'danger'});
                        _autoUpdate = 0;
                    }else if(_log == 'backup'){
	                    if(data.result[i].indexOf("Persist cache") != -1){
	                    	if(persiste == 0){
	                    		persiste = 1;
		                    	$('.TextBackup').text('Création du backup en cours...');
								$('.progress-bar').width('25%');
								$('.progress-bar').text('25%');
	                    	}
	                    }
	                    if(data.result[i].indexOf("Créer l'archive...") != -1){
	                    	var textProgress = $('.progress-bar').text();
	                    	if(netoyage == 0 && Number(textProgress.substring(0, 2)) < 70){
								$('.progress-bar').width((Number(textProgress.substring(0, 2))+1)+'%');
								$('.progress-bar').text((Number(textProgress.substring(0, 2))+1)+'%');
							}
	                    }
	                    if(data.result[i].indexOf("Nettoyage l'ancienne sauvegarde...OK") != -1){
	                    	netoyage = 1;
		                    $('.TextBackup').text('Validation du Backup...');
	                    	$('.progress-bar').width('70%');
							$('.progress-bar').text('70%');
	                    }
					}else if(_log == 'migrate'){
						if(migrateGo == 0){
							if(data.result[i].indexOf("Saving to: '/media/migrate/backupJeedomDownload.tar.gz'") != -1){
			                    $('.TextImage').text('Téléchargement en cours de l\'image...');
			                    pourcentageBar = 0;
			                    migrateGo = 1;
		                    }else{
			                    if(data.result[i].indexOf("%") != -1){
				                    var indexOfFirst = data.result[i].lastIndexOf("%");
				                    var pourcentage = data.result[i].substring((indexOfFirst-2),indexOfFirst);
				                    pourcentage = Number(pourcentage);
				                    if(pourcentageBar < pourcentage){
					                    $('.progress-bar').width(Math.round(pourcentage/5+80)+'%');
										$('.progress-bar').text(Math.round(pourcentage/5+80)+'%');
										pourcentageBar = pourcentage;
										if(pourcentage == 99){
											_autoUpdate = 0;
										}
									}	
								}
		                    }
	                    }else{
		                    if(data.result[i].indexOf("%") != -1){
			                    var indexOfFirst = data.result[i].indexOf("%");
			                    var pourcentage = data.result[i].substring((indexOfFirst-2),indexOfFirst);
			                    pourcentage = Number(pourcentage);
			                    if(pourcentageBar < pourcentage){
				                    $('.progress-bar').width(pourcentage+'%');
									$('.progress-bar').text(pourcentage+'%');
									pourcentageBar = pourcentage;
									if(pourcentage == 99){
										_autoUpdate = 0;
									}
								}
								if(data.result[i].indexOf("Downloaded: 1 files") != -1){
									_autoUpdate = 0;
									$('.TextImage').text('Image Téléchargé et validé !');
									renameImage();
								}	
		                    }
	                    }
					}
                }
            }
            if (init(_autoUpdate, 0) == 1) {
                setTimeout(function () {
                    getJeedomLog(_autoUpdate, _log)
                }, 1000);
            }
        }
    });
}

function backupToUsb(){
	pourcentageBar = 0;
	$('.progress-bar').width('80%');
	$('.progress-bar').text('80%');
	getJeedomLog(1, 'migrate');
	$.ajax({
        type: 'POST',
        url: 'core/ajax/migrate.ajax.php',
        data: {
            action: 'backupToUsb',
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result){
        	var backupToUsbResult = result.result;
        	switch(backupToUsbResult){
	        	case 'nok' :
	        		alert('{{Le backup n\a pas été copié}}');
	        	break;
	        	case 'ok' :
	        		$('.TextBackup').text('Backup Copié...');
					setTimeout(function () {
                    	UpImage();
					}, 1000);
	        	break;
	        	default:
	        		alert(backupToUsbResult);
        	}
        }
	});
}

function UpImage(go){
	pourcentage = 0;
	if(go == 1){
		setStep('3');
		migrateGo = 1;
		$('#step1').hide();
		$('#step2').hide();
		$('.progress-bar').width('0%');
		$('.progress-bar').text('0%');
		$('#step3').show();
		$('#contenuWithStepTree').addClass('animated');
		getJeedomLog(1, 'migrate');
	}else{
		setStep('3');
		$('#step1').hide();
		$('#step2').hide();
		$('.progress-bar').width('0%');
		$('.progress-bar').text('0%');
		$('#step3').show();
		$('#contenuWithStepTree').addClass('animated');
		$.ajax({
	        type: 'POST',
	        url: 'core/ajax/migrate.ajax.php',
	        data: {
	            action: 'imageToUsb',
	        },
	        dataType: 'json',
	        global: false,
	        error: function (request, status, error) {
	        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
	        },
	        success: function (result){
	        	var imageToUsbResult = result.result;
	        	switch(imageToUsbResult){
		        	case 'nok' :
		        		alert('{{L\'image n\'a pas été copié}}');
		        	break;
		        	case 'ok' :
		        		getJeedomLog(1, 'migrate');
		        	break;
		        	default:
		        		alert(imageToUsbResult);
	        	}
	        }
		});
	}
}

function renameImage(){
	$.ajax({
        type: 'POST',
        url: 'core/ajax/migrate.ajax.php',
        data: {
            action: 'renameImage'
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result){
        	GoReload();
        }
	});
}

function GoReload(){
	setStep('4');
	$('#step1').hide();
	$('#step2').hide();
	$('#step3').hide();
	$('.progress-bar').width('0%');
	$('.progress-bar').text('0%');
	$('#step4').show();
	$('#contenuWithStepFor').addClass('animated');
	jeedom.rebootSystem();
	setInterval('page_rebootjs(rebooti)', 15000);
}

function setStep(stepValue){
	$.ajax({
        type: 'POST',
        url: 'core/ajax/migrate.ajax.php',
        data: {
            action: 'setStep',
            stepValues: stepValue
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result){
        }
	});
}

function returnStep(){
	console.log('returnStep demandé');
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
        	if(statusUsb == 'ok'){
	        	$.ajax({
			        type: 'POST',
			        url: 'core/ajax/migrate.ajax.php',
			        data: {
			            action: 'getStep'
			        },
			        dataType: 'json',
			        global: false,
			        error: function (request, status, error) {
			        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
			        },
			        success: function (result){
			        	var stepResult = result.result;
			        	switch(stepResult){
				        	case '2' :
				        		$('#modalReloadStep').modal('show');
				        		stepReload = 2;
				        	break;
				        	case '3' :
				        		$('#modalReloadStep').modal('show');
					        	stepReload = 3;
				        	break;
				        	case '4' :
				        		$('#modalReloadStep').modal('show');
					        	stepReload = 4;
				        	break;
			        	}
			        }
				});
        	}
        }
	});
}

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
			$('.TextMigrate').text('Votre Jeedom viens de redémarrer');
		}else{
			testjeedom++;
			if(testjeedom > '70'){
				$('.progress-bar').addClass('progress-bar-danger').removeClass('progress-bar-success');
				$('.TextMigrate').text('Migration en Cours... merci de ne surtout pas débrancher votre Jeedom');
			}
		}
	}

	function reboot_jeedom(rebooti){
		$('.TextMigrate').text('Merci de patienter...<br />Jeedom est en cours de Migration');
		$('.progress-bar').width('5%');
		$('.progress-bar').text('5%');
		setInterval('page_rebootjs(rebooti)', 15000);
	}

$('#bt_reprendre').on('click', function() {
	if(stepReload !== null){
		switch(stepReload){
        	case 2 :
	        	stepTwo();
        	break;
        	case 3 :
	        	UpImage(1);
        	break;
        	case 4 :
	        	GoReload();
        	break;
    	}
		$('#modalReloadStep').modal('hide');
	}else{
		$('#modalReloadStep').modal('hide');
	}
});

$('#bt_close').on('click', function() {
	setStep(1);
});

</script>
<?php
include_file('desktop', 'imageMaj', 'css');
?>