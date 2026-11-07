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

jeedom.repo = function() {};

jeedom.repo.install = function(_params) {
  const paramsRequired = ['id', 'repo'];
  const paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/repo.ajax.php';
  paramsAJAX.data = {
    action: 'install',
    repo: _params.repo,
    id: _params.id,
    version: _params.version || 'stable'
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.repo.remove = function(_params) {
  const paramsRequired = ['id', 'repo'];
  const paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/repo.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    repo: _params.repo,
    id: _params.id,
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.repo.test = function(_params) {
  const paramsRequired = ['repo'];
  const paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/repo.ajax.php';
  paramsAJAX.data = {
    action: 'test',
    repo: _params.repo,
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.repo.connectMarket = function(_params) {
  const params = _params || {};
  try {
    jeedom.private.checkParamsRequired(params, ['login', 'password']);
  } catch (e) {
    (params.error || jeedom.private.default_params.error)(e);
    return;
  }
  jeedom.repo.marketRequest('connectMarket', _params, {
    login: params.login,
    password: params.password,
    twoFactorCode: params.twoFactorCode || ''
  });
}

jeedom.repo.marketStatus = function(_params) {
  jeedom.repo.marketRequest('marketStatus', _params);
}

jeedom.repo.disconnectMarket = function(_params) {
  jeedom.repo.marketRequest('disconnectMarket', _params);
}

jeedom.repo.marketRequest = function(_action, _params, _data = {}) {
  const params = domUtils.extend({}, jeedom.private.default_params, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/repo.ajax.php';
  paramsAJAX.data = domUtils.extend({ action: _action }, _data);
  domUtils.ajax(paramsAJAX);
}

jeedom.repo.backupList = function(_params) {
  const paramsRequired = ['repo'];
  const paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/repo.ajax.php';
  paramsAJAX.data = {
    action: 'backupList',
    repo: _params.repo,
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.repo.pullInstall = function(_params) {
  const paramsRequired = ['repo'];
  const paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/repo.ajax.php';
  paramsAJAX.data = {
    repo: _params.repo,
    action: 'pullInstall'
  };
  domUtils.ajax(paramsAJAX);
}
