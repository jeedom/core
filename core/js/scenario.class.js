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

jeedom.scenario = function() {};
jeedom.scenario.cache = Array();

if (!isset(jeedom.scenario.update)) {
  jeedom.scenario.update = Array()
}
if (!isset(jeedom.scenario.cache.byGroupObjectName)) {
  jeedom.scenario.cache.byGroupObjectName = Array()
}

jeedom.scenario.all = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    pre_success: function(data) {
      jeedom.scenario.cache.all = data.result;
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
  if (isset(jeedom.scenario.cache.all) && jeedom.scenario.cache.all != null && init(_params.nocache, false) == false) {
    params.success(jeedom.scenario.cache.all);
    return;
  }
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'all',
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.allOrderedByGroupObjectName = function(_params) {
  var asGroup = _params.asGroup ? _params.asGroup : 0
  var asTag = _params.asTag ? _params.asTag : 0
  var paramsRequired = [];
  var paramsSpecifics = {
    pre_success: function(data) {
      if (!isset(jeedom.scenario.cache.byGroupObjectName)) jeedom.scenario.cache.byGroupObjectName = Array()
      if (asTag) return data
      jeedom.scenario.cache.byGroupObjectName[asGroup] = data.result
      return data
    }
  }
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired)
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e)
    return
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {})
  /*
  if (!asTag && isset(jeedom.scenario.cache.byGroupObjectName) && isset(jeedom.scenario.cache.byGroupObjectName[asGroup]) && jeedom.scenario.cache.byGroupObjectName[asGroup] != null  && init(_params.nocache, false) == false) {
    params.success(jeedom.scenario.cache.byGroupObjectName[asGroup])
    return
  }
  */
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'allOrderedByGroupObjectName',
    asGroup: asGroup,
    asTag: asTag
  }
  $.ajax(paramsAJAX)
}

