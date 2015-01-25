
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

 editor = [];

 listColor = ['#16a085', '#27ae60', '#2980b9', '#745cb0', '#f39c12', '#d35400', '#c0392b', '#2c3e50', '#7f8c8d'];
 pColor = 0;

 autoCompleteCondition = [
 {val: 'rand(MIN,MAX)'},
 {val: '#heure#'},
 {val: '#jour#'},
 {val: '#mois#'},
 {val: '#annee#'},
 {val: '#date#'},
 {val: '#time#'},
 {val: '#timestamp#'},
 {val: '#semaine#'},
 {val: '#sjour#'},
 {val: '#minute#'},
 {val: '#IP#'},
 {val: '#hostname#'},
 {val: 'variable(mavariable,defaut)'},
 {val: 'tendance(commande,periode)'},
 {val: 'average(commande,periode)'},
 {val: 'max(commande,periode)'},
 {val: 'min(commande,periode)'},
 {val: 'round(valeur)'},
 {val: 'trigger(commande)'},
 {val: 'randomColor(debut,fin)'},
 {val: 'lastScenarioExecution(scenario)'},
 {val: 'stateDuration(commande)'},
 {val: 'median(commande1,commande2)'},
 {val: 'time(value)'},
 ];
 autoCompleteAction = ['sleep', 'variable', 'scenario', 'stop', 'icon', 'say','wait','return'];

 if (getUrlVars('saveSuccessFull') == 1) {
  $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
}

$("#div_listScenario").resizable({
  handles: "all",
  grid: [1, 10000],
  stop: function () {
    $('.scenarioListContainer').packery();
  }
});

$("#div_listScenario").trigger('resize');

$('.scenarioListContainer').packery();

$('#bt_scenarioThumbnailDisplay').on('click', function () {
  $('#div_editScenario').hide();
  $('#scenarioThumbnailDisplay').show();
  $('.li_scenario').removeClass('active');
});

$('.scenarioDisplayCard').on('click', function () {
  $('#div_tree').jstree('deselect_all');
  $('#div_tree').jstree('select_node', 'scenario' + $(this).attr('data-scenario_id'));
});

$('#div_tree').on('select_node.jstree', function (node, selected) {
  if (selected.node.a_attr.class == 'li_scenario') {
    $.hideAlert();
    $(".li_scenario").removeClass('active');
    $(this).addClass('active');
    $('#scenarioThumbnailDisplay').hide();
    printScenario(selected.node.a_attr['data-scenario_id']);
  }
});

$("#div_tree").jstree({
  "plugins": ["search"]
});
$('#in_treeSearch').keyup(function () {
  $('#div_tree').jstree(true).search($('#in_treeSearch').val());
});

$('.scenarioAttr[data-l1key=group]').autocomplete({
  source: function (request, response, url) {
    $.ajax({
      type: 'POST',
      url: 'core/ajax/scenario.ajax.php',
      data: {
        action: 'autoCompleteGroup',
        term: request.term
      },
      dataType: 'json',
      global: false,
      error: function (request, status, error) {
        handleAjaxError(request, status, error);
      },
      success: function (data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
        }
        response(data.result);
      }
    });
  },
  minLength: 1,
});

$("#bt_changeAllScenarioState").on('click', function () {
  var el = $(this);
  jeedom.config.save({
    configuration: {enableScenario: el.attr('data-state')},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      if (el.attr('data-state') == 1) {
        el.find('i').removeClass('fa-check').addClass('fa-times');
        el.removeClass('btn-success').addClass('btn-danger').attr('data-state', 0);
        el.empty().html('<i class="fa fa-times"></i> {{Désac. scénarios}}');
      } else {
        el.find('i').removeClass('fa-times').addClass('fa-check');
        el.removeClass('btn-danger').addClass('btn-success').attr('data-state', 1);
        el.empty().html('<i class="fa fa-check"></i> {{Act. scénarios}}');
      }
    }
  });
});

$("#bt_addScenario").on('click', function (event) {
  bootbox.dialog({
    title: "Ajout d'un nouveau scénario",
    message: '<div class="row">  ' +
    '<div class="col-md-12"> ' +
    '<form class="form-horizontal" onsubmit="return false;"> ' +
    '<div class="form-group"> ' +
    '<label class="col-md-4 control-label">{{Nom}}</label> ' +
    '<div class="col-md-4"> ' +
    '<input id="in_scenarioAddName" type="text" placeholder="{{Nom de votre scénario}}" class="form-control input-md"> ' +
    '</div> ' +
    '</div> ' +
    '<div class="form-group"> ' +
    '<label class="col-md-4 control-label">{{Type}}</label> ' +
    '<div class="col-md-4"> <div class="radio"> <label> ' +
    '<input name="cbScenarioType" class="cb_scenarioType" type="radio" value="simple" checked="checked"> ' +
    '{{Simple}}</label> ' +
    '</div><div class="radio"> <label> ' +
    '<input  name="cbScenarioType" class="cb_scenarioType" type="radio" value="expert"> {{Avancée}}</label> ' +
    '</div> ' +
    '</div> </div>' +
    '</form> </div>  </div>',
    buttons: {
      "Annuler": {
        className: "btn-default",
        callback: function () {
        }
      },
      success: {
        label: "D'accord",
        className: "btn-primary",
        callback: function () {
          jeedom.scenario.save({
            scenario: {name: $('#in_scenarioAddName').val(), type: $("input[name=cbScenarioType]:checked").val()},
            error: function (error) {
              $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
              var vars = getUrlVars();
              var url = 'index.php?';
              for (var i in vars) {
                if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
                  url += i + '=' + vars[i].replace('#', '') + '&';
                }
              }
              url += 'id=' + data.id + '&saveSuccessFull=1';
              modifyWithoutSave = false;
              window.location.href = url;
            }
          });
        }
      },
    }
  });
});

