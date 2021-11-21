"use strict"

$('body').attr('data-page', 'scenario')

function initScenario() {
  $('#searchContainer').show()
  jeedom.scenario.toHtml({
    id: 'all',
    version: 'mobile',
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(htmls) {
      $.clearDivContent('div_displayScenario')
      //get groups
      var scenarioGroups = []
      var group
      htmls.forEach(function(html) {
        group = $(html).data('group')
        if (group == "") group = '{{Aucun}}'
        group = group[0].toUpperCase() + group.slice(1)
        scenarioGroups.push(group)
      })
      scenarioGroups = Array.from(new Set(scenarioGroups))
      scenarioGroups.sort()

      //set each group:
      var fullDiv = ''
      var inner, nbr
      scenarioGroups.forEach(function(group) {
        if (group != '{{Aucun}}') {
          return
        }
        inner = ''
        nbr = 0
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

      $('#div_displayScenario').html(fullDiv).trigger('create')

      //size and pack:
      setTimeout(function() {
        deviceInfo = getDeviceType()
        jeedomUtils.setTileSize('.scenario')
      }, 100)
    }
  })

  $('#div_displayScenario').on({
    'click': function(event) {
      var toggle = true
      if ($(this).next(".groupContainer").is(":visible")) toggle = false
      $('.groupContainer').hide()
      if (toggle) $(this).next('.groupContainer').show()
    }
  }, '.toggleShowGroup')

  $('body').on('orientationChanged', function(event, _orientation) {
    deviceInfo = getDeviceType()
    jeedomUtils.setTileSize('.scenario')
  })

  //searching:
  $('#in_searchDashboard').off('keyup').on('keyup',function() {
    $('.groupContainer').hide()
    var search = $(this).value()
    search = jeedomUtils.normTextLower(search)
    if (search == '') {
      $('.scenario-widget').show()
      return
    }
    var match, text
    $('.scenario-widget').each(function() {
      match = false
      text = jeedomUtils.normTextLower($(this).find('.widget-name').text())
      if (match || text.indexOf(search) >= 0) {
        match = true;
      }
      if (match) {
        $(this).show()
        $(this).closest(".groupContainer").show()
      } else {
        $(this).hide()
      }
    })
  })

  $('#bt_eraseSearchInput').off('click').on('click',function() {
    $('#in_searchDashboard').val('').keyup()
  })
}