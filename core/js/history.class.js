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

jeedom.history = function() { }
jeedom.history.chart = []
jeedom.history.chartDrawTime = 150

jeedom.history.get = function(_params) {
  var paramsRequired = ['cmd_id', 'dateStart', 'dateEnd']
  var paramsSpecifics = {
    global: _params.global || true,
    pre_success: function(data) {
      if (data.result == undefined) return data
      if (isset(jeedom.cmd.cache.byId[data.result.id])) {
        delete jeedom.cmd.cache.byId[data.result.id]
      }
      return data
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'getHistory',
    id: _params.cmd_id,
    dateStart: _params.dateStart || '',
    dateEnd: _params.dateEnd || '',
    addFirstPreviousValue: _params.addFirstPreviousValue || false,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.history.getLast = function(_params) {
  var paramsRequired = ['cmd_id', 'time']
  var paramsSpecifics = {
    global: _params.global || true,
    pre_success: function(data) {
      if (isset(jeedom.cmd.cache.byId[data.result.id])) {
        delete jeedom.cmd.cache.byId[data.result.id]
      }
      return data
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'getLastHistory',
    id: _params.cmd_id,
    time: _params.time || '',
  }
  $.ajax(paramsAJAX)
}

jeedom.history.getInitDates = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'getInitDates'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.history.removeHistoryInFutur = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/history.ajax.php'
  paramsAJAX.data = {
    action: 'removeHistoryInFutur'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.history.copyHistoryToCmd = function(_params) {
  var paramsRequired = ['source_id', 'target_id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'copyHistoryToCmd',
    source_id: _params.source_id,
    target_id: _params.target_id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.history.generatePlotBand = function(_startTime, _endTime) {
  var plotBands = []
  var day = 86400000
  if ((_endTime - _startTime) > (9 * day)) {
    return plotBands
  }
  _startTime = Math.floor(_startTime / day) * day
  var plotBand
  while (_startTime < _endTime) {
    plotBand = {}
    plotBand.from = _startTime
    plotBand.to = _startTime + day
    if (plotBand.to > _endTime) {
      plotBand.to = _endTime
    }
    plotBands.push(plotBand)
    _startTime += 2 * day
  }
  return plotBands
}

jeedom.history.graphUpdate = function(_params) {
  for (var i in _params) {
    if(_params[i].cmd_id == ''){
      continue;
    }
    for(var chart in jeedom.history.chart){
      for(var serie in jeedom.history.chart[chart]){
        if(jeedom.history.chart[chart].chart.series[serie] && jeedom.history.chart[chart].chart.series[serie].options.id == _params[i].cmd_id){
          jeedom.history.chart[chart].chart.series[serie].addPoint([Date.now()+(-1*(new Date()).getTimezoneOffset()*60*1000),_params[i].value])
        }
      }
    }
  }
}

jeedom.history.changePoint = function(_params) {
  // console.log('changePoint:', _params)
  var paramsRequired = ['cmd_id', 'datetime', 'value', 'oldValue']
  var paramsSpecifics = {
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(result) {
      //changePoint() only allowed on history page:
      if (!isset(jeeFrontEnd.history)) return
      if (document.body.dataset.uimode != 'desktop') return
      if (document.body.dataset.page != 'history') return

      jeedomUtils.showAlert({
        message: '{{La valeur a été éditée avec succès}}',
        level: 'success'
      })
      var shown = document.getElementById('ul_history').querySelectorAll('li.li_history.active[data-cmd_id="' + _params.cmd_id + '"]')
      if (shown) {
        jeeFrontEnd.history.addChart(_params.cmd_id, 0)
        jeeFrontEnd.history.addChart(_params.cmd_id, 1)
      }
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'changeHistoryPoint',
    cmd_id: _params.cmd_id,
    datetime: _params.datetime,
    value: _params.value,
    oldValue: _params.oldValue
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.history.modalchangePoint = function(event, _this, _params) {
  if (jeedom.history.chart[_this.series.chart._jeeId].mode == 'view' || jeedom.history.chart[_this.series.chart._jeeId].mode == 'plan') {
    return
  }
  if (jeedomUtils.userDevice.type == 'tablet' || jeedomUtils.userDevice.type == 'phone') return
  if (event.target.closest('div.jeeDialog') != null) return
  if (jeedom.history.chart[_this.series.chart._jeeId].comparing) return

  if (isset(_params.cmd.display.groupingType) && _params.cmd.display.groupingType != '') {
    jeeDialog.alert('{{Impossible de modifier une valeur sur une courbe avec groupement}}' + ' (' + _params.cmd.display.groupingType + ')')
    return
  }

  var id = _this.series.userOptions.id
  var datetime = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', _this.x)
  var value = _this.y
  jeeDialog.prompt({
    title: "{{Edition d'historique}}",
    message: "<b>" + _this.series.name + "</b><br> {{date :}} <b>" + datetime + "</b><br>{{valeur :}} <b>" + value + "</b><br><i>{{Ne rien mettre pour supprimer la valeur}}</i>"
  }, function(result, key) {
    if (key == 'confirm') {
      if (result === null) result = '' //Will remove history point
      jeedom.history.changePoint({
        cmd_id: id,
        datetime: datetime,
        oldValue: value,
        value: result,
        dateStart: _params.dateStart,
        dateEnd: _params.dateEnd
      })
    }
  })
}

jeedom.history.drawChart = function(_params) {
  domUtils.showLoading()
  if (is_object(_params.dateRange)) {
    _params.dateRange = JSON.stringify(_params.dateRange)
  }
  _params.option = init(_params.option, {
    derive: ''
  })
  var _visible = (isset(_params.visible)) ? _params.visible : true

  //get command history
  domUtils.ajax({
    type: "POST",
    url: "core/ajax/cmd.ajax.php",
    data: {
      action: "getHistory",
      id: _params.cmd_id,
      dateRange: (is_object(_params.dateRange)) ? JSON.stringify(_params.dateRange) || '' : _params.dateRange || '',
      dateStart: _params.dateStart || '',
      dateEnd: _params.dateEnd || '',
      derive: _params.option.derive || '',
      allowZero: init(_params.option.allowZero, 0),
      groupingType: _params.option.groupingType || '',
      lastPointToEnd: _params.option.lastPointToEnd || 0,
      allowFuture: _params.option.allowFuture || 0,
      addFirstPreviousValue: _params.addFirstPreviousValue || 0
    },
    dataType: 'json',
    global: _params.global || true,
    error: function(request, status, error) {
      handleAjaxError(request, status, error)
    },
    success: function(data) {
      //check history exist:
      if (data.state != 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        })
        return
      }

      //check is there is some data and manage alerts:
      if (data.result.data.length < 1) {
        if (_params.option.displayAlert == false) {
          return
        }
        if (!_params.noError) {
          var message = '{{Il n\'existe encore aucun historique pour cette commande :}} ' + data.result.history_name
          if (init(data.result.dateStart) != '') {
            message += (init(data.result.dateEnd) != '') ? ' {{du}} ' + data.result.dateStart + ' {{au}} ' + data.result.dateEnd : ' {{à partir de}} ' + data.result.dateStart
          } else {
            message += (init(data.result.dateEnd) != '') ? ' {{jusqu\'au}} ' + data.result.dateEnd : ''
          }
          jeedomUtils.showAlert({
            message: message,
            level: 'warning'
          })
          if (typeof (init(_params.success)) === 'function') {
            _params.success({
              'error': message
            })
          }
        }
        return
      }

      _params.round = data.result.round
      if (_params.round == 0) _params.round = 2

      if (_params.option.unite) {
        data.result.unite = _params.option.unite
      }

      /*
      comparing true
      Chart exist (empty, not reset), first addSeries is reference, second addSeries is comparison
      Both xAxis must start and end with exact same dateRange and hours so everything is comparable
      Both series start from 00:00:00 but reference can end to current time
      @tsFirst: first timestamp existing in data
      @tsStart: dateStart timestamp, data must start there
      @tsLast: last timestamp existing in data
      @tsEnd: dateEnd timestamp, data must end there and difference between end and start must be the same on both series
      */
      var comparisonSerie = false
      if (isset(_params.compare) && _params.compare == 1) comparisonSerie = true

      if (isset(jeedom.history.chart[_params.el]) && (jeedom.history.chart[_params.el].comparing)) {
        var tsFirst, tsStart, tsLast, tsEnd
        tsFirst = data.result.data[0][0]
        tsStart = Date.parse(data.result.dateStart.replace(/-/g, '/') + ' GMT')
        if (tsStart < tsFirst) {
          data.result.data.unshift([tsStart, data.result.data[0][1]])
        }

        if (!comparisonSerie) { //reference series, may ends at current time:
          tsEnd = Date.parse(data.result.dateEnd.replace(/-/g, '/') + ' GMT')
          jeedom.history.chart[_params.el].comparingToEnd = data.result.dateEnd
          jeedom.history.chart[_params.el].comparingTsDiff = tsEnd - tsStart
        } else { //comparison series, must ends at same timestamp diff than reference one:
          tsEnd = tsStart + jeedom.history.chart[_params.el].comparingTsDiff
          //remove leading and trailing over data:
          data.result.data = data.result.data.filter(v => v[0] <= tsEnd)
        }
        tsLast = data.result.data.slice(-1)[0][0]
        if (tsEnd > tsLast) {
          data.result.data.push([tsEnd, data.result.data.slice(-1)[0][1]])
        }
      }

      //set/check some params:
      if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].cmd[_params.cmd_id])) {
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = null
      }
      _params.option.graphDerive = (data.result.derive == "1") ? true : false

      //series colors, options defined in core/js/jeedom.class.js jeedom.init():
      var colors = Highcharts.getOptions().colors
      var colorsNbr = colors.length
      var colorUsed = []
      var numSeries = 0
      if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].chart) && isset(jeedom.history.chart[_params.el].chart.series)) {
        if (jeedom.history.chart[_params.el].chart.series.length > colorsNbr) { //More series than colors, rotate colors:
          numSeries = Math.abs(jeedom.history.chart[_params.el].chart.series.length % colorsNbr) - 1
        } else { //Ensure no two series with same color:
          jeedom.history.chart[_params.el].chart.series.forEach((serie, index) => {
            if (!serie.userOptions.group) {
              var sColorHindex = Highcharts.getOptions().colors.indexOf(serie.color)
              if (!colorUsed.includes(sColorHindex)) colorUsed.push(sColorHindex)
            }

            for (var i = 0; i < colorsNbr; i++) {
              if (!colorUsed.includes(i)) {
                numSeries = i
                break
              }
            }
          })
        }
      }
      var seriesNumber = numSeries + 1
      if (seriesNumber <= 0 || seriesNumber > colorsNbr) seriesNumber = 1
      if (!isset(_params.option.graphColor) || _params.option.graphColor === undefined) {
        _params.option.graphColor = colors[seriesNumber - 1]
      }

      //step/grouping according to cmd subType:
      _params.option.graphStep = (_params.option.graphStep == "1") ? true : false
      if (isset(data.result.cmd)) {
        if (init(_params.option.graphStep) == '') {
          _params.option.graphStep = (data.result.cmd.subType == 'binary') ? true : false
          if (isset(data.result.cmd.display) && init(data.result.cmd.display.graphStep) != '') {
            _params.option.graphStep = (data.result.cmd.display.graphStep == "0") ? false : true
          }
        }
        if (init(_params.option.graphType) == '') {
          _params.option.graphType = (isset(data.result.cmd.display) && init(data.result.cmd.display.graphType) != '') ? data.result.cmd.display.graphType : 'area'
        }
        if (init(_params.option.groupingType) == '' && isset(data.result.cmd.display) && init(data.result.cmd.display.groupingType) != '') {
          var split = data.result.cmd.display.groupingType.split('||')[0]
		  split = split.split('::')
          _params.option.groupingType = {
            function: split[0],
            time: split[1]
          }
        }
      }

      var stacking = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? null : 'value'
      _params.option.graphStack = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? Math.floor(Math.random() * 10000 + 2) : 1
      _params.showLegend = (init(_params.showLegend, true) && init(_params.showLegend, true) != "0") ? true : false
      _params.showTimeSelector = (init(_params.showTimeSelector, true) && init(_params.showTimeSelector, true) != "0") ? true : false
      _params.showScrollbar = (init(_params.showScrollbar, false) && init(_params.showScrollbar, false) != "0") ? true : false
      _params.showNavigator = (init(_params.showNavigator, true) && init(_params.showNavigator, true) != "0") ? true : false
      _params.showAxis = (init(_params.option.graphScaleVisible, true) && init(_params.option.graphScaleVisible, true) != "0") ? true : false

      //define legend and reset graph:
      var legend = {
        borderColor: 'transparent',
        borderWidth: 0,
        symbolHeight: 8,
        symbolWidth: 16,
        symbolRadius: 0,
        align: 'left',
        shadow: false
      }
      legend.enabled = init(_params.showLegend, true)
      if (isset(_params.newGraph) && _params.newGraph == true) {
        delete jeedom.history.chart[_params.el]
      }

      //___________________________jeedom default chart params:
      var charts = {
        zoomType: 'xy',
        marginTop: 40, //ensure same top space for buttons with or without rangeSelector
        resetZoomButton: {
          position: {
            x: 0,
            y: 0
          }
        },
        pinchType: 'xy',
        renderTo: _params.el,
        alignTicks: false,
        spacingBottom: 5,
        spacingTop: 5,
        spacingRight: 5,
        spacingLeft: 5,
        height: _params.height || null,
        style: {
          fontFamily: 'Roboto'
        },
        events: {
          load: function(event) {
            this.setSize(undefined, undefined, false)

            //default min/max set earlier in series
            //.doing initialized at 1 when chart created with first curve
            var thisId = event.target.userOptions._jeeId
            setTimeout(function() {
              try {
                jeedom.history.setRangeSelectorButtons(event.target.userOptions._jeeId)
                jeedom.history.chartCallback(thisId, { type: 'load' })
              } catch (error) { }
            }, 0)
          },
          redraw: function(event) {
            if (this.rangeSelector === undefined) return true
            if (this.chartWidth > 550 && this.rangeSelector.options.dropdown != 'never') {
              this.update({
                rangeSelector: {
                  dropdown: 'never'
                }
              }, false)
              jeedom.history.setRangeSelectorButtons(event.target.userOptions._jeeId)
            } else if (this.chartWidth <= 550 && this.rangeSelector.options.dropdown != 'always') {
              this.update({
                rangeSelector: {
                  dropdown: 'always'
                }
              }, false)
              jeedom.history.setRangeSelectorButtons(event.target.userOptions._jeeId)
            }
          },
          render: function(event) {
            //shift dotted zones clipPaths to ensure no overlapping step mode:
            var solidClip = null
            document.querySelectorAll('.highcharts-zone-graph-0.customSolidZone').forEach(function(element) {
              solidClip = element.getAttribute('clip-path').replace('url(#', '#').replace(')', '')
              document.querySelector(solidClip).style.transform = 'translate(5px)'
            })
            document.querySelectorAll('.highcharts-zone-graph-1.customDotZone').forEach(function(element) {
              customClip = element.getAttribute('clip-path').replace('url(#', '#').replace(')', '')
              document.querySelector(customClip).style.transform = 'translate(5px)'
            })
          },
          addSeries: function(event) {
            var thisId = this._jeeId
            if (jeedom.history.chart[thisId].doing > 0) { //chart not done, loading several series at once:
              jeedom.history.chart[thisId].doing += 1
            } else {                                      //chart done (-1), loading another series later:
              jeedom.history.chart[thisId].doing = 1
            }

            if (!jeedom.history.chart[thisId].zoom) {
              this.update({
                chart: {
                  animation: false,
                },
              }, false, false)

              setTimeout(function() {
                try {
                  jeedom.history.chartCallback(thisId, { type: 'addSeries' })
                } catch (error) { }
              }, 0)
            }
          },
          selection: function(event) {
            var chartId = event.target._jeeId

            if (event.resetSelection) { //Zoom back after reset zoom button
              this.resetZoomButton.hide()
              jeedom.history.chart[chartId].zoom = false
              //No scale/unit change in zoom, set them back:
              try {
                if (jeedom.history.chart[chartId].yAxisScaling) {
                  jeedom.history.chart[chartId].btToggleyaxisScaling.setState(2)
                } else {
                  jeedom.history.chart[chartId].btToggleyaxisScaling.setState(0)
                }
                if (jeedom.history.chart[chartId].yAxisByUnit) {
                  jeedom.history.chart[chartId].btToggleyaxisbyunit.setState(2)
                } else {
                  jeedom.history.chart[chartId].btToggleyaxisbyunit.setState(0)
                }
              } catch (error) { }

              setTimeout(function() {
                try {
                  if (jeedom.history.chart[chartId].comparing) {
                    var options = {
                      type: 'selection',
                      redraw: true,
                      resetDateRange: true,
                    }
                    jeedom.history.chartCallback(chartId, options)
                  } else {
                    var options = {
                      type: 'selection',
                      redraw: true,
                      extremeXmin: jeedom.history.chart[chartId].zoomPrevXmin,
                      extremeXmax: jeedom.history.chart[chartId].zoomPrevXmax,
                    }
                    jeedom.history.chartCallback(chartId, options)
                  }

                } catch (error) { }
              }, 0)

              return false
            } else { //Enter zoom
              //No scale/unit change in zoom:
              try { this.resetZoomButton.show() } catch (error) { } //Not created first time
              jeedom.history.chart[chartId].zoom = true

              jeedom.history.chart[chartId].zoomPrevXmin = this.xAxis[0].min
              jeedom.history.chart[chartId].zoomPrevXmax = this.xAxis[0].max

              try {
                jeedom.history.chart[chartId].btToggleyaxisScaling.setState(3)
                jeedom.history.chart[chartId].btToggleyaxisbyunit.setState(3)
              } catch (error) { }
            }
          }
        }
      }

      if (charts.height < 10) {
        charts.height = null
      }
      if (isset(_params.transparentBackground) && _params.transparentBackground == "1") {
        charts.backgroundColor = 'rgba(255, 255, 255, 0)'
      }
      if (isset(jeedom.history.chart[_params.el]) && jeedom.history.chart[_params.el].type == 'pie') {
        _params.option.graphType = 'pie'
      }

      //pie chart option from views:
      if (_params.option.graphType == 'pie') {
        var series = {
          type: _params.option.graphType,
          id: _params.cmd_id,
          cursor: 'pointer',
          data: [{
            y: data.result.data[data.result.data.length - 1][1],
            name: (isset(_params.option.name)) ? _params.option.name + ' ' + data.result.unite : data.result.history_name + ' ' + data.result.unite, 
            color: _params.option.graphColor
          }],
        }
        if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
          jeedom.history.chart[_params.el] = {}
          jeedom.history.chart[_params.el].cmd = new Array()
          jeedom.history.chart[_params.el].type = _params.option.graphType
          jeedom.history.chart[_params.el].chart = new Highcharts.Chart({
            chart: charts,
            title: {
              text: ''
            },
            credits: { enabled: false },
            exporting: {
              enabled: _params.enableExport || (jeedom.display.version == 'mobile') ? false : true,
              libURL: '3rdparty/highstock/lib/',
              csv: {
                dateFormat: '%Y-%m-%d'
              },
            },
            tooltip: {
              pointFormat: '{point.y} {series.userOptions.unite}<br/>{series.userOptions.shortName}',
              valueDecimals: _params.round,
            },
            plotOptions: {
              column: {
                stacking: 'normal'
              },
              series: {
                animation: {
                  duration: (getUrlVars('report') == 1) ? 0 : jeedom.history.chartDrawTime
                }
              },
              pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                  color: 'var(--link-color)',
                  style: {
                    textOutline: false,
                    fontSize: '12px',
                    fontWeight: 'normal'
                  },
                },
                showInLegend: true
              }
            },
            series: [series]
          })
          //Store references and init buttons from UI:
          jeedom.history.chart[_params.el].containerId = jeedom.history.chart[_params.el].chart.container.id
          jeedom.history.chart[_params.el].chart._jeeId = _params.el
          jeedom.history.chart[_params.el].doing = 1
          jeedom.history.initChart(_params.el)
        } else {
          jeedom.history.chart[_params.el].chart.series[0].addPoint({
            y: data.result.data[data.result.data.length - 1][1],
            name: (isset(_params.option.name)) ? _params.option.name + ' ' + data.result.unite : data.result.history_name + ' ' + data.result.unite,
            color: _params.option.graphColor
          })
        }
      }

      //not pie, standard curve history:
      if (_params.option.graphType != 'pie') {
        var dataGrouping = {
          enabled: false
        }
        if (isset(_params.option.groupingType) && typeof _params.option.groupingType === 'string' && _params.option.groupingType != '') {
          var split = _params.option.groupingType.split('||')[0]
		  split=split.split('::')
          _params.option.groupingType = {
            function: split[0],
            time: split[1]
          }
        }
        if (isset(_params.option.groupingType) && isset(_params.option.groupingType.function) && isset(_params.option.groupingType.time)) {
          dataGrouping = {
            approximation: _params.option.groupingType.function,
            enabled: true,
            forced: true,
            units: [
              [_params.option.groupingType.time, [1]]
            ]
          }
        }

        //cmd info string has no value:
        if (data.result.timelineOnly) {
          if (!isset(jeedom.history.chart[_params.el]) || !isset(jeedom.history.chart[_params.el].nbTimeline)) {
            nbTimeline = 1
          } else {
            jeedom.history.chart[_params.el].nbTimeline++
            nbTimeline = jeedom.history.chart[_params.el].nbTimeline
          }

          var series = {
            type: 'flags',
            visible: _visible,
            name: (isset(_params.option.name)) ? _params.option.name + ' ' + data.result.unite : data.result.history_name + ' ' + data.result.unite,
            data: [],
            id: _params.cmd_id,
            color: _params.option.graphColor,
            shape: 'squarepin',
            cursor: 'pointer',
            y: -30 - 25 * (nbTimeline - 1),
            point: {
              events: {
                click: function(event) {
                  jeedom.history.modalchangePoint(event, this, data.result)
                }
              }
            }
          }

          for (var i in data.result.data) {
            series.data.push({
              x: data.result.data[i][0],
              title: data.result.data[i][1]
            })
          }
        } else {
          if (_params.option.graphType == 'areaspline') {
            _params.option.graphType = 'area'
          }
          if (_params.calcul) {
            for (var i in data.result.data) {
              data.result.data[i][1] = _params.calcul(data.result.data[i][1])
            }
          }
          if (_params.option.invertData) {
            for (var i in data.result.data) {
              data.result.data[i][1] = -data.result.data[i][1]
            }
          }
          var series = {
            dataGrouping: dataGrouping,
            type: _params.option.graphType,
            visible: _visible,
            id: _params.cmd_id,
            cursor: 'pointer',
            name: (isset(_params.mobile)) ? data.result.unite : ((isset(_params.option.name)) ? _params.option.name + ' ' + data.result.unite : data.result.history_name + ' ' + data.result.unite),
            data: data.result.data,
            color: _params.option.graphColor,
            fillColor: {
              linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
              },
              stops: [
                [0, Highcharts.Color(_params.option.graphColor).setOpacity(Highcharts.getOptions().jeedom.opacityHigh).get('rgba')],
                [1, Highcharts.Color(_params.option.graphColor).setOpacity(Highcharts.getOptions().jeedom.opacityLow).get('rgba')]
              ],
            },
            stack: _params.option.graphStack,
            derive: _params.option.graphDerive,
            step: _params.option.graphStep,
            stacking: stacking,
            unite: data.result.unite,
            shortName: (isset(_params.option.name)) ? _params.option.name : data.result.history_name,
            tooltip: {
              valueDecimals: _params.round
            },
            point: {
              events: {
                click: function(event) {
                  jeedom.history.modalchangePoint(event, this, data.result)
                }
              }
            },
            marker: {
              symbol: 'circle'
            }
          }

          if (init(_params.option.groupingType) == '' && !comparisonSerie && _params.option.graphType != 'column') {
            //continue value to now, dotted if last value older than one minute (ts in millisecond):
            var dateEnd = new Date(data.result.dateEnd)
            dateEnd.setTime(dateEnd.getTime() - dateEnd.getTimezoneOffset() * 60 * 1000)
            var dateEndTs = dateEnd.getTime()
            var diffms = dateEndTs - data.result.data[data.result.data.length - 1][0]
            if (diffms > 60000) {
              series.zoneAxis = 'x'
              data.result.data.push([dateEndTs, data.result.data[data.result.data.length - 1][1]])
              series.zones = [{
                value: data.result.data[data.result.data.length - 2][0],
                dashStyle: 'Solid',
                className: 'customSolidZone'
              },
              {
                value: data.result.data[data.result.data.length - 1][0],
                dashStyle: 'ShortDash',
                className: 'customDotZone'
              }
              ]
            }
          }
        }

        if (isset(_params.option.graphZindex)) {
          series.zIndex = _params.option.graphZindex
        }

        //set axis position. View allow to set left/right on graph axis, or odd/even:
        var axisOpposite
        if (_params.option.graphScale == undefined) {
          if (!isset(jeedom.history.chart[_params.el])) {
            axisOpposite = true
          } else if (jeedom.history.chart[_params.el].chart.yAxis.length & 1) {
            axisOpposite = true
          } else {
            axisOpposite = false
          }
        } else {
          axisOpposite = !parseInt(_params.option.graphScale)
        }

        //______________new first curve:
        if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
          jeedom.history.chart[_params.el] = {}
          jeedom.history.chart[_params.el].cmd = new Array()
          jeedom.history.chart[_params.el].color = seriesNumber - 1
          jeedom.history.chart[_params.el].nbTimeline = 1
          jeedom.history.chart[_params.el].yAxisScaling = true

          //dateRange buttons config:
          var dateRange = ['all', '30 min', '1 hour', '1 day', '7 days', '1 month', '1 year'].indexOf(_params.dateRange)
          if (dateRange == -1) dateRange = 4

          jeedom.history.chart[_params.el].type = _params.option.graphType
          jeedom.history.chart[_params.el].chart = new Highcharts.StockChart({
            chart: charts,
            _jeeId: _params.el,
            credits: { enabled: false },
            plotOptions: {
              column: {
                stacking: 'normal'
              },
              series: {
                pointWidth: _params.pointWidth || undefined,
                animation: {
                  duration: (getUrlVars('report') == 1) ? 0 : jeedom.history.chartDrawTime
                },
                events: {
                  legendItemClick: function(event) {
                    if (!event.browserEvent.ctrlKey && !event.browserEvent.metaKey && !event.browserEvent.altKey) {
                      this.setVisible(!this.visible, true)
                    } else {
                      if (event.browserEvent.altKey) {
                        this.chart.series.forEach(function(serie) {
                          serie.setVisible(true, true)
                        })
                      } else {
                        this.chart.series.forEach(function(serie) {
                          serie.setVisible(false, true)
                        })
                        this.show()
                        this.visible = true
                      }
                    }
                    if (!jeedom.history.chart[this.chart._jeeId].zoom) jeedom.history.setAxisScales(this.chart._jeeId, { redraw: true })
                    return false
                  }
                }
              }
            },
            exporting: {
              enabled: _params.enableExport || (jeedom.display.version == 'mobile') ? false : true,
              libURL: '3rdparty/highstock/lib/',
              csv: {
                dateFormat: '%Y-%m-%d'
              },
            },
            rangeSelector: {
              allButtonsEnabled: true,
              buttonTheme: { // styles for the buttons
                width: 'auto',
                padding: 4
              },
              buttons: [{
                type: 'all',
                count: 1,
                text: '{{Tous}}',
              }, {
                type: 'minute',
                count: 30,
                text: '{{30 min}}',
              }, {
                type: 'hour',
                count: 1,
                text: '{{Heure}}',
              }, {
                type: 'day',
                count: 1,
                text: '{{Jour}}',
                events: {
                  click: function() {
                    jeedom.history.handleRangeButton(this, _params.el)
                  }
                }
              }, {
                type: 'week',
                count: 1,
                text: '{{Semaine}}',
                events: {
                  click: function() {
                    jeedom.history.handleRangeButton(this, _params.el)
                  }
                }
              }, {
                type: 'month',
                count: 1,
                text: '{{Mois}}',
                events: {
                  click: function() {
                    jeedom.history.handleRangeButton(this, _params.el)
                  }
                }
              }, {
                type: 'year',
                count: 1,
                text: '{{Année}}',
                events: {
                  click: function() {
                    jeedom.history.handleRangeButton(this, _params.el)
                  }
                }
              }],
              selected: dateRange,
              inputEnabled: false,
              x: 0,
              y: -35,
              enabled: _params.showTimeSelector
            },
            legend: legend,
            tooltip: {
              enabled: false, //cause errors when hovering before chart done
              xDateFormat: '%a %Y-%m-%d %H:%M:%S',
              pointFormat: '{point.y} {series.userOptions.unite}<br/>{series.userOptions.shortName}',
              valueDecimals: _params.round,
              crosshairs: [true, true]
            },
            yAxis: [{
              id: _params.cmd_id + '-yAxis',
              showEmpty: false,
              gridLineWidth: 0,
              minPadding: 0.001,
              maxPadding: 0.001,
              labels: {
                format: '{value} ' + data.result.unite,
                style: {
                  color: _params.option.graphColor
                },
                align: 'center'
              },
              margin: 7,
              opposite: axisOpposite,
              visible: _params.showAxis
            }],
            xAxis: [{
              type: 'datetime',
              ordinal: false,
              maxPadding: 0.02,
              minPadding: 0.02,
              margin: 0
            }, {
              //needed for compare mode
              type: 'datetime',
              ordinal: false,
              maxPadding: 0.02,
              minPadding: 0.02,
              margin: 0
            }],
            navigator: {
              enabled: _params.showNavigator,
              margin: 5,
              handles: {
                lineWidth: 0,
                width: 8,
                height: 40
              },
              series: {
                type: _params.option.graphType,
                dataGrouping: dataGrouping,
              },
            },
            scrollbar: {
              barBackgroundColor: 'var(--txt-color)',
              barBorderRadius: 0,
              barBorderWidth: 0,
              buttonBackgroundColor: 'var(--txt-color)',
              buttonBorderWidth: 0,
              buttonBorderRadius: 0,
              trackBackgroundColor: 'none',
              trackBorderWidth: 1,
              trackBorderRadius: 0,
              trackBorderColor: 'var(--txt-color)',
              height: _params.showScrollbar ? 16 : 0,
              enabled: true
            },
            series: [series]
          })
          //Store references and init buttons from UI:
          jeedom.history.chart[_params.el].containerId = jeedom.history.chart[_params.el].chart.container.id
          jeedom.history.chart[_params.el].chart._jeeId = _params.el //else only in useroptions
          jeedom.history.chart[_params.el].doing = 1

          var options = { default: {} }
          if (isset(_params.yAxisScaling) && _params.yAxisScaling !== '') options.default.yAxisScaling = _params.yAxisScaling
          if (isset(_params.yAxisByUnit) && _params.yAxisByUnit !== '') options.default.yAxisByUnit = _params.yAxisByUnit
          if (isset(_params.yAxisScalePercent) && _params.yAxisScalePercent !== '') options.default.yAxisScalePercent = _params.yAxisScalePercent
          if (isset(_params.yAxisVisible) && _params.yAxisVisible !== '') options.default.yAxisVisible = _params.yAxisVisible
          jeedom.history.initChart(_params.el, options)
        } else {
          //set options for comparison serie:
          if (comparisonSerie == 1) {
            series.zIndex = -1
            series.dashStyle = 'shortdot'
            series.xAxis = 1
            series.yAxis = 0
            series.name = '{{Comparaison}}'
            series.comparing = '1'
            series.color = colors[1]
            series.fillColor = {
              linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1
              },
              stops: [
                [0, Highcharts.Color(series.color).setOpacity(Highcharts.getOptions().jeedom.opacityHigh).get('rgba')],
                [1, Highcharts.Color(series.color).setOpacity(Highcharts.getOptions().jeedom.opacityLow).get('rgba')]
              ],
            }

            //navigator only on xAxis[0], disable it:
            jeedom.history.chart[_params.el].chart.update({
              rangeSelector: {
                x: -5000
              },
              navigator: {
                enabled: false
              }
            }, false)
          } else if (_params.option.graphStack != 1) {
            //add new yAxis:
            var yAxis = {
              id: _params.cmd_id + '-yAxis',
              showEmpty: false,
              gridLineWidth: 0,
              minPadding: 0.001,
              maxPadding: 0.001,
              labels: {
                format: '{value} ' + data.result.unite,
                style: {
                  color: _params.option.graphColor
                },
                align: 'center'
              },
              margin: 7,
              opposite: axisOpposite,
              visible: _params.showAxis,
            }

            jeedom.history.chart[_params.el].chart.update({
              rangeSelector: {
                x: 0
              },
              navigator: {
                enabled: _params.showNavigator,
              }
            }, false)
            //add axis to chart:
            series.yAxis = _params.cmd_id + '-yAxis'
            jeedom.history.chart[_params.el].chart.addAxis(yAxis, false, false)
          }
          //add series to graph:
          jeedom.history.chart[_params.el].chart.addSeries(series, false)
          jeedom.history.chart[_params.el].dateStart = _params.dateStart
          jeedom.history.chart[_params.el].dateEnd = _params.dateEnd

        }
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = {
          option: _params.option,
          dateRange: _params.dateRange
        }
      }

      jeedom.history.chart[_params.el].dateStart = data.result.dateStart
      jeedom.history.chart[_params.el].dateEnd = data.result.dateEnd

      //set plotband:
      /*
      var extremes = jeedom.history.chart[_params.el].chart.xAxis[0].getExtremes()
      if(!isset(_params.disablePlotBand) || _params.disablePlotBand == false){
        var plotband = jeedom.history.generatePlotBand(extremes.min, extremes.max)
      }
      for (var i in plotband) {
        jeedom.history.chart[_params.el].chart.xAxis[0].addPlotBand(plotband[i])
      }
      */

      domUtils.hideLoading()
      if (typeof (init(_params.success)) == 'function') {
        _params.success(data.result)
      }
    }
  })
}

