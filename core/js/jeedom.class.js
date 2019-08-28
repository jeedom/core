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

function jeedom() {
}

jeedom.cache = [];
jeedom.display = {};
jeedom.connect = 0;
jeedom.theme = {};
jeedom.changes_timeout = null;

if (!isset(jeedom.cache.getConfiguration)) {
  jeedom.cache.getConfiguration = null;
}

jeedom.changes = function(){
  var paramsRequired = [];
  var paramsSpecifics = {
    global: false,
    success: function(data) {
      if(jeedom.connect > 0){
        jeedom.connect = 0;
      }
      jeedom.datetime = data.datetime;
      var cmd_update = [];
      var eqLogic_update = [];
      var object_summary_update = [];
      for(var i in data.result){
        if(data.result[i].name == 'cmd::update'){
          cmd_update.push(data.result[i].option);
          continue;
        }
        if(data.result[i].name == 'eqLogic::update'){
          eqLogic_update.push(data.result[i].option);
          continue;
        }
        if(data.result[i].name == 'jeeObject::summary::update'){
          object_summary_update.push(data.result[i].option);
          continue;
        }
        if(isset(data.result[i].option)){
          $('body').trigger(data.result[i].name,data.result[i].option);
        }else{
          $('body').trigger(data.result[i].name);
        }
      }
      if(cmd_update.length > 0){
        $('body').trigger('cmd::update',[cmd_update]);
      }
      if(eqLogic_update.length > 0){
        $('body').trigger('eqLogic::update',[eqLogic_update]);
      }
      if(object_summary_update.length > 0){
        $('body').trigger('jeeObject::summary::update',[object_summary_update]);
      }
      jeedom.changes_timeout = setTimeout(jeedom.changes, 1);
    },
    error: function(_error){
      if(typeof(user_id) != "undefined" && jeedom.connect == 100){
        notify('{{Erreur de connexion}}','{{Erreur lors de la connexion à Jeedom}} : '+_error.message);
      }
      jeedom.connect++;
      jeedom.changes_timeout = setTimeout(jeedom.changes, 1);
    }
  };
  try {
    jeedom.private.checkParamsRequired(paramsRequired);
  } catch (e) {
    (paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics);
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/event.ajax.php';
  paramsAJAX.data = {
    action: 'changes',
    datetime:jeedom.datetime,
  };
  $.ajax(paramsAJAX);
}


jeedom.init = function () {
  jeedom.datetime = serverDatetime;
  jeedom.display.version = 'desktop';
  if ($.mobile) {
    jeedom.display.version = 'mobile';
  }
  Highcharts.setOptions({
    lang: {
      months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
      'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
      shortMonths: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
      'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
      weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
    }
  });
  $('body').on('cmd::update', function (_event,_options) {
    jeedom.cmd.refreshValue(_options);
  });
  
  $('body').on('scenario::update', function (_event,_options) {
    jeedom.scenario.refreshValue(_options);
  });
  $('body').on('eqLogic::update', function (_event,_options) {
    jeedom.eqLogic.refreshValue(_options);
  });
  $('body').on('jeeObject::summary::update', function (_event,_options) {
    jeedom.object.summaryUpdate(_options);
  });
  
  $('body').on('ui::update', function (_event,_options) {
    if(isset(_options.page) && _options.page != ''){
      if(!$.mobile && getUrlVars('p') != _options.page){
        return;
      }
      if($.mobile && isset(CURRENT_PAGE) && CURRENT_PAGE != _options.page){
        return;
      }
    }
    if(!isset(_options.container) || _options.container == ''){
      _options.container = 'body';
    }
    $(_options.container).setValues(_options.data, _options.type);
    console.log(_options);
  });
  
  $('body').on('jeedom::gotoplan', function (_event,_plan_id) {
    if(getUrlVars('p') == 'plan' && 'function' == typeof (displayPlan)){
      if (_plan_id != $('#sel_planHeader').attr('data-link_id')) {
        planHeader_id = _plan_id;
        displayPlan();
      }
    }
  });
  
  $('body').on('jeedom::alert', function (_event,_options) {
    if (!isset(_options.message) || $.trim(_options.message) == '') {
      if(isset(_options.page) && _options.page != ''){
        if(getUrlVars('p') == _options.page || ($.mobile && isset(CURRENT_PAGE) && CURRENT_PAGE == _options.page)){
          $.hideAlert();
        }
      }else{
        $.hideAlert();
      }
    } else {
      if(isset(_options.page) && _options.page != ''){
        if(getUrlVars('p') == _options.page || ($.mobile && isset(CURRENT_PAGE) && CURRENT_PAGE == _options.page)){
          $('#div_alert').showAlert({message: _options.message, level: _options.level});
        }
      }else{
        $('#div_alert').showAlert({message: _options.message, level: _options.level});
      }
    }
  });
  $('body').on('jeedom::alertPopup', function (_event,_message) {
    alert(_message);
  });
  $('body').on('jeedom::coloredIcons', function (_event,_state) {
    $('body').attr('data-coloredIcons',_state);
  });
  $('body').on('message::refreshMessageNumber', function (_event,_options) {
    refreshMessageNumber();
  });
  $('body').on('update::refreshUpdateNumber', function (_event,_options) {
    refreshUpdateNumber();
  });
  $('body').on('notify', function (_event,_options) {
    notify(_options.title, _options.message, _options.theme);
  });
  if (typeof user_id !== 'undefined') {
    jeedom.changes();
  }
}

jeedom.getConfiguration = function (_params) {
  var paramsRequired = ['key'];
  var paramsSpecifics = {
    pre_success: function (data) {
      jeedom.cache.getConfiguration = data.result;
      var keys = _params.key.split(':');
      data.result = jeedom.cache.getConfiguration;
      for(var i in keys){
        if (data.result[keys[i]]) {
          data.result = data.result[keys[i]];
        }
      }
      return data;
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  if (jeedom.cache.getConfiguration != null) {
    var keys = _params.key.split(':');
    var result = jeedom.cache.getConfiguration;
    for(var i in keys){
      if (result[keys[i]]) {
        result = result[keys[i]];
      }
    }
    _params.success(result);
    return;
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'getConfiguration',
    key: ''
  };
  $.ajax(paramsAJAX);
};

jeedom.haltSystem = function (_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'haltSystem',
  };
  $.ajax(paramsAJAX);
};

jeedom.ssh = function (_params) {
  if($.isPlainObject(_params)){
    command = _params.command;
  }else{
    command = _params;
    _params = {};
  }
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'ssh',
    command : command
  };
  $.ajax(paramsAJAX);
  return 'Execute command : '+command;
};

jeedom.db = function (_params) {
  if($.isPlainObject(_params)){
    command = _params.command;
  }else{
    command = _params;
    _params = {};
  }
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'db',
    command : command
  };
  $.ajax(paramsAJAX);
  return 'Execute command : '+command;
};

