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


 printUpdate();

 $("#md_specifyUpdate").dialog({
    autoOpen: false,
    modal: true,
    height: 300,
    width: 400,
    open: function () {
        $("body").css({overflow: 'hidden'});
    },
    beforeClose: function (event, ui) {
        $("body").css({overflow: 'inherit'});
    }
});

 $('#bt_reapplyUpdate').on('click', function () {
  $('#md_specifyUpdate').dialog({title: "{{Options}}"});
  $("#md_specifyUpdate").dialog('open');
});

 $('#bt_reapplySpecifyUpdate').on('click',function(){
   var level = "-1";
   var mode = '';
   if($('#cb_forceReapplyUpdate').value() == 1){
    mode = 'force';
}
jeedom.update.doAll({
    mode: mode,
    level: level,
    version : $('#sel_updateVersion').value(),
    onlyThisVersion : ($('#cb_allFromThisUpdate').value() == 1) ? 'no':'yes',
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
     $("#md_specifyUpdate").dialog('close');
     getJeedomLog(1, 'update');
 }
});
});

 $('#bt_allChangelog').on('click', function () {
   $('#md_modal2').dialog({title: "{{Changelog}}"});
   $("#md_modal2").load('index.php?v=d&modal=market.allChangelog').dialog('open');
});

 $('.bt_updateAll').on('click', function () {
  var level = $(this).attr('data-level');
  var mode = $(this).attr('data-mode');
  bootbox.confirm('{{Etes-vous sur de vouloir faire les mises à jour ?}} ', function (result) {
    if (result) {
        $.hideAlert();
        jeedom.update.doAll({
            mode: mode,
            level: level,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                getJeedomLog(1, 'update');
            }
        });
    }
});
});

 $('#bt_checkAllUpdate').on('click', function () {
    $.hideAlert();
    jeedom.update.checkAll({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            printUpdate();
        }
    });
});

 $('#table_update').delegate('.changeState', 'click', function () {
    var id = $(this).closest('tr').attr('data-id');
    var state = $(this).attr('data-state');
    bootbox.confirm('{{Etes vous sur de vouloir changer l\'état de l\'objet ?}}', function (result) {
        if (result) {
            $.hideAlert();
            jeedom.update.changeState({
                id: id,
                state: state,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    printUpdate();
                }
            });
        }
    });

});

 $('#table_update').delegate('.update', 'click', function () {
    var id = $(this).closest('tr').attr('data-id');
    bootbox.confirm('{{Etes vous sur de vouloir mettre à jour cet objet ?}}', function (result) {
        if (result) {
            $.hideAlert();
            jeedom.update.do({
                id: id,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    getJeedomLog(1, 'update');
                }
            });
        }
    });
});

 $('#table_update').delegate('.remove', 'click', function () {
    var id = $(this).closest('tr').attr('data-id');
    bootbox.confirm('{{Etes vous sur de vouloir supprimer cet objet ?}}', function (result) {
        if (result) {
            $.hideAlert();
            jeedom.update.remove({
                id: id,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    printUpdate();
                }
            });
        }
    });
});

 $('#table_update').delegate('.view', 'click', function () {
    $('#md_modal').dialog({title: "Market"});
    $('#md_modal').load('index.php?v=d&modal=market.display&type=' + $(this).closest('tr').attr('data-type') + '&logicalId=' + encodeURI($(this).closest('tr').attr('data-logicalId'))).dialog('open');
});

 $('#table_update').delegate('.sendToMarket', 'click', function () {
    $('#md_modal').dialog({title: "Partager sur le market"});
    $('#md_modal').load('index.php?v=d&modal=market.send&type=' + $(this).closest('tr').attr('data-type') + '&logicalId=' + encodeURI($(this).closest('tr').attr('data-logicalId')) + '&name=' + encodeURI($(this).closest('tr').attr('data-logicalId'))).dialog('open');
});

 $('#bt_expertMode').on('click', function () {
    printUpdate();
});

 function getJeedomLog(_autoUpdate, _log) {
    $.ajax({
        type: 'POST',
        url: 'core/ajax/log.ajax.php',
        data: {
            action: 'get',
            logfile: _log,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            setTimeout(function () {
                getJeedomLog(_autoUpdate, _log)
            }, 1000);
        },
        success: function (data) {
            if (data.state != 'ok') {
                setTimeout(function () {
                    getJeedomLog(_autoUpdate, _log)
                }, 1000);
                return;
            }
            var log = '';
            var regex = /<br\s*[\/]?>/gi;
            if($.isArray(data.result)){
                for (var i in data.result.reverse()) {
                    log += data.result[i][2].replace(regex, "\n");
                    if ($.trim(data.result[i][2].replace(regex, "\n")) == '[END ' + _log.toUpperCase() + ' SUCCESS]') {
                        printUpdate();
                        $('#div_alert').showAlert({message: '{{L\'opération est réussie}}', level: 'success'});
                        _autoUpdate = 0;
                    }
                    if ($.trim(data.result[i][2].replace(regex, "\n")) == '[END ' + _log.toUpperCase() + ' ERROR]') {
                        printUpdate();
                        $('#div_alert').showAlert({message: '{{L\'opération a échoué}}', level: 'danger'});
                        _autoUpdate = 0;
                    }
                }
            }
            $('#pre_' + _log + 'Info').text(log);
            $('#pre_updateInfo').parent().scrollTop($('#pre_updateInfo').parent().height() + 200000);
            if (init(_autoUpdate, 0) == 1) {
                setTimeout(function () {
                    getJeedomLog(_autoUpdate, _log)
                }, 1000);
            } else {
                $('#bt_' + _log + 'Jeedom .fa-refresh').hide();
                $('.bt_' + _log + 'Jeedom .fa-refresh').hide();
            }
        }
    });
}

