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

var widget_parameters_opt = {
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

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if ($('#bt_saveWidgets').is(':visible')) {
      $("#bt_saveWidgets").click()
    }
  }
}

$(function() {
  $('sub.itemsNumber').html('(' + $('.widgetsDisplayCard').length + ')')
})

//searching
$('#in_searchWidgets').keyup(function() {
  var search = $(this).value()
  if (search == '') {
    $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    $('.widgetsDisplayCard').show()
    return;
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  $('.widgetsDisplayCard').hide()
  $('.panel-collapse').attr('data-show', 0)
  var match, text
  $('.widgetsDisplayCard .search').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).text())
    if (text.includes(search)) match = true

    if (not) match = !match
    if (match) {
      $(this).closest('.widgetsDisplayCard').show()
      $(this).closest('.panel-collapse').attr('data-show', 1)
    }
  })
  $('.panel-collapse[data-show=1]').collapse('show')
  $('.panel-collapse[data-show=0]').collapse('hide')
})
$('#bt_openAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='false']").click()
})
$('#bt_closeAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='true']").click()
})
$('#bt_resetWidgetsSearch').off('click').on('click', function() {
  $('#in_searchWidgets').val('').keyup()
})

//context menu
$(function() {
  try {
    $.contextMenu('destroy', $('.nav.nav-tabs'))
    jeedom.widgets.all({
      error: function(error) {
        $.fn.showAlert({
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

        $('.nav.nav-tabs').contextMenu({
          selector: 'li',
          autoHide: true,
          zIndex: 9999,
          className: 'widget-context-menu',
          callback: function(key, options, event) {
            if (event.ctrlKey || event.metaKey || event.originalEvent.which == 2) {
              window.open('index.php?v=d&p=widgets&id=' + options.commands[key].id).focus()
            } else {
              printWidget(options.commands[key].id)
            }
          },
          items: contextmenuitems
        })
      }
    })
  } catch (err) {}
})

$('#bt_chooseIcon').on('click', function() {
  var _icon = false
  if ($('div[data-l2key="icon"] > i').length) {
    _icon = $('div[data-l2key="icon"] > i').attr('class')
    _icon = '.' + _icon.replace(' ', '.')
  }
  jeedomUtils.chooseIcon(function(_icon) {
    $('.widgetsAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
  }, {
    icon: _icon
  })
  modifyWithoutSave = true
})

$('#bt_editCode').off('click').on('click', function() {
  jeedomUtils.loadPage('index.php?v=d&p=editor&type=widget')
})

$('#bt_replaceWidget').off('click').on('click', function() {
  $('#md_modal').load('index.php?v=d&modal=widget.replace').dialog('open')
    .dialog("option", "width", 800).dialog("option", "height", 500)
    .dialog({
      title: "{{Remplacement de widget}}",
      position: {
        my: "center center",
        at: "center center",
        of: window
      }
    })
})

$('#bt_applyToCmd').off('click').on('click', function() {
  //store usedBy:
  var checkedId = []
  $('#div_usedBy .cmdAdvanceConfigure').each(function() {
    checkedId.push($(this).data('cmd_id'))
  })

  $('#md_modal').dialog({
      title: "{{Appliquer ce widget à}}"
    })
    .load('index.php?v=d&modal=cmd.selectMultiple&type=' + $('.widgetsAttr[data-l1key=type]').value() + '&subtype=' + $('.widgetsAttr[data-l1key=subtype]').value(), function() {
      jeedomUtils.initTableSorter()

      $('#table_cmdConfigureSelectMultiple tbody tr').each(function(index) {
        if (checkedId.includes($(this).data('cmd_id'))) {
          $(this).find('.selectMultipleApplyCmd').prop('checked', true)
        }
      })

      $('#bt_cmdConfigureSelectMultipleAlertToogle').off('click').on('click', function() {
        $('#table_cmdConfigureSelectMultiple tbody tr input.selectMultipleApplyCmd:visible').each(function() {
          $(this).prop('checked', !$(this).prop('checked'))
          $(this).attr('data-state', 1)
        })
      })

      $('#bt_cmdConfigureSelectMultipleAlertApply').off().on('click', function() {
        var widgets = $('.widgets').getValues('.widgetsAttr')[0]
        widgets.test = $('#div_templateTest .test').getValues('.testAttr')
        jeedom.widgets.save({
          widgets: widgets,
          error: function(error) {
            $.fn.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            modifyWithoutSave = false
            var cmd = {
              template: {
                dashboard: 'custom::' + $('.widgetsAttr[data-l1key=name]').value(),
                mobile: 'custom::' + $('.widgetsAttr[data-l1key=name]').value()
              }
            }
            var cmdDefault = {
              template: {
                dashboard: 'default',
                mobile: 'default'
              }
            }

            $('#table_cmdConfigureSelectMultiple tbody tr').each(function() {
              var thisId = $(this).data('cmd_id')
              if ($(this).find('.selectMultipleApplyCmd').prop('checked')) {
                if (!checkedId.includes(thisId)) {
                  //show in usedBy
                  var thisObject = $(this).find('td').eq(1).html()
                  var thisEq = $(this).find('td').eq(2).html()
                  var thisName = $(this).find('td').eq(3).html()
                  var cmdHumanName = '[' + thisObject + '][' + thisEq + '][' + thisName + ']'
                  var newSpan = '<span class="label label-info cursor cmdAdvanceConfigure" data-cmd_id="' + thisId + '">' + cmdHumanName + '</span>'
                  $('#div_usedBy').append(newSpan)
                }
                cmd.id = thisId
                jeedom.cmd.save({
                  cmd: cmd,
                  error: function(error) {
                    $('#md_cmdConfigureSelectMultipleAlert').showAlert({
                      message: error.message,
                      level: 'danger'
                    })
                  },
                  success: function() {}
                });

              } else {
                if (checkedId.includes(thisId)) {
                  cmdDefault.id = thisId
                  jeedom.cmd.save({
                    cmd: cmdDefault,
                    error: function(error) {
                      $('#md_cmdConfigureSelectMultipleAlert').showAlert({
                        message: error.message,
                        level: 'danger'
                      })
                    },
                    success: function(data) {
                      $('#div_usedBy .cmdAdvanceConfigure[data-cmd_id="' + data.id + '"]').remove()
                    }
                  });
                }
              }
            })
            $('#md_cmdConfigureSelectMultipleAlert').showAlert({
              message: "{{Modification(s) appliquée(s) avec succès}}",
              level: 'success'
            })
          }
        })
      })
    }).dialog('open')
})

$('.widgetsAttr[data-l1key=display][data-l2key=icon]').off('dblclick').on('dblclick', function() {
  $('.widgetsAttr[data-l1key=display][data-l2key=icon]').value('')
})

$('.widgetsAttr[data-l1key=type]').off('change').on('change', function() {
  $('#div_templateReplace').empty()
  $('#div_templateTest').empty()
  $('#div_usedBy').empty()
  $('.selectWidgetSubType').hide().removeClass('widgetsAttr')
  $('.selectWidgetSubType[data-type=' + $(this).value() + ']').show().addClass('widgetsAttr').change()
})

$('.selectWidgetSubType').off('change').on('change', function() {
  $('#div_templateReplace').empty()
  $('#div_templateTest').empty()
  $('#div_usedBy').empty()
  $('.selectWidgetTemplate').hide().removeClass('widgetsAttr')
  $('.selectWidgetTemplate[data-type=' + $('.widgetsAttr[data-l1key=type]').value() + '][data-subtype=' + $(this).value() + ']').show().addClass('widgetsAttr').change()
})

$('#div_templateReplace').off('click', '.chooseIcon').on('click', '.chooseIcon', function() {
  var bt = $(this)
  jeedomUtils.chooseIcon(function(_icon) {
    bt.closest('.form-group').find('.widgetsAttr[data-l1key=replace]').value(_icon)
  }, {
    img: true
  })
  modifyWithoutSave = true
})

$('#div_templateTest').off('click', '.chooseIcon').on('click', '.chooseIcon', function() {
  var bt = $(this)
  jeedomUtils.chooseIcon(function(_icon) {
    bt.closest('.input-group').find('.testAttr').value(_icon)
  }, {
    img: true
  })
  modifyWithoutSave = true
})

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1)
}

function loadTemplateConfiguration(_template, _data) {
  $('.selectWidgetTemplate').off('change')
  jeedom.widgets.getTemplateConfiguration({
    template: _template,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#div_templateReplace').empty()
      if (typeof data.replace != 'undefined' && data.replace.length > 0) {
        $('.type_replace').show()
        var replace = ''
        for (var i in data.replace) {
          replace += '<div class="form-group">'
          if (widget_parameters_opt[data.replace[i]]) {
            replace += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-4 control-label">' + widget_parameters_opt[data.replace[i]].name + '</label>'
          } else {
            replace += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-4 control-label">' + capitalizeFirstLetter(data.replace[i].replace("icon_", "").replace("img_", "").replace("_", " ")) + '</label>'
          }
          replace += '<div class="col-lg-6 col-md-8 col-sm-8 col-xs-8">'
          replace += '<div class="input-group">'
          if (widget_parameters_opt[data.replace[i]]) {
            if (widget_parameters_opt[data.replace[i]].type == 'checkbox') {
              replace += '<input type="checkbox" class="widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
            } else if (widget_parameters_opt[data.replace[i]].type == 'number') {
              replace += '<input type="number" class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
            } else if (widget_parameters_opt[data.replace[i]].type == 'input') {
              replace += '<input class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
            }
          } else {
            replace += '<input class="form-control widgetsAttr roundedLeft" data-l1key="replace" data-l2key="#_' + data.replace[i] + '_#"/>'
          }
          if (data.replace[i].indexOf('icon_') != -1 || data.replace[i].indexOf('img_') != -1) {
            replace += '<span class="input-group-btn">'
            replace += '<a class="btn chooseIcon roundedRight"><i class="fas fa-flag"></i> {{Choisir}}</a>'
            replace += '</span>'
          }
          replace += '</div>'
          replace += '</div>'
          replace += '</div>'
        }
        $('#div_templateReplace').append(replace)
      } else {
        $('.type_replace').hide()
      }

      if (typeof _data != 'undefined') {
        $('.widgets').setValues({
          replace: _data.replace
        }, '.widgetsAttr')
      }
      if (data.test) {
        $('.type_test').show()
      } else {
        $('.type_test').hide()
      }
      $('.selectWidgetTemplate').on('change', function() {
        if ($(this).value() == '' || !$(this).hasClass('widgetsAttr')) return
        loadTemplateConfiguration('cmd.' + $('.widgetsAttr[data-l1key=type]').value() + '.' + $('.widgetsAttr[data-l1key=subtype]').value() + '.' + $(this).value())
      })
      modifyWithoutSave = true
    }
  })
}

