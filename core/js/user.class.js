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

jeedom.user = function() {};
jeedom.user.connectCheck = 0;

jeedom.user.all = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'all',
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.remove = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.save = function(_params) {
  const paramsRequired = ['users'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    users: JSON.stringify(_params.users)
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.saveProfils = function(_params) {
  const paramsRequired = ['profils'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'saveProfils',
    profils: JSON.stringify(_params.profils)
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.get = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'get',
    profils: JSON.stringify(_params.profils),
    id: _params.id || -1
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.isConnect = function(_params) {
  if (Math.round(+new Date() / 1000) > (jeedom.user.connectCheck + 300)) {
    const paramsRequired = [];
    const paramsSpecifics = {
      pre_success: function(data) {
        if (data.state != 'ok') {
          return {
            state: 'ok',
            result: false
          };
        } else {
          jeedom.user.connectCheck = Math.round(+new Date() / 1000);
          return {
            state: 'ok',
            result: true
          };
        }
      }
    };
    try {
      jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
      (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
      return;
    }
    const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    const paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.global = false;
    paramsAJAX.data = {
      action: 'isConnect',
    };
    domUtils.ajax(paramsAJAX);
  } else {
    if ('function' == typeof(_params.success)) {
      _params.success(true);
    }
  }
}

jeedom.user.validateTwoFactorCode = function(_params) {
  const paramsRequired = ['code'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'validateTwoFactorCode',
    code: _params.code,
    enableTwoFactorAuthentification: _params.enableTwoFactorAuthentification || 0
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.removeTwoFactorCode = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'removeTwoFactorCode',
    id: _params.id,
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.useTwoFactorAuthentification = function(_params) {
  const paramsRequired = ['login'];
  const paramsSpecifics = {
    global: false,
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'useTwoFactorAuthentification',
    login: _params.login
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.login = function(_params) {
  const paramsRequired = ['username', 'password'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'login',
    username: _params.username,
    password: _params.password,
    twoFactorCode: _params.twoFactorCode || '',
    storeConnection: _params.storeConnection || 0,
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.refresh = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'refresh',
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.removeBanIp = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'removeBanIp',
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.removeRegisterDevice = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'removeRegisterDevice',
    key: _params.key || '',
    user_id: _params.user_id || ''
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.deleteSession = function(_params) {
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
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'deleteSession',
    id: _params.id
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.supportAccess = function(_params) {
  const paramsRequired = ['enable'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'supportAccess',
    enable: _params.enable
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.user.copyRights = function(_params) {
  const paramsRequired = ['from','to'];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/user.ajax.php';
  paramsAJAX.data = {
    action: 'copyRights',
    from: _params.from,
    to: _params.to
  };
  domUtils.ajax(paramsAJAX);
}
