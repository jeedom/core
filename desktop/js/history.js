
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

 var chart;
 var noChart = 1;
 var colorChart = 0;
 var lastId = null;
 delete jeedom.history.chart['div_graph']

 initHistoryTrigger();

 $(".in_datepicker").datepicker();

 $(".li_history .history").on('click', function (event) {
    $.hideAlert();
    if ($(this).closest('.li_history').hasClass('active')) {
        $(this).closest('.li_history').removeClass('active');
        addChart($(this).closest('.li_history').attr('data-cmd_id'), 0);
    } else {
        $(this).closest('.li_history').addClass('active');
        addChart($(this).closest('.li_history').attr('data-cmd_id'), 1);
        lastId = $(this).closest('.li_history').attr('data-cmd_id');
    }
    return false;
});

 $("body").delegate("ul li input.filter", 'keyup', function () {
    if ($(this).value() == '') {
        $('.cmdList').hide();
    } else {
        $('.cmdList').show();
    }
});

 $(".li_history .remove").on('click', function () {
    var bt_remove = $(this);
    $.hideAlert();
    bootbox.prompt('{{Veuillez indiquer la date (Y-m-d H:m:s) avant laquelle il faut supprimer l\'historique de }} <span style="font-weight: bold ;">' + bt_remove.closest('.li_history').find('.history').text() + '</span> (laissez vide pour tout supprimer) ?', function (result) {
        if (result !== null) {
            emptyHistory(bt_remove.closest('.li_history').attr('data-cmd_id'),result);
        }
    });
});

 $('.displayObject').on('click', function () {
    var list = $('.cmdList[data-object_id=' + $(this).attr('data-object_id') + ']');
    if (list.is(':visible')) {
        $(this).find('i.fa').removeClass('fa-arrow-circle-down').addClass('fa-arrow-circle-right');
        list.hide();
    } else {
        $(this).find('i.fa').removeClass('fa-arrow-circle-right').addClass('fa-arrow-circle-down');
        list.show();
    }
});

 $(".li_history .export").on('click', function () {
    window.open('core/php/export.php?type=cmdHistory&id=' + $(this).closest('.li_history').attr('data-cmd_id'), "_blank", null);
});

 $('#bt_openCmdHistoryConfigure').on('click',function(){
    $('#md_modal').dialog({title: "{{Configuration de l'historique des commandes}}"});
    $("#md_modal").load('index.php?v=d&modal=cmd.configureHistory').dialog('open');
});

 function emptyHistory(_cmd_id,_date) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/cmd.ajax.php", // url du fichier php
        data: {
            action: "emptyHistory",
            id: _cmd_id,
            date: _date
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: '{{Historique supprimé avec succès}}', level: 'success'});
            li = $('li[data-cmd_id=' + _cmd_id + ']');
            if (li.hasClass('active')) {
                li.find('.history').click();
            }
        }
    });
}

function initHistoryTrigger() {
    $('#sel_chartType').on('change', function () {
       $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
       addChart(lastId,0);
       jeedom.cmd.save({
        cmd: {id: lastId, display: {graphType: $(this).value()}},
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('.li_history[data-cmd_id=' + lastId + '] .history').click();
        }
    });
   });
    $('#sel_groupingType').on('change', function () {
        $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
        addChart(lastId,0);
        jeedom.cmd.save({
            cmd: {id: lastId, display: {groupingType: $(this).value()}},
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $('.li_history[data-cmd_id=' + lastId + '] .history').click();
            }
        });
    });
    $('#cb_derive').on('change switchChange.bootstrapSwitch', function () {
       $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
       addChart(lastId,0);
       jeedom.cmd.save({
        cmd: {id: lastId, display: {graphDerive: $(this).value()}},
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('.li_history[data-cmd_id=' + lastId + '] .history').click();
        }
    });
   });
    $('#cb_step').on('change switchChange.bootstrapSwitch', function () {
        $('.li_history[data-cmd_id=' + lastId + ']').removeClass('active');
        addChart(lastId,0);
        jeedom.cmd.save({
            cmd: {id: lastId, display: {graphStep: $(this).value()}},
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $('.li_history[data-cmd_id=' + lastId + '] .history').click();
            }
        });
    });
}

$('#bt_validChangeDate').on('click',function(){
    $(jeedom.history.chart['div_graph'].chart.series).each(function(i, serie){
     if(!isNaN(serie.options.id)){
        var cmd_id = serie.options.id;
        addChart(cmd_id, 0);
        addChart(cmd_id, 1);
    }
});
});

function addChart(_cmd_id, _action) {
    if (_action == 0) {
        if (isset(jeedom.history.chart['div_graph']) && jeedom.history.chart['div_graph'].chart.get(parseInt(_cmd_id)) !== null) {
            jeedom.history.chart['div_graph'].chart.get(parseInt(_cmd_id)).remove();
        }
    } else {
        jeedom.history.drawChart({
            cmd_id: _cmd_id,
            el: 'div_graph',
            dateRange : 'all',
            dateStart : $('#in_startDate').value(),
            dateEnd :  $('#in_endDate').value(),
            success: function (data) {
                if(isset(data.cmd.display)){
                    if (init(data.cmd.display.graphStep) != '') {
                        $('#cb_step').off().value(init(data.cmd.display.graphStep));
                    }
                    if (init(data.cmd.display.graphType) != '') {
                        $('#sel_chartType').off().value(init(data.cmd.display.graphType));
                    }
                    if (init(data.cmd.display.groupingType) != '') {
                        $('#sel_groupingType').off().value(init(data.cmd.display.groupingType));
                    }
                    if (init(data.cmd.display.graphDerive) != '') {
                        $('#cb_derive').off().value(init(data.cmd.display.graphDerive));
                    }
                }
                initHistoryTrigger();
            }
        });
    }
}