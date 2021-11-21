/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

"use strict"

var GLOBAL_ACTION_MODE = null

//searching
$('#in_search').on('keyup', function() {
  try {
    var search = $(this).value()
    var searchID = search
    if (isNaN(search)) searchID = false

    $('.panel-collapse').attr('data-show', 0)
    $('.cmd').show().removeClass('alert-success').addClass('alert-info')
    $('.eqLogic').show()
    $('.cmdSortable').hide()
    if (!search.startsWith('*') && searchID == false) {
      if ((search == '' || _nbCmd_ <= 1500 && search.length < 3) || (_nbCmd_ > 1500 && search.length < 4)) {
        $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
        return
      }
    } else {
      if (search == '*') return
      search = search.substr(1)
    }
    search = jeedomUtils.normTextLower(search)
    var eqLogic, eqParent, eqId, cmd, cmdId
    var eqName, type, category, cmdName
    $('.eqLogic').each(function() {
      eqLogic = $(this)
      eqParent = eqLogic.parents('.panel.panel-default').first()
      if (searchID) {
        eqId = eqLogic.attr('data-id')
        if (eqId != searchID) {
          eqLogic.hide()
        } else {
          eqParent.find('div.panel-collapse').addClass('in')
          return
        }
        $(this).find('.cmd').each(function() {
          cmd = $(this)
          cmdId = cmd.attr('data-id')
          if (cmdId == searchID) {
            eqLogic.parents('.panel-collapse').attr('data-show', 1)
            eqLogic.show()
            eqLogic.find('.cmdSortable').show()
            cmd.removeClass('alert-warning').addClass('alert-success')
            return
          }
        })
      } else {
        eqName = jeedomUtils.normTextLower(eqLogic.attr('data-name'))
        type = jeedomUtils.normTextLower(eqLogic.attr('data-type'))
        category = jeedomUtils.normTextLower(eqLogic.attr('data-translate-category'))
        if (eqName.indexOf(search) < 0 && type.indexOf(search) < 0 && category.indexOf(search) < 0) {
          eqLogic.hide()
        } else {
          eqLogic.parents('.panel-collapse').attr('data-show', 1)
        }
        eqLogic.find('.cmd').each(function() {
          cmd = $(this)
          cmdName = cmd.attr('data-name')
          cmdName = jeedomUtils.normTextLower(cmdName)
          if (cmdName.indexOf(search) >= 0) {
            eqLogic.parents('.panel-collapse').attr('data-show', 1)
            eqLogic.show()
            eqLogic.find('.cmdSortable').show()
            cmd.removeClass('alert-warning').addClass('alert-success')
          }
        })
      }
    })
    $('.panel-collapse[data-show=1]').collapse('show')
    $('.panel-collapse[data-show=0]').collapse('hide')
  } catch (error) {
    console.error(error)
  }
})
$('#bt_resetdisplaySearch').on('click', function() {
  $('#in_search').val('').keyup()
})
$('#bt_openAll').off('click').on('click', function(event) {
  $(".accordion-toggle[aria-expanded='false']").click()
  if (event.ctrlKey || event.metaKey) {
    $('.cmdSortable').show()
  }
})
$('#bt_closeAll').off('click').on('click', function(event) {
  $(".accordion-toggle[aria-expanded='true']").click()
  if (event.ctrlKey || event.metaKey) {
    $('.cmdSortable').hide()
  }
})

//Sorting:
$('#accordionObject').sortable({
  cursor: "move",
  items: ".objectSortable",
  stop: function(event, ui) {
    var objects = []
    $('.objectSortable .panel-heading').each(function() {
      objects.push($(this).attr('data-id'))
    })
    jeedom.object.setOrder({
      objects: objects,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      }
    })
  }
}).disableSelection()

$('.eqLogicSortable').sortable({
  cursor: "move",
  connectWith: ".eqLogicSortable",
  start: function(event, info) {
    //get checked eqlogics in this object:
    $(this).closest('ul.eqLogicSortable').find('.ui-sortable-handle').each(function() {
      if ($(this).find('.cb_selEqLogic').prop('checked') == true) {
        $(this).appendTo(info.item)
      }
    })
  },
  stop: function(event, ui) {
    //set appended eqlogics:
    try {
      ui.item.find('li.eqLogic').each(function(index) {
        ui.item.after($(this))
      })
    } catch (error) {
      console.log('eqLogic sorting error:' + error)
    }
    //set object order:
    var eqLogics = []
    var object = ui.item.closest('.objectSortable')
    var objectId = object.find('.panel-heading').attr('data-id')
    var order = 1
    var eqLogic
    object.find('.eqLogic').each(function() {
      eqLogic = {}
      eqLogic.object_id = objectId
      eqLogic.id = $(this).attr('data-id')
      eqLogic.order = order
      eqLogics.push(eqLogic)
      order++
    })
    jeedom.eqLogic.setOrder({
      eqLogics: eqLogics,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
        $(".eqLogicSortable").sortable("cancel")
      }
    })
    $(event.originalEvent.target).click()
  }
}).disableSelection()

