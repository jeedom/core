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
jeedom.log.coloredThreshold = 300000
jeedom.log.maxLines = 4000

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
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'list',
  };
  domUtils.ajax(paramsAJAX);
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
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'removeAll',
  };
  domUtils.ajax(paramsAJAX);
}

// DEPRECATED: jeedom.log.getScTranslations -> remove in 4.6?
jeedom.log.getScTranslations = function(_params) {
  jeedomUtils.deprecatedFunc('jeedom.log.getScTranslations', 'none', '4.6', '4.4')
  var paramsSpecifics = {};
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'getScTranslations'
  };
  domUtils.ajax(paramsAJAX);
}

// DEPRECATED: jeedom.log.get -> remove in 4.6?
jeedom.log.get = function(_params) {
  jeedomUtils.deprecatedFunc('jeedom.log.get', 'jeedom.log.getDelta', '4.6', '4.4')
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
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'get',
    log: _params.log
  };
  domUtils.ajax(paramsAJAX);
}

jeedom.log.getDelta = function(_params) {
  var paramsRequired = ['log', 'position'];
  var paramsSpecifics = {
    global: _params.global || true,
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'getDelta',
    log: _params.log,
    position: _params.position,
    search: _params.search,
    colored: _params.colored,
    numbered: _params.numbered,
    numberStart: _params.numberStart
  };
  domUtils.ajax(paramsAJAX);
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
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    log: _params.log
  };
  domUtils.ajax(paramsAJAX);
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
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'clearAll',
  };
  domUtils.ajax(paramsAJAX);
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
  var params = domUtils.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/log.ajax.php';
  paramsAJAX.data = {
    action: 'clear',
    log: _params.log
  };
  domUtils.ajax(paramsAJAX);
}

