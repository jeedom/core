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

if (!jeeFrontEnd.massedit) {
  jeeFrontEnd.massedit = {
    _filterType_: null,
    _editIds_: [],
    init: function() {
      window.jeeP = this
    },
    postInit: function() {
      this._filterType_ = document.getElementById('sel_FilterByType').value
      this._editIds_ = []
      this.setEdit()
    },
    resetUI: function() {
      document.getElementById('filter').empty()
      document.getElementById('bt_testFilter').addClass('disabled')
      document.getElementById('testSQL').empty()
      document.getElementById('testResult').empty().unseen()
      document.getElementById('edit')?.empty()
      document.getElementById('execSQL').empty()
    },
    addFilter: function() {
      document.getElementById('bt_testFilter').removeClass('disabled')
      var newFilterHtml = ''
      newFilterHtml += '<div class="form-group filter">'

      newFilterHtml += '<div class="col-md-2 col-xs-3">'
      newFilterHtml += '<select class="selectFilterKey form-control input-sm">'
      var keys = Object.keys(jeephp2js.typePossibilities[this._filterType_])
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
    },
    setEdit: function() {
      var newEditHtml = ''
      newEditHtml += '<div class="edit">'

      newEditHtml += '<div class="col-md-2 col-xs-3">'
      newEditHtml += '<select class="selectEditKey form-control input-sm">'
      var keys = Object.keys(jeephp2js.typePossibilities[this._filterType_])
      keys.forEach((key, index) => {
        newEditHtml += '<option value="' + key + '">' + key + '</option>'
      })
      newEditHtml += '</select>'
      newEditHtml += '</div>'

      var editId = 'j' + Math.random().toString(36).substr(2, 9)
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
    },
    getFilters: function() {
      var filters = []
      $('.form-group.filter').each(function(index) {
        var key = this.querySelector('.selectFilterKey').value
        var value = this.querySelector('select.selectFilterValue').selectedOptions[0].text
        var jValue = false
        if (!(this.querySelector('.selectFilterJValue').disabled)) {
          var jValue = this.querySelector('select.selectFilterJValue').selectedOptions[0].text
        }
        filters.push({
          'key': key,
          'value': value,
          'jValue': jValue
        })
      })
      return filters
    },
    getEdits: function() {
      var edits = []
      document.querySelectorAll('#edit > .edit').forEach(function(edit) {
        var key = edit.querySelector('select.selectEditKey').value
        var value = edit.querySelector('.selectEditValue').value
        var jValue = false
        if (edit.querySelector('.inputEditJValue').disabled != null) {
          var jValue = edit.querySelector('.inputEditJValue').value
        }
        edits.push({
          'key': key,
          'value': value,
          'jValue': jValue
        })
      })
      return edits
    },
    getTestSQLstring: function(_filters) {
      var sqlTable = this._filterType_
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
    },
    getExecSQLstring: function(_filters, _edits) {
      var sqlTable = this._filterType_
      var sqlCmd = ''

      var condition = 'WHERE' + this.getTestSQLstring(_filters).split('WHERE')[1]

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
    },
    getCleaningSpaceSQLstring: function(_edits) {
      var sqlTable = this._filterType_
      var sqlCmd = ''

      var condition = 'WHERE id in (' + this._editIds_.join(', ') + ')'

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
    },
    dbExecuteCommand: function(_command, _mode = 0) { // _mode 0: test, 1: exec, 2: get modified ids
      jeedom.db({
        async: false,
        command: _command,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(result) {
          document.getElementById('testResult').empty().seen()
          if (_mode == 0) {
            for (var i in result.sql) {
              $('#testResult').append('<div class="btn btn-xs btn-primary testSqlDiv" data-id="' + result.sql[i].id + '" style="margin:3px;">' + result.sql[i].name + ' (' + result.sql[i].id + ')' + '</div>')
            }
          }

          if (_mode == 1) {

          }

          if (_mode == 2) {
            jeeP._editIds_ = result.sql.map(function(d) {
              return d['id']
            })
          }
        }
      })
    },
    downloadObjectAsJson: function(exportObj, exportName) {
      var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj))
      var downloadAnchorNode = document.createElement('a')
      downloadAnchorNode.setAttribute("href", dataStr)
      downloadAnchorNode.setAttribute("target", "_blank")
      downloadAnchorNode.setAttribute("download", exportName + ".json")
      document.body.appendChild(downloadAnchorNode) // required for firefox
      downloadAnchorNode.click()
      downloadAnchorNode.remove()
    },
  }
}

