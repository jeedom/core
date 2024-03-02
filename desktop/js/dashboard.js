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

if (!jeeFrontEnd.dashboard) {
  jeeFrontEnd.dashboard = {
    btOverviewTimer: null,
    draggables: [],
    init: function() {
      window.jeeP = this
      this.url_category = getUrlVars('category')
      if (!this.url_category) this.url_category = 'all'
      this.url_tag = getUrlVars('tag')
      if (!this.url_tag) this.url_tag = 'all'
      this.url_summary = getUrlVars('summary')

      this.draggables = []

      if (this.url_summary != '') {
        document.querySelectorAll('#bt_displayObject, #bt_editDashboardWidgetOrder').forEach(function(element) {
          element.parentNode.remove()
        })
        document.querySelectorAll('div.div_object').forEach(function(div_object) {
          var objId = div_object.getAttribute('data-object_id')
          jeeFrontEnd.dashboard.getObjectHtmlFromSummary(objId)
        })
      } else {
        document.querySelectorAll('div.div_object').forEach(function(div_object) {
          var objId = div_object.getAttribute('data-object_id')
          jeeFrontEnd.dashboard.getObjectHtml(objId)
        })
      }
      jeedom.getInfoApplication({
        version: 'dashboard',
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeedom.appMobile.postToApp('initSummary', data.summary)
        }
      })
    },
    postInit: function() {
      jeedomUI.isEditing = false
      jeedomUI.setEqSignals()
      jeedomUI.setHistoryModalHandler()
      document.querySelectorAll('#dashOverviewPrevSummaries > .objectSummaryContainer').unseen().addClass('shadowed')
    },
    resetCategoryFilter: function() {
      document.querySelectorAll('#categoryfilter .catFilterKey').forEach(function(element) {
        element.checked = true
      })
      document.querySelectorAll('div.div_object, div.eqLogic-widget, div.scenario-widget').seen()
      document.querySelectorAll('#dashTopBar button.dropdown-toggle').removeClass('warning')
      document.querySelectorAll('div.div_displayEquipement').forEach(_div => { Packery.data(_div).layout() })
    },
    filterByCategory: function() {
      //get defined categories:
      var cats = []
      document.querySelectorAll('#categoryfilter .catFilterKey').forEach(function(element) {
        if (element.checked) cats.push(element.getAttribute('data-key'))
      })

      //check eqLogics cats:
      var eqCats, catFound
      document.querySelectorAll('div.eqLogic-widget').forEach(function(eqLogic) {
        catFound = false
        if (eqLogic.hasAttribute('data-translate-category')) {
          eqCats = eqLogic.getAttribute('data-translate-category').split(',')
          catFound = eqCats.some(r => cats.includes(r))
        } else if (eqLogic.hasAttribute('data-category')) {
          eqCats = eqLogic.getAttribute('data-category')
          if (cats.findIndex(item => eqCats.toLowerCase() === item.toLowerCase()) >= 0) catFound = true
        } else {
          eqCats = ''
        }
        if (catFound) {
          eqLogic.seen()
          eqLogic.closest('.div_object').seen()
        }
        else eqLogic.unseen()
      })

      if (cats.includes('scenario')) {
        document.querySelectorAll('div.scenario-widget').forEach(_sc => {
          _sc.seen()
          _sc.closest('.div_object').seen()
        })
      } else {
        document.querySelectorAll('div.scenario-widget').unseen()
      }

      document.querySelectorAll('.div_displayEquipement').forEach(function(element) {
        var visibles = [...element.querySelectorAll('div.eqLogic-widget, div.scenario-widget')].filter(el => el.isVisible())
        if (visibles.length == 0) {
          element.closest('.div_object').unseen()
        } else {
          element.closest('.div_object').seen()
        }
      })

      if (cats.length == document.querySelectorAll('#categoryfilter .catFilterKey').length) {
        document.querySelector('#dashTopBar button.dropdown-toggle').removeClass('warning')
      } else {
        document.querySelector('#dashTopBar button.dropdown-toggle').addClass('warning')
      }
      document.querySelectorAll('div.div_displayEquipement').forEach(_div => { Packery.data(_div).layout() })

    },
    editWidgetMode: function(_mode, _save) {
      if (document.getElementById('bt_editDashboardWidgetOrder') == null) return
      if (!isset(_mode)) {
        if (document.getElementById('bt_editDashboardWidgetOrder').getAttribute('data-mode') != undefined && document.getElementById('bt_editDashboardWidgetOrder').getAttribute('data-mode') == 1) {
          this.editWidgetMode(0, false)
          this.editWidgetMode(1, false)
        }
        return
      }

      if (_mode == 0) { //Exit edit mode:
        document.getElementById('div_displayObject').style.height = 'auto'
        document.querySelectorAll('.widget-name a.reportModeHidden, .scenario-widget .widget-name a').removeClass('disabled')
        jeedom.cmd.disableExecute = false
        jeedomUI.isEditing = false
        document.querySelectorAll('#dashTopBar .btn:not(#bt_editDashboardWidgetOrder)').removeClass('disabled')
        if (!isset(_save) || _save) {
          jeedomUI.saveWidgetDisplay({
            dashboard: 1
          })
        }

        jeeFrontEnd.dashboard.draggables.forEach(draggie => {
          draggie.disable()
        })

        document.querySelectorAll('.editingMode').forEach(_edit => {
          _edit.removeClass('editingMode').removeAttribute('data-editid')
          if (_edit._jeeResize) _edit._jeeResize.destroy()
        })
        document.querySelectorAll('.cmd.editOptions').remove()

        document.querySelectorAll('#div_displayObject .row').forEach(function(row) {
          row.style = null
        })
        document.getElementById('dashTopBar').removeClass('editing')

        document.getElementById('in_searchDashboard').removeClass('editing').value = ''
        document.getElementById('in_searchDashboard').readOnly = false
        if (!isset(_save) || _save) document.getElementById('md_dashEdit')?.remove()
      } else { //Enter edit mode!
        document.getElementById('div_displayObject').style.height = (document.getElementById('div_displayObject').offsetHeight*1.2)+'px'
        document.querySelectorAll('.widget-name a.reportModeHidden, .scenario-widget .widget-name a').addClass('disabled')
        jeedomUI.isEditing = true
        jeedom.cmd.disableExecute = true
        this.resetCategoryFilter()
        document.querySelectorAll('#dashTopBar .btn:not(#bt_editDashboardWidgetOrder)').addClass('disabled')

        //set resizables:
        new jeeResize('div.eqLogic-widget, div.scenario-widget', {
          handles: ['right', 'bottom-right', 'bottom'],
          allowHeightOversize: true,
          start: function(event, element) {
            jeeFrontEnd.modifyWithoutSave = true
          },
          resize: function(event, element) {
            if (element.hasAttribute('data-eqlogic_id')) jeedomUtils.positionEqLogic(element.getAttribute('data-eqlogic_id'), false, false)
            if (element.hasAttribute('data-scenario_id')) jeedomUtils.positionEqLogic(element.getAttribute('data-scenario_id'), false, true)
            Packery.data(element.closest('.div_displayEquipement')).layout()
          },
          stop: function(event, element) {
            jeedomUtils.positionEqLogic(element.getAttribute('data-eqlogic_id'), false)
            Packery.data(element.closest('.div_displayEquipement')).layout()
          }
        })

        //set draggables:
        if (jeeFrontEnd.dashboard.draggables.length == 0) {
          //No draggies set yet:
          document.querySelectorAll('div.div_displayEquipement').forEach(_divObject => {
            var pckry = Packery.data(_divObject)
            pckry.getItemElements().forEach(function(itemElem, idx) {
              itemElem.setAttribute('data-order', idx + 1)
              var draggie = new Draggabilly(itemElem)
              jeeFrontEnd.dashboard.draggables.push(draggie)
              pckry.bindDraggabillyEvents(draggie)

              draggie.on('dragStart', function(event, draggedItem) {
                jeedomUI.draggingId = draggedItem.target.closest('.editingMode').getAttribute('data-editid')
              })

              draggie.on('dragEnd', function(event, draggedItem) {
                jeeFrontEnd.modifyWithoutSave = true
                jeedomUI.orderItems(pckry)
              })
            })
          })
        } else {
          jeeFrontEnd.dashboard.draggables.forEach(draggie => {
            draggie.enable()
          })
        }

        //show orders:
        var value
        document.querySelectorAll('.jeedomAlreadyPosition').forEach(function(element) {
          value = element.getAttribute('data-order')
          if (element.querySelector('.counterReorderJeedom')) {
            element.querySelector('.counterReorderJeedom').textContent = value
          } else {
            element.insertAdjacentHTML('afterbegin', '<span class="counterReorderJeedom pull-left">' + value.toString() + '</span>')
          }
        })

        //set unique id whatever we have:
        document.querySelectorAll('div.eqLogic-widget, div.scenario-widget').forEach(function(element, index) {
          element.addClass('editingMode')
          element.setAttribute('data-editid', index)
          element.insertAdjacentHTML('beforeend', '<span class="cmd editOptions cursor"></span>')
        })

        document.getElementById('dashTopBar').addClass('editing')
        document.getElementById('in_searchDashboard').addClass('editing').value = "{{Vous êtes en mode édition. Vous pouvez déplacer les tuiles, les redimensionner,  et éditer les commandes (ordre, widget) avec le bouton à droite du titre. N'oubliez pas de quitter le mode édition pour sauvegarder}}"
        document.getElementById('in_searchDashboard').readOnly = true
        document.querySelectorAll('#div_displayObject .row').forEach(function(row) {
          row.style.marginTop = '40px'
        })
      }
    },
    getObjectHtmlFromSummary: function(_object_id) {
      if (_object_id == null) return
      let self = this
      self._object_id = _object_id
      self.summaryObjEqs = []
      self.summaryObjEqs[_object_id] = []
      jeedom.object.getEqLogicsFromSummary({
        id: _object_id,
        onlyEnable: '1',
        onlyVisible: '0',
        version: 'dashboard',
        summary: this.url_summary,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var dom_divDisplayEq = document.getElementById('div_ob' + _object_id)
          var nbEqs = data.length
          if (nbEqs == 0) {
            dom_divDisplayEq.closest('.div_object').parentNode.remove()
            return
          } else {
            dom_divDisplayEq.closest('.div_object').removeClass('hidden')
          }
          for (var i = 0; i < nbEqs; i++) {
            if (self.summaryObjEqs[self._object_id].includes(data[i].id)) {
              nbEqs--
              return
            }
            self.summaryObjEqs[self._object_id].push(data[i].id)

            jeedom.eqLogic.toHtml({
              id: data[i].id,
              version: 'dashboard',
              error: function(error) {
                jeedomUtils.showAlert({
                  message: error.message,
                  level: 'danger'
                })
              },
              success: function(html) {
                if (html.html != '') {
                  dom_divDisplayEq.html(html.html, true)
                }
                nbEqs--

                //is last ajax:
                if (nbEqs == 0) {
                  jeedomUtils.positionEqLogic()
                  domUtils.hideLoading()
                  new Packery(dom_divDisplayEq, {isLayoutInstant: true, transitionDuration: 0})

                  if (Array.from(dom_divDisplayEq.querySelectorAll('div.eqLogic-widget, div.scenario-widget')).filter(item => item.isVisible()).length == 0) {
                    dom_divDisplayEq.closest('.div_object').remove()
                    return
                  }
                  jeedomUtils.initTooltips()
                }
              }
            })
          }
        }
      })
    },
    getObjectHtml: function(_object_id) {
      let self = this
      jeedom.object.toHtml({
        id: _object_id,
        version: 'dashboard',
        category: 'all',
        summary: self.url_summary,
        tag: self.url_tag,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(html) {
          var dom_divDisplayEq = document.getElementById('div_ob' + _object_id)
          try {
            if (html == '') {
              dom_divDisplayEq.closest('.div_object').parentNode.remove()
              return
            }
            dom_divDisplayEq.html(html)
          } catch (err) {
            console.warn(err)
          }
          if (typeof jeeP == 'undefined') {
            return
          }
          if (self.url_summary != false) {
            if (Array.from(dom_divDisplayEq.querySelectorAll('div.eqLogic-widget, div.scenario-widget')).filter(item => item.isVisible()).length == 0) {
              dom_divDisplayEq.closest('.div_object').remove()
              domUtils.hideLoading()
              return
            }
          }

          jeedomUtils.positionEqLogic()
          new Packery(dom_divDisplayEq, {isLayoutInstant: true, transitionDuration: 0})

          //synch category filter:
          if (self.url_category != 'all') {
            let cat = self.url_category.charAt(0).toUpperCase() + self.url_category.slice(1)
            document.getElementById('dashTopBar button.dropdown-toggle').addClass('warning')
            document.querySelectorAll('#categoryfilter .catFilterKey').forEach(function(element) {
              element.checked = false
            })
            document.querySelector('#categoryfilter .catFilterKey[data-key="' + cat + '"]').checked = true
            this.filterByCategory()
          }

          domUtils.hideLoading()
          jeedomUtils.initTooltips()
        }
      })
    },
    displayChildObject: function(_object_id, _recursion) {
      if (_recursion === false) {
        document.querySelectorAll('.div_object').forEach(function(div_object, idx) {
          if (div_object.getAttribute('data-object_id') == _object_id) {
            div_object.parentNode.removeClass('hideByObjectSel').seen()
          } else {
            div_object.parentNode.addClass('hideByObjectSel').unseen()
          }
        })
      }

      document.querySelectorAll('.div_object[data-father_id="' + _object_id + '"]').forEach(_id => {
        _id.seen()
        document.querySelectorAll('div.div_displayEquipement').forEach(_divObject => {
          Packery.data(_divObject).layout()
        })
        jeeP.displayChildObject(_id.getAttribute('data-object_id'))
      })
    },
  }
}

