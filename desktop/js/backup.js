
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
 jwerty.key('ctrl+s/⌘+s', function (e) {
    e.preventDefault();
    $("#bt_saveBackup").click();
});

 $('#pre_backupInfo').height($(window).height() - $('header').height() - $('footer').height() - 150);

 $("#bt_saveBackup").on('click', function (event) {
    $.hideAlert();
    jeedom.config.save({
        configuration: $('#backup').getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            jeedom.config.load({
                configuration: $('#backup').getValues('.configKey')[0],
                plugin: 'core',
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#backup').setValues(data, '.configKey');
                    modifyWithoutSave = false;
                    $('#div_alert').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
                }
            });
        }
    });
});

 $(".bt_backupJeedom").on('click', function (event) {
    var el = $(this);
    bootbox.confirm('{{Etes-vous sûr de vouloir faire une sauvegarde de}} '+JEEDOM_PRODUCT_NAME+' {{? Une fois lancée cette opération ne peut être annulée}}', function (result) {
        if (result) {
            $.hideAlert();
            el.find('.fa-refresh').show();
            jeedom.backup.backup({
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    getJeedomLog(1, 'backup');
                }
            });
        }
    });
});

 $("#bt_restoreJeedom").on('click', function (event) {
    var el = $(this);
    bootbox.confirm('{{Etes-vous sûr de vouloir restaurer}} '+JEEDOM_PRODUCT_NAME+' {{avec la sauvegarde}} <b>' + $('#sel_restoreBackup option:selected').text() + '</b> ? {{Une fois lancée cette opération ne peut être annulée.}}<span style="color:red;font-weight: bold;">IMPORTANT la restauration d\'un backup est une opération risquée et n\'est à utiliser qu\'en dernier recours.</span>', function (result) {
        if (result) {
            $.hideAlert();
            el.find('.fa-refresh').show();
            jeedom.backup.restoreLocal({
                backup: $('#sel_restoreBackup').value(),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    getJeedomLog(1, 'restore');
                }
            });
        }
    });
});

 $("#bt_removeBackup").on('click', function (event) {
    var el = $(this);
    bootbox.confirm('{{Etes-vous sûr de vouloir supprimer la sauvegarde}} <b>' + $('#sel_restoreBackup option:selected').text() + '</b> ?', function (result) {
        if (result) {
            el.find('.fa-refresh').show();
            jeedom.backup.remove({
                backup: $('#sel_restoreBackup').value(),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    updateListBackup();
                    $('#div_alert').showAlert({message: '{{Sauvegarde supprimée avec succès}}', level: 'success'});
                }
            });
        }
    });
});

 $('#bt_downloadBackup').on('click', function () {
    window.open('core/php/downloadFile.php?pathfile=' + $('#sel_restoreBackup').value(), "_blank", null);
});

 $('#bt_uploadBackup').fileupload({
    dataType: 'json',
    replaceFileInput: false,
    done: function (e, data) {
        if (data.result.state != 'ok') {
            $('#div_alert').showAlert({message: data.result.result, level: 'danger'});
            return;
        }
        updateListBackup();
        $('#div_alert').showAlert({message: '{{Fichier(s) ajouté(s) avec succès}}', level: 'success'});
    }
});

 $(".bt_uploadCloudBackup").on('click', function (event) {
    var el = $(this);
    bootbox.confirm('{{Etes-vous sûr de vouloir envoyer une sauvegarde de}} '+JEEDOM_PRODUCT_NAME+' {{sur le cloud ? Une fois lancée cette opération ne peut être annulée}}', function (result) {
        if (result) {
            el.find('.fa-refresh').show();
            jeedom.backup.uploadCloud({
            	backup: $('#sel_restoreBackup').value(),
            	error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    getJeedomLog(1, 'backupCloud');
                }
            });
        }
    });
});

 $(".bt_restoreRepoBackup").on('click', function (event) {
    var el = $(this);
    bootbox.confirm('{{Etes-vous sûr de vouloir restaurer}} '+JEEDOM_PRODUCT_NAME+' {{avec la sauvegarde Cloud}} <b>' + $('#sel_restoreCloudBackup option:selected').text() + '</b> ? {{Une fois lancée cette opération ne peut être annulée}}', function (result) {
        if (result) {
            el.find('.fa-refresh').show();
            jeedom.backup.restoreCloud({
                backup: el.closest('.repo').find('.sel_restoreCloudBackup').value(),
                repo: el.attr('data-repo'),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    getJeedomLog(1, 'restore');
                }
            });
        }
    });
});

 $.showLoading();
 jeedom.config.load({
    configuration: $('#backup').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#backup').setValues(data, '.configKey');
        modifyWithoutSave = false;
    }
});
 updateListBackup();

 $('#div_pageContainer').delegate('.configKey', 'change', function () {
    modifyWithoutSave = true;
});

 /********************Log************************/

 function getJeedomLog(_autoUpdate, _log) {
    $.ajax({
        type: 'POST',
        url: 'core/ajax/log.ajax.php',
        data: {
            action: 'get',
            log: _log,
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
            if($.isArray(data.result)){
                for (var i in data.result.reverse()) {
                    log += data.result[i]+"\n";
                    if(data.result[i].indexOf('[END ' + _log.toUpperCase() + ' SUCCESS]') != -1){
                        $('#div_alert').showAlert({message: '{{L\'opération est réussie}}', level: 'success'});
                        if(_log == 'restore'){
                            jeedom.user.refresh();
                        }
                        _autoUpdate = 0;
                    }
                    if(data.result[i].indexOf('[END ' + _log.toUpperCase() + ' ERROR]') != -1){
                        $('#div_alert').showAlert({message: '{{L\'opération a échoué}}', level: 'danger'});
                        if(_log == 'restore'){
                            jeedom.user.refresh();
                        }
                        _autoUpdate = 0;
                    }
                }
            }
            $('#pre_backupInfo').text(log);
            if (init(_autoUpdate, 0) == 1) {
                setTimeout(function () {
                    getJeedomLog(_autoUpdate, _log)
                }, 1000);
            } else {
                $('#bt_' + _log + 'Jeedom .fa-refresh').hide();
                $('.bt_' + _log + 'Jeedom .fa-refresh').hide();
                updateListBackup();
                for(var i in REPO_LIST){
                    updateRepoListBackup(REPO_LIST[i]);
                }
            }
        }
    });
}

function updateListBackup() {
    jeedom.backup.list({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            var options = '';
            for (var i in data) {
                options += '<option value="' + i + '">' + data[i] + '</option>';
            }
            $('#sel_restoreBackup').html(options);
        }
    });
}

for(var i in REPO_LIST){
    updateRepoListBackup(REPO_LIST[i]);
}

function updateRepoListBackup(_repo) {
    jeedom.repo.backupList({
        repo : _repo,
        global : false,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            var options = '';
            for (var i in data) {
                options += '<option value="' + data[i] + '">' + data[i] + '</option>';
            }
            $('.sel_restoreCloudBackup[data-repo='+_repo+']').empty().html(options);
        }
    });
}
