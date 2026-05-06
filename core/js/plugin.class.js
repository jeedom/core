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

jeedom.plugin = function () { };
jeedom.plugin.cache = Array();

jeedom.plugin.all = function (_params) {
  const paramsRequired = [];
  const paramsSpecifics = {
    pre_success: function (data) {
      jeedom.plugin.cache.all = data.result;
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  if (isset(jeedom.plugin.cache.all) && 'function' == typeof (_params.success)) {
    _params.success(jeedom.plugin.cache.all);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'all',
    activateOnly: _params.activateOnly || false
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.toggle = function (_params) {
  const paramsRequired = ['id', 'state'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'toggle',
    id: _params.id,
    state: _params.state
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.get = function (_params) {
  const paramsRequired = ['id'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'getConf',
    id: _params.id,
    full: _params.full || 0,
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.getDependancyInfo = function (_params) {
  const paramsRequired = ['id'];
  const paramsSpecifics = {
    global: false,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'getDependancyInfo',
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.dependancyInstall = function (_params) {
  const paramsRequired = ['id'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'dependancyInstall',
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.dependancyChangeAutoMode = function (_params) {
  const paramsRequired = ['id', 'mode'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'dependancyChangeAutoMode',
    id: _params.id,
    mode: _params.mode
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.getDeamonInfo = function (_params) {
  const paramsRequired = ['id'];
  const paramsSpecifics = {
    global: false,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'getDeamonInfo',
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.deamonStart = function (_params) {
  const paramsRequired = ['id'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'deamonStart',
    id: _params.id,
    debug: _params.debug || 0,
    forceRestart: _params.forceRestart || 0
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.deamonStop = function (_params) {
  const paramsRequired = ['id'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'deamonStop',
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.deamonChangeAutoMode = function (_params) {
  const paramsRequired = ['id', 'mode'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'deamonChangeAutoMode',
    id: _params.id,
    mode: _params.mode
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.plugin.createCommunityPost = function (_params) {
  const paramsRequired = ['type'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/plugin.ajax.php';
  paramsAJAX.data = {
    action: 'createCommunityPost',
    type: _params.type,
  };
  domUtils.ajax(paramsAJAX);
}