/*
Special custom Jeedom HighCharts functions
Adding buttons
Chart legend context menu
Hicharts events calls
yAxis scaling
*/
jeedom.history.initChart = function(_chartId, _options) {
  var thisId = _chartId
  jeedom.history.chart[thisId].comparing = false
  jeedom.history.chart[thisId].zoom = false
  jeedom.history.chart[thisId].mode = jeedom.getPageType()

  jeedom.history.default = {
    yAxisVisible: true,
    yAxisScaling: true,
    yAxisByUnit: true,
    tracking: true,
    yAxisScalePercent: 1.005
  }

  if (jeedom.history.chart[thisId].type == 'pie') return false
  if (getUrlVars('v') != 'm') jeedom.history.initLegendContextMenu(_chartId)

  if (isset(_options.default.yAxisScaling)) jeedom.history.default.yAxisScaling = Boolean(Number(_options.default.yAxisScaling))
  if (isset(_options.default.yAxisByUnit)) jeedom.history.default.yAxisByUnit = Boolean(Number(_options.default.yAxisByUnit))
  if (isset(_options.default.yAxisScalePercent)) jeedom.history.default.yAxisScalePercent = Number(_options.default.yAxisScalePercent)
  if (isset(_options.default.yAxisVisible)) jeedom.history.default.yAxisVisible = Boolean(Number(_options.default.yAxisVisible))

  /*
  HichCharts button states (undocumented):
    0: normal
    1: hover
    2: selected
    3: disabled
  */

  var xStart = (jeedom.history.chart[thisId].rangeSelector === undefined ? -15 : 0)

  //Tracking button:
  jeedom.history.chart[thisId].btTracking = jeedom.history.chart[thisId].chart.renderer.button('<i class="fas fa-hand-pointer"></i>', undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, true)
    .attr({
      id: 'hc_bt_tracking',
      height: 10,
      width: 10,
      align: 'right',
      title: "{{Opacité des courbes au suivi de la souris}}"
    })
    .on('click', function() {
      jeedom.history.toggleTracking(thisId)
    })
    .add()
    .align({
      align: 'right',
      x: xStart - 50,
      y: 5
    }, false, null)
  if (jeedom.history.default.tracking) {
    jeedom.history.chart[thisId].tracking = true
    jeedom.history.chart[thisId].btTracking.setState(2)
  } else {
    jeedom.history.chart[thisId].tracking = false
    jeedom.history.chart[thisId].btTracking.setState(0)
  }

  //yAxis scaling by unit:
  jeedom.history.chart[thisId].btToggleyaxisbyunit = jeedom.history.chart[thisId].chart.renderer.button('<i class="icon divers-viral"></i>', undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, true)
    .attr({
      id: 'hc_bt_YaxisByUnit',
      height: 10,
      width: 10,
      align: 'right',
      title: "{{Groupement des axes Y par unité}}"
    })
    .on('click', function() {
      if (jeedom.history.chart[thisId].zoom) return false
      jeedom.history.chart[thisId].yAxisByUnit = !jeedom.history.chart[thisId].yAxisByUnit
      if (!jeedom.history.chart[thisId].yAxisByUnit) {
        jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(0)
      } else {
        jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(2)
      }
      jeedom.history.setAxisScales(thisId, { redraw: true })
    })
    .add()
    .align({
      align: 'right',
      x: xStart - 80,
      y: 5
    }, false, null)
  if (jeedom.history.default.yAxisByUnit) {
    jeedom.history.chart[thisId].yAxisByUnit = true
    jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(2)
  } else {
    jeedom.history.chart[thisId].yAxisByUnit = false
    jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(0)
  }

  //toggle yAxis scaling:
  jeedom.history.chart[thisId].btToggleyaxisScaling = jeedom.history.chart[thisId].chart.renderer.button('<i class="fas fa-compress-alt"></i>', undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, true)
    .attr({
      id: 'hc_bt_toggleYaxisScale',
      height: 10,
      width: 10,
      align: 'right',
      title: "{{Echelle indépendante des axes Y}}"
    })
    .on('click', function() {
      jeedom.history.toggleyAxisScaling(thisId)
    })
    .add()
    .align({
      align: 'right',
      x: xStart - 110,
      y: 5
    }, false, null)
  if (jeedom.history.default.yAxisScaling) {
    jeedom.history.chart[thisId].yAxisScaling = true
    jeedom.history.chart[thisId].btToggleyaxisScaling.setState(2)
  } else {
    jeedom.history.chart[thisId].yAxisScaling = false
    jeedom.history.chart[thisId].btToggleyaxisScaling.setState(0)
  }


  //toggle yAxis visible button:
  jeedom.history.chart[thisId].btToggleyaxisVisible = jeedom.history.chart[thisId].chart.renderer.button(' <i class="fas fa-ruler-vertical"></i>', undefined, undefined, undefined, undefined, undefined, undefined, undefined, undefined, true)
    .attr({
      id: 'hc_bt_toggleYaxis',
      height: 10,
      width: 10,
      align: 'right',
      title: "{{Affichage des axes Y}}"
    })
    .on('click', function() {
      jeedom.history.toggleYaxisVisible(thisId)
    })
    .add()
    .align({
      align: 'right',
      x: xStart - 140,
      y: 5
    }, false, null)
  if (jeedom.history.default.yAxisVisible) {
    jeedom.history.chart[thisId].yAxisVisible = true
    jeedom.history.chart[thisId].btToggleyaxisVisible.setState(2)
  } else {
    jeedom.history.chart[thisId].yAxisVisible = false
    jeedom.history.chart[thisId].btToggleyaxisVisible.setState(0)
  }

  jeedom.history.chart[thisId].yAxisScalePercent = jeedom.history.default.yAxisScalePercent

  //store all that in chart:
  jeedom.history.chart[thisId].chart._jeeButtons = [
    jeedom.history.chart[thisId].btTracking,
    jeedom.history.chart[thisId].btToggleyaxisbyunit,
    jeedom.history.chart[thisId].btToggleyaxisScaling,
    jeedom.history.chart[thisId].btToggleyaxisVisible
  ]
}

