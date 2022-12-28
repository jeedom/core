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

/* jeeDOM UI functionnalities
*/

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
  clearTimeout(domUtils.loadingTimeout)
}


/* Shortcuts Functions
*/
//Hide Show as seen(), unseen() as prototype show/hide are ever declared and fired by bootstrap and jquery
Element.prototype.isVisible = function() {
  return this.offsetWidth > 0 || this.offsetHeight > 0 || this.getClientRects().length > 0 || this.offsetParent != null
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
  let args = Array.prototype.slice.call(arguments)
  this.classList.add(...args)
  return this
}
NodeList.prototype.addClass = function(_className /*, _className... */) {
  if (_className == '') return this
  let args = Array.prototype.slice.call(arguments)
  for (let idx = 0; idx < this.length; idx++) {
    this[idx].addClass(...args)
  }
  return this
}

Element.prototype.removeClass = function(_className /*, _className... */) {
  if (_className == '') return this
  let args = Array.prototype.slice.call(arguments)
  this.classList.remove(...args)
  return this
}
NodeList.prototype.removeClass = function(_className /*, _className... */) {
  if (_className == '') return this
  let args = Array.prototype.slice.call(arguments)
  for (let idx = 0; idx < this.length; idx++) {
    this[idx].removeClass(...args)
  }
  return this
}

Element.prototype.toggleClass = function(_className) {
  this.classList.toggle(_className)
  return this
}
NodeList.prototype.toggleClass = function() {
  for (let idx = 0; idx < this.length; idx++) {
    this[idx].toggleClass()
  }
  return this
}

Element.prototype.hasClass = function(_className) {
  return this.classList.contains(_className)
}


//Misc
NodeList.prototype.last = function() {
  return Array.from(this).pop() || null
}

NodeList.prototype.remove = function() {
  for (let idx = 0; idx < this.length; idx++) {
    this[idx].remove()
  }
  return this
}

Element.prototype.fade = function(_delayms, _opacity, _callback) {
  let opacity = parseInt(this.style.opacity) || 0
  let interval = 50,
      gap = interval / _delayms,
      delay = 0,
      self = this

  if (opacity > _opacity) gap = gap * -1

  let func = function() {
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
    this.value = this.value.substring(0, this.selectionStart) + _valueString + this.value.substring(this.selectionEnd, this.value.length)
  } else {
    this.value += _valueString
  }
  return this
}



Element.prototype.closestAll = function(_selector) {
  //var parents = this.parentNode.querySelectorAll(':scope > :nth-child(' + Array.from(this.parentNode.children).indexOf(this) + 1 +')') //Empty nodeList
  var parents = []
  var parent = this.closest(_selector)
  while (parent != null) {
    parents.push(parent)
    parent = parent.parentNode.closest(_selector)
  }
  return parents
}


/* Widgets
*/
domUtils.issetWidgetOptParam = function(_def, _param) {
  if (_def != '#' + _param + '#') return true
  return false
}

domUtils.createWidgetSlider = function(_options) {
  try {
    if (_options.sliderDiv.hasClass('slider') && _options.sliderDiv.noUiSlider) {
      _options.sliderDiv.noUiSlider.destroy()
    }
  } catch(error) { }

  let createOptions = {
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
        let dec = _options.step.toString().includes('.') ? (_options.step.toString().length - 1) - _options.step.toString().indexOf('.') : 0
        return ((Math.round(value * (100 / _options.step)) / (100 / _options.step)).toFixed(dec) + ' ' + _options.unite).trim()
      }
    }
  }

  if (isset(_options.vertical) && _options.vertical == true) {
    createOptions.orientation = 'vertical'
    createOptions.direction = 'rtl'
  }

  try {
    return noUiSlider.create(_options.sliderDiv, createOptions)
  } catch(error) { }
}


