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

if (!jeeFrontEnd.widgets) {
  jeeFrontEnd.widgets = {
    isLoadging: false, //Avoid triggering multiple time input changes
    init: function() {
      window.jeeP = this
      this.widget_parameters_opt = {
        'desktop_width': {
          'type': 'input',
          'name': '{{Largeur desktop}} <sub>px</sub>'
        },
        'mobile_width': {
          'type': 'input',
          'name': '{{Largeur mobile}} <sub>px</sub>'
        },
        'time_widget': {
          'type': 'checkbox',
          'name': '{{Time widget}}'
        }
      }
      document.querySelector('sub.itemsNumber').innerHTML = '(' + document.querySelectorAll('.widgetsDisplayCard').length + ')'
      if (is_numeric(getUrlVars('id'))) {
        jeeP.printWidget(getUrlVars('id'))
      }
    },
    loadTemplateConfiguration: function(_template, _data) {
      jeedom.widgets.getTemplateConfiguration({
        template: _template,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.getElementById('div_templateReplace').empty()
          if (typeof data.replace != 'undefined' && data.replace.length > 0) {
            document.querySelectorAll('.type_replace').seen()
            var replace = ''
            for (var i in data.replace) {
              replace += '<div class="form-group">'
              if (jeeP.widget_parameters_opt[data.replace[i]]) {
                replace += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-4 control-label">' + jeeP.widget_parameters_opt[data.replace[i]].name + '</label>'
              } else {
                replace += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-4 control-label">' + data.replace[i].replace("icon_", "").replace("img_", "").replace("_", " ") + '</label>'
              }
              replace += '<div class="col-lg-6 col-md-8 col-sm-8 col-xs-8">'
              replace += '<div class="input-group">'
              if (jeeP.widget_parameters_opt[data.replace[i]]) {
                if (jeeP.widget_parameters_opt[data.replace[i]].type == 'checkbox') {
                  replace += '<input type="checkbox" class="widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
                } else if (jeeP.widget_parameters_opt[data.replace[i]].type == 'number') {
                  replace += '<input type="number" class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
                } else if (jeeP.widget_parameters_opt[data.replace[i]].type == 'input') {
                  replace += '<input class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
                }
              } else {
                replace += '<input class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
              }
              if (data.replace[i].includes('icon_') || data.replace[i].includes('img_')) {
                replace += '<span class="input-group-btn">'
                replace += '<a class="btn chooseIcon roundedRight"><i class="fas fa-flag"></i> {{Choisir}}</a>'
                replace += '</span>'
              }
              replace += '</div>'
              replace += '</div>'
              replace += '</div>'
            }
            document.getElementById('div_templateReplace').html(replace, true)
          } else {
            document.querySelectorAll('.type_replace').unseen()
          }

          if (typeof _data != 'undefined') {
            document.querySelectorAll('.widgets').setJeeValues({
              replace: _data.replace
            }, '.widgetsAttr')
          }
          if (data.test) {
            document.querySelectorAll('.type_test').seen()
          } else {
            document.querySelectorAll('.type_test').unseen()
          }
          jeeFrontEnd.modifyWithoutSave = true
        }
      })
    },
    printWidget: function(_id) {
      jeeFrontEnd.widgets.isLoadging = true

      jeedomUtils.hideAlert()
      document.getElementById('div_conf').seen()
      document.getElementById('div_widgetsList').unseen()
      document.getElementById('div_templateTest').empty()

      jeedom.widgets.byId({
        id: _id,
        cache: false,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
          jeeFrontEnd.widgets.isLoadging = false
        },
        success: function(data) {
          document.querySelector('a[data-target="#widgetstab"]').click()

          //Ensure no resiliant data for next save:
          document.querySelectorAll('.widgetsAttr').jeeValue('')
          document.querySelectorAll('.widgetsAttr[data-l1key="type"]').jeeValue('info')
          document.querySelector('.widgetsAttr[data-l1key="subtype"]').jeeValue(
            document.querySelector('.widgetsAttr[data-l1key="subtype"]').selectedIndex = 0
          )

          document.querySelectorAll('.widgets').setJeeValues(data, '.widgetsAttr')
          if (isset(data.test)) {
            for (var i in data.test) {
              jeeP.addTest(data.test[i])
            }
          }

          var usedBy = ''
          for (var i in data.usedBy) {
            usedBy += '<span class="label label-info cursor cmdAdvanceConfigure" data-cmd_id="' + i + '">' + data.usedBy[i] + '</span> '
          }
          document.getElementById('div_usedBy').empty().insertAdjacentHTML('beforeend', usedBy)
          var template = 'cmd.'
          if (data.type && data.type !== null) {
            template += data.type + '.'
          } else {
            template += 'action.'
          }
          if (data.subtype && data.subtype !== null) {
            template += data.subtype + '.'
          } else {
            template += 'other.'
          }
          if (data.template && data.template !== null) {
            template += data.template
          } else {
            template += 'tmplicon'
          }
          jeeP.loadTemplateConfiguration(template, data)
          jeedomUtils.addOrUpdateUrl('id', data.id)
          jeedom.widgets.getPreview({
            id: data.id,
            cache: false,
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              let previewEL = document.getElementById('div_widgetPreview')
              previewEL.empty().html(data.html)
              if (previewEL.querySelector('div.eqLogic-widget') != null) previewEL.querySelector('div.eqLogic-widget').style.position = 'relative'
            }
          })
          jeeFrontEnd.modifyWithoutSave = false
          setTimeout(function() {
            jeeFrontEnd.modifyWithoutSave = false
          }, 500)
          jeeFrontEnd.widgets.isLoadging = false
        }
      })
    },
    addTest: function(_test) {
      if (!isset(_test)) {
        _trigger = {}
      }
      var div = '<div class="test">'
      div += '<div class="form-group">'
      div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Test}}</label>'
      div += '<div class="col-sm-3">'
      div += '<div class="input-group">'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-sm bt_removeTest roundedLeft"><i class="fas fa-minus-circle"></i></a>'
      div += '</span>'
      div += '<input class="testAttr form-control input-sm roundedRight" data-l1key="operation" placeholder="Test, utiliser #value# pour la valeur"/>'
      div += '</div>'
      div += '</div>'
      div += '<div class="col-sm-3">'
      div += '<div class="input-group">'
      div += '<input class="testAttr form-control input-sm roundedLeft" data-l1key="state_light" placeholder="{{Résultat si test ok}} (light)"/>'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-sm chooseIcon roundedRight"><i class="fas fa-flag"></i> {{Choisir}}</a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'
      div += '<div class="col-sm-3">'
      div += '<div class="input-group">'
      div += '<input class="testAttr form-control input-sm roundedLeft" data-l1key="state_dark" placeholder="{{Résultat si test ok}} (dark)"/>'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-sm chooseIcon roundedRight"><i class="fas fa-flag"></i> {{Choisir}}</a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'

      div += '</div>'
      div += '</div>'

      let replaceDiv = document.getElementById('div_templateTest')
      replaceDiv.insertAdjacentHTML('beforeend', div)
      replaceDiv.querySelectorAll('.test').last().setJeeValues(_test, '.testAttr')

    },
    downloadObjectAsJson: function(exportObj, exportName) {
      var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj))
      var downloadAnchorNode = document.createElement('a')
      downloadAnchorNode.setAttribute("href", dataStr)
      downloadAnchorNode.setAttribute("target", "_blank")
      downloadAnchorNode.setAttribute("download", exportName + ".json")
      document.body.appendChild(downloadAnchorNode) // required for firefox
      downloadAnchorNode.click()
      downloadAnchorNode.remove()
    },
    applyToCmd: function() {
      //store usedBy:
      var checkedId = []
      document.querySelectorAll('#div_usedBy .cmdAdvanceConfigure').forEach(_cmd => {
        checkedId.push(_cmd.getAttribute('data-cmd_id'))
      })
      let type = document.querySelector('.widgetsAttr[data-l1key="type"]').jeeValue()
      let subtype = document.querySelector('.widgetsAttr[data-l1key="subtype"]').jeeValue()
      jeeDialog.dialog({
        id: 'md_cmdConfigureSelectMultiple',
        title: "{{Appliquer ce widget à}}",
        contentUrl: 'index.php?v=d&modal=cmd.selectMultiple&type=' + type + '&subtype=' + subtype,
        callback: function() {
          document.querySelectorAll('#table_cmdConfigureSelectMultiple tbody tr').forEach(_tr => {
            if (checkedId.includes(_tr.getAttribute('data-cmd_id'))) {
              _tr.querySelector('input.selectMultipleApplyCmd').checked = true
            }
          })
          document.getElementById('bt_cmdSelectMultipleApply').addEventListener('click', function(event) {
            var widgets = document.querySelectorAll('.widgets').getJeeValues('.widgetsAttr')[0]
            widgets.test = document.querySelectorAll('#div_templateTest .test').getJeeValues('.testAttr')
            jeedom.widgets.save({
              widgets: widgets,
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(data) {
                jeeFrontEnd.modifyWithoutSave = false
                var cmd = {
                  template: {
                    dashboard: 'custom::' + document.querySelector('.widgetsAttr[data-l1key="name"]').jeeValue(),
                    mobile: 'custom::' + document.querySelector('.widgetsAttr[data-l1key="name"]').jeeValue()
                  }
                }
                var cmdDefault = {
                  template: {
                    dashboard: 'default',
                    mobile: 'default'
                  }
                }

                document.querySelectorAll('#table_cmdConfigureSelectMultiple tbody tr').forEach(_tr => {
                  var thisId = _tr.getAttribute('data-cmd_id')
                  if (_tr.querySelector('.selectMultipleApplyCmd').checked) {
                    if (!checkedId.includes(thisId)) {
                      //show in usedBy
                      var thisObject = _tr.childNodes[1].textContent
                      var thisEq = _tr.childNodes[2].textContent
                      var thisName = _tr.childNodes[3].textContent
                      var cmdHumanName = '[' + thisObject + '][' + thisEq + '][' + thisName + ']'
                      var newSpan = '<span class="label label-info cursor cmdAdvanceConfigure" data-cmd_id="' + thisId + '">' + cmdHumanName + '</span>'
                      document.getElementById('div_usedBy').insertAdjacentHTML('beforeend', newSpan)
                    }
                    cmd.id = thisId
                    jeedom.cmd.save({
                      cmd: cmd,
                      error: function(error) {
                        jeedomUtils.showAlert({
                          attachTo: jeeDialog.get('#md_cmdConfigureSelectMultiple', 'dialog'),
                          message: error.message,
                          level: 'danger'
                        })
                      },
                      success: function() { }
                    })

                  } else {
                    if (checkedId.includes(thisId)) {
                      cmdDefault.id = thisId
                      jeedom.cmd.save({
                        cmd: cmdDefault,
                        error: function(error) {
                          jeedomUtils.showAlert({
                            attachTo: jeeDialog.get('#md_cmdConfigureSelectMultiple', 'dialog'),
                            message: error.message,
                            level: 'danger'
                          })
                        },
                        success: function(data) {
                          document.querySelector('#div_usedBy .cmdAdvanceConfigure[data-cmd_id="' + data.id + '"]')?.remove()
                        }
                      })
                    }
                  }
                })
                jeedomUtils.showAlert({
                  message: "{{Modification(s) appliquée(s) avec succès}}",
                  level: 'success'
                })
              }
            })
          })
        }
      })
    },
    saveWidget: function() {
      var widgets = document.getElementById('div_conf').getJeeValues('.widgetsAttr')[0]
      widgets.test = document.querySelectorAll('#div_templateTest .test').getJeeValues('.testAttr')
      jeedom.widgets.save({
        widgets: widgets,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeFrontEnd.modifyWithoutSave = false
          window.location = 'index.php?v=d&p=widgets&id=' + data.id + '&saveSuccessFull=1'
        }
      })
    },
  }
}

