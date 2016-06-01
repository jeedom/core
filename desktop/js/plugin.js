
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
      if(isset(data.update) && isset(data.update.localVersion)){
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
      if (isset(data.update) && isset(data.update.source) && data.update.source == 'market' && isset(data.status) && isset(data.status.market) && data.status.market == 1) {
        $('#span_plugin_market').append('<a class="btn btn-default btn-xs viewOnMarket" data-market_logicalId="' + data.id + '" style="margin-right : 5px;"><i class="fa fa-cloud-download"></i> {{Voir sur le market}}</a>')
      }

      if (isset(data.update) && isset(data.update.source) && isset(data.status) && isset(data.status.market_owner) && data.status.market_owner == 1) {
        $('#span_plugin_market').append('<a class="btn btn-warning btn-xs sendOnMarket" data-market_logicalId="' + data.id + '"><i class="fa fa-cloud-upload"></i> {{Envoyer sur le market}}</a>')
      }
      $('#span_plugin_delete').empty().append('<a class="btn btn-danger btn-xs removePlugin" data-market_logicalId="' + data.id + '"><i class="fa fa-trash"></i> {{Supprimer}}</a>');
      $('#span_plugin_doc').empty();
      if(isset(data.info.doc) && data.info.doc != ''){
        $('#span_plugin_doc').append('<a class="btn btn-primary btn-xs" target="_blank" href="'+data.info.doc+'"><i class="fa fa-book"></i> {{Documentation}}</a>');
      }
      if(isset(data.info.changelog) && data.info.changelog != ''){
        $('#span_plugin_doc').append('<a class="btn btn-primary btn-xs" target="_blank" href="'+data.info.changelog+'"><i class="fa fa-book"></i> {{Changelog}}</a>');
      }

      if (data.checkVersion != -1) {
        $('#span_plugin_require').html('<span>' + data.require + '</span>');
      } else {
        $('#span_plugin_require').html('<span class="label label-danger">' + data.require + '</span>');
      }

      $('#div_configPanel').hide();
      $('#div_plugin_panel').empty();
      if(isset(data.display) && data.display != ''){
        $('#div_configPanel').show();
        var config_panel_html = '<div class="form-group">';
        config_panel_html += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Afficher le panel desktop}}</label>';
        config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
        config_panel_html += '<input type="checkbox" class="configKey tooltips bootstrapSwitch" data-l1key="displayDesktopPanel" />';
        config_panel_html += '</div>';
        config_panel_html += '</div>';
        $('#div_plugin_panel').append(config_panel_html);
      }

      if(isset(data.mobile) && data.mobile != ''){
        $('#div_configPanel').show();
        var config_panel_html = '<div class="form-group">';
        config_panel_html += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Afficher le panel mobile}}</label>';
        config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
        config_panel_html += '<input type="checkbox" class="configKey tooltips bootstrapSwitch" data-l1key="displayMobilePanel" />';
        config_panel_html += '</div>';
        config_panel_html += '</div>';
        $('#div_plugin_panel').append(config_panel_html);
      }

      initCheckBox();

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
   var log_conf = '';
   log_conf = '<form class="form-horizontal">';
   log_conf += '<div class="form-group">';
   log_conf += '<label class="col-sm-2 control-label">{{Niveau de log local}}</label>';
   log_conf += '<div class="col-sm-6">';
   log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="1000" /> {{Aucun}}</label>';
   log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="default" /> {{Defaut}}</label>';
   log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="100" /> {{Debug}}</label>';
   log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="200" /> {{Info}}</label>';
   log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="300" /> {{Warning}}</label>';
   log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="400" /> {{Error}}</label>';
   log_conf += '</div>';
   log_conf += '</div>';
   for(i in data.logs){
    if(!isset(data.logs[i].log) || !$.isArray(data.logs[i].log)){
     continue;
   }
   if(i != -1){
     log_conf += '<div class="form-group slaveConfig" data-slave_id="'+i+'">';
     log_conf += '<label class="col-sm-2 control-label">{{Niveau de log}} '+data.logs[i].name+'</label>';
     log_conf += '<div class="col-sm-6">';
     log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="slaveConfigKey" data-l1key="log::level::' + data.id + '" data-l2key="1000" /> {{Aucun}}</label>';
     log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="slaveConfigKey" data-l1key="log::level::' + data.id + '" data-l2key="default" /> {{Defaut}}</label>';
     log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="slaveConfigKey" data-l1key="log::level::' + data.id + '" data-l2key="100" /> {{Debug}}</label>';
     log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="slaveConfigKey" data-l1key="log::level::' + data.id + '" data-l2key="200" /> {{Info}}</label>';
     log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="slaveConfigKey" data-l1key="log::level::' + data.id + '" data-l2key="300" /> {{Warning}}</label>';
     log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="slaveConfigKey" data-l1key="log::level::' + data.id + '" data-l2key="400" /> {{Error}}</label>';
     log_conf += '</div>';
     log_conf += '</div>';
   }
   log_conf += '<div class="form-group">';
   log_conf += '<label class="col-sm-2 control-label">{{Voir les logs de}} '+data.logs[i].name+'</label>';
   log_conf += '<div class="col-sm-10">';
   for(j in data.logs[i].log){
    log_conf += '<a class="btn btn-info bt_plugin_conf_view_log" data-slaveId="'+i+'" data-log="'+data.logs[i].log[j]+'"><i class="fa fa-paperclip"></i>  '+data.logs[i].log[j].charAt(0).toUpperCase() + data.logs[i].log[j].slice(1)+'</a> ';
  }
  log_conf += '</div>';
  log_conf += '</div>';
}
log_conf += '</form>';
$('#div_plugin_log').empty().append(log_conf);

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
   jeedom.config.load({
    configuration: $('#div_plugin_panel').getValues('.configKey')[0],
    plugin: $('.li_plugin.active').attr('data-plugin_id'),
    error: function (error) {
      alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('#div_plugin_panel').setValues(data, '.configKey');
      modifyWithoutSave = false;
      initTooltips();
      initExpertMode();
    }
  });
   jeedom.config.load({
    configuration: $('#div_plugin_log').getValues('.configKey')[0],
    error: function (error) {
      alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('#div_plugin_log').setValues(data, '.configKey');
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

$('#bt_savePluginPanelConfig').off('click').on('click',function(){
 jeedom.config.save({
  configuration: $('#div_plugin_panel').getValues('.configKey')[0],
  plugin: $('.li_plugin.active').attr('data-plugin_id'),
  error: function (error) {
    alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
  },
  success: function () {
    alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde de la configuration des panels effectuée}}', level: 'success'});
    modifyWithoutSave = false;
  }
});
})

$('#bt_savePluginLogConfig').off('click').on('click',function(){
 jeedom.config.save({
  configuration: $('#div_plugin_log').getValues('.configKey')[0],
  error: function (error) {
    alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
  },
  success: function () {
    alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde de la configuration des logs effectuée}}', level: 'success'});
    modifyWithoutSave = false;
  }
});

 $('#div_plugin_log .slaveConfig').each(function(){
  var slave_id = $(this).attr('data-slave_id');
  jeedom.jeeNetwork.saveConfig({
    configuration: $('#div_plugin_log .slaveConfig[data-slave_id='+slave_id+']').getValues('.slaveConfigKey')[0],
    id: slave_id,
    error: function (error) {
      alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
      modifyWithoutSave = false;
    }
  });
});
})

$('#div_plugin_log').on('click','.bt_plugin_conf_view_log',function(){
 if($('#md_modal').is(':visible')){
   $('#md_modal2').dialog({title: "{{Log du plugin}}"});
   $("#md_modal2").load('index.php?v=d&modal=log.display&log='+$(this).attr('data-log')+'&slaveId='+$(this).attr('data-slaveId')).dialog('open');
 }else{
   $('#md_modal').dialog({title: "{{Log du plugin}}"});
   $("#md_modal").load('index.php?v=d&modal=log.display&log='+$(this).attr('data-log')+'&slaveId='+$(this).attr('data-slaveId')).dialog('open');
 }
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

  $('#div_plugin_configuration .slaveConfig').each(function(){
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

$('#bt_addPluginFromOtherSource').on('click',function(){
  $('#md_modal').dialog({title: "{{Ajouter un plugin}}"});
  $('#md_modal').load('index.php?v=d&modal=update.add').dialog('open');
});
