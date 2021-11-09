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

var jeedomUI = {}
jeedomUI.draggingId = false
jeedomUI.orders = {}
jeedomUI.isEditing = false
/*
@dashboard
@view
draggable call to reorder/insert drag
*/
jeedomUI.orderItems = function(_container, _orderAttr='data-order') {
  var itemElems = _container.packery('getItemElements')
  var _draggingOrder = jeedomUI.orders[jeedomUI.draggingId]
  var _newOrders = {}
  $(itemElems).each( function(i, itemElem ) {
    _newOrders[$(this).attr('data-editId')] = i + 1
  })
  var _draggingNewOrder = _newOrders[jeedomUI.draggingId]
  //----->moved jeedomUI.draggingId from _draggingOrder to _draggingNewOrder

  //rearrange that better:
  var _finalOrder = {}
  for (var [id, order] of Object.entries(_newOrders)) {
    if (order <= _draggingNewOrder) _finalOrder[id] = order
    if (order > _draggingNewOrder) _finalOrder[id] = jeedomUI.orders[id] + 1
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
    $(itemElem).attr(_orderAttr, i + 1)
    var value = i + 1
    if (jeedomUI.isEditing) {
      if ($(itemElem).find(".counterReorderJeedom").length) {
        $(itemElem).find(".counterReorderJeedom").text(value)
      } else {
        $(itemElem).prepend('<span class="counterReorderJeedom pull-left">'+value+'</span>')
      }
    }
  })
}

