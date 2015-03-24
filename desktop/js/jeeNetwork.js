
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
 if (getUrlVars('removeSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'});
}

$(".li_jeeNetwork").on('click', function (event) {
    $.hideAlert();
    $('#pre_logInfo').empty();
    $('#pre_backupInfo').empty();
    $('#pre_updateInfo').empty();
    $('#div_conf').show();
    $('.li_jeeNetwork').removeClass('active');
    $(this).addClass('active');
    jeedom.jeeNetwork.byId({
        id: $(this).attr('data-jeeNetwork_id'),
        cache: false,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.jeeNetworkAttr').value('');
            $('.jeeNetwork').setValues(data, '.jeeNetworkAttr');
            var plugin = '';
            for (var i in data.plugin) {
                plugin += '<span class="label label-info">' + data.plugin[i] + '</span> ';
            }
            if (isset(data.configuration) && isset(data.configuration.auiKey)) {
                $('#bt_connectToSlave').attr('href', 'http://' + data.ip + '/index.php?v=d&auiKey=' + data.configuration.auiKey).show();
            } else {
                $('#bt_connectToSlave').hide();
            }
            $('#div_pluginList').empty().append(plugin);
            modifyWithoutSave = false;
        }
    });
    jeedom.jeeNetwork.loadConfig({
     id: $(this).attr('data-jeeNetwork_id'),
     configuration: $('#administration').getValues('.configKey')[0],
     error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#administration').setValues(data, '.configKey');
        modifyWithoutSave = false;
    }
});
    jeedom.jeeNetwork.listLog({
        id: $(this).attr('data-jeeNetwork_id'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            if (!$.isArray(data)) {
                return;
            }
            var option = '';
            for (var i in data) {
                option += '<option>' + data[i] + '</option>';
            }
            $('#sel_logSlave').empty().append(option);
        }
    });
    jeedom.jeeNetwork.listLocalSlaveBackup({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            var option = '';
            for (var i in data) {
                option += '<option value="' + i + '">' + data[i] + '</option>';
            }
            $('#sel_backupList').empty().append(option);
        }
    });
    return false;
});

$('#bt_showLog').on('click', function () {
    jeedom.jeeNetwork.getLog({
        id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
        log: $('#sel_logSlave').value(),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            if (!$.isArray(data)) {
                return;
            }
            var log = '';
            var regex = /<br\s*[\/]?>/gi;
            for (var i in data.reverse()) {
                log += data[i][0].replace(regex, "\n");
                log += " - ";
                log += data[i][1].replace(regex, "\n");
                log += " - ";
                log += data[i][2].replace(regex, "\n");
                log += "\n";
            }
            $('#pre_logInfo').text(log);
        }
    });
});

$('#bt_emptyLog').on('click', function () {
    jeedom.jeeNetwork.emptyLog({
        id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
        log: $('#sel_logSlave').value(),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#pre_logInfo').empty();
        }
    });
});

$('#bt_removeLog').on('click', function () {
    jeedom.jeeNetwork.removeLog({
        id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
        log: $('#sel_logSlave').value(),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            jeedom.jeeNetwork.listLog({
                id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    if (!$.isArray(data)) {
                        return;
                    }
                    var option = '';
                    for (var i in data) {
                        option += '<option>' + data[i] + '</option>';
                    }
                    $('#sel_logSlave').empty().append(option);
                }
            });
            $('#pre_logInfo').empty();
        }
    });
});

$('#bt_restoreSlave').on('click', function () {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir restaurer cette sauvegarde sur ce Jeedom esclave ?}}', function (result) {
        if (result) {
            jeedom.jeeNetwork.restoreLocalBackup({
                id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
                backup: $('#sel_backupList').value(),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    getJeedomSlaveLog(1, 'restore',$('#pre_backupInfo'));
                }
            });
        }
    });
});

$('#bt_backupSlave').on('click', function () {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir sauvegarder ce Jeedom esclave ?}}', function (result) {
        if (result) {
            jeedom.jeeNetwork.backup({
                id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
                backup: $('#sel_backupList').value(),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    getJeedomSlaveLog(1, 'backup',$('#pre_backupInfo'));
                }
            });
        }
    });
});


$('#bt_haltSlave').on('click', function () {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir arrêter ce Jeedom esclave ?}}', function (result) {
        if (result) {
            jeedom.jeeNetwork.haltSystem({
                id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#div_alert').showAlert({message: '{{Le système est en cours d\'arrêt}}', level: 'success'});
                }
            });
        }
    });
});

$('#bt_rebootSlave').on('click', function () {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir redémarrer ce Jeedom esclave ?}}', function (result) {
        if (result) {
            jeedom.jeeNetwork.rebootSystem({
                id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#div_alert').showAlert({message: '{{Le système est en cours de redémarrage}}', level: 'success'});
                }
            });
        }
    });
});

$('#bt_updateSlave').on('click', function () {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir mettre à jour ce Jeedom esclave ?}}', function (result) {
        if (result) {
            jeedom.jeeNetwork.update({
                id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    getJeedomSlaveLog(1, 'update',$('#pre_updateInfo'));
                    $('#div_alert').showAlert({message: '{{Le système est en cours de mise à jour}}', level: 'success'});
                }
            });
        }
    });
});

