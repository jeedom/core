
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

jeedom.cmd = function () {
};
jeedom.cmd.cache = Array();
if (!isset(jeedom.cmd.cache.byId)) {
    jeedom.cmd.cache.byId = Array();
}
if (!isset(jeedom.cmd.cache.byHumanName)) {
    jeedom.cmd.cache.byHumanName = Array();
}

jeedom.cmd.execute = function (_params) {
    var notify = _params.notify || true;
    if (notify) {
        var eqLogic = $('.cmd[data-cmd_id=' + _params.id + ']').closest('.eqLogic');
        eqLogic.find('.statusCmd').empty().append('<i class="fa fa-spinner fa-spin"></i>');
    }
    if (_params.value != 'undefined' && (is_array(_params.value) || is_object(_params.value))) {
        _params.value = json_encode(_params.value);
    }
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        global: false,
        pre_success: function (data) {
            if (data.state != 'ok') {
                if ('function' != typeof (_params.error)) {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                }
                if (notify) {
                    eqLogic.find('.statusCmd').empty().append('<i class="fa fa-times"></i>');
                    setTimeout(function () {
                        eqLogic.find('.statusCmd').empty();
                    }, 3000);
                }
                return data;
            }
            if (notify) {
                eqLogic.find('.statusCmd').empty().append('<i class="fa fa-rss"></i>');
                setTimeout(function () {
                    eqLogic.find('.statusCmd').empty();
                }, 3000);
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
    paramsAJAX.url = 'core/ajax/cmd.ajax.php';
    var cache = 1;
    if (_params.cache !== undefined) {
        cache = _params.cache;
    }
    paramsAJAX.data = {
        action: 'execCmd',
        id: _params.id,
        cache: cache,
        value: _params.value || ''
    };
    $.ajax(paramsAJAX);
};
jeedom.cmd.test = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        global: false,
        success: function (result) {
            switch (result.type) {
                case 'info' :
                    jeedom.cmd.execute({
                        id: _params.id,
                        cache: 0,
                        notify: false,
                        success: function (result) {
                            bootbox.confirm('{{Résulat de la commande : }}' + result, function () {
                            });
                        }
                    });
                    break;
                case 'action' :
                    switch (result.subType) {
                        case 'other' :
                            jeedom.cmd.execute({
                                id: _params.id,
                                cache: 0,
                                error: function (error) {
                                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                                },
                                success: function () {
                                    $('#div_alert').showAlert({message: '{{Action exécutée avec succès}}', level: 'success'});
                                }
                            });
                            break;
                        case 'slider' :
                            jeedom.cmd.execute({
                                id: _params.id,
                                value: {slider: 50},
                                cache: 0,
                                error: function (error) {
                                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                                },
                                success: function () {
                                    $('#div_alert').showAlert({message: '{{Action exécutée avec succès}}', level: 'success'});
                                }
                            });
                            break;
                        case 'color' :
                            jeedom.cmd.execute({
                                id: _params.id,
                                value: {color: '#fff000'},
                                cache: 0,
                                error: function (error) {
                                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                                },
                                success: function () {
                                    $('#div_alert').showAlert({message: '{{Action exécutée avec succès}}', level: 'success'});
                                }
                            });
                            break;
                        case 'message' :
                            jeedom.cmd.execute({
                                id: _params.id,
                                value: {title: '{{[Jeedom] Message de test}}', message: '{{Ceci est un test de message pour la commande}} ' + result.name},
                                cache: 0,
                                error: function (error) {
                                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                                },
                                success: function () {
                                    $('#div_alert').showAlert({message: '{{Action éxecutée avec succès}}', level: 'success'});
                                }
                            });
                            break;
                    }
                    break;
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
    paramsAJAX.url = 'core/ajax/cmd.ajax.php';
    paramsAJAX.data = {
        action: 'getCmd',
        id: _params.id,
    };
    $.ajax(paramsAJAX);
};
jeedom.cmd.refreshValue = function (_params) {
    var cmd = $('.cmd[data-cmd_id=' + _params.id + ']');
    if (cmd.html() != undefined && cmd.closest('.eqLogic').attr('data-version') != undefined) {
        var version = cmd.closest('.eqLogic').attr('data-version');
        var paramsRequired = ['id'];
        var paramsSpecifics = {
            global: false,
            success: function (result) {
                cmd.replaceWith(result.html);
                initTooltips();
                if ($.mobile) {
                    $('.cmd[data-cmd_id=' + params.id + ']').trigger("create");
                } else {
                    positionEqLogic($('.cmd[data-cmd_id=' + params.id + ']').closest('.eqLogic').attr('data-eqLogic_id'), true);
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
        paramsAJAX.url = 'core/ajax/cmd.ajax.php';
        paramsAJAX.data = {
            action: 'toHtml',
            id: _params.id,
            version: _params.version || version,
            cache: _params.cache || 2,
        };
        $.ajax(paramsAJAX);
    }
};
jeedom.cmd.save = function (_params) {
    var paramsRequired = ['cmd'];
    var paramsSpecifics = {
        pre_success: function (data) {
            if (isset(jeedom.cmd.cache.byId[data.result.id])) {
                delete jeedom.cmd.cache.byId[data.result.id];
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
    paramsAJAX.url = 'core/ajax/cmd.ajax.php';
    paramsAJAX.data = {
        action: 'save',
        cmd: json_encode(_params.cmd)
    };
    $.ajax(paramsAJAX);
}


jeedom.cmd.byId = function (_params) {
    var paramsRequired = ['id'];
    var paramsSpecifics = {
        pre_success: function (data) {
            jeedom.cmd.cache.byId[data.result.id] = data.result;
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
    if (isset(jeedom.cmd.cache.byId[params.id]) && init(params.noCache, false) == false) {
        params.success(jeedom.cmd.cache.byId[params.id]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/cmd.ajax.php';
    paramsAJAX.data = {
        action: 'byId',
        id: _params.id
    };
    $.ajax(paramsAJAX);
}

jeedom.cmd.byHumanName = function (_params) {
    var paramsRequired = ['humanName'];
    var paramsSpecifics = {
        pre_success: function (data) {
            jeedom.cmd.cache.byHumanName[data.result.humanName] = data.result;
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
    if (isset(jeedom.cmd.cache.byHumanName[params.humanName]) && init(params.noCache, false) == false) {
        params.success(jeedom.cmd.cache.byHumanName[params.humanName]);
        return;
    }
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/cmd.ajax.php';
    paramsAJAX.data = {
        action: 'byHumanName',
        humanName: _params.humanName
    };
    $.ajax(paramsAJAX);
}

jeedom.cmd.usedBy = function (_params) {
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
    paramsAJAX.url = 'core/ajax/cmd.ajax.php';
    paramsAJAX.data = {
        action: 'usedBy',
        id: _params.id
    };
    $.ajax(paramsAJAX);
}

jeedom.cmd.changeType = function (_cmd, _subType) {
    var selSubType = '<select style="width : 120px;margin-top : 5px;" class="cmdAttr form-control input-sm" data-l1key="subType">';
    var type = _cmd.find('.cmdAttr[data-l1key=type]').value();
    jeedom.getConfiguration({
        key: 'cmd:type:' + type + ':subtype',
        default: 0,
        async: false,
        error: function (error) {
            _params.error(error);
        },
        success: function (subType) {
            for (var i in subType) {
                selSubType += '<option value="' + i + '">' + subType[i].name + '</option>';
            }
            selSubType += '</select>';
            _cmd.find('.subType').empty();
            _cmd.find('.subType').append(selSubType);
            if (isset(_subType)) {
                _cmd.find('.cmdAttr[data-l1key=subType]').value(_subType);
                modifyWithoutSave = false;
            }
            jeedom.cmd.changeSubType(_cmd);
            if ('function' == typeof (initExpertMode)) {
                initExpertMode();
            }
        }
    });
};
jeedom.cmd.changeSubType = function (_cmd) {
    jeedom.getConfiguration({
        key: 'cmd:type:' + _cmd.find('.cmdAttr[data-l1key=type]').value() + ':subtype:' + _cmd.find('.cmdAttr[data-l1key=subType]').value(),
        default: 0,
        async: false,
        error: function (error) {
            _params.error(error);
        },
        success: function (subtype) {
            for (var i in subtype) {
                if (isset(subtype[i].visible)) {
                    var el = _cmd.find('.cmdAttr[data-l1key=' + i + ']');
                    if (el.attr('type') == 'checkbox' && el.parent().is('span')) {
                        el = el.parent();
                    }
                    if (subtype[i].visible) {
                        el.show();
                        el.removeClass('hide');
                    } else {
                        el.hide();
                        el.addClass('hide');
                    }
                    if (isset(subtype[i].parentVisible)) {
                        if (subtype[i].parentVisible) {
                            el.parent().show();
                            el.parent().removeClass('hide');
                        } else {
                            el.parent().hide();
                            el.parent().addClass('hide');
                        }
                    }
                } else {
                    for (var j in subtype[i]) {
                        var el = _cmd.find('.cmdAttr[data-l1key=' + i + '][data-l2key=' + j + ']');
                        if (el.attr('type') == 'checkbox' && el.parent().is('span')) {
                            el = el.parent();
                        }
                        if (isset(subtype[i][j].visible)) {
                            if (subtype[i][j].visible) {
                                el.show();
                                el.removeClass('hide');
                            } else {
                                el.hide();
                                el.addClass('hide');
                            }

                        }
                        if (isset(subtype[i][j].parentVisible)) {
                            if (subtype[i][j].parentVisible) {
                                el.parent().show();
                                el.parent().removeClass('hide');
                            } else {
                                el.parent().hide();
                                el.parent().addClass('hide');
                            }
                        }
                    }
                }
            }
            if (_cmd.find('.cmdAttr[data-l1key=subType]').value() == 'slider' || _cmd.find('.cmdAttr[data-l1key=subType]').value() == 'other' || _cmd.find('.cmdAttr[data-l1key=subType]').value() == 'color') {
                _cmd.find('.cmdAttr[data-l1key=value]').show();
            }
            if (_cmd.find('.cmdAttr[data-l1key=type]').value() == 'action') {
                _cmd.find('.cmdAttr[data-l1key=configuration][data-l2key=updateCmdId]').show();
                _cmd.find('.cmdAttr[data-l1key=configuration][data-l2key=updateCmdToValue]').show();
            }
            _cmd.find('.cmdAttr[data-l1key=eventOnly]').trigger('change');
            modifyWithoutSave = false;
            if ('function' == typeof (initExpertMode)) {
                initExpertMode();
            }
        }
    });
};
jeedom.cmd.availableType = function () {
    var selType = '<select style="width : 120px; margin-bottom : 3px;" class="cmdAttr form-control input-sm" data-l1key="type">';
    selType += '<option value="info">{{Info}}</option>';
    selType += '<option value="action">{{Action}}</option>';
    selType += '</select>';
    return selType;
};
jeedom.cmd.getSelectModal = function (_options, _callback) {
    if (!isset(_options)) {
        _options = {};
    }
    if ($("#mod_insertCmdValue").length == 0) {
        $('body').append('<div id="mod_insertCmdValue" title="{{Sélectionner la commande}}" ></div>');
        $("#mod_insertCmdValue").dialog({
            autoOpen: false,
            modal: true,
            height: 250,
            width: 800
        });
        jQuery.ajaxSetup({async: false});
        $('#mod_insertCmdValue').load('index.php?v=d&modal=cmd.human.insert');
        jQuery.ajaxSetup({async: true});
    }
    mod_insertCmd.setOptions(_options);
    $("#mod_insertCmdValue").dialog('option', 'buttons', {
        "Annuler": function () {
            $(this).dialog("close");
        },
        "Valider": function () {
            var retour = {};
            retour.cmd = {};
            retour.human = mod_insertCmd.getValue();
            retour.cmd.id = mod_insertCmd.getCmdId();
            if ($.trim(retour) != '' && 'function' == typeof (_callback)) {
                _callback(retour);
            }
            $(this).dialog('close');
        }
    });
    $('#mod_insertCmdValue').dialog('open');
};
jeedom.cmd.displayActionOption = function (_expression, _options, _callback) {
    var html = '';
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/scenario.ajax.php", // url du fichier php
        data: {
            action: 'actionToHtml',
            version: 'scenario',
            expression: _expression,
            option: json_encode(_options)
        },
        dataType: 'json',
        async: ('function' == typeof (_callback)),
        global: false,
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            if (data.result.html != '') {
                html += data.result.html;
            }
            if ('function' == typeof (_callback)) {
                _callback(html);
                return;
            }
        }
    });
    return html;
};


jeedom.cmd.normalizeName = function (_tagname) {
    var arrayOn = ['on', 'marche', 'go', 'lock'];
    var arrayOff = ['off', 'arret', 'arrêt', 'stop', 'unlock'];
    var name = _tagname.toLowerCase();
    /^([^0-9]+)[0-9]*$/.exec(name);
    name = RegExp.$1;
    if (arrayOn.indexOf(name) >= 0) {//Test si name cmd équivalent à "on"
        return 'on';
    } else if (arrayOff.indexOf(name) >= 0) {//Test si name cmd équivalent à "off"
        return 'off';
    }
    if (name.indexOf("lock") == 0) {
        return 'on';
    }
    if (name.indexOf("unlock") == 0) {
        return 'off';
    }
    if (name.indexOf("on") != -1) {
        return 'on';
    }
    if (name.indexOf("off") != -1) {
        return 'off';
    }
    return _tagname;
}
