
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
$(function () {
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
    error: function (request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function (data) {
      if (isset(_replace) && _replace != null) {
        for (i in _replace) {
          var reg = new RegExp(i, "g");
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
      div_alert.showAlert({message: _request.responseText, level: 'danger'});
    } else {
      div_alert.showAlert({message: _request.status + ' : ' + _error, level: 'danger'});
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
  var vars = [], hash, nbVars = 0;
  var hashes = window.location.search.replace('?','').split('&');
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

function getDeviceType() {
  var result = {};
  result.type = 'desktop';
  result.width = $('#pagecontainer').width();
  if (navigator.userAgent.match(/(android)/gi)) {
    result.width = screen.width;
    result.type = 'phone';
    if ($('#pagecontainer').width() > 899) {
      result.type = 'tablet';
    }
  }
  if (navigator.userAgent.match(/(phone)/gi)) {
    result.type = 'phone';
  }
  if (navigator.userAgent.match(/(iPhone)/gi)) {
    result.type = 'phone';
  }
  if (navigator.userAgent.match(/(Lumia)/gi)) {
    result.type = 'phone';
  }
  if (navigator.userAgent.match(/(IEMobile)/gi)) {
    result.type = 'phone';
  }
  if (navigator.userAgent.match(/(iPad)/gi)) {
    result.type = 'tablet';
  }
  result.bSize = 220;
  if (result.type == 'phone') {
    var ori = window.orientation;
    if (ori == 90 || ori == -90) { //landscape
      result.bSize = (result.width / 4) - 12
    } else { //portrait
      result.bSize = (result.width / 2) - 12
    }

  }
  return result;
}