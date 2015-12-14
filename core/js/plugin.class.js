
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


jeedom.plugin = function() {
};

jeedom.plugin.cache = Array();

jeedom.plugin.all = function(_params) {
    var paramsRequired = [];
    var paramsSpecifics = {
        pre_success: function(data) {
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
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'all',
    };
    $.ajax(paramsAJAX);
}


jeedom.plugin.toggle = function(_params) {
    var paramsRequired = ['id', 'state'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'toggle',
        id: _params.id,
        state: _params.state
    };
    $.ajax(paramsAJAX);
};

jeedom.plugin.get = function(_params) {
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
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'getConf',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.plugin.getDependancyInfo = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        global: false,
    };
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'getDependancyInfo',
        id: _params.id,
        slave_id: _params.slave_id || 0
    };
    $.ajax(paramsAJAX);
};

jeedom.plugin.dependancyInstall = function(_params) {
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
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'dependancyInstall',
        id: _params.id,
        slave_id: _params.slave_id || 0
    };
    $.ajax(paramsAJAX);
};

jeedom.plugin.getDeamonInfo = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        global: false,
    };
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'getDeamonInfo',
        id: _params.id,
        slave_id: _params.slave_id || 0
    };
    $.ajax(paramsAJAX);
};

jeedom.plugin.deamonStart = function(_params) {
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
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'deamonStart',
        id: _params.id,
        slave_id: _params.slave_id || 0,
        debug: _params.debug || 0
    };
    $.ajax(paramsAJAX);
};

jeedom.plugin.deamonStop = function(_params) {
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
    paramsAJAX.url = 'core/ajax/plugin.ajax.php';
    paramsAJAX.data = {
        action: 'deamonStop',
        id: _params.id,
        slave_id: _params.slave_id || 0
    };
    $.ajax(paramsAJAX);
};