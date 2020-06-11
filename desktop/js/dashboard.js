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

//show each object elements:
Array.from(document.getElementsByClassName('div_object')).forEach(
  function(element, index, array) {
    getObjectHtml(element.getAttribute('data-object_id'))
  }
)

$('.cmd.cmd-widget.tooltipstered').tooltipster('destroy')

$(function() {
  setTimeout(function() {
    if (typeof rootObjectId != 'undefined') {
      jeedom.object.getImgPath({
        id : rootObjectId,
        success : function(_path) {
          setBackgroundImg(_path)
        }
      })
    }
  }, 1)

  //autoResize new created tiles:
  setTimeout(function() {
    $('.eqLogic-widget > div.autoResize').each(function( index ) {
      var h = $(this).outerHeight(true) + $(this).parent().find('.widget-name').outerHeight(true) + 20
      for (var i = 1; i < 40; i++) {
        if (h <= i * 25) {
          h = i * 25
          break
        }
      }
      $(this).closest('.eqLogic-widget')
      .height(h + 'px')
      .width($(this).outerWidth(true) + 'px')
      $(this).closest('.div_displayEquipement').packery()
    })
    if ($('.eqLogic-widget > div.autoResize').length) jeedomUI.saveWidgetDisplay({dashboard : 1})
  }, 250)

  setTimeout(function() {
    $('input', 'textarea', 'select').click(function() { $(this).focus() })
  }, 750)

  jeedomUI.setEqSignals()
  jeedomUI.setHistoryModalHandler()
})

