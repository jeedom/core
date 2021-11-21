"use strict"

var jeedomUtils = {}
jeedomUtils.backgroundIMG = null
jeedomUtils._elBackground = null

$(function() {
  $(document)
  .ajaxStart(function () {
    $.showLoading()
  })
  .ajaxStop(function () {
    $.hideLoading()
  })
})

window.addEventListener('error', function(event) {
  $.hideLoading()
})

//allow shortcut as app:
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('sw.js').then(function(registration) {
      console.log('ServiceWorker registration successful with scope: ', registration.scope)
    }, function(err) {
      console.log('ServiceWorker registration failed: ', err)
    })
  })
}

var PANEL_SCROLL = 0
var APP_MODE = false
var TAPHOLD = false
var CURRENT_PAGE = null
$.event.special.tap.emitTapOnTaphold = false

$(function() {
  jeedomUtils._elBackground = $('#backgroundforJeedom')
  
  $(window).on('orientationchange', function(event) {
    //wait to get new width:
    window.setTimeout(function() {
      $('body').trigger('orientationChanged', [event.orientation])
    }, 200)
  })
  
  if (getUrlVars('app_mode') == 1) {
    APP_MODE = true
    jeedomUtils._elBackground.height('100%').css('top','0')
    $('#pagecontainer').prepend($('#searchContainer'))
    $('div[data-role=header]').remove()
    $('#searchContainer').css('top',0)
    $('#bt_eraseSearchInput').css('top',0)
    $('#pagecontainer').append('<a href="#bottompanel" id="bt_bottompanel" class="ui-btn ui-btn-inline ui-btn-fab ui-btn-raised clr-primary waves-effect waves-button waves-effect waves-button" style="position:fixed;bottom:10px;right:10px;"><i class="fas fa-bars" style="position:relative;top:-3px"></i></a>')
  }
  
  window.addEventListener("contextmenu", function(e) { e.preventDefault(); })
  jeedomUtils.initApplication()
  
  $('body').on('taphold', '.objectSummaryAction', function(e) {
    jeedomUtils.loadPanel(false)
    $('#bottompanel_objectList').panel('close')
    let object_id = $(this).attr('data-object_id')
    let summary = $(this).attr('data-summary')
    jeedomUtils.loadModal('mobile/modal/summary.action.html',function() {
      initSummaryAction(object_id, summary)
    })
  })
  
  $('body').on('tap', '.link', function(e) {
    jeedomUtils.loadModal(false)
    jeedomUtils.loadPanel(false)
    jeedomUtils.loadPage($(this).attr('data-page'), $(this).attr('data-title'), $(this).attr('data-option'), $(this).attr('data-plugin'))
  })
  
  $('body').on('tap', '.objectSummaryParent', function(e) {
    jeedomUtils.loadModal(false)
    jeedomUtils.loadPanel(false)
    jeedomUtils.loadPage('equipment', '{{Résumé}}', $(this).data('object_id') + ':' + $(this).data('summary'))
  })
  
  $('body').on('click', '.cmd[data-type=info],.cmd .history[data-type=info]', function(event) {
    var mainOpt = $('#bottompanel_mainoption')
    mainOpt.empty()
    mainOpt.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="history" data-title="{{Historique}}" data-option="'+$(this).data('cmd_id')+'"><i class="fas fa-chart-bar"></i> {{Historique}}</a>')
    mainOpt.append('<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" id="bt_warnmeCmd" data-cmd_id="'+$(this).data('cmd_id')+'"><i class="fas fa-bell"></i> {{Préviens moi}}</a>')
    
    mainOpt.panel('open')
    $(document).scrollTop(PANEL_SCROLL)
  })
  
  $('body').on('click', '#bt_warnmeCmd', function() {
    jeedomUtils.loadPage('warnme','{{Me prévenir si}}',{cmd_id : $(this).data('cmd_id')}, null, true)
  })
  
  $('body').on('click', '#bt_switchTheme', function() {
    jeedomUtils.switchTheme(jeedom.theme)
    $('#bottompanel_otherActionList').panel('close')
  })

  $('body').on('click', '#span_nbMessage', function() {
    jeedomUtils.loadPage('message', 'Messages')
  })

})

