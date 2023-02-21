"use strict"

$('body').attr('data-page', 'view')

if (!jeeFrontEnd.view) {
  jeeFrontEnd.view = {
    init: function() {
      window.jeeP = this
    },
  }
}

function initView(_view_id) {
  jeedom.view.all({
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(views) {
      var li = ' <ul data-role="listview">'
      var icon
      for (var i in views) {
        icon = ''
        if (isset(views[i].display) && isset(views[i].display.icon)) {
          icon = views[i].display.icon
        }
        li += '<li><a href="#" class="link" data-page="view" data-title="'+ icon.replace(/\"/g, "\'") + ' ' + views[i].name + '" data-option="' + views[i].id + '">'+ icon + ' ' + views[i].name + '</a></li>'
      }
      li += '</ul>'
      jeedomUtils.loadPanel(li)
    }
  })

  if (isset(_view_id) && is_numeric(_view_id)) {
    jeedom.history.chart = []
    jeedom.view.toHtml({
      id: _view_id,
      version: 'mobile',
      error: function(error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function(html) {
        displayView(html)
        //draw graphs:
        $('.chartToDraw').each(function() {
          $(this).find('.viewZoneData').each(function() {
            var cmdId = $(this).attr('data-cmdid')
            var el = $(this).attr('data-el')
            var options = json_decode($(this).attr('data-option').replace(/'/g, '"'))
            var height = $(this).attr('data-height')
            jeedom.history.drawChart({
              cmd_id: cmdId,
              el: el,
              height: height != '' ? height : null,
              dateRange: $(this).attr('data-daterange'),
              option: options,
              success: function(data) {
                $('.chartToDraw > .viewZoneData[data-cmdid="' + cmdId + '"]').remove()
              }
            })
          })
        })
      }
    })
  } else {
    $('#bottompanel').panel('open')
  }

  $(window).on("resize", function(event) {
    setTimeout(function(){
      if (jeedomUtils.userDevice.type == 'phone') {
        $('.chartContainer').width((jeedomUtils.userDevice.width - 20))
      } else {
        $('.chartContainer').width(((jeedomUtils.userDevice.width / 2) - 20))
      }
      jeedomUtils.setTileSize('.eqLogic, .scenario')
      $('.eqLogicZone').packery({gutter : 0})
      var screenWidth = $(window).width() - 5
      $('.div_viewZone .table-responsive').each(function(){
        $(this).width('auto').css('max-width','none')
        if ($(this).width() < screenWidth) {
          $(this).width(screenWidth)
        } else {
          $(this).css({'overflow':'auto', 'max-width':screenWidth+'px'})
        }
      })
    }, 50)
  })
}

function displayView(html) {
  if (isset(html.raw) && isset(html.raw.img) && html.raw.img != '') {
    jeedomUtils.setBackgroundImage(html.raw.img)
  } else {
    jeedomUtils.setBackgroundImage('')
  }
  try {
    document.getElementById('div_displayView').empty()
    $('#div_displayView').html(html.html).trigger('create')
  } catch(err) {
    console.log(err)
  }
  if (isset(html.raw) && isset(html.raw.configuration) && isset(html.raw.configuration.displayObjectName) && html.raw.configuration.displayObjectName == 1) {
    $('.eqLogic-widget, .scenario-widget').addClass('displayObjectName')
  }
  if (jeedomUtils.userDevice.type == 'phone') {
    $('.chartContainer').width((jeedomUtils.userDevice.width - 20))
  } else {
    $('.chartContainer').width(((jeedomUtils.userDevice.width / 2) - 20))
  }
  jeedomUtils.setTileSize('.eqLogic, .scenario')
  $('.eqLogicZone').packery({gutter : 0})
  setTimeout(function(){
    $('.eqLogicZone').packery({gutter : 0})
  }, 750)
  $('#div_displayView .ui-table-columntoggle-btn').remove()
  var screenWidth = $(window).width() - 5
  $('.div_viewZone .table-responsive').each(function() {
    if ($(this).width() < screenWidth) {
      $(this).width(screenWidth)
    } else {
      $(this).css({'overflow':'auto', 'max-width':screenWidth+'px'})
    }
  })
}