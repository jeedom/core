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
    },
    setFamiliesNumber: function() {
      var ul
      $('span.spanNumber').each(function() {
        ul = $(this).closest('.eqlogicSortable').find('ul.eqLogicSortable')
        $(this).text(' (' + ul.find('li.eqLogic').length + ')')
      })
    },
    setQueryButtons: function() {
      $('div.eqlogicSortable').each(function() {
        if (!Object.keys(jeeP.gen_families).includes($(this).attr('data-id'))) {
          $(this).find('.bt_queryCmdsTypes').addClass('hidden')
        } else {
          $(this).find('.bt_queryCmdsTypes').removeClass('hidden')
        }

      })
    },
    getSelectCmd: function(_family='', _type, _subtype, _none=true) {
      var htmlSelect = '<select class="modalCmdGenericSelect input-xs">'
      if (_none) htmlSelect += '<option value="">{{Aucun}}</option>'
      for (var group in this.genericsByFamily) {
        if (_family != '' && group != _family) continue
          for (var i in this.genericsByFamily[group]) {
            if (this.genericsByFamily[group][i].type.toLowerCase() == _type.toLowerCase() && (this.genericsByFamily[group][i].subtype.includes(_subtype) || this.genericsByFamily[group][i].subtype.length == 0) ) {
              htmlSelect += '<option value="' + this.genericsByFamily[group][i].genkey + '">' + this.genericsByFamily[group][i].name + '</option>'
            }
          }
        }
        htmlSelect += '</select>'
        return htmlSelect
    },
    levenshteinDistance: function(str1='', str2='') {
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
       return track[str2.length][str1.length];
    },
    compareGenericName: function(a, b) {
      if ( a.name < b.name ){
        return -1;
      }
      if ( a.name > b.name ){
        return 1;
      }
      return 0;
    },
    //Modal apply:
    applyModalGeneric: function() {
      var container = $('#md_applyCmdsTypes .jeeDialogContent')
      var genFamilyId = container.attr('data-generic')
      var queryEq, genericName
      container.find('.queryCmd').each(function() {
        queryEq = $(this)
        if ($(this).find('.cb_selCmd').is(':checked')) {
          genericName = $(this).find('.modalCmdGenericSelect option:selected').text()
          if (genericName != '{{Aucun}}') genericName = genFamilyId + jeephp2js.typeStringSep + genericName
          $('div.eqlogicSortable[data-id="' + genFamilyId + '"] li.cmd[data-id="' + $(this).attr('data-id') + '"]').attr({
            'data-changed': '1',
            'data-generic': $(this).find('.modalCmdGenericSelect').val()
          }).find('span.genericType').text(genericName)
          jeeFrontEnd.modifyWithoutSave = true
        }
      })
      jeeDialog.get('#md_applyCmdsTypes').close()
    }
  }
}

jeeFrontEnd.types.init()

jeeP.setQueryButtons()

//searching:
$('#in_searchTypes').on('keyup', function() {
  try {
    var search = this.value
    var searchID = search
    if (isNaN(search)) searchID = false

    $('.panel-collapse').attr('data-show', 0)
    $('.eqLogic').show()
    $('.eqLogicCmds').hide()

    if (search == '') {
      $('.panel-collapse.in').closest('.panel').find('.accordion-toggle').click()
      return
    }

    search = jeedomUtils.normTextLower(search)
    var eqLogic, eqParent, eqId
    var eqName, type, category, cmdName
    $('.eqLogic').each(function() {
      eqLogic = $(this)
      eqParent = eqLogic.parents('.panel.panel-default').first()
      if (searchID) {
        eqId = eqLogic.attr('data-id')
        if (eqId != searchID) {
          eqLogic.hide()
        } else {
          eqLogic.parents('.panel-collapse').attr('data-show', 1)
          return
        }
      } else {
        eqName = jeedomUtils.normTextLower(eqLogic.attr('data-name'))
        type = jeedomUtils.normTextLower(eqLogic.attr('data-type'))
        category = jeedomUtils.normTextLower(eqLogic.attr('data-translate-category'))
        if (eqName.indexOf(search) < 0 && type.indexOf(search) < 0 && category.indexOf(search) < 0) {
          eqLogic.hide()
        } else {
          eqLogic.parents('.panel-collapse').attr('data-show', 1)
        }
      }
    })
    $('.panel-collapse[data-show=1]').collapse('show')
    $('.panel-collapse[data-show=0]').collapse('hide')
  } catch (error) {
    console.error(error)
  }
})
$('#bt_resetypeSearch').on('click', function() {
  $('#in_searchTypes').val('').keyup()
  $('.cb_selEqLogic').prop("checked", false)
})
$('#bt_openAll').off('click').on('click', function(event) {
  $(".accordion-toggle[aria-expanded='false']").click()
  if (event.ctrlKey || event.metaKey) {
    $('ul.eqLogicCmds').show()
  }
})
$('#bt_closeAll').off('click').on('click', function(event) {
  $(".accordion-toggle[aria-expanded='true']").click()
  if (event.ctrlKey || event.metaKey) {
    $('ul.eqLogicCmds').hide()
  }
})

