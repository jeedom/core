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
      this.sourcesEqContainer = $('#eqSource')
      this.filteredObjects = null
      this.filteredEqlogics = null
      this.replacerEqList = null
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
    getCmdSelect: function(eqlogicId=-1) {

    },
    resetEqlogicSelect: function(eqlogicId=-1) {
      $('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"] div.replacer select').val('').keyup()
    },
    resetCmdSelects: function(eqlogicId=-1) {
      $('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"] li.cmd').each(function() {
        $(this).find('select').empty()
      })
    },
    synchEqlogicsReplacers: function() {
      var $eqReplacers = $('#eqSource ul.eqLogic > div.replacer select')
      jeeP.replacerEqList = new Array()
      $eqReplacers.each(function() {
        jeeP.replacerEqList.push($(this).val())
      })

      $('#eqSource ul.eqLogic').each(function() {
        var thisId = $(this).attr('data-id')
        if (jeeP.replacerEqList.includes(thisId)) {
          jeeP.disableEqlogic(thisId)
        } else {
          jeeP.enableEqlogic(thisId)
        }
      })
    },
    enableEqlogic: function(eqlogicId=-1) {
      var $eqEl = $('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"]')
      $eqEl.removeClass('disabled')
    },
    disableEqlogic: function(eqlogicId=-1) {
      var $eqEl = $('#eqSource ul.eqLogic[data-id="' + eqlogicId + '"]')
      $eqEl.find('input.cb_selEqLogic').prop("checked", false)
      $eqEl.find('ul').hide()
      this.resetEqlogicSelect($eqEl.attr('data-id'))
      $eqEl.addClass('disabled')
    }
  }
}

jeeFrontEnd.replace.init()

jeedomUtils.initTooltips()

//searching:
$('#in_searchByName').on('keyup', function() {
  try {
    var search = this.value
    var searchID = search
    if (isNaN(search)) searchID = false

    if (search == '') {
      jeeP.sourcesEqContainer.find('ul.eqLogic').show()
      return
    }

    search = jeedomUtils.normTextLower(search)
    var eqLogic, eqName, eqParent, eqId
    jeeP.sourcesEqContainer.find('ul.eqLogic').each(function() {
      eqLogic = $(this)
      eqParent = jeedomUtils.normTextLower(eqLogic.attr('data-parent'))
      if (searchID) {
        eqId = eqLogic.attr('data-id')
        if (eqId != searchID) {
          eqLogic.hide()
        } else {
          eqLogic.show()
          return
        }
      } else {
        eqName = jeedomUtils.normTextLower(eqLogic.attr('data-name'))
        if (eqName.indexOf(search) < 0) {
          eqLogic.hide()
        } else {
          eqLogic.show()
        }
      }
    })
  } catch (error) {
    console.error(error)
  }
})
$('#bt_resetSearchName').on('click', function() {
  $('#in_searchByName').val('').keyup()
})

