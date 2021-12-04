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

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if ($('#bt_saveObject').is(':visible')) {
      $("#bt_saveObject").click()
    }
  }
}

$(function() {
  $('sub.itemsNumber').html('(' + $('.objectDisplayCard').length + ')')

  if (is_numeric(getUrlVars('id'))) {
    if ($('.objectDisplayCard[data-object_id=' + getUrlVars('id') + ']').length != 0) {
      $('.objectDisplayCard[data-object_id=' + getUrlVars('id') + ']').click()
    } else {
      $('.objectDisplayCard').first().click()
    }
  }
})

//searching
$('#in_searchObject').keyup(function() {
  var search = $(this).value()
  if (search == '') {
    $('.objectDisplayCard').show()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }

  $('.objectDisplayCard').hide()
  var match, text
  $('.objectDisplayCard .name').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).text())
    if (text.includes(search)) match = true

    if (not) match = !match
    if (match) {
      $(this).closest('.objectDisplayCard').show()
    }
  })
})
$('#bt_resetObjectSearch').on('click', function() {
  $('#in_searchObject').val('').keyup()
})

//context menu
$(function() {
  try {
    $.contextMenu('destroy', $('.nav.nav-tabs'))
    jeedom.object.all({
      onlyVisible: 0,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(_objects) {
        if (_objects.length == 0) return

        var contextmenuitems = {}
        var ob, decay
        for (var i = 0; i < _objects.length; i++) {
          ob = _objects[i]
          decay = 0
          if (isset(ob.configuration) && isset(ob.configuration.parentNumber)) {
            decay = ob.configuration.parentNumber
          }
          contextmenuitems[i] = {
            'name': '\u00A0\u00A0\u00A0'.repeat(decay) + ob.name,
            'id': ob.id
          }
        }

        $('.nav.nav-tabs').contextMenu({
          selector: 'li',
          autoHide: true,
          zIndex: 9999,
          className: 'object-context-menu',
          callback: function(key, options, event) {
            if (event.ctrlKey || event.metaKey || event.originalEvent.which == 2) {
              var url = 'index.php?v=d&p=object&id=' + options.commands[key].id
              if (window.location.hash != '') {
                url += window.location.hash
              }
              window.open(url).focus()
            } else {
              printObject(options.commands[key].id)
            }
          },
          items: contextmenuitems
        })
      }
    })
  } catch (err) {}
})

$('#bt_graphObject').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Graphique des liens}}"
  }).load('index.php?v=d&modal=graph.link&filter_type=object&filter_id=' + $('.objectAttr[data-l1key=id]').value()).dialog('open')
})

$('#bt_libraryBackgroundImage').on('click', function() {
  jeedomUtils.chooseIcon(function(_icon) {
    $('.objectImg').show().find('img').replaceWith(_icon)
    $('.objectImg img').attr('width', '240px')
    jeedom.object.uploadImage({
      id: $('.objectAttr[data-l1key=id]').value(),
      file: $('.objectImg img').data('filename'),
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        $.fn.showAlert({
          message: '{{Image de fond appliquée avec succès}}',
          level: 'success'
        })
      }
    })
  }, {
    object_id: $('.objectAttr[data-l1key=id]').value()
  })
})

$('#bt_removeBackgroundImage').off('click').on('click', function() {
  bootbox.confirm('{{Êtes-vous sûr de vouloir enlever l\'image de fond de cet objet ?}}', function(result) {
    if (result) {
      jeedom.object.removeImage({
        id: $('.objectAttr[data-l1key=id]').value(),
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          $('.objectImg').hide()
          $.fn.showAlert({
            message: '{{Image de fond enlevée}}',
            level: 'success'
          })
        },
      })
    }
  })
  return false
})

$('#bt_returnToThumbnailDisplay').on('click', function() {
  setTimeout(function() {
    $('.nav li.active').removeClass('active')
    $('a[href="#' + $('.tab-pane.active').attr('id') + '"]').closest('li').addClass('active')
  }, 500)
  if (jeedomUtils.checkPageModified()) return
  $('#div_conf').hide()
  $('#div_resumeObjectList').show()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Objets}} - ' + JEEDOM_PRODUCT_NAME)
})

