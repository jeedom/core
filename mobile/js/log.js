function initLog(_log) {
	$('#pre_globallog').empty();
	jeedom.log.list({
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'});
		},
		success : function(_data){
			var li = ' <ul data-role="listview" data-inset="false">';
			for(var i in _data){
				li += '<li><a href="#" class="link" data-page="log" data-title="<i class=\'far fa-file\'></i> '+_data[i]+'" data-option="'+_data[i]+'"><span><i class=\'fa fa-file\'></i> '+_data[i]+'</a></li>';
			}
			li += '</ul>';
			panel(li);
		}
	});

	if (isset(_log)) {
		setTimeout(function(){
			$('#pre_globallog').height($('body').height() - $('div[data-role=header]').height() - $('.log_menu').height() - 40);
			jeedom.log.autoupdate({
				log : _log,
				display : $('#pre_globallog'),
				search : $('#in_globalLogSearch'),
				control : $('#bt_globalLogStopStart'),
			});
		}, 1);

		$("#bt_clearLog").off('click').on('click', function(event) {
			jeedom.log.clear({
				log : _log,
				error: function (error) {
					$('#div_alert').showAlert({message: error.message, level: 'danger'});
				},
				success: function(data) {
					if($('#bt_globalLogStopStart').attr('data-state') == 0){
						$('#bt_globalLogStopStart').click();
					}
				}
			});
		});

		$("#bt_removeLog").off('click').on('click', function(event) {
			jeedom.log.remove({
				log : _log,
				error: function (error) {
					$('#div_alert').showAlert({message: error.message, level: 'danger'});
				},
				success: function(data) {
					initLog();
				}
			});
		});	
	}

	$("#bt_removeAllLog").off('click').on('click', function(event) {
		var result = confirm("{{Etes-vous sûr de vouloir supprimer tous les logs ?}}");
		if (result) {
			jeedom.log.removeAll({
				error: function (error) {
					$('#div_alert').showAlert({message: error.message, level: 'danger'});
				},
				success: function(data) {
					initLog();
				}
			});
		}
	});

	$(window).on("resize", function (event) {
		setTimeout(function(){
			$('#pre_globallog').height($('body').height() - $('div[data-role=header]').height() - $('.log_menu').height() - 35);
		}, 100);
	});
}