/*
register legend context menu
@jeedom.history.initChart
*/
jeedom.history.initLegendContextMenu = function(_chartId) {
  new jeeCtxMenu({
    selector: "div.chartContainer .highcharts-legend-item",
    position: function(opt, x, y) {
      //legend bottom graph, open menu upside if possible:
      var menuHeight = opt.ctxMenu.clientHeight
      var menuWidth = opt.ctxMenu.clientWidth
      var winHeight = window.innerHeight
      var winWidth = window.innerWidth

      var newTop = y - menuHeight
      var newLeft = x

      if ((y - menuHeight - 20) < 0) {
        newTop = y
      }
      if ((x + menuWidth + 20) > winWidth) {
        newLeft = x - (menuWidth + 20)
      }
      Object.assign(opt.ctxMenu.style, {
        top: newTop + 'px',
        left: newLeft + 'px'
      })
    },
    build: function(trigger) {
      var __ctxel__ = trigger.parentNode.closest('div.chartContainer').getAttribute('id')
      if (isset(jeeFrontEnd.history) && isset(jeeFrontEnd.history.__ctxel__) && jeedom.history.chart[jeeFrontEnd.history.__ctxel__].comparing) return false
      var chart = jeedom.history.chart[__ctxel__].chart
      if (!chart) return false
      if (jeedom.history.chart[chart._jeeId].type == 'pie') return false
      if (jeedom.history.chart[chart._jeeId].comparing) return false

      var serieId = trigger.getAttribute('class').split('highcharts-series-')[1].split(' ')[0]
      var cmdId = chart.series[serieId].userOptions.id
      var axis = chart.get(cmdId)
      var contextmenuitems = {}
      contextmenuitems['cmdid'] = { 'name': 'id: ' + cmdId, 'id': 'cmdid', 'disabled': true }
      contextmenuitems['isolate'] = { 'name': '{{Isoler}} (Ctrl Clic)', 'id': 'isolate', 'icon': 'fas fa-chart-line' }
      contextmenuitems['showall'] = { 'name': '{{Afficher tout}} (Alt Clic)', 'id': 'showall', 'icon': 'fas fa-poll-h' }
      if (axis.visible) {
        contextmenuitems['hideaxis'] = { 'name': '{{Masquer axe}}', 'id': 'hideaxis', 'icon': 'far fa-eye-slash' }
      } else {
        contextmenuitems['showaxis'] = { 'name': '{{Afficher axe}}', 'id': 'showaxis', 'icon': 'far fa-eye' }
      }

      var idx = 0
      Highcharts.getOptions().colors.forEach(function(color) {
        contextmenuitems['color_' + idx] = {
          'name': '<i class="fas fa-square" style="color:' + Highcharts.getOptions().colors[idx] + '!important;"></i>',
          'id': 'color_' + idx,
          'isHtmlName': true,
          'className': 'inlineItem'
        }
        idx += 1
      })

      return {
        callback: function(key, options) {
          if (key == 'showall') {
            chart.series.forEach(function(serie) {
              serie.show()
            })
            return
          }
          if (key == 'isolate') {
            chart.series.forEach(function(serie) {
              serie.hide()
            })
            chart.series[serieId].show()
            return
          }
          if (key == 'hideaxis') {
            axis.update({
              visible: false
            })

            //synch "yAxis Visible"
            jeedom.history.chart[chart._jeeId].btToggleyaxisVisible.setState(0)
            jeedom.history.chart[chart._jeeId].yAxisVisible = false

            return
          }
          if (key == 'showaxis') {
            axis.update({
              visible: true
            })

            //synch "yAxis Visible"
            var allVisible = true
            chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach(function(yAxis) {
              if (!yAxis.visible) allVisible = false
            })
            if (allVisible) {
              jeedom.history.chart[chart._jeeId].btToggleyaxisVisible.setState(2)
            } else {
              jeedom.history.chart[chart._jeeId].btToggleyaxisVisible.setState(0)
            }
            jeedom.history.chart[chart._jeeId].yAxisVisible = allVisible

            return
          }
          if (key.startsWith('color_')) {
            var idx = key.split('_')[1]
            var opacityHigh = 0.85
            var opacityLow = 0.1
            var newC = Highcharts.getOptions().colors[idx]
            chart.series[serieId].update({
              color: newC,
              fillColor: {
                stops: [
                  [0, Highcharts.Color(newC).setOpacity(opacityHigh).get('rgba')],
                  [1, Highcharts.Color(newC).setOpacity(opacityLow).get('rgba')]
                ]
              }
            })

            axis.update({
              labels: {
                style: {
                  color: newC
                }
              }
            })
            return
          }
        },
        items: contextmenuitems
      }
    }
  })
}

