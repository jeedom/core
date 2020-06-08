
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


jeedom.plan = function () {
};

jeedom.plan.cache = Array();

jeedom.plan.remove = function (_params) {
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
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    id: _params.id || '',
    link_type: _params.link_type || '',
    link_id: _params.link_id || '',
    planHeader_id: _params.planHeader_id || ''
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.execute = function (_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {global: false};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'execute',
    id: _params.id
  };
  $.ajax(paramsAJAX);
};


jeedom.plan.save = function (_params) {
  var paramsRequired = ['plans'];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    plans: json_encode(_params.plans),
  };
  $.ajax(paramsAJAX);
};


jeedom.plan.byId = function (_params) {
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
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'get',
    id: _params.id
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.getObjectPlan = function (_params) {
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
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'getObjectPlan',
    id: _params.id,
    version: _params.version || 'dashboard'
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.create = function (_params) {
  var paramsRequired = ['plan'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'create',
    plan: json_encode(_params.plan),
    version: _params.version
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.copy = function (_params) {
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
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'copy',
    id: _params.id || '',
    link_type: _params.link_type || '',
    link_id: _params.link_id || '',
    planHeader_id: _params.planHeader_id || ''
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.byPlanHeader = function (_params) {
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
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'planHeader',
    planHeader_id: _params.id,
    noHtml : _params.noHtml,
    version: _params.version || 'dashboard'
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.removeImageHeader = function (_params) {
  var paramsRequired = ['planHeader_id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'removeImageHeader',
    id: _params.planHeader_id
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.saveHeader = function (_params) {
  var paramsRequired = ['planHeader'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'savePlanHeader',
    planHeader: json_encode(_params.planHeader)
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.copyHeader = function (_params) {
  var paramsRequired = ['id', 'name'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'copyPlanHeader',
    id: _params.id,
    name: _params.name
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.removeHeader = function (_params) {
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
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'removePlanHeader',
    id: _params.id
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.getHeader = function (_params) {
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
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'getPlanHeader',
    id: _params.id,
    code: _params.code
  };
  $.ajax(paramsAJAX);
};

jeedom.plan.allHeader = function (_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.plan.cache.all = data.result;
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
  if (isset(jeedom.plan.cache.all)) {
    params.success(jeedom.plan.cache.all);
    return;
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plan.ajax.php';
  paramsAJAX.data = {
    action: 'allHeader',
  };
  $.ajax(paramsAJAX);
}