$('#div_pageContainer').off('change', '.widgetsAttr').on('change', '.widgetsAttr:visible', function() {
  modifyWithoutSave = true
})

$('#bt_returnToThumbnailDisplay').on('click', function() {
  setTimeout(function() {
    $('.nav li.active').removeClass('active')
    $('a[href="#' + $('.tab-pane.active').attr('id') + '"]').closest('li').addClass('active')
  }, 500)
  if (jeedomUtils.checkPageModified()) return
  $('#div_conf').hide()
  $('#div_widgetsList').show()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Widgets}} - ' + JEEDOM_PRODUCT_NAME)
})

$('#bt_widgetsAddTest').off('click').on('click', function(event) {
  addTest({})
})

$('#div_templateTest').off('click', '.bt_removeTest').on('click', '.bt_removeTest', function() {
  $(this).closest('.test').remove()
  modifyWithoutSave = true
})

function printWidget(_id) {
  $.hideAlert()
  $('#div_conf').show()
  $('#div_widgetsList').hide()
  $('#div_templateTest').empty()

  jeedom.widgets.byId({
    id: _id,
    cache: false,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('a[href="#widgetstab"]').click()
      $('.selectWidgetTemplate').off('change')
      $('.widgetsAttr').value('')
      $('.widgetsAttr[data-l1key=type]').value('info')
      $('.widgetsAttr[data-l1key=subtype]').value($('.widgetsAttr[data-l1key=subtype]').find('option:first').attr('value'))
      $('.widgets').setValues(data, '.widgetsAttr')
      if (isset(data.test)) {
        for (var i in data.test) {
          addTest(data.test[i])
        }
      }
      var usedBy = ''
      for (var i in data.usedBy) {
        usedBy += '<span class="label label-info cursor cmdAdvanceConfigure" data-cmd_id="' + i + '">' + data.usedBy[i] + '</span> '
      }
      $('#div_usedBy').empty().append(usedBy)
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
      loadTemplateConfiguration(template, data)
      jeedomUtils.addOrUpdateUrl('id', data.id)
      modifyWithoutSave = false
      jeedom.widgets.getPreview({
        id: data.id,
        cache: false,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $('#div_widgetPreview').empty().html(data.html)
          $('#div_widgetPreview .eqLogic-widget').css('position', 'relative')
        }
      })
      setTimeout(function() {
        modifyWithoutSave = false
      }, 1000)
    }
  })
}

