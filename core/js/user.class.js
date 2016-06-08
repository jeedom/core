
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


 jeedom.user = function() {
 };
 jeedom.user.connectCheck = 0;

 jeedom.user.all = function(_params) {
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
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'all',
    };
    $.ajax(paramsAJAX);
}

jeedom.user.remove = function(_params) {
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
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'remove',
        id: _params.id
    };
    $.ajax(paramsAJAX);
}

jeedom.user.save = function(_params) {
    var paramsRequired = ['users'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'save',
        users: json_encode(_params.users)
    };
    $.ajax(paramsAJAX);
}

jeedom.user.saveProfils = function(_params) {
    var paramsRequired = ['profils'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'saveProfils',
        profils: json_encode(_params.profils)
    };
    $.ajax(paramsAJAX);
}

jeedom.user.get = function(_params) {
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
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'get',
        profils: json_encode(_params.profils)
    };
    $.ajax(paramsAJAX);
};

jeedom.user.isConnect = function(_params) {
    if (Math.round(+new Date() / 1000) > (jeedom.user.connectCheck + 300)) {
        var paramsRequired = [];
        var paramsSpecifics = {
            pre_success: function(data) {
                if (data.state != 'ok') {
                    return {state: 'ok', result: false};
                } else {
                    jeedom.user.connectCheck = Math.round(+new Date() / 1000);
                    return {state: 'ok', result: true};
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
        paramsAJAX.url = 'core/ajax/user.ajax.php';
        paramsAJAX.global = false;
        paramsAJAX.data = {
            action: 'isConnect',
        };
        $.ajax(paramsAJAX);
    } else {
        if ('function' == typeof (_params.success)) {
            _params.success(true);
        }
    }
}

jeedom.user.validateTwoFactorCode = function(_params) {
    var paramsRequired = ['code'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'validateTwoFactorCode',
        code: _params.code,
        enableTwoFactorAuthentification : _params.enableTwoFactorAuthentification || 0
    };
    $.ajax(paramsAJAX);
};

jeedom.user.useTwoFactorAuthentification = function(_params) {
    var paramsRequired = ['login'];
    var paramsSpecifics = {
        global: false,
    }
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'useTwoFactorAuthentification',
        login: _params.login
    };
    $.ajax(paramsAJAX);
};

jeedom.user.login = function(_params) {
    var paramsRequired = ['username','password'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'login',
        username: _params.username,
        password: _params.password,
        twoFactorCode: _params.twoFactorCode || '',
        storeConnection: _params.storeConnection || 0,
    };
    $.ajax(paramsAJAX);
};


jeedom.user.refresh = function(_params) {
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
    paramsAJAX.url = 'core/ajax/user.ajax.php';
    paramsAJAX.data = {
        action: 'refresh',
    };
    $.ajax(paramsAJAX);
};