var isEditing = false
//searching
$('#in_searchWidget').off('keyup').on('keyup',function() {
  if (isEditing) return
  var search = $(this).value()
  $('.div_object:not(.hideByObjectSel)').show()
  if (search == '') {
    $('.eqLogic-widget').show()
    $('.scenario-widget').show()
    $('.div_displayEquipement').packery()
    return
  }

  search = normTextLower(search)
  $('.eqLogic-widget').each(function() {
    var match = false
    var text = normTextLower($(this).find('.widget-name').text())
    if (text.indexOf(search) >= 0) match = true

    if ($(this).attr('data-tags') != undefined) {
      text = normTextLower($(this).attr('data-tags'))
      if (text.indexOf(search) >= 0) match = true
    }
    if ($(this).attr('data-category') != undefined) {
      text = normTextLower($(this).attr('data-category'))
      if (text.indexOf(search) >= 0) match = true
    }
    if ($(this).attr('data-eqType') != undefined) {
      text = normTextLower($(this).attr('data-eqType'))
      if (text.indexOf(search) >= 0) match = true
    }
    if ($(this).attr('data-translate-category') != undefined) {
      text = normTextLower($(this).attr('data-translate-category'))
      if (text.indexOf(search) >= 0) match = true
    }

    if (match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  })
  $('.scenario-widget').each(function() {
    var match = false
    var text = normTextLower($(this).find('.widget-name').text())
    if (text.indexOf(search) >= 0) match = true
    if (match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  })
  $('.div_displayEquipement').packery()
  $('.div_displayEquipement').each(function() {
    var count = $(this).find('.scenario-widget:visible').length + $(this).find('.eqLogic-widget:visible').length
    if (count == 0) {
      $(this).closest('.div_object').hide()
    }
  })
})
$('#bt_resetDashboardSearch').on('click', function () {
  if (isEditing) return
  $('#categoryfilter li .catFilterKey').prop("checked", true)
  $('#dashTopBar button.dropdown-toggle').removeClass('warning')
  $('#in_searchWidget').val('').keyup()
})

//category filters
$('#categoryfilter').on('click', function(event) {
  event.stopPropagation()
})
$('#catFilterNone').on('click', function () {
  $('#categoryfilter .catFilterKey').each(function() {
    $(this).prop('checked', false)
  })
  filterByCategory()
})
$('#catFilterAll').on('click', function() {
  $('#categoryfilter .catFilterKey').each(function() {
    $(this).prop('checked', true)
  })
  filterByCategory()
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
  $('.eqLogic-widget, .scenario-widget').each(function() {
    $(this).show()
  })
  $('.div_displayEquipement').packery()
}

function filterByCategory() {
  var cats = []
  $('#categoryfilter .catFilterKey').each(function() {
    if ($(this).is(':checked')) {
      cats.push($(this).attr('data-key'))
    }
  })
  $('.eqLogic-widget').each(function() {
    var cat = $(this).attr('data-category')
    if (cats.includes(cat)) $(this).show()
    else $(this).hide()
  })
  if (cats.includes('scenario')) {
    $('.scenario-widget').show()
  } else {
    $('.scenario-widget').hide()
  }
  $('.div_displayEquipement').packery()
  if (cats.length == $('#categoryfilter .catFilterKey').length) {
    $('#dashTopBar button.dropdown-toggle').removeClass('warning')
  } else {
    $('#dashTopBar button.dropdown-toggle').addClass('warning')
  }
}

$('#bt_displayObject').on('click', function () {
  if (isEditing) return
  if ($(this).attr('data-display') == 1) {
    $('#div_displayObjectList').hide()
    $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-9 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12')
    $('.div_displayEquipement').each(function () {
      $(this).packery()
    })
    $(this).attr('data-display', 0)
  } else {
    $('#div_displayObjectList').show()
    $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8')
    $('.div_displayEquipement').packery()
    $(this).attr('data-display', 1)
  }
})

$('#div_pageContainer').delegate('.editOptions', 'click', function () {
  var eqId = $(this).closest('.eqLogic-widget').attr('data-eqlogic_id')
  $('#md_modal').dialog({title: "{{Configuration}}"}).load('index.php?v=d&modal=eqLogic.configure&eqLogic_id='+eqId).dialog('open')
})

var _draggingId = false
var _orders = {}
function editWidgetMode(_mode,_save) {
  if (!isset(_mode)) {
    if ($('#bt_editDashboardWidgetOrder').attr('data-mode') != undefined && $('#bt_editDashboardWidgetOrder').attr('data-mode') == 1) {
      editWidgetMode(0,false)
      editWidgetMode(1,false)
    }
    return
  }
  var divEquipements = $('.div_displayEquipement')
  if (_mode == 0) {
    jeedom.cmd.disableExecute = false
    isEditing = false
    $('#dashTopBar').removeClass('disabled')
    if (!isset(_save) || _save) {
      jeedomUI.saveWidgetDisplay({dashboard : 1})
    }

    divEquipements.find('.editingMode.allowResize').resizable('destroy')
    divEquipements.find('.editingMode').draggable('disable').removeClass('editingMode','').removeAttr('data-editId')
    divEquipements.find('.cmd.editOptions').remove()

    $('#div_displayObject .row').removeAttr('style')
    $('#dashTopBar').removeAttr('style')
    $('#in_searchWidget').removeAttr('style').val('').prop('readonly', false)
  } else {
    jeedom.cmd.disableExecute = true
    isEditing = true
    resetCategoryFilter()
    $('#dashTopBar').addClass('disabled')

    //show orders:
    $('.ui-draggable').each( function() {
      var value = $(this).attr('data-order')
      if ($(this).find(".counterReorderJeedom").length) {
        $(this).find(".counterReorderJeedom").text(value)
      } else {
        $(this).prepend('<span class="counterReorderJeedom pull-left" style="margin-top: 3px;margin-left: 3px;">'+value+'</span>')
      }
    })

    //set unique id whatever we have:
    divEquipements.find('.eqLogic-widget, .scenario-widget').each(function(index) {
      $(this).addClass('editingMode')
      $(this).attr('data-editId', index)
      $(this).append('<span class="cmd editOptions cursor"></span>')
    })

    //set draggables:
    divEquipements.find('.editingMode').draggable({
      disabled: false,
      distance: 10,
      start: function(event, ui) {
        _draggingId = $(this).attr('data-editId')
        _orders = {}
        $(this).parent().find('.ui-draggable').each(function(i, itemElem ) {
          _orders[_draggingId] = parseInt($(this).attr('data-order'))
        })
      }
    })
    //set resizables:
    divEquipements.find('.eqLogic-widget.allowResize').resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.div_displayEquipement').packery()
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.div_displayEquipement').packery()
      }
    })
    divEquipements.find('.scenario-widget.allowResize').resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.div_displayEquipement').packery()
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.div_displayEquipement').packery()
      }
    })

    $('#div_displayObject .row').css('margin-top', '27px')
    $('#dashTopBar').css({"position":"fixed","top":"55px","z-index":"500","width":"calc(100% - "+($('body').width() - $('#dashTopBar').width())+'px)'})
    $('#in_searchWidget').style("background-color", "var(--al-info-color)", "important")
    .style("color", "var(--linkHoverLight-color)", "important")
    .val("{{Vous êtes en mode édition vous pouvez déplacer les widgets, les redimensionner et changer l'ordre des commandes dans les widgets. N'oubliez pas de quitter le mode édition pour sauvegarder}}")
    .prop('readonly', true)
  }
}

