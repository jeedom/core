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

if (!jeeFrontEnd.history) {
  jeeFrontEnd.history = {
    __el__: 'div_graph',
    __chartHeight__: null,
    __lastId__: null,
    resizeDone: null,
    loadIds: null,
    init: function() {
      window.jeeP = this
      this.$pageContainer = $('#pageContainer')
    },
    postInit: function() {
      this.$divGraph = $('#' + this.__el__)
      this.setCalculList()
      delete jeedom.history.chart[this.__el__]
      this.setChartOptions()
      this.resizeDn()
      this.loadIds = getUrlVars('cmd_id')
      if (typeof this.loadIds == 'string') {
        this.loadIds = this.loadIds.split('-')
        if (is_numeric(this.loadIds[0])) {
          this.loadIds.forEach(function(cmd_id) {
            var li = $('.li_history[data-cmd_id=' + cmd_id + ']')
            if (li) {
              li.find('.history').click()
              li.closest('.cmdList').show()
              li.closest('.cmdList').prev().prev().find('i.fas').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
            }
          })
        }
      }
    },
    resizeDn: function() {
      if (jeedom.history.chart[this.__el__] === undefined) return
      this.__chartHeight__ = this.$divGraph.height() - $('#div_historyOptions').outerHeight(true)
      if (jeedom.history.chart[this.__el__].chart) {
        jeedom.history.chart[this.__el__].chart.setSize(this.$divGraph.width(), this.__chartHeight__)
      }
    },
    highcharts_done_callback: function(_chartId) {
      this.setChartOptions()
    },
    /*
    grouping/type/variation/step Compare mode available only if one curve in chart
    */
    setChartOptions: function() {
      if (!isset(jeedom.history.chart[this.__el__])) return
      var _disabled = true
      var currentSeries = jeedom.history.chart[this.__el__].chart.series.filter(key => !key.name.includes('Navigator '))

      if (currentSeries.length == 1) { //only one series in chart:
        var serieId = currentSeries[0].userOptions.id
        var isCalcul = $('li.li_history[data-cmd_id="' + serieId + '"]').find('a.history').attr('data-calcul') === undefined ? false : true
        if (isCalcul) {
          this.__lastId__ = null
          document.getElementById('cb_derive').checked = false
          document.getElementById('cb_step').checked = false
          document.querySelectorAll('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').forEach((element, index) => {
            element.disabled = _disabled
          })
          document.getElementById('bt_compare').addClass('disabled')
        } else {
          this.__lastId__ = currentSeries[currentSeries.length - 1].userOptions.id
          _disabled = false
          if (isset(currentSeries[0].userOptions.dataGrouping)) {
            var grouping = currentSeries[0].userOptions.dataGrouping.enabled
            if (grouping) {
              var groupingType = currentSeries[0].userOptions.dataGrouping.approximation + '::' + currentSeries[0].userOptions.dataGrouping.units[0][0]
              document.getElementById('sel_groupingType').value = groupingType
            } else {
              document.getElementById('sel_groupingType').selectedIndex = 0
            }
          }

          var type = currentSeries[0].userOptions.type
          if (type == 'areaspline') type = 'area'
          document.getElementById('sel_chartType').value = type
          document.getElementById('cb_derive').checked = currentSeries[0].userOptions.derive
          document.getElementById('cb_step').checked = currentSeries[0].userOptions.step
          document.querySelectorAll('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').forEach((element, index) => {
            element.disabled = _disabled
          })

          document.getElementById('bt_compare').removeClass('disabled')
        }
      } else {
        this.__lastId__ = null
        document.getElementById('sel_groupingType').selectedIndex = 0
        document.getElementById('sel_chartType').selectedIndex = 0
        document.getElementById('cb_derive').checked = false
        document.getElementById('cb_step').checked = false
        document.querySelectorAll('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').forEach((element, index) => {
          element.disabled = _disabled
        })
        jeedom.history.chart[this.__el__].comparing ? document.getElementById('bt_compare').removeClass('disabled') : document.getElementById('bt_compare').addClass('disabled')
      }
    },
    addChart: function(_cmd_id, _action, _options) {
      if (_action == 0) { //Remove series
        if (isset(jeedom.history.chart[this.__el__]) && isset(jeedom.history.chart[this.__el__].chart) && isset(jeedom.history.chart[this.__el__].chart.series)) {
          $(jeedom.history.chart[this.__el__].chart.series).each(function(i, serie) {
            try {
              if (serie.options.id == _cmd_id) {
                serie.yAxis.remove()
                jeedom.history.chart[this.__el__].chart.get(serie.options.id).remove(false)
              }
            } catch (error) {}
          })
        }
        jeedom.history.chart[this.__el__].doing = 0
        jeedom.history.chartDone(this.__el__)
        return
      }

      //Add series:
      var dateStart = document.getElementById('in_startDate').value
      var dateEnd = document.getElementById('in_endDate').value
      jeedom.history.drawChart({
        cmd_id: _cmd_id,
        el: this.__el__,
        dateRange: 'all',
        dateStart: dateStart,
        dateEnd: dateEnd,
        height: this.__chartHeight__,
        option: _options,
        compare: 0,
        success: function(data) {
          if (isset(data.error)) {
            $('.li_history[data-cmd_id="' + _cmd_id + '"]').removeClass('active')
            return
          }
          jeeP.__lastId__ = _cmd_id
          jeeP.resizeDn()
        }
      })
    },
    clearGraph: function() {
      if (!isset(jeedom.history.chart[this.__el__])) return
      jeedom.history.chart[this.__el__].comparing = false
      document.emptyById(this.__el__)
      delete jeedom.history.chart[this.__el__]
      document.getElementById('bt_compare').removeClass('btn-danger').addClass('btn-success', 'disabled')
      document.querySelectorAll('#ul_history, #historyCalculs').forEach((element, index) => {
        element.querySelectorAll('.li_history.active')?.removeClass('active')
      })
      this.setChartOptions()
    },
    emptyHistory: function(_cmd_id, _date) {
      domUtils.ajax({
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
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
            return
          }
          jeedomUtils.showAlert({
            message: '{{Historique supprimé avec succès}}',
            level: 'success'
          })
        }
      })
    },
    compareChart: function(_cmd_id) {
      //compare:
      var fromStart, fromEnd, toStart, toEnd
      fromStart = document.getElementById('in_compareStart1').value + ' 00:00:00'
      fromEnd = document.getElementById('in_compareEnd1').value + ' 23:59:59'
      toStart = document.getElementById('in_compareStart2').value + ' 00:00:00'
      toEnd = document.getElementById('in_compareEnd2').value + ' 23:59:59'

      //Existing serie dateRange can vary, remove all series:
      jeedom.history.setAxisScales(this.__el__, {redraw: true, resetDateRange: true})
      jeedom.history.emptyChart(this.__el__, true)

      //add data from both date range:
      self = this
      jeedom.history.drawChart({
        cmd_id: _cmd_id,
        el: self.__el__,
        dateRange: 'all',
        dateStart: fromStart,
        dateEnd: fromEnd,
        height: self.__chartHeight__,
        option: {lastPointToEnd: 1, allowZero: 1},
        success: function(data) {
          jeedom.history.drawChart({
            cmd_id: _cmd_id,
            el: self.__el__,
            dateRange: 'all',
            dateStart: toStart,
            dateEnd: toEnd,
            height: self.__chartHeight__,
            option: {lastPointToEnd: 1, allowZero: 1, graphScaleVisible: false},
            compare: 1,
            success: function(data) {
              jeedom.history.chart[self.__el__].chart.xAxis[0].setExtremes(null, null, false)
              jeedom.history.chart[self.__el__].chart.redraw()
              jeedom.history.setAxisScales(self.__el__, {redraw: true})
            }
          })
        }
      })
      jeeDialog.get('#md_historyCompare').hide()
    },
    setCalculList: function() {
      var $el = $('#historyCalculs')
      var isOpened = false
      if ($el && $el.find('.displayObject i.fas').hasClass('fa-arrow-circle-down')) isOpened = true
      jeedom.config.load({
        configuration: 'calculHistory',
        convertToHumanReadable : true,
        error: function(error) {
          jeedomUtils.showAlert({message: error.message, level: 'danger'})
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
  }
}

jeeFrontEnd.history.init()

document.getElementById('in_searchHistory').value = ''
moment.locale(jeeFrontEnd.language.substring(0, 2))
jeedomUtils.datePickerInit()

//handle resizing:
window.registerEvent("resize", function history(event) {
  clearTimeout(jeeFrontEnd.history.resizeDone)
  jeeP.resizeDone = setTimeout(function() { jeeP.resizeDn() }, 100)
})

/************Left button list UI***********/
$('#bt_validChangeDate').on('click', function() {
  if (jeedom.history.chart[jeeP.__el__] === undefined) return
  $('.highcharts-plot-band').remove()
  $(jeedom.history.chart[jeeP.__el__].chart.series).each(function(i, serie) {
    if (serie.options && !isNaN(serie.options.id)) {
      var cmd_id = serie.options.id
      jeeP.addChart(cmd_id, 0)
      jeeP.addChart(cmd_id, 1)
    }
  })
})
jeeP.$pageContainer.on({
  'click': function(event) {
    try {
      var _startDate = moment(document.getElementById('in_startDate').value, 'YYYY-MM-DD')
      var _endDate = document.getElementById('in_endDate').value

      var range = $(event.target).parent('g').attr('data-range')
      var newStartdDate = ''

      switch (range) {
        case 'year':
          newStartdDate = _startDate.subtract(1, 'years').format('YYYY-MM-DD')
          break
        case 'month':
          newStartdDate = _startDate.subtract(1, 'months').format('YYYY-MM-DD')
          break
        case 'week':
          newStartdDate = _startDate.subtract(1, 'weeks').format('YYYY-MM-DD')
          break
      }
      if (newStartdDate !='') {
        document.getElementById('in_startDate').value = newStartdDate
        document.getElementById('bt_validChangeDate').triggerEvent('click')
      }
    } catch (error) {}
  }
}, 'g.highcharts-range-selector-group .highcharts-button.highcharts-button-disabled')


$('#bt_findCmdCalculHistory').on('click', function() {
  jeedom.cmd.getSelectModal({
    cmd: {
      type: 'info',
      subType: 'numeric',
      isHistorized: 1
    }
  }, function(result) {
    document.getElementById('in_calculHistory').insertAtCursor(result.human)
  })
})
$('#bt_displayCalculHistory').on('click', function() {
  var calcul = document.getElementById('in_calculHistory').jeeValue()
  if (calcul != '') jeeP.addChart(calcul, 1)
})
$('#bt_configureCalculHistory').on('click', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Configuration des formules de calcul}}",
    beforeClose: function() {
      jeeP.setCalculList()
    },
    contentUrl: 'index.php?v=d&modal=history.calcul'
  })
})
$('#bt_openCmdHistoryConfigure').on('click', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Configuration de l'historique des commandes}}",
    contentUrl: 'index.php?v=d&modal=cmd.configureHistory'
  })
})

