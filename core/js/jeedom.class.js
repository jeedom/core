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
jeedom.nodeJs = {state: -1};
jeedom.display = {};

if (!isset(jeedom.cache.getConfiguration)) {
    jeedom.cache.getConfiguration = Array();
}


jeedom.init = function () {
    jeedom.display.version = 'desktop';
    if ($.mobile) {
        jeedom.display.version = 'mobile';
    }

    socket = null;
    Highcharts.setOptions({
        lang: {
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            shortMonths: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
        }
    });
    if (nodeJsKey != '' && io != null) {
        socket = io.connect();
        socket.on('error', function (reason) {
            console.log('Unable to connect Socket.IO', reason);
        });
        socket.on('connect', function () {
            socket.emit('authentification', nodeJsKey, user_id);
            $('.span_nodeJsState').removeClass('red').addClass('green');
            jeedom.nodeJs.state = true;
            $('body').trigger('nodeJsConnect');
        });
        socket.on('authentification_failed', function () {
            notify('Node JS erreur', '{{Erreur d\'authentification sur node JS, clef invalide}}', 'error');
            $('.span_nodeJsState').removeClass('green').addClass('red');
            jeedom.nodeJs.state = false;
        });
        socket.on('eventCmd', function (_options) {
            _options = json_decode(_options);
            if ($.isArray(_options)) {
                for (var i in _options) {
                    jeedom.cmd.refreshValue({id: _options[i].cmd_id});
                }
            } else {
                jeedom.cmd.refreshValue({id: _options.cmd_id});
            }

        });
        socket.on('eventScenario', function (scenario_id) {
            jeedom.scenario.refreshValue({id: scenario_id});
        });
        socket.on('eventEqLogic', function (eqLogic_id) {
            jeedom.eqLogic.refreshValue({id: eqLogic_id});
        });
        socket.on('jeedom::say', function (_message) {
            responsiveVoice.speak(_message,'French Female');
        });
        socket.on('jeedom::alert', function (_options) {
            _options = json_decode(_options);
            if (!isset(_options.message) || $.trim(_options.message) == '') {
                $.hideAlert();
            } else {
                $('#div_alert').showAlert({message: _options.message, level: _options.level});
            }

        });
        socket.on('message::refreshMessageNumber', function (_options) {
            refreshMessageNumber();
        });
        socket.on('notify', function (title, text, category) {
            var theme = '';
            switch (init(category)) {
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
            notify(title, text, theme);
        });
    } else {
        $('.span_nodeJsState').removeClass('red').addClass('grey');
        jeedom.nodeJs.state = null;
    }
}

jeedom.getConfiguration = function (_params) {
    var paramsRequired = ['key'];
    var paramsSpecifics = {
        pre_success: function (data) {
            jeedom.cache.getConfiguration[_params.key] = data.result;
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
    if (isset(jeedom.cache.getConfiguration[params.key])) {
        _params.success(jeedom.cache.getConfiguration[params.key]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
    paramsAJAX.data = {
        action: 'getConfiguration',
        key: _params.key,
        default: init(_params.default, 0)
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

jeedom.doUPnP = function (_params) {
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
        action: 'doUPnP',
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