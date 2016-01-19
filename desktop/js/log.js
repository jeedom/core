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


 $('#div_logDisplay').height($(window).height() - $('header').height() - $('footer').height() - 50);
 $('#div_logDisplay').scrollTop(999999999);
 $('#bt_downloadLog').click(function() {
 	window.open('core/php/downloadFile.php?pathfile=log/' + $('#sel_log').value(), "_blank", null);
 });

 $("#sel_log").on('change', function() {
 	log = $('#sel_log').value();
 	$('#div_pageContainer').empty().load('index.php?v=d&p=log&logfile=' + log+'&ajax=1',function(){
 		initPage();
 	});
 });

 $('#bt_refreshLog').on('click', function() {
 	log = $('#sel_log').value();
 	$('#div_pageContainer').empty().load('index.php?v=d&p=log&logfile=' + log+'&ajax=1',function(){
 		initPage();
 	});
 });

 $("#bt_clearLog").on('click', function(event) {
            $.ajax({// fonction permettant de faire de l'ajax
                type: "POST", // methode de transmission des données au fichier php
                url: "core/ajax/log.ajax.php", // url du fichier php
                data: {
                	action: "clear",
                	logfile: $('#sel_log').value()
                },
                dataType: 'json',
                error: function(request, status, error) {
                	handleAjaxError(request, status, error);
                },
                success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                	$('#div_alertError').showAlert({message: data.result, level: 'danger'});
                } else {
                	$('#div_pageContainer').empty().load('index.php?v=d&p=log&ajax=1',function(){
                		initPage();
                	});
                }
            }
        });
        });

 $("#bt_removeLog").on('click', function(event) {
            $.ajax({// fonction permettant de faire de l'ajax
                type: "POST", // methode de transmission des données au fichier php
                url: "core/ajax/log.ajax.php", // url du fichier php
                data: {
                	action: "remove",
                	logfile: $('#sel_log').value()
                },
                dataType: 'json',
                error: function(request, status, error) {
                	handleAjaxError(request, status, error);
                },
                success: function(data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                	$('#div_alertError').showAlert({message: data.result, level: 'danger'});
                } else {
                	$('#div_pageContainer').empty().load('index.php?v=d&p=log&ajax=1',function(){
                		initPage();
                	});
                }
            }
        });
        });

 $("#bt_removeAllLog").on('click', function(event) {
 	bootbox.confirm("{{Etes-vous sur de vouloir supprimer tous les logs ?}}", function(result) {
 		if (result) {
                    $.ajax({// fonction permettant de faire de l'ajax
                        type: "POST", // methode de transmission des données au fichier php
                        url: "core/ajax/log.ajax.php", // url du fichier php
                        data: {
                        	action: "removeAll",
                        },
                        dataType: 'json',
                        error: function(request, status, error) {
                        	handleAjaxError(request, status, error);
                        },
                        success: function(data) { // si l'appel a bien fonctionné
                        if (data.state != 'ok') {
                        	$('#div_alertError').showAlert({message: data.result, level: 'danger'});
                        	return;
                        }
                        $('#div_pageContainer').empty().load('index.php?v=d&p=log&ajax=1',function(){
                        	initPage();
                        });

                    }
                });
}
});
});