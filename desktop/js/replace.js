/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

"use strict"

jeephp2js.listObjects.push(
  {
    id: null,
    name: '{{Aucun}}'
  }
)

if (!jeeFrontEnd.replace) {
  jeeFrontEnd.replace = {
    init: function() {
      window.jeeP = this
      this.sourcesEqContainer = document.getElementById('eqSource')
      this.filteredObjects = null
      this.filteredEqlogics = null
      this.replacerEqList = null
    },
    applyFilter: function() {
      jeeP.filteredObjects = new Array()
      var key = null
      document.querySelectorAll('#objectFilter .objectFilterKey').forEach(_filter => {
        if (_filter.checked) {
          key = _filter.getAttribute('data-key')
          if (key == '') key = null
          jeeP.filteredObjects.push(key)
        }
      })
      var byPlugins = new Array()
      document.querySelectorAll('#pluginFilter .pluginFilterKey').forEach(_filter => {
        if (_filter.checked) {
          byPlugins.push(_filter.getAttribute('data-key'))
        }
      })

      jeeP.filteredEqlogics = new Array()
      jeephp2js.listEqlogics.forEach(function(eqlogic) {
        if (jeeP.filteredObjects.includes(eqlogic.object_id) && byPlugins.includes(eqlogic.eqType_name)) {
          jeeP.filteredEqlogics.push(eqlogic)
        }
      })

      jeeP.sourcesEqContainer.empty()
      var eqDiv = ''
      var parentName
      var selectReplaceEqlogics = jeeP.getEqlogicSelect(true)
      var selectReplaceCmds
      jeeP.filteredEqlogics.forEach(function(eqlogic) {
        parentName = jeephp2js.listObjects.filter(o => o.id == eqlogic.object_id)[0].name
        eqDiv = '<ul class="eqLogic cursor" data-id="' + eqlogic.id + '" data-name="' + eqlogic.name + '" data-parent="' + parentName + '">'
        eqDiv += '<input type="checkbox" class="cb_selEqLogic" />'
        eqDiv += '<span>[' + parentName + '][' + eqlogic.name + '] (' + eqlogic.id + ' | ' + eqlogic.eqType_name + ')</span>'

        eqDiv += '<div class="replacer"><i class="far fa-arrow-alt-circle-right"></i> ' + selectReplaceEqlogics + '</div>'

        eqDiv += '<ul style="display:none;">'
        jeephp2js.listCommands.forEach(function(cmd) {
          if (cmd.eqLogic_id == eqlogic.id) {
            if (cmd.type == 'info') {
              eqDiv += '<li class="alert alert-info cmd" data-id="' + cmd.id + '" data-type="' + cmd.type + '">'
            } else {
              eqDiv += '<li class="alert alert-warning cmd" data-id="' + cmd.id + '" data-type="' + cmd.type + '">'
            }
            eqDiv += cmd.name + ' (' + cmd.id + ') ' + cmd.type + ' ' + cmd.subType
            eqDiv += '<div class="replacer"><i class="far fa-arrow-alt-circle-right"></i> <select></select></div>'
            eqDiv += '</li>'
          }
        })
        eqDiv += '</ul>'
        eqDiv += '</ul>'

        jeeP.sourcesEqContainer.insertAdjacentHTML('beforeend', eqDiv)
      })
    },
    resetFilter: function() {
      document.querySelectorAll('#objectFilter input.objectFilterKey').forEach(_filter => {
        _filter.checked = true
      })

      document.querySelectorAll('#pluginFilter input.pluginFilterKey').forEach(_filter => {
        _filter.checked = true
      })

      document.querySelector('#opt_copyEqProperties').checked = false
      document.querySelector('#opt_hideEqs').checked = false
      document.querySelector('#opt_copyCmdProperties').checked = false
      document.querySelector('#opt_removeHistory').checked = false
      document.querySelector('#opt_copyHistory').checked = false

      jeeP.sourcesEqContainer.empty()
    },
    getEqlogicSelect: function(reset=false) {
      if (!this.filteredEqlogics) return ''

      var select = '<select>'
      select += '<option value=""></option>'
      var parentName
      this.filteredObjects.forEach(function(_id) {
        select += '<optgroup label="' + jeephp2js.listObjects.filter(o => o.id == _id)[0].name + '">'
        jeeP.filteredEqlogics.filter(o => o.object_id == _id).forEach(function(eqlogic) {
          parentName = jeephp2js.listObjects.filter(o => o.id == _id)[0].name
          select += '<option value="' + eqlogic.id + '" data-name="' + eqlogic.name + '">[' + parentName + '][' + eqlogic.name + ']</option>'
        })

        select += '</optgroup>'

      })
      select += '</select>'
      return select
    },
    resetEqlogicSelect: function(eqlogicId=-1) {
      let select = document.querySelector('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"] div.replacer select')
      select.value = ''
      select.triggerEvent('keyup')
    },
    resetCmdSelects: function(eqlogicId=-1) {
      document.querySelectorAll('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"] li.cmd').forEach( _cmd => {
        _cmd.querySelector('select').empty()
      })
    },
    synchEqlogicsReplacers: function() {
      var eqReplacers = document.querySelectorAll('#eqSource ul.eqLogic > div.replacer select')
      jeeP.replacerEqList = new Array()
      eqReplacers.forEach(_select => {
        jeeP.replacerEqList.push(_select.value)
      })

      document.querySelectorAll('#eqSource ul.eqLogic').forEach( _ul => {
        var thisId = _ul.getAttribute('data-id')
        if (jeeP.replacerEqList.includes(thisId)) {
          jeeP.disableEqlogic(thisId)
        } else {
          jeeP.enableEqlogic(thisId)
        }
      })
    },
    enableEqlogic: function(eqlogicId=-1) {
      document.querySelector('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"]')?.removeClass('disabled')
    },
    disableEqlogic: function(eqlogicId=-1) {
      var eqEl = document.querySelector('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"]')
      eqEl.querySelector('input.cb_selEqLogic').checked = false
      eqEl.querySelector('ul').unseen()
      this.resetEqlogicSelect(eqEl.getAttribute('data-id'))
      eqEl.addClass('disabled')
    },
    selectReplacerEqlogic: function(_selectEl) {
      //get source and target eqLogics Ids:
      var thisEq = _selectEl.closest('ul.eqLogic')
      var sourceEqId = thisEq.getAttribute('data-id')
      var sourceEqName = thisEq.getAttribute('data-name')
      var targetEqId = _selectEl.value
      var targetEqName = _selectEl.querySelector('option[value="' + targetEqId + '"]').getAttribute('data-name')

      //open cmds ul:
      if (_selectEl.value != '' && !thisEq.querySelector('ul').isVisible()) {
        thisEq.querySelector('ul').seen()
      }

      //Do not replace itself!
      if (sourceEqId == targetEqId) {
        jeedomUtils.showAlert({level: 'warning', message: "{{Vous ne pouvez pas remplacer un équipement par lui même.}}"})
        _selectEl.value = ''
        _selectEl.triggerEvent('keyup')
        jeeP.resetCmdSelects(sourceEqId)
        jeeP.synchEqlogicsReplacers()
        return false
      }

      //Is ever replacing by this eqLogic:
      if (_selectEl.value != '' && is_array(jeeP.replacerEqList) && jeeP.replacerEqList.includes(targetEqId)) {
        jeedomUtils.showAlert({level: 'warning', message: "{{Cet équipement remplace déjà un autre équipement.}}"})
        _selectEl.value = ''
        _selectEl.triggerEvent('keyup')
        jeeP.resetCmdSelects(sourceEqId)
        jeeP.synchEqlogicsReplacers()
        return false
      }

      //Same name will throw error when in same object:
      if (sourceEqName == targetEqName) {
        jeedomUtils.showAlert({level: 'warning', message: "{{Vous ne pouvez pas remplacer un équipement par un équipement de même nom.}}"})
        _selectEl.value = ''
        _selectEl.triggerEvent('keyup')
        jeeP.resetCmdSelects(sourceEqId)
        jeeP.synchEqlogicsReplacers()
        return false
      }

      jeeP.synchEqlogicsReplacers()

      //Get over each command to set its select option with target commands list:
      var replacerCmdsInfo = jeephp2js.listCommands.filter(o => o.eqLogic_id == targetEqId && o.type == 'info')
      var replacerCmdsAction = jeephp2js.listCommands.filter(o => o.eqLogic_id == targetEqId && o.type == 'action')
      var cmdsObject = jeephp2js.listCommands.filter(o => o.eqLogic_id == sourceEqId)
      var cmds = thisEq.querySelectorAll('.cmd')
      var cmdSelect
      cmds.forEach( _cmd => {
        var type = _cmd.getAttribute('data-type')
        if (type == 'info') {
          var optionsCmds = replacerCmdsInfo
        } else {
          var optionsCmds = replacerCmdsAction
        }

        cmdSelect = _cmd.querySelector('select')
        cmdSelect.empty()
        var options = '<option value=""></option>'
        var _cmd = jeephp2js.listCommands.filter(o => o.id == _cmd.getAttribute('data-id'))[0]
        optionsCmds.forEach(function(optionsCmd) {
          if (_cmd.name.toLowerCase() == optionsCmd.name.toLowerCase() && _cmd.type == optionsCmd.type) {
            options += '<option selected value="' + optionsCmd.id + '">' + optionsCmd.name + '</option>'
          } else {
            options += '<option value="' + optionsCmd.id + '">' + optionsCmd.name + '</option>'
          }
        })
        cmdSelect.insertAdjacentHTML('beforeend', options)
      })
    },
    doReplace: function() {
      jeedomUtils.hideAlert()

      var opt_mode = document.getElementById('opt_mode').value
      var opt_copyEqProperties = document.getElementById('opt_copyEqProperties').checked
      var opt_hideEqs = document.getElementById('opt_hideEqs').checked
      var opt_copyCmdProperties = document.getElementById('opt_copyCmdProperties').checked
      var opt_removeHistory = document.getElementById('opt_removeHistory').checked
      var opt_copyHistory = document.getElementById('opt_copyHistory').checked

      var replaceEqs = {}
      var replaceCmds = {}

      document.querySelectorAll('#eqSource ul.eqLogic').forEach(_eglogic => {
        if (_eglogic.querySelector('input.cb_selEqLogic').checked) {
          var sourceEqId = _eglogic.getAttribute('data-id')
          var targetEqId = _eglogic.querySelector(':scope > div.replacer select').value

          if (targetEqId != '') {
            replaceEqs[sourceEqId] = targetEqId
            _eglogic.querySelectorAll('li.cmd').forEach(_cmd => {
              var replaceId = _cmd.querySelector('select').value
              if (replaceId != '') {
                replaceCmds[_cmd.getAttribute('data-id')] = replaceId
              }
            })
          }
        }
      })

      if (opt_copyEqProperties && Object.keys(replaceEqs).length === 0) {
        jeedomUtils.showAlert({
          message: '{{Aucun équipement à remplacer ou copier}}',
          level: 'info'
        })
        return true
      }

      if (!opt_copyEqProperties && Object.keys(replaceCmds).length === 0) {
        jeedomUtils.showAlert({
          message: '{{Aucune commande à remplacer ou copier}}',
          level: 'info'
        })
        return true
      }

      jeedom.massReplace({
        options: {
          mode: opt_mode,
          copyEqProperties: opt_copyEqProperties,
          hideEqs: opt_hideEqs,
          copyCmdProperties: opt_copyCmdProperties,
          removeCmdHistory: opt_removeHistory,
          copyCmdHistory: opt_copyHistory
        },
        eqlogics: replaceEqs,
        cmds: replaceCmds,
        error: function(error) {
          jeedomUtils.showAlert({message: error.message, level: 'danger'})
        },
        success: function(data) {
          jeedomUtils.showAlert({
            message: '{{Remplacement effectué}}' + ' : eqLogics: ' + data.eqlogics + ' | commands: ' + data.cmds,
            level: 'success'
          })
        }
      })
    },
  }
}

