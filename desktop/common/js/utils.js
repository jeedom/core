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

var jeedomUtils = {
  __description: 'Loaded once for every desktop/mobile page. Global UI functions and variables.',
  backgroundIMG: null,
  _elBackground: null
}
jeedomUtils.tileWidthStep = (parseInt(jeedom.theme['widget::step::width']) > 80 ? parseInt(jeedom.theme['widget::step::width']) : 80) + parseInt(jeedom.theme['widget::margin']) // with margin
jeedomUtils.tileHeightStep = (parseInt(jeedom.theme['widget::step::height']) > 60 ? parseInt(jeedom.theme['widget::step::height']) : 60) + parseInt(jeedom.theme['widget::margin']) // with margin
jeedomUtils.tileHeightSteps = Array.apply(null, { length: 50 }).map(function(value, index) { return (index + 1) * jeedomUtils.tileHeightStep })


/*Hijack jQuery ready function, still used in plugins
*/
if (typeof jQuery === 'function') {
  jeedomUtils.$readyFn = jQuery.fn.ready
  jQuery.fn.ready = function() {
    if (domUtils._DOMloading <= 0) {
      jeedomUtils.$readyFn.apply(this, arguments)
    } else {
      setTimeout(function() {
        jQuery.fn.ready.apply(this, arguments[1])
      }, 250, this, arguments)
    }
  }
}
//Deprecated, keep for plugins using jQuery ajax call
document.addEventListener('DOMContentLoaded', function() {
  jeedomUtils._elBackground = document.getElementById('backgroundforJeedom')
  if (typeof jQuery === 'function') {
    $(document)
      .ajaxStart(function() {
        domUtils.showLoading()
      })
      .ajaxStop(function() {
        domUtils.hideLoading()
      })
  }
})

//js error in ! ui:
jeedomUtils.JS_ERROR = []
window.addEventListener('error', function(event) {
  if (event.filename.indexOf('3rdparty/') != -1) return
  if (event.message.includes('ResizeObserver loop')) return
  jeedomUtils.JS_ERROR.push(event)
  document.getElementById('bt_jsErrorModal')?.seen()
  domUtils.hideLoading()
})

if ('SecurityPolicyViolationEvent' in window) { // Check browser support of SecurityPolicyViolationEnevt interface
  window.addEventListener("securitypolicyviolation", function(event) {
    var uri = event.blockedURI
    var msg = `{{Erreur de directive Content Security Policy sur la ressource "${uri}"}}`
    if (event.originalPolicy && event.violatedDirective) {
      var violation = event.originalPolicy.trim().split(';').find(e => e.trim().startsWith(event.violatedDirective))
      if (typeof violation === 'string') {
        if (event.disposition == 'enforce')
          var msg = `{{Impossible de charger la ressource "${uri}", car elle va contre la directive de Content Security Policy :<br /> "${violation.trim()}"}}`
        else
          var msg = `{{La ressource "${uri}" a été chargée, mais elle va contre la directive de Content Security Policy :<br /> "${violation.trim()}"}}`
      }
    }
    jeedomUtils.JS_ERROR.push({ "filename": event.documentURI, "lineno": "0", "message": msg })
    document.getElementById('bt_jsErrorModal').seen()
    domUtils.hideLoading()
  })
}

//UI Time display:
setInterval(function() {
  if (document.getElementById('horloge') === null) {
    return
  }
  let dateJeed = new Date
  dateJeed.setTime((new Date).getTime() + ((new Date).getTimezoneOffset() + jeeFrontEnd.serverTZoffsetMin) * 60000 + jeeFrontEnd.clientServerDiffDatetime)
  document.getElementById('horloge').innerHTML = dateJeed.toLocaleTimeString()
}, 1000)

var modifyWithoutSave = false
jeedomUtils.checkPageModified = function() {
  if (jeeFrontEnd.modifyWithoutSave || window.modifyWithoutSave) {
    if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
      domUtils.hideLoading()
      return true
    }
    window.modifyWithoutSave = false
    jeeFrontEnd.modifyWithoutSave = false
    return false
  }
}

var prePrintEqLogic = undefined
var printEqLogic = undefined
var addCmdToTable = undefined
var saveEqLogic = undefined
jeedomUtils.userDevice = getDeviceType()

//OnePage design PageLoader -------------------------------------
jeedomUtils.loadPage = function(_url, _noPushHistory) {
  jeeFrontEnd.PREVIOUS_LOCATION = window.location.href
  if (jeedomUtils.checkPageModified()) return
  if (jeedomUtils.JS_ERROR.length > 0) {
    document.location.href = _url
    return
  }

  jeedomUtils.closeJeedomMenu()
  jeeDialog.clearToasts()
  jeedomUtils.closeJeeDialogs()
  jeedom.cmd.resetUpdateFunction()

  //Deprecated jQuery contextMenu
  if (typeof jQuery === 'function' && typeof $.contextMenu === 'function') $.contextMenu('destroy')
  document.querySelectorAll('.context-menu-root').remove()

  jeedomUtils.jeeCtxMenuDestroy()

  //Deprecated jQuery UI dialogs
  try {
    $(".ui-dialog-content").dialog("close")
  } catch (e) { }

  if (!isset(_noPushHistory) || _noPushHistory == false) {
    try {
      if (jeeFrontEnd.PREVIOUS_PAGE == null) {
        window.history.replaceState('', '', 'index.php?' + window.location.href.split("index.php?")[1])
        jeeFrontEnd.PREVIOUS_PAGE = 'index.php?' + window.location.href.split("index.php?")[1]
      }
      if (jeeFrontEnd.PREVIOUS_PAGE == null || jeeFrontEnd.PREVIOUS_PAGE != _url) {
        window.history.pushState('', '', _url)
        jeeFrontEnd.PREVIOUS_PAGE = _url
      }
      document.body.setAttribute('data-page', getUrlVars('p') || '')
    } catch (e) { }
  }

  if (typeof jQuery === 'function' && typeof bootbox === 'object') bootbox.hideAll()
  jeedomUtils.hideAlert()
  jeedomUtils.datePickerDestroy()
  jeedomUtils.autocompleteDestroy()
  jeedomUtils.cleanModals()
  jeedom.cmd.update = []
  jeedom.scenario.update = []
  jeephp2js = {}
  delete window.jeeP
  prePrintEqLogic = printEqLogic = addCmdToTable = saveEqLogic = undefined
  if (typeof jQuery === 'function') $('body').off('changeThemeEvent')

  if (_url.indexOf('#') == -1) {
    var url = _url + '&ajax=1'
  } else {
    var n = _url.lastIndexOf("#")
    var url = _url.substring(0, n) + "&ajax=1" + _url.substring(n)
  }

  jeedomUtils.backgroundIMG = null

  //Empty one page and remove listeners:
  var div_pageContainer = document.getElementById('div_pageContainer')
  div_pageContainer.empty()
  div_pageContainer.replaceWith(div_pageContainer.cloneNode(true))
  domUtils.unRegisterEvents()

  //Deprecated: Migrate to registerEvent() and delete:
  document.onkeydown = null
  if (typeof jQuery === 'function') {
    $('body').off('mouseenter mouseleave')
    $(window).off('resize')
  }

  document.getElementById('div_mainContainer').querySelectorAll('script')?.remove()
  document.body.querySelectorAll('script[injext]')?.remove()

  //AJAX LOAD URL INTO PAGE CONTAINER:
  domUtils.DOMloading += 1
  document.getElementById('div_pageContainer').load(url, function() {
    document.body.setAttribute('data-page', getUrlVars('p') || '')
    document.getElementById('bt_getHelpPage')?.setAttribute('data-page', getUrlVars('p'))
    document.getElementById('bt_getHelpPage')?.setAttribute('data-plugin', getUrlVars('m') || '')
    jeedomUtils.initPage()

    domUtils.syncJeeCompletes()
    document.body.triggerEvent('jeedom_page_load')

    //dashboard page on object will set its own background:
    if (!_url.includes('dashboard')) {
      if (jeedomUtils.backgroundIMG !== null) {
        jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
      } else {
        jeedomUtils.setBackgroundImage('')
      }
    }

    setTimeout(function() {
      if (window.location.hash != '') {
        var tab = document.querySelector('.nav-tabs a[data-target="' + window.location.hash + '"]') || document.querySelector('.nav-tabs a[href="' + window.location.hash + '"]')
        if (tab != null) {
          tab.click()
        }
      }
    }, 150) //let time for plugin page!

    setTimeout(function() {
      modifyWithoutSave = false
      jeeFrontEnd.modifyWithoutSave = false
    }, 250)
    domUtils.DOMloading -= 1
  })

  return
}

