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

"use strict"

$(function() {
  $(document)
    .ajaxStart(function () {
        $.showLoading()
    })
    .ajaxStop(function () {
        $.hideLoading()
    })
})

//cmd update:
var utid = 0

//design edit options conservation:
var planEditOption = {state:false, snap:false, grid:false, gridSize:false, highlight:true}

var isEditing = false

//js error in ! ui:
var JS_ERROR = []
window.addEventListener('error', function(event) {
  if (event.filename.indexOf('3rdparty/') != -1) {
    return
  }
  JS_ERROR.push(event)
  $('#bt_jsErrorModal').show()
  $.hideLoading()
})

//UI Time display:
setInterval(function() {
  var dateJeed = new Date
  dateJeed.setTime((new Date).getTime() + ((new Date).getTimezoneOffset() + serverTZoffsetMin)*60000 + clientServerDiffDatetime)
  $('#horloge').text(dateJeed.toLocaleTimeString())
}, 1000)

var modifyWithoutSave = false
function checkPageModified() {
  if (modifyWithoutSave) {
    if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
      $.hideLoading()
      return true
    }
    modifyWithoutSave = false
    return false
  }
}

//OnePage design PageLoader -------------------------------------
var PREVIOUS_PAGE = null
var PREVIOUS_LOCATION = null
var NO_POPSTAT = false
var printEqLogic = undefined
var jeedomBackgroundImg = null
function loadPage(_url, _noPushHistory) {
  PREVIOUS_LOCATION = window.location.href
  if (checkPageModified()) return
  if (JS_ERROR.length > 0) {
    document.location.href = _url
    return
  }

  closeJeedomMenu()

  if ($('.context-menu-root').length > 0) {
    try {
      $.contextMenu('destroy')
    } catch(e) {
      $('.context-menu-root').remove()
    }
  }

  try {
    $(".ui-dialog-content").dialog("close")
  } catch(e) {}

  if (!isset(_noPushHistory) || _noPushHistory == false) {
    try {
      if (PREVIOUS_PAGE == null) {
        window.history.replaceState('','', 'index.php?'+window.location.href.split("index.php?")[1])
        PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1]
      }
      if (PREVIOUS_PAGE == null || PREVIOUS_PAGE != _url) {
        window.history.pushState('','', _url)
        PREVIOUS_PAGE = _url
      }
    } catch(e) {}
  }

  if (isset(bootbox)) bootbox.hideAll()
  $.hideAlert()
  datePickerDestroy()
  jeedom.cmd.update = []
  jeedom.scenario.update = []
  printEqLogic = undefined
  if (__OBSERVER__ !== null) __OBSERVER__.disconnect()

  $('main').css({'padding-right': '', 'padding-left': '', 'margin-right': '', 'margin-left': ''})

  if (_url.indexOf('#') == -1) {
    var url = _url+'&ajax=1'
  } else {
    var n = _url.lastIndexOf("#")
    var url = _url.substring(0,n)+"&ajax=1"+_url.substring(n)
  }

  jeedomBackgroundImg = null
  $('.backgroundforJeedom').css({
    'background-image':'',
    'background-position':'center center',
    'background-repeat':'no-repeat',
    'background-size':'cover'
  })

  $.clearDivContent('div_pageContainer')
  isEditing = false
  $('body').off('mouseenter mouseleave')
  $('#div_pageContainer').off()

  $('#div_pageContainer').load(url, function() {
    if (_url.match('#') && _url.split('#')[1] != '' && $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').html() != undefined) {
      $('.nav-tabs a[href="#' + _url.split('#')[1] + '"]').trigger('click')
    }
    $('#bt_getHelpPage').attr('data-page',getUrlVars('p')).attr('data-plugin',getUrlVars('m'))
    initPage()
    $('body').attr('data-page', getUrlVars('p')).trigger('jeedom_page_load')
    if (jeedomBackgroundImg !== null) {
      setBackgroundImg(jeedomBackgroundImg)
    } else {
      setBackgroundImg('')
    }
    if (window.location.hash != '' && $('.nav-tabs a[href="'+window.location.hash+'"]').length != 0) {
      $('.nav-tabs a[href="'+window.location.hash+'"]').click()
    }

    setTimeout(function() {
      modifyWithoutSave = false
    },500)
  })

  setTimeout(function() {
    //scenarios uses special tooltips not requiring destroy.
    if ($('body').attr('data-page') != 'scenario') {
      if (__OBSERVER__ !== null) {
        var targetNode = document.getElementById('div_mainContainer')
        if (targetNode) __OBSERVER__.observe(targetNode, _observerConfig_)
      } else {
        createObserver()
      }
    }
  }, 500)

  return
}