//Sorting:
$('.eqLogicSortable').sortable({
  cursor: "move",
  connectWith: ".eqLogicSortable",
  zIndex: 0,
  delay: 250,
  opacity: 0.5,
  start: function(event, ui) {
    //get checked eqlogics in this object:
    $(this).closest('ul.eqLogicSortable').find('.ui-sortable-handle').each(function() {
      if ($(this).find('.cb_selEqLogic').prop('checked') == true) {
        $(this).appendTo(ui.item)
      }
    })
    jeeP.sortFromGenericId = ui.item.closest('.eqlogicSortable').attr('data-id')
  },
  stop: function(event, ui) {
    var genericId = ui.item.closest('.eqlogicSortable').attr('data-id')

    //register moved eqLogics ids:
    var eqLogicsIds = []
    eqLogicsIds.push(ui.item.attr('data-id'))
    ui.item.find('li.eqLogic').each(function(index) {
      ui.item.after($(this))
      eqLogicsIds.push($(this).attr('data-id'))
    })

    eqLogicsIds.forEach(function(_id) {
      $('li.eqLogic[data-id="' + _id + '"').attr({
          'data-generic': genericId,
          'data-changed': '1'
        })
    })

    //reset types on commands ?
    if (jeeP.sortFromGenericId != genericId && genericId == '') {
      jeeDialog.confirm({
        message: "{{Supprimer les types generiques sur les commandes ?}}",
        buttons: {
          confirm: {
            label: '{{Oui}}',
            className: 'btn-success'
          },
          cancel: {
            label: '{{Non}}',
            className: 'btn-danger'
          }
        },
        callback: function(result) {
          if (result) {
            eqLogicsIds.forEach(function(_id) {
              $('li.eqLogic[data-id="' + _id + '"').find('li.cmd').attr({
                'data-generic': '',
                'data-changed': '1'
              }).find('.genericType').text('None')
            })
          }
        }
      })
    }

    $(event.originalEvent.target).click()
    event.stopPropagation()
    $('.cb_selEqLogic').prop("checked", false)

    jeeP.setFamiliesNumber()
    jeeP.setQueryButtons()
    jeeFrontEnd.modifyWithoutSave = true
  }
}).disableSelection()


//Handle auto hide context menu
$('#div_pageContainer').on({
  'mouseleave': function(event) {
    $(this).fadeOut().trigger('contextmenu:hide')
  }
}, '.context-menu-root')

