
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


jeedom.view = function () {
};

jeedom.view.cache = Array();

jeedom.view.all = function (_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    pre_success: function (data) {
      jeedom.view.cache.all = data.result;
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  if (isset(jeedom.view.cache.all) && 'function' == typeof (_params.success)) {
    _params.success(jeedom.view.cache.all);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  paramsAJAX.data = {
    action: 'all',
  };
  $.ajax(paramsAJAX);
}

jeedom.view.toHtml = function (_params) {
  var paramsRequired = ['id', 'version'];
  var paramsSpecifics = {
    pre_success: function (data) {
      result = jeedom.view.handleViewAjax({view: data.result});
      result.raw = data.result;
      data.result = result;
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
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  paramsAJAX.data = {
    action: "get",
    id: ($.isArray(_params.id)) ? json_encode(_params.id) : _params.id,
    version: _params.version,
    html : true,
  };
  $.ajax(paramsAJAX);
}

jeedom.view.handleViewAjax = function (_params) {
  var result = {html: '', scenario: [], cmd: [], eqLogic: []};
  var colIdx = 0
  for (var i in _params.view.viewZone) {
    var viewZone = _params.view.viewZone[i];
    if (colIdx == 0 ) result.html += '<div class="col-xs-12 div_rowZones">';
    var div_class = 'div_viewZone ';
    if (!$.mobile) {
      div_class +=' col-xs-12 col-sm-'+init(viewZone.configuration.zoneCol,12);
    }
    if (viewZone.type == 'table') {
      div_class +=' div_viewZoneTable';
    }
    result.html += '<div class="'+div_class+'">';
    result.html += '<legend class="lg_viewZone" data-zone_id="' + viewZone.id + '">' + viewZone.name + '</legend>';
    var div_id = 'div_viewZone' + viewZone.id + Date.now();
    /*         * *****************viewZone widget***************** */
    if (viewZone.type == 'widget') {
      result.html += '<div id="' + div_id + '" class="eqLogicZone" data-viewZone-id="'+viewZone.id+'">';
      for (var j in viewZone.viewData) {
        var viewData = viewZone.viewData[j];
        result.html += viewData.html;
        result[viewData.type].push(viewData.id);
      }
      result.html += '</div>';
    }else if (viewZone.type == 'graph') {
      result.html += '<div id="' + div_id + '" class="chartContainer">';
      result.html += '<script>';
      for (var j in viewZone.viewData) {
        var viewData = viewZone.viewData[j];
        var configuration = json_encode(viewData.configuration);
        result.html += 'jeedom.history.drawChart({noError:true,cmd_id : ' + viewData.link_id + ',el : "' + div_id + '",dateRange : "' + viewZone.configuration.dateRange + '",option : jQuery.parseJSON("' + configuration.replace(/\"/g, "\\\"") + '")});';
      }
      result.html += '</script>';
      result.html += '</div>';
    }else if (viewZone.type == 'table') {
      result.html += viewZone.html;;
    }
    result.html += '</div>';

    colIdx += parseInt(init(viewZone.configuration.zoneCol,12))
    if (colIdx > 11) colIdx = 0
    if (colIdx == 0 ) result.html += '</div>';
  }
  return result;
}


jeedom.view.remove = function (_params) {
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
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    id: _params.id,
  };
  $.ajax(paramsAJAX);
}


jeedom.view.save = function (_params) {
  var paramsRequired = ['id', 'view'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  console.log(_params);
  paramsAJAX.data = {
    action: 'save',
    view_id: _params.id,
    view: json_encode(_params.view),
  };
  console.log(paramsAJAX);
  $.ajax(paramsAJAX);
}

jeedom.view.get = function (_params) {
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
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  paramsAJAX.data = {
    action: 'get',
    id: _params.id,
  };
  $.ajax(paramsAJAX);
}

jeedom.view.setEqLogicOrder = function (_params) {
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
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  paramsAJAX.data = {
    action: 'setEqLogicOrder',
    eqLogics: json_encode(_params.eqLogics),
  };
  $.ajax(paramsAJAX);
}

jeedom.view.setOrder = function(_params) {
  var paramsRequired = ['views'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  paramsAJAX.data = {
    action: 'setOrder',
    views: json_encode(_params.views)
  };
  $.ajax(paramsAJAX);
};


jeedom.view.removeImage = function (_params) {
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
  paramsAJAX.url = 'core/ajax/view.ajax.php';
  paramsAJAX.data = {
    action: 'removeImage',
    id: _params.id
  };
  $.ajax(paramsAJAX);
};
