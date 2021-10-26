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

jeedom.eqLogic = function() {};
jeedom.eqLogic.cache = Array();
jeedom.eqLogic.backGraphIntervals = {};

jeedom.eqLogic.changeDisplayObjectName = function(_display) {
  if (_display) {
    $('div.eqLogic-widget').addClass('displayObjectName');
  } else {
    $('div.eqLogic-widget').removeClass('displayObjectName');
  }
}

if (!isset(jeedom.eqLogic.cache.getCmd)) {
  jeedom.eqLogic.cache.getCmd = Array();
}

if (!isset(jeedom.eqLogic.cache.byId)) {
  jeedom.eqLogic.cache.byId = Array();
}

jeedom.eqLogic.save = function(_params) {
  var paramsRequired = ['type', 'eqLogics'];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.eqLogic.cache.byId[data.result.id])) {
        delete jeedom.eqLogic.cache.byId[data.result.id];
      }
      if (isset(jeedom.object.cache.all)) {
        delete jeedom.object.cache.all;
      }
      if (isset(jeedom.object.cache.getEqLogic[data.result.object_id])) {
        delete jeedom.object.cache.getEqLogic[data.result.object_id];
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.async = _params.async || true;
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    type: _params.type,
    eqLogic: json_encode(_params.eqLogics),
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.byType = function(_params) {
  var paramsRequired = ['type'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'listByType',
    type: _params.type,
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.byObjectId = function(_params) {
  var paramsRequired = ['object_id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'listByObject',
    object_id: _params.object_id,
    onlyVisible: _params.onlyVisible || 0,
    onlyEnable: _params.onlyEnable || 1,
    getCmd: _params.getCmd || 0
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.simpleSave = function(_params) {
  var paramsRequired = ['eqLogic'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'simpleSave',
    eqLogic: json_encode(_params.eqLogic),
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.getUseBeforeRemove = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'getUseBeforeRemove',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.usedBy = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'usedBy',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.remove = function(_params) {
  var paramsRequired = ['id', 'type'];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.eqLogic.cache.byId[_params.id])) {
        delete jeedom.eqLogic.cache.byId[_params.id];
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    type: _params.type,
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.copy = function(_params) {
  var paramsRequired = ['id', 'name'];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.eqLogic.cache.byId[_params.id])) {
        delete jeedom.eqLogic.cache.byId[_params.id];
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'copy',
    name: _params.name,
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.print = function(_params) {
  var paramsRequired = ['id', 'type'];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (data.result && data.result.cmd) {
        jeedom.eqLogic.cache.getCmd[_params.id] = data.result.cmd;
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'get',
    type: _params.type,
    id: _params.id,
    status: _params.status || 0
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.toHtml = function(_params) {
  var paramsRequired = ['id', 'version'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'toHtml',
    id: _params.id,
    version: _params.version
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.getCmd = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.eqLogic.cache.getCmd[_params.id] = data.result;
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  if (isset(jeedom.eqLogic.cache.getCmd[_params.id]) && 'function' == typeof(_params.success) && init(_params.noCache, false) == false) {
    _params.success(jeedom.eqLogic.cache.getCmd[_params.id]);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/cmd.ajax.php';
  paramsAJAX.data = {
    action: 'byEqLogic',
    eqLogic_id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.byId = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {
    pre_success: function(result) {
      jeedom.eqLogic.cache.byId[_params.id] = result;
      return result;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  if (init(_params.noCache, false) == false && isset(jeedom.eqLogic.cache.byId[_params.id]) && 'function' == typeof(_params.success)) {
    _params.success(jeedom.eqLogic.cache.byId[_params.id]);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'byId',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.buildSelectCmd = function(_params) {
  if (!isset(_params.filter)) {
    _params.filter = {};
  }
  jeedom.eqLogic.getCmd({
    id: _params.id,
    async: false,
    success: function(cmds) {
      var result = '';
      for (var i in cmds) {
        if ((init(_params.filter.type, 'all') == 'all' || cmds[i].type == _params.filter.type) &&
          (init(_params.filter.subType, 'all') == 'all' || cmds[i].subType == _params.filter.subType) &&
          (init(_params.filter.isHistorized, 'all') == 'all' || cmds[i].isHistorized == _params.filter.isHistorized)
        ) {
          result += '<option value="' + cmds[i].id + '" data-type="' + cmds[i].type + '"  data-subType="' + cmds[i].subType + '" >' + cmds[i].name + '</option>';
        }
      }
      if ('function' == typeof(_params.success)) {
        _params.success(result);
      }
    }
  });
}

jeedom.eqLogic.getSelectModal = function(_options, callback) {
  if (!isset(_options)) {
    _options = {};
  }
  if ($("#mod_insertEqLogicValue").length == 0) {
    $('body').append('<div id="mod_insertEqLogicValue" title="{{Sélectionner un équipement}}" ></div>');

    $("#mod_insertEqLogicValue").dialog({
      closeText: '',
      autoOpen: false,
      modal: true,
      height: 250,
      width: 800
    });
    jQuery.ajaxSetup({
      async: false
    });
    $('#mod_insertEqLogicValue').load('index.php?v=d&modal=eqLogic.human.insert');
    jQuery.ajaxSetup({
      async: true
    });
  }
  mod_insertEqLogic.setOptions(_options);
  $("#mod_insertEqLogicValue").dialog('option', 'buttons', {
    "{{Annuler}}": function() {
      $(this).dialog("close");
    },
    "{{Valider}}": function() {
      var retour = {};
      retour.human = mod_insertEqLogic.getValue();
      retour.id = mod_insertEqLogic.getId();
      if ($.trim(retour) != '') {
        callback(retour);
      }
      $(this).dialog('close');
    }
  });
  $('#mod_insertEqLogicValue').dialog('open');
}

jeedom.eqLogic.refreshValue = function(_params) {
  var paramsRequired = [];
  var eqLogics = {};
  var sends = {};
  var eqLogic = null;
  for (var i in _params) {
    eqLogic = $('.eqLogic[data-eqLogic_id=' + _params[i].eqLogic_id + ']');
    if (eqLogic.html() == undefined || eqLogic.attr('data-version') == undefined) {
      continue;
    }
    eqLogics[_params[i].eqLogic_id] = {
      eqLogic: eqLogic,
      version: eqLogic.attr('data-version')
    };
    sends[_params[i].eqLogic_id] = {
      version: eqLogic.attr('data-version')
    };
  }
  if (Object.keys(eqLogics).length == 0) {
    return;
  }
  var paramsSpecifics = {
    global: false,
    success: function(result) {
      var html = null;
      var eqLogic = null;
      var uid = null;
      for (var i in result) {
        html = $(result[i].html);
        eqLogic = eqLogics[i].eqLogic;
        uid = html.attr('data-eqLogic_uid');
        if (uid != 'undefined') {
          eqLogic.attr('data-eqLogic_uid', uid);
        }
        eqLogic.addClass(html.attr('class'));
        if (!html.hasClass('eqLogic_layout_table')) {
          eqLogic.removeClass('eqLogic_layout_table');
        }
        if (!html.hasClass('eqLogic_default_table')) {
          eqLogic.removeClass('eqLogic_default_table');
        }
        eqLogic.empty().html(html.children()).trigger('change');
        if ($.mobile) {
          $('.eqLogic[data-eqLogic_id=' + i + ']').trigger("create");
          jeedomUtils.setTileSize('.eqLogic');
        } else {
          if (typeof editWidgetMode == 'function') {
            editWidgetMode();
          }
        }
      }
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'toHtml',
    ids: json_encode(sends),
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.initGraphInfo = function(_eqLogicId) {
  var divGraph = $('div.eqLogic[data-eqlogic_id=' + _eqLogicId + '] div.eqlogicbackgraph')
  if (divGraph.length) {
    var cmdId = divGraph.data('cmdid')
    jeedom.eqLogic.drawGraphInfo(cmdId)
    $('div.eqLogic[data-eqlogic_id=' + _eqLogicId + '] div.cmd-widget[data-cmd_id="' + cmdId + '"] .cmdName').prepend('• ')
  }
}

jeedom.eqLogic.drawGraphInfo = function(_cmdId) {
  var drawEqEl = $('.eqlogicbackgraph[data-cmdid=' + _cmdId + ']')
  if (drawEqEl.length == 0) return false
  var dateEnd = moment().format('YYYY-MM-DD HH:mm:ss')
  var dateStart
  var decay = drawEqEl.data('format')
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
    cmd_id: _cmdId,
    dateStart: dateStart,
    dateEnd: dateEnd,
    success: function(result) {
      if (result.data.length == 0) return false
      var now = (moment().unix() + (serverTZoffsetMin * 60)) * 1000
      var values = result.data.map(function(elt) {
        return elt[1]
      })
      var minValue = Math.min.apply(null, values)
      var maxValue = Math.max.apply(null, values)
      result.data.push([now, result.data.slice(-1)[0][1]])
      drawEqEl.empty().highcharts({
        chart: {
          type: drawEqEl.data('type'),
          borderWidth: 0,
          spacingTop: 0,
          spacingRight: 0,
          spacingBottom: 0,
          spacingLeft: 0,
          plotBorderWidth: 0,
          margin: [0, 0, 0, 0]
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
        legend: {
          enabled: false
        },
        xAxis: {
          visible: false,
          minPadding: 0,
          maxPadding: 0
        },
        yAxis: {
          visible: false,
          min: result.cmd.subType == 'binary' ? 0 : minValue,
          max: result.cmd.subType == 'binary' ? 1 : maxValue + ((maxValue - minValue) / 5)
        },
        plotOptions: {
          column: {
            borderWidth: 0,
            opacity: 0.65
          }
        },
        series: [{
          data: result.data,
          color: drawEqEl.data('color'),
          step: drawEqEl.data('type') == 'area' ? 1 : 0,
          fillOpacity: 0.25,
          enableMouseTracking: false,
          animation: false,
          marker: {
            enabled: false
          }
        }],
        exporting: {
          enabled: false
        },
        credits: {
          enabled: false
        }
      })
      drawEqEl.prepend('<span class="eqLogicGraphPeriod">' + decay.charAt(0) + '</span>')

    }
  })
}

jeedom.eqLogic.setOrder = function(_params) {
  var paramsRequired = ['eqLogics'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'setOrder',
    eqLogics: json_encode(_params.eqLogics)
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.setGenericType = function(_params) {
  var paramsRequired = ['eqLogics'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'setGenericType',
    eqLogics: json_encode(_params.eqLogics)
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.removes = function(_params) {
  var paramsRequired = ['eqLogics'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'removes',
    eqLogics: json_encode(_params.eqLogics)
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.setIsVisibles = function(_params) {
  var paramsRequired = ['eqLogics', 'isVisible'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'setIsVisibles',
    eqLogics: json_encode(_params.eqLogics),
    isVisible: _params.isVisible
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.setIsEnables = function(_params) {
  var paramsRequired = ['eqLogics', 'isEnable'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'setIsEnables',
    eqLogics: json_encode(_params.eqLogics),
    isEnable: _params.isEnable
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.htmlAlert = function(_params) {
  var paramsRequired = ['version'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'htmlAlert',
    version: _params.version
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.htmlBattery = function(_params) {
  var paramsRequired = ['version'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'htmlBattery',
    version: _params.version
  };
  $.ajax(paramsAJAX);
}

// deprecated v4.2 -> remove v4.5 (used by plugins!)
jeedom.eqLogic.builSelectCmd = jeedom.eqLogic.buildSelectCmd