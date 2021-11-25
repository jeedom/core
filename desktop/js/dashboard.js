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

if (SEL_SUMMARY != '') {
  $('#bt_displayObject, #bt_editDashboardWidgetOrder').parent().remove()
}

$('.cmd.cmd-widget.tooltipstered').tooltipster('destroy')

$(function() {
  setTimeout(function() {
    if (typeof rootObjectId != 'undefined') {
      jeedom.object.getImgPath({
        id: rootObjectId,
        success: function(_path) {
          jeedomUtils.setBackgroundImage(_path)
        }
      })
    }
  }, 1)

  //autoResize new created tiles:
  setTimeout(function() {
    $('div.eqLogic-widget > div.autoResize').each(function(index) {
      var h = $(this).outerHeight(true) + $(this).parent().find('.widget-name').outerHeight(true) + 20
      for (var i = 1; i < 40; i++) {
        if (h <= i * 25) {
          h = i * 25
          break
        }
      }
      $(this).closest('div.eqLogic-widget')
        .height(h + 'px')
        .width($(this).outerWidth(true) + 'px')
      $(this).closest('.div_displayEquipement').packery()
    })
    if ($('div.eqLogic-widget > div.autoResize').length) jeedomUI.saveWidgetDisplay({
      dashboard: 1
    })
  }, 250)

  setTimeout(function() {
    $('input', 'textarea', 'select').click(function() {
      $(this).focus()
    })
  }, 750)

  jeedomUI.isEditing = false
  jeedomUI.setEqSignals()
  jeedomUI.setHistoryModalHandler()
})

var modifyWithoutSave = false

//searching
$('#in_searchDashboard').off('keyup').on('keyup', function() {
  if (jeedomUI.isEditing) return
  var search = $(this).value()
  $('.div_object:not(.hideByObjectSel)').show()
  if (search == '') {
    $('.eqLogic-widget').show()
    $('.scenario-widget').show()
    $('.div_displayEquipement').packery()
    return
  }

  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  var match, text
  $('div.eqLogic-widget').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).find('.widget-name').text())
    if (text.includes(search)) match = true

    if ($(this).attr('data-tags') != undefined) {
      text = jeedomUtils.normTextLower($(this).attr('data-tags'))
      if (text.includes(search)) match = true
    }
    if ($(this).attr('data-category') != undefined) {
      text = jeedomUtils.normTextLower($(this).attr('data-category'))
      if (text.includes(search)) match = true
    }
    if ($(this).attr('data-eqType') != undefined) {
      text = jeedomUtils.normTextLower($(this).attr('data-eqType'))
      if (text.includes(search)) match = true
    }
    if ($(this).attr('data-translate-category') != undefined) {
      text = jeedomUtils.normTextLower($(this).attr('data-translate-category'))
      if (text.includes(search)) match = true
    }

    if (not) match = !match

    if (match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  })
  $('div.scenario-widget').each(function() {
    match = false
    text = jeedomUtils.normTextLower($(this).find('.widget-name').text())
    if (text.includes(search)) match = true
    if (match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  })
  $('.div_displayEquipement').each(function() {
    if ($(this).find('div.scenario-widget:visible').length + $(this).find('div.eqLogic-widget:visible').length == 0) {
      $(this).closest('.div_object').hide()
    }
  })
  $('.div_displayEquipement').packery()
})
$('#bt_resetDashboardSearch').on('click', function() {
  if (jeedomUI.isEditing) return
  $('#categoryfilter li .catFilterKey').prop("checked", true)
  $('#dashTopBar button.dropdown-toggle').removeClass('warning')
  $('#in_searchDashboard').val('').keyup()
})


