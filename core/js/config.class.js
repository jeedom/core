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

jeedom.config = function() {};
jeedom.config.locales = {
    fr_FR: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'j ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        },
        calendar: {
            lastDay: '[Hier à] HH:mm',
            sameDay: '[A] HH:mm',
            nextDay: '[Demain à] <br> HH:mm',
            lastWeek: 'ddd [dernier] <br> [à] HH:mm',
            nextWeek: 'ddd [à] <br> HH:mm',
            sameElse: 'YYYY-MM-DD'
        }
    },
    en_US: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'd ',
            week: 'W ',
            month: 'M ',
            year: 'Y '
        },
        calendar: {
            lastDay: '[Yda at] HH:mm',
            sameDay: '[At] HH:mm',
            nextDay: '[Tmw at] HH:mm',
            lastWeek: '[Last] ddd <br> [at] HH:mm',
            nextWeek: 'ddd [at] <br> HH:mm',
            sameElse: 'YYYY-MM-DD'
        }
    },
    de_DE: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 's ',
            day: 't ',
            week: 'W ',
            month: 'M ',
            year: 'J '
        }
    },
    es_ES: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'd ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        },
        calendar: {
            lastDay: '[Ayer a las] <br> HH:mm',
            sameDay: '[A las] HH:mm',
            nextDay: '[Mañana a las] <br> HH:mm',
            lastWeek: '[El pasado] ddd <br> [a las] HH:mm',
            nextWeek: 'ddd [a las] <br> HH:mm',
            sameElse: 'YYYY-MM-DD'
        }
    },
    it_IT: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'o ',
            day: 'g ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        }
    },
    pt_PT: {
        duration: {
            second: 's ',
            minute: 'm ',
            hour: 'h ',
            day: 'd ',
            week: 'S ',
            month: 'M ',
            year: 'A '
        }
    },
}

jeedom.config.save = function(_params) {
    var paramsRequired = ['configuration'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/config.ajax.php';
    paramsAJAX.data = {
        action: 'addKey',
        value: JSON.stringify(_params.configuration),
        plugin: _params.plugin || 'core'
    };
    domUtils.ajax(paramsAJAX);
}

jeedom.config.load = function(_params) {
    var paramsRequired = ['configuration'];
    var paramsSpecifics = {
        global: _params.global || true
    };
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/config.ajax.php';
    paramsAJAX.data = {
        action: 'getKey',
        key: (Array.isArray(_params.configuration) || isPlainObject(_params.configuration)) ? JSON.stringify(_params.configuration) : _params.configuration,
        plugin: _params.plugin || 'core',
        convertToHumanReadable: _params.convertToHumanReadable || false
    };
    domUtils.ajax(paramsAJAX);
}

jeedom.config.remove = function(_params) {
    var paramsRequired = ['configuration'];
    var paramsSpecifics = {};
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
        return;
    }
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
    var paramsAJAX = jeedom.private.getParamsAJAX(params);
    paramsAJAX.url = 'core/ajax/config.ajax.php';
    paramsAJAX.data = {
        action: 'removeKey',
        key: (Array.isArray(_params.configuration) || isPlainObject(_params.configuration)) ? JSON.stringify(_params.configuration) : _params.configuration,
        plugin: _params.plugin || 'core'
    };
    domUtils.ajax(paramsAJAX);
}

jeedom.config.removeImage = function(_params) {
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
    paramsAJAX.url = 'core/ajax/config.ajax.php';
    paramsAJAX.data = {
        action: 'removeImage',
        id: _params.id
    };
    domUtils.ajax(paramsAJAX);
}

jeedom.config.getGenericTypeModal = function(_options, callback) {
    if (!isset(_options)) {
        _options = {}
    }
    if (!isset(_options.type)) {
        _options.type = 'info'
    }
    var url = 'index.php?v=d&modal=genericType.human.insert&type=' + _options.type
    if (_options.object) url += '&object=' + _options.object

    document.getElementById('mod_insertGenericType')?.remove()
    document.body.insertAdjacentHTML('beforeend', '<div id="mod_insertGenericType"></div>')
    jeeDialog.dialog({
        id: 'mod_insertGenericType',
        title: '{{Sélectionner un type générique}}',
        height: 250,
        width: 800,
        top: '20vh',
        contentUrl: url,
        callback: function() { mod_insertGenericType.setOptions(_options) },
        buttons: {
          confirm: {
            label: '{{Valider}}',
            className: 'success',
            callback: {
              click: function(event) {
                var args = {}
                args.human = mod_insertGenericType.getValue()
                args.id = mod_insertGenericType.getId()
                if (args.human.trim() != '') {
                    callback(args)
                }
                document.getElementById('mod_insertGenericType')._jeeDialog.destroy()
              }
            }
          },
          cancel: {
            label: '{{Annuler}}',
            className: 'warning',
            callback: {
              click: function(event) {
                document.getElementById('mod_insertGenericType')._jeeDialog.destroy()
              }
            }
          }
        }
    })
}