//Filters:
//-> object
$('#objectFilter').on('click', function(event) {
  event.stopPropagation()
})
$('#objectFilterNone').on('click', function() {
  $('#objectFilter .objectFilterKey').each(function() {
    $(this).prop('checked', false)
  })
})
$('#objectFilterAll').on('click', function() {
  $('#objectFilter .objectFilterKey').each(function() {
    $(this).prop("checked", true)
  })
})
$('#objectFilter .objectFilterKey').off('mouseup').on('mouseup', function(event) {
  event.preventDefault()
  event.stopPropagation()

  if (event.which == 2) {
    $('#objectFilter li .objectFilterKey').prop("checked", false)
    $(this).prop("checked", true)
  }
  if (event.which != 2) {
    $(this).prop("checked", !$(this).prop("checked"))
  }
})
$('#objectFilter li a').on('mousedown', function(event) {
  event.preventDefault()
  var checkbox = $(this).find('.objectFilterKey')
  if (!checkbox) return
  if (event.which == 2 || event.originalEvent.ctrlKey) {
    if ($('.objectFilterKey:checked').length == 1 && checkbox.is(":checked")) {
      $('#objectFilter li .objectFilterKey').prop("checked", true)
    } else {
      $('#objectFilter li .objectFilterKey').prop("checked", false)
      checkbox.prop("checked", true)
    }
  } else {
    checkbox.prop("checked", !checkbox.prop("checked"))
  }
})
//-> plugin
$('#pluginFilter').on('click', function(event) {
  event.stopPropagation()
})
$('#pluginFilterNone').on('click', function() {
  $('#pluginFilter .pluginFilterKey').each(function() {
    $(this).prop('checked', false)
  })
})
$('#pluginFilterAll').on('click', function() {
  $('#pluginFilter .pluginFilterKey').each(function() {
    $(this).prop("checked", true)
  })
})
$('#pluginFilter .pluginFilterKey').off('mouseup').on('mouseup', function(event) {
  event.preventDefault()
  event.stopPropagation()

  if (event.which == 2) {
    $('#pluginFilter li .pluginFilterKey').prop("checked", false)
    $(this).prop("checked", true)
  }
  if (event.which != 2) {
    $(this).prop("checked", !$(this).prop("checked"))
  }
})
$('#pluginFilter li a').on('mousedown', function(event) {
  event.preventDefault()
  var checkbox = $(this).find('.pluginFilterKey')
  if (!checkbox) return
  if (event.which == 2 || event.originalEvent.ctrlKey) {
    if ($('.pluginFilterKey:checked').length == 1 && checkbox.is(":checked")) {
      $('#pluginFilter li .pluginFilterKey').prop("checked", true)
    } else {
      $('#pluginFilter li .pluginFilterKey').prop("checked", false)
      checkbox.prop("checked", true)
    }
  } else {
    checkbox.prop("checked", !checkbox.prop("checked"))
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
    if ($(event.target).hasClass('cmd')) {
      $(event.target).find('.configureCmd').click()
      return false
    }

    var $el = $(this).find('ul')
    if ($el.is(':visible')) {
      $el.hide()
    } else {
      $el.show()
    }
  }
}, 'ul.eqLogic')

$('#eqSource').on({
  'change': function(event) {
    //get source and target eqLogics Ids:
    var $thisEq = $(this).parents('ul.eqLogic')
    var sourceEqId = $thisEq.attr('data-id')
    var sourceEqName = $thisEq.attr('data-name')
    var targetEqId = $(this).val()
    var targetEqName = $(this).find('option[value="' + targetEqId + '"]').attr('data-name')

    //open cmds ul:
    if ($(this).value() != '' && !$(this).closest('ul.eqLogic').find('ul').is(":visible")) {
      $(this).closest('ul.eqLogic').find('ul').show()
    }

    //Do not replace itself!
    if (sourceEqId == targetEqId) {
      jeedomUtils.showAlert({level: 'warning', message: "{{Vous ne pouvez pas remplacer un équipement par lui même.}}"})
      $(this).val('').keyup()
      jeeP.resetCmdSelects(sourceEqId)
      jeeP.synchEqlogicsReplacers()
      return false
    }

    //Is ever replacing by this eqLogic:
    if ($(this).value() != '' && is_array(jeeP.replacerEqList) && jeeP.replacerEqList.includes(targetEqId)) {
      jeedomUtils.showAlert({level: 'warning', message: "{{Cet équipement remplace déjà un autre équipement.}}"})
      $(this).val('').keyup()
      jeeP.resetCmdSelects(sourceEqId)
      jeeP.synchEqlogicsReplacers()
      return false
    }

    //Same name will throw error when in same object:
    if (sourceEqName == targetEqName) {
      jeedomUtils.showAlert({level: 'warning', message: "{{Vous ne pouvez pas remplacer un équipement par un équipement de même nom.}}"})
      $(this).val('').keyup()
      jeeP.resetCmdSelects(sourceEqId)
      jeeP.synchEqlogicsReplacers()
      return false
    }

    jeeP.synchEqlogicsReplacers()

    //Get over each command to set its select option with target commands list:
    var replacerCmdsInfo = jeephp2js.listCommands.filter(o => o.eqLogic_id == targetEqId && o.type == 'info')
    var replacerCmdsAction = jeephp2js.listCommands.filter(o => o.eqLogic_id == targetEqId && o.type == 'action')
    var cmdsObject = jeephp2js.listCommands.filter(o => o.eqLogic_id == sourceEqId)
    var cmds = $thisEq.find('.cmd')
    var cmdSelect
    cmds.each(function() {
      var type = $(this).attr('data-type')
      if (type == 'info') {
        var optionsCmds = replacerCmdsInfo
      } else {
        var optionsCmds = replacerCmdsAction
      }

      cmdSelect = $(this).find('select')
      cmdSelect.empty()
      var options = '<option value=""></option>'
      var _cmd = jeephp2js.listCommands.filter(o => o.id == $(this).attr('data-id'))[0]
      optionsCmds.forEach(function(optionsCmd) {
        if (_cmd.name.toLowerCase() == optionsCmd.name.toLowerCase() && _cmd.type == optionsCmd.type) {
          options += '<option selected value="' + optionsCmd.id + '">' + optionsCmd.name + '</option>'
        } else {
          options += '<option value="' + optionsCmd.id + '">' + optionsCmd.name + '</option>'
        }
      })
      cmdSelect.append(options)
    })
  }
}, 'ul.eqLogic > .replacer > select')