jeeFrontEnd.replace.init()

jeedomUtils.initTooltips()

//searching:
$('#in_searchByName').on('keyup', function(event) {
  try {
    var search = event.target.value
    var searchID = search
    if (isNaN(search)) searchID = false

    if (search == '') {
      jeeP.sourcesEqContainer.querySelectorAll('ul.eqLogic').seen()
      return
    }

    search = jeedomUtils.normTextLower(search)
    var eqLogic, eqName, eqParent, eqId
    jeeP.sourcesEqContainer.querySelectorAll('ul.eqLogic').forEach(eqLogic => {
      eqParent = jeedomUtils.normTextLower(eqLogic.getAttribute('data-parent'))
      if (searchID) {
        eqId = eqLogic.getAttribute('data-id')
        if (eqId != searchID) {
          eqLogic.unseen()
        } else {
          eqLogic.seen()
          return
        }
      } else {
        eqName = jeedomUtils.normTextLower(eqLogic.getAttribute('data-name'))
        if (eqName.includes(search)) {
          eqLogic.unseen()
        } else {
          eqLogic.seen()
        }
      }
    })
  } catch (error) {
    console.error(error)
  }
})
$('#bt_resetSearchName').on('click', function(event) {
  let input = document.getElementById('in_searchByName')
  input.value = ''
  input.triggerEvent('keyup')
})

