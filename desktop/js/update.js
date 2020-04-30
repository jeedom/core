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
  closeText: '',
  autoOpen: false,
  modal: true,
  height: 600,
  width: 600,
  open: function () {
    $("body").css({overflow: 'hidden'});
  },
  beforeClose: function (event, ui) {
    $("body").css({overflow: 'inherit'});
  }
});

$('#pre_updateInfo').parent().height($(window).outerHeight() - $('header').outerHeight() - 160);

$('#bt_updateJeedom').off('click').on('click', function () {
  $('#md_specifyUpdate').dialog({title: "{{Options}}"});
  $("#md_specifyUpdate").dialog('open');
});


$('.updateOption[data-l1key=force]').off('click').on('click',function(){
  if($(this).value() == 1){
    $('.updateOption[data-l1key="backup::before"]').value(0);
    $('.updateOption[data-l1key="backup::before"]').attr('disabled','disabled');
  }else{
    $('.updateOption[data-l1key="backup::before"]').attr('disabled',false);
  }
});


$('#bt_doUpdate').off('click').on('click', function () {
  $("#md_specifyUpdate").dialog('close');
  var options = $('#md_specifyUpdate').getValues('.updateOption')[0];
  $.hideAlert();
  jeedom.update.doAll({
    options: options,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      $('a[data-toggle=tab][href="#log"]').click();
      getJeedomLog(1, 'update');
    }
  });
});

$('#bt_checkAllUpdate').off('click').on('click', function () {
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


$('#table_update,#table_updateOther').delegate('.update', 'click', function () {
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
          $('a[data-toggle=tab][href="#log"]').click();
          getJeedomLog(1, 'update');
        }
      });
    }
  });
});

$('#table_update,#table_updateOther').delegate('.remove', 'click', function () {
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

$('#table_update,#table_updateOther').delegate('.checkUpdate', 'click', function () {
  var id = $(this).closest('tr').attr('data-id');
  $.hideAlert();
  jeedom.update.check({
    id: id,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      printUpdate();
    }
  });
  
});

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
            printUpdate();
            $('#div_alert').showAlert({message: '{{L\'opération est réussie. Merci de faire F5 pour avoir les dernières nouveautés}}', level: 'success'});
            _autoUpdate = 0;
          }
          if(data.result[i].indexOf('[END ' + _log.toUpperCase() + ' ERROR]') != -1){
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
      var tr_update = []
      var tr_update_other = [];
      for (var i in data) {
        if (!isset(data[i].status)) continue
        if (data[i].type == 'core' || data[i].type == 'plugin') {
          tr_update.push(addUpdate(data[i]));
        } else {
          tr_update_other.push(addUpdate(data[i]));
        }
      }
      $('#table_update tbody').empty().append(tr_update).trigger('update');
      $('#table_updateOther tbody').empty().append(tr_update_other).trigger('update');
    }
  });
  jeedom.config.load({
    configuration: {"update::lastCheck":0,"update::lastDateCore": 0},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('#span_lastUpdateCheck').value(data['update::lastCheck']);
      $('#span_lastUpdateCheck').attr('title','{{Dernière mise à jour du core : }}'+data['update::lastDateCore']);
    }
  });
}

function addUpdate(_update) {
  var labelClass = 'label-success';
  if(init(_update.status) == ''){
    _update.status = 'ok';
  }
  if (_update.status == 'update'){
    labelClass = 'label-warning';
  }
  var tr = '<tr data-id="' + init(_update.id) + '" data-logicalId="' + init(_update.logicalId) + '" data-type="' + init(_update.type) + '">';
  tr += '<td style="width:40px;cursor:default;"><span class="updateAttr label ' + labelClass +'" data-l1key="status" style="font-size:0.8em;text-transform: uppercase;"></span>';
  tr += '</td>';
  tr += '<td style="cursor:default;"><span class="updateAttr" data-l1key="id" style="display:none;"></span><span class="updateAttr" data-l1key="source"></span> / <span class="updateAttr" data-l1key="type"></span> : <span class="updateAttr label label-info" data-l1key="name" style="font-size:0.8em;"></span>';
 
  if(_update.configuration && _update.configuration.version){
    tr += ' <span class="label label-warning">'+_update.configuration.version+'</span>';
  }
  tr += '</td>';
  tr += '<td style="width:250px;"><span class="updateAttr label label-primary" data-l1key="localVersion" style="font-size:0.8em;cursor:default;"></span></td>';
  tr += '<td style="width:250px;"><span class="updateAttr label label-primary" data-l1key="remoteVersion" style="font-size:0.8em;cursor:default;"></span></td>';
  tr += '<td style="width:180px;cursor:default;">';
  if (_update.type != 'core') {
    tr += '<input type="checkbox" class="updateAttr" data-l1key="configuration" data-l2key="doNotUpdate"><span style="font-size:0.9em;">{{Ne pas mettre à jour}}</span>';
  }
  tr += '</td>';
  tr += '<td>';
  if (_update.type != 'core') {
    if (isset(_update.plugin) && isset(_update.plugin.changelog) && _update.plugin.changelog != '') {
      tr += '<a class="btn btn-default btn-xs cursor" target="_blank" href="'+_update.plugin.changelog+'" style="margin-bottom : 5px;"><i class="fas fa-book"></i> {{Changelog}}</a> ';
    }
  }else{
    tr += '<a class="btn btn-default btn-xs" href="https://doc.jeedom.com/fr_FR/core/3.3/changelog" target="_blank" style="margin-bottom : 5px;"><i class="fas fa-book"></i> {{Changelog}}</a> ';
  }
  if (_update.type != 'core') {
    if (_update.status == 'update') {
      tr += '<a class="btn btn-info btn-xs update" style="margin-bottom : 5px;" title="{{Mettre à jour}}"><i class="fas fa-refresh"></i> {{Mettre à jour}}</a> ';
    }else if (_update.type != 'core') {
      tr += '<a class="btn btn-info btn-xs update" style="margin-bottom : 5px;" title="{{Re-installer}}"><i class="fas fa-refresh"></i> {{Reinstaller}}</a> ';
    }
  }
  tr += '<a class="btn btn-info btn-xs pull-right checkUpdate" style="margin-bottom : 5px;" ><i class="fas fa-check"></i> {{Vérifier}}</a>';

  if (_update.type != 'core') {
    tr += '<a class="btn btn-danger btn-xs pull-right remove" style="margin-bottom : 5px;" ><i class="far fa-trash-alt"></i> {{Supprimer}}</a>';
  }
  tr += '</td>';
  tr += '</tr>';
  var html = $(tr);
  html.setValues(_update, '.updateAttr');
  return html;
}

$('#bt_saveUpdate').on('click',function(){
  jeedom.update.saves({
    updates : $('#table_update tbody tr').getValues('.updateAttr'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      $('#div_alert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
      printUpdate();
    }
  });
});