// DEPRECATED: jeedom.log.autoupdate -> remove in 4.6?
jeedom.log.autoupdate = function(_params) {
  jeedomUtils.deprecatedFunc('jeedom.log.autoupdate', 'jeedom.log.autoUpdateDelta', '4.6', '4.4')
  if (!isset(_params.once)) _params['once'] = 0
  if (!isset(_params.callNumber)) _params.callNumber = 0
  if (!isset(_params.log)) return
  if (!isset(_params.display)) return

  //Deprecated use with jQuery objects by plugins:
  if (_params.callNumber == 0) {
    if (isElement_jQuery(_params.log)) _params.log = _params.log[0]
    if (isElement_jQuery(_params.display)) _params.display = _params.display[0]
    if (isElement_jQuery(_params.search)) _params.search = _params.search[0]
    if (isElement_jQuery(_params.control)) _params.control = _params.control[0]
  }

  if (!_params.display.isVisible()) return


  if (_params.callNumber > 0 && isset(_params.control) && _params.control.getAttribute('data-state') != 1) {
    return
  }
  if (_params.callNumber > 0 && isset(jeedom.log.currentAutoupdate[_params.display.getAttribute('id')]) && jeedom.log.currentAutoupdate[_params.display.getAttribute('id')].log != _params.log) {
    return
  }
  if (_params.callNumber == 0) {
    if (isset(_params.default_search)) {
      _params.search.value = _params.default_search
    }
    _params.display.scrollTop = _params.display.offsetHeight + 200000
    if (_params.control.getAttribute('data-state') == 0 && _params.once == 0) {
      _params.control.setAttribute('data-state', 1)
    }

    _params.control.unRegisterEvent('click').registerEvent('click', function (event) {
      if (this.getAttribute('data-state') == 1) {
        this.setAttribute('data-state', 0)
        this.removeClass('btn-warning').addClass('btn-success')
        this.innerHTML = '<i class="fa fa-play"></i><span class="hidden-768"> {{Reprendre}}</span>'
      } else {
        this.removeClass('btn-success').addClass('btn-warning')
        this.innerHTML = '<i class="fa fa-pause"></i><span class="hidden-768"> {{Pause}}</span>'
        this.setAttribute('data-state', 1)
        _params.display.scrollTop = _params.display.offsetHeight + 200000
        _params.once = 0
        jeedom.log.autoupdate(_params)
      }
    })

    _params.search.unRegisterEvent('keypress').registerEvent('keypress', function (event) {
      if (_params.control.getAttribute('data-state') == 0) {
        _params.control.click()
      }
    })
  }
  _params.callNumber++

  jeedom.log.currentAutoupdate[_params.display.getAttribute('id')] = {
    log: _params.log
  }


  if (_params.callNumber > 0 && (_params.display.scrollTop + _params.display.offsetHeight + 1) < _params.display.scrollHeight) {
    if (_params.control.getAttribute('data-state') == 1) {
      _params.control.click()
    }
    return
  }

  jeedom.log.get({
    log: _params.log,
    slaveId: _params.slaveId,
    global: (_params.callNumber == 1),
    success: function(result) {
      var log = ''
      var line
      var isSysLog = (_params.display.id == 'pre_globallog') ? true : false
      var isScenaroLog = (_params.display.id == 'pre_scenariolog') ? true : false

      if (is_array(result)) {
        //line by line, numbered for system log:
        for (var i in result.reverse()) {
          if (!isset(_params.search) || _params.search.value == '' || result[i].toLowerCase().indexOf(_params['search'].value.toLowerCase()) != -1) {
            line = result[i].trim()
            if (isSysLog) {
              log += i.padStart(4, 0) + '|' + line + "\n"
            } else {
              log += line + "\n"
            }
          }
        }
      }

      var colorMe = false
      var dom_brutlogcheck = document.getElementById('brutlogcheck')
      if (dom_brutlogcheck == null) {
        var isAuto = false
        dom_brutlogcheck = {
          checked: false
        }
      } else {
        var isAuto = (dom_brutlogcheck.getAttribute('autoswitch') == 1) ? true : false
      }
      var isLong = (log.length > jeedom.log.coloredThreshold) ? true : false

      if (!dom_brutlogcheck.checked && !isLong) {
        colorMe = true
      } else if (isLong && !isAuto && !dom_brutlogcheck.checked) {
        colorMe = true
      } else if (isLong && isAuto && _params.callNumber == 1) {
        colorMe = false
        dom_brutlogcheck.checked = true
      } else if (!isLong && isAuto && _params.callNumber == 1) {
        colorMe = true
        dom_brutlogcheck.checked = false
      }

      if (colorMe) {
        if (isScenaroLog) {
          log = jeedom.log.scenarioColorReplace(log)
        } else {
          log = jeedom.log.stringColorReplace(log)
        }
        _params.display.innerHTML = log
      } else {
        _params.display.textContent = log
      }

      if (_params.once != 1) {
        _params.display.scrollTop = _params.display.offsetHeight + 200000
        if (jeedom.log.timeout !== null) {
          clearTimeout(jeedom.log.timeout)
        }
        jeedom.log.timeout = setTimeout(function() {
          jeedom.log.autoupdate(_params)
        }, dom_brutlogcheck.checked ? 1000 : 2000)
      }
    },
    error: function() {
      if (jeedom.log.timeout !== null) {
        clearTimeout(jeedom.log.timeout)
      }
      jeedom.log.timeout = setTimeout(function() {
        jeedom.log.autoupdate(_params)
      }, 1000)
    },
  });
}

jeedom.log.updateBtn = function(_control) {
  if (_control.getAttribute('data-state') == 1) {
    _control.removeClass('btn-success').addClass('btn-warning')
    _control.innerHTML = '<i class="fa fa-pause"></i><span class="hidden-768"> {{Pause}}</span>'
  } else {
    _control.removeClass('btn-warning').addClass('btn-success')
    _control.innerHTML = '<i class="fa fa-play"></i><span class="hidden-768"> {{Reprendre}}</span>'
  }
}

