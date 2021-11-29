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

jeedom.log = function() {};
jeedom.log.timeout = null
jeedom.log.currentAutoupdate = []
jeedom.log.coloredThreshold = 200000

jeedom.log.list = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'list',
  };
  $.ajax(paramsAJAX);
}

jeedom.log.removeAll = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'removeAll',
  };
  $.ajax(paramsAJAX);
}

jeedom.log.getScTranslations = function(_params) {
  var paramsSpecifics = {};
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'getScTranslations'
  };
  $.ajax(paramsAJAX);
}

jeedom.log.get = function(_params) {
  var paramsRequired = ['log'];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'get',
    log: _params.log
  };
  $.ajax(paramsAJAX);
}

jeedom.log.remove = function(_params) {
  var paramsRequired = ['log'];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    log: _params.log
  };
  $.ajax(paramsAJAX);
}

jeedom.log.clearAll = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'clearAll',
  };
  $.ajax(paramsAJAX);
}

jeedom.log.clear = function(_params) {
  var paramsRequired = ['log'];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'clear',
    log: _params.log
  };
  $.ajax(paramsAJAX);
}

jeedom.log.autoupdate = function(_params) {
  if (!isset(_params['once'])) {
    _params['once'] = 0
  }
  if (!isset(_params.callNumber)) {
    _params.callNumber = 0
  }
  if (!isset(_params.log)) {
    console.log('[jeedom.log.autoupdate] No logfile')
    return
  }
  if (!isset(_params.display)) {
    console.log('[jeedom.log.autoupdate] No display')
    return
  }
  if (!_params['display'].is(':visible')) {
    return
  }
  if (_params.callNumber > 0 && isset(_params['control']) && _params['control'].attr('data-state') != 1) {
    return
  }
  if (_params.callNumber > 0 && isset(jeedom.log.currentAutoupdate[_params.display.uniqueId().attr('id')]) && jeedom.log.currentAutoupdate[_params.display.uniqueId().attr('id')].log != _params.log) {
    return
  }
  if (_params.callNumber == 0) {
    if (isset(_params.default_search)) {
      _params['search'].value(_params.default_search);
    } else {
      _params['search'].value('');
    }
    _params.display.scrollTop(_params.display.height() + 200000);
    if (_params['control'].attr('data-state') == 0 && _params['once'] == 0) {
      _params['control'].attr('data-state', 1);
    }
    _params['control'].off('click').on('click', function() {
      if ($(this).attr('data-state') == 1) {
        $(this).attr('data-state', 0);
        $(this).removeClass('btn-warning').addClass('btn-success');
        $(this).html('<i class="fa fa-play"></i><span class="hidden-768"> {{Reprendre}}</span>');
      } else {
        $(this).removeClass('btn-success').addClass('btn-warning');
        $(this).html('<i class="fa fa-pause"></i><span class="hidden-768"> {{Pause}}</span>');
        $(this).attr('data-state', 1);
        _params.display.scrollTop(_params.display.height() + 200000);
        _params['once'] = 0;
        jeedom.log.autoupdate(_params);
      }
    });

    _params['search'].off('keypress').on('keypress', function() {
      if (_params['control'].attr('data-state') == 0) {
        _params['control'].trigger('click');
      }
    });
  }
  _params.callNumber++;
  jeedom.log.currentAutoupdate[_params.display.uniqueId().attr('id')] = {
    log: _params.log
  };

  if (_params.callNumber > 0 && (_params.display.scrollTop() + _params.display.innerHeight() + 1) < _params.display[0].scrollHeight) {
    if (_params['control'].attr('data-state') == 1) {
      _params['control'].trigger('click');
    }
    return;
  }

  jeedom.log.get({
    log: _params.log,
    slaveId: _params.slaveId,
    global: (_params.callNumber == 1),
    success: function(result) {
      var log = ''
      var line
      var isSysLog = (_params.display[0].id == 'pre_globallog') ? true : false
      var isScenaroLog = (_params.display[0].id == 'pre_scenariolog') ? true : false

      if ($.isArray(result)) {
        //line by line, numbered for system log:
        for (var i in result.reverse()) {
          if (!isset(_params['search']) || _params['search'].value() == '' || result[i].toLowerCase().indexOf(_params['search'].value().toLowerCase()) != -1) {
            line = $.trim(result[i])
            if (isSysLog) {
              log += i.padStart(4, 0) + '|' + line + "\n"
            } else {
              log += line + "\n"
            }
          }
        }
      }

      var colorMe = false
      var isAuto = ($rawLogCheck.attr('autoswitch') == 1) ? true : false
      var isLong = (log.length > jeedom.log.coloredThreshold) ? true : false

      if (!$rawLogCheck.is(':checked') && !isLong) {
        colorMe = true
      } else if (isLong && !isAuto && !$rawLogCheck.is(':checked')) {
        colorMe = true
      } else if (isLong && isAuto && _params.callNumber == 1) {
        colorMe = false
        $rawLogCheck.prop('checked', true)
      } else if (!isLong && isAuto && _params.callNumber == 1) {
        colorMe = true
        $rawLogCheck.prop('checked', false)
      }

      if (colorMe) {
        if (isScenaroLog) {
          log = jeedom.log.scenarioColorReplace(log)
        } else {
          log = jeedom.log.stringColorReplace(log)
        }
        _params.display.html(log)
      } else {
        _params.display.text(log)
      }

      if (_params['once'] != 1) {
        _params.display.scrollTop(_params.display.height() + 200000);
        if (jeedom.log.timeout !== null) {
          clearTimeout(jeedom.log.timeout)
        }
        jeedom.log.timeout = setTimeout(function() {
          jeedom.log.autoupdate(_params)
        }, 1000)
      }
    },
    error: function() {
      if (jeedom.log.timeout !== null) {
        clearTimeout(jeedom.log.timeout);
      }
      jeedom.log.timeout = setTimeout(function() {
        jeedom.log.autoupdate(_params)
      }, 1000);
    },
  });
}

