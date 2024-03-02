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

if (!jeeFrontEnd.types) {
  jeeFrontEnd.types = {
    init: function() {
      window.jeeP = this
      this.generics = jeephp2js.generics
      this.gen_families = jeephp2js.gen_families
      this.genericsByFamily = {}
      for (var i in this.gen_families) {
        this.genericsByFamily[this.gen_families[i]] = {}
      }
      this.setQueryButtons()
    },
    setFamiliesNumber: function() {
      var ul
      document.querySelectorAll('span.spanNumber').forEach(_span => {
        ul = _span.closest('.eqlogicSortable').querySelector('ul.eqLogicSortable')
        _span.textContent = ' (' + ul.querySelectorAll('li.eqLogic').length + ')'
      })
    },
    setQueryButtons: function() {
      document.querySelectorAll('div.eqlogicSortable').forEach(_sort => {
        if (!Object.keys(jeeP.gen_families).includes(_sort.getAttribute('data-id'))) {
          _sort.querySelectorAll('.bt_queryCmdsTypes')?.addClass('hidden')
        } else {
          _sort.querySelectorAll('.bt_queryCmdsTypes')?.removeClass('hidden')
        }

      })
    },
    getSelectCmd: function(_family = '', _type, _subtype, _none = true) {
      var htmlSelect = '<select class="modalCmdGenericSelect input-xs">'
      if (_none) htmlSelect += '<option value="">{{Aucun}}</option>'
      for (var group in this.genericsByFamily) {
        if (_family != '' && group != _family) continue
        for (var i in this.genericsByFamily[group]) {
          if (this.genericsByFamily[group][i].type.toLowerCase() == _type.toLowerCase() && (this.genericsByFamily[group][i].subtype.includes(_subtype) || this.genericsByFamily[group][i].subtype.length == 0)) {
            htmlSelect += '<option value="' + this.genericsByFamily[group][i].genkey + '">' + this.genericsByFamily[group][i].name + '</option>'
          }
        }
      }
      htmlSelect += '</select>'
      return htmlSelect
    },
    levenshteinDistance: function(str1 = '', str2 = '') {
      const track = Array(str2.length + 1).fill(null).map(() => Array(str1.length + 1).fill(null))
      for (let i = 0; i <= str1.length; i += 1) {
        track[0][i] = i
      }
      for (let j = 0; j <= str2.length; j += 1) {
        track[j][0] = j
      }
      for (let j = 1; j <= str2.length; j += 1) {
        for (let i = 1; i <= str1.length; i += 1) {
          const indicator = str1[i - 1] === str2[j - 1] ? 0 : 1
          track[j][i] = Math.min(
            track[j][i - 1] + 1, // deletion
            track[j - 1][i] + 1, // insertion
            track[j - 1][i - 1] + indicator, // substitution
          )
        }
      }
      return track[str2.length][str1.length]
    },
    compareGenericName: function(a, b) {
      if (a.name < b.name) {
        return -1
      }
      if (a.name > b.name) {
        return 1
      }
      return 0
    },
    listGenericTypes: function() {
      jeeDialog.dialog({
        id: 'md_applyCmdsTypes',
        title: "{{Liste des Types Génériques (Core et Plugins)}}",
        width: 'calc(80% - 200px)',
        onClose: function() {
          jeeDialog.get('#md_applyCmdsTypes').destroy()
        },
      })

      var container = document.querySelector('#md_applyCmdsTypes .jeeDialogContent')
      var inner = '<table class="table table-condensed">'
      inner += '<td>{{Générique}}</td><td>{{Nom}}</td><td>{{Type}}</td><td>{{Sous type}}</td>'

      var family, familyName, generics, generic, infos, actions
      for (var familyId in jeeP.gen_families) {
        infos = []
        actions = []
        family = jeeP.gen_families[familyId]
        generics = jeeP.genericsByFamily[family]
        inner += '<tr class="center"><td colspan=5><legend>' + family + ' (id: ' + familyId + ')</legend></td></tr>'

        //seperate to infos first
        for (generic in generics) {
          generic = generics[generic]
          if (generic.type == 'Info') {
            infos.push(generic)
          }
          if (generic.type == 'Action') {
            actions.push(generic)
          }
        }

        infos.sort(jeeP.compareGenericName)
        actions.sort(jeeP.compareGenericName)

        for (var idx in infos) {
          generic = infos[idx]
          inner += '<tr>'
          inner += '<td>' + generic.genkey + '</td><td>' + generic.name + '</td><td class="label-info">' + generic.type + '</td><td class="label">' + generic.subtype + '</td>'//<td>' + generic.comment + '</td>'
          inner += '</tr>'
        }
        for (var idx in actions) {
          generic = actions[idx]
          inner += '<tr>'
          inner += '<td>' + generic.genkey + '</td><td>' + generic.name + '</td><td class="label-warning">' + generic.type + '</td><td class="label">' + generic.subtype + '</td>'//<td>' + generic.comment + '</td>'
          inner += '</tr>'
        }
      }
      inner += '</table><br/>'
      container.empty().insertAdjacentHTML('beforeend', inner)
    },
    //Modal apply:
    queryCmdsTypes: function(_butAuto) {
      var genFamilyId = _butAuto.closest('div.eqlogicSortable').getAttribute('data-id')
      var genFamily = jeeP.gen_families[genFamilyId]

      //Get selected eqLogics and all their cmd data:
      var queryEqIds = {}
      queryEqIds[(_butAuto.closest('li.eqLogic').getAttribute('data-id'))] = { 'cmds': [] }
      _butAuto.closest('ul.eqLogicSortable').querySelectorAll('li.eqLogic.dragSelected').forEach(_handle => {
        if (_handle.querySelector('ul.eqLogicCmds')) {
          queryEqIds[_handle.getAttribute('data-id')] = { 'cmds': [] }
        }
      })
      var cmd
      for (var _id in queryEqIds) {
        document.querySelectorAll('li.eqLogic[data-id="' + _id + '"] li.cmd').forEach(_cmd => {
          cmd = {}
          cmd['id'] = _cmd.getAttribute('data-id')
          cmd['name'] = _cmd.getAttribute('data-name').toLowerCase().trim()
          cmd['type'] = _cmd.getAttribute('data-type')
          cmd['subtype'] = _cmd.getAttribute('data-subtype')
          cmd['generic'] = _cmd.getAttribute('data-generic')
          cmd['genericName'] = _cmd.querySelector('.genericType').textContent.replace(genFamily + jeephp2js.typeStringSep, '')
          cmd['queryGeneric'] = ''
          cmd['queryGenericName'] = ''
          cmd['possibilities'] = []
          queryEqIds[_id].cmds.push(cmd)
        })
      }

      //Iterate for each generic in this family, each eqLogic selected, each eqLogic commands:
      var thisGen, thisCmd
      Object.keys(jeeP.genericsByFamily[genFamily]).forEach(key => {
        thisGen = jeeP.genericsByFamily[genFamily][key]
        for (var _id in queryEqIds) {
          for (var _cmd in queryEqIds[_id].cmds) {
            thisCmd = queryEqIds[_id].cmds[_cmd]
            if (thisGen.type.toLowerCase() != thisCmd.type.toLowerCase()) continue
            if (thisGen.subtype.length > 0 && !thisGen.subtype.includes(thisCmd.subtype)) continue
            thisCmd.possibilities.push(thisGen)
          }
        }
      })

      //Find something in possibilities ...
      var thisPoss, match
      for (var _id in queryEqIds) {
        for (var _cmd in queryEqIds[_id].cmds) {
          thisCmd = queryEqIds[_id].cmds[_cmd]
          if (thisCmd.possibilities.length == 0) continue
          if (thisCmd.possibilities.length == 1) {
            thisCmd.queryGeneric = thisCmd.possibilities[0].genkey
            thisCmd.queryGenericName = thisCmd.possibilities[0].name
            continue
          }
          for (var poss in thisCmd.possibilities) {
            thisPoss = thisCmd.possibilities[poss]
            match = false

            if (thisPoss.shortName == thisCmd.name)
              match = true
            else if (thisPoss.shortName.endsWith(thisCmd.name))
              match = true
            else if (thisPoss.subtype.length == 1 && thisPoss.subtype[0] == 'slider' && thisCmd.name.includes('position'))
              match = true
            else if (thisPoss.subtype.length == 1 && thisPoss.subtype[0] == 'slider' && thisCmd.name.includes('lumino'))
              match = true
            else if (thisPoss.subtype.length == 1 && thisPoss.subtype[0] == 'slider' && thisCmd.name.includes('intensi'))
              match = true
            else if (thisPoss.shortName.includes(thisCmd.name))
              match = true
            else if (jeeP.levenshteinDistance(thisPoss.shortName, thisCmd.name) < (thisCmd.name.length / 2) + 1)
              match = true

            if (match) {
              thisCmd.queryGeneric = thisPoss.genkey
              thisCmd.queryGenericName = thisPoss.name
              break
            }
          }
        }
      }

      //_______________modal
      jeeDialog.dialog({
        id: 'md_applyCmdsTypes',
        title: "{{Types Génériques automatiques}} (" + genFamily + ")",
        width: 'calc(80% - 200px)',
        buttons: {
          confirm: {
            label: '<i class="fa fa-check"></i> {{Appliquer}}',
            className: 'success',
            callback: {
              click: function(event) {
                jeeFrontEnd.types.applyModalGeneric()
              }
            }
          },
          cancel: {
            className: 'hidden'
          }
        },
        onClose: function() {
          jeeDialog.get('#md_applyCmdsTypes').destroy()
        },
      })

      var container = document.querySelector('#md_applyCmdsTypes .jeeDialogContent')
      container.setAttribute('data-generic', genFamilyId)
      var inner = '<br/>'
      var eqName, cmdName, thisCmd, thisClass, select
      for (var _id in queryEqIds) {
        inner += '<div class="queryEq" data-id="' + _id + '">'
        eqName = document.querySelector('li.eqLogic[data-id="' + _id + '"]').getAttribute('data-name')
        inner += '<div class="center biggerText">' + eqName + '</div>'
        for (var _cmd in queryEqIds[_id].cmds) {
          thisCmd = queryEqIds[_id].cmds[_cmd]
          // console.log(thisCmd)
          cmdName = document.querySelector('li.cmd[data-id="' + thisCmd.id + '"]').getAttribute('data-name')
          thisClass = thisCmd.type == 'info' ? 'alert-info' : 'alert-warning'

          inner += '<div class="form-group queryCmd" data-id="' + thisCmd.id + '">'
          inner += '<div class="col-xs-3">'
          inner += '<input type = "checkbox" class="cb_selCmd" title = "{{Sélectionner la commande}}" checked> '
          inner += '<label>' + cmdName + '</label>'
          inner += '</div>'
          inner += '<span class="col-xs-5 ' + thisClass + '">' + thisCmd.genericName + '</span>'

          select = jeeP.getSelectCmd(genFamily, thisCmd.type, thisCmd.subtype)
          select = select.replace('<option value="' + thisCmd.queryGeneric + '">', '<option selected value="' + thisCmd.queryGeneric + '">')

          inner += '<div class="col-xs-4">' + select + '</div>'
          inner += '</div>'
        }
        inner += '</div>'
      }

      container.empty().insertAdjacentHTML('beforeend', inner)

    },
    applyModalGeneric: function() {
      var container = document.querySelector('#md_applyCmdsTypes .jeeDialogContent')
      var genFamilyId = container.getAttribute('data-generic')
      var cmd, select, genericName
      container.querySelectorAll('.queryCmd').forEach(_queryCmd => {
        if (_queryCmd.querySelector('.cb_selCmd').checked) {
          select = _queryCmd.querySelector('.modalCmdGenericSelect')
          genericName = select.options[select.selectedIndex].text
          if (genericName != '{{Aucun}}') {
            genericName = jeeP.gen_families[genFamilyId] + jeephp2js.typeStringSep + genericName
          }
          cmd = document.querySelector('div.eqlogicSortable[data-id="' + genFamilyId + '"] li.cmd[data-id="' + _queryCmd.getAttribute('data-id') + '"]')
          cmd.setAttribute('data-changed', '1')
          cmd.setAttribute('data-generic', select.value)
          cmd.querySelector('span.genericType').textContent = genericName
          jeeFrontEnd.modifyWithoutSave = true
        }
      })
      jeeDialog.get('#md_applyCmdsTypes').close()
    },
    //Save:
    saveGenericTypes: function() {
      var eqLogics = []
      var eqGeneric
      document.querySelectorAll('.eqlogicSortable').forEach(_listEqlogic => {
        eqGeneric = _listEqlogic.getAttribute('data-id')
        if (eqGeneric == 'none') {
          eqGeneric = null
        }
        _listEqlogic.querySelectorAll('li.eqLogic').forEach(_eqlogic => {
          if (_eqlogic.getAttribute('data-changed') == '0') return true
          eqLogics.push({
            id: _eqlogic.getAttribute('data-id'),
            generic_type: eqGeneric
          })
        })
      })

      if (eqLogics.length > 0) {
        jeedom.eqLogic.setGenericType({
          eqLogics: eqLogics,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
            return false
          }
        })
      }

      //save cmds:
      var cmds = []
      document.querySelectorAll('li.cmd').forEach(_cmd => {
        if (_cmd.getAttribute('data-changed') == '0') return true
        cmds.push({
          id: _cmd.getAttribute('data-id'),
          generic_type: _cmd.getAttribute('data-generic')
        })
      })

      if (cmds.length > 0) {
        jeedom.cmd.multiSave({
          cmds: cmds,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
            return false
          }
        })
      }

      //back to work!
      document.querySelectorAll('li.eqLogic, li.cmd').forEach(_el => {
        _el.setAttribute('data-changed', '0')
      })
      jeeFrontEnd.modifyWithoutSave = false

      jeedomUtils.showAlert({
        message: '{{Types Génériques sauvegardés}}' + ' (equipment: ' + eqLogics.length + ' | command: ' + cmds.length + ')',
        level: 'success'
      })
    },
    removeCmdsGenerics: function(_eqLogics) {
      jeeDialog.confirm('{{Supprimer les types génériques sur les commandes ?}}', function(result) {
        if (result) {
          _eqLogics.forEach(_eqLogic => {
            _eqLogic.querySelectorAll('ul.eqLogicCmds li.cmd').forEach(_cmd => {
              _cmd.setAttribute('data-generic', '')
              _cmd.setAttribute('data-changed', '1')
              _cmd.querySelector('.genericType').textContent = 'None'
            })
          })
          jeeFrontEnd.modifyWithoutSave = true
        }
      })
    }
  }
}

