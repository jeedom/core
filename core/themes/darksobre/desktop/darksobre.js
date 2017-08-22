Highcharts.setOptions({colors: ['#7cb5ec',  '#90ed7d', '#f7a35c', '#8085e9','#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1']});

/* DARK SOBRE JS v0.9.91 by dJuL */

if ($.type(dark_sobre) !== "object") {
  var dark_sobre = { config: {} };
}
else if ($.type(dark_sobre.config) !== "object") {
  dark_sobre.config = {};
}

dark_sobre.config = $.extend(true, {
  
    general: {
      background: 'core/themes/darksobre/desktop/background.jpg' //Image de fond du thème
    },
    
    dashboard: {
      widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
      removeBackground: false //mettre à true pour ne pas afficher le papier peint sur le dashboard, DEFAUT: false
    },
     
    view: {
      widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
      removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les vues, DEFAUT: false
    },
    
    plan: {
      widgetShadow: false , //mettre à true pour activer l'ombre des widgets, DEFAUT: false
      removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les designs, DEFAUT: false
    },
    
    panel: {
      widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
      removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les panels, DEFAUT: false
    },
         
    advanced: {
      opacity: 0.4 , //opacité du fond des tuiles du résumé domotique, entre 0 et 1, DEFAUT: 0.4
      duration_fadeIn: 300 //durée du fondu d'apparition des pages (1000 corespond à 1 seconde), 0 pour désactiver, DEFAUT: 300
    }

}, dark_sobre.config);

dark_sobre.setOpacity = function() {
  var el = $(this);
  var background = el.css('background-color');
  if (background.indexOf('rgba') < 0) {
    el.css('background-color', background.replace(')', ', ' + dark_sobre.config.advanced.opacity + ')').replace('rgb', 'rgba')).change(dark_sobre.setOpacity);
  }
  dark_sobre.showContent();
};
dark_sobre.showContent = function() {
  if (dark_sobre.config.advanced.duration_fadeIn > 0) {
    $('#div_mainContainer').animate({opacity: 100}, dark_sobre.config.advanced.duration_fadeIn * 10);
  }
  else {
    $('#div_mainContainer').css('opacity', 100);
  }
};
dark_sobre.init = function() {
  var autoResizeEls = $('#div_pageContainer').children('div.row.row-overflow').children();
  function autoResize() {
    autoResizeEls.each(function() {
      var el = $(this);
      el.css('height', $(window).height() - 42);
    });
  }
  $(window).on('resize', function() {
    autoResize();
    setTimeout(autoResize);
  });
  dark_sobre.onInit();
};
dark_sobre.onInit = function() { 
  dark_sobre.showContent();
};
(function() {
  dark_sobre.style = '<style type="text/css">';
  dark_sobre.style+= '#div_mainContainer{opacity:0}';
  if (dark_sobre.config.general.background != 'core/themes/darksobre/desktop/background.jpg') {
    dark_sobre.style+= 'body,header{background-image:url(' + dark_sobre.config.general.background + ')!important}';
  }
  var type = null;
  var url = window.location.href;
  if (url.indexOf("p=dashboard") > -1) {
    type = 'dashboard';
  }
  else if (url.indexOf("p=view") > -1) {
    type = 'view';
  }
  else if (url.indexOf("p=plan") > -1) {
    type = 'plan';
  }
  else if (url.indexOf("p=panel") > -1) {
   type = 'panel';
  }
  else if (url.indexOf("p=display") > -1) {
    dark_sobre.onInit = function() { 
      $('div.col-xs-4.object.col-xs-height').each(dark_sobre.setOpacity);
    };
  }
  if (type && dark_sobre.config[type].removeBackground) {
     dark_sobre.style+= 'body{background-image:none!important}';
  }
  dark_sobre.style+= '.eqLogic-widget{box-shadow:'+ ((type && dark_sobre.config[type].widgetShadow) ?
   '0 2px 2px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12)' : 'none') + '!important}</style>';
  $(dark_sobre.style).appendTo($('head'));
})();
$(document).ready(dark_sobre.init);
