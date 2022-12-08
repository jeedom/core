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

/* DOM utils namespace
*/
var domUtils = {
  __description: 'DOM related Jeedom functions.'
}
//jeedomUtils not loaded before first ajax calls:
domUtils.ajaxCalling = 0
domUtils.loadingTimeout = null
domUtils.showLoading = function() {
  document.getElementById('div_jeedomLoading')?.seen()
  //Hanging timeout:
  clearTimeout(domUtils.loadingTimeout)
  domUtils.loadingTimeout = setTimeout(() => {
    if (!document.getElementById('div_jeedomLoading')?.isHidden()) {
      domUtils.hideLoading()
      domUtils.ajaxCalling --
      if (jeedomUtils) jeedomUtils.showAlert({level: 'danger', message: 'Operation Timeout: Something has gone wrong!'})
    }
  }, 20 * 1000)
}
domUtils.hideLoading = function() {
  document.getElementById('div_jeedomLoading')?.unseen()
}


/* Extension Functions
*/
String.prototype.HTMLFormat = function() {
  return this.replace(/[\u00A0-\u9999<>\&]/g, function(i) {
    return '&#' + i.charCodeAt(0) + ';'
  })
}

String.prototype.stripAccents = function() {
  var in_chrs = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
    out_chrs = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY',
    transl = {}
  var chars_rgx = eval('/[' + in_chrs + ']/g')
  for (var i = 0; i < in_chrs.length; i++) {
    transl[in_chrs.charAt(i)] = out_chrs.charAt(i)
  }
  return this.replace(chars_rgx, function(match) {
    return transl[match]
  })
}

EventTarget.prototype.triggerEvent = function(_eventName, _params) {
  if (!isset(_params)) {
    _params = {}
  }
  _params.bubbles = _params.bubbles || true
  _params.cancelable = _params.cancelable || false
  _params.detail = _params.detail || undefined

  var event = new Event(_eventName, _params)
  this.dispatchEvent(event)

  return this
}
NodeList.prototype.triggerEvent = function(_eventName, _params) {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].triggerEvent(_eventName, _params)
  }
  return this
}

/* Shortcuts Functions
*/
//Hide Show as seen(), unseen() as prototype show/hide are ever declared and fired by bootstrap and jquery
Element.prototype.isVisible = function() {
  return this.offsetWidth > 0 || this.offsetHeight > 0 || this.getClientRects().length > 0
}
Element.prototype.isHidden = function() {
  return (this.offsetParent === null)
}
Element.prototype.seen = function() {
  this.style.display = ''
  return this
}
NodeList.prototype.seen = function() {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].seen()
  }
  return this
}
Element.prototype.unseen = function() {
  this.style.display = 'none'
  return this
}
NodeList.prototype.unseen = function() {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].unseen()
  }
  return this
}

Element.prototype.empty = function() {
  while (this.firstChild) {
    this.removeChild(this.lastChild)
  }
  return this
}
NodeList.prototype.empty = function() {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].empty()
  }
  return this
}
Document.prototype.emptyById = function(_id) {
  if (_id == '') return
  if (!(_id instanceof Element)) {
    var _id = document.getElementById(_id)
  }
  if (_id) {
    return _id.empty()
  }
  return null
}

//CSS Class manipulation
Element.prototype.addClass = function(_className /*, _className... */) {
  if (_className == '') return this
  var args = Array.prototype.slice.call(arguments)
  this.classList.add(...args)
  return this
}
NodeList.prototype.addClass = function(_className /*, _className... */) {
  if (_className == '') return this
  var args = Array.prototype.slice.call(arguments)
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].addClass(...args)
  }
  return this
}

Element.prototype.removeClass = function(_className /*, _className... */) {
  if (_className == '') return this
  var args = Array.prototype.slice.call(arguments)
  this.classList.remove(...args)
  return this
}
NodeList.prototype.removeClass = function(_className /*, _className... */) {
  if (_className == '') return this
  var args = Array.prototype.slice.call(arguments)
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].removeClass(...args)
  }
  return this
}