$(function() {
  var $body = $('body')
  if (getDeviceType()['type'] == 'desktop') userDeviceType = 'desktop'
  $body.attr('data-device', userDeviceType)

  $.alertTrigger = function() {
    initRowOverflow()
  }
  $body.attr('data-page', getUrlVars('p'))
  $body.off('jeedom_page_load').on('jeedom_page_load',function() {
    if (getUrlVars('saveSuccessFull') == 1) {
      $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'})
      PREVIOUS_PAGE=window.location.href.split('&saveSuccessFull')[0]+window.location.hash
      window.history.replaceState({}, document.title, window.location.href.split('&saveSuccessFull')[0]+window.location.hash)
    }
    if (getUrlVars('removeSuccessFull') == 1) {
      $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'})
      PREVIOUS_PAGE=window.location.href.split('&saveSuccessFull')[0]+window.location.hash
      window.history.replaceState({}, document.title, window.location.href.split('&removeSuccessFull')[0]+window.location.hash)
    }
  })

  //tab in url:
  if (window.location.hash != '' && $('.nav-tabs a[href="'+window.location.hash+'"]').length != 0) {
    $('.nav-tabs a[href="'+window.location.hash+'"]').click()
  }

  //browser history:
  $body.on('shown.bs.tab','.nav-tabs a', function(event) {
    if (event.target.hash == '') {
      return
    }
    if ($(this).closest('.ui-dialog-content').html() !== undefined) {
      return
    }
    if (PREVIOUS_PAGE == null) {
      window.history.replaceState('','', 'index.php?'+window.location.href.split("index.php?")[1])
      PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1]
    }
    window.location.hash = event.target.hash
  })
  window.addEventListener('hashchange', function(event) {
    NO_POPSTAT = true
    setTimeout(function() {
      NO_POPSTAT = false
    },200)
  })
  window.addEventListener('popstate', function(event) {
    if (event.state === null) {
      if (NO_POPSTAT) {
        NO_POPSTAT = false
        return
      }
      if (window.location.hash != '' && $('.nav-tabs a[href="'+window.location.hash+'"]:visible').length != 0) {
        $('.nav-tabs a[href="'+window.location.hash+'"]').click()
      } else if (PREVIOUS_PAGE !== null && PREVIOUS_PAGE.includes('#') && PREVIOUS_PAGE.split('#')[0] != 'index.php?'+window.location.href.split("index.php?")[1].split('#')[0]) {
        if (checkPageModified()) return
        loadPage('index.php?'+window.location.href.split("index.php?")[1],true)
        PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1]
      }
      return
    }
    if (checkPageModified()) return
    loadPage('index.php?'+window.location.href.split("index.php?")[1],true)
    PREVIOUS_PAGE = 'index.php?'+window.location.href.split("index.php?")[1]
  })

  setJeedomTheme()
  changeJeedomThemeAuto()

  setJeedomMenu()
  initJeedomModals()
  setJeedomGlobalUI()

  $(window).resize(function() {
    initRowOverflow()
  })

  initPage()
  if (jeedomBackgroundImg != null) {
    setBackgroundImg(jeedomBackgroundImg)
  } else {
    setBackgroundImg('')
  }

  //options for notify()
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

  setTimeout(function() {
    initTooltips()
    createObserver()
    $body.trigger('jeedom_page_load')
  }, 1)
})

//Jeedom theme__
var BACKGROUND_IMG = ''
function setJeedomTheme() {
  var $body = $('body')

  if (getCookie('currentTheme') == 'alternate') {
    var themeButton = '<i class="fas fa-random"></i> {{Thème principal}}'
    $('#bt_switchTheme').html(themeButton)
    $('#bootstrap_theme_css').attr('data-nochange', 1)
  }

  if (jeedom.theme.currentTheme) {
    $body.attr('data-theme', jeedom.theme.currentTheme)
  }

  //button event:
  $body.on('click', '#bt_switchTheme', function() {
    closeJeedomMenu()
    var theme = 'core/themes/'+jeedom.theme.default_bootstrap_theme_night+'/desktop/'+jeedom.theme.default_bootstrap_theme_night+'.css'
    var themeShadows = 'core/themes/'+jeedom.theme.default_bootstrap_theme_night+'/desktop/shadows.css'
    var themeCook = 'alternate'
    var themeButton = '<i class="fas fa-random"></i> {{Thème principal}}'
    $('#bootstrap_theme_css').attr('data-nochange', 1)

    if ($('#bootstrap_theme_css').attr('href').split('?md5')[0] == theme) {
      $body.attr('data-theme', jeedom.theme.default_bootstrap_theme)
      theme = 'core/themes/'+jeedom.theme.default_bootstrap_theme+'/desktop/'+jeedom.theme.default_bootstrap_theme+'.css'
      themeShadows = 'core/themes/'+jeedom.theme.default_bootstrap_theme+'/desktop/shadows.css';
      themeCook = 'default'
      themeButton = '<i class="fas fa-random"></i> {{Thème alternatif}}'
    } else {
      $body.attr('data-theme', jeedom.theme.default_bootstrap_theme_night)
    }
    setCookie('currentTheme', themeCook, 30)
    $('#bootstrap_theme_css').attr('href', theme)
    $('#bt_switchTheme').html(themeButton)
    if ($("#shadows_theme_css").length > 0) $('#shadows_theme_css').attr('href', themeShadows)
    setBackgroundImg(BACKGROUND_IMG)
    triggerThemechange()
  })

  if (typeof jeedom.theme != 'undefined' && typeof jeedom.theme.css != 'undefined' && Object.keys(jeedom.theme.css).length > 0) {
    for (var i in jeedom.theme.css) {
      document.body.style.setProperty(i,jeedom.theme.css[i])
    }
  }

  if (typeof jeedom.theme['interface::advance::coloredIcons'] != 'undefined' && jeedom.theme['interface::advance::coloredIcons'] == '1') {
    $body.attr('data-coloredIcons', 1)
  } else {
    $body.attr('data-coloredIcons', 0)
  }
}