/*
HighCharts events callbacks on load / addSeries / selection
Decrement .doing and call chartDone when .doing == 0
*/
jeedom.history.chartCallback = function(_chartId, _options) {
  if (_chartId === undefined || !isset(_options)) return false
  if (jeedom.history.chart[_chartId].type == 'pie') return false

  //Reset zoom:
  if (isset(_options.type) && _options.type == 'selection') {
    jeedom.history.setAxisScales(_chartId, _options)
    return true
  }

  //Series added, decrement:
  if (isset(_options.type) && _options.type == 'addSeries' || _options.type == 'load') {
    jeedom.history.chart[_chartId].doing -= 1
  }

  //Is done ?
  if (jeedom.history.chart[_chartId].doing == 0) {
    jeedom.history.chartDone(_chartId)
    return true
  }
}

/*
Once chart is done
*/
jeedom.history.chartDone = function(_chartId) {
  if (_chartId === undefined) return false
  if (jeedom.history.chart[_chartId].doing > 0) return false
  var chart = jeedom.history.chart[_chartId].chart
  jeedom.history.chart[_chartId].doing = -1
  try {
    setTimeout(function() {
      if (isset(jeedom.history.chart[_chartId]) && !jeedom.history.chart[_chartId].comparing) {
        jeedom.history.chart[_chartId].chart.setSize(undefined, undefined, false)
        jeedom.history.setAxisScales(_chartId)
        jeedom.history.chart[_chartId].chart.update({
          chart: {
            animation: jeedom.history.chartDrawTime,
          },
          tooltip: {
            enabled: true,
          },
        }) //last redraw!

        if (isset(jeeFrontEnd[jeedom.history.chart[_chartId].mode]) && typeof jeeFrontEnd[jeedom.history.chart[_chartId].mode].highcharts_done_callback === "function") {
          jeeFrontEnd[jeedom.history.chart[_chartId].mode].highcharts_done_callback(_chartId)
        }
      }
    }, (getUrlVars('report') == 1) ? 0 : jeedom.history.chartDrawTime)
  } catch (error) {
    console.error(error)
  }
}

