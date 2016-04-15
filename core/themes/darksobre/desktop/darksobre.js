/* DARK SOBRE JS v0.9.86 by dJuL */

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
    enable: true , //metre à false pour désactiver la transparence des widgets sur le dashboard, DEFAUT: true
    widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
    removeBackground: false //mettre à true pour ne pas afficher le papier peint sur le dashboard, DEFAUT: false
  },
   
  view: {
    enable: true , //metre à false pour désactiver la transparence des widgets dans les vues, DEFAUT: true
    widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
    removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les vues, DEFAUT: false
  },
  
  plan: {
    enable: false , //metre à true pour activer la transparence des widgets dans les designs, DEFAUT: false
    widgetShadow: false , //mettre à true pour activer l'ombre des widgets, DEFAUT: false
    removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les designs, DEFAUT: false
  },
  
  panel: {
    enableNames: [ //Mettre les noms de pannels où activer la transparence (se trouve après "m=" dans la barre d'adresse un fois le panel chargé
      'networks' ,
      'votre autre nom' ,
      'etc...'
    ] , 
    widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
    removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les panels, DEFAUT: false
  },
    
  delay: { //désactivé par défaut, à utiliser uniquement si votre navigateur crash
    enable: false , //Mettre à true pour utiliser les délais à la place de l'inspection des noeuds (si crash du navigateur), DEFAUT: false
    local_adress: '192.168.0.1' , //entrez votre adresse locale, ex:'192.168.0.1' ou 'jeedom.local'
    local_timeout: 250 ,  //délai en locale (1000 corespond à 1 seconde), DEFAUT: 250
    external_timeout: 800 //délai lors d'un accès distant (1000 corespond à 1 seconde), DEFAUT: 800
  },
    
  advanced: {
    opacity: 0.4 , //opacité du fond des tuiles, entre 0 et 1, DEFAUT: 0.4
    duration_fadeIn: 300 , //durée du fondu d'apparition des pages (1000 corespond à 1 seconde), 0 pour désactiver, DEFAUT: 300
    delay_fadeIn: 10 , //ne pas toucher, délai avant déclanchement du fade, DEFAUT: 10
    force_visible_delay: 1000 //ne pas réduire, délai avant transparence et affichage forcé de la page si aucun widget trouvé, DEFAUT: 1000
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
dark_sobre.observer = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    for (var i = 0; i < mutation.addedNodes.length; i++) {
      dark_sobre.onMutationProcess(mutation.addedNodes[i]);
    }
  })   
});
dark_sobre.observe = function() {
  dark_sobre.observer.observe(this, {
    childList : true
  });
};
dark_sobre.delayProcess = function(selector, delay) {
  setTimeout((function() {
      $(selector).each(dark_sobre.setOpacity);
  }), delay); 
};
dark_sobre.process = function(selector, type) {
  if (dark_sobre.config[type].enable) {
    if (!dark_sobre.config.delay.enable) {
      $(selector).each(dark_sobre.observe);
      dark_sobre.delayProcess('.eqLogic-widget', dark_sobre.config.advanced.force_visible_delay);
    }
    else {
      dark_sobre.delayProcess('.eqLogic-widget', window.location.href.indexOf(dark_sobre.config.delay.local_adress > -1) ? 
        dark_sobre.config.delay.local_timeout : dark_sobre.config.delay.external_timeout);
    }
  }
  else {
    dark_sobre.showContent();
  }
};
dark_sobre.showContent = function() {
  if (!dark_sobre.contentVisible) {
    dark_sobre.contentVisible = true;
    setTimeout(function() {
      if (dark_sobre.config.advanced.duration_fadeIn > 0) {
        $('#div_mainContainer').hide().css('visibility', 'visible').fadeIn(dark_sobre.config.advanced.duration_fadeIn);
      }
      else {
        $('#div_mainContainer').css('visibility', 'visible');
      }
    }, dark_sobre.config.advanced.delay_fadeIn);
  }
};
dark_sobre.init = function() {
   dark_sobre.onInit();
   setTimeout(dark_sobre.showContent, dark_sobre.config.advanced.force_visible_delay + 200);
};
dark_sobre.onInit = function() { 
   dark_sobre.showContent();
};
(function() {
  dark_sobre.style = '<style type="text/css">';
  dark_sobre.style+= '#div_mainContainer{visibility:hidden}';
  if (dark_sobre.config.general.background != 'core/themes/darksobre/desktop/background.jpg') {
    dark_sobre.style+= 'body,header{background-image:url(' + dark_sobre.config.general.background + ')!important}';
  }
  var type = null;
  var url = window.location.href;
  if (url.indexOf("p=dashboard") > -1) {
      type = 'dashboard';
      dark_sobre.onInit = function() { 
        dark_sobre.process('.div_displayEquipement', type);
      };
      dark_sobre.onMutationProcess = function(elMutation) { 
        if (elMutation.classList && elMutation.classList.contains("eqLogic-widget")) {
          dark_sobre.setOpacity.bind(elMutation)();
        }
      };
   }
   else if (url.indexOf("p=view") > -1) {
      type = 'view';
      dark_sobre.onInit = function() { 
        dark_sobre.process('.div_displayView', type);
      };
      dark_sobre.onMutationProcess = function(elMutation) { 
        var el = $(elMutation)
        if (el.hasClass("col-xs-3")) {
          el.find(".eqLogic-widget").each(dark_sobre.setOpacity);
        }
      };
   }
   else if (url.indexOf("p=plan") > -1) {
      type = 'plan';
      dark_sobre.onInit = function() { 
        dark_sobre.process('.div_displayObject', type);
      };
      dark_sobre.onMutationProcess = function(elMutation) { 
        if (elMutation.classList && elMutation.classList.contains("eqLogic-widget")) {
          dark_sobre.setOpacity.bind(elMutation)();
        }
      };
   }
   else if (url.indexOf("p=panel") > -1) {
     for (var i = 0; i < dark_sobre.config.panel.enableNames.length; i++) {
       if (url.indexOf("m=" + dark_sobre.config.panel.enableNames[i]) > -1) {
         type = 'panel';
         dark_sobre.config[type].enable = true;
         dark_sobre.onInit = function() { 
           dark_sobre.delayProcess('.eqLogic-widget', dark_sobre.config.advanced.force_visible_delay);
         };
         break;
       }
     }
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
