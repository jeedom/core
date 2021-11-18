"use strict"

$('body').attr('data-page', 'timeline')

// dates are sorted on desktop by tablesorter. Mobile sort them with 3rdparty momentJs

function initTimeline() {
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
      $('.changeTimelineFolder').off('click').on('click',function() {
        $('.changeTimelineFolder').removeClass('active')
        $(this).addClass('active')
        $('#bottompanel').panel('close')
        displayTimeline()
      })
      $('#bt_refreshTimeline').on('click',function() {
        $('#bottompanel').panel('close')
        displayTimeline()
      })
      displayTimeline()
    }
  })
}

//exact same success function desktop/mobile:
function displayTimeline() {
  jeedom.timeline.byFolder({
    folder : $('.changeTimelineFolder.active').attr('data-value'),
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      if (data.length == 0) return
      data.sort(sortByDateConsistentASC)
      data = data.reverse()
      var dataLength = data.length
      var decayFactor = 130

      var isFirstOfDay, isLastOfDay = false
      var nextDate, thisDateTs = false
      var prevDate = moment().format("YYYY-MM-DD")
      var prevDateTs = moment().unix()
      var content = '<div class="label-warning day">'+data[0].date.substring(0,10)+'</div>'

      var thisData, date, time, lineClass, style, height, li
      for (var i in data) {
        thisData = data[i]
        date = thisData.date.substring(0,10)
        time = thisData.date.substring(11,19)
        thisDateTs = moment(thisData.date.substring(0,19)).unix()
        lineClass = ''

        if (prevDate != date) {
          isFirstOfDay = true
          prevDateTs = moment(prevDate + ' 00:00:00').unix()
        } else {
          if (i < dataLength -1) {
            nextDate = data[parseInt(i)+1].date.substring(0,10)
            if (date != nextDate) {
              isLastOfDay = true
            }
          }
        }

        //actual time marker:
        if (i == 0) {
          li = '<li style="background-color:transparent!important;">'
          li += '<div class="time typeInfo">' + moment().format('HH:mm:ss') + '</div>'
          li += '<div class="date">' + date + '</div>'
          li += '</li>'
          content += li
        }

        //time spacing:
        style = ''
        height = Math.abs((prevDateTs - thisDateTs) / decayFactor)
        if (height > 5) {
          style = 'margin-top:'+height+'px!important;'
        }
        if (isLastOfDay && i < dataLength -1) {
          height = Math.abs((thisDateTs - moment(data[parseInt(i)+1].date.substring(0,19)).unix()) / decayFactor)
          style += 'margin-bottom:'+height+'px!important;'
        }
        li = '<li style="'+style+'">'
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
        li += '<div class="html">'+thisData.html+'</div>'

        li += '</div>'
        li += '<span class="vertLine '+lineClass+'"></span>'
        //time:
        li += '<div class="time '+lineClass+'">'+time+'</div>'

        //date:
        li += '<div class="date">'+date+'</div>'

        li += '</li>'
        content += li

        //newDay ?
        if (isLastOfDay) {
          content += '<div class="label-warning day">'+nextDate+'</div>'
        }

        prevDate = date
        prevDateTs = thisDateTs
        isFirstOfDay = isLastOfDay = false
      }
      $('#timelineContainer ul').empty().append(content)
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
    r = (typeof itemA.key !== 'undefined' && typeof itemB.key !== 'undefined')?
    itemA.key - itemB.key : 0
  }
  return r
}