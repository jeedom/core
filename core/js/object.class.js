
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


jeedom.object = function() {
};

jeedom.object.cache = Array();

if (!isset(jeedom.object.cache.html)) {
    jeedom.object.cache.html = Array();
}

if (!isset(jeedom.object.cache.getEqLogic)) {
    jeedom.object.cache.getEqLogic = Array();
}

if (!isset(jeedom.object.cache.html)) {
    jeedom.object.cache.html = Array();
}

if (!isset(jeedom.object.cache.byId)) {
    jeedom.object.cache.byId = Array();
}

jeedom.object.getEqLogic = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function(data) {
            jeedom.object.cache.getEqLogic[_params.id] = data.result;
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
    if (isset(jeedom.object.cache.getEqLogic[params.id])) {
        params.success(jeedom.object.cache.getEqLogic[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
    paramsAJAX.data = {
        action: "listByObject",
        object_id: _params.id,
        onlyEnable: _params.onlyEnable || false,
        orderByName : _params.orderByName || false
    };
    $.ajax(paramsAJAX);
};

jeedom.object.all = function(_params) {
    var paramsRequired = [];
    var paramsSpecifics = {
        pre_success: function(data) {
            jeedom.object.cache.all = data.result;
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
    if (isset(jeedom.scenario.cache.all)) {
        params.success(jeedom.scenario.cache.all);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/object.ajax.php';
    paramsAJAX.data = {
        action: 'all',
    };
    $.ajax(paramsAJAX);
};

jeedom.object.toHtml = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function(data) {
            if (_params.id == 'all' || $.isArray(_params.id)) {
                for (var i in data.result) {
                    jeedom.object.cache.html[i] = data.result[i];
                }
            } else {
                jeedom.object.cache.html[_params.id] = data.result;
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
    if (init(params.useCache, false) == true && isset(jeedom.object.cache.html[params.id])) {
        params.success(jeedom.object.cache.html[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/object.ajax.php';
    paramsAJAX.data = {
        action: 'toHtml',
        id: ($.isArray(_params.id)) ? json_encode(_params.id) : _params.id,
        version: _params.version || 'dashboard',
    };
    $.ajax(paramsAJAX);
};

jeedom.object.remove = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function(data) {
            if (isset(jeedom.object.cache.all)) {
                delete jeedom.object.cache.all;
            }
            if (isset(jeedom.object.cache.html[_params.id])) {
                delete jeedom.object.cache.html[_params.id];
            }
            if (isset(jeedom.object.cache.getEqLogic[_params.id])) {
                delete jeedom.object.cache.getEqLogic[_params.id];
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
    if (init(params.useCache, false) == true && isset(jeedom.object.cache.html[params.id])) {
        params.success(jeedom.object.cache.html[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/object.ajax.php';
    paramsAJAX.data = {
        action: 'remove',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.object.save = function(_params) {
    var paramsRequired = ['object'];
    var paramsSpecifics = {
        pre_success: function(data) {
            if (isset(jeedom.object.cache.all)) {
                delete jeedom.object.cache.all;
            }
            if (isset(jeedom.object.cache.html[_params.id])) {
                delete jeedom.object.cache.html[_params.id];
            }
            if (isset(jeedom.object.cache.getEqLogic[_params.id])) {
                delete jeedom.object.cache.getEqLogic[_params.id];
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
    if (init(params.useCache, false) == true && isset(jeedom.object.cache.html[params.id])) {
        params.success(jeedom.object.cache.html[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/object.ajax.php';
    paramsAJAX.data = {
        action: 'save',
        object: json_encode(_params.object),
    };
    $.ajax(paramsAJAX);
};


jeedom.object.byId = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function(data) {
            jeedom.object.cache.byId[data.result.id] = data.result;
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
    if (isset(jeedom.object.cache.byId[params.id]) && init(_params.cache,true) == true) {
        params.success(jeedom.object.cache.byId[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/object.ajax.php';
    paramsAJAX.data = {
        action: 'byId',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.object.setOrder = function(_params) {
    var paramsRequired = ['objects'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/object.ajax.php';
    paramsAJAX.data = {
        action: 'setOrder',
        objects: json_encode(_params.objects)
    };
    $.ajax(paramsAJAX);
};