function changeJeedomThemeAuto() {
  if (typeof jeedom.theme == 'undefined') return
  if (typeof jeedom.theme.theme_changeAccordingTime == 'undefined' || jeedom.theme.theme_changeAccordingTime == 0) return
  if (typeof jeedom.theme.default_bootstrap_theme == 'undefined' || typeof jeedom.theme.default_bootstrap_theme_night == 'undefined') return
  if (jeedom.theme.default_bootstrap_theme == jeedom.theme.default_bootstrap_theme_night) return

  checkThemechange()
  setInterval(function() {
    checkThemechange()
  }, 60000)
}

function checkThemechange() {
  if (getCookie('currentTheme') == 'alternate' || $('#bootstrap_theme_css').attr('data-nochange') == 1) return

  var theme = jeedom.theme.default_bootstrap_theme_night
  var themeCss = 'core/themes/'+jeedom.theme.default_bootstrap_theme_night+'/desktop/' + jeedom.theme.default_bootstrap_theme_night + '.css'
  var currentTime = parseInt((new Date()).getHours()*100+ (new Date()).getMinutes());

  if (parseInt(jeedom.theme.theme_start_day_hour.replace(':','')) <  currentTime && parseInt(jeedom.theme.theme_end_day_hour.replace(':','')) > currentTime) {
    theme  = jeedom.theme.default_bootstrap_theme
    themeCss = 'core/themes/'+jeedom.theme.default_bootstrap_theme+'/desktop/' + jeedom.theme.default_bootstrap_theme + '.css'
  }

  var currentTheme = $('#bootstrap_theme_css').attr('href')
  if (currentTheme.indexOf('?md5') != -1) {
    currentTheme = currentTheme.substring(0, currentTheme.indexOf('?md5'))
  }
  if (currentTheme != themeCss) {
    $.get(themeCss)
    .done(function() {
      $('#bootstrap_theme_css').attr('href', themeCss)
      $('body').attr('data-theme',theme)
      if ($("#shadows_theme_css").length > 0) $('#shadows_theme_css').attr('href', 'core/themes/'+theme+'/desktop/shadows.css')
      setBackgroundImg(BACKGROUND_IMG)
      triggerThemechange()
    })
    .fail(function() {
      console.error("changeThemeAuto: can't find theme file " + themeCss)
    })
  }
}

function triggerThemechange() {
  //set jeedom logo:
  if ($('body')[0].hasAttribute('data-theme')) {
    var currentTheme = $('body').attr('data-theme')
    if (currentTheme.endsWith('Dark')) {
      $('#homeLogoImg').attr('src', jeedom.theme.logo_dark)
    } else {
      $('#homeLogoImg').attr('src', jeedom.theme.logo_light)
    }
  }

  //trigger event for widgets:
  if ($('body').attr('data-page') && ['dashboard', 'view', 'plan','widgets'].includes($('body').attr('data-page')) ) {
    if (currentTheme.endsWith('Light')) {
      $('body').trigger('changeThemeEvent', ['Light'])
    } else {
      $('body').trigger('changeThemeEvent', ['Dark'])
    }
  }
}

function setBackgroundImg(_path) {
  if (!isset(jeedom) || !isset(jeedom.theme) || !isset(jeedom.theme.showBackgroundImg) || jeedom.theme.showBackgroundImg == 0) {
    return
  }
  BACKGROUND_IMG = _path
  if (_path === null) {
    document.body.style.setProperty('--dashBkg-url','url("")')
    $('.backgroundforJeedom').css('background-image','url("")')
  } else if (_path === '') {
    var mode = 'light'
    if ($('body').attr('data-theme') == 'core2019_Dark') {
      mode = 'dark'
    }
    _path = 'core/img/background/jeedom_abstract_01_'+mode+'.jpg'
    if (['administration','profils'].indexOf($('body').attr('data-page')) != -1) {
      _path = 'core/img/background/jeedom_abstract_03_'+mode+'.jpg'
    }
    if (['display','eqAnalyse','log','history','report','health'].indexOf($('body').attr('data-page')) != -1) {
      _path = 'core/img/background/jeedom_abstract_02_'+mode+'.jpg'
    }
    $('.backgroundforJeedom').css('background-image','url("'+_path+'")')
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")')
  } else {
    $('.backgroundforJeedom').css('background-image','url("'+_path+'")')
    document.body.style.setProperty('--dashBkg-url','url("../../../../'+_path+'")')
  }
}

