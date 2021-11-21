"use strict"

$('body').attr('data-page', 'view')

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
      }
    })
  } else {
    $('#bottompanel').panel('open')
  }

  $(window).on("resize", function(event) {
    setTimeout(function(){
      if (deviceInfo.type == 'phone') {
        $('.chartContainer').width((deviceInfo.width - 20))
      } else {
        $('.chartContainer').width(((deviceInfo.width / 2) - 20))
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
    $.clearDivContent('div_displayView')
    $('#div_displayView').html(html.html).trigger('create')
  } catch(err) {
    console.log(err)
  }
  if (isset(html.raw) && isset(html.raw.configuration) && isset(html.raw.configuration.displayObjectName) && html.raw.configuration.displayObjectName == 1) {
    $('.eqLogic-widget, .scenario-widget').addClass('displayObjectName')
  }
  if (deviceInfo.type == 'phone') {
    $('.chartContainer').width((deviceInfo.width - 20))
  } else {
    $('.chartContainer').width(((deviceInfo.width / 2) - 20))
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