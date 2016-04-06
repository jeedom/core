/* DARK SOBRE JS BETA v0.9.6 by dJuL */

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
  
  dashboard : { //activé par défaut
    enable: true , //metre à false pour désactiver la transparence des widgets sur le dashboard, DEFAUT: true
    widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
    removeBackground: false //mettre à true pour ne pas afficher le papier peint sur le dashboard, DEFAUT: false
  },
   
  view : { //activé par défaut
    enable: true , //metre à false pour désactiver la transparence des widgets dans les vues, DEFAUT: true
    widgetShadow: true , //mettre à false pour désactiver l'ombre des widgets, DEFAUT: true
    removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les vues, DEFAUT: false
  },
  
  plan : { //désactivé par défaut
    enable: false , //metre à true pour activer la transparence des widgets dans les designs, DEFAUT: false
    widgetShadow: false , //mettre à true pour activer l'ombre des widgets, DEFAUT: false
    removeBackground: false //mettre à true pour ne pas afficher le papier peint dans les designs, DEFAUT: false
  },
    
  delay : { //désactivé par défaut
    enable: false , //Mettre à true pour utiliser les delais (pb de transparence des tuiles parfois avec chrome), DEFAUT: false
    local_adress: '192.168.0.1' , //entrez votre adresse locale, ex:'192.168.0.1' ou 'jeedom.local'
    local_timeout: 200 ,  //delay en locale (1000 corespond à 1 seconde), DEFAUT: 200
    external_timeout: 800 //delay lors d'un accès distant (1000 corespond à 1 seconde), DEFAUT: 800
  },
    
  advanced : {
    opacity : 0.4 , //opacité du fond des tuiles, entre 0 et 1, DEFAUT: 0.4
    duration_fadeIn : 300 , //durée du fondu d'apparition des pages (1000 corespond à 1 seconde), 0 pour désactiver, DEFAUT: 300
    delay_fadeIn : 10 , //ne pas toucher, delai avant déclanchement du fade, DEFAUT: 10
    force_visible_delay: 1000 //doit être > delay external_timeout (si delay enable). delai avant affichage forcé de la page si aucun widget trouvé (bug chrome aléatoire), DEFAUT: 1000
  }

}, dark_sobre.config);

dark_sobre.setOpacity = function() {
  var el = $(this);
  var background = el.css('background-color');
  if (background.indexOf('rgba') < 0) {
    el.css('background-color', background.replace(')', ', ' + dark_sobre.config.advanced.opacity + ')').replace('rgb', 'rgba'));
  }
  dark_sobre.showContent();
};
dark_sobre.observer = new MutationObserver(function(mutations) {
  mutations.forEach(function(mutation) {
    var el = $(mutation.target);
    if (mutation.type == "attributes" && el.hasClass('eqLogic-widget')) {
      dark_sobre.setOpacity.bind(el[0])();
    }
  })   
});
dark_sobre.observe = function() {
  dark_sobre.observer.observe(this, {
    attributes: true,
    subtree: true,
    attributeFilter: ['style']
  });
};
dark_sobre.delayProcess = function(selector) {
  if (!dark_sobre.config.delay.enable) return;
  var delay = window.location.href.indexOf(dark_sobre.config.delay.local_adress > -1) ? 
    dark_sobre.config.delay.local_timeout : dark_sobre.config.delay.external_timeout;
  setTimeout((function() {
      $(selector).each(dark_sobre.setOpacity);
  }), delay); 
};
dark_sobre.process = function(selector, type) {
  if (dark_sobre.config[type].enable) {
    $(selector).each(dark_sobre.observe);
    dark_sobre.delayProcess('.eqLogic-widget');
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
   setTimeout(dark_sobre.showContent, dark_sobre.config.advanced.force_visible_delay);
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
  if (window.location.href.indexOf("p=dashboard") > -1) {
      type = 'dashboard';
      dark_sobre.onInit = function() { 
        dark_sobre.process('.div_displayEquipement', type);
      };
   }
   else if (window.location.href.indexOf("p=view") > -1) {
      type = 'view';
      dark_sobre.onInit = function() { 
        dark_sobre.process('.div_displayView', type);
      };
   }
   else if (window.location.href.indexOf("p=plan") > -1) {
      type = 'plan';
      dark_sobre.onInit = function() { 
        dark_sobre.process('.div_displayObject', type);
      };
   }
   else if (window.location.href.indexOf("p=display") > -1) {
      dark_sobre.onInit = function() { 
        $('div.col-xs-4.object.col-xs-height').each(dark_sobre.setOpacity);
      };
   }
   if (type && dark_sobre.config[type].removeBackground) {
       dark_sobre.style+= 'body{background-image:none!important}';
   }
   if (type && dark_sobre.config[type].widgetShadow) {
       dark_sobre.style+= '.eqLogic-widget{box-shadow:0 2px 2px 0 rgba(0, 0, 0, 0.16),0 2px 10px 0 rgba(0, 0, 0, 0.12)!important}';
   }
   dark_sobre.style+= '</style>';
   $(dark_sobre.style).appendTo($('head'));
})();
$(document).ready(dark_sobre.init);