/* First time loading, all next goes by loadpage()
*/
document.addEventListener('DOMContentLoaded', function() {
  jeedom.init()
  document.body.setAttribute('data-device', jeedomUtils.userDevice.type)
  document.body.setAttribute('data-page', getUrlVars('p'))
  document.body.style.setProperty('--bkg-opacity-light', jeedom.theme['interface::background::opacitylight'])
  document.body.style.setProperty('--bkg-opacity-dark', jeedom.theme['interface::background::opacitydark'])

  document.body.addEventListener('jeedom_page_load', function(event) {
    if (getUrlVars('saveSuccessFull') == 1) {
      jeedomUtils.showAlert({ message: '{{Sauvegarde effectuée avec succès}}', level: 'success' })
      jeeFrontEnd.PREVIOUS_PAGE = window.location.href.split('&saveSuccessFull')[0] + window.location.hash
      window.history.replaceState({}, document.title, window.location.href.split('&saveSuccessFull')[0] + window.location.hash)
    }
    if (getUrlVars('removeSuccessFull') == 1) {
      jeedomUtils.showAlert({ message: '{{Suppression effectuée avec succès}}', level: 'success' })
      jeeFrontEnd.PREVIOUS_PAGE = window.location.href.split('&removeSuccessFull')[0] + window.location.hash
      window.history.replaceState({}, document.title, window.location.href.split('&removeSuccessFull')[0] + window.location.hash)
    }
  })

  //tab in url:
  var tab = document.querySelector('.nav-tabs a[data-target="' + window.location.hash + '"]')
  if (tab == null) {
    tab = document.querySelector('.nav-tabs a[href="' + window.location.hash + '"]')
  }
  if (window.location.hash != '' && tab != null) {
    tab.click()
  }

  //browser history:

  //custom jQuery event can't use pur js event listener
  if (typeof jQuery === 'function') {
    $('body').on('shown.bs.tab', '.nav-tabs a', function(event) {
      if (event.target.getAttribute('data-target') == '' && event.target.getAttribute('href') == '') return
      if (event.target.closest('.ui-dialog-content')?.innerHTML !== undefined) return
      if (event.target.closest('.jeeDialog')?.innerHTML !== undefined) return

      if (jeeFrontEnd.PREVIOUS_PAGE == null) {
        window.history.replaceState('', '', 'index.php?' + window.location.href.split("index.php?")[1])
        jeeFrontEnd.PREVIOUS_PAGE = 'index.php?' + window.location.href.split("index.php?")[1]
      }
      var hash = event.target.getAttribute('data-target') || event.target.getAttribute('href')
      if (hash) {
        window.location.hash = hash
      } else {
        history.replaceState(null, null, ' ')
      }
    })
  }

  window.addEventListener('hashchange', function(event) {
    jeeFrontEnd.NO_POPSTAT = true
    setTimeout(function() {
      jeeFrontEnd.NO_POPSTAT = false
    }, 200)
  })

  window.addEventListener('popstate', function(event) {
    if (event.state === null) {
      if (jeeFrontEnd.NO_POPSTAT) {
        jeeFrontEnd.NO_POPSTAT = false
        return
      }
      var tab = document.querySelector('.nav-tabs a[data-target="' + window.location.hash + '"]') || document.querySelector('.nav-tabs a[href="' + window.location.hash + '"]')
      if (window.location.hash != '' && tab != null) {
        tab.click()
      } else if (jeeFrontEnd.PREVIOUS_PAGE !== null && jeeFrontEnd.PREVIOUS_PAGE.includes('#') && jeeFrontEnd.PREVIOUS_PAGE.split('#')[0] != 'index.php?' + window.location.href.split("index.php?")[1].split('#')[0]) {
        if (jeedomUtils.checkPageModified()) return
        jeedomUtils.loadPage('index.php?' + window.location.href.split("index.php?")[1], true)
        jeeFrontEnd.PREVIOUS_PAGE = 'index.php?' + window.location.href.split("index.php?")[1]
      }
      return
    }
    if (jeedomUtils.checkPageModified()) return
    jeedomUtils.loadPage('index.php?' + window.location.href.split("index.php?")[1], true)
    jeeFrontEnd.PREVIOUS_PAGE = 'index.php?' + window.location.href.split("index.php?")[1]
  })

  jeedomUtils.setJeedomTheme()
  jeedomUtils.changeJeedomThemeAuto()

  jeedomUtils.initJeedomModals()
  jeedomUtils.setJeedomGlobalUI()

  if (jeedomUtils.backgroundIMG != null) {
    jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
  } else {
    jeedomUtils.setBackgroundImage('')
  }
  jeedomUtils.initPage()

  setTimeout(function() {
    jeedomUtils.initTooltips()
    document.body.triggerEvent('jeedom_page_load')
  })

  jeedomUtils.setJeedomMenu()

  /*Move and register common scripts/css to not reload them:
    Managed on loadPage() by domUtils.loadScript() and domUtils.html()
  */
  document.getElementById('div_pageContainer')?.querySelectorAll('script').forEach(script => {
    if (script.src.includes('desktop/common/js') || script.src.includes('/3rdparty/')) {
      domUtils.headInjexted.push(script.src)
      document.head.appendChild(script)
    }
  })
  document.getElementById('div_pageContainer')?.querySelectorAll('link[rel="stylesheet"]').forEach(stylesheet => {
    if (stylesheet.href.includes('desktop/common/css') || stylesheet.href.includes('/3rdparty/')) {
      stylesheet.setAttribute('injext', '1')
      domUtils.headInjexted.push(stylesheet.href)
      document.head.appendChild(stylesheet)
    }
  })

  //flatpickr theme:
  var flatpickrDarkCss = document.querySelector('head > link[rel="stylesheet"][href*="3rdparty/flatpickr/flatpickr.dark.css"]')
  if (flatpickrDarkCss) {
    if (document.body.getAttribute('data-theme').endsWith('Dark')) {
      flatpickrDarkCss.disabled = false
    } else {
      flatpickrDarkCss.disabled = true
    }
  }
})

jeedomUtils.showAlert = function(_options) {
  //if (getUrlVars('report') == 1) return
  var options = init(_options, {})
  options.title = init(options.title, '')
  options.message = init(options.message, '')
  options.level = init(options.level, '')
  options.emptyBefore = init(options.emptyBefore, false)
  options.timeOut = init(options.timeOut, init(options.ttl, parseInt(jeedom.theme['interface::toast::duration']) * 1000))
  options.extendedTimeOut = init(options.extendedTimeOut, parseInt(jeedom.theme['interface::toast::duration']) * 1000)
  if (options.level == 'danger') {
    options.timeOut = 0
  }
  options.attachTo = init(_options.attachTo, false)
  if (!options.attachTo) options.attachTo = init(_options.attach, false) //Deprecated, old toastr param

  jeeDialog.toast(options)
}

jeedomUtils.hideAlert = function() {
  jeeDialog.clearToasts()

  //Deprecated, old div_alert may be used by plugins:
  document.querySelectorAll('.jqAlert').forEach(function(element) {
    element.innerHTML = ''
    element.unseen()
  })
}