jeeFrontEnd.massedit.init()

jeeFrontEnd.massedit.postInit()
document.querySelectorAll('.selectEditKey').triggerEvent('change')

//change filter type:
$('#sel_FilterByType').off('change').on('change', function() {
  jeeP.resetUI()
  jeeP._filterType_ = this.value
  jeeP.setEdit()
  document.querySelectorAll('.selectEditKey').triggerEvent('change')
})

//add filter:
$('#bt_addFilter').off('click').on('click', function() {
  jeeP.addFilter()
  document.getElementById('testResult').empty().unseen()
  document.getElementById('testSQL').empty()
})

//remove filter:
$('body').on({
  'click': function(event) {
    this.closest('div.form-group').remove()
    document.getElementById('testResult').empty().unseen()
    if ($('.form-group.filter').length == 0) {
      $('#bt_testFilter').addClass('disabled')
    }
  }
}, 'a.bt_removeFilter')

//change filter key:
$('body').on({
  'change': function(event) {
    var key = this.value
    var selectValues = this.closest('div.form-group').querySelector('select.selectFilterValue')
    var selectJValues = this.closest('div.form-group').querySelector('select.selectFilterJValue')
    selectValues.empty()
    selectJValues.empty()

    //set possible values for key
    var newOption
    if (typeof jeephp2js.typePossibilities[jeeP._filterType_][key][0] != 'undefined') {
      selectJValues.disabled = true
      jeephp2js.typePossibilities[jeeP._filterType_][key].forEach(function(item, index) {
        newOption = document.createElement('option')
        newOption.text = item
        newOption.value = index
        selectValues.appendChild(newOption)
      })
    } else {
      selectJValues.removeAttribute('disabled')
      var values = Object.keys(jeephp2js.typePossibilities[jeeP._filterType_][key])
      values.forEach((value, index) => {
        newOption = document.createElement('option')
        newOption.text = value
        newOption.value = index
        selectValues.appendChild(newOption)
      })
      selectValues.triggerEvent('change')
    }

  }
}, 'select.selectFilterKey')

//change filter value:
$('body').on({
  'change': function(event) {
    document.getElementById('testSQL').empty()
    document.getElementById('testResult').empty().unseen()

    var selectKey = this.closest('div.form-group').querySelector('select.selectFilterKey')
    var key = selectKey.value
    var value = this.selectedOptions[0].text
    var selectJValues = this.closest('div.form-group').querySelector('select.selectFilterJValue')
    selectJValues.empty()

    //does have json value ?
    if (selectJValues.disabled) return false

    //set json values for filter:
    var newOption
    var jValues = Object.keys(jeephp2js.typePossibilities[jeeP._filterType_][key][value])
    jValues.forEach((jValue, index) => {
      newOption = document.createElement('option')
      newOption.text = jeephp2js.typePossibilities[jeeP._filterType_][key][value][index]
      newOption.value = index
      selectJValues.appendChild(newOption)
    })
  }
}, 'select.selectFilterValue')

//change edit key:
$('body').on({
  'change': function(event) {
    var value = this.value
    this.closest('div.form-group').querySelector('input.selectEditValue').value = ''
    var editValueId = this.closest('div.form-group').querySelector('input.selectEditValue').getAttribute('list')
    var inputJValue = this.closest('div.form-group').querySelector('input.inputEditJValue')
    inputJValue.value = ''

    //set possible values for key if necessary
    var inputValues = this.closest('div.form-group').querySelector('#' + editValueId)
    inputValues.empty()
    var key = this.value
    var newOption
    if (typeof jeephp2js.typePossibilities[jeeP._filterType_][key][0] != 'undefined') {
      inputJValue.disabled = true
    } else {
      inputJValue.removeAttribute('disabled')
      var values = Object.keys(jeephp2js.typePossibilities[jeeP._filterType_][key])
      values.forEach((value, index) => {
        newOption = document.createElement('option')
        newOption.text = value
        newOption.value = value
        inputValues.appendChild(newOption)
      })
      inputValues.triggerEvent('change')
    }
  }
}, 'select.selectEditKey')

