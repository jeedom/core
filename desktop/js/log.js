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

 function changeLog(_log){
    jeedom.log.autoupdate({
        log : _log,
        display : $('#pre_globallog'),
        search : $('#in_globalLogSearch'),
        control : $('#bt_globalLogStopStart'),
    });
}

$('#bt_downloadLog').click(function() {
  window.open('core/php/downloadFile.php?pathfile=log/' + $('#sel_log').value(), "_blank", null);
});

$(".li_log").on('click', function() {
    $(".li_log").removeClass('active');
    $(this).addClass('active');
    changeLog($(this).attr('data-log'))
});

$("#bt_clearLog").on('click', function(event) {
    $.ajax({
        type: "POST", 
        url: "core/ajax/log.ajax.php", 
        data: {
         action: "clear",
         logfile: $('.li_log.active').attr('data-log')
     },
     dataType: 'json',
     error: function(request, status, error) {
         handleAjaxError(request, status, error);
     },
     success: function(data) { 
        if (data.state != 'ok') {
         $('#div_alertError').showAlert({message: data.result, level: 'danger'});
     }
 }
});
});

$("#bt_removeLog").on('click', function(event) {
    $.ajax({
        type: "POST", 
        url: "core/ajax/log.ajax.php", 
        data: {
           action: "remove",
           logfile: $('.li_log.active').attr('data-log')
       },
       dataType: 'json',
       error: function(request, status, error) {
           handleAjaxError(request, status, error);
       },
       success: function(data) { 
        if (data.state != 'ok') {
           $('#div_alertError').showAlert({message: data.result, level: 'danger'});
           return;
       } 
       window.location.reload();
   }
});
});

$("#bt_removeAllLog").on('click', function(event) {
  bootbox.confirm("{{Etes-vous sur de vouloir supprimer tous les logs ?}}", function(result) {
     if (result) {
        $.ajax({
            type: "POST", 
            url: "core/ajax/log.ajax.php", 
            data: {
               action: "removeAll",
           },
           dataType: 'json',
           error: function(request, status, error) {
               handleAjaxError(request, status, error);
           },
           success: function(data) {
            if (data.state != 'ok') {
               $('#div_alertError').showAlert({message: data.result, level: 'danger'});
               return;
           }
           window.location.reload();
       }
   });
    }
});
});