jwerty.key('ctrl+s', function (e) {
  e.preventDefault();
  saveScenario();
});

$("#bt_saveScenario").on('click', function (event) {
  saveScenario();
});

$("#bt_delScenario").on('click', function (event) {
  $.hideAlert();
  bootbox.confirm('{{Etes-vous sûr de vouloir supprimer le scénario}} <span style="font-weight: bold ;">' + $('.scenarioAttr[data-l1key=name]').value() + '</span> ?', function (result) {
    if (result) {
      jeedom.scenario.remove({
        id: $('.scenarioAttr[data-l1key=id]').value(),
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
          modifyWithoutSave = false;
          window.location.replace('index.php?v=d&p=scenario');
        }
      });
    }
  });
});

$("#bt_testScenario").on('click', function () {
  $.hideAlert();
  jeedom.scenario.changeState({
    id: $('.scenarioAttr[data-l1key=id]').value(),
    state: 'start',
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      $('#div_alert').showAlert({message: '{{Lancement du scénario réussi}}', level: 'success'});
    }
  });
});

$("#bt_copyScenario").on('click', function () {
  bootbox.prompt("Nom du scénario ?", function (result) {
    if (result !== null) {
      jeedom.scenario.copy({
        id: $('.scenarioAttr[data-l1key=id]').value(),
        name: result,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          window.location.replace('index.php?v=d&p=scenario&id=' + data.id);
        }
      });
    }
  });
});

$("#bt_stopScenario").on('click', function () {
  jeedom.scenario.changeState({
    id: $('.scenarioAttr[data-l1key=id]').value(),
    state: 'stop',
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      printScenario($('.scenarioAttr[data-l1key=id]').value());
    }
  });
});

$('#bt_displayScenarioVariable').on('click', function () {
  $('#md_modal').closest('.ui-dialog').css('z-index', '1030');
  $('#md_modal').dialog({title: "{{Variables des scénarios}}"});
  $("#md_modal").load('index.php?v=d&modal=dataStore.management&type=scenario').dialog('open');
});

$('#in_addElementType').on('change',function(){
  $('.addElementTypeDescription').hide();
  $('.addElementTypeDescription.'+$(this).value()).show();
});


$('#sel_otherAction').on('change',function(){
  $('.sel_otherActionDescription').hide();
  $('.sel_otherActionDescription.'+$(this).value()).show();
});


/*******************Element***********************/

$('body').delegate('.helpSelectCron','click',function(){
  var el = $(this).closest('.schedule').find('.scenarioAttr[data-l1key=schedule]');
  jeedom.getCronSelectModal({},function (result) {
    el.value(result.value);
  });
});

$('body').delegate( '.bt_addScenarioElement','click', function (event) {
  var elementDiv = $(this).closest('.element');
  var expression = false;
  if ($(this).hasClass('fromSubElement')) {
    elementDiv = $(this).closest('.subElement').find('.expressions').eq(0);
    expression = true;
  }
  $('#md_addElement').modal('show');
  $("#bt_addElementSave").off();
  $("#bt_addElementSave").on('click', function (event) {
    if (expression) {
      elementDiv.append(addExpression({type: 'element', element: {type: $("#in_addElementType").value()}}));
    } else {
      elementDiv.append(addElement({type: $("#in_addElementType").value()}));
    }
    setEditor();
    updateSortable();
    $('#md_addElement').modal('hide');
  });
});

$('body').delegate('.bt_removeElement', 'click', function (event) {
  if ($(this).closest('.expression').length != 0) {
    $(this).closest('.expression').remove();
  } else {
    $(this).closest('.element').remove();
  }
});

$('body').delegate('.bt_addAction', 'click', function (event) {
  $(this).closest('.subElement').children('.expressions').append(addExpression({type: 'action'}));
  setAutocomplete();
  updateSortable();
});

$('body').delegate('.bt_removeExpression', 'click', function (event) {
  $(this).closest('.expression').remove();
  updateSortable();
});

