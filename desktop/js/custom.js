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

var editorDesktopJS = null
var editorDesktopCSS = null
var editorMobileJS = null
var editorMobileCSS = null
var $tabContainer = $('#div_pageContainer .tab-content')

jeedom.config.load({
  configuration: $('#div_spanAlertMessage').getValues('.configKey:not(.noSet)')[0],
  error: function(error) {
    $.fn.showAlert({
      message: error.message,
      level: 'danger'
    })
  },
  success: function(data) {
    $('#div_spanAlertMessage').setValues(data, '.configKey')
    modifyWithoutSave = false
  }
})

setTimeout(function() {
  editorDesktopJS = CodeMirror.fromTextArea(document.getElementById("ta_jsDesktopContent"), {
    lineNumbers: true,
    mode: "text/javascript",
    matchBrackets: true,
    viewportMargin: Infinity,
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
  })
  editorDesktopJS.setOption("extraKeys", {
    "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
    "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
  })
  editorDesktopCSS = CodeMirror.fromTextArea(document.getElementById("ta_cssDesktopContent"), {
    lineNumbers: true,
    mode: "text/css",
    matchBrackets: true,
    viewportMargin: Infinity,
    foldGutter: true,
    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
  })
  editorDesktopCSS.setOption("extraKeys", {
    "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
    "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
  })
  $(window).resize(function() {
    editorDesktopJS.setSize(null, $tabContainer.height() - 50)
    editorDesktopCSS.setSize(null, $tabContainer.height() - 50)
    if (editorMobileJS) editorMobileJS.setSize(null, $tabContainer.height() - 50)
    if (editorMobileCSS) editorMobileCSS.setSize(null, $tabContainer.height() - 50)
  })
  $(window).resize()
}, 250)

$('a[data-toggle="tab"][href="#mobile"]').on('shown.bs.tab', function() {
  if (editorMobileJS == null) {
    editorMobileJS = CodeMirror.fromTextArea(document.getElementById("ta_jsMobileContent"), {
      lineNumbers: true,
      mode: "text/javascript",
      matchBrackets: true,
      viewportMargin: Infinity,
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
    })
    editorMobileJS.setOption("extraKeys", {
      "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
      "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
    })
  }
  editorMobileJS.setSize(null, $tabContainer.height() - 50)

  if (editorMobileCSS == null) {
    editorMobileCSS = CodeMirror.fromTextArea(document.getElementById("ta_cssMobileContent"), {
      lineNumbers: true,
      mode: "text/css",
      matchBrackets: true,
      viewportMargin: Infinity,
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
    })
    editorMobileCSS.setOption("extraKeys", {
      "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
      "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
    })
  }
  editorMobileCSS.setSize(null, $tabContainer.height() - 50)
})

$('.saveCustom').on('click', function() {
  var version = $(this).attr('data-version')
  var type = $(this).attr('data-type')
  var content = ''
  var editor = null
  if (version == 'desktop' && type == 'js') {
    editor = editorDesktopJS
  }
  if (version == 'desktop' && type == 'css') {
    editor = editorDesktopCSS
  }
  if (version == 'mobile' && type == 'js') {
    editor = editorMobileJS
  }
  if (version == 'mobile' && type == 'css') {
    editor = editorMobileCSS
  }
  if (editor != null) {
    content = editor.getValue()
  }

  jeedom.config.save({
    configuration: $('#div_spanAlertMessage').getValues('.configKey')[0],
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedom.saveCustom({
        version: version,
        type: type,
        content: content,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $.fn.showAlert({
            message: 'Sauvegarde réussie',
            level: 'success'
          })
        }
      })
    }
  })
})