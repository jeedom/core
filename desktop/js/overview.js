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

if (!jeeFrontEnd.overview) {
  jeeFrontEnd.overview = {
    summaryObjEqs: [],
    _SummaryObserver_: null,
    observerConfig: {
      attributes: false,
      childList: true,
      characterData: false,
      subtree: true
    },
    init: function() {
      window.jeeP = this
    },
    postInit: function() {
      this.modal = document.getElementById('md_overviewSummary')
      this.modalContent = this.modal.querySelector('div.jeeDialogContent')
      this.checkResumeEmpty()
      this.createSummaryObserver()
      domUtils.hideLoading()
      self = this
      this.modal._jeeDialog.options.onResize = function(event) {
        Packery.data(self.modalContent).layout()
      }
    },
    createSummaryObserver: function() {
      this._SummaryObserver_ = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.type == 'childList' && mutation.target.hasClass('objectSummaryContainer')) {
            try {
              jeeP.updateSummary(mutation.addedNodes[0].className)
            } catch {}
          }
        })
      })

      var targetNode = document.getElementById('objectOverviewContainer')
      if (targetNode) this._SummaryObserver_.observe(targetNode, this.observerConfig)
    },
    updateSummary: function(_className) {
      _className = _className.replace('objectSummaryContainer ', '')
      var parent = document.querySelector('.' + _className).closest('.objectPreview')
      if (parent == null) return
      parent.querySelector('.topPreview')?.querySelectorAll('.objectSummaryParent')?.remove()
      var pResume = parent.querySelector('.resume')
      if (pResume == null) return
      pResume.querySelectorAll('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').forEach(function(element) {
        parent.querySelector('.topPreview').appendChild(element)
      })
      parent.querySelectorAll('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]')?.last()?.addClass('last')
      if (pResume.querySelector('.objectSummaryParent[data-summary="temperature"]') != null && pResume.querySelector('.objectSummaryParent[data-summary^="temp"]') != null ) {
        parent.find('.topPreview').appendChild(pResume.querySelector('.objectSummaryParent[data-summary^="temp"]'))
      }
      this.checkResumeEmpty()
    },
    checkResumeEmpty: function() {
      var button
      document.querySelectorAll('.objectPreview').forEach(function(element) {
        var visibles = [...element.querySelectorAll('.objectSummaryParent')].filter(el => el.isVisible())
        if (visibles.length == 0) {
          button = '<span class="bt_config"><i class="fas fa-cogs"></i></span>'
          element.querySelector('.bt_config')?.remove()
          element.querySelector('.topPreview').insertAdjacentHTML('beforeend', button)
        }
      })
    },
    getSummaryHtml: function(_object_id, _summary, _title) {
      this.summaryObjEqs[_object_id] = []
      self = this
      jeedom.object.getEqLogicsFromSummary({
        id: _object_id,
        onlyEnable: '1',
        onlyVisible: '0',
        version: 'dashboard',
        summary: _summary,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          self.modalContent.empty()
          try {
            Packery.data(self.modalContent).destroy()
          } catch (e) {}


          jeeP.modal.querySelector('div.jeeDialogTitle > span.title').innerHTML = _title
          jeeP.modal._jeeDialog.show()

          var nbEqs = data.length
          for (var i = 0; i < nbEqs; i++) {
            if (self.summaryObjEqs[_object_id].includes(data[i].id)) {
              nbEqs--
              return
            }
            self.summaryObjEqs[_object_id].push(data[i].id)

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
                  self.modalContent.html(html.html, true)
                }
                nbEqs--

                //is last ajax:
                if (nbEqs == 0) {
                  //adapt modal size:
                  let bRect = document.body.getBoundingClientRect()
                  var fullWidth = 0
                  var fullHeight = 0
                  var thisWidth = 0
                  var thisHeight = 0

                  document.querySelectorAll('#md_overviewSummary div.eqLogic-widget').forEach(function(element) {
                    thisWidth = element.offsetWidth
                    thisHeight = element.offsetHeight
                    if (fullHeight == 0 || fullHeight < thisHeight + 5) fullHeight = thisHeight + 5
                    if ((fullWidth + thisWidth + 150) < bRect.width) {
                      fullWidth += thisWidth + 7
                    } else {
                      fullHeight += thisHeight + 5
                    }
                  })

                  if (fullWidth == 0) {
                    fullWidth = 120
                    fullHeight = 120
                  }

                  fullWidth += 26
                  fullHeight += 51
                  self.modal.style.width = fullWidth + 'px'
                  self.modal.style.height = fullHeight + 'px'

                  new Packery(self.modalContent, {
                    gutter: parseInt(jeedom.theme['widget::margin']) * 2,
                    isLayoutInstant: true,
                    transitionDuration: 0,
                  })

                  //check is inside screen:
                  var modalLeft = self.modal.offsetLeft
                  if (modalLeft + fullWidth + 26 > bRect.width || modalLeft < 5) {
                    self.modal.style.left = bRect.width - fullWidth - 50 + 'px'
                  }

                  jeedomUtils.initTooltips(document.getElementById('md_overviewSummary'))
                }
              }
            })
          }
        }
      })
    },
  }
}

