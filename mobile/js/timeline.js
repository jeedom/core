// dates are sorted on desktop by tablesorter. Mobile sort them with 3rdparty momentJs
$('body').attr('data-page', 'timeline')
function initTimeline() {

  jeedom.timeline.listFolder({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (data) {
      var rightPanel = '<ul data-role="listview" class="ui-icon-alt">'
      rightPanel += '<li><a id="bt_refreshTimeline" href="#"><i class="fas fa-sync"></i> {{Rafraîchir}}</a></li>'
      for(var i in data){
        if(data[i] == 'main'){
          rightPanel += '<li><a class="changeTimelineFolder active" href="#" data-value="'+data[i]+'">{{Principal}}</a></li>'
        }else{
          rightPanel += '<li><a class="changeTimelineFolder" href="#" data-value="'+data[i]+'">'+data[i]+'</a></li>'
        }
      }
      rightPanel += '</ul>'
      panel(rightPanel)
      $('.changeTimelineFolder').off('click').on('click',function(){
        $('.changeTimelineFolder').removeClass('active')
        $(this).addClass('active')
        displayTimeline()
      })
      $('#bt_refreshTimeline').on('click',function() {
        displayTimeline()
      })
      displayTimeline()
    }
  })
}

//exact same success function desktop/mobile:
function displayTimeline(_folder){
  jeedom.timeline.byFolder({
    folder : $('.changeTimelineFolder.active').attr('data-value'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (data) {
      data.sort(sortByDateConsistentASC)
      data = data.reverse()
      dataLength = data.length
      var content = ''

      var nextDate, thisDateTs, prevDateTs = false
      for (var i in data) {
        var thisData = data[i]
        var date = thisData.date.substring(0,10)
        var time = thisData.date.substring(11,19)
        thisDateTs = moment(thisData.date.substring(0,19)).unix()
        var lineClass = ''

        //time spacing:
        if (prevDateTs) {
          var height = Math.abs((prevDateTs - thisDateTs) / 130)
          if (height > 5) {
            var li = '<li style="margin-top:'+height+'px!important;">'
          } else {
            var li = '<li>'
          }
        } else {
          var li = '<li>'
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
        if (i == 0) {
          content = '<div class="label-warning day">'+date+'</div>' + content
        } else {
          if (i < dataLength -1) {
            nextDate = data[parseInt(i)+1].date.substring(0,10)
            if (date != nextDate) {
              content += '<div class="label-warning day">'+nextDate+'</div>'
            }
          }
        }
        prevDateTs = thisDateTs
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
  if(r === 0){
    r = (typeof itemA.key !== 'undefined' && typeof itemB.key !== 'undefined')?
    itemA.key - itemB.key : 0
  }
  return r
}