//Jeedom UI__
function initJeedomModals() {
  $.fn.modal.Constructor.prototype.enforceFocus = function() {}

  if (isset(jeedom_langage) ) {
    var lang = jeedom_langage.substr(0, 2)
    var supportedLangs = ['fr', 'de', 'es']
    if ( lang != 'en' && supportedLangs.includes(lang) ) {
      bootbox.addLocale('fr', {OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Annuler'})
      bootbox.addLocale('de', {OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Abbrechen'})
      bootbox.addLocale('es', {OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Anular'})
      bootbox.setLocale('fr') //needed for date format
    }
  }

  $('body').on('show', '.modal',function() {
    document.activeElement.blur()
    $(this).find('.modal-body :input:visible').first().focus()
  });

  $('body').on('focusin','.bootbox-input', function(event) {
    event.stopPropagation()
  })

  $('.bootbox.modal').on('shown.bs.modal', function() {
    $(this).find(".bootbox-accept").focus()
  })

  $('#md_reportBug').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: ((jQuery(window).height() - 100) < 700) ? jQuery(window).height() - 100 : 700,
    width: ((jQuery(window).width() - 100) < 900) ? (jQuery(window).width() - 100) : 900,
    position: { my: 'center center-10', at: 'center center', of: window },
    open: function() {
      $('body').css({overflow: 'hidden'})
      $(this).closest('.ui-dialog').find(':button').blur()
      $(this).dialog({
        height: ((jQuery(window).height() - 100) < 700) ? jQuery(window).height() - 100 : 700,
        width: ((jQuery(window).width() - 100) < 900) ? (jQuery(window).width() - 100) : 900,
        position: { my: 'center center-10', at: 'center center', of: window }
      })
      setTimeout(function() {initTooltips($('#md_reportBug'))}, 500)
    },
    beforeClose: function(event, ui) {
      emptyModal('md_reportBug')
    }
  })

  $('#md_modal').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (jQuery(window).height() - 125),
    width: ((jQuery(window).width() - 50) < 1500) ? (jQuery(window).width() - 50) : 1500,
    position: {my: 'center bottom-50', at: 'center bottom', of: window},
    open: function() {
      $('body').css({overflow: 'hidden'})
      $(this).closest('.ui-dialog').find(':button').blur()
      $(this).dialog({
        height: (jQuery(window).height() - 125),
        width: ((jQuery(window).width() - 50) < 1500) ? (jQuery(window).width() - 50) : 1500,
        position: {my: 'center bottom-50', at: 'center bottom', of: window}
      })
      setTimeout(function() {initTooltips($('#md_modal'))}, 500)
    },
    beforeClose: function(event, ui) {
      emptyModal('md_modal')
    }
  })

  $('#md_modal2').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (jQuery(window).height() - 125),
    width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
    position: {my: 'center bottom-50', at: 'center bottom',  of: window},
    open: function() {
      $('body').css({overflow: 'hidden'})
      $(this).closest('.ui-dialog').find(':button').blur()
      $(this).dialog({
        height: (jQuery(window).height() - 125),
        width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
        position: {my: 'center bottom-50', at: 'center bottom',  of: window},
      })
      setTimeout(function() {initTooltips($('#md_modal2'))}, 500)
    },
    beforeClose: function(event, ui) {
      emptyModal('md_modal2')
    }
  })

  $('#md_modal3').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (jQuery(window).height() - 125),
    width: ((jQuery(window).width() - 250) < 1000) ? (jQuery(window).width() - 50) : 1000,
    position: {my: 'center bottom-50', at: 'center bottom',  of: window},
    open: function() {
      $('body').css({overflow: 'hidden'})
      $(this).closest('.ui-dialog').find(':button').blur()
      $(this).dialog({
        height : (jQuery(window).height() - 125),
        width : ((jQuery(window).width() - 250) < 1000) ? (jQuery(window).width() - 50) : 1000,
        position: {my: 'center bottom-50', at: 'center bottom',  of: window},
      })
      setTimeout(function() {initTooltips($('#md_modal3'))}, 500)
    },
    beforeClose: function(event, ui) {
      emptyModal('md_modal3')
    }
  })

  function emptyModal(_id='') {
    if (_id == '') return
    $('body').css({overflow: 'inherit'})
    $.clearDivContent(_id)
  }
}

function setButtonCtrlHandler(_button, _title, _uri, _modal='#md_modal') {
  $(_button).on('click', function(event) {
    closeJeedomMenu()
    if (isEditing == true) return false
    if (event.ctrlKey || event.metaKey || event.originalEvent.which == 2) {
      var title = encodeURI(_title)
      var url = '/index.php?v=d&p=modaldisplay&loadmodal='+_uri+'&title=' + title
      window.open(url).focus()
    } else {
      $(_modal).dialog('close')
      $(_modal).dialog({title: _title}).load('index.php?v=d&modal='+_uri).dialog('open')
    }
  })
  $(_button).on('mouseup', function(event) {
    if( event.which == 2 ) {
      event.preventDefault()
      $(_button).trigger(jQuery.Event('click', { ctrlKey: true }))
    }
  })
}