jeeFrontEnd.types.init()

//Sortable:
var sortLists = document.getElementById('genericsContainer').querySelectorAll('.eqLogicSortable')
sortLists.forEach(_group => {
  new Sortable(_group, {
    group: {
      name: 'expressions',
    },
    delay: 50,
    animation: 150,
    draggable: 'li.eqLogic',
    direction: 'vertical',
    handle: '.bt_sortable, .cb_selEqLogic',
    multiDrag: true,
    selectedClass: 'dragSelected',
    avoidImplicitDeselect: true,
    removeCloneOnHide: true,
    onSelect: function(evt) {
      if (!evt.item.querySelector('.cb_selEqLogic').checked)
        setTimeout(function() { evt.item.querySelector('.cb_selEqLogic').checked = true }, 10)
    },
    onDeselect: function(evt) {
      if (evt.item.querySelector('.cb_selEqLogic').checked) {
        setTimeout(function() { evt.item.querySelector('.cb_selEqLogic').checked = false }, 10)
      }
    },
    onAdd: function(evt) {
      let generic = evt.target.closest('.eqlogicSortable').getAttribute('data-id')
      let eqLogics = (evt.items.length > 1) ? evt.items : [evt.item]
      eqLogics.forEach(_eqLogic => {
        _eqLogic.setAttribute('data-generic', generic)
        _eqLogic.setAttribute('data-changed', '1')
      })
      jeeP.setFamiliesNumber()
      jeeP.setQueryButtons()
      jeeFrontEnd.modifyWithoutSave = true

      if (!generic) {
        jeeP.removeCmdsGenerics(eqLogics)
      }
    }
  })
})