$('.cmdSortable').sortable({
  cursor: "move",
  stop: function(event, ui) {
    var cmds = []
    var eqLogic = ui.item.closest('.eqLogic')
    var order = 1
    var cmd
    eqLogic.find('.cmd').each(function() {
      cmd = {}
      cmd.id = $(this).attr('data-id')
      cmd.order = order
      cmds.push(cmd)
      order++
    })
    jeedom.cmd.setOrder({
      cmds: cmds,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      }
    })
  }
}).disableSelection()

//Modals:
$('.configureObject').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de l'objet}}"
  }).load('index.php?v=d&modal=object.configure&object_id=' + $(this).closest('.panel-heading').attr('data-id')).dialog('open')
})

$('.configureEqLogic').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de l'équipement}}"
  }).load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $(this).closest('.eqLogic').attr('data-id')).dialog('open')
})

$('.configureCmd').off('click').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Configuration de la commande}}"
  }).load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-id')).dialog('open')
})

$('.cmd').off('dblclick').on('dblclick', function() {
  if ($(this).find('.configureCmd').length) {
    $('#md_modal').dialog({
      title: "{{Configuration de la commande}}"
    }).load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open')
  }
})

//events:
$('.bt_exportcsv').on('click', function() {
  var fullFile = ''
  var eqLogic, eqParent, cmd
  $('.eqLogic').each(function() {
    eqLogic = $(this)
    eqParent = eqLogic.parents('.panel.panel-default').first()
    eqParent = eqParent.find('a.accordion-toggle').text()
    fullFile += eqParent + ',' + eqLogic.attr('data-id') + ',' + eqLogic.attr('data-name') + ',' + eqLogic.attr('data-type') + "\n"
    eqLogic.find('.cmd').each(function() {
      cmd = $(this)
      fullFile += "\t\t" + cmd.attr('data-id') + ',' + cmd.attr('data-name') + "\n"
    })
  })
  $('.bt_exportcsv').attr('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(fullFile))
})


$('.objectSelectEqlogics').on('click', function() {
  var object = $(this).closest('.objectSortable')
  if (object.find('.accordion-toggle').attr('aria-expanded') == 'false') object.find('.accordion-toggle').click()

  //inactive or not:
  var elements
  if ($('#cb_actifDisplay').is(':checked')) elements = $(this).closest('.objectSortable').find('li.eqLogic .cb_selEqLogic')
  else elements = $(this).closest('.objectSortable').find('li.eqLogic[data-enable="1"] .cb_selEqLogic')

  elements.each(function() {
    $(this).prop('checked', true)
  })
  setEqActions()
})
$('.objectUnselectEqlogics').on('click', function() {
  var object = $(this).closest('.objectSortable')
  $(this).closest('.objectSortable').find('li.eqLogic .cb_selEqLogic').each(function() {
    $(this).prop('checked', false)
  })
  setEqActions()
})

$('.eqLogicSortable > li.eqLogic').on('click', function(event) {
  if (event.target.tagName.toUpperCase() == 'I') return
  //checkbox clicked:
  if (event.target.tagName.toUpperCase() == 'INPUT') return
  //cmd cliked inside li:
  if ($(event.target).hasClass('cmd')) {
    $(event.target).find('.configureCmd').click()
    return false
  }

  if (!$(event.target).hasClass('eqLogic')) {
    event.stopPropagation()
    return false
  }
  var $el = $(this).find('ul.cmdSortable')
  if ($el.is(':visible')) {
    $el.hide()
  } else {
    $el.show()
  }
})

$('#cb_actifDisplay').on('change', function() {
  if ($(this).value() == 1) {
    $('.eqLogic[data-enable=0]').show()
  } else {
    $('.eqLogic[data-enable=0]').hide()
  }
})

$('[aria-controls="historytab"]').on('click', function() {
  $('.eqActions').hide()
  setRemoveHistoryTable()
})

function setRemoveHistoryTable() {
  var $tableRemoveHistory = $('#table_removeHistory')
  jeedomUtils.initTableSorter()
  $tableRemoveHistory[0].config.widgetOptions.resizable_widths = ['180px', '160px', '80px', '']
  $tableRemoveHistory.trigger('applyWidgets')
    .trigger('resizableReset')
    .trigger('sorton', [
      [
        [0, 1]
      ]
    ])
  $tableRemoveHistory.trigger("update")
}

$('[aria-controls="displaytab"]').on('click', function() {
  $('#display').show()
  if (GLOBAL_ACTION_MODE) $('.eqActions').show()
})

$('.cb_selEqLogic').on('change', function() {
  setEqActions()
})

function setEqActions() {
  var found = false
  $('.cb_selEqLogic').each(function() {
    if ($(this).value() == 1) {
      found = true
      return
    }
  })
  if (found) {
    GLOBAL_ACTION_MODE = 'eqLogic'
    $('.eqActions').show()
    $('.cb_selCmd').hide()
    $('#bt_removeEqlogic').show()
    $('.bt_setIsVisible').show()
    $('.bt_setIsEnable').show()
  } else {
    GLOBAL_ACTION_MODE = null
    $('.eqActions').hide()
    $('.cb_selCmd').show()
    $('#bt_removeEqlogic').hide()
    $('.bt_setIsVisible').hide()
    $('.bt_setIsEnable').hide()
  }
}

$('.cb_selCmd').on('change', function() {
  var found = false
  $('.cb_selCmd').each(function() {
    if ($(this).value() == 1) {
      found = true;
      return;
    }
  })
  if (found) {
    GLOBAL_ACTION_MODE = 'cmd'
    $('.eqActions').show()
    $('.cb_selEqLogic').hide()
    $('.bt_setIsVisible').show()
  } else {
    GLOBAL_ACTION_MODE = null
    $('.cb_selEqLogic').show()
    $('.eqActions').hide()
    $('.bt_setIsVisible').hide()
  }
})

$('#bt_removeEqlogic').on('click', function() {
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer tous ces équipements ?}}', function(result) {
    if (result) {
      var eqLogics = []
      $('.cb_selEqLogic').each(function() {
        if ($(this).value() == 1) {
          eqLogics.push($(this).closest('.eqLogic').attr('data-id'))
        }
      })
      jeedom.eqLogic.removes({
        eqLogics: eqLogics,
        error: function(error) {
          $.fn.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.loadPage('index.php?v=d&p=display')
        }
      })
    }
  })
})