$(".objectDisplayCard").off('click').on('click', function(event) {
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=object&id=' + $(this).attr('data-object_id')
    window.open(url).focus()
  } else {
    printObject($(this).attr('data-object_id'))
  }
  return false
})
$('.objectDisplayCard').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-object_id')
    $('.objectDisplayCard[data-object_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

$('select[data-l2key="synthToAction"]').off().on('change', function() {
  $('select[data-l2key="synthToView"], select[data-l2key="synthToPlan"], select[data-l2key="synthToPlan3d"]').parent().addClass('hidden')
  $('select[data-l2key="' + $(this).val() + '"]').parent().removeClass('hidden')
})

function printObject(_id) {
  $.hideAlert()
  var objName = $('.objectListContainer .objectDisplayCard[data-object_id="' + _id + '"]').attr('data-object_name')
  var objIcon = $('.objectListContainer .objectDisplayCard[data-object_id="' + _id + '"]').attr('data-object_icon')
  loadObjectConfiguration(_id)
  $('.objectname_resume').empty().append(objIcon + '  ' + objName)
}

function loadObjectConfiguration(_id) {
  try {
    $('#bt_uploadImage').fileupload('destroy')
    $('#bt_uploadImage').parent().html('<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">')
  } catch (error) {}

  $('#bt_uploadImage').fileupload({
    replaceFileInput: false,
    url: 'core/ajax/object.ajax.php?action=uploadImage&id=' + _id,
    dataType: 'json',
    done: function(e, data) {
      if (data.result.state != 'ok') {
        $.fn.showAlert({
          message: data.result.result,
          level: 'danger'
        })
        return
      }
      if (isset(data.result.result.filepath)) {
        var filePath = data.result.result.filepath
        filePath = '/data/object/' + filePath.split('/data/object/')[1]
        $('.objectImg').show().find('img').attr('src', filePath)
      } else {
        $('.objectImg').hide()
      }
      $.fn.showAlert({
        message: '{{Image de fond ajoutée avec succès}}',
        level: 'success'
      })
    }
  })

  $(".objectDisplayCard").removeClass('active')
  $('.objectDisplayCard[data-object_id=' + _id + ']').addClass('active')
  $('#div_conf').show()
  $('#div_resumeObjectList').hide()
  jeedom.object.byId({
    id: _id,
    cache: false,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('.objectAttr').value('')
      $('.objectAttr[data-l1key=father_id] option').show()
      $('#summarytab input[type=checkbox]').value(0)
      $('.object').setValues(data, '.objectAttr')

      if (!isset(data.configuration.hideOnOverview)) {
        $('input[data-l2key="hideOnOverview"]').prop('checked', false)
      }
      if (!isset(data.configuration.useBackground)) {
        $('.objectAttr[data-l1key=configuration][data-l2key=useBackground]').prop('checked', false)
      }
      if (!isset(data.configuration.synthToAction)) {
        $('select[data-l2key="synthToView"], select[data-l2key="synthToPlan"], select[data-l2key="synthToPlan3d"]').parent().addClass('hidden')
        $('select[data-l2key="synthToAction"]').val('synthToDashboard')
      }

      if (!isset(data.configuration.useCustomColor) || data.configuration.useCustomColor == "0") {
        var bodyStyles = window.getComputedStyle(document.body)
        var objectBkgdColor = bodyStyles.getPropertyValue('--objectBkgd-color')
        var objectTxtColor = bodyStyles.getPropertyValue('--objectTxt-color')

        if (!objectBkgdColor === undefined) {
          objectBkgdColor = jeedomUtils.rgbToHex(objectBkgdColor)
        } else {
          objectBkgdColor = '#696969'
        }
        if (!objectTxtColor === undefined) {
          objectTxtColor = jeedomUtils.rgbToHex(objectTxtColor)
        } else {
          objectTxtColor = '#ebebeb'
        }

        $('.objectAttr[data-l1key=display][data-l2key=tagColor]').value(objectBkgdColor)
        $('.objectAttr[data-l1key=display][data-l2key=tagTextColor]').value(objectTxtColor)

        $('.objectAttr[data-l1key=display][data-l2key=tagColor]').click(function() {
          $('input[data-l2key="useCustomColor"').prop('checked', true)
        })
        $('.objectAttr[data-l1key=display][data-l2key=tagTextColor]').click(function() {
          $('input[data-l2key="useCustomColor"').prop('checked', true)
        })
      }

      $('.objectAttr[data-l1key=father_id] option[value=' + data.id + ']').hide()
      $('.div_summary').empty()
      $('.tabnumber').empty()

      if (isset(data.img) && data.img != '') {
        $('.objectImg').show().find('img').attr('src', data.img)
      } else {
        $('.objectImg').hide()
      }

      //set summary tab:
      if (isset(data.configuration) && isset(data.configuration.summary)) {
        var el
        var summary = data.configuration.summary
        for (var i in summary) {
          el = $('.type' + i)
          if (el != undefined) {
            for (var j in summary[i]) {
              addSummaryInfo(el, summary[i][j])
            }
            if (summary[i].length != 0) {
              $('.summarytabnumber' + i).append('(' + summary[i].length + ')')
            }
          }
        }
      } else {
        var summary = {}
      }

      //set eqlogics tab:
      addEqlogicsInfo(_id, data.name, summary)

      var hash = window.location.hash
      jeedomUtils.addOrUpdateUrl('id', data.id)
      if (hash == '') {
        $('.nav-tabs a[href="#objecttab"]').click()
      } else {
        window.location.hash = hash
      }
      modifyWithoutSave = false
      setTimeout(function() {
        modifyWithoutSave = false
      }, 500)
    }
  })
}