//Contextmenu commands:
Object.keys(jeeP.genericsByFamily).forEach(key => {
  Object.keys(jeeP.generics).forEach(genkey => {
    if (jeeP.generics[genkey].family == key) {
      jeeP.genericsByFamily[key][genkey] = {}
      jeeP.genericsByFamily[key][genkey]['genkey'] = genkey
      jeeP.genericsByFamily[key][genkey]['name'] = jeeP.generics[genkey].name
      jeeP.genericsByFamily[key][genkey]['shortName'] = jeeP.generics[genkey].name.replace(key, '').toLowerCase().trim()
      jeeP.genericsByFamily[key][genkey]['type'] = jeeP.generics[genkey].type
      jeeP.genericsByFamily[key][genkey]['subtype'] = jeeP.generics[genkey].subtype == undefined ? [] : jeeP.generics[genkey].subtype
      //jeeP.genericsByFamily[key][genkey]['comment'] = generics[genkey].comment == undefined ? '' : generics[genkey].comment
    }
  })
})
new jeeCtxMenu({
  selector: "li.cmd",
  appendTo: 'div#div_pageContainer',
  build: function(trigger) {
    trigger.addClass('hover')
    var eqGeneric = trigger.closest('.eqlogicSortable').getAttribute('data-id')
    var cmdId = trigger.getAttribute('data-id')
    var cmdType = trigger.getAttribute('data-type')
    var cmdSubType = trigger.getAttribute('data-subType')

    var contextmenuitems = {}
    if (trigger.getAttribute('data-generic')) {
      contextmenuitems['deleteme'] = { 'name': '{{Supprimer}}', 'id': 'delete_me' }
      contextmenuitems['sep1'] = "---------"
    }

    var items
    var uniqId = 0
    for (var group in jeeP.genericsByFamily) {
      items = {}
      for (var i in jeeP.genericsByFamily[group]) {
        if (jeeP.genericsByFamily[group][i].type.toLowerCase() == cmdType.toLowerCase() && (jeeP.genericsByFamily[group][i].subtype.includes(cmdSubType) || jeeP.genericsByFamily[group][i].subtype.length == 0)) {
          items[uniqId] = {
            //'name': '<span title="'+jeeP.genericsByFamily[group][i].comment+'">'+jeeP.genericsByFamily[group][i].name+'</span>',
            'name': jeeP.genericsByFamily[group][i].name,
            'id': group + '::' + jeeP.genericsByFamily[group][i].genkey,
            //'isHtmlName': true
          }
          uniqId++
        }
      }
      if (Object.keys(items).length > 0) {
        if (group == jeeP.gen_families[eqGeneric]) {
          group = "<b>" + group + "</b>"
        }
        contextmenuitems[group] = {
          'name': group,
          'isHtmlName': true,
          'items': items
        }
      }
    }

    return {
      callback: function(key, options) {
        if (options.commands[key].id == 'delete_me') {
          document.querySelector('li.cmd[data-id="' + cmdId + '"] .genericType').textContent = 'None'
          document.querySelector('li.cmd[data-id="' + cmdId + '"]').setAttribute('data-generic', '')
        } else {
          //var text = options.commands[key].id.split('::')[0] + jeephp2js.typeStringSep + options.commands[key].node.innerText
          var text = options.commands[key].id.split('::')[0] + jeephp2js.typeStringSep + options.commands[key].name
          document.querySelector('li.cmd[data-id="' + cmdId + '"] .genericType').textContent = text
          document.querySelector('li.cmd[data-id="' + cmdId + '"]').setAttribute('data-generic', options.commands[key].id.split('::')[1])
        }
        document.querySelector('li.cmd[data-id="' + cmdId + '"]').setAttribute('data-changed', '1')
        jeeFrontEnd.modifyWithoutSave = true
      },
      items: contextmenuitems
    }
  },
  events: {
    hide: function(event) {
      document.querySelectorAll('li.cmd.hover').removeClass('hover')
    }
  }
})

