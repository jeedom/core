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
jeedomUtils.tileWidthStep = parseInt(jeedom.theme['widget::step::width']) > 110 ? parseInt(jeedom.theme['widget::step::width']) : 110
jeedomUtils.tileHeightStep = parseInt(jeedom.theme['widget::step::height']) > 100 ? parseInt(jeedom.theme['widget::step::height']) : 100
jeedomUtils.tileHeightSteps = Array.apply(null, { length: 10 }).map(function(value, index) { return (index + 1) * jeedomUtils.tileHeightStep })


/*Hijack jQuery ready function, still used in plugins
*/
jeedomUtils.$readyFn = jQuery.fn.ready
jQuery.fn.ready = function() {
  if (domUtils._DOMloading <= 0) {
    jeedomUtils.$readyFn.apply(this, arguments)
  } else {
    setTimeout(function() {
      jQuery.fn.ready.apply(this, arguments[1])
    }, 100, this, arguments)
  }
}
//Deprecated, keep for plugins using jQuery ajax call
document.addEventListener('DOMContentLoaded', function() {
  jeedomUtils._elBackground = document.getElementById('backgroundforJeedom')
  $(document)
    .ajaxStart(function() {
      domUtils.showLoading()
    })
    .ajaxStop(function() {
      domUtils.hideLoading()
    })
})

//js error in ! ui:
jeedomUtils.JS_ERROR = []
window.addEventListener('error', function(event) {
  if (event.filename.indexOf('3rdparty/') != -1) {
    return
  }
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
  var dateJeed = new Date
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

var printEqLogic = undefined
//OnePage design PageLoader -------------------------------------
jeedomUtils.loadPage = function(_url, _noPushHistory) {
  jeeFrontEnd.PREVIOUS_LOCATION = window.location.href
  if (jeedomUtils.checkPageModified()) return
  if (jeedomUtils.JS_ERROR.length > 0) {
    document.location.href = _url
    return
  }

  jeedomUtils.closeJeedomMenu()
  window.toastr.clear()
  jeedom.cmd.resetUpdateFunction()

  $.contextMenu('destroy')
  document.querySelectorAll('.context-menu-root').remove()

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
    } catch (e) { }
  }

  if (isset(bootbox)) bootbox.hideAll()
  jeedomUtils.hideAlert()
  jeedomUtils.datePickerDestroy()
  jeedomUtils.autocompleteDestroy()
  jeedomUtils.cleanModals()
  jeedom.cmd.update = []
  jeedom.scenario.update = []
  jeephp2js = {}
  delete window.jeeP
  printEqLogic = undefined
  if (jeedomUtils.OBSERVER !== null) jeedomUtils.OBSERVER.disconnect()
  $('body').off('changeThemeEvent')

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
  $('body').off('mouseenter mouseleave')
  $(window).off('resize')

  document.getElementById('div_mainContainer').querySelectorAll('script')?.remove()
  document.querySelectorAll('script[injext]')?.remove()

  document.getElementById('div_pageContainer').load(url, function() {
    document.body.setAttribute('data-page', getUrlVars('p') || '')
    document.getElementById('bt_getHelpPage').setAttribute('data-page', getUrlVars('p'))
    document.getElementById('bt_getHelpPage').setAttribute('data-plugin', getUrlVars('m') || '')
    jeedomUtils.initPage()

    document.body.triggerEvent('jeedom_page_load')

    //dashboard page on object will set its own background:
    if (!_url.includes('dashboard&object_id')) {
      if (jeedomUtils.backgroundIMG !== null) {
        jeedomUtils.setBackgroundImage(jeedomUtils.backgroundIMG)
      } else {
        jeedomUtils.setBackgroundImage('')
      }
    }

    setTimeout(function() {
      if (window.location.hash != '' && document.querySelector('ul.nav-tabs a[href="' + window.location.hash + '"]') != null) {
        document.querySelector('ul.nav-tabs a[href="' + window.location.hash + '"]').triggerEvent('click')
      }
    }, 150) //let time for plugin page!

    setTimeout(function() {
      modifyWithoutSave = false
      jeeFrontEnd.modifyWithoutSave = false
    }, 250)
  })

  setTimeout(function() {
    //scenarios uses special tooltips not requiring destroy.
    if (document.body.getAttribute('data-page') != 'scenario') {
      if (jeedomUtils.OBSERVER !== null) {
        var targetNode = document.getElementById('div_mainContainer')
        if (targetNode) jeedomUtils.OBSERVER.observe(targetNode, jeedomUtils.observerConfig)
      } else {
        jeedomUtils.createObserver()
      }
    }
  }, 500)

  return
}