//change edit value:
$('body').on({
  'change': function(event) {
    var key = this.closest('div.form-group').querySelector('select.selectEditKey').value
    var value = this.closest('div.form-group').querySelector('input.selectEditValue').value
    if (!isset(jeephp2js.typePossibilities[jeeP._filterType_][key][value])) {
      return false
    }

    var inputJValue = this.closest('div.form-group').querySelector('input.inputEditJValue')
    inputJValue.value = ''
    //set possible json values for value:
    var editJValueId = inputJValue.getAttribute('list')
    var inputJValues = this.closest('div.form-group').querySelector('#' + editJValueId)
    inputJValues.empty()

    var jValues = jeephp2js.typePossibilities[jeeP._filterType_][key][value]
    if (!jValues || typeof jValues == 'string') return false
    var newOption
    jValues.forEach((jValue, index) => {
      newOption = document.createElement('option')
      newOption.text = jValue
      newOption.value = jValue
      inputJValues.appendChild(newOption)
    })
  }
}, 'input.selectEditValue')

//open test items:
$('body').on({
  'click': function(event) {
    event.preventDefault()
    event.stopPropagation()
    event.stopImmediatePropagation()
    var thisId = this.getAttribute('data-id')
    jeedom[jeeP._filterType_]['byId']({
      id: thisId,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(_item) {
        if (isset(_item.result)) _item = _item.result

        if (jeeP._filterType_ == 'eqLogic') {
          var url = 'index.php?v=d&p=' + _item.eqType_name + '&m=' + _item.eqType_name + '&id=' + _item.id
          window.open(url)
          return true
        }
        if (jeeP._filterType_ == 'cmd') {
          jeeDialog.dialog({
            id: 'jee_modal2',
            title: '{{Configuration de la commande}}',
            contentUrl: 'index.php?v=d&modal=cmd.configure&cmd_id=' + _item.id
          })
          return true
        }
        if (jeeP._filterType_ == 'object') {
          var url = 'index.php?v=d&p=object&id=' + _item.id
          window.open(url)
          return true
        }
        if (jeeP._filterType_ == 'scenario') {
          var url = 'index.php?v=d&p=scenario&id=' + _item.id
          window.open(url)
          return true
        }
      }
    })
  }
}, '.testSqlDiv')


//page buttons:
$('#bt_exportFilter').off('click').on('click', function() {
  var filters = jeeP.getFilters()
  var edits = jeeP.getEdits()
  var jsonData = {
    'type': jeeP._filterType_,
    'filters': filters,
    'edits': edits
  }
  jeeP.downloadObjectAsJson(jsonData, 'jeedom_massEdit_' + new Date().toISOString().slice(0, 10))
})

$("#bt_importFilter").change(function(event) {
  var uploadedFile = event.target.files[0]
  if (uploadedFile.type !== "application/json") {
    jeedomUtils.showAlert({
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
          newFilter = jeeP.addFilter()
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
    jeedomUtils.showAlert({
      message: "{{Problème lors de la lecture du fichier.}}",
      level: 'danger'
    })
    return false
  }
})

$('#bt_testFilter').off('click').on('click', function() {
  var filters = jeeP.getFilters()
  var sqlCmd = jeeP.getTestSQLstring(filters)
  document.getElementById('testSQL').empty().append(sqlCmd)
  jeeP.dbExecuteCommand(sqlCmd, 0)
})

$('#bt_execMassEdit').off('click').on('click', function() {
  var filters = jeeP.getFilters()
  var edits = jeeP.getEdits()

  //get ids of modifying items to clean spaces in json string later.
  var sqlCmd = jeeP.getTestSQLstring(filters)
  jeeP.dbExecuteCommand(sqlCmd, 2)

  //exec user edition:
  sqlCmd = jeeP.getExecSQLstring(filters, edits)
  $('#execSQL').empty().append(sqlCmd + '<br>' + 'Editing items: ' + jeeP._editIds_.length)
  jeeP.dbExecuteCommand(sqlCmd, 1)

  //clean spaces:
  //may integrate later json_search in searchconfiguration functions
  if (sqlCmd.includes('JSON_REPLACE') && jeeP._editIds_.length > 0) {
    sqlCmd = jeeP.getCleaningSpaceSQLstring(edits)
    jeeP.dbExecuteCommand(sqlCmd, 1)
  }

  jeeP._editIds_ = []
})

//Register events on top of page container:

//Manage events outside parents delegations:

//Specials

/*Events delegations
*/