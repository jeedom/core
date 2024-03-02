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

jeedom.cmd = function() { }
jeedom.cmd.disableExecute = false
jeedom.cmd.cache = Array()
if (!isset(jeedom.cmd.cache.byId)) {
  jeedom.cmd.cache.byId = Array()
}
if (!isset(jeedom.cmd.cache.byHumanName)) {
  jeedom.cmd.cache.byHumanName = Array()
}
if (!isset(jeedom.cmd.update)) {
  jeedom.cmd.update = Array()
}

jeedom.cmd.notifyEq = function(_eqlogic, _hide) {
  if (!_eqlogic) {
    return
  }
  if (isElement_jQuery(_eqlogic)) _eqlogic = _eqlogic[0]
  var refresh = _eqlogic.querySelector('.cmd.refresh')
  if (refresh != null) {
    refresh.addClass('spinning')
  } else {
    _eqlogic.querySelector('.widget-name')?.insertAdjacentHTML('afterbegin', '<span class="cmd refresh pull-right remove"><i class="fas fa-sync"></i></span>')
  }
  var refresh = _eqlogic.querySelector('.cmd.refresh')
  if (_hide && refresh != null) {
    setTimeout(function() {
      if (refresh.hasClass('remove')) {
        refresh.remove()
      } else {
        refresh.removeClass('spinning')
      }
    }, 1000)
  }
}