$("#bt_addObject, #bt_addObject2").on('click', function(event) {
  bootbox.prompt("{{Nom du nouvel objet}} ?", function(result) {
    if (result !== null) {
      jeedom.object.save({
        object: {
          name: result,
          isVisible: 1
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1')
        }
      })
    }
  })
})

$('.objectAttr[data-l1key=display][data-l2key=icon]').on('dblclick', function() {
  $(this).value('')
})

$("#bt_saveObject").on('click', function(event) {
  var object = $('.object').getValues('.objectAttr')[0]
  if (!isset(object.configuration)) {
    object.configuration = {}
  }
  if (!isset(object.configuration.summary)) {
    object.configuration.summary = {}
  }

  var type, summaries, summary
  $('.object .div_summary').each(function() {
    type = $(this).attr('data-type')
    object.configuration.summary[type] = []
    summaries = {}
    $(this).find('.summary').each(function() {
      summary = $(this).getValues('.summaryAttr')[0]
      object.configuration.summary[type].push(summary)
    })
  })

  jeedom.object.save({
    object: object,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      modifyWithoutSave = false
      window.location = 'index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1'
    }
  })
  return false
})

$("#bt_removeObject").on('click', function(event) {
  $.hideAlert()
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer l\'objet}} <span style="font-weight: bold ;">' + $('.objectDisplayCard.active .name').text().trim() + '</span> ?', function(result) {
    if (result) {
      jeedom.object.remove({
        id: $('.objectDisplayCard.active').attr('data-object_id'),
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=object&removeSuccessFull=1')
        }
      })
    }
  })
  return false
})