Element.prototype.toggleClass = function(_className) {
  this.classList.toggle(_className)
  return this
}
NodeList.prototype.toggleClass = function() {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].toggleClass()
  }
  return this
}

Element.prototype.hasClass = function(_className) {
  return this.classList.contains(_className)
}


//Misc
NodeList.prototype.last = function() {
  return Array.from(this).pop()
}

NodeList.prototype.remove = function() {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].remove()
  }
  return this
}

Element.prototype.fade = function(_delayms, _opacity, _callback) {
  var opacity = parseInt(this.style.opacity) || 0
  var interval = 25,
      gap = interval / _delayms,
      delay = 0,
      self = this

  if (opacity > _opacity) gap = gap * -1

  var func = function() {
    let stop = false
    delay += interval
    opacity = opacity + gap
    if (gap > 0 && opacity >= _opacity) {
      opacity = _opacity
      stop = true
    }
    if (gap < 0 && opacity <= 0) {
      opacity = 0
      self.unseen()
      stop = true
    }
    self.style.opacity = opacity
    if (stop) {
      window.clearInterval(fading)
      if (typeof _callback === 'function') {
        _callback()
      }
    }
  }

  self.seen()
  var fading = window.setInterval(func, interval)
  return this
}

Element.prototype.insertAtCursor = function(_valueString) {
  if (this.selectionStart || this.selectionStart == '0') {
    var startPos = this.selectionStart
    var endPos = this.selectionEnd
    this.value = this.value.substring(0, startPos) + _valueString + this.value.substring(endPos, this.value.length)
  } else {
    this.value += _valueString
  }
  return this
}



/* Set and Get element values according to Jeedom data
Must be high performance
*/
Element.prototype.findAtDepth = function(selector, maxDepth) {
  var depths = [], i
  if (maxDepth > 0) {
    for (i = 1; i <= maxDepth; i++) {
      depths.push(':scope > ' + new Array(i).join('* > ') + selector)
    }
    selector = depths.join(', ')
  }
  return this.querySelectorAll(selector)
}

Element.prototype.getJeeValues = function(_attr, _depth) {
  var value = {}
  var idx, value, depthFound, thatElement, elValue, l1key, l2key, l3key
  var elements = this.findAtDepth(_attr, init(_depth, 0))
  for (idx = 0; idx < elements.length; idx++) {
    thatElement = elements[idx]
    elValue = thatElement.jeeValue()
    try {
      if (elValue.trim().substr(0, 1) == '{') {
        elValue = JSON.parse(thatElement.jeeValue())
      }
    } catch (e) { }

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
NodeList.prototype.getJeeValues = function(_attr, _depth) {
  var values = [], elValues
  for (var idx = 0; idx < this.length; idx++) {
    elValues = this[idx].getJeeValues(_attr, _depth)
    values.push(elValues[0])
  }
  return values
}

Element.prototype.setJeeValues = function(_object, _attr) {
  var selector
  for (var i in _object) {
    selector = _attr + '[data-l1key="' + i + '"]'
    if ((!is_array(_object[i]) || (this.querySelector(selector) !== null && this.querySelector(selector).getAttribute('multiple') == 'multiple')) && !is_object(_object[i])) {
      this.querySelectorAll(_attr + '[data-l1key="' + i + '"]').jeeValue(_object[i])
    } else {
      for (var j in _object[i]) {
        selector = _attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]'
        if ((is_array(_object[i][j]) || (this.querySelector(selector) !== null && this.querySelector(selector).getAttribute('multiple') == 'multiple')) || is_object(_object[i][j])) {
          for (var k in _object[i][j]) {
            this.querySelectorAll(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"][data-l3key="' + k + '"]').jeeValue(_object[i][j][k])
          }
        } else {
          this.querySelectorAll(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]').jeeValue(_object[i][j])
        }
      }
    }
  }
  return this
}
NodeList.prototype.setJeeValues = function(_object, _attr) {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].setJeeValues(_object, _attr)
  }
  return this
}

