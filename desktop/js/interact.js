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

var actionOptions = []
var $interactListContainer = $('.interactListContainer')

document.onkeydown = function(event) {
  if (jeedomUtils.getOpenedModal()) return

  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if ($('#bt_saveInteract').is(':visible')) {
      $("#bt_saveInteract").click()
    }
  }
}

$(function() {
  $('sub.itemsNumber').html('(' + $('.interactDisplayCard').length + ')')
})

//searching
$('#in_searchInteract').keyup(function() {
  var search = $(this).value()
  if (search == '') {
    $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
    $('.interactDisplayCard').show()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }

  $('.panel-collapse:not(.in)').closest('.panel').find('.accordion-toggle').click()
  $('.interactDisplayCard').hide()
  $('.panel-collapse').attr('data-show', 0)
  var match, text
  $('.interactDisplayCard .name').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).text())
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      $(this).closest('.interactDisplayCard').show()
      $(this).closest('.panel-collapse').attr('data-show', 1)
    }
  })
  $('.panel-collapse[data-show=1]').collapse('show')
  $('.panel-collapse[data-show=0]').collapse('hide')
})
$('#bt_resetInteractSearch').on('click', function() {
  $('#in_searchInteract').val('').keyup()
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


//contextMenu:
$(function() {
  try {
    $.contextMenu('destroy', $('.nav.nav-tabs'))
    jeedom.interact.all({
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(interacts) {
        if (interacts.length == 0) {
          return
        }
        var interactGroups = []
        for (i = 0; i < interacts.length; i++) {
          group = interacts[i].group
          if (group == null) continue
          if (group == "") group = 'Aucun'
          group = group[0].toUpperCase() + group.slice(1)
          interactGroups.push(group)
        }
        interactGroups = Array.from(new Set(interactGroups))
        interactGroups.sort()
        var interactList = []
        for (var i = 0; i < interactGroups.length; i++) {
          group = interactGroups[i]
          interactList[group] = []
          for (var j = 0; j < interacts.length; j++) {
            var sc = interacts[j]
            var scGroup = sc.group
            if (scGroup == null) continue
            if (scGroup == "") scGroup = 'Aucun'
            if (scGroup.toLowerCase() != group.toLowerCase()) continue
            if (sc.name == "") sc.name = sc.query
            interactList[group].push([sc.name, sc.id])
          }
        }
        //set context menu!
        var contextmenuitems = {}
        var uniqId = 0
        for (var group in interactList) {
          var groupinteracts = interactList[group]
          var items = {}
          for (var index in groupinteracts) {
            var sc = groupinteracts[index]
            var scName = sc[0]
            var scId = sc[1]
            items[uniqId] = {
              'name': scName,
              'id': scId
            }
            uniqId++
          }
          contextmenuitems[group] = {
            'name': group,
            'items': items
          }
        }

        if (Object.entries(contextmenuitems).length > 0 && contextmenuitems.constructor === Object) {
          $('.nav.nav-tabs').contextMenu({
            selector: 'li',
            autoHide: true,
            zIndex: 9999,
            className: 'interact-context-menu',
            callback: function(key, options, event) {
              if (event.ctrlKey || event.metaKey || event.originalEvent.which == 2) {
                var url = 'index.php?v=d&p=interact&id=' + options.commands[key].id
                if (window.location.hash != '') {
                  url += window.location.hash
                }
                window.open(url).focus()
              } else {
                printInteract(options.commands[key].id)
              }
            },
            items: contextmenuitems
          })
        }
      }
    })
  } catch (err) {}
})

$('.interactAttr[data-l1key=group]').autocomplete({
  source: function(request, response, url) {
    $.ajax({
      type: 'POST',
      url: 'core/ajax/interact.ajax.php',
      data: {
        action: 'autoCompleteGroup',
        term: request.term
      },
      dataType: 'json',
      global: false,
      error: function(request, status, error) {
        handleAjaxError(request, status, error)
      },
      success: function(data) {
        if (data.state != 'ok') {
          $.fn.showAlert({
            message: data.result,
            level: 'danger'
          })
          return
        }
        response(data.result)
      }
    })
  },
  minLength: 1,
})

$('#bt_chooseIcon').on('click', function() {
  var _icon = false
  if ($('div[data-l2key="icon"] > i').length) {
    _icon = $('div[data-l2key="icon"] > i').attr('class')
    _icon = '.' + _icon.replace(' ', '.')
  }
  jeedomUtils.chooseIcon(function(_icon) {
    $('.interactAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
  }, {
    icon: _icon
  })
  modifyWithoutSave = true
})

$('.interactAttr[data-l1key=display][data-l2key=icon]').on('dblclick', function() {
  $(this).value('')
})

$("#div_action").sortable({
  axis: "y",
  cursor: "move",
  items: ".action",
  placeholder: "ui-state-highlight",
  tolerance: "intersect",
  forcePlaceholderSize: true
})

$('.displayInteracQuery').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Liste des interactions}}"
  }).load('index.php?v=d&modal=interact.query.display&interactDef_id=' + $('.interactAttr[data-l1key=id]').value()).dialog('open')
})

