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

jeedom.eqLogic = function() { }
jeedom.eqLogic.cache = Array()
jeedom.eqLogic.backGraphIntervals = {}

jeedom.eqLogic.changeDisplayObjectName = function(_display) {
  if (_display) {
    document.querySelectorAll('div.eqLogic-widget').addClass('displayObjectName')
  } else {
    document.querySelectorAll('div.eqLogic-widget').removeClass('displayObjectName')
  }
}

if (!isset(jeedom.eqLogic.cache.getCmd)) {
  jeedom.eqLogic.cache.getCmd = Array()
}

if (!isset(jeedom.eqLogic.cache.byId)) {
  jeedom.eqLogic.cache.byId = Array()
}

jeedom.eqLogic.save = function(_params) {
  var paramsRequired = ['type', 'eqLogics']
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.eqLogic.cache.byId[data.result.id])) {
        delete jeedom.eqLogic.cache.byId[data.result.id]
      }
      if (isset(jeedom.object.cache.all)) {
        delete jeedom.object.cache.all
      }
      if (isset(jeedom.object.cache.getEqLogic[data.result.object_id])) {
        delete jeedom.object.cache.getEqLogic[data.result.object_id]
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
  paramsAJAX.async = _params.async || true
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'save',
    type: _params.type,
    eqLogic: JSON.stringify(_params.eqLogics),
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.byType = function(_params) {
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'listByType',
    type: _params.type,
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.byObjectId = function(_params) {
  var paramsRequired = ['object_id']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'listByObject',
    object_id: _params.object_id,
    onlyVisible: _params.onlyVisible || 0,
    onlyEnable: _params.onlyEnable || 1,
    getCmd: _params.getCmd || 0
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.simpleSave = function(_params) {
  var paramsRequired = ['eqLogic']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'simpleSave',
    eqLogic: JSON.stringify(_params.eqLogic),
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.getUseBeforeRemove = function(_params) {
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'getUseBeforeRemove',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.usedBy = function(_params) {
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'usedBy',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.remove = function(_params) {
  var paramsRequired = ['id', 'type']
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.eqLogic.cache.byId[_params.id])) {
        delete jeedom.eqLogic.cache.byId[_params.id]
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'remove',
    type: _params.type,
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.copy = function(_params) {
  var paramsRequired = ['id', 'name']
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.eqLogic.cache.byId[_params.id])) {
        delete jeedom.eqLogic.cache.byId[_params.id]
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'copy',
    name: _params.name,
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.print = function(_params) {
  var paramsRequired = ['id', 'type']
  var paramsSpecifics = {
    pre_success: function(data) {
      if (data.result && data.result.cmd) {
        jeedom.eqLogic.cache.getCmd[_params.id] = data.result.cmd
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'get',
    type: _params.type,
    id: _params.id,
    status: _params.status || 0,
    getCmdState: _params.getCmdState || 0
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.toHtml = function(_params) {
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'toHtml',
    id: _params.id,
    version: _params.version
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.getCmd = function(_params) {
  var paramsRequired = ['id']
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.eqLogic.cache.getCmd[_params.id] = data.result
      return data
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  if (isset(jeedom.eqLogic.cache.getCmd[_params.id]) && 'function' == typeof (_params.success) && init(_params.noCache, false) == false) {
    _params.success(jeedom.eqLogic.cache.getCmd[_params.id])
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cmd.ajax.php'
  paramsAJAX.data = {
    action: 'byEqLogic',
    eqLogic_id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.byId = function(_params) {
  var paramsRequired = ['id']
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.eqLogic.cache.byId[data.result.id] = data.result
      return data
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  if (init(_params.noCache, false) == false && isset(jeedom.eqLogic.cache.byId[_params.id]) && 'function' == typeof (_params.success)) {
    _params.success(jeedom.eqLogic.cache.byId[_params.id])
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'byId',
    id: _params.id
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.buildSelectCmd = function(_params) {
  if (!isset(_params.filter)) {
    _params.filter = {}
  }
  jeedom.eqLogic.getCmd({
    id: _params.id,
    async: false,
    success: function(cmds) {
      var result = ''
      for (var i in cmds) {
        if ((init(_params.filter.type, 'all') == 'all' || cmds[i].type == _params.filter.type) &&
          (init(_params.filter.subType, 'all') == 'all' || cmds[i].subType == _params.filter.subType) &&
          (init(_params.filter.isHistorized, 'all') == 'all' || cmds[i].isHistorized == _params.filter.isHistorized)
        ) {
          result += '<option value="' + cmds[i].id + '" data-type="' + cmds[i].type + '"  data-subType="' + cmds[i].subType + '" >' + cmds[i].name + '</option>'
        }
      }
      if ('function' == typeof (_params.success)) {
        _params.success(result)
      }
    }
  })
}

jeedom.eqLogic.getSelectModal = function(_options, callback) {
  document.getElementById('mod_insertEqLogicValue')?.remove()
  document.body.insertAdjacentHTML('beforeend', '<div id="mod_insertEqLogicValue"></div>')
  jeeDialog.dialog({
    id: 'mod_insertEqLogicValue',
    title: '{{Sélectionner un équipement}}',
    height: 250,
    width: 800,
    top: '20vh',
    contentUrl: 'index.php?v=d&modal=eqLogic.human.insert',
    callback: function() { if (_options) mod_insertEqLogic.setOptions(_options) },
    buttons: {
      confirm: {
        label: '{{Valider}}',
        className: 'success',
        callback: {
          click: function(event) {
            var args = {}
            args.human = mod_insertEqLogic.getValue()
            args.id = mod_insertEqLogic.getId()
            if (args.human.trim() != '') {
                callback(args)
            }
            document.getElementById('mod_insertEqLogicValue')._jeeDialog.destroy()
          }
        }
      },
      cancel: {
        label: '{{Annuler}}',
        className: 'warning',
        callback: {
          click: function(event) {
            document.getElementById('mod_insertEqLogicValue')._jeeDialog.destroy()
          }
        }
      }
    }
  })
}

jeedom.eqLogic.refreshValue = function(_params) {
  var paramsRequired = []
  var eqLogics = {}
  var sends = {}
  var eqLogic = null
  var page = document.body.getAttribute('data-page')

  for (var i in _params) {
    eqLogic = document.querySelector('.eqLogic[data-eqLogic_id="' + _params[i].eqLogic_id + '"]')
    if(eqLogic.classList.contains("is-dragging")){
      continue;
    }
    if (eqLogic != null) {
      if ((page == 'dashboard' && _params[i].visible == '0') || _params[i].enable == '0') { //Remove it
        let parent = eqLogic.parentNode
        eqLogic.remove()
        if (parent.querySelectorAll('.eqLogic').length == 0) {
          if (page == 'dashboard') {
            parent.parentNode.remove()
          }
        } else if (Packery.data(parent) != undefined) {
          Packery.data(parent).layout()
        }
        continue
      }
    } else {
      if ((page == 'dashboard' && _params[i].visible == '0') || _params[i].enable == '0') continue
    }

    eqLogics[_params[i].eqLogic_id] = {
      eqLogic: eqLogic,
    }
    sends[_params[i].eqLogic_id] = {
      version: ((version = eqLogic?.getAttribute('data-version')) != undefined) ? version : 'dashboard'
    }
  }

  if (Object.keys(eqLogics).length == 0) {
    return
  }
  var paramsSpecifics = {
    global: false,
    noDisplayError: true,
    success: function(result) {
      var eqLogic = null
      var uid = null
      for (var i in result) {
        tile = domUtils.parseHTML(result[i].html)
        if (tile.childNodes.length == 0) {
          continue
        }
        eqLogic = eqLogics[i].eqLogic
        if (isElement_jQuery(eqLogic)) eqLogic = eqLogic[0]
        if (eqLogic == null) {
          if (page == 'dashboard') {
            if ((object_div = document.getElementById('div_ob' + result[i].object_id)) != null) {
              if (object_div.querySelector('.eqLogic')?.getAttribute('data-order') >= result[i].order) {
                object_div.querySelector('.eqLogic').before(tile)
              } else if ((previousEqLogic = object_div.querySelector('.eqLogic[data-order="' + (result[i].order - 1) + '"]')) != null) {
                previousEqLogic.after(tile)
              } else {
                object_div.html(result[i].html, true)
              }
              jeedomUtils.positionEqLogic(result[i].id)
              var packer = Packery.data(object_div)
              if (packer != undefined) packer.destroy()
              new Packery(object_div, {isLayoutInstant: true, transitionDuration: 0})

              document.querySelectorAll('div.eqLogic-widget').forEach(function(element, idx) {
                element.setAttribute('data-order', idx + 1)
              })
            }
          } else if (page == 'eqAnalyse' && result[i].alert != '') {
            document.querySelector('.alertListContainer').html(result[i].html, true)
            jeedomUtils.positionEqLogic(result[i].id, false)
            jeedomUtils.initTooltips()
            let container = document.querySelector('.alertListContainer')
            Packery.data(container).destroy()
            new Packery(container, { itemSelector: "#alertEqlogic .eqLogic-widget", isLayoutInstant: true, transitionDuration: 0 })
          }
        } else {
          if (page == 'eqAnalyse' && result[i].alert == '') {
            eqLogic.remove()
            if (document.querySelector('.alertListContainer')?.querySelectorAll('.eqLogic').length > 0) {
              let container = document.querySelector('.alertListContainer')
              var packer = Packery.data(container)
              if (packer != undefined) packer.destroy()
            }
            continue
          }

          uid = tile.childNodes[0].getAttribute('data-eqLogic_uid')
          if (uid != undefined) {
            eqLogic.setAttribute('data-eqLogic_uid', uid)
          }

          eqLogic.classList.add(...tile.childNodes[0].classList)
          if (!tile.childNodes[0].hasClass('eqLogic_layout_table')) {
            eqLogic.removeClass('eqLogic_layout_table')
          }
          if (!tile.childNodes[0].hasClass('eqLogic_layout_default')) {
            eqLogic.removeClass('eqLogic_layout_default')
          }
          try {
            eqLogic.empty().appendChild(tile)
            eqLogic.querySelector('.eqLogic-widget').replaceWith(...eqLogic.querySelector('.eqLogic-widget').childNodes)
          } catch (error) {
            console.error(error)
          }
          eqLogic.triggerEvent('change')
          jeedomUtils.initTooltips()
        }

        if (jeedomUtils.userDevice.type == undefined) {
          eqLogic.triggerEvent('create')
          jeedomUtils.setTileSize('.eqLogic')
        } else if (jeeFrontEnd.dashboard && jeeFrontEnd.dashboard.editWidgetMode && typeof jeeFrontEnd.dashboard.editWidgetMode == 'function' && document.getElementById('bt_editDashboardWidgetOrder') != null) {
          jeeFrontEnd.dashboard.editWidgetMode()
        }
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
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'toHtml',
    ids: JSON.stringify(sends),
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.initGraphInfo = function(_eqLogicUid, _doNotHighlightGraphCmd) {
  var divGraph = document.querySelector('div.eqLogic[data-eqlogic_uid="' + _eqLogicUid + '"]:not(.zone-widget) div.eqlogicbackgraph')
  if (divGraph != null) {
    var cmdId = divGraph.dataset.cmdid
    if (!_doNotHighlightGraphCmd || _doNotHighlightGraphCmd === false) {
      document.querySelector('div.eqLogic[data-eqlogic_uid="' + _eqLogicUid + '"] div.cmd-widget[data-cmd_id="' + cmdId + '"] .cmdName')?.insertAdjacentHTML('afterbegin', '<span class="graphInfoCmd">• </span>')
    }
    jeedom.eqLogic.drawGraphInfo(_eqLogicUid, cmdId)
  }
}

jeedom.eqLogic.drawGraphInfo = function(_eqLogicUid, _cmdId) {
  var drawEqEl = document.querySelector('div.eqLogic[data-eqlogic_uid="' + _eqLogicUid + '"] .eqlogicbackgraph[data-cmdid="' + _cmdId + '"]')
  if (drawEqEl == null) return
  drawEqEl.empty()
  if (drawEqEl.length == 0) return false
  if (drawEqEl.hasClass('fixedbackgraph')) {
    var topMargin = 0
  } else {
    var topMargin = 35
  }
  var dateEnd = moment().format('YYYY-MM-DD HH:mm:ss')
  var dateStart
  var decay = drawEqEl.dataset.format
  switch (decay) {
    case 'hour':
      jeedom.eqLogic.backGraphIntervals[_cmdId] = 2 * 60 * 1000
      dateStart = moment().subtract(1, 'hours').format('YYYY-MM-DD HH:mm:ss')
      break
    case 'week':
      jeedom.eqLogic.backGraphIntervals[_cmdId] = 60 * 60 * 1000
      dateStart = moment().subtract(7, 'days').format('YYYY-MM-DD HH:mm:ss')
      break
    case 'month':
      jeedom.eqLogic.backGraphIntervals[_cmdId] = 0
      dateStart = moment().subtract(1, 'month').format('YYYY-MM-DD HH:mm:ss')
      break
    default:
      jeedom.eqLogic.backGraphIntervals[_cmdId] = 600 * 60 * 1000
      dateStart = moment().subtract(1, 'days').format('YYYY-MM-DD HH:mm:ss')
  }

  if (jeedom.eqLogic.backGraphIntervals[_cmdId] != 0) {
    setTimeout(function() {
      jeedom.eqLogic.drawGraphInfo(_cmdId)
    }, jeedom.eqLogic.backGraphIntervals[_cmdId])
  }

  jeedom.history.get({
    global: false,
    noDisplayError: true,
    cmd_id: _cmdId,
    dateStart: dateStart,
    dateEnd: dateEnd,
    addFirstPreviousValue: true,
    success: function(result) {
      if (result.data.length == 0) return false
      if (result.timelineOnly) return false
      var now = (moment().unix() + (jeeFrontEnd.serverTZoffsetMin * 60)) * 1000
      var values = result.data.map(function(elt) {
        return elt[1]
      })
      var minValue = result.cmd.subType == 'binary' ? 0 : Math.min.apply(null, values)
      var maxValue = result.cmd.subType == 'binary' ? 1.1 : Math.max.apply(null, values) * 1.01
      result.data.push([now, result.data.slice(-1)[0][1]])
      new Highcharts.StockChart({
        chart: {
          renderTo: drawEqEl,
          type: drawEqEl.dataset.type,
          borderWidth: 0,
          spacingTop: 0,
          spacingRight: 0,
          spacingBottom: 0,
          spacingLeft: 0,
          plotBorderWidth: 0,
          margin: minValue < 0 ? [topMargin, 0, 5, 0] : [topMargin, 0, 0, 0],
        },
        title: {
          text: ''
        },
        scrollbar: {
          enabled: false
        },
        rangeSelector: {
          enabled: false
        },
        navigator: {
          enabled: false
        },
        legend: {
          enabled: false
        },
        xAxis: {
          type: 'datetime',
          ordinal: false,
          visible: false,
          minPadding: 0,
          maxPadding: 0
        },
        yAxis: {
          visible: false,
          min: minValue,
          max: maxValue,
          tickPositions: [minValue, maxValue]
        },
        plotOptions: {
          column: {
            borderWidth: 0,
            opacity: 0.65
          }
        },
        series: [{
          data: result.data,
          color: drawEqEl.dataset.color,
          step: drawEqEl.dataset.type == 'area' ? 1 : 0,
          fillOpacity: 0.25,
          enableMouseTracking: false,
          animation: false,
          pointWidth: 2,
          marker: {
            enabled: false
          },
          dataGrouping: {
            approximation: 'high',
            enabled: true,
            forced: true,
            groupPixelWidth: result.cmd.subType == 'binary' ? 0 : 0.5
          },
        }],
        exporting: {
          enabled: false
        },
        credits: {
          enabled: false
        }
      })
      drawEqEl.insertAdjacentHTML('afterbegin', '<span class="eqLogicGraphPeriod">' + decay.charAt(0) + '</span>')
    }
  })
}

jeedom.eqLogic.setOrder = function(_params) {
  var paramsRequired = ['eqLogics']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'setOrder',
    eqLogics: JSON.stringify(_params.eqLogics)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.setGenericType = function(_params) {
  var paramsRequired = ['eqLogics']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'setGenericType',
    eqLogics: JSON.stringify(_params.eqLogics)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.removes = function(_params) {
  var paramsRequired = ['eqLogics']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'removes',
    eqLogics: JSON.stringify(_params.eqLogics)
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.setIsVisibles = function(_params) {
  var paramsRequired = ['eqLogics', 'isVisible']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'setIsVisibles',
    eqLogics: JSON.stringify(_params.eqLogics),
    isVisible: _params.isVisible
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.setIsEnables = function(_params) {
  var paramsRequired = ['eqLogics', 'isEnable']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'setIsEnables',
    eqLogics: JSON.stringify(_params.eqLogics),
    isEnable: _params.isEnable
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.htmlAlert = function(_params) {
  var paramsRequired = ['version']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'htmlAlert',
    version: _params.version
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.eqLogic.htmlBattery = function(_params) {
  var paramsRequired = ['version']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php'
  paramsAJAX.data = {
    action: 'htmlBattery',
    version: _params.version
  }
  domUtils.ajax(paramsAJAX)
}

// deprecated v4.2 -> remove v4.5 (used by plugins!)
jeedom.eqLogic.builSelectCmd = function(_params) {
  jeedomUtils.deprecatedFunc('jeedom.eqLogic.builSelectCmd', 'jeedom.eqLogic.buildSelectCmd', '4.4', '4.1')
  return jeedom.eqLogic.buildSelectCmd(_params)
}