/*
Set each existing yAxis scale according to chart yAxisScaling and yAxisByUnit
*/
jeedom.history.setAxisScales = function(_chartId, _options) {
  if (_chartId === undefined) return false
  if (jeedom.history.chart[_chartId].type == 'pie') return false
  var chart = jeedom.history.chart[_chartId].chart

  //All done with render false, redraw at end if in _options

  /*
  Coming from HighChart event
  */
  if (isset(_options)) {
    /*
    xAxis[0] min/max : zoomed dateRange in navigator
    xAxis[0] dataMin/dataMan : full dateRange
    */
    if (isset(_options.extremeXmin) && isset(_options.extremeXmax)) {
      chart.xAxis[0].setExtremes(_options.extremeXmin, _options.extremeXmax, true) // redraw now for right min/max later
    }

    if (isset(_options.resetDateRange) && _options.resetDateRange == true) {
      chart.xAxis[0].setExtremes(null, null, false)
      chart.xAxis[1].setExtremes(null, null, false) //comparing axis
    }
  }

  var units = {}

  //No scale | unit : All axis with same unit will get same min/max
  if (!jeedom.history.chart[_chartId].yAxisScaling && jeedom.history.chart[_chartId].yAxisByUnit) {
    var unit, mathMin, mathMax
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      if (axis.series.length == 0) return
      unit = axis.series[0].userOptions.unite
      if (unit == '') unit = axis.userOptions.id
      if (unit != '' && !(unit in units)) {
        units[unit] = {
          unit: unit,
          min: 1000000,
          max: -1000000,
          axis: []
        }
      }
      units[unit].axis.push(axis.userOptions.id)

      if (axis.series[0].data.length > 0) {
        var mathMin = Math.min.apply(Math, axis.series[0].data.filter(x => x.y !== null).map(function(key) { return key.y }))
        var mathMax = Math.max.apply(Math, axis.series[0].data.filter(x => x.y !== null).map(function(key) { return key.y }))
      } else if (axis.series[0].points) {
        var mathMin = Math.min.apply(Math, axis.series[0].points.filter(x => x.y !== null).map(function(key) { return key.y }))
        var mathMax = Math.max.apply(Math, axis.series[0].points.filter(x => x.y !== null).map(function(key) { return key.y }))
      }
      if (mathMin < units[unit].min) units[unit].min = mathMin
      if (mathMax > units[unit].max) units[unit].max = mathMax

      if (jeedom.history.chart[_chartId].comparing && axis.series[1]) {
        if (axis.series[0].data.length > 0) {
          cmin = Math.min.apply(Math, axis.series[1].data.filter(x => x !== null).map(function(key) { return key.y }))
          cmax = Math.max.apply(Math, axis.series[1].data.filter(x => x !== null).map(function(key) { return key.y }))
        } else if (axis.series[0].points) {
          cmin = Math.min.apply(Math, axis.series[1].points.filter(x => x !== null).map(function(key) { return key.y }))
          cmax = Math.max.apply(Math, axis.series[1].points.filter(x => x !== null).map(function(key) { return key.y }))
        }
        if (cmin < units[unit].min) units[unit].min = cmin
        if (cmax > units[unit].max) units[unit].max = cmax
      }
    })
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      unit = axis.series[0].userOptions.unite
      if (unit == '') unit = axis.userOptions.id
      if (axis.stacking.stacksTouched == 0) {
        axis.update({
          softMin: null,
          softMax: null,
          min: units[unit].min > 0 ? 0 : units[unit].min,
          max: units[unit].max < 0 ? 0 : units[unit].max,
          tickPositions: null
        }, false)
        axis.setExtremes(null, units[unit].max * jeedom.history.chart[_chartId].yAxisScalePercent, false)
      } else {
        axis.setExtremes(null, null, false, false)
      }
    })
  }

  //No scale | No unit : (HighChart default) All axis will get same global min/max
  if (!jeedom.history.chart[_chartId].yAxisScaling && !jeedom.history.chart[_chartId].yAxisByUnit) {
    var softMax = 0
    var mathMax
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      mathMax = Math.max.apply(Math, axis.series[0].data.filter(x => x !== null).map(function(key) { return key.y }))
      if (mathMax > softMax) softMax = mathMax
    })
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      if (axis.stacking.stacksTouched == 0) {
        axis.update({
          softMin: 0,
          softMax: softMax / jeedom.history.chart[_chartId].yAxisScalePercent,
          min: null,
          max: null,
          tickPositions: null
        }, false)
        axis.setExtremes(null, null, false)
      } else {
        axis.setExtremes(null, null, false, false)
      }
    })
  }

  //scale | unit : (Jeedom default)  All axis with same unit will get same min/max
  if (jeedom.history.chart[_chartId].yAxisScaling && jeedom.history.chart[_chartId].yAxisByUnit) {
    var unit, mathMin, mathMax, cmin, cmax
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      if (axis.series.length == 0) return
      unit = axis.series[0].userOptions.unite
      if (unit == '') unit = axis.userOptions.id
      if (unit != '' && !(unit in units)) {
        units[unit] = {
          unit: unit,
          min: 1000000,
          max: -1000000,
          axis: []
        }
      }
      units[unit].axis.push(axis.userOptions.id)

      if (axis.series[0].data.length > 0) {
        var mathMin = Math.min.apply(Math, axis.series[0].data.filter(x => x !== null).map(function(key) { return key.options.y }))
        var mathMax = Math.max.apply(Math, axis.series[0].data.filter(x => x !== null).map(function(key) { return key.options.y }))
      } else if (axis.series[0].points) {
        var mathMin = Math.min.apply(Math, axis.series[0].points.filter(x => x !== null).map(function(key) { return key.y }))
        var mathMax = Math.max.apply(Math, axis.series[0].points.filter(x => x !== null).map(function(key) { return key.y }))
      }

      if (mathMin < units[unit].min) units[unit].min = mathMin
      if (mathMax > units[unit].max) units[unit].max = mathMax

      if (jeedom.history.chart[_chartId].comparing && axis.series[1]) {
        if (axis.series[0].data.length > 0) {
          cmin = Math.min.apply(Math, axis.series[1].data.filter(x => x !== null).map(function(key) { return key.y }))
          cmax = Math.max.apply(Math, axis.series[1].data.filter(x => x !== null).map(function(key) { return key.y }))
        } else if (axis.series[0].points) {
          cmin = Math.min.apply(Math, axis.series[1].points.filter(x => x !== null).map(function(key) { return key.y }))
          cmax = Math.max.apply(Math, axis.series[1].points.filter(x => x !== null).map(function(key) { return key.y }))
        }
        if (cmin < units[unit].min) units[unit].min = cmin
        if (cmax > units[unit].max) units[unit].max = cmax
      }
    })
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      if (axis.series.length == 0) return
      unit = axis.series[0].userOptions.unite
      if (unit == '') unit = axis.userOptions.id
      if (axis.stacking.stacksTouched == 0) {
        axis.update({
          softMin: null,
          softMax: null,
          min: units[unit].min > 0 ? 0 : units[unit].min,
          max: units[unit].max < 0 ? 0 : units[unit].max,
          tickPositions: null
        }, false)
        axis.setExtremes(units[unit].min / jeedom.history.chart[_chartId].yAxisScalePercent, units[unit].max * jeedom.history.chart[_chartId].yAxisScalePercent, false)
      } else {
        axis.setExtremes(null, null, false, false)
      }
    })
  }

  //scale | No unit : Each axis will get its own min/max
  if (jeedom.history.chart[_chartId].yAxisScaling && !jeedom.history.chart[_chartId].yAxisByUnit) {
    var min, max
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      if (axis.series[0].data.length > 0) {
        var min = Math.min.apply(Math, axis.series[0].data.filter(x => x !== null).map(function(key) { return key.options.y }))
        var max = Math.max.apply(Math, axis.series[0].data.filter(x => x !== null).map(function(key) { return key.options.y }))
      } else if (axis.series[0].points) {
        var min = Math.min.apply(Math, axis.series[0].points.filter(x => x !== null).map(function(key) { return key.y }))
        var max = Math.max.apply(Math, axis.series[0].points.filter(x => x !== null).map(function(key) { return key.y }))
      }

      if (jeedom.history.chart[_chartId].comparing && axis.series[1]) {
        if (axis.series[0].data.length > 0) {
          cmin = Math.min.apply(Math, axis.series[1].data.filter(x => x !== null).map(function(key) { return key.y }))
          cmax = Math.max.apply(Math, axis.series[1].data.filter(x => x !== null).map(function(key) { return key.y }))
        } else if (axis.series[0].points) {
          cmin = Math.min.apply(Math, axis.series[1].points.filter(x => x !== null).map(function(key) { return key.y }))
          cmax = Math.max.apply(Math, axis.series[1].points.filter(x => x !== null).map(function(key) { return key.y }))
        }
        if (cmin < min) min = cmin
        if (cmax > max) max = cmax
      }

      if (axis.stacking.stacksTouched == 0) {
        axis.update({
          softMin: null,
          softMax: null,
          min: min / 1.005,
          max: max * 1.005,
          tickPositions: null
        }, false)
        axis.setExtremes(min / jeedom.history.chart[_chartId].yAxisScalePercent, max * jeedom.history.chart[_chartId].yAxisScalePercent, false)
      } else {
        axis.setExtremes(null, null, false, false)
      }
    })
  }

  /*
  Set axis visible / color.
  No unit: all visible with series color
  unit: if single, visible with series color, else only first visible, uncolored
  @design : do nothing, user choice!
  */
  if (jeedom.history.chart[_chartId].mode != 'view' && jeedom.history.chart[_chartId].mode != 'plan') {
    if (Object.keys(units).length == 0) { //no unit
      chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
        if (axis.series.length == 0) return
        var seriesColor = axis.series[0].color
        axis.update({
          visible: true,
          labels: {
            style: {
              color: seriesColor
            },
          }
        }, false)
      })
    } else { //unit
      var overUnits = Object.keys(units).filter(key => units[key].axis.length > 1)
      overUnits.forEach((unit, index) => {
        units[unit].axis.forEach((id, idx) => {
          var axis = chart.get(id)
          if (idx == 0) {
            axis.update({
              visible: true,
              labels: {
                style: {
                  color: 'var(--link-color)'
                },
              }
            }, false)
          } else {
            axis.update({
              visible: false,
            }, false)
          }
        })
      })
    }
  }

  /*
  @view
  no unit: all axis colored, unit: all axis uncolored
  */
  if (jeedom.history.chart[_chartId].mode == 'view') {
    if (Object.keys(units).length == 0) { //no unit
      chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
        var seriesColor = axis.series[0].color
        axis.update({
          labels: {
            style: {
              color: seriesColor
            },
          }
        }, false, false)
      })
    } else {
      chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
        axis.update({
          labels: {
            style: {
              color: 'var(--link-color)'
            },
          }
        }, false, false)
      })
    }
  }

  if (isset(_options)) {
    if (isset(_options.redraw) && _options.redraw == true) {
      chart.redraw()
    }
  }
}