Element.prototype.jeeValue = function(_value) {
  if (isset(_value)) { //SET
    if (this.length > 1 && this.tagName.toLowerCase() != 'select') {
      var idx
      for (idx = 0; idx < this.length; idx++) {
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
    return this
  } else { //GET
    var value = ''
    if (this.matches('input, select, textarea')) {
      if (this.getAttribute('type') == 'checkbox' || this.getAttribute('type') == 'radio') {
        value = (this.checked) ? '1' : '0'
      } else {
        value = this.value
      }
    }
    if (this.matches('div, span, p')) {
      value = this.innerHTML || null
    }
    if (this.matches('a') && this.getAttribute('value') != undefined) {
      value = this.getAttribute('value')
    }
    if (value == '') {
      value = this.value
    }
    return value
  }
}
NodeList.prototype.jeeValue = function(_value) {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].jeeValue(_value)
  }
}

domUtils.extend = function(_object /*, _object... */) {
  var extended = {}
  var deep = false
  var i = 0
  var length = arguments.length

  // Check if a deep merge
  if ( Object.prototype.toString.call( arguments[0] ) === '[object Boolean]' ) {
    deep = arguments[0]
    i++
  }

  // Merge the object into the extended object
  var merge = function (obj) {
    for ( var prop in obj ) {
      if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
        // If deep merge and property is an object, merge properties
        if ( deep && Object.prototype.toString.call(obj[prop]) === '[object Object]' ) {
          extended[prop] = extend( true, extended[prop], obj[prop] )
        } else {
          extended[prop] = obj[prop]
        }
      }
    }
  }

  // Loop through each object and conduct a merge
  for ( ; i < length; i++ ) {
    var obj = arguments[i]
    merge(obj)
  }

  return extended
}



//DOM appended element with script tag (template widget, scenario etc) aren't executed
domUtils.element2dom = function(_element, _container) {
  _element.childNodes.forEach(function(element) {
    if (element !== undefined && element.nodeName === 'SCRIPT') {
      var script = document.createElement('script')
      if (element.type) {
        script.type = element.type
      } else {
        script.type = "text/javascript"
      }
      Array.prototype.forEach.call(element.attributes, function(attr) {
        script.setAttribute(attr.nodeName, attr.nodeValue)
      })
      if (element.src != '') {
        if (element.src.includes('getJS.php?file=')) {
          var sourceCode = domUtils.ajax({
            url: element.src,
            async: false,
            type: 'get',
            dataType: 'html',
            global: true
          })
          script.removeAttribute('src')
          script.setAttribute('data-from', element.src)
          script.text = sourceCode
          var parent = element.parentNode
          element.remove()
          parent.appendChild(script)
        } else {
          script.onload = async function() {
            domUtils.element2dom(element, _container)
          }
          _container.appendChild(script)
        }
      } else {
        script.text = element.text
        element.replaceWith(script)
      }
    } else if (element !== undefined && element.childNodes.length) {
       domUtils.element2dom(element, _container)
    }
  })
  return _element
}
domUtils.parseHTML = function(_htmlString) {
  var documentFragment = document.createDocumentFragment()
  var newEl = document.createElement('span')
  newEl.innerHTML = _htmlString
  var newFilteredEl = domUtils.element2dom(newEl, documentFragment)
  documentFragment.appendChild(newFilteredEl)
  newFilteredEl.replaceWith(...newFilteredEl.childNodes)
  return documentFragment
}
Element.prototype.html = function(_htmlString, _append) {
  //get ?
  if (!isset(_htmlString)) return this.innerHTML

  if (!isset(_append) || _append === false) this.empty()
  this.appendChild(domUtils.parseHTML(_htmlString))
  return this
}

Element.prototype.load = function(_path, _callback) {
  var self = this

  domUtils.ajax({
    url: _path,
    async: domUtils.ajaxSettings.async,
    type: 'get',
    global: domUtils.ajaxSettings.global,
    dataType: 'html',
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(rawHtml) {
      self.html(rawHtml)
      if (typeof _callback === 'function') {
        _callback()
      }
    }
  })
}