//Filters:
//-> object
$('#objectFilter').on('click', function(event) {
  event.stopPropagation()
})
$('#objectFilterNone').on('click', function(event) {
  document.querySelectorAll('#objectFilter input.objectFilterKey').forEach(_filter => {
    _filter.checked = false
  })
})
$('#objectFilterAll').on('click', function(event) {
  document.querySelectorAll('#objectFilter input.objectFilterKey').forEach(_filter => {
    _filter.checked = true
  })
})
$('#objectFilter input.objectFilterKey').off('mouseup').on('mouseup', function(event) {
  event.preventDefault()
  event.stopPropagation()
  var checkbox = event.target.closest('input.objectFilterKey')
  if (event.which == 2) {
    document.querySelectorAll('#objectFilter input.objectFilterKey').forEach(_filter => { _filter.checked = false })
    checkbox.checked = true
  } else {
    checkbox.checked = !checkbox.checked
  }
})
$('#objectFilter li a').on('mousedown', function(event) {
  event.preventDefault()
  var checkbox = event.target.querySelector('input.objectFilterKey') || event.target.closest('input.objectFilterKey')
  if (checkbox == null) return
  if (event.which == 2 || event.ctrlKey) {
    if (document.querySelectorAll('input.objectFilterKey:checked').length == 1 && checkbox.checked) {
      document.querySelectorAll('#objectFilter li input.objectFilterKey').forEach(_key => { _key.checked = true })
    } else {
      document.querySelectorAll('#objectFilter li input.objectFilterKey').forEach(_key => { _key.checked = false })
      checkbox.checked = true
    }
  } else {
    checkbox.checked = !checkbox.checked
  }
})
//-> plugin
$('#pluginFilter').on('click', function(event) {
  event.stopPropagation()
})
$('#pluginFilterNone').on('click', function(event) {
  document.querySelectorAll('#pluginFilter input.pluginFilterKey').forEach(_filter => {
    _filter.checked = false
  })
})
$('#pluginFilterAll').on('click', function(event) {
  document.querySelectorAll('#pluginFilter input.pluginFilterKey').forEach(_filter => {
    _filter.checked = true
  })
})
$('#pluginFilter input.pluginFilterKey').off('mouseup').on('mouseup', function(event) {
  event.preventDefault()
  event.stopPropagation()
  var checkbox = event.target.closest('input.pluginFilterKey')
  if (event.which == 2) {
    document.querySelectorAll('#pluginFilter input.pluginFilterKey').forEach(_filter => { _filter.checked = false })
    checkbox.checked = true
  } else {
    checkbox.checked = !checkbox.checked
  }
})
$('#pluginFilter li a').on('mousedown', function(event) {
  event.preventDefault()
  var checkbox = event.target.querySelector('input.pluginFilterKey') || event.target.closest('input.pluginFilterKey')
  if (checkbox == null) return
  if (event.which == 2 || event.ctrlKey) {
    if (document.querySelectorAll('input.pluginFilterKey:checked').length == 1 && checkbox.checked) {
      document.querySelectorAll('#pluginFilter li input.pluginFilterKey').forEach(_key => { _key.checked = true })
    } else {
      document.querySelectorAll('#pluginFilter li input.pluginFilterKey').forEach(_key => { _key.checked = false })
      checkbox.checked = true
    }
  } else {
    checkbox.checked = !checkbox.checked
  }
})

