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

jeedom.cache = function() { }

jeedom.cache.set = function(_params) {
  var paramsRequired = ['key', 'value']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cache.ajax.php'
  paramsAJAX.data = {
    action: 'set',
    key: _params.key,
    value: _params.value,
    lifetime: _params.lifetime || 0,
    options: JSON.stringify(_params.options) || null
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cache.byKey = function(_params) {
  var paramsRequired = ['key']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cache.ajax.php'
  paramsAJAX.data = {
    action: 'byKey',
    key: _params.key
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cache.remove = function(_params) {
  var paramsRequired = ['key']
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cache.ajax.php'
  paramsAJAX.data = {
    action: 'remove',
    key: _params.key
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cache.clean = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cache.ajax.php'
  paramsAJAX.data = {
    action: 'clean'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.cache.flush = function(_params) {
  var paramsRequired = []
  var paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  var paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/cache.ajax.php'
  paramsAJAX.data = {
    action: 'flush'
  }
  domUtils.ajax(paramsAJAX)
}