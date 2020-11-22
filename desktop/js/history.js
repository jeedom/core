/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

"use strict"

var chart
var noChart = 1
var colorChart = 0
var lastId = null
delete jeedom.history.chart['div_graph']

$(function() {
  var cmdIds = getUrlVars('cmd_id')
  if (typeof cmdIds == 'string') {
    cmdIds = cmdIds.split('-')
    if (is_numeric(cmdIds[0])) {
      cmdIds.forEach(function(cmd_id) {
        var li = $('.li_history[data-cmd_id='+cmd_id+']')
        if (li) {
          li.find('.history').click()
          li.closest('.cmdList').show()
        }
      })
    }
  }
  setChartOptions()
})

//handle resizing:
var resizeDone
function resizeDn() {
  var chart = $('#div_graph').highcharts()
  var height = $('#div_graph').height() - $('#div_historyOptions').outerHeight(true) - $('#div_alertHistory').outerHeight(true)
  if (chart) {
    chart.setSize( $('#div_graph').width(), height)
  }
  $('.bs-sidebar').height( height)
}
$(window).resize(function() {
  clearTimeout(resizeDone)
  resizeDone = setTimeout(resizeDn, 100)
})

function setChartOptions() {
  var _prop = 'disabled'
  if ($('.highcharts-legend-item:not(.highcharts-legend-item-hidden)').length == 1) {
    //only one graph:
    lastId = $('.highcharts-legend-item:not(.highcharts-legend-item-hidden)').attr('data-cmd_id')
    _prop = false
    chart = $('#div_graph').highcharts()
    var grouping, groupingType, type
    $(chart.series).each(function(idx, serie) {
      if (serie.userOptions.id == lastId) {
        if (isset(serie.userOptions.dataGrouping)) {
          grouping = serie.userOptions.dataGrouping.enabled
          if (grouping) {
            groupingType = serie.userOptions.dataGrouping.approximation + '::' + serie.userOptions.dataGrouping.units[0][0]
            $('#sel_groupingType').off().value(groupingType)
          }
        } else {
          $('#sel_groupingType').off().val($('#sel_groupingType option:first').val())
        }
        type = serie.userOptions.type
        if (type == 'areaspline') type = 'area'
        $('#sel_chartType').off().value(type)
        $('#cb_derive').prop('checked', serie.userOptions.derive)
        $('#cb_step').prop('checked', serie.userOptions.step)
        initHistoryTrigger()
        return false
      }
    })
  } else {
    lastId = null
    $('#sel_groupingType').val($('#sel_groupingType option:first').val())
    $('#sel_chartType').val($('#sel_chartType option:first').val())
    setChartExtremes()
  }
  $('#sel_groupingType, #sel_chartType').prop('disabled', _prop)
  resizeDn()
}

$('#bt_findCmdCalculHistory').on('click', function() {
  jeedom.cmd.getSelectModal({cmd: {type: 'info',subType : 'numeric',isHistorized : 1}}, function(result) {
    $('#in_calculHistory').atCaret('insert', result.human)
  })
})

$('#bt_displayCalculHistory').on('click', function() {
  var calcul = $('#in_calculHistory').value()
  if (calcul != '') addChart(calcul, 1)
})

$('#bt_configureCalculHistory').on('click', function() {
  $('#md_modal').dialog({title: "{{Configuration des formules de calcul}}"}).load('index.php?v=d&modal=history.calcul').dialog('open')
})

$('#bt_clearGraph').on('click', function() {
  if (jeedom.history.chart['div_graph'] === undefined) return
  while (jeedom.history.chart['div_graph'].chart.series.length > 0) {
    jeedom.history.chart['div_graph'].chart.series[0].remove(true)
  }
  delete jeedom.history.chart['div_graph']
  $('#ul_history').find('.li_history.active').removeClass('active')
  setChartOptions()
})

datePickerInit()

$(".li_history .history").on('click', function(event) {
  $.hideAlert()
  if ($(this).closest('.li_history').hasClass('active')) {
    $(this).closest('.li_history').removeClass('active')
    addChart($(this).closest('.li_history').attr('data-cmd_id'), 0)
  } else {
    $(this).closest('.li_history').addClass('active')
    addChart($(this).closest('.li_history').attr('data-cmd_id'), 1)
  }
  return false
})

$(".li_history .remove").on('click', function() {
  var bt_remove = $(this);
  $.hideAlert();
  bootbox.prompt('{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de }} <span style="font-weight: bold ;">' + bt_remove.closest('.li_history').find('.history').text() + '</span> (laissez vide pour tout supprimer) ?', function(result) {
    if (result !== null) {
      emptyHistory(bt_remove.closest('.li_history').attr('data-cmd_id'),result)
    }
  })
})

$('.displayObject').on('click', function() {
  var list = $('.cmdList[data-object_id=' + $(this).attr('data-object_id') + ']')
  if (list.is(':visible')) {
    $(this).find('i.fa').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
    list.hide()
  } else {
    $(this).find('i.fa').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
    list.show()
  }
})

$(".li_history .export").on('click', function() {
  window.open('core/php/export.php?type=cmdHistory&id=' + $(this).closest('.li_history').attr('data-cmd_id'), "_blank", null)
})

$('#bt_openCmdHistoryConfigure').on('click', function() {
  $('#md_modal').dialog({title: "{{Configuration de l'historique des commandes}}"}).load('index.php?v=d&modal=cmd.configureHistory').dialog('open')
})

