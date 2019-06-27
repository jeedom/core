/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

var JS_ERROR = [];
var BACKGROUND_IMG = '';
var PREVIOUS_PAGE = null;
var NO_POPSTAT = false;
var TOOLTIPSOPTIONS = {
  arrow: false,
  delay: 350,
  interactive: true,
  contentAsHTML: true
}

var __OBSERVER__ = null;
var _observerConfig_ = {
  attributes: true,
  childList: true,
  characterData: true,
  subtree: true
}

window.addEventListener('error', function (evt) {
  if(evt.filename.indexOf('file=3rdparty/') != -1){
    return;
  }
  JS_ERROR.push(evt)
  $('#bt_jsErrorModal').show();
  $.hideLoading();
});

uniqId_count = 0;
modifyWithoutSave = false;
nbActiveAjaxRequest = 0;
jeedomBackgroundImg = null;
utid = Date.now();

$(document).ajaxStart(function () {
  nbActiveAjaxRequest++;
  $.showLoading();
});

$(document).ajaxStop(function () {
  nbActiveAjaxRequest--;
  if (nbActiveAjaxRequest <= 0) {
    nbActiveAjaxRequest = 0;
    $.hideLoading();
  }
});

setInterval(function () {
  var dateLoc = new Date;
  var dateJeed = new Date;
  dateJeed.setTime(dateLoc.getTime() +(dateLoc.getTimezoneOffset() + serverTZoffsetMin)*60000 + clientServerDiffDatetime);
  $('#horloge').text(dateJeed.toLocaleTimeString());
}, 1000);

function loadPage(_url,_noPushHistory){
  if (modifyWithoutSave) {
    if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
      return;
    }
    modifyWithoutSave = false;
  }
  if (typeof unload_page !== "undefined") {
    unload_page();
  }
  try{
    $(".ui-dialog-content").dialog("close");
  }catch(e){
    
  }
  if(!isset(_noPushHistory) || _noPushHistory == false) {
    try {
      if(PREVIOUS_PAGE == null){
        window.history.replaceState('','', 'index.php?'+window.location.href.split("index.php?")[1]);
        PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1];
      }
      if(PREVIOUS_PAGE == null || PREVIOUS_PAGE != _url){
        window.history.pushState('','', _url)
        PREVIOUS_PAGE = _url;
      }
    } catch(e) {
      
    }
  }
  if(isset(bootbox)){
    bootbox.hideAll();
  }
  jeedom.cmd.update = Array();
  jeedom.scenario.update = Array();
  $('main').css('padding-right','').css('padding-left','').css('margin-right','').css('margin-left','');
  $('#div_pageContainer').add("#div_pageContainer *").off();
  $.hideAlert();
  $('.bt_pluginTemplateShowSidebar').remove();
  removeContextualFunction();
  if(_url.indexOf('#') == -1){
    var url = _url+'&ajax=1';
  }else{
    var n=_url.lastIndexOf("#");
    var url = _url.substring(0,n)+"&ajax=1"+_url.substring(n)
  }
  
  $('.backgroundforJeedom').css({
    'background-image':'',
    'background-position':'center center',
    'background-repeat':'no-repeat',
    'background-size':'cover'
  });
  jeedomBackgroundImg = null;
  
  if (__OBSERVER__ !== null) __OBSERVER__.disconnect()
  
  $('#div_pageContainer').empty().load(url,function(){
    if (_url.match('#') && _url.split('#')[1] != '' && $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').html() != undefined) {
      $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').trigger('click');
    }
    $('body').attr('data-page',getUrlVars('p'));
    $('#bt_getHelpPage').attr('data-page',getUrlVars('p')).attr('data-plugin',getUrlVars('m'));
    initPage();
    $('body').trigger('jeedom_page_load');
    if(jeedomBackgroundImg !== null){
      setBackgroundImg(jeedomBackgroundImg);
    }else{
      setBackgroundImg('');
    }
    if(window.location.hash != '' && $('.nav-tabs a[href="'+window.location.hash+'"]').length != 0){
      $('.nav-tabs a[href="'+window.location.hash+'"]').click();
    }
    setTimeout(function(){
      modifyWithoutSave = false;
    },500)
  });
  
  setTimeout(function() {
    //scenarios uses special tooltips not requiring destroy.
    if ($('body').attr('data-page') != 'scenario') {
      if (__OBSERVER__ !== null) {
        __OBSERVER__.observe(document.getElementById('div_mainContainer'), _observerConfig_)
      } else {
        createObserver()
      }
    }
  }, 750)
  
  return;
}

function removeContextualFunction(){
  printEqLogic = undefined
}

