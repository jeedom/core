
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


 jeedom.jeeObject = function() {
 };

 jeedom.jeeObject.cache = Array();

 if (!isset(jeedom.jeeObject.cache.getEqLogic)) {
    jeedom.jeeObject.cache.getEqLogic = Array();
}

if (!isset(jeedom.jeeObject.cache.byId)) {
    jeedom.jeeObject.cache.byId = Array();
}

jeedom.jeeObject.getEqLogic = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function(data) {
            jeedom.jeeObject.cache.getEqLogic[_params.id] = data.result;
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
    if (isset(jeedom.jeeObject.cache.getEqLogic[params.id])) {
        params.success(jeedom.jeeObject.cache.getEqLogic[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/eqLogic.ajax.php';
    paramsAJAX.data = {
        action: "listByObject",
        jeeObject_id: _params.id,
        onlyEnable: _params.onlyEnable || 0,
        orderByName : _params.orderByName || 0
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeObject.all = function(_params) {
    var paramsRequired = [];
    var paramsSpecifics = {
        pre_success: function(data) {
            jeedom.jeeObject.cache.all = data.result;
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
    if (isset(jeedom.jeeObject.cache.all)) {
        params.success(jeedom.jeeObject.cache.all);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeObject.ajax.php';
    paramsAJAX.data = {
        action: 'all',
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeObject.toHtml = function(_params) {
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
    paramsAJAX.url = 'core/ajax/jeeObject.ajax.php';
    paramsAJAX.data = {
        action: 'toHtml',
        id: ($.isArray(_params.id)) ? json_encode(_params.id) : _params.id,
        version: _params.version || 'dashboard',
        category :  _params.category || 'all',
        summary :  _params.summary || '',
        tag :  _params.tag || 'all',
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeObject.remove = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function(data) {
            if (isset(jeedom.jeeObject.cache.all)) {
                delete jeedom.jeeObject.cache.all;
            }
            if (isset(jeedom.jeeObject.cache.getEqLogic[_params.id])) {
                delete jeedom.jeeObject.cache.getEqLogic[_params.id];
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
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeObject.ajax.php';
    paramsAJAX.data = {
        action: 'remove',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeObject.save = function(_params) {
    var paramsRequired = ['jeeObject'];
    var paramsSpecifics = {
        pre_success: function(data) {
            if (isset(jeedom.jeeObject.cache.all)) {
                delete jeedom.jeeObject.cache.all;
            }
            if (isset(jeedom.jeeObject.cache.getEqLogic[_params.id])) {
                delete jeedom.jeeObject.cache.getEqLogic[_params.id];
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
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeObject.ajax.php';
    paramsAJAX.data = {
        action: 'save',
        jeeObject: json_encode(_params.jeeObject),
    };
    $.ajax(paramsAJAX);
};


jeedom.jeeObject.byId = function(_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function(data) {
            jeedom.jeeObject.cache.byId[data.result.id] = data.result;
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
    if (isset(jeedom.jeeObject.cache.byId[params.id]) && init(_params.cache,true) == true) {
        params.success(jeedom.jeeObject.cache.byId[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeeObject.ajax.php';
    paramsAJAX.data = {
        action: 'byId',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.jeeObject.setOrder = function(_params) {
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
    paramsAJAX.url = 'core/ajax/jeeObject.ajax.php';
    paramsAJAX.data = {
        action: 'setOrder',
        jeeObjects: json_encode(_params.objects)
    };
    $.ajax(paramsAJAX);
};


jeedom.jeeObject.summaryUpdate = function(_params) {
    var jeeObjects = {};
    var sends = {};
    for(var i in _params){
        var jeeObject = $('.jeeObjectSummary' + _params[i].jeeObject_id);
        if (jeeObject.html() == undefined || jeeObject.attr('data-version') == undefined) {
            continue;
        }
        if(isset(_params[i]['keys'])){
            var updated = false;
            for(var j in _params[i]['keys']){
                var keySpan = jeeObject.find('.jeeObjectSummary'+j);
                if(keySpan.html() != undefined){
                    updated = true;
                    if(keySpan.closest('.jeeObjectSummaryParent').attr('data-displayZeroValue') == 0 && _params[i]['keys'][j]['value'] === 0){
                        keySpan.closest('.jeeObjectSummaryParent').hide();
                        continue;
                    }
                    if(_params[i]['keys'][j]['value'] === null){
                        continue;
                    }
                    keySpan.closest('.jeeObjectSummaryParent').show();
                    keySpan.empty().append(_params[i]['keys'][j]['value']);
                }
            }
            if(updated){
                continue;
            }
        }
        jeeObjects[_params[i].jeeObject_id] = {jeeObject : jeeObject, version : jeeObject.attr('data-version')};
        sends[_params[i].jeeObject_id] = {version : jeeObject.attr('data-version')};
    }
    if (Object.keys(jeeObjects).length == 0){
        return;
    }
    var paramsRequired = [];
    var paramsSpecifics = {
        global: false,
        success: function (result) {
            for(var i in result){
                jeeObjects[i].jeeObject.replaceWith($(result[i].html));
                if($('.jeeObjectSummary' + i).closest('.jeeObjectSummaryHide') != []){
                    if($(result[i].html).html() == ''){
                        $('.jeeObjectSummary' + i).closest('.jeeOjectSummaryHide').hide();
                    }else{
                        $('.jeeObjectSummary' + i).closest('.jeeObjectSummaryHide').show();
                    }
                }
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
    paramsAJAX.url = 'core/ajax/jeeObject.ajax.php';
    paramsAJAX.data = {
        action: 'getSummaryHtml',
        ids: json_encode(sends),
    };
    $.ajax(paramsAJAX);
};