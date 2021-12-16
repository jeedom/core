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

var jeedomUIHistory = {
  done: false,
  default: {
    tracking: true,
    yAxisByUnit: true,
    yAxisScaling: true,
    yAxisVisible: true
  }
}

/*
@history
@view
@plan
@modal history
@mobile history
*/
jeedomUIHistory.initLegendContextMenu = function(_chartId) {
  $.contextMenu({
    selector: "div.chartContainer .highcharts-legend-item",
    position: function(opt, x, y) {
      //legend bottom graph, open menu upside if possible:
      var menuHeight = opt.$menu[0].clientHeight
      var menuWidth = opt.$menu[0].clientWidth
      var winHeight = $(window).height()
      var winWidth = $(window).width()

      var newTop =  y - menuHeight
      var newLeft = x

      if ((y - menuHeight - 20) < 0) {
        newTop = y
      }
      if ((x + menuWidth + 20) > winWidth) {
        newLeft = x - (menuWidth + 20)
      }
      opt.$menu.css({top: newTop, left: newLeft})
    },
    build: function($trigger) {
      var __ctxel__ = $($trigger[0].parentNode).closest('div.chartContainer').attr('id')
      var chart = jeedom.history.chart[__ctxel__].chart
      if (!chart) return false
      if (jeedom.history.chart[chart._jeeId].type == 'pie') return false
      if (jeedom.history.chart[chart._jeeId].comparing) return false

      var seriesName = $($trigger[0]).find('text').text()
      var serieId = chart.series.filter(key => key.name == seriesName)[0].index
      var cmdId = chart.series[serieId].userOptions.id
      var axis = chart.get(cmdId)
      var contextmenuitems = {}
      contextmenuitems['cmdid'] = {'name': 'id: ' + cmdId, 'id': 'cmdid', 'disabled': true}
      if (!jeedom.history.chart[chart._jeeId].zoom) contextmenuitems['togglescaling'] = {'name': 'Toggle yAxis Scaling', 'id': 'togglescaling'}
      contextmenuitems['isolate'] = {'name': '{{Isoler}} (Ctrl Clic)', 'id': 'isolate', 'icon': 'fas fa-chart-line'}
      contextmenuitems['showall'] = {'name': '{{Afficher tout}} (Alt Clic)', 'id': 'showall', 'icon': 'fas fa-poll-h'}
      if (axis.visible) {
        contextmenuitems['hideaxis'] = {'name': '{{Masquer axe}}', 'id': 'hideaxis', 'icon': 'far fa-eye-slash'}
      } else {
        contextmenuitems['showaxis'] = {'name': '{{Afficher axe}}', 'id': 'showaxis', 'icon': 'far fa-eye'}
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
          if (key == 'togglescaling') {
            jeedomUIHistory.toggleyAxisScaling(chart._jeeId)
            return
          }
          if (key == 'showall') {
            $(chart.series).each(function(idx, item) {
              item.show()
            })
            return
          }
          if (key == 'isolate') {
            $(chart.series).each(function(idx, item) {
              item.hide()
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
timeout interval for chart done stuff
@history.class.js
*/
jeedomUIHistory.chartDone = function(_chartId) {
  try {
    if (_chartId === undefined) return false
    var chart = jeedom.history.chart[_chartId].chart
    chart.update({
      chart: {
        animation: true,
      },
    }, false)
    chart.setSize()
    jeedomUIHistory.setAxisScales(_chartId, {redraw: true})

    setTimeout(function() {
      try {
        if (!jeedom.history.chart[_chartId].comparing && typeof setChartOptions === "function") {
          setChartOptions()
        }
      } catch (error) {}
    }, 100)

  } catch (error) { console.error(error)}
}

/*
Set each existing yAxis scale according to chart yAxisScaling and yAxisByUnit
@history.class.js event resetSelection
*/
jeedomUIHistory.setAxisScales = function(_chartId, _options) {
  if (_chartId === undefined) return false
  //All done with render false, redraw at end
  var chart = jeedom.history.chart[_chartId].chart

  if (isset(_options)) {
    /*
    xAxis[0] min/max : zoomed dateRange in navigator
    xAxis[0] dataMin/dataMan : full dateRange
    */
    if (isset(_options.extremeXmin) && isset(_options.extremeXmax)) {
      chart.xAxis[0].setExtremes(_options.extremeXmin, _options.extremeXmax, false)
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

      mathMin = Math.min.apply(Math, axis.series[0].data.map(function(x) { return x.y }))
      mathMax = Math.max.apply(Math, axis.series[0].data.map(function(x) { return x.y }))
      if (mathMin < units[unit].min) units[unit].min = mathMin
      if (mathMax > units[unit].max) units[unit].max = mathMax
    })
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      unit = axis.series[0].userOptions.unite
      if (unit == '') unit = axis.userOptions.id
      axis.update({
        softMin: 0,
        softMax: null,
        min: null,
        max: units[unit].max,
        tickPositions: null
      }, false)
      axis.setExtremes(null,  units[unit].max * 1.005, false)
    })
  }

  //No scale | No unit : (HighChart default) All axis will get same global min/max
  if (!jeedom.history.chart[_chartId].yAxisScaling && !jeedom.history.chart[_chartId].yAxisByUnit) {
    var softMax = 0
    var mathMax
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      mathMax = Math.max.apply(Math, axis.series[0].data.map(function(x) { return x.y }))
      if (mathMax > softMax) softMax = mathMax
    })
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      axis.update({
        softMin: 0,
        softMax: softMax / 1.005,
        min: null,
        max: null,
        tickPositions: null
      }, false)
      axis.setExtremes(null, null, false)
    })
  }

  //scale | unit : (Jeedom default)  All axis with same unit will get same min/max
  if (jeedom.history.chart[_chartId].yAxisScaling && jeedom.history.chart[_chartId].yAxisByUnit) {
    var unit, mathMin, mathMax
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
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

      mathMin = Math.min.apply(Math, axis.series[0].data.map(function(x) { return x.y }))
      mathMax = Math.max.apply(Math, axis.series[0].data.map(function(x) { return x.y }))
      if (mathMin < units[unit].min) units[unit].min = mathMin
      if (mathMax > units[unit].max) units[unit].max = mathMax
    })
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      unit = axis.series[0].userOptions.unite
      if (unit == '') unit = axis.userOptions.id
      axis.update({
        softMin: null,
        softMax: null,
        min: null,
        max: null,
        tickPositions: null
      }, false)
      axis.setExtremes(units[unit].min / 1.005,  units[unit].max * 1.005, false)
    })
  }

  //scale | No unit : Each axis will get its own min/max
  if (jeedom.history.chart[_chartId].yAxisScaling && !jeedom.history.chart[_chartId].yAxisByUnit) {
    var min, max, axisId
    chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
      axisId = axis.userOptions.id
      if (!axisId) axisId = 0
      min = Math.min.apply(Math, axis.series[0].data.map(function (i) {return i.options.y}))
      max = Math.max.apply(Math, axis.series[0].data.map(function (i) {return i.options.y}))
      axis.update({
        softMin: null,
        softMax: null,
        min: null,
        max: null,
        tickPositions: null
      }, false)
      axis.setExtremes(min / 1.005, max * 1.005, false)
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
  no unit: all axis colored, unit: all axis uncolored
  @view
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
        }, false)
      })
    } else {
      chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
        axis.update({
          labels: {
            style: {
              color: 'var(--link-color)'
            },
          }
        }, false)
      })
    }
  }

  if (isset(_options)) {
    if (isset(_options.redraw) && _options.redraw == true) {
      chart.redraw()
    }
  }

  if (typeof setChartOptions === "function") {
    if (!jeedom.history.chart[_chartId].comparing) setChartOptions()
  }
}

