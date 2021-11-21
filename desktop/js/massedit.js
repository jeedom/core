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

var _filterType_
var _editIds_ = []

$(function() {
  _filterType_ = $('#sel_FilterByType').value()
  setEdit()
  $('.selectEditKey').change()
})

//change filter type:
$('#sel_FilterByType').off('change').on('change', function() {
  resetUI()
  _filterType_ = $(this).value()
  setEdit()
})

function resetUI() {
  $('#filter').empty()
  $('#bt_testFilter').addClass('disabled')
  $('#testSQL').empty()
  $('#testResult').empty().hide()
  $('#edit').empty()
  $('#execSQL').empty()
}

//add filter:
$('#bt_addFilter').off('click').on('click', function() {
  addFilter()
  $('#testResult').empty().hide()
  $('#testSQL').empty()
})

function addFilter() {
  $('#bt_testFilter').removeClass('disabled')
  var newFilterHtml = ''
  newFilterHtml += '<div class="form-group filter">'

  newFilterHtml += '<div class="col-md-2 col-xs-3">'
  newFilterHtml += '<select class="selectFilterKey form-control input-sm">'
  var keys = Object.keys(typePossibilities[_filterType_])
  keys.forEach((key, index) => {
    newFilterHtml += '<option value="' + key + '">' + key + '</option>'
  })
  newFilterHtml += '</select>'
  newFilterHtml += '</div>'

  newFilterHtml += '<div class="col-md-3 col-xs-3">'
  newFilterHtml += '<select class="selectFilterValue form-control input-sm"></select>'
  newFilterHtml += '</div>'

  newFilterHtml += '<div class="col-md-3 col-xs-3">'
  newFilterHtml += '<select class="selectFilterJValue form-control input-sm" disabled></select>'
  newFilterHtml += '</div>'

  newFilterHtml += '<div class="col-md-1 col-xs-2">'
  newFilterHtml += '<a class="btn btn-xs btn-warning bt_removeFilter" title="{{Supprimer ce filtre}}"><i class="fas fa-minus"></i></a>'
  newFilterHtml += '</div>'

  newFilterHtml += '</div>'

  var newFilter = $(newFilterHtml)
  $('#filter').append(newFilter)
  newFilter.find('.selectFilterKey').change()
  return newFilter
}

//remove filter:
$('body').on({
  'click': function(event) {
    $(this).closest('div.form-group').remove()
    $('#testResult').empty().hide()
    if ($('.form-group.filter').length == 0) {
      $('#bt_testFilter').addClass('disabled')
    }
  }
}, 'a.bt_removeFilter')

//change filter key:
$('body').on({
  'change': function(event) {
    var key = $(this).value()
    var selectValues = $(this).closest('div.form-group').find('select.selectFilterValue')
    var selectJValues = $(this).closest('div.form-group').find('select.selectFilterJValue')
    selectValues.empty()
    selectJValues.empty()

    //set possible values for key
    var option
    if (typeof typePossibilities[_filterType_][key][0] != 'undefined') {
      selectJValues.prop('disabled', 'disabled')
      typePossibilities[_filterType_][key].forEach(function(item, index) {
        option = $("<option></option>").attr("value", index).text(item)
        selectValues.append(option)
      })
    } else {
      selectJValues.prop('disabled', false)
      var values = Object.keys(typePossibilities[_filterType_][key])
      values.forEach((value, index) => {
        option = $("<option></option>").attr("value", index).text(value)
        selectValues.append(option)
      })
      selectValues.change()
    }

  }
}, 'select.selectFilterKey')

//change filter value:
$('body').on({
  'change': function(event) {
    $('#testSQL').empty()
    $('#testResult').empty().hide()

    var selectKey = $(this).closest('div.form-group').find('select.selectFilterKey')
    var key = selectKey.value()
    var value = $("option:selected", $(this)).text()
    var selectJValues = $(this).closest('div.form-group').find('select.selectFilterJValue')
    selectJValues.empty()

    //does have json value ?
    if (selectJValues.is(':disabled')) return false

    //set json values for filter:
    var option
    var jValues = Object.keys(typePossibilities[_filterType_][key][value])
    jValues.forEach((jValue, index) => {
      option = $("<option></option>").attr("value", index).text(typePossibilities[_filterType_][key][value][index])
      selectJValues.append(option)
    })
  }
}, 'select.selectFilterValue')


