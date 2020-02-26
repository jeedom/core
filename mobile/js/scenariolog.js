$('body').attr('data-page', 'scenariolog')

function initScenariolog(_scenarioId) {
	$('#pre_globallog').empty()
	if (isset(_scenarioId)) {
		setTimeout(function(){
			if (_scenarioId == "-1") {
                _scenarioId = $('#bottompanel .ui-listview li.ui-first-child > a > span').text().trim()
            }

			$('#pre_globallog').height($('body').height() - $('div[data-role=header]').height() - $('.log_menu').height() - 40)
			jeedom.log.autoupdate({
				log : 'scenarioLog/scenario'+_scenarioId+'.log',
				display : $('#pre_globallog'),
				search : $('#in_globalLogSearch'),
				control : $('#bt_globalLogStopStart'),
			})
		}, 250)

		$("#bt_clearLog").off('click').on('click', function(event) {
			jeedom.scenario.emptyLog({
				id : _scenarioId,
				error: function (error) {
					$('#div_alert').showAlert({message: error.message, level: 'danger'})
				},
				success: function(data) {
					if($('#bt_globalLogStopStart').attr('data-state') == 0){
						$('#bt_globalLogStopStart').click()
					}
				}
			})
		})

	}

	$(window).on("resize", function (event) {
		setTimeout(function(){
			$('#pre_globallog').height($('body').height() - $('div[data-role=header]').height() - $('.log_menu').height() - 35)
		}, 100)
	})
}