/*
Toggle all yAxis scaling
*/
jeedom.history.toggleyAxisScaling = function(_chartId) {
  if (jeedom.history.chart[_chartId].zoom) return
  var chart = jeedom.history.chart[_chartId].chart

  jeedom.history.chart[_chartId].yAxisScaling = !jeedom.history.chart[_chartId].yAxisScaling
  if (!jeedom.history.chart[_chartId].yAxisScaling) {
    jeedom.history.chart[_chartId].btToggleyaxisScaling.setState(0)
  } else {
    jeedom.history.chart[_chartId].btToggleyaxisScaling.setState(2)
  }
  jeedom.history.setAxisScales(_chartId, { redraw: true })
}

/*
Set inactive opacity for tracking
*/
jeedom.history.toggleTracking = function(_chartId) {
  var chart = jeedom.history.chart[_chartId].chart
  if (jeedom.history.chart[_chartId].tracking) {
    jeedom.history.chart[_chartId].btTracking.setState(0)
    var opacity = 1
  } else {
    var opacity = Highcharts.getOptions().jeedom.opacityLow
    jeedom.history.chart[_chartId].btTracking.setState(2)
  }
  jeedom.history.chart[_chartId].tracking = !jeedom.history.chart[_chartId].tracking

  chart.update({
    plotOptions: {
      series: {
        states: {
          inactive: {
            opacity: opacity
          }
        }
      }
    }
  })
}