var PAGE_HISTORY = []
$(window).on('popstate', function(event) {
  if (isset(event.isTrigger)) return
  if (isset(event.historyState)) return
  if ($('.ui-popup-container:not(.ui-popup-hidden)').length > 0) return
  event.preventDefault()
  if (PAGE_HISTORY.length <= 1) return
  
  PAGE_HISTORY.pop()
  var history_page = PAGE_HISTORY.pop()
  if (!history_page || !history_page.page) {
    return
  }
  jeedomUtils.loadModal(false)
  jeedomUtils.loadPanel(false)
  if (!history_page.option) {
    jeedomUtils.loadPage(history_page.page, history_page.title)
  } else if (!history_page.plugin) {
    jeedomUtils.loadPage(history_page.page, history_page.title, history_page.option)
  } else {
    jeedomUtils.loadPage(history_page.page, history_page.title, history_page.option, history_page.plugin)
  }
  if (history_page.scroll) {
    setTimeout(function() {
      $(document).scrollTop(history_page.scroll)
    }, 1000)
  }
})

//theming:
jeedomUtils.setBackgroundImage = function(_path) {
  //Exact same function desktop/mobile, only transitionJeedomBackground() differ
  if (!isset(jeedom) || !isset(jeedom.theme) || !isset(jeedom.theme.showBackgroundImg) || jeedom.theme.showBackgroundImg == 0) {
    return
  }
  if (_path === null) {
    jeedomUtils._elBackground.find('#bottom').css('background-image', 'url("")').show()
  } else if (_path === '') {
    var mode = 'light'
    if ($('body').attr('data-theme') == 'core2019_Dark') {
      mode = 'dark'
    }
    
    if (['dashboard', 'overview', 'home', 'equipment'].indexOf($('body').attr('data-page')) != -1) {
      _path = jeedom.theme['interface::background::dashboard']
    } else if (['display', 'eqAnalyse', 'log', 'timeline', 'history', 'report', 'health', 'administration', 'profils', 'update', 'backup', 'cron', 'user'].indexOf($('body').attr('data-page')) != -1) {
      _path = jeedom.theme['interface::background::analysis']
    } else {
      _path = jeedom.theme['interface::background::tools']
    }
    
    if (_path.substring(0, 4) == 'core') {
      jeedomUtils._elBackground.removeClass('custom')
      _path += mode + '.jpg'
    } else {
      jeedomUtils._elBackground.addClass('custom')
    }
    jeedomUtils.transitionJeedomBackground(_path)
  } else {
    jeedomUtils.transitionJeedomBackground(_path)
  }
  jeedomUtils.backgroundIMG = _path
}

jeedomUtils.transitionJeedomBackground = function(_path) {
  jeedomUtils._elBackground.find('#bottom').css('background-image', 'url("../../../../' + _path + '")')
}

jeedomUtils.switchTheme = function(themeConfig) {
  var theme = 'core/themes/' + themeConfig.mobile_theme_color_night + '/mobile/' + themeConfig.mobile_theme_color_night + '.css'
  var themeShadows = 'core/themes/' + themeConfig.mobile_theme_color_night + '/mobile/shadows.css'
  var themeCook = 'alternate'
  
  if ($('#jQMnDColor').attr('href') == theme) {
    $('body').attr('data-theme', themeConfig.mobile_theme_color)
    theme = 'core/themes/' + themeConfig.mobile_theme_color + '/mobile/' + themeConfig.mobile_theme_color + '.css'
    themeShadows = 'core/themes/' + themeConfig.mobile_theme_color + '/mobile/shadows.css'
    themeCook = 'default'
    $('#jQMnDColor').attr('href', theme).attr('data-nochange',0)
  } else {
    $('#jQMnDColor').attr('href', theme).attr('data-nochange',1)
    $('body').attr('data-theme', themeConfig.mobile_theme_color_night)
  }
  
  setCookie('currentThemeMobile', themeCook, 0.3)
  
  if ($("#shadows_theme_css").length > 0) $('#shadows_theme_css').attr('href', themeShadows)
  jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
  jeedomUtils.triggerThemechange()
}