jeeFrontEnd.dashboard.init()

if (typeof jeephp2js.rootObjectId != 'undefined') {
  jeedom.object.getImgPath({
    id: jeephp2js.rootObjectId,
    success: function(_path) {
      jeedomUtils.setBackgroundImage(_path)
    }
  })
  let lia = document.querySelector('#dashOverviewPrev a[data-object_id="' + jeephp2js.rootObjectId + '"]')
  if (lia) lia.parentNode.addClass('active')
}

jeeP.postInit()

//searching
document.getElementById('in_searchDashboard')?.addEventListener('keyup', function(event) {
  if (jeedomUI.isEditing) return
  var search = this.value
  document.querySelectorAll('.div_object:not(.hideByObjectSel)').seen()
  if (search == '') {
    document.querySelectorAll('div.eqLogic-widget, div.scenario-widget').seen()
    document.querySelectorAll('div.div_displayEquipement').forEach(_div => { Packery.data(_div).layout() })
    return
  }

  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  var match, text
  document.querySelectorAll('div.eqLogic-widget').forEach(function(element) {
    match = false
    text = jeedomUtils.normTextLower(element.querySelector('.widget-name > a')?.textContent)
    if (text.includes(search)) match = true

    if (element.getAttribute('data-tags') != undefined) {
      text = jeedomUtils.normTextLower(element.getAttribute('data-tags'))
      if (text.includes(search)) match = true
    }
    if (element.getAttribute('data-category') != undefined) {
      text = jeedomUtils.normTextLower(element.getAttribute('data-category'))
      if (text.includes(search)) match = true
    }
    if (element.getAttribute('data-eqType') != undefined) {
      text = jeedomUtils.normTextLower(element.getAttribute('data-eqType'))
      if (text.includes(search)) match = true
    }
    if (element.getAttribute('data-translate-category') != undefined) {
      text = jeedomUtils.normTextLower(element.getAttribute('data-translate-category'))
      if (text.includes(search)) match = true
    }

    if (not) match = !match

    if (match) {
      element.seen()
    } else {
      element.unseen()
    }
  })
  document.querySelectorAll('div.scenario-widget').forEach(function(element) {
    text = jeedomUtils.normTextLower(element.querySelector('.widget-name > a')?.textContent)
    if (text.includes(search)) {
      element.seen()
    } else {
      element.unseen()
    }
  })
  document.querySelectorAll('.div_displayEquipement').forEach(function(element) {
    var visibles = [...element.querySelectorAll('div.eqLogic-widget, div.scenario-widget')].filter(el => el.isVisible())
    if (visibles.length == 0) element.closest('.div_object').unseen()
  })
  document.querySelectorAll('div.div_displayEquipement').forEach(_div => { Packery.data(_div).layout() })
})
document.getElementById('bt_resetDashboardSearch')?.addEventListener('click', function(event) {
  if (jeedomUI.isEditing) return
  document.querySelectorAll('#categoryfilter li .catFilterKey').forEach(cat => { cat.checkd = true})
  document.querySelectorAll('#dashTopBar button.dropdown-toggle').removeClass('warning')
  document.getElementById('in_searchDashboard').jeeValue('').triggerEvent('keyup')
})