//change edit key:
function setEdit() {
  var newEditHtml = ''
  newEditHtml += '<div class="edit">'

  newEditHtml += '<div class="col-md-2 col-xs-3">'
  newEditHtml += '<select class="selectEditKey form-control input-sm">'
  var keys = Object.keys(typePossibilities[_filterType_])
  keys.forEach((key, index) => {
    newEditHtml += '<option value="' + key + '">' + key + '</option>'
  })
  newEditHtml += '</select>'
  newEditHtml += '</div>'

  var editId = Math.random().toString(36).substr(2, 9)
  newEditHtml += '<div class="col-md-3 col-xs-3">'
  newEditHtml += '<input class="selectEditValue form-control input-sm" type="text" value="" list="' + editId + '_EditvaluesList" />'
  newEditHtml += '<datalist id="' + editId + '_EditvaluesList">'
  newEditHtml += '<option></option>'
  newEditHtml += '</datalist>'
  newEditHtml += '</div>'

  newEditHtml += '<div class="col-md-3 col-xs-3">'
  newEditHtml += '<input class="inputEditJValue form-control input-sm" type="text" value="" list="' + editId + '_EditJValuesList" disabled />'
  newEditHtml += '<datalist id="' + editId + '_EditJValuesList">'
  newEditHtml += '<option></option>'
  newEditHtml += '</datalist>'
  newEditHtml += '</div>'

  newEditHtml += '</div>'

  $('#edit').append($(newEditHtml))
}

//change edit key:
$('body').on({
  'change': function(event) {
    var value = $(this).value()
    $(this).closest('div.form-group').find('input.selectEditValue').val('')
    var editValueId = $(this).closest('div.form-group').find('input.selectEditValue').attr('list')
    var inputJValue = $(this).closest('div.form-group').find('input.inputEditJValue')
    inputJValue.val('')

    //set possible values for key if necessary
    var inputValues = $(this).closest('div.form-group').find('#' + editValueId)
    inputValues.empty()
    var key = $(this).value()
    var option
    if (typeof typePossibilities[_filterType_][key][0] != 'undefined') {
      inputJValue.prop('disabled', 'disabled')
    } else {
      inputJValue.prop('disabled', false)
      var values = Object.keys(typePossibilities[_filterType_][key])
      values.forEach((value, index) => {
        option = $("<option></option>").attr("value", value).text(value)
        inputValues.append(option)
      })
      inputValues.change()
    }
  }
}, 'select.selectEditKey')

//change edit value:
$('body').on({
  'change': function(event) {
    var key = $(this).closest('div.form-group').find('select.selectEditKey').val()
    var value = $(this).closest('div.form-group').find('input.selectEditValue').val()

    if (!isset(typePossibilities[_filterType_][key][value])) {
      return false
    }

    var inputJValue = $(this).closest('div.form-group').find('input.inputEditJValue')
    inputJValue.val('')
    //set possible json values for value:
    var editJValueId = inputJValue.attr('list')
    var inputJValues = $(this).closest('div.form-group').find('#' + editJValueId)
    inputJValues.empty()

    var jValues = typePossibilities[_filterType_][key][value]
    if (!jValues || typeof jValues == 'string') return false
    var option
    jValues.forEach((jValue, index) => {
      option = $("<option></option>").attr("value", jValue).text(jValue)
      inputJValues.append(option)
    })
  }
}, 'input.selectEditValue')