$('#bt_validChangeDate').on('click',function() {
  if (jeedom.history.chart['div_graph'] === undefined) return
  $('.highcharts-plot-band').remove()
  $(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie) {
    if (serie.options && !isNaN(serie.options.id)) {
      var cmd_id = serie.options.id
      addChart(cmd_id, 0)
      addChart(cmd_id, 1)
    }
  })
})

function initHistoryTrigger() {
  $('#sel_groupingType').off('change').on('change', function() {
    if (lastId == null) return

    var currentId = lastId
    var groupingType = $(this).value()
    $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active')
    addChart(currentId, 0)
    jeedom.cmd.save({
      cmd: {id: currentId, display: {groupingType: groupingType}},
      error: function(error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      },
      success: function() {
        $('.li_history[data-cmd_id=' + currentId + '] .history').click()
      }
    })
  })

  $('#sel_chartType').off('change').on('change', function() {
    if (lastId == null) return

    var currentId = lastId
    var graphType = $(this).value()
    $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active')
    addChart(currentId, 0)
    jeedom.cmd.save({
      cmd: {id: currentId, display: {graphType: graphType}},
      error: function(error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      },
      success: function() {
        $('.li_history[data-cmd_id=' + currentId + '] .history').click()
      }
    })
  })

  $('#cb_derive').off('change').on('change', function() {
    var graphDerive = $(this).value()
    var nbSeries = chart.series.length
    $(chart.series).each(function(idx, serie) {
      var cmdId = $('g.highcharts-legend-item.highcharts-series-'+idx).attr('data-cmd_id')
      if (cmdId == undefined) {
        nbSeries--
        return
      }
      addChart(cmdId, 0)
      jeedom.cmd.save({
        cmd: {id: cmdId, display: {graphDerive: graphDerive}},
        error: function(error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          nbSeries--
          addChart(data.id, 1)
          if (nbSeries == 0) {
            setTimeout(function(){
              setChartExtremes()
            }, 1000)
          }
        }
      })
    })

  })

  $('#cb_step').off('change').on('change', function() {
    var graphStep = $(this).value()
    var nbSeries = chart.series.length
    $(chart.series).each(function(idx, serie) {
      var cmdId = $('g.highcharts-legend-item.highcharts-series-'+idx).attr('data-cmd_id')
      if (cmdId == undefined) {
        nbSeries--
        return
      }
      addChart(cmdId, 0)
      jeedom.cmd.save({
        cmd: {id: cmdId, display: {graphStep: graphStep}},
        error: function(error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          nbSeries--
          addChart(data.id, 1)
          if (nbSeries == 0) {
            setTimeout(function(){
              setChartExtremes()
            }, 1000)
          }
        }
      })
    })
    setChartExtremes()
  })

  $('.highcharts-legend-item').off('click').on('click',function(event) {
    if (event.ctrlKey || event.metaKey || event.altKey) {
      event.stopImmediatePropagation()
      var chart = $('#div_graph').highcharts()
      if (event.altKey) {
        $(chart.series).each(function(idx, item) {
          item.show()
        })
      } else {
        var serieId = $(this).attr("class").split('highcharts-series-')[1].split(' ')[0]
        $(chart.series).each(function(idx, item) {
          item.hide()
        })
        chart.series[serieId].show()
      }
    }
    setChartOptions()
    setTimeout(setChartExtremes, 500)
  })
}

function addChart(_cmd_id, _action, _options) {
  //_action: 0=remove 1=add
  if (_action == 0) {
    //remove serie:
    if (isset(jeedom.history.chart['div_graph']) && isset(jeedom.history.chart['div_graph'].chart) && isset(jeedom.history.chart['div_graph'].chart.series)) {
      $(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie) {
        try {
          if (serie.options.id == _cmd_id) {
            serie.remove()
            setChartOptions()
            setTimeout(function(){
              setChartExtremes()
            }, 500)
          }
        } catch(error) {}
      })
    }
    return
  }

  var dateStart = $('#in_startDate').value()
  var dateEnd = $('#in_endDate').value()
  jeedom.history.drawChart({
    cmd_id: _cmd_id,
    el: 'div_graph',
    dateRange : 'all',
    dateStart : dateStart,
    dateEnd :  dateEnd,
    height : $('#div_graph').height(),
    option : _options,
    success: function(data) {
      if (isset(data.error)) {
        $('.li_history[data-cmd_id='+_cmd_id+']').removeClass('active')
        return
      }
      $('.highcharts-legend-item').last().attr('data-cmd_id', _cmd_id)
      setChartOptions()
    }
  })
}

function emptyHistory(_cmd_id, _date) {
  $.ajax({
    type: "POST",
    url: "core/ajax/cmd.ajax.php",
    data: {
      action: "emptyHistory",
      id: _cmd_id,
      date: _date
    },
    dataType: 'json',
    error: function(request, status, error) {
      handleAjaxError(request, status, error)
    },
    success: function(data) {
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'})
        return
      }
      $('#div_alert').showAlert({message: '{{Historique supprimé avec succès}}', level: 'success'})
      li = $('li[data-cmd_id=' + _cmd_id + ']')
      if (li.hasClass('active')) {
        li.find('.history').click()
      }
    }
  })
}

function setChartExtremes() {
  try {
    var yExtremes = chart.yAxis[0].getExtremes(true)
    var min = yExtremes.dataMin / 1.005
    var max = yExtremes.dataMax * 1.005
    chart.yAxis[0].setExtremes(min, max, true, true)
  }
  catch(error) {}
}