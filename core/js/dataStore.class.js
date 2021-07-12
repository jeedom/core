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

jeedom.dataStore = function() {};

jeedom.dataStore.save = function(_params) {
    var paramsRequired = ['id', 'value', 'type', 'key', 'link_id'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.async = _params.async || true;

    paramsAJAX.url = 'core/ajax/dataStore.ajax.php';
    paramsAJAX.data = {
        action: 'save',
        id: _params.id,
        value: _params.value,
        type: _params.type,
        key: _params.key,
        link_id: _params.link_id,
    };
    $.ajax(paramsAJAX);
}

jeedom.dataStore.byTypeLinkIdKey = function(_params) {
    var paramsRequired = ['type', 'linkId', 'key', 'usedBy'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/dataStore.ajax.php';
    paramsAJAX.data = {
        action: 'byTypeLinkIdKey',
        type: _params.type,
        linkId: _params.linkId,
        key: _params.key,
        usedBy: _params.usedBy
    };
    $.ajax(paramsAJAX);
}

jeedom.dataStore.all = function(_params) {
    var paramsRequired = ['type', 'usedBy'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/dataStore.ajax.php';
    paramsAJAX.data = {
        action: 'all',
        type: _params.type,
        usedBy: _params.usedBy
    };
    $.ajax(paramsAJAX);
}

jeedom.dataStore.getSelectModal = function(_options, callback) {
    if (!isset(_options)) {
        _options = {};
    }
    if ($("#mod_insertDataStoreValue").length != 0) {
        $("#mod_insertDataStoreValue").remove();
    }
    $('body').append('<div id="mod_insertDataStoreValue" title="{{Sélectionner une variable}}" ></div>');
    $("#mod_insertDataStoreValue").dialog({
        closeText: '',
        autoOpen: false,
        modal: true,
        height: 250,
        width: 800
    });
    jQuery.ajaxSetup({
        async: false
    });
    $('#mod_insertDataStoreValue').load('index.php?v=d&modal=dataStore.human.insert');
    jQuery.ajaxSetup({
        async: true
    });
    mod_insertDataStore.setOptions(_options);
    $("#mod_insertDataStoreValue").dialog('option', 'buttons', {
        "{{Annuler}}": function() {
            $(this).dialog("close");
        },
        "{{Valider}}": function() {
            var retour = {};
            retour.human = mod_insertDataStore.getValue();
            retour.id = mod_insertDataStore.getId();
            if ($.trim(retour) != '') {
                callback(retour);
            }
            $(this).dialog('close');
        }
    });
    $('#mod_insertDataStoreValue').dialog('open');
}

jeedom.dataStore.remove = function(_params) {
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
    paramsAJAX.url = 'core/ajax/dataStore.ajax.php';
    paramsAJAX.data = {
        action: 'remove',
        id: _params.id
    };
    $.ajax(paramsAJAX);
}