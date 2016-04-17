
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
 if($('#md_modal').is(':visible')){
    $('#bt_returnToThumbnailDisplay').hide();
    $('#div_confPlugin').addClass('col-lg-12').removeClass('col-md-9 col-sm-8');
    alert_div_plugin_configuration = $('#div_alertPluginConfiguration');
}else{
    alert_div_plugin_configuration = $('#div_alert');
}


setTimeout(function(){

  $('.pluginListContainer').packery();
},100);

if(!$('#md_modal').is(':visible')){
   if((isset(userProfils.doNotAutoHideMenu) && userProfils.doNotAutoHideMenu == 1) || jQuery.support.touch){
    $('#sd_pluginList').show();
    setTimeout(function(){
      $('.pluginListContainer').packery();
  },100);
}
if((!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1) && !jQuery.support.touch){
    $('#div_resumePluginList').addClass('col-lg-12').removeClass('col-md-9 col-sm-8');
    $('#div_confPlugin').addClass('col-lg-12').removeClass('col-md-9 col-sm-8');
    $('#bt_displayPluginList').on('mouseenter',function(){
     var timer = setTimeout(function(){
        $('#bt_displayPluginList').find('i').hide();
        $('#div_resumePluginList').addClass('col-md-9 col-sm-8').removeClass('col-lg-12');
        $('#div_confPlugin').addClass('col-md-9 col-sm-8').removeClass('col-lg-12');
        $('#sd_pluginList').show();
        $('.pluginListContainer').packery();
    }, 100);
     $(this).data('timerMouseleave', timer)
 }).on("mouseleave", function(){
  clearTimeout($(this).data('timerMouseleave'));
});

 $('#sd_pluginList').on('mouseleave',function(){
   var timer = setTimeout(function(){
     $('#sd_pluginList').hide();
     $('#bt_displayPluginList').find('i').show();
     $('#div_resumePluginList').removeClass('col-md-9 col-sm-8').addClass('col-lg-12');
     $('#div_confPlugin').removeClass('col-md-9 col-sm-8').addClass('col-lg-12');
     $('.pluginListContainer').packery();
 }, 300);
   $(this).data('timerMouseleave', timer);
}).on("mouseenter", function(){
  clearTimeout($(this).data('timerMouseleave'));
});

}
}

