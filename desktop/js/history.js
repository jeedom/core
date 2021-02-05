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

var lastId = null
var isComparing = false
var chart = $('#div_graph').highcharts()
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
  jeedomUtils.datePickerInit()
  setChartOptions()
})

//handle resizing:
var resizeDone
function resizeDn() {
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


/************Left button list UI***********/
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


$('#bt_openCmdHistoryConfigure').on('click', function() {
  $('#md_modal').dialog({title: "{{Configuration de l'historique des commandes}}"}).load('index.php?v=d&modal=cmd.configureHistory').dialog('open')
})

//search filter opening:
$('body').on({
  'keyup': function(event) {
    if ($(this).value() == '') {
      $('.cmdList').hide()
      $('.displayObject').find('i.fas').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
    } else {
      $('.cmdList').show()
      //let filtering job gone:
      setTimeout(function() {
        $('.cmdList').each(function() {
          if ($(this).find('.li_history:visible').length > 0) {
            $('.displayObject[data-object_id=' + $(this).attr('data-object_id') + ']').find('i.fas').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
          } else {
            $('.displayObject[data-object_id=' + $(this).attr('data-object_id') + ']').find('i.fas').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
          }
        })
      }, 250)
    }
  }
}, 'ul li input.filter')

$(".li_history .history").on('click', function(event) {
  $.hideAlert()
  if (isComparing) return
  if ($(this).closest('.li_history').hasClass('active')) {
    $(this).closest('.li_history').removeClass('active')
    addChart($(this).closest('.li_history').attr('data-cmd_id'), 0)
  } else {
    $(this).closest('.li_history').addClass('active')
    addChart($(this).closest('.li_history').attr('data-cmd_id'), 1)
  }
  return false
})

$('.displayObject').on('click', function() {
  var list = $('.cmdList[data-object_id=' + $(this).attr('data-object_id') + ']')
  if (list.is(':visible')) {
    $(this).find('i.fas').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
    list.hide()
  } else {
    $(this).find('i.fas').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
    list.show()
  }
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
$(".li_history .export").on('click', function() {
  window.open('core/php/export.php?type=cmdHistory&id=' + $(this).closest('.li_history').attr('data-cmd_id'), "_blank", null)
})



/************Charting***********/
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
        $('#bt_compare').removeClass('disabled')
        initHistoryTrigger()
        return false
      }
    })
  } else {
    lastId = null
    $('#sel_groupingType').val($('#sel_groupingType option:first').val())
    $('#sel_chartType').val($('#sel_chartType option:first').val())
    $('#bt_compare').addClass('disabled')
    setChartYExtremes()
  }
  $('#sel_groupingType, #sel_chartType').prop('disabled', _prop)
  resizeDn()
}

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
        $('.li_history[data-cmd_id=' + currentId + ']').addClass('active')
        addChart(currentId)
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
              setChartYExtremes()
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
              setChartYExtremes()
            }, 1000)
          }
        }
      })
    })
    setChartYExtremes()
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
    setTimeout(setChartYExtremes, 500)
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
              setChartYExtremes()
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
    compare: 0,
    success: function(data) {
      if (isset(data.error)) {
        $('.li_history[data-cmd_id='+_cmd_id+']').removeClass('active')
        return
      }
      $('.highcharts-legend-item').last().attr('data-cmd_id', _cmd_id)
      setChartOptions()
      setTimeout(function(){
        setChartYExtremes()
      }, 500)
    }
  })
}

$('#bt_clearGraph').on('click', function() {
  clearGraph()
})

function clearGraph(_lastId=null) {
  isComparing = false
  if (jeedom.history.chart['div_graph'] === undefined) return
  while (jeedom.history.chart['div_graph'].chart.series.length > 0) {
    jeedom.history.chart['div_graph'].chart.series[0].remove(true)
  }
  $('#bt_compare').removeClass('btn-danger').addClass('btn-success').addClass('disabled')
  chart.xAxis[1].update({visible: false})
  delete jeedom.history.chart['div_graph']
  $('#ul_history').find('.li_history.active').removeClass('active')
  setChartOptions()
  lastId = _lastId
}