jeeFrontEnd.widgets.init()

//context menu
try {
  jeedom.widgets.all({
    error: function(error) {
      jeedomUtils.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(_widgets) {
      if (_widgets.length == 0) return

      var widgetsList = []
      widgetsList['info'] = []
      widgetsList['action'] = []
      for (var i = 0; i < _widgets.length; i++) {
        wg = _widgets[i]
        if (wg.type == 'info') widgetsList['info'].push([wg.name, wg.id])
        if (wg.type == 'action') widgetsList['action'].push([wg.name, wg.id])
      }

      //set context menu!
      var contextmenuitems = {}
      var uniqId = 0
      var groupWidgets, items, wg, wgName, wgId
      for (var group in widgetsList) {
        groupWidgets = widgetsList[group]
        items = {}
        for (var index in groupWidgets) {
          wg = groupWidgets[index]
          wgName = wg[0]
          wgId = wg[1]
          items[uniqId] = {
            'name': wgName,
            'id': wgId
          }
          uniqId++
        }
        contextmenuitems[group] = {
          'name': group,
          'items': items
        }
      }

      new jeeCtxMenu({
        selector: '.nav.nav-tabs > li',
        autoHide: true,
        zIndex: 9999,
        className: 'widget-context-menu',
        callback: function(key, options, event) {
          if (!jeedomUtils.checkPageModified()) {
            if (event.ctrlKey || event.metaKey || event.which == 2) {
              window.open('index.php?v=d&p=widgets&id=' + options.commands[key].id).focus()
            } else {
              jeeP.printWidget(options.commands[key].id)
            }
          }
        },
        items: contextmenuitems
      })
    }
  })
} catch (err) { }


//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    jeeP.saveWidget()
  }
})