//Contextmenu Equipments:
$.contextMenu({
  selector: "li.eqLogic",
  appendTo: 'div#div_pageContainer',
  build: function($trigger) {
    $trigger.addClass('hover')
    var eqGeneric = $trigger.closest('.eqlogicSortable').attr('data-id')
    var eqIds = [$trigger.attr('data-id')]
    $trigger.closest('ul.eqLogicSortable').find('.ui-sortable-handle').each(function() {
      if ($(this).find('.cb_selEqLogic').prop('checked') == true) {
        eqIds.push($(this).attr('data-id'))
      }
    })
    eqIds = [...new Set(eqIds)]

    var contextmenuitems = {}
    contextmenuitems['none'] = {'name': '{{Aucun}}', 'id': 'none'}
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
        for (var idx in eqIds) {
          $('li.eqLogic[data-id="' + eqIds[idx] + '"]').attr({
            'data-generic': dataGeneric,
            'data-changed': '1'
          }).appendTo($('#gen_' + dataGeneric + ' ul.eqLogicSortable'))
        }
        jeeP.setFamiliesNumber()
        jeeP.setQueryButtons()
        jeeFrontEnd.modifyWithoutSave = true
      },
      items: contextmenuitems
    }
  },
  events: {
    hide: function(event) {
      $('li.eqLogic.hover').removeClass('hover')
    }
  }
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
$.contextMenu({
  selector: "li.cmd",
  appendTo: 'div#div_pageContainer',
  build: function($trigger) {
    $trigger.addClass('hover')
    var eqGeneric = $trigger.closest('.eqlogicSortable').attr('data-id')
    var cmdId = $trigger.attr('data-id')
    var cmdType = $trigger.attr('data-type')
    var cmdSubType = $trigger.attr('data-subType')

    var contextmenuitems = {}
    contextmenuitems['deleteme'] = {'name': '{{Supprimer}}', 'id': 'delete_me'}
    contextmenuitems['sep1'] = "---------"

    var items
    var uniqId = 0
    for (var group in jeeP.genericsByFamily) {
      items = {}
      for (var i in jeeP.genericsByFamily[group]) {
        if (jeeP.genericsByFamily[group][i].type.toLowerCase() == cmdType.toLowerCase() && (jeeP.genericsByFamily[group][i].subtype.includes(cmdSubType) || jeeP.genericsByFamily[group][i].subtype.length == 0) ) {
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
          $('li.cmd[data-id="' + cmdId + '"] .genericType').text('None')
          $('li.cmd[data-id="' + cmdId + '"]').attr('data-generic', '')
        } else {
          //var text = options.commands[key].id.split('::')[0] + jeephp2js.typeStringSep + options.commands[key].$node[0].innerText
          var text = options.commands[key].id.split('::')[0] + jeephp2js.typeStringSep + options.commands[key].name
          $('li.cmd[data-id="' + cmdId + '"] .genericType').text(text)
          $('li.cmd[data-id="' + cmdId + '"]').attr('data-generic', options.commands[key].id.split('::')[1])
        }
        $('li.cmd[data-id="' + cmdId + '"]').attr('data-changed', '1')
        jeeFrontEnd.modifyWithoutSave = true
      },
      items: contextmenuitems
    }
  },
  events: {
    hide: function(event) {
      $('li.cmd.hover').removeClass('hover')
    }
  }
})


//UI:
$('.eqLogicSortable > li.eqLogic').on('click', function(event) {
  if (event.target.tagName.toUpperCase() == 'I') return
  //checkbox clicked:
  if (event.target.tagName.toUpperCase() == 'INPUT') return

  if (!$(event.target).hasClass('eqLogic')) {
    event.stopPropagation()
    return false
  }

  var $el = $(this).find('ul.eqLogicCmds')
  if ($el.is(':visible')) {
    $el.hide()
  } else {
    $el.show()
  }
})

$('.bt_resetCmdsTypes').on('click', function(event) {
  $(this).closest('li.eqLogic').find('ul.eqLogicCmds li.cmd').each(function() {
    $(this).attr({
      'data-generic': '',
      'data-changed': '1'
    }).find('.genericType').text('None')
  })
  jeeFrontEnd.modifyWithoutSave = true
})