//Set summary dialog:
jeeDialog.dialog({
  id: 'md_overviewSummary',
  show: false,
  backdrop: false,
  width: 500,
  height: 200,
  retainPosition: true,
})

jeeFrontEnd.overview.init()

//move to top summary:
document.querySelectorAll('.objectPreview').forEach(function(element) {
  var parent = element.querySelector('.topPreview')
  element.querySelectorAll('.objectSummaryParent[data-summary="temperature"], .objectSummaryParent[data-summary="motion"], .objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="humidity"]').forEach(function(el) {
    parent.appendChild(el)
  })
  element.querySelectorAll('.objectSummaryParent[data-summary="security"], .objectSummaryParent[data-summary="motion"]')?.last()?.addClass('last')
  if (element.querySelector('.objectSummaryParent[data-summary="temperature"]') != null && element.querySelector('.objectSummaryParent[data-summary^="temp"]') != null) {
    parent.appendChild(element.querySelector('.objectSummaryParent[data-summary^="temp"]'))
  }
})

//infos/actions tile signals:
jeedomUI.isEditing = false
jeedomUI.setEqSignals()

document.querySelectorAll('.resume')?.seen()

jeeFrontEnd.overview.postInit()

//Init Packery:
new Packery(jeeP.modalContent)

//summary modal events:
jeeP.modalContent.addEventListener('click', function(event) {
  if (event.target.closest('div.eqLogic-widget') == null) { //modal background click
    jeeP.modal._jeeDialog.close()
    return
  }

  if (event.target.matches('div.eqLogic-widget .history') || event.target.closest('.cmd-widget.history') != null ) { //history in summary modal
    event.stopImmediatePropagation()
    event.stopPropagation()
    if (event.ctrlKey || event.metaKey) {
      var cmdIds = []
      event.target.closest('div.eqLogic-widget').querySelectorAll('.history[data-cmd_id]').forEach(function(cmd) {
        cmdIds.push(cmd.getAttribute('data-cmd_id'))
      })
      cmdIds = cmdIds.join('-')
    } else {
      var cmdIds = event.target.closest('.history[data-cmd_id]').getAttribute('data-cmd_id')
    }
    jeeFrontEnd.overview.modalContent.empty()
    jeeDialog.dialog({
      id: 'md_cmdHistory',
      title: '{{Historique}}',
      contentUrl: 'index.php?v=d&modal=cmd.history&id=' + cmdIds
    })
  }
}, {capture: false})


//div_pageContainer events delegation:
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('.objectPreview .name')) {
    var url = 'index.php?v=d&p=dashboard&object_id=' + _target.closest('.objectPreview').getAttribute('data-object_id') + '&btover=1'
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      window.open(url).focus()
    } else {
      jeedomUtils.loadPage(url)
    }
    return
  }

  if (_target = event.target.closest('.objectSummaryParent')) {
    //action summary:
    if (event.ctrlKey) return

    event.stopPropagation()
    event.preventDefault()
    var objectId = event.target.closest('.objectPreview').getAttribute('data-object_id')
    var summaryType = _target.getAttribute('data-summary')

    var icon = _target.querySelector('i') //?.outerHTML
    if (icon) {
      var title = icon.outerHTML + ' ' +  _target.closest('.objectPreview').querySelector('.topPreview .name').textContent
    } else {
      var title = _target.closest('.objectPreview').querySelector('.name').textContent
    }
    jeeP.getSummaryHtml(objectId, summaryType, title)
    return
  }

  if (_target = event.target.closest('.objectPreview')) {
    var url = _target.getAttribute('data-url')
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      window.open(url).focus()
    } else {
      jeedomUtils.loadPage(url)
    }
    return
  }

  if (_target = event.target.closest('.objectPreview .bt_config')) {
    var objectId = event.target.closest('.objectPreview').getAttribute('data-object_id')
    var url = 'index.php?v=d&p=object&id=' + objectId + '#summarytab'
    jeedomUtils.loadPage(url)
    return
  }
})

document.getElementById('div_pageContainer').addEventListener('mouseup', function(event) {
  var _target = null
  if (_target = event.target.closest('.objectPreview .name')) {
    if (event.which == 2) {
      event.preventDefault()
      var id = event.target.closest('.objectPreview').getAttribute('data-object_id')
      document.querySelector('.objectPreview[data-object_id="' + id + '"] .name').triggerEvent('click', {detail: {ctrlKey: true}})
    }
    return
  }

  if (_target = event.target.closest('.objectSummaryParent')) {
    if (event.which == 2) {
      var id = _target.getAttribute('data-object_id')
      var url = 'index.php?v=d&p=dashboard&summary=' + _target.getAttribute('data-summary') + '&object_id=' + id + '&childs=0'
      window.open(url).focus()
      return
    }
  }

  if (_target = event.target.closest('.objectPreview')) {
    if (event.which == 2) {
      if (event.target.hasClass('topPreview') || event.target.hasClass('name')) return
      event.preventDefault()
      var id = event.target.getAttribute('data-object_id') || event.target.closest('.objectPreview').getAttribute('data-object_id')
      document.querySelector('.objectPreview[data-object_id="' + id + '"]').triggerEvent('click', {detail: {ctrlKey: true}})
    }
    return
  }
})