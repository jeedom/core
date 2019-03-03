$(function () {
  var style = '<style>';
  var widget_shadow = true;
  if(typeof jeedom.theme != 'undefined'){
    if(typeof jeedom.theme['widget::shadow'] != 'undefined' && jeedom.theme['widget::shadow'] == '1'){
      widget_shadow = false;
    }
  }
  if(widget_shadow){
    style += '.eqLogic-widget,.scenario-widget {';
    style += 'box-shadow: 0 2px 2px rgba(0,0,0,0.15);';
    style += 'z-index: 0;';
    style += 'transition: box-shadow 0.4s cubic-bezier(.25,.8,.25,1);';
    style += '}';
    
    style += '.eqLogic-widget:hover,.scenario-widget:hover {';
    style += 'box-shadow: 0 6px 22px rgba(0,0,0,0.25);';
    style += 'z-index: 5;';
    style += '}';
  }
  
  style += '</style>';
  $('html > head').append($(style));
});