/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_editDashboardWidgetOrder')) {
    if (_target.getAttribute('data-mode') == 1) {
        _target.setAttribute('data-mode', 0)
        jeedomUtils.hideAlert()
        jeeFrontEnd.modifyWithoutSave = false
        jeedomUtils.enableTooltips()
        document.querySelectorAll('div.div_object .bt_editDashboardTilesAutoResizeUp, div.div_object .bt_editDashboardTilesAutoResizeDown').unseen()
        document.querySelectorAll('.counterReorderJeedom').remove()
        jeeP.editWidgetMode(0)
        document.querySelectorAll('div.div_displayEquipement').forEach(_div => { Packery.data(_div).layout() })
      } else {
        _target.setAttribute('data-mode', 1)
        jeedomUtils.disableTooltips()
        document.querySelectorAll('div.div_object .bt_editDashboardTilesAutoResizeUp, div.div_object .bt_editDashboardTilesAutoResizeDown').seen()
        jeeP.editWidgetMode(1)
      }
    return
  }

  if (_target = event.target.closest('.editOptions')) { //Edit mode tile icon
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

  if (_target = event.target.closest('.bt_editDashboardTilesAutoResizeUp')) { //Edit mode resize up button
    var id_object = _target.getAttribute('data-obecjtid')
    var objectContainer = document.querySelector('#div_ob' + id_object + '.div_displayEquipement')
    var arHeights = []
    objectContainer.querySelectorAll('div.eqLogic-widget, div.scenario-widget').forEach(function(element) {
      arHeights.push(element.offsetHeight)
    })
    var maxHeight = Math.max(...arHeights)
    objectContainer.querySelectorAll('div.eqLogic-widget, div.scenario-widget').forEach(function(element) {
      element.style.height = maxHeight + 'px'
    })
    Packery.data(objectContainer).layout()
    return
  }

  if (_target = event.target.closest('.bt_editDashboardTilesAutoResizeDown')) { //Edit mode resize down button
    var id_object = _target.getAttribute('data-obecjtid')
    var objectContainer = document.querySelector('#div_ob' + id_object + '.div_displayEquipement')
    var arHeights = []
    objectContainer.querySelectorAll('div.eqLogic-widget, div.scenario-widget').forEach(function(element) {
      arHeights.push(element.offsetHeight)
    })
    var minHeight = Math.min(...arHeights)
    objectContainer.querySelectorAll('div.eqLogic-widget, div.scenario-widget').forEach(function(element) {
      element.style.height = minHeight + 'px'
    })
    Packery.data(objectContainer).layout()
    return
  }

  if (_target = event.target.closest('#bt_overview')) { //bt_overview arrow:
    if (_target.getAttribute('data-state') == '0') {
      _target.setAttribute('data-state', '1')
    } else {
      _target.setAttribute('data-state', '0')
      document.getElementById('dashOverviewPrev').unseen()
    }
    clearTimeout(jeeP.btOverviewTimer)
    return
  }

  if (_target = event.target.closest('.objectPreview')) { //
    var url = 'index.php?v=d&p=dashboard&object_id=' + _target.getAttribute('data-object_id') + '&childs=0&btover=1'
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      window.open(url).focus()
    } else {
      jeedomUtils.loadPage(url)
    }
    return
  }

  if (_target = event.target.closest('.li_object')) { //Dashboard mode list:
    var object_id = _target.querySelector('a[data-object_id]')?.getAttribute('data-object_id')
    if (document.querySelector('.div_object[data-object_id="' + object_id + '"]') != null && getUrlVars('summary') === false) { //Object already there as child
      jeedom.object.getImgPath({
        id: object_id,
        success: function(_path) {
          jeedomUtils.setBackgroundImage(_path)
        }
      })
      document.querySelectorAll('#dashOverviewPrev .li_object').removeClass('active')
      _target.addClass('active')
      jeeP.displayChildObject(object_id, false)
      jeedomUtils.addOrUpdateUrl('object_id', object_id)
      return
    } else  if (_target.querySelector('a[data-object_id]') != null) {
      jeedomUtils.loadPage(_target.querySelector('a[data-object_id]').getAttribute('data-href'))
      return
    }
    return
  }

  //category filter
  if (document.getElementById('categoryfilter').contains(event.target)) {
    event.stopPropagation()
  }
  if (event.target.matches('#catFilterNone')) {
    document.querySelectorAll('#categoryfilter .catFilterKey').forEach(checkbox => { checkbox.checked = false })
    jeeP.filterByCategory()
    return
  }
  if (event.target.matches('#catFilterAll')) {
    jeeP.resetCategoryFilter()
    return
  }

  if (['input', 'textarea', 'select'].includes(event.target.tagName.toLowerCase())) {
    event.target.focus()
    return
  }
}, {buble: true})