/* First time loading, all next goes by loadpage()
*/
document.addEventListener('DOMContentLoaded', function() {
  jeedom.init()
  if (getDeviceType()['type'] == 'desktop') jeedomUtils.userDeviceType = 'desktop'
  document.body.setAttribute('data-device', jeedomUtils.userDeviceType)
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
  var tab = document.querySelector('.nav-tabs a[href="' + window.location.hash + '"]')
  if (window.location.hash != '' && tab != null) {
    tab.triggerEvent('click')
    //$('.nav-tabs a[href="'+window.location.hash+'"]').click()
  }

  //browser history:
  var $body = $('body')
  $body.on('shown.bs.tab', '.nav-tabs a', function(event) {
    if (event.target.hash == '') {
      return
    }
    if (event.target.closest('.ui-dialog-content')?.innerHTML !== undefined) {
      return
    }
    if (jeeFrontEnd.PREVIOUS_PAGE == null) {
      window.history.replaceState('', '', 'index.php?' + window.location.href.split("index.php?")[1])
      jeeFrontEnd.PREVIOUS_PAGE = 'index.php?' + window.location.href.split("index.php?")[1]
    }
    window.location.hash = event.target.hash
  })
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
      var tab = document.querySelector('.nav-tabs a[href="' + window.location.hash + '"]')
      if (window.location.hash != '' && tab != null) {
        tab.triggerEvent('click')
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

  jeedomUtils.setJeedomMenu()
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
    jeedomUtils.createObserver()
    $body.trigger('jeedom_page_load')
  })

  /*Move and register common scripts/css to not reload them:
    Managed on loadPage() by domUtils.loadScript() and domUtils.html()
  */
  document.getElementById('div_pageContainer').querySelectorAll('script').forEach(script => {
    if (script.src.includes('desktop/common/js') || script.src.includes('/3rdparty/')) {
      domUtils.headInjexted.push(script.src)
      document.head.appendChild(script)
    }
  })
  document.getElementById('div_pageContainer').querySelectorAll('link[rel="stylesheet"]').forEach(stylesheet => {
    if (stylesheet.href.includes('desktop/common/css') || stylesheet.href.includes('/3rdparty/')) {
      stylesheet.setAttribute('injext', '1')
      domUtils.headInjexted.push(stylesheet.href)
      document.head.appendChild(stylesheet)
    }
  })
})


/*Toastr____________ options for jeedom.notify() toastr, need jeedom.theme set!
jQuery dependant, will have to migrate to pure js!
*/
toastr.options = {
  "newestOnTop": true,
  "closeButton": true,
  "debug": false,
  "positionClass": jeedom.theme['interface::toast::position'],
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": parseInt(jeedom.theme['interface::toast::duration']) * 1000,
  "extendedTimeOut": "1500",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut",
  "progressBar": true,
  "onclick": function() {
    window.toastr.clear()
    $('#md_modal').dialog({ title: "{{Centre de Messages}}" }).load('index.php?v=d&modal=message.display').dialog('open')
  }
}
jeedomUtils.toastrUIoptions = {
  "newestOnTop": true,
  "closeButton": true,
  "tapToDismiss": false,
  "debug": false,
  "positionClass": jeedom.theme['interface::toast::position'],
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": parseInt(jeedom.theme['interface::toast::duration']) * 1000,
  "extendedTimeOut": "1500",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut",
  "progressBar": true,
  "onclick": function(event) {
    event.clickToClose = true
  }
}