//category filters
$('#categoryfilter').on('click', function(event) {
  event.stopPropagation()
})
$('#catFilterNone').on('click', function() {
  $('#categoryfilter .catFilterKey').each(function() {
    $(this).prop('checked', false)
  })
  filterByCategory()
})
$('#catFilterAll').on('click', function() {
  resetCategoryFilter()
})
$('#categoryfilter .catFilterKey').off('mouseup').on('mouseup', function(event) {
  event.preventDefault()
  event.stopPropagation()

  if (event.which == 2) {
    $('#categoryfilter li .catFilterKey').prop("checked", false)
    $(this).prop("checked", true)
  }
  filterByCategory()
  if (event.which != 2) {
    $(this).prop("checked", !$(this).prop("checked"))
  }
})
$('#categoryfilter li a').on('mousedown', function(event) {
  event.preventDefault()
  var checkbox = $(this).find('.catFilterKey')
  if (!checkbox) return
  if (event.which == 2) {
    if ($('.catFilterKey:checked').length == 1 && checkbox.is(":checked")) {
      $('#categoryfilter li .catFilterKey').prop("checked", true)
    } else {
      $('#categoryfilter li .catFilterKey').prop("checked", false)
      checkbox.prop("checked", true)
    }
  } else {
    checkbox.prop("checked", !checkbox.prop("checked"))
  }
  filterByCategory()
})

function resetCategoryFilter() {
  $('#categoryfilter .catFilterKey').each(function() {
    $(this).prop("checked", true)
  })
  $('div.div_object, div.eqLogic-widget, div.scenario-widget').show()
  $('div.div_displayEquipement').packery()
  $('#dashTopBar button.dropdown-toggle').removeClass('warning')
}

function filterByCategory() {
  //get defined categories:
  var cats = []
  $('#categoryfilter .catFilterKey').each(function() {
    if ($(this).is(':checked')) {
      cats.push($(this).attr('data-key'))
    }
  })
  //check eqLogics cats:
  var eqCats, catFound
  $('div.eqLogic-widget').each(function() {
    catFound = false
    if ($(this).hasAttr('data-translate-category')) {
      eqCats = $(this).attr('data-translate-category').split(',')
      catFound = eqCats.some(r => cats.includes(r))
    } else if ($(this).hasAttr('data-category')) {
      eqCats = $(this).attr('data-category')
      if (cats.findIndex(item => eqCats.toLowerCase() === item.toLowerCase()) >= 0) catFound = true
    } else {
      eqCats = ''
    }
    if (catFound) $(this).show()
    else $(this).hide()
  })

  if (cats.includes('scenario')) {
    $('div.scenario-widget').show()
  } else {
    $('div.scenario-widget').hide()
  }

  $('#div_displayObject div.div_object').each(function() {
    $(this).show()
    if ($(this).find('div.div_displayEquipement > div:visible').length == 0) $(this).hide()
  })

  if (cats.length == $('#categoryfilter .catFilterKey').length) {
    $('#dashTopBar button.dropdown-toggle').removeClass('warning')
  } else {
    $('#dashTopBar button.dropdown-toggle').addClass('warning')
  }

  $('#div_displayObject div.div_displayEquipement').packery()
}


//Preview in Synthesis context:
$(function() {
  $('#dashOverviewPrevSummaries > .objectSummaryContainer').hide().addClass('shadowed')
})
$('#dashOverviewPrev').on({
  'mouseenter': function(event) {

    $('#dashOverviewPrevSummaries > .objectSummaryContainer').hide()

    var width = $(window).width()
    var position = $(this).position()
    var css = {
      top: position.top - 5
    }
    if (position.left > width / 2) {
      css.left = 'unset'
      css.right = width - (position.left + 160)
    } else {
      css.left = position.left
      css.right = 'unset'
    }

    $('.objectSummaryContainer.objectSummary' + $(this).attr('data-object_id')).show().css(css)
  }
}, '.objectPreview')

$('.objectPreview, .objectPreview .name').off('click').on('click', function(event) {
  var url = 'index.php?v=d&p=dashboard&object_id=' + $(this).closest('.objectPreview').attr('data-object_id') + '&childs=0' + '&btover=1'
  if (event.ctrlKey || event.metaKey) {
    window.open(url).focus()
  } else {
    jeedomUtils.loadPage(url)
  }
  return false
})
$('.objectPreview, .objectPreview .name').off('mouseup').on('mouseup', function(event) {
  if (event.which == 2) {
    event.preventDefault()
    var id = $(this).closest('.objectPreview').attr('data-object_id')
    $('.objectPreview[data-object_id="' + id + '"] .name').trigger(jQuery.Event('click', {
      ctrlKey: true
    }))
  }
})