/* ____________Ajax Management____________
*/
domUtils.handleAjaxError = function(_request, _status, _error) {
  domUtils.hideLoading()
  if (_request.status != '0') {
    if (init(_request.responseText, '') != '') {
      jeedomUtils.showAlert({
        message: _request.responseText,
        level: 'danger'
      })
    } else {
      jeedomUtils.showAlert({
        message: _request.status + ' : ' + _error,
        level: 'danger'
      })
    }
  }
}
domUtils.ajaxSettings = {
  async: true,
  global: true,
  dataType: 'json',
  type: 'post',
  error_callback: domUtils.handleAjaxError
}
domUtils.ajaxSetup = function(_params) {
  for (const key in _params) {
    domUtils.ajaxSettings[key] = _params[key]
  }
}
domUtils.countAjax = function(_type, _global) {
  if (_global === false) return
  if (_type == 0) {
    domUtils.ajaxCalling ++
    if (domUtils.ajaxCalling == 1) domUtils.showLoading()
  } else {
    domUtils.ajaxCalling --
    if (domUtils.ajaxCalling <= 0) domUtils.hideLoading()
  }
  //Should not but may:
  if (domUtils.ajaxCalling < 0) {
    domUtils.ajaxCalling = 0
    domUtils.hideLoading()
  }
}
domUtils.ajax = function(_params) {
  _params.global = isset(_params.global) ? _params.global : domUtils.ajaxSettings.global
  _params.async = isset(_params.async) ? _params.async : domUtils.ajaxSettings.async
  _params.dataType = isset(_params.dataType) ? _params.dataType : domUtils.ajaxSettings.dataType
  _params.type = isset(_params.type) ? _params.type : domUtils.ajaxSettings.type
  _params.success = (typeof _params.success === 'function') ? _params.success : function() {return arguments}
  _params.complete = (typeof _params.complete === 'function') ? _params.complete : function() {return arguments}
  _params.onError = (typeof _params.error === 'function') ? _params.error : domUtils.ajaxSettings.error_callback

  domUtils.countAjax(0, _params.global)

  var isGet = _params.type.toLowerCase() == 'get' ? true : false
  var isJson = _params.dataType.toLowerCase() == 'json' ? true : false

  if (_params.async === false) { //Synchronous request:
    const request = new XMLHttpRequest()
    request.open(_params.type, _params.url, false)
    request.send(new URLSearchParams(_params.data))
    if (request.status === 200) { //Answer ok
      domUtils.countAjax(1, _params.global)
      isJson ? _params.success(JSON.parse(request.responseText)) : _params.success(request.responseText)
    } else { //Weird thing happened
      domUtils.countAjax(1, _params.global)
      _params.onError('', '', error)
    }
    _params.complete()
  } else { //Asynchronous request:
    var url = _params.url
    var request
    if (isGet && is_object(_params.data)) {
      url = url + '?' + new URLSearchParams(_params.data)
    }
    fetch(url, {
      method: _params.type,
      body: isGet ? null : new URLSearchParams(_params.data),
      headers: isGet ? new Headers() : {"Content-Type": "application/x-www-form-urlencoded"}
    })
    .then(function(response) {
      if (!response.ok) {
        _params.onError(response, response.status, response.statusText)
        return
      }
      if (isJson) {
        return response.json()
      } else {
        return response.text()
      }
    })
    .then(function(obj) {
      return _params.success(obj)
    }).then(async function() {
      domUtils.countAjax(1, _params.global)
      return _params.complete()
    })
    .catch(function(error) {
      domUtils.countAjax(1, _params.global)
      _params.onError('', '', error)
    })
  }
}






