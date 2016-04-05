/* DARK SOBRE JS BETA v0.9.4 */
var dark_sobre = {
  config : {
  
    dashboard : { //activé par défaut
      enable: true , //metre à false pour désactiver la transparence des widget sur le dashboard, DEFAUT: true
      removeBackground: false , //mettre à true pour ne pas afficher le papier peint sur le dashboard, DEFAUT: false
    },
    
    view : { //activé par défaut
      enable: true , //metre à false pour désactiver la transparence des widget dans les vues, DEFAUT: true
      removeBackground: false , //mettre à true pour ne pas afficher le papier peint dans les vues, DEFAUT: false
    },
    
    plan : { //désactivé par défaut
      enable: false , //metre à true pour activer la transparence des widget dans les designs, DEFAUT: false
      removeBackground: false , //mettre à true pour ne pas afficher le papier peint dans les designs, DEFAUT: false
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
      force_visible_delay: 1000, //ne pas toucher, delai avant affichage forcé de la page si aucun widget trouvé (bug chrome aléatoire), DEFAUT: 1000
    }

  }
};

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
  if (dark_sobre.config[type].removeBackground) {
    document.body.style.setProperty('background-image', 'none', 'important');
  }
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
      $('#div_mainContainer').hide().css('visibility', 'visible').fadeIn(dark_sobre.config.advanced.duration_fadeIn);
    }, dark_sobre.config.advanced.delay_fadeIn);
  }
};
dark_sobre.init = function() {
   if (window.location.href.indexOf("p=dashboard") > -1) {
      dark_sobre.process('.div_displayEquipement', 'dashboard');
   }
   else if (window.location.href.indexOf("p=view") > -1) {
      dark_sobre.process('.div_displayView', 'view');
   }
   else if (window.location.href.indexOf("p=plan") > -1) {
      dark_sobre.process('.div_displayObject', 'plan');
   }
   else if (window.location.href.indexOf("p=display") > -1) {
      $('div.col-xs-4.object.col-xs-height').each(dark_sobre.setOpacity);
   }
   else {
      dark_sobre.showContent();
   }
   setTimeout(dark_sobre.showContent, dark_sobre.config.advanced.force_visible_delay);
};
$(document).ready(dark_sobre.init);