jeedom.log.autoUpdateDelta = function(_params) {
  // Normalize parameters
  if (!isset(_params.callNumber)) _params.callNumber = 0
  if (!isset(_params.position)) _params.position = 0;
  if (!isset(_params.lineNum)) _params.lineNum = 0;
  if (!isset(_params.log)) return
  if (!isset(_params.display)) return

  // Deprecated use with jQuery objects by plugins
  if (_params.callNumber == 0) {
    if (isElement_jQuery(_params.log)) _params.log = _params.log[0]
    if (isElement_jQuery(_params.display)) _params.display = _params.display[0]
    if (isElement_jQuery(_params.search)) _params.search = _params.search[0]
    if (isElement_jQuery(_params.control)) _params.control = _params.control[0]
  }

  // Exit if invisible
  if (!_params.display.isVisible()) return

  // Exit if Paused
  if (
    _params.callNumber > 0
    && isset(_params.control)
    && _params.control.getAttribute('data-state') != 1
  ) {
    return
  }
  // Exit if a newer instance on another log is running
  if (
    _params.callNumber > 0
    && isset(jeedom.log.currentAutoupdate[_params.display.getAttribute('id')])
    && jeedom.log.currentAutoupdate[_params.display.getAttribute('id')].log != _params.log
  ) {
    return
  }

  if (_params.callNumber == 0) {
    // Empty space on first call
    _params.position = 0;
    _params.lineNum = 0;
    _params.display.empty();

    if (isset(_params.default_search)) {
      _params.search.value = _params.default_search
    }
    _params.display.scrollTop = _params.display.offsetHeight + _params.display.scrollHeight + 1
    if (_params.control.getAttribute('data-state') == 0) {
      _params.control.setAttribute('data-state', 1)
      jeedom.log.updateBtn(_params.control)
    }

    // Setup callback: On control button click
    _params.control.unRegisterEvent('click').registerEvent('click', function (event) {
      if (this.getAttribute('data-state') == 1) {
        // On "Pause" requested
        this.setAttribute('data-state', 0)
        jeedom.log.updateBtn(this)
      } else {
        // On "Continue" requested
        this.setAttribute('data-state', 1)
        jeedom.log.updateBtn(this)
        _params.display.scrollTop = _params.display.offsetHeight + _params.display.scrollHeight + 1
        jeedom.log.autoUpdateDelta(_params)
      }
    })

    // Setup callback: On search field update
    _params.search.unRegisterEvent('keypress').registerEvent('keypress', function (event) {
      // Reset log position and empty view
      _params.position = 0;
      _params.lineNum = 0;
      _params.display.empty();
      // Enable refresh/scrolling if disabled
      if (_params.control.getAttribute('data-state') == 0) {
        _params.control.click()
      }
    })
  }
  _params.callNumber++

  jeedom.log.currentAutoupdate[_params.display.getAttribute('id')] = {
    log: _params.log
  }

  // Disable auto update if log is scrolled
  if (_params.callNumber > 1 && (_params.display.scrollTop + _params.display.offsetHeight + 10) < _params.display.scrollHeight) {
    if (_params.control.getAttribute('data-state') == 1) {
      _params.control.click()
    }
    return
  }

  var dom_brutlogcheck = document.getElementById('brutlogcheck')
  // If element does NOT exists OR is disabled, Then colored
  var colorMe = dom_brutlogcheck == null || !dom_brutlogcheck.checked

  jeedom.log.getDelta({
    log: _params.log,
    slaveId: _params.slaveId,
    position: _params.position,
    search: (!isset(_params.search)) ? '' : _params.search.value.toLowerCase(),
    colored: (colorMe ? ((_params.display.id == 'pre_scenariolog') ? 2 : 1) : 0), // 0: no color ; 1: global log colors ; 2: Scenario colors
    numbered: (_params.display.id == 'pre_globallog'),
    numberStart: _params.lineNum,
    global: (_params.callNumber == 1),
    success: function(result) {
      // Optimise search operation
      var searchString = (!isset(_params.search)) ? '' : _params.search.value.toLowerCase()
      // Store log file current position
      _params.position = result.position;
      // Store log file current line
      _params.lineNum = result.line;

      // Get logs as text from ajax
      if (result.logText.length > 0) {
        if (colorMe) {
          _params.display.innerHTML += result.logText
        } else {
          _params.display.textContent += result.logText
        }
      }

      _params.display.scrollTop = _params.display.offsetHeight + _params.display.scrollHeight + 1
      if (jeedom.log.timeout !== null) {
        clearTimeout(jeedom.log.timeout)
      }
      jeedom.log.timeout = setTimeout(function() {
        jeedom.log.autoUpdateDelta(_params)
      }, 1000)
    },
    error: function() {
      if (jeedom.log.timeout !== null) {
        clearTimeout(jeedom.log.timeout)
      }
      jeedom.log.timeout = setTimeout(function() {
        jeedom.log.autoUpdateDelta(_params)
      }, 1000)
    },
  });
}

