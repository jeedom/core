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

var $divCommandResult = $('#div_commandResult')

$('.bt_dbCommand').off('click').on('click', function() {
  var command = $(this).attr('data-command')
  dbExecuteCommand(command, false)
})

$('#ul_listSqlHistory').off('click', '.bt_dbCommand').on('click', '.bt_dbCommand', function() {
  var command = $(this).attr('data-command')
  dbExecuteCommand(command, false)
})

$('#bt_validateSpecificCommand').off('click').on('click', function() {
  var command = $('#in_specificCommand').val()
  dbExecuteCommand(command, true)
})
$('#in_specificCommand').keypress(function(event) {
  if (event.which == 13) {
    $('#bt_validateSpecificCommand').trigger('click')
  }
})

function dbExecuteCommand(_command, _addToList) {
  if (!isset(_addToList)) _addToList = false

  $.clearDivContent('div_commandResult')
  jeedom.db({
    command: _command,
    error: function(error) {
      $.fn.showAlert({
        message: error.message,
        level: 'danger'
      })
    },
    success: function(result) {
      $('#h3_executeCommand').empty().append('{{Commande :}} "' + _command + '"<br/>{{Temps d\'éxécution}} :' + ' ' + result.time + 's')
      $divCommandResult.append(dbGenerateTableFromResponse(result.sql))
      $('#in_specificCommand').val(_command)

      if (_addToList) {
        if ($('.bt_dbCommand[data-command="' + _command.replace(/"/g, '\\"') + '"]').html() == undefined) {
          $('#ul_listSqlHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="' + _command.replace(/"/g, '\\"') + '">' + _command + '</a></li>')
        }
        var kids = $('#ul_listSqlHistory').children()
        if (kids.length >= 10) {
          kids.last().remove()
        }
      }
    }
  })
}

function dbGenerateTableFromResponse(_response) {
  var result = '<table class="table table-condensed table-bordered">'
  result += '<thead>'
  result += '<tr>'
  for (var i in _response[0]) {
    result += '<th>' + i + '</th>'
  }
  result += '</tr></thead>'

  result += '<tbody>'
  for (var i in _response) {
    result += '<tr>'
    for (var j in _response[i]) {
      result += '<td>' + _response[i][j] + '</td>'
    }
    result += '</tr>'
  }
  result += '</tbody></table>'
  return result
}

//*************************SQL result top scrollbar handler**************************
$(function() {
  window.onresize = function() {
    var tHeight = $('#dbCommands').height() + $('#jeedomMenuBar').height() + 15
    $('#div_commandResult').height($(window).height() - tHeight)
  }
  $(window).trigger('resize')
})


//*************************SQL constructor**************************
$('#sqlOperation').off('change').on('change', function() {
  var operation = $(this).value()
  switch (operation) {
    case 'SELECT':
      $(this).removeClass('warning danger').addClass('info')
      $('#sql_selector').parent().show()
      $('#lblFrom').show().text('FROM')

      $('#sqlSetGroup').hide()
      $('#sqlWhereGroup').show()
      break
    case 'INSERT':
      $(this).removeClass('info danger').addClass('warning')
      $('#sql_selector').parent().hide()
      $('#lblFrom').show().text('INTO')

      $('#sqlSetGroup').show()
      $('#sqlWhereGroup').hide()
      defineSQLsetGroup()
      break
    case 'UPDATE':
      $(this).removeClass('info warning').addClass('danger')
      $('#sql_selector').parent().hide()
      $('#lblFrom').hide()

      $('#sqlSetGroup').show()
      $('#sqlWhereGroup').show()
      $('#checksqlwhere').prop('checked', true).trigger('change')
      defineSQLsetGroup()
      break
    case 'DELETE':
      $(this).removeClass('info warning').addClass('danger')
      $('#sql_selector').parent().hide()
      $('#lblFrom').show().text('FROM')

      $('#sqlSetGroup').hide()
      $('#sqlWhereGroup').show()
      $('#checksqlwhere').prop('checked', true).trigger('change')
      break
  }
  $(window).trigger('resize')
})

$('#sqlTable').off('change').on('change', function() {
  var selectedTable = $(this).value()
  var options = ''
  for (var col in _tableList_[selectedTable]) {
    options += '<option value="' + _tableList_[selectedTable][col]['colName'] + '">' + _tableList_[selectedTable][col]['colName'] + '</option>'
  }
  $('#sqlWhere').empty().append(options)

  if (['INSERT', 'UPDATE'].includes($('#sqlOperation').value())) {
    defineSQLsetGroup()
  }
  $(window).trigger('resize')
})

$('#checksqlwhere').off('change').on('change', function() {
  if (!$(this).is(':checked')) {
    $('#sqlWhere, #sqlLike, #sqlLikeValue').addClass('disabled')
  } else {
    $('#sqlWhere, #sqlLike, #sqlLikeValue').removeClass('disabled')
  }
})

$('#bt_writeDynamicCommand').off('click').on('click', function() {
  var sqlString = constructSQLstring()
  $('#in_specificCommand').val(sqlString)
})

$('#bt_execDynamicCommand').off('click').on('click', function() {
  var sqlString = constructSQLstring()
  $('#in_specificCommand').val(sqlString)
  dbExecuteCommand(sqlString, true)
})

function constructSQLstring() {
  var operation = $('#sqlOperation').value()
  var command = operation

  switch (operation) {
    case 'SELECT':
      command += ' ' + $('#sql_selector').val() + ' FROM `' + $('#sqlTable').val() + '`'
      break
    case 'INSERT':
      command += ' INTO `' + $('#sqlTable').val() + '`'
      break
    case 'UPDATE':
      command += ' `' + $('#sqlTable').val() + '` SET '
      break
    case 'DELETE':
      command += ' FROM `' + $('#sqlTable').val() + '`'
      break
  }
  if (operation == 'INSERT') {
    var col, cols, value, values
    cols = values = ''
    $('#sqlSetOptions input.sqlSetter').each(function() {
      col = $(this).attr('id')
      value = $(this).val()
      if (value != '') {
        cols += '`' + col + '`,'
        values += '' + value + ','
      }
    })
    command += ' (' + cols.slice(0, -1) + ') VALUES (' + values.slice(0, -1) + ')'
  }

  if (operation == 'UPDATE') {
    var col, value, isNull
    $('#sqlSetOptions input.sqlSetter').each(function() {
      col = $(this).attr('id')
      value = $(this).val()
      isNull = $(this).closest('.form-group').find('.checkSqlColNull').is(':checked')
      if (value != '') {
        command += '`' + col + '`= ' + value + ','
      } else if (isNull) {
        command += '`' + col + '`= NULL,'
      }
    })
    command = command.slice(0, -1)
  }

  if (['SELECT', 'UPDATE', 'DELETE'].includes(operation) && $('#checksqlwhere').is(':checked') && $('#sqlLikeValue').val() != '') {
    command += ' WHERE '
    command += '`' + $('#sqlWhere').val() + '`'
    command += ' ' + $('#sqlLike').value()
    command += ' ' + $('#sqlLikeValue').val()
  }

  return command
}

function defineSQLsetGroup() {
  var selectedTable = $('#sqlTable').value()
  var operation = $('#sqlOperation').value()
  var options = '<div id="sqlSetOptions">'
  var name, type, extra
  for (var col in _tableList_[selectedTable]) {
    name = _tableList_[selectedTable][col]['colName']
    type = _tableList_[selectedTable][col]['colType']
    extra = _tableList_[selectedTable][col]['colExtra']
    options += '<div class="form-group">'
    if (extra == 'auto_increment') {
      options += '<label class="col-xs-2 control-label warning">' + name + '</label>'
      options += '<div class="col-xs-8"><input id="' + name + '" class="form-control disabled input-sm" type="text" value="" placeholder="' + type + ' (auto-increment)" disabled/></div>'
    } else {
      options += '<label class="col-xs-2 control-label">' + name + '</label>'
      options += '<div class="col-xs-8"><input id="' + name + '" class="form-control sqlSetter input-sm" type="text" value="" placeholder="' + type + '"/></div>'
      if (operation == 'UPDATE') options += '<label class="col-xs-2"><input class="checkSqlColNull" type="checkbox"/>Null</label>'
    }
    options += '</div>'
  }
  options += '</div>'

  $('#sqlSetGroup > label').empty().append(options.slice(0, -1))
}