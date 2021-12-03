/**
 * Set of configuration default, variables and functions
 * @namespace jeedom.private
 */
var init = function(_param, _default) {
  return (typeof _param == 'number') ? _param : (typeof _param != 'boolean' || _param) && (_param !== false && _param || _default || '');
}


jeedom.private = {
  /**
   * Default parameters for all API functions
   * These values are merged with passed values in function call
   * @example default_params = {
   *      async : true,         // async ajax call (sync ajax deprecated: https://xhr.spec.whatwg.org/)
   *      type : 'POST',        // data transmission
   *      dataTye : 'json',     // data type
   *      error : jeedom.private.fn_error, // on error Callback
   *      success : function (_data) {      // on success Callback
   *          return _data;
   *      },
   *      complete : function () {}        // end Callback
   * };
   */
  default_params: {
    async: true,
    type: 'POST',
    dataType: 'json',
    pre_success: function(_data) {
      return _data;
    },
    success: function(_data) {},
    post_success: function(_data) {},
    complete: function() {},
    error: function(_data) {
      //API error or wrong ajax return
      console.log(_data);
    }
  },
  /**
   * Object returned if no error
   */
  API_end_successful: 'API\'s call went alright, AJAX is running or ended if {async : false} ! Doesn\'t mean it\'s going to work as expected... It depends on your parameters, none traitment has been made.',
  code: 42
};

/**
 * String to help user know what's going on
 */
var no_error_code = 'No error code has been sent.';
var no_result = '';
var code = 42;

/**
 * Conversion function on error
 * Convert ajax return into object
 */
jeedom.private.handleAjaxErrorAPI = function(_request, _status, _error) {
  if (_request.status && _request.status != '0') {
    if (_request.responseText) {
      return {
        type: 'AJAX',
        code: code,
        message: _request.responseText
      };
    } else {
      return {
        type: 'AJAX',
        code: code,
        message: _request.status + ' : ' + _error
      };
    }
  }
  return {
    type: 'AJAX',
    code: code,
    message: 'Unknown error'
  };
}


/**
 * Return API Ajax parameters according to caller parameters
 */
jeedom.private.getParamsAJAX = function(_params) {
  // Special case parameter type
  var typeInData = false;
  if ($.inArray(_params.type, ['POST', 'GET']) === -1) {
    typeInData = true;
    _params.data = _params.data || {};
    _params._type = _params.type; //Get initial type
    _params.type = 'POST'; //Use POST
  }

  var paramsAJAX = {
    type: _params.type,
    dataType: _params.dataType,
    async: _params.async,
    global: _params.global,
    error: function(_request, _status, _error) {
      _params.error(jeedom.private.handleAjaxErrorAPI(_request, _status, _error));
    },
    success: function(data) {
      data = _params.pre_success(data);
      if (data.state != 'ok') {
        _params.error({
          type: 'PHP',
          message: data.result || 'Error - ' + no_result || '',
          code: data.code || no_error_code || ''
        });
      } else {
        //Directly send data object to caller
        var result = init(data.result, no_result);

        if (data.result === false) {
          result = false;
        }

        _params.success(result);
      }
      _params.post_success(data);
    },
    complete: _params.complete,
    data: {}
  };

  if (typeInData) {
    paramsAJAX.data.type = _params._type;
  }

  return paramsAJAX;
}

/**
 * Check if parameter value verify regex expression
 * Recursive for value as array or object
 * Must be secured in try/catch(e)
 *
 * Console example test
 * try { jeedom.private.checkParamsValue({value : [{test : 'check', test2 :'eeee'},{test : 'oefop', test2 : 'kfefe', test3 : 10}], regexp : /a|e|ch|1|zec/}); } catch(e) { console.log(e); }
 *
 * @param {Object} _params
 * @param _params.value : value of parameter to test
 * @param {Object} _params.regexp : regex to check
 * @param {string} [_params.name] : optionnal parameter name to test
 */
