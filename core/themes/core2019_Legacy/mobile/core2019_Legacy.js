$(function () {
  var widget_shadow = true
  if(typeof jeedom.theme != 'undefined'){
    if(typeof jeedom.theme['widget::shadow'] != 'undefined' && jeedom.theme['widget::shadow'] == '1'){
      widget_shadow = false
    }
  }
  if(widget_shadow){
    themePath = 'core/themes/' + jeedom.theme['default_bootstrap_theme'] + '/mobile'
    $('head').append('<link rel="stylesheet" type="text/css" href="'+themePath+'/shadows.css">');  }
})