jeedom.dbcorrectTable = function (_params) {
  var paramsRequired = ['table'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'dbcorrectTable',
    table : _params.table
  };
  $.ajax(paramsAJAX);
};


jeedom.rebootSystem = function (_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'rebootSystem',
  };
  $.ajax(paramsAJAX);
};

jeedom.health = function (_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'health',
  };
  $.ajax(paramsAJAX);
};

jeedom.saveCustum = function (_params) {
  var paramsRequired = ['version', 'type', 'content'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'saveCustom',
    type: _params.type,
    version: _params.version,
    content: _params.content,
  };
  $.ajax(paramsAJAX);
};

jeedom.forceSyncHour = function (_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'forceSyncHour',
  };
  $.ajax(paramsAJAX);
};

jeedom.getCronSelectModal = function(_options,_callback) {
  if ($("#mod_insertCronValue").length == 0) {
    $('body').append('<div id="mod_insertCronValue" title="{{Assistant cron}}" ></div>');
    $("#mod_insertCronValue").dialog({
      closeText: '',
      autoOpen: false,
      modal: true,
      height: 310,
      width: 800
    });
    jQuery.ajaxSetup({
      async: false
    });
    $('#mod_insertCronValue').load('index.php?v=d&modal=cron.human.insert');
    jQuery.ajaxSetup({
      async: true
    });
  }
  $("#mod_insertCronValue").dialog('option', 'buttons', {
    "{{Annuler}}": function() {
      $(this).dialog("close");
    },
    "{{Valider}}": function() {
      var retour = {};
      retour.cron = {};
      retour.value = mod_insertCron.getValue();
      if ($.trim(retour) != '' && 'function' == typeof(_callback)) {
        _callback(retour);
      }
      $(this).dialog('close');
    }
  });
  $('#mod_insertCronValue').dialog('open');
};