$(".li_plugin,.pluginDisplayCard").on('click', function () {
    $.hideAlert();
    $('#div_resumePluginList').hide();
    $('.li_plugin').removeClass('active');
    $('.li_plugin[data-plugin_id='+$(this).attr('data-plugin_id')+']').addClass('active');
    $.showLoading();
    jeedom.plugin.get({
        id: $(this).attr('data-plugin_id'),
        error: function (error) {
            alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#span_plugin_id').html(data.id);
            $('#span_plugin_name').html(data.name);
            $('#span_plugin_author').html(data.author);
            if(isset(data.update) && isset(data.update.configuration) && isset(data.update.configuration.version)){
                $('#span_plugin_install_version').html(data.update.configuration.version);
            }else{
                $('#span_plugin_install_version').html('');
            }
            if(isset(data.update) && isset(data.update) && isset(data.update.localVersion)){
                $('#span_plugin_install_date').html(data.update.localVersion);
            }else{
                $('#span_plugin_install_date').html('');
            }
            $('#span_plugin_licence').html(data.licence);
            if($.trim(data.installation) == '' || $.trim(data.installation) == 'Aucune'){
                $('#span_plugin_installation').closest('.panel').hide();
            }else{
                $('#span_plugin_installation').closest('.panel').show();
                $('#span_plugin_installation').html(data.installation);
            }

            if(data.hasDependency == 0 || data.activate != 1){
                $('#div_plugin_dependancy').closest('.panel').hide();
            }else{
                $('#div_plugin_dependancy').closest('.panel').show();
                $("#div_plugin_dependancy").load('index.php?v=d&modal=plugin.dependancy&plugin_id='+data.id);
            }
            if(data.hasOwnDeamon == 0 || data.activate != 1){
                $('#div_plugin_deamon').closest('.panel').hide();
            }else{
                $('#div_plugin_deamon').closest('.panel').show();
                $("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+data.id);
            }
            
            $('#span_plugin_market').empty();
            if (data.status.market == 1) {
                $('#span_plugin_market').append('<a class="btn btn-default btn-xs viewOnMarket" data-market_logicalId="' + data.id + '" style="margin-right : 5px;"><i class="fa fa-cloud-download"></i> {{Voir sur le market}}</a>')
            }

            if (data.status.market_owner == 1) {
                $('#span_plugin_market').append('<a class="btn btn-warning btn-xs sendOnMarket" data-market_logicalId="' + data.id + '"><i class="fa fa-cloud-upload"></i> {{Envoyer sur le market}}</a>')
            }
            $('#span_plugin_delete').empty().append('<a class="btn btn-danger btn-xs removePlugin" data-market_logicalId="' + data.id + '"><i class="fa fa-trash"></i> {{Supprimer}}</a>');
            $('#span_plugin_doc').empty().append('<a class="btn btn-primary btn-xs" target="_blank" href="https://www.jeedom.fr/doc/documentation/plugins/' + data.id + '/fr_FR/' + data.id + '.html"><i class="fa fa-book"></i> {{Documentation}}</a>');

            if (data.checkVersion != -1) {
                $('#span_plugin_require').html('<span>' + data.require + '</span>');
            } else {
                $('#span_plugin_require').html('<span class="label label-danger">' + data.require + '</span>');
            }

            $('#div_plugin_toggleState').empty();
            if (data.checkVersion != -1) {
             var html = '<form class="form-horizontal">';
             html += '<div class="form-group">';
             html += '<label class="col-sm-2 control-label">{{Statut}}</label>';
             html += '<div class="col-sm-2">';
             if (data.activate == 1) {
                $('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-danger').addClass('panel-success');
                html += '<span class="label label-success" style="font-size:1em;position:relative;top:7px;">{{Actif}}</span>';
            }else{
                $('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-success').addClass('panel-danger');
                html += '<span class="label label-danger" style="font-size:1em;position:relative;top:7px;">{{Inactif}}</span>';
            }
            html += '</div>';
            html += '<label class="col-sm-2 control-label">{{Action}}</label>';
            html += '<div class="col-sm-4">';
            if (data.activate == 1) {
               html += '<a class="btn btn-danger togglePlugin" data-state="0" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fa fa-times"></i> {{Désactiver}}</a>';
           }else{
               html += '<a class="btn btn-success togglePlugin" data-state="1" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fa fa-check"></i> {{Activer}}</a>';
           }
           html += '</div>';
           html += '</div>';
           html += '</form>';
           $('#div_plugin_toggleState').html(html);
       }else{
           $('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-success').addClass('panel-danger');
           $('#div_plugin_toggleState').html('{{Votre version de jeedom ne permet pas d\'activer ce plugin}}');
       }
       initExpertMode();
       $('#div_plugin_configuration').empty();
       if (data.checkVersion != -1) {
        if (data.configurationPath != '' && data.activate == 1) {
         $('#div_plugin_configuration').load('index.php?v=d&plugin='+data.id+'&configure=1', function () {
            if($.trim($('#div_plugin_configuration').html()) == ''){
                $('#div_plugin_configuration').closest('.panel').hide();
                return;
            }else{
               $('#div_plugin_configuration').closest('.panel').show();
           }
           jeedom.config.load({
            configuration: $('#div_plugin_configuration').getValues('.configKey')[0],
            plugin: $('.li_plugin.active').attr('data-plugin_id'),
            error: function (error) {
                alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                $('#div_plugin_configuration').setValues(data, '.configKey');
                $('#div_plugin_configuration').parent().show();
                modifyWithoutSave = false;
                initTooltips();
                initExpertMode();
            }
        });
           $('.slaveConfig').each(function(){
            var slave_id = $(this).attr('data-slave_id');
            jeedom.jeeNetwork.loadConfig({
                configuration: $('#div_plugin_configuration .slaveConfig[data-slave_id='+slave_id+']').getValues('.slaveConfigKey')[0],
                plugin: $('.li_plugin.active').attr('data-plugin_id'),
                id: slave_id,
                error: function (error) {
                    alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    $('#div_plugin_configuration .slaveConfig[data-slave_id='+slave_id+']').setValues(data, '.slaveConfigKey');
                    modifyWithoutSave = false;
                    initTooltips();
                    initExpertMode();
                }
            });
        })
       });
     } else {
        $('#div_plugin_configuration').closest('.panel').hide();
    }
} else {
    $('#div_plugin_configuration').closest('.alert').hide();
}
$('#div_confPlugin').show();
modifyWithoutSave = false;
}
});
return false;
});

$('#span_plugin_delete').delegate('.removePlugin','click',function(){
    var _el = $(this);
    bootbox.confirm('{{Etes vous sûr de vouloir supprimer ce plugin ?}}', function (result) {
        if (result) {
            $.hideAlert();
            jeedom.update.remove({
                id: _el.attr('data-market_logicalId'),
                error: function (error) {
                    alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                 loadPage('index.php?v=d&p=plugin');
             }
         });
        }
    });
});

$("#div_plugin_toggleState").delegate(".togglePlugin", 'click', function () {
    var _el = $(this);
    jeedom.plugin.toggle({
        id: _el.attr('data-plugin_id'),
        state: _el.attr('data-state'),
        error: function (error) {
            alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            if($('#md_modal').is(':visible')){
                $("#md_modal").load('index.php?v=d&p=plugin&ajax=1&id=' + _el.attr('data-plugin_id')).dialog('open');
            }else{
             window.location.href = 'index.php?v=d&p=plugin&id=' + _el.attr('data-plugin_id');
         }
     }
 });
});

$('#bt_uploadPlugin').fileupload({
    dataType: 'json',
    replaceFileInput: false,
    done: function (e, data) {
        if (data.result.state != 'ok') {
            alert_div_plugin_configuration.showAlert({message: data.result.result, level: 'danger'});
            return;
        }
        alert_div_plugin_configuration.showAlert({message: '{{Plugin ajouté avec succès. Recharger la page pour le voir.}}', level: 'success'});
    }
});

if (sel_plugin_id != -1) {
    if ($('#ul_plugin .li_plugin[data-plugin_id=' + sel_plugin_id + ']').length != 0) {
        $('#ul_plugin .li_plugin[data-plugin_id=' + sel_plugin_id + ']').click();
    } else {
        $('#ul_plugin .li_plugin:first').click();
    }
} 

$('#bt_returnToThumbnailDisplay').on('click',function(){
    $('#div_resumePluginList').show();
    $('#div_confPlugin').hide();
    $('.pluginListContainer').packery();
});

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_savePluginConfig").click();
});