// Standard log replacement:
// DEPRECATED: jeedom.log.colorReplacement -> remove in 4.6?
jeedom.log.colorReplacement = {
  'WARNING:': '--startTg--span class="warning"--endTg--WARNING--startTg--/span--endTg--:',
  'Erreur': '--startTg--span class="danger"--endTg--Erreur--startTg--/span--endTg--',
  'OK': '--startTg--strong--endTg--OK--startTg--/strong--endTg--',
  '[INFO]': '--startTg--span class="label label-xs label-info"--endTg--INFO--startTg--/span--endTg--',
  '[DEBUG]': '--startTg--span class="label label-xs label-success"--endTg--DEBUG--startTg--/span--endTg--',
  '[WARNING]': '--startTg--span class="label label-xs label-warning"--endTg--WARNING--startTg--/span--endTg--',
  '[ALERT]': '--startTg--span class="label label-xs label-warning"--endTg--ALERT--startTg--/span--endTg--',
  '[ERROR]': '--startTg--span class="label label-xs label-danger"--endTg--ERROR--startTg--/span--endTg--',
}

// DEPRECATED: jeedom.log.stringColorReplace -> remove in 4.6?
jeedom.log.stringColorReplace = function(_str) {
  jeedomUtils.deprecatedFunc('jeedom.log.stringColorReplace', 'none', '4.6', '4.4')
  for (var re in jeedom.log.colorReplacement) {
    _str = _str.split(re).join(jeedom.log.colorReplacement[re])
  }
  //Avoid html code:
  _str = _str.replace(/</g, "&lt;").replace(/>/g, "&gt;")
  //Set back replaced badges to html:
  _str = _str.replace(/--endTg--/g, ">").replace(/--startTg--/g, "<")
  return _str
}

// Scenario log replacement:
// DEPRECATED: jeedom.log.colorScReplacement -> remove in 4.6?
jeedom.log.colorScReplacement = null

// DEPRECATED: jeedom.log.scenarioColorReplace -> remove in 4.6?
jeedom.log.scenarioColorReplace = function(_str) {
  jeedomUtils.deprecatedFunc('jeedom.log.scenarioColorReplace', 'none', '4.6', '4.4')
  if (jeedom.log.colorScReplacement == null) {
    // Only load translations if we are going to use them
    jeedom.log.getScTranslations({
      global: false,
      success: function(result) {
        jeedom.log.colorScReplacement = JSON.parse(result)
        jeedom.log.colorScReplacement[' Start : ']  = {
          'txt': ' Start : ',
          'replace':  '<strong> -- Start : </strong>'
        }
        jeedom.log.colorScReplacement['Log :'] = {
          'txt': 'Log :',
          'replace':  '<span class="success">&ensp;&ensp;&ensp;Log :</span>'
        }
      },
      error: function() {
        console.log('Unable to get jeedom scenario translations')
      }
    })

  }
  if (jeedom.log.colorScReplacement == null) return _str
  for (var item in jeedom.log.colorScReplacement) {
    _str = _str.split(jeedom.log.colorScReplacement[item]['txt']).join(jeedom.log.colorScReplacement[item]['replace'].replace('::', jeedom.log.colorScReplacement[item]['txt']))
  }
  return _str
}
