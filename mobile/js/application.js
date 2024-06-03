"use strict"

var jeedomUtils = {}
jeedomUtils.initTooltips = function(_el) { } // no Tooltips in mobile but the function is call by jeedom.eqLogic.refreshValue
jeedomUtils.backgroundIMG = null
jeedomUtils._elBackground = null
jeedomUtils.scrolling = false

jeedomUtils.loadingTimeout = null
jeedomUtils.showLoading = function() {
  document.getElementById('div_jeedomLoading').seen()
  //Hanging timeout:
  clearTimeout(jeedomUtils.loadingTimeout)
  jeedomUtils.loadingTimeout = setTimeout(() => {
    if (!document.getElementById('div_jeedomLoading').isHidden()) {
      jeedomUtils.hideLoading()
      $.fn.showAlert({ level: 'danger', message: 'Operation Timeout: Something has gone wrong!' })
    }
  }, 20 * 1000)
}
jeedomUtils.showAlert = $.fn.showAlert
jeedomUtils.hideLoading = function() {
  document.getElementById('div_jeedomLoading').unseen()
}

$(function() {
  $(document)
    .ajaxStart(function() {
      jeedomUtils.showLoading()
    })
    .ajaxStop(function() {
      jeedomUtils.hideLoading()
    })
})

window.addEventListener('error', function(event) {
  jeedomUtils.hideLoading()
})


var delayedExec = function(after, fn) {
  var timer
  return function() {
    timer && clearTimeout(timer)
    timer = setTimeout(fn, after)
  }
}

var scrollStopper = delayedExec(250, function() {
  jeedomUtils.scrolling = false
})

