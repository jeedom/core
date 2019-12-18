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

//searching
$('#in_searchPlugin').off('keyup').keyup(function () {
  var search = $(this).value()
  if (search == '') {
    $('.pluginDisplayCard').show()
    $('.pluginListContainer').packery()
    return;
  }
  search = normTextLower(search)

  $('.pluginDisplayCard').hide()
  $('.pluginDisplayCard .name').each(function(){
    var text = $(this).text()
    text = normTextLower(text)
    if (text.indexOf(search) >= 0) {
      $(this).closest('.pluginDisplayCard').show()
    }
  });
  $('.pluginListContainer').packery()
})

$('#bt_resetPluginSearch').on('click', function () {
  $('#in_searchPlugin').val('')
  $('#in_searchPlugin').keyup()
})

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


$('body').off('click','.bt_refreshPluginInfo').on('click','.bt_refreshPluginInfo',function(){
  $('.pluginDisplayCard[data-plugin_id='+$('#span_plugin_id').text()+']').click();
});

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
      if(isset(data.update) && isset(data.update.localVersion)){
        localVer = data.update.localVersion
        if (localVer.length > 20) localVer = localVer.substring(0,20) + '...'
        $('#span_plugin_install_date').html(localVer);
      }else{
        $('#span_plugin_install_date').html('');
      }
      $('#span_plugin_license').html(data.license);
      if($.trim(data.installation) == '' || $.trim(data.installation) == 'Aucune'){
        $('#span_plugin_installation').closest('.panel').hide();
      }else{
        $('#span_plugin_installation').closest('.panel').show();
        $('#span_plugin_installation').html(data.installation);
      }

      if(isset(data.update) && isset(data.update.configuration) && isset(data.update.configuration.version)){
        $('#span_plugin_install_version').html(data.update.configuration.version);
      }else{
        $('#span_plugin_install_version').html('');
      }

      $('#div_plugin_dependancy').closest('.panel').parent().addClass('col-md-6')
      $('#div_plugin_deamon').closest('.panel').parent().addClass('col-md-6')
      if(data.hasDependency == 0 || data.activate != 1){
        $('#div_plugin_dependancy').closest('.panel').hide();
        $('#div_plugin_deamon').closest('.panel').parent().removeClass('col-md-6')
      }else{
        $('#div_plugin_dependancy').closest('.panel').show();
        $('#div_plugin_dependancy').closest('.panel')
        $("#div_plugin_dependancy").load('index.php?v=d&modal=plugin.dependancy&plugin_id='+data.id);
      }

      if(data.hasOwnDeamon == 0 || data.activate != 1){
        $('#div_plugin_deamon').closest('.panel').hide();
        $('#div_plugin_dependancy').closest('.panel').parent().removeClass('col-md-6')
      }else{
        $('#div_plugin_deamon').closest('.panel').show();
        $("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+data.id);
      }
      $('#span_right_button').empty();
      $('#span_right_button').append('<a class="btn btn-sm roundedLeft bt_refreshPluginInfo"><i class="fas fa-sync"></i> {{Rafraichir}}</a>');
      if(isset(data.documentation) && data.documentation != ''){
        $('#span_right_button').append('<a class="btn btn-primary btn-sm" target="_blank" href="'+data.documentation+'"><i class="fas fa-book"></i> {{Documentation}}</a>');
      }
      if(isset(data.changelog) && data.changelog != ''){
        $('#span_right_button').append('<a class="btn btn-primary btn-sm" target="_blank" href="'+data.changelog+'"><i class="fas fa-book"></i> {{Changelog}}</a>');
      }
      if(isset(data.info.display) && data.info.display != ''){
        $('#span_right_button').append('<a class="btn btn-primary btn-sm" target="_blank" href="'+data.info.display+'"><i class="fas fa-book"></i> {{Détails}}</a>');
      }
      $('#span_right_button').append('<a class="btn btn-danger btn-sm removePlugin roundedRight" data-market_logicalId="' + data.id + '"><i class="fas fa-trash"></i> {{Supprimer}}</a>');
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
        config_panel_html += '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-6 control-label">{{Afficher le panneau desktop}}</label>';
        config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
        config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="displayDesktopPanel" />';
        config_panel_html += '</div>';
        config_panel_html += '</div>';
        $('#div_plugin_panel').append(config_panel_html);
      }

      if(isset(data.mobile) && data.mobile != ''){
        $('#div_configPanel').show();
        var config_panel_html = '<div class="form-group">';
        config_panel_html += '<label class="col-lg-4 col-md-4 col-sm-4 col-xs-6 control-label">{{Afficher le panneau mobile}}</label>';
        config_panel_html += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
        config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="displayMobilePanel" />';
        config_panel_html += '</div>';
        config_panel_html += '</div>';
        $('#div_plugin_panel').append(config_panel_html);
      }

      $('#div_plugin_functionality').empty();
      count = 0;
      var config_panel_html = '<div class="row">';
      config_panel_html += '<div class="col-sm-6">';
      for(var i in data.functionality){
        config_panel_html += '<div class="form-group">';
        config_panel_html += '<label class="col-lg-3 col-md-4 col-sm-4 col-xs-6 control-label">'+i+'</label>';
        config_panel_html += '<label class="col-lg-2 col-md-2 col-sm-3 col-xs-6">';
        if(data.functionality[i].exists){
          config_panel_html += '<span class="label label-success">{{Oui}}</span>';
          config_panel_html += '</label>';
          if(data.functionality[i].controlable){
            config_panel_html += '<label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Activer}}</label>';
            config_panel_html += '<label class="col-lg-2 col-md-2 col-sm-2 col-xs-6">';
            config_panel_html += '<input type="checkbox" class="configKey tooltips" data-l1key="functionality::'+i+'::enable" checked/>';
            config_panel_html += '</label>';
          }
        }else{
          config_panel_html += '<span class="label label-danger">{{Non}}</span>';
          config_panel_html += '</label>';
        }
        config_panel_html += '</div>';
        count++;
        if(count == 5){
          config_panel_html += '</div>';
          config_panel_html += '<div class="col-sm-6">';
        }
      }
      config_panel_html += '</div>';
      config_panel_html += '</div>';
      $('#div_plugin_functionality').append(config_panel_html);

      $('#div_plugin_toggleState').empty();
      if (data.checkVersion != -1) {
        var html = '<form class="form-horizontal"><fieldset>';
        html += '<div class="form-group">';
        html += '<label class="col-sm-2 control-label">{{Statut}}</label>';
        html += '<div class="col-sm-4">';
        if (data.activate == 1) {
          $('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-danger').addClass('panel-success');
          html += '<span class="label label-success">{{Actif}}</span>';
        }else{
          $('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-success').addClass('panel-danger');
          html += '<span class="label label-danger">{{Inactif}}</span>';
        }
        html += '</div>';
        html += '<label class="col-sm-2 control-label">{{Action}}</label>';
        html += '<div class="col-sm-4">';
        if (data.activate == 1) {
          html += '<a class="btn btn-danger btn-sm togglePlugin" data-state="0" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fas fa-times"></i> {{Désactiver}}</a>';
        }else{
          html += '<a class="btn btn-success btn-sm togglePlugin" data-state="1" data-plugin_id="' + data.id + '" style="position:relative;top:-2px;"><i class="fas fa-check"></i> {{Activer}}</a>';
        }
        html += '</div>';
        html += '</div>';
        html += '</fieldset></form>';
        $('#div_plugin_toggleState').html(html);
      }else{
        $('#div_plugin_toggleState').closest('.panel').removeClass('panel-default panel-success').addClass('panel-danger');
        $('#div_plugin_toggleState').html('{{Votre version de}} '+JEEDOM_PRODUCT_NAME+' {{ne permet pas d\'activer ce plugin}}');
      }
      var log_conf = '';
      for(var i in  data.logs){
        log_conf = '<form class="form-horizontal">';
        log_conf += '<div class="form-group">';
        log_conf += '<label class="col-sm-3 control-label">{{Niveau log}}</label>';
        log_conf += '<div class="col-sm-9">';
        log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="1000" /> {{Aucun}}</label>';
        log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="default" /> {{Defaut}}</label>';
        log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="100" /> {{Debug}}</label>';
        log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="200" /> {{Info}}</label>';
        log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="300" /> {{Warning}}</label>';
        log_conf += '<label class="radio-inline"><input type="radio" name="rd_logupdate' + data.id + '" class="configKey" data-l1key="log::level::' + data.id + '" data-l2key="400" /> {{Error}}</label>';
        log_conf += '</div>';
        log_conf += '</div>';
        log_conf += '<div class="form-group">';
        log_conf += '<label class="col-sm-3 control-label">{{Logs}}</label>';
        log_conf += '<div class="col-sm-9">';
        for(j in data.logs[i].log){
          log_conf += '<a class="btn btn-info bt_plugin_conf_view_log" data-slaveId="'+data.logs[i].id+'" data-log="'+data.logs[i].log[j]+'"><i class="fas fa-paperclip"></i>  '+data.logs[i].log[j].charAt(0).toUpperCase() + data.logs[i].log[j].slice(1)+'</a> ';
        }
        log_conf += '</div>';
        log_conf += '</div>';
        log_conf += '</form>';
      }

      log_conf += '<form class="form-horizontal">';
      log_conf += '<div class="form-group">';
      log_conf += '<label class="col-sm-3 control-label">{{Heartbeat (min)}}</label>';
      log_conf += '<div class="col-sm-2">';
      log_conf += '<input class="configKey form-control" data-l1key="heartbeat::delay::' + data.id + '" />';
      log_conf += '</div>';
      if(data.hasOwnDeamon){
        log_conf += '<label class="col-sm-3 control-label">{{Redémarrer démon}}</label>';
        log_conf += '<div class="col-sm-2">';
        log_conf += '<input type="checkbox" class="configKey" data-l1key="heartbeat::restartDeamon::' + data.id + '" />';
        log_conf += '</div>';
      }
      log_conf += '</div>';
      log_conf += '</form>';

      $('#div_plugin_log').empty().append(log_conf);
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
              plugin: $('#span_plugin_id').text(),
              error: function (error) {
                alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
              },
              success: function (data) {
                $('#div_plugin_configuration').setValues(data, '.configKey');
                $('#div_plugin_configuration').parent().show();
                modifyWithoutSave = false;
              }
            });
          });
        } else {
          $('#div_plugin_configuration').closest('.panel').hide();
        }
        jeedom.config.load({
          configuration: $('#div_plugin_panel').getValues('.configKey')[0],
          plugin: $('#span_plugin_id').text(),
          error: function (error) {
            alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
          },
          success: function (data) {
            $('#div_plugin_panel').setValues(data, '.configKey');
            modifyWithoutSave = false;
          }
        });
        jeedom.config.load({
          configuration: $('#div_plugin_functionality').getValues('.configKey')[0],
          plugin: $('#span_plugin_id').text(),
          error: function (error) {
            alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
          },
          success: function (data) {
            $('#div_plugin_functionality').setValues(data, '.configKey');
            modifyWithoutSave = false;
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
          }
        });
      } else {
        $('#div_plugin_configuration').closest('.alert').hide();
      }
      $('#div_confPlugin').show();
      modifyWithoutSave = false;
      addOrUpdateUrl('id',$('#span_plugin_id').text(), data.name + ' - ' + JEEDOM_PRODUCT_NAME);
      setTimeout(function(){initTooltips($("#div_confPlugin"))},500)
    }
  });
  return false;
});