//Jeedom theme__
jeedomUtils.setJeedomTheme = function() {
  if (getCookie('currentTheme') == 'alternate') {
    var themeButton = '<i class="fas fa-adjust"></i> {{Thème principal}}'
    document.getElementById('bt_switchTheme')?.html(themeButton)
    document.getElementById('jeedom_theme_currentcss').setAttribute('data-nochange', 1)
  }
  if (jeedom.theme.currentTheme) {
    document.body.setAttribute('data-theme', jeedom.theme.currentTheme)
    if (jeedom.theme.currentTheme == jeedom.theme.jeedom_theme_alternate) {
      var themeButton = '<i class="fas fa-adjust"></i> {{Thème principal}}'
      document.getElementById('bt_switchTheme')?.html(themeButton)
    }
  }

  //button event:
  document.getElementById('bt_switchTheme')?.addEventListener('click', function() {
    jeedomUtils.closeJeedomMenu()
    jeedomUtils.switchTheme()
  })

  jeedomUtils.changeTheme = function(_theme) {
    var currentTheme = document.body.getAttribute('data-theme').toLowerCase()
    if (_theme == 'toggle' || !currentTheme.endsWith(_theme)) {
      jeedomUtils.switchTheme()
    }
  }

  jeedomUtils.switchTheme = function() {
    var theme = 'core/themes/' + jeedom.theme.jeedom_theme_alternate + '/desktop/' + jeedom.theme.jeedom_theme_alternate + '.css'
    var themeShadows = 'core/themes/' + jeedom.theme.jeedom_theme_alternate + '/desktop/shadows.css'
    var themeCook = 'alternate'
    var themeButton = '<i class="fas fa-adjust"></i> {{Thème principal}}'
    var cssTag = document.getElementById('jeedom_theme_currentcss')
    cssTag.setAttribute('data-nochange', 1)

    if (cssTag.attributes.href.value.split('?md5')[0] == theme) {
      document.body.setAttribute('data-theme', jeedom.theme.jeedom_theme_main)
      theme = 'core/themes/' + jeedom.theme.jeedom_theme_main + '/desktop/' + jeedom.theme.jeedom_theme_main + '.css'
      themeShadows = 'core/themes/' + jeedom.theme.jeedom_theme_main + '/desktop/shadows.css'
      themeCook = 'default'
      themeButton = '<i class="fas fa-adjust"></i> {{Thème alternatif}}'
    } else {
      document.body.setAttribute('data-theme', jeedom.theme.jeedom_theme_alternate)
    }
    setCookie('currentTheme', themeCook, 30)
    cssTag.setAttribute('href', theme)
    document.getElementById('bt_switchTheme').innerHTML = themeButton
    if (document.getElementById('shadows_theme_css') != null) document.getElementById('shadows_theme_css').href = themeShadows
    jeedomUtils.triggerThemechange()
    let backgroundImgPath = jeedomUtils._elBackground.querySelector('#bottom').style.backgroundImage
    if (backgroundImgPath.indexOf('/data/') == -1 && backgroundImgPath.indexOf('/plugins/') == -1) {
      jeedomUtils.setBackgroundImage('')
    }
  }

  if (typeof jeedom.theme != 'undefined' && typeof jeedom.theme.css != 'undefined' && Object.keys(jeedom.theme.css).length > 0) {
    for (var i in jeedom.theme.css) {
      document.body.style.setProperty(i, jeedom.theme.css[i])
    }
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
}

jeedomUtils.changeJeedomThemeAuto = function() {
  if (typeof jeedom.theme == 'undefined') return
  if (typeof jeedom.theme.theme_changeAccordingTime == 'undefined' || jeedom.theme.theme_changeAccordingTime == 0) return
  if (typeof jeedom.theme.jeedom_theme_main == 'undefined' || typeof jeedom.theme.jeedom_theme_alternate == 'undefined') return
  if (jeedom.theme.jeedom_theme_main == jeedom.theme.jeedom_theme_alternate) return

  jeedomUtils.checkThemechange()
  setInterval(function() {
    jeedomUtils.checkThemechange()
  }, 60000)
}

jeedomUtils.checkThemechange = function() {
  //User forced current theme:
  if (getCookie('currentTheme') == 'alternate' || document.getElementById('jeedom_theme_currentcss')?.getAttribute('data-nochange') == 1) return

  //Should have themeCss, check currentTheme:
  var theme = jeedom.theme.jeedom_theme_alternate
  var themeCss = 'core/themes/' + jeedom.theme.jeedom_theme_alternate + '/desktop/' + jeedom.theme.jeedom_theme_alternate + '.css'
  var currentTime = parseInt((new Date()).getHours() * 100 + (new Date()).getMinutes())

  //if (parseInt(jeedom.theme.theme_start_day_hour.replace(':', '')) < currentTime && parseInt(jeedom.theme.theme_end_day_hour.replace(':', '')) > currentTime) {
  if (
    (parseInt(jeedom.theme.theme_start_day_hour.replace(':', '')) < currentTime
      && parseInt(jeedom.theme.theme_end_day_hour.replace(':', '')) > currentTime)
    || typeof jeedom.theme.theme_changeAccordingTime == 'undefined'
    || jeedom.theme.theme_changeAccordingTime == 0
  ) {
    theme = jeedom.theme.jeedom_theme_main
    themeCss = 'core/themes/' + jeedom.theme.jeedom_theme_main + '/desktop/' + jeedom.theme.jeedom_theme_main + '.css'
  }

  var currentTheme = document.getElementById('jeedom_theme_currentcss').getAttribute('href')
  if (currentTheme.indexOf('?md5') != -1) {
    currentTheme = currentTheme.substring(0, currentTheme.indexOf('?md5'))
  }
  if (currentTheme != themeCss) {
    document.body.setAttribute('data-theme', theme)
    document.getElementById('jeedom_theme_currentcss').setAttribute('href', themeCss)
    document.getElementById('shadows_theme_css')?.setAttribute('href', 'core/themes/' + theme + '/desktop/shadows.css')
    jeedomUtils.setBackgroundImage('')
    jeedomUtils.triggerThemechange()
  }
}

jeedomUtils.triggerThemechange = function() {
  //set jeedom logo:
  if (document.body.hasAttribute('data-theme')) {
    var currentTheme = document.body.getAttribute('data-theme')
    if (currentTheme.endsWith('Dark')) {
      document.getElementById('homeLogoImg')?.setAttribute('src', jeedom.theme.logo_dark)
    } else {
      document.getElementById('homeLogoImg')?.setAttribute('src', jeedom.theme.logo_light)
    }
  }

  //trigger event for widgets:
  if (document.body.hasAttribute('data-page') && ['dashboard', 'view', 'plan', 'widgets'].includes(document.body.getAttribute('data-page'))) {
    if (currentTheme.endsWith('Dark')) {
      document.body.triggerEvent('changeThemeEvent', { detail: { theme: 'Dark' } })
    } else {
      document.body.triggerEvent('changeThemeEvent', { detail: { theme: 'Light' } }) //Legacy theme is a light one
    }
  }

  //Switch flatpickr theme:
  var flatpickrDarkCss = document.querySelector('head > link[rel="stylesheet"][href*="3rdparty/flatpickr/flatpickr.dark.css"]')
  if (currentTheme.endsWith('Dark')) {
    flatpickrDarkCss.disabled = false
  } else {
    flatpickrDarkCss.disabled = true
  }
}

jeedomUtils.setBackgroundImage = function(_path) {
  if (getUrlVars('rescue') == 1) return false
  //Exact same function desktop/mobile, only transitionJeedomBackground() differ
  if (!isset(jeedom) || !isset(jeedom.theme) || !isset(jeedom.theme.showBackgroundImg) || jeedom.theme.showBackgroundImg == 0) {
    return
  }
  if (!jeedomUtils._elBackground) jeedomUtils._elBackground = document.getElementById('backgroundforJeedom')
  if (_path === null) {
    jeedomUtils._elBackground.querySelector('#bottom').css.backgroundImage = 'url("")'
    jeedomUtils._elBackground.querySelector('#bottom').seen()
  } else if (_path === '') {
    var mode = 'light'
    if (document.body.getAttribute('data-theme') == 'core2019_Dark') {
      mode = 'dark'
    }

    if (['dashboard', 'overview', 'home', 'equipment'].includes(document.body.getAttribute('data-page'))) {
      if (jeedom.theme.product_interface_image) {
        _path = jeedom.theme.product_interface_image
      }
      else {
        _path = jeedom.theme['interface::background::dashboard']
      }
    } else if (['display', 'eqAnalyse', 'log', 'timeline', 'history', 'report', 'health', 'administration', 'profils', 'update', 'backup', 'cron', 'user'].includes(document.body.getAttribute('data-page'))) {
      if (jeedom.theme.product_interface_image) {
        _path = jeedom.theme.product_interface_image
      }
      else {
        _path = jeedom.theme['interface::background::analysis']
      }
    } else {
      if (document.body.hasAttribute('data-page')) {
        if (jeedom.theme.product_interface_image) {
          _path = jeedom.theme.product_interface_image
        }
        else {
          _path = jeedom.theme['interface::background::tools']
        }
      }
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
  _path = 'url("../../../../' + _path + '")'
  if (document.body.getAttribute('data-theme') == 'core2019_Dark') {
    var opacity = document.body.style.getPropertyValue('--bkg-opacity-dark')
  } else {
    var opacity = document.body.style.getPropertyValue('--bkg-opacity-light')
  }

  var top = jeedomUtils._elBackground.querySelector('#top')
  var bottom = jeedomUtils._elBackground.querySelector('#bottom')
  if (top.style.backgroundImage == _path && top.style.opacity == opacity) {
    return
  }

  top.style.opacity = 0
  top.style.backgroundImage = _path
  top.fade(250, opacity)
  bottom.fade(200, 0, function() {
    bottom.style.backgroundImage = _path
  })
}


//Jeedom UI__
jeedomUtils.initJeedomModals = function() { //Deprecated jQuery UI dilaog/bootbox
  if (typeof jQuery !== 'function') return
  if (typeof $.fn.modal !== 'function') return

  $.fn.modal.Constructor.prototype.enforceFocus = function() { }

  //Deprecated bootbox, keep for plugins
  if (isset(jeeFrontEnd.language)) {
    var lang = jeeFrontEnd.language.substr(0, 2)
    var supportedLangs = ['fr', 'de', 'es']
    if (lang != 'en' && supportedLangs.includes(lang)) {
      bootbox.addLocale('fr', { OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Annuler' })
      bootbox.addLocale('de', { OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Abbrechen' })
      bootbox.addLocale('es', { OK: '<i class="fas fa-check"></i> Ok', CONFIRM: '<i class="fas fa-check"></i> Ok', CANCEL: '<i class="fas fa-times"></i> Anular' })
      bootbox.setLocale('fr') //needed for date format
    }
  }

  //Deprecated bootbox, keep for plugins
  $('body').on('show', '.modal', function() {
    document.activeElement.blur()
    $(this).find('.modal-body :input:visible').first().focus()
  })
  $('body').on('focusin', '.bootbox-input', function(event) {
    event.stopPropagation()
  })
  $('.bootbox.modal').on('shown.bs.modal', function() {
    $(this).find(".bootbox-accept").focus()
  })

  //Deprecated jQuery UI dialog, keep for plugins
  $('#md_modal').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (window.innerHeight - 125),
    width: ((window.innerWidth - 50) < 1500) ? (window.innerWidth - 50) : 1500,
    position: { my: 'center top+80', at: 'center top', of: window },
    open: function() {
      document.body.style.overflow = 'hidden'
      this.closest('.ui-dialog').querySelectorAll('button, input[type="button"]')?.forEach(el => { el.blur() })
      $(this).dialog({
        height: (window.innerHeight - 125),
        width: ((window.innerWidth - 50) < 1500) ? (window.innerWidth - 50) : 1500,
        position: { my: 'center top+80', at: 'center top', of: window }
      })
      setTimeout(function() { jeedomUtils.initTooltips($('#md_modal')) }, 500)
    },
    beforeClose: function(event, ui) {
      $(this).parent('.ui-dialog').removeClass('summaryActionMain')
      emptyModal('md_modal')
      $('#md_modal').off('dialogresize')
    }
  })

  $('#md_modal2').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (window.innerHeight - 125),
    width: ((window.innerWidth - 150) < 1200) ? (window.innerWidth - 50) : 1200,
    position: { my: 'center bottom-50', at: 'center bottom', of: window },
    open: function() {
      document.body.style.overflow = 'hidden'
      this.closest('.ui-dialog').querySelectorAll('button, input[type="button"]')?.forEach(el => { el.blur() })
      $(this).dialog({
        height: (window.innerHeight - 125),
        width: ((window.innerWidth - 150) < 1200) ? (window.innerWidth - 50) : 1200,
        position: { my: 'center bottom-50', at: 'center bottom', of: window },
      })
      setTimeout(function() { jeedomUtils.initTooltips($('#md_modal2')) }, 500)
    },
    beforeClose: function(event, ui) {
      emptyModal('md_modal2')
      $('#md_modal2').off('dialogresize')
    }
  })

  $('#md_modal3').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: (window.innerHeight - 125),
    width: ((window.innerWidth - 250) < 1000) ? (window.innerWidth - 50) : 1000,
    position: { my: 'center bottom-50', at: 'center bottom', of: window },
    open: function() {
      document.body.style.overflow = 'hidden'
      this.closest('.ui-dialog').querySelectorAll('button, input[type="button"]')?.forEach(el => { el.blur() })
      $(this).dialog({
        height: (window.innerHeight - 125),
        width: ((window.innerWidth - 250) < 1000) ? (window.innerWidth - 50) : 1000,
        position: { my: 'center bottom-50', at: 'center bottom', of: window },
      })
      setTimeout(function() { jeedomUtils.initTooltips($('#md_modal3')) }, 500)
    },
    beforeClose: function(event, ui) {
      emptyModal('md_modal3')
      $('#md_modal3').off('dialogresize')
    }
  })

  function emptyModal(_id = '') {
    if (_id == '') return
    document.body.style.overflow = 'inherit'
    document.getElementById(_id).empty()
  }
}

jeedomUtils.setButtonCtrlHandler = function(_buttonId, _title, _uri, _modal = 'jee_modal', _open = true) {
  if (document.getElementById(_buttonId) === null) {
    return
  }
  document.getElementById(_buttonId).addEventListener('click', event => {
    jeedomUtils.closeJeedomMenu()
    try {
      if (jeedomUI.isEditing == true) return false
    } catch (error) { }

    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      var title = encodeURI(_title)
      var url = '/index.php?v=d&p=modaldisplay&loadmodal=' + _uri + '&title=' + title
      window.open(url).focus()
    } else {
      jeeDialog.dialog({
        id: _modal,
        title: _title,
        contentUrl: 'index.php?v=d&modal=' + _uri
      })
    }
  })

  document.getElementById(_buttonId).addEventListener('mouseup', event => {
    if (event.which == 2) {
      event.preventDefault()
      event.target.triggerEvent('click', { detail: { ctrlKey: true } })
    }
  })
}

