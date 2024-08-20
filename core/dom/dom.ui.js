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

domUtils.showLoading = function(_timeout) {
  document.getElementById('div_jeedomLoading')?.seen()
  //Hanging timeout:
  if (domUtils.loadingTimeout && domUtils.loadingTimeout != null) {
    clearTimeout(domUtils.loadingTimeout)
    domUtils.loadingTimeout = null
  }
  if (_timeout && typeof _timeout == 'number') {
    domUtils.loadingTimeout = setTimeout(() => {
      if (!document.getElementById('div_jeedomLoading')?.isHidden()) {
        domUtils.hideLoading()
        domUtils.DOMloading = 0
        if (jeedomUtils) jeedomUtils.showAlert({ level: 'danger', message: 'Operation Timeout: Something has gone wrong!' })
      }
    }, _timeout * 1000)
  }
}
domUtils.hideLoading = function() {
  document.getElementById('div_jeedomLoading')?.unseen()
  if (domUtils.loadingTimeout && domUtils.loadingTimeout != null) {
    clearTimeout(domUtils.loadingTimeout)
    domUtils.loadingTimeout = null
  }
}

/* HTMLCollection is live, NodeList is static and iterable
*/
//HTMLCollection.prototype.forEach = Array.prototype.forEach

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
Element.prototype.toggle = function() {
  if (this.offsetParent === null){
    this.style.display = ''
  } else {
    this.style.display = 'none'
  }
  return this
}
NodeList.prototype.toggle = function() {
  for (var idx = 0; idx < this.length; idx++) {
    this[idx].toggle()
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

//CSS Class manipulation
Element.prototype.addClass = function(_className /*, _className... */) {
  if (_className == '') return this
  let args = Array.prototype.slice.call(arguments)
  if (args[0].includes(' ')) args = args[0].split(' ')
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
  if (_className == '') {
    this.classList = ''
    return this
  }

  let args = Array.prototype.slice.call(arguments)
  if (args.length > 0 && args[0].includes(' ')) args = args[0].split(' ')
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
  if (this.selectionStart >= 0) {
    let value = this.value.substring(0, this.selectionStart) + _valueString
    this.value = value + this.value.substring(this.selectionEnd, this.value.length)
    this.setSelectionRange(value.length, value.length)
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

HTMLSelectElement.prototype.sortOptions = function(_text) {
  if (!isset(_text)) _text = true
  var optionsAr = Array.from(this.options)
  optionsAr.sort(function(a, b) {
    if (_text) {
      return a.textContent > b.textContent ? 1 : -1
    } else {
      return a.value > b.value ? 1 : -1
    }
  })
  for (let opt of optionsAr) {
    this.appendChild(opt)
  }
  this.selectedIndex = 0
  return this
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
  } catch (error) { }

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
  } catch (error) { }
}

/*Components
*/
document.addEventListener('DOMContentLoaded', function() {
  if (document.head.querySelectorAll('script[src*="bootstrap.min.js"]').length == 0) {
    document.body.addEventListener('click', function(event) {
      //Close all dropdowns
      document.querySelectorAll('div.dropdown.open').removeClass('open')
      document.querySelectorAll('button.dropdown-toggle').forEach(_bt => _bt.parentNode.removeClass('open'))
      var _target = null

      //Accordions
      if (_target = event.target.closest('a.accordion-toggle')) {
        event.preventDefault()

        let ref = _target.getAttribute('href')
        if (!ref) return
        let panelGroup = _target.closest('div.panel-group')
        if (!panelGroup) {
          var panel = document.querySelector(ref)
        } else {
          var panel = panelGroup.querySelector(ref)
        }
        if (!panel) return
        var isOpen = panel.hasClass('in')

        //Close all if has parent declared:
        var parentRef = _target.getAttribute('data-parent')
        if (parentRef && parentRef != '') {
          _target.closest(parentRef)?.querySelectorAll('div.panel-collapse').removeClass('in')
        }

        isOpen ? panel.removeClass('in') : panel.addClass('in')
        return
      }

      //Collapse
      if (_target = event.target.closest('a[data-toggle="collapse"]')) {
        _target.parentNode.querySelector('.collapse')?.toggleClass('in')
        return
      }

      //Tabs
      if (_target = event.target.closest('a[role="tab"]')) {
        event.preventDefault()
        let tabList = _target.closest('[role="tablist"]')
        if (!tabList) return
        let contentContainer = tabList.nextElementSibling
        if (!contentContainer || !contentContainer.hasClass('tab-content')) return
        let contentRef = _target.getAttribute('data-target')
        if (!contentRef) contentRef = _target.getAttribute('href')
        if (!contentRef) return
        let tab = document.querySelector(contentRef)
        if (!tab) return
        //Update current tab level:
        tabList.querySelectorAll('li[role="presentation"].active').removeClass('active')
        _target.closest('li[role="presentation"]')?.addClass('active')

        contentContainer.querySelectorAll('div[role="tabpanel"].active').removeClass('active')
        tab.addClass('active')

        //Update tabs inside current tab:
        tab.querySelectorAll('[role="tablist"]').forEach(_list => {
          let active = _list.querySelector('li.active')
          if (active == null) {
            _list.querySelector('a[role="tab"]').click()
          } else {
            active.querySelector('a[role="tab"]').click()
          }
        })

        if (_target.getAttribute('data-target') == '' && event.target.getAttribute('href') == '') return
        if (_target.closest('.ui-dialog-content') != null) return
        if (_target.closest('.jeeDialog') != null) return

        if (jeeFrontEnd.PREVIOUS_PAGE == null) {
          window.history.replaceState('', '', 'index.php?' + window.location.href.split("index.php?")[1])
          jeeFrontEnd.PREVIOUS_PAGE = 'index.php?' + window.location.href.split("index.php?")[1]
        }
        window.location.hash = _target.getAttribute('data-target') || _target.getAttribute('href')
        return
      }

      //DropDowns
      if (_target = event.target.closest('button.dropdown-toggle')) {
        _target.parentNode.toggleClass('open')
        return
      }
    })
  }
})

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
        if (!is_object(matches[0])) {
          matches.forEach(_src => {
            matchesAr.push({ text: _src, value: _src })
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
    response: function(event, ui) { },
    focus: function(event) { },
    select: function(event, ui) { },
  }

  //Merge defaults and submitted options:
  _options = domUtils.extend(defaultOptions, _options)

  _options.sourceAr = []
  if (Array.isArray(_options.source)) {
    if (is_object(_options.source[0])) {
      _options.sourceAr = _options.source
    } else {
      _options.source.forEach(_src => {
        _options.sourceAr.push({ text: _src, value: _src })
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
    _options.data.container._jeeComplete = { reference: _options.data.item, references: [_options.data.item] }
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
  click = mousedown + mouseup
  use mousdown to fire before focusout
  */
  if (createEvents) {
    _options.data.container.registerEvent('mousedown', function jeeComplete(event) {
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
        inputValue = inputValue.substring(0, _options.request.start - 1) + inputValue.substring(_options.request.end - 1)
        inputValue = inputValue.slice(0, _options.request.start - 1) + _options.data.text + inputValue.slice(_options.request.start - 1)
        ulContainer._jeeComplete.reference.value = inputValue
      }
      _options.data.container.unseen()
      setTimeout(() => {
        ulContainer._jeeComplete.reference.blur()
      })
    }, { capture: true, buble: true })
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
      _options.data.container.querySelector('li.jeeCompleteItem.active')?.firstChild.triggerEvent('mousedown')
      _options.data.container.unseen()
      setTimeout(() => {
        event.target.blur()
      })
      return
    }

    if (event.key == 'Backspace') {
      _options.data.container.unseen()
      _options.request.term = _options.request.term.slice(0, -1)
      _options.request.end--

      document.getElementById(_options.id)._jeeComplete.request = _options.request
      _options._source(_options.request)
      return
    } else if (event.key == 'Delete') {
      _options.data.container.unseen()
      if (event.target.selectionStart >= _options.request.start && event.target.selectionEnd <= _options.request.end) {
        _options.request.end--
        _options.request.term = _options.request.term.substr(_options.request.start - 1, _options.request.end - 1)

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
      _options.request.end++
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
  document.querySelectorAll('ul.jeeComplete').forEach(_jee => {
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

/* jeeDialog()
jeeDialog.toast() Handle toast
jeeDialog.alert() / confirm() / prompt() Handle mini modals
jeeDialog.modal() handle mini modal with predefined content
jeeDialog.dialog() handle complete moveable/resiable dialogs
*/
var jeeDialog = (function() {
  'use strict'
  let exports = {
    _description: 'Jeedom dialog function handling modals and alert messages. /core/dom/dom.ui.js'
  }

  /*________________TOAST
  */
  exports.toast = function(_options) {
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
        var toast = event.target.closest('.jeeToast.toast')
        toast._jeeDialog.close(toast)
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
    toast.addClass('jeeToast', 'toast', 'toast-' + _options.level)
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
        if (typeof _options.attachTo === 'string') {
          _options.attachTo = document.querySelector(_options.attachTo)
        }

        if (_options.attachTo != null) {
          _options.attachTo.appendChild(toastContainer)
        }
      } catch (error) { }
    } else {
      if (toastContainer.parentNode != document.body) {
        document.body.appendChild(toastContainer)
      }
    }

    //Register element _jeeDialog object:
    toast._jeeDialog = {
      close: function(toast) {
        toast.remove()
      }
    }
    if (_options.timeOut > 0) {
      toast._jeeDialog.setHideTimeout = function(_delay) {
        toast._jeeDialog.hideTimeoutId = setTimeout(function() {
          toast.remove()
          if (toastContainer.childNodes.length == 0) {
            exports.clearToasts()
          }
        }, _delay)
      }
      toast._jeeDialog.setHideTimeout(_options.timeOut)

      //Progress bar:
      toast._jeeDialog.progressBar = toastProgress
      toast._jeeDialog.updateProgress = function(timeout) {
        var percentage = ((toast._jeeDialog.progressBarHideETA - (new Date().getTime())) / parseFloat(timeout)) * 100
        toast._jeeDialog.progressBar.style.width = percentage + '%'
      }
      toast._jeeDialog.progressBarHideETA = new Date().getTime() + parseFloat(_options.timeOut)
      toast._jeeDialog.progressIntervalId = setInterval(toast._jeeDialog.updateProgress, 10, _options.timeOut)

      //Events:
      toast.addEventListener('mouseenter', function(event) {
        clearTimeout(event.target._jeeDialog.hideTimeoutId)
        clearInterval(event.target._jeeDialog.progressIntervalId)
      })
      toast.addEventListener('mouseleave', function(event) {
        event.target._jeeDialog.setHideTimeout(_options.extendedTimeOut)
        event.target._jeeDialog.progressBarHideETA = new Date().getTime() + parseFloat(_options.extendedTimeOut)
        event.target._jeeDialog.progressIntervalId = setInterval(event.target._jeeDialog.updateProgress, 10, _options.extendedTimeOut)
      })
    } else {
      toast.style.paddingBottom = '6px'
    }

    toast.addEventListener('click', function(event) {
      _options.onclick(event)
    })
    return toast
  }
  exports.clearToasts = function() {
    document.querySelectorAll('.jeeToastContainer')?.remove()
    return true
  }

  /* Dialogs / popups common:
  */
  exports.setDialogDefaults = function(_options) {
    let commonDefaults = {
      id: '',
      autoOpen: true,
      width: '30vw',
      height: '20vh',
      position: {
        from: 'center',
        to: 'center'
      },
      backdrop: true,
      isMainDialog: false,
      container: document.body,
      open: function() { },
      onShown: function() { },
      beforeClose: function() { },
      onClose: function() {
        cleanBackdrop()
      }
    }
    _options = domUtils.extend(commonDefaults, _options)
    return _options
  }

  function setDialog(_container) {
    var _params = _container._jeeDialog.options
    let defaultParams = {
      setTitle: true,
      setContent: true,
      setFooter: true,
      backdrop: true,
      buttons: {}
    }
    _params = domUtils.extend(defaultParams, _params)

    setBackDrop(_params)

    var template = document.createElement('template')
    //Title part and close button:
    if (_params.setTitle) {
      if (_params.isMainDialog) {
        var dialogTitle = document.createElement('div')
        dialogTitle.addClass('jeeDialogTitle')
        let html = '<span class="title">' + _params.title + '</span>'
        html += '<div class="titleButtons">'
        html += '<button class="btClose" type="button"></button>'
        html += '<button class="btToggleMaximize" type="button"></button>'
        //html += '<button class="btMinimize" type="button"></button>'
        html += '</div>'
        dialogTitle.innerHTML = html
        template.appendChild(dialogTitle)

        dialogTitle.querySelector('button.btClose').addEventListener('click', function(event) {
          event.target.closest('div.jeeDialog')._jeeDialog.close()
          cleanBackdrop()
        })
        dialogTitle.querySelector('button.btToggleMaximize').addEventListener('click', function(event) {
          let dialog = event.target.closest('div.jeeDialog')
          if (dialog.getAttribute('data-maximize') == '0') { //Not maximized
            dialog.setAttribute('data-maximize', '1')
          } else { //Restore
            dialog.setAttribute('data-maximize', '0')
          }
          let onResize = dialog._jeeDialog.options.onResize
          if (onResize) {
            setTimeout(function() { onResize(event) })
          }
        })
        /*
        dialogTitle.querySelector('button.btMinimize').addEventListener('click', function(event) {
          //Do stuff!!
        })
        */
      }
      else {
        var dialogTitle = document.createElement('div')
        dialogTitle.addClass('jeeDialogTitle')
        dialogTitle.innerHTML = '<span class="title">' + _params.title + '</span><button class="btClose" type="button"></button>'
        template.appendChild(dialogTitle)

        dialogTitle.querySelector(':scope > .btClose').addEventListener('click', function(event) {
          event.target.closest('div.jeeDialog')._jeeDialog.close()
          cleanBackdrop()
        })
      }
    }

    //Content part:
    if (_params.setContent) {
      var dialogContent = document.createElement('div')
      dialogContent.addClass('jeeDialogContent')
      if (_params.message != undefined && _params.message != '') {
        dialogContent.innerHTML = '<div>' + _params.message + '</div>'
      }
      template.appendChild(dialogContent)
    }

    //Footer part and buttons:
    if (_params.setFooter) {
      var dialogFooter = document.createElement('div')
      dialogFooter.addClass('jeeDialogFooter')
      template.appendChild(dialogFooter)

      let buttons = {}
      for ( let button of Object.entries(_params.buttons)) {
        buttons[button[0]] = domUtils.extend(_params.defaultButtons[button[0]],button[1])
      }

      for (let defaultButton of Object.entries(_params.defaultButtons)) {
        if (! isset(buttons[defaultButton[0]])) {
          buttons[defaultButton[0]] = defaultButton[1]
        }
      }

      for (var button of Object.entries(buttons)) {
        var buttonEL = exports.addButton(button, dialogFooter)
        if (buttonEL.getAttribute('data-type') === 'confirm') {
          _container.addEventListener('keyup', function(event) {
            if (event.key !== 'Enter') return
            if (event.target.getAttribute('data-type') === 'confirm') return //Avoid double call with button focused
            event.preventDefault()
            event.target.closest('div.jeeDialog').querySelector('button[data-type="confirm"]')?.click()
          })
        }
        if (buttonEL.getAttribute('data-type') === 'cancel') {
          _container.addEventListener('keyup', function(event) {
            if (event.key !== 'Escape') return
            if (event.target.getAttribute('data-type') === 'cancel') return //Avoid double call with button focused
            event.preventDefault()
            event.target.closest('div.jeeDialog').querySelector('button[data-type="cancel"]')?.click()
          })
        }
      }

    }

    _container.append(...template.children)
    return _container
  }

  exports.addButton = function(_button, _footer) {
    let button = document.createElement('button')
    button.setAttribute('type', 'button')
    button.setAttribute('data-type', _button[0])
    button.innerHTML = _button[1].label
    button.classList = 'button ' + _button[1].className
    if (isset(_button[1].callback)) {
      for (var [key, value] of Object.entries(_button[1].callback)) {
        button.addEventListener(key, function(event) {
          value.apply(this, [event, this.getAttribute('data-type')])
        })
      }
    }
    _footer.appendChild(button)
    return button
  }

  function setPosition(_dialog, _params) {
    _dialog.style = null
    if (_params.width) {
      if (is_int(_params.width)) {
        _dialog.style.width = _params.width + 'px'
      } else {
        _dialog.style.width = _params.width
      }
      if (_params.isMainDialog) {
        //Horizontally centered:
        let bRect = document.body.getBoundingClientRect()
        let mRect = _dialog.getBoundingClientRect()
        _dialog.style.left = (bRect.width / 2) - (mRect.width / 2) + "px"
      }
    }

    if (_params.height) {
      if (is_int(_params.height)) {
        _dialog.style.height = _params.height + 'px'
      } else {
        _dialog.style.height = _params.height
      }
    }

    if (_params.zIndex) _dialog.style.zIndex = _params.zIndex

    if (is_int(_params.top)) {
      _dialog.style.top = _params.top + 'px'
    } else {
      _dialog.style.top = _params.top
    }
  }

  function setBackDrop(_params, _show) {
    if (_params.backdrop) {
      var backDrop = document.getElementById('jeeDialogBackdrop')
      if (backDrop === null) {
        backDrop = document.createElement('div')
        backDrop.setAttribute('id', 'jeeDialogBackdrop')
        backDrop.unseen()
        document.body.appendChild(backDrop)
      }
      if (_params.isMainDialog) {
        backDrop.addEventListener('click', function(event) {
          document.querySelectorAll('div.jeeDialog:not(.jeeDialogNoCloseBackdrop)').forEach(_dialog => {
            if (isset(_dialog._jeeDialog)) _dialog._jeeDialog.close(_dialog)
          })
        })
      }
      if (_show === true) backDrop.seen()
    } else {
      cleanBackdrop()
    }
  }

  function cleanBackdrop() {
    var jeeDialogs = document.querySelectorAll('div.jeeDialog')
    var jeeDialogsWithBackdrop = 0
    var keep = false
    jeeDialogs.forEach(_dialog => {
      if (isset(_dialog._jeeDialog)) {
        if (_dialog._jeeDialog.options.backdrop === true && _dialog.isVisible()) {
          keep = true
          jeeDialogsWithBackdrop++
        } else if (_dialog._jeeDialog.options.backdrop === true) {
          jeeDialogsWithBackdrop++
        }
      }
    })
    if (keep === true) return false
    if (jeeDialogsWithBackdrop > 0) {
      document.getElementById('jeeDialogBackdrop')?.unseen()
    } else {
      document.getElementById('jeeDialogBackdrop')?.remove()
    }
  }

  /* getter
  jeeDialog.get('#eqLogicConfigureTab', 'dialog')
  jeeDialog.get(element, 'options')
  jeeDialog.get(element, 'title')
  eeDialog.get(element).destroy()
  */
  exports.get = function(_el, _option = '') {
    if (!isset(_option) || _option == '') _option = 'options'
    if (typeof _el === 'string') {
      _el = document.querySelector(_el)
    }
    if (_el == null) return null
    let dialog = _el.closest('div.jeeDialog')
    if (dialog == null) return null

    if (_option == 'options') {
      return (isset(dialog._jeeDialog) ? dialog._jeeDialog : null)
    } else if (_option == 'dialog') {
      return dialog
    } else if (_option == 'title') {
      return dialog.querySelector('div.jeeDialogTitle')
    } else if (_option == 'content') {
      return dialog.querySelector('div.jeeDialogContent')
    } else if (_option == 'footer') {
      return dialog.querySelector('div.jeeDialogFooter')
    }
    return null
  }

  /*________________PROMPTS
  */
  exports.alert = function(_options, _callback) {
    if (typeof _options === 'string') {
      _options = {
        message: _options
      }
    }
    if (_options.callback && typeof _options.callback === 'function') _callback = _options.callback
    var defaultOptions = this.setDialogDefaults({
      id: 'jeeDialogAlert',
      width: false,
      height: 'auto',
      top: '20vh',
      title: '',
      message: '',
      backdrop: true,
      buttons: {},
      defaultButtons: {
        confirm: {
          label: '<i class="fa fa-check"></i> {{OK}}',
          className: 'success',
          callback: {
            click: function(event) {
              var dialog = event.target.closest('div.jeeDialog')
              dialog._jeeDialog.close(dialog)
              if (typeof _callback === 'function') {
                _callback(true)
              }
            }
          }
        }
      }
    })

    _options = domUtils.extend(defaultOptions, _options)
    _options = domUtils.extend({
      setTitle: true,
      setContent: true,
      setFooter: true,
    }, _options)

    //Build alert container:
    var dialogContainer = document.createElement('div')
    dialogContainer.setAttribute('id', _options.id)
    dialogContainer.addClass('jeeDialog', 'jeeDialogAlert')
    dialogContainer.style.display = 'none'

    //Register element _jeeDialog object:
    dialogContainer._jeeDialog = {
      options: _options,
      dialog: dialogContainer,
      close: function(dialog) {
        if (dialog == undefined) dialog = this.dialog
        dialog._jeeDialog.options.beforeClose()
        dialog.remove()
        cleanBackdrop()
      }
    }

    //Build dialog:
    var dialog = setDialog(dialogContainer)

    //Inject dialog:
    if (_options.backdrop) {
      var backDrop = document.getElementById('jeeDialogBackdrop')
      dialogContainer = document.body.insertBefore(dialogContainer, backDrop)
    } else {
      _options.container.appendChild(dialogContainer)
    }

    //Set Dialog size:
    setPosition(dialogContainer, _options)

    //Finally:
    if (_options.autoOpen) {
      if (_options.backdrop) backDrop.seen()
      dialogContainer.style.display = ''
      setTimeout(function() {
        dialogContainer.querySelector('button[data-type="confirm"]')?.focus()
      })
    }
    return dialogContainer
  }

  exports.confirm = function(_options, _callback) {
    if (typeof _options === 'string') {
      _options = {
        message: _options
      }
    }
    if (_options.callback && typeof _options.callback === 'function') _callback = _options.callback
    var defaultOptions = this.setDialogDefaults({
      id: 'jeeDialogConfirm',
      width: false,
      height: 'auto',
      top: '20vh',
      title: '',
      message: '',
      backdrop: true,
      buttons: {},
      defaultButtons: {
        cancel: {
          label: '<i class="fa fa-times"></i> {{Annuler}}',
          className: 'warning',
          callback: {
            click: function(event) {
              var dialog = event.target.closest('div.jeeDialog')
              dialog._jeeDialog.close(dialog)
              if (typeof _callback === 'function') {
                _callback(null)
              }
            }
          }
        },
        confirm: {
          label: '<i class="fa fa-check"></i> {{OK}}',
          className: 'success',
          callback: {
            click: function(event) {
              var dialog = event.target.closest('div.jeeDialog')
              dialog._jeeDialog.close(dialog)
              if (typeof _callback === 'function') {
                _callback(true)
              }
            }
          }
        }
      }
    })
    _options = domUtils.extend(defaultOptions, _options)
    _options = domUtils.extend({
      setTitle: true,
      setContent: true,
      setFooter: true,
    }, _options)

    //Build alert container:
    var dialogContainer = document.createElement('div')
    dialogContainer.setAttribute('id', _options.id)
    dialogContainer.addClass('jeeDialog', 'jeeDialogConfirm')
    dialogContainer.style.display = 'none'

    //Register element _jeeDialog object:
    dialogContainer._jeeDialog = {
      options: _options,
      dialog: dialogContainer,
      close: function(dialog) {
        if (dialog == undefined) dialog = this.dialog
        dialog._jeeDialog.options.beforeClose()
        dialog.remove()
        cleanBackdrop()
      }
    }

    //Build dialog:
    var dialog = setDialog(dialogContainer)

    //Inject dialog:
    if (_options.backdrop) {
      var backDrop = document.getElementById('jeeDialogBackdrop')
      dialogContainer = document.body.insertBefore(dialogContainer, backDrop)
    } else {
      _options.container.appendChild(dialogContainer)
    }

    //Set Dialog size:
    setPosition(dialogContainer, _options)

    //Finally:
    if (_options.autoOpen) {
      if (_options.backdrop) backDrop.seen()
      dialogContainer.style.display = ''
      setTimeout(function() {
        dialogContainer.querySelector('button[data-type="confirm"]')?.focus()
      })
    }
    return dialogContainer
  }

  exports.prompt = function(_options, _callback) {
    if (typeof _options === 'string') {
      _options = {
        title: _options
      }
    }
    if (_options.callback && typeof _options.callback === 'function') _callback = _options.callback
    var defaultOptions = this.setDialogDefaults({
      id: 'jeeDialogPrompt',
      width: false,
      height: 'auto',
      top: '20vh',
      title: '',
      message: '',
      inputType: 'input',
      value: false,
      pattern: '',
      placeholder: false,
      inputOptions: false,
      backdrop: true,
      buttons: {},
      defaultButtons: {
        cancel: {
          label: '<i class="fa fa-times"></i> {{Annuler}}',
          className: 'warning',
          callback: {
            click: function(event) {
              var dialog = event.target.closest('div.jeeDialog')
              if (typeof _callback === 'function') {
                _callback(null)
              }
              dialog._jeeDialog.close(dialog)
            }
          }
        },
        confirm: {
          label: '<i class="fa fa-check"></i> {{OK}}',
          className: 'success',
          callback: {
            click: function(event) {
              var dialog = event.target.closest('div.jeeDialog')
              if (typeof _callback === 'function') {
                var data = event.target.closest('div.jeeDialog').querySelector('div.jeeDialogContent').getJeeValues('.promptAttr')[0]
                var key = event.target.closest('button').getAttribute('data-type')
                if (Object.keys(data).length == 1) data = data.result
                if (data == '') data = null
                _callback.apply(this, [data, key])
              }
              dialog._jeeDialog.close(dialog)
            }
          }
        }
      }
    })

    _options = domUtils.extend(defaultOptions, _options)
    _options = domUtils.extend({
      setTitle: true,
      setContent: true,
      setFooter: true,
    }, _options)

    //Build alert container:
    var dialogContainer = document.createElement('div')
    dialogContainer.setAttribute('id', _options.id)
    dialogContainer.addClass('jeeDialog', 'jeeDialogPrompt')
    dialogContainer.style.display = 'none'

    //Register element _jeeDialog object:
    dialogContainer._jeeDialog = {
      options: _options,
      dialog: dialogContainer,
      close: function(dialog) {
        if (dialog == undefined) dialog = this.dialog
        dialog._jeeDialog.options.beforeClose()
        dialog.remove()
        cleanBackdrop()
      }
    }

    //Build dialog:
    var dialog = setDialog(dialogContainer)

    let dialogContent = dialogContainer.querySelector('div.jeeDialogContent')
    if (_options.inputType) { //Can provide input and such as message!
      switch (_options.inputType) {
        case 'input':
          var content = document.createElement('input')
          content.setAttribute('type', 'text')
          content.setAttribute('data-l1key', 'result')
          content.addClass('promptAttr')
          if (_options.placeholder) content.setAttribute('placeholder', _options.placeholder)
          if (_options.value) content.value = _options.value
          dialogContent.appendChild(content)
          break
        case 'date':
        case 'time':
          var content = document.createElement('input')
          content.setAttribute('data-l1key', 'result')
          content.setAttribute('type', 'text')
          content.addClass('promptAttr')
          if (_options.placeholder) content.setAttribute('placeholder', _options.placeholder)
          if (_options.value) content.value = _options.value

          if (_options.pattern) {
            content.setAttribute('pattern', _options.pattern)
          } else {
            if (options.inputType === 'date') {
              content.setAttribute('pattern', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
            } else if (options.inputType === 'time') {
              content.setAttribute('pattern', '[0-9]{2}:[0-9]{2}:[0-9]{2}')
            }
          }
          content.setAttribute('onblur', "this.reportValidity()")

          dialogContent.appendChild(content)
          break
        case 'select':
          var content = document.createElement('select')
          content.setAttribute('data-l1key', 'result')
          content.addClass('promptAttr')
          if (_options.inputOptions) {
            _options.inputOptions.forEach(_option => {
              var opt = document.createElement("option")
              opt.setAttribute('value', _option.value)
              opt.textContent = _option.text
              content.add(opt, null)
            })
          }
          dialogContent.appendChild(content)
          break
        case 'textarea':
          var content = document.createElement('textarea')
          content.setAttribute('data-l1key', 'result')
          content.addClass('promptAttr')
          if (_options.value) content.value = _options.value
          dialogContent.appendChild(content)
          break
      }
    }


    //Inject dialog:
    if (_options.backdrop) {
      var backDrop = document.getElementById('jeeDialogBackdrop')
      dialogContainer = document.body.insertBefore(dialogContainer, backDrop)
    } else {
      _options.container.appendChild(dialogContainer)
    }

    //Set Dialog size:
    setPosition(dialogContainer, _options)

    //Finally:
    if (_options.autoOpen) {
      if (_options.backdrop) backDrop.seen()
      dialogContainer.style.display = ''
      _options.onShown(dialogContainer)
      setTimeout(function() {
        var set = dialogContainer.querySelector('.promptAttr')
        if (set != null) {
          set.focus()
        } else {
          dialogContainer.querySelector('button[data-type="confirm"]')?.focus()
        }
      })
    }

    return dialogContainer
  }

  exports.modal = function(_element, _options) {
    if (!isset(_options)) _options = {}
    var defaultOptions = this.setDialogDefaults({
      width: false,
      height: 'auto',
      top: '20vh',
      backdrop: true,
      buttons: {},
      defaultButtons: {}
    })

    if (_element._jeeDialog == undefined) {
      _options = domUtils.extend(defaultOptions, _options)
      //Register element _jeeDialog object:
      _element._jeeDialog = {
        options: _options,
        dialog: _element,
        show: function() {
          setBackDrop(_options, true)
          this.dialog.seen()
        },
        hide: function() {
          this.dialog.unseen()
          cleanBackdrop()
        },
        close: function() {
          this.dialog.remove()
          cleanBackdrop()
        },
        destroy: function() {
          this.dialog.remove()
          cleanBackdrop()
        }
      }
      _element.querySelector(':scope > .btClose')?.addEventListener('click', function(event) {
        cleanBackdrop()
        event.target.closest('div.jeeDialog').remove()
      })
      //Set Dialog size:
      setPosition(_element, _options)
    }
    return _element
  }


  /*________________DIALOGS
  */
  var specialDialogOptions = {
    md_cmdHistory: {
      width: '800px',
      height: '500px',
      top: '15vh',
      retainPosition: true,
      zIndex: 1030,
      backdrop: false,
    },
    mod_selectIcon: {
      width: '95vw',
      height: '90vh',
      top: '5vh',
      zIndex: 1025
    },
    jee_modal2: {
      width: '75vw',
      top: '7vh',
      zIndex: 1021,
    },
    jee_modal3: {
      width: '60vw',
      top: '5vh',
      zIndex: 1022,
    }
  }
  exports.dialog = function(_options) {
    //Require _options.id to construct and initialize a container:
    if (!isset(_options)) _options = {}
    if (!isset(_options.id)) _options.id = 'jee_modal'

    var dialogContainer = document.getElementById(_options.id)
    if (dialogContainer == null) {
      dialogContainer = document.createElement('div')
      dialogContainer.setAttribute('id', _options.id)
      if (_options.fullScreen) {
        dialogContainer.setAttribute('data-maximize', '1')
      } else {
        dialogContainer.setAttribute('data-maximize', '0')
      }
      dialogContainer.addClass('jeeDialog', 'jeeDialogMain')
      dialogContainer.style.display = 'none'
      document.body.appendChild(dialogContainer)
    }

    //First initialize dialog:
    if (dialogContainer._jeeDialog == undefined) {
      var defaultOptions = this.setDialogDefaults({
        id: 'jee_modal',
        show: true,
        retainPosition: false,
        fullScreen: document.body.getAttribute('data-device') == 'phone' ? true : false,
        contentUrl: '',
        zIndex: 1019,
        width: '90vw',
        height: '65vh',
        top: '10vh',
        backdrop: true,
        buttons: {},
        defaultButtons: {
          cancel: {
            label: '<i class="fa fa-times"></i> {{Annuler}}',
            className: 'warning',
            callback: {
              click: function(event) { }
            }
          },
          confirm: {
            label: '<i class="fa fa-check"></i> {{OK}}',
            className: 'success',
            callback: {
              click: function(event) { }
            }
          }
        },
        title: 'Jeedom',
        setTitle: true,
        setContent: true,
        setFooter: false,
        callback: false,
        onMove: false,
        onResize: false
      })

      _options = domUtils.extend(defaultOptions, _options)
      _options.isMainDialog = true

      //Check special init values:
      if (_options.id in specialDialogOptions) {
        _options = domUtils.extend(_options, specialDialogOptions[_options.id])
      }

      if (Object.keys(_options.buttons).length > 0) {
        _options.setFooter = true
      }

      //Register element _jeeDialog object:
      dialogContainer._jeeDialog = {
        options: _options,
        dialog: dialogContainer,
        show: function() {
          setBackDrop(_options, true)
          this.dialog._jeeDialog.options.onShown()
          if (!_options.retainPosition || this.dialog.style.width == '') {
            if (!_options.fullScreen) {
              this.dialog.setAttribute('data-maximize', '0')
            } else {
              this.dialog.setAttribute('data-maximize', '1')
            }
            setPosition(this.dialog, _options)
          }
          document.querySelectorAll('div.jeeDialog.jeeDialogMain').removeClass('active')
          this.dialog.addClass('active')
          this.dialog.seen()
          setTimeout(function() {
            dialogContainer.querySelector('button[data-type="confirm"]')?.focus()
          })
        },
        hide: function() {
          this.dialog.unseen()
          cleanBackdrop()
        },
        close: function() {
          this.dialog._jeeDialog.options.beforeClose()
          this.dialog.querySelector('div.jeeDialogContent').empty()
          jeeDialog.clearToasts()
          this.dialog.unseen()
          this.dialog._jeeDialog.options.onClose()
          this.dialog.removeClass('active')
          let _dialog = document.querySelectorAll('div.jeeDialog.jeeDialogMain:not([style*="display: none;"])')
          _dialog[_dialog.length - 1]?.addClass('active')
          cleanBackdrop()
        },
        destroy: function() {
          this.dialog.remove()
          cleanBackdrop()
        }
      }

      //Build dialog:
      var dialog = setDialog(dialogContainer)
      dialogContainer.addClass('jeeDialog', 'jeeDialogMain')
      if (_options.setFooter === true) {
        dialogContainer.addClass('hasfooter')
      }

      dialogContainer.parentNode.addEventListener('mousedown', function(event) {
        document.querySelectorAll('div.jeeDialog.jeeDialogMain').removeClass('active')
        try { event.target.closest('div.jeeDialog.jeeDialogMain').addClass('active') } catch (e) { } //Dialog may close!
      })

      //____Set Moveable
      var nextLeft, nextTop, initialLeft, initialTop
      var bodyRect = null
      var divTitle = dialogContainer.querySelector('div.jeeDialogTitle')
      if (divTitle) {
        divTitle.addEventListener('mousedown', dragStart, false)
        divTitle.addEventListener('touchstart', dragStart, false)
        var onMove, moveDone
        function dragStart(event) {
          if (event.target.matches('button')) return
          event.preventDefault()
          if (dialogContainer.getAttribute('data-maximize') == '1') return
          bodyRect = document.body.getBoundingClientRect()
          let bRect = dialogContainer.getBoundingClientRect()
          initialLeft = event.clientX || event.targetTouches[0].pageX
          initialLeft -= bRect.left
          initialTop = event.clientY || event.targetTouches[0].pageY
          initialTop -= bRect.top
          document.body.addEventListener('mouseup', dragEnd, false)
          document.body.addEventListener('touchend', dragEnd, false)
          document.body.addEventListener('mousemove', dragging, false)
          document.body.addEventListener('touchmove', dragging, false)
          onMove = dialogContainer._jeeDialog.options.onMove
          if (onMove) {
            moveDone = null
          }
        }
        function dragging(event) {
          event.preventDefault()
          let modalRect = dialogContainer.getBoundingClientRect()
          nextLeft = event.clientX || event.targetTouches[0].pageX
          nextLeft -= initialLeft
          nextTop = event.clientY || event.targetTouches[0].pageY
          nextTop -= initialTop

          if (nextTop <= 0) {
            nextTop = 0
          }
          if (nextLeft >= (bodyRect.right - modalRect.width)) {
            nextLeft = (bodyRect.right - modalRect.width)
          }
          if (nextTop >= (bodyRect.bottom - modalRect.height)) {
            nextTop = (bodyRect.bottom - modalRect.height)
          }
          if (nextLeft <= 0) {
            nextLeft = 0
          }
          dialogContainer.style.left = nextLeft + 'px'
          dialogContainer.style.top = nextTop + 'px'
          if (onMove) {
            clearTimeout(moveDone)
            moveDone = setTimeout(function() { onMove(event) }, 100)
          }
        }
        function dragEnd(event) {
          document.body.removeEventListener('mouseup', dragEnd, false)
          document.body.removeEventListener('touchend', dragEnd, false)
          document.body.removeEventListener('mousemove', dragging, false)
          document.body.removeEventListener('touchmove', dragging, false)
        }

        //____Set Resizeable
        var resizer, initialLeft, initialTop, initialWidth, initialHeight
        var resizers = ['top', 'top-right', 'right', 'bottom-right', 'bottom', 'bottom-left', 'left', 'top-left']
        resizers.forEach(handle => {
          var div = document.createElement('div')
          div.addClass('resizer', handle)
          div.setAttribute('data-resize', handle)
          dialogContainer.appendChild(div)
          div.addEventListener('mousedown', resizeStart, false)
          div.addEventListener('touchstart', resizeStart, false)
        })
        //Set onResize event:
        var onResize, resizeDone
        function resizeStart(event) {
          if (event.target.matches('button')) return
          event.preventDefault()
          if (dialogContainer.getAttribute('data-maximize') == '1') return
          resizer = event.target.getAttribute('data-resize')
          let bRect = dialogContainer.getBoundingClientRect()
          initialLeft = bRect.left
          initialTop = bRect.top
          initialWidth = bRect.width
          initialHeight = bRect.height
          document.body.addEventListener('mouseup', resizeEnd, false)
          document.body.addEventListener('touchend', resizeEnd, false)
          document.body.addEventListener('mousemove', resizing, false)
          document.body.addEventListener('touchmove', resizing, false)
          onResize = dialogContainer._jeeDialog.options.onResize
          if (onResize) {
            resizeDone = null
          }
        }
        function resizing(event) {
          let clientX = (typeof event.clientX == 'number') ? event.clientX : event.targetTouches[0].pageX
          let clientY = (typeof event.clientY == 'number') ? event.clientY : event.targetTouches[0].pageY
          if (resizer.includes('top')) {
            dialogContainer.style.top = clientY + 'px'
            let height = initialHeight + (initialTop - clientY)
            if (height > 200) dialogContainer.style.height = height + 'px'
          }
          if (resizer.includes('right')) {
            let width = clientX - initialLeft
            if (width > 350) dialogContainer.style.width = width + 'px'
          }
          if (resizer.includes('bottom')) {
            let height = dialogContainer.style.height = clientY - initialTop
            if (height > 200) dialogContainer.style.height = height + 'px'
          }
          if (resizer.includes('left')) {
            dialogContainer.style.left = clientX + 'px'
            let width = initialWidth + (initialLeft - clientX)
            if (width > 350) dialogContainer.style.width = width + 'px'
          }
          if (onResize) {
            clearTimeout(resizeDone)
            resizeDone = setTimeout(function() { onResize(event) }, 100)
          }
        }
        function resizeEnd(event) {
          document.body.removeEventListener('mouseup', resizeEnd, false)
          document.body.removeEventListener('touchend', resizeEnd, false)
          document.body.removeEventListener('mousemove', resizing, false)
          document.body.removeEventListener('touchmove', resizing, false)
        }
      }
    } else {
      _options = domUtils.extend(dialogContainer._jeeDialog.options, _options)
      var spanTitle = dialogContainer.querySelector('div.jeeDialogTitle > span.title')
      if (spanTitle) spanTitle.innerHTML = _options.title
    }

    if (_options.contentUrl != '') {
      dialogContainer.querySelector('div.jeeDialogContent').load(_options.contentUrl, function() {
        if (_options.callback) _options.callback()
        jeedomUtils.initTooltips()
      })
    } else {
      if (_options.callback) _options.callback()
      jeedomUtils.initTooltips()
    }
    if (_options.show) {
      dialogContainer._jeeDialog.show()
    }

    return dialogContainer
  }

  return exports
})()


/* new jeeCtxMenu({})
Core lib for context menus
*/
var jeeCtxMenu = function(_options) {
  var ctxInstance = { //Always initialize with new jeeCtxMenu({}) or this won't be unique per menu!
    realTrigger: null
  }

  function setPosition(_ctxMenu, _event) {
    //Set main menu (must be built with items!) in DOM position to get calculated BoundingRect:
    Object.assign(_ctxMenu.style, {
      opacity: 0,
      height: null,
      display: null
    })

    //Is there use positionning:
    if (ctxInstance.options.position) {
      ctxInstance.options.position.apply(ctxInstance, [ctxInstance, _event.clientX, _event.clientY])
      _ctxMenu.style.opacity = 1
    } else {
      //Keep mouse hover menu to avoid setting click event to close:
      var bRect = document.body.getBoundingClientRect()
      var cRect = _ctxMenu.getBoundingClientRect()
      var newLeft = _event.clientX - 5
      var newTop = _event.clientY - 5

      //Outside right:
      if (newLeft > (bRect.width - cRect.width)) {
        newLeft = bRect.width - cRect.width
      }
      //Outside bottom:
      if (newTop > (bRect.height - cRect.height)) {
        newTop = bRect.height - cRect.height
      }
      //Too much height:
      if (cRect.height > (bRect.height - 50)) {
        _ctxMenu.style.height = (bRect.height - 50) + 'px'
        newTop = 40
      }
      Object.assign(_ctxMenu.style, {
        left: newLeft + 'px',
        top: newTop + 'px',
        opacity: 1
      })
    }
  }

  function _build(_params) {
    ctxInstance.options.commands = []
    ctxInstance.options.inputs = {}
    ctxInstance.ctxMenu.empty()
    Object.keys(_params.items).forEach((_key) => {
      _buildItem(ctxInstance.ctxMenu, _key, _params.items[_key], _params.callback)
    })
  }

  function _buildItem(_itemContainer, _key, _item, _callback) {
    var itemDiv, itemContDiv
    //Set new menu item:
    itemDiv = document.createElement('div')
    itemDiv.addClass('ctxItem')
    if (isset(_item.className)) itemDiv.addClass(_item.className)
    if (_item.icon != undefined) {
      _item.isHtmlName = true
      _item.name = '<i class="' + _item.icon + '"></i> ' + _item.name
    }
    if (_item.isHtmlName) {
      itemDiv.innerHTML = _item.name
    } else {
      itemDiv.textContent = _item.name
    }

    //Is item a sperator:
    let isSep = new RegExp("^[-]+$").test(_item) // item: '-----'
    if (!isSep && isset(_item.type) && _item.type == 'cm_separator') isSep = true

    if (!isSep) {
      //Reference for dynamic scanning at show()
      _item.menuItem = itemDiv
      ctxInstance.options.commands[_key] = _item
    }

    if (isset(_item.items)) {
      itemDiv.addClass('ctxSubMenu')
      itemContDiv = document.createElement('div')
      itemContDiv.addClass('ctxSubMenuContainer')
      itemContDiv.style.display = 'none'
      itemDiv = _itemContainer.appendChild(itemDiv)
      itemDiv.appendChild(itemContDiv)
      Object.keys(_item.items).forEach((_key) => {
        _buildItem(itemContDiv, _key, _item.items[_key], _callback)
      })
      //Events:
      itemDiv.addEventListener('mouseenter', function(event) {
        let subContainer = event.target.querySelector('div.ctxSubMenuContainer')

        //Reset and 'show' subContainer to get computed rect:
        Object.assign(subContainer.style, {
          opacity: 0,
          height: null,
          display: null
        })

        let bRect = document.body.getBoundingClientRect()
        let tRect = event.target.getBoundingClientRect()
        let sRect = subContainer.getBoundingClientRect()

        let newLeft = tRect.x + tRect.width
        let newTop = tRect.y

        //Outside right:
        if (newLeft >= (bRect.width - sRect.width)) {
          newLeft = tRect.x - sRect.width + 5
        }
        //Outside bottom:
        if (newTop > (bRect.height - sRect.height)) {
          newTop = bRect.height - sRect.height - 10
        }
        //Too much height:
        if (sRect.height > (bRect.height - 50)) {
          subContainer.style.height = (bRect.height - 50) + 'px'
          newTop = 40
        }

        Object.assign(subContainer.style, {
          left: newLeft + 'px',
          top: newTop + 'px',
          opacity: 1
        })
      })
      itemDiv.addEventListener('mouseleave', function(event) {
        var subContainer = event.target.querySelector('div.ctxSubMenuContainer')
        subContainer.style.display = 'none'
      })
    } else if (isSep) {
      itemDiv.addClass('ctxSeparator')
      _itemContainer.appendChild(itemDiv)
    } else {
      if (isset(_item.type) && (_item.type == 'checkbox' || _item.type == 'radio')) {
        itemDiv.addClass('ctxInput')
        var label = document.createElement('label')
        var span = document.createElement('span')
        if (_item.isHtmlName) {
          span.innerHTML = _item.name
        } else {
          span.textContent = _item.name
        }
        var input = document.createElement('input')
        input.type = _item.type
        if (isset(_item.radio)) input.name = _item.radio
        if (isset(_item.selected)) input.checked = _item.selected
        if (isset(_item.value)) input.value = _item.value
        label.appendChild(input)
        label.appendChild(span)

        itemDiv.innerHTML = ''
        itemDiv.appendChild(label)

        ctxInstance.options.inputs[_key] = _item
        ctxInstance.options.inputs[_key].node = input
      }
      //Events
      itemDiv.addEventListener('mouseup', function(event) { //Context menu item click:
        if (event.target.hasClass('disabled')) return
        setTimeout(() => { //Wait for mouseup firing default click
          let then = true
          event.realTrigger = ctxInstance.realTrigger
          ctxInstance.options.trigger = ctxInstance.realTrigger
          if (isset(_item.callback)) { //Per item global
            then = _item.callback.apply(ctxInstance.realTrigger, [_key, ctxInstance.options, event])
          } else if (isset(_item.events) && isset(_item.events.click)) { //Input click callback
            then = _item.events.click.apply(input, [_key, event])
          } else if (ctxInstance.options.callback) { //Default global callback
            then = ctxInstance.options.callback.apply(ctxInstance.realTrigger, [_key, ctxInstance.options, event])
          } else if (_callback) { //Dynamic build global
            then = _callback.apply(ctxInstance.realTrigger, [_key, ctxInstance.options, event])
          }
          if (then != false) {
            ctxInstance.hide(event)
          }
        })
      })
      _itemContainer.appendChild(itemDiv)
    }
  }

  var defaultOptions = {
    selector: false,
    appendTo: 'body',
    items: false,
    className: '',
    autoHide: false,
    zIndex: 12000,
    isDisable: false,
    callback: false, //Default item callback
    build: false, //Dynamic function building called at show
    position: false, //fn called on setPosition
    events: {
      show: function() { }, //Beforte show
      hide: function() { }, //Before hide
    },
  }

  //Merge defaults and submitted options:
  _options = domUtils.extend(defaultOptions, _options)
  if (!_options.selector) return null

  var ctxMenuContainer = document.createElement('div')
  ctxMenuContainer.addClass('jeeCtxMenu')
  if (_options.className != '') ctxMenuContainer.addClass(_options.className)
  ctxMenuContainer.style.display = 'none'
  ctxMenuContainer.style.zIndex = _options.zIndex
  document.querySelector(_options.appendTo)?.appendChild(ctxMenuContainer)

  ctxInstance.options = _options
  ctxInstance.ctxMenu = ctxMenuContainer

  ctxInstance.hideAll = function() {
    document.querySelectorAll('div.jeeCtxMenu').forEach(_ctx => {
      if (isset(_ctx._jeeCtxMenu)) {
        _ctx._jeeCtxMenu.ctxMenu.unseen()
      }
    })
  }
  ctxInstance.enable = function() {
    this.isDisable = false
  }
  ctxInstance.disable = function() {
    this.isDisable = true
  }
  ctxInstance.setInputValues = function(opt, data) { //Set data inputs values according to opt element data-x attributes
    var datasetDomStringMAp = opt.dataset
    for (var _key in data) {
      if (isset(ctxInstance.options.inputs[_key])) {
        ctxInstance.options.inputs[_key].node.jeeValue(datasetDomStringMAp[_key])
      }
    }
  }
  ctxInstance.getInputValues = function(opt, data) { //Store data values as data-x attributes on opt element
    Object.keys(ctxInstance.options.inputs).forEach((_key) => {
      opt.setAttribute('data-' + _key, ctxInstance.options.inputs[_key].node.jeeValue())
    })
  }
  ctxInstance.show = function(_event) {
    if (typeof ctxInstance.options.build === 'function') { //Dynamic build
      var _args = ctxInstance.options.build(ctxInstance.realTrigger)
      if (_args === false) return false
      if (!isset(_args)) _args = {}
      if (!isset(_args.items)) _args.items = false
      if (!isset(_args.callback)) _args.callback = false
      _build(_args)
    }

    //Update disabled items:
    var _item, _key
    for (_key in ctxInstance.options.commands) {
      _item = ctxInstance.options.commands[_key]
      var itemDisabled = (typeof _item.disabled === 'function') ? _item.disabled.apply(ctxInstance.realTrigger, [_item.id, ctxInstance]) : _item.disabled
      if (itemDisabled === true) {
        _item.menuItem.addClass('disabled')
      } else {
        _item.menuItem.removeClass('disabled')
      }
    }

    if (typeof ctxInstance.options.events.show === 'function') ctxInstance.options.events.show.apply(ctxInstance, [ctxInstance])
    setPosition(ctxInstance.ctxMenu, _event) //Will show
  }
  ctxInstance.hide = function(_event) {
    if (typeof ctxInstance.options.events.hide === 'function') ctxInstance.options.events.hide.apply(ctxInstance, [ctxInstance])
    if (typeof ctxInstance.options.build === 'function') {
      this.ctxMenu.empty().unseen()
    } else {
      this.ctxMenu.unseen()
    }
  }
  ctxInstance.destroy = function() {
    document.querySelector(ctxInstance.options.appendTo)?.unRegisterEvent('contextmenu')
    //document.body.unRegisterEvent('mousedown', 'closeContexts')
    this.ctxMenu.remove()
  }

  ctxMenuContainer._jeeCtxMenu = ctxInstance //Usable outside scope

  /* Events
  */
  document.querySelector(ctxInstance.options.appendTo)?.registerEvent('contextmenu', function(event) {
    ctxInstance.hideAll()
    if (event.target.matches(ctxInstance.options.selector + ', ' + ctxInstance.options.selector + ' *') || event.target.closest(ctxInstance.options.selector) != null) {
      event.preventDefault()
      if (ctxInstance.isDisable) return
      event.stopImmediatePropagation()
      event.stopPropagation()
      ctxInstance.realTrigger = event.target.closest(ctxInstance.options.selector) //Store real element triggering context menu for subsequent calls
      event.realTrigger = ctxInstance.realTrigger
      ctxInstance.show(event)
      return
    }
  }, { capture: true, bubble: true })

  /* Main context menu should always open under mouse to allways trigger mouseleave
  let doesExist = domUtils.registeredEvents.filter(l => l.id == 'closeContexts').length > 0
  if (!doesExist) { //Only one event when multiple context menus
    document.body.registerEvent('mousedown', function closeContexts(event) {
      if (event.target.closest('div.ctxItem') != null) return //Avoid closing itself before item click!
      ctxInstance.hideAll()
    })
  }
  */

  if (_options.autoHide) {
    ctxMenuContainer.registerEvent('mouseleave', function(event) {
      setTimeout(function() {
        if (!event.target.closest('div.jeeCtxMenu').isVisible()) return //May be closed by click, avoir twice hide
        ctxInstance.hide(event)
      }, 100)
    })
  }else{
    document.addEventListener('click', event => {
      if (ctxMenuContainer.contains(event.target)) {
        return;
      }
      setTimeout(function() {
        if (!ctxMenuContainer.closest('div.jeeCtxMenu').isVisible()) return //May be closed by click, avoir twice hide
        ctxInstance.hide(event)
      }, 100)
    })
  }

  if (_options.items) {
    _build({
      items: _options.items
    })
  }

  return ctxInstance
}


/* new jeeFileUploader({})
Core lib for input upload file
*/
var jeeFileUploader = function(_options) {
  var defaultOptions = {
    fileInput: false,
    replaceFileInput: false,
    singleFileUploads: true,
    limitMultiFileUploads: undefined,
    limitUploadFileSize: undefined,
    accept: undefined,
    url: '',
    dataType: 'json',
    add: false,
    done: false,
  }
  if (!_options.fileInput) {
    console.warn('jeeFileUploader: no fileInput provided.')
    return null
  }

  //Merge defaults and submitted options:
  if (!isset(_options.singleFileUploads) && _options.fileInput.getAttribute('multiple') == 'multiple') {
    _options.singleFileUploads = false
  }
  _options = domUtils.extend(defaultOptions, _options)

  if (!_options.url) {
    let dataurl = _options.fileInput.getAttribute('data-url')
    if (dataurl != null) {
      _options.url = dataurl
    }
  }

  if (_options.accept) _options.fileInput.setAttribute('accept', _options.accept)

  var displayLimit = null
  if (_options.limitUploadFileSize != undefined) {
    displayLimit = domUtils.octetsToHumanSize(_options.limitUploadFileSize)
  }

  //Event:
  _options.fileInput.registerEvent('change', function jeeFileUpload(event) {
    var data = new FormData()
    if (_options.singleFileUploads) {
      if (event.target.files.length > 1) {
        jeeDialog.alert('{{Vous ne pouvez uploader qu\'un seul fichier.}}')
        return false
      }
      if (_options.limitUploadFileSize != undefined) {
        if (event.target.files[0].size > _options.limitUploadFileSize) {
          jeeDialog.alert('{{Taille de fichier trop importante.}} (' + displayLimit + ')')
          return false
        }
      }

      data.append('file', event.target.files[0])
      if (typeof _options.add === 'function') {
        _options.data = data
        _options.add.apply(_options.fileInput, [event, _options])
      }
    } else {
      let files = event.target.files
      for (let i = 0; i < files.length; i++) {

        if (_options.limitMultiFileUploads != undefined && i > _options.limitMultiFileUploads) break
        let file = files.item(i)
        if (_options.limitUploadFileSize != undefined) {
          if (file.size > _options.limitUploadFileSize) {
            jeeDialog.alert('{{Taille de fichier trop importante.}} (' + displayLimit + ')')
            return false
          }
        }

        if (typeof _options.add === 'function') {
          let addData = new FormData()
          data.append('file', file)
          _options.data = data
          _options.add.apply(_options.fileInput, [event, _options])
        } else {
          data.append('file-' + i, file)
        }
      }
    }

    if (!_options.add) {
      domUtils.ajax({
        url: _options.url,
        async: true,
        dataType: 'json',
        type: 'POST',
        data: data,
        processData: false,
        error: function() {
          console.warn('jeeFileUploader: ajax error.')
        },
        success: function(data) {
          if (_options.done) _options.done.apply(_options.fileInput, [event, { result: data }])
        },
      })
    }
  })

  _options.destroy = function() {
    this.fileInput.unRegisterEvent('change', 'jeeFileUpload')
  }
  _options.submit = function() {
    domUtils.ajax({
      url: _options.url,
      async: true,
      dataType: 'json',
      type: 'POST',
      data: _options.data,
      processData: false,
      error: function() {
        console.warn('jeeFileUploader: ajax error.')
      },
      success: function(data) {
        if (_options.done) _options.done.apply(_options.fileInput, [event, { result: data }])
      },
    })
  }

  return _options
}


/* new jeeResize(selector, {})
Core lib for resizeable elements
*/
var jeeResize = function(_selector, _options) {
  var elements = document.querySelectorAll(_selector)
  if (elements.length == 0) {
    console.warn('jeeResize: no elements found. selector:', _selector)
    return null
  }

  var defaultOptions = {
    cancel: false,
    state: true,
    containment: false,
    handles: ['top', 'top-right', 'right', 'bottom-right', 'bottom', 'bottom-left', 'left', 'top-left'],
    start: false,
    resize: false,
    stop: false,
    allowHeightOversize: false
  }
  _options = domUtils.extend(defaultOptions, _options)

  var currentRszr = {}

  elements.forEach(elResize => {
    elResize._jeeResize = {}
    elResize._jeeResize.options = _options
    elResize._jeeResize.element = elResize
    elResize._jeeResize.destroy = function() {
      elResize.querySelectorAll('.jeeresizer').forEach(_rszr => {
        _rszr.remove()
      })
    }

    _options.handles.forEach(handle => {
      var div = document.createElement('div')
      div.addClass('jeeresizer', handle)
      div.setAttribute('data-resize', handle)
      elResize.appendChild(div)
      div.addEventListener('pointerdown', resizeStart, false)
      div.addEventListener('touchstart', resizeStart, false)
    })
  })

  var initialLeft, initialTop, initialWidth, initialHeight
  function resizeStart(event) {
    event.preventDefault()
    event.stopPropagation()
    event.stopImmediatePropagation()

    currentRszr = event.target.parentNode._jeeResize
    currentRszr.rszElement = this
    currentRszr.resizer = this.getAttribute('data-resize')
    if (currentRszr.options.cancel !== false && event.target.parentNode.hasClass(currentRszr.options.cancel)) return false

    var next
    if (currentRszr.options.start) {
      next = currentRszr.options.start.apply(currentRszr.rszElement, [event, currentRszr.element])
    }
    if (next === false) return

    if (event.target.parentNode._jeeResize.options.containment !== false) {
      currentRszr.containmentRect = currentRszr.options.containment.getBoundingClientRect()
    } else {
      currentRszr.containmentRect = document.body.getBoundingClientRect()
    }

    let bRect = event.target.parentNode.getBoundingClientRect()
    initialLeft = bRect.left
    initialTop = bRect.top
    initialWidth = bRect.width
    initialHeight = bRect.height
    document.body.addEventListener('pointerup', resizeEnd, false)
    document.body.addEventListener('touchend', resizeEnd, false)
    document.body.addEventListener('pointermove', resizing, false)
    document.body.addEventListener('touchmove', resizing, false)
  }
  function resizing(event) {
    try {
      var clientX = event.clientX || event.targetTouches[0].pageX
      var clientY = event.clientY || event.targetTouches[0].pageY
      var element = currentRszr.rszElement.parentNode
    } catch (error) {
      return
    }
    if (element == null) return

    if (currentRszr.resizer.includes('left')) {
      let minLeft = currentRszr.containmentRect.left
      let maxLeft = currentRszr.containmentRect.right
      let left = clientX
      if (left >= minLeft && left <= maxLeft) {
        element.style.left = left - currentRszr.containmentRect.left + 'px'
        let width = initialWidth + (initialLeft - clientX)
        width = width <= currentRszr.containmentRect.width ? width : currentRszr.containmentRect.width
        element.style.width = width + 'px'
      }
    }

    if (currentRszr.resizer.includes('top')) {
      let minTop = currentRszr.containmentRect.top
      let maxTop = currentRszr.containmentRect.bottom
      let top = clientY
      if (top >= minTop && top <= maxTop) {
        element.style.top = top - currentRszr.containmentRect.top + 'px'
        let height = initialHeight + (initialTop - clientY)
        height = height <= currentRszr.containmentRect.height ? height : currentRszr.containmentRect.height
        element.style.height = height + 'px'
      }
    }

    if (currentRszr.resizer.includes('right')) {
      let maxWidth = currentRszr.containmentRect.width - element.offsetLeft
      let width = clientX - initialLeft
      if (width <= maxWidth) {
        element.style.width = width + 'px'
      }
    }

    if (currentRszr.resizer.includes('bottom')) {
      let maxHeight = currentRszr.containmentRect.height - element.offsetTop
      let height = clientY - initialTop
      if (_options.allowHeightOversize || height <= maxHeight) {
        element.style.height = height + 'px'
      }
    }
    if (currentRszr.options.resize) {
      currentRszr.options.resize.apply(currentRszr.rszElement, [event, currentRszr.element])
    }
  }
  function resizeEnd(event) {
    document.body.removeEventListener('pointerup', resizeEnd, false)
    document.body.removeEventListener('touchend', resizeEnd, false)
    document.body.removeEventListener('pointermove', resizing, false)
    document.body.removeEventListener('touchmove', resizing, false)
    if (currentRszr.options.end) {
      currentRszr.options.end.apply(currentRszr.rszElement, [event, currentRszr.element])
    }
  }

  return _options
}