function setJeedomGlobalUI() {
  if (typeof jeedom_firstUse != 'undefined' && isset(jeedom_firstUse) && jeedom_firstUse == 1 && getUrlVars('noFirstUse') != 1) {
    $('#md_modal').dialog({title: "{{Bienvenue dans Jeedom}}"}).load('index.php?v=d&modal=first.use').dialog('open')
  }

  $(window).bind('beforeunload', function() {
    if (modifyWithoutSave) {
      return '{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}';
    }
  })

  setButtonCtrlHandler('#bt_showEventInRealTime', '{{Evénements en temps réel}}', 'log.display&log=event', '#md_modal')
  setButtonCtrlHandler('#bt_showNoteManager', '{{Notes}}', 'note.manager', '#md_modal')
  setButtonCtrlHandler('#bt_showExpressionTesting', "{{Testeur d'expression}}", 'expression.test', '#md_modal')
  setButtonCtrlHandler('#bt_showDatastoreVariable', '{{Variables des scénarios}}', 'dataStore.management&type=scenario', '#md_modal')
  setButtonCtrlHandler('#bt_showSearching', '{{Recherche}}', 'search', '#md_modal')

  $('#bt_gotoDashboard').on('click',function(event) {
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.stopPropagation()
      return
    }
    loadPage('index.php?v=d&p=dashboard')
  })

  $('#bt_gotoView').on('click',function(event) {
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.stopPropagation()
      return
    }
    loadPage('index.php?v=d&p=view')
  })

  $('#bt_gotoPlan').on('click',function(event) {
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.stopPropagation()
      return
    }
    loadPage('index.php?v=d&p=plan')
  })

  $('#bt_gotoPlan3d').on('click',function(event) {
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.stopPropagation()
      return
    }
    loadPage('index.php?v=d&p=plan3d')
  })

  $('#bt_jeedomAbout').on('click', function() {
    closeJeedomMenu()
    $('#md_modal').dialog({title: "{{A propos}}"}).load('index.php?v=d&modal=about').dialog('open')
  })

  $('#bt_getHelpPage').on('click',function() {
    jeedom.getDocumentationUrl({
      plugin: $(this).attr('data-plugin'),
      page: $(this).attr('data-page'),
      theme: $('body').attr('data-theme'),
      error: function(error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      },
      success: function(url) {
        window.open(url,'_blank')
      }
    });
  })

  $('.bt_reportBug').on('click',function(event) {
    if (!getDeviceType()['type'] == 'desktop' || $(window).width() < 768) {
      event.preventDefault()
      return
    }
    closeJeedomMenu()
    $('#md_reportBug').load('index.php?v=d&modal=report.bug').dialog('open')
  })

  $('#bt_messageModal').on('click',function() {
    $('#md_modal').dialog({title: "{{Centre de Messages}}"}).load('index.php?v=d&p=message&ajax=1').dialog('open')
  })
  $('#bt_jsErrorModal').on('click',function() {
    $('#md_modal').dialog({title: "{{Erreur Javascript}}"}).load('index.php?v=d&modal=js.error').dialog('open')
  })

  $('body').on('click', '.objectSummaryParent',function() {
    if ($('body').attr('data-page') == "overview" && $(this).parents('.objectSummaryglobal').length == 0) return false
    loadPage('index.php?v=d&p=dashboard&summary='+$(this).data('summary')+'&object_id='+$(this).data('object_id'))
  })

  $('body').off('click','.jeeHelper[data-helper=cron]').on('click','.jeeHelper[data-helper=cron]',function() {
    var el = $(this).closest('div').find('input')
    jeedom.getCronSelectModal({},function(result) {
      el.value(result.value)
    })
  })

  $('body').on({
    'keydown': function(event) {
      if (event.key == 'Escape') {
        $('#categoryfilter li .catFilterKey').prop("checked", true)
        $('#dashTopBar button.dropdown-toggle').removeClass('warning')
        $(this).val('').keyup()
      }
    }
  }, 'input[id^="in_search"]')
}

//Initiators__
function initPage() {
  initTableSorter()
  initReportMode()
  $.initTableFilter()
  initRowOverflow()
  initHelp()
  initTextArea()
  $('.nav-tabs a').on('click',function() {
    var scrollHeight = $(document).scrollTop()
    $(this).tab('show')
    $(window).scrollTop(scrollHeight)
    setTimeout(function() {
      $(window).scrollTop(scrollHeight)
    }, 0)
  })

  setTimeout(function() { initTooltips() }, 750)
  if (getDeviceType()['type'] == 'desktop') $("input[id^='in_search']").first().focus()
}