jeedomUtils.setJeedomGlobalUI = function() {
  if (typeof jeeFrontEnd.jeedom_firstUse != 'undefined' && isset(jeeFrontEnd.jeedom_firstUse) && jeeFrontEnd.jeedom_firstUse == 1 && getUrlVars('noFirstUse') != 1) {
    jeeDialog.dialog({
      id: 'md_firstUse',
      title: "{{Bienvenue dans Jeedom}}",
      width: window.innerWidth > 800 ? 720 : '80vw',
      height: window.innerHeight > 600 ? 400 : '80vw',
      zIndex: 1040,
      onClose: function() {
        jeeDialog.get('#md_firstUse').destroy()
      },
      contentUrl: 'index.php?v=d&modal=first.use'
    })
  }

  window.addEventListener('beforeunload', function(event) {
    //keep old root for plugins
    if (jeeFrontEnd.modifyWithoutSave == true || window.modifyWithoutSave == true) {
      event.preventDefault()
      return '{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}'
    }
  })

  jeedomUtils.setButtonCtrlHandler('bt_showEventInRealTime', '{{Evénements en temps réel}}', 'log.display&log=event', 'jee_modal')
  jeedomUtils.setButtonCtrlHandler('bt_showNoteManager', '{{Notes}}', 'note.manager', 'jee_modal')
  jeedomUtils.setButtonCtrlHandler('bt_showExpressionTesting', "{{Testeur d'expression}}", 'expression.test', 'jee_modal')
  jeedomUtils.setButtonCtrlHandler('bt_showDatastoreVariable', '{{Variables}}', 'dataStore.management&type=scenario', 'jee_modal', false)
  jeedomUtils.setButtonCtrlHandler('bt_showSearching', '{{Recherche}}', 'search', 'jee_modal')

  document.getElementById('bt_gotoDashboard')?.addEventListener('click', function(event) {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    if (event.altKey) {
      jeedomUtils.loadPage('index.php?v=d&p=dashboardit')
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=dashboard')
  })

  document.getElementById('bt_gotoView')?.addEventListener('click', function(event) {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=view')
  })

  document.getElementById('bt_gotoPlan')?.addEventListener('click', function(event) {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=plan')
  })

  document.getElementById('bt_gotoPlan3d')?.addEventListener('click', function(event) {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=plan3d')
  })

  document.getElementById('bt_jeedomAbout')?.addEventListener('click', function(event) {
    jeedomUtils.closeJeedomMenu()
    jeeDialog.dialog({
      id: 'jee_modal3',
      title: '{{A propos}}',
      width: window.innerWidth > 850 ? 800 : '80vw',
      height: window.innerHeight > 750 ? 700 : '80vw',
      contentUrl: 'index.php?v=d&modal=about'
    })
  })

  document.getElementById('bt_getHelpPage')?.addEventListener('click', function(event) {
    jeedom.getDocumentationUrl({
      plugin: this.getAttribute('data-plugin'),
      page: this.getAttribute('data-page'),
      theme: document.body.getAttribute('data-theme'),
      error: function(error) {
        jeedomUtils.showAlert({ message: error.message, level: 'danger' })
      },
      success: function(url) {
        window.open(url, '_blank')
      }
    })
  })

  document.querySelector('.bt_reportBug')?.addEventListener('click', function(event) {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.closeJeedomMenu()
    jeeDialog.dialog({
      id: 'md_reportBug',
      title: '<i class="fas fa-ticket-alt"></i> {{Demande de support}}',
      width: '60%',
      contentUrl: 'index.php?v=d&modal=report.bug'
    })
  })

  document.getElementById('bt_messageModal')?.addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Centre de Messages}}",
      contentUrl: 'index.php?v=d&modal=message.display'
    })
  })

  document.getElementById('bt_jsErrorModal')?.addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Erreur Javascript}}",
      contentUrl: 'index.php?v=d&modal=js.error'
    })
  })

  document.body.addEventListener('keydown', function(event) {
    if (event.key == 'Escape') {
      if (event.target.matches('input[id^="in_search"]')) {
        //search input escape
        event.stopPropagation()
        var els = ((els = document.querySelectorAll('#categoryfilter li .catFilterKey')) != null ? els.forEach(function(item) { item.checked = true }) : null)
        var els = ((els = document.querySelectorAll('#dashTopBar button.dropdown-toggle')) != null ? els.removeClass('warning') : null)
        event.target.value = ''
        return
      }
      else if (event.target.matches('body')) {
        //close active modal
        document.querySelector('div.jeeDialog.active')?._jeeDialog.close()
      }
    }
  })

  document.body.addEventListener('click', function(event) {
    //Summary display:
    if (!event.ctrlKey && (event.target.matches('.objectSummaryParent') || event.target.closest('.objectSummaryParent') != null)) {
      event.stopPropagation()
      var _el = (event.target.matches('.objectSummaryParent')) ? event.target : event.target.closest('.objectSummaryParent')
      if (document.body.getAttribute('data-page') == "overview" && _el.closest('.objectSummaryglobal') == null) return false
      var url = 'index.php?v=d&p=dashboard&summary=' + _el.dataset.summary + '&object_id=' + _el.dataset.object_id
      if (window.location.href.includes('&btover=1') || (document.body.getAttribute('data-page') != "dashboard" && jeeFrontEnd.userProfils.homePage == 'core::overview')) {
        url += '&btover=1'
      }
      jeedomUtils.loadPage(url)
      return
    }
    //Summary action:
    if (event.ctrlKey && (event.target.matches('.objectSummaryAction') || event.target.closest('.objectSummaryAction') != null)) {
      event.stopPropagation()
      jeedomUtils.closeModal()
      jeedomUtils.closeJeeDialogs()
      var _el = (event.target.matches('.objectSummaryAction')) ? event.target : event.target.closest('.objectSummaryAction')
      var url = 'index.php?v=d&modal=summary.action&summary=' + _el.dataset.summary + '&object_id=' + _el.dataset.object_id
      url += '&coords=' + event.clientX + '::' + event.clientY
      jeeDialog.dialog({
        id: 'md_summaryAction',
        setTitle: false,
        contentUrl: url
      })
      return
    }
    //close all modales on outside click - deprecated 4.4
    if (event.target.matches('.ui-widget-overlay')) {
      event.stopPropagation()
      if (typeof jQuery === 'function') $('.ui-dialog-content').dialog("close")
      return
    }

    //display cron modal construction:
    if (event.target.parentNode != null && (event.target.parentNode.matches('.jeeHelper[data-helper="cron"]') || event.target.matches('.jeeHelper[data-helper="cron"]'))) {
      event.stopPropagation()
      var input = event.target.closest('div').querySelector('input')
      if (input) {
        jeedom.getCronSelectModal({}, function(result) {
          input.value = result.value
        })
      }
      return
    }

    //buton show password:
    if (event.target.parentNode != null && (event.target.parentNode.matches('a.bt_showPass') || event.target.matches('a.bt_showPass'))) {
      event.stopPropagation()
      var _el = event.target.matches('a.bt_showPass') ? event.target : event.target.parentNode
      jeedomUtils.hideAlert()
      _el.closest('.input-group').querySelector('input').toggleClass('inputPassword')
      if (_el.querySelector('.fas').hasClass('fa-eye-slash')) {
        _el.querySelector('.fas').removeClass('fa-eye-slash').addClass('fa-eye')
      } else {
        _el.querySelector('.fas').removeClass('fa-eye').addClass('fa-eye-slash')
      }
      return
    }
  })
}

