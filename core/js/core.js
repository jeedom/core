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

$(function() {
  if (!$.mobile) {
    jeedom.init();
  }
});

function getTemplate(_folder, _version, _filename, _replace) {
  if (_folder == 'core') {
    var path = _folder + '/template/' + _version + '/' + _filename;
  } else {
    var path = 'plugins/' + _folder + '/desktop/template/' + _version + '/' + _filename;
  }
  var template = '';
  $.ajax({
    type: "POST",
    url: path,
    async: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (isset(_replace) && _replace != null) {
        var reg = null;
        for (i in _replace) {
          reg = new RegExp(i, "g");
          data = data.replace(reg, _replace[i]);
        }
      }
      template = data;
    }
  });
  return template;
}

function handleAjaxError(_request, _status, _error, _div_alert) {
  $.hideLoading();
  var div_alert = init(_div_alert, $('#div_alert'));
  if (_request.status != '0') {
    if (init(_request.responseText, '') != '') {
      div_alert.showAlert({
        message: _request.responseText,
        level: 'danger'
      });
    } else {
      div_alert.showAlert({
        message: _request.status + ' : ' + _error,
        level: 'danger'
      });
    }
  }
}

function init(_value, _default) {
  if (!isset(_default)) {
    _default = '';
  }
  if (!isset(_value)) {
    return _default;
  }
  return _value;
}

function getUrlVars(_key) {
  var vars = [],
    hash, nbVars = 0;
  var hashes = window.location.search.replace('?', '').split('&');
  for (var i = 0; i < hashes.length; i++) {
    if (hashes[i] !== "" && hashes[i] !== "?") {
      hash = hashes[i].split('=');
      nbVars++;
      vars[hash[0]] = hash[1];
      if (isset(_key) && _key == hash[0]) {
        return hash[1];
      }
    }
  }
  if (isset(_key)) {
    return false;
  }
  vars.length = nbVars;
  return vars;
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  if (!isset(exdays)) exdays = 1;
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;samesite=Strict";
}

function getCookie(name) {
  var cookies = document.cookie.split(';');
  var csplit = null;
  for (var i in cookies) {
    csplit = cookies[i].split('=');
    if (name.trim() == csplit[0].trim()) {
      return csplit[1];
    }
  }
  return '';
}

function getDeviceType() {
  var ua = navigator.userAgent
  var result = {}
  result.width = $('#pagecontainer').width()
  result.type = 'desktop'
  result.subType = ''
  result.bSize = 220

  if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
    result.type = 'tablet'
  }
  if (/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
    result.type = 'phone'
  }

  if (/(android)/.test(ua)) {
    result.subType = 'android'
  }
  if (/ipad|iP(hone|od)/.test(ua)) {
    result.subType = 'ios'
  }

  if (result.subType == 'android') {
    result.width = screen.width
    if ($('#pagecontainer').width() > 899) {
      result.type = 'tablet'
    }
  }

  if (result.type == 'phone') {
    var margin = (result.subType == 'ios' ? 6 : 12)
    var ori = window.orientation
    if (ori == 90 || ori == -90) { //landscape
      result.bSize = (result.width / 4) - margin
    } else { //portrait
      result.bSize = (result.width / 2) - margin
    }
  }

  return result
}