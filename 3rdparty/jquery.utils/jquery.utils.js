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

/**************UI*****************************/
(function($) {
  var scriptsCache = []
  jQuery.include = function(_path, _callback) {
    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
      if (options.dataType == 'script' || originalOptions.dataType == 'script') {
        options.cache = true
      }
    })
    for (var i in _path) {
      if (jQuery.inArray(_path[i], scriptsCache) == -1) {
        var extension = _path[i].substr(_path[i].length - 3)
        if (extension == 'css') {
          $('<link rel="stylesheet" href="' + _path[i] + '" type="text/css" />').appendTo('head')
        }
        if (extension == '.js') {
          if (_path[i].indexOf('?file=') >= 0) {
            $('<script type="text/javascript" src="' + _path[i] + '"></script>').appendTo('head')
          } else {
            $('<script type="text/javascript" src="core/php/getResource.php?file=' + _path[i] + '"></script>').appendTo('head')
          }
        }

        scriptsCache.push(_path[i])
      }
    }
    $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
      if (options.dataType == 'script' || originalOptions.dataType == 'script') {
        options.cache = false
      }
    })
    _callback()
    return
  }

  /***************************Fast Vanilla div emptying************************/
  jQuery.clearDivContent = function(_id = '') {
    if (_id == '') return
    var contain = document.getElementById(_id)
    if (contain) {
      while (contain.firstChild) {
        contain.removeChild(contain.firstChild)
      }
    }
  }

  /********************************loading************************/
  jQuery.showLoading = function() {
    $('#div_jeedomLoading').show()
  }
  jQuery.hideLoading = function() {
    $('#div_jeedomLoading').hide()
  }

  /*********************jquery alert*************************************/
  jQuery.fn.showAlert = function(_options) {
    var options = init(_options, {})
    options.title = init(options.title, '')
    options.message = init(options.message, '')
    options.level = init(options.level, '')
    options.emptyBefore = init(options.emptyBefore, false)
    options.timeOut = init(options.timeOut, parseInt(jeedom.theme['interface::toast::duration']) * 1000)
    options.extendedTimeOut = init(options.extendedTimeOut, parseInt(jeedom.theme['interface::toast::duration']) * 1000)
    if (options.level == 'danger') {
      options.timeOut = 0
    }
    if ($.mobile) {
      if (options.ttl == 0) {
        options.ttl = 5000
      }
      new $.nd2Toast({
        message: options.message,
        ttl: options.ttl
      })
      return
    }

    options.attachTo = init(options.attach, false)

    jeeDialog.toast(options)

    /*
    var options = init(_options, {})
    options.message = init(options.message, '')
    options.level = init(options.level, '')
    options.emptyBefore = init(options.emptyBefore, false)
    options.show = init(options.show, true)
    if (!options.ttl) {
      if (options.level == 'danger') {
        options.ttl = 0
      } else {
        options.ttl = 5000
      }
    }
    if ($.mobile) {
      if (options.ttl == 0) {
        options.ttl = 5000
      }
      new $.nd2Toast({
        message: options.message,
        ttl: options.ttl
      })
      return
    }

    if (options.level == 'danger') options.level = 'error'
    if (options.emptyBefore == true) {
      jeeDialog.clearToasts()
    }
    let options_toastr = jeedomUtils.toastrUIoptions
    options_toastr.timeOut = options.ttl
    toastr[options.level](options.message, ' ', options_toastr)



    if (this.attr('id') && this.attr('id') != 'div_alert') {
      try {
        var modal = $(this).parent('.ui-dialog-content')
        $("#toast-container").appendTo(modal).css('position', 'absolute')
      } catch (error) {
        console.error('showAlert: ' + error)
      }
    }
    */
  }

  jQuery.fn.hideAlert = function() {
    //old div_alert:
    $('#jqAlertSpacer' + $(this).attr('id')).remove()
    $(this).text('').hide()
    if (typeof jeedomUtils.initRowOverflow == 'function') {
      jeedomUtils.initRowOverflow()
    }

    jeeDialog.clearToasts()
    return $(this)
  }

  jQuery.hideAlert = function() {
    if (!$.mobile) {
      //Old div_alert:
      $('.jqAlert').text('')
      $('.jqAlert').hide()
      if (typeof jeedomUtils.initRowOverflow == 'function') {
        jeedomUtils.initRowOverflow()
      }
    }
    jeeDialog.clearToasts()
  }

  /**********************Jquery.value******************************/

  jQuery.fn.findAtDepth = function(selector, maxDepth) {
    var depths = [], i
    if (maxDepth > 0) {
      for (i = 1; i <= maxDepth; i++) {
        depths.push('> ' + new Array(i).join('* > ') + selector)
      }
      selector = depths.join(', ')
    }
    return this.find(selector)
  }

  jQuery.fn.value = function(_value) {
    var $this = $(this)
    if (isset(_value)) {
      if ($this.length > 1) {
        var idx
        for (idx = 0; idx < $this.length; idx++) {
          $($this[idx]).value(_value)
        }
      } else {
        if ($this.is('input')) {
          if ($this.attr('type') == 'checkbox') {
            if (init(_value) === '') {
              return
            }
            $this.prop('checked', (init(_value) == 1) ? true : false)
          } else if ($this.attr('type') == 'radio') {
            $this.prop('checked', (init(_value) == 1) ? true : false)
          } else {
            $this.val(init(_value))
          }
        } else if ($this.is('select')) {
          if (init(_value) == '') {
            $this.find('[value=""]').prop('selected', true)
          } else {
            $this.val(init(_value))
          }
        } else if ($this.is('textarea')) {
          $this.val(init(_value))
        } else if ($this.is('span, div, p, pre')) {
          $this.html(init(_value))
        } else if ($this.is('button') && $this.hasClass('dropdown-toggle')) {
          var button = $this
          $this.closest('div.dropdown').find('ul.dropdown-menu li a').each(function() {
            if ($this.attr('data-value') == _value) {
              button.html($this.text() + '<span class="caret"></span>')
              button.attr('value', _value)
            }
          })
        }
        $this.trigger('change')
      }
    } else {
      var value = ''
      if ($this.is('input, select, textarea')) {
        if ($this.attr('type') == 'checkbox' || $this.attr('type') == 'radio') {
          value = ($this.is(':checked')) ? '1' : '0'
        } else {
          value = $this.val()
        }
      }
      if ($this.is('div, span, p')) {
        value = $this.html()
      }
      if ($this.is('a') && $this.attr('value') != undefined) {
        value = $this.attr('value')
      }
      if (value == '') {
        value = $this.val()
      }
      return value
    }
  }

  jQuery.fn.getValues = function(_attr, _depth) {
    var values = []
    var $this = $(this)

    var idx, idx2, value, depthFound, $that, elValue, l1key, l2key, l3key
    for (idx = 0; idx < $this.length; idx++) {
      value = {}
      depthFound = $($this[idx]).findAtDepth(_attr, init(_depth, 0))
      for (idx2 = 0; idx2 < depthFound.length; idx2++) {
        $that = $(depthFound[idx2])
        elValue = $that.value()
        try {
          if ($.trim(elValue).substr(0, 1) == '{') {
            elValue = JSON.parse($that.value())
          }
        } catch (e) { }
        l1key = $that.attr('data-l1key')
        if (l1key != undefined && l1key != '') {
          l2key = $that.attr('data-l2key')
          if (l2key !== undefined) {
            if (!isset(value[l1key])) {
              value[l1key] = {}
            }
            l3key = $that.attr('data-l3key')
            if (l3key !== undefined) {
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
      values.push(value)
    }

    return values
  }

  jQuery.fn.setValues = function(_object, _attr) {
    var $this = $(this)
    for (var i in _object) {
      if ((!is_array(_object[i]) || $this.find(_attr + '[data-l1key="' + i + '"]').attr('multiple') == 'multiple') && !is_object(_object[i])) {
        $this.find(_attr + '[data-l1key="' + i + '"]').value(_object[i])
      } else {
        for (var j in _object[i]) {
          if ((is_array(_object[i][j]) || $this.find(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]').attr('multiple') == 'multiple') || is_object(_object[i][j])) {
            for (var k in _object[i][j]) {
              $this.find(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"][data-l3key="' + k + '"]').value(_object[i][j][k])
            }
          } else {
            $this.find(_attr + '[data-l1key="' + i + '"][data-l2key="' + j + '"]').value(_object[i][j])
          }
        }
      }
    }
  }


  /**************LI FILTER*****************************/

  jQuery.initTableFilter = function() {
    $("body").delegate("ul li input.filter", 'keyup', function() {
      $(this).closest('ul').ulFilter()
    })
  }


  jQuery.fn.ulFilter = function() {
    var ul = $(this)
    var li = $(this).find('li:not(.filter):not(.nav-header):first')
    var find = 'li.filter input.filter'
    delete inputs
    var inputs = new Array()
    ul.find(find).each(function(i) {
      var filterOn = ''
      if ($(this).is(':visible')) {
        var value = $(this).value()
        var filterOn = $(this).attr('filterOn')
      }
      if (filterOn != '' && value != '') {
        var infoInput = new Array()
        infoInput[0] = filterOn
        infoInput[1] = value.toLowerCase()
        inputs.push(infoInput)
      }
    })
    var searchText = 1
    var showLi = true
    $(this).find('li:not(.filter):not(.nav-header)').each(function() {
      showLi = true
      for (var i = 0; i < inputs.length; i++) {
        searchText = $(this).find('a').text().toLowerCase().stripAccents().indexOf(inputs[i][1].stripAccents())
        if (searchText < 0) {
          showLi = false
          break
        }
      }
      if (showLi) {
        $(this).show()
      } else {
        $(this).hide()
      }
    })
    return this
  }

  String.prototype.stripAccents = function() {
    var in_chrs = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
      out_chrs = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY',
      transl = {}
    eval('var chars_rgx = /[' + in_chrs + ']/g')
    for (var i = 0; i < in_chrs.length; i++) {
      transl[in_chrs.charAt(i)] = out_chrs.charAt(i)
    }
    return this.replace(chars_rgx, function(match) {
      return transl[match]
    })
  }
})(jQuery);

/**************OTHERS*****************************/
(function($) {
  if ($.fn.style) {
    return
  }

  // Escape regex chars with \
  var escape = function(text) {
    return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&")
  }

  // For those who need them (< IE 9), add support for CSS functions
  var isStyleFuncSupported = !!CSSStyleDeclaration.prototype.getPropertyValue
  if (!isStyleFuncSupported) {
    CSSStyleDeclaration.prototype.getPropertyValue = function(a) {
      return this.getAttribute(a)
    }
    CSSStyleDeclaration.prototype.setProperty = function(styleName, value, priority) {
      this.setAttribute(styleName, value)
      var priority = typeof priority != 'undefined' ? priority : ''
      if (priority != '') {
        // Add priority manually
        var rule = new RegExp(escape(styleName) + '\\s*:\\s*' + escape(value) +
          '(\\s*;)?', 'gmi')
        this.cssText =
          this.cssText.replace(rule, styleName + ': ' + value + ' !' + priority + ';')
      }
    }
    CSSStyleDeclaration.prototype.removeProperty = function(a) {
      return this.removeAttribute(a)
    }
    CSSStyleDeclaration.prototype.getPropertyPriority = function(styleName) {
      var rule = new RegExp(escape(styleName) + '\\s*:\\s*[^\\s]*\\s*!important(\\s*;)?',
        'gmi')
      return rule.test(this.cssText) ? 'important' : ''
    }
  }

  // The style function
  $.fn.style = function(styleName, value, priority) {
    // DOM node
    var node = this.get(0)
    // Ensure we have a DOM node
    if (typeof node == 'undefined') {
      return this
    }
    // CSSStyleDeclaration
    var style = this.get(0).style
    if (!style) {
      return this
    }
    // Getter/Setter
    if (typeof styleName != 'undefined') {
      if (typeof value != 'undefined') {
        // Set style property
        priority = typeof priority != 'undefined' ? priority : ''
        style.setProperty(styleName, value, priority)
        return this
      } else {
        // Get style property
        return style.getPropertyValue(styleName)
      }
    } else {
      // Get CSSStyleDeclaration
      return style
    }
  }

  $.fn.hasAttr = function(name) {
    return this.attr(name) !== undefined
  }
})(jQuery);

/**************WIDGETS*****************************/
(function($) {
  $.issetWidgetOptParam = function(_def, _param) {
    if (_def != '#' + _param + '#') return true
    return false
  }

  $.createWidgetSlider = function(_options) {
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
})(jQuery)