jeedom.scenario.saveAll = function(_params) {
  var paramsRequired = ['scenarios'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'saveAll',
    scenarios: json_encode(_params.scenarios),
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.toHtml = function(_params) {
  var paramsRequired = ['id', 'version'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'toHtml',
    id: ($.isArray(_params.id)) ? json_encode(_params.id) : _params.id,
    version: _params.version
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.changeState = function(_params) {
  var paramsRequired = ['id', 'state'];
  var paramsSpecifics = {
    global: false
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'changeState',
    id: _params.id,
    state: _params.state
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.getTemplate = function(_params) {
  var paramsRequired = [];
  var paramsSpecifics = {
    global: false
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'getTemplate',
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.convertToTemplate = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'convertToTemplate',
    id: _params.id,
    template: _params.template || '',
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.removeTemplate = function(_params) {
  var paramsRequired = ['template'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'removeTemplate',
    template: _params.template,
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.loadTemplateDiff = function(_params) {
  var paramsRequired = ['template', 'id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'loadTemplateDiff',
    template: _params.template,
    id: _params.id,
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.applyTemplate = function(_params) {
  var paramsRequired = ['template', 'id', 'convert'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'applyTemplate',
    template: _params.template,
    id: _params.id,
    convert: _params.convert,
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.refreshValue = function(_params) {
  if (!isset(_params.global) || !_params.global) {
    if (isset(jeedom.scenario.update) && isset(jeedom.scenario.update[_params.scenario_id])) {
      jeedom.scenario.update[_params.scenario_id](_params);
      return;
    }
  }
  if ($('.scenario[data-scenario_id=' + _params.scenario_id + ']').html() == undefined) {
    return;
  }
  var version = $('.scenario[data-scenario_id=' + _params.scenario_id + ']').attr('data-version');
  var paramsRequired = ['id'];
  var paramsSpecifics = {
    global: false,
    success: function(result) {
      $('.scenario[data-scenario_id=' + params.scenario_id + ']').empty().html($(result).children());
      if ($.mobile) {
        $('.scenario[data-scenario_id=' + params.scenario_id + ']').trigger("create");
        jeedomUtils.setTileSize('.scenario');
      }
    }
  };
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'toHtml',
    id: _params.scenario_id,
    version: _params.version || version
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.copy = function(_params) {
  var paramsRequired = ['id', 'name'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'copy',
    id: _params.id,
    name: _params.name
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.byId = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'byId',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.get = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'get',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.save = function(_params) {
  var paramsRequired = ['scenario'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'save',
    scenario: json_encode(_params.scenario)
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.remove = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  delete jeedom.scenario.cache.all
  delete jeedom.scenario.cache.byGroupObjectName
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'remove',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.clearAllLogs = function(_params) {
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
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'clearAllLogs'
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.emptyLog = function(_params) {
  var paramsRequired = ['id'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'emptyLog',
    id: _params.id
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.getSelectModal = function(_options, callback) {
  if (!isset(_options)) {
    _options = {};
  }
  if ($("#mod_insertScenarioValue").length != 0) {
    $("#mod_insertScenarioValue").remove();
  }
  $('body').append('<div id="mod_insertScenarioValue" title="{{Sélectionner le scénario}}" ></div>');
  $("#mod_insertScenarioValue").dialog({
    closeText: '',
    autoOpen: false,
    modal: true,
    height: 250,
    width: 800
  });
  jQuery.ajaxSetup({
    async: false
  });
  $('#mod_insertScenarioValue').load('index.php?v=d&modal=scenario.human.insert');
  jQuery.ajaxSetup({
    async: true
  });

  mod_insertScenario.setOptions(_options);
  $("#mod_insertScenarioValue").dialog('option', 'buttons', {
    "{{Annuler}}": function() {
      $(this).dialog("close");
    },
    "{{Valider}}": function() {
      var retour = {};
      retour.human = mod_insertScenario.getValue();
      retour.id = mod_insertScenario.getId();
      if ($.trim(retour) != '') {
        callback(retour);
      }
      $(this).dialog('close');
    }
  });
  $('#mod_insertScenarioValue').dialog('open');
}

jeedom.scenario.testExpression = function(_params) {
  var paramsRequired = ['expression'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'testExpression',
    expression: _params.expression
  };
  $.ajax(paramsAJAX);
}

jeedom.scenario.setOrder = function(_params) {
  var paramsRequired = ['scenarios'];
  var paramsSpecifics = {};
  try {
    jeedom.private.checkParamsRequired(_params || {}, paramsRequired);
  } catch (e) {
    (_params.error || paramsSpecifics.error || jeedom.private.default_params.error)(e);
    return;
  }
  var params = $.extend({}, jeedom.private.default_params, paramsSpecifics, _params || {});
  var paramsAJAX = jeedom.private.getParamsAJAX(params);
  paramsAJAX.url = 'core/ajax/scenario.ajax.php';
  paramsAJAX.data = {
    action: 'setOrder',
    scenarios: json_encode(_params.scenarios)
  };
  $.ajax(paramsAJAX);
}

/* Actions Autocomplete */
jeedom.scenario.autoCompleteCondition = [
  '#rand(MIN,MAX)',
  '##minute#',
  '##heure#',
  '##jour#',
  '##semaine#',
  '##mois#',
  '##annee#',
  '##sjour#',
  '##date#',
  '##time#',
  '##timestamp#',
  '##IP#',
  '##hostname#',
  '#tag(montag,defaut)',
  '#variable(mavariable,defaut)',
  '#genericType(generic,object)',
  '#delete_variable(mavariable)',
  '#tendance(commande,periode)',
  '#average(commande,periode)',
  '#max(commande,periode)',
  '#min(commande,periode)',
  '#round(valeur)',
  '#trigger(commande)',
  '#randomColor(debut,fin)',
  '#lastScenarioExecution(scenario)',
  '#stateDuration(commande)',
  '#lastChangeStateDuration(commande,value)',
  '#age(commande)',
  '#median(commande1,commande2)',
  '#avg(commande1,commande2)',
  '#time(value)',
  '#collectDate(cmd)',
  '#valueDate(cmd)',
  '#eqEnable(equipement)',
  '#name(type,commande)',
  '#value(commande)',
  '#lastCommunication(equipement)',
  '#color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)'
]
jeedom.scenario.autoCompleteAction = [
  'setColoredIcon',
  'tag',
  'report',
  'exportHistory',
  'sleep',
  'variable',
  'delete_variable',
  'scenario',
  'stop',
  'wait',
  'gotodesign',
  'log',
  'message',
  'equipement',
  'ask',
  'jeedom_poweroff',
  'scenario_return',
  'alert',
  'popup',
  'icon',
  'event',
  'remove_inat',
  'genericType'
]
jeedom.scenario.setAutoComplete = function(_params) {
  if (!isset(_params)) {
    _params = {}
    _params.parent = $('#div_scenarioElement')
    _params.type = 'expression'
  }

  _params.parent.find('.expression').each(function() {
    if ($(this).find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      $(this).find('.expressionAttr[data-l1key=' + _params.type + ']').autocomplete({
        minLength: 1,
        source: function(request, response) {
          //return last term after last space:
          var values = request.term.split(' ')
          var term = values[values.length - 1]
          if (term == '') return false //only space entered
          response(
            $.ui.autocomplete.filter(jeedom.scenario.autoCompleteCondition, term)
          )
        },
        response: function(event, ui) {
          //remove leading # from all values:
          $.each(ui.content, function(index, _obj) {
            _obj.label = _obj.label.substr(1)
            _obj.value = _obj.label
          })
        },
        focus: function() {
          event.preventDefault()
          return false
        },
        select: function(event, ui) {
          //update input value:
          if (this.value.substr(-1) == '#') {
            this.value = this.value.slice(0, -1) + ui.item.value
          } else {
            var values = this.value.split(' ')
            var term = values[values.length - 1]
            this.value = this.value.slice(0, -term.length) + ui.item.value
          }
          return false
        }
      })
    }

    if ($(this).find('.expressionAttr[data-l1key=type]').value() == 'action') {
      $(this).find('.expressionAttr[data-l1key=' + _params.type + ']').autocomplete({
        source: jeedom.scenario.autoCompleteAction,
        close: function(event, ui) {
          $(this).blur()
          //$(this).trigger('focusout')
        }
      })
    }
  })
}