/*
Toggle all yAxis visibility
*/
jeedom.history.toggleYaxisVisible = function(_chartId) {
  var chart = jeedom.history.chart[_chartId].chart
  if (jeedom.history.chart[_chartId].yAxisVisible) {
    jeedom.history.chart[_chartId].btToggleyaxisVisible.setState(0)
  } else {
    jeedom.history.chart[_chartId].btToggleyaxisVisible.setState(2)
  }
  jeedom.history.chart[_chartId].yAxisVisible = !jeedom.history.chart[_chartId].yAxisVisible

  jeedom.history.chart[_chartId].chart.yAxis.forEach((axis, index) => {
    axis.update({
      visible: jeedom.history.chart[_chartId].yAxisVisible
    })
  })
}

/*
Remove all series/yAxis from chart:
*/
jeedom.history.emptyChart = function(_chartId, _yAxis) {
  if (jeedom.history.chart[_chartId] === undefined) return false
  if (!isset(_yAxis)) _yAxis = false
  jeedom.history.chart[_chartId].chart.series.forEach(function(series) {
    if (series.options && !isNaN(series.options.id)) {
      if (!series.name.includes('Navigator ')) {
        var cmd_id = series.options.id
        series.remove(false)
        if (_yAxis) {
          try {
            jeedom.history.chart[_chartId].chart.get(cmd_id + '-yAxis').remove(false)
          } catch (e) { }
        }
      }
    }
  })
  jeedom.history.chart[_chartId].chart.redraw()
}

