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

$(function() {
  displayTimeline()
})

$('#bt_openCmdHistoryConfigure').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de l'historique des commandes}}"
  }).load('index.php?v=d&modal=cmd.configureHistory').dialog('open')
})

$('#sel_timelineFolder').off('change').on('change', function() {
  displayTimeline()
})

$('#bt_tabTimeline').on('click', function() {
  $.hideAlert()
  displayTimeline()
})

$('#timelineContainer ul').on('click', '.bt_scenarioLog', function() {
  $('#md_modal').dialog({
    title: "{{Log d'exécution du scénario}}"
  }).load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + $(this).closest('.tml-scenario').attr('data-id')).dialog('open')
})

$('#timelineContainer ul').on('click', '.bt_gotoScenario', function() {
  jeedomUtils.loadPage('index.php?v=d&p=scenario&id=' + $(this).closest('.tml-scenario').attr('data-id'))
})

$('#timelineContainer ul').on('click', '.bt_historicCmd', function() {
  $('#md_modal2').dialog({
    title: "{{Historique}}"
  }).load('index.php?v=d&modal=cmd.history&id=' + $(this).closest('.tml-cmd').attr('data-id')).dialog('open')
})

$('#timelineContainer ul').on('click', '.bt_configureCmd', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de la commande}}"
  }).load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.tml-cmd').attr('data-id')).dialog('open')
})

$('#bt_refreshTimeline').on('click', function() {
  displayTimeline()
})

//exact same success function desktop/mobile:
function displayTimeline() {
  jeedom.timeline.byFolder({
    folder: $('#sel_timelineFolder').value(),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#timelineContainer ul').empty()
      if (data.length == 0) return
      data.sort(sortByDateConsistentASC)
      data = data.reverse()
      var dataLength = data.length
      var decayFactor = 130

      moment.locale(jeedom_langage)

      var isFirstOfDay, isLastOfDay = false
      var nextDate, thisDateTs = false
      var prevDate = moment().format("YYYY-MM-DD")
      var prevDateTs = moment().unix()
      var content = '<div class="label-warning day">' + moment(data[0].date).format('ddd YYYY-MM-DD') + '</div>'

      var thisData, date, dateFull, time, lineClass, style, height, li
      for (var i in data) {
        thisData = data[i]
        date = thisData.date.substring(0, 10)
        time = thisData.date.substring(11, 19)
        dateFull = moment(thisData.date).format('ddd YYYY-MM-DD')
        thisDateTs = moment(thisData.date.substring(0, 19)).unix()
        lineClass = ''
        if (prevDate != date) {
          isFirstOfDay = true
          prevDateTs = moment(prevDate + ' 00:00:00').unix()
        } else {
          if (i < dataLength - 1) {
            nextDate = data[parseInt(i) + 1].date.substring(0, 10)
            if (date != nextDate) {
              isLastOfDay = true
            }
          }
        }

        //actual time marker:
        if (i == 0) {
          li = '<li style="background-color:transparent!important;">'
          li += '<div class="time typeInfo">' + moment().format('HH:mm:ss') + '</div>'
          li += '<div class="date">' + dateFull + '</div>'
          li += '</li>'
          content += li
        }

        //time spacing:
        style = ''
        height = Math.abs((prevDateTs - thisDateTs) / decayFactor)
        if (height > 5) {
          style = 'margin-top:' + height + 'px!important;'
        }
        if (isLastOfDay && i < dataLength - 1) {
          height = Math.abs((thisDateTs - moment(data[parseInt(i) + 1].date.substring(0, 19)).unix()) / decayFactor)
          style += 'margin-bottom:' + height + 'px!important;'
        }
        li = '<li style="' + style + '">'
        li += '<div>'

        //scenario or cmd info/action:
        li += '<div class="type">'
        if (thisData.group && thisData.plugins) {
          if (thisData.group == 'action') {
            li += thisData.type + '&#160&#160<i class="warning fas fa-terminal"></i><span class="hidden">action</span>'
            lineClass = 'typeAction'
          } else {
            li += thisData.type + '&#160&#160<i class="info fas fa-info-circle"></i><span class="hidden">info</span>'
            lineClass = 'typeInfo'
          }
          li += '&#160&#160' + thisData.plugins
        }
        if (thisData.type == 'scenario') {
          li += thisData.type + '&#160&#160<i class="success jeedom-clap_cinema"></i>'
          lineClass = 'typeScenario'
        }
        li += '</div>'

        //html:
        li += '<div class="html">' + thisData.html + '</div>'

        li += '</div>'
        li += '<span class="vertLine ' + lineClass + '"></span>'
        //time:
        li += '<div class="time ' + lineClass + '">' + time + '</div>'

        //date:
        li += '<div class="date">' + dateFull + '</div>'

        li += '</li>'
        content += li

        //newDay ?
        if (isLastOfDay) {
          content += '<div class="label-warning day">' + moment(nextDate).format('ddd YYYY-MM-DD') + '</div>'
        }

        prevDate = date
        prevDateTs = thisDateTs
        isFirstOfDay = isLastOfDay = false
      }
      $('#timelineContainer ul').empty().append(content)
      if (user_isAdmin != 1) {
        $('.bt_configureCmd').remove()
        $('.bt_gotoScenario').remove()
      }
    }
  })
}

function sortByDateConsistentASC(itemA, itemB) {
  var valueA = itemA.date
  var valueB = itemB.date
  var a = moment(valueA)
  var b = moment(valueB)
  var r = 0
  if (a.isValid() && b.isValid()) {
    r = ((a.valueOf() > b.valueOf()) ? 1 : ((a.valueOf() < b.valueOf()) ? -1 : 0))
  }
  if (r === 0) {
    r = (typeof itemA.key !== 'undefined' && typeof itemB.key !== 'undefined') ?
      itemA.key - itemB.key : 0
  }
  return r
}