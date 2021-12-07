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
jeedomUIHistory.isComparing = false
jeedomUIHistory.chart = null

/*
@history
@view
@plan
*/
jeedomUIHistory.initLegendContextMenu = function(_container) {
  if (!isset(_container)) _container = $('body')
  _container.contextMenu({
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
      if (jeedomUIHistory.isComparing) return false
      jeedomUIHistory.chart = $($trigger[0].parentNode).closest('div.chartContainer').highcharts()
      if (!jeedomUIHistory.chart) return false

      var serieId = $trigger.attr("class").split('highcharts-series-')[1].split(' ')[0]
      var cmdId = jeedomUIHistory.chart.series[serieId].userOptions.id
      var axis = jeedomUIHistory.chart.get(cmdId)
      var contextmenuitems = {}
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
          if (key == 'showall') {
            $(jeedomUIHistory.chart.series).each(function(idx, item) {
              item.show()
            })
            return
          }
          if (key == 'isolate') {
            $(jeedomUIHistory.chart.series).each(function(idx, item) {
              item.hide()
            })
            jeedomUIHistory.chart.series[serieId].show()
            return
          }
          if (key == 'hideaxis') {
            axis.update({
              visible: false
            })
            return
          }
          if (key == 'showaxis') {
            axis.update({
              visible: true
            })
            return
          }
          if (key.startsWith('color_')) {
            var idx = key.split('_')[1]
            var opacityHigh = 0.85
            var opacityLow = 0.1
            var newC = Highcharts.getOptions().colors[idx]
            jeedomUIHistory.chart.series[serieId].update({
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
Set all yAxis scale the same based on all axis min/max
@history.class.js
*/
jeedomUIHistory.alignAllYaxis = function(_respectUnites=false) {
  if (!jeedomUIHistory.chart) return
  var min = 10000
  var max = -10000
  jeedomUIHistory.chart.yAxis.forEach((axis, index) => {
    if (axis.dataMin < min) min = axis.dataMin
    if (axis.dataMax > max) max = axis.dataMax
  })
  jeedomUIHistory.chart.yAxis.forEach((axis, index) => {
    axis.setExtremes(min / 1.005, max * 1.005)
  })
}