//Initiators__
jeedomUtils.initPage = function() {
  jeedomUtils.initTableSorter()
  jeedomUtils.initReportMode()
  if (typeof jQuery === 'function' && typeof $.initTableFilter === 'function') $.initTableFilter()
  jeedomUtils.initHelp()
  jeedomUtils.initTextArea()

  if (getUrlVars('theme') !== false) {
    jeedomUtils.changeTheme(getUrlVars('theme'))
  }

  /*
  $('.nav-tabs a').on('click', function(event) {
    if (event.delegateTarget.getAttribute('data-action') == 'returnToThumbnailDisplay') {
      event.preventDefault()
      event.stopPropagation()
    }
  })
  */

  setTimeout(function() {
    jeedomUtils.initTooltips()
  }, 750)
  try {
    if (getDeviceType()['type'] == 'desktop') document.querySelector("input[id^='in_search']")?.focus()
  } catch (error) { }
  jeedomUtils.initDisplayAsTable()
}

jeedomUtils.initDisplayAsTable = function() {
  var buttonAsTable = document.getElementById('bt_displayAsTable')
  if (buttonAsTable != null) {
    if (getCookie('jeedom_displayAsTable') == 'true' || jeedom.theme.theme_displayAsTable == 1) {
      buttonAsTable.dataset.state = 1
      buttonAsTable.addClass('active')
      var cardClass = buttonAsTable.dataset.card
      if (cardClass != undefined) {
        document.querySelectorAll(cardClass)?.addClass('displayAsTable')
      }
      var containerClass = buttonAsTable.dataset.container
      if (containerClass != undefined) {
        document.querySelector(containerClass)?.addClass('containerAsTable')
      }
    }

    buttonAsTable.addEventListener('click', function(event) {
      if (this.dataset.state == '0') {
        this.dataset.state = '1'
        this.addClass('active')
        setCookie('jeedom_displayAsTable', 'true', 7)

        try {
          document.querySelectorAll(this.dataset.card)?.addClass('displayAsTable')
          document.querySelector(this.dataset.container)?.addClass('containerAsTable')
        } catch (error) { }
      } else {
        this.dataset.state = '0'
        this.removeClass('active')
        setCookie('jeedom_displayAsTable', 'false', 7)

        try {
          document.querySelectorAll(this.dataset.card)?.removeClass('displayAsTable')
          document.querySelector(this.dataset.container)?.removeClass('containerAsTable')
        } catch (error) { }
      }
    })
  }
}


jeedomUtils.TOOLTIPSOPTIONS = {
  onTrigger: (instance, event) => {
    if (instance.reference.getAttribute('title') != null) {
      instance.reference.setAttribute('data-title', instance.reference.getAttribute('title'))
      instance.reference.removeAttribute('title')
    }
    if (instance.reference.getAttribute('data-title') == '') return false
    instance.setContent(instance.reference.getAttribute('data-title'))
    return true
  },
  lazy: false,
  onCreate: (instance) => {
    instance.reference.addClass('tippied')
  },
  arrow: true,
  allowHTML: true,
  distance: 10,
  delay: [50, 0],
  //trigger: 'click',
  //hideOnClick: false
}
jeedomUtils.initTooltips = function(_el) {
  var selector = '[tooltip]:not(.tippied), [title]:not(.tippied):not(.ui-button)'
  var items = null

  if (!isset(_el)) {
    items = document.querySelectorAll(selector)
  } else {
    if (isElement_jQuery(_el)) _el = _el[0] //Deprecated, keep for plugins
    items = _el.querySelectorAll(selector)
  }

  items.forEach(_tip => {
    if (_tip.getAttribute('title') != null) {
      _tip.setAttribute('data-title', _tip.getAttribute('title'))
      _tip.removeAttribute('title')
    }
    if (_tip.getAttribute('tooltip') != null) {
      _tip.setAttribute('data-title', _tip.getAttribute('tooltip'))
      _tip.removeAttribute('tooltip')
    }
  })

  tippy(items, jeedomUtils.TOOLTIPSOPTIONS)
}


jeedomUtils.disableTooltips = function() {
  document.querySelectorAll('.tippied').forEach(_tip => {
    if (_tip._tippy) _tip._tippy.disable()
  })
}
jeedomUtils.enableTooltips = function() {
  document.querySelectorAll('.tippied').forEach(_tip => {
    if (_tip._tippy) _tip._tippy.enable()
  })
}

jeedomUtils.initTextArea = function() {
  if (typeof jQuery === 'function') {
    $('body').on('change keyup keydown paste cut', 'textarea.autogrow', function() {
      $(this).height(0).height(this.scrollHeight)
    })
  }
}

jeedomUtils.initReportMode = function() {
  if (getUrlVars('report') == 1) {
    document.querySelectorAll('header')?.unseen()
    document.querySelectorAll('footer')?.unseen()
    let mainContainer = document.getElementById('div_mainContainer')
    if (mainContainer != null) mainContainer.style.marginTop = '-50px'
    let wrap = document.getElementById('wrap')
    if (wrap != null) wrap.style.marginBottom = '0px'
    document.querySelectorAll('.reportModeVisible')?.seen()
    document.querySelectorAll('.reportModeHidden')?.unseen()
  }
}

jeedomUtils.initTableSorter = function(filter) {
  if (typeof jQuery !== 'function') return
  // if (typeof $.tablesorter !== 'function') return
  var widgets = ['uitheme', 'resizable']
  if (!filter) {
    filter = true
  }
  if (filter !== false) {
    widgets.push('filter')
  }

  $('table.tablesorter').tablesorter({
    dateFormat: "yyyy-mm-dd",
    theme: "bootstrap",
    widthFixed: false,
    widgets: widgets,
    ignoreCase: true,
    delayInit: false,
    resizable: false,
    saveSort: false,
    sortLocaleCompare: true,
    widgetOptions: {
      filter_ignoreCase: true,
      resizable: true,
      stickyHeaders_offset: $('header.navbar-fixed-top').height()
    },
    cssIcon: 'tablesorter-icon',
    initialized: function(table) {
      $(table).find('thead .tablesorter-header-inner').append('<i class="tablesorter-icon"></i>')
    }
  }).css('width', '')
}

jeedomUtils.initDataTables = function(_selector, _paging, _searching) {
  if (!isset(_selector)) _selector = 'body'
  if (!_paging) _paging = false
  if (!_searching) _searching = false
  document.querySelector(_selector).querySelectorAll('table.dataTable').forEach(_table => {
    if (_table._dataTable) {
      _table._dataTable.destroy()
    }
    new DataTable(_table, {
      columns: [
        { select: 0, sort: "asc" }
      ],
      paging: _paging,
      searchable: _searching,
    })
  })
}


