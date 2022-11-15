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

"use strict"

String.prototype.HTMLFormat = function() {
  return this.replace(/[\u00A0-\u9999<>\&]/g, function (i) {
    return '&#' + i.charCodeAt(0) + ';';
  });
}

Element.prototype.triggerEvent = function(_eventName) {
  var customEvent = document.createEvent('Event')
  customEvent.initEvent(_eventName, true, true)
  this.dispatchEvent(customEvent)
}

Element.prototype.findAtDepth = function(selector, maxDepth) {
  var i, depths = []
  if (maxDepth > 0) {
    for (i = 1; i <= maxDepth; i++) {
      depths.push('> ' + new Array(i).join('* > ') + selector)
    }
    selector = depths.join(', ')
    return this.querySelectorAll(selector)[0]
  }
  return this.querySelectorAll(selector)
}

//Called from single DOM element
Element.prototype.getValues = function(_attr, _depth) {
  var value = {}
  var idx, value, depthFound, thatElement, elValue, l1key, l2key, l3key
  var elements = this.findAtDepth(_attr, init(_depth, 0))
  for (idx=0; idx < elements.length; idx++) {
    thatElement = elements[idx]
    elValue = thatElement.jeeValue()
    try {
      if ($.trim(elValue).substr(0, 1) == '{') {
        elValue = JSON.parse(thatElement.jeeValue())
      }
    } catch (e) {}

    l1key = thatElement.getAttribute('data-l1key')
    if (l1key !== null && l1key != '') {
      l2key = thatElement.getAttribute('data-l2key')
      if (l2key !== null) {
        if (!isset(value[l1key])) {
          value[l1key] = {}
        }
        l3key = thatElement.getAttribute('data-l3key')
        if (l3key !== null) {
          if (!isset(value[l1key][l2key])) {
            value[l1key][l2key] = {}
          }
          if (isset(value[l1key][l2key][l3key])) {
            if (!is_array(value[l1key][l2key][l3key])) {
              value[l1key][l2key][l3key] = [value[l1key][l2key][l3key]]
            }
            value[l1key][l2key][l3key].push(elValue)
          } else {
            value[l1key][l2key][l3key] = elValue
          }
        } else {
          if (isset(value[l1key][l2key])) {
            if (!is_array(value[l1key][l2key])) {
              value[l1key][l2key] = [value[l1key][l2key]]
            }
            value[l1key][l2key].push(elValue)
          } else {
            value[l1key][l2key] = elValue
          }
        }
      } else {
        if (isset(value[l1key])) {
          if (!is_array(value[l1key])) {
            value[l1key] = [value[l1key]]
          }
          value[l1key].push(elValue)
        } else {
          value[l1key] = elValue
        }
      }
    }
  }

  return [value]
}

//Called from DOM NodeList
NodeList.prototype.getValues = function(_attr, _depth) {
  var values = [], elValues
  for (var idx=0; idx < this.length; idx++) {
    elValues = this[idx].getValues(_attr, _depth)
    values.push(elValues[0])
  }
  return values
}

Element.prototype.setValues = function(_object, _attr) {
  var selector
  for (var i in _object) {
    selector = _attr + '[data-l1key="' + i + '"]'
    if ((!is_array(_object[i]) || (this.querySelector(selector) !== null && this.querySelector(selector).getAttribute('multiple') == 'multiple')) && !is_object(_object[i])) {
      this.querySelectorAll(_attr + '[data-l1key="' + i + '"]').forEach((element) => {
        element.jeeValue(_object[i])
      })
    } else {
      for (var j in _object[i]) {
        selector = _attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]'
        if ((is_array(_object[i][j]) || (this.querySelector(selector) !== null && this.querySelector(selector).getAttribute('multiple') == 'multiple')) || is_object(_object[i][j])) {
          for (var k in _object[i][j]) {
            this.querySelectorAll(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"][data-l3key="' + k + '"]').forEach((element) => {
              element.jeeValue(_object[i][j][k])
            })
          }
        } else {
          this.querySelectorAll(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]').forEach((element) => {
            element.jeeValue(_object[i][j])
          })
        }
      }
    }
  }
}