//Right options:
$('#sel_groupingType').off('change').on('change', function(event) {
  if (event.isTrigger == 3) return
  if (jeeP.__lastId__ == null) return
  var currentId = jeeP.__lastId__
  var groupingType = this.value
  $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active')
  jeeP.addChart(currentId, 0)
  jeedom.cmd.save({
    cmd: {
      id: currentId,
      display: {
        groupingType: groupingType
      }
    },
    error: function(error) {
      jeedomUtils.showAlert({
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
  if (jeeP.__lastId__ == null) return
  var currentId = jeeP.__lastId__
  var graphType = this.value
  $('.li_history[data-cmd_id=' + currentId + ']').removeClass('active')
  jeeP.addChart(currentId, 0)
  jeedom.cmd.save({
    cmd: {
      id: currentId,
      display: {
        graphType: graphType
      }
    },
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      $('.li_history[data-cmd_id=' + currentId + ']').addClass('active')
      jeeP.addChart(currentId)
    }
  })
})

$('#cb_derive').off('change').on('change', function(event) {
  if (event.isTrigger == 3) return
  if (jeeP.__lastId__ == null) return
  var currentId = jeeP.__lastId__
  var graphDerive = this.checked ? '1' : '0'
  jeeP.addChart(currentId, 0)
  jeedom.cmd.save({
    cmd: {
      id: currentId,
      display: {
        graphDerive: graphDerive
      }
    },
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeeP.addChart(data.id, 1)
    }
  })
})

$('#cb_step').off('change').on('change', function(event) {
  if (event.isTrigger == 3) return
  if (jeeP.__lastId__ == null) return
  var currentId = jeeP.__lastId__
  var graphStep = this.checked ? '1' : '0'
  jeeP.addChart(currentId, 0)
  jeedom.cmd.save({
    cmd: {
      id: currentId,
      display: {
        graphStep: graphStep
      }
    },
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeeP.addChart(data.id, 1)
    }
  })
})


//Right buttons:
$('#bt_clearGraph').on('click', function() {
  jeeP.clearGraph()
})

//search filter opening:
jeeP.$pageContainer.on({
  'keyup': function(event) {
    if (this.value == '') {
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
jeeP.$pageContainer.on({
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

jeeP.$pageContainer.on({
  'click': function(event) {
    jeedomUtils.hideAlert()
    if (event.target.className.includes('remove') || event.target.className.includes('export')) return
    if (isset(jeedom.history.chart[jeeP.__el__]) && jeedom.history.chart[jeeP.__el__].comparing) return

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
      jeeP.addChart($(this).closest('.li_history').attr('data-cmd_id'), 0, options)
    } else {
      $(this).closest('.li_history').addClass('active')
      jeeP.addChart($(this).closest('.li_history').attr('data-cmd_id'), 1, options)
    }
    return false
  }
}, '.li_history .history')

jeeP.$pageContainer.on({
  'click': function(event) {
    jeedomUtils.hideAlert()
    var bt_remove = $(this)
    jeeDialog.prompt({
      inputType: 'date',
      pattern: '[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}',
      placeholder: 'yyyy-mm-dd hh:mm:ss',
      message: '{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de}} <span style="font-weight: bold ;"> ' + bt_remove.closest('.li_history').find('.history').text() + '</span> (laissez vide pour tout supprimer) ?'
      }, function(result) {
      if (result !== null) {
        jeeP.emptyHistory(bt_remove.closest('.li_history').attr('data-cmd_id'), result)
      }
    })
  }
}, '.li_history .remove')

jeeP.$pageContainer.on({
  'click': function(event) {
    window.open('core/php/export.php?type=cmdHistory&id=' + $(this).closest('.li_history').attr('data-cmd_id'), "_blank", null)
  }
}, '.li_history .export')


//__________________Comparison functions

//Compare period modal presets:
$('#sel_setPeriod').off('change').on('change', function() {
  var startDate = document.getElementById('in_compareEnd1').value
  var num = this.value.split('.')[0]
  var type = this.value.split('.')[1]

  var m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  document.getElementById('in_compareStart1').value = endDate

  //range to compare with:
  num = document.getElementById('sel_comparePeriod').value.split('.')[0]
  type = document.getElementById('sel_comparePeriod').value.split('.')[1]

  startDate = endDate
  m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  document.getElementById('in_compareStart2').value = endDate
})
$('#sel_comparePeriod').off('change').on('change', function() {
  var startDate = document.getElementById('in_compareEnd1').value
  var num = this.value.split('.')[0]
  var type = this.value.split('.')[1]

  var m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  document.getElementById('in_compareEnd2').value = endDate

  startDate = document.getElementById('in_compareStart1').value
  m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
  endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
  document.getElementById('in_compareStart2').value = endDate
})

jeeDialog.dialog({
  id: 'md_historyCompare',
  title: "{{Période de comparaison}}",
  show: false,
  width: 720,
  height: 260,
  callback: function() {
    var contentEl = jeeDialog.get('#md_historyCompare', 'content')
    var newContent = document.getElementById('md_getCompareRange')
    contentEl.appendChild(newContent)
    newContent.removeClass('hidden')

    document.getElementById('in_compareStart1').value = document.getElementById('in_startDate').value
    document.getElementById('sel_setPeriod').triggerEvent('change')
    document.getElementById('sel_comparePeriod').triggerEvent('change')
  },
  buttons: {
    confirm: {
      label: '<i class="fas fa-check"></i> {{Comparer}}',
      className: 'success',
      callback: {
        click: function(event) {
          jeedom.history.chart[jeeP.__el__].comparing = true
          $('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').prop('disabled', true)
          $('#bt_compare').removeClass('btn-success').addClass('btn-danger')
          jeedom.history.chart[jeeP.__el__].chart.xAxis[1].update({
            visible: true
          })
          jeeP.compareChart(jeeP.__lastId__)
        }
      }
    },
    cancel: {
      className: 'hidden'
    }
  },
})


$('#md_getCompareRange').on({
  'change': function(event) {
    var fromStart = moment(document.getElementById('in_compareStart1').value + ' 00:00:00', 'YYYY-MM-DD HH:mm:ss')
    var fromEnd = moment(document.getElementById('in_compareEnd1').value + ' 23:59:59', 'YYYY-MM-DD HH:mm:ss')
    var toStart = moment(document.getElementById('in_compareStart2').value + ' 00:00:00', 'YYYY-MM-DD HH:mm:ss')
    var toEnd = moment(document.getElementById('in_compareEnd2').value + ' 23:59:59', 'YYYY-MM-DD HH:mm:ss')

    var diffPeriod = fromEnd.diff(fromStart, 'days')
    var cdiffPeriod = toEnd.diff(toStart, 'days')
    var text = '{{Comparer}} ' + diffPeriod + ' {{jours avec}} ' + cdiffPeriod + ' {{jours il y a}} ' + $('#sel_comparePeriod option:selected').text()
    $('#md_getCompareRange .spanCompareDiffResult').text(text)
    if (diffPeriod != cdiffPeriod) {
      jeeDialog.get('#md_historyCompare').show()
    } else {
      jeeDialog.get('#md_historyCompare').hide()
    }
  }
}, 'input.in_datepicker')

$('#bt_compare').off().on('click', function() {
  if (!jeedom.history.chart[jeeP.__el__].comparing) { //Go comparing:
    if (jeeP.__lastId__ == null) return
    $(this).attr('data-cmdId', jeeP.__lastId__)
    jeeDialog.get('#md_historyCompare').show()
  } else { //Stop comparing:
    jeeP.clearGraph()
    jeeP.addChart($(this).attr('data-cmdId'))
    $('li.li_history[data-cmd_id="' + $(this).attr('data-cmdId') + '"]').addClass('active')
    $(this).removeClass('btn-danger').addClass('btn-success')
  }
})

jeeFrontEnd.history.postInit()