jeedomUtils.initHelp = function() {
  document.querySelectorAll('.help').forEach(element => {
    if (element.getAttribute('data-help') != undefined) {
      element.insertAdjacentHTML('beforeend', ' <sup><i class="fas fa-question-circle tooltips" title="' + element.getAttribute('data-help') + '"></i></sup>')
    }
  })
}

//Deprecated, plugins may use, old jQuery ui autocomplete
jeedomUtils.autocompleteDestroy = function() {
  document.querySelectorAll('ul.ui-autocomplete, div.ui-helper-hidden-accessible')?.remove()
}

jeedomUtils.datePickerInit = function(_format, _selector) {
  if (!isset(_format)) _format = 'Y-m-d'
  let _enableTime = _format.includes(' ') ? true : false

  if (!isset(_selector)) _selector = 'input.in_datepicker'

  //Default us
  let lang = jeeFrontEnd.language.substring(0, 2)
  if (lang == 'fr') flatpickr.localize(flatpickr.l10ns.fr)
  if (lang == 'es') flatpickr.localize(flatpickr.l10ns.es)

  document.querySelectorAll(_selector).forEach(_input => {
    flatpickr(_input, {
      enableTime: _enableTime,
      dateFormat: _format,
      time_24hr: true,
    })
  })
}

jeedomUtils.dateTimePickerInit = function(_step) {
  if (!isset(_step)) _step = 5
  let lang = jeeFrontEnd.language.substring(0, 2)
  if (lang == 'fr') flatpickr.localize(flatpickr.l10ns.fr)
  if (lang == 'es') flatpickr.localize(flatpickr.l10ns.es)

  // .isdatepicker deprecated 4.4
  document.querySelectorAll('input.in_timepicker, input.isdatepicker').forEach(_input => {
    flatpickr(_input, {
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      minuteIncrement: _step
    })
  })
}

jeedomUtils.datePickerDestroy = function() {
  document.querySelectorAll('input.isdatepicker, input.in_datepicker').forEach(_input => {
    if (isset(_input._flatpickr)) _input._flatpickr.destroy()
  })
  document.querySelectorAll('body > div.flatpickr-calendar').forEach(_div => {
    _div.remove()
  })
}

jeedomUtils.initSpinners = function() {
  if (typeof jQuery === 'function') {
    $('input[type="number"].ui-spinner').spinner({
      icons: {
        down: "ui-icon-triangle-1-s",
        up: "ui-icon-triangle-1-n"
      }
    })
  }

  document.querySelectorAll('input[type="number"].ispin').forEach(_spin => {
    var step = _spin.getAttribute('step') != undefined ? parseFloat(_spin.getAttribute('step')) : 1
    var min = _spin.getAttribute('min') != undefined ? parseFloat(_spin.getAttribute('min')) : 1
    var max = _spin.getAttribute('max') != undefined ? parseFloat(_spin.getAttribute('max')) : 1
    new ISpin(_spin, {
      wrapperClass: 'ispin-wrapper',
      buttonsClass: 'ispin-button',
      step: step,
      min: min,
      max: max,
      disabled: false,
      repeatInterval: 200,
      wrapOverflow: true,
      parse: Number
    })
    if (_spin.hasClass('roundedLeft')) {
      _spin.closest('.ispin-wrapper').addClass('roundedLeft')
    }
    if (_spin.hasClass('roundedRight')) {
      _spin.closest('.ispin-wrapper').addClass('roundedRight')
    }
  })
}

jeedomUtils.jeeCtxMenuDestroy = function() {
  document.querySelectorAll('div.jeeCtxMenu').forEach(_ctx => {
    if (isset(_ctx._jeeCtxMenu)) {
      _ctx._jeeCtxMenu.destroy()
    } else {
      _ctx.remove()
    }
  })
}

//General functions__
jeedomUtils.normTextLower = function(_text) {
  try {
    var result = _text.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase()
  } catch (error) {
    var result = ''
  }
  return result
}

jeedomUtils.linkify = function(inputText) {
  if (!inputText || inputText == '' || inputText === null) {
    return ''
  }
  var replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim
  var replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>')
  var replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim
  var replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>')
  var replacePattern3 = /(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/gim
  var replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>')
  return replacedText
}

jeedomUtils.sleep = function(milliseconds) {
  var start = new Date().getTime()
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds) {
      break
    }
  }
}

jeedomUtils.uniqId_count = 0
jeedomUtils.uniqId = function(_prefix) {
  if (typeof _prefix == 'undefined') {
    _prefix = 'jee-uniq'
  }
  var result = _prefix + '-' + jeedomUtils.uniqId_count + '-' + Math.random().toString(36).substring(8)
  jeedomUtils.uniqId_count++
  if (document.getElementById(result) != null) {
    return jeedomUtils.uniqId(_prefix)
  }
  return result
}

jeedomUtils.taAutosize = function(_el) {
  //http://www.jacklmoore.com/autosize/
  if (isset(_el)) {
    var doOn = _el
  } else {
    var doOn = document.querySelectorAll('.ta_autosize')
  }
  autosize(doOn)
  autosize.update(doOn)
}

jeedomUtils.hexToRgb = function(hex) {
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null
}

jeedomUtils.componentToHex = function(c) {
  var hex = c.toString(16)
  return hex.length == 1 ? "0" + hex : hex
}

jeedomUtils.rgbToHex = function(r, g, b) {
  if (typeof r === 'string' && !g) {
    r = r.trim()
    if (r.startsWith('rgb')) {
      r = r.replace('rgb', '')
    }
    if (r.startsWith('(')) {
      r = r.replace('(', '')
    }
    if (r.endsWith(')')) {
      r = r.replace(')', '')
    }
    var strAr = r.split(',')
    r = parseInt(strAr[0].trim())
    g = parseInt(strAr[1].trim())
    b = parseInt(strAr[2].trim())
  }
  return "#" + jeedomUtils.componentToHex(r) + jeedomUtils.componentToHex(g) + jeedomUtils.componentToHex(b)
}

jeedomUtils.addOrUpdateUrl = function(_param, _value, _title) {
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
    if (jeeFrontEnd.PREVIOUS_PAGE != 'index.php?' + window.location.href.split("index.php?")[1]) {
      window.history.pushState('', '', window.location.href)
    }
    if (_title && _title != '') {
      document.title = _title
    }
    window.history.pushState('', '', url.toString())
    jeeFrontEnd.PREVIOUS_PAGE = 'index.php?' + url.split("index.php?")[1]
  } else {
    if (_title && _title != '') {
      document.title = _title
    }
  }
}

//Global UI functions__
jeedomUtils.setJeedomMenu = function() {
  //Listener on body to catch Jeedom links for loadpage() instead of reloading url
  document.body.addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('a')) {
      if (_target.hasClass('noOnePageLoad')) return
      if (_target.getAttribute('href') == undefined || _target.getAttribute('href') == '' || _target.getAttribute('href') == '#') return
      if (_target.getAttribute('href').match("^data:")) return
      if (_target.getAttribute('href').match("^http")) return
      if (_target.getAttribute('href').match("^#")) return
      if (_target.getAttribute('target') == '_blank') return

      if (!_target.hasClass('navbar-brand')) jeedomUtils.closeJeedomMenu()

      event.preventDefault()
      event.stopPropagation()
      jeedomUtils.loadPage(_target.getAttribute('href'))
    }

    //one submenu opened at a time in mobile:
    if (event.target.matches('.navbar-nav > li > input')) {
      var checked = event.target.checked
      document.querySelectorAll('#jeedomMenuBar .navbar-nav li > input').forEach(input => {
        input.checked = false
      })
      event.target.checked = checked
    }

    if (event.target.matches('.navbar-nav > li > ul > li > input')) {
      var checked = event.target.checked
      document.querySelectorAll('#jeedomMenuBar .navbar-nav > li > ul > li > input').forEach(input => {
        input.checked = false
      })
      event.target.checked = checked
    }
  })

  if (typeof user_isAdmin !== 'undefined' && user_isAdmin == 1) {
    document.getElementById('configName')?.addEventListener('click', event => {
      //center mouse click event to new tab:
      if (isset(event.detail) && event.detail.newtab) {
        var url = 'index.php?v=d&p=administration'
        window.open(url).focus()
        return false
      }

      //shortcuts:
      if (event.ctrlKey && event.altKey) {
        jeedomUtils.loadPage('index.php?v=d&p=massedit')
        return false
      }
      if (event.ctrlKey) {
        jeedomUtils.loadPage('index.php?v=d&p=system')
        return false
      }
      if (event.altKey) {
        jeedomUtils.loadPage('index.php?v=d&p=database')
        return false
      }
      if (event.shiftKey) {
        jeedomUtils.loadPage('index.php?v=d&p=editor')
        return false
      }

      //open configuration:
      jeedomUtils.loadPage('index.php?v=d&p=administration')

    })

    document.getElementById('configName')?.addEventListener('mouseup', event => {
      if (event.which == 2) {
        event.preventDefault()
        event.target.triggerEvent('click', { detail: { newtab: true } })
      }
    })

  }
}

