
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


printCron();
printListener();

$("#bt_refreshCron").on('click', function () {
  printCron();
  printListener();
});

$("#bt_addCron").on('click', function () {
  $('#table_cron tbody').append(addCron({}));
});

jwerty.key('ctrl+s/⌘+s', function (e) {
  e.preventDefault();
  $("#bt_save").click();
});

$("#bt_save").on('click', function () {
  jeedom.cron.save({
    crons: $('#table_cron tbody tr').getValues('.cronAttr'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: printCron
  });
});

$("#bt_changeCronState").on('click', function () {
  var el = $(this);
  jeedom.config.save({
    configuration: {enableCron: el.attr('data-state')},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      if (el.attr('data-state') == 1) {
        el.removeClass('btn-success').addClass('btn-danger').attr('data-state', 0);
        el.empty().html('<i class="fas fa-times"></i> {{Désactiver le système cron}}');
      } else {
        el.removeClass('btn-danger').addClass('btn-success').attr('data-state', 1);
        el.empty().html('<i class="fas fa-check"></i> {{Activer le système cron}}</a>');
      }
    }
  });
});

$("#table_cron").delegate(".remove", 'click', function () {
  $(this).closest('tr').remove();
});

$("#table_cron").delegate(".stop", 'click', function () {
  jeedom.cron.setState({
    state: 'stop',
    id: $(this).closest('tr').attr('id'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: printCron
  });
});

$("#table_cron").delegate(".start", 'click', function () {
  jeedom.cron.setState({
    state: 'start',
    id: $(this).closest('tr').attr('id'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: printCron
  });
});

$("#table_cron").delegate(".display", 'click', function () {
  $('#md_modal').dialog({title: "{{Détails du cron}}"});
  $("#md_modal").load('index.php?v=d&modal=object.display&class=cron&id='+$(this).closest('tr').attr('id')).dialog('open');
});

$("#table_listener").delegate(".display", 'click', function () {
  $('#md_modal').dialog({title: "{{Détails du listener}}"});
  $("#md_modal").load('index.php?v=d&modal=object.display&class=listener&id='+$(this).closest('tr').attr('id')).dialog('open');
});

$('#table_cron').delegate('.cronAttr[data-l1key=deamon]', 'change', function () {
  if ($(this).value() == 1) {
    $(this).closest('tr').find('.cronAttr[data-l1key=deamonSleepTime]').show();
  } else {
    $(this).closest('tr').find('.cronAttr[data-l1key=deamonSleepTime]').hide();
  }
});

$('#div_pageContainer').delegate('.cronAttr', 'change', function () {
  modifyWithoutSave = true;
});

function printCron() {
  $.showLoading();
  jeedom.cron.all({
    success: function (data) {
      $.showLoading();
      $('#table_cron tbody').empty();
      var tr = [];
      for (var i in data) {
        tr.push(addCron(data[i]));
      }
      $('#table_cron tbody').append(tr);
      modifyWithoutSave = false;
      $.hideLoading();
    }
  });
}

function addCron(_cron) {
  $.hideAlert();
  var disabled ='';
  if(init(_cron.deamon) == 1){
    disabled ='disabled';
  }
  var tr = '<tr id="' + init(_cron.id) + '">';
  tr += '<td class="option"><span class="cronAttr" data-l1key="id"></span></td>';
  tr += '<td>';
  if(init(_cron.id) != ''){
    tr += '<a class="btn btn-default btn-xs display"><i class="fas fa-file"></i></a> ';
  }
  if(init(_cron.deamon) == 0){
    if (init(_cron.state) == 'run') {
      tr += ' <a class="btn btn-danger btn-xs stop" style="color : white;"><i class="fas fa-stop"></i></a>';
    }
    if (init(_cron.state) != '' && init(_cron.state) != 'starting' && init(_cron.state) != 'run' && init(_cron.state) != 'stoping') {
      tr += ' <a class="btn btn-success btn-xs start" style="color : white;"><i class="fas fa-play"></i></a>';
    }
  }
  tr += '</td>';
  tr += '<td class="enable"><center>';
  tr += '<input type="checkbox"class="cronAttr" data-l1key="enable" checked '+disabled+'/>';
  tr += '</center></td>';
  tr += '<td>';
  tr += init(_cron.pid);
  tr += '</td>';
  tr += '<td class="deamons">';
  tr += '<input type="checkbox" class="cronAttr" data-l1key="deamon" '+disabled+' /></span> ';
  tr += '<input class="cronAttr form-control input-sm" data-l1key="deamonSleepTime" style="width : 50px; display : inline-block;" />';
  tr += '</td>';
  tr += '<td class="once">';
  if(init(_cron.deamon) == 0){
    tr += '<input type="checkbox" class="cronAttr" data-l1key="once" /></span> ';
  }
  tr += '</td>';
  tr += '<td class="class"><input class="form-control cronAttr input-sm" data-l1key="class" '+disabled+' /></td>';
  tr += '<td class="function"><input class="form-control cronAttr input-sm" data-l1key="function" '+disabled+' /></td>';
  tr += '<td class="schedule"><input class="cronAttr form-control input-sm" data-l1key="schedule" '+disabled+' /></td>';
  tr += '<td class="function">';
  if(init(_cron.deamon) == 0){
    tr += '<input class="form-control cronAttr input-sm" data-l1key="timeout" />';
  }
  tr += '</td>';
  tr += '<td class="lastRun">';
  tr += init(_cron.lastRun);
  tr += '</td>';
  tr += '<td class="runtime">';
  tr += init(_cron.runtime,'0')+'s';
  tr += '</td>';
  tr += '<td class="state">';
  var label = 'label label-info';
  if (init(_cron.state) == 'run') {
    label = 'label label-success';
  }
  if (init(_cron.state) == 'stop') {
    label = 'label label-danger';
  }
  if (init(_cron.state) == 'starting') {
    label = 'label label-warning';
  }
  if (init(_cron.state) == 'stoping') {
    label = 'label label-warning';
  }
  tr += '<span class="' + label + '">' + init(_cron.state) + '</span>';
  tr += '</td>';
  tr += '<td class="action">';
  tr += '<i class="fas fa-minus-circle remove pull-right cursor"></i>';
  tr += '</td>';
  tr += '</tr>';
  var result = $(tr);
  result.setValues(_cron, '.cronAttr');
  return result;
}


function printListener() {
  $.showLoading();
  jeedom.listener.all({
    success: function (data) {
      $.showLoading();
      $('#table_listener tbody').empty();
      var tr = [];
      for (var i in data) {
        tr.push(addListener(data[i]));
      }
      $('#table_listener tbody').append(tr);
      modifyWithoutSave = false;
      $.hideLoading();
    }
  });
}


function addListener(_listener) {
  $.hideAlert();
  var disabled ='';
  var tr = '<tr id="' + init(_listener.id) + '">';
  tr += '<td class="option"><span class="listenerAttr" data-l1key="id"></span></td>';
  tr += '<td>';
  if(init(_listener.id) != ''){
    tr += '<a class="btn btn-default btn-xs display"><i class="fas fa-file"></i></a> ';
  }
  tr += '</td>';
  tr += '<td><textarea class="form-control listenerAttr input-sm" data-l1key="event_str" disabled ></textarea></td>';
  tr += '<td><input class="form-control listenerAttr input-sm" data-l1key="class" disabled /></td>';
  tr += '<td><input class="form-control listenerAttr input-sm" data-l1key="function" disabled /></td>';
  tr += '</tr>';
  var result = $(tr);
  result.setValues(_listener, '.listenerAttr');
  return result;
}

/***********************DEAMON*****************************/

getDeamonState();

$('#bt_refreshDeamon').on('click',function(){
  getDeamonState();
});

function getDeamonState(){
  $('#table_deamon tbody').empty();
  jeedom.plugin.all({
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (plugins) {
      for (var i in plugins) {
        if(plugins[i].hasOwnDeamon == 0){
          continue;
        }
        jeedom.plugin.getDeamonInfo({
          id : plugins[i].id,
          async:false,
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          },
          success: function (deamonInfo) {
            var html = '<tr>';
            html += '<td>';
            html += deamonInfo.plugin.name;
            html += '</td>';
            html += '<td>';
            html += deamonInfo.state;
            html += '</td>';
            html += '<td>';
            html += deamonInfo.last_launch;
            html += '</td>';
            html += '<td>';
            html += '<a class="bt_deamonAction btn btn-success" data-action="start" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-play"></i></a> ';
            if(deamonInfo.auto == 0){
              html += '<a class="bt_deamonAction btn btn-danger" data-action="stop" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-stop"></i></a> ';
              html += '<a class="bt_deamonAction btn btn-danger" data-action="enableAuto" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-magic"></i></a> ';
            }else{
              html += '<a class="bt_deamonAction btn btn-success" data-action="disableAuto" data-plugin="'+deamonInfo.plugin.id+'"><i class="fas fa-times"></i></a> ';
            }
            html += '</td>';
            html += '</tr>';
            $('#table_deamon tbody').append(html);
          }
        });
      }
    }
  });
}

$('#table_deamon tbody').on('click','.bt_deamonAction',function(){
  var plugin = $(this).data('plugin');
  var action = $(this).data('action');
  if(action == 'start'){
    jeedom.plugin.deamonStart({
      id : plugin,
      forceRestart : 1,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        getDeamonState();
      }
    })
  }else if(action == 'stop'){
    jeedom.plugin.deamonStop({
      id : plugin,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        getDeamonState();
      }
    })
  }else if(action == 'enableAuto'){
    jeedom.plugin.deamonChangeAutoMode({
      id : plugin,
      mode:1,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        getDeamonState();
      }
    })
  }else if(action == 'disableAuto'){
    jeedom.plugin.deamonChangeAutoMode({
      id : plugin,
      mode:0,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success: function () {
        getDeamonState();
      }
    })
  }
});
