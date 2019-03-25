function initEqanalyse() {
	jeedom.eqLogic.htmlAlert({
		version : 'mobile',
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (eqLogics) {
			div = '';
			for (var i in eqLogics) {
				div += eqLogics[i].html;
			}
			$('#div_displayAlert').empty().html(div).trigger('create');
			setTileSize('.eqLogic');
			$('#div_displayAlert').packery({gutter : 0});
			setTimeout(function () {
				$('#div_displayAlert').packery({gutter : 0});
			}, 10);
			jeedom.eqLogic.htmlBattery({
				version : 'mobile',
				error: function (error) {
					$('#div_alert').showAlert({message: error.message, level: 'danger'});
				},
				success: function (eqLogics) {
					div = '';
					for (var i in eqLogics) {
						div += eqLogics[i].html;
					}
					$('#div_displayBattery').empty().html(div).trigger('create');
					$('ul[data-role=nd2tabs]').tabs();
					setTileSize('.eqLogic');
					$('#div_displayBattery').packery({gutter : 0});
					setTimeout(function () {
						$('#div_displayBattery').packery({gutter : 0});
					}, 10);
				}
			});
		}
	});
	
	
}