//searching
document.getElementById('in_searchWidgets').addEventListener('keyup', function() {
  var search = this.value
  if (search == '') {
    document.querySelectorAll('#accordionWidgets .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
    document.querySelectorAll('.widgetsDisplayCard').seen()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  document.querySelectorAll('#accordionWidgets .accordion-toggle').forEach(_panel => { _panel.setAttribute('data-show', 0) })
  document.querySelectorAll('.widgetsDisplayCard').unseen()
  var match, text

  document.querySelectorAll('.widgetsDisplayCard .name').forEach(scName => {
    match = false
    text = jeedomUtils.normTextLower(scName.textContent)
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      scName.closest('.widgetsDisplayCard').seen()
      scName.closest('.panel').querySelector('.accordion-toggle').setAttribute('data-show', 1)
    }

  })
  document.querySelectorAll('.accordion-toggle.collapsed[data-show="1"]').forEach(_panel => { _panel.click() })
  document.querySelectorAll('.accordion-toggle:not(.collapsed)[data-show="0"]').forEach(_panel => { _panel.click() })
})

/*Events delegations
*/
//ThumbnailDisplay
document.getElementById('div_widgetsList').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_openAll')) {
    document.querySelectorAll('#accordionWidgets .accordion-toggle.collapsed').forEach(_panel => { _panel.click() })
    return
  }

  if (_target = event.target.closest('#bt_closeAll')) {
    document.querySelectorAll('#accordionWidgets .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
    return
  }

  if (_target = event.target.closest('#bt_resetWidgetsSearch')) {
    document.getElementById('in_searchWidgets').jeeValue('').triggerEvent('keyup')
    return
  }

  if (_target = event.target.closest('#bt_addWidgets')) {
    jeeDialog.prompt("{{Nom du widget}} ?", function(result) {
      if (result !== null) {
        jeedom.widgets.save({
          widgets: {
            name: result
          },
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeeFrontEnd.modifyWithoutSave = false
            jeedomUtils.loadPage('index.php?v=d&p=widgets&id=' + data.id + '&saveSuccessFull=1')
            jeedomUtils.showAlert({
              message: '{{Sauvegarde effectuée avec succès}}',
              level: 'success'
            })
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_mainImportWidgets')) {
    document.getElementById('uploadFile').click()
    event.stopPropagation()
    return
  }

  if (_target = event.target.closest('#bt_editCode')) {
    jeedomUtils.loadPage('index.php?v=d&p=editor&type=widget')
    return
  }

  if (_target = event.target.closest('#bt_replaceWidget')) {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: "{{Remplacement de widget}}",
      width: 800,
      height: 500,
      contentUrl: 'index.php?v=d&modal=widget.replace'
    })
    return
  }

  if (_target = event.target.closest('.widgetsDisplayCard')) {
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      var url = '/index.php?v=d&p=widgets&id=' + _target.getAttribute('data-widgets_id')
      window.open(url).focus()
    } else {
      jeeP.printWidget(_target.getAttribute('data-widgets_id'))
    }
    return
  }
})

document.getElementById('div_widgetsList').addEventListener('mouseup', function(event) {
  var _target = null
  if (_target = event.target.closest('.widgetsDisplayCard')) {
    if (event.which == 2) {
      event.preventDefault()
      var id = _target.getAttribute('data-widgets_id')
      document.querySelector('.widgetsDisplayCard[data-widgets_id="' + id + '"] .name').triggerEvent('click', { detail: { ctrlKey: true } })
    }
    return
  }
})

document.getElementById('div_widgetsList').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('#uploadFile')) {
    jeedomUtils.hideAlert()
    var uploadedFile = event.target.files[0]
    if (uploadedFile.type !== "application/json") {
      jeedomUtils.showAlert({
        message: "{{L'import de widget se fait au format json à partir d'un widget précédemment exporté.}}",
        level: 'danger'
      })
      return false
    }

    if (uploadedFile) {
      jeeDialog.prompt("{{Nom du widget}} ?", function(result) {
        if (result !== null) {
          jeedom.widgets.save({
            widgets: {
              name: result
            },
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              var readFile = new FileReader()
              readFile.readAsText(uploadedFile)
              readFile.onload = function(e) {
                var objectData = JSON.parse(e.target.result)
                if (!isset(objectData.jeedomCoreVersion)) {
                  jeedomUtils.showAlert({
                    message: "{{Fichier json non compatible.}}",
                    level: 'danger'
                  })
                  return false
                }
                objectData.id = data.id
                objectData.name = data.name
                if (isset(objectData.test)) {
                  for (var i in objectData.test) {
                    jeeP.addTest(objectData.test[i])
                  }
                }
                jeedom.widgets.save({
                  widgets: objectData,
                  error: function(error) {
                    jeedomUtils.showAlert({
                      message: error.message,
                      level: 'danger'
                    })
                  },
                  success: function(data) {
                    jeedomUtils.loadPage('index.php?v=d&p=widgets&id=' + objectData.id + '&saveSuccessFull=1')
                  }
                })
              }
            }
          })
        }
      })
    } else {
      jeedomUtils.showAlert({
        message: "{{Problème lors de la lecture du fichier.}}",
        level: 'danger'
      })
      return false
    }
    return
  }
})


