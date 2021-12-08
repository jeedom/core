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
var $divGraph = $('#div_graph')
var __el__ = 'div_graph'
delete jeedom.history.chart[__el__]

$(function() {
  var cmdIds = getUrlVars('cmd_id')
  if (typeof cmdIds == 'string') {
    cmdIds = cmdIds.split('-')
    if (is_numeric(cmdIds[0])) {
      cmdIds.forEach(function(cmd_id) {
        var li = $('.li_history[data-cmd_id=' + cmd_id + ']')
        if (li) {
          li.find('.history').click()
          li.closest('.cmdList').show()
        }
      })
    }
  }
  resizeDn()
  moment.locale(jeedom_langage)
  jeedomUtils.datePickerInit()
  setChartOptions()
})

//handle resizing:
var resizeDone
function resizeDn() {
  if (jeedom.history.chart[__el__] === undefined) return
  var height = $divGraph.height() - $('#div_historyOptions').outerHeight(true)
  if (jeedom.history.chart[__el__].chart) {
    jeedom.history.chart[__el__].chart.setSize($divGraph.width(), height)
  }
  $('.bs-sidebar').height(height)
}
$(window).resize(function() {
  clearTimeout(resizeDone)
  resizeDone = setTimeout(resizeDn, 100)
})


/************Left button list UI***********/
$('#bt_validChangeDate').on('click', function() {
  if (jeedom.history.chart[__el__] === undefined) return
  $('.highcharts-plot-band').remove()
  $(jeedom.history.chart[__el__].chart.series).each(function(i, serie) {
    if (serie.options && !isNaN(serie.options.id)) {
      var cmd_id = serie.options.id
      addChart(cmd_id, 0)
      addChart(cmd_id, 1)
    }
  })
})
$('#bt_findCmdCalculHistory').on('click', function() {
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'info',
      subType: 'numeric',
      isHistorized: 1
    }
  }, function(result) {
    $('#in_calculHistory').atCaret('insert', result.human)
  })
})
$('#bt_displayCalculHistory').on('click', function() {
  var calcul = $('#in_calculHistory').value()
  if (calcul != '') addChart(calcul, 1)
})
$('#bt_configureCalculHistory').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration des formules de calcul}}"
  }).load('index.php?v=d&modal=history.calcul').dialog('open')
})
$('#bt_openCmdHistoryConfigure').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de l'historique des commandes}}"
  }).load('index.php?v=d&modal=cmd.configureHistory').dialog('open')
})

//Right options:
function initHistoryTrigger() {
  $('#sel_groupingType').off('change').on('change', function() {
    if (lastId == null) return
    var currentId = lastId
    var groupingType = $(this).value()
    $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active')
    addChart(currentId, 0)
    jeedom.cmd.save({
      cmd: {
        id: currentId,
        display: {
          groupingType: groupingType
        }
      },
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
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
      cmd: {
        id: currentId,
        display: {
          graphType: graphType
        }
      },
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        $('.li_history[data-cmd_id=' + currentId + ']').addClass('active')
        addChart(currentId)
      }
    })
  })

  $('#cb_derive').off('change').on('change', function() {
    var graphDerive = $(this).value()
    $(jeedom.history.chart[__el__].chart.series).each(function(idx, serie) {
      if (!isset(serie.userOptions) || !isset(serie.userOptions.id)) {
        return
      }
      var cmdId = serie.userOptions.id
      addChart(cmdId, 0)
      jeedom.cmd.save({
        cmd: {
          id: cmdId,
          display: {
            graphDerive: graphDerive
          }
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          addChart(data.id, 1)
        }
      })
    })

  })

  $('#cb_step').off('change').on('change', function() {
    var graphStep = $(this).value()
    $(jeedom.history.chart[__el__].chart.series).each(function(idx, serie) {
      if (!isset(serie.userOptions) || !isset(serie.userOptions.id)) {
        return
      }
      var cmdId = serie.userOptions.id
      addChart(cmdId, 0)
      jeedom.cmd.save({
        cmd: {
          id: cmdId,
          display: {
            graphStep: graphStep
          }
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          addChart(data.id, 1)
        }
      })
    })
  })
}