$(function () {
  $.alertTrigger = function(){
    initRowOverflow();
  }
  $('body').attr('data-page',getUrlVars('p'));
  
  $('body').off('jeedom_page_load').on('jeedom_page_load',function(){
    if (getUrlVars('saveSuccessFull') == 1) {
      $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
      PREVIOUS_PAGE=window.location.href.split('&saveSuccessFull')[0]+window.location.hash;
      window.history.replaceState({}, document.title, window.location.href.split('&saveSuccessFull')[0]+window.location.hash);
    }
    if (getUrlVars('removeSuccessFull') == 1) {
      $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'});
      PREVIOUS_PAGE=window.location.href.split('&saveSuccessFull')[0]+window.location.hash;
      window.history.replaceState({}, document.title, window.location.href.split('&removeSuccessFull')[0]+window.location.hash);
    }
  });
  
  if(window.location.hash != '' && $('.nav-tabs a[href="'+window.location.hash+'"]').length != 0){
    $('.nav-tabs a[href="'+window.location.hash+'"]').click();
  }
  
  $('body').on('shown.bs.tab','.nav-tabs a', function (e) {
    if(e.target.hash == ''){
      return;
    }
    if(PREVIOUS_PAGE == null){
      window.history.replaceState('','', 'index.php?'+window.location.href.split("index.php?")[1]);
      PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1];
    }
    window.location.hash = e.target.hash;
  })
  
  window.addEventListener('hashchange', function (event){
    NO_POPSTAT = true
    setTimeout(function(){
      NO_POPSTAT = false;
    },200)
  });
  
  window.addEventListener('popstate', function (event){
    if(event.state === null){
      if(NO_POPSTAT){
        NO_POPSTAT = false;
        return;
      }
      if(window.location.hash != '' && $('.nav-tabs a[href="'+window.location.hash+'"]:visible').length != 0){
        $('.nav-tabs a[href="'+window.location.hash+'"]').click();
      }else if(PREVIOUS_PAGE !== null && PREVIOUS_PAGE.includes('#') && PREVIOUS_PAGE.split('#')[0] != 'index.php?'+window.location.href.split("index.php?")[1].split('#')[0]){
        if (modifyWithoutSave) {
          if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
            return;
          }
          modifyWithoutSave = false;
        }
        loadPage('index.php?'+window.location.href.split("index.php?")[1],true);
        PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1];
      }
      return;
    }
    if (modifyWithoutSave) {
      if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
        return;
      }
      modifyWithoutSave = false;
    }
    loadPage('index.php?'+window.location.href.split("index.php?")[1],true);
    PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1];
  });
  
  $('body').on('click','a',function(e){
    if ($(window).width() < 768) {
      $('ul.dropdown-menu').css('display', '')
    }
    if($(this).hasClass('noOnePageLoad')){
      return;
    }
    if($(this).hasClass('fancybox-nav')){
      return;
    }
    if($(this).attr('href') == undefined || $(this).attr('href') == '' || $(this).attr('href') == '#'){
      return;
    }
    if ($(this).attr('href').match("^http")) {
      return;
    }
    if ($(this).attr('href').match("^#")) {
      return;
    }
    if($(this).attr('target') == '_blank'){
      return;
    }
    $('li.dropdown.open').click();
    $('.navbar-collapse').removeClass('in');
    loadPage($(this).attr('href'));
    e.preventDefault();
    e.stopPropagation();
  });
  
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "positionClass": "toast-top-right",
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
  
  $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
    if ($(this).parent().hasClass('dropdown-submenu')) return
    event.preventDefault();
    event.stopPropagation();
    $(this).parent().siblings().removeClass('open');
    $(this).parent().toggleClass('open');
    $('.dropdown-menu').dropdown('toggle');
  });
  
  if (getDeviceType()['type'] == 'desktop') {
    $('ul.dropdown-menu [data-toggle=dropdown]').on('mouseenter', function (event) {
      if ($(window).width() < 768) return
      event.preventDefault();
      event.stopPropagation();
      $(this).parent().siblings().removeClass('open');
      $(this).parent().toggleClass('open');
    });
  }
  
  $('li.dropdown-submenu a.dropdown-toggle').on('click',function(event) {
    event.stopPropagation()
    var opened = false
    if ($(this).parent().hasClass('open')) opened = true
    $('li.dropdown-submenu').removeClass('open')
    if (!opened) $(this).parent().addClass('open')
  });
  
  $('.dropdown-menu').on('mouseleave', '.dropdown-submenu.open a',function(){
    if ($(window).width() < 768) return
    if ($(this).closest('.dropdown-submenu').is(':hover')) {
      return;
    }
    $(this).trigger('mouseenter');
  })
  
  $('.dropdown-menu').on('mouseleave', '.dropdown-submenu.open .dropdown-menu',function(){
    if ($(window).width() < 768) return
    $(this).closest('.dropdown-submenu').find('a').trigger('mouseenter');
  })
  
  $('ul.nav li.dropdown').hover(function() {
    if ($(window).width() < 768) return
    $(this).find('.dropdown-menu').first().stop(true, true).show();
  }, function() {
    if ($(window).width() < 768) return
    $(this).find('.dropdown-menu').first().stop(true, true).hide();
  });
  
  /*********************Gestion des dialogs********************************/
  $.fn.modal.Constructor.prototype.enforceFocus = function () {};
  
  $('body').on( "show", ".modal",function () {
    document.activeElement.blur();
    $(this).find(".modal-body :input:visible").first().focus();
  });
  
  $('body').on('focusin','.bootbox-input', function (e) {
    e.stopPropagation();
  });
  
  $('.bootbox.modal').on('shown.bs.modal', function() {
    $(this).find(".bootbox-accept").focus();
  })
  
  /************************Help*************************/
  setTimeout(function() {
    if ( isset(jeedom_langage) ) {
      lang = jeedom_langage.substr(0, 2)
      supportedLangs = ['fr', 'de', 'es']
      if ( lang != 'en' && supportedLangs.includes(lang) ) {
        bootbox.addLocale('fr', {OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Annuler'})
        bootbox.addLocale('de', {OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Abbrechen'})
        bootbox.addLocale('es', {OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Anular'})
        bootbox.setLocale('fr')
      }
    }
  }, 250)
  
  //Display report bug
  $("#md_reportBug").dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: ((jQuery(window).height() - 100) < 700) ? jQuery(window).height() - 100 : 700,
    width: ((jQuery(window).width() - 100) < 900) ? (jQuery(window).width() - 100) : 900,
    position: { my: "center bottom-10", at: "center bottom", of: window },
    open: function () {
      $("body").css({overflow: 'hidden'})
      $(this).closest( ".ui-dialog" ).find(":button").blur();
      $(this).dialog({
        height: ((jQuery(window).height() - 100) < 700) ? jQuery(window).height() - 100 : 700,
        width: ((jQuery(window).width() - 100) < 900) ? (jQuery(window).width() - 100) : 900,
        position: { my: "center bottom-10", at: "center bottom", of: window },
      });
      setTimeout(function(){initTooltips($("#md_reportBug"))},500);
    },
    beforeClose: function (event, ui) {
      $("body").css({overflow: 'inherit'})
      $("#md_reportBug").empty();
    }
  });
  
  //Display help
  $("#md_pageHelp").dialog({
    autoOpen: false,
    modal: false,
    closeText: '',
    height: (jQuery(window).height() - 100),
    width: ((jQuery(window).width() - 100) < 1500) ? (jQuery(window).width() - 50) : 1500,
    position: { my: "center bottom-10", at: "center bottom", of: window },
    open: function () {
      $("body").css({overflow: 'hidden'});
      $(this).closest( ".ui-dialog" ).find(":button").blur();
      $(this).dialog({
        height: (jQuery(window).height() - 100),
        width: ((jQuery(window).width() - 100) < 1500) ? (jQuery(window).width() - 50) : 1500,
        position: { my: "center bottom-10", at: "center bottom", of: window },
      });
      setTimeout(function(){initTooltips($("#md_pageHelp"))},500);
    },
    beforeClose: function (event, ui) {
      $("body").css({overflow: 'inherit'});
      $("#md_pageHelp").empty();
    }
  });
  
  $("#md_modal").dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (jQuery(window).height() - 125),
    width: ((jQuery(window).width() - 50) < 1500) ? (jQuery(window).width() - 50) : 1500,
    position: {my: 'center bottom-50', at: 'center bottom', of: window},
    open: function () {
      $('body').css({overflow: 'hidden'});
      $(this).closest('.ui-dialog').find(':button').blur();
      $(this).dialog({
        height: (jQuery(window).height() - 125),
        width: ((jQuery(window).width() - 50) < 1500) ? (jQuery(window).width() - 50) : 1500,
        position: {my: 'center bottom-50', at: 'center bottom', of: window},
      });
      setTimeout(function(){initTooltips($("#md_modal"))},500);
    },
    beforeClose: function (event, ui) {
      $("body").css({overflow: 'inherit'});
      $("#md_modal").empty();
    }
  });
  
  $("#md_modal2").dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (jQuery(window).height() - 125),
    width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
    position: {my: 'center bottom-50', at: 'center bottom',  of: window},
    open: function () {
      $("body").css({overflow: 'hidden'});
      $(this).closest( ".ui-dialog" ).find(":button").blur();
      $(this).dialog({
        height: (jQuery(window).height() - 125),
        width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
        position: {my: 'center bottom-50', at: 'center bottom',  of: window},
      });
      setTimeout(function(){initTooltips($("#md_modal2"))},500);
    },
    beforeClose: function (event, ui) {
      $("body").css({overflow: 'inherit'});
      $("#md_modal2").empty();
    }
  });
  
  $("#md_modal3").dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (jQuery(window).height() - 125),
    width: ((jQuery(window).width() - 250) < 1000) ? (jQuery(window).width() - 50) : 1000,
    position: {my: 'center bottom-50', at: 'center bottom',  of: window},
    open: function () {
      $("body").css({overflow: 'hidden'});
      $(this).closest( ".ui-dialog" ).find(":button").blur();
      $(this).dialog({
        height : (jQuery(window).height() - 125),
        width : ((jQuery(window).width() - 250) < 1000) ? (jQuery(window).width() - 50) : 1000,
        position: {my: 'center bottom-50', at: 'center bottom',  of: window},
      });
      setTimeout(function(){initTooltips($("#md_modal3"))},500);
    },
    beforeClose: function (event, ui) {
      $("body").css({overflow: 'inherit'});
      $("#md_modal3").empty();
    }
  });
  
  $('#bt_jeedomAbout').on('click', function () {
    $('#md_modal').dialog({title: "{{A propos}}"});
    $('#md_modal').load('index.php?v=d&modal=about').dialog('open');
  });
  
  $('#bt_getHelpPage').on('click',function(){
    jeedom.getDocumentationUrl({
      plugin: $(this).attr('data-plugin'),
      page: $(this).attr('data-page'),
      error: function(error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function(url) {
        window.open(url,'_blank');
      }
    });
  });
  
  $('body').on( 'click','.bt_pageHelp', function () {
    showHelpModal($(this).attr('data-name'), $(this).attr('data-plugin'));
  });
  
  $('body').on( 'click','.bt_reportBug', function () {
    $('#md_reportBug').load('index.php?v=d&modal=report.bug').dialog('open');
  });
  
  $(window).bind('beforeunload', function (e) {
    if (modifyWithoutSave) {
      return '{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}';
    }
  });
  
  $(window).resize(function () {
    initRowOverflow();
  });
  
  if (typeof jeedom_firstUse != 'undefined' && isset(jeedom_firstUse) && jeedom_firstUse == 1 && getUrlVars('noFirstUse') != 1) {
    $('#md_modal').dialog({title: "{{Bienvenue dans Jeedom}}"});
    $("#md_modal").load('index.php?v=d&modal=first.use').dialog('open');
  }
  
  $('#bt_haltSystem').on('click', function () {
    $.hideAlert();
    bootbox.confirm('{{Êtes-vous sûr de vouloir arrêter le système ?}}', function (result) {
      if (result) {
        window.location.href = 'index.php?v=d&p=shutdown';
      }
    });
  });
  
  $('#bt_rebootSystem').on('click', function () {
    $.hideAlert();
    bootbox.confirm('{{Êtes-vous sûr de vouloir redémarrer le système ?}}', function (result) {
      if (result) {
        window.location.href = 'index.php?v=d&p=reboot';
      }
    });
  });
  
  $('#bt_showEventInRealTime').on('click',function(){
    $('#md_modal').dialog({title: "{{Evénements en temps réel}}"});
    $("#md_modal").load('index.php?v=d&modal=log.display&log=event').dialog('open');
  });
  
  $('#bt_showNoteManager').on('click',function(){
    $('#md_modal').dialog({title: "{{Notes}}"});
    $("#md_modal").load('index.php?v=d&modal=note.manager').dialog('open');
  });
  
  $('#bt_gotoDashboard').on('click',function(event){
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.preventDefault()
      return
    }
    if('ontouchstart' in window || navigator.msMaxTouchPoints){
      event.preventDefault()
      return
    }
    $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
    loadPage('index.php?v=d&p=dashboard');
  });
  
  $('#bt_gotoView').on('click',function(){
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.preventDefault()
      return
    }
    if('ontouchstart' in window || navigator.msMaxTouchPoints){
      event.preventDefault()
      return
    }
    $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
    loadPage('index.php?v=d&p=view');
  });
  
  $('#bt_gotoPlan').on('click',function(){
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.preventDefault()
      return
    }
    if('ontouchstart' in window || navigator.msMaxTouchPoints){
      event.preventDefault()
      return
    }
    $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
    loadPage('index.php?v=d&p=plan');
  });
  
  $('#bt_gotoPlan3d').on('click',function(){
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.preventDefault()
      return
    }
    if('ontouchstart' in window || navigator.msMaxTouchPoints){
      event.preventDefault()
      return
    }
    $('ul.dropdown-menu [data-toggle=dropdown]').parent().parent().parent().siblings().removeClass('open');
    loadPage('index.php?v=d&p=plan3d');
  });
  
  $('#bt_messageModal').on('click',function(){
    $('#md_modal').dialog({title: "{{Centre de Messages}}"});
    $('#md_modal').load('index.php?v=d&p=message&ajax=1').dialog('open');
  });
  $('#bt_jsErrorModal').on('click',function(){
    $('#md_modal').dialog({title: "{{Erreur Javascript}}"});
    $('#md_modal').load('index.php?v=d&modal=js.error').dialog('open');
  });
  
  $('body').on('click','.objectSummaryParent',function(){
    loadPage('index.php?v=d&p=dashboard&summary='+$(this).data('summary')+'&object_id='+$(this).data('object_id'));
  });
  
  if (getCookie('currentTheme') == 'alternate') {
    var themeButton = '<i class="fas fa-sync-alt"></i> {{Thème principal}}'
    $('#bt_switchTheme').html(themeButton)
    $('#bootstrap_theme_css').attr('data-nochange',0)
  }
  
  if(jeedom.theme.currentTheme){
    $('body').attr('data-theme',jeedom.theme.currentTheme);
  }
  
  $('body').on('click','#bt_switchTheme',function(){
    var theme = 'core/themes/'+jeedom.theme.default_bootstrap_theme_night+'/desktop/' + jeedom.theme.default_bootstrap_theme_night + '.css';
    var themShadows = 'core/themes/'+jeedom.theme.default_bootstrap_theme_night+'/desktop/shadows.css';
    var themeCook = 'alternate'
    var themeButton = '<i class="fas fa-sync-alt"></i> {{Thème principal}}';
    $('body').attr('data-theme',jeedom.theme.default_bootstrap_theme_night);
    if ($('#bootstrap_theme_css').attr('href').split('?md5')[0] == theme) {
      $('body').attr('data-theme',jeedom.theme.default_bootstrap_theme);
      theme = 'core/themes/'+jeedom.theme.default_bootstrap_theme+'/desktop/' + jeedom.theme.default_bootstrap_theme + '.css';
      themShadows = 'core/themes/'+jeedom.theme.default_bootstrap_theme+'/desktop/shadows.css';
      themeCook = 'default'
      themeButton = '<i class="fas fa-sync-alt"></i> {{Thème alternatif}}'
      $('#bootstrap_theme_css').attr('data-nochange',0)
    } else {
      $('#bootstrap_theme_css').attr('data-nochange',1);
    }
    document.cookie = "currentTheme=" + themeCook + "; path=/"
    $('#bootstrap_theme_css').attr('href', theme);
    $('#bt_switchTheme').html(themeButton)
    if ($("#shadows_theme_css").length > 0) $('#shadows_theme_css').attr('href', themShadows);
    setBackgroundImg(BACKGROUND_IMG);
  });
  
  if(typeof jeedom.theme != 'undefined' && typeof jeedom.theme.css != 'undefined' && Object.keys(jeedom.theme.css).length > 0){
    for(var i in jeedom.theme.css){
      document.body.style.setProperty(i,jeedom.theme.css[i]);
    }
  }
  
  $('body').attr('data-coloredIcons',0);
  if(typeof jeedom.theme['interface::advance::coloredIcons'] != 'undefined' && jeedom.theme['interface::advance::coloredIcons'] == '1'){
    $('body').attr('data-coloredIcons',1);
  }
  
  initPage();
  changeThemeAuto();
  if(jeedomBackgroundImg != null){
    setBackgroundImg(jeedomBackgroundImg);
  }else{
    setBackgroundImg('');
  }
  
  setTimeout(function(){
    initTooltips()
    createObserver()
    $('body').trigger('jeedom_page_load')
  }, 1)
});