$('#bt_interactThumbnailDisplay').on('click', function() {
  if (jeedomUtils.checkPageModified()) return
  $('#div_conf').hide()
  $('#interactThumbnailDisplay').show()
  jeedomUtils.addOrUpdateUrl('id', null, '{{Interactions}} - ' + JEEDOM_PRODUCT_NAME)
})

$('.interactDisplayCard').off('click').on('click', function(event) {
  if (event.ctrlKey || event.metaKey) {
    var url = '/index.php?v=d&p=interact&id=' + $(this).attr('data-interact_id')
    window.open(url).focus()
  } else {
    printInteract($(this).attr('data-interact_id'))
  }
})
$('.interactDisplayCard').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).attr('data-interact_id')
    $('.interactDisplayCard[data-interact_id="' + id + '"]').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

$('#bt_duplicate').on('click', function() {
  bootbox.prompt("{{Nom}} ?", function(result) {
    if (result !== null) {
      var interact = $('.interact').getValues('.interactAttr')[0]
      interact.actions = {}
      interact.actions.cmd = $('#div_action .action').getValues('.expressionAttr')
      interact.name = result
      interact.id = ''
      jeedom.interact.save({
        interact: interact,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          modifyWithoutSave = false;
          jeedomUtils.loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1')
        }
      })
    }
  })
})

if (is_numeric(getUrlVars('id'))) {
  if ($('.interactDisplayCard[data-interact_id=' + getUrlVars('id') + ']').length != 0) {
    $('.interactDisplayCard[data-interact_id=' + getUrlVars('id') + ']').click();
  }
}

$('#bt_testInteract,#bt_testInteract2').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Tester les interactions}}"
  }).load('index.php?v=d&modal=interact.test').dialog('open')
})

$('#div_conf').on({
  'click': function(event) {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'info'
      }
    }, function(result) {
      $('.interactAttr[data-l1key=reply]').atCaret('insert', result.human)
    })
  }
}, '.listEquipementInfoReply')