//open test items:
$('body').on({
  'click': function(event) {
    event.preventDefault()
    event.stopPropagation()
    event.stopImmediatePropagation()
    var thisId = $(this).attr('data-id')
    jeedom[_filterType_]['byId']({
      id: thisId,
      error: function(error) {
        $.fn.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(_item) {
        if (isset(_item.result)) _item = _item.result

        if (_filterType_ == 'eqLogic') {
          var url = 'index.php?v=d&p=' + _item.eqType_name + '&m=' + _item.eqType_name + '&id=' + _item.id
          window.open(url)
          return true
        }
        if (_filterType_ == 'cmd') {
          $('#md_modal').dialog({
            title: "{{Configuration de la commande}}"
          }).load('index.php?v=d&modal=cmd.configure&cmd_id=' + _item.id).dialog('open')
          return true
        }
        if (_filterType_ == 'object') {
          var url = 'index.php?v=d&p=object&id=' + _item.id
          window.open(url)
          return true
        }
        if (_filterType_ == 'scenario') {
          var url = 'index.php?v=d&p=scenario&id=' + _item.id
          window.open(url)
          return true
        }
      }
    })
  }
}, '.testSqlDiv')


//_____Getters
function getFilters() {
  var filters = []
  $('.form-group.filter').each(function(index) {
    var key = $(this).find('.selectFilterKey').value()
    var value = $("option:selected", $(this).find('.selectFilterValue')).text()
    var jValue = false
    if (!$(this).find('.selectFilterJValue').is(':disabled')) {
      var jValue = $("option:selected", $(this).find('.selectFilterJValue')).text()
    }
    filters.push({
      'key': key,
      'value': value,
      'jValue': jValue
    })
  })
  return filters
}

function getEdits() {
  var edits = []
  $('#edit > .edit').each(function(index) {
    var key = $(this).find('.selectEditKey').value()
    var value = $(this).find('.selectEditValue').val()
    var jValue = false
    if (!$(this).find('.inputEditJValue').is(':disabled')) {
      var jValue = $(this).find('.inputEditJValue').val()
    }
    edits.push({
      'key': key,
      'value': value,
      'jValue': jValue
    })
  })
  return edits
}

function getTestSQLstring(_filters) {
  var sqlTable = _filterType_
  var sqlCmd = ''
  sqlCmd = 'SELECT id, name FROM `' + sqlTable + '`'
  for (var i = 0; i < _filters.length; i++) {
    if (i == 0) {
      sqlCmd += ' WHERE '
    } else {
      sqlCmd += ' AND '
    }
    if (_filters[i].jValue) {
      sqlCmd += 'JSON_CONTAINS(`' + _filters[i].key + '`, \'{"' + _filters[i].value + '" : "' + _filters[i].jValue + '"}\')'
    } else {
      sqlCmd += '`' + _filters[i].key + '` = "' + _filters[i].value + '"'
    }
  }
  return sqlCmd
}

function getExecSQLstring(_filters, _edits) {
  var sqlTable = _filterType_
  var sqlCmd = ''

  var condition = 'WHERE' + getTestSQLstring(_filters).split('WHERE')[1]

  //only support one edit at that time:
  var edit = _edits[0]
  if (edit.jValue) {
    sqlCmd = 'UPDATE `' + sqlTable + '`'
    sqlCmd += ' SET `' + edit.key + '` = JSON_REPLACE(`' + edit.key + '`, "' + "$." + edit.value + '", "' + edit.jValue + '")'
  } else {
    sqlCmd = 'UPDATE `' + sqlTable + '`'
    sqlCmd += ' SET `' + edit.key + '` = "' + edit.value + '"'
  }

  sqlCmd += ' ' + condition
  //sqlCmd += ' ' + condition + ' RETURNING `' + sqlTable + '.id`'

  return sqlCmd
}

function getCleaningSpaceSQLstring(_edits) {
  var sqlTable = _filterType_
  var sqlCmd = ''

  var condition = 'WHERE id in (' + _editIds_.join(', ') + ')'

  var edit = _edits[0]
  if (edit.jValue) {
    sqlCmd = 'UPDATE `' + sqlTable + '`'
    sqlCmd += ' SET `' + edit.key + '` = REPLACE(`' + edit.key + '`, ": ", ":")'
  } else {
    sqlCmd = 'UPDATE `' + sqlTable + '`'
    sqlCmd += ' SET `' + edit.key + '` = "' + edit.value + '"'
  }

  sqlCmd += ' ' + condition

  return sqlCmd
}

function dbExecuteCommand(_command, _mode = 0) { // _mode 0: test, 1: exec, 2: get modified ids
  //console.log('___dbExecuteCommand: ' + _mode + ' -> ' + _command)
  jeedom.db({
    async: false,
    command: _command,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(result) {
      $('#testResult').empty().show()
      if (_mode == 0) {
        for (var i in result.sql) {
          $('#testResult').append('<div class="btn btn-xs btn-primary testSqlDiv" data-id="' + result.sql[i].id + '" style="margin:3px;">' + result.sql[i].name + ' (' + result.sql[i].id + ')' + '</div>')
        }
      }

      if (_mode == 1) {

      }

      if (_mode == 2) {
        _editIds_ = result.sql.map(function(d) {
          return d['id']
        })
      }
    }
  })
}

function downloadObjectAsJson(exportObj, exportName) {
  var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj))
  var downloadAnchorNode = document.createElement('a')
  downloadAnchorNode.setAttribute("href", dataStr)
  downloadAnchorNode.setAttribute("target", "_blank")
  downloadAnchorNode.setAttribute("download", exportName + ".json")
  document.body.appendChild(downloadAnchorNode) // required for firefox
  downloadAnchorNode.click()
  downloadAnchorNode.remove()
}

