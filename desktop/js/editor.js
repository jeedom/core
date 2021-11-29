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

var _elfInstance

$(function() {
  CodeMirror.modeURL = "3rdparty/codemirror/mode/%N/%N.js"

  var hash = 'l1_'
  var lang = jeedom_langage.substring(0, 2)

  var options = {
    url: 'core/php/editor.connector.php',
    cssAutoLoad: false,
    lang: lang,
    startPathHash: hash,
    rememberLastDir: false,
    defaultView: 'list',
    sound: false,
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
        ['view'],
        ['preference']
      ]
    },
    handlers: {
      dblclick: function(event, elfinderInstance)
      {
        elfinderInstance.exec('edit')
        return false
      },
      init: function(event, elfinderInstance)
      {
        if (editorType == 'custom') {
          elfinderInstance._commands.jee_onoffcustom.getActive()
        }
        killTooltips()
      },
      open: function(event, elfinderInstance)
      {
        killTooltips()
      },
    },
    commandsOptions: {
      edit: {
        editors: [
          {
            info : {
              name : '{{Editer}}'
            },
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

              $modal.width('75%').css('left', '15%')

              //expand on resize modal:
              $('.elfinder-dialog-edit').resize(function() {
                editor.refresh()
              })

              setTimeout(function() {
                editor.scrollIntoView({line:0, char:0}, 20)
                editor.setOption("extraKeys", {
                  "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
                  "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
                })

              }, 250)
            },
            close : function(textarea, instance) {
              //this.myCodeMirror = null
            },
            save : function(textarea, editor) {
              textarea.value = this.myCodeMirror.getValue()
              //this.myCodeMirror = null
            }
          }
        ]
      },
    }
  }

  //custom editor settings:
  if (editorType != '') {
    //remove places in toolbar:
    options.ui = ['toolbar', 'tree', 'path', 'stat']

    if (editorType == 'widget') {
      options = setCommandCreatewidget(options)
      options.url = 'core/php/editor.connector.php?type=widget'
      options.startPathHash = getHashFromPath('data/customTemplates')
    }

    if (editorType == 'custom') {
      options = setCommandCustom(options)
      options.url = 'core/php/editor.connector.php?type=custom'
      options.startPathHash = getHashFromPath('desktop/custom')
    }
  }

  _elfInstance = $('#elfinder').elfinder(options).elfinder('instance')

  $('#elfinder').css("height", $(window).height() - 50)
  $('.ui-state-default.elfinder-navbar.ui-resizable').css('height', '100%')
})


function setCommandCreatewidget(options) {
  $('#bt_getHelpPage').attr('data-page','widgets')
  //initiate widget options modal:
  $("#md_widgetCreate").dialog({
    closeText: '',
    autoOpen: false,
    modal: true,
    height: 280,
    width: 300,
    open: function() {
      $("body").css({overflow: 'hidden'})
    },
    beforeClose: function(event, ui) {
      $("body").css({overflow: 'inherit'})
    }
  })

  //button create inside options modal:
  $('#bt_widgetCreate').off('click').on('click', function() {
    if ($('#sel_widgetSubtype').value() == '') {
      $.fn.showAlert({message: '{{Le sous-type ne peut être vide}}', level: 'danger'})
      return
    }
    if ($('#in_widgetName').value() == '') {
      $.fn.showAlert({message: '{{Le nom ne peut être vide}}', level: 'danger'})
      return
    }
    var name = 'cmd.'+$('#sel_widgetType').value()+'.'+$('#sel_widgetSubtype').value()+'.'+$('#in_widgetName').value()+'.html'
    var filePath = 'data/customTemplates/' + $('#sel_widgetVersion').value() + '/'
    jeedom.createFile({
      path : filePath,
      name :name,
      error: function(error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function() {
        $("#md_widgetCreate").dialog('close')
        $.fn.showAlert({message: '{{Fichier enregistré avec succès}}', level: 'success'})
        var hash = getHashFromPath(filePath.replace('data/customTemplates/', '').replace('/', ''))
        _elfInstance.exec('open', hash)
        //_elfInstance.exec('reload')

        var path = filePath.replace('data/customTemplates/', '') + name
        hash = getHashFromPath(path)
        setTimeout(function() {
          _elfInstance.exec('edit', hash)
        }, 350)
      }
    })
  })

  //new custom command in elfinder:
  elFinder.prototype._options.commands.push('jee_createWidget')
  options.uiOptions.toolbar.push(['jee_createWidget'])

  elFinder.prototype.commands.jee_createWidget = function() {
    this.init = function() {
        this.title = this.fm.i18n("{{Créer un Widget}}")
    }
    this.exec = function(hashes) {
      $('#md_widgetCreate').dialog({title: "{{Options}}"}).dialog('open')
      $('#sel_widgetType').trigger('change')

      $("#md_widgetCreate").keydown(function (event) {
          if (event.keyCode == $.ui.keyCode.ENTER) {
              $('#bt_widgetCreate').trigger('click')
          }
      })
      return $.Deferred().done()
    }
    this.getstate = function() {
      return 0
    }
  }

  return options
}

function setCommandCustom(options) {
  $('#bt_getHelpPage').attr('data-page','custom')
  //new custom command in elfinder:
  elFinder.prototype._options.commands.push('jee_onoffcustom')
  options.uiOptions.toolbar.push(['jee_onoffcustom'])
  elFinder.prototype.commands.jee_onoffcustom = function() {
    this.init = function() {
      if (customActive == '1') {
        this.config = 1
      } else {
        this.config = 0
      }
    }
    this.exec = function(hashes) {
      this.config = 1-this.config
      //save config:
      jeedom.config.save({
        configuration: {
          'enableCustomCss': this.config.toString()
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
          this.config = 1-this.config
        },
        success: function(data) {
          $.fn.showAlert({
            message: '{{Configutation sauvegardée}}',
            level: 'success'
          })
        }
      })
      this.getActive()
      return $.Deferred().done()
    }
    this.getstate = function() {
      return 0
    }
    this.getActive = function() {
      var myClass = ''
      var myIcon = ''
      if (this.config == 1) {
        this.title = this.fm.i18n("{{Activé}}")
        myClass = 'btn-warning'
        myIcon = ' <i class="fas fa-toggle-on"></i>'
      } else {
        this.title = this.fm.i18n("{{Désactivé}}")
        myClass = 'btn-success'
        myIcon = ' <i class="fas fa-toggle-off"></i>'
      }
      $('#elfinder .elfinder-button-icon-jee_onoffcustom + .elfinder-button-text')
        .removeClass('btn-success btn-warning')
        .addClass(myClass)
        .text(this.title)
        .append(myIcon)
    }
  }
  return options
}

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

function getHashFromPath(_path) {
  return 'l1_' + btoa(_path).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '.').replace(/\.+$/, '')
}