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

if (!jeeFrontEnd.timeline) {
  jeeFrontEnd.timeline = {
    init: function() {
      window.jeeP = this
      this.loadStart = 0
      this.loadOffset = 35
      if (user_isAdmin != 1) {
        document.querySelector('.bt_configureCmd')?.remove()
        document.querySelector('.bt_gotoScenario')?.remove()
      }
    },
    displayTimeline: function() {
      document.querySelector('#timelineContainer #events').empty()
      jeeP.displayTimelineSegment(jeeP.loadStart, jeeP.loadOffset)
    },
    displayTimelineSegment: function(_start, _offset) {
      if (!isset(_start)) _start = jeeP.loadStart
      if (!isset(_offset)) _offset = jeeP.loadOffset

      jeedom.timeline.byFolder({
        folder: document.getElementById('sel_timelineFolder').value,
        start: _start,
        offset: _offset,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          if (data.length == 0) return
          data.sort(jeeP.sortByDateConsistentASC)
          data = data.reverse()
          var dataLength = data.length
          var decayFactor = 130

          moment.locale(jeeFrontEnd.language.substring(0, 2))

          var lastEvent = document.querySelector('#timelineContainer #events li.event:last-child')
          if (lastEvent != null) {
            var isFirstOfDay = data[0].date.substring(0, 10) != lastEvent.querySelector('div.date').innerHTML.substring(4) ? true : false
            var isLastOfDay = false
            var prevDate = moment(lastEvent.querySelector('div.date').innerHTML, 'ddd  YYYY-MM-DD').format("YYYY-MM-DD")
            var prevDateTs = moment(prevDate + ' ' + lastEvent.querySelector('div.time').innerHTML, "YYYY-MM-DD hh:mm:ss").unix()
            var content = ''
          } else {
            var isFirstOfDay, isLastOfDay = false
            var nextDate, thisDateTs = false
            var prevDate = moment().format("YYYY-MM-DD")
            var prevDateTs = moment().unix()
            var content = '<div class="label-warning day">' + moment(data[0].date).format('ddd YYYY-MM-DD') + '</div>'
          }

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

            if (lastEvent == null) {
              //actual time marker:
              if (i == 0) {
                li = '<li style="background-color:transparent!important;">'
                li += '<div class="time typeInfo">' + moment().format('HH:mm:ss') + '</div>'
                li += '<div class="date">' + dateFull + '</div>'
                li += '</li>'
                content += li
              }
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
            if (style != '') {
              li = '<li class="event" style="' + style + '">'
            } else {
              li = '<li class="event">'
            }

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
          document.querySelector('#timelineContainer #events').insertAdjacentHTML('beforeend', content)
          //$('#timelineContainer ul').empty().append(content)

          jeeP.isScrolling = false
          document.getElementById('timelineBottom').seen()
        }
      })

    },
    sortByDateConsistentASC: function(itemA, itemB) {
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
    },
  }
}

jeeFrontEnd.timeline.init()

//searching
$('#in_searchTimeline').keyup(function() {
  var search = this.value
  if (search == '') {
    $('#timelineContainer > ul > li').show()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  $('#timelineContainer > ul > li').hide()
  var match, text
  $('#timelineContainer > ul > li').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).find('.tml-cmd').text())
    if (text.includes(search)) {
      match = true
    }
    text = jeedomUtils.normTextLower($(this).find('.type').text())
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      $(this).show()
    }
  })
})
$('#bt_resetTimelineSearch').on('click', function() {
  $('#in_searchTimeline').val('').keyup()
})

$('#bt_openCmdHistoryConfigure').on('click', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Configuration de l'historique des commandes}}",
    contentUrl: 'index.php?v=d&modal=cmd.configureHistory'
  })
})

$('#sel_timelineFolder').off('change').on('change', function() {
  jeeP.displayTimeline()
})

$('#bt_removeTimelineEvent').on('click', function() {
  jeedom.timeline.deleteAll({
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      jeedomUtils.showAlert({
        message: '{{Ev ements de la timeline supprim  avec succ }}',
        level: 'success'
      })
      jeeP.displayTimeline()
    }
  })
})

$('#bt_tabTimeline').on('click', function() {
  jeedomUtils.hideAlert()
  jeeP.displayTimeline()
})

$('#timelineContainer ul').on('click', '.bt_scenarioLog', function() {
  jeeDialog.dialog({
    id: 'jee_modal',
    title: "{{Log d'exécution du scénario}}",
    contentUrl: 'index.php?v=d&modal=scenario.log.execution&scenario_id=' + this.closest('.tml-scenario').getAttribute('data-id')
  })
})

$('#timelineContainer ul').on('click', '.bt_gotoScenario', function() {
  jeedomUtils.loadPage('index.php?v=d&p=scenario&id=' + $(this).closest('.tml-scenario').attr('data-id'))
})

$('#timelineContainer ul').on('click', '.bt_historicCmd', function() {
  jeeDialog.dialog({
    id: 'jee_modal2',
    title: "{{Historique}}",
    contentUrl: 'index.php?v=d&modal=cmd.history&id=' + this.closest('.tml-cmd').getAttribute('data-id')
  })
})

$('#timelineContainer ul').on('click', '.bt_configureCmd', function() {
  jeeDialog.dialog({
    id: 'jee_modal2',
    title: '{{Configuration de la commande}}',
    contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + this.closest('.tml-cmd').getAttribute('data-id')
  })
})

$('#bt_refreshTimeline').on('click', function() {
  jeeP.loadStart = 0
  jeeP.loadOffset = 35
  jeeP.displayTimeline()
})

$('#timelineBottom a.bt_loadMore').on('click', function() {
  var more = parseInt($(this).attr('data-load'))
  jeeP.loadStart = jeeP.loadStart + jeeP.loadOffset + 1
  jeeP.loadOffset = more
  jeeP.displayTimelineSegment(jeeP.loadStart, jeeP.loadOffset)
})

document.getElementById('div_mainContainer').registerEvent('scroll', function (event) {
  if (jeeP == undefined || jeeP.isScrolling) return
  let container = document.getElementById('div_mainContainer')
  if (container.scrollTop >= container.scrollHeight - window.innerHeight) {
    jeeP.isScrolling = true
    jeeP.loadStart = jeeP.loadStart + jeeP.loadOffset + 1
    jeeP.loadOffset = 50
    jeeP.displayTimelineSegment(jeeP.loadStart, jeeP.loadOffset)
  }
})

jeeP.displayTimeline()

//Register events on top of page container:

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/