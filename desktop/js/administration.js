
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
 actionOptions = [];
 var url = document.location.toString();
 if (url.match('#')) {
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
} 
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
})

$('#div_pageContainer').delegate('.configKey[data-l1key="market::allowDNS"]', 'change', function () {
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


$('#div_pageContainer').delegate('.enableRepository', 'change', function () {
    if($(this).value() == 1){
        $('.repositoryConfiguration'+$(this).attr('data-repo')).show();
    }else{
        $('.repositoryConfiguration'+$(this).attr('data-repo')).hide();
    }
});

$('#div_pageContainer').delegate('.configKey[data-l1key="ldap:enable"]', 'change', function () {
    if($(this).value() == 1){
        $('#div_config_ldap').show();
    }else{
        $('#div_config_ldap').hide();
    }
});

$('#div_pageContainer').delegate('.configKey[data-l1key="cache::engine"]', 'change', function () {
   $('.cacheEngine').hide();
   $('.cacheEngine.'+$(this).value()).show();
});

$('#div_pageContainer').delegate('.configKey[data-l1key="log::engine"]', 'change', function () {
   $('.logEngine').hide();
   $('.logEngine.'+$(this).value()).show();
});

$(".bt_regenerate_api").on('click', function (event) {
    $.hideAlert();
    var el = $(this);
    bootbox.confirm('{{Etes-vous sûr de vouloir réinitialiser la clef API de }}'+el.attr('data-plugin')+' ?', function (result) {
        if (result) {
         $.ajax({
            type: "POST", 
            url: "core/ajax/config.ajax.php",
            data: {
                action: "genApiKey",
                plugin:el.attr('data-plugin'),
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
                el.closest('.input-group').find('.span_apikey').value(data.result);
            }
        });
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

$("#bt_cleanCache").on('click', function (event) {
    $.hideAlert();
    cleanCache();
});

$("#bt_flushCache").on('click', function (event) {
    $.hideAlert();
    flushCache();
});

$("#bt_clearJeedomLastDate").on('click', function (event) {
    $.hideAlert();
    clearJeedomDate();
});

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveGeneraleConfig").click();
});

$("#bt_saveGeneraleConfig").on('click', function (event) {
    $.hideAlert();
    saveConvertColor();
    saveObjectSummary();
    var config = $('#config').getValues('.configKey')[0];
    config.actionOnMessage = json_encode($('#div_actionOnMessage .actionOnMessage').getValues('.expressionAttr'));
    jeedom.config.save({
        configuration: config,
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
                    loadAactionOnMessage();
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

$('#bt_addActionOnMessage').on('click',function(){
    addActionOnMessage();
});


function loadAactionOnMessage(){
    $('#div_actionOnMessage').empty();
    jeedom.config.load({
        configuration: 'actionOnMessage',
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            if(data == ''){
                return;
            }
            actionOptions = [];
            for (var i in data) {
                addActionOnMessage(data[i]);
            }
            jeedom.cmd.displayActionsOption({
                params : actionOptions,
                async : false,
                error: function (error) {
                  $('#div_alert').showAlert({message: error.message, level: 'danger'});
              },
              success : function(data){
                for(var i in data){
                    $('#'+data[i].id).append(data[i].html.html);
                }
                taAutosize();
            }
        });
        }
    });
}

function addActionOnMessage(_action) {
    if (!isset(_action)) {
        _action = {};
    }
    if (!isset(_action.options)) {
        _action.options = {};
    }
    var div = '<div class="actionOnMessage">';
    div += '<div class="form-group ">';
    div += '<label class="col-sm-1 control-label">Action</label>';
    div += '<div class="col-sm-2">';
    div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour desactiver l\'action}}" />';
    div += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="background" title="{{Cocher pour que la commande s\'éxecute en parrallele des autres actions}}" />';
    div += '</div>';
    div += '<div class="col-sm-4">';
    div += '<div class="input-group">';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-default bt_removeAction btn-sm"><i class="fa fa-minus-circle"></i></a>';
    div += '</span>';
    div += '<input class="expressionAttr form-control input-sm cmdAction" data-l1key="cmd" />';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-default btn-sm listAction" title="{{Sélectionner un mot-clé}}"><i class="fa fa-tasks"></i></a>';
    div += '<a class="btn btn-default btn-sm listCmdAction"><i class="fa fa-list-alt"></i></a>';
    div += '</span>';
    div += '</div>';
    div += '</div>';
    var actionOption_id = uniqId();
    div += '<div class="col-sm-5 actionOptions" id="'+actionOption_id+'">';
    div += '</div>';
    div += '</div>';
    $('#div_actionOnMessage').append(div);
    $('#div_actionOnMessage .actionOnMessage:last').setValues(_action, '.expressionAttr');
    actionOptions.push({
        expression : init(_action.cmd, ''),
        options : _action.options,
        id : actionOption_id
    });
}

$("body").delegate('.bt_removeAction', 'click', function () {
    $(this).closest('.actionOnMessage').remove();
});

$('body').delegate('.cmdAction.expressionAttr[data-l1key=cmd]', 'focusout', function (event) {
    var expression = $(this).closest('.actionOnMessage').getValues('.expressionAttr');
    var el = $(this);
    jeedom.cmd.displayActionOption($(this).value(), init(expression[0].options), function (html) {
        el.closest('.actionOnMessage').find('.actionOptions').html(html);
        taAutosize();
    })
});

$("body").delegate(".listCmdAction", 'click', function () {
    var el = $(this).closest('.actionOnMessage').find('.expressionAttr[data-l1key=cmd]');
    jeedom.cmd.getSelectModal({cmd: {type: 'action'}}, function (result) {
        el.value(result.human);
        jeedom.cmd.displayActionOption(el.value(), '', function (html) {
            el.closest('.actionOnMessage').find('.actionOptions').html(html);
            taAutosize();
        });
    });
});

$("body").delegate(".listAction", 'click', function () {
  var el = $(this).closest('.actionOnMessage').find('.expressionAttr[data-l1key=cmd]');
  jeedom.getSelectActionModal({}, function (result) {
    el.value(result.human);
    jeedom.cmd.displayActionOption(el.value(), '', function (html) {
      el.closest('.actionOnMessage').find('.actionOptions').html(html);
      taAutosize();
  });
});
});

$('.bt_selectAlertCmd').on('click', function () {
    var type=$(this).attr('data-type');
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'message'}}, function (result) {
        $('.configKey[data-l1key="alert::'+type+'Cmd"]').atCaret('insert', result.human);
    });
});

$('.bt_selectWarnMeCmd').on('click', function () {
    jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'message'}}, function (result) {
        $('.configKey[data-l1key="interact::warnme::defaultreturncmd"]').value(result.human);
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
        loadAactionOnMessage();
        modifyWithoutSave = false;
    }
});

$('#div_pageContainer').delegate('.configKey', 'change', function () {
    modifyWithoutSave = true;
});


$('#bt_resetHour').on('click',function(){
   $.ajax({
    type: "POST", 
    url: "core/ajax/jeedom.ajax.php", 
    data: {
        action: "resetHour"
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
        loadPage('index.php?v=d&p=administration');
    }
});
});

$('#bt_resetHwKey').on('click',function(){
   $.ajax({
    type: "POST", 
    url: "core/ajax/jeedom.ajax.php", 
    data: {
        action: "resetHwKey"
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

$('#bt_removeTimelineEvent').on('click',function(){
    jeedom.removeTimelineEvents({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#div_alert').showAlert({message: '{{Evènement de la timeline supprimé avec succès}}', level: 'success'});
        }
    });
});

$('#bt_removeBanIp').on('click',function(){
    jeedom.user.removeBanIp({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            window.location.reload();
        }
    });
});

function clearJeedomDate() {
    $.ajax({
        type: "POST", 
        url: "core/ajax/jeedom.ajax.php", 
        data: {
            action: "clearDate"
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
            $('#in_jeedomLastDate').value('');
        }
    });
}


function flushCache() {
  jeedom.cache.flush({
    error: function (error) {
     $('#div_alert').showAlert({message: data.result, level: 'danger'});
 },
 success: function (data) {
    updateCacheStats();
    $('#div_alert').showAlert({message: '{{Cache vidé}}', level: 'success'});
}
});
}

function cleanCache() {
    jeedom.cache.clean({
        error: function (error) {
         $('#div_alert').showAlert({message: data.result, level: 'danger'});
     },
     success: function (data) {
        updateCacheStats();
        $('#div_alert').showAlert({message: '{{Cache nettoyé}}', level: 'success'});
    }
});
}

function updateCacheStats(){
 jeedom.cache.stats({
    error: function (error) {
     $('#div_alert').showAlert({message: data.result, level: 'danger'});
 },
 success: function (data) {
    $('#span_cacheObject').html(data.count);
}
});
}


/********************Convertion************************/
function printConvertColor() {
    $.ajax({
        type: "POST", 
        url: "core/ajax/config.ajax.php", 
        data: {
            action: "getKey",
            key: 'convertColor'
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
        success: function (data) { 
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

/**************************SYSTEM***********************************/
$('#bt_accessDbAdministration').on('click',function(){
    $('#md_modal').dialog({title: "{{Administration base de données}}"});
    $("#md_modal").load('index.php?v=d&modal=db.action').dialog('open');
});

/**************************Summary***********************************/

$('#bt_addObjectSummary').on('click', function () {
    addObjectSummary();
});

$('#div_pageContainer').undelegate('.objectSummary .objectSummaryAction[data-l1key=chooseIcon]', 'click').delegate('.objectSummary .objectSummaryAction[data-l1key=chooseIcon]', 'click', function () {
    var objectSummary = $(this).closest('.objectSummary');
    chooseIcon(function (_icon) {
        objectSummary.find('.objectSummaryAttr[data-l1key=icon]').empty().append(_icon);
    });
});

$('#div_pageContainer').undelegate('.objectSummary .objectSummaryAction[data-l1key=remove]', 'click').delegate('.objectSummary .objectSummaryAction[data-l1key=remove]', 'click', function () {
    $(this).closest('.objectSummary').remove();
});

$('#div_pageContainer').undelegate('.objectSummary .objectSummaryAction[data-l1key=createVirtual]', 'click').delegate('.objectSummary .objectSummaryAction[data-l1key=createVirtual]', 'click', function () {
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
    $.ajax({
        type: "POST", 
        url: "core/ajax/config.ajax.php", 
        data: {
            action: "getKey",
            key: 'object:summary'
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
    tr += '<option value="text">{{Texte}}</option>';
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
        success: function (data) { 
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            printObjectSummary();
            modifyWithoutSave = false;
        }
    });
}