//Auto apply
$('.bt_queryCmdsTypes').off('click').on('click', function() {
  var genFamilyId = $(this).closest('div.eqlogicSortable').attr('data-id')
  var genFamily = jeeP.gen_families[genFamilyId]

  //Get selected eqLogics and all their cmd data:
  var queryEqIds = {}
  queryEqIds[($(this).closest('li.eqLogic ').attr('data-id'))] = {'cmds': []}
  $(this).closest('ul.eqLogicSortable').find('.ui-sortable-handle').each(function() {
    if ($(this).find('.cb_selEqLogic').prop('checked') == true) {
      queryEqIds[$(this).attr('data-id')] = {'cmds': []}
    }
  })
  var cmd
  for (var _id in queryEqIds) {
    $('li.eqLogic[data-id="' + _id + '"] li.cmd').each(function() {
      cmd = {}
      cmd['id'] = $(this).attr('data-id')
      cmd['name'] = $(this).attr('data-name').toLowerCase().trim()
      cmd['type'] = $(this).attr('data-type')
      cmd['subtype'] = $(this).attr('data-subtype')
      cmd['generic'] = $(this).attr('data-generic')
      cmd['genericName'] = $(this).find('.genericType').text().replace(genFamily + jeephp2js.typeStringSep, '')
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
        else if (jeeP.levenshteinDistance(thisPoss.shortName, thisCmd.name) < (thisCmd.name.length / 2) +1)
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

  var container = $('#md_applyCmdsTypes .jeeDialogContent')
  container.attr('data-generic', genFamilyId)
  var inner = '<br/>'
  var eqName, cmdName, thisCmd, thisClass, select
  for (var _id in queryEqIds) {
    inner += '<div class="queryEq" data-id="' + _id + '">'
    eqName = $('li.eqLogic[data-id="' + _id + '"]').attr('data-name')
    inner += '<div class="center biggerText">' + eqName + '</div>'
    for (var _cmd in queryEqIds[_id].cmds) {
      thisCmd = queryEqIds[_id].cmds[_cmd]
      cmdName = $('li.cmd[data-id="' + thisCmd.id + '"]').attr('data-name')
      thisClass = thisCmd.type == 'info' ? 'alert-info' : 'alert-warning'

      inner += '<div class="form-group queryCmd" data-id="' + thisCmd.id + '">'
      inner += '<label class="col-xs-2">' + cmdName + '</label>'
      inner += '<span class="col-xs-3 ' + thisClass + '">' + thisCmd.genericName + '</span>'

      select = jeeP.getSelectCmd(genFamily, thisCmd.type, thisCmd.subtype)
      select = select.replace('<option value="' + thisCmd.queryGeneric + '">', '<option selected value="' + thisCmd.queryGeneric + '">')

      inner += '<div class="col-xs-6">' + select + '</div>'
      inner += '<div class="col-xs-1"><input type="checkbox" class="cb_selCmd" checked/></div>'
      inner += '</div>'
    }
    inner += '</div>'
  }

  container.empty().append(inner)
})

$('#md_applyCmdsTypes').on({
  'click': function(event) {
    var state = $(this).prop('checked')
    if (event.ctrlKey) {
      $(this).closest('.queryEq').find('.cb_selCmd').each(function(){
        $(this).prop('checked', state)
      })
    }
  }
}, '.cb_selCmd')

$("#bt_saveGenericTypes").off('click').on('click', function(event) {
  //save eqLogics:
  var eqLogics = []
  var eqGeneric, eqId
  $('.eqlogicSortable').each(function() {
    eqGeneric = $(this).attr('data-id')
    if (eqGeneric == 'none') {
      eqGeneric = null
    }
    $(this).find('li.eqLogic').each(function() {
      if ($(this).attr('data-changed') == '0') return true
      eqId = $(this).attr('data-id')
      eqLogics.push({
        id : eqId,
        generic_type : eqGeneric
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
  var cmdGeneric, cmdId
  $('li.cmd').each(function() {
    if ($(this).attr('data-changed') == '0') return true
    cmdGeneric = $(this).attr('data-generic')
    cmdId = $(this).attr('data-id')
    cmds.push({
      id : cmdId,
      generic_type : cmdGeneric
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
  $('li.eqLogic, li.cmd').each(function() {
    $(this).attr('data-changed', '0')
  })
  jeeFrontEnd.modifyWithoutSave = false

  jeedomUtils.showAlert({
    message: '{{Types Génériques sauvegardés}}' + ' (equipment: ' + eqLogics.length + ' | command: ' + cmds.length + ')',
    level: 'success'
  })
})

$('#bt_listGenericTypes').off('click').on('click', function() {
  jeeDialog.dialog({
    id: 'md_applyCmdsTypes',
    title: "{{Liste des Types Génériques (Core et Plugins)}}",
    width: 'calc(80% - 200px)',
    onClose: function() {
      jeeDialog.get('#md_applyCmdsTypes').destroy()
    },
  })

  var container = $('#md_applyCmdsTypes .jeeDialogContent')
  var inner = '<table class="table table-bordered table-condensed">'
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
  container.empty().append(inner)
})

//Register events on top of page container:

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/