jeedomUtils.showAlert = function(_options) {
  var options = init(_options, {})
  options.message = init(options.message, '')
  options.level = init(options.level, '')
  options.emptyBefore = init(options.emptyBefore, false)
  options.show = init(options.show, true)
  options.attach = init(options.attach, false)
  if (!options.ttl) {
    if (options.level == 'danger') {
      options.ttl = 0
    } else {
      options.ttl = 5000
    }
  }
  if (options.level == 'danger') options.level = 'error'
  if (options.emptyBefore == true) {
    window.toastr.clear()
  }
  let options_toastr = jeedomUtils.toastrUIoptions
  options_toastr.timeOut = options.ttl

  toastr[options.level](options.message, ' ', options_toastr)

  var toastContainer = document.getElementById('toast-container')
  if (options.attach) {
    try {
      var attachTo = document.querySelector(options.attach)
      if (attachTo != null) {
        attachTo.appendChild(toastContainer)
        toastContainer.style.position = 'absolute'
      }
    } catch (error) {
      console.error('jeedomUtils.showAlert: ' + error)
    }
  } else {
    toastContainer.style.position = ''
  }
}

jeedomUtils.hideAlert = function() {
  window.toastr.clear()

  //Deprecated, old div_alert may be used on plugins:
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
    document.getElementById('jeedom_theme_currentcss').setAttribute('data-nochange', 1)

    if (document.getElementById('jeedom_theme_currentcss').getAttribute('href').split('?md5')[0] == theme) {
      document.body.setAttribute('data-theme', jeedom.theme.jeedom_theme_main)
      theme = 'core/themes/' + jeedom.theme.jeedom_theme_main + '/desktop/' + jeedom.theme.jeedom_theme_main + '.css'
      themeShadows = 'core/themes/' + jeedom.theme.jeedom_theme_main + '/desktop/shadows.css'
      themeCook = 'default'
      themeButton = '<i class="fas fa-adjust"></i> {{Thème alternatif}}'
    } else {
      document.body.setAttribute('data-theme', jeedom.theme.jeedom_theme_alternate)
    }
    setCookie('currentTheme', themeCook, 30)
    document.getElementById('jeedom_theme_currentcss').setAttribute('href', theme)
    document.getElementById('bt_switchTheme').html(themeButton)
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
  if (getCookie('currentTheme') == 'alternate' || document.getElementById('jeedom_theme_currentcss')?.getAttribute('data-nochange') == 1) return

  var theme = jeedom.theme.jeedom_theme_alternate
  var themeCss = 'core/themes/' + jeedom.theme.jeedom_theme_alternate + '/desktop/' + jeedom.theme.jeedom_theme_alternate + '.css'
  var currentTime = parseInt((new Date()).getHours() * 100 + (new Date()).getMinutes())

  if (parseInt(jeedom.theme.theme_start_day_hour.replace(':', '')) < currentTime && parseInt(jeedom.theme.theme_end_day_hour.replace(':', '')) > currentTime) {
    theme = jeedom.theme.jeedom_theme_main
    themeCss = 'core/themes/' + jeedom.theme.jeedom_theme_main + '/desktop/' + jeedom.theme.jeedom_theme_main + '.css'
  }

  var currentTheme = document.getElementById('jeedom_theme_currentcss').getAttribute('href')
  if (currentTheme.indexOf('?md5') != -1) {
    currentTheme = currentTheme.substring(0, currentTheme.indexOf('?md5'))
  }
  if (currentTheme != themeCss) {
    $.get(themeCss)
      .done(function() {
        document.getElementById('jeedom_theme_currentcss').setAttribute('href', themeCss)
        document.body.setAttribute('data-theme', theme)
        if (document.getElementById('shadows_theme_css') != null) document.getElementById('shadows_theme_css').setAttribute('href', 'core/themes/' + theme + '/desktop/shadows.css')
        jeedomUtils.setBackgroundImage('')
        jeedomUtils.triggerThemechange()
      })
      .fail(function() {
        console.error("changeThemeAuto: can't find theme file " + themeCss)
      })
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
    if (currentTheme.endsWith('Light')) {
      document.body.triggerEvent('changeThemeEvent', {detail: {theme: 'Light'}})
    } else {
      document.body.triggerEvent('changeThemeEvent', {detail: {theme: 'Dark'}})
    }
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

    if (['dashboard', 'overview', 'home', 'equipment'].indexOf(document.body.getAttribute('data-page')) != -1) {
      if (jeedom.theme.product_interface_image) {
        _path = jeedom.theme.product_interface_image
      }
      else {
        _path = jeedom.theme['interface::background::dashboard']
      }
    } else if (['display', 'eqAnalyse', 'log', 'timeline', 'history', 'report', 'health', 'administration', 'profils', 'update', 'backup', 'cron', 'user'].indexOf(document.body.getAttribute('data-page')) != -1) {
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
jeedomUtils.initJeedomModals = function() {
  $.fn.modal.Constructor.prototype.enforceFocus = function() { }

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

  $('#md_reportBug').dialog({
    autoOpen: false,
    modal: true,
    closeText: '',
    height: ((window.innerHeight - 100) < 700) ? window.innerHeight - 100 : 700,
    width: ((window.innerWidth - 100) < 900) ? (window.innerWidth - 100) : 900,
    position: { my: 'center center-10', at: 'center center', of: window },
    open: function() {
      document.body.style.overflow = 'hidden'
      this.closest('.ui-dialog').querySelectorAll('button, input[type="button"]')?.forEach(el => { el.blur() })
      $(this).dialog({
        height: ((window.innerHeight - 100) < 700) ? window.innerHeight - 100 : 700,
        width: ((window.innerWidth - 100) < 900) ? (window.innerWidth - 100) : 900,
        position: { my: 'center center-10', at: 'center center', of: window }
      })
      setTimeout(function() { jeedomUtils.initTooltips($('#md_reportBug')) }, 500)
    },
    beforeClose: function(event, ui) {
      emptyModal('md_reportBug')
    }
  })

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

jeedomUtils.setButtonCtrlHandler = function(_buttonId, _title, _uri, _modal = '#md_modal', _open = true) {
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
      $(_modal).dialog('close')
      $(_modal).dialog({ title: _title }).load('index.php?v=d&modal=' + _uri)
      if (_open) $(_modal).dialog('open')
    }
  })

  document.getElementById(_buttonId).addEventListener('mouseup', event => {
    if (event.which == 2) {
      event.preventDefault()
      event.target.triggerEvent('click', {detail: {ctrlKey: true}})
    }
  })
}

jeedomUtils.setJeedomGlobalUI = function() {
  if (typeof jeeFrontEnd.jeedom_firstUse != 'undefined' && isset(jeeFrontEnd.jeedom_firstUse) && jeeFrontEnd.jeedom_firstUse == 1 && getUrlVars('noFirstUse') != 1) {
    $('#md_modal').dialog({ title: "{{Bienvenue dans Jeedom}}" }).load('index.php?v=d&modal=first.use').dialog('open')
  }

  window.addEventListener('beforeunload', function(event) {
    //keep old root for plugins
    if (jeeFrontEnd.modifyWithoutSave == true || window.modifyWithoutSave == true) {
      event.preventDefault()
      return '{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}'
    }
  })

  jeedomUtils.setButtonCtrlHandler('bt_showEventInRealTime', '{{Evénements en temps réel}}', 'log.display&log=event', '#md_modal')
  jeedomUtils.setButtonCtrlHandler('bt_showNoteManager', '{{Notes}}', 'note.manager', '#md_modal')
  jeedomUtils.setButtonCtrlHandler('bt_showExpressionTesting', "{{Testeur d'expression}}", 'expression.test', '#md_modal')
  jeedomUtils.setButtonCtrlHandler('bt_showDatastoreVariable', '{{Variables}}', 'dataStore.management&type=scenario', '#md_modal', false)
  jeedomUtils.setButtonCtrlHandler('bt_showSearching', '{{Recherche}}', 'search', '#md_modal')

  document.getElementById('bt_gotoDashboard').addEventListener('click', function() {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=dashboard')
  })

  document.getElementById('bt_gotoView').addEventListener('click', function() {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=view')
  })

  document.getElementById('bt_gotoPlan').addEventListener('click', function() {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=plan')
  })

  document.getElementById('bt_gotoPlan3d').addEventListener('click', function() {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.loadPage('index.php?v=d&p=plan3d')
  })

  document.getElementById('bt_jeedomAbout').addEventListener('click', function() {
    jeedomUtils.closeJeedomMenu()
    $('#md_modal').dialog({ title: "{{A propos}}" }).load('index.php?v=d&modal=about').dialog('open')
  })

  document.getElementById('bt_getHelpPage').addEventListener('click', function(event) {
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

  document.querySelector('.bt_reportBug')?.addEventListener('click', function() {
    if (!getDeviceType()['type'] == 'desktop' || window.innerWidth < 768) {
      event.stopPropagation()
      return
    }
    jeedomUtils.closeJeedomMenu()
    $('#md_reportBug').load('index.php?v=d&modal=report.bug').dialog('open')
  })

  document.getElementById('bt_messageModal').addEventListener('click', function() {
    jeedomUtils.closeModal('md_modal')
    $('#md_modal').dialog({ title: "{{Centre de Messages}}" }).load('index.php?v=d&modal=message.display').dialog('open')
  })

  document.getElementById('bt_jsErrorModal').addEventListener('click', function() {
    jeedomUtils.closeModal('md_modal')
    $('#md_modal').dialog({ title: "{{Erreur Javascript}}" }).load('index.php?v=d&modal=js.error').dialog('open')
  })

  document.body.addEventListener('keydown', function(event) {
    //search input escape:
    if (event.target.matches('input[id^="in_search"]')) {
      if (event.key == 'Escape') {
        event.stopPropagation()
        var els = ((els = document.querySelectorAll('#categoryfilter li .catFilterKey')) != null ? els.forEach(function(item) { item.checked = true }) : null)
        var els = ((els = document.querySelectorAll('#dashTopBar button.dropdown-toggle')) != null ? els.removeClass('warning') : null)
        event.target.value = ''
        return
      }
    }
  })

  document.body.addEventListener('click', function(event) {
    //Summary display:
    if (!event.ctrlKey && event.target.parentNode != null && (event.target.parentNode.matches('.objectSummaryParent') || event.target.matches('.objectSummaryParent'))) {
      event.stopPropagation()
      var _el = event.target.matches('.objectSummaryParent') ? event.target : event.target.parentNode

      if (document.body.getAttribute('data-page') == "overview" && _el.closest('.objectSummaryglobal') == null) return false

      var url = 'index.php?v=d&p=dashboard&summary=' + _el.dataset.summary + '&object_id=' + _el.dataset.object_id
      if (window.location.href.includes('&btover=1') || (document.body.getAttribute('data-page') != "dashboard" && jeeFrontEnd.userProfils.homePage == 'core::overview')) {
        url += '&btover=1'
      }
      jeedomUtils.loadPage(url)
      return
    }
    //Summary action:
    if (event.ctrlKey && event.target.parentNode != null && (event.target.parentNode.matches('.objectSummaryAction') || event.target.matches('.objectSummaryAction'))) {
      event.stopPropagation()
      jeedomUtils.mouseX = event.clientX
      jeedomUtils.mouseY = event.clientY
      jeedomUtils.closeModal()

      var _el = event.target.matches('.objectSummaryAction') ? event.target : event.target.parentNode
      var url = 'index.php?v=d&modal=summary.action&summary=' + _el.dataset.summary + '&object_id=' + _el.dataset.object_id
      $('#md_modal').dialog({ title: "{{Action sur résumé}}" }).load(url)
      return
    }

    //close all modales on outside click
    if (event.target.matches('.ui-widget-overlay')) {
      event.stopPropagation()
      $('.ui-dialog-content').dialog("close")
      return
    }

    //display cron modal construction:
    if (event.target.parentNode != null && (event.target.parentNode.matches('.jeeHelper[data-helper=cron]') || event.target.matches('.jeeHelper[data-helper=cron]'))) {
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
      console.log('clicked .bt_showPass')
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
  $.initTableFilter()
  jeedomUtils.initHelp()
  jeedomUtils.initTextArea()

  /*$('.nav-tabs a').on('click', function() {
    $(this).tab('show')
    $('#div_mainContainer').scrollTop(0)
  })*/

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

    buttonAsTable.addEventListener('click', function() {
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


jeedomUtils.OBSERVER = null
jeedomUtils.observerConfig = {
  attributes: false,
  childList: true,
  characterData: false,
  subtree: true
}
jeedomUtils.createObserver = function() {
  var mainContent = document.getElementById('div_mainContainer')
  if (mainContent) {
    jeedomUtils.OBSERVER = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (mutation.type == 'childList') {
          if (mutation.addedNodes.length >= 1) {
            if (mutation.addedNodes[0].nodeName != '#text') {
              jeedomUtils.initTooltips($(mutation.target))
            }
          }
        } else if (mutation.type == 'attributes') {
          if (mutation.attributeName == 'title') jeedomUtils.initTooltips($(mutation.target))
        }
      })
    })
    jeedomUtils.OBSERVER.observe(mainContent, jeedomUtils.observerConfig)
  }
}

jeedomUtils.TOOLTIPSOPTIONS = {
  arrow: false,
  delay: 650,
  interactive: false,
  contentAsHTML: true,
  debug: false
}
jeedomUtils.initTooltips = function(_el) {
  if (!_el) {
    try {
      $('.tooltips:not(.tooltipstered), [title]:not(.ui-button)').tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
    } catch(e) { }
  } else {
    //cmd update:
    if (_el.parents('.cmd-widget[title]').length) {
      var me = _el.closest('.cmd-widget[title]')
      if (me.hasClass('tooltipstered')) me.tooltipster('destroy')
      me.tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
      return
    }

    if (_el.hasClass('tooltips') && !_el.hasClass('tooltipstered') || _el.is('[title]')) {
      if (_el.is('[title]') && _el.hasClass('tooltipstered')) {
        _el.tooltipster('destroy')
      }
      _el.tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
    }

    _el.find('.tooltipstered[title]').tooltipster('destroy')
    _el.find('.tooltips:not(.tooltipstered), [title]').tooltipster(jeedomUtils.TOOLTIPSOPTIONS)
  }
}

jeedomUtils.initTextArea = function() {
  $('body').on('change keyup keydown paste cut', 'textarea.autogrow', function() {
    $(this).height(0).height(this.scrollHeight)
  })
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

jeedomUtils.initHelp = function() {
  document.querySelectorAll('.help').forEach(element => {
    if (element.getAttribute('data-help') != undefined) {
      element.insertAdjacentHTML('beforeend', ' <sup><i class="fas fa-question-circle tooltips" title="' + element.getAttribute('data-help') + '"></i></sup>')
    }
  })
}

jeedomUtils.autocompleteDestroy = function() {
  document.querySelectorAll('ul.ui-autocomplete, div.ui-helper-hidden-accessible')?.remove()
}

jeedomUtils.datePickerInit = function() {
  var datePickerRegion = jeeFrontEnd.language.substring(0, 2)
  if (isset($.datepicker.regional[datePickerRegion])) {
    var datePickerRegional = $.datepicker.regional[datePickerRegion]
  } else {
    var datePickerRegional = $.datepicker.regional['en']
  }
  datePickerRegional.dateFormat = "yy-mm-dd"
  $('.in_datepicker').datepicker(datePickerRegional)
}

jeedomUtils.dateTimePickerInit = function(_step) {
  $('input.isdatepicker').datetimepicker('destroy')
  $('.xdsoft_datetimepicker').remove()
  if (!isset(_step)) _step = 10
  $('input.isdatepicker').datetimepicker({
    datepicker: false,
    format: 'H:i',
    step: _step
  })
}

jeedomUtils.initSpinners = function() {
  $('input[type="number"].ui-spinner').spinner({
    icons: {
      down: "ui-icon-triangle-1-s",
      up: "ui-icon-triangle-1-n"
    }
  })
}

jeedomUtils.datePickerDestroy = function() {
  $('.in_datepicker').datepicker("destroy")
  document.querySelectorAll('.in_datepicker')?.forEach(element => {
    element.removeClass('hasDatepicker').removeAttribute('id')
  })
  document.getElementById('ui-datepicker-div')?.remove()

  //datetime:
  $('input.isdatepicker').datetimepicker('destroy')
  document.querySelectorAll('.xdsoft_datetimepicker')?.remove()
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

jeedomUtils.taAutosize = function() {
  //http://www.jacklmoore.com/autosize/
  autosize(document.querySelectorAll('.ta_autosize'))
  autosize.update(document.querySelectorAll('.ta_autosize'))
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
  if ($.type(r) === 'string' && !g) {
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
jeedomUtils.userDeviceType = 'mobile'
jeedomUtils.setJeedomMenu = function() {
  $('body').on('click', 'a', function(event) {
    if (this.hasClass('noOnePageLoad')) {
      return
    }
    if (this.hasClass('fancybox-nav')) {
      return
    }
    if (this.getAttribute('href') == undefined || this.getAttribute('href') == '' || this.getAttribute('href') == '#') {
      return
    }
    if (this.getAttribute('href').match("^data:")) {
      return
    }
    if (this.getAttribute('href').match("^http")) {
      return
    }
    if (this.getAttribute('href').match("^#")) {
      return
    }
    if (this.getAttribute('target') == '_blank') {
      return
    }

    if (!this.hasClass('navbar-brand')) jeedomUtils.closeJeedomMenu()

    event.preventDefault()
    event.stopPropagation()
    jeedomUtils.loadPage(this.getAttribute('href'))
  })

  //one submenu opened at a time in mobile:
  document.getElementById('jeedomMenuBar').addEventListener('click', event => {
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
    document.getElementById('configName').addEventListener('click', event => {
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

    document.getElementById('configName').addEventListener('mouseup', event => {
      if (event.which == 2) {
        event.preventDefault()
        event.target.triggerEvent('click', {detail: {newtab: true}})
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
  var margin = jeedom.theme['widget::margin'] + 'px ' + jeedom.theme['widget::margin'] * 2 + 'px ' + jeedom.theme['widget::margin'] + 'px 0'

  //Get full width, step columns, to fill right space:
  if (document.getElementsByClassName('posEqWidthRef').length > 0) {
    var containerWidth = document.getElementsByClassName('posEqWidthRef')[0].offsetWidth
  } else {
    var containerWidth = window.innerWidth - 22
  }
  var cols = Math.floor(containerWidth / jeedomUtils.tileWidthStep) + 1
  var tileWidthAdd = containerWidth - (cols * jeedomUtils.tileWidthStep)
  var widthStep = jeedomUtils.tileWidthStep + (tileWidthAdd / cols) - (2 * parseInt(jeedom.theme['widget::margin']))
  var widthSteps = Array.apply(null, { length: 10 }).map(function(value, index) { return (index + 1) * widthStep })

  if (_id != undefined) {
    var tile = (_scenario) ? document.querySelector('.scenario-widget[data-scenario_id="' + _id + '"]') : document.querySelector('.eqLogic-widget[data-eqlogic_id="' + _id + '"]')
    if (init(_preResize, true)) {
      Object.assign(tile.style, {
        width: (Math.floor(tile.width() / jeedom.theme['widget::step::width']) * jeedom.theme['widget::step::width'] - (2 * jeedom.theme['widget::margin'])) + 'px',
        height: (Math.floor(tile.height() / jeedom.theme['widget::step::height']) * jeedom.theme['widget::step::height'] - (2 * jeedom.theme['widget::margin'])) + 'px'
      })
    }
    var width = jeedomUtils.getClosestInArray(tile.offsetWidth, widthSteps)
    tile.dataset.confWidth = tile.offsetWidth
    var height = jeedomUtils.getClosestInArray(tile.offsetHeight, jeedomUtils.tileHeightSteps)
    Object.assign(tile.style, {
      width: (width + (2 * widthSteps.indexOf(width) * parseInt(jeedom.theme['widget::margin']))) + 'px',
      height: (height + (2 * jeedomUtils.tileHeightSteps.indexOf(height) * parseInt(jeedom.theme['widget::margin']))) + 'px',
      margin: margin
    })
  } else {
    var width, height, idx, element
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
        width: (width + (2 * widthSteps.indexOf(width) * parseInt(jeedom.theme['widget::margin']))) + 'px',
        height: (height + (2 * jeedomUtils.tileHeightSteps.indexOf(height) * parseInt(jeedom.theme['widget::margin']))) + 'px',
        margin: margin
      })
      tile.classList.add("jeedomAlreadyPosition")
    }
  }
}
jeedomUtils.getClosestInArray = function(_num, _refAr) {
  return _refAr.reduce(function(prev, curr) {
    return (Math.abs(curr - _num) < Math.abs(prev - _num) ? curr : prev)
  })
}

jeedomUtils.showHelpModal = function(_name, _plugin) {
  if (init(_plugin) != '' && _plugin != undefined) {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_plugin_' + _plugin + '.php #primary', function() {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $('#div_helpWebsite').text().trim() == '') {
        $('a[href="#div_helpSpe"]').click()
        $('a[href="#div_helpWebsite"]').hide()
      } else {
        $('a[href="#div_helpWebsite"]').show().click()
      }
    })
    $('#div_helpSpe').load('index.php?v=d&plugin=' + _plugin + '&modal=help.' + init(_name))
  } else {
    $('#div_helpWebsite').load('index.php?v=d&modal=help.website&page=doc_' + init(_name) + '.php #primary', function() {
      if ($('#div_helpWebsite').find('.alert.alert-danger').length > 0 || $('#div_helpWebsite').text().trim() == '') {
        $('a[href="#div_helpSpe"]').click()
        $('a[href="#div_helpWebsite"]').hide()
      } else {
        $('a[href="#div_helpWebsite"]').show().click()
      }
    })
    $('#div_helpSpe').load('index.php?v=d&modal=help.' + init(_name))
  }
}

jeedomUtils.reloadPagePrompt = function(_title) {
  bootbox.confirm({
    title: '<h4><i class="success fas fa-check-circle"></i> ' + _title + '</h4>',
    message: '{{Voulez vous recharger la page maintenant ?}}',
    buttons: {
      confirm: {
        label: '{{Recharger}}',
        className: 'btn-success'
      },
      cancel: {
        label: '{{Rester sur la page}}',
        className: 'btn-info'
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
  if ($("#mod_selectIcon").length == 0) {
    $('#div_pageContainer').append('<div id="mod_selectIcon"></div>')
    $("#mod_selectIcon").dialog({
      title: '{{Choisir une illustration}}',
      closeText: '',
      autoOpen: false,
      modal: true,
      height: (window.innerHeight - 150),
      width: 1500,
      open: function() {
        if ((window.innerWidth - 50) < 1500) {
          $('#mod_selectIcon').dialog({ width: window.innerWidth - 50 })
        }
        document.body.style.overflow = 'hidden'
        setTimeout(function() { jeedomUtils.initTooltips($("#mod_selectIcon")) }, 500)
      },
      beforeClose: function(event, ui) {
        $('body').css({ overflow: 'inherit' })
      }
    })
  }
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
  $('#mod_selectIcon').empty().load(url, function() {
    $("#mod_selectIcon").dialog('option', 'buttons', {
      "{{Annuler}}": function() {
        $(this).dialog("close")
      },
      "{{Valider}}": function() {
        var icon = $('.iconSelected .iconSel').html()
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

jeedomUtils.getOpenedModal = function() {
  var _return = false
  var modals = ['md_reportBug', 'md_modal', 'md_modal2', 'md_modal3', 'ui-id-5']
  modals.forEach(function(_modal) {
    if ($('.ui-dialog[aria-describedby="' + _modal + '"]').is(':visible') == true) {
      _return = _modal
    }
  })
  return _return
}

jeedomUtils.closeModal = function(_modals = '') {
  if (_modals == '') {
    _modals = ['md_reportBug', 'md_modal', 'md_modal2', 'md_modal3', 'ui-id-5']
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
  $.contextMenu('destroy', ctxSelector)
  document.querySelector('.contextmenu-checkbox')?.remove()
  jeedomUtils.checkContextMenu = $.contextMenu({
    selector: ctxSelector,
    appendTo: 'div#div_pageContainer',
    className: 'contextmenu-checkbox',
    zIndex: 9999,
    items: {
      all: {
        name: "{{Sélectionner tout}}",
        callback: function(key, opt) {
          let thisType = jeedomUtils.getElementType(opt.$trigger[0])
          jeedomUtils.setCheckboxStateByType(thisType, 1, _callback)
        }
      },
      none: {
        name: "{{Désélectionner tout}}",
        callback: function(key, opt) {
          let thisType = jeedomUtils.getElementType(opt.$trigger[0])
          jeedomUtils.setCheckboxStateByType(thisType, 0, _callback)
        }
      },
      invert: {
        name: "{{Inverser la sélection}}",
        callback: function(key, opt) {
          let thisType = jeedomUtils.getElementType(opt.$trigger[0])
          jeedomUtils.setCheckboxStateByType(thisType, -1, _callback)
        }
      }
    }
  })
}

//Need jQuery and jQuery UI plugin loaded:
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


/*Migration purpose
*/
//var jq2 = jQuery.noConflict()