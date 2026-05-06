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

jeedom.timeline = function() {};

jeedom.timeline.getLength = function(_params) {
  const paramsRequired = [];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/timeline.ajax.php';
  paramsAJAX.data = {
    action: 'getLength',
    folder: _params.folder || 'main'
  };
  domUtils.ajax(paramsAJAX)
}

jeedom.timeline.byFolder = function(_params) {
  const paramsRequired = [];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/timeline.ajax.php';
  paramsAJAX.data = {
    action: 'byFolder',
    folder: _params.folder || 'main',
    start: _params.start || 0,
    offset: _params.offset || 0
  };
  domUtils.ajax(paramsAJAX)
}

jeedom.timeline.deleteAll = function(_params) {
  const paramsRequired = [];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/timeline.ajax.php';
  paramsAJAX.data = {
    action: 'deleteAll'
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.timeline.listFolder = function(_params) {
  const paramsRequired = [];
  const paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  const paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/timeline.ajax.php';
  paramsAJAX.data = {
    action: 'listFolder'
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.timeline.removeEventInFutur = function(_params) {
  const paramsRequired = []
  const paramsSpecifics = {}
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  const params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  const paramsAJAX = jeedom.private.getParamsAJAX(params)
  paramsAJAX.url = 'core/ajax/timeline.ajax.php'
  paramsAJAX.data = {
    action: 'removeEventInFutur'
  }
  domUtils.ajax(paramsAJAX)
}

jeedom.timeline.autocompleteFolder = function() {
  jeedom.timeline.listFolder({
    global: false,
    success: function(data) {
      const availableTags = []
      for (const i in data) {
        if (data[i] != 'main') {
          availableTags.push(data[i])
        }
      }
      document.querySelectorAll('[data-l2key="timeline::folder"]').forEach(_input => {
        _input.jeeComplete({
          id: 'timelineAutocomplete',
          minLength: 0,
          source: availableTags,
          focus: function() {
            return false
          },
          select: function(event, data) {
            //Ensure timeline folders comma sperated:
            let inputValue = data.item.value
            const term = data.value
            if (inputValue.includes(' ')) {
              const values = inputValue.split(' ')
              values.pop()
              inputValue = values.join(',') + ',' + data.value
            }
            const values = inputValue.split(',')
            values.pop()
            let newValue = values.join(',') + ',' + data.value
            if (newValue.substring(0, 1) == ',') newValue = newValue.substr(1)

            data.item.value = newValue
            return false
          }
        })
      })
    }
  })
}