$("#bt_saveInteract").on('click', function() {
  var interact = $('.interact').getValues('.interactAttr')[0]
  interact.filtres.type = {}
  $('option[data-l1key=filtres][data-l2key=type]').each(function() {
    interact.filtres.type[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0'
  })
  interact.filtres.subtype = {}
  $('option[data-l1key=filtres][data-l2key=subtype]').each(function() {
    interact.filtres.subtype[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0'
  })
  interact.filtres.unite = {}
  $('option[data-l1key=filtres][data-l2key=unite]').each(function() {
    interact.filtres.unite[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0'
  })
  interact.filtres.object = {}
  $('option[data-l1key=filtres][data-l2key=object]').each(function() {
    interact.filtres.object[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0'
  })
  interact.filtres.plugin = {}
  $('option[data-l1key=filtres][data-l2key=plugin]').each(function() {
    interact.filtres.plugin[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0'
  })
  interact.filtres.category = {}
  $('option[data-l1key=filtres][data-l2key=category]').each(function() {
    interact.filtres.category[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0'
  })
  interact.filtres.visible = {}
  $('option[data-l1key=filtres][data-l2key=visible]').each(function() {
    interact.filtres.visible[$(this).attr('data-l3key')] = ($(this).prop('selected') === true) ? '1' : '0'
  })

  interact.actions = {}
  interact.actions.cmd = $('#div_action .action').getValues('.expressionAttr')

  jeedom.interact.save({
    interact: interact,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('.interactDisplayCard[data-interact_id=' + data.id + ']').click()
      $.fn.showAlert({
        message: '{{Sauvegarde réussie avec succès}}',
        level: 'success'
      })
    }
  })
})

$("#bt_regenerateInteract,#bt_regenerateInteract2").on('click', function() {
  bootbox.confirm('{{Êtes-vous sûr de vouloir régénérer toutes les interactions (cela peut être très long) ?}}', function(result) {
    if (result) {
      jeedom.interact.regenerateInteract({
        interact: {
          query: result
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          $.fn.showAlert({
            message: '{{Toutes les interactions ont été régénérées}}',
            level: 'success'
          })
        }
      })
    }
  })
})

$("#bt_addInteract,#bt_addInteract2").on('click', function() {
  bootbox.prompt("{{Demande}} ?", function(result) {
    if (result !== null) {
      jeedom.interact.save({
        interact: {
          query: result
        },
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          modifyWithoutSave = false;
          jeedomUtils.loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1')
        }
      })
    }
  })
})

$("#bt_removeInteract").on('click', function() {
  $.hideAlert()
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer l\'interaction}} <span style="font-weight: bold ;">' + $('.interactDisplayCard.active .name').text() + '</span> ?', function(result) {
    if (result) {
      jeedom.interact.remove({
        id: $('.interactDisplayCard.active').attr('data-interact_id'),
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          modifyWithoutSave = false
          jeedomUtils.loadPage('index.php?v=d&p=interact&removeSuccessFull=1')
        }
      })
    }
  })
})

$('#bt_addAction').off('click').on('click', function() {
  addAction({}, 'action', '{{Action}}')
  modifyWithoutSave = true
})

$('#div_conf').on({
  'focusout': function(event) {
    var type = $(this).attr('data-type')
    var expression = $(this).closest('.' + type).getValues('.expressionAttr')
    var el = $(this)
    jeedom.cmd.displayActionOption($(this).value(), init(expression[0].options), function(html) {
      el.closest('.' + type).find('.actionOptions').html(html)
      jeedomUtils.taAutosize()
    })
  }
}, '.cmdAction.expressionAttr[data-l1key=cmd]')

$('#div_conf').on({
  'click': function(event) {
    var type = $(this).attr('data-type')
    var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]')
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'info'
      }
    }, function(result) {
      el.value(result.human)
      jeedom.cmd.displayActionOption(el.value(), '', function(html) {
        el.closest('.' + type).find('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
  }
}, '.listCmd')

$('#div_conf').on({
  'click': function(event) {
    var type = $(this).attr('data-type')
    var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]')
    jeedom.getSelectActionModal({}, function(result) {
      el.value(result.human)
      jeedom.cmd.displayActionOption(el.value(), '', function(html) {
        el.closest('.' + type).find('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
  }
}, '.listAction')

$('#div_conf').on({
  'click': function(event) {
    var type = $(this).attr('data-type')
    var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]')
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'action'
      }
    }, function(result) {
      el.value(result.human)
      jeedom.cmd.displayActionOption(el.value(), '', function(html) {
        el.closest('.' + type).find('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
  }
}, '.listCmdAction')

$('#div_conf').on({
  'click': function(event) {
    var type = $(this).attr('data-type')
    $(this).closest('.' + type).remove()
    modifyWithoutSave = true
  }
}, '.bt_removeAction')

function printInteract(_id) {
  $.hideAlert()
  $('#div_conf').show()
  $('#interactThumbnailDisplay').hide()
  $('.interactDisplayCard').removeClass('active')
  $('.interactDisplayCard[data-interact_id=' + _id + ']').addClass('active')
  jeedom.interact.get({
    id: _id,
    success: function(data) {
      actionOptions = []
      $('#div_action').empty()
      $('.interactAttr').value('')
      $('.interact').setValues(data, '.interactAttr')
      $('.interactAttr[data-l1key=filtres][data-l2key=type]').prop('selected', false)
      $('.interactAttr[data-l1key=filtres][data-l2key=subtype]').prop('selected', false)
      $('.interactAttr[data-l1key=filtres][data-l2key=unite]').prop('selected', false)
      $('.interactAttr[data-l1key=filtres][data-l2key=object]').prop('selected', false)
      $('.interactAttr[data-l1key=filtres][data-l2key=plugin]').prop('selected', false)
      $('.interactAttr[data-l1key=filtres][data-l2key=category]').prop('selected', false)
      if (isset(data.filtres) && isset(data.filtres.type) && $.isPlainObject(data.filtres.type)) {
        for (var i in data.filtres.type) {
          if (data.filtres.type[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=type][data-l3key=' + i + ']').prop('selected', true)
        }
      }
      if (isset(data.filtres) && isset(data.filtres.subtype) && $.isPlainObject(data.filtres.subtype)) {
        for (var i in data.filtres.subtype) {
          if (data.filtres.subtype[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=subtype][data-l3key=' + i + ']').prop('selected', true)
        }
      }
      if (isset(data.filtres) && isset(data.filtres.unite) && $.isPlainObject(data.filtres.unite)) {
        for (var i in data.filtres.unite) {
          if (data.filtres.unite[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=unite][data-l3key="' + i + '"]').prop('selected', true)
        }
      }
      if (isset(data.filtres) && isset(data.filtres.object) && $.isPlainObject(data.filtres.object)) {
        for (var i in data.filtres.object) {
          if (data.filtres.object[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=object][data-l3key=' + i + ']').prop('selected', true)
        }
      }
      if (isset(data.filtres) && isset(data.filtres.plugin) && $.isPlainObject(data.filtres.plugin)) {
        for (var i in data.filtres.plugin) {
          if (data.filtres.plugin[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=plugin][data-l3key=' + i + ']').prop('selected', true)
        }
      }
      if (isset(data.filtres) && isset(data.filtres.category) && $.isPlainObject(data.filtres.category)) {
        for (var i in data.filtres.category) {
          if (data.filtres.category[i] == 1) $('.interactAttr[data-l1key=filtres][data-l2key=category][data-l3key=' + i + ']').prop('selected', true)
        }
      }
      if (isset(data.actions.cmd) && $.isArray(data.actions.cmd) && data.actions.cmd.length != null) {
        for (var i in data.actions.cmd) {
          addAction(data.actions.cmd[i], 'action', '{{Action}}');
        }
      }
      jeedomUtils.taAutosize()

      var hash = window.location.hash
      jeedomUtils.addOrUpdateUrl('id', data.id)
      if (hash == '') {
        $('.nav-tabs a[href="#generaltab"]').click()
      } else {
        window.location.hash = hash
      }

      jeedom.cmd.displayActionsOption({
        params: actionOptions,
        async: false,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          for (var i in data) {
            if (data[i].html != '') {
              $('#' + data[i].id).append(data[i].html.html)
            }
          }
          jeedomUtils.taAutosize()
        }
      })

      $('#div_pageContainer').off('change', '.interactAttr').on('change', '.interactAttr:visible', function() {
        modifyWithoutSave = true
      })
      $('#div_pageContainer').off('mousedown', 'select option.interactAttr').on('mousedown', 'select option.interactAttr:visible', function() {
        modifyWithoutSave = true
      })
      modifyWithoutSave = false
    }
  })
}

function addAction(_action, _type, _name) {
  if (!isset(_action)) {
    _action = {}
  }
  if (!isset(_action.options)) {
    _action.options = {}
  }
  var div = '<div class="' + _type + '">'
  div += '<div class="form-group ">'
  div += '<div class="col-sm-5">'
  div += '<div class="input-group input-group-sm">'
  div += '<span class="input-group-btn">'
  div += '<a class="btn btn-default btn-sm bt_removeAction roundedLeft" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>'
  div += '</span>'
  div += '<input class="expressionAttr form-control cmdAction input-sm" data-l1key="cmd" data-type="' + _type + '" />'
  div += '<span class="input-group-btn">'
  div += '<a class="btn btn-default btn-sm listAction"" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></a>'
  div += '<a class="btn btn-default btn-sm listCmdAction roundedRight" data-type="' + _type + '"><i class="fas fa-list-alt"></i></a>'
  div += '</span>'
  div += '</div>'
  div += '</div>'
  var actionOption_id = jeedomUtils.uniqId()
  div += '<div class="col-sm-7 actionOptions" id="' + actionOption_id + '"></div>'
  $('#div_' + _type).append(div)
  $('#div_' + _type + ' .' + _type + '').last().setValues(_action, '.expressionAttr')
  actionOptions.push({
    expression: init(_action.cmd, ''),
    options: _action.options,
    id: actionOption_id
  })
}