jeedom.history.setRangeSelectorButtons = function(_chartId) {
  if (_chartId === undefined || jeedom.history.chart[_chartId].chart === undefined || jeedom.history.chart[_chartId].chart.rangeSelector === undefined) return false
  var chart = jeedom.history.chart[_chartId].chart
  var min = chart.xAxis[0].dataMin
  var max = chart.xAxis[0].dataMax
  chart.rangeSelector.buttonOptions.forEach(function(option, index) {
    if (max - (option._range + min) < 0) {
      chart.rangeSelector.buttons[index].addClass('warning')
    } else {
      chart.rangeSelector.buttons[index].removeClass('warning')
    }
  })
}

/*
Handle rangeSelector buttons for dynamic reloading:
*/
jeedom.history.handleRangeButton = function(_button, _chartId) {
  var mStart = moment(jeedom.history.chart[_chartId].dateStart, 'YYYY-MM-DD')
  var mEnd = moment(jeedom.history.chart[_chartId].dateEnd, 'YYYY-MM-DD hh:mm:ss')
  var mRequestStart = mEnd.clone().subtract(_button.count, _button.type)

  if (mRequestStart.isBefore(mStart)) {
    var cmds = jeedom.history.chart[_chartId].cmd

    //delete all series and their yAxis, then reload them with larger date range and same parameters!
    var chart = jeedom.history.chart[_chartId].chart
    var dateEnd = jeedom.history.chart[_chartId].dateEnd
    var done = 0
    var cmd_id, cmd_option
    jeedom.history.chart[_chartId].chart.series.forEach(function(series) {
      if (series.options && !isNaN(series.options.id)) {
        cmd_id = series.options.id
        cmd_option = cmds[cmd_id].option
        delete cmd_option.graphStack

        if (!series.name.includes('Navigator ')) {
          series.remove(false)
          chart.get(cmd_id + '-yAxis').remove(false)
          done += 1
        }
        jeedom.history.drawChart({
          cmd_id: cmd_id,
          el: _chartId,
          dateRange: 'all',
          option: cmd_option,
          dateStart: mRequestStart.format('YYYY-MM-DD'),
          dateEnd: dateEnd,
          newGraph: false,
          global: false,
          success: function(data) {
            done -= 1
            if (done == 0) {
              chart.xAxis[0].setExtremes(mRequestStart.valueOf(), mEnd.valueOf())
              jeedom.history.setRangeSelectorButtons(_chartId)
            }
          }
        })
      }
    })

    var in_startDate = document.getElementById("in_startDate")
    if (in_startDate) in_startDate.value = mRequestStart.format('YYYY-MM-DD')

    return true
  } else {
    return true
  }
}
