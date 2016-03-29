/* DARK SOBRE JS BETA v0.9.3 */
$(document).ready(function() {
   function dark_sobre_setOpacity() {
      var el = $(this);
      var background = el.css('background-color');
      if (background.indexOf('rgba') < 0) {
         el.css('background-color', background.replace(')', ', 0.4)').replace('rgb', 'rgba'));
      }
   }
   var dark_sobre_observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
         var el = $(mutation.target);
         if (mutation.type == "attributes" && el.hasClass('eqLogic-widget')) {
            dark_sobre_setOpacity.bind(el[0])();
         }
      })
   });
   function dark_sobre_observe() {
      dark_sobre_observer.observe(this, {
         attributes: true,
         subtree: true,
         attributeFilter: ['style']
      });
   }
   if (window.location.href.indexOf("p=dashboard") > -1) {
      $('.div_displayEquipement').each(dark_sobre_observe);
   }
   else if (window.location.href.indexOf("p=view") > -1) {
      $('.div_displayView').each(dark_sobre_observe);
   }
   else if (window.location.href.indexOf("p=display") > -1) {
      $('div.col-xs-4.object.col-xs-height').each(dark_sobre_setOpacity);
   }
   setTimeout(function() {
      $('#div_mainContainer').hide().css('visibility', 'visible').fadeIn(300);
   }, 10);
});