$("#bt_savePluginConfig").on('click', function (event) {
    savePluginConfig();
    return false;
});

$('#bt_displayMarket,#bt_displayMarket2').on('click', function () {
    $('#md_modal').dialog({title: "{{Market Jeedom}}"});
    $('#md_modal').load('index.php?v=d&modal=market.list&type=plugin').dialog('open');
});

$('body').delegate('.viewOnMarket', 'click', function () {
    $('#md_modal2').dialog({title: "{{Market Jeedom}}"});
    $('#md_modal2').load('index.php?v=d&modal=market.display&type=plugin&logicalId=' + $(this).attr('data-market_logicalId')).dialog('open');
});

$('body').delegate('.sendOnMarket', 'click', function () {
    $('#md_modal2').dialog({title: "{{Envoyer sur le market}}"});
    $('#md_modal2').load('index.php?v=d&modal=market.send&type=plugin&logicalId=' + $(this).attr('data-market_logicalId')).dialog('open');
});

$('body').delegate('.configKey', 'change', function () {
    modifyWithoutSave = true;
});

function savePluginConfig(_param) {
    jeedom.config.save({
        configuration: $('#div_plugin_configuration').getValues('.configKey')[0],
        plugin: $('.li_plugin.active').attr('data-plugin_id'),
        error: function (error) {
            alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
        },
        success: function () {



            alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
            modifyWithoutSave = false;
            var postSave = $('.li_plugin.active').attr('data-plugin_id')+'_postSaveConfiguration';
            if (typeof window[postSave] == 'function'){
                window[postSave]();
            }
            if (isset(_param) && typeof _param.success == 'function'){
                _param.success(0);
            }
            if(!isset(_param) || !isset(_param.relaunchDeamon) || _param.relaunchDeamon){
                jeedom.plugin.deamonStart({
                    id : $('.li_plugin.active').attr('data-plugin_id'),
                    slave_id: 0,
                    forceRestart: 1,
                    error: function (error) {
                        alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        $("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+$('.li_plugin.active').attr('data-plugin_id'));
                    }
                });
            }
        }
    });

    $('.slaveConfig').each(function(){
        var slave_id = $(this).attr('data-slave_id');
        jeedom.jeeNetwork.saveConfig({
            configuration: $('#div_plugin_configuration .slaveConfig[data-slave_id='+slave_id+']').getValues('.slaveConfigKey')[0],
            plugin: $('.li_plugin.active').attr('data-plugin_id'),
            id: slave_id,
            error: function (error) {
                alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
                modifyWithoutSave = false;
                var postSave = $('.li_plugin.active').attr('data-plugin_id')+'_postSaveSlaveConfiguration';
                if (typeof window[postSave] == 'function'){
                    window[postSave](slave_id);
                }
                if (isset(_param) && typeof _param.success == 'function'){
                    _param.success(slave_id);
                }
                if(!isset(_param) || !isset(_param.relaunchDeamon) || _param.relaunchDeamon){
                 jeedom.plugin.deamonStart({
                    id : $('.li_plugin.active').attr('data-plugin_id'),
                    slave_id: slave_id,
                    forceRestart: 1,
                    error: function (error) {
                        alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        $("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+$('.li_plugin.active').attr('data-plugin_id'));
                    }
                });
             }
         }
     });
    });
}

