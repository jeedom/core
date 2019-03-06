$(function () {
  var widget_shadow = true
  if(typeof jeedom.theme != 'undefined'){
    if(typeof jeedom.theme['shadow'] != 'undefined' && jeedom.theme['shadow'] == '1'){
      widget_shadow = false
    }
  }
  if(widget_shadow){
    themePath = 'core/themes/' + jeedom.theme['default_bootstrap_theme'] + '/desktop'
    $('head').append('<link rel="stylesheet" type="text/css" href="'+themePath+'/shadows.css">');  }
  })
  