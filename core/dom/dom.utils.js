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
var domUtils = function() {
  if (typeof arguments[0] == 'function') {
    if (domUtils._DOMloading <= 0) {
      arguments[0].apply(this)
      return
    }
    domUtils.registeredFuncs.push(arguments[0])
  }
}
Object.assign(domUtils, {
  __description: 'DOM related Jeedom functions.',
  DOMloading: 0,
  _DOMloading: 0,
  loadingTimeout: null,
  ajaxSettings: {
    async: true,
    global: true,
    dataType: 'json',
    type: 'post',
    noDisplayError: false
  },
  registeredEvents: [],
  registeredFuncs: [],
  headInjexted: [],
  controller : new AbortController()
})

window.addEventListener('beforeunload', function(event) {
    domUtils.controller.abort()
})

domUtils.DOMReady = function() {
  domUtils.hideLoading()
  for (var i = 0; i < domUtils.registeredFuncs.length; i++) {
    let f = domUtils.registeredFuncs.shift()
    try {
      f.apply(this)
    } catch (e) { }
  }
}

Object.defineProperty(domUtils, 'DOMloading', {
  enumerable: true,
  get: function() {
    return this._DOMloading
  },
  set: function(number) {
    if (number === 1) domUtils.showLoading()
    this._DOMloading = number < 0 ? 0 : number
    if (this._DOMloading <= 0) {
      domUtils.DOMReady()
    }
  }
})

document.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() { //document.readyState still interactive
    domUtils.DOMloading = domUtils._DOMloading || 0
  }, 200)
})

/* Extension Functions
*/
String.prototype.HTMLFormat = function() {
  return this.replace(/[\u00A0-\u9999<>\&]/g, function(i) {
    return '&#' + i.charCodeAt(0) + ';'
  })
}

String.prototype.stripAccents = function() {
  let in_chrs = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
    out_chrs = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY',
    transl = {}
  let chars_rgx = eval('/[' + in_chrs + ']/g')
  for (let i = 0; i < in_chrs.length; i++) {
    transl[in_chrs.charAt(i)] = out_chrs.charAt(i)
  }
  return this.replace(chars_rgx, function(match) {
    return transl[match]
  })
}

Element.prototype.uniqueId = function(_prefix) {
  if (!isset(_prefix)) _prefix = 'jee-id-'
  this.setAttribute('id', _prefix + Math.floor(Math.random() * Date.now()).toString(16))
  return this
}
domUtils.uniqueId = function(_prefix) {
  if (!isset(_prefix)) _prefix = 'jee-id-'
  return _prefix + Math.floor(Math.random() * Date.now()).toString(16)
}

/* Set and Get element values according to Jeedom data
Must be high performance
*/
Element.prototype.findAtDepth = function(selector, maxDepth) {
  let depths = [], i
  if (maxDepth > 0) {
    for (i = 1; i <= maxDepth; i++) {
      depths.push(':scope > ' + new Array(i).join('* > ') + selector)
    }
    selector = depths.join(', ')
  }
  return this.querySelectorAll(selector)
}

Element.prototype.getJeeValues = function(_attr, _depth) {
  let value = {}
  let idx, depthFound, thatElement, elValue, l1key, l2key, l3key
  let elements = this.findAtDepth(_attr, init(_depth, 0))
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
  let values = [], elValues
  for (let idx = 0; idx < this.length; idx++) {
    elValues = this[idx].getJeeValues(_attr, _depth)
    values.push(elValues[0])
  }
  return values
}

