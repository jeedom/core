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

function jeedom() { }
jeedom.__description = 'All js methods per Jeedom class. Handles front-end <-> ajax <-> php classes.'
jeedom.cache = []
jeedom.display = {}
jeedom.connect = 0
jeedom.theme = {}
jeedom.changes_timeout = null

jeeFrontEnd = {
  __description: 'Global object where each Core page register its own functions and variable in its sub-object name.',
  jeedom_firstUse: '',
  language: '',
  userProfils: {},
  planEditOption: { state: false, snap: false, grid: false, gridSize: false, highlight: true },
  //loadPage history:
  PREVIOUS_PAGE: null,
  PREVIOUS_LOCATION: null,
  NO_POPSTAT: false,
  modifyWithoutSave: false,
  //@index.php
  serverDatetime: null,
  clientServerDiffDatetime: null,
  serverTZoffsetMin: null,
}

/*
Jeedom namespace for data transfer php -> js through sendVarToJS(). Emptied on loadPage()
@php:
sendVarToJS([
  'jeephp2js.myjsvar1' => init('type', ''),
  'jeephp2js.myjsvar2' => config::byKey('enableCustomCss')
]);
*/
jeephp2js = {}

var Highcharts

if (!isset(jeedom.cache.getConfiguration)) {
  jeedom.cache.getConfiguration = null
}