function addTest(_test) {
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
  $('#div_templateTest').append(div)
  $('#div_templateTest').find('.test').last().setValues(_test, '.testAttr')
}

$("#bt_addWidgets").off('click').on('click', function(event) {
  bootbox.prompt("{{Nom du widget}} ?", function(result) {
    if (result !== null) {
      jeedom.widgets.save({
        widgets: {
          name: result
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=widgets&id=' + data.id + '&saveSuccessFull=1')
          $.fn.showAlert({
            message: '{{Sauvegarde effectuée avec succès}}',
            level: 'success'
          })
        }
      })
    }
  })
})

$('#div_usedBy').off('click', '.cmdAdvanceConfigure').on('click', '.cmdAdvanceConfigure', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de la commande}}"
  }).load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-cmd_id')).dialog('open')
})

$(".widgetsDisplayCard").off('click').on('click', function(event) {
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=widgets&id=' + $(this).attr('data-widgets_id')
    window.open(url).focus()
  } else {
    printWidget($(this).attr('data-widgets_id'))
  }
})
$('.widgetsDisplayCard').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-widgets_id')
    $('.widgetsDisplayCard[data-widgets_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

if (is_numeric(getUrlVars('id'))) {
  if ($('.widgetsDisplayCard[data-widgets_id=' + getUrlVars('id') + ']').length != 0) {
    $('.widgetsDisplayCard[data-widgets_id=' + getUrlVars('id') + ']').click()
  } else {
    $('.widgetsDisplayCard').first().click()
  }
}

$("#bt_saveWidgets").on('click', function(event) {
  var widgets = $('.widgets').getValues('.widgetsAttr')[0]
  widgets.test = $('#div_templateTest .test').getValues('.testAttr')
  jeedom.widgets.save({
    widgets: widgets,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      modifyWithoutSave = false
      window.location = 'index.php?v=d&p=widgets&id=' + data.id + '&saveSuccessFull=1'
    }
  })
  return false
})

