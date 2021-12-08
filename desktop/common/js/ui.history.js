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

var jeedomUIHistory = {}

/*
@history
@view
@plan
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
      var chart = $($trigger[0].parentNode).closest('div.chartContainer').highcharts()
      if (jeedom.history.chart[chart._jeeId].comparing) return false
      if (!chart) return false

      var serieId = $trigger.attr("class").split('highcharts-series-')[1].split(' ')[0]
      var cmdId = chart.series[serieId].userOptions.id
      var axis = chart.get(cmdId)
      var contextmenuitems = {}
      contextmenuitems['togglescaling'] = {'name': 'Toggle yAxis Scaling', 'id': 'togglescaling'}
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
Remove all series/yAxis from chart:
@history
*/
jeedomUIHistory.emptyChart = function(_chartId) {
  jeedom.history.chart[_chartId].chart.series.forEach(function(series) {
    series.remove(true)
  })
  jeedom.history.chart[_chartId].chart.yAxis.forEach(function(yAxis) {
    yAxis.remove(true)
  })
}

/*
Set all yAxis scale the same based on all axis min/max
@history.class.js
*/
jeedomUIHistory.resetyAxisScaling = function(_chartId) {
  var chart = jeedom.history.chart[_chartId].chart
  var min = 100000
  var max = -100000
  chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
    if (axis.dataMin < min) min = axis.dataMin
    if (axis.dataMax > max) max = axis.dataMax
  })
  chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
    axis.setExtremes(min / 1.005, max * 1.005)
  })
}

/*
Adapt each yAxis scale min/max
@history.class.js
*/
jeedomUIHistory.setAxisScaling = function(_chartId) {
  var chart = jeedom.history.chart[_chartId].chart
  var min, max, axisId
  chart.yAxis.filter(v => v.userOptions.id != 'navigator-y-axis').forEach((axis, index) => {
    axisId = axis.userOptions.id
    if (!axisId) axisId = 0
    min = Math.min.apply(Math, axis.series[0].data.map(function (i) {return i.options.y}))
    max = Math.max.apply(Math, axis.series[0].data.map(function (i) {return i.options.y}))
    axis.setExtremes(min / 1.005, max * 1.005)
  })
}

/*
Toggle all yAxis scaling
@history.class.js
*/
jeedomUIHistory.toggleyAxisScaling = function(_chartId) {
  if (jeedom.history.chart[_chartId].zoom) return
  var chart = jeedom.history.chart[_chartId].chart
  if (jeedom.history.chart[_chartId].yAxisScaling) {
    jeedom.history.chart[_chartId].btToggleyaxisScaling.setState(0)
    jeedomUIHistory.resetyAxisScaling(_chartId)
  } else {
    jeedom.history.chart[_chartId].btToggleyaxisScaling.setState(2)
    jeedomUIHistory.setAxisScaling(_chartId)
  }
  jeedom.history.chart[_chartId].yAxisScaling = !jeedom.history.chart[_chartId].yAxisScaling
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
Called from new chart load event
@history.class.js
*/
jeedomUIHistory.initChart = function(_chartId) {
  var thisId = _chartId
  jeedom.history.chart[thisId].comparing = false
  jeedom.history.chart[thisId].zoom = false
  var hideButtons = $('body').attr('data-page') == 'plan' ? true : false

  /*HichChart button states (undocumented):
    0: normal
    1: hover
    2: selected
    3: disabled
  */

  //Tracking button:
  jeedom.history.chart[thisId].btTracking = jeedom.history.chart[thisId].chart.renderer.button('Tracking', 0, 6)
  .attr({
    id: 'hc_bt_tracking',
    height: 10
  })
  .on('click', function(event) {
    jeedomUIHistory.toggleTracking(thisId)
  })
  .css({
    transform: 'translateX(calc(100% - 100px)) translateY(5px)',
  })
  .add()
  jeedom.history.chart[thisId].tracking = true
  jeedom.history.chart[thisId].btTracking.setState(2)
  if (hideButtons) jeedom.history.chart[thisId].btTracking.hide()

  //toggle yAxis scaling:
  jeedom.history.chart[thisId].btToggleyaxisScaling = jeedom.history.chart[thisId].chart.renderer.button('yAxis Scaling', 0, 6)
  .attr({
    id: 'hc_bt_resetYaxisScale',
    height: 10
  })
  .on('click', function(event) {
    jeedomUIHistory.toggleyAxisScaling(thisId)
  })
  .css({
    transform: 'translateX(calc(100% - 195px)) translateY(5px)',
  })
  .add()
  jeedom.history.chart[thisId].yAxisScaling = true
  jeedom.history.chart[thisId].btToggleyaxisScaling.setState(2)
  if (hideButtons) jeedom.history.chart[thisId].btToggleyaxisScaling.hide()

  //toggle yAxis visible button:
  jeedom.history.chart[thisId].btToggleyaxisVisible = jeedom.history.chart[thisId].chart.renderer.button('yAxis Visible', 0, 6)
  .attr({
    id: 'hc_bt_toggleYaxis',
    height: 10
  })
  .on('click', function(event) {
    jeedomUIHistory.toggleYaxisVisible(thisId)
  })
  .css({
    transform: 'translateX(calc(100% - 286px)) translateY(5px)',
  })
  .add()
  jeedom.history.chart[thisId].yAxisVisible = true
  jeedom.history.chart[thisId].btToggleyaxisVisible.setState(2)
  if (hideButtons) jeedom.history.chart[thisId].btToggleyaxisVisible.hide()
}