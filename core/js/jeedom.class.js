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

 function jeedom() {
 }

 jeedom.cache = [];
 jeedom.display = {};

 if (!isset(jeedom.cache.getConfiguration)) {
    jeedom.cache.getConfiguration = null;
}

jeedom.changes = function(){
   var paramsRequired = [];
   var paramsSpecifics = {
    global: false,
    success: function(data) {
        jeedom.datetime = data.datetime;
        for(var i in data.result){
            if(isset(data.result[i].option)){
                $('body').trigger(data.result[i].name,data.result[i].option);
            }
        }
        setTimeout(jeedom.changes, 1000);
    },
    error: function(){
        setTimeout(jeedom.changes, 1000);
    }
};
try {
    jeedom.private.checkParamsRequired(paramsRequired);
} catch (e) {
    (paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
}
var params = $.extend({}, jeedom.private.default_params, paramsSpecifics);
var paramsAJAX = jeedom.private.getParamsAJAX(params);
paramsAJAX.url = 'core/ajax/event.ajax.php';
paramsAJAX.data = {
    action: 'changes',
    datetime:jeedom.datetime,
};
$.ajax(paramsAJAX);
}


jeedom.init = function () {
    jeedom.datetime = serverDatetime;
    jeedom.display.version = 'desktop';
    if ($.mobile) {
        jeedom.display.version = 'mobile';
    }
    Highcharts.setOptions({
        lang: {
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            shortMonths: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
        }
    });

    $('body').on('cmd::update', function (_event,_options) {
        jeedom.cmd.refreshValue({id: _options.cmd_id});
    });

    $('body').on('scenario::update', function (_event,_options) {
        jeedom.scenario.refreshValue({id: _options.id});
    });
    $('body').on('eqLogic::update', function (_event,eqLogic_id) {
        jeedom.eqLogic.refreshValue({id: eqLogic_id});
    });
    $('body').on('jeedom::say', function (_event,_message) {
        responsiveVoice.speak(_message,'French Female');
    });

    $('body').on('jeedom::gotoplan', function (_event,_plan_id) {
        if(getUrlVars('p') == 'plan' && 'function' == typeof (displayPlan)){
         if (_plan_id != $('#sel_planHeader').attr('data-link_id')) {
            planHeader_id = _plan_id;
            displayPlan();
        }
    }
});

    $('body').on('jeedom::alert', function (_event,_options) {
        if (!isset(_options.message) || $.trim(_options.message) == '') {
         if(isset(_options.page) && _options.page != ''){
            if(getUrlVars('p') == _options.page || ($.mobile && isset(CURRENT_PAGE) && CURRENT_PAGE == _options.page)){
                $.hideAlert();
            }
        }else{
            $.hideAlert();
        }
    } else {
        if(isset(_options.page) && _options.page != ''){
            if(getUrlVars('p') == _options.page || ($.mobile && isset(CURRENT_PAGE) && CURRENT_PAGE == _options.page)){
                $('#div_alert').showAlert({message: _options.message, level: _options.level});
            }
        }else{
            $('#div_alert').showAlert({message: _options.message, level: _options.level});
        }
    }

});
    $('body').on('jeedom::alertPopup', function (_event,_message) {
        alert(_message);
    });
    $('body').on('message::refreshMessageNumber', function (_event,_options) {
        refreshMessageNumber();
    });
    $('body').on('notify', function (_event,_options) {
        var theme = '';
        switch (init(_options.category)) {
            case 'event' :
            if (init(userProfils.notifyEvent) == 'none') {
                return;
            } else {
                theme = userProfils.notifyEvent;
            }
            break;
            case 'scenario' :
            if (init(userProfils.notifyLaunchScenario) == 'none') {
                return;
            } else {
                theme = userProfils.notifyLaunchScenario;
            }
            break;
            case 'message' :
            if (init(userProfils.notifyNewMessage) == 'none') {
                return;
            } else {
                theme = userProfils.notifyNewMessage;
            }
            refreshMessageNumber();
            break;
        }
        notify(_options.title, _options.message, _options.theme);
    });

    jeedom.changes();
}

jeedom.getConfiguration = function (_params) {
    var paramsRequired = ['key'];
    var paramsSpecifics = {
        pre_success: function (data) {
            jeedom.cache.getConfiguration = data.result;
            var keys = _params.key.split(':');
            data.result = jeedom.cache.getConfiguration;
            for(var i in keys){
                if (data.result[keys[i]]) {
                    data.result = data.result[keys[i]];
                }
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
    if (jeedom.cache.getConfiguration != null) {
        var keys = _params.key.split(':');
        var result = jeedom.cache.getConfiguration;
        for(var i in keys){
            if (result[keys[i]]) {
                result = result[keys[i]];
            }
        }
        _params.success(result);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
    paramsAJAX.data = {
        action: 'getConfiguration',
        key: ''
    };
    $.ajax(paramsAJAX);
};

jeedom.haltSystem = function (_params) {
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
    paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
    paramsAJAX.data = {
        action: 'haltSystem',
    };
    $.ajax(paramsAJAX);
};

jeedom.ssh = function (_params) {
    if($.isPlainObject(_params)){
        command = _params.command;
    }else{
        command = _params;
        _params = {};
    }
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
    paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
    paramsAJAX.data = {
        action: 'ssh',
        command : command
    };
    $.ajax(paramsAJAX);
    return 'Execute command : '+command;
};


jeedom.rebootSystem = function (_params) {
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
    paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
    paramsAJAX.data = {
        action: 'rebootSystem',
    };
    $.ajax(paramsAJAX);
};

jeedom.saveCustum = function (_params) {
    var paramsRequired = ['version', 'type', 'content'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
    paramsAJAX.data = {
        action: 'saveCustom',
        type: _params.type,
        version: _params.version,
        content: _params.content,
    };
    $.ajax(paramsAJAX);
};

jeedom.forceSyncHour = function (_params) {
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
    paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
    paramsAJAX.data = {
        action: 'forceSyncHour',
    };
    $.ajax(paramsAJAX);
};

jeedom.getCronSelectModal = function(_options,_callback) {
    if ($("#mod_insertCronValue").length == 0) {
        $('body').append('<div id="mod_insertCronValue" title="{{Assistant cron}}" ></div>');
        $("#mod_insertCronValue").dialog({
            autoOpen: false,
            modal: true,
            height: 250,
            width: 800
        });
        jQuery.ajaxSetup({
            async: false
        });
        $('#mod_insertCronValue').load('index.php?v=d&modal=cron.human.insert');
        jQuery.ajaxSetup({
            async: true
        });
    }
    $("#mod_insertCronValue").dialog('option', 'buttons', {
        "{{Annuler}}": function() {
            $(this).dialog("close");
        },
        "{{Valider}}": function() {
            var retour = {};
            retour.cron = {};
            retour.value = mod_insertCron.getValue();
            if ($.trim(retour) != '' && 'function' == typeof(_callback)) {
                _callback(retour);
            }
            $(this).dialog('close');
        }
    });
    $('#mod_insertCronValue').dialog('open');
};