function getObjectHtml(_object_id) {
  jeedom.object.toHtml({
    id: _object_id,
    version: 'dashboard',
    category : SEL_CATEGORY,
    summary : SEL_SUMMARY,
    tag : SEL_TAG,
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function(html) {
      var $divDisplayEq = $('#div_ob'+_object_id)
      try {
        $divDisplayEq.empty().html(html)
      } catch(err) {
        console.log(err)
      }
      if (SEL_SUMMARY != '') {
        if ($divDisplayEq.find('.eqLogic-widget:visible, .scenario-widget:visible').length == 0) {
          $divDisplayEq.closest('.div_object').remove()
          return
        }
      }
      positionEqLogic()

      var container = $divDisplayEq.packery()
      var packData = $divDisplayEq.data('packery')
      if (isset(packData) && packData.items.length == 1) {
        $divDisplayEq.packery('destroy').packery()
      }
      var itemElems = container.find('.eqLogic-widget, .scenario-widget').draggable()
      container.packery('bindUIDraggableEvents', itemElems)

      $(itemElems).each( function(i, itemElem ) {
        $(itemElem).attr('data-order', i + 1 )
      })
      container.on('dragItemPositioned', function() {
          jeedomUI.orderItems(container)
      })
      itemElems.draggable('disable')
    }
  })
}

$('#bt_editDashboardWidgetOrder').on('click',function() {
  if ($(this).attr('data-mode') == 1) {
    $('.tooltipstered').tooltipster('enable')
    $.hideAlert()
    $(this).attr('data-mode',0)
    editWidgetMode(0)
    $(this).css('color','black')
    $('.bt_editDashboardWidgetAutoResize').hide()
    $('.counterReorderJeedom').remove()
    $('.div_displayEquipement').packery()
  } else {
    $('.eqLogic-widget .tooltipstered,.scenario-widget .tooltipstered').tooltipster('disable')
    $(this).attr('data-mode',1)
    $('.bt_editDashboardWidgetAutoResize').show()
    $('.bt_editDashboardWidgetAutoResize').off('click').on('click', function() {
      var doesMin = false
      if (event.ctrlKey) doesMin = true
      var id_object = $(this).attr('id').replace('edit_object_','')
      var objectContainer = $('#div_ob'+id_object+'.div_displayEquipement')
      var arHeights = []
      objectContainer.find('.eqLogic-widget,.scenario-widget').each(function(index, element) {
        var h = $(this).height()
        arHeights.push(h)
      })
      if (doesMin) {
        var maxHeight = Math.min(...arHeights)
      } else {
        var maxHeight = Math.max(...arHeights)
      }
      objectContainer.find('.eqLogic-widget,.scenario-widget').each(function(index, element) {
        $(this).height(maxHeight)
      })
      objectContainer.packery()
    })
    editWidgetMode(1)
  }
})

$('.li_object').on('click',function() {
  $('.div_object').removeClass('hideByObjectSel')
  var object_id = $(this).find('a').attr('data-object_id')
  if ($('.div_object[data-object_id='+object_id+']').html() != undefined) {
    jeedom.object.getImgPath({
      id : object_id,
      success : function(_path){
        setBackgroundImg(_path)
      }
    })
    $('.li_object').removeClass('active')
    $(this).addClass('active')
    displayChildObject(object_id,false)
    addOrUpdateUrl('object_id',object_id)
  } else {
    loadPage($(this).find('a').attr('data-href'))
  }
})

function displayChildObject(_object_id, _recursion) {
  if (_recursion === false) {
    $('.div_object').addClass('hideByObjectSel').hide()
  }
  $('.div_object[data-object_id='+_object_id+']').show({effect : 'drop',queue : false})
  $('.div_object[data-father_id='+_object_id+']').each(function() {
    $(this).show({effect : 'drop',queue : false}).find('.div_displayEquipement').packery()
    displayChildObject($(this).attr('data-object_id'),true)
  })
}