$('#cb_tracking').off('change').on('change', function() {
  if (chart) {
    if ($(this).is(':checked')) {
      var opacity = 0.1
    } else {
      var opacity = 1
    }
    chart.update({
        plotOptions: {
          series: {
            states: {
              inactive: {opacity: opacity}
            }
          }
        }
      })
  }
})

$("#md_getCompareRange").dialog({
  closeText: '',
  autoOpen: false,
  modal: true,
  width: 520,
  height: 180,
  open: function() {
    $(this).parent().css({'top': 120})
    $('#in_compareStart1').value($('#in_startDate').value())
    $('#in_compareEnd1').value($('#in_endDate').value())
  },
  beforeClose: function(event, ui) {
  }
})

$('#bt_compare').off().on('click', function() {
  if (!isComparing) {
    if (lastId == null) return
    $('#md_getCompareRange').removeClass('hidden').dialog({title: "{{Période de comparaison}}"}).dialog('open')
  } else {
    clearGraph(lastId)
    chart.xAxis[1].update({visible: false})
    chart.update({
      navigator: { enabled: true },
      scrollbar: { enabled: true }
    })
    addChart(lastId)
    $('li.li_history[data-cmd_id="'+lastId+'"]').addClass('active')
    $(this).removeClass('btn-danger').addClass('btn-success')
  }
})

$('#bt_doCompare').off('click').on('click', function() {
  isComparing = true
  $('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').prop('disabled', true)
  $('#bt_compare').removeClass('btn-success').addClass('btn-danger')
  chart.xAxis[1].update({visible: true})
  compareChart(lastId)
})

function compareChart(_cmd_id, _options) {
  //compare:
  var fromStart, fromEnd, toStart, toEnd
  fromStart = $('#in_compareStart1').value() +' 00:00:00'
  fromEnd = $('#in_compareEnd1').value() +' 23:59:59'
  toStart = $('#in_compareStart2').value() +' 00:00:00'
  toEnd = $('#in_compareEnd2').value() +' 23:59:59'

  //remove all series from chart:
  while (jeedom.history.chart['div_graph'].chart.series.length > 0) {
    jeedom.history.chart['div_graph'].chart.series[0].remove(true)
  }

  //add data from both date range:
  jeedom.history.drawChart({
    cmd_id: _cmd_id,
    el: 'div_graph',
    dateRange : 'all',
    dateStart : fromStart,
    dateEnd :  fromEnd,
    height : $('#div_graph').height(),
    option : _options,
    success: function(data) {
      jeedom.history.drawChart({
        cmd_id: _cmd_id,
        el: 'div_graph',
        dateRange : 'all',
        dateStart : toStart,
        dateEnd :  toEnd,
        height : $('#div_graph').height(),
        option : _options,
        compare: 1,
        success: function(data) {
          setTimeout(function() {
            setChartXExtremes()
            setChartYExtremes()
          }, 500)
        }
      })
    }
  })
  $('#md_getCompareRange').dialog('close')
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

function setChartYExtremes() {
  if (!chart) return
  var max = 0
  var min = 10000
  chart.yAxis.forEach((axis, index) => {
    if (axis.getExtremes().dataMin != null && axis.getExtremes().dataMin < min ) min = axis.getExtremes().dataMin
    if (axis.getExtremes().dataMax != null && axis.getExtremes().dataMax > max ) max = axis.getExtremes().dataMax
  })
  chart.yAxis.forEach((axis, index) => {
    axis.setExtremes(min / 1.005, max * 1.005, true, false)
  })
}

function setChartXExtremes() {
  //only used for comparison
  try {
    var xExtremes0 = chart.xAxis[0].getExtremes()
    var xExtremes1 = chart.xAxis[1].getExtremes()
    chart.xAxis[0].setExtremes(xExtremes0.dataMin, xExtremes0.dataMin + (xExtremes1.dataMax - xExtremes1.dataMin), true, false)
    chart.xAxis[1].setExtremes(xExtremes1.dataMin, xExtremes1.dataMax, true, false)
    chart.update({
      navigator: { enabled: false },
      scrollbar: { enabled: false }
    })
  }
  catch(error) {}
}