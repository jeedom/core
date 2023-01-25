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

if (!jeeFrontEnd.view) {
  jeeFrontEnd.view = {
    draggables: [],
    init: function() {
      window.jeeP = this
      jeedomUI.isEditing = false
      this.draggables = []
      jeedomUI.setEqSignals()
      jeedomUI.setHistoryModalHandler()
      if (jeephp2js.view_id != '') {
        jeeFrontEnd.view.printView(jeephp2js.view_id)
      }
    },
    printView: function(_id) {
      jeedom.view.toHtml({
        id: _id,
        version: 'dashboard',
        useCache: true,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(html) {
          if (isset(html.raw) && isset(html.raw.img) && html.raw.img != '') {
            jeedomUtils.setBackgroundImage(html.raw.img)
          } else {
            jeedomUtils.setBackgroundImage('')
          }

          try {
            var summary = ''
            for (var i in html.raw.viewZone) {
              summary += '<li style="padding:0px 0px"><a style="padding:2px 20px" class="cursor bt_gotoViewZone" data-zone_id="' + html.raw.viewZone[i].id + '">' + html.raw.viewZone[i].name + '</a></li>'
            }
            document.getElementById('ul_viewSummary').empty().insertAdjacentHTML('beforeend', summary)
          } catch (err) {
            console.warn(err)
          }

          try {
            document.querySelector('.div_displayView').empty().html(html.html)
          } catch (err) {
            console.warn(err)
          }

          setTimeout(function() {
            jeedomUtils.initReportMode()
            jeedomUtils.positionEqLogic()

            document.querySelectorAll('div.eqLogicZone').forEach(_zone => {
              var pckry = new Packery(_zone, {isLayoutInstant: true})
              pckry.getItemElements().forEach(function(itemElem, idx) {
                itemElem.setAttribute('data-vieworder', idx + 1)
              })
            })

            if (isset(html.raw) && isset(html.raw.configuration) && isset(html.raw.configuration.displayObjectName) && html.raw.configuration.displayObjectName == 1) {
              document.querySelectorAll('.eqLogic-widget, .scenario-widget').addClass('displayObjectName')
            }
            if (getUrlVars('fullscreen') == 1) {
              jeeP.fullScreen(true)
            }
          }, 10)

          //draw graphs:
          document.querySelectorAll('.chartToDraw').forEach(_chart => {
            _chart.querySelectorAll('.viewZoneData').forEach(_zone => {
              var cmdId = _zone.getAttribute('data-cmdid')
              var el = _zone.getAttribute('data-el')
              var options = json_decode(_zone.getAttribute('data-option').replace(/'/g, '"'))
              var height = _zone.getAttribute('data-height')
              jeedom.history.drawChart({
                cmd_id: cmdId,
                el: el,
                height: height != '' ? height : null,
                dateRange: _zone.getAttribute('data-daterange'),
                option: options,
                success: function(data) {
                  document.querySelectorAll('.chartToDraw > .viewZoneData[data-cmdid="' + cmdId + '"]').remove()
                }
              })
            })
          })
        }
      })
    },
    fullScreen: function(_mode) {
      _mode = getBool(_mode)
      if (_mode) {
        document.body.addClass('fullscreen')
        document.querySelectorAll('.bt_hideFullScreen').unseen()
      } else {
        document.body.removeClass('fullscreen')
        document.querySelectorAll('.bt_hideFullScreen').seen()
      }
    },
    editWidgetMode: function(_mode, _save) {
      if (!isset(_mode)) {
        if (document.getElementById('bt_editViewWidgetOrder').getAttribute('data-mode') == '1') {
          this.editWidgetMode(0, false)
          this.editWidgetMode(1, false)
        }
        return
      }
      var divEquipements = document.querySelector('div.div_displayView')
      if (_mode == 0 || _mode == '0') { //Exit edit mode:
        jeeFrontEnd.modifyWithoutSave = false
        jeedomUI.isEditing = false
        jeedom.cmd.disableExecute = false

        jeeFrontEnd.view.draggables.forEach(draggie => {
          draggie.disable()
        })

        document.querySelectorAll('.editingMode').forEach(_edit => {
          _edit.removeClass('editingMode').removeAttribute('data-editId')
          if (_edit._jeeResize) _edit._jeeResize.destroy()
        })
        document.querySelectorAll('.cmd.editOptions').remove()

        if (!isset(_save) || _save) {
          document.getElementById('md_dashEdit')?.remove()
          jeedomUI.saveWidgetDisplay({
            view: 1
          })
        }
      } else { //Enter edit mode!
        jeedomUI.isEditing = true
        jeedom.cmd.disableExecute = true
        document.querySelectorAll('.eqLogic-widget, .scenario-widget').addClass('editingMode')

        //set draggables:
        if (jeeFrontEnd.view.draggables.length == 0) {
          //No draggies set yet:
          document.querySelectorAll('div.eqLogicZone').forEach(_divObject => {
            var pckry = Packery.data(_divObject)
            pckry.getItemElements().forEach(function(itemElem, idx) {
              itemElem.setAttribute('data-vieworder', idx + 1)
              var draggie = new Draggabilly(itemElem)
              jeeFrontEnd.view.draggables.push(draggie)
              pckry.bindDraggabillyEvents(draggie)
              draggie.on('dragEnd', function(event, draggedItem) {
                jeeFrontEnd.modifyWithoutSave = true
                jeedomUI.draggingId = draggedItem.target.closest('.editingMode').getAttribute('data-editId')
                jeedomUI.orderItems(pckry, 'data-vieworder')
              })
            })
          })
        } else {
          jeeFrontEnd.view.draggables.forEach(draggie => {
            draggie.enable()
          })
        }

        //show orders:
        var value
        divEquipements.querySelectorAll('.jeedomAlreadyPosition').forEach(_draggable => {
          value = _draggable.getAttribute('data-vieworder')
          if (_draggable.querySelector(".counterReorderJeedom") != null) {
            _draggable.querySelector(".counterReorderJeedom").textContent = value
          } else {
            _draggable.insertAdjacentHTML('afterbegin', '<span class="counterReorderJeedom pull-left">' + value + '</span>')
          }
        })

        //set unique id whatever we have:
        divEquipements.querySelectorAll('.eqLogic-widget, .scenario-widget').forEach((_div, _idx) => {
          _div.addClass('editingMode')
          _div.setAttribute('data-editId', _idx)
          _div.insertAdjacentHTML('beforeend', '<span class="cmd editOptions cursor"></span>')
        })

        //set resizables:
        new jeeResize('div.eqLogic-widget, div.scenario-widget', {
          handles: ['right', 'bottom-right', 'bottom'],
          start: function(event, element) {
            jeeFrontEnd.modifyWithoutSave = true
          },
          resize: function(event, element) {
            if (element.hasAttribute('data-eqlogic_id')) jeedomUtils.positionEqLogic(element.getAttribute('data-eqlogic_id'), false, false)
            if (element.hasAttribute('data-scenario_id')) jeedomUtils.positionEqLogic(element.getAttribute('data-scenario_id'), false, true)
            Packery.data(element.closest('.eqLogicZone')).layout()
          },
          stop: function(event, element) {
            jeedomUtils.positionEqLogic(element.getAttribute('data-eqlogic_id'), false)
            Packery.data(element.closest('.eqLogicZone')).layout()
          }
        })
      }
    },
  }
}

jeeFrontEnd.view.init()

//Register events on top of page container:
window.registerEvent("resize", function view(event) {
  if (event.isTrigger) return
  jeedomUtils.positionEqLogic()
})

/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_editViewWidgetOrder')) {
    if (_target.getAttribute('data-mode') == '1') {
      document.getElementById('md_dashEdit')?.remove()
      jeedomUtils.hideAlert()
      _target.setAttribute('data-mode', 0)
      document.querySelectorAll('.counterReorderJeedom').remove()
      jeeP.editWidgetMode(0)
    } else {
      _target.setAttribute('data-mode', 1)
      jeeP.editWidgetMode(1)
    }
    return
  }

  if (_target = event.target.closest('#bt_displayView')) {
    if (_target.getAttribute('data-display') == '1') {
      _target.closest('.row').querySelector('.div_displayViewList').unseen()
      _target.closest('.row').querySelector('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12')
      document.querySelectorAll('div.eqLogicZone').forEach(_divObject => {
        Packery.data(_divObject).layout()
      })
      /*
      $('.eqLogicZone').each(function() {
        $(this).packery({isLayoutInstant: true})
      })
      */
      _target.setAttribute('data-display', '0')
    } else {
      _target.closest('.row').querySelector('.div_displayViewList').seen()
      _target.closest('.row').querySelector('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8')

      document.querySelectorAll('div.eqLogicZone').forEach(_divObject => {
        Packery.data(_divObject).layout()
      })
      //$('.eqLogicZone').packery({isLayoutInstant: true})
      _target.setAttribute('data-display', '1')
    }
    return
  }

  if (_target = event.target.closest('.bt_gotoViewZone')) {
    document.querySelector('.lg_viewZone[data-zone_id="' + _target.getAttribute('data-zone_id') + '"]').scrollIntoView()
    return
  }

  if (_target = event.target.closest('.editOptions')) {
    var eqId = _target.closest('div.eqLogic-widget').getAttribute('data-eqlogic_id')
    jeeDialog.dialog({
      id: 'md_dashEdit',
      width: '600px',
      height: '650px',
      top: '15vh',
      buttons: {
        confirm: {
          label: '<i class="fa fa-check"></i> {{Appliquer}}',
          className: 'success',
          callback: {
            click: function(event) {
              jeeFrontEnd.md_eqlogicDashEdit.eqlogicSave()
            }
          }
        },
        cancel: {
          className: 'hidden'
        }
      },
      retainPosition: true,
      zIndex: 1021,
      backdrop: false,
      contentUrl: 'index.php?v=d&modal=eqLogic.dashboard.edit&eqLogic_id=' + eqId
    })
    return
  }
})