jeedomUtils.triggerThemechange = function() {
  //set jeedom logo:
  var currentTheme = $('body').attr('data-theme')
  if (currentTheme.endsWith('Dark')) {
    $('#homeLogoImg').attr('src', jeedom.theme.logo_mobile_dark)
  } else {
    $('#homeLogoImg').attr('src', jeedom.theme.logo_mobile_light)
  }
  //trigger event for widgets:
  if ( $('body').attr('data-page') && ['equipment', 'view'].includes($('body').attr('data-page')) ) {
    if (currentTheme.endsWith('Light')) {
      $('body').trigger('changeThemeEvent', ['Light'])
    } else {
      $('body').trigger('changeThemeEvent', ['Dark'])
    }
  }
}

jeedomUtils.changeThemeAuto = function(_ambiantLight){
  if (typeof jeedom.theme == 'undefined') return
  if (typeof jeedom.theme.mobile_theme_color_night == 'undefined' || typeof jeedom.theme.mobile_theme_color == 'undefined') return
  if (jeedom.theme.mobile_theme_color == jeedom.theme.mobile_theme_color_night) return
  
  if (jeedom.theme.mobile_theme_useAmbientLight == "1" && 'AmbientLightSensor' in window) {
    const sensor = new AmbientLightSensor()
    sensor.onreading = () => {
      if ($('#jQMnDColor').attr('data-nochange') == 1) {
        return
      }
      if (sensor.illuminance < 200 && sensor.illuminance > 50) {
        return
      }
      var theme = jeedom.theme.mobile_theme_color
      var themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color+'/mobile/' + jeedom.theme.mobile_theme_color + '.css'
      if (sensor.illuminance < 50) {
        theme = jeedom.theme.mobile_theme_color_night
        themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color_night+'/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
      }
      if ($('#jQMnDColor').attr('href') != themeCss) {
        setTimeout(function() {
          if (sensor.illuminance < 100 && sensor.illuminance > 50) {
            return
          }
          var theme = jeedom.theme.mobile_theme_color
          var themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color+'/mobile/' + jeedom.theme.mobile_theme_color + '.css'
          if (sensor.illuminance < 50) {
            theme = jeedom.theme.mobile_theme_color_night
            themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color_night+'/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
          }
          if ($('#jQMnDColor').attr('href') != themeCss) {
            $('body').attr('data-theme',theme)
            $('#jQMnDColor').attr('href', themeCss)
            jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
            jeedomUtils.triggerThemechange()
          }
        }, 500)
      }
    }
    sensor.start()
  } else if (jeedom.theme.theme_changeAccordingTime == "1") {
    setInterval(function() {
      jeedomUtils.checkThemechange()
    }, 60000)
  }
}

jeedomUtils.checkThemechange = function() {
  var $jQMnDColor = $('#jQMnDColor')
  if ($jQMnDColor.attr('data-nochange') == 1) return
  
  var defaultTheme = jeedom.theme.mobile_theme_color
  var defaultThemeCss = 'core/themes/' + defaultTheme + '/mobile/' + defaultTheme + '.css'
  if (jeedom.theme.theme_changeAccordingTime == "0" && defaultThemeCss == $jQMnDColor.attr('href')) return
  
  var theme = jeedom.theme.mobile_theme_color_night
  var themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color_night+'/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
  var currentTime = parseInt((new Date()).getHours()*100 + (new Date()).getMinutes())

  if (parseInt(jeedom.theme.theme_start_day_hour.replace(':','')) <  currentTime && parseInt(jeedom.theme.theme_end_day_hour.replace(':','')) >  currentTime) {
    theme = jeedom.theme.mobile_theme_color
    themeCss = 'core/themes/'+jeedom.theme.mobile_theme_color+'/mobile/' + jeedom.theme.mobile_theme_color + '.css'
  }

  if ($jQMnDColor.attr('href') != themeCss) {
    $('body').attr('data-theme',theme)
    $('#jQMnDColor').attr('href', themeCss)
    jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
    jeedomUtils.triggerThemechange()
  }
}