$('#bt_chooseIcon').on('click', function() {
  var _icon = false
  var icon = false
  var color = false
  if ($('div[data-l2key="icon"] > i').length) {
    color = '';
    var class_icon = $('div[data-l2key="icon"] > i').attr('class')
    class_icon = class_icon.replace(' ', '.').split(' ')
    icon = '.' + class_icon[0]
    if (class_icon[1]) {
      color = class_icon[1]
    }
  }
  jeedomUtils.chooseIcon(function(_icon) {
    modifyWithoutSave = true
    $('.objectAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
  }, {
    icon: icon,
    color: color
  })
})

$('#div_pageContainer').off('change', '.objectAttr').on('change', '.objectAttr:visible', function() {
  modifyWithoutSave = true
});

$('.addSummary').on('click', function() {
  var type = $(this).attr('data-type')
  addSummaryInfo($('.type' + type))
  modifyWithoutSave = true
})

$('.bt_checkAll').on('click', function() {
  $(this).closest('tr').find('input[type="checkbox"]').each(function() {
    $(this).prop("checked", true)
  })
})

$('.bt_checkNone').on('click', function() {
  $(this).closest('tr').find('input[type="checkbox"]').each(function() {
    $(this).prop("checked", false)
  })
})

//cmd info modal autoselect object:
$('#div_pageContainer').on({
  'click': function(event) {
    var el = $(this).closest('.summary').find('.summaryAttr[data-l1key=cmd]')
    var type = $(this).closest('.div_summary').data('type')
    jeedom.cmd.getSelectModal({
      object: {
        id: $('.objectAttr[data-l1key="id"]').val()
      },
      cmd: {
        type: 'info'
      }
    }, function(result) {
      el.value(result.human)
      updateSummaryTabNbr(type)
      updateSummaryButton(result.human, type, true)
    })
    $('body').trigger('mod_insertCmdValue_Visible')
  }
}, '.listCmdInfo')

$('#div_pageContainer').on({
  'click': function(event) {
    var cmd = $(this).closest('.summary').find('input[data-l1key="cmd"]').val()
    var type = $(this).closest('.div_summary').data('type')
    $(this).closest('.summary').remove()
    updateSummaryTabNbr(type)
    updateSummaryButton(cmd, type, false)
  }
}, '.bt_removeSummary')

//populate summary tab infos:
function addSummaryInfo(_el, _summary) {
  if (!isset(_summary)) {
    _summary = {}
  }
  var div = '<div class="summary">'
  div += '<div class="form-group">'
  div += '<label class="col-sm-1 control-label">{{Commande}}</label>'
  div += '<div class="col-sm-4 has-success">'
  div += '<div class="input-group">'
  div += '<span class="input-group-btn">'
  div += '<input type="checkbox" class="summaryAttr roundedLeft" data-l1key="enable" checked title="{{Activer}}" />'
  div += '<a class="btn btn-default bt_removeSummary btn-sm"><i class="fas fa-minus-circle"></i></a>'
  div += '</span>'
  div += '<input class="summaryAttr form-control input-sm" data-l1key="cmd" />'
  div += '<span class="input-group-btn">'
  div += '<a class="btn btn-sm listCmdInfo btn-success roundedRight"><i class="fas fa-list-alt"></i></a>'
  div += '</span>'
  div += '</div>'
  div += '</div>'
  div += '<div class="col-sm-2 has-success">'
  div += '<label><input type="checkbox" class="summaryAttr" data-l1key="invert" />{{Inverser}}</label>'
  div += '</div>'
  div += '</div>'
  _el.find('.div_summary').append(div)
  _el.find('.summary').last().setValues(_summary, '.summaryAttr')
}

$('.bt_showObjectSummary').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Vue d'ensemble des objets}}"
  }).load('index.php?v=d&modal=object.summary').dialog('open')
})

//eqLogics tab searching
$('#in_searchCmds').keyup(function() {
  var search = $(this).value()
  if (search == '') {
    $('#eqLogicsCmds .panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    return
  }
  search = jeedomUtils.normTextLower(search)
  $('#eqLogicsCmds .panel-collapse').attr('data-show', 0)
  var text
  $('#eqLogicsCmds .form-group').each(function() {
    text = jeedomUtils.normTextLower($(this).attr('data-cmdname'))
    if (text.indexOf(search) >= 0) {
      $(this).closest('.panel-collapse').attr('data-show', 1)
    }
  })
  $('#eqLogicsCmds .panel-collapse[data-show=1]').collapse('show')
  $('#eqLogicsCmds .panel-collapse[data-show=0]').collapse('hide')
})
$('#bt_openAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='false']").each(function() {
    $(this).click()
  })
})
$('#bt_closeAll').off('click').on('click', function() {
  $(".accordion-toggle[aria-expanded='true']").each(function() {
    $(this).click()
  })
})
$('#bt_resetCmdSearch').on('click', function() {
  $('#in_searchCmds').val('').keyup()
})