/* ____________Listeners Management____________
All events on #div_pageContainer and underneath are removed at jeedomUtils.loadPage() (div_pageContainer empty and cloned):

Events higher in DOM will persist if not referenced and removed at loadPage()

Usage:
>> window.registerEvent("resize", function() {console.log('page resized')})
<< window.unRegisterEvent('resize') //will remove all set resize events

>> document.getElementById('div_mainContainer').registerEvent('scroll', function timelineAutoLoad(event) {console.log('page scroll')})
<< document.getElementById('div_mainContainer').unRegisterEvent('scroll', 'timelineAutoLoad') // remove only this listener for scroll

<< domUtils.unRegisterEvents() implemented at jeedomUtils.loadPage()!
*/
domUtils.registeredEvents = []
domUtils.unRegisterEvents = function() {
  for (var listener of domUtils.registeredEvents) {
    listener.element.removeEventListener(listener.type, listener.callback, false)
  }
  domUtils.registeredEvents = []
}
EventTarget.prototype.registerEvent = function(_type, listener) {
  if (typeof listener !== 'function') return
  domUtils.registeredEvents.push({
    element: this,
    type: _type,
    id: listener.name || '',
    callback: listener
  })
  this.addEventListener(_type, listener)
  return this
}
EventTarget.prototype.unRegisterEvent = function(_type, _id) {
  var self = this
  var listeners = domUtils.registeredEvents.filter(function(listener) {
    return ( (_type? listener.type == _type : true) && (_id? listener.id == _id : true) && listener.element == self )
  })

  for (var listener of listeners) {
    this.removeEventListener(listener.type, listener.callback, false)
    domUtils.registeredEvents = domUtils.registeredEvents.filter(ev => !listeners.includes(ev))
  }

  return this
}


/* Widgets
*/
domUtils.issetWidgetOptParam = function(_def, _param) {
  if (_def != '#' + _param + '#') return true
  return false
}

domUtils.createWidgetSlider = function(_options) {
  var createOptions = {
    start: [_options.state],
    connect: [true, false],
    step: _options.step,
    range: {
      'min': _options.min,
      'max': _options.max
    },
    tooltips: _options.tooltips
  }

  if (isset(_options.format) && _options.format == true) {
    createOptions.format = {
      from: Number,
      to: function(value) {
        var dec = _options.step.toString().includes('.') ? (_options.step.toString().length - 1) - _options.step.toString().indexOf('.') : 0
        return ((Math.round(value * (100 / _options.step)) / (100 / _options.step)).toFixed(dec) + ' ' + _options.unite).trim()
      }
    }
  }

  if (isset(_options.vertical) && _options.vertical == true) {
    createOptions.orientation = 'vertical'
    createOptions.direction = 'rtl'
  }

  return noUiSlider.create(_options.sliderDiv, createOptions)
}


//Global functions
function isElement_jQuery(_element) {
  return (_element instanceof jQuery && _element.length)
}

function isElement_DOM(_element) {
  return (_element instanceof HTMLElement)
}

function in_array(a, b, d) {
  var c = ""
  if (d)
    for (c in b) {
      if (b[c] === a)
        return !0
    }
  else
    for (c in b)
      if (b[c] == a)
        return !0
  return !1
}

function json_decode(a) {
  var b = window.JSON
  if ("object" === typeof b && "function" === typeof b.parse)
    try {
      return b.parse(a)
    } catch (d) {
      if (!(d instanceof SyntaxError))
        throw Error("Unexpected error type in json_decode()")
      window.php_js = window.php_js || {}
      window.php_js.last_error_json = 4
      return null
    }
  b = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g
  b.lastIndex = 0
  b.test(a) && (a = a.replace(b, function(a) {
    return "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
  }))
  if (/^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, "")))
    return a = eval("(" + a + ")")
  window.php_js = window.php_js || {}
  window.php_js.last_error_json = 4
  return null
}

