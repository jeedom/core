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
$('#bt_downloadLog').click(function() {
  window.open('core/php/downloadFile.php?pathfile=log/' + $('.li_log.active').attr('data-log'), "_blank", null);
});

$(".li_log").on('click', function() {
  $('#pre_globallog').empty();
  $(".li_log").removeClass('active');
  $(this).addClass('active');
  $('#bt_globalLogStopStart').removeClass('btn-success').addClass('btn-warning');
  $('#bt_globalLogStopStart').html('<i class="fas fa-pause"></i> {{Pause}}');
  $('#bt_globalLogStopStart').attr('data-state',1);
  jeedom.log.autoupdate({
    log : $(this).attr('data-log'),
    display : $('#pre_globallog'),
    search : $('#in_globalLogSearch'),
    control : $('#bt_globalLogStopStart'),
  });
});

$("#bt_clearLog").on('click', function(event) {
  jeedom.log.clear({
    log : $('.li_log.active').attr('data-log'),
    success: function(data) {
      $('.li_log.active a').html('<i class="fa fa-check"></i> '+$('.li_log.active').attr('data-log'));
      $('.li_log.active i').removeClass().addClass('fas fa-check');
      if($('#bt_globalLogStopStart').attr('data-state') == 0){
        $('#bt_globalLogStopStart').click();
      }
    }
  });
});

$("#bt_removeLog").on('click', function(event) {
  jeedom.log.remove({
    log : $('.li_log.active').attr('data-log'),
    success: function(data) {
      loadPage('index.php?v=d&p=log');
    }
  });
});

$("#bt_removeAllLog").on('click', function(event) {
  bootbox.confirm("{{Êtes-vous sûr de vouloir supprimer tous les logs ?}}", function(result) {
    if (result) {
      jeedom.log.removeAll({
        error: function (error) {
          $('#div_alertError').showAlert({message: error.message, level: 'danger'});
        },
        success: function(data) {
          loadPage('index.php?v=d&p=log');
        }
      });
    }
  });
});
