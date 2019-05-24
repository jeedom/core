
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



$('.bt_systemCommand').off('click').on('click',function(){
  var command = $(this).attr('data-command');
  $('#pre_commandResult').empty();
  if($(this).parent().hasClass('list-group-item-danger')){
    bootbox.confirm('{{Etes-vous sûr de vouloir exécuter cette commande : }}<strong>'+command+'</strong> ? {{Celle-ci est classée en dangereuse}}', function (result) {
      if (result) {
        jeedom.ssh({
          command : command,
          success : function(log){
            $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
            $('#pre_commandResult').append(log);
          }
        })
      }
    });
  }else{
    jeedom.ssh({
      command : command,
      success : function(log){
        $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
        $('#pre_commandResult').append(log);
      }
    })
  }
});


$('#ul_listSystemHistory').off('click','.bt_systemCommand').on('click','.bt_systemCommand',function(){
  var command = $(this).attr('data-command');
  $('#pre_commandResult').empty();
  $('#div_commandResult').empty();
  jeedom.ssh({
    command : command,
    success : function(log){
      $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
      $('#in_specificCommand').value(command)
      $('#pre_commandResult').append(log);
    }
  })
});

$('#bt_validateSpecifiCommand').off('click').on('click',function(){
  var command = $('#in_specificCommand').value();
  $('#pre_commandResult').empty();
  jeedom.ssh({
    command : command,
    success : function(log){
      $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
      $('#pre_commandResult').append(log);
      $('#ul_listSystemHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="'+command+'">'+command+'</a></li>');
      var kids = $('#ul_listSystemHistory').children();
      if (kids.length >= 10) {
        kids.last().remove();
      }
    }
  })
});

$('#in_specificCommand').keypress(function(e) {
  if(e.which == 13) {
    var command = $('#in_specificCommand').value();
    $('#pre_commandResult').empty();
    jeedom.ssh({
      command : command,
      success : function(log){
        $('#h3_executeCommand').empty().append('{{Commande : }}'+command);
        $('#pre_commandResult').append(log);
        if($('.bt_systemCommand[data-command="'+command.replace(/"/g, '\\"')+'"]').html() == undefined){
          $('#ul_listSystemHistory').prepend('<li class="cursor list-group-item list-group-item-success"><a class="bt_systemCommand" data-command="'+command.replace(/"/g, '\\"')+'">'+command+'</a></li>');
        }
        var kids = $('#ul_listSystemHistory').children();
        if (kids.length >= 10) {
          kids.last().remove();
        }
      }
    })
  }
});

$('#bt_cleanFileSystemRight').off('click').on('click',function(){
  jeedom.cleanFileSystemRight({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('#div_alert').showAlert({message: '{{Remise à plat des droits fait avec succès}}', level: 'success'});
    }
  });
});

$('#bt_consitency').off('click').on('click',function(){
  jeedom.consistency({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
     $('#div_alert').showAlert({message: '{{Exécution de la vérification effectuée, voir le log consistency pour afficher le résultat}}', level: 'success'});
    }
  });
});
