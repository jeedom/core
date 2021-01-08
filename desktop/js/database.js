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

$('.bt_dbCommand').off('click').on('click',function() {
  var command = $(this).attr('data-command')
  dbExecuteCommand(command, false)
})

$('#ul_listSqlHistory').off('click','.bt_dbCommand').on('click','.bt_dbCommand',function() {
  var command = $(this).attr('data-command')
  dbExecuteCommand(command, false)
})

$('#bt_validateSpecificCommand').off('click').on('click',function() {
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
    command : _command,
    error: function(error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success: function(result) {
      $('#h3_executeCommand').empty().append('{{Commande : }}"'+_command+'" - {{Temps d\'éxécution}} : '+result.time+'s')
      $divCommandResult.append(dbGenerateTableFromResponse(result.sql))
      $('#in_specificCommand').val(_command)

      if (_addToList) {
        if ($('.bt_dbCommand[data-command="'+_command.replace(/"/g, '\\"')+'"]').html() == undefined) {
          $('#ul_listSqlHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="'+_command.replace(/"/g, '\\"')+'">'+_command+'</a></li>')
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
    result += '<th>'+i+'</th>'
  }
  result += '</tr></thead>'

  result += '<tbody>'
  for (var i in _response) {
    result += '<tr>'
    for (var j in _response[i]) {
      result += '<td>'+_response[i][j]+'</td>'
    }
    result += '</tr>'
  }
  result += '</tbody></table>'
  return result
}

//*************************SQL constructor**************************

$('#sqlFrom').off('change').on('change',function() {
  var selectedTable = $(this).val()
  for (var table in _tableList_) {
    if (table == selectedTable) {
      var options = ''
      for (var col in _tableList_[table]) {
        options += '<option value="'+_tableList_[table][col]+'">'+_tableList_[table][col]+'</option>'
      }
      $('#sqlWhere').empty().append(options)
      break
    }
  }
})

$('#checksqlwhere').off('change').on('change',function() {
  if (!$(this).is(':checked')) {
    $('#sqlWhere, #sqlLike, #sqlLikeValue').addClass('disabled')
  } else {
    $('#sqlWhere, #sqlLike, #sqlLikeValue').removeClass('disabled')
  }
})

$('#bt_validateDynamicCommand').off('click').on('click',function() {
  var command = 'SELECT '
  command += $('#sql_selector').val() + ' FROM '
  command += '`' + $('#sqlFrom').val() + '`'

  if ($('#checksqlwhere').is(':checked')) {
    command += ' WHERE '
    command += '`' + $('#sqlWhere').val() + '`'
    command += ' ' + $('#sqlLike').value()
    command += ' ' + $('#sqlLikeValue').val()
  }
  dbExecuteCommand(command, true)
})