jeedomUtils.closeJeedomMenu = function() {
  document.querySelectorAll('#jeedomMenuBar .navbar-nav')?.addClass('disabled')
  setTimeout(function() {
    document.querySelectorAll('#jeedomMenuBar .navbar-nav')?.removeClass('disabled')
  }, 250)

  if (window.innerWidth < 768) {
    document.querySelectorAll('#jeedomMenuBar .navbar-collapse.in')?.removeClass('in')
  }
}

jeedomUtils.positionEqLogic = function(_id, _preResize, _scenario) {
  var margin = '0px ' + jeedom.theme['widget::margin'] + 'px ' + jeedom.theme['widget::margin'] + 'px 0'

  //Get full width, step columns, to fill right space:
  if (document.getElementsByClassName('posEqWidthRef').length > 0) {
    var containerWidth = document.getElementsByClassName('posEqWidthRef')[0].offsetWidth
  } else {
    var containerWidth = window.innerWidth - 22
  }
  var cols = Math.floor(containerWidth / jeedomUtils.tileWidthStep)
  var tileWidthAdd = containerWidth - (cols * jeedomUtils.tileWidthStep)
  var widthStep = jeedomUtils.tileWidthStep + (tileWidthAdd / cols)
  var widthSteps = Array.apply(null, { length: 50 }).map(function(value, index) { return (index + 1) * widthStep })

  if (_id != undefined) {
    var tile = (_scenario) ? document.querySelector('.scenario-widget[data-scenario_id="' + _id + '"]') : document.querySelector('.eqLogic-widget[data-eqlogic_id="' + _id + '"]')
    if (init(_preResize, true)) {
      Object.assign(tile.style, {
        width: (Math.floor(tile.style.width / jeedom.theme['widget::step::width']) * jeedom.theme['widget::step::width'] - (2 * jeedom.theme['widget::margin'])) + 'px',
        height: (Math.floor(tile.style.height / jeedom.theme['widget::step::height']) * jeedom.theme['widget::step::height'] - (2 * jeedom.theme['widget::margin'])) + 'px'
      })
    }
    var width = jeedomUtils.getClosestInArray(tile.offsetWidth, widthSteps)
    tile.dataset.confWidth = tile.offsetWidth
    var height = jeedomUtils.getClosestInArray(tile.offsetHeight, jeedomUtils.tileHeightSteps)
    Object.assign(tile.style, {
      width: (width - parseInt(jeedom.theme['widget::margin'])) + 'px',
      height: (height - parseInt(jeedom.theme['widget::margin'])) + 'px',
      margin: margin
    })
    tile.classList.add('jeedomAlreadyPosition')
  } else {
    var width, height, idx
    var elements = document.querySelectorAll('div.eqLogic-widget, div.scenario-widget')

    for (idx = 0; idx < elements.length; idx++) {
      tile = elements[idx]

      if (tile.dataset.confWidth === undefined) {
        tile.dataset.confWidth = tile.offsetWidth
        tile.dataset.stepHeight = jeedomUtils.tileHeightSteps.indexOf(jeedomUtils.getClosestInArray(tile.offsetHeight, jeedomUtils.tileHeightSteps))
      }
      width = jeedomUtils.getClosestInArray(tile.dataset.confWidth, widthSteps)
      height = jeedomUtils.tileHeightSteps[tile.dataset.stepHeight]

      Object.assign(tile.style, {
        width: (width - parseInt(jeedom.theme['widget::margin'])) + 'px',
        height: (height - parseInt(jeedom.theme['widget::margin'])) + 'px',
        margin: margin
      })
      tile.classList.add('jeedomAlreadyPosition')
    }
  }
}
jeedomUtils.getClosestInArray = function(_num, _refAr) {
  return _refAr.reduce(function(prev, curr) {
    //return (Math.abs(curr - _num) < Math.abs(prev - _num) ? curr : prev) // old
    return (Math.abs(_num) > Math.abs(prev) ? curr : prev) // new
  })
}

//Deprecated 4.4, obsolete 4.6
jeedomUtils.showHelpModal = function(_name, _plugin) {
  var url_helpWebsite
  var url_helpSpe
  if (init(_plugin) != '' && _plugin != undefined) {
    url_helpWebsite = 'index.php?v=d&modal=help.website&page=doc_plugin_' + _plugin + '.php #primary'
    url_helpSpe = 'index.php?v=d&plugin=' + _plugin + '&modal=help.' + init(_name)
  } else {
    url_helpWebsite = 'index.php?v=d&modal=help.website&page=doc_' + init(_name) + '.php #primary'
    url_helpSpe = 'index.php?v=d&modal=help.' + init(_name)
  }

  document.getElementById('div_helpWebsite').load(url_helpWebsite, function() {
    if (document.getElementById('div_helpWebsite').querySelectorAll('.alert.alert-danger').length > 0 || document.getElementById('div_helpWebsite').textContent.trim() == '') {
      document.querySelector('a[href="#div_helpSpe"]').click()
      document.querySelector('a[href="#div_helpWebsite"]').unseen()
    } else {
      document.querySelector('a[href="#div_helpWebsite"]').seen().click()
    }
  })
  document.getElementById('div_helpSpe').load(url_helpSpe)
}

jeedomUtils.reloadPagePrompt = function(_title) {
  jeeDialog.confirm({
    title: '<i class="success fas fa-check-circle"></i> ' + _title,
    message: '{{Voulez vous recharger la page maintenant ?}}',
    buttons: {
      confirm: {
        label: '{{Recharger}}',
        className: 'success'
      },
      cancel: {
        label: '{{Rester sur la page}}',
        className: 'info'
      }
    },
    callback: function(result) {
      if (result) {
        window.location.reload(true)
      }
    }
  })
}