var btOverviewTimer
var $divPreview = $('#dashOverviewPrev')
$('#div_pageContainer').on({
  'mouseenter': function(event) {
    if (!jeedomUI.isEditing) {
      btOverviewTimer = setTimeout(function() {
        $divPreview.show(350)
      }, 300)
    }
  }
}, '#bt_overview')

$('#div_pageContainer').on({
  'mouseleave': function(event) {
    clearTimeout(btOverviewTimer)
  }
}, '#bt_overview')

$('#div_pageContainer').on({
  'mouseleave': function(event) {
    $('#dashOverviewPrevSummaries > .objectSummaryContainer').hide()
    if ($('#bt_overview').attr('data-state') == 0) {
      $divPreview.hide(350)
    }
  }
}, '#dashOverviewPrev')

$('#div_pageContainer').on({
  'click': function(event) {
    if ($(this).hasClass('clickable')) {
      if ($(this).attr('data-state') == 0) {
        $(this).attr('data-state', 1)
        $divPreview.show(350)
      } else {
        $(this).attr('data-state', 0)
        $divPreview.hide(350)
      }
    }
    clearTimeout(btOverviewTimer)
  }
}, '#bt_overview')

//Preview in Dashboard context:
$('.li_object').on('click', function() {
  $('.div_object').parent().removeClass('hideByObjectSel')
  var object_id = $(this).find('a').attr('data-object_id')
  if ($('.div_object[data-object_id=' + object_id + ']').html() != undefined) {
    jeedom.object.getImgPath({
      id: object_id,
      success: function(_path) {
        jeedomUtils.setBackgroundImage(_path)
      }
    })
    $('#dashOverviewPrev .li_object').removeClass('active')
    $(this).addClass('active')
    displayChildObject(object_id, false)
    jeedomUtils.addOrUpdateUrl('object_id', object_id)
  } else {
    jeedomUtils.loadPage($(this).find('a').attr('data-href'))
  }
})