/*
Toggle all yAxis scaling
@history.class.js
*/
jeedomUIHistory.toggleyAxisScaling = function(_chartId) {
  if (jeedom.history.chart[_chartId].zoom) return
  var chart = jeedom.history.chart[_chartId].chart

  jeedom.history.chart[_chartId].yAxisScaling = !jeedom.history.chart[_chartId].yAxisScaling
  if (!jeedom.history.chart[_chartId].yAxisScaling) {
    jeedom.history.chart[_chartId].btToggleyaxisScaling.setState(0)
  } else {
    jeedom.history.chart[_chartId].btToggleyaxisScaling.setState(2)
  }
  this.setAxisScales(_chartId, {redraw: true})
}

/*
Set inactive opacity for tracking
@history.class.js
*/
jeedomUIHistory.toggleTracking = function(_chartId) {
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
@history.class.js
*/
jeedomUIHistory.toggleYaxisVisible = function(_chartId) {
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
@history
*/
jeedomUIHistory.emptyChart = function(_chartId) {
  jeedom.history.chart[_chartId].chart.series.forEach(function(series) {
    series.remove(false)
  })
  /*
  jeedom.history.chart[_chartId].chart.yAxis.forEach(function(yAxis) {
    yAxis.remove(false)
  })
  */
  jeedom.history.chart[_chartId].chart.redraw()
}

/*
Set list of calculs on history page, synched back from modal calcul
@history
*/
jeedomUIHistory.setCalculList = function() {
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

/*
Called from new chart load event
@history.class.js
*/
jeedomUIHistory.initChart = function(_chartId) {
  var thisId = _chartId
  jeedom.history.chart[thisId].comparing = false
  jeedom.history.chart[thisId].zoom = false
  jeedom.history.chart[thisId].mode = jeedom.getPageType()
  if (jeedom.history.chart[thisId].type == 'pie') return false

  //default:
  if (jeedom.history.chart[thisId].mode == 'plan') {
    jeedomUIHistory.default.yAxisScaling = false
  }

  /*HichChart button states (undocumented):
    0: normal
    1: hover
    2: selected
    3: disabled
  */

  //Tracking button:
  jeedom.history.chart[thisId].btTracking = jeedom.history.chart[thisId].chart.renderer.button('Tracking', null, null)
  .attr({
    id: 'hc_bt_tracking',
    height: 10,
    align: 'right',
    title: "{{Opacité des courbes au suivi de la souris}}"
  })
  .on('click', function(event) {
    jeedomUIHistory.toggleTracking(thisId)
  })
  .add()
  .align({
    align: 'right',
    x: -35,
    y: 5
  }, false, null)
  if (jeedomUIHistory.default.tracking) {
    jeedom.history.chart[thisId].tracking = true
    jeedom.history.chart[thisId].btTracking.setState(2)
  } else {
    jeedom.history.chart[thisId].tracking = false
    jeedom.history.chart[thisId].btTracking.setState(0)
  }

  //yAxis scaling by unit:
  jeedom.history.chart[thisId].btToggleyaxisbyunit = jeedom.history.chart[thisId].chart.renderer.button('U', 0, 6)
  .attr({
    id: 'hc_bt_YaxisByUnit',
    height: 10,
    align: 'right',
    title: "{{Groupement des axes Y par unité}}"
  })
  .on('click', function(event) {
    if (jeedom.history.chart[thisId].zoom) return false
    jeedom.history.chart[thisId].yAxisByUnit = !jeedom.history.chart[thisId].yAxisByUnit
    if (!jeedom.history.chart[thisId].yAxisByUnit) {
      jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(0)
    } else {
      jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(2)
    }
    jeedomUIHistory.setAxisScales(thisId, {redraw: true})
  })
  .add()
  .align({
    align: 'right',
    x: -102,
    y: 5
  }, false, null)
  if (jeedomUIHistory.default.yAxisByUnit) {
    jeedom.history.chart[thisId].yAxisByUnit = true
    jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(2)
  } else {
    jeedom.history.chart[thisId].yAxisByUnit = false
    jeedom.history.chart[thisId].btToggleyaxisbyunit.setState(0)
  }

  //toggle yAxis scaling:
  jeedom.history.chart[thisId].btToggleyaxisScaling = jeedom.history.chart[thisId].chart.renderer.button('yAxis Scaling', 0, 6)
  .attr({
    id: 'hc_bt_toggleYaxisScale',
    height: 10,
    align: 'right',
    title: "{{Echelle independante des axes Y}}"
  })
  .on('click', function(event) {
    jeedomUIHistory.toggleyAxisScaling(thisId)
  })
  .add()
  .align({
    align: 'right',
    x: -130,
    y: 5
  }, false, null)
  if (jeedomUIHistory.default.yAxisScaling) {
    jeedom.history.chart[thisId].yAxisScaling = true
    jeedom.history.chart[thisId].btToggleyaxisScaling.setState(2)
  } else {
    jeedom.history.chart[thisId].yAxisScaling = false
    jeedom.history.chart[thisId].btToggleyaxisScaling.setState(0)
  }

  //toggle yAxis visible button:
  jeedom.history.chart[thisId].btToggleyaxisVisible = jeedom.history.chart[thisId].chart.renderer.button('yAxis Visible', 0, 6)
  .attr({
    id: 'hc_bt_toggleYaxis',
    height: 10,
    align: 'right',
    title: "{{Affichage des axes Y}}"
  })
  .on('click', function(event) {
    jeedomUIHistory.toggleYaxisVisible(thisId)
  })
  .add()
  .align({
    align: 'right',
    x: -223,
    y: 5
  }, false, null)
  if (jeedomUIHistory.default.yAxisVisible) {
    jeedom.history.chart[thisId].yAxisVisible = true
    jeedom.history.chart[thisId].btToggleyaxisVisible.setState(2)
  } else {
    jeedom.history.chart[thisId].yAxisVisible = false
    jeedom.history.chart[thisId].btToggleyaxisVisible.setState(0)
  }

  //store all that in chart:
  jeedom.history.chart[thisId].chart._jeeButtons = [
    jeedom.history.chart[thisId].btTracking,
    jeedom.history.chart[thisId].btToggleyaxisbyunit,
    jeedom.history.chart[thisId].btToggleyaxisScaling,
    jeedom.history.chart[thisId].btToggleyaxisVisible
    ]
}