jeedom.changes = function() {
  var paramsRequired = []
  var paramsSpecifics = {
    global: false,
    noDisplayError: true,
    success: function(data) {
      if (jeedom.connect > 0) {
        jeedom.connect = 0
      }
      jeedom.datetime = data.datetime
      var cmd_update = []
      var eqLogic_update = []
      var object_summary_update = []
      for (var i in data.result) {
        if (data.result[i].name == 'cmd::update') {
          cmd_update.push(data.result[i].option)
          continue
        }
        if (data.result[i].name == 'eqLogic::update') {
          eqLogic_update.push(data.result[i].option)
          continue
        }
        if (data.result[i].name == 'jeeObject::summary::update') {
          object_summary_update.push(data.result[i].option)
          continue
        }
        if (isset(data.result[i].option)) {
          if (['scenario::update', 'ui::update', 'jeedom::gotoplan', 'jeedom::alert', 'jeedom::alertPopup', 'jeedom::coloredIcons', 'message::refreshMessageNumber', 'update::refreshUpdateNumber', 'notify', 'checkThemechange', 'changeTheme'].includes(data.result[i].name)) {
            document.body.dispatchEvent(new CustomEvent(data.result[i].name, { detail: data.result[i].option }))
          } else {
            if (typeof jQuery === 'function') {
              $('body').trigger(data.result[i].name, data.result[i].option)
            } else {
              document.body.dispatchEvent(new CustomEvent(data.result[i].name, { detail: data.result[i].option }))
            }
          }
        } else {
          document.body.dispatchEvent(new CustomEvent(data.result[i].name))
        }
      }
      if (cmd_update.length > 0) {
        document.body.dispatchEvent(new CustomEvent('cmd::update', { detail: cmd_update }))
      }
      if (eqLogic_update.length > 0) {
        document.body.dispatchEvent(new CustomEvent('eqLogic::update', { detail: eqLogic_update }))
      }
      if (object_summary_update.length > 0) {
        document.body.dispatchEvent(new CustomEvent('jeeObject::summary::update', { detail: object_summary_update }))
      }
      jeedom.changes_timeout = setTimeout(jeedom.changes, 1)
    },
    error: function(_error) {
      if (typeof (user_id) != "undefined" && jeedom.connect == 100) {
        jeedom.notify('{{Erreur de connexion}}', '{{Erreur lors de la connexion}} : ' + _error.message)
      }
      jeedom.connect++
      jeedom.changes_timeout = setTimeout(jeedom.changes, 1)
    }
  }
  try {
    jeedom.private.checkParamsRequired(paramsRequired)
  } catch (e) {
    (paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics)
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/event.ajax.php'
  paramsAJAX.data = {
    action: 'changes',
    datetime: jeedom.datetime,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.init = function() {
  jeedom.datetime = jeeFrontEnd.serverDatetime
  jeedom.display.version = document.body.dataset.uimode

  var cssComputedStyle = getComputedStyle(document.documentElement)
  Highcharts.setOptions({
    accessibility: {
      enabled: false
    },
    jeedom: {
      opacityHigh: 0.85,
      opacityLow: 0.1
    },
    lang: {
      months: ['{{Janvier}}', '{{Février}}', '{{Mars}}', '{{Avril}}', '{{Mai}}', '{{Juin}}', '{{Juillet}}', '{{Août}}', '{{Septembre}}', '{{Octobre}}', '{{Novembre}}', '{{Décembre}}'],
      shortMonths: ['{{Janvier}}', '{{Février}}', '{{Mars}}', '{{Avril}}', '{{Mai}}', '{{Juin}}', '{{Juillet}}', '{{Août}}', '{{Septembre}}', '{{Octobre}}', '{{Novembre}}', '{{Décembre}}'],
      weekdays: ['{{Dimanche}}', '{{Lundi}}', '{{Mardi}}', '{{Mercredi}}', '{{Jeudi}}', '{{Vendredi}}', '{{Samedi}}'],
      downloadCSV: '{{Téléchargement CSV}}',
      downloadJPEG: '{{Téléchargement JPEG}}',
      downloadPDF: '{{Téléchargement PDF}}',
      downloadPNG: '{{Téléchargement PNG}}',
      downloadSVG: '{{Téléchargement SVG}}',
      downloadXLS: '{{Téléchargement XLS}}',
      printChart: '{{Imprimer}}',
      viewFullscreen: '{{Plein écran}}',
    },
    colors: [
      cssComputedStyle.getPropertyValue('--al-info-color'),
      cssComputedStyle.getPropertyValue('--al-warning-color'),
      cssComputedStyle.getPropertyValue('--al-success-color'),
      cssComputedStyle.getPropertyValue('--lb-yellow-color'),
      cssComputedStyle.getPropertyValue('--al-primary-color'),
      cssComputedStyle.getPropertyValue('--al-danger-color'),
      cssComputedStyle.getPropertyValue('--scBlocCOM'),
      cssComputedStyle.getPropertyValue('--scBlocFOR'),
      cssComputedStyle.getPropertyValue('--scBlocACTION'),
      cssComputedStyle.getPropertyValue('--scBlocAT')
    ]
  })

  document.body.addEventListener('cmd::update', function(_event) {
    jeedom.cmd.refreshValue(_event.detail)
  })

  document.body.addEventListener('cmd::update', function(_event) {
    jeedom.history.graphUpdate(_event.detail)
  })

  document.body.addEventListener('eqLogic::update', function(_event) {
    jeedom.eqLogic.refreshValue(_event.detail)
  })

  document.body.addEventListener('jeeObject::summary::update', function(_event) {
    jeedom.object.summaryUpdate(_event.detail)
  })

  document.body.addEventListener('scenario::update', function(_event) {
    jeedom.scenario.refreshValue(_event.detail)
  })

  document.body.addEventListener('ui::update', function(_event) {
    if (isset(_event.detail.page) && _event.detail.page != '') {
      if (jeedom.display.version == 'mobile') {
        if (!PAGE_HISTORY || PAGE_HISTORY.length == 0 || !PAGE_HISTORY[PAGE_HISTORY.length - 1].page || PAGE_HISTORY[PAGE_HISTORY.length - 1].page != _event.detail.page) {
          return
        }
      } else if (getUrlVars('p') != _event.detail.page) {
        return
      }
    }
    if (!isset(_event.detail.container) || _event.detail.container == '') {
      _event.detail.container = 'body'
    }
    document.querySelectorAll(_event.detail.container).setJeeValues(_event.detail.data, _event.detail.type)
  })

  document.body.addEventListener('jeedom::gotoplan', function(_event) {
    if (getUrlVars('p') == 'plan' && 'function' == typeof (jeeFrontEnd.plan.displayPlan)) {
      if (_event.detail != jeephp2js.planHeader_id) {
        jeephp2js.planHeader_id = _event.detail
        jeeFrontEnd.plan.displayPlan()
      }
    }
  })

  document.body.addEventListener('jeedom::alert', function(_event) {
    if (!isset(_event.detail.message) || _event.detail.message.trim() == '') {
      if (isset(_event.detail.page) && _event.detail.page != '') {
        if (getUrlVars('p') == _event.detail.page || (jeedom.display.version == 'mobile' && isset(CURRENT_PAGE) && CURRENT_PAGE == _event.detail.page)) {
          jeedomUtils.hideAlert()
        }
      } else {
        jeedomUtils.hideAlert()
      }
    } else {
      if (isset(_event.detail.page) && _event.detail.page != '') {
        let options = {
          message: _event.detail.message,
          level: _event.detail.level
        }
        if (_event.detail.ttl) {
          options.ttl = _event.detail.ttl
        }
        if (getUrlVars('p') == _event.detail.page || (jeedom.display.version == 'mobile' && isset(CURRENT_PAGE) && CURRENT_PAGE == _event.detail.page)) {
          jeedomUtils.showAlert(options)
        }
      } else {
        jeedomUtils.showAlert(_event.detail)
      }
    }
  })

  document.body.addEventListener('jeedom::alertPopup', function(_event) {
    alert(_event.detail)
  })

  document.body.addEventListener('jeedom::coloredIcons', function(_event) {
    document.body.setAttribute('data-coloredIcons', _event.detail)
  })

  document.body.addEventListener('message::refreshMessageNumber', function() {
    jeedom.refreshMessageNumber()
  })

  document.body.addEventListener('update::refreshUpdateNumber', function() {
    jeedom.refreshUpdateNumber()
  })

  document.body.addEventListener('notify', function(_event) {
    jeedom.notify(_event.detail.title, _event.detail.message, _event.detail.theme)
  })

  document.body.addEventListener('checkThemechange', function(_event) {
    document.getElementById('jeedom_theme_currentcss').setAttribute('data-nochange', 0)

    if (isset(_event.detail.theme_start_day_hour)) {
      jeedom.theme.theme_start_day_hour = _event.detail.theme_start_day_hour
    }
    if (isset(_event.detail.theme_end_day_hour)) {
      jeedom.theme.theme_end_day_hour = _event.detail.theme_end_day_hour
    }
    if (isset(_event.detail.theme_changeAccordingTime)) {
      jeedom.theme.theme_changeAccordingTime = _event.detail.theme_changeAccordingTime
    }
    jeedomUtils.checkThemechange()
  })

  document.body.addEventListener('changeTheme', function(_event) {
    jeedomUtils.changeTheme(_event.detail)
  })
  if (typeof user_id !== 'undefined') {
    jeedom.changes()
  }
}

jeedom.getPageType = function(_modal) {
  if (isset(_modal) && _modal == true) {
    let modalType = undefined
    let modals = Array.prototype.slice.call(document.querySelectorAll('.jeeDialogMain')).filter(item => item.isVisible())
    if (modals.length == 1) {
      modalType = modals[0].querySelector('div[data-modalType]')?.getAttribute('data-modalType')
    } else if (modals.length > 1) {
      let prevMod = 0
      modals.forEach(_mod => {
        if (_mod.style.zIndex > prevMod) {
          prevMod = _mod.style.zIndex
          modalType = _mod.querySelector('div[data-modalType]')?.getAttribute('data-modalType')
        }
      })
    }
    if (modalType != undefined) return modalType
  }
  return document.body.getAttribute('data-page')
}

jeedom.MESSAGE_NUMBER
jeedom.refreshMessageNumber = function() {
  jeedom.message.number({
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(_number) {
      jeedom.MESSAGE_NUMBER = _number
      if (_number == 0 || _number == '0') {
        document.getElementById('span_nbMessage').unseen()
      } else {
        document.getElementById('span_nbMessage').seen().textContent = _number
      }
    }
  })
}

jeedom.UPDATE_NUMBER
jeedom.refreshUpdateNumber = function() {
  if (jeedom.update == undefined) return //mobile
  if (document.getElementById('span_nbUpdate') === null) return // for a not admin profil
  jeedom.update.number({
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(_number) {
      jeedom.UPDATE_NUMBER = _number
      if (_number == 0 || _number == '0') {
        document.getElementById('span_nbUpdate').unseen()
      } else {
        document.getElementById('span_nbUpdate').seen().textContent = _number
      }
    }
  })
}

jeedom.notify = function(_title, _text, _class_name) {
  if (_title == '' && _text == '') {
    return true
  }
  if (typeof jeeDialog !== 'undefined' && typeof jeeDialog.toast !== 'undefined') {
    let options = {
      title: _title,
      message: _text,
      onclick: function() {
        jeeDialog.clearToasts()
        jeeDialog.dialog({
          id: 'jee_modal',
          title: "{{Centre de Messages}}",
          contentUrl: 'index.php?v=d&modal=message.display'
        })
      }
    }
    if (isset(_class_name) != '') {
      options.level = _class_name
    }
    jeeDialog.toast(options)
  } else {
    //no jeeDialog in mobile
    jeedomUtils.notify(_title, _text)
  }
}

jeedom.getStringUsedBy = function(_params) {
  var paramsRequired = ['search']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getStringUsedBy',
    search: _params.search
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.getIdUsedBy = function(_params) {
  var paramsRequired = ['search']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getIdUsedBy',
    search: _params.search
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.getConfiguration = function(_params) {
  var paramsRequired = ['key']
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.cache.getConfiguration = data.result
      var keys = _params.key.split(':')
      data.result = jeedom.cache.getConfiguration
      for (var i in keys) {
        if (data.result[keys[i]]) {
          data.result = data.result[keys[i]]
        }
      }
      return data
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  if (jeedom.cache.getConfiguration != null) {
    var keys = _params.key.split(':')
    var result = jeedom.cache.getConfiguration
    for (var i in keys) {
      if (result[keys[i]]) {
        result = result[keys[i]]
      }
    }
    _params.success(result)
    return
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getConfiguration',
    key: ''
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.getInfoApplication = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getInfoApplication',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.haltSystem = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'haltSystem',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.ssh = function(_params) {
  if (isPlainObject(_params)) {
    command = _params.command
  } else {
    command = _params
    _params = {}
  }
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'ssh',
    command: command
  }
  domUtils.ajax(paramsAJAX)
  return 'Execute command : ' + command
}

jeedom.db = function(_params) {
  if (isPlainObject(_params)) {
    command = _params.command
  } else {
    command = _params
    _params = {}
  }
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'db',
    command: command
  }
  domUtils.ajax(paramsAJAX)
  return 'Execute command : ' + command
}

jeedom.dbcorrectTable = function(_params) {
  var paramsRequired = ['table']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'dbcorrectTable',
    table: _params.table
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.rebootSystem = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'rebootSystem',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.systemCorrectPackage = function(_params) {
  var paramsRequired = ['package']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'systemCorrectPackage',
    package: _params.package
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.health = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'health',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.forceSyncHour = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'forceSyncHour',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.getCronSelectModal = function(_options, _callback) {
  document.getElementById('mod_insertCronValue')?.remove()
  document.body.insertAdjacentHTML('beforeend', '<div id="mod_insertCronValue"></div>')
  jeeDialog.dialog({
    id: 'mod_insertCronValue',
    title: '{{Assistant cron}}',
    height: 310,
    width: 800,
    top: '20vh',
    contentUrl: 'index.php?v=d&modal=cron.human.insert',
    buttons: {
      confirm: {
        label: '{{Valider}}',
        className: 'success',
        callback: {
          click: function(event) {
            var args = {}
            args.cron = {}
            args.value = mod_insertCron.getValue()
            if (args.value != undefined && args.value.trim() != '' && 'function' === typeof (_callback)) {
              _callback(args)
            }
            document.getElementById('mod_insertCronValue')._jeeDialog.destroy()
          }
        }
      },
      cancel: {
        label: '{{Annuler}}',
        className: 'warning',
        callback: {
          click: function(event) {
            document.getElementById('mod_insertCronValue')._jeeDialog.destroy()
          }
        }
      }
    }
  })
}

jeedom.getSelectActionModal = function(_options, _callback) {
  if (!isset(_options)) {
    _options = {}
  }

  document.getElementById('mod_insertActionValue')?.remove()
  document.body.insertAdjacentHTML('beforeend', '<div id="mod_insertActionValue"></div>')
  jeeDialog.dialog({
    id: 'mod_insertActionValue',
    title: '{{Sélectionner la commande}}',
    height: 310,
    width: 800,
    top: '20vh',
    contentUrl: 'index.php?v=d&modal=action.insert',
    callback: function() { mod_insertAction.setOptions(_options) },
    buttons: {
      confirm: {
        label: '{{Valider}}',
        className: 'success',
        callback: {
          click: function(event) {
            var args = {}
            args.human = mod_insertAction.getValue()
            if (args.human.trim() != '' && 'function' === typeof (_callback)) {
              _callback(args)
            }
            document.getElementById('mod_insertActionValue')._jeeDialog.destroy()
          }
        }
      },
      cancel: {
        label: '{{Annuler}}',
        className: 'warning',
        callback: {
          click: function(event) {
            document.getElementById('mod_insertActionValue')._jeeDialog.destroy()
          }
        }
      }
    }
  })
}

jeedom.getGraphData = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getGraphData',
    filter_type: params.filter_type || null,
    filter_id: params.filter_id || null,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.getDocumentationUrl = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getDocumentationUrl',
    plugin: params.plugin || null,
    page: params.page || null,
    theme: params.theme || null,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.addWarnme = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'addWarnme',
    cmd_id: params.cmd_id,
    test: params.test,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.getFileFolder = function(_params) {
  var paramsRequired = ['type', 'path']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getFileFolder',
    type: _params.type,
    path: _params.path,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.getFileContent = function(_params) {
  var paramsRequired = ['path']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'getFileContent',
    path: _params.path,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.setFileContent = function(_params) {
  var paramsRequired = ['path', 'content']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'setFileContent',
    path: _params.path,
    content: _params.content,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.deleteFile = function(_params) {
  var paramsRequired = ['path']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'deleteFile',
    path: _params.path,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.createFolder = function(_params) {
  var paramsRequired = ['path', 'name']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'createFolder',
    path: _params.path,
    name: _params.name,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.renameFolder = function(_params) {
  var paramsRequired = ['src', 'dst']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'renameFolder',
    src: _params.src,
    dst: _params.dst
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.deleteFolder = function(_params) {
  var paramsRequired = ['path']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'deleteFolder',
    path: _params.path
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.createFile = function(_params) {
  var paramsRequired = ['path', 'name']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'createFile',
    path: _params.path,
    name: _params.name,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.emptyRemoveHistory = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'emptyRemoveHistory',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.version = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'version'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.removeImageIcon = function(_params) {
  var paramsRequired = ['filepath']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'removeImageIcon',
    filepath: _params.filepath
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cleanFileSystemRight = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'cleanFileSystemRight'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.consistency = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'consistency'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cleanDatabase = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'cleanDatabase'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.massEditSave = function(_params) {
  var paramsRequired = ['type', 'objects']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'massEditSave',
    type: _params.type,
    objects: JSON.stringify(_params.objects)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.massReplace = function(_params) {
  var paramsRequired = ['options', 'eqlogics', 'cmds']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'massReplace',
    options: _params.options,
    eqlogics: _params.eqlogics,
    cmds: _params.cmds
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.systemGetUpgradablePackage = function(_params) {
  var paramsRequired = ['type']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'systemGetUpgradablePackage',
    type: _params.type,
    forceRefresh: _params.forceRefresh || false
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.systemUpgradablePackage = function(_params) {
  var paramsRequired = ['type']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php'
  paramsAJAX.data = {
    action: 'systemUpgradablePackage',
    type: _params.type,
  }
  domUtils.ajax(paramsAJAX)
}