/*Autocomplete inputs
  If several inputs share same autocomplete (same options), set un id on call options so they all share same container.
  Each input has their own focus/blut/keyup/keydown event (keydown prevent arrow up/down moving selection to input start/end)
  As autocomplete container can have multiple reference input, it has only one click listerner, and transit some data:
    ._jeeComplete.reference : current focused input
    ._jeeComplete.references : all inputs using this container
    ._jeeComplete.request : the current _options.request to set current input value
  */
HTMLInputElement.prototype.jeeComplete = function(_options) {
  var defaultOptions = {
    ignoreKeyCodes: [8, 13, 16, 17, 18, 27, 46],
    zIndex: 5000,
    minLength: 1,
    forceSingle: false,
    id: false,
    data: {
      value: null,
      text: null,
      item: this,
      content: null,
      container: null,
    },
    _source: function(request) {
      if (typeof _options.source === 'function') {
        _options.data.content = _options.source(request, _options._response)
      } else {
        var matches = []
        var term = jeedomUtils.normTextLower(request.term)
        _options.sourceAr.forEach(_pair => {
          if (jeedomUtils.normTextLower(_pair.value).includes(term)) {
            matches.push(_pair)
          }
        })
        _options._response(matches)
      }
    },
    _response: function(matches) {
      if (matches === false) return

      var matchesAr = []
      if (Array.isArray(matches) && matches.length > 0) {
        if (!is_object(matches[0] )) {
          matches.forEach(_src => {
            matchesAr.push({text: _src, value: _src})
          })
          matches = matchesAr
        }
      } else { //invalid data
        return false
      }
      _options.data.content = matches
      _options.response(event, _options.data)
      _options.setUIContent()
    },
    response: function(event, ui) {},
    focus: function(event) {},
    select: function(event, ui) {},
  }

  //Merge defaults and submitted options:
  _options = domUtils.extend(defaultOptions, _options)

  _options.sourceAr = []
  if (Array.isArray(_options.source)) {
    if (is_object(_options.source[0])) {
      _options.sourceAr = _options.source
    } else {
      _options.source.forEach(_src => {
        _options.sourceAr.push({text: _src, value: _src})
      })
    }
  }

  //Let know this input has autocomple:
  this._jeeComplete = _options

  var createEvents = false

  //Support same container for multiple inputs:
  if (_options.id != false) {
    _options.data.container = document.getElementById(_options.id)
  }
  if (_options.data.container == null) {
    createEvents = true
    _options.data.container = document.createElement('ul')
    _options.data.container.addClass('jeeComplete').unseen()
    _options.data.container._jeeComplete = {reference: _options.data.item, references: [_options.data.item]}
    _options.data.container = document.body.appendChild(_options.data.container)
  } else {
    _options.data.container._jeeComplete.references.push(_options.data.item)
  }
  if (_options.id == false) {
    _options.data.container.uniqueId()
    _options.id = _options.data.container.getAttribute('id')
  } else {
    _options.data.container.setAttribute('id', _options.id)
  }

  _options.request = {
    term: '',
    start: null,
    end: null
  }

  _options.setUIContent = function(_paires) {
    if (!Array.isArray(_options.data.content) || _options.data.content.length == 0) {
      _options.data.container.unseen()
      return
    }
    _options.data.container.empty()
    var newValue
    _options.data.content.forEach(_pair => {
      newValue = document.createElement('li')
      newValue.innerHTML = '<div data-value=' + _pair.value + '>' + _pair.text + '</div>'
      newValue.addClass('jeeCompleteItem')
      _options.data.container.appendChild(newValue)
    })
    var inputPos = _options.data.item.getBoundingClientRect()
    _options.data.container.style.zIndex = _options.zIndex
    _options.data.container.style.top = inputPos.top + _options.data.item.offsetHeight + 'px'
    _options.data.container.style.left = inputPos.left + 'px'
    _options.data.container.style.width = _options.data.item.offsetWidth + 'px'
    setTimeout(function() {
      _options.data.container.seen()
    }, 250)
  }

  /*Events
  */
  if (createEvents) {
    _options.data.container.registerEvent('click', function jeeComplete(event) {
      var selectedLi = event.target.closest('li.jeeCompleteItem') || event.target
      if (selectedLi == null) return
      var selected = selectedLi.firstChild
      var ulContainer = document.getElementById(_options.id)
      //set selected value and send to registered select option:
      _options.data.value = selected.getAttribute('data-value')
      _options.data.text = selected.textContent
      _options.data.item = ulContainer._jeeComplete.reference
      var next = _options.select(event, _options.data)
      if (next === false) {
        return
      }

      _options.request = ulContainer._jeeComplete.request
      if (_options.forceSingle) {
        ulContainer._jeeComplete.reference.value = _options.data.text
      } else {
        var inputValue = ulContainer._jeeComplete.reference.value
        inputValue = inputValue.substring(0, _options.request.start-1) + inputValue.substring(_options.request.end-1)
        inputValue = inputValue.slice(0, _options.request.start-1) + _options.data.text + inputValue.slice(_options.request.start-1)
        ulContainer._jeeComplete.reference.value = inputValue
      }
      _options.data.container.unseen()
      setTimeout(()=> {
        ulContainer._jeeComplete.reference.triggerEvent('blur')
      })

    }, {capture: true, buble: true})
  }

  this.unRegisterEvent('keydown', 'jeeComplete').registerEvent('keydown', function jeeComplete(event) {
    if (event.key == 'ArrowDown' || event.key == 'ArrowUp') {
      event.preventDefault()
    }
  })

  this.unRegisterEvent('keyup', 'jeeComplete').registerEvent('keyup', function jeeComplete(event) {
    /*keyCode:
      Backspace 8
      Enter 13
      Shift 16
      Control 17
      Alt 18
      AltGraph 18
      Escape 27
      ArrowLeft 37
      ArrowUp 38
      ArrowRight 39
      ArrowDown 40
      Delete 46
    */
    //console.log('keyup', event, 'key:', event.key, 'code:', event.keyCode)
    //console.log('selection:', event.target.selectionStart, event.target.selectionEnd)

    if (event.ctrlKey || event.altKey || event.metaKey) return

    if (event.key == ' ') {
      _options.request.term = ''
      return
    }
    if (_options.request.term == '') {
      _options.request.start = event.target.selectionStart
      _options.request.end = event.target.selectionEnd
    } else if (!event.key.includes('Arrow') && event.key != 'Backspace' && event.key != 'Delete') {
      _options.request.end = event.target.selectionEnd
    }

    //Arrow up/down select guest:
    if (event.key == 'ArrowDown') {
      if (_options.data.container.querySelector('li.jeeCompleteItem.active') == null) {
        _options.data.container.querySelector('li.jeeCompleteItem')?.addClass('active')
      } else {
        var active = _options.data.container.querySelector('li.jeeCompleteItem.active')
        if (active.nextElementSibling != null) {
          active.removeClass('active').nextElementSibling.addClass('active')
        }
      }
      return
    }

    if (event.key == 'ArrowUp') {
      if (_options.data.container.querySelector('li.jeeCompleteItem.active') == null) {
        _options.data.container.querySelectorAll('li.jeeCompleteItem').last()?.addClass('active')
      } else {
        var active = _options.data.container.querySelector('li.jeeCompleteItem.active')
        if (active.previousElementSibling != null) {
          active.removeClass('active').previousElementSibling.addClass('active')
        }
      }
      return
    }

    if (event.key == 'Enter') {
      _options.data.container.querySelector('li.jeeCompleteItem.active')?.firstChild.triggerEvent('click')
      _options.data.container.unseen()
      event.target.blur()
      return
    }

    if (event.key == 'Backspace') {
      _options.data.container.unseen()
      _options.request.term = _options.request.term.slice(0, -1)
      _options.request.end --

      document.getElementById(_options.id)._jeeComplete.request = _options.request
      _options._source(_options.request)
      return
    } else if (event.key == 'Delete') {
      _options.data.container.unseen()
      if (event.target.selectionStart >= _options.request.start && event.target.selectionEnd <= _options.request.end) {
        _options.request.end --
        _options.request.term = _options.request.term.substr(_options.request.start-1, _options.request.end-1)

        document.getElementById(_options.id)._jeeComplete.request = _options.request
        _options._source(_options.request)
      }
    } else if (event.key == 'ArrowLeft') {
      _options.data.container.unseen()
      return
    } else if (event.key == 'ArrowRight') {
      _options.data.container.unseen()
      return
    } else if (_options.ignoreKeyCodes.includes(event.keyCode)) {
      return
    } else {
      _options.request.term += event.key
      _options.request.end ++
    }

    if (event.key.length == 1 && _options.request.term.length >= _options.minLength) {
      document.getElementById(_options.id)._jeeComplete.request = _options.request
      _options._source(_options.request)
    }
  })

  this.unRegisterEvent('focus', 'jeeComplete').registerEvent('focus', function jeeComplete(event) {
    _options.data.item = event.target
    document.getElementById(_options.id)._jeeComplete.reference = event.target
    _options.focus(event)
  })

  this.unRegisterEvent('blur', 'jeeComplete').registerEvent('blur', function jeeComplete(event) {
    event.target.triggerEvent('change')
    setTimeout(function() { //Let time for click!
      _options.request.term = ''
      _options.data.container.unseen()
    }, 250)
  })
}

