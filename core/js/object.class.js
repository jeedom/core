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

jeedom.object = function() {};
jeedom.object.cache = Array();

if (!isset(jeedom.object.cache.getEqLogic)) {
  jeedom.object.cache.getEqLogic = Array();
}

if (!isset(jeedom.object.cache.byId)) {
  jeedom.object.cache.byId = Array();
}

jeedom.object.getEqLogic = function(_params) {
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
    action: "listByObject",
    object_id: _params.id,
    onlyEnable: _params.onlyEnable || 0,
    orderByName: _params.orderByName || 0,
    onlyHasCmds: json_encode(_params.onlyHasCmds) || 0
  };
  $.ajax(paramsAJAX);
}

jeedom.object.getEqLogicsFromSummary = function(_params) {
  var paramsRequired = ['id', 'summary'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);

  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: "getEqLogicsFromSummary",
    id: _params.id,
    summary: _params.summary,
    onlyEnable: _params.onlyEnable || '1',
    onlyVisible: _params.onlyVisible || '0'
  };
  $.ajax(paramsAJAX);
}

jeedom.object.all = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (!isset(_params.onlyHasEqLogic)) {
        jeedom.object.cache.all = data.result;
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
  if (isset(jeedom.object.cache.all) && !isset(_params.onlyHasEqLogic)) {
    params.success(jeedom.object.cache.all);
    return;
  }
  if (_params.onlyVisible == undefined) {
    _params.onlyVisible = true
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'all',
    onlyHasEqLogic: _params.onlyHasEqLogic || '',
    searchOnchild: _params.searchOnchild || '1',
    onlyVisible: _params.onlyVisible
  };
  $.ajax(paramsAJAX);
}

jeedom.object.toHtml = function(_params) {
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
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'toHtml',
    id: ($.isArray(_params.id)) ? json_encode(_params.id) : _params.id,
    version: _params.version || 'dashboard',
    category: _params.category || 'all',
    summary: _params.summary || '',
    tag: _params.tag || 'all',
  };
  $.ajax(paramsAJAX);
}

jeedom.object.remove = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.object.cache.all)) {
        delete jeedom.object.cache.all;
      }
      if (isset(jeedom.object.cache.getEqLogic[_params.id])) {
        delete jeedom.object.cache.getEqLogic[_params.id];
      }
      if (isset(jeedom.object.cache.byId[_params.id])) {
        delete jeedom.object.cache.byId[_params.id];
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
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.object.save = function(_params) {
  var paramsRequired = ['object'];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (isset(jeedom.object.cache.all)) {
        delete jeedom.object.cache.all;
      }
      if (isset(jeedom.object.cache.getEqLogic[data.result.id])) {
        delete jeedom.object.cache.getEqLogic[data.result.id];
      }
      if (isset(jeedom.object.cache.byId[data.result.id])) {
        delete jeedom.object.cache.byId[data.result.id];
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
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    object: json_encode(_params.object),
  };
  $.ajax(paramsAJAX);
}

jeedom.object.byId = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.object.cache.byId[data.result.id] = data.result;
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
  if (isset(jeedom.object.cache.byId[params.id]) && init(_params.cache, true) == true) {
    params.success(jeedom.object.cache.byId[params.id]);
    return;
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'byId',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.object.getActionSummary = function(_params) {
  var paramsRequired = ['object_id', 'summary'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'getActionSummary',
    object_id: _params.object_id,
    summary: _params.summary,
    version: _params.version || 'dashboard'
  };
  $.ajax(paramsAJAX);
}

jeedom.object.setOrder = function(_params) {
  var paramsRequired = ['objects'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'setOrder',
    objects: json_encode(_params.objects)
  };
  $.ajax(paramsAJAX);
}

jeedom.object.getUISelectList = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'getUISelectList',
    objects: _params.none
  };
  $.ajax(paramsAJAX);
}



jeedom.object.summaryUpdate = function(_params) {
  var objects = {};
  var sends = {};
  var object = null;
  var summarySpan = null;
  var keySpan = null;
  var updated = null;
  var icon = null;

  for (var i in _params) {
    object = $('.objectSummary' + _params[i].object_id);
    if (object.html() == undefined || object.attr('data-version') == undefined) {
      continue;
    }
    if (isset(_params[i]['keys'])) {
      updated = false;
      for (var key in _params[i]['keys']) {
        summarySpan = object.find('.objectSummaryParent[data-summary="' + key + '"]')
        keySpan = summarySpan.find('.objectSummary' + key);
        if (summarySpan.html() != undefined) {
          updated = true;
          //hide if no display if nul:
          if (summarySpan.attr('data-displayZeroValue') == 0 && _params[i]['keys'][key]['value'] === 0) {
            summarySpan.hide();
            continue;
          }
          if (_params[i]['keys'][key]['value'] === null) {
            continue;
          }
          try {
            //update icon and value:
            if (_params[i]['keys'][key]['value'] == 0 && summarySpan.attr('data-iconnul') != '') {
              icon = decodeURIComponent(summarySpan.attr('data-iconnul')).replaceAll('+', ' ')
            } else {
              icon = decodeURIComponent(summarySpan.attr('data-icon')).replaceAll('+', ' ')
            }
            summarySpan.find('i').remove()
            summarySpan.show().prepend(icon)

            //update number:
            if (_params[i]['keys'][key]['value'] == 0 && summarySpan.attr('data-hidenulnumber') == '1') {
              keySpan.empty()
            } else {
              keySpan.empty().append(_params[i]['keys'][key]['value']).show();
            }
          } catch(e) {}
        }
      }
      if ((updated && !isset(_params[i]['force'])) || (updated && _params[i]['force'] != 1)) {
        continue;
      }
    }
    objects[_params[i].object_id] = {
      object: object,
      version: object.attr('data-version')
    };
    sends[_params[i].object_id] = {
      version: object.attr('data-version')
    };
  }
  if (Object.keys(objects).length == 0) {
    return;
  }
  var paramsRequired = [];
  var paramsSpecifics = {
    global: false,
    success: function(result) {
      for (var i in result) {
        objects[i].object.replaceWith($(result[i].html));
        if ($('.objectSummary' + i).closest('.objectSummaryHide') != []) {
          if ($(result[i].html).html() == '') {
            $('.objectSummary' + i).closest('.objectSummaryHide').hide();
          } else {
            $('.objectSummary' + i).closest('.objectSummaryHide').show();
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
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'getSummaryHtml',
    ids: json_encode(sends),
  };
  $.ajax(paramsAJAX);
}

jeedom.object.getImgPath = function(_params) {
  if (_params.id == 'all') {
    return;
  }
  jeedom.object.byId({
    id: _params.id,
    global: false,
    async: false,
    error: function(data) {
      return;
    },
    success: function(data) {
      if (!isset(data.img)) {
        return '';
      }
      if (isset(data.configuration.useBackground) && data.configuration.useBackground == 1) {
        jeedomUtils.setBackgroundImage('')
        return
      }
      _params.success(data.img);
    }
  });
}

jeedom.object.removeImage = function(_params) {
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
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'removeImage',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.object.uploadImage = function(_params) {
  var paramsRequired = ['id', 'file'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/object.ajax.php';
  paramsAJAX.data = {
    action: 'uploadImage',
    id: _params.id,
    file: _params.file
  };
  $.ajax(paramsAJAX);
}