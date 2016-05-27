/* DARK SOBRE JS v0.9.86 by dJuL */

if ($.type(darksobre) !== "object") {
  var darksobre = { config: {} };
}
else if ($.type(darksobre.config) !== "object") {
  darksobre.config = {};
}

darksobre.config = $.extend(true, {
  
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

}, darksobre.config);

darksobre.setOpacity = function() {
  var el = $(this);
  var background = el.css('background-color');
  if (background.indexOf('rgba') < 0) {
    el.css('background-color', background.replace(')', ', ' + darksobre.config.advanced.opacity + ')').replace('rgb', 'rgba')).change(darksobre.setOpacity);
  }
  darksobre.showContent();
};
darksobre.observer = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    for (var i = 0; i < mutation.addedNodes.length; i++) {
      darksobre.onMutationProcess(mutation.addedNodes[i]);
    }
  })   
});
darksobre.observe = function() {
  darksobre.observer.observe(this, {
    childList : true
  });
};
darksobre.delayProcess = function(selector, delay) {
  setTimeout((function() {
    $(selector).each(darksobre.setOpacity);
  }), delay); 
};
darksobre.process = function(selector, type) {
  if (darksobre.config[type].enable) {
    if (!darksobre.config.delay.enable) {
      $(selector).each(darksobre.observe);
      darksobre.delayProcess('.eqLogic-widget', darksobre.config.advanced.force_visible_delay);
    }
    else {
      darksobre.delayProcess('.eqLogic-widget', window.location.href.indexOf(darksobre.config.delay.local_adress > -1) ? 
        darksobre.config.delay.local_timeout : darksobre.config.delay.external_timeout);
    }
  }
  else {
    darksobre.showContent();
  }
};
darksobre.showContent = function() {
  if (!darksobre.contentVisible) {
    darksobre.contentVisible = true;
    setTimeout(function() {
      if (darksobre.config.advanced.duration_fadeIn > 0) {
        $('#div_mainContainer').hide().css('visibility', 'visible').fadeIn(darksobre.config.advanced.duration_fadeIn);
      }
      else {
        $('#div_mainContainer').css('visibility', 'visible');
      }
    }, darksobre.config.advanced.delay_fadeIn);
  }
};
darksobre.init = function() {
 darksobre.onInit();
 setTimeout(darksobre.showContent, darksobre.config.advanced.force_visible_delay + 100);
 setTimeout(darksobre.showContent, darksobre.config.advanced.force_visible_delay + 200);
};
darksobre.onInit = function() { 
 darksobre.showContent();
};
(function() {
  darksobre.style = '<style type="text/css">';
  darksobre.style+= '#div_mainContainer{visibility:hidden}';
  if (darksobre.config.general.background != 'core/themes/darksobre/desktop/background.jpg') {
    darksobre.style+= 'body,header{background-image:url(' + darksobre.config.general.background + ')!important}';
  }
  var type = null;
  type = 'dashboard';
  darksobre.onInit = function() { 
    darksobre.process('.div_displayEquipement', type);
  };
  darksobre.onMutationProcess = function(elMutation) { 
    if (elMutation.classList && elMutation.classList.contains("eqLogic-widget")) {
      darksobre.setOpacity.bind(elMutation)();
    }
  };
  if (type && darksobre.config[type].removeBackground) {
   darksobre.style+= 'body{background-image:none!important}';
 }
 darksobre.style+= '.eqLogic-widget{box-shadow:'+ ((type && darksobre.config[type].widgetShadow) ?
   '0 2px 2px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12)' : 'none') + '!important}</style>';
 $(darksobre.style).appendTo($('head'));
})();
$(document).ready(darksobre.init);