//Standard log replacement:
jeedom.log.colorReplacement = {
  'WARNING:': '<span class="warning">WARNING</span>:',
  'Erreur': '<span class="danger">Erreur</span>',
  'OK': '<strong>OK</strong>',
  '[INFO]': '<span class="label label-xs label-info">INFO</span>',
  '[DEBUG]': '<span class="label label-xs label-success">DEBUG</span>',
  '[WARNING]': '<span class="label label-xs label-warning">WARNING</span>',
  '[ALERT]': '<span class="label label-xs label-warning">ALERT</span>',
  '[ERROR]': '<span class="label label-xs label-danger">ERROR</span>',

}
jeedom.log.stringColorReplace = function(_str) {
  for (var re in jeedom.log.colorReplacement) {
    _str = _str.split(re).join(jeedom.log.colorReplacement[re])
  }
  return _str
}

//scenario log replacement:
jeedom.log.colorScReplacement = null
jeedom.log.getScTranslations({
  success: function(result) {
    jeedom.log.colorScReplacement = JSON.parse(result)
    jeedom.log.colorScReplacement[' Start : '] = {
      'txt': ' Start : ',
      'replace': '<strong> -- Start : </strong>'
    }
    jeedom.log.colorScReplacement['Log :'] = {
      'txt': 'Log :',
      'replace': '<span class="success">&ensp;&ensp;&ensp;Log :</span>'
    }
  },
  error: function() {
    console.log('Unable to get jeedom scenario traductions')
  }
})

jeedom.log.scenarioColorReplace = function(_str) {
  if (jeedom.log.colorScReplacement == null) return _str
  for (var item in jeedom.log.colorScReplacement) {
    _str = _str.split(jeedom.log.colorScReplacement[item]['txt']).join(jeedom.log.colorScReplacement[item]['replace'].replace('::', jeedom.log.colorScReplacement[item]['txt']))
  }
  return _str
}