$('body').delegate('.bt_selectCmdExpression', 'click', function (event) {
  var el = $(this);
  var expression = $(this).closest('.expression');
  var type = 'info';
  if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
    type = 'action';
  }
  jeedom.cmd.getSelectModal({cmd: {type: type}}, function (result) {
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
      expression.find('.expressionAttr[data-l1key=expression]').value(result.human);
      jeedom.cmd.displayActionOption(expression.find('.expressionAttr[data-l1key=expression]').value(), '', function (html) {
        expression.find('.expressionOptions').html(html);
      });
    }
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      message = 'Aucun choix possible';
      if(result.cmd.subType == 'numeric'){
       message = '<div class="row">  ' +
       '<div class="col-md-12"> ' +
       '<form class="form-horizontal" onsubmit="return false;"> ' +
       '<div class="form-group"> ' +
       '<label class="col-xs-5 control-label" >'+result.human+' {{est}}</label>' +
       '             <div class="col-xs-3">' +
       '                <select class="conditionAttr form-control" data-l1key="operator">' +
       '                    <option value="==">{{égale}}</option>' +
       '                  <option value=">">{{supérieur}}</option>' +
       '                  <option value="<">{{inférieur}}</option>' +
       '                 <option value="!=">{{différent}}</option>' +
       '            </select>' +
       '       </div>' +
       '      <div class="col-xs-4">' +
       '         <input type="number" class="conditionAttr form-control" data-l1key="operande" />' +
       '    </div>' +
       '</div>' +
       '<div class="form-group"> ' +
       '<label class="col-xs-5 control-label" >{{Ensuite}}</label>' +
       '             <div class="col-xs-3">' +
       '                <select class="conditionAttr form-control" data-l1key="next">' +
       '                    <option value="">rien</option>' +
       '                  <option value="ET">{{et}}</option>' +
       '                  <option value="OU">{{ou}}</option>' +
       '            </select>' +
       '       </div>' +
       '</div>' +
       '</div> </div>' +
       '</form> </div>  </div>';
     }
     if(result.cmd.subType == 'string'){
      message = '<div class="row">  ' +
      '<div class="col-md-12"> ' +
      '<form class="form-horizontal" onsubmit="return false;"> ' +
      '<div class="form-group"> ' +
      '<label class="col-xs-5 control-label" >'+result.human+' {{est}}</label>' +
      '             <div class="col-xs-3">' +
      '                <select class="conditionAttr form-control" data-l1key="operator">' +
      '                    <option value="==">{{égale}}</option>' +
      '                  <option value=">">{{supérieur}}</option>' +
      '                  <option value="<">{{inférieur}}</option>' +
      '                 <option value="!=">{{différent}}</option>' +
      '            </select>' +
      '       </div>' +
      '      <div class="col-xs-4">' +
      '         <input class="conditionAttr form-control" data-l1key="operande" />' +
      '    </div>' +
      '</div>' +
      '<div class="form-group"> ' +
      '<label class="col-xs-5 control-label" >{{Ensuite}}</label>' +
      '             <div class="col-xs-3">' +
      '                <select class="conditionAttr form-control" data-l1key="next">' +
      '                    <option value="">rien</option>' +
      '                  <option value="ET">{{et}}</option>' +
      '                  <option value="OU">{{ou}}</option>' +
      '            </select>' +
      '       </div>' +
      '</div>' +
      '</div> </div>' +
      '</form> </div>  </div>';
    }
    if(result.cmd.subType == 'binary'){
      message = '<div class="row">  ' +
      '<div class="col-md-12"> ' +
      '<form class="form-horizontal" onsubmit="return false;"> ' +
      '<div class="form-group"> ' +
      '<label class="col-xs-5 control-label" >'+result.human+' {{est}}</label>' +
      '            <div class="col-xs-7">' +
      '                 <input class="conditionAttr" data-l1key="operator" value="==" style="display : none;" />' +
      '                  <select class="conditionAttr form-control" data-l1key="operande">' +
      '                       <option value="1">Ouvert</option>' +
      '                        <option value="0">Fermé</option>' +
      '                         <option value="1">Allumé</option>' +
      '                          <option value="0">Eteint</option>' +
      '                       </select>' +
      '                    </div>' +
      '                 </div>' +
      '<div class="form-group"> ' +
      '<label class="col-xs-5 control-label" >{{Ensuite}}</label>' +
      '             <div class="col-xs-3">' +
      '                <select class="conditionAttr form-control" data-l1key="next">' +
      '                    <option value="">rien</option>' +
      '                  <option value="ET">{{et}}</option>' +
      '                  <option value="OU">{{ou}}</option>' +
      '            </select>' +
      '       </div>' +
      '</div>' +
      '</div> </div>' +
      '</form> </div>  </div>';
    }

    bootbox.dialog({
      title: "Ajout d'un nouveau scénario",
      message: message,
      buttons: {
        "Ne rien mettre": {
          className: "btn-default",
          callback: function () {
            expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human);
          }
        },
        success: {
          label: "Valider",
          className: "btn-primary",
          callback: function () {
           var condition = result.human;
           condition += ' ' + $('.conditionAttr[data-l1key=operator]').value();
           condition += ' ' + $('.conditionAttr[data-l1key=operande]').value();
           condition += ' ' + $('.conditionAttr[data-l1key=next]').value().' ';
           expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', condition);
           if($('.conditionAttr[data-l1key=next]').value() != ''){
              el.click();
           }
         }
       },
     }
   });


  }
});
});