jeedomUtils.insertHeader = function(rel, href, size=null, media=null, id=null, type=null) {
  var link = document.createElement('link')
  link.rel = rel
  link.href = href
  if (size != null) {
    link.size = size
  }
  if (media != null) {
    link.media = media
  }
  if (id != null) {
    link.id = id
  }
  if (type != null) {
    link.type = type
  }
  document.head.appendChild(link)
}

function isset() {
  var a = arguments, b = a.length, d = 0
  if (0 === b)
  throw Error("Empty isset")
  for (; d !== b; ) {
    if (void 0 === a[d] || null === a[d])
    return!1
    d++
  }
  return!0
}

var serverDatetime
var clientServerDiffDatetime
var serverTZoffsetMin
var jeedom_langage
var user_id
var user_login
var plugins
var userProfils
var deviceInfo
var defaultMobilePage = null

jeedomUtils.initApplication = function(_reinit) {
  $.ajax({
    type: 'POST',
    url: 'core/ajax/jeedom.ajax.php',
    data: {
      action: 'getInfoApplication',
      auth : getUrlVars('auth'),
    },
    dataType: 'json',
    error: function (request, status, error) {
      confirm('Erreur de communication. Etes-vous connecté à Internet ? Voulez-vous réessayer ?')
    },
    success: function (data) {
      jeedom.theme = data.result
      jeedom_langage = data.result.langage
      
      jeedomUtils.insertHeader("apple-touch-icon",jeedom.theme.product_icon, "128x128")
      jeedomUtils.insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, "256x256")
      jeedomUtils.insertHeader("apple-touch-icon-precomposed",jeedom.theme.product_icon, "256x256")
      jeedomUtils.insertHeader("shortcut icon",jeedom.theme.product_icon, "128x128")
      jeedomUtils.insertHeader("icon",jeedom.theme.product_icon, "128x128")
      jeedomUtils.insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 320px)")
      jeedomUtils.insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 320px) and (-webkit-device-pixel-ratio: 2)")
      jeedomUtils.insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 768px) and (orientation: portrait)")
      jeedomUtils.insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 768px) and (orientation: landscape)")
      jeedomUtils.insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)")
      jeedomUtils.insertHeader("apple-touch-startup-image",jeedom.theme.product_icon, null, "(device-width: 1536px)  and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)")
      if (data.state != 'ok' || (isset(data.result.connected) && data.result.connected == false)) {
        jeedomUtils.loadModal(false)
        jeedomUtils.loadPanel(false)
        jeedomUtils.loadPage('connection', 'Connexion')
        return
      }
      document.body.style.setProperty('--bkg-opacity-light', jeedom.theme['interface::background::opacitylight'])
      document.body.style.setProperty('--bkg-opacity-dark', jeedom.theme['interface::background::opacitydark'])
      if (init(_reinit, false) == false) {
        document.title = data.result.product_name
        $('#favicon').attr("href", data.result.product_icon)
        jeedomUtils.loadModal(false)
        jeedomUtils.loadPanel(false)
        /*************Initialisation environement********************/
        serverDatetime  = data.result.serverDatetime
        var clientDatetime = new Date()
        clientServerDiffDatetime = serverDatetime*1000 - clientDatetime.getTime()
        serverTZoffsetMin = data.result.serverTZoffsetMin
        user_id = data.result.user_id
        user_login = data.result.user_login
        plugins = data.result.plugins
        userProfils = data.result.userProfils
        jeedom.init()
        var include = []
        if (typeof jeedom.theme != 'undefined' && typeof jeedom.theme.css != 'undefined' && Object.keys(jeedom.theme.css).length > 0) {
          for(var i in jeedom.theme.css) {
            document.body.style.setProperty(i,jeedom.theme.css[i])
          }
        }
        if (typeof jeedom.theme.mobile_theme_useAmbientLight == undefined) {
          jeedom.theme.mobile_theme_useAmbientLight = "0"
        }
        if (typeof jeedom.theme.theme_changeAccordingTime == undefined) {
          jeedom.theme.theme_changeAccordingTime = "0"
        }
        
        if (typeof jeedom.theme['interface::advance::coloredIcons'] != 'undefined' && jeedom.theme['interface::advance::coloredIcons'] == '1') {
          $('body').attr('data-coloredIcons', 1)
        } else {
          $('body').attr('data-coloredIcons', 0)
        }
        
        if (typeof jeedom.theme['interface::advance::coloredcats'] != 'undefined' && jeedom.theme['interface::advance::coloredcats'] == '1') {
          $('body').attr('data-coloredcats', 1)
        } else {
          $('body').attr('data-coloredcats', 0)
        }
        
        //set theme
        var widget_shadow = true
        var useAdvance = 0
        if (typeof jeedom.theme != 'undefined') {
          if (typeof jeedom.theme['interface::advance::enable'] != 'undefined') {
            useAdvance = parseInt(jeedom.theme['interface::advance::enable'])
          }
          if (typeof jeedom.theme['widget::shadow'] != 'undefined' && useAdvance == 1 && jeedom.theme['widget::shadow'] == '1') {
            widget_shadow = false
          }
        }
        var themeWich = 'default'
        if (getCookie('currentThemeMobile') == 'alternate') {
          themeWich = 'alternate'
        }
        var themeCSS = false
        var themeShadowCSS = false
        if (themeWich == 'default') {
          themeCSS = 'core/themes/' + jeedom.theme.mobile_theme_color + '/mobile/' + jeedom.theme.mobile_theme_color + '.css'
          themeShadowCSS = 'core/themes/' + jeedom.theme.mobile_theme_color + '/mobile/shadows.css'
          $('body').attr('data-theme', jeedom.theme.mobile_theme_color)
        } else {
          themeCSS = 'core/themes/' + jeedom.theme.mobile_theme_color_night + '/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
          themeShadowCSS = 'core/themes/' + jeedom.theme.mobile_theme_color_night + '/mobile/shadows.css'
          $('body').attr('data-theme', jeedom.theme.mobile_theme_color_night)
          $('#jQMnDColor').attr('href', themeCSS).attr('data-nochange',1)
        }
        $('#jQMnDColor').attr('href', themeCSS)
        
        jeedomUtils.changeThemeAuto()
        jeedomUtils.checkThemechange()
        if (widget_shadow) {
          jeedomUtils.insertHeader("stylesheet", themeShadowCSS, null, null, 'shadows_theme_css', 'text/css')
        }
        
        //custom:
        if (isset(data.result.custom) && data.result.custom != null) {
          if (isset(data.result.custom.css) && data.result.custom.css) {
            include.push('mobile/custom/custom.css')
          }
          if (isset(data.result.custom.js) && data.result.custom.js) {
            include.push('mobile/custom/custom.js')
          }
        }
        
        jeedomUtils.triggerThemechange()
        for(var i in plugins){
          if (plugins[i].eventjs == 1) {
            include.push('plugins/'+plugins[i].id+'/mobile/js/event.js')
          }
        }
        
        $.get("core/php/icon.inc.php", function (data) {
          $("head").append(data)
          $.include(include, function() {
            deviceInfo = getDeviceType()
            jeedom.object.summaryUpdate([{object_id:'global'}])
            
            if (APP_MODE) {
              jeedomUtils.loadPage('home', 'Accueil')
            } else {
              if (getUrlVars('p') == 'view') {
                jeedomUtils.loadPage('view', 'Vue',getUrlVars('view_id'));
              } else if (isset(userProfils) && userProfils != null && isset(userProfils.homePageMobile) && userProfils.homePageMobile != 'home') {
                var res = userProfils.homePageMobile.split("::")
                if (res[0] == 'core') {
                  switch (res[1]) {
                    case 'overview':
                    defaultMobilePage = ['overview', "<i class=\'fab fa-hubspot\'></i> {{Synthèse}}"]
                    jeedomUtils.loadPage(defaultMobilePage)
                    break
                    case 'dashboard':
                    defaultMobilePage = ['equipment', userProfils.defaultMobileObjectName, userProfils.defaultMobileObject]
                    jeedomUtils.loadPage(defaultMobilePage)
                    break
                    case 'plan':
                    defaultMobilePage = null
                    window.location.href = 'index.php?v=d&p=plan&plan_id=' + userProfils.defaultMobilePlan
                    break
                    case 'view':
                    defaultMobilePage = ['view', userProfils.defaultMobileViewName, userProfils.defaultMobileView]
                    jeedomUtils.loadPage(defaultMobilePage)
                    break
                  }
                } else {
                  jeedomUtils.loadPage(res[1], 'Plugin', '', res[0])
                }
              } else {
                jeedomUtils.loadPage('home', '{{Accueil}}')
              }
            }
            
            if (APP_MODE) {
              $('#pagecontainer').css('padding-top',0)
            } else {
              $('#pagecontainer').css('padding-top','72px')
            }
          })
        })
        jeedomUtils.refreshMessageNumber()
      }
    }
  })
}