//Edit mode:
function editWidgetMode(_mode, _save) {
  if (!isset(_mode)) {
    if ($('#bt_editDashboardWidgetOrder').attr('data-mode') != undefined && $('#bt_editDashboardWidgetOrder').attr('data-mode') == 1) {
      editWidgetMode(0, false)
      editWidgetMode(1, false)
    }
    return
  }
  var divEquipements = $('div.div_displayEquipement')
  if (_mode == 0) {
    jeedom.cmd.disableExecute = false
    jeedomUI.isEditing = false
    $('#dashTopBar .btn:not(#bt_editDashboardWidgetOrder)').removeClass('disabled')
    if (!isset(_save) || _save) {
      jeedomUI.saveWidgetDisplay({
        dashboard: 1
      })
    }

    divEquipements.find('.editingMode.allowResize').resizable('destroy')
    divEquipements.find('.editingMode').draggable('disable').removeClass('editingMode', '').removeAttr('data-editId')
    divEquipements.find('.cmd.editOptions').remove()

    $('#div_displayObject .row').removeAttr('style')
    $('#dashTopBar').removeClass('editing')
    $('#in_searchDashboard')
      .removeClass('editing')
      .val('')
      .prop('readonly', false)
  } else {
    jeedomUI.isEditing = true
    jeedom.cmd.disableExecute = true
    resetCategoryFilter()
    $('#dashTopBar .btn:not(#bt_editDashboardWidgetOrder)').addClass('disabled')

    //show orders:
    var value
    $('.jeedomAlreadyPosition').each(function() {
      value = $(this).attr('data-order')
      if ($(this).find(".counterReorderJeedom").length) {
        $(this).find(".counterReorderJeedom").text(value)
      } else {
        $(this).prepend('<span class="counterReorderJeedom pull-left">' + value + '</span>')
      }
    })

    //set unique id whatever we have:
    divEquipements.find('div.eqLogic-widget, div.scenario-widget').each(function(index) {
      $(this).addClass('editingMode')
      $(this).attr('data-editId', index)
      $(this).append('<span class="cmd editOptions cursor"></span>')
    })

    //set draggables:
    divEquipements.find('.editingMode').draggable({
      disabled: false,
      distance: 10,
      start: function(event, ui) {
        modifyWithoutSave = true
        jeedomUI.draggingId = $(this).attr('data-editId')
        jeedomUI.orders = {}
        $(this).parent().find('.ui-draggable').each(function(i, itemElem) {
          jeedomUI.orders[jeedomUI.draggingId] = parseInt($(this).attr('data-order'))
        })
      }
    })
    //set resizables:
    divEquipements.find('div.eqLogic-widget.allowResize').resizable({
      start: function(event, ui) {
        modifyWithoutSave = true
      },
      resize: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.div_displayEquipement').packery()
      },
      stop: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.div_displayEquipement').packery()
      }
    })
    divEquipements.find('div.scenario-widget.allowResize').resizable({
      start: function(event, ui) {
        modifyWithoutSave = true
      },
      resize: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.div_displayEquipement').packery()
      },
      stop: function(event, ui) {
        jeedomUtils.positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.div_displayEquipement').packery()
      }
    })

    $('#div_displayObject .row').css('margin-top', '40px')
    $('#dashTopBar').addClass('editing')
    $('#in_searchDashboard')
      .addClass('editing')
      .val("{{Vous êtes en mode édition. Vous pouvez déplacer les tuiles, les redimensionner,  et éditer les commandes (ordre, widget) avec le bouton à droite du titre. N'oubliez pas de quitter le mode édition pour sauvegarder}}")
      .prop('readonly', true)
  }
}
$('#bt_editDashboardWidgetOrder').on('click', function() {
  if ($(this).attr('data-mode') == 1) {
    modifyWithoutSave = false
    $('#md_modal').dialog('close')
    $('div.eqLogic-widget .tooltipstered, div.scenario-widget .tooltipstered').tooltipster('enable')
    $.hideAlert()
    $(this).attr('data-mode', 0)
    editWidgetMode(0)
    $(this).css('color', 'black')
    $('div.div_object .bt_editDashboardTilesAutoResizeUp, div.div_object .bt_editDashboardTilesAutoResizeDown').hide()
    $('.counterReorderJeedom').remove()
    $('.div_displayEquipement').packery()
  } else {
    $('div.eqLogic-widget .tooltipstered, div.scenario-widget .tooltipstered').tooltipster('disable')
    $(this).attr('data-mode', 1)
    $('div.div_object .bt_editDashboardTilesAutoResizeUp, div.div_object .bt_editDashboardTilesAutoResizeDown').show()

    $('div.div_object  .bt_editDashboardTilesAutoResizeUp').off('click').on('click', function() {
      var id_object = $(this).attr('id').replace('expandTiles_object_', '')
      var objectContainer = $('#div_ob' + id_object + '.div_displayEquipement')
      var arHeights = []
      objectContainer.find('div.eqLogic-widget, div.scenario-widget').each(function(index, element) {
        var h = $(this).height()
        arHeights.push(h)
      })
      var maxHeight = Math.max(...arHeights)
      objectContainer.find('div.eqLogic-widget, div.scenario-widget').each(function(index, element) {
        $(this).height(maxHeight)
      })
      objectContainer.packery()
    })

    $('div.div_object  .bt_editDashboardTilesAutoResizeDown').off('click').on('click', function() {
      var id_object = $(this).attr('id').replace('compressTiles_object_', '')
      var objectContainer = $('#div_ob' + id_object + '.div_displayEquipement')
      var arHeights = []
      objectContainer.find('div.eqLogic-widget, div.scenario-widget').each(function(index, element) {
        var h = $(this).height()
        arHeights.push(h)
      })
      var maxHeight = Math.min(...arHeights)
      objectContainer.find('div.eqLogic-widget, div.scenario-widget').each(function(index, element) {
        $(this).height(maxHeight)
      })
      objectContainer.packery()
    })
    editWidgetMode(1)
  }
})

//Dashboard or summary:
var summaryObjEqs = []