$('body').delegate('.bt_selectOtherActionExpression', 'click', function (event) {
  var expression = $(this).closest('.expression');
  $('#md_selectOtherAction').modal('show');
  $("#bt_selectOtherActionSave").off().on('click', function (event) {
    expression.find('.expressionAttr[data-l1key=expression]').value($('#sel_otherAction').value());
    jeedom.cmd.displayActionOption(expression.find('.expressionAttr[data-l1key=expression]').value(), '', function (html) {
      expression.find('.expressionOptions').html(html);
    });
    $('#md_selectOtherAction').modal('hide');
  });


});


$('body').delegate('.bt_selectScenarioExpression', 'click', function (event) {
  var expression = $(this).closest('.expression');
  jeedom.scenario.getSelectModal({}, function (result) {
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
      expression.find('.expressionAttr[data-l1key=expression]').value(result.human);
    }
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human);
    }
  });
});

$('body').delegate('.expression .expressionAttr[data-l1key=expression]', 'focusout', function (event) {
  var el = $(this);
  if (el.closest('.expression').find('.expressionAttr[data-l1key=type]').value() == 'action') {
    var expression = el.closest('.expression').getValues('.expressionAttr');
    jeedom.cmd.displayActionOption(el.value(), init(expression[0].options), function (html) {
      el.closest('.expression').find('.expressionOptions').html(html);
    });
  }
});


/**************** Scheduler **********************/

$('.scenarioAttr[data-l1key=mode]').on('change', function () {
  if ($(this).value() == 'schedule' || $(this).value() == 'all') {
    $('.scheduleDisplay').show();
    $('#bt_addSchedule').show();
  } else {
    $('.scheduleDisplay').hide();
    $('#bt_addSchedule').hide();
  }
  if ($(this).value() == 'provoke' || $(this).value() == 'all') {
    $('.provokeDisplay').show();
    $('#bt_addTrigger').show();
  } else {
    $('.provokeDisplay').hide();
    $('#bt_addTrigger').hide();
  }
});

$('#bt_addTrigger').on('click', function () {
  addTrigger('');
});

$('#bt_addSchedule').on('click', function () {
  addSchedule('');
});

$('body').delegate('.bt_removeTrigger', 'click', function (event) {
  $(this).closest('.trigger').remove();
});

$('body').delegate('.bt_removeSchedule', 'click', function (event) {
  $(this).closest('.schedule').remove();
});

$('body').delegate('.bt_selectTrigger', 'click', function (event) {
  var el = $(this);
  jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
    el.closest('.trigger').find('.scenarioAttr[data-l1key=trigger]').value(result.human);
  });
});

$('body').delegate('.bt_sortable', 'mouseenter', function () {
  var expressions = $(this).closest('.expressions');
  $("#div_scenarioElement").sortable({
    axis: "y",
    cursor: "move",
    items: ".sortable",
    placeholder: "ui-state-highlight",
    forcePlaceholderSize: true,
    forceHelperSize: true,
    grid: [0, 11],
    refreshPositions: true,
    dropOnEmpty: false,
    update: function (event, ui) {
      if (ui.item.findAtDepth('.element', 2).length == 1) {
        ui.item.replaceWith(ui.item.findAtDepth('.element', 2));
      }
      if (ui.item.hasClass('element') && ui.item.parent().attr('id') != 'div_scenarioElement') {
        ui.item.replaceWith(addExpression({type: 'element', element: {html: ui.item.clone().wrapAll("<div/>").parent().html()}}));
      }
      if (ui.item.hasClass('expression') && ui.item.parent().attr('id') == 'div_scenarioElement') {
        $("#div_scenarioElement").sortable("cancel");
      }
      if (ui.item.closest('.subElement').hasClass('noSortable')) {
        $("#div_scenarioElement").sortable("cancel");
      }
      updateSortable();
    },
    start: function (event, ui) {
      if (expressions.find('.sortable').length < 3) {
        expressions.find('.sortable.empty').show();
      }
    },
  });
$("#div_scenarioElement").sortable("enable");
});

$('body').delegate('.bt_sortable', 'mouseout', function () {
  $("#div_scenarioElement").sortable("disable");

});

/***********************LOG*****************************/