var __OBSERVER__ = null
var _observerConfig_ = {
  attributes: true,
  childList: true,
  characterData: true,
  subtree: true
}
function createObserver() {
  var mainContent = document.getElementById('div_mainContainer')
  if (mainContent) {
    __OBSERVER__ = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if ( mutation.type == 'childList' ) {
          if (mutation.addedNodes.length >= 1) {
            if (mutation.addedNodes[0].nodeName != '#text') {
              initTooltips($(mutation.target))
            }
          }
        } else if (mutation.type == 'attributes') {
          if (mutation.attributeName == 'title') initTooltips($(mutation.target))
        }
      })
    })
    __OBSERVER__.observe(mainContent, _observerConfig_)
  }
}

var TOOLTIPSOPTIONS = {
  arrow: false,
  delay: 650,
  interactive: false,
  contentAsHTML: true
}
function initTooltips(_el) {
  if (!_el) {
    $('.tooltips:not(.tooltipstered), [title]:not(.ui-button)').tooltipster(TOOLTIPSOPTIONS)
  } else {
    //cmd update:
    if (_el.parents('.cmd-widget[title]').length) {
      var me = _el.closest('.cmd-widget[title]')
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

function initTextArea() {
  $('body').on('change keyup keydown paste cut', 'textarea.autogrow', function() {
    $(this).height(0).height(this.scrollHeight)
  });
}

function initRowOverflow() {
  var hWindow = $(window).outerHeight() - $('header').outerHeight() - 5
  if ($('#div_alert').is(':visible')) {
    hWindow -= ($('#div_alert').outerHeight() + 20)
  }
  if ($('.row-overflow').attr('data-offset') != undefined) {
    hWindow -= $('.row-overflow').attr('data-offset')
  }
  $('.row-overflow > div:not(#div_displayObjectList)').height(hWindow).css({'padding-top': '0px', 'overflow-y': 'auto', 'overflow-x': 'hidden', 'padding-top': '5px'})
}

function initReportMode() {
  if (getUrlVars('report') == 1) {
    $('header').hide()
    $('footer').hide()
    $('#div_mainContainer').css('margin-top', '-50px')
    $('#wrap').css('margin-bottom', '0px')
    $('.reportModeVisible').show()
    $('.reportModeHidden').hide()
  }
}

function initTableSorter(filter) {
  var widgets = ['uitheme', 'zebra', 'resizable']
  if (!filter) {
    filter = true
  }
  if (filter !== false) {
    widgets.push('filter')
  }
  $('table.tablesorter').tablesorter({
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
      $(table).find('thead .tablesorter-header-inner').append('<i class="tablesorter-icon"></i>')
    }
  })
  $('table.tablesorter').css('width','')
}

function initHelp() {
  $('.help').each(function() {
    if ($(this).attr('data-help') != undefined) {
      $(this).append(' <sup><i class="fas fa-question-circle tooltips" title="'+$(this).attr('data-help')+'" style="font-size : 1em;color:grey;"></i></sup>')
    }
  })
}

function datePickerDestroy() {
  $('.in_datepicker').datepicker( "destroy" )
  $('.in_datepicker').removeClass("hasDatepicker").removeAttr('id')
  $('#ui-datepicker-div').remove()
}

function datePickerInit() {
  var datePickerRegion = jeedom_langage.substring(0,2)
  if (isset($.datepicker.regional[datePickerRegion])) {
    var datePickerRegional = $.datepicker.regional[datePickerRegion]
  } else {
    var datePickerRegional = $.datepicker.regional['en']
  }
  datePickerRegional.dateFormat = "yy-mm-dd"
  $('.in_datepicker').datepicker(datePickerRegional)
}

//General functions__
function normTextLower(_text) {
  return _text.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase()
}

function linkify(inputText) {
  if(!inputText || inputText == '' || inputText === null){
    return '';
  }
  var replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim
  var replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>')
  var replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim
  var replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>')
  var replacePattern3 = /(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/gim
  var replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>')
  return replacedText
}

var MESSAGE_NUMBER
function refreshMessageNumber() {
  jeedom.message.number({
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success : function(_number) {
      MESSAGE_NUMBER = _number;
      if (_number == 0 || _number == '0') {
        $('#span_nbMessage').hide()
      } else {
        $('#span_nbMessage').html(_number).show()
      }
    }
  })
}

var UPDATE_NUMBER
function refreshUpdateNumber() {
  jeedom.update.number({
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success : function(_number) {
      UPDATE_NUMBER = _number;
      if (_number == 0 || _number == '0') {
        $('#span_nbUpdate').hide()
      } else {
        $('#span_nbUpdate').html(_number).show()
      }
    }
  })
}

function notify(_title, _text, _class_name) {
  if (_title == '' && _text == '') {
    return true
  }
  if (isset(_class_name) != '' && isset(toastr[_class_name])) {
    toastr[_class_name](_text, _title)
  } else {
    toastr.info(_text, _title)
  }
}

function sleep(milliseconds) {
  var start = new Date().getTime()
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds) {
      break
    }
  }
}

var uniqId_count = 0
function uniqId(_prefix) {
  if (typeof _prefix == 'undefined') {
    _prefix = 'jee-uniq'
  }
  var result = _prefix +'-'+ uniqId_count + '-' + Math.random().toString(36).substring(8)
  uniqId_count++
  if ($('#'+result).length) {
    return uniqId(_prefix)
  }
  return result
}

function taAutosize() {
  autosize($('.ta_autosize'))
  autosize.update($('.ta_autosize'))
}

function hexToRgb(hex) {
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null
}

function componentToHex(c) {
  var hex = c.toString(16)
  return hex.length == 1 ? "0" + hex : hex
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

function addOrUpdateUrl(_param,_value,_title) {
  var url = new URL(window.location.href)
  var query_string = url.search
  var search_params = new URLSearchParams(query_string)
  if (_value == null) {
    search_params.delete(_param)
  } else {
    search_params.set(_param, _value)
  }
  url.search = search_params.toString()
  url = url.toString()
  if (url != window.location.href) {
    if (url.indexOf('#') != -1) {
      url = url.substring(0, url.indexOf('#'))
    }
    if (PREVIOUS_PAGE != 'index.php?'+window.location.href.split("index.php?")[1]) {
      window.history.pushState('','', window.location.href)
    }
    if (_title && _title != '') {
      document.title = _title
    }
    window.history.pushState('','', url.toString())
    PREVIOUS_PAGE = 'index.php?'+url.split("index.php?")[1]
  } else {
    if (_title && _title != '') {
      document.title = _title
    }
  }
}

function closeJeedomMenu() {
  $('#jeedomMenuBar .navbar-nav').addClass('disabled')
  setTimeout(function() {
    $('#jeedomMenuBar .navbar-nav').removeClass('disabled')
  }, 250)

  if ($(window).width() < 768) {
    $('#jeedomMenuBar .navbar-collapse.in').removeClass('in')
  }
}

//Global UI functions__
var userDeviceType = 'mobile'
function setJeedomMenu() {
  $('body').on('click', 'a', function(event) {
    if ($(this).hasClass('noOnePageLoad')) {
      return
    }
    if ($(this).hasClass('fancybox-nav')) {
      return
    }
    if ($(this).attr('href') == undefined || $(this).attr('href') == '' || $(this).attr('href') == '#') {
      return
    }
    if ($(this).attr('href').match("^data:")) {
      return
    }
    if ($(this).attr('href').match("^http")) {
      return
    }
    if ($(this).attr('href').match("^#")) {
      return
    }
    if ($(this).attr('target') == '_blank') {
      return
    }

    if (!$(this).hasClass('navbar-brand')) closeJeedomMenu()

    event.preventDefault()
    event.stopPropagation()
    loadPage($(this).attr('href'))
  })

  //one submenu opened at a time in mobile:
  $('body').on('click', '#jeedomMenuBar .navbar-nav > li > input', function() {
    var checked = $(this).prop("checked")
    $('#jeedomMenuBar .navbar-nav li > input').prop("checked", false)
    $(this).prop("checked", checked)
  })
  $('body').on('click', '#jeedomMenuBar .navbar-nav > li > ul > li > input', function() {
    var checked = $(this).prop("checked")
    $('#jeedomMenuBar .navbar-nav > li > ul > li > input').prop("checked", false)
    $(this).prop("checked", checked)
  })
}

function positionEqLogic(_id, _preResize, _scenario) {
  if (_id != undefined) {
    var widget = (_scenario) ? $('div.scenario-widget[data-scenario_id='+_id+']') : $('div.eqLogic-widget[data-eqlogic_id='+_id+']')
    widget.css({'margin': '0px', 'padding': '0px'})
    if ($(this).width() == 0) {
      $(this).width('230')
    }
    if ($(this).height() == 0) {
      $(this).height('110')
    }
    if (init(_preResize, true)) {
      widget.width(Math.floor(widget.width() / jeedom.theme['widget::step::width']) * jeedom.theme['widget::step::width'] - (2 * jeedom.theme['widget::margin']))
      widget.height(Math.floor(widget.height() / jeedom.theme['widget::step::height']) * jeedom.theme['widget::step::height'] - (2 * jeedom.theme['widget::margin']))
    }
    widget.width(calculWidgetSize(widget.width(),jeedom.theme['widget::step::width'],jeedom.theme['widget::margin']))
    widget.height(calculWidgetSize(widget.height(),jeedom.theme['widget::step::height'],jeedom.theme['widget::margin']))
    if (!widget.hasClass(widget.attr('data-category'))) {
      widget.addClass(widget.attr('data-category'))
    }
    widget.css('margin',jeedom.theme['widget::margin']+'px')
  } else {
    $('div.eqLogic-widget:not(.jeedomAlreadyPosition), div.scenario-widget:not(.jeedomAlreadyPosition)')
    .css('margin','0px')
    .css('padding','0px')
    .each(function() {
      if ($(this).width() == 0) {
        $(this).width('230')
      }
      if ($(this).height() == 0) {
        $(this).height('110')
      }
      $(this).width(calculWidgetSize($(this).width(),jeedom.theme['widget::step::width'],jeedom.theme['widget::margin']))
      .height(calculWidgetSize($(this).height(),jeedom.theme['widget::step::height'],jeedom.theme['widget::margin']))
      if (!$(this).hasClass($(this).attr('data-category'))) {
        $(this).addClass($(this).attr('data-category'))
      }
    })
    .css('margin',jeedom.theme['widget::margin']+'px')
    $('div.eqLogic-widget, div.scenario-widget').addClass('jeedomAlreadyPosition')
  }
}

function showHelpModal(_name, _plugin) {
  if (init(_plugin) != '' && _plugin != undefined) {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_plugin_' + _plugin + '.php #primary', function() {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
        $('a[href="#div_helpSpe"]').click()
        $('a[href="#div_helpWebsite"]').hide()
      } else {
        $('a[href="#div_helpWebsite"]').show().click()
      }
    })
    $('#div_helpSpe').load('index.php?v=d&plugin=' + _plugin + '&modal=help.' + init(_name))
  } else {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_' + init(_name) + '.php #primary', function() {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $.trim($('#div_helpWebsite').text()) == '') {
        $('a[href="#div_helpSpe"]').click()
        $('a[href="#div_helpWebsite"]').hide()
      } else {
        $('a[href="#div_helpWebsite"]').show().click()
      }
    })
    $('#div_helpSpe').load('index.php?v=d&modal=help.' + init(_name))
  }
}

