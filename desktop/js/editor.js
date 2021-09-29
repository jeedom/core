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


$(function() {
  CodeMirror.modeURL = "3rdparty/codemirror/mode/%N/%N.js"

  var hash = 'l1_'
  if (rootPath != '') {
  	hash += btoa(rootPath).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '.').replace(/\.+$/, '')
  }
  var lang = jeedom_langage.substring(0, 2)

  var options = {
    url: 'core/php/editor.connector.php',
    cssAutoLoad: false,
    lang: lang,
    startPathHash: hash,
    rememberLastDir: false,
    defaultView: 'list',
    sort: 'kindDirsFirst',
    sortDirect: 'kindDirsFirst',
    contextmenu: {
      cwd: ['reload', 'back', '|', 'upload', 'mkdir', 'mkfile', 'paste', '|', 'info'],
      files: ['cmdedit', 'edit', '|', 'rename' ,'|', 'getfile' , 'download', '|', 'copy', 'cut', 'paste', 'duplicate', '|','rm', '|', 'archive', 'extract', '|', 'info', 'places']
    },
    uiOptions: {
      toolbar: [
        ['back', 'forward'],
        ['reload', 'sort'],
        ['home', 'up'],
        ['mkdir', 'mkfile', 'upload','download'],
        ['info'],
        ['copy', 'cut', 'paste'],
        ['edit','duplicate', 'rename', 'rm'],
        ['extract', 'archive'],
        ['search'],
        ['view']
      ]
    },
    handlers: {
      dblclick: function(event, elfinderInstance)
      {
        elfinderInstance.exec('edit')
        return false
      }
    },
    commandsOptions: {
      edit: {
        editors: [
          {
          load : function(textarea) {
            self = this
            var elfinderInstance = $('#elfinder').elfinder(options).elfinder('instance')
            var fileUrl = elfinderInstance.url(self.file.hash)
            fileUrl = fileUrl.replace('/core/php/../../', '')
            var $modal = $(textarea).closest('.ui-front')
            $modal.find('.elfinder-dialog-title').html(fileUrl)

            this.myCodeMirror = CodeMirror.fromTextArea(textarea, {
              styleActiveLine: true,
              lineNumbers: true,
              lineWrapping: true,
              matchBrackets: true,
              autoRefresh: true,
              foldGutter: true,
              gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
            })
            var editor = this.myCodeMirror

            //Auto mode set:
            var info, m, mode, spec;
            if (!info) {
              info = CodeMirror.findModeByMIME(self.file.mime);
            }
            if (!info && (m = self.file.name.match(/.+\.([^.]+)$/))) {
              info = CodeMirror.findModeByExtension(m[1]);
            }
            if (info) {
              mode = info.mode
              spec = info.mime
              editor.setOption('mode', spec)
              CodeMirror.autoLoadMode(editor, mode)
            }

            //is python ?
            if (self.file.mime == 'text/x-python') {
              self.myCodeMirror.setOption('mode', {
                  name: "python",
                  version: 3,
                  singleLineStringErrors: false
                }
              )
              self.myCodeMirror.setOption('indentUnit', 4)
              self.myCodeMirror.setOption('smartIndent', false)
            }

            $(".cm-s-default").style('height', '100%', 'important')
            editor.setOption('theme', 'monokai')

            //expand on resize modal:
            $('.elfinder-dialog-edit').resize(function() {
              editor.refresh()
            })
            $modal.width('75%').css('left', '15%')

            setTimeout(function() {
              editor.scrollIntoView({line:0, char:0}, 20)
              editor.setOption("extraKeys", {
                "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
                "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
              })

            }, 250)
          },
          close : function(textarea, instance) {
            //this.myCodeMirror = null;
          },
          save : function(textarea, editor) {
            textarea.value = this.myCodeMirror.getValue();
            //this.myCodeMirror = null;
            }
          }
        ]
      },
    }
  }
  var elfinstance
  elfinstance = $('#elfinder').elfinder(options).elfinder('instance')

  $('#elfinder').css("height", $(window).height() - 50)
  $('.ui-state-default.elfinder-navbar.ui-resizable').css('height', '100%')

  elfinstance.one('init', function(event) {
    killTooltips()
  })
  elfinstance
    .bind('open', function(event) {
      killTooltips()
    })
    .bind('contextmenucreate', function(event) {
      setTimeout(function() {
        $('.elfinder-button-icon-edit').next().text('{{Editer}}')
      }, 0)
    })
})

function killTooltips() {
  setTimeout(function() {
  try {
    $('.elfinder-workzone .tooltipstered').tooltipster('destroy')
  } catch(error) {}
  try {
    $('.elfinder-workzone [title]').removeAttr('title')
  } catch(error) {}
  }, 500)
}


//resize explorer in browser window:
$(window).resize(function() {
  $('#elfinder').css("width", $(window).width())
  $('#elfinder').css("height", $(window).height() - 50)
})