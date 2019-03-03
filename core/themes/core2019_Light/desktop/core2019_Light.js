$(function () {
  if(typeof theme_config != 'undefined'){
    if(typeof theme_config['widget::shadow'] != 'undefined' && theme_config['widget::shadow'] == '1'){
      var style = '<style>';
      style += '.eqLogic-widget:hover,.scenario-widget:hover {';
      style += 'box-shadow: 0 6px 22px rgba(0,0,0,0.25);';
      style += 'z-index: 5;';
      style += '}';
      style += '.eqLogic-widget,.scenario-widget {';
      style += 'transition: box-shadow 0.4s cubic-bezier(.25,.8,.25,1);';
      style += '}';
      style += '</style>';
      $('html > head').append($(style));
    }
  }
  
});
