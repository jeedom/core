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

$('#div_pageContainer').on('click','.bt_gotoViewZone',function() {
  var top = $('.div_displayViewContainer').scrollTop()+ $('.lg_viewZone[data-zone_id='+$(this).attr('data-zone_id')+']').offset().top - 60
  $('.div_displayViewContainer').animate({ scrollTop: top}, 500)
})

var isEditing = false
var _draggingId = false
var _orders = {}
function orderItems(_container) {
  //exact same function dashboard and view!
  var itemElems = _container.packery('getItemElements')
  var _draggingOrder = _orders[_draggingId]
  var _newOrders = {}
  $(itemElems).each( function(i, itemElem ) {
    _newOrders[$(this).attr('data-editId')] = i + 1
  })
  var _draggingNewOrder = _newOrders[_draggingId]
  //----->moved _draggingId from _draggingOrder to _draggingNewOrder

  //rearrange that better:
  var _finalOrder = {}
  for (var [id, order] of Object.entries(_newOrders)) {
    if (order <= _draggingNewOrder) _finalOrder[id] = order
    if (order > _draggingNewOrder) _finalOrder[id] = _orders[id] + 1
  }

  //set dom positions:
  var arrKeys = Object.keys(_finalOrder)
  var arrLength = arrKeys.length
  var firstElId = arrKeys.find(key => _finalOrder[key] === 1)
  $('.ui-draggable[data-editId="'+firstElId+'"]').parent().prepend($('.ui-draggable[data-editId="'+firstElId+'"]'))

  for (var i = 2; i < arrLength + 1; i++) {
    var thisId = arrKeys.find(key => _finalOrder[key] === i)
    var prevId = arrKeys.find(key => _finalOrder[key] === i-1)
    $('.ui-draggable[data-editId="'+prevId+'"]').after($('.ui-draggable[data-editId="'+thisId+'"]'))
  }

  //reload from dom positions:
  _container.packery('reloadItems').packery()

  itemElems = _container.packery('getItemElements')
  $(itemElems).each(function(i, itemElem) {
    $(itemElem).attr('data-order', i + 1)
    var value = i + 1
    if (isEditing) {
      if ($(itemElem).find(".counterReorderJeedom").length) {
        $(itemElem).find(".counterReorderJeedom").text(value)
      } else {
        $(itemElem).prepend('<span class="counterReorderJeedom pull-left" style="margin-top: 3px;margin-left: 3px;">'+value+'</span>')
      }
    }
  })
}

function fullScreen(_mode) {
  if (_mode) {
    $('header').hide()
    $('footer').hide()
    $('#div_mainContainer').css('margin-top', '-50px')
    $('.backgroundforJeedom').css({'margin-top': '-50px', 'height': '100%'})
    $('#wrap').css('margin-bottom', '0px')
    $('.div_displayView').height($('html').height() - 5)
    $('.div_displayViewContainer').height($('html').height() - 5)
    $('.bt_hideFullScreen').hide()
  } else {
    $('header').show()
    $('footer').show()
    $('#div_mainContainer').css('margin-top', '0px')
    $('.backgroundforJeedom').css({'margin-top': '0px', 'height': 'calc(100% - 50px)'})
    $('#wrap').css('margin-bottom', '15px')
    $('.div_displayView').height($('body').height())
    $('.div_displayViewContainer').height($('body').height())
    $('.bt_hideFullScreen').show()
  }
}

$('#bt_editViewWidgetOrder').off('click').on('click',function() {
  if ($(this).attr('data-mode') == 1) {
    $.hideAlert()
    $(this).attr('data-mode',0)
    $('.counterReorderJeedom').remove()
    editWidgetMode(0)
  } else {
    $('#div_alert').showAlert({message: "{{Vous êtes en mode édition vous pouvez déplacer les widgets, les redimensionner et changer l'ordre des commandes dans les widgets}}", level: 'info'})
    $(this).attr('data-mode',1)
    editWidgetMode(1)
  }
})

