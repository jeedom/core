$('body').attr('data-page', 'eqAnalyse')

function initEqanalyse() {
	jeedom.eqLogic.htmlAlert({
		version : 'mobile',
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'})
		},
		success: function (eqLogics) {
			div = ''
			for (var i in eqLogics) {
				div += eqLogics[i].html
			}
			$('#div_displayAlert').empty().html(div).trigger('create')

			setTimeout(function () {
              	deviceInfo = getDeviceType()
                setTileSize('.eqLogic')
				$('#div_displayAlert').packery({gutter : 0})
			}, 100)

			jeedom.eqLogic.htmlBattery({
				version : 'mobile',
				error: function (error) {
					$('#div_alert').showAlert({message: error.message, level: 'danger'})
				},
				success: function (eqLogics) {
					div = ''
					for (var i in eqLogics) {
						div += eqLogics[i].html
					}
					$('#div_displayBattery').empty().html(div).trigger('create')
					$('ul[data-role=nd2tabs]').tabs()

                  	setTimeout(function () {
                        deviceInfo = getDeviceType()
                        setTileSize('.eqLogic')
                        $('#div_displayBattery').packery({gutter : 0})
                    }, 100)
				}
			})
		}
	})

    $('body').on('orientationChanged', function (event, _orientation) {
      deviceInfo = getDeviceType()
      setTileSize('.eqLogic')
      $('#div_displayAlert').packery({gutter : 0})
      $('#div_displayBattery').packery({gutter : 0})
    })
}