setTimeout(function() {
  $("body").on('keydown',"input[id^='in_search']",function(event) {
    if(event.key == 'Escape') {
      $(this).val('').keyup();
    }
  })
}, 500)

function changeThemeAuto(){
  if(typeof jeedom.theme == 'undefined'){
    return;
  }
  if(typeof jeedom.theme.theme_changeAccordingTime == 'undefined' || jeedom.theme.theme_changeAccordingTime == 0){
    return;
  }
  if(typeof jeedom.theme.default_bootstrap_theme == 'undefined' || typeof jeedom.theme.default_bootstrap_theme_night == 'undefined'){
    return;
  }
  if(jeedom.theme.default_bootstrap_theme == jeedom.theme.default_bootstrap_theme_night){
    return;
  }
  setInterval(function () {
    if($('#bootstrap_theme_css').attr('data-nochange') == 1){
      return;
    }
    var theme  = jeedom.theme.default_bootstrap_theme_night;
    var themeCss = 'core/themes/'+jeedom.theme.default_bootstrap_theme_night+'/desktop/' + jeedom.theme.default_bootstrap_theme_night + '.css';
    var currentTime = parseInt((new Date()).getHours()*100+ (new Date()).getMinutes());
    if(parseInt(jeedom.theme.theme_start_day_hour.replace(':','')) <  currentTime && parseInt(jeedom.theme.theme_end_day_hour.replace(':','')) >  currentTime){
      theme  = jeedom.theme.default_bootstrap_theme;
      themeCss = 'core/themes/'+jeedom.theme.default_bootstrap_theme+'/desktop/' + jeedom.theme.default_bootstrap_theme + '.css';
    }
    if($('#bootstrap_theme_css').attr('href') != themeCss){
      $('#bootstrap_theme_css').attr('href', themeCss);
      $('body').attr('data-theme',theme);
      if ($("#shadows_theme_css").length > 0) $('#shadows_theme_css').attr('href', 'core/themes/'+theme+'/desktop/shadows.css');
      setBackgroundImg(BACKGROUND_IMG);
    }
  }, 60000);
}

