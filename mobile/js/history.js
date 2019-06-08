$('body').attr('data-page', 'history')

function initHistory(_cmd_id) {
	addChart(_cmd_id,1)
	delete jeedom.history.chart['div_graph']
}

function addChart(_cmd_id, _action) {
	if (_action == 0) {
		if (isset(jeedom.history.chart['div_graph']) && isset(jeedom.history.chart['div_graph'].chart) && isset(jeedom.history.chart['div_graph'].chart.series)) {
			$(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie){
				try {
					if(serie.options.id == _cmd_id){
						serie.remove()
					}
				}catch(error) {
				}
			})
		}
	} else {
		lastId = _cmd_id
		jeedom.history.drawChart({
			cmd_id: _cmd_id,
			el: 'div_graph',
			dateRange : 'all',
			dateStart : $('#in_startDate').value(),
			dateEnd :  $('#in_endDate').value(),
			height : $('#div_graph').height(),
			success: function (data) {
				if(isset(data.cmd.display)){
					if (init(data.cmd.display.graphStep) != '') {
						$('#cb_step').off().value(init(data.cmd.display.graphStep))
						$('#cb_step').checkboxradio('refresh')
					}
					if (init(data.cmd.display.graphType) != '') {
						$('#sel_chartType').off().value(init(data.cmd.display.graphType))
						$('#sel_chartType').selectmenu('refresh')
					}
					if (init(data.cmd.display.groupingType) != '') {
						$('#sel_groupingType').off().value(init(data.cmd.display.groupingType))
						$('#sel_groupingType').selectmenu('refresh')
					}
					if (init(data.cmd.display.graphDerive) != '') {
						$('#cb_derive').off().value(init(data.cmd.display.graphDerive))
						$('#cb_derive').checkboxradio('refresh')
					}
				}
				initHistoryTrigger()
			}
		})
	}
}

function initHistoryTrigger() {
	$('#sel_chartType').off('change').on('change', function () {
		addChart(lastId,0)
		jeedom.cmd.save({
			cmd: {id: lastId, display: {graphType: $(this).value()}},
			error: function (error) {
				$('#div_alert').showAlert({message: error.message, level: 'danger'})
			},
			success: function () {
				addChart(lastId,1)
			}
		})
	})
	$('#sel_groupingType').off('change').on('change', function () {
		addChart(lastId,0)
		jeedom.cmd.save({
			cmd: {id: lastId, display: {groupingType: $(this).value()}},
			error: function (error) {
				$('#div_alert').showAlert({message: error.message, level: 'danger'})
			},
			success: function () {
				addChart(lastId,1)
			}
		})
	})
	$('#cb_derive').off('change').on('change', function () {
		addChart(lastId,0)
		jeedom.cmd.save({
			cmd: {id: lastId, display: {graphDerive: $(this).value()}},
			error: function (error) {
				$('#div_alert').showAlert({message: error.message, level: 'danger'})
			},
			success: function () {
				addChart(lastId,1)
			}
		})
	})
	$('#cb_step').off('change').on('change', function () {
		addChart(lastId,0)
		jeedom.cmd.save({
			cmd: {id: lastId, display: {graphStep: $(this).value()}},
			error: function (error) {
				$('#div_alert').showAlert({message: error.message, level: 'danger'})
			},
			success: function () {
				addChart(lastId,1)
			}
		})
	})
}

$('#bt_validChangeDate').on('click',function(){
	$(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie){
		if(!isNaN(serie.options.id)){
			var cmd_id = serie.options.id
			addChart(cmd_id, 0)
			addChart(cmd_id, 1)
		}
	})
})