jeedomUtils.loadPage = function(_page, _title, _option, _plugin, _dialog) {
  //handle default mobile home switching:
  if (Array.isArray(_page)) {
    _title = _page[1]
    _option = _page[2]
    _page = _page[0]
  }
  CURRENT_PAGE = _page
  //handle browser history:
  if (PAGE_HISTORY[PAGE_HISTORY.length - 1]) {
    PAGE_HISTORY[PAGE_HISTORY.length - 1].scroll = $(document).scrollTop()
  }
  if (!isset(_dialog) || !_dialog) {
    PAGE_HISTORY.push({page : _page, title : _title,option : _option, plugin : _plugin})
  }
  
  $.showLoading()
  $('#searchContainer').hide()
  try {
    $('#bottompanel').panel('close')
    $('#bottompanel_mainoption').panel('close')
    $('.ui-popup').popup('close')
  } catch (e) {
    
  }
  if (isset(_title)) {
    if (!isset(_dialog) || !_dialog) {
      $('#pageTitle').empty().append(_title)
    } else {
      $('#popupDialog .nd-title').text(_title)
    }
  }
  if (_page == 'connection') {
    var page = 'index.php?v=m&ajax=1&p=' + _page
    $('#page').load(page, function() {
      $('body').attr('data-page', 'connection')
      $('#page').trigger('create')
      if (APP_MODE) {
        $('div[data-role=header]').remove();
        $('#pagecontainer').css('padding-top',0)
      } else {
        $('#pagecontainer').css('padding-top','72px')
        setTimeout(function() {
          $('#pagecontainer').css('padding-top','72px')
        }, 100)
      }
    })
    return
  }
  var page = 'index.php?v=m&ajax=1'
  if (isset(_dialog) && _dialog) {
    page += '&modal='+_page
  } else {
    //alternate between defaultMobilePage and home:
    var thisPage = $('body').attr('data-page')
    if (defaultMobilePage != null && defaultMobilePage[0] != thisPage && _page == 'home') {
      _page = defaultMobilePage[0]
      _title = defaultMobilePage[1]
      _option = defaultMobilePage[2]
      $('#pageTitle').empty().append(_title)
    }
    page += '&p=' + _page
  }
  if (init(_plugin) != '') {
    page += '&m=' + _plugin
  }
  
  if (isset(_dialog) && _dialog) {
    $('#popupDialog .content').load(page, function() {
      var functionName = ''
      if (init(_plugin) != '') {
        functionName = 'init' + _plugin.charAt(0).toUpperCase() + _plugin.substring(1).toLowerCase() + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      } else {
        functionName = 'init' + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      }
      if ('function' == typeof (window[functionName])) {
        if (init(_option) != '') {
          window[functionName](_option)
        } else {
          window[functionName]()
        }
      }
      Waves.init()
      $("#popupDialog").popup({
        beforeposition: function() {
          $(this).css({
            width: window.innerWidth - 40,
          })
        },
        x: 5,
        y: 70
      })
      $('#popupDialog').trigger('create').popup('open')
    })
  } else {
    $('#page').hide().load(page, function() {
      $('body').attr('data-page', _page)
      $('#page').trigger('create')
      jeedomUtils.setBackgroundImage('')
      window.history.pushState('', '', 'index.php?v=m&p=home')
      var functionName = ''
      if (init(_plugin) != '') {
        functionName = 'init' + _plugin.charAt(0).toUpperCase() + _plugin.substring(1).toLowerCase() + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      } else {
        functionName = 'init' + _page.charAt(0).toUpperCase() + _page.substring(1).toLowerCase()
      }
      
      if ('function' == typeof (window[functionName])) {
        if (init(_option) != '') {
          window[functionName](_option)
        } else {
          window[functionName]()
        }
      }
      Waves.init()
      if (APP_MODE) {
        $('div[data-role=header]').remove()
        $('#pagecontainer').css('padding-top',0)
      } else {
        $('#pagecontainer').css('padding-top','72px')
        setTimeout(function() {
          $('#pagecontainer').css('padding-top','72px')
        }, 100)
      }
      $('#page').fadeIn(400)
    })
  }
  
  setTimeout(function() {
    if ($.active == 0) $.hideLoading()
  }, 1500)
}