function setBackgroundImg(_path){
  if(!isset(jeedom) || !isset(jeedom.theme) || !isset(jeedom.theme.showBackgroundImg) || jeedom.theme.showBackgroundImg == 0){
    return;
  }
  BACKGROUND_IMG = _path;
  if(_path === null){
    document.body.style.setProperty('--dashBkg-url','url("")');
    $('.backgroundforJeedom').css('background-image','url("")');
  }else if(_path === ''){
    var mode = 'light'
    if ($('body').attr('data-theme') == 'core2019_Dark') {
      mode = 'dark';
    }
    _path = 'core/img/background/jeedom_abstract_01_'+mode+'.jpg';
    if(['administration','profils'].indexOf($('body').attr('data-page')) != -1){
      _path = 'core/img/background/jeedom_abstract_03_'+mode+'.jpg';
    }
    if(['display','eqAnalyse','log','history','report','health'].indexOf($('body').attr('data-page')) != -1){
      _path = 'core/img/background/jeedom_abstract_02_'+mode+'.jpg';
    }
    $('.backgroundforJeedom').css('background-image','url("'+_path+'")');
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")');
  }else{
    $('.backgroundforJeedom').css('background-image','url("'+_path+'")');
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")');
  }
}

//Initiators__
function initPage(){
  initTableSorter();
  initReportMode();
  $.initTableFilter();
  initRowOverflow();
  initHelp();
  initTextArea();
  $('.nav-tabs a').on('click',function (e) {
    var scrollHeight = $(document).scrollTop();
    $(this).tab('show');
    $(window).scrollTop(scrollHeight);
    setTimeout(function() {
      $(window).scrollTop(scrollHeight);
    }, 0);
  });
  
  setTimeout(function() { initTooltips() }, 750)
  if (getDeviceType()['type'] == 'desktop') $("input[id^='in_search']").focus()
}