//Right buttons:
$('#bt_clearGraph').on('click', function() {
  clearGraph()
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
  if (isset(jeedom.history.chart[__el__]) && jeedom.history.chart[__el__].comparing) return
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
  bootbox.prompt('{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de}} <span style="font-weight: bold ;"> ' + bt_remove.closest('.li_history').find('.history').text() + '</span> (laissez vide pour tout supprimer) ?', function(result) {
    if (result !== null) {
      emptyHistory(bt_remove.closest('.li_history').attr('data-cmd_id'), result)
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
    _prop = false
    var serieId = $('.highcharts-legend-item:not(.highcharts-legend-item-hidden)').attr("class").split('highcharts-series-')[1].split(' ')[0]
    if (!isset(jeedom.history.chart[__el__].chart.series[serieId])) return
    lastId = jeedom.history.chart[__el__].chart.series[serieId].userOptions.id

    var grouping, groupingType, type
    $(jeedom.history.chart[__el__].chart.series).each(function(idx, serie) {
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
  }
  $('#sel_groupingType, #sel_chartType').prop('disabled', _prop)
  resizeDn()
}

function addChart(_cmd_id, _action, _options) {
  //_action: 0=remove 1=add
  if (_action == 0) {
    //remove serie:
    if (isset(jeedom.history.chart[__el__]) && isset(jeedom.history.chart[__el__].chart) && isset(jeedom.history.chart[__el__].chart.series)) {
      $(jeedom.history.chart[__el__].chart.series).each(function(i, serie) {
        try {
          if (serie.options.id == _cmd_id) {
            serie.yAxis.remove()
            jeedom.history.chart[__el__].chart.get(serie.options.id).remove(false)
          }
        } catch (error) {}
      })
    }
    if (!jeedom.history.chart[__el__].yAxisScaling) jeedomUIHistory.resetyAxisScaling(__el__)
    setChartOptions()
    return
  }
  var dateStart = $('#in_startDate').value()
  var dateEnd = $('#in_endDate').value()
  jeedom.history.drawChart({
    cmd_id: _cmd_id,
    el: __el__,
    dateRange: 'all',
    dateStart: dateStart,
    dateEnd: dateEnd,
    height: $divGraph.height(),
    option: _options,
    compare: 0,
    success: function(data) {
      if (isset(data.error)) {
        $('.li_history[data-cmd_id=' + _cmd_id + ']').removeClass('active')
        return
      }
      setChartOptions()
    }
  })
}

function clearGraph(_lastId = null) {
  jeedom.history.chart[__el__].comparing = false
  if (jeedom.history.chart[__el__] === undefined) return

  $.clearDivContent(__el__)
  delete jeedom.history.chart[__el__]
  $('#bt_compare').removeClass('btn-danger').addClass('btn-success').addClass('disabled')
  $('#ul_history').find('.li_history.active').removeClass('active')
  setChartOptions()
  lastId = _lastId
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
        $.fn.showAlert({
          message: data.result,
          level: 'danger'
        })
        return
      }
      $.fn.showAlert({
        message: '{{Historique supprimé avec succès}}',
        level: 'success'
      })
      li = $('li[data-cmd_id=' + _cmd_id + ']')
      if (li && li.hasClass('active')) {
        li.find('.history').click()
      }
    }
  })
}

function resetChartXExtremes() {
  //only used for comparison
  try {
    var xExtremes0 = jeedom.history.chart[__el__].chart.xAxis[0].getExtremes()
    var xExtremes1 = jeedom.history.chart[__el__].chart.xAxis[1].getExtremes()
    jeedom.history.chart[__el__].chart.xAxis[0].setExtremes(xExtremes0.dataMin, xExtremes0.dataMin + (xExtremes1.dataMax - xExtremes1.dataMin), true, false)
    jeedom.history.chart[__el__].chart.xAxis[1].setExtremes(xExtremes1.dataMin, xExtremes1.dataMax, true, false)
    jeedom.history.chart[__el__].chart.update({
      navigator: {
        enabled: false
      },
      scrollbar: {
        enabled: false
      }
    })
  } catch (error) {}
}

//__________________Comparison functions

//Compare period modal presets:
$('#sel_setPeriod').off('change').on('change', function() {
  var startDate = $('#in_compareEnd1').value()
  var num = $(this).value().split('.')[0]
  var type = $(this).value().split('.')[1]

  var m_startDate = moment(startDate)
  var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareStart1').value(endDate)

  //range to compare with:
  num = $('#sel_comparePeriod').value().split('.')[0]
  type = $('#sel_comparePeriod').value().split('.')[1]

  startDate = endDate
  m_startDate = moment(startDate)
  endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareStart2').value(endDate)
})
$('#sel_comparePeriod').off('change').on('change', function() {
  var startDate = $('#in_compareEnd1').value()
  var num = $(this).value().split('.')[0]
  var type = $(this).value().split('.')[1]

  var m_startDate = moment(startDate)
  var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareEnd2').value(endDate)

  startDate = $('#in_compareStart1').value()
  m_startDate = moment(startDate)
  endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareStart2').value(endDate)
})

//Load date ranges modal:
$("#md_getCompareRange").dialog({
  closeText: '',
  autoOpen: false,
  modal: true,
  width: 680,
  height: 180,
  open: function() {
    $(this).parent().css({
      'top': 120
    })
    $('#in_compareStart1').value($('#in_startDate').value())
    $('#sel_setPeriod').trigger('change')
    $('#sel_comparePeriod').trigger('change')
  },
  beforeClose: function(event, ui) {}
})

$('#bt_compare').off().on('click', function() {
  if (!jeedom.history.chart[__el__].comparing) {
    //Go comparing:
    if (lastId == null) return
    $('#md_getCompareRange').removeClass('hidden').dialog({
      title: "{{Période de comparaison}}"
    }).dialog('open')
  } else {
    //Stop comparing:
    clearGraph(lastId)
    addChart(lastId)
    $('li.li_history[data-cmd_id="' + lastId + '"]').addClass('active')
    $(this).removeClass('btn-danger').addClass('btn-success')
  }
})

$('#bt_doCompare').off('click').on('click', function() {
  jeedom.history.chart[__el__].comparing = true
  $('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').prop('disabled', true)
  $('#bt_compare').removeClass('btn-success').addClass('btn-danger')
  jeedom.history.chart[__el__].chart.xAxis[1].update({
    visible: true
  })
  compareChart(lastId)
})

function compareChart(_cmd_id) {
  //compare:
  var fromStart, fromEnd, toStart, toEnd
  fromStart = $('#in_compareStart1').value() + ' 00:00:00'
  fromEnd = $('#in_compareEnd1').value() + ' 23:59:59'
  toStart = $('#in_compareStart2').value() + ' 00:00:00'
  toEnd = $('#in_compareEnd2').value() + ' 23:59:59'

  //Existing serie dateRange can vary, remove all series:
  jeedomUIHistory.emptyChart(__el__)

  //add data from both date range:
  jeedom.history.drawChart({
    cmd_id: _cmd_id,
    el: __el__,
    dateRange: 'all',
    dateStart: fromStart,
    dateEnd: fromEnd,
    height: $divGraph.height(),
    success: function(data) {
      jeedom.history.drawChart({
        cmd_id: _cmd_id,
        el: __el__,
        dateRange: 'all',
        dateStart: toStart,
        dateEnd: toEnd,
        height: $divGraph.height(),
        option: {graphScaleVisible: false},
        compare: 1,
        success: function(data) {
          jeedomUIHistory.resetyAxisScaling(__el__)
          setTimeout(function() {
            resetChartXExtremes()
          }, 500)
        }
      })
    }
  })
  $('#md_getCompareRange').dialog('close')
}

//__________________Legend items
$(function() {
  jeedomUIHistory.initLegendContextMenu(__el__)
})