//Contextmenu Equipments:
new jeeCtxMenu({
  selector: "li.eqLogic",
  appendTo: 'div#div_pageContainer',
  build: function(trigger) {
    trigger.addClass('hover')
    var eqGeneric = trigger.closest('.eqlogicSortable').getAttribute('data-id')
    var eqIds = [trigger.getAttribute('data-id')]
    trigger.closest('ul.eqLogicSortable').querySelectorAll('li.eqLogic.dragSelected').forEach(_hdl => {
      eqIds.push(_hdl.getAttribute('data-id'))
    })
    eqIds = [...new Set(eqIds)]

    var contextmenuitems = {}
    contextmenuitems['none'] = { 'name': '{{Aucun}}', 'id': 'none' }
    for (var group in jeeP.gen_families) {
      contextmenuitems[group] = {
        'name': jeeP.gen_families[group],
        'id': group
      }
    }

    return {
      callback: function(key, options) {
        var dataGeneric = options.commands[key].id
        if (dataGeneric == 'none') dataGeneric = ''
        var eqLogics = []
        for (var idx in eqIds) {
          let eqlogic = document.querySelector('li.eqLogic[data-id="' + eqIds[idx] + '"]')
          eqLogics.push(eqlogic)
          eqlogic.setAttribute('data-generic', dataGeneric)
          eqlogic.setAttribute('data-changed', '1')
          document.querySelector('#gen_' + dataGeneric + ' ul.eqLogicSortable').appendChild(eqlogic)
        }
        jeeP.setFamiliesNumber()
        jeeP.setQueryButtons()
        jeeFrontEnd.modifyWithoutSave = true

        if (!dataGeneric) {
          jeeP.removeCmdsGenerics(eqLogics)
        }
      },
      items: contextmenuitems
    }
  },
  events: {
    hide: function(event) {
      document.querySelectorAll('li.eqLogic.hover').removeClass('hover')
    }
  }
})