function createObserver() {
  __OBSERVER__ = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if ( mutation.type == 'childList' ) {
        if (mutation.addedNodes.length >= 1) {
          if (mutation.addedNodes[0].nodeName != '#text') {
            //console.log('Added ' + mutation.addedNodes[0].tagName + ' tag.')
            initTooltips($(mutation.target))
          }
        }
        else if (mutation.removedNodes.length >= 1) {
          //console.log('Removed ' + mutation.removedNodes[0].tagName + ' tag.')
        }
      } else if (mutation.type == 'attributes') {
        //console.log('Modified ' + mutation.attributeName + ' attribute.')
        if (mutation.attributeName == 'title') initTooltips($(mutation.target))
      }
    })
  })
  __OBSERVER__.observe(document.getElementById('div_mainContainer'), _observerConfig_)
}

function initTooltips(_el) {
  if (!_el) {
    $('.tooltips:not(.tooltipstered), [title]:not(.ui-button)').tooltipster(TOOLTIPSOPTIONS)
  } else {
    //cmd update:
    if (_el.parents('.cmd-widget[title]').length) {
      me = _el.closest('.cmd-widget[title]')
      if (me.hasClass('tooltipstered')) me.tooltipster('destroy')
      me.tooltipster(TOOLTIPSOPTIONS)
      return;
    }
    
    if (_el.hasClass('tooltips') && !_el.hasClass('tooltipstered') || _el.is('[title]')) {
      if (_el.is('[title]') && _el.hasClass('tooltipstered')){
        _el.tooltipster('destroy');
      }
      _el.tooltipster(TOOLTIPSOPTIONS)
    }
    
    _el.find('.tooltipstered[title]').tooltipster('destroy')
    _el.find('.tooltips:not(.tooltipstered), [title]').tooltipster(TOOLTIPSOPTIONS)
    
  }
}

function initTextArea(){
  $('body').on('change keyup keydown paste cut', 'textarea.autogrow', function () {
    $(this).height(0).height(this.scrollHeight);
  });
}

function initRowOverflow() {
  var hWindow = $(window).outerHeight() - $('header').outerHeight() - 5;
  if($('#div_alert').is(':visible')){
    hWindow -= ($('#div_alert').outerHeight() + 20);
  }
  if($('.row-overflow').attr('data-offset') != undefined){
    hWindow -= $('.row-overflow').attr('data-offset');
  }
  $('.row-overflow > div:not(#div_displayObjectList)').css('padding-top','0px').height(hWindow).css('overflow-y', 'auto').css('overflow-x', 'hidden').css('padding-top','5px');
}

function initReportMode() {
  if (getUrlVars('report') == 1) {
    $('header').hide();
    $('footer').hide();
    $('#div_mainContainer').css('margin-top', '-50px');
    $('#wrap').css('margin-bottom', '0px');
    $('.reportModeVisible').show();
    $('.reportModeHidden').hide();
  }
}