document.getElementById('div_pageContainer').addEventListener('mouseenter', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_overview')) { //bt_overview arrow:
    event.stopImmediatePropagation()
    event.stopPropagation()
    if (jeedomUI.isEditing) return
      if (document.getElementById('dashOverviewPrev').isVisible()) return
    jeeP.btOverviewTimer = setTimeout(function() {
      document.getElementById('dashOverviewPrev').seen()
    }, 300)
    return
  }

  if (_target = event.target.closest('.objectPreview')) { //Show summary in overview preview tiles
    document.querySelectorAll('#dashOverviewPrevSummaries > .objectSummaryContainer').unseen()
    var width = window.outerWidth
    var position = event.target.getBoundingClientRect()
    var css = {
      top: position.top - 95 + 'px'
    }
    if (position.left > width / 2) {
      css.left = 'unset'
      css.right = width - (position.left + 160) + 'px'
    } else {
      css.left = position.left + 'px'
      css.right = 'unset'
    }
    var summary = document.querySelector('.objectSummaryContainer.objectSummary' + _target.getAttribute('data-object_id'))
    summary.seen()
    Object.assign(summary.style, css)
    return
  }
}, {capture: true})

document.getElementById('div_pageContainer').addEventListener('mouseleave', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_overview')) { //bt_overview arrow:
    clearTimeout(jeeP.btOverviewTimer)
    event.stopImmediatePropagation()
    event.stopPropagation()
    return
  }

  if (event.target.matches('#dashOverviewPrev')) { //Overview preview container
    document.querySelectorAll('#dashOverviewPrevSummaries > .objectSummaryContainer').unseen()
    if (document.getElementById('bt_overview').getAttribute('data-state') == '0') {
      document.getElementById('dashOverviewPrev').unseen()
    }
    return
  }
}, {capture: true})

