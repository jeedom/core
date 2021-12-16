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

jeedom.history = function() {};
jeedom.history.chart = [];

jeedom.history.get = function(_params) {
  var paramsRequired = ['cmd_id', 'dateStart', 'dateEnd'];
  var paramsSpecifics = {
    global: _params.global || true,
    pre_success: function(data) {
      if (isset(jeedom.cmd.cache.byId[data.result.id])) {
        delete jeedom.cmd.cache.byId[data.result.id];
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'getHistory',
    id: _params.cmd_id,
    dateStart: _params.dateStart || '',
    dateEnd: _params.dateEnd || '',
  };
  $.ajax(paramsAJAX);
}

jeedom.history.getInitDates = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'getInitDates'
  };
  $.ajax(paramsAJAX);
}

jeedom.history.copyHistoryToCmd = function(_params) {
  var paramsRequired = ['source_id', 'target_id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'copyHistoryToCmd',
    source_id: _params.source_id,
    target_id: _params.target_id
  };
  $.ajax(paramsAJAX);
}

jeedom.history.generatePlotBand = function(_startTime, _endTime) {
  var plotBands = [];
  var day = 86400000
  if ((_endTime - _startTime) > (9 * day)) {
    return plotBands;
  }
  _startTime = Math.floor(_startTime / day) * day;
  var plotBand
  while (_startTime < _endTime) {
    plotBand = {};
    plotBand.from = _startTime;
    plotBand.to = _startTime + day;
    if (plotBand.to > _endTime) {
      plotBand.to = _endTime;
    }
    plotBands.push(plotBand);
    _startTime += 2 * day;
  }
  return plotBands;
}

jeedom.history.changePoint = function(_params) {
  var paramsRequired = ['cmd_id', 'datetime', 'value', 'oldValue'];
  var paramsSpecifics = {
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      });
    },
    success: function(result) {
      $.fn.showAlert({
        message: '{{La valeur a été éditée avec succès}}',
        level: 'success'
      });
      var serie = null;
      for (var i in jeedom.history.chart) {
        serie = jeedom.history.chart[i].chart.get(_params.cmd_id);
        if (serie != null && serie != undefined) {
          serie.remove();
          serie = null;
          jeedom.history.drawChart({
            cmd_id: _params.cmd_id,
            el: i,
            dateRange: jeedom.history.chart[i].cmd[_params.cmd_id].dateRange,
            dateStart: _params.dateStart,
            dateEnd: _params.dateEnd,
            option: jeedom.history.chart[i].cmd[_params.cmd_id].option
          });
        }
      }
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'changeHistoryPoint',
    cmd_id: _params.cmd_id,
    datetime: _params.datetime,
    value: _params.value,
    oldValue: _params.oldValue
  };
  $.ajax(paramsAJAX);
}