jeedom.cmd.execute = function(_params) {
  if (jeedom.cmd.disableExecute) {
    return
  }
  var notify = _params.notify ?? true
  if (notify) {
    var eqLogic = document.querySelector('.cmd[data-cmd_id="' + _params.id + '"]')?.closest('div.eqLogic-widget')
    if (eqLogic) jeedom.cmd.notifyEq(eqLogic, false)
  }
  if (_params.value != 'undefined' && (is_array(_params.value) || is_object(_params.value))) {
    _params.value = JSON.stringify(_params.value)
  }
  var paramsRequired = ['id']
  var paramsSpecifics = {
    global: false,
    pre_success: function(data) {
      if (data.state != 'ok') {
        if (data.code == -32005) {
          if (jeedom.display.version == 'mobile') {
            var result = prompt("{{Veuillez indiquer le code ?}}", "")
            if (result != null) {
              _params.codeAccess = result
              jeedom.cmd.execute(_params)
            } else {
              jeedom.cmd.refreshValue({
                id: _params.id
              })
              if ('function' != typeof (_params.error)) {
                jeedomUtils.showAlert({
                  message: data.result,
                  level: 'danger'
                })
              }
              if (notify) {
                jeedom.cmd.notifyEq(eqLogic, true)
              }
              return data
            }
          } else {
            jeeDialog.prompt("{{Veuillez indiquer le code ?}}", function(result) {
              if (result != null) {
                _params.codeAccess = result
                jeedom.cmd.execute(_params)
              } else {
                jeedom.cmd.refreshValue({
                  id: _params.id
                })
                if ('function' != typeof (_params.error)) {
                  jeedomUtils.showAlert({
                    message: data.result,
                    level: 'danger'
                  })
                }
                if (notify) {
                  jeedom.cmd.notifyEq(eqLogic, true)
                }
                return data
              }

            })
          }
        } else if (data.code == -32006) {
          if (jeedom.display.version == 'mobile') {
            var result = confirm("{{Êtes-vous sûr de vouloir faire cette action ?}}")
            if (result) {
              _params.confirmAction = 1
              jeedom.cmd.execute(_params)
            } else {
              jeedom.cmd.refreshValue({
                id: _params.id
              })
              if ('function' != typeof (_params.error)) {
                jeedomUtils.showAlert({
                  message: data.result,
                  level: 'danger'
                })
              }
              if (notify) {
                jeedom.cmd.notifyEq(eqLogic, true)
              }
              return data
            }
          } else {
            jeeDialog.confirm("{{Êtes-vous sûr de vouloir faire cette action ?}}", function(result) {
              if (result) {
                _params.confirmAction = 1
                jeedom.cmd.execute(_params)
              } else {
                jeedom.cmd.refreshValue({
                  id: _params.id
                })
                if ('function' != typeof (_params.error)) {
                  jeedomUtils.showAlert({
                    message: data.result,
                    level: 'danger'
                  })
                }
                if (notify) {
                  jeedom.cmd.notifyEq(eqLogic, true)
                }
                return data
              }

            })
          }
        } else {
          if ('function' != typeof (_params.error)) {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
          }
          if (notify) {
            jeedom.cmd.notifyEq(eqLogic, true)
          }
          return data
        }
      }
      if (notify) {
        jeedom.cmd.notifyEq(eqLogic, true)
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
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  var cache = 1
  if (_params.cache !== undefined) {
    cache = _params.cache
  }
  paramsAJAX.data = {
    action: 'execCmd',
    id: _params.id,
    codeAccess: _params.codeAccess || '',
    confirmAction: _params.confirmAction || '',
    cache: cache,
    value: _params.value || '',
  }
  if (typeof user_login != "undefined") {
    paramsAJAX.data.user_login = user_login
  }
  if (typeof user_id != "undefined") {
    paramsAJAX.data.user_id = user_id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.test = function(_params) {
  var paramsRequired = ['id']
  var paramsSpecifics = {
    global: false,
    success: function(result) {
      switch (result.type) {
        case 'info':
          jeedom.cmd.execute({
            id: _params.id,
            cache: 0,
            notify: false,
            success: function(result) {
              jeedomUtils.showAlert({
                message: '{{Résultat de la commande :}}' + ' ' + result,
                level: 'success'
              })
            }
          })
          break
        case 'action':
          switch (result.subType) {
            case 'other':
              jeedom.cmd.execute({
                id: _params.id,
                cache: 0,
                error: function(error) {
                  jeedomUtils.showAlert({
                    message: error.message,
                    level: 'danger'
                  })
                },
                success: function() {
                  jeedomUtils.showAlert({
                    message: '{{Action exécutée avec succès}}',
                    level: 'success'
                  })
                }
              })
              break
            case 'slider':
              let min = result.configuration.minValue || 0
              let max = result.configuration.maxValue || 100
              jeeDialog.prompt({
                title: '{{Entrer une valeur entre}}' + ' ' + min + ' ' + '{{et}}' + ' ' + max ,
                value: parseInt(min) + (parseInt(max) / 2),
                callback: function(result) {
                  if (result === null) {
                    return
                  }
                  jeedom.cmd.execute({
                    id: _params.id,
                    value: {
                      slider: result
                    },
                    cache: 0,
                    error: function(error) {
                      jeedomUtils.showAlert({
                        message: error.message,
                        level: 'danger'
                      })
                    },
                    success: function() {
                      jeedomUtils.showAlert({
                        message: '{{Action exécutée avec succès}}',
                        level: 'success'
                      })
                    }
                  })
                }
              })
              break
            case 'color':
              jeeDialog.prompt({
                title: "{{Quelle couleur (#rrggbb) ?}}",
                placeholder: '#rrggbb',
                value: '#ff000',
                callback: function(result) {
                  if (result === null) {
                    return
                  }
                  jeedom.cmd.execute({
                    id: _params.id,
                    value: {
                      color: result
                    },
                    cache: 0,
                    error: function(error) {
                      jeedomUtils.showAlert({
                        message: error.message,
                        level: 'danger'
                      })
                    },
                    success: function() {
                      jeedomUtils.showAlert({
                        message: '{{Action exécutée avec succès}}',
                        level: 'success'
                      })
                    }
                  })
                }
              })
              break
            case 'select':
              let values = result.configuration.listValue.split(';')
              let inputOptions = []
              for (let i in values) {
                inputOptions.push({ text: values[i].split('|')[1], value: values[i].split('|')[0] })
              }
              jeeDialog.prompt({
                title: "{{Valeur ?}}",
                inputType: 'select',
                inputOptions: inputOptions,
                callback: function(result) {
                  if (result === null) {
                    return
                  }
                  jeedom.cmd.execute({
                    id: _params.id,
                    value: {
                      select: result
                    },
                    cache: 0,
                    error: function(error) {
                      jeedomUtils.showAlert({
                        message: error.message,
                        level: 'danger'
                      })
                    },
                    success: function() {
                      jeedomUtils.showAlert({
                        message: '{{Action exécutée avec succès}}',
                        level: 'success'
                      })
                    }
                  })
                }
              })
              break
            case 'message':
              let productName = JEEDOM_PRODUCT_NAME
              let content = '<input class="promptAttr" data-l1key="title" autocomplete="off" type="text" placeholder="{{Titre pour la commande}} ' + result.name + '">'
              content += '<textarea class="promptAttr" data-l1key="message" placeholder="{{Message pour la commande}} ' + result.name + '"></textarea>'
              jeeDialog.prompt({
                title: "{{Message}}",
                message: content,
                inputType: false,
                callback: function(result) {
                  if (result) {
                    jeedom.cmd.execute({
                      id: _params.id,
                      value: {
                        title: result.title,
                        message: result.message
                      },
                      cache: 0,
                      error: function(error) {
                        jeedomUtils.showAlert({
                          message: error.message,
                          level: 'danger'
                        })
                      },
                      success: function() {
                        jeedomUtils.showAlert({
                          message: '{{Action exécutée avec succès}}',
                          level: 'success'
                        })
                      }
                    })
                  }
                }
              })
              break
          }
          break
      }
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'getCmd',
    id: _params.id,
  }
  domUtils.ajax(paramsAJAX)
}

//deprecated
jeedom.cmd.refreshByEqLogic = function(_params) {
  var cmds = document.querySelectorAll('.cmd[data-eqLogic_id="' + _params.eqLogic_id + '"]')
  if (cmds.length == 0) {
    return
  }
  cmds.forEach(_cmd => {
    if (_cmd.closest('.eqLogic[data-eqLogic_id="' + _params.eqLogic_id + '"]') == null) {
      return true
    }
    jeedom.cmd.toHtml({
      global: false,
      noDisplayError: true,
      id: _cmd.getAttribute('data-cmd_id'),
      version: _cmd.getAttribute('data-version'),
      success: function(data) {
        var html = domUtils.parseHTML(data.html).childNodes[0]
        var uid = html.getAttribute('data-cmd_uid')
        if (uid != 'undefined') {
          cmd.setAttribute('data-cmd_uid', uid)
        }
        cmd.empty().html(html)
        cmd.setAttribute("class", html.getAttribute("class"))
      }
    })
  })
}

jeedom.cmd.refreshValue = function(_params) {
  var cmd = null
  for (var i in _params) {
    if(_params[i].cmd_id == ''){
      continue;
    }
    //update tile graph info:
    if (document.querySelector('.eqlogicbackgraph[data-cmdid="' + _params[i].cmd_id + '"]') != null) {
      jeedom.eqLogic.drawGraphInfo(_params[i].cmd_id)
    }
    if (document.querySelector('.cmd[data-cmd_id="' + _params[i].cmd_id + '"]')?.hasClass('noRefresh')) {
      continue
    }
    if (!isset(jeedom.cmd.update) || !isset(jeedom.cmd.update[_params[i].cmd_id])) {
      continue
    }
    if (typeof jeedom.cmd.update[_params[i].cmd_id] == 'function') {
      jeedom.cmd.update[_params[i].cmd_id](_params[i])
    }
    for (var j in jeedom.cmd.update[_params[i].cmd_id]) {
      jeedom.cmd.update[_params[i].cmd_id][j](_params[i])
    }
  }
}

jeedom.cmd.addUpdateFunction = function(_cmd_id, _function) {
  if(_cmd_id == ''){
    return;
  }
  if (!isset(jeedom.cmd.update)) {
    jeedom.cmd.update = []
  }
  if (!isset(jeedom.cmd.update[_cmd_id])) {
    jeedom.cmd.update[_cmd_id] = [_function]
    return
  }
  if (typeof jeedom.cmd.update[_cmd_id] == 'function') {
    let prevFunction = jeedom.cmd.update[_cmd_id]
    if (prevFunction.toString() == _function.toString()) {
      return
    }
    jeedom.cmd.update[_cmd_id] = [prevFunction, _function]
  }
  for (var i in jeedom.cmd.update[_cmd_id]) {
    if (jeedom.cmd.update[_cmd_id][i].toString() == _function.toString()) {
      return
    }
  }
  jeedom.cmd.update[_cmd_id].push(_function)
}

jeedom.cmd.resetUpdateFunction = function() {
  jeedom.cmd.update = []
}

jeedom.cmd.getWidgetHelp = function(_params) {
  var paramsRequired = ['id', 'version']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'getWidgetHelp',
    id: _params.id,
    version: _params.version,
    widgetName: _params.widgetName
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.toHtml = function(_params) {
  var paramsRequired = ['id', 'version']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'toHtml',
    id: _params.id,
    version: _params.version
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.replaceCmd = function(_params) {
  var paramsRequired = ['source_id', 'target_id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'replaceCmd',
    source_id: _params.source_id,
    target_id: _params.target_id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.save = function(_params) {
  var paramsRequired = ['cmd']
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.cmd.cache.byId[data.result.id])) {
        delete jeedom.cmd.cache.byId[data.result.id]
      }
      if (isset(jeedom.eqLogic.cache.byId[data.result.eqLogic_id])) {
        delete jeedom.eqLogic.cache.byId[data.result.eqLogic_id]
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
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'save',
    cmd: JSON.stringify(_params.cmd)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.setIsVisibles = function(_params) {
  var paramsRequired = ['cmds', 'isVisible']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'setIsVisibles',
    cmds: JSON.stringify(_params.cmds),
    isVisible: _params.isVisible
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.multiSave = function(_params) {
  var paramsRequired = ['cmds']
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.cmd.cache.byId = []
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
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'multiSave',
    cmd: JSON.stringify(_params.cmds)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.byId = function(_params) {
  var paramsRequired = ['id']
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.cmd.cache.byId[data.result.id] = data.result
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
  if (isset(jeedom.cmd.cache.byId[params.id]) && init(params.noCache, false) == false) {
    params.success(jeedom.cmd.cache.byId[params.id])
    return
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'byId',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.getHumanCmdName = function(_params) {
  var paramsRequired = ['id']
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.cmd.cache.byId[data.result.id] = data.result
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
  if (isset(jeedom.cmd.cache.byId[params.id]) && init(params.noCache, false) == false) {
    params.success(jeedom.cmd.cache.byId[params.id])
    return
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'getHumanCmdName',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.byHumanName = function(_params) {
  var paramsRequired = ['humanName']
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.cmd.cache.byHumanName[data.result.humanName] = data.result
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
  if (isset(jeedom.cmd.cache.byHumanName[params.humanName]) && init(params.noCache, false) == false) {
    params.success(jeedom.cmd.cache.byHumanName[params.humanName])
    return
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'byHumanName',
    humanName: _params.humanName
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.usedBy = function(_params) {
  var paramsRequired = ['id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'usedBy',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.dropInflux = function(_params) {
  var paramsRequired = ['cmd_id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'dropInflux',
    cmd_id: _params.cmd_id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.historyInflux = function(_params) {
  var paramsRequired = ['cmd_id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'historyInflux',
    cmd_id: _params.cmd_id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.dropDatabaseInflux = function(_params) {
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
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'dropDatabaseInflux'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.historyInfluxAll = function(_params) {
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
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'historyInfluxAll'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.changeType = function(_cmd, _subType) {
  if(_cmd.length == 0){
    return; 
  }
  if (isElement_jQuery(_cmd)) {
    _cmd = _cmd[0]
  }
  if ((type = _cmd.querySelector('.cmdAttr[data-l1key="type"]')) === null) {
    return
  }
  if (type.jeeValue() == 'action') {
    _cmd.querySelector('.cmdAction[data-action="test"]')?.seen()
    _cmd.querySelector('.cmdAttr[data-l1key="htmlstate"]')?.unseen()
  } else {
    _cmd.querySelector('.cmdAction[data-action="test"]')?.unseen()
    _cmd.querySelector('.cmdAttr[data-l1key="htmlstate"]')?.seen()
  }

  var selSubType = document.createElement('select')
  selSubType.style.width = '120px'
  selSubType.style.marginTop = '5px'
  selSubType.addClass('cmdAttr', 'form-control', 'input-sm')
  selSubType.setAttribute('data-l1key', 'subType')

  jeedom.getConfiguration({
    key: 'cmd:type:' + type.jeeValue() + ':subtype',
    default: 0,
    async: false,
    error: function(error) {
      _params.error(error)
    },
    success: function(subType) {
      for (var i in subType) {
        newOption = document.createElement('option')
        newOption.text = subType[i].name
        newOption.value = i
        selSubType.appendChild(newOption)
      }
      _cmd.querySelector('.subType')?.empty()
      _cmd.querySelector('.subType')?.appendChild(selSubType)

      if (isset(_subType) && _subType != '') {
        _cmd.querySelector('.cmdAttr[data-l1key="subType"]')?.jeeValue(_subType)
        modifyWithoutSave = false
      } else {
        _cmd.querySelector('.cmdAttr[data-l1key="subType"]').selectedIndex = 0
        modifyWithoutSave = true
      }
      jeedom.cmd.changeSubType(_cmd)
    }
  })
}

jeedom.cmd.changeSubType = function(_cmd) {
  if(_cmd.length == 0){
    return; 
  }
  if (isElement_jQuery(_cmd)) {
    _cmd = _cmd[0]
  }
  if ((type = _cmd.querySelector('.cmdAttr[data-l1key="type"]')) === null || (subtype = _cmd.querySelector('.cmdAttr[data-l1key="subType"]')) === null) {
    return
  }
  jeedom.getConfiguration({
    key: 'cmd:type:' + type.jeeValue() + ':subtype:' + subtype.jeeValue(),
    default: 0,
    async: false,
    error: function(error) {
      _params.error(error)
    },
    success: function(subtype) {
      for (var i in subtype) {
        if (isset(subtype[i].visible)) {
          var el = _cmd.querySelector('.cmdAttr[data-l1key="' + i + '"]')
          if (!el) continue
          if (el.getAttribute('type') == 'checkbox' && el.parentNode.tagName.toLowerCase() == 'span') {
            el = el.parentNode
          }
          if (subtype[i].visible) {
            if (el.hasClass('bootstrapSwitch')) {
              el.parentNode.parentNode.seen()
              el.parentNode.parentNode.removeClass('hide')
            }
            if (el.getAttribute('type') == 'checkbox') {
              el.parentNode.seen()
              el.parentNode.removeClass('hide')
            }
            el.seen()
            el.removeClass('hide')
          } else {
            if (el.hasClass('bootstrapSwitch')) {
              el.parentNode.parentNode.unseen()
              el.parentNode.parentNode.addClass('hide')
            }
            if (el.getAttribute('type') == 'checkbox') {
              el.parentNode.unseen()
              el.parentNode.addClass('hide')
            }
            el.unseen()
            el.addClass('hide')
          }
          if (isset(subtype[i].parentVisible)) {
            if (subtype[i].parentVisible) {
              el.parentNode.seen()
              el.parentNode.removeClass('hide')
            } else {
              el.parentNode.unseen()
              el.parentNode.addClass('hide')
            }
          }
        } else {
          for (var j in subtype[i]) {
            var el = _cmd.querySelector('.cmdAttr[data-l1key="' + i + '"][data-l2key="' + j + '"]')
            if (!el) continue
            if (el.getAttribute('type') == 'checkbox' && el.parentNode.tagName.toLowerCase() == 'span') {
              el = el.parentNode
            }

            if (isset(subtype[i][j].visible)) {
              if (subtype[i][j].visible) {
                if (el.hasClass('bootstrapSwitch')) {
                  el.parentNode.parentNode.parentNode.seen()
                  el.parentNode.parentNode.parentNode.removeClass('hide')
                }
                if (el.getAttribute('type') == 'checkbox') {
                  el.parentNode.seen()
                  el.parentNode.removeClass('hide')
                }
                el.seen()
                el.removeClass('hide')
              } else {
                if (el.hasClass('bootstrapSwitch')) {
                  el.parentNode.parentNode.parentNode.unseen()
                  el.parentNode.parentNode.parentNode.addClass('hide')
                }
                if (el.getAttribute('type') == 'checkbox') {
                  el.parentNode.unseen()
                  el.parentNode.addClass('hide')
                }
                el.unseen()
                el.addClass('hide')
              }
            }
            if (isset(subtype[i][j].parentVisible)) {
              if (subtype[i][j].parentVisible) {
                el.parentNode.seen()
                el.parentNode.removeClass('hide')
              } else {
                el.parentNode.unseen()
                el.parentNode.addClass('hide')
              }
            }
          }
        }
      }

      if (type.jeeValue() == 'action') {
        _cmd.querySelector('.cmdAttr[data-l1key="value"]')?.seen()
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="updateCmdId"]')?.seen()
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="updateCmdToValue"]')?.seen()
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="returnStateValue"]')?.unseen()
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="returnStateTime"]')?.unseen()
      } else {
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="returnStateValue"]')?.seen()
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="returnStateTime"]')?.seen()
        _cmd.querySelector('.cmdAttr[data-l1key="value"]')?.unseen()
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="updateCmdId"]')?.unseen()
        _cmd.querySelector('.cmdAttr[data-l1key="configuration"][data-l2key="updateCmdToValue"]')?.unseen()
      }
      modifyWithoutSave = false
    }
  })
}

jeedom.cmd.availableType = function() {
  var selType = '<select style="width : 120px; margin-bottom : 3px;" class="cmdAttr form-control input-sm" data-l1key="type">'
  selType += '<option value="info">{{Info}}</option>'
  selType += '<option value="action">{{Action}}</option>'
  selType += '</select>'
  return selType
}

jeedom.cmd.getSelectModal = function(_options, _callback) {
  if (!isset(_options)) {
    _options = {}
  }

  document.getElementById('mod_insertCmdValue')?.remove()
  document.body.insertAdjacentHTML('beforeend', '<div id="mod_insertCmdValue" ></div>')
  jeeDialog.dialog({
    id: 'mod_insertCmdValue',
    title: '{{Sélectionner la commande}}',
    height: 250,
    width: 800,
    top: '20vh',
    contentUrl: 'index.php?v=d&modal=cmd.human.insert',
    callback: function() { mod_insertCmd.setOptions(_options) },
    buttons: {
      confirm: {
        label: '{{Valider}}',
        className: 'success',
        callback: {
          click: function(event) {
            var args = {}
            args.cmd = {}
            args.human = mod_insertCmd.getValue()
            args.cmd.id = mod_insertCmd.getCmdId()
            args.cmd.type = mod_insertCmd.getType()
            args.cmd.subType = mod_insertCmd.getSubType()
            if (args.human.trim() != '' && 'function' === typeof (_callback)) {
              _callback(args)
            }
            document.getElementById('mod_insertCmdValue')._jeeDialog.destroy()
          }
        }
      },
      cancel: {
        label: '{{Annuler}}',
        className: 'warning',
        callback: {
          click: function(event) {
            if (isset(_options.returnCancel) && 'function' === typeof (_callback)) {
              _callback({})
            }
            document.getElementById('mod_insertCmdValue')._jeeDialog.destroy()
          }
        }
      }
    }
  })
}

jeedom.cmd.displayActionOption = function(_expression, _options, _callback) {
  if (_expression == '') {
    if (typeof _callback === 'function') {
      _callback('')
      return
    }
    return ''
  }

  if (document.body.getAttribute('data-page') != "scenario") {
    if (jeedom.scenario.autoCompleteActionScOnly.includes(_expression)) {
      if ('function' == typeof (_callback)) {
        _callback('Unsupported')
        return
      }
      return 'Unsupported'
    }
  }

  var html = ''
  domUtils.ajax({
    type: "POST",
    url: "core/ajax/scenario.ajax.php",
    data: {
      action: 'actionToHtml',
      version: 'scenario',
      expression: _expression,
      option: JSON.stringify(_options)
    },
    dataType: 'json',
    async: ('function' == typeof (_callback)),
    global: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error)
    },
    success: function(data) {
      if (data.state != 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        })
        return
      }
      if (data.result.html != '') {
        html += data.result.html
      }
      if ('function' == typeof (_callback)) {
        _callback(html)
        return
      }
    }
  })
  return html
}

jeedom.cmd.displayActionsOption = function(_params) {
  var paramsRequired = ['params']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.async = _params.async || true
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'actionToHtml',
    params: JSON.stringify(_params.params)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.normalizeName = function(_tagname) {
  var cmdName = _tagname.toLowerCase().trim()
  var cmdTests = []
  var cmdType = null
  var cmdList = {
    'on': 'on',
    'off': 'off',
    'monter': 'on',
    'descendre': 'off',
    'ouvrir': 'on',
    'ouvrirStop': 'on',
    'ouvert': 'on',
    'fermer': 'off',
    'activer': 'on',
    'active': 'on',
    'inactive': 'off',
    'desactiver': 'off',
    'désactiver': 'off',
    'lock': 'on',
    'unlock': 'off',
    'verrouiller': 'on',
    'deverrouiller': 'off',
    'déverrouiller': 'off',
    'marche': 'on',
    'arret': 'off',
    'arrêt': 'off',
    'stop': 'off',
    'go': 'on'
  }
  var cmdTestsList = [' ', '-', '_']
  for (var i in cmdTestsList) {
    cmdTests = cmdTests.concat(cmdName.split(cmdTestsList[i]))
  }
  for (var j in cmdTests) {
    if (cmdList[cmdTests[j]]) {
      return cmdList[cmdTests[j]]
    }
  }
  return _tagname
}

jeedom.cmd.setOrder = function(_params) {
  var paramsRequired = ['cmds']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'setOrder',
    cmds: JSON.stringify(_params.cmds)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cmd.getDeadCmd = function(_params) {
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
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'getDeadCmd'
  }
  domUtils.ajax(paramsAJAX)
}

/* time widgets */
jeedom.cmd.formatMomentDuration = function(_duration) {
  var durationString = ''
  var used = 0
  var lang = jeeFrontEnd.language

  if (_duration._data.years > 0) {
    durationString += _duration._data.years + jeedom.config.locales[lang].duration.year
    used++
  }
  if (_duration._data.months > 0) {
    durationString += _duration._data.months + jeedom.config.locales[lang].duration.month
    used++
  }
  if (_duration._data.days > 0) {
    durationString += _duration._data.days + jeedom.config.locales[lang].duration.day
    used++
  }

  if (used == 3) return durationString
  if (_duration._data.hours > 0) {
    durationString += _duration._data.hours + jeedom.config.locales[lang].duration.hour
    used++
  }

  if (used == 3) return durationString
  if (_duration._data.minutes > 0) {
    durationString += _duration._data.minutes + jeedom.config.locales[lang].duration.minute
    used++
  }

  if (used == 3) return durationString
  if (_duration._data.seconds > 0) {
    durationString += _duration._data.seconds + jeedom.config.locales[lang].duration.second
    used++
  }

  return durationString
}

jeedom.cmd.displayDuration = function(_date, _el, _type = 'duration') {
  if (isElement_jQuery(_el)) _el = _el[0] //Deprecated, keep for mobile during transition
  if (_type == 'date') {
    moment.locale(jeeFrontEnd.language.substring(0, 2))
    if (isset(jeedom.config.locales[jeeFrontEnd.language].calendar)) {
      var dateString = moment(_date, 'YYYY-MM-DD HH:mm:ss').calendar(jeedom.config.locales[jeeFrontEnd.language].calendar)
    } else {
      var dateString = moment(_date, 'YYYY-MM-DD HH:mm:ss').calendar(jeedom.config.locales['en_US'].calendar)
    }
    _el.innerHTML = dateString
    return true
  }

  if (_el.getAttribute('data-interval') != null) {
    clearInterval(_el.getAttribute('data-interval'))
  }

  var tsDate = moment(_date).unix() * 1000
  var now = Date.now() + ((new Date).getTimezoneOffset() + jeeFrontEnd.serverTZoffsetMin) * 60000 + jeeFrontEnd.clientServerDiffDatetime

  var interval = 10000
  //_past more than one second ?
  if (now - tsDate > 1000) {
    var duration = moment.duration(moment() - moment(_date))
    var durationSec = duration._milliseconds / 1000
    if (durationSec > 86399) {
      interval = 3600000
    } else if (durationSec > 3599) {
      interval = 60000
    }
    var durationString = jeedom.cmd.formatMomentDuration(duration)
  } else {
    var durationString = "0" + jeedom.config.locales[jeeFrontEnd.language].duration.second
  }
  _el.innerHTML = durationString

  //set refresh interval:
  var myinterval = setInterval(function() {
    var duration = moment.duration(moment() - moment(_date))
    var durationString = jeedom.cmd.formatMomentDuration(duration)
    _el.innerHTML = durationString
  }, interval)

  _el.setAttribute('data-interval', myinterval)
}
