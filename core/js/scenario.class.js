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

jeedom.scenario = function() { }
jeedom.scenario.cache = Array()

if (!isset(jeedom.scenario.update)) {
  jeedom.scenario.update = Array()
}
if (!isset(jeedom.scenario.cache.byGroupObjectName)) {
  jeedom.scenario.cache.byGroupObjectName = Array()
}

jeedom.scenario.all = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.scenario.cache.all = data.result
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
  if (isset(jeedom.scenario.cache.all) && jeedom.scenario.cache.all != null && init(_params.nocache, false) == false) {
    params.success(jeedom.scenario.cache.all)
    return
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'all',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.allOrderedByGroupObjectName = function(_params) {
  var asGroup = _params.asGroup ? _params.asGroup : 0
  var asTag = _params.asTag ? _params.asTag : 0
  var paramsRequired = []
  var paramsSpecifics = {
    pre_success: function(data) {
      if (!isset(jeedom.scenario.cache.byGroupObjectName)) jeedom.scenario.cache.byGroupObjectName = Array()
      if (asTag) return data
      jeedom.scenario.cache.byGroupObjectName[asGroup] = data.result
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
  /*
  if (!asTag && isset(jeedom.scenario.cache.byGroupObjectName) && isset(jeedom.scenario.cache.byGroupObjectName[asGroup]) && jeedom.scenario.cache.byGroupObjectName[asGroup] != null  && init(_params.nocache, false) == false) {
    params.success(jeedom.scenario.cache.byGroupObjectName[asGroup])
    return
  }
  */
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'allOrderedByGroupObjectName',
    asGroup: asGroup,
    asTag: asTag
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.saveAll = function(_params) {
  var paramsRequired = ['scenarios']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'saveAll',
    scenarios: JSON.stringify(_params.scenarios),
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.toHtml = function(_params) {
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'toHtml',
    id: (Array.isArray(_params.id)) ? JSON.stringify(_params.id) : _params.id,
    version: _params.version
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.changeState = function(_params) {
  var paramsRequired = ['id', 'state']
  var paramsSpecifics = {
    global: false
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'changeState',
    id: _params.id,
    state: _params.state
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.getTemplate = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {
    global: false
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'getTemplate',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.convertToTemplate = function(_params) {
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'convertToTemplate',
    id: _params.id,
    template: _params.template || '',
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.removeTemplate = function(_params) {
  var paramsRequired = ['template']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'removeTemplate',
    template: _params.template,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.loadTemplateDiff = function(_params) {
  var paramsRequired = ['template', 'id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'loadTemplateDiff',
    template: _params.template,
    id: _params.id,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.applyTemplate = function(_params) {
  var paramsRequired = ['template', 'id', 'convert']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'applyTemplate',
    template: _params.template,
    id: _params.id,
    convert: _params.convert,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.refreshValue = function(_params) {
  if (!isset(_params.global) || !_params.global) {
    if (isset(jeedom.scenario.update) && isset(jeedom.scenario.update[_params.scenario_id])) {
      jeedom.scenario.update[_params.scenario_id](_params)
      return
    }
  }
  var sc = document.querySelector('.scenario-widget[data-scenario_id="' + _params.scenario_id + '"]')
  if (sc == null) {
    return
  }
  var version = sc.getAttribute('data-version')
  var paramsRequired = ['id']
  var paramsSpecifics = {
    global: false,
    success: function(result) {
      try {
        var tile = domUtils.parseHTML(result)
        sc.empty().appendChild(tile)
        sc.querySelector('.scenario-widget').replaceWith(...sc.querySelector('scenario-widget').childNodes)
      } catch (error) {
        console.error(error)
      }
      var tile = domUtils.parseHTML(result)
      sc.empty().appendChild(result.childNodes)
      if (jeedomUtils.userDevice.type == undefined) {
        sc.triggerEvent('create')
        jeedomUtils.setTileSize('.scenario')
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'toHtml',
    id: _params.scenario_id,
    version: _params.version || version
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.copy = function(_params) {
  var paramsRequired = ['id', 'name']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'copy',
    id: _params.id,
    name: _params.name
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.byId = function(_params) {
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'byId',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.get = function(_params) {
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'get',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.save = function(_params) {
  var paramsRequired = ['scenario']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'save',
    scenario: JSON.stringify(_params.scenario)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.remove = function(_params) {
  var paramsRequired = ['id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'remove',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.clearAllLogs = function(_params) {
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'clearAllLogs'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.emptyLog = function(_params) {
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'emptyLog',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.getSelectModal = function(_options, callback) {
  if (!isset(_options)) {
    _options = {}
  }
  document.getElementById('mod_insertScenarioValue')?.remove()
  document.body.insertAdjacentHTML('beforeend', '<div id="mod_insertScenarioValue"></div>')
  jeeDialog.dialog({
    id: 'mod_insertScenarioValue',
    title: '{{Sélectionner le scénario}}',
    height: 250,
    width: 800,
    top: '20vh',
    contentUrl: 'index.php?v=d&modal=scenario.human.insert',
    callback: function() { mod_insertScenario.setOptions(_options) },
    buttons: {
      confirm: {
        label: '{{Valider}}',
        className: 'success',
        callback: {
          click: function(event) {
            var args = {}
            args.human = mod_insertScenario.getValue()
            args.id = mod_insertScenario.getId()
            if (args.human.trim() != '') {
              callback(args)
            }
            document.getElementById('mod_insertScenarioValue')._jeeDialog.destroy()
          }
        }
      },
      cancel: {
        label: '{{Annuler}}',
        className: 'warning',
        callback: {
          click: function(event) {
            document.getElementById('mod_insertScenarioValue')._jeeDialog.destroy()
          }
        }
      }
    }
  })
}

jeedom.scenario.testExpression = function(_params) {
  var paramsRequired = ['expression']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'testExpression',
    expression: _params.expression
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.scenario.setOrder = function(_params) {
  var paramsRequired = ['scenarios']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/scenario.ajax.php'
  paramsAJAX.data = {
    action: 'setOrder',
    scenarios: JSON.stringify(_params.scenarios)
  }
  domUtils.ajax(paramsAJAX)
}

/* Conditions Autocomplete */
jeedom.scenario.autoCompleteCondition = [
  '#rand(MIN,MAX)',
  '##minute#',
  '##heure#',
  '##jour#',
  '##semaine#',
  '##mois#',
  '##annee#',
  '##sjour#',
  '##date#',
  '##time#',
  '##timestamp#',
  '##IP#',
  '##hostname#',
  '#tag(montag,defaut)',
  '#variable(mavariable,defaut)',
  '#genericType(generic,object)',
  '#delete_variable(mavariable)',
  '#tendance(commande,periode)',
  '#average(commande,periode)',
  '#max(commande,periode)',
  '#min(commande,periode)',
  '#round(valeur)',
  '#trigger(commande)',
  '#triggerValue(commande)',
  '#randomColor(debut,fin)',
  '#lastScenarioExecution(scenario)',
  '#stateDuration(commande)',
  '#lastChangeStateDuration(commande,value)',
  '#age(commande)',
  '#median(commande1,commande2)',
  '#avg(commande1,commande2)',
  '#time(value)',
  '#time_op(time,value)',
  '#collectDate(cmd)',
  '#valueDate(cmd)',
  '#eqEnable(equipement)',
  '#name(type,commande)',
  '#value(commande)',
  '#lastCommunication(equipement)',
  '#color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)'
]
/* Actions Autocomplete */
jeedom.scenario.autoCompleteAction = [
  'setColoredIcon',
  'tag',
  'report',
  'exportHistory',
  'sleep',
  'variable',
  'delete_variable',
  'scenario',
  'wait',
  'gotodesign',
  'message',
  'equipement',
  'ask',
  'jeedom_poweroff',
  'alert',
  'popup',
  'event',
  'changeTheme',
  'remove_inat',
  'genericType'
]
/* Actions Autocomplete only for scenarios*/
jeedom.scenario.autoCompleteActionScOnly = [
  'stop',
  'log',
  'scenario_return',
  'icon'
]

jeedom.scenario.setAutoComplete = function(_params) {
  if (jeeFrontEnd.scenario_autocomplete == 1) {
    return
  }
  if (!isset(_params)) {
    _params = {}
    _params.parent = document.getElementById('div_scenarioElement')
    _params.type = 'expression'
  }

  if (isElement_jQuery(_params.parent)) _params.parent = _params.parent[0]


  _params.parent.querySelectorAll('.expression').forEach(_expr => {
    //Empty Action block ?
    var attrType = _expr.querySelector('.expressionAttr[data-l1key="type"]')
    if (attrType) {
      if (attrType.value == 'condition') {
        _expr.querySelector('.expressionAttr[data-l1key="' + _params.type + '"]').jeeComplete({
          id: 'scenarioConditionAutocomplete',
          minLength: 1,
          source: function(request, response) {
            //return last term after last space:
            var values = request.term.split(' ')
            var term = values[values.length - 1]
            if (term == '') return false //only space entered
            response(
              jeedom.scenario.autoCompleteCondition.filter(item => item.includes(term))
            )
          },
          response: function(event, data) {
            //remove leading # from all values:
            data.content.forEach(_content => {
              _content.text = _content.text.substr(1)
              _content.value = _content.value.substr(1)
            })
          },
          focus: function(event) {
            event.preventDefault()
            return false
          },
          select: function(event, data) {
            if (data.value.substr(-1) == '#') {
              data.value = data.value.slice(0, -1) + data.value
            } else {
              var values = data.value.split(' ')
              var term = values[values.length - 1]
              data.value = data.value.slice(0, -term.length) + data.value
            }
          }
        })
      }

      if (attrType.value == 'action') {
        if (document.body.getAttribute('data-page') == 'scenario') {
          jeedom.scenario.autoCompleteActionContext = jeedom.scenario.autoCompleteAction.concat(jeedom.scenario.autoCompleteActionScOnly)
        } else {
          jeedom.scenario.autoCompleteActionContext = jeedom.scenario.autoCompleteAction
        }

        _expr.querySelector('.expressionAttr[data-l1key="' + _params.type + '"]').jeeComplete({
          source: jeedom.scenario.autoCompleteActionContext,
          id: 'scenarioActionAutocomplete',
          forceSingle: true
        })
      }
    }

  })
}