$('#span_right_button').delegate('.removePlugin','click',function(){
  var _el = $(this);
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer ce plugin ?}}', function (result) {
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

if (typeof(sel_plugin_id) !== "undefined" && sel_plugin_id != -1) {
  if ($('.pluginDisplayCard[data-plugin_id=' + sel_plugin_id + ']').length != 0) {
    $('.pluginDisplayCard[data-plugin_id=' + sel_plugin_id + ']').click();
  } else {
    $('.pluginDisplayCard').first().click();
  }
}

$('#bt_returnToThumbnailDisplay').on('click',function(){
  setTimeout(function(){
    $('.nav li.active').removeClass('active');
    $('a[href="#'+$('.tab-pane.active').attr('id')+'"]').closest('li').addClass('active')
  },500);
  if (modifyWithoutSave) {
    if (!confirm('{{Attention vous quittez une page ayant des données modifiées non sauvegardées. Voulez-vous continuer ?}}')) {
      return;
    }
    modifyWithoutSave = false;
  }
  $('#div_resumePluginList').show();
  $('#div_confPlugin').hide();
  $('.pluginListContainer').packery();
  addOrUpdateUrl('id',null,'{{Gestion Plugins}} - '+JEEDOM_PRODUCT_NAME);
});

jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  $("#bt_savePluginConfig").click();
});