$('#bt_removeWidgets').on('click', function(event) {
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer le widget}} <span style="font-weight: bold ;">' + $('input[data-l1key="name"]').val() + '</span> ?', function(result) {
    if (result) {
      jeedom.widgets.remove({
        id: $('.widgetsAttr[data-l1key=id]').value(),
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          modifyWithoutSave = false
          window.location = 'index.php?v=d&p=widgets&removeSuccessFull=1'
        }
      })
    }
  })
})

$("#bt_exportWidgets").on('click', function(event) {
  var widgets = $('.widgets').getValues('.widgetsAttr')[0]
  widgets.test = $('#div_templateTest .test').getValues('.testAttr')
  widgets.id = ""
  jeedom.version({
    success: function(version) {
      widgets.jeedomCoreVersion = version
      downloadObjectAsJson(widgets, widgets.name)
    }
  })
  return false
})

$("#bt_mainImportWidgets").change(function(event) {
  $('#div_alert').hide()
  var uploadedFile = event.target.files[0]
  if (uploadedFile.type !== "application/json") {
    $.fn.showAlert({
      message: "{{L'import de widgets se fait au format json à partir de widgets précedemment exporté.}}",
      level: 'danger'
    })
    return false
  }

  if (uploadedFile) {
    bootbox.prompt("{{Nom du widget}} ?", function(result) {
      if (result !== null) {
        jeedom.widgets.save({
          widgets: {
            name: result
          },
          error: function(error) {
            $.fn.showAlert({
              message: error.message,
              level: 'danger'
            });
          },
          success: function(data) {
            var readFile = new FileReader()
            readFile.readAsText(uploadedFile)
            readFile.onload = function(e) {
              var objectData = JSON.parse(e.target.result)
              if (!isset(objectData.jeedomCoreVersion)) {
                $.fn.showAlert({
                  message: "{{Fichier json non compatible.}}",
                  level: 'danger'
                })
                return false
              }
              objectData.id = data.id
              objectData.name = data.name
              if (isset(objectData.test)) {
                for (var i in objectData.test) {
                  addTest(objectData.test[i])
                }
              }
              jeedom.widgets.save({
                widgets: objectData,
                error: function(error) {
                  $.fn.showAlert({
                    message: error.message,
                    level: 'danger'
                  });
                },
                success: function(data) {
                  jeedomUtils.loadPage('index.php?v=d&p=widgets&id=' + objectData.id + '&saveSuccessFull=1');
                }
              })
            }
          }
        })
      }
    })
  } else {
    $.fn.showAlert({
      message: "{{Problème lors de la lecture du fichier.}}",
      level: 'danger'
    })
    return false
  }
})

