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
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
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
    domUtils.ajax(paramsAJAX);
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
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/dataStore.ajax.php';
    paramsAJAX.data = {
        action: 'byTypeLinkIdKey',
        type: _params.type,
        linkId: _params.linkId,
        key: _params.key,
        usedBy: _params.usedBy
    };
    domUtils.ajax(paramsAJAX);
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
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/dataStore.ajax.php';
    paramsAJAX.data = {
        action: 'all',
        type: _params.type,
        usedBy: _params.usedBy
    };
    domUtils.ajax(paramsAJAX);
}

jeedom.dataStore.getSelectModal = function(_options, callback) {
    if (!isset(_options)) {
        _options = {};
    }
    document.getElementById('mod_insertDataStoreValue')?.remove()
    document.body.insertAdjacentHTML('beforeend', '<div id="mod_insertDataStoreValue"></div>')
    jeeDialog.dialog({
        id: 'mod_insertDataStoreValue',
        title: '{{Sélectionner une variable}}',
        height: 250,
        width: 800,
        top: '20vh',
        contentUrl: 'index.php?v=d&modal=dataStore.human.insert',
        callback: function() { mod_insertDataStore.setOptions(_options) },
        buttons: {
          confirm: {
            label: '{{Valider}}',
            className: 'success',
            callback: {
              click: function(event) {
                var args = {}
                args.human = mod_insertDataStore.getValue()
                args.id = mod_insertDataStore.getId()
                if (args.human.trim() != '') {
                    callback(args)
                }
                document.getElementById('mod_insertDataStoreValue')._jeeDialog.destroy()
              }
            }
          },
          cancel: {
            label: '{{Annuler}}',
            className: 'warning',
            callback: {
              click: function(event) {
                document.getElementById('mod_insertDataStoreValue')._jeeDialog.destroy()
              }
            }
          }
        }
    })
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
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/dataStore.ajax.php';
    paramsAJAX.data = {
        action: 'remove',
        id: _params.id
    };
    domUtils.ajax(paramsAJAX);
}