jeedom.history.modalchangePoint = function(event, _this, _params) {
  var deviceInfo = getDeviceType()
  if ($.mobile || deviceInfo.type == 'tablet' || deviceInfo.type == 'phone') return
  if ($('#md_modal2').is(':visible')) return
  if ($('#md_modal1').is(':visible')) return
  if (typeof isComparing !== 'undefined' && isComparing == true) return

  var id = _this.series.userOptions.id
  var datetime = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', _this.x)
  var value = _this.y
  bootbox.prompt("{{Edition de la série :}} <b>" + _this.series.name + "</b> {{et du point de}} <b>" + datetime + "</b> ({{valeur :}} <b>" + value + "</b>) ? {{Ne rien mettre pour supprimer la valeur}}", function(result) {
    if (result !== null) {
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
  $.showLoading();
  if ($.type(_params.dateRange) == 'object') {
    _params.dateRange = json_encode(_params.dateRange);
  }
  _params.option = init(_params.option, {
    derive: ''
  });
  var _visible = (isset(_params.visible)) ? _params.visible : true

  //get command history
  $.ajax({
    type: "POST",
    url: "core/ajax/cmd.ajax.php",
    data: {
      action: "getHistory",
      id: _params.cmd_id,
      dateRange: ($.type(_params.dateRange) == 'object') ? json_encode(_params.dateRange) || '' : _params.dateRange || '',
      dateStart: _params.dateStart || '',
      dateEnd: _params.dateEnd || '',
      derive: _params.option.derive || '',
      allowZero: init(_params.option.allowZero, 0),
      groupingType: _params.option.groupingType || '',
      lastPointToEnd: _params.option.lastPointToEnd || 0,
    },
    dataType: 'json',
    global: _params.global || true,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      //check history exist:
      if (data.state != 'ok') {
        $.fn.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }

      //check is there is some data and manage alerts:
      if (data.result.data.length < 1) {
        if (_params.option.displayAlert == false) {
          return;
        }
        if (!_params.noError) {
          var message = '{{Il n\'existe encore aucun historique pour cette commande :}} ' + data.result.history_name;
          if (init(data.result.dateStart) != '') {
            message += (init(data.result.dateEnd) != '') ? ' {{du}} ' + data.result.dateStart + ' {{au}} ' + data.result.dateEnd : ' {{à partir de}} ' + data.result.dateStart;
          } else {
            message += (init(data.result.dateEnd) != '') ? ' {{jusqu\'au}} ' + data.result.dateEnd : '';
          }
          $('#div_alertHistory').showAlert({
            message: message,
            level: 'warning'
          });
          if (typeof(init(_params.success)) == 'function') {
            _params.success({
              'error': message
            })
          }
        }
        return;
      }

      //If is comparing, add midnight start and end points to both series for range adjusting:
      if (typeof isComparing !== 'undefined' && isComparing == true) {
        var tsFirst = data.result.data[0][0]
        var tsStart = Date.parse(data.result.dateStart)
        if (tsStart < tsFirst) {
          data.result.data.unshift([tsStart, data.result.data[0][1]])
        }
        var tsLast = data.result.data.slice(-1)[0][0]
        var tsEnd = Date.parse(data.result.dateEnd)
        if (tsEnd > tsLast) {
          data.result.data.push([tsEnd, data.result.data.slice(-1)[0][1]])
        }
      }
      //if this serie a comparison one:
      var comparisonSerie = false
      if (isset(_params.compare) && _params.compare == 1) comparisonSerie = true

      //set/check some params:
      if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].cmd[_params.cmd_id])) {
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = null;
      }
      _params.option.graphDerive = (data.result.derive == "1") ? true : false;

      //series colors, options defined in core/js/jeedom.class.js jeedom.init():
      var colors = Highcharts.getOptions().colors
      var colorsNbr = colors.length
      var numSeries = 0
      if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].chart.series)) {
        jeedom.history.chart[_params.el].chart.series.forEach((serie, index) => {
          if (!serie.userOptions.group) numSeries +=1
        })
      }
      var seriesNumber = numSeries + 1
      if (seriesNumber > colorsNbr || seriesNumber == 0) {
        seriesNumber = 1
      }
      if (!isset(_params.option.graphColor) || _params.option.graphColor === undefined) {
        _params.option.graphColor = colors[seriesNumber - 1];
      }

      //step/grouping according to cmd subType:
      _params.option.graphStep = (_params.option.graphStep == "1") ? true : false;
      if (isset(data.result.cmd)) {
        if (init(_params.option.graphStep) == '') {
          _params.option.graphStep = (data.result.cmd.subType == 'binary') ? true : false;
          if (isset(data.result.cmd.display) && init(data.result.cmd.display.graphStep) != '') {
            _params.option.graphStep = (data.result.cmd.display.graphStep == "0") ? false : true;
          }
        }
        if (init(_params.option.graphType) == '') {
          _params.option.graphType = (isset(data.result.cmd.display) && init(data.result.cmd.display.graphType) != '') ? data.result.cmd.display.graphType : 'area';
        }
        if (init(_params.option.groupingType) == '' && isset(data.result.cmd.display) && init(data.result.cmd.display.groupingType) != '') {
          var split = data.result.cmd.display.groupingType.split('::');
          _params.option.groupingType = {
            function: split[0],
            time: split[1]
          };
        }
      }

      var stacking = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? null : 'value';
      _params.option.graphStack = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? Math.floor(Math.random() * 10000 + 2) : 1;
      _params.showLegend = (init(_params.showLegend, true) && init(_params.showLegend, true) != "0") ? true : false;
      _params.showTimeSelector = (init(_params.showTimeSelector, true) && init(_params.showTimeSelector, true) != "0") ? true : false;
      _params.showScrollbar = (init(_params.showScrollbar, false) && init(_params.showScrollbar, false) != "0") ? true : false;
      _params.showNavigator = (init(_params.showNavigator, true) && init(_params.showNavigator, true) != "0") ? true : false;
      _params.showAxis = (init(_params.option.graphScaleVisible, true) && init(_params.option.graphScaleVisible, true) != "0") ? true : false;

      //define legend and reset graph:
      var legend = {
        borderColor: 'transparent',
        borderWidth: 0,
        symbolHeight: 8,
        symbolWidth: 16,
        symbolRadius: 0,
        align: 'left',
        shadow: false
      };
      legend.enabled = init(_params.showLegend, true);
      if (isset(_params.newGraph) && _params.newGraph == true) {
        delete jeedom.history.chart[_params.el]
      }

      //jeedom default chart params:
      var charts = {
        zoomType: 'xy',
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
            //default min/max set earlier in series
            var thisId = event.target.userOptions._jeeId
            clearTimeout(jeedomUIHistory.done)
            jeedomUIHistory.done = setTimeout(jeedomUIHistory.chartDone.bind(null, thisId), 250)
          },
          redraw: function(event) {
            if (this._jeeButtons) {
              var xTheshold = (this.chartWidth - this.rangeSelector.buttons[6].translateX) + this.rangeSelector.buttons[6].width
              if (xTheshold < 380) {
                this._jeeButtons.forEach(function(button, i) {
                  button.hide()
                })
              } else {
                this._jeeButtons.forEach(function(button, i) {
                  button.show()
                })
              }
            }
          },
          render: function(event) {
            //shift dotted zones clipPaths to ensure no overlapping step mode:
            var solidClip = null;
            $('.highcharts-zone-graph-0.customSolidZone').each(function() {
              solidClip = $(this).attr('clip-path').replace('url(#', '#').replace(')', '')
              $(solidClip).css('transform', 'translate(5px)')
            })
            var customClip = null;
            $('.highcharts-zone-graph-1.customDotZone').each(function() {
              customClip = $(this).attr('clip-path').replace('url(#', '#').replace(')', '')
              $(customClip).css('transform', 'translate(5px)')
            })
          },
          addSeries: function(event) {
            /*
            External function needs series to be added to get datas, axis ...
            Disable chart animation, through it and set in external function
            */
            var thisId = this._jeeId
            if (!jeedom.history.chart[thisId].zoom) {
              this.update({
                chart: {
                  animation: false,
                },
              }, false)

              clearTimeout(jeedomUIHistory.done)
              jeedomUIHistory.done = setTimeout(jeedomUIHistory.chartDone.bind(null, thisId), 1000)

              setTimeout(function() {
                try {
                  jeedomUIHistory.setAxisScales(thisId)
                } catch (error) {}
              }, 10)
            }
          },
          selection: function(event) {
            // zoom or reset zoom event
            var chartId = event.target._jeeId
            //zoom back after reset zoom button. allways play with immutables!
            if (event.resetSelection) {
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
              } catch (error) {}

              setTimeout(function() {
                try {
                  jeedomUIHistory.setAxisScales(chartId)
                } catch (error) {}
              }, 100)

              return false
            } else {
              //No scale/unit change in zoom:
              try { this.resetZoomButton.show() } catch (error) {} //Not created first time
              jeedom.history.chart[chartId].zoom = true
              try {
                jeedom.history.chart[chartId].btToggleyaxisScaling.setState(3)
                jeedom.history.chart[chartId].btToggleyaxisbyunit.setState(3)
              } catch (error) {}
            }
          }
        }
      }

      if (charts.height < 10) {
        charts.height = null;
      }
      if (isset(_params.transparentBackground) && _params.transparentBackground == "1") {
        charts.backgroundColor = 'rgba(255, 255, 255, 0)';
      }
      if (isset(jeedom.history.chart[_params.el]) && jeedom.history.chart[_params.el].type == 'pie') {
        _params.option.graphType = 'pie';
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
          }],
          color: _params.option.graphColor
        };
        if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
          jeedom.history.chart[_params.el] = {};
          jeedom.history.chart[_params.el].cmd = new Array();
          jeedom.history.chart[_params.el].type = _params.option.graphType;
          jeedom.history.chart[_params.el].chart = new Highcharts.Chart({
            chart: charts,
            title: {
              text: ''
            },
            credits: { enabled: false },
            exporting: {
              enabled: _params.enableExport || ($.mobile) ? false : true
            },
            tooltip: {
              pointFormat: '{point.y} {series.userOptions.unite}<br/>{series.userOptions.shortName}',
              valueDecimals: 2,
            },
            plotOptions: {
              series: {
                animation: {
                  duration: (getUrlVars('report') == 1) ? 0 : 1000
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
          });
          //Store references and init buttons from UI:
          jeedom.history.chart[_params.el].containerId = jeedom.history.chart[_params.el].chart.container.id
          jeedom.history.chart[_params.el].chart._jeeId = _params.el
        } else {
          jeedom.history.chart[_params.el].chart.series[0].addPoint({
            y: data.result.data[data.result.data.length - 1][1],
            name: (isset(_params.option.name)) ? _params.option.name + ' ' + data.result.unite : data.result.history_name + ' ' + data.result.unite,
            color: _params.option.graphColor
          });
        }
      }

      //not pie, standard curve history:
      if (_params.option.graphType != 'pie') {
        var dataGrouping = {
          enabled: false
        };
        if (isset(_params.option.groupingType) && jQuery.type(_params.option.groupingType) == 'string' && _params.option.groupingType != '') {
          var split = _params.option.groupingType.split('::');
          _params.option.groupingType = {
            function: split[0],
            time: split[1]
          };
        }
        if (isset(_params.option.groupingType) && isset(_params.option.groupingType.function) && isset(_params.option.groupingType.time)) {
          dataGrouping = {
            approximation: _params.option.groupingType.function,
            enabled: true,
            forced: true,
            units: [
              [_params.option.groupingType.time, [1]]
            ]
          };
        }

        //cmd info string has no value:
        if (data.result.timelineOnly) {
          if (!isset(jeedom.history.chart[_params.el]) || !isset(jeedom.history.chart[_params.el].nbTimeline)) {
            nbTimeline = 1;
          } else {
            jeedom.history.chart[_params.el].nbTimeline++;
            nbTimeline = jeedom.history.chart[_params.el].nbTimeline;
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
            });
          }
        } else {
          if (_params.option.graphType == 'areaspline') {
            _params.option.graphType = 'area'
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
              valueDecimals: 2
            },
            point: {
              events: {
                click: function(event) {
                  jeedom.history.modalchangePoint(event, this, data.result)
                }
              }
            },
            marker:{
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
          series.zIndex = _params.option.graphZindex;
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

        //new first curve:
        if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
          jeedom.history.chart[_params.el] = {}
          jeedom.history.chart[_params.el].cmd = new Array()
          jeedom.history.chart[_params.el].color = seriesNumber - 1
          jeedom.history.chart[_params.el].nbTimeline = 1
          jeedom.history.chart[_params.el].yAxisScaling = true

          //set min max to overide 0->max default HighChart:
          var yMin = null
          var yMax = null
          if (jeedom.history.chart[_params.el].yAxisScaling) {
            yMin = Math.min.apply(Math, series.data.map(function (i) {return i[1]})) / 1.005
            yMax = Math.max.apply(Math, series.data.map(function (i) {return i[1]})) * 1.005
          }

          //dateRange buttons config:
          var dateRange = ['all', '30 min', '1 hour', '1 day', '7 days', '1 month', '1 year'].indexOf(_params.dateRange)
          if (dateRange == -1) dateRange = 4

          jeedom.history.chart[_params.el].type = _params.option.graphType;
          jeedom.history.chart[_params.el].chart = new Highcharts.StockChart({
            chart: charts,
            _jeeId: _params.el,
            credits: { enabled: false },
            plotOptions: {
              series: {
                animation: {
                  duration: (getUrlVars('report') == 1) ? 0 : 1000
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
                    if (!jeedom.history.chart[this.chart._jeeId].zoom) jeedomUIHistory.setAxisScales(this.chart._jeeId)
                    return false
                  }
                }
              }
            },
            exporting: {
              enabled: _params.enableExport || ($.mobile) ? false : true
            },
            rangeSelector: {
              buttonTheme: { // styles for the buttons
                width: 'auto',
                padding: 4
              },
              buttons: [{
                type: 'all',
                count: 1,
                text: '{{Tous}}'
              }, {
                type: 'minute',
                count: 30,
                text: '{{30 min}}'
              }, {
                type: 'hour',
                count: 1,
                text: '{{Heure}}'
              }, {
                type: 'day',
                count: 1,
                text: '{{Jour}}'
              }, {
                type: 'week',
                count: 1,
                text: '{{Semaine}}'
              }, {
                type: 'month',
                count: 1,
                text: '{{Mois}}'
              }, {
                type: 'year',
                count: 1,
                text: '{{Année}}'
              }],
              selected: dateRange,
              inputEnabled: false,
              enabled: _params.showTimeSelector
            },
            responsive: {
              rules: [{
                condition: {
                  maxWidth: 710
                },
                chartOptions: {
                  rangeSelector: {
                    dropdown: 'always'
                  }
                }
              }]
            },
            legend: legend,
            tooltip: {
              xDateFormat: '%a %Y-%m-%d %H:%M:%S',
              pointFormat: '{point.y} {series.userOptions.unite}<br/>{series.userOptions.shortName}',
              valueDecimals: 2,
              crosshairs: [true, true]
            },
            yAxis: [{
              id: _params.cmd_id,
              min: yMin,
              max: yMax,
              showEmpty: false,
              gridLineWidth: 0,
              minPadding: 0.001,
              maxPadding: 0.001,
              labels: {
                format: '{value} ' + series.unite,
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
                width: 5,
                height: 40
              },
              series: {
                includeInCSVExport: false
              }
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
          if (jeedomUIHistory != undefined && typeof jeedomUIHistory.initChart === "function") {
            jeedomUIHistory.initChart(_params.el)
          }
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
          } else {
            //add new yAxis:
            //set min max to overide 0->max default HighChart:
            var yMin = undefined
            var yMax = undefined
            if (jeedom.history.chart[_params.el].yAxisScaling) {
              yMin = Math.min.apply(Math, series.data.map(function (i) {return i[1]})) / 1.005
              yMax = Math.max.apply(Math, series.data.map(function (i) {return i[1]})) * 1.005
            }

            var yAxis = {
              id: _params.cmd_id,
              min: yMin,
              max: yMax,
              showEmpty: false,
              gridLineWidth: 0,
              minPadding: 0.001,
              maxPadding: 0.001,
              labels: {
                format: '{value} ' + series.unite,
                style: {
                  color: _params.option.graphColor
                },
                align: 'center'
              },
              margin: 7,
              opposite: axisOpposite,
              visible: _params.showAxis,
            }

            //add axis to chart:
            series.yAxis = _params.cmd_id
            jeedom.history.chart[_params.el].chart.addAxis(yAxis)
          }
          //add series to graph:
          jeedom.history.chart[_params.el].chart.addSeries(series, false)

        }
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = {
          option: _params.option,
          dateRange: _params.dateRange
        }
      }

      //set plotband:
      var extremes = jeedom.history.chart[_params.el].chart.xAxis[0].getExtremes()
      var plotband = jeedom.history.generatePlotBand(extremes.min, extremes.max)
      for (var i in plotband) {
        jeedom.history.chart[_params.el].chart.xAxis[0].addPlotBand(plotband[i])
      }

      $.hideLoading()
      if (typeof(init(_params.success)) == 'function') {
        _params.success(data.result)
      }
    }
  })
}