function chooseIcon(_callback, _params) {
  if ($("#mod_selectIcon").length == 0) {
    $('#div_pageContainer').append('<div id="mod_selectIcon"></div>')
    $("#mod_selectIcon").dialog({
      title: '{{Choisissez une icône}}',
      closeText: '',
      autoOpen: false,
      modal: true,
      height: (jQuery(window).height() - 150),
      width: 1500,
      open: function() {
        if ((jQuery(window).width() - 50) < 1500) {
          $('#mod_selectIcon').dialog({width: jQuery(window).width() - 50})
        }
        $('body').css({overflow: 'hidden'});
        setTimeout(function() {initTooltips($("#mod_selectIcon"))},500)
      },
      beforeClose: function(event, ui) {
        $('body').css({overflow: 'inherit'})
      }
    });
  }
  var url = 'index.php?v=d&modal=icon.selector'
  if(_params && _params.img && _params.img === true) {
    url += '&showimg=1'
  }
  if (_params && _params.icon) {
    var icon = _params.icon
    var replaceAr = ['icon_blue', 'icon_green', 'icon_orange', 'icon_red', 'icon_yellow']
    replaceAr.forEach(function(element) {
      icon = icon.replace(element, '')
    })
    icon = icon.trim().replace(new RegExp('  ', 'g'), ' ')
    icon = icon.trim().replace(new RegExp(' ', 'g'), '.')
    url += '&selectIcon=' + icon
  }
  if (_params && _params.color) {
    url += '&colorIcon=' + _params.color
  }
  $('#mod_selectIcon').empty().load(url,function() {
    $("#mod_selectIcon").dialog('option', 'buttons', {
      "Annuler": function() {
        $(this).dialog("close")
      },
      "Valider": function() {
        var icon = $('.iconSelected .iconSel').html();
        if (icon == undefined) {
          icon = ''
        }
        icon = icon.replace(/"/g, "'")
        _callback(icon)
        $(this).dialog('close')
      }
    })
    $('#mod_selectIcon').dialog('open')
  })
}

function calculWidgetSize(_size,_step,_margin) {
  var result = Math.ceil(_size / _step) * _step - (2*_margin)
  if (result < _size) {
    result += Math.ceil((_size - result) / _step)* _step
  }
  return result
}

function getOpenedModal() {
  var _return = false
  var modals = ['md_reportBug', 'md_modal', 'md_modal2', 'md_modal3', 'ui-id-5']
  modals.forEach(function(_modal) {
    if ($('.ui-dialog[aria-describedby="'+_modal+'"]').is(':visible') == true) {
      _return = _modal
    }
  })
  return _return
}

//Extensions__
jQuery.fn.findAtDepth = function(selector, maxDepth) {
  var depths = [], i
  if (maxDepth > 0) {
    for (i = 1; i <= maxDepth; i++) {
      depths.push('> ' + new Array(i).join('* > ') + selector)
    }
    selector = depths.join(', ')
  }
  return this.find(selector)
}

jQuery.fn.setCursorPosition = function(position) {
  if(this.lengh == 0) return this;
  return $(this).setSelection(position, position)
}

jQuery.fn.setSelection = function(selectionStart, selectionEnd) {
  if(this.lengh == 0) return this;
  input = this[0]
  if (input.createTextRange) {
    var range = input.createTextRange()
    range.collapse(true)
    range.moveEnd('character', selectionEnd)
    range.moveStart('character', selectionStart)
    range.select()
  } else if (input.setSelectionRange) {
    input.focus()
    input.setSelectionRange(selectionStart, selectionEnd)
  }
  return this
}

//deprecated
function initCheckBox() {}

function editWidgetCmdMode(_mode) {}
