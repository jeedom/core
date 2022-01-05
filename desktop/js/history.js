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

var $divGraph = $('#div_graph')
var __el__ = 'div_graph'
var __chartHeight__ = null
var __lastId__ = null //last cmd_id added to chart
delete jeedom.history.chart[__el__]

var $pageContainer = $('#pageContainer')
$(function() {
  $('#in_searchHistory').val('').keyup()
  var cmdIds = getUrlVars('cmd_id')
  if (typeof cmdIds == 'string') {
    cmdIds = cmdIds.split('-')
    if (is_numeric(cmdIds[0])) {
      cmdIds.forEach(function(cmd_id) {
        var li = $('.li_history[data-cmd_id=' + cmd_id + ']')
        if (li) {
          li.find('.history').click()
          li.closest('.cmdList').show()
          li.closest('.cmdList').prev().prev().find('i.fas').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
        }
      })
    }
  }
  setCalculList()
  moment.locale(jeedom_langage.substring(0, 2))
  jeedomUtils.datePickerInit()
  setChartOptions()
  resizeDn()
})

//handle resizing:
var resizeDone
function resizeDn() {
  if (jeedom.history.chart[__el__] === undefined) return
  var __chartHeight__ = $divGraph.height() - $('#div_historyOptions').outerHeight(true)
  if (jeedom.history.chart[__el__].chart) {
    jeedom.history.chart[__el__].chart.setSize($divGraph.width(), __chartHeight__)
  }
  $('.bs-sidebar').height(__chartHeight__)
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
    title: "{{Configuration des formules de calcul}}",
    beforeClose: function(event, ui) {
      setCalculList()
    }
  }).load('index.php?v=d&modal=history.calcul').dialog('open')
})
$('#bt_openCmdHistoryConfigure').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de l'historique des commandes}}"
  }).load('index.php?v=d&modal=cmd.configureHistory').dialog('open')
})