//Widget
document.getElementById('div_conf').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_returnToThumbnailDisplay')) {
    setTimeout(function() {
      document.querySelector('.nav li.active').removeClass('active')
      document.querySelector('a[data-target="#' + document.querySelector('.tab-pane.active').getAttribute('id') + '"]').closest('li').addClass('active')
    }, 500)
    if (jeedomUtils.checkPageModified()) return
    document.getElementById('div_conf').unseen()
    document.getElementById('div_widgetsList').seen()
    jeedomUtils.addOrUpdateUrl('id', null, '{{Widgets}} - ' + JEEDOM_PRODUCT_NAME)
    return
  }

  if (_target = event.target.closest('#bt_applyToCmd')) {
    jeeFrontEnd.widgets.applyToCmd()
    return
  }

  if (_target = event.target.closest('#bt_exportWidgets')) {
    var widgets = document.getElementById('div_conf').getJeeValues('.widgetsAttr')[0]
    widgets.test = document.querySelectorAll('#div_templateTest .test').getJeeValues('.testAttr')
    widgets.id = ""
    jeedom.version({
      success: function(version) {
        widgets.jeedomCoreVersion = version
        jeeP.downloadObjectAsJson(widgets, widgets.name)
      }
    })
    return false
    return
  }

  if (_target = event.target.closest('#bt_saveWidgets')) {
    jeeP.saveWidget()
    return
  }

  if (_target = event.target.closest('#bt_removeWidgets')) {
    let name = document.querySelector('input[data-l1key="name"]').value
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer le widget}} <span style="font-weight: bold ;">' + name + '</span> ?', function(result) {
      if (result) {
        jeedom.widgets.remove({
          id: document.querySelector('.widgetsAttr[data-l1key="id"]').jeeValue(),
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeeFrontEnd.modifyWithoutSave = false
            window.location = 'index.php?v=d&p=widgets&removeSuccessFull=1'
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_chooseIcon')) {
    var _icon = document.querySelector('div[data-l2key="icon"] > i')
    if (_icon != null) {
      _icon = _icon.getAttribute('class')
    } else {
      _icon = false
    }
    jeedomUtils.chooseIcon(function(_icon) {
      document.querySelector('div[data-l2key="icon"]').innerHTML = _icon
      jeeFrontEnd.modifyWithoutSave = true
    }, { icon: _icon })
    return
  }

  if (_target = event.target.closest('#div_templateReplace .chooseIcon')) {
    var params = { img: true }
    let input = _target.closest('div').querySelector('input').value
    if (input.startsWith('<i class=')) params.icon = input.substring(10, input.length - 6)
    jeedomUtils.chooseIcon(function(_icon) {
      _target.closest('.form-group').querySelector('.widgetsAttr[data-l1key="replace"]').jeeValue(_icon)
    }, params)
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#div_templateTest .chooseIcon')) {
    var params = { img: true }
    let input = _target.closest('div').querySelector('input').value
    if (input.startsWith('<i class=')) params.icon = input.substring(10, input.length - 6)
    jeedomUtils.chooseIcon(function(_icon) {
      _target.closest('.input-group').querySelector('.testAttr').jeeValue(_icon)
    }, params)
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#bt_widgetsAddTest')) {
    jeeP.addTest({})
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('#div_templateTest .bt_removeTest')) {
    _target.closest('.test').remove()
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.cmdAdvanceConfigure')) {
    jeeDialog.dialog({
      id: 'jee_modal2',
      title: '{{Configuration de la commande}}',
      contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + _target.getAttribute('data-cmd_id')
    })
    return
  }
})