//UI
$('#eqSource').on({
  'click': function(event) {
    if (event.target.tagName.toUpperCase() == 'I') return
    if (event.target.tagName.toUpperCase() == 'SELECT') return
    if (event.target.tagName.toUpperCase() == 'OPTION') return
    //checkbox clicked:
    if (event.target.tagName.toUpperCase() == 'INPUT') return
    //cmd cliked inside li:
    if (event.target.hasClass('cmd')) {
      return false
    }

    var el = event.target.closest('ul.eqLogic').querySelector('ul')
    if (el.isVisible()) {
      el.unseen()
    } else {
      el.seen()
    }
  }
}, 'ul.eqLogic')

$('#eqSource').on({
  'change': function(event) {
    let me = event.target.closest('ul.eqLogic > .replacer > select')
    jeeP.selectReplacerEqlogic(me)
  }
}, 'ul.eqLogic > .replacer > select')

$('#bt_clearReplace').on('click', function(event) {
  jeeP.resetFilter()
})

$('#bt_applyFilters').on('click', function(event) {
  jeeP.applyFilter()
})

$('#bt_replace').on('click', function(event) {
  jeeDialog.confirm({
    message: "<b>{{Il est fortement conseillé de réaliser un backup système avant d'utiliser cet outil !}}</b>" + "<br>" +  "{{Êtes-vous sûr de vouloir Remplacer ces équipements et commandes ?}}",
    callback: function(result) {
      if (result) {
        jeeP.doReplace()
      }
    }
  })
})

//Register events on top of page container:

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/