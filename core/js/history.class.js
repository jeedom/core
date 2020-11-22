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
    pre_success: function(data) {
      if (isset(jeedom.cmd.cache.byId[data.result.id])) {
        delete jeedom.cmd.cache.byId[data.result.id];
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch(e) {
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
    dateEnd: _params.dateEnd || ''
  };
  $.ajax(paramsAJAX);
}

jeedom.history.getInitDates = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch(e) {
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
  } catch(e) {
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
  var paramsRequired = ['cmd_id','datetime','value','oldValue'];
  var paramsSpecifics = {
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function(result) {
      $('#div_alert').showAlert({message: '{{La valeur a été éditée avec succès}}', level: 'success'});
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
            option: jeedom.history.chart[i].cmd[_params.cmd_id].option
          });
        }
      }
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch(e) {
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
    oldValue : _params.oldValue
  };
  $.ajax(paramsAJAX);
}

jeedom.history.drawChart = function(_params) {
  $.showLoading();
  if ($.type(_params.dateRange) == 'object') {
    _params.dateRange = json_encode(_params.dateRange);
  }
  _params.option = init(_params.option, {derive: ''});
  var _visible = (isset(_params.visible)) ? _params.visible : true

  //get command history
  $.ajax({
    type: "POST",
    url: "core/ajax/cmd.ajax.php",
    data: {
      action: "getHistory",
      id: _params.cmd_id,
      dateRange:  ($.type(_params.dateRange) == 'object') ? json_encode(_params.dateRange)  || '' : _params.dateRange || '',
      dateStart: _params.dateStart || '',
      dateEnd: _params.dateEnd || '',
      derive: _params.option.derive || '',
      allowZero: init(_params.option.allowZero, 0),
      groupingType : _params.option.groupingType || '',
      lastPointToEnd : _params.option.lastPointToEnd || 0,
    },
    dataType: 'json',
    global: _params.global || true,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      //check history exist:
      if (data.state != 'ok') {
        $('#div_alert').showAlert({message: data.result, level: 'danger'});
        return;
      }
      if (data.result.data.length < 1) {
        if (_params.option.displayAlert == false) {
          return;
        }
        if (!_params.noError) {
          var message = '{{Il n\'existe encore aucun historique pour cette commande :}} ' + data.result.history_name;
          if (init(data.result.dateStart) != '') {
            message += (init(data.result.dateEnd) != '') ?  ' {{du}} ' + data.result.dateStart + ' {{au}} ' + data.result.dateEnd : ' {{à partir de}} ' + data.result.dateStart;
          } else {
            message += (init(data.result.dateEnd) != '') ? ' {{jusqu\'au}} ' + data.result.dateEnd:'';
          }
          $('#div_alertHistory').showAlert({message: message, level: 'warning'});
          if (typeof (init(_params.success)) == 'function') {
            _params.success({'error': message})
          }
        }
        return;
      }

      //set/check some params:
      if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].cmd[_params.cmd_id])) {
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = null;
      }
      _params.option.graphDerive = (data.result.derive == "1") ? true : false;

      //series colors, options defined in core/js/jeedom.class.js jeedom.init():
      var colors = Highcharts.getOptions().colors
      var opacityHigh = 0.85
      var opacityLow = 0.1
      var seriesNumber = 1
      var colorsNbr = colors.length
      if (isset(jeedom.history.chart[_params.el]) && isset(jeedom.history.chart[_params.el].chart.series)) {
        seriesNumber = jeedom.history.chart[_params.el].chart.series.length
      }
      if (seriesNumber > colorsNbr || seriesNumber == 0) {
        seriesNumber = 1
      }
      if (!isset(_params.option.graphColor) || _params.option.graphColor === undefined) {
        _params.option.graphColor = colors[seriesNumber-1];
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
          _params.option.groupingType = {function :split[0],time : split[1] };
        }
      }

      var stacking = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? null : 'value';
      _params.option.graphStack = (_params.option.graphStack == undefined || _params.option.graphStack == null || _params.option.graphStack == 0) ? Math.floor(Math.random() * 10000 + 2) : 1;
      _params.option.graphScale = (_params.option.graphScale == undefined) ? 0 : parseInt(_params.option.graphScale);
      _params.showLegend = (init(_params.showLegend, true) && init(_params.showLegend, true) != "0") ? true : false;
      _params.showTimeSelector = (init(_params.showTimeSelector, true) && init(_params.showTimeSelector, true) != "0") ? true : false;
      _params.showScrollbar = (init(_params.showScrollbar, true) && init(_params.showScrollbar, true) != "0") ? true : false;
      _params.showNavigator = (init(_params.showNavigator, true) && init(_params.showNavigator, true) != "0") ? true : false;

      //define legend and reset graph:
      var legend = {borderColor: 'black', borderWidth: 2, shadow: true};
      legend.enabled = init(_params.showLegend, true);
      if (isset(_params.newGraph) && _params.newGraph == true) {
        delete jeedom.history.chart[_params.el]
      }

      //jeedom default chart params:
      var charts = {
        zoomType: 'xy',
        pinchType: 'xy',
        renderTo: _params.el,
        alignTicks: false,
        spacingBottom: 5,
        spacingTop: 5,
        spacingRight: 5,
        spacingLeft: 5,
        height : _params.height || null,
        style: {fontFamily: 'Roboto'},
        events : {
          load: function() {
            var min = this.series[0].dataMin / 1.005
            var max = this.series[0].dataMax * 1.005
            this.yAxis[0].setExtremes(min, max, true, false)
          },
          selection: function(event) {
            //zoom back after reset zoom button. allways play with immutables!
            if (event.resetSelection) {
              var min = this.fullMin
              var max = this.fullMax
              if (this.series.length <= 2) {
                chart = $('#div_graph').highcharts()
                setTimeout(function() {
                  event.target.yAxis[0].setExtremes(min, max, true, true)
                }, 10)
              }
            } else {
              this.fullMin = this.yAxis[0].dataMin / 1.005
              this.fullMax = this.yAxis[0].dataMax * 1.005
            }
          },
          addSeries: function(event) {
            this.yAxis[0].setExtremes()
          },
          render: function() {
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
                  y:data.result.data[data.result.data.length - 1][1],
                  name : (isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name + ' '+ data.result.unite,
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
            credits: {
              text: '',
              href: '',
            },
            exporting: {
              enabled: _params.enableExport || ($.mobile) ? false : true
            },
            tooltip: {
              pointFormat: '{point.y} <br/>{series.name}',
              valueDecimals: 2,
            },
            plotOptions: {
              series: {
                animation: {
                  duration : (getUrlVars('report') == 1) ? 0 : 1000
                }
              },
              pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                  style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                  }
                },
                showInLegend: true
              }
            },
            series: [series]
          });
        } else {
          jeedom.history.chart[_params.el].chart.series[0].addPoint({
                y:data.result.data[data.result.data.length - 1][1],
                name : (isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name + ' '+ data.result.unite,
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
          _params.option.groupingType = {function :split[0],time : split[1] };
        }
        if (isset(_params.option.groupingType) && isset(_params.option.groupingType.function) && isset(_params.option.groupingType.time)) {
          dataGrouping = {
            approximation: _params.option.groupingType.function,
            enabled: true,
            forced: true,
            units: [[_params.option.groupingType.time,[1]]]
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
            name: (isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name+ ' '+ data.result.unite,
            data: [],
            id: _params.cmd_id,
            color: _params.option.graphColor,
            shape: 'squarepin',
            cursor: 'pointer',
            y : -30 - 25*(nbTimeline - 1),
            point: {
              events: {
                click: function(event) {
                  var deviceInfo = getDeviceType();
                  if ($.mobile || deviceInfo.type == 'tablet' || deviceInfo.type == 'phone') return
                  if ($('#md_modal2').is(':visible')) return
                  if ($('#md_modal1').is(':visible')) return

                  var id = this.series.userOptions.id;
                  var datetime = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x);
                  var value = this.y;
                  bootbox.prompt("{{Edition de la série :}} <b>" + this.series.name + "</b> {{et du point de}} <b>" + datetime + "</b> ({{valeur :}} <b>" + value + "</b>) ? {{Ne rien mettre pour supprimer la valeur}}", function(result) {
                    if (result !== null) {
                      jeedom.history.changePoint({cmd_id: id, datetime: datetime,oldValue:value, value: result});
                    }
                  });
                }
              }
            }
          }

          for (var i in data.result.data) {
            series.data.push({
              x : data.result.data[i][0],
              title : data.result.data[i][1]
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
            name: (isset(_params.mobile)) ? data.result.unite : ((isset(_params.option.name)) ? _params.option.name + ' '+ data.result.unite : data.result.history_name + ' '+ data.result.unite),
            data: data.result.data,
            color: _params.option.graphColor,
            fillColor: {
              linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
              stops: [
                      [0, Highcharts.Color(_params.option.graphColor).setOpacity(opacityHigh).get('rgba')],
                      [1, Highcharts.Color(_params.option.graphColor).setOpacity(opacityLow).get('rgba')]
                    ],
              },
            stack: _params.option.graphStack,
            derive: _params.option.graphDerive,
            step: _params.option.graphStep,
            yAxis: _params.option.graphScale,
            stacking : stacking,
            unite: data.result.unite,
            shortName: (isset(_params.option.name)) ? _params.option.name : data.result.history_name,
            tooltip: {
              valueDecimals: 2
            },
            point: {
              events: {
                click: function(event) {
                  var deviceInfo = getDeviceType();
                  if ($.mobile || deviceInfo.type == 'tablet' || deviceInfo.type == 'phone') {
                    return
                  }
                  if ($('#md_modal2').is(':visible')) {
                    return
                  }
                  if ($('#md_modal1').is(':visible')) {
                    return
                  }
                  var id = this.series.userOptions.id;
                  var datetime = Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x)
                  var value = this.y
                  bootbox.prompt("{{Edition de la série :}} <b>" + this.series.name + "</b> {{et du point de}} <b>" + datetime + "</b> ({{valeur :}} <b>" + value + "</b>) ? {{Ne rien mettre pour supprimer la valeur}}", function(result) {
                    if (result !== null) {
                      jeedom.history.changePoint({cmd_id: id, datetime: datetime,oldValue:value, value: result})
                    }
                  })
                }
              }
            }
          }

          if (init(_params.option.groupingType) == '') {
            //continue value to now, dotted if last value older than one minute (ts in millisecond):
            var dateEnd = new Date(data.result.dateEnd)
            dateEnd.setTime( dateEnd.getTime() - dateEnd.getTimezoneOffset()*60*1000 )
            var dateEndTs = dateEnd.getTime()
            var diffms = dateEndTs - data.result.data[data.result.data.length - 1][0]
            if (diffms > 60000) {
              series.zoneAxis = 'x'
              data.result.data.push([dateEndTs, data.result.data[data.result.data.length - 1][1]])
              series.zones = [
                {value: data.result.data[data.result.data.length - 2][0], dashStyle: 'Solid', className: 'customSolidZone'},
                {value: data.result.data[data.result.data.length - 1][0], dashStyle: 'ShortDash', className: 'customDotZone'}
              ]
            }
          }
        }

        if (isset(_params.option.graphZindex)) {
          series.zIndex = _params.option.graphZindex;
        }

        //new first curve:
        if (!isset(jeedom.history.chart[_params.el]) || (isset(_params.newGraph) && _params.newGraph == true)) {
          jeedom.history.chart[_params.el] = {}
          jeedom.history.chart[_params.el].cmd = new Array()
          jeedom.history.chart[_params.el].color = seriesNumber - 1
          jeedom.history.chart[_params.el].nbTimeline = 1

          if (_params.dateRange == '30 min') {
            var dateRange = 1
          } else  if (_params.dateRange == '1 hour') {
            var dateRange = 2
          } else  if (_params.dateRange == '1 day') {
            var dateRange = 3
          } else  if (_params.dateRange == '7 days') {
            var dateRange = 4
          } else  if (_params.dateRange == '1 month') {
            var dateRange = 5
          } else  if (_params.dateRange == '1 year') {
            var dateRange = 6
          } else  if (_params.dateRange == 'all') {
            var dateRange = 0
          } else {
            var dateRange = 4
          }

          jeedom.history.chart[_params.el].type = _params.option.graphType;
          jeedom.history.chart[_params.el].chart = new Highcharts.StockChart({
            chart: charts,
            credits: {
              text: '',
              href: '',
            },
            navigator: {
              enabled:  _params.showNavigator,
              series: {
                includeInCSVExport: false
              }
            },
            plotOptions: {
              series: {
                animation: {
                  duration : (getUrlVars('report') == 1) ? 0 : 1000
                }
              }
            },
            exporting: {
              enabled: _params.enableExport || ($.mobile) ? false : true
            },
            rangeSelector: {
              buttonTheme: { // styles for the buttons
                width : 'auto',
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
            legend: legend,
            tooltip: {
              xDateFormat: '%Y-%m-%d %H:%M:%S',
              pointFormat: '{point.y} {series.userOptions.unite}<br/>{series.userOptions.shortName}',
              valueDecimals: 2,
            },
            yAxis: [{
              format: '{value}',
              showEmpty: false,
              minPadding: 0.001,
              maxPadding: 0.001,
              showLastLabel: true
            }, {
              opposite: false,
              format: '{value}',
              showEmpty: false,
              gridLineWidth: 0,
              minPadding: 0.001,
              maxPadding: 0.001,
              labels: {
                align: 'left',
                x: 2
              }
            }],
            xAxis: {
              type: 'datetime',
              ordinal: false,
              maxPadding : 0.02,
              minPadding : 0.02
            },
            scrollbar: {
              barBackgroundColor: 'gray',
              barBorderRadius: 7,
              barBorderWidth: 0,
              buttonBackgroundColor: 'gray',
              buttonBorderWidth: 0,
              buttonBorderRadius: 7,
              trackBackgroundColor: 'none', trackBorderWidth: 1,
              trackBorderRadius: 8,
              trackBorderColor: '#CCC',
              enabled: _params.showScrollbar
            },
            series: [series]
          })
        } else {
          //add curve to existing graph:
          jeedom.history.chart[_params.el].chart.addSeries(series)
        }
        jeedom.history.chart[_params.el].cmd[_params.cmd_id] = {option: _params.option, dateRange: _params.dateRange}
      }

      //set plotband:
      var extremes = jeedom.history.chart[_params.el].chart.xAxis[0].getExtremes()
      var plotband = jeedom.history.generatePlotBand(extremes.min, extremes.max)
      for (var i in plotband) {
        jeedom.history.chart[_params.el].chart.xAxis[0].addPlotBand(plotband[i])
      }

      $.hideLoading()
      if (typeof (init(_params.success)) == 'function') {
        _params.success(data.result)
      }
    }
  })
}