jeedomUtils.loadModal = function(_name, _callback) {
  try {
    if (_name === false) {
      $('#div_popup').empty().popup("close")
      $("[data-role=popup]").popup("close")
    } else {
      $('#div_popup').empty().load(_name, function() {
        $('#div_popup').trigger('create').popup("open")
        if ('function' == typeof (_callback)) {
          _callback()
        }
        setTimeout(function(){
          $('#div_popup-popup').css({
            'position': 'absolute',
            'top': 105,
            'left': '50%',
            'transform': 'translate(-50%)'
          })
        }, 100)
      })
    }
  } catch(e) {}
}

jeedomUtils.loadPanel = function(_content) {
  try{
    if (_content === false) {
      $('#bottompanel').empty().trigger('create')
      $('#bt_bottompanel').hide()
      $('#bottompanel').panel('close')
    } else {
      $('#bottompanel').empty().append(_content).trigger('create')
      $('#bt_bottompanel').show()
    }
  } catch(e) {}
}

$(document).on('panelbeforeopen', function(event) {
  PANEL_SCROLL = $(document).scrollTop()
  event.stopImmediatePropagation()
})

$(document).on('panelopen', function(event) {
  $(document).scrollTop(PANEL_SCROLL)
})

$(document).on('panelbeforeclose', function(event) {
  PANEL_SCROLL = $(document).scrollTop()
})