$('.bt_setIsVisible').on('click', function() {
  if (GLOBAL_ACTION_MODE == 'eqLogic') {
    var eqLogics = []
    $('.cb_selEqLogic').each(function() {
      if ($(this).value() == 1) {
        eqLogics.push($(this).closest('.eqLogic').attr('data-id'))
      }
    })
    jeedom.eqLogic.setIsVisibles({
      eqLogics: eqLogics,
      isVisible: $(this).attr('data-value'),
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedomUtils.loadPage('index.php?v=d&p=display')
      }
    })
  }

  if (GLOBAL_ACTION_MODE == 'cmd') {
    var cmds = []
    $('.cb_selCmd').each(function() {
      if ($(this).value() == 1) {
        cmds.push($(this).closest('.cmd').attr('data-id'))
      }
    })
    jeedom.cmd.setIsVisibles({
      cmds: cmds,
      isVisible: $(this).attr('data-value'),
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function() {
        jeedomUtils.loadPage('index.php?v=d&p=display')
      }
    })
  }
})

$('.bt_setIsEnable').on('click', function() {
  var eqLogics = []
  $('.cb_selEqLogic').each(function() {
    if ($(this).value() == 1) {
      eqLogics.push($(this).closest('.eqLogic').attr('data-id'))
    }
  })
  jeedom.eqLogic.setIsEnables({
    eqLogics: eqLogics,
    isEnable: $(this).attr('data-value'),
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function() {
      jeedomUtils.loadPage('index.php?v=d&p=display')
    }
  })
})

$('#bt_removeHistory').on('click', function() {
  $('#md_modal').dialog({
    title: "{{Historique des suppressions}}"
  }).load('index.php?v=d&modal=remove.history').dialog('open')
})

$('#bt_emptyRemoveHistory').on('click', function() {
  jeedom.emptyRemoveHistory({
    error: function(error) {
      $('#div_alertRemoveHistory').showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      $('#table_removeHistory tbody').empty()
      $('#div_alertRemoveHistory').showAlert({
        message: '{{Historique vidé avec succès}}',
        level: 'success'
      })
    }
  })
})