Element.prototype.jeeValue = function(_value) {
  if (isset(_value)) { //SET
    if (this.length > 1 && this.tagName != 'SELECT') {
      var idx
      for (idx=0; idx < this.length; idx++) {
        this[idx].jeeValue(_value)
      }
    } else {
      if (this.tagName === 'INPUT') {
        if (this.getAttribute('type') == 'checkbox') {
          if (init(_value) === '') {
            return
          }
          this.checked = (init(_value) == '1') ? true : false
        } else if (this.getAttribute('type') == 'radio') {
          this.checked = (init(_value) == '1') ? true : false
        } else {
          this.value = init(_value)
        }
      } else if (this.tagName === 'SELECT') {
        if (init(_value) == '') {
          this.value = ''
        } else {
          this.value = init(_value)
        }
      } else if (this.matches('textarea')) {
        this.value = init(_value)
      } else if (this.matches('span, div, p, pre')) {
        this.innerHTML = init(_value)
      } else if (this.matches('button') && this.hasClass('dropdown-toggle')) {
        var button = this
        this.closest('div.dropdown').querySelectorAll('ul.dropdown-menu li a').forEach((element) => {
          if (this.getAttribute('data-value') == _value) {
            button.innerHTML(this.innerHTML + '<span class="caret"></span>')
            button.setAttribute('value', _value)
          }
        })
      }
      this.triggerEvent('change')
    }
  } else { //GET
    var value = '';
    if (this.matches('input, select, textarea')) {
      if (this.getAttribute('type') == 'checkbox' || this.getAttribute('type') == 'radio') {
        value = (this.checked) ? '1' : '0'
      } else {
        value = this.value
      }
    }
    if (this.matches('div, span, p')) {
      value = this.innerHTML
    }
    if (this.matches('a') && this.getAttribute('value') != undefined) {
      value = this.getAttribute('value');
    }
    if (value == '') {
      value = this.value
    }
    return value
  }
}

//js fonctions
function in_array(a, b, d) {
  var c = "";
  if (d)
    for (c in b) {
      if (b[c] === a)
        return !0
    }
  else
    for (c in b)
      if (b[c] == a)
        return !0;
  return !1
}

function json_decode(a) {
  var b = this.window.JSON;
  if ("object" === typeof b && "function" === typeof b.parse)
    try {
      return b.parse(a)
    } catch (d) {
      if (!(d instanceof SyntaxError))
        throw Error("Unexpected error type in json_decode()");
      this.php_js = this.php_js || {};
      this.php_js.last_error_json = 4;
      return null
    }
  b = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
  b.lastIndex = 0;
  b.test(a) && (a = a.replace(b, function(a) {
    return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
  }));
  if (/^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, "")))
    return a = eval("(" + a + ")");
  this.php_js = this.php_js || {};
  this.php_js.last_error_json = 4;
  return null
}