Element.prototype.setJeeValues = function(_object, _attr) {
  let selector
  for (let i in _object) {
    selector = _attr + '[data-l1key="' + i + '"]'
    if ((!is_array(_object[i]) || (this.querySelector(selector) !== null && this.querySelector(selector).getAttribute('multiple') == 'multiple')) && !is_object(_object[i])) {
      this.querySelectorAll(_attr + '[data-l1key="' + i + '"]').jeeValue(_object[i])
    } else {
      for (let j in _object[i]) {
        selector = _attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]'
        if ((is_array(_object[i][j]) || (this.querySelector(selector) !== null && this.querySelector(selector).getAttribute('multiple') == 'multiple')) || is_object(_object[i][j])) {
          for (let k in _object[i][j]) {
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
  for (let idx = 0; idx < this.length; idx++) {
    this[idx].setJeeValues(_object, _attr)
  }
  return this
}

Element.prototype.jeeValue = function(_value) {
  if (isset(_value)) { //SET
    if (this.length > 1 && this.tagName.toLowerCase() != 'select') {
      for (let idx = 0; idx < this.length; idx++) {
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
    let value = ''
    if (this.matches('input, select, textarea')) {
      if (this.getAttribute('type') == 'checkbox' || this.getAttribute('type') == 'radio') {
        value = (this.checked) ? '1' : '0'
      } else {
        value = this.value
      }
    }
    if (this.matches('span')) {
      value = this.innerHTML || this.textContent
      if (value == '') value = null
    }
    if (this.matches('div, p')) {
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
  for (let idx = 0; idx < this.length; idx++) {
    this[idx].jeeValue(_value)
  }
}

domUtils.extend = function(_object /*, _object... */) {
  let extended = {}
  let deep = false
  let i = 0
  let length = arguments.length
  // Check if a deep merge
  if (Object.prototype.toString.call(arguments[0]) === '[object Boolean]') {
    deep = arguments[0]
    i++
  }
  // Merge the object into the extended object
  let merge = function(obj) {
    for (let prop in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, prop)) {
        // If deep merge and property is an object, merge properties
        if (deep && Object.prototype.toString.call(obj[prop]) === '[object Object]') {
          extended[prop] = extend(true, extended[prop], obj[prop])
        } else {
          extended[prop] = obj[prop]
        }
      }
    }
  }
  // Loop through each object and conduct a merge
  for (; i < length; i++) {
    merge(arguments[i])
  }
  return extended
}


/* ____________DOM Injection Management____________
*/
//Parse script tags and recreate them dynamically to be loaded and executed in order
domUtils.loadScript = function(_scripts, _idx, _callback) {
  if (_idx == undefined || _idx === null) _idx = 0
  if (_idx >= _scripts.length) {
    if (typeof _callback === 'function') {
      return _callback()
    }
    return
  }
  var script = document.createElement('script')
  script.type = _scripts[_idx].type || "text/javascript"
  Array.prototype.forEach.call(_scripts[_idx].attributes, function(attr) {
    script.setAttribute(attr.nodeName, attr.nodeValue)
  })
  script.setAttribute('injext', '1')
  if (_scripts[_idx].src != '') {
    //stock in head ?
    if (_scripts[_idx].src.includes('desktop/common/js') || _scripts[_idx].src.includes('/3rdparty/')) {
      if (domUtils.headInjexted.includes(_scripts[_idx].src)) {
        _scripts[_idx].remove()
        domUtils.loadScript(_scripts, _idx + 1, _callback)
      } else {
        domUtils.headInjexted.push(_scripts[_idx].src)
        _scripts[_idx].remove()
        script.onload = function() {
          domUtils.loadScript(_scripts, _idx + 1, _callback)
        }
        document.head.append(script)
      }
    } else {
      script.onload = function() {
        domUtils.loadScript(_scripts, _idx + 1, _callback)
      }
      _scripts[_idx].replaceWith(script)
    }
  } else {
    script.text = _scripts[_idx].text
    _scripts[_idx].replaceWith(script)
    domUtils.loadScript(_scripts, _idx + 1, _callback)
  }
}


//Use new html document to load scripts synch/ordered
domUtils.DOMparseHTML = function(_htmlString) {
  var frag = document.createRange().createContextualFragment(_htmlString)
  var node = null
  var nodeChilds = []
  frag.childNodes.forEach(_child => {
    if (!node && _child.tagName != undefined && _child.tagName !== 'SCRIPT') {
      node = _child
    } else {
      nodeChilds.push(_child)
    }
  })
  if (!node) {
    return null
  }

  if (nodeChilds.length > 0) {
    node.append(...nodeChilds)
  }

  return node
}

//Scripts with src won't load until inserted in DOM
domUtils.parseHTML = function(_htmlString) {
  let newEl = document.createElement('template')
  newEl.innerHTML = _htmlString

  domUtils.DOMloading += 1
  newEl.content.querySelectorAll('script').forEach(function(element) {
    let script = document.createElement('script')
    script.setAttribute('injext', '1')
    element.src != '' ? script.src = element.src : script.text = element.text
    element.replaceWith(script)
  })
  domUtils.DOMloading -= 1

  return newEl.content
}

Element.prototype.html = function(_htmlString, _append, _callback) {
  if (!isset(_htmlString)) return this.innerHTML
  if (!isset(_append) || _append === false) this.empty()
  domUtils.DOMloading += 1
  let template = document.createElement('template')
  template.innerHTML = _htmlString

  //Filter head CSS
  template.content.querySelectorAll('link[rel="stylesheet"]').forEach(stylesheet => {
    if (stylesheet.href.includes('desktop/common/css') || stylesheet.href.includes('/3rdparty/')) {
      if (!domUtils.headInjexted.includes(stylesheet.href)) {
        stylesheet.setAttribute('injext', '1')
        domUtils.headInjexted.push(stylesheet.href)
        document.head.appendChild(stylesheet)
      } else {
        stylesheet.remove()
      }
    }
  })
  this.append(template.content)

  let self = this
  if (this.querySelectorAll('script').length > 0) {
    domUtils.loadScript(this.querySelectorAll('script'), 0, function() {
      domUtils.DOMloading -= 1
      if (typeof _callback === 'function') {
        return _callback.apply(self)
      } else {
        return self
      }
    }, document.head)
  } else {
    domUtils.DOMloading -= 1
    if (typeof _callback === 'function') {
      return _callback.apply(self)
    } else {
      return self
    }
  }
}

Element.prototype.load = function(_path, _callback) {
  let self = this
  domUtils.DOMloading += 1
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
      self.html(rawHtml, false, function() {
        domUtils.DOMloading -= 1
        if (typeof _callback === 'function') {
          _callback(self)
        }
      })
    }
  })
}

/* ____________Ajax Management____________
*/
domUtils.handleAjaxError = function(_request, _status, _error, _params) {
  domUtils.hideLoading()
  var msg = _request + ' : ' + _status + ' /error: ' + _error
  if (isset(_params)) {
    msg += ' /async:' + _params.async + ' /type:' + _params.type + ' /dataType:' + _params.dataType
    if (isset(_params.data) && isset(_params.data.action)) {
      msg += ' /action:' + _params.data.action
    }
  }
  jeedomUtils.showAlert({
    message: msg,
    level: 'warning',
    ttl: 15000
  })
}

domUtils.ajaxSetup = function(_params) {
  for (const key in _params) {
    domUtils.ajaxSettings[key] = _params[key]
  }
}

/*Handle nested multi-level js object to query string
*/
domUtils.getUrlString = function(params, keys = [], isArray = false) {
  const p = Object.keys(params).map(key => {
    let val = params[key]
    if ("[object Object]" === Object.prototype.toString.call(val) || Array.isArray(val)) {
      if (Array.isArray(params)) {
        keys.push('')
      } else {
        keys.push(key)
      }
      return domUtils.getUrlString(val, keys, Array.isArray(val))
    } else {
      let tKey = key
      if (keys.length > 0) {
        const tKeys = isArray ? keys : [...keys, key]
        tKey = tKeys.reduce((str, k) => { return '' === str ? k : `${str}[${k}]` }, '')
      }
      if (isArray) {
        return `${tKey}[]=${encodeURIComponent(val)}`
      } else {
        return `${tKey}=${encodeURIComponent(val)}`
      }
    }
  }).join('&')
  keys.pop()
  return p
}



domUtils.ajax = function(_params) {
  _params.global = isset(_params.global) ? _params.global : domUtils.ajaxSettings.global
  _params.async = isset(_params.async) ? _params.async : domUtils.ajaxSettings.async
  _params.dataType = isset(_params.dataType) ? _params.dataType : domUtils.ajaxSettings.dataType
  _params.type = isset(_params.type) ? _params.type : domUtils.ajaxSettings.type
  _params.noDisplayError = isset(_params.noDisplayError) ? _params.noDisplayError : domUtils.ajaxSettings.noDisplayError
  _params.success = (typeof _params.success === 'function') ? _params.success : function() { return arguments }
  _params.complete = (typeof _params.complete === 'function') ? _params.complete : function() { }
  _params.onError = (typeof _params.error === 'function') ? _params.error : null
  _params.timeoutRetry = isset(_params.timeoutRetry) ? _params.timeoutRetry : 0
  _params.processData = isset(_params.processData) ? _params.processData : true

  if (_params.global) domUtils.DOMloading += 1

  let isGet = _params.type.toLowerCase() == 'get' ? true : false
  let isJson = _params.dataType.toLowerCase() == 'json' ? true : false

  var sendData = _params.data
  var postHeaders = new Headers()
  if (isset(_params.data) && isJson && _params.processData === true) {
    sendData = domUtils.getUrlString(_params.data)
    sendData = new URLSearchParams(sendData)
    postHeaders = { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }
  }

  if (_params.async === false) { //Synchronous request:
    const request = new XMLHttpRequest()
    request.open(_params.type, _params.url, false)
    request.send(new URLSearchParams(_params.data))
    if (request.status === 200) { //Answer ok
      if (_params.global) domUtils.DOMloading -= 1
      isJson ? _params.success(JSON.parse(request.responseText)) : _params.success(request.responseText)
    } else { //Weird thing happened
      if (_params.global) domUtils.DOMloading -= 1
      domUtils.handleAjaxError(request, request.status, request.responseText)
      if (_params.onError) _params.onError(request)
    }
    _params.complete()
  } else { //Asynchronous request:
    let url = _params.url
    if (isGet && is_object(_params.data)) {
      url = url + '?' + new URLSearchParams(_params.data)
    }
    fetch(url, {
      method: _params.type,
      body: isGet ? null : sendData,
      headers: isGet ? new Headers() : postHeaders,
      redirect: 'follow',
      referrerPolicy: 'no-referrer',
      mode: 'cors',
      credentials: 'same-origin',
	    signal: domUtils.controller.signal,
      //Safari AbortSignal.timeout not a function
      //signal: (_params.url == 'core/ajax/event.ajax.php' && _params.data.action == 'changes') ? null : AbortSignal.timeout(10000) //changes polling!
    })
      .then(response => {
        if (!response.ok) {
          if (_params.global) domUtils.DOMloading -= 1
          throw response
        }
        if (isJson) {
          return response.json()
        } else {
          return response.text()
        }
      })
      .then(obj => {
        return _params.success(obj)
      }).then(async function() {
        if (_params.global) domUtils.DOMloading -= 1
        _params.complete()
        return
      })
      .catch(error => {
        if(domUtils.controller.signal.aborted){
          return;
        }  
        if (_params.global) domUtils.DOMloading -= 1
        if (typeof error.text === 'function') { //Catched from fetch return
          error.text().then(errorMessage => {
            if ((error.status == 504 || error.status == 500 || (error.status == 502 && error.headers.get('server') == 'openresty'))) { //Gateway Timeout or Internal Server Error
              //Timeout, retry:
              if (_params.timeoutRetry < 3) {
                _params.timeoutRetry++
                setTimeout(() => {
                  domUtils.ajax(_params)
                }, 100)
                return
              } else {
                console.warn('[Timeout Error], retried two times:', error, errorMessage, _params)
              }
            } else {
              console.warn('[Bad Fetch response]', error, errorMessage, _params)
            }
          })
        } else { //Catched from fetch error
          if (error.code == 20) return //NetworkError when attempting to fetch resource.
          if (error.name == 'TypeError' && error.message.includes('NetworkError')) return //The operation was aborted.
          if (error.name == 'TypeError') {
            console.warn('Error JS > TypeError > ', error)
          }
          if (error.name == 'ReferenceError') {
            console.warn('Error Reference > ReferenceError > ' + error)
          }
          if (!_params.noDisplayError) {
            domUtils.handleAjaxError(_params.url, error.name, error.message, _params)
          }
          if (_params.onError) {
            _params.onError(error)
          }

          //Alpha test code:
          /*
          console.warn('[Fetch Server Error]', 'name:', error.name, ' | message:', error.message, ' | code:', error.code, ' | request:', _params.url, ' | action:', _params.data.action, _params)
          console.dir(error)
          domUtils.handleAjaxError(_params.url, error.name, error.message, _params)
          */
        }
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
domUtils.unRegisterEvents = function() {
  for (let listener of domUtils.registeredEvents) {
    listener.element.removeEventListener(listener.type, listener.callback, false)
  }
  domUtils.registeredEvents = []
}

EventTarget.prototype.registerEvent = function(_type, _listener, _options) {
  //To be removed, removeEventListener need same EventTarget, callback, capture setting
  if (typeof _listener !== 'function') return
  domUtils.registeredEvents.push({
    element: this,
    type: _type,
    id: _listener.name || '',
    callback: _listener,
    options: _options
  })
  this.addEventListener(_type, _listener, _options)
  return this
}

EventTarget.prototype.unRegisterEvent = function(_type, _id) {
  var that = this
  var listeners = domUtils.registeredEvents.filter(function(listener) {
    return ((isset(_type) ? listener.type == _type : true) && (isset(_id) ? listener.id == _id : true) && listener.element == that)
  })
  for (var listener of listeners) {
    var result = that.removeEventListener(listener.type, listener.callback, listener.options)
    domUtils.registeredEvents = domUtils.registeredEvents.filter(ev => !listeners.includes(ev))
  }
  return that
}

EventTarget.prototype.getRegisteredEvent = function(_type, _id) {
  let self = this
  let listeners = domUtils.registeredEvents.filter(function(listener) {
    return ((isset(_type) ? listener.type == _type : true) && (isset(_id) ? listener.id == _id : true) && listener.element == self)
  })
  return listeners
}

EventTarget.prototype.triggerEvent = function(_eventName, _params) {
  if (!isset(_params)) {
    _params = {}
  }
  _params.bubbles = _params.bubbles || true
  _params.cancelable = _params.cancelable || false
  _params.detail = _params.detail || undefined
  let event = new CustomEvent(_eventName, _params)
  this.dispatchEvent(event)
  return this
}
NodeList.prototype.triggerEvent = function(_eventName, _params) {
  for (let idx = 0; idx < this.length; idx++) {
    this[idx].triggerEvent(_eventName, _params)
  }
  return this
}

domUtils.octetsToHumanSize = function(_size) {
  _size = Math.abs(parseInt(_size, 10))
  var def = [[1, 'octets'], [1024, 'Ko'], [1024 * 1024, 'Mo'], [1024 * 1024 * 1024, 'Go'], [1024 * 1024 * 1024 * 1024, 'To']]
  for (var i = 0; i < def.length; i++) {
    if (_size < def[i][0]) return (_size / def[i - 1][0]).toFixed(2) + ' ' + def[i - 1][1]
  }

}

/*Global window functions
*/
function isElement_jQuery(_element) {
  if (typeof jQuery !== 'function') return false
  return (_element instanceof jQuery && _element.length)
}

function isElement_DOM(_element) {
  return (_element instanceof HTMLElement)
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

function isset() {
  let a = arguments,
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

function getNearestMultiple(_value, _factor, _method) {
  if (!_factor) return _value
  _method = _method || 'round'
  return Math[_method](_value / _factor) * _factor
}

function json_decode(a) {
  let b = window.JSON
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
  let b, d = window.JSON
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

function isInWindow(_el) {
  var { top, bottom } = _el.getBoundingClientRect()
  var vHeight = (window.innerHeight || document.documentElement.clientHeight)
  return (
    (top > 0 || bottom > 0) &&
    top < vHeight
  )
}

function getBool(val) {
  if (val === undefined) return false
  var num = +val
  return !isNaN(num) ? !!num : !!String(val).toLowerCase().replace(!!0, '')
}

//Prefer [array].includes()
function in_array(a, b, d) {
  let c = ""
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

function isPlainObject(obj) {
  return Object.prototype.toString.call(obj) === '[object Object]'
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

//Prefer Array.isArray(a)
function is_array(a) {
  let b, d = function(a) {
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
  let d, c = 0
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