$('#bt_clearReplace').on('click', function() {
  $('#objectFilter .objectFilterKey').each(function() {
    $(this).prop("checked", true)
  })

  $('#pluginFilter .pluginFilterKey').each(function() {
    $(this).prop("checked", true)
  })

  $('#opt_copyEqProperties').prop("checked", false)
  $('#opt_hideEqs').prop("checked", false)
  $('#opt_copyCmdProperties').prop("checked", false)
  $('#opt_removeHistory').prop("checked", false)
  $('#opt_copyHistory').prop("checked", false)

  jeeP.sourcesEqContainer.empty()
})

$('#bt_applyFilters').on('click', function() {
  jeeP.filteredObjects = new Array()
  var key = null
  $('#objectFilter .objectFilterKey').each(function() {
    if ($(this).is(':checked')) {
      key = $(this).attr('data-key')
      if (key == '') key = null
      jeeP.filteredObjects.push(key)
    }
  })
  var byPlugins = new Array()
  $('#pluginFilter .pluginFilterKey').each(function() {
    if ($(this).is(':checked')) {
      byPlugins.push($(this).attr('data-key'))
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

    jeeP.sourcesEqContainer.append(eqDiv)
  })
})

$('#bt_replace').on('click', function() {
  jeeDialog.confirm({
    message: "<b>{{Il est fortement conseillé de réaliser un backup système avant d'utiliser cet outil !}}</b>" + "<br>" +  "{{Êtes-vous sûr de vouloir Remplacer ces équipements et commandes ?}}",
    callback: function(result) {
      if (result) {
        jeedomUtils.hideAlert()

        var opt_mode = $('#opt_mode').value()
        var opt_copyEqProperties = $('#opt_copyEqProperties').is(':checked')
        var opt_hideEqs = $('#opt_hideEqs').is(':checked')
        var opt_copyCmdProperties = $('#opt_copyCmdProperties').is(':checked')
        var opt_removeHistory = $('#opt_removeHistory').is(':checked')
        var opt_copyHistory = $('#opt_copyHistory').is(':checked')

        var replaceEqs = {}
        var replaceCmds = {}

        $('#eqSource ul.eqLogic').each(function() {
          if ($(this).find('input.cb_selEqLogic').is(":checked")) {
            var sourceEqId = $(this).attr('data-id')
            var targetEqId = $(this).find('> div.replacer select').val()

            if (targetEqId != '') {
              replaceEqs[sourceEqId] = targetEqId
              $(this).find('li.cmd').each(function() {
                var replaceId = $(this).find('select').val()
                if (replaceId != '') {
                  replaceCmds[$(this).attr('data-id')] = replaceId
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
      }
    }
  })
})