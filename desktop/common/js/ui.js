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

var _draggingId = false
var _orders = {}

var jeedomUI = {

    orderItems: function(_container, _orderAttr='data-order') {
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
        $(itemElem).attr(_orderAttr, i + 1)
        var value = i + 1
        if (isEditing) {
          if ($(itemElem).find(".counterReorderJeedom").length) {
            $(itemElem).find(".counterReorderJeedom").text(value)
          } else {
            $(itemElem).prepend('<span class="counterReorderJeedom pull-left" style="margin-top: 3px;margin-left: 3px;">'+value+'</span>')
          }
        }
      })
    },

}


