
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

jeedom.widgets = function() {
};

jeedom.widgets.remove = function(_params) {
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
  paramsAJAX.url = 'core/ajax/widgets.ajax.php';
  paramsAJAX.data = {
    action: "remove",
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.widgets.byId = function(_params) {
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
  paramsAJAX.url = 'core/ajax/widgets.ajax.php';
  paramsAJAX.data = {
    action: "byId",
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.widgets.save = function(_params) {
  var paramsRequired = ['widgets'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/widgets.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    widgets: json_encode(_params.widgets),
  };
  $.ajax(paramsAJAX);
}

jeedom.widgets.all = function(_params) {
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
  paramsAJAX.url = 'core/ajax/widgets.ajax.php';
  paramsAJAX.data = {
    action: 'all'
  };
  $.ajax(paramsAJAX);
}

jeedom.widgets.getTemplateConfiguration = function(_params) {
  var paramsRequired = ['template'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/widgets.ajax.php';
  paramsAJAX.data = {
    action: 'getTemplateConfiguration',
    template: _params.template
  };
  $.ajax(paramsAJAX);
}

jeedom.widgets.getPreview = function(_params) {
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
  paramsAJAX.url = 'core/ajax/widgets.ajax.php';
  paramsAJAX.data = {
    action: "getPreview",
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.widgets.replacement = function(_params) {
  var paramsRequired = ['version','replace','by'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/widgets.ajax.php';
  paramsAJAX.data = {
    action: "replacement",
    version: _params.version,
    replace: _params.replace,
    by: _params.by
  };
  $.ajax(paramsAJAX);
}

jeedom.widgets.getThemeImg = function(_light,_dark){
  if(_light != '' && _dark == ''){
    return _light;
  }
  if(_light == '' && _dark != ''){
    return _dark;
  }
  if ($('body')[0].hasAttribute('data-theme')) {
    if ($('body').attr('data-theme').endsWith('Light')) return _light;
  }
  return _dark;
}
