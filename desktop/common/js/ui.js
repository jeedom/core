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
if (!jeedomUI) {
  var jeedomUI = {
    __description: 'Common function object for Dashboard, View, Synthesis',
    draggingId: false,
    orders: {},
    isEditing: false,
    /*
    @dashboard
    @view
    draggabilly call to reorder/insert drag
    */
    orderItems: function(_pckryInstance, _orderAttr='data-order') {
      var _draggingOrder = parseInt(jeedomUI.orders[jeedomUI.draggingId])
      var _newOrders = {}
      _pckryInstance.items.forEach(function(itemElem, i) {
        _newOrders[itemElem.element.getAttribute('data-editid')] = i + 1
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
      var firstElId = arrKeys.find(key => _finalOrder[key] === 1)
      var firstEl = _pckryInstance.element.querySelector('.editingMode[data-editid="' + firstElId + '"]')
      if (firstEl != null) {
        firstEl.parentNode.insertBefore(firstEl, firstEl.parentNode.firstChild)
      }

      var thisId, prevId, thisEl, prevEl
      for (var i = 2; i < arrKeys.length + 1; i++) {
        thisId = arrKeys.find(key => _finalOrder[key] === i)
        thisEl = document.querySelector('.editingMode[data-editid="' + thisId + '"]')
        prevId = arrKeys.find(key => _finalOrder[key] === i-1)
        prevEl =  document.querySelector('.editingMode[data-editid="' + prevId + '"]')
        if (thisEl && prevEl) prevEl.parentNode.insertBefore(thisEl, prevEl.nextSibling)
      }

      _pckryInstance.reloadItems()
      var itemElems = _pckryInstance.getItemElements()

      itemElems.forEach(function(itemElem, i) {
        if (!itemElem.hasClass('packery-drop-placeholder')) {
          itemElem.setAttribute(_orderAttr, i + 1)
          itemElem.style.transform = null
          if (jeedomUI.isEditing) {
            try { //In case template isn't contained in a single div !
            itemElem.querySelector('.counterReorderJeedom').textContent = (i + 1).toString()
            } catch(error) { }
          } else {
            itemElem.insertAdjacentHTML('afterbegin', '<span class="counterReorderJeedom pull-left">' + (i + 1).toString() + '</span>')
          }
        }
      })
      _pckryInstance.layout()

      document.querySelectorAll('.packery-drop-placeholder').remove()
      setTimeout(() => {
        _pckryInstance.layout()
      }, 500)
    },

    /*
    @dashboard
    @view
    save eqlogics/scenarios size/order
    */
    saveWidgetDisplay: function(_params) {
      if (init(_params) == '') {
        _params = {}
      }
      /*var cmds = []
      //Get all commands for display default eqLogic:
      document.querySelectorAll('div.eqLogic-widget:not(div.eqLogic_layout_table)').forEach(function(el_eqLogic) {
        var order = 1
        el_eqLogic.querySelectorAll('.cmd').forEach(function(el_cmd) {
          var cmd = {}
          cmd.id = el_cmd.getAttribute('data-cmd_id') || undefined
          cmd.order = order
          cmds.push(cmd)
          order++
        })
      })
      //Get all commands for display table eqLogic:
      document.querySelectorAll('div.eqLogic-widget.eqLogic_layout_table').forEach(function(el_eqLogic) {
        var order = 1
        el_eqLogic.querySelectorAll('.cmd').forEach(function(el_cmd) {
          var cmd = {}
          cmd.id = el_cmd.getAttribute('data-cmd_id') || undefined
          cmd.line = el_cmd.closest('td')?.getAttribute('data-line') || undefined
          cmd.column = el_cmd.closest('td')?.getAttribute('data-column') || undefined
          cmd.order = order
          cmds.push(cmd)
          order++
        })
      })*/

      //Get size/order tile:
      var eqLogics = []
      var scenarios = []
      if (init(_params['dashboard']) == 1) {
        document.querySelectorAll('.div_displayEquipement').forEach(function(el_display) {
          var order = 1
          el_display.querySelectorAll('div.eqLogic-widget, div.scenario-widget').forEach(function(el_tile) {
            if (el_tile.offsetWidth > 0 && el_tile.offsetHeight > 0) {
              if (el_tile.hasClass('eqLogic-widget')) {
                var eqLogic = {id: el_tile.getAttribute('data-eqlogic_id')}
                eqLogic.display = {}
                eqLogic.display.width = Math.floor(el_tile.offsetWidth / 2) * 2 + 'px'
                eqLogic.display.height = Math.floor(el_tile.offsetHeight / 2) * 2+ 'px'
                eqLogic.order = el_tile.getAttribute('data-order') || order
                eqLogics.push(eqLogic)
              } else if (el_tile.hasClass('scenario-widget')) {
                var scenario = {id: el_tile.getAttribute('data-scenario_id')}
                scenario.display = {}
                scenario.display.width = Math.floor(el_tile.offsetWidth / 2) * 2 + 'px'
                scenario.display.height = Math.floor(el_tile.offsetHeight / 2) * 2+ 'px'
                scenario.order = el_tile.getAttribute('data-order') || order
                scenarios.push(scenario)
              }
              order++
            }
          })
        })
        jeedom.eqLogic.setOrder({
          eqLogics: eqLogics,
          error: function (error) {
            jeedomUtils.showAlert({message: error.message, level: 'danger'})
          },
        /*  success:function(data){
            jeedom.cmd.setOrder({
              cmds: cmds,
              error: function (error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              }
            });
          }*/
        })
        jeedom.scenario.setOrder({
          scenarios: scenarios,
          error: function (error) {
            jeedomUtils.showAlert({message: error.message, level: 'danger'})
          }
        })
      }

      if (init(_params['view']) == 1) {
        var components = []
        document.querySelectorAll('.eqLogicZone').forEach(function(el_zone) {
          var order = 1
          el_zone.querySelectorAll('.eqLogic-widget,.scenario-widget').forEach(function(el_tile) {
            if (el_tile.hasClass('eqLogic-widget')) {
              var eqLogic = {id: el_tile.getAttribute('data-eqlogic_id'), type:'eqLogic'}
              eqLogic.display = {}
              eqLogic.display.width = Math.floor(el_tile.offsetWidth / 2) * 2 + 'px'
              eqLogic.display.height = Math.floor(el_tile.offsetHeight / 2) * 2+ 'px'
              eqLogic.viewZone_id = el_tile.closest('.eqLogicZone').getAttribute('data-viewZone-id') || undefined
              eqLogic.viewOrder = el_tile.getAttribute('data-viewOrder') || order
              components.push(eqLogic)
            } else if (el_tile.hasClass('scenario-widget')) {
              var scenario = {id: el_tile.getAttribute('data-scenario_id'), type:'scenario'}
              scenario.display = {}
              scenario.display.width = Math.floor(el_tile.offsetWidth / 2) * 2 + 'px'
              scenario.display.height = Math.floor(el_tile.offsetHeight / 2) * 2+ 'px'
              scenario.viewZone_id = el_tile.closest('.eqLogicZone').getAttribute('data-viewZone-id') || undefined
              scenario.viewOrder = el_tile.getAttribute('data-viewOrder') || order
              components.push(scenario)
            }
            order++
          })
        })
        jeedom.view.setComponentOrder({
          components: components,
          error: function (error) {
            jeedomUtils.showAlert({message: error.message, level: 'danger'})
          },
        /*  success:function(data){
            jeedom.cmd.setOrder({
              cmds: cmds,
              error: function (error) {
                jeedomUtils.showAlert({message: error.message, level: 'danger'})
              }
            })
          }*/
        })
      }
    },

    /*
    @dashboard
    @view
    @overview
    infos/actions tile signals
    */
    setEqSignals: function() {
      document.body.unRegisterEvent('mouseenter', 'eqLogicSignalMouseenter')
      document.body.registerEvent('mouseenter', function eqLogicSignalMouseenter(event) {
        if (jeedomUI.isEditing) return false
        if (event.target.matches('.cmd-widget[data-type="action"]:not([data-subtype="select"])')) {
          event.target.closest('div.eqLogic-widget')?.addClass('eqSignalAction')
          return
        }
        if (event.target.matches('.cmd-widget.history[data-type="info"]')) {
          event.target.closest('div.eqLogic-widget')?.addClass('eqSignalInfo')
          return
        }
        if (event.target.matches('.cmd-widget[data-type="action"] .timeCmd')) {
          event.target.closest('div.eqLogic-widget')?.removeClass('eqSignalAction').addClass('eqSignalInfo')
          return
        }
      }, {capture: true})
      document.body.unRegisterEvent('mouseleave', 'eqLogicSignalMouseleave')
      document.body.registerEvent('mouseleave', function eqLogicSignalMouseleave(event) {
        if (jeedomUI.isEditing) return false
        if (event.target.matches('.cmd-widget[data-type="action"]:not([data-subtype="select"])')) {
          event.target.closest('div.eqLogic-widget')?.removeClass('eqSignalAction')
          return
        }
        if (event.target.matches('.cmd-widget.history[data-type="info"]')) {
          event.target.closest('div.eqLogic-widget')?.removeClass('eqSignalInfo')
          return
        }
        if (event.target.matches('.cmd-widget[data-type="action"] .timeCmd')) {
          event.target.closest('div.eqLogic-widget')?.removeClass('eqSignalInfo').addClass('eqSignalAction')
          return
        }
      }, {capture: true})
    },

    /*
    @dashboard
    @view
    Handle history modal openning on infos
    */
    setHistoryModalHandler: function() {
      document.getElementById('div_pageContainer').unRegisterEvent('click', 'historyModalHandler')
      document.getElementById('div_pageContainer').registerEvent('click', function historyModalHandler(event) {
        if (jeedomUI.isEditing) return false
          if (document.body.getAttribute('data-page') == 'plan' && jeeFrontEnd.planEditOption.state) return false
        if (event.target.closest('.history[data-cmd_id]') == null) return false
        event.stopImmediatePropagation()
        event.stopPropagation()

        if ((event.ctrlKey || event.metaKey) && event.target.closest('div.eqLogic.eqLogic-widget') != null) {
          var cmdIds = []
          event.target.closest('div.eqLogic.eqLogic-widget').querySelectorAll('.history[data-cmd_id]')?.forEach(function (element) {
            cmdIds.push(element.dataset.cmd_id)
          })
          cmdIds = [...new Set(cmdIds)]
          cmdIds = cmdIds.join('-')
        } else {
          var cmdIds = event.target.closest('.history[data-cmd_id]').dataset.cmd_id
        }
        jeeDialog.dialog({
          id: 'md_cmdHistory',
          title: '{{Historique}}',
          contentUrl: 'index.php?v=d&modal=cmd.history&id=' + cmdIds
        })
      }, {capture: false})
    },
  }
}