function initTableSorter(filter) {
  var widgets = ['uitheme', 'zebra', 'resizable'];
  if(!filter){
    filter = true;
  }
  if (filter !== false) {
    widgets.push('filter')
  }
  $(".tablesorter").tablesorter({
    dateFormat : "yyyy-mm-dd",
    theme: "bootstrap",
    widthFixed: false,
    headerTemplate: '{content} ',
    widgets: widgets,
    ignoreCase: true,
    delayInit: false,
    resizable: false,
    saveSort: false,
    sortLocaleCompare: true,
    widgetOptions: {
      filter_ignoreCase: true,
      resizable: true,
      stickyHeaders_offset: $('header.navbar-fixed-top').height(),
      zebra: ["ui-widget-content even", "ui-state-default odd"],
    },
    cssIcon: 'tablesorter-icon',
    initialized : function(table){
      $(table).find('thead .tablesorter-header-inner').append('<i class="tablesorter-icon"></i>');
    }
  });
  $(".tablesorter").css('width','');
}

function initHelp(){
  $('.help').each(function(){
    if($(this).attr('data-help') != undefined){
      $(this).append(' <sup><i class="fas fa-question-circle tooltips" title="'+$(this).attr('data-help')+'" style="font-size : 1em;color:grey;"></i></sup>');
    }
  });
}

//Commons__
function getCookie(name) {
  var cookies = document.cookie.split(';');
  for(var i in cookies){
    var csplit = cookies[i].split('=');
    if(name.trim() == csplit[0].trim()){
      return csplit[1];
    }
  }
  return '';
}

function normTextLower(_text) {
  return _text.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase()
}

function linkify(inputText) {
  var replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
  var replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');
  var replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
  var replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');
  var replacePattern3 = /(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/gim;
  var replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');
  return replacedText
}

function showHelpModal(_name, _plugin) {
  if (init(_plugin) != '' && _plugin != undefined) {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_plugin_' + _plugin + '.php #primary', function () {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
        $('a[href="#div_helpSpe"]').click();
        $('a[href="#div_helpWebsite"]').hide();
      } else {
        $('a[href="#div_helpWebsite"]').show();
        $('a[href="#div_helpWebsite"]').click();
      }
    });
    $('#div_helpSpe').load('index.php?v=d&plugin=' + _plugin + '&modal=help.' + init(_name));
  } else {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_' + init(_name) + '.php #primary', function () {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
        $('a[href="#div_helpSpe"]').click();
        $('a[href="#div_helpWebsite"]').hide();
      } else {
        $('a[href="#div_helpWebsite"]').show();
        $('a[href="#div_helpWebsite"]').click();
      }
    });
    $('#div_helpSpe').load('index.php?v=d&modal=help.' + init(_name));
  }
  $('#md_pageHelp').dialog('open');
}

function refreshMessageNumber() {
  jeedom.message.number({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function (_number) {
      MESSAGE_NUMBER = _number;
      if (_number == 0 || _number == '0') {
        $('#span_nbMessage').hide();
      } else {
        $('#span_nbMessage').html(_number);
        $('#span_nbMessage').show();
      }
    }
  });
}

function refreshUpdateNumber() {
  jeedom.update.number({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function (_number) {
      UPDATE_NUMBER = _number;
      if (_number == 0 || _number == '0') {
        $('#span_nbUpdate').hide();
      } else {
        $('#span_nbUpdate').html(_number).show();
      }
    }
  });
}