function printUpdate() {
    jeedom.update.get({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#table_update tbody').empty();
            for (var i in data) {
                addUpdate(data[i]);
            }
            $('#table_update').trigger('update');
            initTooltips();
        }
    });

    jeedom.config.load({
        configuration: "update::lastCheck",
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#span_lastUpdateCheck').value(data);
        }
    });
}

function addUpdate(_update) {
    if (_update.status != 'update' && _update.type != 'core') {
        if ($('#bt_expertMode').attr('state') == 0) {
            return;
        }
    }
    var tr = '<tr data-id="' + init(_update.id) + '" data-logicalId="' + init(_update.logicalId) + '" data-type="' + init(_update.type) + '">';
    tr += '<td><span class="updateAttr" data-l1key="type"></span></td>';
    tr += '<td><span class="updateAttr" data-l1key="name"></span></td>';
    tr += '<td><span class="updateAttr" data-l1key="localVersion"></span></td>';
    tr += '<td><span class="updateAttr" data-l1key="remoteVersion"></span></td>';
    tr += '<td><span class="updateAttr label label-success" data-l1key="status"></span>';
    if (isset(_update.configuration) && isset(_update.configuration.version)) {
        if (_update.configuration.version == 'beta') {
            tr += ' <span class="label label-danger">' + _update.configuration.version + '</span>';
        } else {
            tr += ' <span class="label label-info">' + _update.configuration.version + '</span>';
        }
    }
    tr += '</td>';
    tr += '<td style="width : 400px;">';
    if (_update.status == 'update') {
        tr += '<a class="btn btn-info btn-xs pull-right update tooltips" style="color : white;margin-bottom : 5px;" title="{{Mettre à jour}}"><i class="fa fa-refresh"></i> {{Mettre à jour}}</a>';
    }

    if (_update.type != 'core') {
        tr += '<a class="btn btn-danger btn-xs pull-right remove expertModeVisible tooltips" data-state="unhold" style="color : white;margin-bottom : 5px;" ><i class="fa fa-trash-o"></i> {{Supprimer}}</a>';
        if (isset(_update.configuration) && isset(_update.configuration.market_owner) && _update.configuration.market_owner == 1) {
            tr += '<a class="btn btn-success btn-xs pull-right sendToMarket tooltips cursor expertModeVisible" style="color : white;margin-bottom : 5px;" title="{{Envoyer sur le market}}"><i class="fa fa-cloud-upload"></i> {{Partager}}</a>';
        }
        if (isset(_update.configuration) && isset(_update.configuration.market) && _update.configuration.market == 1) {
            tr += '<a class="btn btn-primary btn-xs pull-right view tooltips cursor" style="color : white;margin-bottom : 5px;"><i class="fa fa-search"></i> {{Voir}}</a>';
        }
    } else {
        tr += '<a class="btn btn-default btn-xs pull-right" href="http://blog.jeedom.fr" target="_blank" style="margin-bottom : 5px;"><i class="fa fa-bars"></i> {{Changelog}}</a>';
    }

    tr += '</td>';
    tr += '</tr>';
    $('#table_update').append(tr);
    $('#table_update tbody tr:last').setValues(_update, '.updateAttr');
}