$("#bt_savePluginConfig").on('click', function (event) {
  savePluginConfig();
  return false;
});

$('.displayStore').on('click', function () {
  $('#md_modal').dialog({title: "{{Market}}"});
  $('#md_modal').load('index.php?v=d&modal=update.list&type=plugin&repo='+$(this).attr('data-repo')).dialog('open');
});

$('.pullInstall').on('click', function () {
  jeedom.repo.pullInstall({
    repo : $(this).attr('data-repo'),
    error: function (error) {
      alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      alert_div_plugin_configuration.showAlert({message: '{{Synchronisation réussi. Nombre de plugins installé : }}'+data.number, level: 'success'});
    }
  });
});

$('#div_pageContainer').off( 'change', '.configKey').on( 'change','.configKey:visible',function () {
  modifyWithoutSave = true;
});

$('#bt_savePluginPanelConfig').off('click').on('click',function(){
  jeedom.config.save({
    configuration: $('#div_plugin_panel').getValues('.configKey')[0],
    plugin: $('#span_plugin_id').text(),
    error: function (error) {
      alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde de la configuration des panneaux effectuée}}', level: 'success'});
      modifyWithoutSave = false;
    }
  });
})

$('#bt_savePluginFunctionalityConfig').off('click').on('click',function(){
  jeedom.config.save({
    configuration: $('#div_plugin_functionality').getValues('.configKey')[0],
    plugin: $('#span_plugin_id').text(),
    error: function (error) {
      alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde des fonctionalités effectuée}}', level: 'success'});
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
})

$('#div_plugin_log').on('click','.bt_plugin_conf_view_log',function(){
  if($('#md_modal').is(':visible')){
    $('#md_modal2').dialog({title: "{{Log du plugin}}"});
    $("#md_modal2").load('index.php?v=d&modal=log.display&log='+$(this).attr('data-log')).dialog('open');
  }else{
    $('#md_modal').dialog({title: "{{Log du plugin}}"});
    $("#md_modal").load('index.php?v=d&modal=log.display&log='+$(this).attr('data-log')).dialog('open');
  }
});

function savePluginConfig(_param) {
  jeedom.config.save({
    configuration: $('#div_plugin_configuration').getValues('.configKey')[0],
    plugin:$('#span_plugin_id').text(),
    error: function (error) {
      alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      if (!isset(_param)){
        _param = {};
      }
      alert_div_plugin_configuration.showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
      modifyWithoutSave = false;
      var postSave = $('#span_plugin_id').text()+'_postSaveConfiguration';
      if (typeof window[postSave] == 'function'){
        window[postSave]();
      }
      if (typeof _param.success == 'function'){
        _param.success(0);
      }
      if($('#div_plugin_configuration .saveParam[data-l1key=relaunchDeamon]').html() != undefined){
        _param.relaunchDeamon = $('#div_plugin_configuration .saveParam[data-l1key=relaunchDeamon]').value();
      }
      if(!isset(_param.relaunchDeamon) || _param.relaunchDeamon != '0'){
        jeedom.plugin.deamonStart({
          id : $('#span_plugin_id').text(),
          slave_id: 0,
          forceRestart: 1,
          error: function (error) {
            alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'});
          },
          success: function (data) {
            $("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+$('#span_plugin_id').text());
          }
        });
      }
    }
  });
}

$('#bt_addPluginFromOtherSource').on('click',function(){
  $('#md_modal').dialog({title: "{{Ajouter un plugin}}"});
  $('#md_modal').load('index.php?v=d&modal=update.add').dialog('open');
});