$('#bt_logScenario').on('click', function () {
  $('#md_modal').dialog({title: "{{Log d'exécution du scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open');
});

/***********************EXPORT*****************************/

$('#bt_exportScenario').on('click', function () {
  $('#md_modal').dialog({title: "{{Export du scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.export&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open');
});

/***********************Template*****************************/

$('#bt_templateScenario').on('click', function () {
  $('#md_modal').dialog({title: "{{Template de scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.template&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open');
});


/**************** Initialisation **********************/

$('body').delegate('.scenarioAttr', 'change', function () {
  modifyWithoutSave = true;
});

$('body').delegate('.expressionAttr', 'change', function () {
  modifyWithoutSave = true;
});

$('body').delegate('.elementAttr', 'change', function () {
  modifyWithoutSave = true;
});

$('body').delegate('.subElementAttr', 'change', function () {
  modifyWithoutSave = true;
});

if (is_numeric(getUrlVars('id'))) {
  $('#div_tree').jstree('deselect_all');
  $('#div_tree').jstree('select_node', 'scenario' + getUrlVars('id'));
}

function updateSortable() {
  $('.element').removeClass('sortable');
  $('#div_scenarioElement > .element').addClass('sortable');
  $('.subElement .expressions').each(function () {
    if ($(this).children('.sortable:not(.empty)').length > 0) {
      $(this).children('.sortable.empty').hide();
    } else {
      $(this).children('.sortable.empty').show();
    }
  });
}

function setEditor() {
  $('.expressionAttr[data-l1key=type][value=code]').each(function () {
    var expression = $(this).closest('.expression');
    var code = expression.find('.expressionAttr[data-l1key=expression]');
    if (code.attr('id') == undefined) {
      code.uniqueId();
      var id = code.attr('id');
      setTimeout(function () {
        editor[id] = CodeMirror.fromTextArea(document.getElementById(id), {
          lineNumbers: true,
          mode: 'text/x-php',
          matchBrackets: true
        });
      }, 1);
    }
  });
}

function setAutocomplete() {
  $('.expression').each(function () {
    if ($(this).find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      $(this).find('.expressionAttr[data-l1key=expression]').sew({values: autoCompleteCondition, token: '[ |#]'});
    }
    if ($(this).find('.expressionAttr[data-l1key=type]').value() == 'action') {
      $(this).find('.expressionAttr[data-l1key=expression]').autocomplete({
        source: autoCompleteAction,
        close: function (event, ui) {
          $(this).trigger('focusout');
        }
      });
    }
  });
}

function printScenario(_id) {
  $.showLoading();
  jeedom.scenario.get({
    id: _id,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      if (data.type == 'simple') {
        $('#bt_switchToExpertMode').attr('href', 'index.php?v=d&p=scenarioAssist&id=' + _id)
      }
      pColor = 0;
      $('.scenarioAttr').value('');
      $('body').setValues(data, '.scenarioAttr');
      data.lastLaunch = (data.lastLaunch == null) ? '{{Jamais}}' : data.lastLaunch;
      $('#span_lastLaunch').text(data.lastLaunch);

      $('#div_scenarioElement').empty();
      $('#div_scenarioElement').append('<a class="btn btn-default bt_addScenarioElement tootlips" title="Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)"><i class="fa fa-plus-circle"></i> {{Ajouter bloc}}</a><br/><br/>');
      $('.provokeMode').empty();
      $('.scheduleMode').empty();
      $('.scenarioAttr[data-l1key=mode]').trigger('change');
      for (var i in data.schedules) {
        $('#div_schedules').schedule.display(data.schedules[i]);
      }
      $('#bt_stopScenario').hide();
      switch (data.state) {
        case 'error' :
        $('#span_ongoing').text('Erreur');
        $('#span_ongoing').removeClass('label-info label-danger label-success').addClass('label-warning');
        break;
        case 'on' :
        $('#span_ongoing').text('Actif');
        $('#span_ongoing').removeClass('label-info label-danger label-warning').addClass('label-success');
        break;
        case 'in progress' :
        $('#span_ongoing').text('En cours');
        $('#span_ongoing').addClass('label-success');
        $('#span_ongoing').removeClass('label-success label-danger label-warning').addClass('label-info');
        $('#bt_stopScenario').show();
        break;
        case 'stop' :
        $('#span_ongoing').text('Arrêté');
        $('#span_ongoing').removeClass('label-info label-success label-warning').addClass('label-danger');
        break;
      }
      if (data.isActive != 1) {
        $('#in_ongoing').text('Inactif');
        $('#in_ongoing').removeClass('label-danger');
        $('#in_ongoing').removeClass('label-success');
      }
      if ($.isArray(data.trigger)) {
        for (var i in data.trigger) {
          if (data.trigger[i] != '' && data.trigger[i] != null) {
            addTrigger(data.trigger[i]);
          }
        }
      } else {
        if (data.trigger != '' && data.trigger != null) {
          addTrigger(data.trigger);
        }
      }
      if ($.isArray(data.schedule)) {
        for (var i in data.schedule) {
          if (data.schedule[i] != '' && data.schedule[i] != null) {
            addSchedule(data.schedule[i]);
          }
        }
      } else {
        if (data.schedule != '' && data.schedule != null) {
          addSchedule(data.schedule);
        }
      }

      if(data.elements.length == 0){
        $('#div_scenarioElement').append('<center><span style=\'color:#767676;font-size:1.2em;font-weight: bold;\'>Pour constituer votre scénario veuillez ajouter des blocs</span></center>')
      }

      for (var i in data.elements) {
        $('#div_scenarioElement').append(addElement(data.elements[i]));
      }
      updateSortable();
      setEditor();
      setAutocomplete();
      $('#div_editScenario').show();
      $.hideLoading();
      modifyWithoutSave = false;
      setTimeout(function () {
        modifyWithoutSave = false;
      }, 1000);
    }
  });
}

function saveScenario() {
  $.hideAlert();
  var scenario = $('body').getValues('.scenarioAttr')[0];
  scenario.type = "expert";
  var elements = [];
  $('#div_scenarioElement').children('.element').each(function () {
    elements.push(getElement($(this)));
  });
  scenario.elements = elements;
  jeedom.scenario.save({
    scenario: scenario,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
      modifyWithoutSave = false;
      if ($('#ul_scenario .li_scenario[data-scenario_id=' + data.id + ']').length != 0) {
        $('#ul_scenario .li_scenario[data-scenario_id=' + data.id + ']').click();
      } else {
        var vars = getUrlVars();
        var url = 'index.php?';
        for (var i in vars) {
          if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
            url += i + '=' + vars[i].replace('#', '') + '&';
          }
        }
        url += 'id=' + data.id + '&saveSuccessFull=1';
        window.location.href = url;
      }
    }
  });
}

function addTrigger(_trigger) {
  var div = '<div class="form-group trigger">';
  div += '<label class="col-xs-3 control-label">{{Evènement}}</label>';
  div += '<div class="col-xs-7">';
  div += '<input class="scenarioAttr input-sm form-control" data-l1key="trigger" value="' + _trigger + '" >';
  div += '</div>';
  div += '<div class="col-xs-1">';
  div += '<a class="btn btn-default btn-xs cursor bt_selectTrigger"><i class="fa fa-list-alt"></i></a>';
  div += '</div>';
  div += '<div class="col-xs-1">';
  div += '<i class="fa fa-minus-circle bt_removeTrigger cursor"></i>';
  div += '</div>';
  div += '</div>';
  $('.provokeMode').append(div);
}

function addSchedule(_schedule) {
  var div = '<div class="form-group schedule">';
  div += '<label class="col-xs-3 control-label">{{Programmation}}</label>';
  div += '<div class="col-xs-7">';
  div += '<input class="scenarioAttr input-sm form-control" data-l1key="schedule" value="' + _schedule + '">';
  div += '</div>';
  div += '<div class="col-xs-1">';
  div += '<i class="fa fa-question-circle cursor floatright helpSelectCron"></i>';
  div += '</div>';
  div += '<div class="col-xs-1">';
  div += '<i class="fa fa-minus-circle bt_removeSchedule cursor"></i>';
  div += '</div>';
  div += '</div>';
  $('.scheduleMode').append(div);
}

function addExpression(_expression) {
  if (!isset(_expression.type) || _expression.type == '') {
    return '';
  }
  var retour = '<div class="expression row sortable" style="margin-top : 4px;">';
  retour += '<input class="expressionAttr" data-l1key="id" style="display : none;" value="' + init(_expression.id) + '"/>';
  retour += '<input class="expressionAttr" data-l1key="scenarioSubElement_id" style="display : none;" value="' + init(_expression.scenarioSubElement_id) + '"/>';
  retour += '<input class="expressionAttr" data-l1key="type" style="display : none;" value="' + init(_expression.type) + '"/>';
  switch (_expression.type) {
    case 'condition' :
    if (isset(_expression.expression)) {
      _expression.expression = _expression.expression.replace(/"/g, '&quot;');
    }
    retour += '<div class="col-xs-11" style="position : relative; top : 5px;">';
    retour += '<textarea class="expressionAttr form-control input-sm" data-l1key="expression" style="resize: vertical;height : 27px;" rows="1">' + init(_expression.expression) + '</textarea>';
    retour += '</div>';
    retour += '<div class="col-xs-1">';
    retour += ' <a class="btn btn-default btn-xs cursor bt_selectCmdExpression" style="position : relative; top : 3px;" title="Rechercher une commande"><i class="fa fa-list-alt"></i></a>';
    retour += ' <a class="btn btn-default btn-xs cursor bt_selectScenarioExpression" style="position : relative; top : 3px;" title="Rechercher un scenario"><i class="fa fa-history"></i></a>';
    retour += '</div>';
    break;
    case 'element' :
    retour += '<div class="col-xs-12">';
    if (isset(_expression.element) && isset(_expression.element.html)) {
      retour += _expression.element.html;
    } else {
      var element = addElement(_expression.element, true);
      if ($.trim(element) == '') {
        return '';
      }
      retour += element;
    }
    retour += '</div>';
    break;
    case 'action' :
    retour += '<div class="col-xs-2">';
    retour += '<i class="fa fa-arrows-v pull-left cursor bt_sortable" style="margin-top : 9px;"></i>';
    retour += '<i class="fa fa-minus-circle pull-left cursor bt_removeExpression" style="margin-top : 9px;"></i>';
    if (!isset(_expression.options) || !isset(_expression.options.enable) || _expression.options.enable == 1) {
      retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" checked style="margin-top : 9px;" title="Décocher pour desactiver l\'action"/>';
    } else {
      retour += '<input type="checkbox" class="expressionAttr" data-l1key="options" data-l2key="enable" style="margin-top : 9px;" title="Decocher pour desactiver l\'action"/>';
    }
    retour += ' <a class="btn btn-default btn-xs cursor bt_selectOtherActionExpression pull-right" style="margin-top : 4px;" title="{{Sélectionner un mot-clé}}"><i class="fa fa-tasks"></i></a>';
    retour += ' <a class="btn btn-default btn-xs cursor bt_selectCmdExpression pull-right" style="margin-top : 4px;" title="{{Sélectionner la commande}}"><i class="fa fa-list-alt"></i></a>';
    retour += '</div>';
    retour += '<div class="col-xs-3">';
    retour += '<input class="expressionAttr form-control input-sm" data-l1key="expression" value="' + init(_expression.expression) + '" style="font-weight:bold;"/>';
    retour += '</div>';
    retour += '<div class="col-xs-7 expressionOptions">';
    retour += jeedom.cmd.displayActionOption(init(_expression.expression), init(_expression.options));
    retour += '</div>';
    break;
    case 'code' :
    retour += '<div class="col-xs-1">';
    retour += '<i class="fa fa-bars pull-left cursor bt_sortable" style="margin-top : 9px;"></i>';
    retour += '</div>';
    retour += '<div class="col-xs-11">';
    retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>';
    retour += '</div>';
    break;
    case 'comment' :
    retour += '<div class="col-xs-1">';
    retour += '</div>';
    retour += '<div class="col-xs-11">';
    retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>';
    retour += '</div>';
    break;
  }
  retour += '</div>';
  return retour;
}

function addSubElement(_subElement) {
  if (!isset(_subElement.type) || _subElement.type == '') {
    return '';
  }
  if (!isset(_subElement.options)) {
    _subElement.options = {};
  }
  var noSortable = '';
  if (_subElement.type == 'if' || _subElement.type == 'for' || _subElement.type == 'code') {
    noSortable = 'noSortable';
  }
  var retour = '<div class="subElement ' + noSortable + '" style="position : relative;top : -8px;">';
  retour += '<input class="subElementAttr" data-l1key="id" style="display : none;" value="' + init(_subElement.id) + '"/>';
  retour += '<input class="subElementAttr" data-l1key="scenarioElement_id" style="display : none;" value="' + init(_subElement.scenarioElement_id) + '"/>';
  retour += '<input class="subElementAttr" data-l1key="type" style="display : none;" value="' + init(_subElement.type) + '"/>';
  switch (_subElement.type) {
    case 'if' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '<legend style="margin-top : 0px;margin-bottom : 0px;color : inherit;font-weight:bold;border : none;font-size:1.2em;"><div style="position : relative;left:15px;">{{SI}} ';
    retour += '<div class="expressions" style="display : inline-block; width : 90%">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '</div>';
    retour += '</div></legend>';
    break;
    case 'then' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '<legend style="margin-top : 0px;margin-bottom : 0px;color : inherit;font-weight:bold;border : none;border-top: 1px solid #e5e5e5;font-size:1.2em;">{{ALORS}}';
    retour += '<a class="btn btn-xs btn-default bt_addScenarioElement pull-right fromSubElement tootlips" style="position : relative; top : 2px;" title="Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)"><i class="fa fa-plus-circle"></i> {{Ajouter bloc}}</a>';
    retour += '<a class="btn btn-xs btn-default bt_addAction pull-right" style="position : relative; top : 2px;"><i class="fa fa-plus-circle"></i> {{Ajouter action}}</a>';
    retour += '</legend>';
    retour += '<div class="expressions">';
    retour += '<div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '</div>';
    break;
    case 'else' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '<legend style="margin-top : 2px;margin-bottom : 0px;color : inherit;font-weight:bold;border : none;border-top: 1px solid #e5e5e5;font-size:1.2em;">{{SINON}}';
    retour += '<a class="btn btn-xs btn-default bt_addScenarioElement pull-right fromSubElement tootlips" style="position : relative; top : 2px;"><i class="fa fa-plus-circle" title="Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)"></i> {{Ajouter bloc}}</a>';
    retour += '<a class="btn btn-xs btn-default bt_addAction pull-right" style="position : relative; top : 2px;"><i class="fa fa-plus-circle"></i> {{Ajouter action}}</a>';
    retour += '</legend>';
    retour += '<div class="expressions">';
    retour += '<div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '</div>';
    break;
    case 'for' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '<legend style="margin-top : 0px;margin-bottom : 5px;color : inherit;font-weight:bold;border : none;"><span style="position : relative;left:15px;">{{DE 1 A}} ';
    retour += '<div class="expressions" style="display : inline-block; width : 90%">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '</div>';
    retour += '</span></legend>';
    break;
    case 'in' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '<legend style="margin-top : 0px;margin-bottom : 5px;color : inherit;font-weight:bold;border : none;"><span style="position : relative;left:15px;">{{DANS (min)}} ';
    retour += '<div class="expressions" style="display : inline-block; width : 90%">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '</div>';
    retour += '</span></legend>';
    break;
    case 'at' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '<legend style="margin-top : 0px;margin-bottom : 5px;color : inherit;font-weight:bold;border : none;"><span style="position : relative;left:15px;">{{A (Hmm)}} ';
    retour += '<div class="expressions" style="display : inline-block; width : 90%">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '</div>';
    retour += '</span></legend>';
    break;
    case 'do' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '<legend style="margin-top : 3px;margin-bottom : 5px;color : inherit;font-weight:bold;border : none;border-top: 1px solid #e5e5e5;">FAIRE';
    retour += '<a class="btn btn-xs btn-default bt_addScenarioElement pull-right fromSubElement tootlips" style="position : relative; top : 2px;" title="Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)"><i class="fa fa-plus-circle"></i> {{Ajouter bloc}}</a>';
    retour += '<a class="btn btn-xs btn-default bt_addAction pull-right" style="position : relative; top : 2px;"><i class="fa fa-plus-circle"></i> {{Ajouter action}}</a>';
    retour += '</legend>';
    retour += '<div class="expressions">';
    retour += '<div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '</div>';
    break;
    case 'code' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '<legend style="margin-top : 0px;margin-bottom : 5px;color : inherit;border : none;"><div style="position : relative;left:15px;">{{CODE}}';
    retour += '</div></legend>';
    retour += '<div class="expressions">';
    retour += '<div class="sortable empty" style="height : 30px;"></div>';
    var expression = {type: 'code'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '</div>';
    break;
    case 'comment' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="comment"/><br/>';
    retour += '<div class="expressions">';
    retour += '<div class="sortable empty" style="height : 30px;"></div>';
    var expression = {type: 'comment'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '</div>';
    break;
    case 'action' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '<legend style="margin-top : 2px;margin-bottom : 5px;color : inherit;border : none;"><div style="position : relative;left:15px;top:2px">{{ACTION}}';
    retour += '<a class="btn btn-xs btn-default bt_addScenarioElement pull-right fromSubElement tootlips" style="position : relative; top : 10px;left:-35px;" title="Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)"><i class="fa fa-plus-circle"></i> {{Ajouter bloc}}</a>';
    retour += '<a class="btn btn-xs btn-default bt_addAction pull-right" style="position : relative; top : 10px;left:-35px;"><i class="fa fa-plus-circle"></i> {{Ajouter action}}</a>';
    retour += '</div></legend>';
    retour += '<div class="expressions" style="margin-top : 8px;">';
    retour += '<div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '</div>';
    break;
  }
  retour += '</div>';
  return retour;
}


function addElement(_element) {
  if (!isset(_element)) {
    return;
  }
  if (!isset(_element.type) || _element.type == '') {
    return '';
  }
  color = listColor[pColor];
  pColor++;
  if (pColor > 4) {
    pColor = 0;
  }
  var div = '<div class="element" style="color : white;padding-right : 7px;padding-left : 7px;padding-bottom : 0px;margin-bottom : 2px;background-color : ' + color + '">';
  div += '<input class="elementAttr" data-l1key="id" style="display : none;" value="' + init(_element.id) + '"/>';
  div += '<input class="elementAttr" data-l1key="type" style="display : none;" value="' + init(_element.type) + '"/>';
  div += '<i class="fa fa-arrows-v pull-left cursor bt_sortable" style="position : relative; top : 15px;z-index : 2;"></i>';
  div += '<i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i>';
  switch (_element.type) {
    case 'if' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j]);
      }
    } else {
      div += addSubElement({type: 'if'});
      div += addSubElement({type: 'then'});
      div += addSubElement({type: 'else'});
    }
    break;
    case 'for' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j]);
      }
    } else {
      div += addSubElement({type: 'for'});
      div += addSubElement({type: 'do'});
    }
    break;
    case 'in' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j]);
      }
    } else {
      div += addSubElement({type: 'in'});
      div += addSubElement({type: 'do'});
    }
    break;
    case 'at' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j]);
      }
    } else {
      div += addSubElement({type: 'at'});
      div += addSubElement({type: 'do'});
    }
    break;
    case 'code' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j]);
      }
    } else {
      div += addSubElement({type: 'code'});
    }
    break;
    case 'comment' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j]);
      }
    } else {
      div += addSubElement({type: 'comment'});
    }
    break;
    case 'action' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j]);
      }
    } else {
      div += addSubElement({type: 'action'});
    }
    break;
  }
  div += '</div>';
  return div;
}

function getElement(_element) {
  var element = _element.getValues('.elementAttr', 1);
  if (element.length == 0) {
    return;
  }
  element = element[0];
  element.subElements = [];

  _element.findAtDepth('.subElement', 2).each(function () {
    var subElement = $(this).getValues('.subElementAttr', 2);
    subElement = subElement[0];
    subElement.expressions = [];
    var expression_dom = $(this).children('.expressions');
    if (expression_dom.length == 0) {
      expression_dom = $(this).children('legend').findAtDepth('.expressions', 2);
    }
    expression_dom.children('.expression').each(function () {
      var expression = $(this).getValues('.expressionAttr', 3);
      expression = expression[0];
      if (expression.type == 'element') {
        expression.element = getElement($(this).findAtDepth('.element', 2));
      }
      if (subElement.type == 'code') {
        var id = $(this).find('.expressionAttr[data-l1key=expression]').attr('id');
        if (id != undefined && isset(editor[id])) {
          expression.expression = editor[id].getValue();
        }
      }
      subElement.expressions.push(expression);

    });
    element.subElements.push(subElement);
  });
  return element;
}
