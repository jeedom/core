"use strict"

$('body').attr('data-page', 'timeline')

function initTimeline() {
  window.loadStart = 0
  window.loadOffset = 35
  jeedom.timeline.listFolder({
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      var rightPanel = '<ul data-role="listview" class="ui-icon-alt">'
      rightPanel += '<li><a id="bt_refreshTimeline" href="#"><i class="fas fa-sync"></i> {{Rafraîchir}}</a></li>'
      rightPanel += '<li><a class="changeTimelineFolder active" href="#" data-value="main">{{Principal}}</a></li>'
      rightPanel += '<span class="ui-bottom-sheet-link ui-bottom-sheet-sep">&#160&#160&#160 {{Timelines}} <i class="fas fa-sort-down"></i></span>'
      for (var i in data) {
        if (data[i] == 'main') continue
        rightPanel += '<li><a class="changeTimelineFolder" href="#" data-value="'+data[i]+'">'+data[i]+'</a></li>'
      }
      rightPanel += '</ul>'
      jeedomUtils.loadPanel(rightPanel)
      $('#bottompanel a.changeTimelineFolder').off('click').on('click',function() {
        $('#bottompanel a.changeTimelineFolder').removeClass('active')
        $(this).addClass('active')
        $('#bottompanel').panel('close')
        window.loadStart = 0
        window.loadOffset = 35
        displayTimeline(window.loadStart, window.loadOffset)
      })

      $('#bt_refreshTimeline').on('click',function() {
        $('#bottompanel').panel('close')
        window.loadStart = 0
        window.loadOffset = 35
        displayTimeline(window.loadStart, window.loadOffset)
      })

      $('#timelineBottom a.bt_loadMore').on('click', function() {
        var more = parseInt($(this).attr('data-load'))
        window.loadStart = window.loadStart + window.loadOffset + 1
        window.loadOffset = more
        displayTimelineSegment(window.loadStart, window.loadOffset)
      })


      window.onscroll = function(event) {
        if (window.isScrolling) return
        if (window.scrollY >= document.getElementById('timelineContainer').scrollHeight - window.innerHeight) {
          window.isScrolling = true
          window.loadStart = window.loadStart + window.loadOffset + 1
          window.loadOffset = 50
          displayTimelineSegment(window.loadStart, window.loadOffset)
        }
      }

      displayTimeline(window.loadStart, window.loadOffset)
    }
  })
}



//Nearly same functions desktop/mobile apart jeeP, folder selector, dateFull, window.isScrolling:
function displayTimeline() {
  document.querySelector('#timelineContainer #events').empty()
  displayTimelineSegment(window.loadStart, window.loadOffset)
}
function displayTimelineSegment(_start, _offset) {
  if (!isset(_start)) _start = jeeP.loadStart
  if (!isset(_offset)) _offset = jeeP.loadOffset

  jeedom.timeline.byFolder({
    folder: document.querySelector('#bottompanel .changeTimelineFolder.active').getAttribute('data-value'),
    start: _start,
    offset: _offset,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      if (data.length == 0) return
      data.sort(sortByDateConsistentASC)
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

      var thisData, date, time, lineClass, style, height, li
      for (var i in data) {
        thisData = data[i]
        date = thisData.date.substring(0, 10)
        time = thisData.date.substring(11, 19)
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
            li += '<div class="date">' + date + '</div>'
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
        li += '<div class="date">' + date + '</div>'

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

      window.isScrolling = false
      document.getElementById('timelineBottom').seen()
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