function notify(_title, _text, _class_name) {
  if (_title == '' && _text == '') {
    return true;
  }
  if (isset(_class_name) != '' && isset(toastr[_class_name])) {
    toastr[_class_name](_text, _title);
  } else {
    toastr.info(_text, _title);
  }
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function chooseIcon(_callback, _params) {
  if ($("#mod_selectIcon").length == 0) {
    $('#div_pageContainer').append('<div id="mod_selectIcon"></div>');
    $("#mod_selectIcon").dialog({
      title: '{{Choisissez une icône}}',
      closeText: '',
      autoOpen: false,
      modal: true,
      height: (jQuery(window).height() - 150),
      width: 1500,
      open: function () {
        if ((jQuery(window).width() - 50) < 1500) {
          $('#mod_selectIcon').dialog({width: jQuery(window).width() - 50});
        }
        $("body").css({overflow: 'hidden'});
        setTimeout(function(){initTooltips($("#mod_selectIcon"))},500);
      },
      beforeClose: function (event, ui) {
        $("body").css({overflow: 'inherit'});
      }
    });
  }
  var url = 'index.php?v=d&modal=icon.selector';
  if(_params && _params.img && _params.img === true) {
    url += '&showimg=1';
  }
  if(_params && _params.icon) {
    icon = _params.icon
    replaceAr = ['icon_blue', 'icon_green', 'icon_orange', 'icon_red', 'icon_yellow']
    replaceAr.forEach(function(element) {
      icon = icon.replace(element, '')
    })
    icon = icon.trim().replace(new RegExp('  ', 'g'), ' ')
    icon = icon.trim().replace(new RegExp(' ', 'g'), '.')
    url += '&selectIcon=' + icon;
  }
  $('#mod_selectIcon').empty().load(url,function(){
    $("#mod_selectIcon").dialog('option', 'buttons', {
      "Annuler": function () {
        $(this).dialog("close");
      },
      "Valider": function () {
        var icon = $('.iconSelected .iconSel').html();
        if (icon == undefined) {
          icon = '';
        }
        icon = icon.replace(/"/g, "'");
        _callback(icon);
        $(this).dialog('close');
      }
    });
    $('#mod_selectIcon').dialog('open');
  });
}

function calculWidgetSize(_size,_step,_margin){
  var result = Math.ceil(_size / _step) * _step - (2*_margin);
  if(result < _size){
    result += Math.ceil((_size - result) / _step)* _step;
  }
  return result;
}

function positionEqLogic(_id,_preResize,_scenario) {
  if(_id != undefined){
    var widget = (_scenario) ? $('.scenario-widget[data-scenario_id='+_id+']') : $('.eqLogic-widget[data-eqlogic_id='+_id+']');
    widget.css('margin','0px').css('padding','0px');
    if($(this).width() == 0){
      $(this).width('230');
    }
    if($(this).height() == 0){
      $(this).height('110');
    }
    if(init(_preResize,true)){
      widget.width(Math.floor(widget.width() / jeedom.theme['widget::step::width']) * jeedom.theme['widget::step::width'] - (2 * jeedom.theme['widget::margin']));
      widget.height(Math.floor(widget.height() / jeedom.theme['widget::step::height']) * jeedom.theme['widget::step::height'] - (2 * jeedom.theme['widget::margin']));
    }
    widget.width(calculWidgetSize(widget.width(),jeedom.theme['widget::step::width'],jeedom.theme['widget::margin']));
    widget.height(calculWidgetSize(widget.height(),jeedom.theme['widget::step::height'],jeedom.theme['widget::margin']));
    if(!widget.hasClass(widget.attr('data-category'))){
      widget.addClass(widget.attr('data-category'));
    }
    widget.css('margin',jeedom.theme['widget::margin']+'px');
  }else{
    $('.eqLogic-widget:not(.jeedomAlreadyPosition),.scenario-widget:not(.jeedomAlreadyPosition)').css('margin','0px').css('padding','0px');
    $('.eqLogic-widget:not(.jeedomAlreadyPosition),.scenario-widget:not(.jeedomAlreadyPosition)').each(function () {
      if($(this).width() == 0){
        $(this).width('230');
      }
      if($(this).height() == 0){
        $(this).height('110');
      }
      $(this).width(calculWidgetSize($(this).width(),jeedom.theme['widget::step::width'],jeedom.theme['widget::margin']));
      $(this).height(calculWidgetSize($(this).height(),jeedom.theme['widget::step::height'],jeedom.theme['widget::margin']));
      if(!$(this).hasClass($(this).attr('data-category'))){
        $(this).addClass($(this).attr('data-category'));
      }
    });
    $('.eqLogic-widget:not(.jeedomAlreadyPosition),.scenario-widget:not(.jeedomAlreadyPosition)').css('margin',jeedom.theme['widget::margin']+'px');
    $('.eqLogic-widget,.scenario-widget').addClass('jeedomAlreadyPosition');
  }
}

function uniqId(_prefix){
  if(typeof _prefix == 'undefined'){
    _prefix = 'jee-uniq';
  }
  var result = _prefix +'-'+ uniqId_count + '-'+Math.random().toString(36).substring(8);;
  uniqId_count++;
  if($('#'+result).length){
    return uniqId(_prefix);
  }
  return result;
}

function taAutosize(){
  autosize($('.ta_autosize'));
  autosize.update($('.ta_autosize'));
}

function hexToRgb(hex) {
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null;
}

function componentToHex(c) {
  var hex = c.toString(16);
  return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
  if ($.type(r) === 'string' && !g) {
    r = r.trim()
    if (r.startsWith('rgb')) {
      r = r.replace('rgb', '')
    }
    if (r.startsWith('(')){
      r = r.replace('(', '')
    }
    if (r.endsWith(')')){
      r = r.replace(')', '')
    }
    strAr = r.split(',')
    r = parseInt(strAr[0].trim())
    g = parseInt(strAr[1].trim())
    b = parseInt(strAr[2].trim())
  }
  return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b)
}

function addOrUpdateUrl(_param,_value,_title){
  var url = new URL(window.location.href);
  var query_string = url.search;
  var search_params = new URLSearchParams(query_string);
  if(_value == null){
    search_params.delete(_param);
  }else{
    search_params.set(_param, _value);
  }
  url.search = search_params.toString();
  url = url.toString();
  if(url != window.location.href){
    if (url.indexOf('#') != -1) {
      url = url.substring(0, url.indexOf('#'));
    }
    if(PREVIOUS_PAGE != 'index.php?'+window.location.href.split("index.php?")[1]){
      window.history.pushState('','', window.location.href);
    }
    if(_title && _title != ''){
      document.title = _title;
    }
    window.history.pushState('','', url.toString());
    PREVIOUS_PAGE = 'index.php?'+url.split("index.php?")[1];
  }else{
    if(_title && _title != ''){
      document.title = _title;
    }
  }
}

function saveWidgetDisplay(_params) {
  if(init(_params) == ''){
    _params = {};
  }
  var cmds = [];
  var eqLogics = [];
  var scenarios = [];
  $('.eqLogic-widget:not(.eqLogic_layout_table)').each(function(){
    var eqLogic = $(this);
    order = 1;
    eqLogic.find('.cmd').each(function(){
      cmd = {};
      cmd.id = $(this).attr('data-cmd_id');
      cmd.order = order;
      cmds.push(cmd);
      order++;
    });
  });
  $('.eqLogic-widget.eqLogic_layout_table').each(function(){
    var eqLogic = $(this);
    order = 1;
    eqLogic.find('.cmd').each(function(){
      cmd = {};
      cmd.id = $(this).attr('data-cmd_id');
      cmd.line = $(this).closest('td').attr('data-line');
      cmd.column = $(this).closest('td').attr('data-column');
      cmd.order = order;
      cmds.push(cmd);
      order++;
    });
  });
  if(init(_params['dashboard']) == 1){
    $('.div_displayEquipement').each(function(){
      order = 1;
      $(this).find('.eqLogic-widget,.scenario-widget').each(function(){
        if($(this).hasClass('eqLogic-widget')){
          var eqLogic = {id :$(this).attr('data-eqlogic_id')}
          eqLogic.display = {};
          eqLogic.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px';
          eqLogic.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px';
          eqLogic.order = ($(this).attr('data-order') != undefined) ? $(this).attr('data-order') : order;
          eqLogics.push(eqLogic);
        }
        if($(this).hasClass('scenario-widget')){
          var scenario = {id :$(this).attr('data-scenario_id')}
          scenario.display = {};
          scenario.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px';
          scenario.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px';
          scenario.order = ($(this).attr('data-order') != undefined) ? $(this).attr('data-order') : order;
          scenarios.push(scenario);
        }
        order++;
      });
    });
    jeedom.eqLogic.setOrder({
      eqLogics: eqLogics,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success:function(data){
        jeedom.cmd.setOrder({
          cmds: cmds,
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          }
        });
      }
    });
    jeedom.scenario.setOrder({
      scenarios: scenarios,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      }
    });
  } else if(init(_params['view']) == 1){
    $('.eqLogicZone').each(function(){
      order = 1;
      $(this).find('.eqLogic-widget').each(function(){
        var eqLogic = {id :$(this).attr('data-eqlogic_id')}
        eqLogic.display = {};
        eqLogic.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px';
        eqLogic.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px';
        eqLogic.viewZone_id = $(this).closest('.eqLogicZone').attr('data-viewZone-id');
        eqLogic.order = order;
        eqLogics.push(eqLogic);
        order++;
      });
    });
    jeedom.view.setEqLogicOrder({
      eqLogics: eqLogics,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success:function(data){
        jeedom.cmd.setOrder({
          cmds: cmds,
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          }
        });
      }
    });
  }
}