domUtils.syncJeeCompletes = function() {
  var jeeItems = document.querySelectorAll('ul.jeeComplete')
  jeeItems.forEach(_jee => {
    var existing = []
    _jee._jeeComplete.references.forEach(_ref => {
      if (_ref.isConnected === true) {
        existing.push(_ref)
      }
    })
    if (existing.length > 0) {
      _jee._jeeComplete.references = existing
    } else {
      _jee.remove()
    }
  })
}

var jeeDialog = (function()
{
    'use strict'

    /*________________TOASTR
    jeeDialog.toast({
      level: 'warning',
      title: 'I\'m a toast!',
      message: 'What about a coffee ?',
      timeOut: 5000,
      extendedTimeOut: 4000,
      onclick: function() {
        jeeDialog.clearToasts()
        $('#md_modal').dialog({ title: "{{Centre de Messages}}" }).load('index.php?v=d&modal=message.display').dialog('open')
      }
    })
    */
    var _toast = function(_options) {
      var defaultOptions = {
        id: 'jeeToastContainer',
        positionClass: jeedom.theme['interface::toast::position'] || 'toast-bottom-right',
        title: '',
        message: '',
        level: 'info',
        timeOut: jeedom.theme['interface::toast::duration'] * 1000 || 3000,
        extendedTimeOut: jeedom.theme['interface::toast::duration'] * 1000 || 3000,
        emptyBefore: false,
        attachTo: false,
        onclick: function(event) {
          event.target.closest('.toast').remove()
        }
      }
      //Merge defaults and submitted options:
      _options = domUtils.extend(defaultOptions, _options)
      _options.timeOut = parseInt(_options.timeOut)
      _options.extendedTimeOut = parseInt(_options.extendedTimeOut)

      var toastContainer = document.getElementById('jeeToastContainer')
      if (toastContainer == null) {
        toastContainer = document.createElement('div')
        toastContainer.setAttribute('id', _options.id)
        toastContainer.addClass('jeeToastContainer', _options.positionClass)
        document.body.appendChild(toastContainer)
      } else {
        if (_options.emptyBefore) {
          toastContainer.empty()
        }
      }

      //Main toast div:
      var toast = document.createElement('div')
      toast.addClass('jeeToast', 'toast', 'toast-'+_options.level)
      //Child title div:
      var toastTitle = document.createElement('div')
      toastTitle.addClass('jeeToast', 'toastTitle')
      toastTitle.innerHTML = _options.title
      toast.appendChild(toastTitle)
      //Child message div:
      var toastMessage = document.createElement('div')
      toastMessage.innerHTML = _options.message
      toastMessage.addClass('jeeToast', 'toastMessage')
      toast.appendChild(toastMessage)
      //Child progress bar:
      if (_options.timeOut > 0) {
        _options.progressIntervalId = null
        var toastProgress = document.createElement('div')
        toastProgress.addClass('jeeToast', 'toastProgress')
        toast.appendChild(toastProgress)
      }

      //Add to container:
      toastContainer.appendChild(toast)

      if (_options.attachTo) {
        try {
          var attachTo = document.querySelector(options.attach)
          if (attachTo != null) {
            attachTo.appendChild(toastContainer)
          }
        } catch (error) { }
      } else {
        if (toastContainer.parentNode != document.body) {
          document.body.appendChild(toastContainer)
        }
      }

      //Register element _toast object:
      if (_options.timeOut > 0) {
        toast._toast = {}
        toast._toast.setHideTimeout = function(_delay) {
          toast._toast.hideTimeoutId = setTimeout(function() {
            toast.remove()
            if (toastContainer.childNodes.length == 0) {
              _clearToasts()
            }
          }, _delay)
        }
        toast._toast.setHideTimeout(_options.timeOut)

        //Progress bar:
        toast._toast.progressBar = toastProgress
        toast._toast.updateProgress = function(timeout) {
          var percentage = ((toast._toast.progressBarHideETA - (new Date().getTime())) / parseFloat(timeout)) * 100
          toast._toast.progressBar.style.width = percentage + '%'
        }
        toast._toast.progressBarHideETA = new Date().getTime() + parseFloat(_options.timeOut)
        toast._toast.progressIntervalId = setInterval(toast._toast.updateProgress, 10, _options.timeOut)

        //Events:
        toast.addEventListener('mouseenter', function(event) {
          clearTimeout(event.target._toast.hideTimeoutId)
          clearInterval(event.target._toast.progressIntervalId)
          event.target.querySelector('.toastProgress').unseen()
          event.target.setAttribute('style', 'padding-bottom: 6px')
        })
        toast.addEventListener('mouseleave', function(event) {
          event.target._toast.setHideTimeout(_options.extendedTimeOut)
          event.target._toast.progressBarHideETA = new Date().getTime() + parseFloat(_options.extendedTimeOut)
          event.target._toast.progressIntervalId = setInterval(event.target._toast.updateProgress, 10, _options.extendedTimeOut)
          event.target.querySelector('.toastProgress').seen()
          event.target.removeAttribute('style')
        })
      } else {
        toast.style.paddingBottom = '6px'
      }

      toast.addEventListener('click', function(event) {
        _options.onclick(event)
      })
      return toast
    }
    var _clearToasts = function() {
      document.querySelectorAll('.jeeToastContainer')?.remove()
      return true
    }

    /*________________DIALOG --WIP!!!!
    */
    var _create = function(_options) {
      console.log('jeeDialog _create', _options)

      var defaultOptions = {
        autoOpen: false,
        classes: '',

      }

      //Merge defaults and submitted options:
      _options = domUtils.extend(defaultOptions, _options)

      _options.id = _options.id || domUtils.uniqueId()

      console.log('merged _options:', _options)

      var _diagMain = document.createElement('div')
      _diagMain.setAttribute('id', _options.id)

      //classes
      //title part
      //content part
      //buttons part

      if (!autoOpen) _diagMain.style.display = 'none'
      document.body.appendChild(_diagMain)
      return _diagMain
    }

    /*________________PROMPT --WIP!!!!
    */

    return { //Accessible functions: jeeDialog.toast(_options), ...
      _description: 'Jeedom dialog function handling modals and alert messages. /core/dom/dom.ui.js',
      toast: function(_options) {
        return _toast(_options)
      },
      clearToasts: function() {
        return _clearToasts()
      },
      create: function(_options) {
        return _create(_options)
      },

    }
})()