/* VARIABLE */

var rebooti = '0';
var testjeedom = '0';
var persiste = 0;
var netoyage = 0;
var migrateGo = 0;
var pourcentageBar = 0;
var stepReload = null;


/* on test si on a déjà une migration en cours */
returnStep();


/* BOUTON ONCLICK */

$('#bt_next').on('click', function() {
	testUsb();
});

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
        	case 5 :
	        	finalisation();
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


/* FONCTION */

function testUsb(){
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
}

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
								}	
		                    }else if(data.result[i].indexOf("Downloaded: 1 files") != -1){
									_autoUpdate = 0;
									$('.TextImage').text('Image Téléchargé et validé !');
									renameImage();
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
		$('#step4').hide();
		$('#step5').hide();
		$('.progress-bar').width('0%');
		$('.progress-bar').text('0%');
		$('#step3').show();
		$('#contenuWithStepTree').addClass('animated');
		getJeedomLog(1, 'migrate');
	}else{
		setStep('3');
		$('#step1').hide();
		$('#step2').hide();
		$('#step4').hide();
		$('#step5').hide();
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
	$('#step5').hide();
	$('.progress-bar').width('0%');
	$('.progress-bar').text('0%');
	pourcentageBar = 0;
	$('#step4').show();
	$('#contenuWithStepFor').addClass('animated');
	jeedom.rebootSystem();
	reboot_jeedom(rebooti);
}

function finalisation(){
	setStep('5');
	$('#step1').hide();
	$('#step2').hide();
	$('#step3').hide();
	$('#step4').hide();
	$('.progress-bar').width('0%');
	$('.progress-bar').text('0%');
	pourcentageBar = 0;
	$('#step5').show();
	$('#contenuWithStepFive').addClass('animated');
	$.ajax({
        type: 'POST',
        url: 'core/ajax/migrate.ajax.php',
        data: {
            action: 'finalisation'
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (result){
        	if(result.result == 'ok'){
	        	//setTimeout('window.location.replace("index.php?v=d&p=dashboard")', 4000);
				//setStep(1);
				getJeedomLog(1, 'migrate');
        	}else{
	        	alert('erreur');
        	}
        	
        }
	});
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
				        	case '5' :
				        		$('#modalReloadStep').modal('show');
					        	stepReload = 5;
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
		finalisation();
	}else{
		testjeedom++;
		pourcentageBar = pourcentageBar+2;
		$('.progress-bar').width(pourcentageBar+'%');
		$('.progress-bar').text(pourcentageBar+'%');
		if(testjeedom > '80'){
			$('.progress-bar').addClass('progress-bar-danger').removeClass('progress-bar-success');
			$('.TextMigrate').text('Migration en Cours... merci de ne surtout pas débrancher votre Jeedom');
		}
	}
}

function reboot_jeedom(rebooti){
	$('.TextMigrate').text('Merci de patienter...<br />Jeedom est en cours de Migration');
	$('.progress-bar').width('5%');
	$('.progress-bar').text('5%');
	pourcentageBar = 5;
	setInterval('page_rebootjs(rebooti)', 15000);
}
