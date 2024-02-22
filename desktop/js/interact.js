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

if (!jeeFrontEnd.interact) {
  jeeFrontEnd.interact = {
    init: function() {
      window.jeeP = this
      this.actionOptions = []
      if (is_numeric(getUrlVars('id'))) {
        this.printInteract(getUrlVars('id'))
      }
      document.querySelector('sub.itemsNumber').innerHTML = '(' + document.querySelectorAll('.interactDisplayCard').length + ')'
      this.setContextMenu()
      this.setAutoComplete()
    },
    printInteract: function(_id) {
      jeedomUtils.hideAlert()
      document.getElementById('div_conf').seen()
      document.getElementById('interactThumbnailDisplay').unseen()
      document.querySelectorAll('.interactDisplayCard').removeClass('active')
      document.querySelectorAll('.interactDisplayCard[data-interact_id="' + _id + '"]').addClass('active')
      jeedom.interact.get({
        id: _id,
        success: function(data) {
          jeeP.actionOptions = []
          document.getElementById('div_action').empty()
          document.querySelectorAll('.interactAttr').jeeValue('')
          document.querySelectorAll('.interact').setJeeValues(data, '.interactAttr')
          document.querySelectorAll('.interactAttr[data-l1key="filtres"]').forEach(_filter => {
            _filter.selected = false
          })
          var option = null
          if (isset(data.filtres) && isset(data.filtres.type) && isPlainObject(data.filtres.type)) {
            for (var i in data.filtres.type) {
              if (data.filtres.type[i] == '1') (option = document.querySelector('.interactAttr[data-l1key="filtres"][data-l2key="type"][data-l3key="' + i + '"]')) != null ? option.selected = true : null
            }
          }
          if (isset(data.filtres) && isset(data.filtres.subtype) && isPlainObject(data.filtres.subtype)) {
            for (var i in data.filtres.subtype) {
              if (data.filtres.subtype[i] == '1') (option = document.querySelector('.interactAttr[data-l1key="filtres"][data-l2key="subtype"][data-l3key="' + i + '"]')) != null ? option.selected = true : null
            }
          }
          if (isset(data.filtres) && isset(data.filtres.unite) && isPlainObject(data.filtres.unite)) {
            for (var i in data.filtres.unite) {
              if (data.filtres.unite[i] == '1') (option = document.querySelector('.interactAttr[data-l1key="filtres"][data-l2key="unite"][data-l3key="' + i + '"]')) != null ? option.selected = true : null
            }
          }
          if (isset(data.filtres) && isset(data.filtres.object) && isPlainObject(data.filtres.object)) {
            for (var i in data.filtres.object) {
              if (data.filtres.object[i] == '1') (option = document.querySelector('.interactAttr[data-l1key="filtres"][data-l2key="object"][data-l3key="' + i + '"]')) != null ? option.selected = true : null
            }
          }
          if (isset(data.filtres) && isset(data.filtres.plugin) && isPlainObject(data.filtres.plugin)) {
            for (var i in data.filtres.plugin) {
              if (data.filtres.plugin[i] == '1') (option = document.querySelector('.interactAttr[data-l1key="filtres"][data-l2key="plugin"][data-l3key="' + i + '"]')) != null ? option.selected = true : null
            }
          }
          if (isset(data.filtres) && isset(data.filtres.category) && isPlainObject(data.filtres.category)) {
            for (var i in data.filtres.category) {
              if (data.filtres.category[i] == '1') (option = document.querySelector('.interactAttr[data-l1key="filtres"][data-l2key="category"][data-l3key="' + i + '"]')) != null ? option.selected = true : null
            }
          }
          if (isset(data.actions) && isset(data.actions.cmd) && Array.isArray(data.actions.cmd) && data.actions.cmd.length != null) {
            for (var i in data.actions.cmd) {
              jeeP.addAction(data.actions.cmd[i], 'action', '{{Action}}')
            }
          }
          jeedomUtils.taAutosize()

          var hash = window.location.hash
          jeedomUtils.addOrUpdateUrl('id', data.id)
          if (hash == '') {
            document.querySelector('.nav-tabs a[href="#generaltab"]')?.click()
          } else {
            window.location.hash = hash
          }

          jeedom.cmd.displayActionsOption({
            params: jeeP.actionOptions,
            async: false,
            error: function(error) {
              jeedomUtils.showAlert({
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              for (var i in data) {
                if (data[i].html != '') {
                  document.getElementById(data[i].id).html(data[i].html.html)
                }
              }
              jeedomUtils.taAutosize()
              jeeFrontEnd.modifyWithoutSave = false
            }
          })
          jeeFrontEnd.modifyWithoutSave = false
        }
      })
    },
    addAction: function(_action, _type, _name) {
      if (!isset(_action)) {
        _action = {}
      }
      if (!isset(_action.options)) {
        _action.options = {}
      }
      var div = '<div class="' + _type + '">'
      div += '<div class="form-group ">'
      div += '<div class="col-sm-5">'
      div += '<div class="input-group input-group-sm">'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default btn-sm bt_removeAction roundedLeft" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>'
      div += '</span>'
      div += '<input class="expressionAttr form-control cmdAction input-sm" data-l1key="cmd" data-type="' + _type + '" />'
      div += '<span class="input-group-btn">'
      div += '<a class="btn btn-default btn-sm listAction" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></a>'
      div += '<a class="btn btn-default btn-sm listCmd roundedRight" data-type="' + _type + '" title="{{Sélectionner la commande}}"><i class="fas fa-list-alt"></i></a>'
      div += '</span>'
      div += '</div>'
      div += '</div>'
      var actionOption_id = jeedomUtils.uniqId()
      div += '<div class="col-sm-7 actionOptions" id="' + actionOption_id + '"></div>'
      document.getElementById('div_' + _type).insertAdjacentHTML('beforeend', div)
      document.querySelectorAll('#div_' + _type + ' .' + _type + '').last().setJeeValues(_action, '.expressionAttr')
      jeeP.actionOptions.push({
        expression: init(_action.cmd, ''),
        options: _action.options,
        id: actionOption_id
      })
    },
    setContextMenu: function() {
      try {
        jeedom.interact.all({
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(interacts) {
            if (interacts.length == 0) {
              return
            }
            var interactGroups = []
            for (i = 0; i < interacts.length; i++) {
              group = interacts[i].group
              if (group == null) continue
              if (group == "") group = 'Aucun'
              group = group[0].toUpperCase() + group.slice(1)
              interactGroups.push(group)
            }
            interactGroups = Array.from(new Set(interactGroups))
            interactGroups.sort()
            var interactList = []
            for (var i = 0; i < interactGroups.length; i++) {
              group = interactGroups[i]
              interactList[group] = []
              for (var j = 0; j < interacts.length; j++) {
                var sc = interacts[j]
                var scGroup = sc.group
                if (scGroup == null) continue
                if (scGroup == "") scGroup = 'Aucun'
                if (scGroup.toLowerCase() != group.toLowerCase()) continue
                if (sc.name == "") sc.name = sc.query
                interactList[group].push([sc.name, sc.id])
              }
            }
            //set context menu!
            var contextmenuitems = {}
            var uniqId = 0
            for (var group in interactList) {
              var groupinteracts = interactList[group]
              var items = {}
              for (var index in groupinteracts) {
                var sc = groupinteracts[index]
                var scName = sc[0]
                var scId = sc[1]
                items[uniqId] = {
                  'name': scName,
                  'id': scId
                }
                uniqId++
              }
              contextmenuitems[group] = {
                'name': group,
                'items': items
              }
            }

            if (Object.entries(contextmenuitems).length > 0 && contextmenuitems.constructor === Object) {
              new jeeCtxMenu({
                selector: '.nav.nav-tabs > li',
                autoHide: true,
                zIndex: 9999,
                className: 'interact-context-menu',
                callback: function(key, options, event) {
                  if (!jeedomUtils.checkPageModified()) {
                    if (event.ctrlKey || event.metaKey || event.which == 2) {
                      var url = 'index.php?v=d&p=interact&id=' + options.commands[key].id
                      if (window.location.hash != '') {
                        url += window.location.hash
                      }
                      window.open(url).focus()
                    } else {
                      jeeP.printInteract(options.commands[key].id)
                    }
                  }
                },
                items: contextmenuitems
              })
            }
          }
        })
      } catch (err) { }
    },
    setAutoComplete: function() {
      document.querySelector('.interactAttr[data-l1key="group"]').jeeComplete({
        source: function(request, response, url) {
          domUtils.ajax({
            type: 'POST',
            url: 'core/ajax/interact.ajax.php',
            data: {
              action: 'autoCompleteGroup',
              term: request.term
            },
            dataType: 'json',
            global: false,
            error: function(request, status, error) {
              handleAjaxError(request, status, error)
            },
            success: function(data) {
              if (data.state != 'ok') {
                jeedomUtils.showAlert({
                  message: data.result,
                  level: 'danger'
                })
                return
              }
              response(data.result)
            }
          })
        },
        minLength: 1,
      })
    },
    //Get filters for save / duplicate
    getInteractFilters: function() {
      var filters = {}
      filters.type = {}
      document.querySelectorAll('option[data-l1key="filtres"][data-l2key="type"]').forEach(_option => {
        filters.type[_option.getAttribute('data-l3key')] = (_option.selected) ? '1' : '0'
      })
      filters.subtype = {}
      document.querySelectorAll('option[data-l1key="filtres"][data-l2key="subtype"]').forEach(_option => {
        filters.subtype[_option.getAttribute('data-l3key')] = (_option.selected) ? '1' : '0'
      })
      filters.unite = {}
      document.querySelectorAll('option[data-l1key="filtres"][data-l2key="unite"]').forEach(_option => {
        filters.unite[_option.getAttribute('data-l3key')] = (_option.selected) ? '1' : '0'
      })
      filters.object = {}
      document.querySelectorAll('option[data-l1key="filtres"][data-l2key="object"]').forEach(_option => {
        filters.object[_option.getAttribute('data-l3key')] = (_option.selected) ? '1' : '0'
      })
      filters.plugin = {}
      document.querySelectorAll('option[data-l1key="filtres"][data-l2key="plugin"]').forEach(_option => {
        filters.plugin[_option.getAttribute('data-l3key')] = (_option.selected) ? '1' : '0'
      })
      filters.category = {}
      document.querySelectorAll('option[data-l1key="filtres"][data-l2key="category"]').forEach(_option => {
        filters.category[_option.getAttribute('data-l3key')] = (_option.selected) ? '1' : '0'
      })
      filters.visible = {}
      document.querySelectorAll('option[data-l1key="filtres"][data-l2key="visible"]').forEach(_option => {
        filters.visible[_option.getAttribute('data-l3key')] = (_option.selected) ? '1' : '0'
      })
      return filters
    },
  }
}

jeeFrontEnd.interact.init()

//Set sortable:
Sortable.create(document.getElementById('div_action'), {
  delay: 100,
  delayOnTouchOnly: true,
  draggable: '.action',
  filter: 'a, input, textarea',
  preventOnFilter: false,
  direction: 'vertical',
  removeCloneOnHide: true,
})

//Register events on top of page container:
document.registerEvent('keydown', function(event) {
  if (jeedomUtils.getOpenedModal()) return
  if ((event.ctrlKey || event.metaKey) && event.which == 83) { //s
    event.preventDefault()
    if (document.getElementById('bt_saveInteract').isVisible()) {
      document.getElementById('bt_saveInteract').click()
    }
  }
})

//searching
document.getElementById('in_searchInteract')?.addEventListener('keyup', function(event) {
  var search = event.target.value
  if (search == '') {
    document.querySelectorAll('#accordionInteract .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
    document.querySelectorAll('.interactDisplayCard').seen()
    return
  }
  search = jeedomUtils.normTextLower(search)
  var not = search.startsWith(":not(")
  if (not) {
    search = search.replace(':not(', '')
  }
  document.querySelectorAll('#accordionInteract .accordion-toggle').forEach(_panel => { _panel.setAttribute('data-show', 0) })
  document.querySelectorAll('.interactDisplayCard').unseen()
  var match, text
  document.querySelectorAll('.interactDisplayCard .name').forEach(_name => {
    match = false
    text = jeedomUtils.normTextLower(_name.textContent)
    if (text.includes(search)) {
      match = true
    }

    if (not) match = !match
    if (match) {
      _name.closest('.interactDisplayCard').seen()
      _name.closest('.panel').querySelector('.accordion-toggle').setAttribute('data-show', 1)
    }
  })
  document.querySelectorAll('.accordion-toggle.collapsed[data-show="1"]').forEach(_panel => { _panel.click() })
  document.querySelectorAll('.accordion-toggle:not(.collapsed)[data-show="0"]').forEach(_panel => { _panel.click() })
})

/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('change', function(event) {
  var _target = null
  if (_target = event.target.closest('.interactAttr')) {
    if (_target.isVisible()) jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

//ThumbnailDisplay
document.getElementById('interactThumbnailDisplay').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_openAll')) {
    document.querySelectorAll('#accordionInteract .accordion-toggle.collapsed').forEach(_panel => { _panel.click() })
    return
  }

  if (_target = event.target.closest('#bt_closeAll')) {
    document.querySelectorAll('#accordionInteract .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
    return
  }

  if (_target = event.target.closest('#bt_resetInteractSearch')) {
    document.getElementById('in_searchInteract').jeeValue('').triggerEvent('keyup')
    return
  }

  if (_target = event.target.closest('.interactDisplayCard')) {
    if ((isset(event.detail) && event.detail.ctrlKey) || event.ctrlKey || event.metaKey) {
      var url = '/index.php?v=d&p=interact&id=' + _target.getAttribute('data-interact_id')
      window.open(url).focus()
    } else {
      jeeP.printInteract(_target.getAttribute('data-interact_id'))
    }
    return
  }

  if (_target = event.target.closest('#bt_regenerateInteract')) {
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir régénérer toutes les interactions (cela peut être très long) ?}}', function(result) {
      if (result) {
        jeedom.interact.regenerateInteract({
          interact: {
            query: result
          },
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeedomUtils.showAlert({
              message: '{{Toutes les interactions ont été régénérées}}',
              level: 'success'
            })
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_testInteract')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Tester les interactions}}",
      contentUrl: 'index.php?v=d&modal=interact.test'
    })
    return
  }

  if (_target = event.target.closest('#bt_addInteract')) {
    jeeDialog.prompt("{{Demande}} ?", function(result) {
      if (result !== null) {
        jeedom.interact.save({
          interact: {
            query: result
          },
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeeFrontEnd.modifyWithoutSave = false
            jeedomUtils.loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1')
          }
        })
      }
    })
    return
  }
})

document.getElementById('interactThumbnailDisplay').addEventListener('mouseup', function(event) {
  var _target = null
  if (_target = event.target.closest('.interactDisplayCard')) {
    if (event.which == 2) {
      event.preventDefault()
      let id = _target.getAttribute('data-interact_id')
      document.querySelector('.interactDisplayCard[data-interact_id="' + id + '"]').triggerEvent('click', { detail: { ctrlKey: true } })
    }
    return
  }
})


//Interaction
document.getElementById('div_conf').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_interactThumbnailDisplay')) {
    if (jeedomUtils.checkPageModified()) return
    document.getElementById('div_conf').unseen()
    document.getElementById('interactThumbnailDisplay').seen()
    jeedomUtils.addOrUpdateUrl('id', null, '{{Interactions}} - ' + JEEDOM_PRODUCT_NAME)
    return
  }

  if (_target = event.target.closest('#bt_chooseIcon')) {
    var _icon = document.querySelector('div[data-l2key="icon"] > i')
    if (_icon != null) {
      _icon = _icon.getAttribute('class')
    } else {
      _icon = false
    }
    jeedomUtils.chooseIcon(function(_icon) {
      document.querySelector('div[data-l2key="icon"]').innerHTML = _icon
      jeeFrontEnd.modifyWithoutSave = true
    }, { icon: _icon })
    return
  }

  if (_target = event.target.closest('#bt_duplicate')) {
    jeeDialog.prompt("{{Nom}} ?", function(result) {
      if (result !== null) {
        var interact = document.querySelectorAll('.interact').getJeeValues('.interactAttr')[0]
        interact.filtres = jeeFrontEnd.interact.getInteractFilters()
        interact.actions = {}
        interact.actions.cmd = document.querySelectorAll('#div_action .action').getJeeValues('.expressionAttr')
        interact.name = result
        interact.id = ''
        jeedom.interact.save({
          interact: interact,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            jeeFrontEnd.modifyWithoutSave = false
            jeedomUtils.loadPage('index.php?v=d&p=interact&id=' + data.id + '&saveSuccessFull=1')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_saveInteract')) {
    var interact = document.querySelectorAll('.interact').getJeeValues('.interactAttr')[0]
    interact.filtres = jeeFrontEnd.interact.getInteractFilters()
    interact.actions = {}
    interact.actions.cmd = document.querySelectorAll('#div_action .action').getJeeValues('.expressionAttr')
    jeedom.interact.save({
      interact: interact,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeedomUtils.showAlert({
          message: '{{Sauvegarde réussie avec succès}}',
          level: 'success'
        })
        jeeFrontEnd.modifyWithoutSave = false
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_removeInteract')) {
    jeedomUtils.hideAlert()
    let name = document.querySelector('input[data-l1key="name"]').value
    let id = document.querySelector('input[data-l1key="id"]').value
    jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer l\'interaction}} <span style="font-weight: bold ;">' + name + '</span> ?', function(result) {
      if (result) {
        jeedom.interact.remove({
          id: id,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function() {
            jeeFrontEnd.modifyWithoutSave = false
            jeedomUtils.loadPage('index.php?v=d&p=interact&removeSuccessFull=1')
          }
        })
      }
    })
    return
  }

  if (_target = event.target.closest('#bt_addAction')) {
    jeeP.addAction({}, 'action', '{{Action}}')
    jeeFrontEnd.modifyWithoutSave = true
    return
  }

  if (_target = event.target.closest('.displayInteracQuery')) {
    jeeDialog.dialog({
      id: 'jee_modal',
      title: "{{Liste des interactions}}",
      contentUrl: 'index.php?v=d&modal=interact.query.display&interactDef_id=' + document.querySelector('.interactAttr[data-l1key="id"]').value
    })
    return
  }

  if (_target = event.target.closest('.listEquipementInfoReply')) {
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'info'
      }
    }, function(result) {
      document.querySelector('.interactAttr[data-l1key="reply"]').insertAtCursor(result.human)
    })
    return
  }

  if (_target = event.target.closest('.listCmd')) {
    var type = _target.getAttribute('data-type')
    var el = _target.closest('.' + type).querySelector('.expressionAttr[data-l1key="cmd"]')
    jeedom.cmd.getSelectModal({
      cmd: {
        type: 'action'
      }
    }, function(result) {
      el.jeeValue(result.human)
      jeeFrontEnd.modifyWithoutSave = true
      jeedom.cmd.displayActionOption(result.human, '', function(html) {
        el.closest('.' + type).querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
    return
  }

  if (_target = event.target.closest('.listAction')) {
    var type = _target.getAttribute('data-type')
    var el = _target.closest('.' + type).querySelector('.expressionAttr[data-l1key="cmd"]')
    jeedom.getSelectActionModal({}, function(result) {
      el.jeeValue(result.human)
      jeeFrontEnd.modifyWithoutSave = true
      jeedom.cmd.displayActionOption(result.human, '', function(html) {
        el.closest('.' + type).querySelector('.actionOptions').html(html)
        jeedomUtils.taAutosize()
      })
    })
    return
  }

  if (_target = event.target.closest('.bt_removeAction')) {
    var type = _target.getAttribute('data-type')
    _target.closest('.' + type).remove()
    jeeFrontEnd.modifyWithoutSave = true
    return
  }
})

document.getElementById('div_conf').addEventListener('dblclick', function(event) {
  var _target = null
  if (_target = event.target.closest('.interactAttr[data-l1key="display"][data-l2key="icon"]')) {
    _target.remove()
    return
  }
})

document.getElementById('div_conf').addEventListener('focusout', function(event) {
  var _target = null
  if (_target = event.target.closest('.cmdAction.expressionAttr[data-l1key="cmd"]')) {
    var type = _target.getAttribute('data-type')
    var expression = _target.closest('.' + type).getJeeValues('.expressionAttr')
    jeedom.cmd.displayActionOption(_target.jeeValue(), init(expression[0].options), function(html) {
      _target.closest('.' + type).querySelector('.actionOptions').html(html)
      jeedomUtils.taAutosize()
    })
    return
  }
})