document.getElementById('div_pageContainer').addEventListener('mouseup', function(event) {
  var _target = null
  if (_target = event.target.closest('.objectPreview')) {
    if (event.which == 2) {
      event.preventDefault()
      var id = _target.getAttribute('data-object_id')
      document.querySelector('.objectPreview[data-object_id="' + id + '"] .name').triggerEvent('click', {detail: {ctrlKey: true}})
    }
    return
  }

  if (event.target.matches('#categoryfilter input.catFilterKey')) {
    var checkbox = event.target.closest('li').querySelector('input.catFilterKey')
    if (checkbox == null) return
    event.preventDefault()
    event.stopPropagation()

    if (event.which == 2) {
      if (document.querySelectorAll('.catFilterKey:checked').length == 1 && checkbox.checked) {
        document.querySelectorAll('#categoryfilter input.catFilterKey').forEach(checkbox => { checkbox.checked = true })
      } else {
        document.querySelectorAll('#categoryfilter input.catFilterKey').forEach(checkbox => { checkbox.checked = false })
        checkbox.checked = true
      }
    }
    setTimeout(function() { jeeP.filterByCategory() }, 1)
    return
  }
})

document.getElementById('div_pageContainer').addEventListener('mousedown', function(event) {
  if (event.target.matches('#categoryfilter a')) {
    event.stopImmediatePropagation()
    event.stopPropagation()
    event.preventDefault()
    var checkbox = event.target.closest('li').querySelector('.catFilterKey')
    if (checkbox == null) return

    var checkbox = event.target.closest('li').querySelector('.catFilterKey')
    if (checkbox == null) return
    if (event.which == 2) {
      if (document.querySelectorAll('.catFilterKey:checked').length == 1 && checkbox.checked) {
        document.querySelectorAll('#categoryfilter input.catFilterKey').forEach(checkbox => { checkbox.checked = true })
      } else {
        document.querySelectorAll('#categoryfilter input.catFilterKey').forEach(checkbox => { checkbox.checked = false })
        checkbox.checked = true
      }
    } else {
      checkbox.checked = !checkbox.checked
    }
    setTimeout(function() { jeeP.filterByCategory() }, 1)
  }
})

//Event for App Mobile:
document.body.addEventListener('jeeObject::summary::update', function(_event) {
  for (var i in _event.detail) {
    if(isset(_event.detail[i].force) && _event.detail[i].force == 1) continue
    if(_event.detail[i].object_id == 'global') {
      /* SEND UPDATE SUMMARY TO APP */
      jeedom.appMobile.postToApp('updateSummary', _event.detail[i].keys)
    }
  }
})

//Resize responsive tiles:
window.registerEvent('resize', function dashboard(event) {
  if (event.isTrigger) return
  jeedomUtils.positionEqLogic()
})
