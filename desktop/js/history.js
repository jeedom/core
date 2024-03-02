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
      this.pageContainer = document.getElementById('pageContainer')
      this.setHistoryOptions(false)
      document.getElementById('in_searchHistory').value = ''
      moment.locale(jeeFrontEnd.language.substring(0, 2))
      jeedomUtils.datePickerInit()
    },
    postInit: function() {
      this.divGraph = document.getElementById(this.__el__)
      this.setCalculList()
      delete jeedom.history.chart[this.__el__]
      this.setChartOptions()
      this.resizeDn()

      //Load ids from url:
      this.loadIds = getUrlVars('cmd_id')
      if (typeof this.loadIds == 'string') {
        this.loadIds = this.loadIds.split('-')
        if (is_numeric(this.loadIds[0])) {
          this.loadIds.forEach(function(cmd_id) {
            var li = document.querySelector('.li_history[data-cmd_id="' + cmd_id + '"]')
            if (li) {
              li.addClass('active')
              jeeFrontEnd.history.addChart(cmd_id, 1)
              li.closest('.cmdList').seen()
              li.closest('.cmdList').parentNode.querySelector('.displayObject > i.fas').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
            }
          })
        }
      }
    },
    resizeDn: function() {
      if (jeedom.history.chart[this.__el__] === undefined) return
      this.__chartHeight__ = this.divGraph.offsetHeight - document.getElementById('div_historyOptions').offsetHeight
      if (jeedom.history.chart[this.__el__].chart) {
        jeedom.history.chart[this.__el__].chart.setSize(this.divGraph.offsetWidth, this.__chartHeight__)
      }
    },
    highcharts_done_callback: function(_chartId) {
      this.setChartOptions()
    },
    //grouping/type/variation/step Compare mode available only if one curve in chart
    setChartOptions: function() {
      if (!isset(jeedom.history.chart[this.__el__])) return
      var _disabled = true
      var currentSeries = jeedom.history.chart[this.__el__].chart.series.filter(key => !key.name.includes('Navigator '))

      if (currentSeries.length == 1) { //only one series in chart:
        var serieId = currentSeries[0].userOptions.id
        var isCalcul = document.querySelector('li.li_history[data-cmd_id="' + serieId + '"] a.history')?.getAttribute('data-calcul') === null ? false : true
        if (isCalcul) {
          this.__lastId__ = null
          document.getElementById('cb_derive').checked = false
          document.getElementById('cb_step').checked = false
          this.setHistoryOptions(false)
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
          this.setHistoryOptions(true)
          document.getElementById('bt_compare').removeClass('disabled')
        }
      } else {
        this.__lastId__ = null
        document.getElementById('sel_groupingType').selectedIndex = 0
        document.getElementById('sel_chartType').selectedIndex = 0
        document.getElementById('cb_derive').checked = false
        document.getElementById('cb_step').checked = false
        this.setHistoryOptions(false)
        jeedom.history.chart[this.__el__].comparing ? document.getElementById('bt_compare').removeClass('disabled') : document.getElementById('bt_compare').addClass('disabled')
      }
    },
    addChart: function(_cmd_id, _action, _options) {
      if (_action == 0) { //Remove series
        if (isset(jeedom.history.chart[this.__el__]) && isset(jeedom.history.chart[this.__el__].chart) && isset(jeedom.history.chart[this.__el__].chart.series)) {
          jeedom.history.chart[this.__el__].chart.series.forEach(_serie => {
            try {
              if (_serie.options.id == _cmd_id) {
                _serie.yAxis.remove()
                jeedom.history.chart[this.__el__].chart.get(_serie.options.id).remove(false)
              }
            } catch (error) {}
          })
        }
        jeeP.__lastId__ = null
        this.setChartOptions()
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
            document.querySelector('.li_history[data-cmd_id="' + _cmd_id + '"]').removeClass('active')
            return
          }
          jeeP.__lastId__ = _cmd_id
          jeeP.setChartOptions()
          jeeP.resizeDn()
        }
      })
    },
    clearGraph: function() {
      if (!isset(jeedom.history.chart[this.__el__])) return
      jeedom.history.chart[this.__el__].comparing = false
      document.getElementById(this.__el__).empty()
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
    getCompareModal: function() {
      jeeDialog.dialog({
        id: 'md_historyCompare',
        title: "{{Période de comparaison}}",
        width: 720,
        height: 260,
        callback: function() {
          var contentEl = jeeDialog.get('#md_historyCompare', 'content')

          if (contentEl.querySelector('#md_getCompareRange') == null) {
            var newContent = document.getElementById('md_getCompareRange-template').cloneNode(true)
            newContent.setAttribute('id', 'md_getCompareRange')
            newContent.removeClass('hidden')
            contentEl.appendChild(newContent)
          }

          //Set modal events:
          document.getElementById('md_getCompareRange').addEventListener('change', function(event) {
            var _target = null
            var _md = document.getElementById('md_getCompareRange')
            if (_target = event.target.closest('#md_getCompareRange input.in_datepicker')) {
              var fromStart = moment(_md.querySelector('#in_compareStart1').value + ' 00:00:00', 'YYYY-MM-DD HH:mm:ss')
              var fromEnd = moment(_md.querySelector('#in_compareEnd1').value + ' 23:59:59', 'YYYY-MM-DD HH:mm:ss')
              var toStart = moment(_md.querySelector('#in_compareStart2').value + ' 00:00:00', 'YYYY-MM-DD HH:mm:ss')
              var toEnd = moment(_md.querySelector('#in_compareEnd2').value + ' 23:59:59', 'YYYY-MM-DD HH:mm:ss')

              var diffPeriod = fromEnd.diff(fromStart, 'days')
              var cdiffPeriod = toEnd.diff(toStart, 'days')
              var text = '{{Comparer}} ' + diffPeriod + ' {{jours avec}} ' + cdiffPeriod + ' {{jours il y a}} ' + document.getElementById('sel_comparePeriod').selectedOptions[0].text
              _md.querySelector('.spanCompareDiffResult').textContent = text
              if (diffPeriod != cdiffPeriod) {
                jeeDialog.get('#md_historyCompare').show()
              } else {
                jeeDialog.get('#md_historyCompare').hide()
              }
              return
            }

            if (_target = event.target.closest('#md_getCompareRange #sel_setPeriod')) {
              var startDate = _md.querySelector('#in_compareEnd1').value
              var num = event.target.value.split('.')[0]
              var type = event.target.value.split('.')[1]

              var m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
              var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
              document.getElementById('in_compareStart1').value = endDate

              //range to compare with:
              num = _md.querySelector('#sel_comparePeriod').value.split('.')[0]
              type = _md.querySelector('#sel_comparePeriod').value.split('.')[1]

              startDate = endDate
              m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
              endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
              _md.querySelector('#in_compareStart2').value = endDate
              return
            }

            if (_target = event.target.closest('#md_getCompareRange #sel_comparePeriod')) {
              var startDate = _md.querySelector('#in_compareEnd1').value
              var num = event.target.value.split('.')[0]
              var type = event.target.value.split('.')[1]

              var m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
              var endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
              _md.querySelector('#in_compareEnd2').value = endDate

              startDate = _md.querySelector('#in_compareStart1').value
              m_startDate = moment(startDate, 'YYYY-MM-DD HH:mm:ss')
              endDate = m_startDate.subtract(num, type).format("YYYY-MM-DD")
              _md.querySelector('#in_compareStart2').value = endDate
              return
            }
          })

          var _md = document.getElementById('md_getCompareRange')
          _md.querySelector('#in_compareStart1').value = document.querySelector('#in_startDate').value
          _md.querySelector('#sel_setPeriod').triggerEvent('change')
          _md.querySelector('#sel_comparePeriod').triggerEvent('change')
        },
        onShown: function() {
          jeeDialog.get('#md_historyCompare', 'content').querySelector('#md_getCompareRange').removeClass('hidden')
        },
        buttons: {
          confirm: {
            label: '<i class="fas fa-check"></i> {{Comparer}}',
            className: 'success',
            callback: {
              click: function(event) {
                jeedom.history.chart[jeeP.__el__].comparing = true
                document.querySelectorAll('#sel_groupingType, #sel_chartType, #cb_derive, #cb_step').forEach(_check => { _check.checked = true})
                document.getElementById('bt_compare').removeClass('btn-success').addClass('btn-danger')
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
    },
    compareChart: function(_cmd_id) {
      //compare:
      var fromStart, fromEnd, toStart, toEnd
      var _md = document.getElementById('md_getCompareRange')
      fromStart = _md.querySelector('#in_compareStart1').value + ' 00:00:00'
      fromEnd = _md.querySelector('#in_compareEnd1').value + ' 23:59:59'
      toStart = _md.querySelector('#in_compareStart2').value + ' 00:00:00'
      toEnd = _md.querySelector('#in_compareEnd2').value + ' 23:59:59'

      //Existing serie dateRange can vary, remove all series:
      jeedom.history.setAxisScales(this.__el__, {redraw: true, resetDateRange: true})
      jeedom.history.emptyChart(this.__el__, true)

      //add data from both date range:
      let self = this
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
    setHistoryOptions: function(_mode) {
      if (!isset(_mode)) _mode = true
      document.getElementById('div_historyOptions').querySelectorAll('input, select, a').forEach(_ctrl => {
        if (_ctrl.getAttribute('id') != 'in_startDate' && _ctrl.getAttribute('id') != 'in_endDate' && _ctrl.getAttribute('id') != 'bt_validChangeDate' && _ctrl.getAttribute('id') != 'bt_compare' && _ctrl.getAttribute('id') != 'bt_clearGraph') {
          if (_mode) {
            _ctrl.removeClass('disabled')
          } else {
            _ctrl.addClass('disabled')
          }
        }
      })
    },
    setCalculList: function() {
      var elCalculList = document.getElementById('historyCalculs')
      var isOpened = false
      if (elCalculList != null && elCalculList.querySelector('.displayObject i.fas')?.hasClass('fa-arrow-circle-down')) isOpened = true
      jeedom.config.load({
        configuration: 'calculHistory',
        convertToHumanReadable : true,
        error: function(error) {
          jeedomUtils.showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          if (!elCalculList) return
          elCalculList.empty()
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
          elCalculList.insertAdjacentHTML('beforeend', html)
          if (isOpened) elCalculList.querySelector('.displayObject').click()
        }
      })
    }
  }
}

jeeFrontEnd.history.init()

jeeFrontEnd.history.postInit()

//Register events on top of page container:
window.registerEvent("resize", function history(event) {
  clearTimeout(jeeFrontEnd.history.resizeDone)
  jeeP.resizeDone = setTimeout(function() { jeeP.resizeDn() }, 100)
})


/*Events delegations
*/
//Options
document.getElementById('div_historyOptions').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_validChangeDate')) {
    if (jeedom.history.chart[jeeP.__el__] === undefined) return
    jeedom.history.chart[jeeP.__el__].chart.series.forEach(_series => {
      if (_series.options && !isNaN(_series.options.id)) {
        var cmdId = _series.options.id
        jeeP.addChart(cmdId, 0)
        jeeP.addChart(cmdId, 1)
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_compare')) {
    if (!jeedom.history.chart[jeeP.__el__].comparing) { //Go comparing:
      if (jeeP.__lastId__ == null) return
      _target.setAttribute('data-cmdId', jeeP.__lastId__)
      jeeP.setHistoryOptions(false)
      jeeP.getCompareModal()
    } else { //Stop comparing:
      jeeP.clearGraph()
      jeeP.addChart(_target.getAttribute('data-cmdId'))
      document.querySelector('li.li_history[data-cmd_id="' + _target.getAttribute('data-cmdId') + '"]').addClass('active')
      _target.removeClass('btn-danger').addClass('btn-success')
      jeeP.setHistoryOptions(true)
    }
    return
  }

  if (_target = event.target.closest('#bt_clearGraph')) {
    jeeP.clearGraph()
    return
  }
})

document.getElementById('div_historyOptions').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('#sel_groupingType')) {
    if (event.isTrigger == 3) return
    if (jeeP.__lastId__ == null) return
    var currentId = jeeP.__lastId__
    var groupingType = _target.value
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
        jeeP.addChart(currentId)
      }
    })
    return
  }

  if (_target = event.target.closest('#sel_chartType')) {
    if (event.isTrigger == 3) return
    if (jeeP.__lastId__ == null) return
    var currentId = jeeP.__lastId__
    var graphType = _target.value
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
        jeeP.addChart(currentId)
      }
    })
    return
  }

  if (_target = event.target.closest('#cb_derive')) {
    if (event.isTrigger == 3) return
    if (jeeP.__lastId__ == null) return
    var currentId = jeeP.__lastId__
    var graphDerive = _target.checked ? '1' : '0'
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
    return
  }

  if (_target = event.target.closest('#cb_step')) {
    if (event.isTrigger == 3) return
    if (jeeP.__lastId__ == null) return
    var currentId = jeeP.__lastId__
    var graphStep = _target.checked ? '1' : '0'
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
    return
  }
})
//Sidebar:
document.getElementById('sidebar').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_findCmdCalculHistory')) {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'info',
        subType: 'numeric',
        isHistorized: 1
      }
    }, function(result) {
      document.getElementById('in_calculHistory').insertAtCursor(result.human)
    })
    return
  }

  if (_target = event.target.closest('#bt_displayCalculHistory')) {
    var calcul = document.getElementById('in_calculHistory').jeeValue()
    if (calcul != '') jeeP.addChart(calcul, 1)
    return
  }

  if (_target = event.target.closest('#bt_configureCalculHistory')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Configuration des formules de calcul}}",
      beforeClose: function() {
        jeeP.setCalculList()
      },
      contentUrl: 'index.php?v=d&modal=history.calcul'
    })
    return
  }

  if (_target = event.target.closest('#bt_openCmdHistoryConfigure')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Configuration de l'historique des commandes}}",
      contentUrl: 'index.php?v=d&modal=cmd.configureHistory'
    })
    return
  }

  if (_target = event.target.closest('#bt_resetSearch')) {
    document.getElementById('in_searchHistory').jeeValue('').triggerEvent('keyup')
    return
  }

  if (_target = event.target.closest('.displayObject')) {
    let list = document.querySelector('.cmdList[data-object_id="' + _target.getAttribute('data-object_id') + '"]')
    if (!list) return
    if (list.isVisible()) {
      _target.querySelector('i.fas').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
      list.unseen()
    } else {
      _target.querySelector('i.fas').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
      list.seen()
    }
    return
  }

  if (_target = event.target.closest('.li_history .remove')) {
    jeedomUtils.hideAlert()
    jeeDialog.prompt({
      placeholder: 'yyyy-mm-dd hh:mm:ss or -1',
      message: '{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de}} <span style="font-weight: bold ;"> ' + _target.closest('.li_history').querySelector('.history').textContent + ' ?</span><br>({{Mettez -1 pour tout supprimer}})'
      }, function(result) {
      if (result !== null) {
        jeeP.emptyHistory(_target.closest('.li_history').getAttribute('data-cmd_id'), result)
      }
    })
    return
  }

  if (_target = event.target.closest('.li_history .export')) {
    window.open('core/php/export.php?type=cmdHistory&id=' + _target.closest('.li_history').getAttribute('data-cmd_id'), "_blank", null)
    return
  }

  if (_target = event.target.closest('.li_history .history')) {
    jeedomUtils.hideAlert()
    if (_target.hasClass('remove') || _target.hasClass('export')) return
    if (isset(jeedom.history.chart[jeeP.__el__]) && jeedom.history.chart[jeeP.__el__].comparing) return

    var options = null
    if (_target.hasClass('historycalcul')) {
      var options = {
        graphType: _target.getAttribute('data-graphtype'),
        groupingType: _target.getAttribute('data-groupingtype'),
        graphStep: (_target.getAttribute('data-graphstep') == 0) ? false : true,
        name: _target.textContent
      }
    }
    if (_target.closest('.li_history').hasClass('active')) {
      _target.closest('.li_history').removeClass('active')
      jeeP.addChart(_target.closest('.li_history').getAttribute('data-cmd_id'), 0, options)
    } else {
      _target.closest('.li_history').addClass('active')
      jeeP.addChart(_target.closest('.li_history').getAttribute('data-cmd_id'), 1, options)
    }
    return
  }

})

document.getElementById('sidebar').addEventListener('keyup', function(event) {
  var _target = null
  if (_target = event.target.closest('ul li input.filter')) {
    if (event.target.value == '') {
      document.querySelectorAll('#ul_history .cmdList').unseen()
      document.querySelectorAll('.displayObject i.fas').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
    } else {
      document.querySelectorAll('#ul_history .cmdList').seen()
      //let filtering job gone:
      setTimeout(function() {
        document.querySelectorAll('#ul_history .cmdList').forEach(_cmd => {
          if (_cmd.querySelectorAll('.li_history:not([style="display:none"]').length > 0) {
            document.querySelector('.displayObject[data-object_id="' + _cmd.getAttribute('data-object_id') + '"] i.fas')?.removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down')
          } else {
            document.querySelector('.displayObject[data-object_id="' + _cmd.getAttribute('data-object_id') + '"] i.fas')?.removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right')
          }
        })
      }, 250)
    }
    return
  }
})

