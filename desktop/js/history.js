
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

initHistoryTrigger();

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

$(".li_history .remove").on('click', function () {
    var bt_remove = $(this);
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir supprimer l\'historique de}} <span style="font-weight: bold ;">' + bt_remove.closest('.li_history').find('.history').text() + '</span> ?', function (result) {
        if (result) {
            emptyHistory(bt_remove.closest('.li_history').attr('data-cmd_id'));
        }
    });
});

$(".li_history .export").on('click', function () {
    window.open('core/php/export.php?type=cmdHistory&id=' + $(this).closest('.li_history').attr('data-cmd_id'), "_blank", null);
});

function emptyHistory(_cmd_id) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/cmd.ajax.php", // url du fichier php
        data: {
            action: "emptyHistory",
            id: _cmd_id
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
            $('#div_alert').showAlert({message: '{{Historique supprimé avec succes}}', level: 'success'});
            li = $('li[data-cmd_id=' + _cmd_id + ']');
            if (li.hasClass('active')) {
                li.find('.history').click();
            }
        }
    });
}

function initHistoryTrigger() {
    $('#sel_chartType').on('change', function () {
        $('.li_history[data-cmd_id=' + lastId + '] .history').click();
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
    $('#cb_derive').on('change', function () {
        $('.li_history[data-cmd_id=' + lastId + '] .history').click();
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
    $('#cb_step').on('change', function () {
        $('.li_history[data-cmd_id=' + lastId + '] .history').click();
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

function addChart(_cmd_id, _action) {
    if (_action == 0) {
        if (isset(jeedom.history.chart['div_graph'])) {
            jeedom.history.chart['div_graph'].chart.get(parseInt(_cmd_id)).remove();
        }
    } else {
        jeedom.history.drawChart({
            cmd_id: _cmd_id,
            el: 'div_graph',
            daterange: 'all',
            success: function (data) {
                if (init(data.cmd.display.graphStep) != '') {
                    $('#cb_step').off().value(init(data.cmd.display.graphStep));
                }
                if (init(data.cmd.display.graphType) != '') {
                    $('#sel_chartType').off().value(init(data.cmd.display.graphType));
                }
                if (init(data.cmd.display.graphDerive) != '') {
                    $('#cb_derive').off().value(init(data.cmd.display.graphDerive));
                }
                initHistoryTrigger();
            }
        });
    }
}