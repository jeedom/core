
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


jeedom.eqLogic = function () {
};

jeedom.eqLogic.cache = Array();
jeedom.eqLogic.displayObjectName = false;

jeedom.eqLogic.changeDisplayObjectName = function(_display){
  
}

if (!isset(jeedom.eqLogic.cache.getCmd)) {
  jeedom.eqLogic.cache.getCmd = Array();
}

if (!isset(jeedom.eqLogic.cache.byId)) {
  jeedom.eqLogic.cache.byId = Array();
}

jeedom.eqLogic.save = function (_params) {
  var paramsRequired = ['type', 'eqLogics'];
  var paramsSpecifics = {
    pre_success: function (data) {
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
  paramsAJAX.async =  _params.async || true;
  paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    type: _params.type,
    eqLogic: json_encode(_params.eqLogics),
  };
  $.ajax(paramsAJAX);
}

jeedom.eqLogic.simpleSave = function (_params) {
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

jeedom.eqLogic.remove = function (_params) {
  var paramsRequired = ['id', 'type'];
  var paramsSpecifics = {
    pre_success: function (data) {
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

jeedom.eqLogic.copy = function (_params) {
  var paramsRequired = ['id', 'name'];
  var paramsSpecifics = {
    pre_success: function (data) {
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

jeedom.eqLogic.print = function (_params) {
  var paramsRequired = ['id', 'type'];
  var paramsSpecifics = {
    pre_success: function (data) {
      if(data.result && data.result.cmd){
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

jeedom.eqLogic.toHtml = function (_params) {
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

jeedom.eqLogic.getCmd = function (_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {
    pre_success: function (data) {
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
  if (isset(jeedom.eqLogic.cache.getCmd[_params.id]) && 'function' == typeof (_params.success) && init(_params.noCache, false) == false) {
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


jeedom.eqLogic.byId = function (_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {
    pre_success: function (result) {
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
  if (init(_params.noCache, false) == false && isset(jeedom.eqLogic.cache.byId[_params.id]) && 'function' == typeof (_params.success)) {
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

jeedom.eqLogic.builSelectCmd = function (_params) {
  if (!isset(_params.filter)) {
    _params.filter = {};
  }
  jeedom.eqLogic.getCmd({
    id: _params.id,
    async: false,
    success: function (cmds) {
      var result = '';
      for (var i in cmds) {
        if ((init(_params.filter.type, 'all') == 'all' || cmds[i].type == _params.filter.type) &&
        (init(_params.filter.subType, 'all') == 'all' || cmds[i].subType == _params.filter.subType) &&
        (init(_params.filter.isHistorized, 'all') == 'all' || cmds[i].isHistorized == _params.filter.isHistorized)
      ) {
        result += '<option value="' + cmds[i].id + '" data-type="' + cmds[i].type + '"  data-subType="' + cmds[i].subType + '" >' + cmds[i].name + '</option>';
      }
    }
    if ('function' == typeof (_params.success)) {
      _params.success(result);
    }
  }
});
}

jeedom.eqLogic.getSelectModal = function (_options, callback) {
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
    jQuery.ajaxSetup({async: false});
    $('#mod_insertEqLogicValue').load('index.php?v=d&modal=eqLogic.human.insert');
    jQuery.ajaxSetup({async: true});
  }
  mod_insertEqLogic.setOptions(_options);
  $("#mod_insertEqLogicValue").dialog('option', 'buttons', {
    "Annuler": function () {
      $(this).dialog("close");
    },
    "Valider": function () {
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
};

jeedom.eqLogic.refreshValue = function (_params) {
  var paramsRequired = [];
  var eqLogics = {};
  var sends = {};
  for(var i in _params){
    jeedom.cmd.refreshByEqLogic({eqLogic_id : _params[i].eqLogic_id});
    var eqLogic = $('.eqLogic[data-eqLogic_id=' + _params[i].eqLogic_id + ']');
    if (eqLogic.html() == undefined || eqLogic.attr('data-version') == undefined) {
      continue;
    }
    eqLogics[_params[i].eqLogic_id] = {eqLogic : eqLogic, version : eqLogic.attr('data-version')};
    sends[_params[i].eqLogic_id] = {version : eqLogic.attr('data-version')};
  }
  if (Object.keys(eqLogics).length == 0){
    return;
  }
  var paramsSpecifics = {
    global: false,
    success: function (result) {
       for(var i in result){
        var gridstack = false;
        var html = $(result[i].html);
        var eqLogic = eqLogics[i].eqLogic;
        var visible = eqLogic.is(":visible");
        var uid = html.attr('data-eqLogic_uid');
        if(uid != 'undefined'){
          eqLogic.attr('data-eqLogic_uid',uid);
        }
        eqLogic.empty().html(html.children());
        eqLogic.attr("class", html.attr("class"));
        var top =  eqLogic.css('top');
        var left =  eqLogic.css('left');
        var width =  eqLogic.css('width');
        var height =  eqLogic.css('height');
        var marginTop =  eqLogic.css('margin-top');
        var marginBottom =  eqLogic.css('margin-bottom');
        var marginLeft =  eqLogic.css('margin-left');
        var marginRight =  eqLogic.css('margin-right');
        var paddingTop =  eqLogic.css('padding-top');
        var paddingBottom =  eqLogic.css('padding-bottom');
        var paddingRight =  eqLogic.css('padding-left');
        var paddingLeft =  eqLogic.css('padding-right');
        var position =  eqLogic.css('position');
        var transform_origin =  eqLogic.css('transform-origin');
        var transform =  eqLogic.css('transform');
        var zindex =  eqLogic.css('z-index');
        eqLogic.attr("style", html.attr("style"));
        eqLogic.css('top',top);
        eqLogic.css('left',left);
        eqLogic.css('width',width);
        eqLogic.css('height',height);
        eqLogic.css('margin-top',marginTop);
        eqLogic.css('margin-bottom',marginBottom);
        eqLogic.css('margin-left',marginLeft);
        eqLogic.css('margin-right',marginRight);
        eqLogic.css('padding-top',paddingTop);
        eqLogic.css('padding-bottom',paddingBottom);
        eqLogic.css('padding-left',paddingLeft);
        eqLogic.css('padding-right',paddingRight);
        eqLogic.css('position',position);
        eqLogic.css('transform-origin',transform_origin);
        eqLogic.css('transform',transform);
        eqLogic.css('z-index',zindex);
        if(!visible){
          eqLogic.hide();
        }
        eqLogic.trigger('change');
        if ($.mobile) {
          $('.eqLogic[data-eqLogic_id=' + i + ']').trigger("create");
          setTileSize('.eqLogic');
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
};


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
};

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
};

jeedom.eqLogic.setIsVisibles = function(_params) {
  var paramsRequired = ['eqLogics','isVisible'];
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
    isVisible : _params.isVisible
  };
  $.ajax(paramsAJAX);
};

jeedom.eqLogic.setIsEnables = function(_params) {
  var paramsRequired = ['eqLogics','isEnable'];
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
    isEnable : _params.isEnable
  };
  $.ajax(paramsAJAX);
};


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
};


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
};