//searching:
document.getElementById('in_searchTypes')?.addEventListener('keyup', function(event) {
  try {
    var search = this.value
    var searchID = search
    if (isNaN(search)) searchID = false

    document.querySelectorAll('#genericsContainer .accordion-toggle').forEach(_panel => { _panel.setAttribute('data-show', 0) })
    document.querySelectorAll('.eqLogic').seen()
    document.querySelectorAll('.eqLogicCmds').unseen()

    if (search == '') {
      document.querySelectorAll('#genericsContainer .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
      return
    }

    search = jeedomUtils.normTextLower(search)
    var eqParent, eqId
    var eqName, type, category
    document.querySelectorAll('.eqLogic').forEach(_eqlogic => {
      eqParent = _eqlogic.closest('.panel.panel-default')
      if (searchID) {
        eqId = _eqlogic.getAttribute('data-id')
        if (eqId != searchID) {
          _eqlogic.unseen()
        } else {
          _eqlogic.closest('.panel-collapse').setAttribute('data-show', '1')
          return
        }
      } else {
        eqName = jeedomUtils.normTextLower(_eqlogic.getAttribute('data-name'))
        type = jeedomUtils.normTextLower(_eqlogic.getAttribute('data-type'))
        category = jeedomUtils.normTextLower(_eqlogic.getAttribute('data-translate-category'))
        if (!eqName.includes(search) && !type.includes(search) && !category.includes(search)) {
          _eqlogic.unseen()
        } else {
          _eqlogic.closest('.panel').querySelector('.accordion-toggle').setAttribute('data-show', 1)
        }
      }
    })
    document.querySelectorAll('.accordion-toggle.collapsed[data-show="1"]').forEach(_panel => { _panel.click() })
    document.querySelectorAll('.accordion-toggle:not(.collapsed)[data-show="0"]').forEach(_panel => { _panel.click() })
  } catch (error) {
    console.warn(error)
  }
})

/*Events delegations
*/
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
  var _target = null
  if (_target = event.target.closest('#bt_openAll')) {
    document.querySelectorAll('#genericsContainer .accordion-toggle.collapsed').forEach(_panel => { _panel.click() })
    if (event.ctrlKey || event.metaKey) {
      document.querySelectorAll('ul.eqLogicCmds').seen()
    }
    return
  }

  if (_target = event.target.closest('#bt_closeAll')) {
    document.querySelectorAll('#genericsContainer .accordion-toggle:not(.collapsed)').forEach(_panel => { _panel.click() })
    if (event.ctrlKey || event.metaKey) {
      document.querySelectorAll('ul.eqLogicCmds').unseen()
    }
    return
  }

  if (_target = event.target.closest('#bt_resetypeSearch')) {
    document.getElementById('in_searchTypes').jeeValue('').triggerEvent('keyup')
    document.querySelectorAll('.cb_selEqLogic').forEach(_check => {
      if (_check.checked) {
        _check.checked = false
        Sortable.utils.deselect(_check.closest('li.eqLogic.dragSelected'))
      }
    })
    return
  }

  if ((_target = event.target.matches('li.eqLogic') || (_target = event.target.matches('li.eqLogic span.eqName')))) {
    _target = event.target.closest('li.eqLogic')
    var el = _target.querySelector('ul.eqLogicCmds')
    if (el?.isVisible()) {
      el?.unseen()
    } else {
      el?.seen()
    }
    return
  }

  if (_target = event.target.closest('.bt_resetCmdsTypes')) {
    var eqLogics = []
    _target.closest('ul.eqLogicSortable').querySelectorAll('li.eqLogic').forEach(_handle => {
      if (_handle.hasClass('dragSelected') || _handle == _target.closest('li.eqLogic')) {
        eqLogics.push(_handle)
      }
    })
    jeeP.removeCmdsGenerics(eqLogics)
    return
  }

  if (_target = event.target.closest('.bt_queryCmdsTypes')) {
    jeeP.queryCmdsTypes(_target)
    return
  }

  if (_target = event.target.closest('.cb_selCmd')) {
    var state = _target.checked
    if (event.ctrlKey) {
      _target.closest('.queryEq').querySelectorAll('.cb_selCmd').forEach(_selCmd => {
        _selCmd.checked = state
      })
    }
    return
  }

  if (_target = event.target.closest('#bt_saveGenericTypes')) {
    jeeP.saveGenericTypes()
    return
  }

  if (_target = event.target.closest('#bt_listGenericTypes')) {
    jeeP.listGenericTypes()
    return
  }
})