jeedom.getSelectActionModal = function(_options, _callback){
  if (!isset(_options)) {
    _options = {};
  }
  if ($("#mod_insertActionValue").length == 0) {
    $('body').append('<div id="mod_insertActionValue" title="{{Sélectionner la commande}}" ></div>');
    $("#mod_insertActionValue").dialog({
      closeText: '',
      autoOpen: false,
      modal: true,
      height: 310,
      width: 800
    });
    jQuery.ajaxSetup({
      async: false
    });
    $('#mod_insertActionValue').load('index.php?v=d&modal=action.insert');
    jQuery.ajaxSetup({
      async: true
    });
  }
  mod_insertAction.setOptions(_options);
  $("#mod_insertActionValue").dialog('option', 'buttons', {
    "Annuler": function() {
      $(this).dialog("close");
    },
    "Valider": function() {
      var retour = {};
      retour.action = {};
      retour.human = mod_insertAction.getValue();
      if ($.trim(retour) != '' && 'function' == typeof(_callback)) {
        _callback(retour);
      }
      $(this).dialog('close');
    }
  });
  $('#mod_insertActionValue').dialog('open');
}

jeedom.getGraphData = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'getGraphData',
    filter_type: params.filter_type || null,
    filter_id: params.filter_id || null,
  };
  $.ajax(paramsAJAX);
};


jeedom.getDocumentationUrl = function (_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'getDocumentationUrl',
    plugin: params.plugin || null,
    page: params.page || null,
  };
  $.ajax(paramsAJAX);
};


jeedom.addWarnme = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'addWarnme',
    cmd_id: params.cmd_id,
    test: params.test,
  };
  $.ajax(paramsAJAX);
};


jeedom.getTimelineEvents = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'getTimelineEvents'
  };
  $.ajax(paramsAJAX);
};

jeedom.removeTimelineEvents = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'removeTimelineEvents'
  };
  $.ajax(paramsAJAX);
};


jeedom.getFileFolder = function(_params) {
  var paramsRequired = ['type','path'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'getFileFolder',
    type : _params.type,
    path : _params.path,
  };
  $.ajax(paramsAJAX);
};

jeedom.getFileContent = function(_params) {
  var paramsRequired = ['path'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'getFileContent',
    path : _params.path,
  };
  $.ajax(paramsAJAX);
};

jeedom.setFileContent = function(_params) {
  var paramsRequired = ['path','content'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'setFileContent',
    path : _params.path,
    content : _params.content,
  };
  $.ajax(paramsAJAX);
};


jeedom.deleteFile = function(_params) {
  var paramsRequired = ['path'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'deleteFile',
    path : _params.path,
  };
  $.ajax(paramsAJAX);
};

jeedom.createFile = function(_params) {
  var paramsRequired = ['path','name'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'createFile',
    path : _params.path,
    name : _params.name,
  };
  $.ajax(paramsAJAX);
};


jeedom.emptyRemoveHistory = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'emptyRemoveHistory',
  };
  $.ajax(paramsAJAX);
};

jeedom.version = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'version'
  };
  $.ajax(paramsAJAX);
};

jeedom.removeImageIcon = function(_params) {
  var paramsRequired = ['filename'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'removeImageIcon',
    filename : _params.filename
  };
  $.ajax(paramsAJAX);
};

jeedom.cleanFileSystemRight = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'cleanFileSystemRight'
  };
  $.ajax(paramsAJAX);
};

jeedom.consistency = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'consistency'
  };
  $.ajax(paramsAJAX);
};

jeedom.cleanDatabase = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/jeedom.ajax.php';
  paramsAJAX.data = {
    action: 'cleanDatabase'
  };
  $.ajax(paramsAJAX);
};
