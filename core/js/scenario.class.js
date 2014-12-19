
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


jeedom.scenario = function () {
};

jeedom.scenario.cache = Array();

if (!isset(jeedom.scenario.cache.html)) {
    jeedom.scenario.cache.html = Array();
}

jeedom.scenario.all = function (_params) {
    var paramsRequired = [];
    var paramsSpecifics = {
        pre_success: function (data) {
            jeedom.scenario.cache.all = data.result;
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
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'all',
    };
    $.ajax(paramsAJAX);
}

jeedom.scenario.toHtml = function (_params) {
    var paramsRequired = ['id', 'version'];
    var paramsSpecifics = {
        pre_success: function (data) {
            if (_params.id == 'all' || $.isArray(_params.id)) {
                for (var i in data.result) {
                    jeedom.scenario.cache.html[i] = data.result[i];
                }
            } else {
                jeedom.scenario.cache.html[_params.id] = data.result;
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
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'toHtml',
        id: ($.isArray(_params.id)) ? json_encode(_params.id) : _params.id,
        version: _params.version
    };
    $.ajax(paramsAJAX);
}


jeedom.scenario.changeState = function (_params) {
    var paramsRequired = ['id', 'state'];
    var paramsSpecifics = {global: false};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'changeState',
        id: _params.id,
        state: _params.state
    };
    $.ajax(paramsAJAX);
}

jeedom.scenario.getTemplate = function (_params) {
    var paramsRequired = [];
    var paramsSpecifics = {global: false};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'getTemplate',
    };
    $.ajax(paramsAJAX);
}

jeedom.scenario.convertToTemplate = function (_params) {
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
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'convertToTemplate',
        id: _params.id,
    };
    $.ajax(paramsAJAX);
}

jeedom.scenario.removeTemplate = function (_params) {
    var paramsRequired = ['template'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'removeTemplate',
        template: _params.template,
    };
    $.ajax(paramsAJAX);
}

jeedom.scenario.loadTemplateDiff = function (_params) {
    var paramsRequired = ['template', 'id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'loadTemplateDiff',
        template: _params.template,
        id: _params.id,
    };
    $.ajax(paramsAJAX);
}


jeedom.scenario.applyTemplate = function (_params) {
    var paramsRequired = ['template', 'id', 'convert'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'applyTemplate',
        template: _params.template,
        id: _params.id,
        convert: _params.convert,
    };
    $.ajax(paramsAJAX);
}

jeedom.scenario.refreshValue = function (_params) {
    if ($('.scenario[data-scenario_id=' + _params.id + ']').html() != undefined) {
        var version = $('.scenario[data-scenario_id=' + _params.id + ']').attr('data-version');
        var paramsRequired = ['id'];
        var paramsSpecifics = {
            global: false,
            success: function (result) {
                $('.scenario[data-scenario_id=' + params.id + ']').empty().html($(result).children());
                initTooltips();
                if ($.mobile) {
                    $('.scenario[data-scenario_id=' + params.id + ']').trigger("create");
                    setTileSize('.scenario');
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
        paramsAJAX.url = 'core/ajax/scenario.ajax.php';
        paramsAJAX.data = {
            action: 'toHtml',
            id: _params.id,
            version: _params.version || version
        };
        $.ajax(paramsAJAX);
    }
};


jeedom.scenario.copy = function (_params) {
    var paramsRequired = ['id', 'name'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'copy',
        id: _params.id,
        name: _params.name
    };
    $.ajax(paramsAJAX);
};


jeedom.scenario.get = function (_params) {
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
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'get',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.scenario.save = function (_params) {
    var paramsRequired = ['scenario'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'save',
        scenario: json_encode(_params.scenario)
    };
    $.ajax(paramsAJAX);
};

jeedom.scenario.remove = function (_params) {
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
    paramsAJAX.url = 'core/ajax/scenario.ajax.php';
    paramsAJAX.data = {
        action: 'remove',
        id: _params.id
    };
    $.ajax(paramsAJAX);
};

jeedom.scenario.getSelectModal = function (_options, callback) {
    if (!isset(_options)) {
        _options = {};
    }
    if ($("#mod_insertScenarioValue").length == 0) {
        $('body').append('<div id="mod_insertScenarioValue" title="{{Sélectionner le scénario}}" ></div>');

        $("#mod_insertScenarioValue").dialog({
            autoOpen: false,
            modal: true,
            height: 250,
            width: 800
        });
        jQuery.ajaxSetup({async: false});
        $('#mod_insertScenarioValue').load('index.php?v=d&modal=scenario.human.insert');
        jQuery.ajaxSetup({async: true});
    }
    mod_insertScenario.setOptions(_options);
    $("#mod_insertScenarioValue").dialog('option', 'buttons', {
        "Annuler": function () {
            $(this).dialog("close");
        },
        "Valider": function () {
            var retour = {};
            retour.human = mod_insertScenario.getValue();
            retour.id = mod_insertScenario.getId();
            if ($.trim(retour) != '') {
                callback(retour);
            }
            $(this).dialog('close');
        }
    });
    $('#mod_insertScenarioValue').dialog('open');
};