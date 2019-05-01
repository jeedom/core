$(function () {
  var widget_shadow = true
  var useAdvance = 0

  if(typeof jeedom.theme != 'undefined'){
    if(typeof jeedom.theme['interface::advance::enable'] != 'undefined'){
      useAdvance = parseInt(jeedom.theme['interface::advance::enable'])
    }
    if(typeof jeedom.theme['widget::shadow'] != 'undefined' && useAdvance == 1 && jeedom.theme['widget::shadow'] == '1'){
      widget_shadow = false
    }
  }

  if(widget_shadow){
    themePath = 'core/themes/' + jeedom.theme['default_bootstrap_theme'] + '/desktop'
    $('head').append('<link rel="stylesheet" type="text/css" href="'+themePath+'/shadows.css">');
  }
})
