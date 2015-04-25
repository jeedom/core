
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

 printConnectionState();
 printWifiList();

 $("#bt_genKeyAPI").on('click', function (event) {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir réinitialiser la clef API de Jeedom ? Vous devrez reconfigurer tous les équipements communicant avec Jeedom et utilisant la clef API}}', function (result) {
        if (result) {
            genKeyAPI();
        }
    });
});

 $('#bt_forceSyncHour').on('click', function () {
    $.hideAlert();
    jeedom.forceSyncHour({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#div_alert').showAlert({message: '{{Commande réalisée avec succès}}', level: 'success'});
        }
    });
});

 $('#bt_restartNgrok').on('click', function () {
   $.hideAlert();
   jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
       jeedom.network.restartNgrok({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
         modifyWithoutSave = false;
         window.location.href='index.php?v=d&p=administration&panel=config_network';
     }
 });
   }
}); 
});


 $('#bt_haltNgrok').on('click', function () {
   $.hideAlert();
   jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
       jeedom.network.stopNgrok({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
         modifyWithoutSave = false;
         window.location.href='index.php?v=d&p=administration&panel=config_network';
     }
 });
   }
}); 



});

 $("#bt_nodeJsKey").on('click', function (event) {
    $.hideAlert();
    genNodeJsKey();
});

 $("#bt_flushMemcache").on('click', function (event) {
    $.hideAlert();
    flushMemcache();
});

 $("#bt_clearJeedomLastDate").on('click', function (event) {
    $.hideAlert();
    clearJeedomDate();
});

 $('.changeJeeNetworkMode').on('click', function () {
    var mode = $(this).attr('data-mode');
    bootbox.confirm('{{Etes-vous sûr de vouloir changer le mode de Jeedom. Cette opération est très risquée. Si vous passer de Maitre à Esclave cela va supprimer tous vos équipements, objet, vue, plan, plugin non compatible avec le fonctionnement deporté. Aucun retour en arriere n\'est possible ?}}', function (result) {
        if (result) {
            jeedom.jeeNetwork.changeMode({
                mode: mode,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    window.location.reload();
                }
            });
        }
    });
});

 jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveGeneraleConfig").click();
});

 $("#bt_saveGeneraleConfig").on('click', function (event) {
    $.hideAlert();
    saveConvertColor();
    jeedom.config.save({
        configuration: $('#config').getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            jeedom.config.load({
                configuration: $('#config').getValues('.configKey')[0],
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#config').setValues(data, '.configKey');
                    modifyWithoutSave = false;
                    $('#div_alert').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
                }
            });
        }
    });
});

 $('#bt_accessDB').on('click', function () {
    var href = $(this).attr('data-href');
    bootbox.confirm('{{Attention ceci est une opération risquée. Confirmez-vous que vous comprennez bien les risques et que en cas de Jeedom non fonctionel par la suite aucune demande de support ne sera acceptée (cette tentative d\'accès est enregistré) ?}}', function (result) {
        if (result) {
            var win = window.open(href, '_blank');
            win.focus();
        }
    });
});

 $("#bt_testLdapConnection").on('click', function (event) {
    jeedom.config.save({
        configuration: $('#config').getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            modifyWithoutSave = false;
            $.ajax({
                type: 'POST',
                url: 'core/ajax/user.ajax.php',
                data: {
                    action: 'testLdapConneciton',
                },
                dataType: 'json',
                error: function (request, status, error) {
                    handleAjaxError(request, status, error);
                },
                success: function (data) {
                    if (data.state != 'ok') {
                        $('#div_alert').showAlert({message: '{{Connexion échouée :}} ' + data.result, level: 'danger'});
                        return;
                    }
                    $('#div_alert').showAlert({message: '{{Connexion réussie}}', level: 'success'});
                }
            });
        }
    });

return false;
});

$('#bt_addColorConvert').on('click', function () {
    addConvertColor();
});

$('#bt_selectMailCmd').on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'message'}}, function (result) {
        $('.configKey[data-l1key=emailAdmin]').atCaret('insert', result.human);
    });
});

if (getUrlVars('panel') != false) {
 $('a[href=#'+getUrlVars('panel')+']').click();
}

printConvertColor();

$.showLoading();
jeedom.config.load({
    configuration: $('#config').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#config').setValues(data, '.configKey');
        modifyWithoutSave = false;
    }
});
$('body').delegate('.configKey', 'change', function () {
    modifyWithoutSave = true;
});