//Right options:
$('#sel_groupingType').off('change').on('change', function(event) {
  if (event.isTrigger == 3) return
  if (__lastId__ == null) return
  var currentId = __lastId__
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

$('#sel_chartType').off('change').on('change', function(event) {
  if (event.isTrigger == 3) return
  if (__lastId__ == null) return
  var currentId = __lastId__
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

$('#cb_derive').off('change').on('change', function(event) {
  if (event.isTrigger == 3) return
  if (__lastId__ == null) return
  var currentId = __lastId__
  var graphDerive = $(this).value()
  addChart(currentId, 0)
  jeedom.cmd.save({
    cmd: {
      id: currentId,
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

$('#cb_step').off('change').on('change', function(event) {
  if (event.isTrigger == 3) return
  if (__lastId__ == null) return
  var currentId = __lastId__
  var graphStep = $(this).value()
  addChart(currentId, 0)
  jeedom.cmd.save({
    cmd: {
      id: currentId,
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


//Right buttons:
$('#bt_clearGraph').on('click', function() {
  clearGraph()
})

//search filter opening:
$pageContainer.on({
  'keyup': function(event) {
    if ($(this).value() == '') {
      $('#ul_history .cmdList').hide()
      $('.displayObject').find('i.fas').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
    } else {
      $('#ul_history .cmdList').show()
      //let filtering job gone:
      setTimeout(function() {
        $('#ul_history .cmdList').each(function() {
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

$('#bt_resetSearch').on('click', function() {
  $('#in_searchHistory').val('').keyup()
})

//List of object / cmds event:
$pageContainer.on({
  'click': function(event) {
    var list = $('.cmdList[data-object_id=' + $(this).attr('data-object_id') + ']')
    if (list.is(':visible')) {
      $(this).find('i.fas').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
      list.hide()
    } else {
      $(this).find('i.fas').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
      list.show()
    }
  }
}, '.displayObject')

$pageContainer.on({
  'click': function(event) {
    $.hideAlert()
    if (isset(jeedom.history.chart[__el__]) && jeedom.history.chart[__el__].comparing) return

    var options = null
    if ($(this).hasClass('historycalcul')) {
      var options = {
        graphType: $(this).attr('data-graphtype'),
        groupingType: $(this).attr('data-groupingtype'),
        graphStep: ($(this).attr('data-graphstep') == 0) ? false : true,
        name: $(this).text()
      }
    }
    if ($(this).closest('.li_history').hasClass('active')) {
      $(this).closest('.li_history').removeClass('active')
      addChart($(this).closest('.li_history').attr('data-cmd_id'), 0, options)
    } else {
      $(this).closest('.li_history').addClass('active')
      addChart($(this).closest('.li_history').attr('data-cmd_id'), 1, options)
    }
    return false
  }
}, '.li_history .history')

$pageContainer.on({
  'click': function(event) {
    $.hideAlert()
    var bt_remove = $(this)
    bootbox.prompt('{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de}} <span style="font-weight: bold ;"> ' + bt_remove.closest('.li_history').find('.history').text() + '</span> (laissez vide pour tout supprimer) ?', function(result) {
      if (result !== null) {
        emptyHistory(bt_remove.closest('.li_history').attr('data-cmd_id'), result)
      }
    })
  }
}, '.li_history .remove')

$pageContainer.on({
  'click': function(event) {
    window.open('core/php/export.php?type=cmdHistory&id=' + $(this).closest('.li_history').attr('data-cmd_id'), "_blank", null)
  }
}, '.li_history .export')

/*
Set list of calculs on history page, synched back from modal calcul
*/
function  setCalculList() {
  var $el = $('#historyCalculs')
  var isOpened = false
  if ($el && $el.find('.displayObject i.fas').hasClass('fa-arrow-circle-down')) isOpened = true
  jeedom.config.load({
    configuration: 'calculHistory',
    convertToHumanReadable : true,
    error: function(error) {
      $.showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      if (!$el) return
      $el.empty()
      if (data.length == 0) return

      var html = '<span class="label cursor displayObject" data-object_id="jeedom-config-calculs" style="background-color:var(--btn-default-color);color:var(--linkHoverLight-color);">{{Mes Calculs}} <i class="fas fa-arrow-circle-right"></i></span>'
      html += '<br/>'
      html += '<div class="cmdList" data-object_id="jeedom-config-calculs" style="display:none;margin-left : 20px;">'
      for (var i in data) {
        if (isset(data[i].calcul) && data[i].calcul != '') {
          var dataName = data[i].name != '' ? data[i].name : data[i].calcul.substring(0, 40)
          html += '<li class="cursor li_history" data-cmd_id="' + data[i].calcul + '">';
          html += '<a class="history historycalcul" title="' + data[i].calcul + '" data-calcul="' + data[i].calcul + '" data-graphstep="' + data[i].graphStep + '" data-graphtype="' + data[i].graphType + '" data-groupingtype="' + data[i].groupingType + '">' + dataName + '</a>';
          html += '</li>';
        }
      }
      html += '</div><br/>'
      $el.append(html)
      if (isOpened) $el.find('.displayObject').trigger('click')
    }
  })
}

/************Charting***********/

/*
grouping/type/variation/step Compare mode available only if one curve in chart
@page loaded, jeedom.history.setAxisScales(), clearGraph()
*/
function setChartOptions(_chartId) {
  if (!isset(jeedom.history.chart[__el__])) return
  var _prop = 'disabled'
  var currentSeries = jeedom.history.chart[__el__].chart.series.filter(key => !key.name.includes('Navigator '))

  if (currentSeries.length == 1) { //only one series in chart:
    var serieId = currentSeries[0].userOptions.id
    var isCalcul = $('li.li_history[data-cmd_id="' + serieId + '"]').find('a.history').attr('data-calcul') === undefined ? false : true
    if (isCalcul) {
      __lastId__ = null
      $("#cb_derive, #cb_step").prop("checked", false)
      $('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').prop('disabled', _prop)
      $('#bt_compare').addClass('disabled')
    } else {
      __lastId__ = currentSeries[currentSeries.length - 1].userOptions.id
      _prop = false
      var grouping = currentSeries[0].userOptions.dataGrouping.enabled
      if (grouping) {
        var groupingType = currentSeries[0].userOptions.dataGrouping.approximation + '::' + currentSeries[0].userOptions.dataGrouping.units[0][0]
        $('#sel_groupingType').value(groupingType)
      } else {
        $('#sel_groupingType').val($('#sel_groupingType option:first').val())
      }

      if (isset(currentSeries[0].userOptions.dataGrouping)) {
        var grouping = currentSeries[0].userOptions.dataGrouping.enabled
        if (grouping) {
          var groupingType = currentSeries[0].userOptions.dataGrouping.approximation + '::' + currentSeries[0].userOptions.dataGrouping.units[0][0]
          $('#sel_groupingType').value(groupingType)
        } else {
          $('#sel_groupingType').val($('#sel_groupingType option:first').val())
        }
      }

      var type = currentSeries[0].userOptions.type
      if (type == 'areaspline') type = 'area'
      $('#sel_chartType').value(type)

      $("#cb_derive").prop("checked", currentSeries[0].userOptions.derive)
      $("#cb_step").prop("checked", currentSeries[0].userOptions.step)

      $('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').prop('disabled', _prop)
      $('#bt_compare').removeClass('disabled')
    }
  } else {
    __lastId__ = null
    $('#sel_groupingType').val($('#sel_groupingType option:first').val())
    $('#sel_chartType').val($('#sel_chartType option:first').val())
    $("#cb_derive, #cb_step").prop("checked", false)
    $('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').prop('disabled', _prop)
    jeedom.history.chart[__el__].comparing ? $('#bt_compare').removeClass('disabled') : $('#bt_compare').addClass('disabled')
  }
}

function addChart(_cmd_id, _action, _options) {
  if (_action == 0) { //Remove series
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
    jeedom.history.chart[__el__].doing = 0
    jeedom.history.chartDone(__el__)
    return
  }

  //Add series:
  var dateStart = $('#in_startDate').value()
  var dateEnd = $('#in_endDate').value()
  jeedom.history.drawChart({
    cmd_id: _cmd_id,
    el: __el__,
    dateRange: 'all',
    dateStart: dateStart,
    dateEnd: dateEnd,
    height: __chartHeight__,
    option: _options,
    compare: 0,
    success: function(data) {
      if (isset(data.error)) {
        $('.li_history[data-cmd_id="' + _cmd_id + '"]').removeClass('active')
        return
      }
      __lastId__ = _cmd_id
      resizeDn()
    }
  })
}

function clearGraph() {
  jeedom.history.chart[__el__].comparing = false
  if (jeedom.history.chart[__el__] === undefined) return

  $.clearDivContent(__el__)
  delete jeedom.history.chart[__el__]
  $('#bt_compare').removeClass('btn-danger').addClass('btn-success').addClass('disabled')
  $('#ul_history, #historyCalculs').find('.li_history.active').removeClass('active')
  setChartOptions()
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

//__________________Comparison functions

//Compare period modal presets:
$('#sel_setPeriod').off('change').on('change', function() {
  var startDate = $('#in_compareEnd1').value()
  var num = $(this).value().split('.')[0]
  var type = $(this).value().split('.')[1]

  var m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareStart1').value(endDate)

  //range to compare with:
  num = $('#sel_comparePeriod').value().split('.')[0]
  type = $('#sel_comparePeriod').value().split('.')[1]

  startDate = endDate
  m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareStart2').value(endDate)
})
$('#sel_comparePeriod').off('change').on('change', function() {
  var startDate = $('#in_compareEnd1').value()
  var num = $(this).value().split('.')[0]
  var type = $(this).value().split('.')[1]

  var m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareEnd2').value(endDate)

  startDate = $('#in_compareStart1').value()
  m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  $('#in_compareStart2').value(endDate)
})

//Load date ranges modal:
$("#md_getCompareRange").dialog({
  closeText: '',
  autoOpen: false,
  modal: true,
  width: 720,
  height: 260,
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

$('#md_getCompareRange').on({
  'change': function(event) {
    var fromStart = moment($('#in_compareStart1').value() + ' 00:00:00', 'YYYY-MM-DD HH:mm:ss')
    var fromEnd = moment($('#in_compareEnd1').value() + ' 23:59:59', 'YYYY-MM-DD HH:mm:ss')
    var toStart = moment($('#in_compareStart2').value() + ' 00:00:00', 'YYYY-MM-DD HH:mm:ss')
    var toEnd = moment($('#in_compareEnd2').value() + ' 23:59:59', 'YYYY-MM-DD HH:mm:ss')

    var diffPeriod = fromEnd.diff(fromStart, 'days')
    var cdiffPeriod = toEnd.diff(toStart, 'days')
    var text = '{{Comparer}} ' + diffPeriod + ' {{jours avec}} ' + cdiffPeriod + ' {{jours il y a}} ' + $('#sel_comparePeriod option:selected').text()
    $('#md_getCompareRange .spanCompareDiffResult').text(text)
    if (diffPeriod != cdiffPeriod) {
      $('#md_getCompareRange .spanCompareDiff').show()
    } else {
      $('#md_getCompareRange .spanCompareDiff').hide()
    }
  }
}, 'input.in_datepicker')

$('#bt_compare').off().on('click', function() {
  if (!jeedom.history.chart[__el__].comparing) { //Go comparing:
    if (__lastId__ == null) return
    $(this).attr('data-cmdId', __lastId__)
    $('#md_getCompareRange').removeClass('hidden').dialog({
      title: "{{Période de comparaison}}"
    }).dialog('open')
  } else { //Stop comparing:
    clearGraph()
    addChart($(this).attr('data-cmdId'))
    $('li.li_history[data-cmd_id="' + $(this).attr('data-cmdId') + '"]').addClass('active')
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
  compareChart(__lastId__)
})

function compareChart(_cmd_id) {
  //compare:
  var fromStart, fromEnd, toStart, toEnd
  fromStart = $('#in_compareStart1').value() + ' 00:00:00'
  fromEnd = $('#in_compareEnd1').value() + ' 23:59:59'
  toStart = $('#in_compareStart2').value() + ' 00:00:00'
  toEnd = $('#in_compareEnd2').value() + ' 23:59:59'

  //Existing serie dateRange can vary:
  jeedom.history.setAxisScales(__el__, {redraw: true, resetDateRange: true})
  jeedom.history.emptyChart(__el__)

  //add data from both date range:
  jeedom.history.drawChart({
    cmd_id: _cmd_id,
    el: __el__,
    dateRange: 'all',
    dateStart: fromStart,
    dateEnd: fromEnd,
    height: __chartHeight__,
    option: {lastPointToEnd: 1, allowZero: 1},
    success: function(data) {
      jeedom.history.drawChart({
        cmd_id: _cmd_id,
        el: __el__,
        dateRange: 'all',
        dateStart: toStart,
        dateEnd: toEnd,
        height: __chartHeight__,
        option: {lastPointToEnd: 1, allowZero: 1, graphScaleVisible: false},
        compare: 1,
        success: function(data) {
          jeedom.history.chart[__el__].chart.xAxis[0].setExtremes(null, null, false)
          jeedom.history.chart[__el__].chart.redraw()
        }
      })
    }
  })
  $('#md_getCompareRange').dialog('close')
}