jeedomUtils.chooseIcon = function(_callback, _params) {
  var url = 'index.php?v=d&modal=icon.selector'
  if (_params && _params.img && _params.img === true) {
    url += '&showimg=1'
  }
  if (_params && _params.icon) {
    var icon = _params.icon
    var replaceAr = ['icon_blue', 'icon_green', 'icon_orange', 'icon_red', 'icon_yellow']
    replaceAr.forEach(function(element) {
      if (icon.includes(element)) {
        icon = icon.replace(element, '')
        _params.color = (!_params.color) ? element : _params.color
      }
    })
    icon = icon.trim().split(' ').join('.')
    url += '&selectIcon=' + icon
  }
  if (_params && _params.color) {
    url += '&colorIcon=' + _params.color
  }
  if (_params && _params.object_id) {
    url += '&object_id=' + _params.object_id
  }
  if (_params && _params.path) {
    url += '&path=' + encodeURIComponent(_params.path)
  }
  jeeDialog.dialog({
    id: 'mod_selectIcon',
    title: '{{Choisir une illustration}}',
    width: (window.innerWidth - 50) < 1500 ? window.innerWidth - 50 : window.innerHeight - 150,
    height: window.innerHeight - 150,
    buttons: {
      confirm: {
        label: '{{Appliquer}}',
        className: 'success',
        callback: {
          click: function(event) {
            if (document.getElementById('mod_selectIcon').querySelector('.iconSelected .iconSel') === null) {
              jeeDialog.get('#mod_selectIcon').close()
              return
            }
            var icon = document.getElementById('mod_selectIcon').querySelector('.iconSelected .iconSel').innerHTML
            if (icon == undefined) {
              icon = ''
            }
            icon = icon.replace(/"/g, "'")
            _callback(icon)
            jeeDialog.get('#mod_selectIcon').close()
          }
        }
      },
      cancel: {
        label: '{{Annuler}}',
        className: 'warning',
        callback: {
          click: function(event) {
            jeeDialog.get('#mod_selectIcon').close()
          }
        }
      }
    },
    onClose: function() {
      jeeDialog.get('#mod_selectIcon').destroy() //No twice footer select/search
    },
    contentUrl: url
  })
}

jeedomUtils.getOpenedModal = function() {
  var _return = false
  document.querySelectorAll('div.jeeDialog').forEach(_dialog => {
    if (_dialog.isVisible()) _return = true
  })
  //Deprecated 4.4:
  document.querySelectorAll('div.ui-dialog.ui-widget').forEach(_dialog => {
    if (_dialog.isVisible()) _return = true
  })
  return _return
}

//Deprecated 4.4 keep for plugins
jeedomUtils.closeModal = function(_modals = '') {
  if (typeof jQuery != 'function') return
  if (_modals == '') {
    _modals = ['md_modal', 'md_modal2', 'md_modal3']
  }
  if (!Array.isArray(_modals)) {
    _modals = [_modals]
  }
  _modals.forEach(function(_modal) {
    try {
      $('#' + _modal).dialog('close')
    } catch (error) { }

  })
}

jeedomUtils.closeJeeDialogs = function() {
  document.querySelectorAll('div.jeeDialog').forEach(_dialog => {
    //uninitialized modal doesn't have _jeeDialog
    if (isset(_dialog._jeeDialog)) _dialog._jeeDialog.close(_dialog)
  })
}

//Deprecated jQuery UI ui-dialog
jeedomUtils.cleanModals = function(_modals = '') {
  document.querySelectorAll('.ui-dialog .cleanableModal')?.forEach(function(element) {
    element.closest('.ui-dialog')?.remove()
  })
}

//Context menu on checkbox
jeedomUtils.setCheckboxStateByType = function(_type, _state, _callback) {
  if (!isset(_type)) return false
  if (!isset(_state)) _state = -1
  var checkboxes = document.querySelectorAll(_type)
  if (checkboxes == null) return
  var isCallback = (isset(_callback) && typeof _callback === 'function') ? true : false
  var execCallback = false
  checkboxes.forEach(function(checkbox) {
    execCallback = false
    if (_state == -1) {
      checkbox.checked = !checkbox.checked
      execCallback = true
    } else {
      if (checkbox.checked != _state) {
        checkbox.checked = _state
        execCallback = true
      }
    }
    if (isCallback && execCallback) {
      _callback(checkbox)
    }
  })
}
jeedomUtils.getElementType = function(_el) {
  let thisType = ''
  if (_el.tagName === 'INPUT') thisType = 'input[type="' + _el.getAttribute('type') + '"]'

  if (_el.getAttribute("data-context")) {
    thisType += '[data-context="' + _el.getAttribute("data-context") + '"]'
  } else {
    if (_el.getAttribute("data-l1key")) {
      thisType += '[data-l1key="' + _el.getAttribute("data-l1key") + '"]'
    }
    if (_el.getAttribute("data-l2key")) {
      thisType += '[data-l2key="' + _el.getAttribute("data-l2key") + '"]'
    }
  }
  return thisType
}
jeedomUtils.setCheckContextMenu = function(_callback) {
  let ctxSelector = 'input[type="checkbox"].checkContext, input[type="radio"].checkContext'
  try {
    document.querySelector('.contextmenu-checkbox')._jeeCtxMenu.destroy()
    document.querySelector('.contextmenu-checkbox')?.remove()
  } catch (e) { }

  jeedomUtils.checkContextMenu = new jeeCtxMenu({
    selector: ctxSelector,
    appendTo: 'body',
    className: 'contextmenu-checkbox',
    zIndex: 9999,
    items: {
      all: {
        name: "{{Sélectionner tout}}",
        callback: function(key, opt) {
          let thisType = jeedomUtils.getElementType(opt.trigger)
          jeedomUtils.setCheckboxStateByType(thisType, 1, _callback)
        }
      },
      none: {
        name: "{{Désélectionner tout}}",
        callback: function(key, opt) {
          let thisType = jeedomUtils.getElementType(opt.trigger)
          jeedomUtils.setCheckboxStateByType(thisType, 0, _callback)
        }
      },
      invert: {
        name: "{{Inverser la sélection}}",
        callback: function(key, opt) {
          let thisType = jeedomUtils.getElementType(opt.trigger)
          jeedomUtils.setCheckboxStateByType(thisType, -1, _callback)
        }
      }
    }
  })
}

//Need jQuery and jQuery UI plugin loaded:
if (typeof jQuery === 'function') {
  jQuery.fn.setCursorPosition = function(position) {
    if (this.lengh == 0) return this
    return $(this).setSelection(position, position)
  }
  jQuery.fn.setSelection = function(selectionStart, selectionEnd) {
    if (this.lengh == 0) return this
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
  $.ui.dialog.prototype._focusTabbable = $.noop //avoid ui-dialog focus on inputs when opening
}
//Deprecated functions:
/**
 * Send message to alert about deprecated function.
 * @param {string} _oldFnName
 * @param {string} _newFnName
 * @param {string} _since
 * @param {string} _to
 * @param {string} _line
 */
jeedomUtils.deprecatedFunc = function(_oldFnName, _newFnName, _since, _to, _line) {
  if (jeeFrontEnd.coreBranch == 'V4-stable') return
  var msg = `!WARNING! Deprecated function ${_oldFnName} since Core v${_since}: Use new Core v${_to} ${_newFnName}() function.`

  if (document.body.getAttribute('data-type') == 'plugin') {
    var _pluginId = $('body').attr('data-page')
    jeedom.plugin.get({
      id: _pluginId,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        msg += ' plugin: ' + _pluginId + ' | require: ' + data.require
      }
    })
  }

  setTimeout(() => {
    console.error(msg)
    var jsError = {
      filename: 'desktop/common/js/utils.js',
      lineno: '-1',
      message: msg,
    }
    var isShown = jeedomUtils.JS_ERROR.filter(v => v.message == msg)
    if (isShown.length < 1) {
      jeedomUtils.JS_ERROR.push(jsError)
      document.getElementById('bt_jsErrorModal')?.seen()
    }
  }, 500)
}

//Introduced in v4.2 -> deprecated v4.4 -> obsolete v4.5
function checkPageModified() {
  jeedomUtils.deprecatedFunc('checkPageModified', 'jeedomUtils.checkPageModified', '4.4', '4.2')
  return jeedomUtils.checkPageModified()
}
function loadPage(_url, _noPushHistory) {
  jeedomUtils.deprecatedFunc('loadPage', 'jeedomUtils.loadPage', '4.4', '4.2')
  return jeedomUtils.loadPage(_url, _noPushHistory)
}
function initPage() {
  jeedomUtils.deprecatedFunc('initPage', 'jeedomUtils.initPage', '4.4', '4.2')
  return jeedomUtils.initPage()
}
function initTooltips(_el) {
  jeedomUtils.deprecatedFunc('initTooltips', 'jeedomUtils.initTooltips', '4.4', '4.2')
  return jeedomUtils.initTooltips(_el)
}
function initTableSorter(filter) {
  jeedomUtils.deprecatedFunc('initTableSorter', 'jeedomUtils.initTableSorter', '4.4', '4.2')
  return jeedomUtils.initTableSorter(filter)
}
function initHelp() {
  jeedomUtils.deprecatedFunc('initHelp', 'jeedomUtils.initHelp', '4.4', '4.2')
  return jeedomUtils.initHelp()
}
function datePickerInit() {
  jeedomUtils.deprecatedFunc('datePickerInit', 'jeedomUtils.datePickerInit', '4.4', '4.2')
  return jeedomUtils.datePickerInit()
}
function normTextLower(_text) {
  jeedomUtils.deprecatedFunc('normTextLower', 'jeedomUtils.normTextLower', '4.4', '4.2')
  return jeedomUtils.normTextLower(_text)
}
function sleep(milliseconds) {
  jeedomUtils.deprecatedFunc('sleep', 'jeedomUtils.sleep', '4.4', '4.2')
  return jeedomUtils.sleep(milliseconds)
}
function uniqId(_prefix) {
  jeedomUtils.deprecatedFunc('uniqId', 'jeedomUtils.uniqId', '4.4', '4.2')
  return jeedomUtils.uniqId(_prefix)
}
function taAutosize() {
  jeedomUtils.deprecatedFunc('taAutosize', 'jeedomUtils.taAutosize', '4.4', '4.2')
  return jeedomUtils.taAutosize()
}
function hexToRgb(hex) {
  jeedomUtils.deprecatedFunc('hexToRgb', 'jeedomUtils.hexToRgb', '4.4', '4.2')
  return jeedomUtils.hexToRgb(hex)
}
function componentToHex(c) {
  jeedomUtils.deprecatedFunc('componentToHex', 'jeedomUtils.componentToHex', '4.4', '4.2')
  return jeedomUtils.componentToHex(c)
}
function rgbToHex(r, g, b) {
  jeedomUtils.deprecatedFunc('rgbToHex', 'jeedomUtils.rgbToHex', '4.4', '4.2')
  return jeedomUtils.rgbToHex(r, g, b)
}
function addOrUpdateUrl(_param, _value, _title) {
  jeedomUtils.deprecatedFunc('addOrUpdateUrl', 'jeedomUtils.addOrUpdateUrl', '4.4', '4.2')
  return jeedomUtils.addOrUpdateUrl(_param, _value, _title)
}
function positionEqLogic(_id, _preResize, _scenario) {
  jeedomUtils.deprecatedFunc('positionEqLogic', 'jeedomUtils.positionEqLogic', '4.4', '4.2')
  return jeedomUtils.positionEqLogic(_id, _preResize, _scenario)
}
function chooseIcon(_callback, _params) {
  jeedomUtils.deprecatedFunc('chooseIcon', 'jeedomUtils.chooseIcon', '4.4', '4.2')
  return jeedomUtils.chooseIcon(_callback, _params)
}
function getOpenedModal() {
  jeedomUtils.deprecatedFunc('getOpenedModal', 'jeedomUtils.getOpenedModal', '4.4', '4.2')
  return jeedomUtils.getOpenedModal()
}


//Introduced in v4.3 -> obsolete 4.5 ?
var jeedom_langage = jeeFrontEnd.language
var userProfils = jeeFrontEnd.userProfils