//populate eqLogics tab:
function addEqlogicsInfo(_id, _objName, _summay) {
  $('#eqLogicsCmds').empty()
  jeedom.eqLogic.byObjectId({
    object_id: _id,
    onlyVisible: '0',
    onlyEnable: '0',
    getCmd: '1',
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(eqLogics) {
      var summarySelect = '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">'
      summarySelect += '<i class="fas fa-tags"></i>&nbsp;&nbsp;{{Résumé(s)}}&nbsp;&nbsp;<span class="caret"></span>'
      summarySelect += '</button>'
      summarySelect += '<ul class="dropdown-menu" role="menu" style="top:unset;left:unset;height: 190px;overflow: auto;">'
      Object.keys(config_objSummary).forEach(function(key) {
        summarySelect += '<li><a tabIndex="-1"><input type="checkbox" data-value="' + config_objSummary[key].key + '" data-name="' + config_objSummary[key].name + '" />&nbsp' + config_objSummary[key].name + '</a></li>'
      })
      summarySelect += '</ul>'

      var nbEqs = eqLogics.length
      var thisEq, thisId, thisEqName, panel, nbCmds, ndCmdsInfo, humanName
      for (var i = 0; i < nbEqs; i++) {
        thisEq = eqLogics[i]
        thisId = thisEq.id
        thisEqName = thisEq.name
        panel = '<div class="panel-group" style="margin-bottom:5px;">'
        panel += '<div class="panel panel-default">'
        panel += '<div class="panel-heading">'
        panel += '<h3 class="panel-title">'
        panel += '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#eqlogicId-' + thisId + '">' + thisEqName + '</a>'
        panel += '<span><a href="index.php?v=d&p=' + thisEq.eqType_name + '&m=' + thisEq.eqType_name + '&id=' + thisId + '" class="pull-right"><i class="fas fa-external-link-alt"></i></a></span>'
        panel += '</h3>'
        panel += '</div>'
        panel += '<div id="eqlogicId-' + thisId + '" class="panel-collapse collapse">'
        panel += '<div class="panel-body">'

        nbCmds = thisEq.cmds.length
        ndCmdsInfo = 0
        for (var j = 0; j < nbCmds; j++) {
          if (thisEq.cmds[j].type != 'info') continue

          ndCmdsInfo += 1
          humanName = '#[' + _objName + '][' + thisEqName + '][' + thisEq.cmds[j].name + ']#'
          panel += '<form class="form-horizontal">'
          panel += '<div class="form-group" data-cmdname="' + humanName + '">'
          panel += '<label class="col-sm-5 col-xs-6 control-label">' + humanName + '</label>'
          panel += '<div class="col-sm-2 col-xs-4">'
          panel += summarySelect
          panel += '</div>'
          panel += '<div class="col-sm-5 col-xs-2 buttontext"></div>'
          panel += '</div>'
          panel += '</form>'
        }
        panel += '</div>'
        panel += '</div>'
        panel += '</div>'

        if (ndCmdsInfo > 0) $('#eqLogicsCmds').append(panel)
      }

      //set select values:
      for (var i in _summay) {
        for (var j in _summay[i]) {
          updateSummaryButton(_summay[i][j].cmd, i, true)
        }
      }
    }
  })
}

function updateSummaryButton(_cmd, _key, _state) {
  var cmdDiv = $('#eqlogicsTab div[data-cmdname="' + _cmd + '"]')
  cmdDiv.find('ul input[data-value="' + _key + '"]').prop("checked", _state)
  var txtDiv = cmdDiv.find('.buttontext')
  var summaryName = config_objSummary[_key].name
  if (_state) {
    //add new summary:
    cmdDiv.find('i').addClass('warning')
    if (txtDiv.text() == '') {
      txtDiv.text(summaryName)
    } else {
      txtDiv.text(txtDiv.text() + ' | ' + summaryName)
    }
  } else {
    //remove summary:
    var hasSummary = false
    var newText = ''
    txtDiv.text('')
    cmdDiv.find('ul input').each(function() {
      if ($(this).is(':checked')) {
        hasSummary = true
        if (newText == '') {
          newText = $(this).data('name')
        } else {
          newText += ' | ' + $(this).data('name')
        }
      }
    })

    if (hasSummary) {
      cmdDiv.find('i').addClass('warning')
      txtDiv.text(newText)
    } else {
      cmdDiv.find('i').removeClass('warning')
    }
  }
}

//sync eqLogic cmd -> summaryInfo
$('#eqlogicsTab').on({
  'change': function(event) {
    var type = $(this).data('value')
    var cmd = $(this).closest('.form-group').data('cmdname')
    var state = $(this).is(':checked')
    updateSummaryButton(cmd, type, state)
    modifyWithoutSave = true

    var el = $('.type' + type)
    var summary = {
      cmd: cmd,
      enable: "1",
      invert: "0"
    }
    if (el != undefined) {
      if (state) {
        addSummaryInfo(el, summary)
      } else {
        el.find('input[data-l1key="cmd"]').each(function() {
          if ($(this).val() == cmd) {
            $(this).closest('.summary').remove()
          }
        })
      }
      updateSummaryTabNbr(type)
    }
  }
}, 'input[type="checkbox"]')

//update summary tab number:
function updateSummaryTabNbr(type) {
  var tab = $('.summarytabnumber' + type)
  var nbr = $('#summarytab' + type).find('.summary').length
  tab.empty()
  if (nbr > 0) tab.append('(' + nbr + ')')
}