function json_encode(a) {
  var b, d = window.JSON;
  try {
    if ("object" === typeof d && "function" === typeof d.stringify) {
      b = d.stringify(a);
      if (void 0 === b)
        throw new SyntaxError("json_encode");
      return b
    }
    var c = function(a) {
        var b = /[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
          c = {
            "\b": "\\b",
            "\t": "\\t",
            "\n": "\\n",
            "\f": "\\f",
            "\r": "\\r",
            '"': '\\"',
            "\\": "\\\\"
          };
        b.lastIndex = 0;
        return b.test(a) ? '"' + a.replace(b, function(a) {
          var b = c[a];
          return "string" ===
            typeof b ? b : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
        }) + '"' : '"' + a + '"'
      },
      e = function(a, b) {
        var d = "",
          f = 0,
          m = f = "",
          m = 0,
          s = d,
          k = [],
          l = b[a];
        l && ("object" === typeof l && "function" === typeof l.toJSON) && (l = l.toJSON(a));
        switch (typeof l) {
          case "string":
            return c(l);
          case "number":
            return isFinite(l) ? String(l) : "null";
          case "boolean":
          case "null":
            return String(l);
          case "object":
            if (!l)
              return "null";
            if (this.PHPJS_Resource && l instanceof this.PHPJS_Resource || window.PHPJS_Resource && l instanceof window.PHPJS_Resource)
              throw new SyntaxError("json_encode");
            d += "    ";
            k = [];
            if ("[object Array]" === Object.prototype.toString.apply(l)) {
              m = l.length;
              for (f = 0; f < m; f += 1)
                k[f] = e(f, l) || "null";
              return m = 0 === k.length ? "[]" : d ? "[\n" + d + k.join(",\n" + d) + "\n" + s + "]" : "[" + k.join(",") + "]"
            }
            for (f in l)
              Object.hasOwnProperty.call(l, f) && (m = e(f, l)) && k.push(c(f) + (d ? ": " : ":") + m);
            return m = 0 === k.length ? "{}" : d ? "{\n" + d + k.join(",\n" + d) + "\n" + s + "}" : "{" + k.join(",") + "}";
          default:
            throw new SyntaxError("json_encode");
        }
      };
    return e("", {
      "": a
    })
  } catch (f) {
    if (!(f instanceof SyntaxError))
      throw Error("Unexpected error type in json_encode()");
    this.php_js = this.php_js || {};
    this.php_js.last_error_json = 4;
    return null
  }
}

function isset() {
  var a = arguments,
    b = a.length,
    d = 0;
  if (0 === b)
    throw Error("Empty isset");
  for (; d !== b;) {
    if (void 0 === a[d] || null === a[d])
      return !1;
    d++
  }
  return !0
}

function is_double(a) {
  return this.is_float(a)
}

function is_float(a) {
  return +a === a && (!isFinite(a) || !!(a % 1))
}

function is_int(a) {
  return a === +a && isFinite(a) && !(a % 1)
}

function is_integer(a) {
  return this.is_int(a)
}

function is_long(a) {
  return this.is_float(a)
}

function is_null(a) {
  return null === a
}

function is_numeric(a) {
  return ("number" === typeof a || "string" === typeof a) && "" !== a && !isNaN(a)
}

function is_object(a) {
  return "[object Array]" === Object.prototype.toString.call(a) ? !1 : null !== a && "object" === typeof a
}

function is_real(a) {
  return this.is_float(a)
}

function is_scalar(a) {
  return /boolean|number|string/.test(typeof a)
}

function is_string(a) {
  return "string" == typeof a
}

function is_unicode(a) {
  if ("string" !== typeof a)
    return !1;
  for (var b = [], d = RegExp("[\ud800-\udbff]([sS])", "g"), c = RegExp("([sS])[\udc00-\udfff]", "g"), e = RegExp("^[\udc00-\udfff]$"), f = RegExp("^[\ud800-\udbff]$"); null !== (b = d.exec(a));)
    if (!b[1] || !b[1].match(e))
      return !1;
  for (; null !== (b = c.exec(a));)
    if (!b[1] || !b[1].match(f))
      return !1;
  return !0
}

function is_array(a) {
  var b, d = function(a) {
    return (a = /\W*function\s+([\w\$]+)\s*\(/.exec(a)) ? a[1] : "(Anonymous)"
  };
  if (!a || "object" !== typeof a)
    return !1;
  window.php_js = window.php_js || {};
  window.php_js.ini = window.php_js.ini || {};
  b = window.php_js.ini["phpjs.objectsAsArrays"];
  return function(a) {
    if (!a || "object" !== typeof a || "number" !== typeof a.length)
      return !1;
    var b = a.length;
    a[a.length] = "bogus";
    if (b !== a.length)
      return a.length -= 1, !0;
    delete a[a.length];
    return !1
  }(a) || (!b || 0 !== parseInt(b.local_value, 10) && (!b.local_value.toLowerCase ||
    "off" !== b.local_value.toLowerCase())) && "[object Object]" === Object.prototype.toString.call(a) && "Object" === d(a.constructor)
}

function is_binary(a) {
  return "string" === typeof a
}

function is_bool(a) {
  return !0 === obj || !1 === obj
}

function is_buffer(a) {
  return "string" === typeof a
}

function count(a, b) {
  var d, c = 0;
  if (null === a || "undefined" === typeof a)
    return 0;
  if (a.constructor !== Array && a.constructor !== Object)
    return 1;
  "COUNT_RECURSIVE" === b && (b = 1);
  1 != b && (b = 0);
  for (d in a)
    a.hasOwnProperty(d) && (c++, 1 != b || (!a[d] || a[d].constructor !== Array && a[d].constructor !== Object) || (c += this.count(a[d], 1)));
  return c
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