$(document).on('panelclose', function(event) {
  $(document).scrollTop(PANEL_SCROLL)
})

jeedomUtils.MESSAGE_NUMBER = null
jeedomUtils.refreshMessageNumber = function() {
  jeedom.message.number({
    success: function (_number) {
      jeedomUtils.MESSAGE_NUMBER = _number
      $('.span_nbMessage').html(_number)
      if (_number > 0) {
        $('#span_nbMessage').show()
      } else {
        $('#span_nbMessage').hide()
      }
    }
  })
}

jeedomUtils.notify = function(_title, _text) {
  new $.nd2Toast({
    message :  _title+'. '+_text,
    ttl : 3000
  })
}

jeedomUtils.setTileSize = function(_filter) {
  if (typeof jeedom.theme['widget::margin'] == 'undefined') {
    jeedom.theme['widget::margin'] = 4
  }
  var bsize = deviceInfo.bSize
  $(_filter).each(function() {
    $(this).css({'margin':'0px', 'padding':'0px'})
    if ($(this).hasClass('col2')) {
      $(this).width(bsize * 2)
    } else {
      $(this).width(bsize - jeedom.theme['widget::margin'])
    }
    $(this).css('margin',jeedom.theme['widget::margin']+'px')
  });
}

function init(_value, _default) {
  if (!isset(_default)) {
    _default = ''
  }
  if (!isset(_value)) {
    return _default
  }
  return _value
}

jeedomUtils.normTextLower = function(_text) {
  return _text.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase()
}

//deprecated, remove v4.3
function refreshUpdateNumber() {
  jeedomUtils.refreshMessageNumber()
}
function positionEqLogic(_id) {
  jeedomUtils.positionEqLogic(_id)
}
function setTileSize(_filter) {
  jeedomUtils.setTileSize(_filter)
}
function panel(_name, _callback) {
  jeedomUtils.loadPanel(_name, _callback)
}
function setBackgroundImage(_path){
  jeedomUtils.setBackgroundImage(_path)
}

//deprecated, remove v4.3
var page = jeedomUtils.loadPage