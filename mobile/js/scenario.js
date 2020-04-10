$('body').attr('data-page', 'scenario')

function initScenario() {
  $('#searchContainer').show();
  jeedom.scenario.toHtml({
    id: 'all',
    version: 'mobile',
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (htmls) {
      //get groups
      var scenarioGroups = []
      htmls.forEach(function(html) {
        var group = $(html).data('group')
        if (group == "") group = '{{Aucun}}'
        group = group[0].toUpperCase() + group.slice(1)
        scenarioGroups.push(group)
      })
      scenarioGroups = Array.from(new Set(scenarioGroups))
      scenarioGroups.sort()

      //set each group:
      var fullDiv = ''
      scenarioGroups.forEach(function(group) {
        if (group != '{{Aucun}}') {
          return
        }
        var inner = ''
        var nbr = 0
        htmls.forEach(function(html) {
          if ($(html).data('group') == "") {
            inner += "\n"+html
            nbr += 1
          }
        })
        fullDiv += '<legend class="toggleShowGroup cursor">' + group + ' <sup>('+nbr+')</sup></legend>'
        fullDiv += '<div class="groupContainer" style="display:none">'
        fullDiv += inner
        fullDiv += '\n</div>'
      })

      scenarioGroups.forEach(function(group) {
        if (group == '{{Aucun}}') {
          return
        }
        var inner = ''
        var nbr = 0
        htmls.forEach(function(html) {
          if ($(html).data('group').toLowerCase() == group.toLowerCase()) {
            inner += "\n"+html
            nbr += 1
          }
        })
        fullDiv += '<legend class="toggleShowGroup cursor">' + group + ' <sup>('+nbr+')</sup></legend>'
        fullDiv += '<div class="groupContainer" style="display:none">'
        fullDiv += inner
        fullDiv += '\n</div>'
      })

      $('#div_displayScenario').empty().html(fullDiv).trigger('create')

      //size and pack:
      setTimeout(function () {
        deviceInfo = getDeviceType()
        setTileSize('.scenario')
        $('#div_displayScenario').packery({gutter : 0})
      }, 100)

    }
  })

  $('#div_displayScenario').delegate('.toggleShowGroup', 'click', function() {
    var toggle = true
    if ($(this).next(".groupContainer").is(":visible")) toggle = false
    $('.groupContainer').hide()
    if (toggle) $(this).next(".groupContainer").show()
    setTimeout(function () {
        $('#div_displayScenario').packery({gutter : 0})
      }, 100)
  })

  $('body').on('orientationChanged', function (event, _orientation) {
    deviceInfo = getDeviceType()
    setTileSize('.scenario')
    $('#div_displayScenario').packery({gutter : 0})
  })

  //searching:
  $('#in_searchWidget').off('keyup').on('keyup',function() {
    $('.groupContainer').hide()
    var search = $(this).value()
    search = normTextLower(search)
    if(search == ''){
      $('.scenario-widget').show()
      $('#div_displayScenario').packery()
      return
    }
    $('.scenario-widget').each(function(){
      var match = false
      text = normTextLower($(this).find('.widget-name').text())
      if(match || text.indexOf(search) >= 0){
        match = true;
      }
      if(match){
        $(this).show()
        $(this).closest(".groupContainer").show()
      }else{
        $(this).hide()
      }
    })
    $('#div_displayScenario').packery()
  })

  $('#bt_eraseSearchInput').off('click').on('click',function(){
    $('#in_searchWidget').val('').keyup()
  })

}