function editWidgetCmdMode(_mode) {
  if(!isset(_mode)){
    if($('#bt_editDashboardWidgetOrder').attr('data-mode') != undefined && $('#bt_editDashboardWidgetOrder').attr('data-mode') == 1){
      editWidgetMode(0);
      editWidgetMode(1);
    }
    return;
  }
  
  if(_mode == 0) {
    $( ".eqLogic-widget.eqLogic_layout_table table.tableCmd").removeClass('table-bordered');
    $.contextMenu('destroy');
    if( $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_table table.tableCmd.ui-sortable').length > 0){
      try{
        $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_table table.tableCmd').sortable('destroy');
      }catch(e){
        
      }
    }
    if( $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_default.ui-sortable').length > 0){
      try{
        $('.eqLogic-widget.allowReorderCmd.eqLogic_layout_default').sortable('destroy');
      }catch(e){
        
      }
    }
    if( $('.eqLogic-widget.ui-draggable').length > 0){
      $('.eqLogic-widget.allowReorderCmd').off('mouseover','.cmd');
      $('.eqLogic-widget.allowReorderCmd').off('mouseleave','.cmd');
    }
  } else {
    $( ".eqLogic-widget.allowReorderCmd.eqLogic_layout_default").sortable({items: ".cmd"});
    $(".eqLogic-widget.eqLogic_layout_table table.tableCmd").addClass('table-bordered');
    $('.eqLogic-widget.eqLogic_layout_table table.tableCmd td').sortable({
      connectWith: '.eqLogic-widget.eqLogic_layout_table table.tableCmd td',items: ".cmd"});
      $('.eqLogic-widget.allowReorderCmd').on('mouseover','.cmd',function(){
        $('.eqLogic-widget').draggable('disable');
      });
      $('.eqLogic-widget.allowReorderCmd').on('mouseleave','.cmd',function(){
        $('.eqLogic-widget').draggable('enable');
      });
      $.contextMenu({
        selector: '.eqLogic-widget',
        zIndex: 9999,
        events: {
          show: function(opt) {
            $.contextMenu.setInputValues(opt, this.data());
          },
          hide: function(opt) {
            $.contextMenu.getInputValues(opt, this.data());
          }
        },
        items: {
          configuration: {
            name: "{{Configuration avancée}}",
            icon : 'fas fa-cog',
            callback: function(key, opt){
              saveWidgetDisplay()
              $('#md_modal').dialog({title: "{{Configuration du widget}}"});
              $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id='+$(this).attr('data-eqLogic_id')).dialog('open');
            }
          },
          sep1 : "---------",
          layoutDefaut: {
            name: "{{Defaut}}",
            icon : 'fas fa-square',
            disabled:function(key, opt) {
              return !$(this).hasClass('allowLayout') || !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard' : 'default'},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          layoutTable: {
            name: "{{Table}}",
            icon : 'fas fa-table',
            disabled:function(key, opt) {
              return !$(this).hasClass('allowLayout') || $(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard' : 'table'},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          sep2 : "---------",
          addTableColumn: {
            name: "{{Ajouter colonne}}",
            icon : 'fas fa-plus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbColumn' : parseInt($(this).find('table.tableCmd').attr('data-column')) + 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          addTableLine: {
            name: "{{Ajouter ligne}}",
            icon : 'fas fa-plus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbLine' : parseInt($(this).find('table.tableCmd').attr('data-line')) + 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          removeTableColumn: {
            name: "{{Supprimer colonne}}",
            icon : 'fas fa-minus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbColumn' : parseInt($(this).find('table.tableCmd').attr('data-column')) - 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          },
          removeTableLine: {
            name: "{{Supprimer ligne}}",
            icon : 'fas fa-minus',
            disabled:function(key, opt) {
              return !$(this).hasClass('eqLogic_layout_table');
            },
            callback: function(key, opt){
              saveWidgetDisplay()
              jeedom.eqLogic.simpleSave({
                eqLogic : {
                  id : $(this).attr('data-eqLogic_id'),
                  display : {'layout::dashboard::table::nbLine' : parseInt($(this).find('table.tableCmd').attr('data-line')) - 1},
                },
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
              });
            }
          }
        }
      })
    }
  }
  
  //Extensions__
  jQuery.fn.findAtDepth = function (selector, maxDepth) {
    var depths = [], i;
    if (maxDepth > 0) {
      for (i = 1; i <= maxDepth; i++) {
        depths.push('> ' + new Array(i).join('* > ') + selector);
      }
      selector = depths.join(', ');
    }
    return this.find(selector);
  };
  
  function initCheckBox(){}
  