jeedom.private.checkParamValue = function(_params) {
  try {
    checkParamsRequired(_params, ['value', 'regexp']);
  } catch (e) {
    throw {
      type: 'API',
      code: code,
      message: 'Error in SARA JS API. Uncomplete specified parameters in checkParamValue. ' + e.message
    };
  }

  var value = _params.value;
  var regexp = _params.regexp;
  var name = _params.name || 'One parameter';

  if (typeof value == 'object') {
    //Recursivity for array or object
    for (var i in value) {
      checkParamValue({
        name: name,
        value: value[i],
        regexp: regexp
      });
    }
  } else {
    /*
      Value to string conversion
      To check if in array, use regex /word_1|word_2|word_3|word_4/
    */
    value += '';
    if (regexp.test(value) === false) {
      throw {
        type: 'API',
        code: code,
        message: name + ' isn\'t correct (doesn\'t match : ' + regexp.toString() + '). `' + value + '` received.'
      };
    }
  }
}

/**
 * Check if all required parameters are in _params object
 *
 * Each function must call checkParamsRequired after string[] creation with required params
 *
 * @return {Object} ret : check result
 * @return {boolean} ret.result : are all required parameters present
 * @return {Object[]} ret.missing : set of missing options
 * @return {string} ret.missing.name : missing parameter name
 * @return {boolean} ret.missing.optional : is missing parameter optionnal
 * @return {number} ret.missing.group : parameter associated group. 0 for required parameters, n for optionnal parameters. same for all same member group, need at least one defined or function fail
 * @return {string} ret.missing.toString : return missing parameter as string (used for display)
 */
jeedom.private.checkParamsRequired = function(_params, _paramsRequired) {
  var missings = Array();
  var group = Array();
  var missingAtLeastOneParam = false;
  var optionalGroupNumber = 0;
  var ok = null;
  for (var key in _paramsRequired) {
    if (typeof _paramsRequired[key] === 'object') {
      optionalGroupNumber++;
      ok = false;
      //One is enough, but need all present / missing parameters:
      for (var key2 in _paramsRequired[key]) {
        if (_params.hasOwnProperty(_paramsRequired[key][key2])) {
          ok = true;
        } else {
          missings.push({
            name: _paramsRequired[key][key2],
            optional: true,
            group: {
              id: optionalGroupNumber
            }
          });
        }
      }

      //Is group checked:
      group[optionalGroupNumber] = {
        checked: ok
      };

      //Does one required parameter is missing
      if (!ok) {
        missingAtLeastOneParam = true;
      }
    } else if (!_params.hasOwnProperty(_paramsRequired[key])) {
      missings.push({
        name: _paramsRequired[key],
        optional: false,
        group: {
          id: 0,
          checked: false
        }
      });
      missingAtLeastOneParam = true;
    }
  }

  if (missingAtLeastOneParam) {
    var tostring = 'Parameters missing : ';
    var miss = null;
    for (var i in missings) {
      miss = missings[i];
      tostring += miss.name + ' ';

      //If optionnal parameter, define if optionnal group is set
      var checkedstring = miss.optional && (group[miss.group.id].checked) ? 'yes' : 'no' || '';
      tostring += (miss.optional) ? '[optional, group=' + miss.group.id + ' checked=' + checkedstring + ']' : '[needed]';
      tostring += ', ';
    }
    //Ensure no ending comma / space:
    tostring = tostring.substring(0, tostring.length - 2);
    throw {
      type: 'API',
      code: code,
      message: tostring
    };
  }
  return;
}

/**
 * Check global
 * Must be secured in try/catch(e)
 */
jeedom.private.checkAndGetParams = function(_params, _paramsSpecifics, _paramsRequired) {
  //Throw execption if error
  jeedom.private.checkParamsRequired(_params, _paramsRequired || []);
  //Merge default and function specific parameters
  var params = $.extend({}, jeedom.private.default_params, _paramsSpecifics, _params || {});

  //Convert all objects in params to json
  var param = null;
  for (var attr in params) {
    params[attr] = (typeof params[attr] == 'object') ? json_encode(params[attr]) : params[attr];
  }

  var paramsAJAX = jeedom.private.getParamsAJAX(params);

  return {
    params: params,
    paramsAJAX: paramsAJAX
  };
}

/**
 * Generic function : check parameters and values
 */
jeedom.private.checkParamsValue = function(_params) {
  if (Object.prototype.toString.call(_params) == '[object Object]') {
    jeedom.private.checkParamValue(_params);
  } else {
    for (var i in _params) {
      jeedom.private.checkParamValue(_params[i]);
    }
  }
}