/* VARIABLE */

var rebooti = '0';
var testjeedom = '0';
var persiste = 0;
var netoyage = 0;
var migrateGo = 0;
var pourcentageBar = 0;
var stepReload = null;
var telechargement = 0;
var Cleaning = 0;
var FinalDown = 0;
var Maj = 0;
var End = 0;

/* on test si on a déjà une migration en cours */
returnStep();


/* BOUTON ONCLICK */

$('#bt_next').on('click', function() {
	testUsb();
});

$('#bt_reprendre').on('click', function() {
	if (stepReload !== null) {
		switch (stepReload) {
			case 2:
				stepTwo();
				break;
			case 3:
				UpImage(1);
				break;
			case 4:
				GoReload();
				break;
			case 5:
				finalisation(1);
				break;
		}
		$('#modalReloadStep').modal('hide');
	} else {
		$('#modalReloadStep').modal('hide');
	}
});

$('#bt_close').on('click', function() {
	setStep(1);
});

$('#bt_zero').on('click', function() {
	End = 1;
	$('#modalFinalStep').modal('hide');
	setStep(0);
	$('.progress-bar').width('100%');
	$('.progress-bar').text('100%');
	window.location.replace("index.php?v=d&logout=1");
});

$('#bt_backup').on('click', function() {
	$('#modalFinalStep').modal('hide');
	setStep(0);
	installBackup();
});


/* FONCTION */