function getObjectHtmlFromSummary(_object_id) {
  if (_object_id == null) return
  var $divDisplayEq = $('#div_ob' + _object_id)
  summaryObjEqs[_object_id] = []
  jeedom.object.getEqLogicsFromSummary({
    id: _object_id,
    onlyEnable: '1',
    onlyVisible: '0',
    version: 'dashboard',
    summary: SEL_SUMMARY,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(data) {
      var nbEqs = data.length
      if (nbEqs == 0) {
        $divDisplayEq.closest('.div_object').remove()
        return
      } else {
        $divDisplayEq.closest('.div_object').removeClass('hidden')
      }
      for (var i = 0; i < nbEqs; i++) {
        if (summaryObjEqs[_object_id].includes(data[i].id)) {
          nbEqs--
          return
        }
        summaryObjEqs[_object_id].push(data[i].id)

        jeedom.eqLogic.toHtml({
          id: data[i].id,
          version: 'dashboard',
          error: function(error) {
            $.fn.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(html) {
            if (html.html != '') {
              $divDisplayEq.append(html.html)
            }
            nbEqs--

            //is last ajax:
            if (nbEqs == 0) {
              jeedomUtils.positionEqLogic()
              $divDisplayEq.packery()
              if ($divDisplayEq.find('div.eqLogic-widget:visible, div.scenario-widget:visible').length == 0) {
                $divDisplayEq.closest('.div_object').remove()
              }
            }
          }
        })
      }
    }
  })
}

function getObjectHtml(_object_id) {
  jeedom.object.toHtml({
    id: _object_id,
    version: 'dashboard',
    category: 'all',
    summary: SEL_SUMMARY,
    tag: SEL_TAG,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(html) {
      var $divDisplayEq = $('#div_ob' + _object_id)
      try {
        $.clearDivContent('div_ob' + _object_id)
        $divDisplayEq.html(html)
      } catch (err) {
        console.log(err)
      }
      if (SEL_SUMMARY != '') {
        if ($divDisplayEq.find('div.eqLogic-widget:visible, div.scenario-widget:visible').length == 0) {
          $divDisplayEq.closest('.div_object').remove()
          return
        }
      }
      jeedomUtils.positionEqLogic()
      var container = $divDisplayEq.packery()

      var packData = $divDisplayEq.data('packery')
      if (isset(packData) && packData.items.length == 1) {
        $divDisplayEq.packery('destroy').packery()
      }

      //synch category filter:
      if (SEL_CATEGORY != 'all') {
        var cat = SEL_CATEGORY.charAt(0).toUpperCase() + SEL_CATEGORY.slice(1)
        $('#dashTopBar button.dropdown-toggle').addClass('warning')
        $('#categoryfilter .catFilterKey').each(function() {
          $(this).prop('checked', false)
        })
        $('#categoryfilter .catFilterKey[data-key="' + cat + '"]').prop('checked', true)
        filterByCategory()
      }

      var itemElems = container.find('div.eqLogic-widget, div.scenario-widget')
      container.packery('bindUIDraggableEvents', itemElems)

      $(itemElems).each(function(i, itemElem) {
        $(itemElem).attr('data-order', i + 1)
      })
      container.on('dragItemPositioned', function() {
        jeedomUI.orderItems(container)
      })
    }
  })
}

function displayChildObject(_object_id, _recursion) {
  if (_recursion === false) {
    $('.div_object').parent('.col-md-12').addClass('hideByObjectSel').hide()
  }
  $('.div_object[data-object_id=' + _object_id + ']').parent('.col-md-12').removeClass('hideByObjectSel').show({
    effect: 'drop',
    queue: false
  })
  $('.div_object[data-father_id=' + _object_id + ']').each(function() {
    $(this).parent().show({
      effect: 'drop',
      queue: false
    }).find('.div_displayEquipement').packery()
    displayChildObject($(this).attr('data-object_id'), true)
  })
}

$('#div_pageContainer').on({
  'click': function(event) {
    var eqId = $(this).closest('div.eqLogic-widget').attr('data-eqlogic_id')
    $('#md_modal').dialog({
      title: "{{Configuration Affichage}}"
    }).load('index.php?v=d&modal=eqLogic.dashboard.edit&eqLogic_id=' + eqId).dialog('open')
  }
}, '.editOptions')