document.getElementById('div_conf').addEventListener('dblclick', function(event) {
  var _target = null
  if (_target = event.target.closest('.widgetsAttr[data-l1key="display"][data-l2key="icon"]')) {
    _target.innerHTML = ''
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

document.getElementById('div_conf').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.widgetsAttr')) {
    jeeFrontEnd.modifyWithoutSave = true
  }

  if (_target = event.target.closest('#bt_importWidgets')) {
    jeedomUtils.hideAlert()
    var uploadedFile = _target.files[0]
    if (uploadedFile.type !== "application/json") {
      jeedomUtils.showAlert({
        message: "{{L'import de widgets se fait au format json à partir de widgets précedemment exporté.}}",
        level: 'danger'
      })
      return false
    }
    if (uploadedFile) {
      var readFile = new FileReader()
      readFile.readAsText(uploadedFile)
      readFile.onload = function(e) {
        var objectData = JSON.parse(e.target.result)
        if (!isset(objectData.jeedomCoreVersion)) {
          jeedomUtils.showAlert({
            message: "{{Fichier json non compatible.}}",
            level: 'danger'
          })
          return false
        }

        objectData.id = document.querySelector('.widgetsAttr[data-l1key=id]').jeeValue()
        objectData.name = document.querySelector('.widgetsAttr[data-l1key=name]').jeeValue()
        if (isset(objectData.test)) {
          for (var i in objectData.test) {
            jeeP.addTest(objectData.test[i])
          }
        }
        jeeP.loadTemplateConfiguration('cmd.' + objectData.type + '.' + objectData.subtype + '.' + objectData.template, objectData)
      }
    } else {
      jeedomUtils.showAlert({
        message: "{{Problème lors de la lecture du fichier.}}",
        level: 'danger'
      })
      return false
    }
    return
  }

  if (_target = event.target.closest('.selectWidgetTemplate')) {
    if (jeeP.isLoadging) return
    let type = document.querySelector('.widgetsAttr[data-l1key="type"]').jeeValue()
    let subtype = document.querySelector('.widgetsAttr[data-l1key="subtype"]').jeeValue()
    jeeP.loadTemplateConfiguration('cmd.' + type + '.' + subtype + '.' + _target.value)
    return
  }

  if (_target = event.target.closest('.widgetsAttr[data-l1key="type"]')) {
    document.getElementById('div_templateReplace').empty()
    document.getElementById('div_templateTest').empty()
    document.getElementById('div_usedBy').empty()
    document.querySelectorAll('.selectWidgetSubType').unseen().removeClass('widgetsAttr')
    document.querySelector('.selectWidgetSubType[data-type="' + event.target.jeeValue() + '"]')?.seen().addClass('widgetsAttr').triggerEvent('change')
    return
  }

  if (_target = event.target.closest('.selectWidgetSubType')) {
    document.getElementById('div_templateReplace').empty()
    document.getElementById('div_templateTest').empty()
    document.getElementById('div_usedBy').empty()
    document.querySelectorAll('.selectWidgetTemplate').unseen().removeClass('widgetsAttr')
    let type = document.querySelector('.widgetsAttr[data-l1key="type"]').jeeValue()
    document.querySelector('.selectWidgetTemplate[data-type="' + type + '"][data-subtype="' + event.target.jeeValue() + '"]')?.seen().addClass('widgetsAttr').triggerEvent('change')
    return
  }
})
