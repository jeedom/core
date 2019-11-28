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
      displayTimeline()
    }
  })
  
  $('#bt_refreshTimeline').on('click',function(){
    displayTimeline()
  })
}


function displayTimeline(_folder){
  jeedom.timeline.byFolder({
    folder : $('.changeTimelineFolder.active').attr('data-value'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (data) {
      var key = 0
      data.forEach(function(element) {
        data.key = key
        key ++
      })
      data.sort(sortByDateConsistentASC)
      data.reverse()
      var tr = ''
      for (var i in data) {
        if (!data[i].date) continue
        tr += '<tr>'
        tr += '<td>'
        tr += data[i].date
        tr += '</td>'
        tr += '<td>'
        if (data[i].group && data[i].plugins) {
          if (data[i].group == 'action') {
            tr += '<i class="warning fas fa-terminal"></i>'
          } else {
            tr += '<i class="info fas fa-info-circle"></i>'
          }
        } else {
          tr += '<i class="success jeedom-clap_cinema"></i>'
        }
        tr += '</td>'
        tr += '<td>'
        tr += data[i].html
        tr += '</td>'
        tr += '</tr>'
      }
      $('#table_timeline tbody').empty().append(tr)
      $('#table_timeline .pull-right').remove()
      sepDays()
    }
  });
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

function sepDays() {
  prevDate = ''
  $('#table_timeline tbody tr').each(function() {
    thisDate = $(this).text().substring(0,10)
    if (thisDate != prevDate) {
      $(this).addClass('sepDay')
    } else {
      $(this).removeClass('sepDay')
    }
    prevDate = thisDate
  })
}
