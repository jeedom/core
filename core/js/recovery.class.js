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

jeedom.recovery = function() { }

jeedom.recovery.start = function(_params) {
    var paramsRequired = ['hardware', 'mode']
    var paramsSpecifics = {}
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
        return
    }
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
    var paramsAJAX = jeedom.private.getParamsAJAX(params)
    paramsAJAX.url = 'core/ajax/recovery.ajax.php'
    paramsAJAX.data = {
        action: 'start',
        hardware: _params.hardware,
        mode: _params.mode
    }
    domUtils.ajax(paramsAJAX)
}

jeedom.recovery.cancel = function(_params) {
    var paramsSpecifics = {}
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
    var paramsAJAX = jeedom.private.getParamsAJAX(params)
    paramsAJAX.url = 'core/ajax/recovery.ajax.php'
    paramsAJAX.data = {
        action: 'cancel'
    }
    domUtils.ajax(paramsAJAX)
}

jeedom.recovery.getProgress = function(_params) {
    var paramsSpecifics = {}
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
    var paramsAJAX = jeedom.private.getParamsAJAX(params)
    paramsAJAX.url = 'core/ajax/recovery.ajax.php'
    paramsAJAX.data = {
        action: 'getProgress'
    }
    domUtils.ajax(paramsAJAX)
}

jeedom.recovery.usbConnected = function(_params) {
    var paramsRequired = ['hardware']
    var paramsSpecifics = {}
    try {
        jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
    } catch (e) {
        (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
        return
    }
    var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
    var paramsAJAX = jeedom.private.getParamsAJAX(params)
    paramsAJAX.url = 'core/ajax/recovery.ajax.php'
    paramsAJAX.data = {
        action: 'usbConnected',
        hardware: _params.hardware
    }
    domUtils.ajax(paramsAJAX)
}