if (view_id != '') {
  jeedom.view.toHtml({
    id: view_id,
    version: 'dashboard',
    useCache: true,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function (html) {
      if (isset(html.raw) && isset(html.raw.img) && html.raw.img != '') {
        setBackgroundImg(html.raw.img)
      } else {
        setBackgroundImg('')
      }
      try {
        var summary = ''
        for (var i in html.raw.viewZone) {
          summary += '<li style="padding:0px 0px"><a style="padding:2px 20px" class="cursor bt_gotoViewZone" data-zone_id="'+html.raw.viewZone[i].id+'">'+html.raw.viewZone[i].name+'</a></li>'
        }
        $('#ul_viewSummary').empty().append(summary)
      } catch(err) {
        console.log(err)
      }

      try {
        $('.div_displayView').last().empty().html(html.html)
      } catch(err) {
        console.log(err)
      }
      setTimeout(function() {
        initReportMode()
        positionEqLogic()
        $('.eqLogicZone').disableSelection()
        $('input', 'textarea', 'select').click(function() { $(this).focus() })

        $('.eqLogicZone').each(function() {
          var container = $(this).packery()
          var itemElems = container.find('.eqLogic-widget, .scenario-widget').draggable()
          container.packery('bindUIDraggableEvents', itemElems)

          $(itemElems).each( function(i, itemElem ) {
            $(itemElem).attr('data-order', i + 1 )
          })
          container.on('dragItemPositioned', function() {
            orderItems(container)
          })
          itemElems.draggable('disable')
        })

        if (isset(html.raw) && isset(html.raw.configuration) && isset(html.raw.configuration.displayObjectName) && html.raw.configuration.displayObjectName == 1) {
          $('.eqLogic-widget, .scenario-widget').addClass('displayObjectName')
        }
        if (getUrlVars('fullscreen') == 1) {
          fullScreen(true)
        }
      }, 10)
    }
  })
}

$('#div_pageContainer').off('click','.history[data-cmd_id]').on('click','.history[data-cmd_id]', function(event) {
  event.stopPropagation()
  var cmdIds = []
  $(this).closest('.eqLogic.eqLogic-widget').find('.history[data-cmd_id]').each(function () {
    cmdIds.push($(this).data('cmd_id'))
  })
  cmdIds = cmdIds.join('-')
  var cmdShow = $(this).data('cmd_id')
  $('#md_modal2').dialog({title: "Historique"}).load('index.php?v=d&modal=cmd.history&id=' + cmdIds + '&showId=' + cmdShow).dialog('open')
})

$('.bt_displayView').on('click', function () {
  if ($(this).attr('data-display') == 1) {
    $(this).closest('.row').find('.div_displayViewList').hide()
    $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12')
    $('.eqLogicZone').each(function () {
      $(this).packery()
    });
    $(this).attr('data-display', 0)
  } else {
    $(this).closest('.row').find('.div_displayViewList').show();
    $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8')
    $('.eqLogicZone').packery()
    $(this).attr('data-display', 1)
  }
})

$('#div_pageContainer').delegate('.editOptions', 'click', function () {
  var eqId = $(this).closest('.eqLogic-widget').attr('data-eqlogic_id')
  $('#md_modal').dialog({title: "{{Configuration}}"}).load('index.php?v=d&modal=eqLogic.configure&eqLogic_id='+eqId).dialog('open')
})

function editWidgetMode(_mode, _save) {
  if (!isset(_mode)) {
    if ($('#bt_editViewWidgetOrder').attr('data-mode') != undefined && $('#bt_editViewWidgetOrder').attr('data-mode') == 1) {
      editWidgetMode(0,false)
      editWidgetMode(1,false)
    }
    return
  }
  var divEquipements = $('.div_displayView')
  if (_mode == 0 || _mode == '0') {
    isEditing = false
    jeedom.cmd.disableExecute = false

    divEquipements.find('.editingMode.allowResize').resizable('destroy')
    divEquipements.find('.editingMode').draggable('disable').removeClass('editingMode','').removeAttr('data-editId')
    divEquipements.find('.cmd.editOptions').remove()

    if (!isset(_save) || _save) {
      saveWidgetDisplay({view : 1})
    }
  } else {
    isEditing = true
    jeedom.cmd.disableExecute = true
    $('.eqLogic-widget, .scenario-widget').addClass('editingMode')

    //show orders:
    $('.ui-draggable').each(function() {
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
      start: function(event, ui) {
        _draggingId = $(this).attr('data-editId')
        _orders = {}
        $(this).parent().find('.ui-draggable').each( function(i, itemElem ) {
          _orders[_draggingId] = parseInt($(this).attr('data-order'))
        })
      }
    })

    $('.eqLogicZone .eqLogic-widget.allowResize').resizable({
      grid: [ 2, 2 ],
      resize: function(event, ui) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.eqLogicZone').packery()
      },
      stop: function(event, ui) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
        ui.element.closest('.eqLogicZone').packery()
      }
    })
    $('.eqLogicZone .scenario-widget.allowResize').resizable({
      grid: [ 2, 2 ],
      resize: function(event, ui) {
        positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.eqLogicZone').packery()
      },
      stop: function(event, ui) {
        positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
        ui.element.closest('.eqLogicZone').packery()
      }
    })
  }
  editWidgetCmdMode(_mode)
}