function json_encode(a) {
  var b, d = window.JSON
  try {
    if ("object" === typeof d && "function" === typeof d.stringify) {
      b = d.stringify(a)
      if (void 0 === b)
        throw new SyntaxError("json_encode")
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
        }
      b.lastIndex = 0
      return b.test(a) ? '"' + a.replace(b, function(a) {
        var b = c[a]
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
          l = b[a]
        l && ("object" === typeof l && "function" === typeof l.toJSON) && (l = l.toJSON(a))
        switch (typeof l) {
          case "string":
            return c(l)
          case "number":
            return isFinite(l) ? String(l) : "null"
          case "boolean":
          case "null":
            return String(l)
          case "object":
            if (!l)
              return "null"
            if (window.PHPJS_Resource && l instanceof window.PHPJS_Resource || window.PHPJS_Resource && l instanceof window.PHPJS_Resource)
              throw new SyntaxError("json_encode")
            d += "    "
            k = []
            if ("[object Array]" === Object.prototype.toString.apply(l)) {
              m = l.length
              for (f = 0; f < m; f += 1)
                k[f] = e(f, l) || "null"
              return m = 0 === k.length ? "[]" : d ? "[\n" + d + k.join(",\n" + d) + "\n" + s + "]" : "[" + k.join(",") + "]"
            }
            for (f in l)
              Object.hasOwnProperty.call(l, f) && (m = e(f, l)) && k.push(c(f) + (d ? ": " : ":") + m)
            return m = 0 === k.length ? "{}" : d ? "{\n" + d + k.join(",\n" + d) + "\n" + s + "}" : "{" + k.join(",") + "}"
          default:
            throw new SyntaxError("json_encode")
        }
      }
    return e("", {
      "": a
    })
  } catch (f) {
    if (!(f instanceof SyntaxError))
      throw Error("Unexpected error type in json_encode()")
    window.php_js = window.php_js || {}
    window.php_js.last_error_json = 4
    return null
  }
}

function isset() {
  var a = arguments,
    b = a.length,
    d = 0
  if (0 === b)
    throw Error("Empty isset")
  for (; d !== b;) {
    if (void 0 === a[d] || null === a[d])
      return !1
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
    return !1
  for (var b = [], d = RegExp("[\ud800-\udbff]([sS])", "g"), c = RegExp("([sS])[\udc00-\udfff]", "g"), e = RegExp("^[\udc00-\udfff]$"), f = RegExp("^[\ud800-\udbff]$"); null !== (b = d.exec(a));)
    if (!b[1] || !b[1].match(e))
      return !1
  for (; null !== (b = c.exec(a));)
    if (!b[1] || !b[1].match(f))
      return !1
  return !0
}

function is_array(a) {
  var b, d = function(a) {
    return (a = /\W*function\s+([\w\$]+)\s*\(/.exec(a)) ? a[1] : "(Anonymous)"
  }
  if (!a || "object" !== typeof a)
    return !1
  window.php_js = window.php_js || {}
  window.php_js.ini = window.php_js.ini || {}
  b = window.php_js.ini["phpjs.objectsAsArrays"]
  return function(a) {
    if (!a || "object" !== typeof a || "number" !== typeof a.length)
      return !1
    var b = a.length
    a[a.length] = "bogus"
    if (b !== a.length)
      return a.length -= 1, !0
    delete a[a.length]
    return !1
  }(a) || (!b || 0 !== parseInt(b.local_value, 10) && (!b.local_value.toLowerCase ||
    "off" !== b.local_value.toLowerCase())) && "[object Object]" === Object.prototype.toString.call(a) && "Object" === d(a.constructor)
}

function is_binary(a) {
  return "string" === typeof a
}

function is_bool(a) {
  return 0 === a || 1 === a
}

function is_buffer(a) {
  return "string" === typeof a
}

function count(a, b) {
  var d, c = 0
  if (null === a || "undefined" === typeof a)
    return 0
  if (a.constructor !== Array && a.constructor !== Object)
    return 1
  "COUNT_RECURSIVE" === b && (b = 1)
  1 != b && (b = 0)
  for (d in a)
    a.hasOwnProperty(d) && (c++, 1 != b || (!a[d] || a[d].constructor !== Array && a[d].constructor !== Object) || (c += this.count(a[d], 1)))
  return c
}

function init(_value, _default) {
  if (!isset(_default)) {
    _default = ''
  }
  if (!isset(_value)) {
    return _default
  }
  return _value
}