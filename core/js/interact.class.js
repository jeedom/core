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

jeedom.interact = function() {};

jeedom.interact.all = function(_params) {
  const paramsRequired = [];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/interact.ajax.php';
  paramsAJAX.data = {
    action: "all",
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.interact.remove = function(_params) {
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
  paramsAJAX.url = 'core/ajax/interact.ajax.php';
  paramsAJAX.data = {
    action: "remove",
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.interact.get = function(_params) {
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
  paramsAJAX.url = 'core/ajax/interact.ajax.php';
  paramsAJAX.data = {
    action: "byId",
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.interact.save = function(_params) {
  const paramsRequired = ['interact'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/interact.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    interact: JSON.stringify(_params.interact),
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.interact.regenerateInteract = function(_params) {
  const paramsRequired = [];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/interact.ajax.php';
  paramsAJAX.data = {
    action: 'regenerateInteract',
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.interact.execute = function(_params) {
  const paramsRequired = ['query'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/interact.ajax.php';
  paramsAJAX.data = {
    action: 'execute',
    query: _params.query,
  };
  domUtils.ajax(paramsAJAX);
}