function testUsb() {
	$.ajax({
		type: 'POST',
		url: 'core/ajax/migrate.ajax.php',
		data: {
			action: 'usbTry',
		},
		dataType: 'json',
		global: false,
		error: function(request, status, error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function(result) {
			var statusUsb = result.result.statut;
			switch (statusUsb) {
				case 'sdaNull':
					alert('{{Aucune clé USB trouvée. Veuillez insérer une clé USB.}}');
					break;
				case 'sdaSup':
					alert('{{Merci de ne brancher qu\'une seule clé USB.}}');
					break;
				case 'sdaNumNull':
					alert('{{Votre clé USB doit être formatée en FAT32.}}');
					break;
				case 'sdaNumSup':
					alert('{{Il ne doit y avoir qu\'une seule partition sur votre clé USB.}}');
					break;
				case 'space':
					alert('{{L\'espace libre sur votre clé USB est trop petit}} (' + result.result.space + ' Mo) {{L\'espace minimum nécessaire est de}} ' + result.result.minSpace + ' {{Mo}}. {{Merci}}');
					break;
				case 'ok':
					$('.debut').hide();
					$('.usb').show();
					$('.usb').append('<span id="contenuTextSpan">{{Clé USB vérifiée passage à l\'étape 2 en cours}}<br /><i class="icon_green fas fa-3x fa-sync" id="bt_next"></i></span>');
					setTimeout(function() {
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

function stepTwo() {
	setStep('2');
	/* Lancement du backup */
	jeedom.backup.backup({
		error: function(error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function() {
			verifBackup();
		}
	});
}

function verifBackup() {
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
		error: function(request, status, error) {
			setTimeout(function() {
				getJeedomLog(_autoUpdate, _log)
			}, 1000);
		},
		success: function(data) {
			if (data.state != 'ok') {
				setTimeout(function() {
					getJeedomLog(_autoUpdate, _log)
				}, 1000);
				return;
			}
			var log = '';
			if ($.isArray(data.result)) {
				for (var i in data.result.reverse()) {
					log += data.result[i] + "\n";
					if (FinalDown > 0) {
						if (data.result[i].indexOf('[START RESTORE]') != -1 && FinalDown == 1) {
							FinalDown++;
							$('.TextFinalisation').text('{{Début de restauration de la sauvegarde !}}');
							$('.progress-bar').width('95%');
							$('.progress-bar').text('95%');
						} else if (data.result[i].indexOf('[END RESTORE SUCCESS]') != -1 && FinalDown == 2) {
							End = 1;
							_autoUpdate = 0
							$('.progress-bar').width('100%');
							$('.progress-bar').text('100%');
							window.location.replace("index.php?v=d&logout=1");
						}
					} else {
						if (data.result[i].indexOf('[END BACKUP SUCCESS]') != -1) {
							$('.TextBackup').text('{{Sauvegarde terminée. Copie en cours sur la clé USB, veuillez ne pas la débrancher...}}');
							$('.progress-bar').width('75%');
							$('.progress-bar').text('75%');
							backupToUsb();
							_autoUpdate = 0;
						}
						if (data.result[i].indexOf('[END UPDATE SUCCESS]') != -1) {
							if (Maj == 0) {
								$('.TextFinalisation').text('{{Test de l\'image}}');
								var textProgress = $('.progress-bar').text();
								$('.progress-bar').width('50%');
								$('.progress-bar').text('50%');
							} else {
								$('.TextFinalisation').text('{{La mise à jour de votre box Jeedom s\'est terminée avec succès.}}');
								var textProgress = $('.progress-bar').text();
								$('.progress-bar').width('70%');
								$('.progress-bar').text('70%');
							}
							_autoUpdate = 0;
							final();
						} else if (data.result[i].indexOf('[END BACKUP ERROR]') != -1) {
							$.fn.showAlert({
								message: '{{L\'opération a échoué.}}',
								level: 'danger'
							});
							_autoUpdate = 0;
						} else if (_log == 'backup') {
							if (data.result[i].indexOf("Persist cache") != -1) {
								if (persiste == 0) {
									persiste = 1;
									$('.TextBackup').text('{{Création de la sauvegarde en cours...}}');
									$('.progress-bar').width('25%');
									$('.progress-bar').text('25%');
								}
							}
							if (data.result[i].indexOf("Créer l'archive...") != -1) {
								var textProgress = $('.progress-bar').text();
								if (netoyage == 0 && Number(textProgress.substring(0, 2)) < 70) {
									$('.progress-bar').width((Number(textProgress.substring(0, 2)) + 1) + '%');
									$('.progress-bar').text((Number(textProgress.substring(0, 2)) + 1) + '%');
								}
							}
							if (data.result[i].indexOf("Nettoyage l'ancienne sauvegarde...OK") != -1) {
								netoyage = 1;
								$('.TextBackup').text('{{Validation du Backup...}}');
								$('.progress-bar').width('70%');
								$('.progress-bar').text('70%');
							}
						} else if (_log == 'update') {
							if (data.result[i].indexOf("Téléchargement") != -1 || data.result[i].indexOf("Download url") != -1) {
								if (telechargement == 0) {
									telechargement = 1;
									$('.TextFinalisation').text('{{Téléchargement de la mise à jour.}}');
									$('.progress-bar').width('15%');
									$('.progress-bar').text('15%');
								}
							}
							if (data.result[i].indexOf("Cleaning folders") != -1) {
								if (Cleaning == 0) {
									Cleaning = 1;
									$('.progress-bar').width('20%');
									$('.progress-bar').text('20%');
								}
							}
							if (data.result[i].indexOf("Check update") != -1) {
								if (Cleaning == 0) {
									Cleaning = 1;
									$('.TextFinalisation').text('{{Vérification de la mise à jour.}}');
									$('.progress-bar').width('30%');
									$('.progress-bar').text('30%');
								}
							}
						} else if (_log == 'migrate') {
							if (migrateGo == 0) {
								if (data.result[i].indexOf("Saving to: '/media/migrate/backupJeedomDownload.tar.gz'") != -1) {
									$('.TextImage').text('{{Cette tâche peut prendre jusqu\'à 10 minutes pour commencer. Téléchargement de l\'image en cours...}}');
									pourcentageBar = 0;
									migrateGo = 1;
								} else {
									if (data.result[i].indexOf("%") != -1) {
										var indexOfFirst = data.result[i].lastIndexOf("%");
										var pourcentage = data.result[i].substring((indexOfFirst - 2), indexOfFirst);
										pourcentage = Number(pourcentage);
										if (pourcentageBar < pourcentage) {
											$('.progress-bar').width(Math.round(pourcentage / 5 + 80) + '%');
											$('.progress-bar').text(Math.round(pourcentage / 5 + 80) + '%');
											pourcentageBar = pourcentage;
											if (pourcentage == 99) {
												_autoUpdate = 0;
											}
										}
									}
								}
							} else {
								if (data.result[i].indexOf("%") != -1) {
									var indexOfFirst = data.result[i].indexOf("%");
									var pourcentage = data.result[i].substring((indexOfFirst - 2), indexOfFirst);
									pourcentage = Number(pourcentage);
									if (pourcentageBar < pourcentage) {
										$('.progress-bar').width(pourcentage + '%');
										$('.progress-bar').text(pourcentage + '%');
										pourcentageBar = pourcentage;
										var filterVal = 'blur(' + (10 - Number(pourcentage / 10)) + 'px)';
										console.log(filterVal);
										$('.imageUpBlur').css({
											'filter': filterVal,
											'-webkit-filter': filterVal,
											'-moz-filter': filterVal,
											'-o-filter': filterVal,
											'-ms-filter': filterVal
										});
									}
								} else if (data.result[i].indexOf("Downloaded: 1 files") != -1) {
									_autoUpdate = 0;
									$('.TextImage').text('{{Image Téléchargée et validée !}}');
									var filterVal = 'blur(0)';
									console.log(filterVal);
									$('.imageUpBlur').css({
										'filter': filterVal,
										'-webkit-filter': filterVal,
										'-moz-filter': filterVal,
										'-o-filter': filterVal,
										'-ms-filter': filterVal
									});
									renameImage();
								}
							}
						}
					}
				}
			}
			if (init(_autoUpdate, 0) == 1) {
				setTimeout(function() {
					getJeedomLog(_autoUpdate, _log)
				}, 1000);
			}
		}
	});
}

function backupToUsb() {
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
		error: function(request, status, error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function(result) {
			var backupToUsbResult = result.result;
			switch (backupToUsbResult) {
				case 'nok':
					alert('{{La sauvegarde n\'a pas été copiée}}');
					break;
				case 'ok':
					$('.TextBackup').text('{{Sauvegarde Copiée...}}');
					setTimeout(function() {
						UpImage();
					}, 1000);
					break;
				default:
					alert(backupToUsbResult);
			}
		}
	});
}

function UpImage(go) {
	pourcentage = 0;
	var filterVal = 'blur(10px)';
	if (go == 1) {
		setStep('3');
		migrateGo = 1;
		$('#step1').hide();
		$('#step2').hide();
		$('#step4').hide();
		$('#step5').hide();
		$('.progress-bar').width('0%');
		$('.progress-bar').text('0%');
		$('#step3').show();
		$('.imageUpBlur').css({
			'filter': filterVal,
			'-webkit-filter': filterVal,
			'-moz-filter': filterVal,
			'-o-filter': filterVal,
			'-ms-filter': filterVal
		});
		$('#contenuWithStepTree').addClass('animated');
		getJeedomLog(1, 'migrate');
	} else {
		setStep('3');
		$('#step1').hide();
		$('#step2').hide();
		$('#step4').hide();
		$('#step5').hide();
		$('.progress-bar').width('0%');
		$('.progress-bar').text('0%');
		$('.imageUpBlur').css({
			'filter': filterVal,
			'-webkit-filter': filterVal,
			'-moz-filter': filterVal,
			'-o-filter': filterVal,
			'-ms-filter': filterVal
		});
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
			error: function(request, status, error) {
				$.fn.showAlert({
					message: error.message,
					level: 'danger'
				});
			},
			success: function(result) {
				var imageToUsbResult = result.result;
				switch (imageToUsbResult) {
					case 'telechargement':
						getJeedomLog(1, 'migrate');
						break;
					case 'fileExist':
						GoReload();
						break;
					default:
						alert(imageToUsbResult);
				}
			}
		});
	}
}

function renameImage() {
	$.ajax({
		type: 'POST',
		url: 'core/ajax/migrate.ajax.php',
		data: {
			action: 'renameImage'
		},
		dataType: 'json',
		global: false,
		error: function(request, status, error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function(result) {
			GoReload();
		}
	});
}

function GoReload() {
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
	$('#jqueryLoadingDiv').html('');
	setTimeout(function() {
		jeedom.rebootSystem();
	}, 5000);
	setTimeout(function() {
		reboot_jeedom();
	}, 30000);
}

function finalisation(go) {
	$('#step1').hide();
	$('#step2').hide();
	$('#step3').hide();
	$('#step4').hide();
	$('.progress-bar').width('0%');
	$('.progress-bar').text('0%');
	pourcentageBar = 0;
	$('#step5').show();
	$('#contenuWithStepFive').addClass('animated');
	console.log('function Finalisation');
	$.ajax({
		type: 'POST',
		url: 'core/ajax/jeedom.ajax.php',
		data: {
			action: 'getInfoApplication'
		},
		dataType: 'json',
		error: function(request, status, error) {
			confirm('Erreur de communication. Êtes-vous connecté à Internet ? Voulez-vous réessayer ?');
		},
		success: function(data) {
			console.log('getInfoApplication > ' + JSON.stringify(data));
			setTimeout(function() {
				$.ajax({
					type: 'POST',
					url: 'core/ajax/user.ajax.php',
					data: {
						action: 'login',
						username: 'admin',
						password: 'admin',
						storeConnection: 1
					},
					dataType: 'json',
					global: false,
					error: function(request, status, error) {
						console.log('Ajax User Error');
						$.fn.showAlert({
							message: error.message,
							level: 'danger'
						});
					},
					success: function(result) {
						console.log('Succes Login ;) > ' + JSON.stringify(result));
						final();
					}
				});
			}, 3000);
		}
	});
}

function final() {
	setStep('5');
	$.ajax({
		type: 'POST',
		url: 'core/ajax/migrate.ajax.php',
		data: {
			action: 'finalisation'
		},
		dataType: 'json',
		global: false,
		error: function(request, status, error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function(result) {
			$('#modalFinalStep').modal('show');
		}
	});
}

function installBackup() {
	$.ajax({
		type: 'POST',
		url: 'core/ajax/migrate.ajax.php',
		data: {
			action: 'GoBackupInstall'
		},
		dataType: 'json',
		global: false,
		error: function(request, status, error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function(result) {
			FinalDown = 1;
			getJeedomLog(1, 'restore');
		}
	});
}

function setStep(stepValue) {
	$.ajax({
		type: 'POST',
		url: 'core/ajax/migrate.ajax.php',
		data: {
			action: 'setStep',
			stepValues: stepValue
		},
		dataType: 'json',
		global: false,
		error: function(request, status, error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function(result) {}
	});
}

function returnStep() {
	$.ajax({
		type: 'POST',
		url: 'core/ajax/migrate.ajax.php',
		data: {
			action: 'usbTry',
		},
		dataType: 'json',
		global: false,
		error: function(request, status, error) {
			$.fn.showAlert({
				message: error.message,
				level: 'danger'
			});
		},
		success: function(result) {
			var statusUsb = result.result.statut;
			if (statusUsb == 'ok') {
				$.ajax({
					type: 'POST',
					url: 'core/ajax/migrate.ajax.php',
					data: {
						action: 'getStep'
					},
					dataType: 'json',
					global: false,
					error: function(request, status, error) {
						$.fn.showAlert({
							message: error.message,
							level: 'danger'
						});
					},
					success: function(result) {
						var stepResult = result.result;
						switch (stepResult) {
							case '2':
								$('#modalReloadStep').modal('show');
								stepReload = 2;
								break;
							case '3':
								$('#modalReloadStep').modal('show');
								stepReload = 3;
								break;
							case '4':
								$('#modalReloadStep').modal('show');
								stepReload = 4;
								break;
							case '5':
								$('#modalReloadStep').modal('show');
								stepReload = 5;
								break;
							default:
								$('#modalFirstStep').modal('show');
						}
					}
				});
			}
		}
	});
}

function refresh() {
	$.ajax({
		url: "desktop/js/rebootjs.js?t=" + Date.now(),
		success: function(retour) {
			$('#reboot_jeedom').html(retour);
		}
	});
}

function page_rebootjs() {
	refresh();
	if (rebooti == '1') {
		$('.TextMigrate').text('{{Votre box Jeedom vient de redémarrer. Merci de patienter, le premier redémarrage pouvant durer jusqu\'à 5 minutes.}}');
		$('.progress-bar').width('90%');
		$('.progress-bar').text('90%');
		setTimeout(function() {
			finalisation();
		}, 300000);
	} else {
		testjeedom++;
		pourcentageBar = pourcentageBar + 3;
		$('.progress-bar').width(pourcentageBar + '%');
		$('.progress-bar').text(pourcentageBar + '%');
		if (pourcentageBar > '80') {
			$('.progress-bar').addClass('progress-bar-danger').removeClass('progress-bar-success');
			$('.TextMigrate').text('{{Migration en Cours... Veuillez ne surtout pas débrancher votre box Jeedom}}');
		}
		setTimeout(function() {
			page_rebootjs();
		}, 150000);
	}
}

function reboot_jeedom() {
	$('.TextMigrate').text('{{Merci de patienter... Jeedom est en cours de migration}}');
	$('.progress-bar').width('5%');
	$('.progress-bar').text('5%');
	pourcentageBar = 5;
	page_rebootjs();
	console.log('reboot de la Jeedom suppression des cookies');
	setcookie('PHPSESSID', '', time() - 365 * 24 * 3600, "/", '', false, true);
}

function confirmOnLeave(msg) {
	window.onbeforeunload = function(e) {
		if (End == 0) {
			e = e || window.event;
			msg = msg || '';

			// For IE and Firefox
			if (e) {
				e.returnValue = msg;
			}

			// For Chrome and Safari
			return msg;
		}
	};
}

confirmOnLeave('{{Attention si vous fermez cette page la migration ne pourra pas s\'effectuer.}}');