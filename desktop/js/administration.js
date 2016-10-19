
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

 $('body').delegate('.configKey[data-l1key="market::allowDNS"]', 'change', function () {
    if($(this).value() == 1){
       $('.configKey[data-l1key=externalProtocol]').attr('disabled',true);
       $('.configKey[data-l1key=externalAddr]').attr('disabled',true);
       $('.configKey[data-l1key=externalPort]').attr('disabled',true);
       $('.configKey[data-l1key=externalAddr]').value('');
       $('.configKey[data-l1key=externalPort]').value('');
   }else{
    $('.configKey[data-l1key=externalProtocol]').attr('disabled',false);
    $('.configKey[data-l1key=externalAddr]').attr('disabled',false);
    $('.configKey[data-l1key=externalPort]').attr('disabled',false);
}
});


 $('body').delegate('.enableRepository', 'change', function () {
    if($(this).value() == 1){
        $('.repositoryConfiguration'+$(this).attr('data-repo')).show();
    }else{
        $('.repositoryConfiguration'+$(this).attr('data-repo')).hide();
    }
});

 $('body').delegate('.configKey[data-l1key="ldap:enable"]', 'change', function () {
    if($(this).value() == 1){
        $('#div_config_ldap').show();
    }else{
        $('#div_config_ldap').hide();
    }
});

 $('body').delegate('.configKey[data-l1key="cache::engine"]', 'change', function () {
     $('.cacheEngine').hide();
     $('.cacheEngine.'+$(this).value()).show();
 });

 $('body').delegate('.configKey[data-l1key="log::engine"]', 'change', function () {
     $('.logEngine').hide();
     $('.logEngine.'+$(this).value()).show();
 });

 $("#bt_genKeyAPI").on('click', function (event) {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir réinitialiser la clef API de Jeedom ? Vous devrez reconfigurer tous les équipements communiquant avec Jeedom et utilisant la clef API}}', function (result) {
        if (result) {
            genKeyAPI();
        }
    });
});

 $("#bt_genKeyAPIPro").on('click', function (event) {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir réinitialiser la clef API PRO de Jeedom ?}}', function (result) {
        if (result) {
            genKeyAPIPro();
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

 $('#bt_restartDns').on('click', function () {
   $.hideAlert();
   jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
       jeedom.network.restartDns({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
         modifyWithoutSave = false;
         loadPage('index.php?v=d&p=administration&panel=config_network');
     }
 });
   }
}); 
});


 $('#bt_haltDns').on('click', function () {
   $.hideAlert();
   jeedom.config.save({
    configuration: $('#config').getValues('.configKey')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
       jeedom.network.stopDns({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
         modifyWithoutSave = false;
         loadPage('index.php?v=d&p=administration&panel=config_network');
     }
 });
   }
}); 
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
    bootbox.confirm('{{Etes-vous sûr de vouloir changer le mode de Jeedom ? Cette opération est très risquée. Si vous passer de Maitre à Esclave cela va supprimer tous vos équipements, objet, vue, plan, plugin non compatibles avec le fonctionnement déporté. Aucun retour en arrière n\'est possible.}}', function (result) {
        if (result) {
            jeedom.jeeNetwork.changeMode({
                mode: mode,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                   loadPage('index.php?v=d&p=administration');
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
    saveObjectSummary();
    jeedom.config.save({
        configuration: $('#config').getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            jeedom.config.load({
                configuration: $('#config').getValues('.configKey:not(.noSet)')[0],
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
    bootbox.confirm('{{Attention ceci est une opération risquée. Confirmez-vous que vous comprennez bien les risques et qu\'en cas de Jeedom non fonctionel par la suite aucune demande de support ne sera acceptée (cette tentative d\'accès est enregistrée) ?}}', function (result) {
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
                    action: 'testLdapConnection',
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
     $('a[href="#'+getUrlVars('panel')+'"]').click();
 }

 printConvertColor();

 $.showLoading();
 jeedom.config.load({
    configuration: $('#config').getValues('.configKey:not(.noSet)')[0],
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#config').setValues(data, '.configKey');
        $('.configKey[data-l1key="market::allowDNS"]').trigger('change');
        $('.configKey[data-l1key="ldap:enable"]').trigger('change');
        modifyWithoutSave = false;
    }
});

 $('body').delegate('.configKey', 'change', function () {
    modifyWithoutSave = true;
});

 $('#bt_resetHwKey').on('click',function(){
 $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/jeedom.ajax.php", // url du fichier php
        data: {
            action: "resetHwKey"
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
        loadPage('index.php?v=d&p=administration');
    }
});
});

 $('#bt_resetHardwareType').on('click',function(){
    jeedom.config.save({
        configuration: {hardware_name : ''},
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            loadPage('index.php?v=d&p=administration');
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

function genKeyAPIPro() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "genKeyAPIPro"
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
        $('#in_keyAPIPro').value(data.result);
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

/*CMD color*/

$('.bt_resetColor').on('click', function () {
    var el = $(this);
    jeedom.getConfiguration({
        key: $(this).attr('data-l1key'),
        default: 1,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.configKey[data-l1key="' + el.attr('data-l1key') + '"]').value(data);
        }
    });
});

$('.testRepoConnection').on('click',function(){
    var repo = $(this).attr('data-repo');
    jeedom.config.save({
        configuration: $('#config').getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            jeedom.config.load({
                configuration: $('#config').getValues('.configKey:not(.noSet)')[0],
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#config').setValues(data, '.configKey');
                    modifyWithoutSave = false;
                    jeedom.repo.test({
                        repo: repo,
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        },
                        success: function (data) {
                            $('#div_alert').showAlert({message: '{{Test réussi}}', level: 'success'});
                        }
                    });
                }
            });
        }
    });
});

/**************************SYSTEM***********************************/
$('#bt_accessSystemAdministration').on('click',function(){
    $('#md_modal').dialog({title: "{{Administration système}}"});
    $("#md_modal").load('index.php?v=d&modal=system.action').dialog('open');
});

/**************************Summary***********************************/

$('#bt_addObjectSummary').on('click', function () {
    addObjectSummary();
});

$('body').undelegate('.objectSummary .objectSummaryAction[data-l1key=chooseIcon]', 'click').delegate('.objectSummary .objectSummaryAction[data-l1key=chooseIcon]', 'click', function () {
    var objectSummary = $(this).closest('.objectSummary');
    chooseIcon(function (_icon) {
        objectSummary.find('.objectSummaryAttr[data-l1key=icon]').empty().append(_icon);
    });
});

$('body').undelegate('.objectSummary .objectSummaryAction[data-l1key=remove]', 'click').delegate('.objectSummary .objectSummaryAction[data-l1key=remove]', 'click', function () {
    $(this).closest('.objectSummary').remove();
});

$('body').undelegate('.objectSummary .objectSummaryAction[data-l1key=createVirtual]', 'click').delegate('.objectSummary .objectSummaryAction[data-l1key=createVirtual]', 'click', function () {
    var objectSummary = $(this).closest('.objectSummary');
    $.ajax({
        type: "POST", 
        url: "core/ajax/object.ajax.php", 
        data: {
            action: "createSummaryVirtual",
            key: objectSummary.find('.objectSummaryAttr[data-l1key=key]').value()
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
            $('#div_alert').showAlert({message: '{{Création des commandes virtuel réussies}}', level: 'success'});
        }
    });
});

$("#table_objectSummary").sortable({axis: "y", cursor: "move", items: ".objectSummary", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});


printObjectSummary();

function printObjectSummary() {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/config.ajax.php", // url du fichier php
        data: {
            action: "getKey",
            key: 'object:summary'
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

        $('#table_objectSummary tbody').empty();
        for (var i in data.result) {
		 if(isset(data.result[i].key) && data.result[i].key == ''){
                continue;
            }
            if(!isset(data.result[i].name)){
                continue;
            }
            if(!isset(data.result[i].key)){
                data.result[i].key = i.toLowerCase().stripAccents().replace(/\_/g, '').replace(/\-/g, '').replace(/\&/g, '').replace(/\s/g, '');
            }
            addObjectSummary(data.result[i]);
        }
        modifyWithoutSave = false;
    }
});
}

function addObjectSummary(_summary) {
    var tr = '<tr class="objectSummary">';
    tr += '<td>';
    tr += '<input class="objectSummaryAttr form-control input-sm" data-l1key="key" />';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="objectSummaryAttr form-control input-sm" data-l1key="name" />';
    tr += '</td>';
    tr += '<td>';
    tr += '<select class="objectSummaryAttr form-control input-sm" data-l1key="calcul">';
    tr += '<option value="sum">{{Somme}}</option>';
    tr += '<option value="avg">{{Moyenne}}</option>';
    tr += '</select>';
    tr += '</td>';
    tr += '<td>';
    tr += '<a class="objectSummaryAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> {{Icône}}</a>';
    tr += '<span class="objectSummaryAttr" data-l1key="icon" style="margin-left : 10px;"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<input class="objectSummaryAttr form-control input-sm" data-l1key="unit" />';
    tr += '</td>';
    tr += '<td>';
    tr += '<select class="objectSummaryAttr form-control input-sm" data-l1key="count">';
    tr += '<option value="">{{Aucun}}</option>';
    tr += '<option value="binary">{{Binaire}}</option>';
    tr += '</select>';
    tr += '</td>';
    tr += '<td>';
    tr += '<center><input type="checkbox" class="objectSummaryAttr" data-l1key="allowDisplayZero" /></center>';
    tr += '</td>';
    tr += '<td>';
    if(isset(_summary) && isset(_summary.key) && _summary.key != ''){
        tr += '<a class="btn btn-success btn-sm objectSummaryAction" data-l1key="createVirtual"><i class="fa fa-puzzle-piece"></i> {{Créer virtuel}}</a>';
   }
   tr += '</td>';
   tr += '<td>';
   tr += '<a class="objectSummaryAction cursor" data-l1key="remove"><i class="fa fa-minus-circle"></i></a>';
   tr += '</td>';
   tr += '</tr>';
   $('#table_objectSummary tbody').append(tr);
   if (isset(_summary)){
	$('#table_objectSummary tbody tr:last').setValues(_summary, '.objectSummaryAttr');
   }
   if(isset(_summary) && isset(_summary.key) && _summary.key != ''){
    $('#table_objectSummary tbody tr:last .objectSummaryAttr[data-l1key=key]').attr('disabled','disabled');
}
modifyWithoutSave = true;
}

function saveObjectSummary() {
    summary = {};
    temp = $('#table_objectSummary tbody tr').getValues('.objectSummaryAttr');
    for(var i in temp){
        temp[i].key = temp[i].key.toLowerCase().stripAccents().replace(/\_/g, '').replace(/\-/g, '').replace(/\&/g, '').replace(/\s/g, '')
        if(temp[i].key == ''){
            temp[i].key = temp[i].name.toLowerCase().stripAccents().replace(/\_/g, '').replace(/\-/g, '').replace(/\&/g, '').replace(/\s/g, '')
        }
        summary[temp[i].key] = temp[i]
    }
    value = {'object:summary' : summary};
    $.ajax({
        type: "POST", 
        url: "core/ajax/config.ajax.php", 
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
        printObjectSummary();
        modifyWithoutSave = false;
    }
});
}
