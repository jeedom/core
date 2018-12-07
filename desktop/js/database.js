  
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

 function dbGenerateTableFromResponse(_response){
  var result = '<table class="table table-condensed table-bordered">';
  result += '<thead>';
  result += '<tr>';
  for(var i in _response[0]){
    result += '<th>';
    result += i;
    result += '</th>';
  }
  result += '</tr>';
  result += '</thead>';
  result += '<tbody>';
  for(var i in _response){
   result += '<tr>';
   for(var j in _response[i]){
     result += '<td>';
     result += _response[i][j];
     result += '</td>';
   }
   result += '</tr>';
 }
 result += '</tbody>';
 result += '</table>';
 return result;
}

$('.bt_dbCommand').off('click').on('click',function(){
  var command = $(this).attr('data-command');
  $('#div_commandResult').empty();
  jeedom.db({
    command : command,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(log){
     $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
     $('#div_commandResult').append(dbGenerateTableFromResponse(log));
   }
 })
});

$('#ul_listSqlHistory').off('click','.bt_dbCommand').on('click','.bt_dbCommand',function(){
  var command = $(this).attr('data-command');
  $('#div_commandResult').empty();
  jeedom.db({
    command : command,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(log){
     $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
     $('#in_specificCommand').value(command)
     $('#div_commandResult').append(dbGenerateTableFromResponse(log));
   }
 })
});

$('#bt_validateSpecifiCommand').off('click').on('click',function(){
  var command = $('#in_specificCommand').value();
  $('#div_commandResult').empty();
  jeedom.db({
    command : command,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(log){
      $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
      $('#div_commandResult').append(dbGenerateTableFromResponse(log));
      $('#ul_listSqlHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="'+command+'">'+command+'</a></li>');
      var kids = $('#ul_listSqlHistory').children();
      if (kids.length >= 10) {
        kids.last().remove();
      }
    }
  })
});

$('#in_specificCommand').keypress(function(e) {
  if(e.which == 13) {
   var command = $('#in_specificCommand').value();
   $('#div_commandResult').empty();
   jeedom.db({
    command : command,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success : function(log){
      $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
      $('#div_commandResult').append(dbGenerateTableFromResponse(log));
      $('#ul_listSqlHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_dbCommand" data-command="'+command+'">'+command+'</a></li>');
      var kids = $('#ul_listSqlHistory').children();
      if (kids.length >= 10) {
        kids.last().remove();
      }
    }
  })
 }
});