window.addEventListener('scroll', function(e) {
  jeedomUtils.scrolling = true
  scrollStopper()
})

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

  if (getUrlVars('app_mode') == 1 || window.ReactNativeWebView != undefined) {
    APP_MODE = true
    jeedomUtils._elBackground.height('100%').css('top', '0')
    $('#pagecontainer').prepend($('#searchContainer'))
    $('div[data-role=header]').hide()
    $('#searchContainer').css('top', 0)
    $('#bt_eraseSearchInput').css('top', 0)
    $('#pagecontainer').append('<a href="#bottompanel" id="bt_bottompanel" class="ui-btn ui-btn-inline ui-btn-fab ui-btn-raised clr-primary waves-effect waves-button waves-effect waves-button" style="position:fixed;bottom:10px;right:10px;display:none;"><i class="fas fa-bars" style="position:relative;top:-3px"></i></a>')
  }

  window.addEventListener("contextmenu", function(e) {
    e.preventDefault()
  })
  jeedomUtils.initApplication()

  $('body').on('taphold', '.objectSummaryAction', function(e) {
    jeedomUtils.loadPanel(false)
    $('#bottompanel_objectList').panel('close')
    let object_id = this.getAttribute('data-object_id')
    let summary = this.getAttribute('data-summary')
    jeedomUtils.loadModal('mobile/modal/summary.action.html', function() {
      initSummaryAction(object_id, summary)
    })
  })

  $('body').on('tap', '.link', function(e) {
    jeedomUtils.loadModal(false)
    jeedomUtils.loadPanel(false)
    jeedomUtils.loadPage(this.getAttribute('data-page'), this.getAttribute('data-title'), this.getAttribute('data-option'), this.getAttribute('data-plugin'))
  })

  $('body').on('tap', '.objectSummaryParent', function(e) {
    jeedomUtils.loadModal(false)
    jeedomUtils.loadPanel(false)
    jeedomUtils.loadPage('equipment', '{{Résumé}}', this.getAttribute('data-object_id') + ':' + this.getAttribute('data-summary'))
  })

  $('body').on('click', '.cmd[data-type=info],.cmd .history[data-type=info]', function(event) {
    let mainOpt = $('#bottompanel_mainoption')
    mainOpt.empty()
    mainOpt.append('<a class="link ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" data-page="history" data-title="{{Historique}}" data-option="' + $(this).data('cmd_id') + '"><i class="fas fa-chart-bar"></i> {{Historique}}</a>')
    mainOpt.append('<a class="ui-bottom-sheet-link ui-btn ui-btn-inline waves-effect waves-button" id="bt_warnmeCmd" data-cmd_id="' + $(this).data('cmd_id') + '"><i class="fas fa-bell"></i> {{Préviens moi}}</a>')

    mainOpt.panel('open')
    $(document).scrollTop(PANEL_SCROLL)
  })

  $('body').on('click', '#bt_warnmeCmd', function() {
    jeedomUtils.loadPage('warnme', '{{Me prévenir si}}', { cmd_id: $(this).data('cmd_id') }, null, true)
  })

  $('body').on('click', '#bt_switchTheme', function() {
    jeedomUtils.switchTheme(jeedom.theme)
    $('#bottompanel_otherActionList').panel('close')
  })

  $('body').on('click', '#span_nbMessage', function() {
    jeedomUtils.loadPage('message', 'Messages')
  })
  $('body').on('click', '#bt_changeTheme', function() {
    jeedomUtils.changeTheme('toggle')
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
  let history_page = PAGE_HISTORY.pop()
  if (!history_page || !history_page.page) {
    return
  }
  jeedomUtils.loadModal(false)
  jeedomUtils.loadPanel(false)
  jeedomUtils.loadPage(history_page.page, history_page.title, history_page.option, history_page.plugin)
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
    let mode = 'light'
    if ($('body').attr('data-theme') == 'core2019_Dark') {
      mode = 'dark'
    }

    if (['dashboard', 'overview', 'home', 'equipment'].indexOf(document.body.getAttribute('data-page')) != -1) {
      _path = jeedom.theme['interface::background::dashboard']
    } else if (['display', 'eqAnalyse', 'log', 'timeline', 'history', 'report', 'health', 'administration', 'profils', 'update', 'backup', 'cron', 'user'].indexOf(document.body.getAttribute('data-page')) != -1) {
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

jeedomUtils.changeTheme = function(_theme) {
  if (_theme == 'toggle' || !document.body.getAttribute('data-theme').toLowerCase().endsWith(_theme)) {
    jeedomUtils.switchTheme(jeedom.theme)
  }
}

jeedomUtils.switchTheme = function(themeConfig) {
  let theme = 'core/themes/' + themeConfig.mobile_theme_color_night + '/mobile/' + themeConfig.mobile_theme_color_night + '.css'
  let themeShadows = 'core/themes/' + themeConfig.mobile_theme_color_night + '/mobile/shadows.css'
  let themeCook = 'alternate'
  var cssTag = document.getElementById('jeedom_theme_currentcss')

  if (cssTag.attributes.href.value == theme) {
    document.body.setAttribute('data-theme', themeConfig.mobile_theme_color)
    theme = 'core/themes/' + themeConfig.mobile_theme_color + '/mobile/' + themeConfig.mobile_theme_color + '.css'
    themeShadows = 'core/themes/' + themeConfig.mobile_theme_color + '/mobile/shadows.css'
    themeCook = 'default'
    cssTag.setAttribute('href', theme)
    cssTag.setAttribute('data-nochange', 0)
  } else {
    cssTag.setAttribute('href', theme)
    cssTag.setAttribute('data-nochange', 1)
    document.body.setAttribute('data-theme', themeConfig.mobile_theme_color_night)
  }

  setCookie('currentThemeMobile', themeCook, 0.3)

  if (document.getElementById("shadows_theme_css") != null) document.getElementById("shadows_theme_css").attributes.href.value = themeShadows
  jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
  jeedomUtils.triggerThemechange()
}

jeedomUtils.triggerThemechange = function() {
  //set jeedom logo:
  let currentTheme = document.body.getAttribute('data-theme')
  if (currentTheme.endsWith('Dark')) {
    $('#homeLogoImg').attr('src', jeedom.theme.logo_mobile_dark)
  } else {
    $('#homeLogoImg').attr('src', jeedom.theme.logo_mobile_light)
  }
  //trigger event for widgets:
  if ($('body').attr('data-page') && ['equipment', 'view'].includes($('body').attr('data-page'))) {
    if (currentTheme.endsWith('Light')) {
      $('body').trigger('changeThemeEvent', ['Light'])
    } else {
      $('body').trigger('changeThemeEvent', ['Dark'])
    }
  }
}

jeedomUtils.changeThemeAuto = function(_ambiantLight) {
  if (typeof jeedom.theme == 'undefined') return
  if (typeof jeedom.theme.mobile_theme_color_night == 'undefined' || typeof jeedom.theme.mobile_theme_color == 'undefined') return
  if (jeedom.theme.mobile_theme_color == jeedom.theme.mobile_theme_color_night) return

  var cssTag = document.getElementById('jeedom_theme_currentcss')
  if (jeedom.theme.mobile_theme_useAmbientLight == "1" && 'AmbientLightSensor' in window) {
    const sensor = new AmbientLightSensor()
    sensor.onreading = () => {
      if (cssTag.getAttribute('data-nochange') == '1') {
        return
      }
      if (sensor.illuminance < 200 && sensor.illuminance > 50) {
        return
      }
      let theme = jeedom.theme.mobile_theme_color
      let themeCss = 'core/themes/' + jeedom.theme.mobile_theme_color + '/mobile/' + jeedom.theme.mobile_theme_color + '.css'
      if (sensor.illuminance < 50) {
        theme = jeedom.theme.mobile_theme_color_night
        themeCss = 'core/themes/' + jeedom.theme.mobile_theme_color_night + '/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
      }
      if (cssTag.attributes.href.value != themeCss) {
        setTimeout(function() {
          if (sensor.illuminance < 100 && sensor.illuminance > 50) {
            return
          }
          let theme = jeedom.theme.mobile_theme_color
          let themeCss = 'core/themes/' + jeedom.theme.mobile_theme_color + '/mobile/' + jeedom.theme.mobile_theme_color + '.css'
          if (sensor.illuminance < 50) {
            theme = jeedom.theme.mobile_theme_color_night
            themeCss = 'core/themes/' + jeedom.theme.mobile_theme_color_night + '/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
          }
          if (cssTag.attributes.href.value != themeCss) {
            $('body').attr('data-theme', theme)
            cssTag.setAttribute('href', themeCss)
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
  let cssTag = document.getElementById('jeedom_theme_currentcss')
  if (cssTag.getAttribute('data-nochange') == '1') return

  let defaultTheme = jeedom.theme.mobile_theme_color
  let defaultThemeCss = 'core/themes/' + defaultTheme + '/mobile/' + defaultTheme + '.css'
  if (jeedom.theme.theme_changeAccordingTime == "0" && defaultThemeCss == cssTag.attributes.href.value) return

  let theme = jeedom.theme.mobile_theme_color_night
  let themeCss = 'core/themes/' + jeedom.theme.mobile_theme_color_night + '/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
  let currentTime = parseInt((new Date()).getHours() * 100 + (new Date()).getMinutes())
  if (parseInt(jeedom.theme.theme_start_day_hour.replace(':', '')) < currentTime && parseInt(jeedom.theme.theme_end_day_hour.replace(':', '')) > currentTime) {
    theme = jeedom.theme.mobile_theme_color
    themeCss = 'core/themes/' + jeedom.theme.mobile_theme_color + '/mobile/' + jeedom.theme.mobile_theme_color + '.css'
  }
  if (cssTag.attributes.href.value != themeCss) {
    document.body.setAttribute('data-theme', theme)
    cssTag.setAttribute('href', themeCss)
    jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
    jeedomUtils.triggerThemechange()
  }
}


jeedomUtils.insertHeader = function(rel, href, size = null, media = null, id = null, type = null) {
  let link = document.createElement('link')
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
  let a = arguments, b = a.length, d = 0
  if (0 === b)
    throw Error("Empty isset")
  for (; d !== b;) {
    if (void 0 === a[d] || null === a[d])
      return !1
    d++
  }
  return !0
}

var user_id
var user_login
var plugins
var defaultMobilePage = null

jeedomUtils.initApplication = function(_reinit) {
  jeedom.refreshMessageNumber()
  domUtils.ajax({
    type: 'POST',
    url: 'core/ajax/jeedom.ajax.php',
    data: {
      action: 'getInfoApplication',
      auth: getUrlVars('auth'),
    },
    dataType: 'json',
    error: function(request, status, error) {
      confirm('Erreur de communication. Etes-vous connecté à Internet ? Voulez-vous réessayer ?')
    },
    success: function(data) {
      /* SEND SUMMARY TO APP */
      jeedom.appMobile.postToApp('initSummary', data.result.summary)

      jeedom.theme = data.result
      jeeFrontEnd.language = data.result.language

      if (jeedom.theme.product_icon_apple) {
        var icon_apple = jeedom.theme.product_icon_apple
      } else {
        var icon_apple = jeedom.theme.product_icon
      }
      jeedomUtils.insertHeader("apple-touch-icon", icon_apple, "128x128")
      jeedomUtils.insertHeader("apple-touch-startup-image", icon_apple, "256x256")
      jeedomUtils.insertHeader("apple-touch-icon-precomposed", icon_apple, "256x256")
      jeedomUtils.insertHeader("shortcut icon", icon_apple, "128x128")
      jeedomUtils.insertHeader("icon", icon_apple, "128x128")
      jeedomUtils.insertHeader("apple-touch-startup-image", icon_apple, null, "(device-width: 320px)")
      jeedomUtils.insertHeader("apple-touch-startup-image", icon_apple, null, "(device-width: 320px) and (-webkit-device-pixel-ratio: 2)")
      jeedomUtils.insertHeader("apple-touch-startup-image", icon_apple, null, "(device-width: 768px) and (orientation: portrait)")
      jeedomUtils.insertHeader("apple-touch-startup-image", icon_apple, null, "(device-width: 768px) and (orientation: landscape)")
      jeedomUtils.insertHeader("apple-touch-startup-image", icon_apple, null, "(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)")
      jeedomUtils.insertHeader("apple-touch-startup-image", icon_apple, null, "(device-width: 1536px)  and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)")

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
        jeeFrontEnd.serverDatetime = data.result.serverDatetime
        jeeFrontEnd.clientDatetime = new Date()
        jeeFrontEnd.clientServerDiffDatetime = jeeFrontEnd.serverDatetime * 1000 - jeeFrontEnd.clientDatetime.getTime()
        jeeFrontEnd.serverTZoffsetMin = data.result.serverTZoffsetMin
        user_id = data.result.user_id
        user_login = data.result.user_login
        plugins = data.result.plugins
        jeeFrontEnd.userProfils = data.result.userProfils
        jeedom.init()
        jeedomUtils.userDevice = getDeviceType()
        document.body.setAttribute('data-device', jeedomUtils.userDevice.type)

        let include = []
        if (typeof jeedom.theme != 'undefined' && typeof jeedom.theme.css != 'undefined' && Object.keys(jeedom.theme.css).length > 0) {
          for (let i in jeedom.theme.css) {
            document.body.style.setProperty(i, jeedom.theme.css[i])
          }
        }
        if (typeof jeedom.theme.mobile_theme_useAmbientLight == undefined) {
          jeedom.theme.mobile_theme_useAmbientLight = "0"
        }
        if (typeof jeedom.theme.theme_changeAccordingTime == undefined) {
          jeedom.theme.theme_changeAccordingTime = "0"
        }

        if (typeof jeedom.theme['interface::advance::coloredIcons'] != 'undefined' && jeedom.theme['interface::advance::coloredIcons'] == '1') {
          document.body.setAttribute('data-coloredIcons', 1)
        } else {
          document.body.setAttribute('data-coloredIcons', 0)
        }

        if (typeof jeedom.theme['interface::advance::coloredcats'] != 'undefined' && jeedom.theme['interface::advance::coloredcats'] == '1') {
          document.body.setAttribute('data-coloredcats', 1)
        } else {
          document.body.setAttribute('data-coloredcats', 0)
        }

        //set theme
        let widget_shadow = true
        let useAdvance = 0
        if (typeof jeedom.theme != 'undefined') {
          if (typeof jeedom.theme['interface::advance::enable'] != 'undefined') {
            useAdvance = parseInt(jeedom.theme['interface::advance::enable'])
          }
          if (typeof jeedom.theme['widget::shadow'] != 'undefined' && useAdvance == 1 && jeedom.theme['widget::shadow'] == '1') {
            widget_shadow = false
          }
        }
        let themeWich = 'default'
        if (getCookie('currentThemeMobile') == 'alternate') {
          themeWich = 'alternate'
        }
        let themeCSS = false
        let themeShadowCSS = false
        if (themeWich == 'default') {
          themeCSS = 'core/themes/' + jeedom.theme.mobile_theme_color + '/mobile/' + jeedom.theme.mobile_theme_color + '.css'
          themeShadowCSS = 'core/themes/' + jeedom.theme.mobile_theme_color + '/mobile/shadows.css'
          document.body.setAttribute('data-theme', jeedom.theme.mobile_theme_color)
        } else {
          themeCSS = 'core/themes/' + jeedom.theme.mobile_theme_color_night + '/mobile/' + jeedom.theme.mobile_theme_color_night + '.css'
          themeShadowCSS = 'core/themes/' + jeedom.theme.mobile_theme_color_night + '/mobile/shadows.css'
          document.body.setAttribute('data-theme', jeedom.theme.mobile_theme_color_night)
          document.getElementById('jeedom_theme_currentcss').setAttribute('data-nochange', '1')
        }
        document.getElementById('jeedom_theme_currentcss').href = themeCSS

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
        // hide changeTheme button if theme change is not possible
        if (jeedom.theme.mobile_theme_color == jeedom.theme.mobile_theme_color_night) {
          $('#bt_changeTheme').hide()
        }
        for (let i in plugins) {
          if (plugins[i].eventjs == 1) {
            include.push('plugins/' + plugins[i].id + '/mobile/js/event.js')
          }
        }

        //load some css, then ...
        $.get("core/php/icon.inc.php", function(data) {
          document.head.insertAdjacentHTML('beforeend', data)
          $.include(include, function() {
            jeedom.object.summaryUpdate([{ object_id: 'global' }])
            //store default mobile page user preference:
            if (isset(jeeFrontEnd.userProfils) && jeeFrontEnd.userProfils != null && isset(jeeFrontEnd.userProfils.homePageMobile) && jeeFrontEnd.userProfils.homePageMobile != 'home') {
              let res = jeeFrontEnd.userProfils.homePageMobile.split("::")
              if (res[0] == 'core') {
                switch (res[1]) {
                  case 'overview':
                    defaultMobilePage = ['overview', "<i class=\'fab fa-hubspot\'></i> {{Synthèse}}"]
                    break
                  case 'dashboard':
                    defaultMobilePage = ['equipment', jeeFrontEnd.userProfils.defaultMobileObjectName, jeeFrontEnd.userProfils.defaultMobileObject]
                    break
                  case 'view':
                    defaultMobilePage = ['view', jeeFrontEnd.userProfils.defaultMobileViewName, jeeFrontEnd.userProfils.defaultMobileView]
                    break
                }
              }
            }

            let redirect = getUrlVars('p')
            let redirections = [
              { page: 'timeline', title: '{{Timeline}}' },
              { page: 'health', title: '{{Santé}}' },
              { page: 'log', title: '{{Logs}}' },
              { page: 'eqAnalyse', title: '{{Analyse équipement}}' },
              { page: 'notes', title: '{{Notes}}' },
              { page: 'cron', title: '{{Crons}}' },
              { page: 'deamon', title: '{{Démons}}' },
              { page: 'message', title: '{{Message}}' },
              { page: 'overview', title: "<i class=\'fab fa-hubspot\'></i> {{Synthèse}}" },
              { page: 'scenario', title: "{{Scenario}}" },
              { page: 'home', title: "{{Accueil}}" },
            ]
            window.redirected = false
            if (redirect && redirections.map(i => i.page).includes(redirect)) {
              for (let redir of redirections) {
                if (redir.page == redirect) {
                  window.redirected = true
                  jeedomUtils.loadPage(redir.page, redir.title)
                }
              }
            } else if (redirect == 'view') {
              jeedomUtils.loadPage('view', '{{Vue}}', getUrlVars('view_id'))
            } else if (redirect == 'dashboard' || redirect == 'equipment') {
              jeedomUtils.loadPage('equipment', '{{Dashboard}}', getUrlVars('object_id'))
            }
            else if (redirect == 'plan') {
              window.location.href = 'index.php?v=d&p=plan&fullscreen=1&plan_id=' + getUrlVars('plan_id')
            } else if (isset(jeeFrontEnd.userProfils) && jeeFrontEnd.userProfils != null && isset(jeeFrontEnd.userProfils.homePageMobile) && jeeFrontEnd.userProfils.homePageMobile != 'home') {
              let res = jeeFrontEnd.userProfils.homePageMobile.split("::")
              if (res[0] == 'core') {
                switch (res[1]) {
                  case 'overview':
                    jeedomUtils.loadPage(defaultMobilePage)
                    break
                  case 'dashboard':
                    jeedomUtils.loadPage(defaultMobilePage)
                    break
                  case 'plan':
                    window.location.href = 'index.php?v=d&p=plan&plan_id=' + jeeFrontEnd.userProfils.defaultMobilePlan
                    break
                  case 'view':
                    jeedomUtils.loadPage(defaultMobilePage)
                    break
                }
              } else {
                jeedomUtils.loadPage(res[1], 'Plugin', '', res[0])
              }
            } else {
              if (redirect != '' && APP_MODE == true) {
                jeedom.plugin.get({
                  id: redirect,
                  async: false,
                  error: function(error) {
                    jeedomUtils.showAlert({
                      message: 'Erreur sur affichage du panel',
                      level: 'danger'
                    })
                  },
                  success: function(data) {
                    if (data.mobile != '') {
                      jeedomUtils.loadPage(data.mobile, 'Plugin', '', redirect)
                    } else {
                      jeedomUtils.loadPage(redirect, 'Plugin', '', redirect)
                    }
                  }
                })
              } else {
                jeedomUtils.loadPage('home', '{{Accueil}}')
              }
            }

            if (APP_MODE) {
              document.getElementById('pagecontainer').style.paddingTop = '0'
            } else {
              document.getElementById('pagecontainer').style.paddingTop = '72px'
            }
          })
        })
      }
    }
  })
  document.body.addEventListener('jeeObject::summary::update', function(_event) {
    for (var i in _event.detail) {
      if (isset(_event.detail[i].force) && _event.detail[i].force == 1) continue
      if (_event.detail[i].object_id == 'global') {
        /* SEND UPDATE SUMMARY TO APP */
        jeedom.appMobile.postToApp('updateSummary', _event.detail[i].keys)
      }
    }
  })
}

jeedomUtils.loadPage = function(_page, _title, _option, _plugin, _dialog) {
  window.onscroll = null

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
    PAGE_HISTORY.push({ page: _page, title: _title, option: _option, plugin: _plugin })
  }

  jeedomUtils.showLoading()
  document.getElementById('searchContainer')?.unseen()
  try {
    $('#bottompanel').panel('close')
    $('#bottompanel_mainoption').panel('close')
    $('.ui-popup').popup('close')
  } catch (e) { }

  if (isset(_title)) {
    if (!isset(_dialog) || !_dialog) {
      document.getElementById('pageTitle')?.empty().insertAdjacentHTML('beforeend', _title)
    } else {
      document.getElementById('popupDialog').querySelector('.nd-title')?.empty().insertAdjacentHTML('beforeend', _title)
    }
  }

  if (_page == 'connection') {
    let page = 'index.php?v=m&ajax=1&p=' + _page
    $('#page').load(page, function() {
      document.body.setAttribute('data-page', 'connection')
      $('#page').trigger('create')
      if (APP_MODE) {
        $('div[data-role=header]').remove()
        $('#pagecontainer').css('padding-top', 0)
      } else {
        $('#pagecontainer').css('padding-top', '72px')
        setTimeout(function() {
          $('#pagecontainer').css('padding-top', '72px')
        }, 100)
      }
    })
    return
  }

  let page = 'index.php?v=m&ajax=1'
  if (isset(_dialog) && _dialog) {
    page += '&modal=' + _page
  } else {
    //Alternate between defaultMobilePage and home:
    if (window.redirected === false && defaultMobilePage != null && defaultMobilePage[0] != document.body.getAttribute('data-page') && _page == 'home') {
      _page = defaultMobilePage[0]
      _title = defaultMobilePage[1]
      _option = defaultMobilePage[2]
      document.getElementById('pageTitle')?.empty().insertAdjacentHTML('beforeend', _title)
    }
    window.redirected = false
    page += '&p=' + _page
  }
  if (init(_plugin) != '') {
    page += '&m=' + _plugin
    page += '&plugin=' + _plugin // index.php needs plugin variable to load plugin file
  }

  if (isset(_dialog) && _dialog) {
    $('#popupDialog .content')[0].load(page, function() {
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
    jeedom.cmd.resetUpdateFunction()

    $('#page').load(page, function() {
      document.body.setAttribute('data-page', _page)

      if (init(_plugin) != '') {
        document.body.setAttribute('data-plugin', _plugin)
      } else {
        document.body.setAttribute('data-plugin', null)
      }
      $('#page').trigger('create')
      jeedomUtils.setBackgroundImage('')
      page = 'index.php?v=m'
      page += (init(_plugin) != '') ? '&p=' + _plugin : '&p=' + _page
      if (getUrlVars('app_mode') == 1) page += '&app_mode=1'
      window.history.pushState('', '', page)

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
        document.querySelectorAll('div[data-role=header]')?.remove()
        var node = (document.getElementById(pagecontainer)) ? node.style.paddingTop = 0 : null
      } else {
        var node = (document.getElementById(pagecontainer)) ? node.style.paddingTop = '72px' : null
        setTimeout(function() {
          var node = (document.getElementById(pagecontainer)) ? node.style.paddingTop = '72px' : null
        }, 100)
      }
      document.getElementById('page').fade(400, 1)
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
        setTimeout(function() {
          $('#div_popup-popup').css({
            'position': 'absolute',
            'top': 105,
            'left': '50%',
            'transform': 'translate(-50%)'
          })
        }, 100)
      })
    }
  } catch (e) { }
}

jeedomUtils.loadPanel = function(_content) {
  try {
    if (_content === false) {
      $('#bottompanel').empty().trigger('create')
      $('#bt_bottompanel').hide()
      $('#bottompanel').panel('close')
    } else {
      $('#bottompanel').empty().append(_content).trigger('create')
      $('#bt_bottompanel').show()
    }
  } catch (e) { }
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

jeedomUtils.postToApp = function(_action, _options) {
  let message = {}
  if (window.ReactNativeWebView != undefined) {
    message.action = _action
    message.options = _options
    window.ReactNativeWebView.postMessage(JSON.stringify(message))
  }
}

jeedomUtils.appMobile = {}

jeedomUtils.appMobile.vibration = function(type = "impactMedium") {
  jeedomUtils.postToApp('vibration', { type: type })
}
jeedomUtils.appMobile.notifee = function(title, body, time) {
  jeedomUtils.postToApp('notifee', { title: title, body: body, time: time })
}
jeedomUtils.appMobile.modal = function(_options) {
  jeedomUtils.postToApp('modal', _options)
}

jeedom.MESSAGE_NUMBER = null
jeedom.refreshMessageNumber = function() {
  jeedom.message.number({
    success: function(_number) {
      jeedom.MESSAGE_NUMBER = _number
      $('.span_nbMessage').html(_number)
      if (_number > 0) {
        $('#span_nbMessage').show()
      } else {
        $('#span_nbMessage').hide()
      }
    }
  })
}

jeedom.notify = function(_title, _text) {
  if (window.ReactNativeWebView != undefined) {
    jeedomUtils.appMobile.notifee(_title, _text, 3000)
  } else {
    new $.nd2Toast({
      message: _title + ':  ' + _text,
      ttl: 3000
    })
  }
}

jeedomUtils.setTileSize = function(_filter) {
  if (typeof jeedom.theme['widget::margin'] == 'undefined') {
    jeedom.theme['widget::margin'] = 4
  }
  let bsize = jeedomUtils.userDevice.bSize

  document.querySelectorAll(_filter)?.forEach(function(node) {
    Object.assign(node.style, { margin: "0px", padding: "0px" })
    if (node.hasClass('col2')) {
      node.style.width = (bsize * 2) + 'px'
    } else if (node.hasClass('col1')) {
      node.style.width = (bsize - jeedom.theme['widget::margin']) + 'px'
    } else if (jeedom.theme['interface::mobile::onecolumn'] == 1) {
      node.style.width = (bsize * 2) + 'px'
    } else {
      node.style.width = (bsize - jeedom.theme['widget::margin']) + 'px'
    }
    node.style.margin = jeedom.theme['widget::margin'] + 'px'
  })
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