$("#bt_importWidgets").change(function(event) {
  $('#div_alert').hide()
  var uploadedFile = event.target.files[0]
  if (uploadedFile.type !== "application/json") {
    $.fn.showAlert({
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
        $.fn.showAlert({
          message: "{{Fichier json non compatible.}}",
          level: 'danger'
        })
        return false
      }
      objectData.id = $('.widgetsAttr[data-l1key=id]').value();
      objectData.name = $('.widgetsAttr[data-l1key=name]').value();
      if (isset(objectData.test)) {
        for (var i in objectData.test) {
          addTest(objectData.test[i])
        }
      }
      loadTemplateConfiguration('cmd.' + objectData.type + '.' + objectData.subtype + '.' + objectData.template, objectData)
    }
  } else {
    $.fn.showAlert({
      message: "{{Problème lors de la lecture du fichier.}}",
      level: 'danger'
    })
    return false
  }
})

function downloadObjectAsJson(exportObj, exportName) {
  var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj))
  var downloadAnchorNode = document.createElement('a')
  downloadAnchorNode.setAttribute("href", dataStr)
  downloadAnchorNode.setAttribute("target", "_blank")
  downloadAnchorNode.setAttribute("download", exportName + ".json")
  document.body.appendChild(downloadAnchorNode) // required for firefox
  downloadAnchorNode.click()
  downloadAnchorNode.remove()
}