$('#bt_checkUpdateSlave').on('click', function () {
    $.hideAlert();
    jeedom.jeeNetwork.checkUpdate({
        id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.li_jeeNetwork.active').click();
            $('#div_alert').showAlert({message: '{{La vérification des mises à jour a été faite avec succès}}', level: 'success'});
        }
    });
});

$('#bt_showMessage').on('click', function () {
    $.hideAlert();
    jeedom.jeeNetwork.getMessage({
        id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            if (!$.isArray(data)) {
                return;
            }
            var log = '';
            for (var i in data) {
                log += data[i]['date'];
                log += " - ";
                log += data[i]['plugin'];
                log += " - ";
                log += data[i]['message'];
                log += "\n";
            }
            $('#pre_logInfo').text(log);
        }
    });
});

$('#bt_emptyMessage').on('click', function () {
    $.hideAlert();
    jeedom.jeeNetwork.removeAllMessage({
        id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.li_jeeNetwork.active').click();
            $('#pre_logInfo').empty();
            $('#div_alert').showAlert({message: '{{Les messages ont été correctement supprimés}}', level: 'success'});
        }
    });
});

$("#bt_addJeeNetwork").on('click', function (event) {
    bootbox.prompt("Nom du Jeedom esclave ?", function (result) {
        if (result !== null) {
            jeedom.jeeNetwork.save({
                jeeNetwork: {name: result},
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    modifyWithoutSave = false;
                    window.location.replace('index.php?v=d&p=jeeNetwork&id=' + data.id + '&saveSuccessFull=1');
                }
            });
        }
    });
});

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveJeeNetwork").click();
});

$("#bt_saveJeeNetwork").on('click', function (event) {
    if ($('.li_jeeNetwork.active').attr('data-jeeNetwork_id') != undefined) {
        jeedom.jeeNetwork.save({
            jeeNetwork: $('.jeeNetwork').getValues('.jeeNetworkAttr')[0],
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                modifyWithoutSave = false;
                $('.li_jeeNetwork.active').click();
                $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner une connexion jeeNetwork}}', level: 'danger'});
    }
    return false;
});

$("#bt_removeJeeNetwork").on('click', function (event) {
    if ($('.li_jeeNetwork.active').attr('data-jeeNetwork_id') != undefined) {
        $.hideAlert();
        bootbox.confirm('{{Etes-vous sûr de vouloir supprimer la connexion jeeNetwork}} <span style="font-weight: bold ;">' + $('.li_jeeNetwork.active a').text() + '</span> ?', function (result) {
            if (result) {
                jeedom.jeeNetwork.remove({
                    id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function () {
                        modifyWithoutSave = false;
                        window.location.replace('index.php?v=d&p=jeeNetwork&removeSuccessFull=1');
                    }
                });
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner une connexion jeeNetwork}}', level: 'danger'});
    }
    return false;
});

if (is_numeric(getUrlVars('id'))) {
    if ($('#ul_jeeNetwork .li_jeeNetwork[data-jeeNetwork_id=' + getUrlVars('id') + ']').length != 0) {
        $('#ul_jeeNetwork .li_jeeNetwork[data-jeeNetwork_id=' + getUrlVars('id') + ']').click();
    } else {
        $('#ul_jeeNetwork .li_jeeNetwork:first').click();
    }
} else {
    $('#ul_jeeNetwork .li_jeeNetwork:first').click();
}

$('body').delegate('.objectAttr', 'change', function () {
    modifyWithoutSave = true;
});


$('#bt_saveGeneraleConfig').on('click',function(){
    jeedom.jeeNetwork.saveConfig({
        configuration: $('#administration').getValues('.configKey')[0],
        id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
            modifyWithoutSave = false;
        }
    });
});


function getJeedomSlaveLog(_autoUpdate, _log,_el) {
    $.ajax({
        type: 'POST',
        url: 'core/ajax/jeeNetwork.ajax.php',
        data: {
            action: 'getLog',
            log: _log,
            id: $('.li_jeeNetwork.active').attr('data-jeeNetwork_id')
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            setTimeout(function () {
                getJeedomSlaveLog(_autoUpdate, _log,_el)
            }, 1000);
        },
        success: function (data) {
            if (data.state != 'ok') {
                setTimeout(function () {
                    getJeedomSlaveLog(_autoUpdate, _log,_el)
                }, 1000);
                return;
            }
            var log = '';
            var regex = /<br\s*[\/]?>/gi;
            for (var i in data.result.reverse()) {
                log += data.result[i][2].replace(regex, "\n");
                if ($.trim(data.result[i][2].replace(regex, "\n")) == '[END ' + _log.toUpperCase() + ' SUCCESS]') {
                    _autoUpdate = 0;
                    $('#div_alert').showAlert({message: '{{L\'opération est réussie}}', level: 'success'});
                    $("#bt_saveJeeNetwork").click();
                }
                if ($.trim(data.result[i][2].replace(regex, "\n")) == '[END ' + _log.toUpperCase() + ' ERROR]') {
                    $('#div_alert').showAlert({message: '{{L\'opération a échoué}}', level: 'danger'});
                    _autoUpdate = 0;
                }
            }
            _el.text(log);
            if (init(_autoUpdate, 0) == 1) {
                setTimeout(function () {
                    getJeedomSlaveLog(_autoUpdate, _log,_el)
                }, 1000);
            } else {
                $('#bt_' + _log + 'Jeedom .fa-refresh').hide();
                $('.bt_' + _log + 'Jeedom .fa-refresh').hide();
            }
        }
    });
}