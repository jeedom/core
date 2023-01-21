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
    init: function() {
      window.jeeP = this
      jeedomUI.isEditing = false
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
            console.log(err)
          }

          try {
            document.querySelector('.div_displayView').empty().html(html.html)
          } catch (err) {
            console.log(err)
          }

          setTimeout(function() {
            jeedomUtils.initReportMode()
            jeedomUtils.positionEqLogic()

            //$('input', 'textarea', 'select').click(function() { $(this).focus() })

            $('.eqLogicZone').each(function() {
              var container = $(this).packery({isLayoutInstant: true})
              var itemElems = container.find('.eqLogic-widget, .scenario-widget').draggable()
              container.packery('bindUIDraggableEvents', itemElems)

              //set vieworder for editMode:
              $(itemElems).each(function(i, itemElem) {
                $(itemElem).attr('data-vieworder', i + 1)
              })
              container.on('dragItemPositioned', function() {
                jeedomUI.orderItems(container, 'data-vieworder')
              })

              itemElems.draggable('disable')
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

        $(divEquipements).find('.editingMode.allowResize').resizable('destroy')
        $(divEquipements).find('.editingMode').draggable('disable').removeClass('editingMode', '').removeAttr('data-editId')
        divEquipements.querySelectorAll('.cmd.editOptions').remove()

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

        //show orders:
        var value
        divEquipements.querySelectorAll('.jeedomAlreadyPosition.ui-draggable').forEach(_draggable => {
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

        //set draggables:
        $(divEquipements).find('.editingMode').draggable({
          disabled: false,
          distance: 10,
          start: function(event, ui) {
            jeeFrontEnd.modifyWithoutSave = true
            jeedomUI.draggingId = $(this).attr('data-editId')
            jeedomUI.orders = {}
            this.parentNode.querySelectorAll('.ui-draggable').forEach((_draggable, _idx) => {
              jeedomUI.orders[jeedomUI.draggingId] = parseInt(_draggable.getAttribute('data-vieworder'))
            })
          }
        })
        //set resizables:
        $('.eqLogicZone .eqLogic-widget.allowResize').resizable({
          grid: [2, 2],
          start: function(event, ui) {
            jeeFrontEnd.modifyWithoutSave = true
          },
          resize: function(event, ui) {
            jeedomUtils.positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
            ui.element.closest('.eqLogicZone').packery()
          },
          stop: function(event, ui) {
            jeedomUtils.positionEqLogic(ui.element.attr('data-eqlogic_id'), false)
            ui.element.closest('.eqLogicZone').packery()
          }
        })
        $('.eqLogicZone .scenario-widget.allowResize').resizable({
          grid: [2, 2],
          start: function(event, ui) {
            jeeFrontEnd.modifyWithoutSave = true
          },
          resize: function(event, ui) {
            jeedomUtils.positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
            ui.element.closest('.eqLogicZone').packery()
          },
          stop: function(event, ui) {
            jeedomUtils.positionEqLogic(ui.element.attr('data-scenario_id'), false, true)
            ui.element.closest('.eqLogicZone').packery()
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
      $('.eqLogicZone').each(function() {
        $(this).packery({isLayoutInstant: true})
      })
      _target.setAttribute('data-display', '0')
    } else {
      _target.closest('.row').querySelector('.div_displayViewList').seen()
      _target.closest('.row').querySelector('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8')
      $('.eqLogicZone').packery({isLayoutInstant: true})
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