//page buttons:
$('#bt_exportFilter').off('click').on('click', function() {
  var filters = getFilters()
  var edits = getEdits()
  var jsonData = {
    'type': _filterType_,
    'filters': filters,
    'edits': edits
  }
  downloadObjectAsJson(jsonData, 'jeedom_massEdit_' + new Date().toISOString().slice(0, 10))
})

$("#bt_importFilter").change(function(event) {
  var uploadedFile = event.target.files[0]
  if (uploadedFile.type !== "application/json") {
    $.fn.showAlert({
      message: "{{L'import d'édition en masse se fait au format json.}}",
      level: 'danger'
    })
    return false
  }
  if (uploadedFile) {
    var readFile = new FileReader()
    readFile.readAsText(uploadedFile)
    readFile.onload = function(e) {
      var massEditData = JSON.parse(e.target.result)
      var newFilter
      try {
        //type:
        $('#sel_FilterByType').val(massEditData.type).change()

        //filters:
        for (var idx in massEditData.filters) {
          newFilter = addFilter()
          newFilter.find('.selectFilterKey').val(massEditData.filters[idx].key).change()
          newFilter.find('.selectFilterValue option:contains(' + massEditData.filters[idx].value + ')').attr('selected', 'selected')
          newFilter.find('.selectFilterValue').change()
          if (massEditData.filters[idx].jValue != false) {
            newFilter.find('.selectFilterJValue').prop('disabled', false)
            newFilter.find('.selectFilterJValue option:contains(' + massEditData.filters[idx].jValue + ')').attr('selected', 'selected')
          }
        }

        //edits:
        for (var idx in massEditData.edits) {
          $('.selectEditKey').val(massEditData.edits[idx].key).change()
          $('.selectEditValue').val(massEditData.edits[idx].value)
          if (massEditData.edits[idx].jValue != false) {
            $('.inputEditJValue').prop('disabled', false).val(massEditData.edits[idx].jValue)
          }
        }

      } catch (error) {
        console.error(error)
      }
    }
  } else {
    $.fn.showAlert({
      message: "{{Problème lors de la lecture du fichier.}}",
      level: 'danger'
    })
    return false
  }
})

$('#bt_testFilter').off('click').on('click', function() {
  var filters = getFilters()
  var sqlCmd = getTestSQLstring(filters)
  $('#testSQL').empty().append(sqlCmd)
  dbExecuteCommand(sqlCmd, 0)
})

$('#bt_execMassEdit').off('click').on('click', function() {
  var filters = getFilters()
  var edits = getEdits()

  //get ids of modifying items to clean spaces in json string later.
  var sqlCmd = getTestSQLstring(filters)
  dbExecuteCommand(sqlCmd, 2)

  //exec user edition:
  sqlCmd = getExecSQLstring(filters, edits)
  $('#execSQL').empty().append(sqlCmd + '<br>' + 'Editing items: ' + _editIds_.length)
  dbExecuteCommand(sqlCmd, 1)

  //clean spaces:
  //may integrate later json_search in searchconfiguration functions
  if (sqlCmd.includes('JSON_REPLACE') && _editIds_.length > 0) {
    sqlCmd = getCleaningSpaceSQLstring(edits)
    dbExecuteCommand(sqlCmd, 1)
  }

  _editIds_ = []

})