$('#bt_testMarketConnection').on('click', function () {
    jeedom.config.save({
        configuration: $('#config').getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            jeedom.market.test({
               error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
             $('#div_alert').showAlert({message: '{{Connexion au market réussie}}', level: 'success'});
         }

     });
        }
    });
});

function genKeyAPI() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "genKeyAPI"
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
        }
        $('#in_keyAPI').value(data.result);
    }
});
}

function genNodeJsKey() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "genNodeJsKey"
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
        }
        $('#in_nodeJsKey').value(data.result);
    }
});
}

function clearJeedomDate() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/jeedom.ajax.php", // url du fichier php
        data: {
            action: "clearDate"
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
        }
        $('#in_jeedomLastDate').value('');
    }
});
}


function flushMemcache() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/jeedom.ajax.php", // url du fichier php
        data: {
            action: "flushcache"
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
        }
        $('#div_alert').showAlert({message: '{{Cache vidé}}', level: 'success'});
    }
});
}


/********************Convertion************************/
function printConvertColor() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "getKey",
            key: 'convertColor'
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
        }

        $('#table_convertColor tbody').empty();
        for (var color in data.result) {
            addConvertColor(color, data.result[color]);
        }
        modifyWithoutSave = false;
    }
});
}

function addConvertColor(_color, _html) {
    var tr = '<tr>';
    tr += '<td>';
    tr += '<input class="color form-control input-sm" value="' + init(_color) + '"/>';
    tr += '</td>';
    tr += '<td>';
    tr += '<input type="color" class="html form-control input-sm" value="' + init(_html) + '" />';
    tr += '</td>';
    tr += '</tr>';
    $('#table_convertColor tbody').append(tr);
    modifyWithoutSave = true;
}

function saveConvertColor() {
    var value = {};
    var colors = {};
    $('#table_convertColor tbody tr').each(function () {
        colors[$(this).find('.color').value()] = $(this).find('.html').value();
    });
    value.convertColor = colors;
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: 'addKey',
            value: json_encode(value)
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
        }
        modifyWithoutSave = false;
    }
});
}

/**************************NETWORK***********************************/

function printConnectionState(){
    jeedom.network.connectionState({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            var tr = '';
            for(var i in data){
              tr += '<tr>'; 
              tr += '<td>'+data[i].device+'</td>'; 
               tr += '<td>'+data[i].ip+'</td>'; 
              tr += '<td>'+data[i].type+'</td>'; 
              tr += '<td>'+data[i].state+'</td>'; 
              tr += '<td>'+data[i].connection+'</td>'; 
              tr += '</tr>';  
          }
          $('#table_networkState tbody').empty().append(tr);
      }
  });
}

function printWifiList(_rescan){
 jeedom.network.listWifi({
    rescan : _rescan || false,
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        var option = '';
        for(var i in data){
          option += '<option value="'+data[i].ssid+'" data-signal="'+data[i].signal+'" data-channel="'+data[i].channel+'" data-security="'+data[i].security+'" data-mode="'+data[i].mode+'">'; 
          option += data[i].ssid; 
          option += '</option>';  
      }
      $('.configKey[data-l1key="network::wifi::ssid"]').empty().append(option);

      jeedom.config.load({
        configuration: {"network::wifi::ssid" : ""},
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#config').setValues(data, '.configKey');
            modifyWithoutSave = false;
        }
    });
  }
});
}

$('.configKey[data-l1key="network::wifi::ssid"]').on('change',function(){
    var option = $('.configKey[data-l1key="network::wifi::ssid"] option:selected');
    $('#span_wifiSignal').empty().append(option.attr('data-signal'));
    $('#span_wifiChannel').empty().append(option.attr('data-channel'));
    $('#span_wifiSecurity').empty().append(option.attr('data-security'));
    $('#span_wifiMode').empty().append(option.attr('data-mode'));
});

$('#bt_connectToWifi').on('click', function () {
   $.hideAlert();
   jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
       jeedom.network.connectToWireless({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
         modifyWithoutSave = false;
         window.location.href='index.php?v=d&p=administration&panel=config_network';
     }
 });
   }
}); 
});

$('#bt_disconnectToWifi').on('click', function () {
   $.hideAlert();
   jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
       jeedom.network.disconnectFromWireless({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
         modifyWithoutSave = false;
         window.location.href='index.php?v=d&p=administration&panel=config_network';
     }
 });
   }
}); 
});

$('#bt_refreshWifiList').on('click',function(){
    printWifiList(true);
});

$('#bt_refresNetworkState').on('click',function(){
    printConnectionState();
});