/*
@dashboard
@view
save eqlogics/scenarios size/order
*/
jeedomUI.saveWidgetDisplay = function(_params) {
  if (init(_params) == '') {
    _params = {}
  }
  var cmds = []
  var eqLogics = []
  var scenarios = []
  $('div.eqLogic-widget:not(div.eqLogic_layout_table)').each(function() {
    var eqLogic = $(this)
    var order = 1
    eqLogic.find('.cmd').each(function() {
      var cmd = {};
      cmd.id = $(this).attr('data-cmd_id')
      cmd.order = order
      cmds.push(cmd)
      order++
    })
  })
  $('div.eqLogic-widget.eqLogic_layout_table').each(function() {
    var eqLogic = $(this)
    var order = 1
    eqLogic.find('.cmd').each(function() {
      var cmd = {}
      cmd.id = $(this).attr('data-cmd_id')
      cmd.line = $(this).closest('td').attr('data-line')
      cmd.column = $(this).closest('td').attr('data-column')
      cmd.order = order
      cmds.push(cmd)
      order++
    })
  })
  if (init(_params['dashboard']) == 1) {
    $('.div_displayEquipement').each(function() {
      var order = 1
      $(this).find('div.eqLogic-widget, div.scenario-widget').each(function() {
        if ($(this).hasClass('eqLogic-widget')) {
          var eqLogic = {id :$(this).attr('data-eqlogic_id')}
          eqLogic.display = {}
          eqLogic.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px'
          eqLogic.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px'
          eqLogic.order = ($(this).attr('data-order') != undefined) ? $(this).attr('data-order') : order
          eqLogics.push(eqLogic)
        } else if ($(this).hasClass('scenario-widget')) {
          var scenario = {id :$(this).attr('data-scenario_id')}
          scenario.display = {}
          scenario.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px'
          scenario.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px'
          scenario.order = ($(this).attr('data-order') != undefined) ? $(this).attr('data-order') : order
          scenarios.push(scenario)
        }
        order++
      })
    })
    jeedom.eqLogic.setOrder({
      eqLogics: eqLogics,
      error: function (error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success:function(data){
        jeedom.cmd.setOrder({
          cmds: cmds,
          error: function (error) {
            $.fn.showAlert({message: error.message, level: 'danger'})
          }
        });
      }
    })
    jeedom.scenario.setOrder({
      scenarios: scenarios,
      error: function (error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      }
    })
  } else if (init(_params['view']) == 1) {
    var components = []
    $('.eqLogicZone').each(function() {
      var order = 1
      $(this).find('.eqLogic-widget,.scenario-widget').each(function() {
        if ($(this).hasClass('eqLogic-widget')) {
          var eqLogic = {id :$(this).attr('data-eqlogic_id'),type:'eqLogic'}
          eqLogic.display = {}
          eqLogic.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px'
          eqLogic.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px'
          eqLogic.viewZone_id = $(this).closest('.eqLogicZone').attr('data-viewZone-id')
          eqLogic.viewOrder = ($(this).attr('data-viewOrder') != undefined) ? $(this).attr('data-viewOrder') : order
          components.push(eqLogic)
        } else if ($(this).hasClass('scenario-widget')) {
          var scenario = {id :$(this).attr('data-scenario_id'),type:'scenario'}
          scenario.display = {}
          scenario.display.width =  Math.floor($(this).outerWidth() / 2) * 2 + 'px'
          scenario.display.height = Math.floor($(this).outerHeight() / 2) * 2+ 'px'
          scenario.viewZone_id = $(this).closest('.eqLogicZone').attr('data-viewZone-id')
          scenario.viewOrder = ($(this).attr('data-viewOrder') != undefined) ? $(this).attr('data-viewOrder') : order
          components.push(scenario)
        }
        order++
      })
    })
    jeedom.view.setComponentOrder({
      components: components,
      error: function (error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success:function(data){
        jeedom.cmd.setOrder({
          cmds: cmds,
          error: function (error) {
            $.fn.showAlert({message: error.message, level: 'danger'})
          }
        })
      }
    })
  }
}

/*
@dashboard
@view
@overview
infos/actions tile signals
*/
jeedomUI.setEqSignals = function() {
  $('body').off('mouseenter').off('mouseleave')
  .on('mouseenter','div.eqLogic-widget .cmd-widget[data-type="action"][data-subtype!="select"]', function (event) {
    if(!jeedomUI.isEditing) $(this).closest('div.eqLogic-widget').addClass('eqSignalAction')
  })
  .on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"][data-subtype!="select"]', function (event) {
    if(!jeedomUI.isEditing) $(this).closest('div.eqLogic-widget').removeClass('eqSignalAction')
  })
  .on('mouseenter','div.eqLogic-widget .cmd-widget.history[data-type="info"]', function (event) {
    if(!jeedomUI.isEditing) $(this).closest('div.eqLogic-widget').addClass('eqSignalInfo')
  })
  .on('mouseleave','div.eqLogic-widget .cmd-widget.history[data-type="info"]', function (event) {
    if(!jeedomUI.isEditing) $(this).closest('div.eqLogic-widget').removeClass('eqSignalInfo')
  })
  .on('mouseenter','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd', function (event) {
    if(!jeedomUI.isEditing) $(this).closest('div.eqLogic-widget').removeClass('eqSignalAction').addClass('eqSignalInfo')
  })
  .on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd', function (event) {
    if(!jeedomUI.isEditing) $(this).closest('div.eqLogic-widget').removeClass('eqSignalInfo').addClass('eqSignalAction')
  })
}

/*
@dashboard
@view
Handle history modal openning on infos
*/
jeedomUI.setHistoryModalHandler = function() {
  $('#div_pageContainer').off('click', '.history, .timeCmd.history').on('click', '.history, .timeCmd.history', function (event) {
    if (jeedomUI.isEditing) return false
    event.stopImmediatePropagation()
    event.stopPropagation()
    if ((event.ctrlKey || event.metaKey) && $(this).closest('div.eqLogic.eqLogic-widget').html() != undefined) {
      var cmdIds = []
      $(this).closest('div.eqLogic.eqLogic-widget').find('.history[data-cmd_id]').each(function () {
        cmdIds.push($(this).data('cmd_id'))
      })
      cmdIds = [...new Set(cmdIds)]
      cmdIds = cmdIds.join('-')
    } else {
      var cmdIds = $(this).closest('.history[data-cmd_id]').data('cmd_id')
    }
    $('#md_modal2').dialog({title: "{{Historique}}"}).load('index.php?v=d&modal=cmd.history&id=' + cmdIds).dialog('open')
  })
}
