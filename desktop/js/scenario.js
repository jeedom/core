
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
 listColorStrong = ['#12846D', '#229351', '#246F9E', '#634F96', '#D88811', '#B74600', '#A53026', '#1D2935', '#687272'];
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
 {val: 'lastChangeStateDuration(commande,value)'},
 {val: 'median(commande1,commande2)'},
 {val: 'time(value)'},
 {val: 'collectDate(cmd)'},
 {val: 'valueDate(cmd)'},
 {val: 'eqEnable(equipement)'}
 ];
 autoCompleteAction = ['report','sleep', 'variable', 'scenario', 'stop', 'wait','gotodesign','log','message','equipement','ask','jeedom_poweroff','scenario_return','alert','popup','icon','event'];

 if (getUrlVars('saveSuccessFull') == 1) {
  $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
}

if((!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1) && !jQuery.support.touch){
  $('#div_listScenario').hide();
  $('#scenarioThumbnailDisplay').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
  $('#div_editScenario').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');

  $('#bt_displayScenarioList').on('mouseenter',function(){
   var timer = setTimeout(function(){
    $('#bt_displayScenarioList').find('i').hide();
    $('#scenarioThumbnailDisplay').addClass('col-lg-10 col-md-10 col-sm-9').removeClass('col-lg-12');
    $('#div_editScenario').addClass('col-lg-10 col-md-10 col-sm-9').removeClass('col-lg-12');
    $('#div_listScenario').show();
    $('.scenarioListContainer').packery();
  }, 100);
   $(this).data('timerMouseleave', timer)
 }).on("mouseleave", function(){
  clearTimeout($(this).data('timerMouseleave'));
});

 $('#div_listScenario').on('mouseleave',function(){
  var timer = setTimeout(function(){
   $('#div_listScenario').hide();
   $('#bt_displayScenarioList').find('i').show();
   $('#scenarioThumbnailDisplay').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
   $('#div_editScenario').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
   $('.scenarioListContainer').packery();
 }, 300);
  $(this).data('timerMouseleave', timer);
}).on("mouseenter", function(){
  clearTimeout($(this).data('timerMouseleave'));
});
}

setTimeout(function(){
  $('.scenarioListContainer').packery();
},100);

$("#div_listScenario").trigger('resize');

$('.scenarioListContainer').packery();

$('#bt_scenarioThumbnailDisplay').off('click').on('click', function () {
  $('#div_editScenario').hide();
  $('#scenarioThumbnailDisplay').show();
  $('.li_scenario').removeClass('active');
  $('.scenarioListContainer').packery();
});

$('.scenarioDisplayCard').off('click').on('click', function () {
  $('#div_tree').jstree('open_all');
  $('#div_tree').jstree('deselect_all');
  $('#div_tree').jstree('select_node', 'scenario' + $(this).attr('data-scenario_id'));
});

$('.accordion-toggle').off('click').on('click', function () {
  setTimeout(function(){
    $('.scenarioListContainer').packery();
  },100);
});

$('#div_tree').off('click').on('select_node.jstree', function (node, selected) {
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

$("#bt_changeAllScenarioState,#bt_changeAllScenarioState2").off('click').on('click', function () {
  var el = $(this);
  jeedom.config.save({
    configuration: {enableScenario: el.attr('data-state')},
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
     loadPage('index.php?v=d&p=scenario');
   }
 });
});

$("#bt_addScenario,#bt_addScenario2").off('click').on('click', function (event) {
  bootbox.dialog({
    title: "{{Ajout d'un nouveau scénario}}",
    message: '<div class="row">  ' +
    '<div class="col-md-12"> ' +
    '<form class="form-horizontal" onsubmit="return false;"> ' +
    '<div class="form-group"> ' +
    '<label class="col-md-4 control-label">{{Nom}}</label> ' +
    '<div class="col-md-4"> ' +
    '<input id="in_scenarioAddName" type="text" placeholder="{{Nom de votre scénario}}" class="form-control input-md"> ' +
    '</div> ' +
    '</div> ' +
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
              loadPage(url);
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

$("#bt_saveScenario,#bt_saveScenario2").off('click').on('click', function (event) {
  saveScenario();
});

$("#bt_delScenario,#bt_delScenario2").off('click').on('click', function (event) {
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
          loadPage('index.php?v=d&p=scenario');
        }
      });
    }
  });
});

$("#bt_testScenario,#bt_testScenario2").off('click').on('click', function () {
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

$("#bt_copyScenario").off('click').on('click', function () {
  bootbox.prompt("Nom du scénario ?", function (result) {
    if (result !== null) {
      jeedom.scenario.copy({
        id: $('.scenarioAttr[data-l1key=id]').value(),
        name: result,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
          loadPage('index.php?v=d&p=scenario&id=' + data.id);
        }
      });
    }
  });
});

$("#bt_stopScenario").off('click').on('click', function () {
  jeedom.scenario.changeState({
    id: $('.scenarioAttr[data-l1key=id]').value(),
    state: 'stop',
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
      $('#div_alert').showAlert({message: '{{Arrêt du scénario réussi}}', level: 'success'});
    }
  });
});

$('#bt_displayScenarioVariable,#bt_displayScenarioVariable2').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Variables des scénarios}}"});
  $("#md_modal").load('index.php?v=d&modal=dataStore.management&type=scenario').dialog('open');
});

$('.bt_showExpressionTest').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Testeur d'expression}}"});
  $("#md_modal").load('index.php?v=d&modal=expression.test').dialog('open');
});

$('.bt_showScenarioSummary').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Résumé scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.summary').dialog('open');
});

$('#in_addElementType').off('change').on('change',function(){
  $('.addElementTypeDescription').hide();
  $('.addElementTypeDescription.'+$(this).value()).show();
});

/*******************Element***********************/

$('#div_pageContainer').off('click','.helpSelectCron').on('click','.helpSelectCron',function(){
  var el = $(this).closest('.schedule').find('.scenarioAttr[data-l1key=schedule]');
  jeedom.getCronSelectModal({},function (result) {
    el.value(result.value);
  });
});

$('#div_pageContainer').off('click','.bt_addScenarioElement').on( 'click','.bt_addScenarioElement', function (event) {
  var elementDiv = $(this).closest('.element');
  var expression = false;
  if ($(this).hasClass('fromSubElement')) {
    elementDiv = $(this).closest('.subElement').find('.expressions').eq(0);
    expression = true;
  }
  $('#md_addElement').modal('show');
  $("#bt_addElementSave").off('click').on('click', function (event) {
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

$('#div_pageContainer').off('click','.bt_removeElement').on('click','.bt_removeElement',  function (event) {
  if ($(this).closest('.expression').length != 0) {
    $(this).closest('.expression').remove();
  } else {
    $(this).closest('.element').remove();
  }
});

$('#div_pageContainer').off('click','.bt_addAction').on( 'click','.bt_addAction', function (event) {
  $(this).closest('.subElement').children('.expressions').append(addExpression({type: 'action'}));
  setAutocomplete();
  updateSortable();
});

$('#div_pageContainer').off('click','.bt_addSinon').on( 'click','.bt_addSinon', function (event) {
 
  if($(this).children("i").hasClass('fa-chevron-right')){
      $(this).children("i").removeClass('fa-chevron-right').addClass('fa-chevron-down');
       $(this).closest('.subElement').next().css('display','table');
  }
  else
  {
      if($(this).closest('.subElement').next().children('.expressions').children('.expression').length>0)
      {
         alert("{{Le bloc Sinon ne peut être supprimé s'il contient des éléments}}");
      }
      else
      {  
         $(this).children("i").removeClass('fa-chevron-down').addClass('fa-chevron-right');
         $(this).closest('.subElement').next().css('display','none');
      }
  }
});

$('#div_pageContainer').off('click','.bt_addSinon').on( 'click','.bt_addSinon', function (event) {
 
  if($(this).children("i").hasClass('fa-chevron-right')){
      $(this).children("i").removeClass('fa-chevron-right').addClass('fa-chevron-down');
       $(this).closest('.subElement').next().css('display','table');
  }
  else
  {
      if($(this).closest('.subElement').next().children('.expressions').children('.expression').length>0)
      {
         alert("{{Le bloc Sinon ne peut être supprimé s'il contient des éléments}}");
      }
      else
      {  
         $(this).children("i").removeClass('fa-chevron-down').addClass('fa-chevron-right');
         $(this).closest('.subElement').next().css('display','none');
      }
  }
});

$('#div_pageContainer').off('click','.bt_removeExpression').on('click','.bt_removeExpression',  function (event) {
  $(this).closest('.expression').remove();
  updateSortable();
});

$('#div_pageContainer').off('click','.bt_selectCmdExpression').on('click','.bt_selectCmdExpression',  function (event) {
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
       '                    <option value="==">{{égal}}</option>' +
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
      '                  <option value="matches">{{contient}}</option>' +
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
      '                    <option value="">{{rien}}</option>' +
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
      '                       <option value="1">{{Ouvert}}</option>' +
      '                       <option value="0">{{Fermé}}</option>' +
      '                       <option value="1">{{Allumé}}</option>' +
      '                       <option value="0">{{Eteint}}</option>' +
      '                       <option value="1">{{Déclenché}}</option>' +
      '                       <option value="0">{{Au repos}}</option>' +
      '                       </select>' +
      '                    </div>' +
      '                 </div>' +
      '<div class="form-group"> ' +
      '<label class="col-xs-5 control-label" >{{Ensuite}}</label>' +
      '             <div class="col-xs-3">' +
      '                <select class="conditionAttr form-control" data-l1key="next">' +
      '                  <option value="">{{rien}}</option>' +
      '                  <option value="ET">{{et}}</option>' +
      '                  <option value="OU">{{ou}}</option>' +
      '            </select>' +
      '       </div>' +
      '</div>' +
      '</div> </div>' +
      '</form> </div>  </div>';
    }

    bootbox.dialog({
      title: "{{Ajout d'un nouveau scénario}}",
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
           if(result.cmd.subType == 'string'){
            if($('.conditionAttr[data-l1key=operator]').value() == 'matches'){
              condition += ' "/' + $('.conditionAttr[data-l1key=operande]').value()+'/"';
            }else{
             condition += ' "' + $('.conditionAttr[data-l1key=operande]').value()+'"';
           }
         }else{
          condition += ' ' + $('.conditionAttr[data-l1key=operande]').value();
        }
        condition += ' ' + $('.conditionAttr[data-l1key=next]').value()+' ';
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


$('#div_pageContainer').off('click','.bt_selectOtherActionExpression').on('click','.bt_selectOtherActionExpression',  function (event) {
  var expression = $(this).closest('.expression');
  jeedom.getSelectActionModal({scenario : true}, function (result) {
   expression.find('.expressionAttr[data-l1key=expression]').value(result.human);
   jeedom.cmd.displayActionOption(expression.find('.expressionAttr[data-l1key=expression]').value(), '', function (html) {
    expression.find('.expressionOptions').html(html);
  });
 });
});


$('#div_pageContainer').off('click','.bt_selectScenarioExpression').on('click','.bt_selectScenarioExpression',  function (event) {
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

$('#div_pageContainer').off('click','.bt_selectEqLogicExpression').on('click','.bt_selectEqLogicExpression',  function (event) {
  var expression = $(this).closest('.expression');
  jeedom.eqLogic.getSelectModal({}, function (result) {
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'action') {
      expression.find('.expressionAttr[data-l1key=expression]').value(result.human);
    }
    if (expression.find('.expressionAttr[data-l1key=type]').value() == 'condition') {
      expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human);
    }
  });
});

$('#div_pageContainer').off('click','.expression .expressionAttr[data-l1key=expression]').on('focusout','.expression .expressionAttr[data-l1key=expression]',  function (event) {
  var el = $(this);
  if (el.closest('.expression').find('.expressionAttr[data-l1key=type]').value() == 'action') {
    var expression = el.closest('.expression').getValues('.expressionAttr');
    jeedom.cmd.displayActionOption(el.value(), init(expression[0].options), function (html) {
      el.closest('.expression').find('.expressionOptions').html(html);
    });
  }
});


/**************** Scheduler **********************/

$('.scenarioAttr[data-l1key=mode]').off('change').on('change', function () {
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

$('#bt_addTrigger').off('click').on('click', function () {
  addTrigger('');
});

$('#bt_addSchedule').off('click').on('click', function () {
  addSchedule('');
});

$('#div_pageContainer').off('click','.bt_removeTrigger').on('click','.bt_removeTrigger',  function (event) {
  $(this).closest('.trigger').remove();
});

$('#div_pageContainer').off('click','.bt_removeSchedule').on('click','.bt_removeSchedule',  function (event) {
  $(this).closest('.schedule').remove();
});

$('#div_pageContainer').off('click','.bt_selectTrigger').on('click','.bt_selectTrigger',  function (event) {
  var el = $(this);
  jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
    el.closest('.trigger').find('.scenarioAttr[data-l1key=trigger]').value(result.human);
  });
});

$('#div_pageContainer').off('click','.bt_selectDataStoreTrigger').on( 'click','.bt_selectDataStoreTrigger', function (event) {
  var el = $(this);
  jeedom.dataStore.getSelectModal({cmd: {type: 'info'}}, function (result) {
    el.closest('.trigger').find('.scenarioAttr[data-l1key=trigger]').value(result.human);
  });
});


$('#div_pageContainer').off('mouseenter','.bt_sortable').on('mouseenter','.bt_sortable',  function () {
  var expressions = $(this).closest('.expressions');
  $("#div_scenarioElement").sortable({
    axis: "y",
    cursor: "move",
    items: ".sortable",
    opacity: 0.5,
    forcePlaceholderSize: true,
    forceHelperSize: true,
    grid: [0, 11],
    refreshPositions: true,
    dropOnEmpty: false,
    update: function (event, ui) {
      if (ui.item.findAtDepth('.element', 2).length == 1 && ui.item.parent().attr('id') == 'div_scenarioElement') {
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

$('#div_pageContainer').off('mouseout','.bt_sortable').on('mouseout','.bt_sortable',  function () {
  $("#div_scenarioElement").sortable("disable");

});

$('#bt_graphScenario').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Graphique de lien}}"});
  $("#md_modal").load('index.php?v=d&modal=graph.link&filter_type=scenario&filter_id='+$('.scenarioAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_logScenario').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Log d'exécution du scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.log.execution&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_exportScenario').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Export du scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.export&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open');
});

$('#bt_templateScenario').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Template de scénario}}"});
  $("#md_modal").load('index.php?v=d&modal=scenario.template&scenario_id=' + $('.scenarioAttr[data-l1key=id]').value()).dialog('open');
});

/**************** Initialisation **********************/

$('#div_pageContainer').on('change','.scenarioAttr',  function () {
  modifyWithoutSave = true;
});

$('#div_pageContainer').on('change','.expressionAttr',  function () {
  modifyWithoutSave = true;
});

$('#div_pageContainer').on('change','.elementAttr',  function () {
  modifyWithoutSave = true;
});

$('#div_pageContainer').on('change', '.subElementAttr', function () {
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

function updateElseToggle() {
  $('.subElementElse').each(function () {
    if ($(this).parent().css('display')=='table') $(this).parent().prev().find('.bt_addSinon:first').children('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
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
  jeedom.scenario.update[_id] =function(_options){
    if(_options.scenario_id =! $('#div_pageContainer').getValues('.scenarioAttr')[0]['id']){
      return;
    }
    switch(_options.state){
     case 'error' :
     $('#bt_stopScenario').hide();
     $('#span_ongoing').text('{{Erreur}}');
     $('#span_ongoing').removeClass('label-info label-danger label-success').addClass('label-warning');
     break;
     case 'on' :
     $('#bt_stopScenario').show();
     $('#span_ongoing').text('{{Actif}}');
     $('#span_ongoing').removeClass('label-info label-danger label-warning').addClass('label-success');
     break;
     case 'in progress' :
     $('#bt_stopScenario').show();
     $('#span_ongoing').text('{{En cours}}');
     $('#span_ongoing').addClass('label-success');
     $('#span_ongoing').removeClass('label-success label-danger label-warning').addClass('label-info');
     break;
     case 'stop' :
     $('#bt_stopScenario').hide();
     $('#span_ongoing').text('{{Arrêté}}');
     $('#span_ongoing').removeClass('label-info label-success label-warning').addClass('label-danger');
     break;
   }
 }
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
    $('.scenarioAttr[data-l1key=object_id] option:first').attr('selected',true);
    $('.scenarioAttr[data-l1key=object_id]').val('');
    $('#div_pageContainer').setValues(data, '.scenarioAttr');
    data.lastLaunch = (data.lastLaunch == null) ? '{{Jamais}}' : data.lastLaunch;
    $('#span_lastLaunch').text(data.lastLaunch);

    $('#div_scenarioElement').empty();
    $('#div_scenarioElement').append('<a class="btn btn-default btn-sm pull-right bt_addScenarioElement tootlips" title="{{Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)}}"><i class="fa fa-plus-circle"></i> {{Ajouter bloc}}</a><br/><br/>');
    $('.provokeMode').empty();
    $('.scheduleMode').empty();
    $('.scenarioAttr[data-l1key=mode]').trigger('change');
    for (var i in data.schedules) {
      $('#div_schedules').schedule.display(data.schedules[i]);
    }
    jeedom.scenario.update[_id](data);
    if (data.isActive != 1) {
      $('#in_ongoing').text('{{Inactif}}');
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
    actionOptions = []
    for (var i in data.elements) {
      $('#div_scenarioElement').append(addElement(data.elements[i]));
    }
    jeedom.cmd.displayActionsOption({
      params : actionOptions,
      async : false,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
      },
      success : function(data){
       $.showLoading();
       for(var i in data){
        $('#'+data[i].id).append(data[i].html.html);
      }
      $.hideLoading();
    }
  });
    updateSortable();
    setEditor();
    setAutocomplete();
    updateElseToggle();
    $('#div_editScenario').show();
    modifyWithoutSave = false;
    setTimeout(function () {
      modifyWithoutSave = false;
    }, 1000);
  }
});
}

function saveScenario() {
  $.hideAlert();
  var scenario = $('#div_pageContainer').getValues('.scenarioAttr')[0];
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
      $('#div_alert').showAlert({message: '{{Sauvegarde du scénario réussi}}', level: 'success'});
      printScenario(scenario.id);
    }
  });
}

function addTrigger(_trigger) {
  var div = '<div class="form-group trigger">';
  div += '<label class="col-xs-3 control-label">{{Evénement}}</label>';
  div += '<div class="col-xs-9">';
  div += '<div class="input-group">';
  div += '<input class="scenarioAttr input-sm form-control" data-l1key="trigger" value="' + _trigger.replace(/"/g,'&quot;') + '" >';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-default btn-sm cursor bt_selectTrigger" title="{{Choisir une commande}}"><i class="fa fa-list-alt"></i></a>';
  div += '<a class="btn btn-default btn-sm cursor bt_selectDataStoreTrigger" title="{{Choisir une variable}}"><i class="fa fa-calculator"></i></a>';
  div += '<a class="btn btn-default btn-sm cursor bt_removeTrigger"><i class="fa fa-minus-circle"></i></a>';
  div += '</span>';
  div += '</div>';
  div += '</div>';
  div += '</div>';
  $('.provokeMode').append(div);
}

function addSchedule(_schedule) {
  var div = '<div class="form-group schedule">';
  div += '<label class="col-xs-3 control-label">{{Programmation}}</label>';
  div += '<div class="col-xs-9">';
  div += '<div class="input-group">';
  div += '<input class="scenarioAttr input-sm form-control" data-l1key="schedule" value="' + _schedule.replace(/"/g,'&quot;') + '">';
  div += '<span class="input-group-btn">';
  div += '<a class="btn btn-default btn-sm cursor helpSelectCron"><i class="fa fa-question-circle"></i></a>';
  div += '<a class="btn btn-default btn-sm cursor bt_removeSchedule"><i class="fa fa-minus-circle"></i></a>';
  div += '</span>';
  div += '</div>';
  div += '</div>';
  div += '</div>';
  $('.scheduleMode').append(div);
}

function addExpression(_expression) {
  if (!isset(_expression.type) || _expression.type == '') {
    return '';
  }
  var sortable = 'sortable';
  if (_expression.type == 'condition') {
    sortable = 'noSortable';
  }
  var retour = '<div class="expression ' + sortable + ' col-xs-12" style="margin-bottom: 0px; margin-right: 0px;margin-left: 0px;padding-right: 0px; padding-left: 0px">';
  retour += '<input class="expressionAttr" data-l1key="id" style="display : none;" value="' + init(_expression.id) + '"/>';
  retour += '<input class="expressionAttr" data-l1key="scenarioSubElement_id" style="display : none;" value="' + init(_expression.scenarioSubElement_id) + '"/>';
  retour += '<input class="expressionAttr" data-l1key="type" style="display : none;" value="' + init(_expression.type) + '"/>';
  switch (_expression.type) {
    case 'condition' :
    if (isset(_expression.expression)) {
      _expression.expression = _expression.expression.replace(/"/g, '&quot;');
    }
    retour += '<div class="input-group input-group-sm" style="width: 100%; padding-top: 5px;">';
    retour += '    <textarea class="expressionAttr form-control" data-l1key="expression" rows="1" style="resize:vertical;">' + init(_expression.expression) + '</textarea>';
    retour += '    <span class="input-group-btn">';
    retour += '       <button type="button" class="btn btn-default cursor bt_selectCmdExpression tooltips"  title="{{Rechercher une commande}}"><i class="fa fa-list-alt"></i></button>';
    retour += '       <button type="button" class="btn btn-default cursor bt_selectScenarioExpression tooltips"  title="{{Rechercher un scenario}}"><i class="fa fa-history"></i></button>';
    retour += '       <button type="button" class="btn btn-default cursor bt_selectEqLogicExpression tooltips"  title="{{Rechercher d\'un équipement}}"><i class="fa fa-cube"></i></button>';
    retour += '    </span>';
    retour += '</div>';

    break;
    case 'element' :
    retour += '<div class="col-xs-12" style="padding-right: 0px; padding-left: 0px;">';
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
    retour += '<div class="col-xs-1" style="margin-top: 4px">';
    if (!isset(_expression.options) || !isset(_expression.options.enable) || _expression.options.enable == 1) {
      retour += '<input type="checkbox" class="expressionAttr pull-right " data-l1key="options" data-l2key="enable" checked style="margin-top : 9px;" title="Décocher pour desactiver l\'action"/>';
    } else {
      retour += '<input type="checkbox" class="expressionAttr pull-right " data-l1key="options" data-l2key="enable" style="margin-top : 9px;" title="Décocher pour désactiver l\'action"/>';
    }
    retour += '<i class="fa fa-arrows-v pull-right cursor bt_sortable" style="margin-top : 9px; margin-right: 10px; "></i>';
    retour += '</div>';
    retour += '<div class="col-xs-4" style="margin-top: 4px"><div class="input-group input-group-sm">';
    retour += '<span class="input-group-btn">';
    retour += '<button class="btn btn-default bt_removeExpression" type="button" title="{{Supprimer l\'action}}"><i class="fa fa-minus-circle"></i></button>';
    retour += '</span>';
    retour += '<input class="expressionAttr form-control" data-l1key="expression" value="' + init(_expression.expression).replace(/"/g,'&quot;') + '" style="font-weight:bold;"/>';
    retour += '<span class="input-group-btn">';
    retour += '<button class="btn btn-default bt_selectOtherActionExpression" type="button" title="{{Sélectionner un mot-clé}}"><i class="fa fa-tasks"></i></button>';
    retour += '<button class="btn btn-default bt_selectCmdExpression" type="button" title="{{Sélectionner la commande}}"><i class="fa fa-list-alt"></i></button>';
    retour += '</span>';
    retour += '</div></div>';
    var actionOption_id = uniqId();
    retour += '<div class="col-xs-7 expressionOptions" style="margin-top: 4px" id="'+actionOption_id+'">';
    retour += '</div>';
    actionOptions.push({
      expression : init(_expression.expression, ''),
      options : _expression.options,
      id : actionOption_id
    });
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
    retour += '<textarea class="expressionAttr form-control" data-l1key="expression">' + init(_expression.expression) + '</textarea>';
    break;
  }
  retour += '</div>';
  return retour;
}

$('#div_pageContainer').on('click','.subElementAttr[data-l1key=options][data-l2key=allowRepeatCondition]',function(){
  if($(this).attr('value') == 0){
    $(this).attr('value',1);
    $(this).html('<span class="fa-stack"><i class="fa fa-refresh fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>');
  }else{
    $(this).attr('value',0);
    $(this).html('<span class="fa-stack"><i class="fa fa-refresh fa-stack-1x"></span>');
  }
});

function addSubElement(_subElement, _pColor) {
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
  var displayElse = 'table';
  if (_subElement.type == 'else') {
    if (!isset(_subElement.expressions) || _subElement.expressions.length==0) displayElse = 'none';
  }
  var retour = '<div class="subElement ' + noSortable + '" style="display:' + displayElse + '; width:100%;">';
  retour += '<input class="subElementAttr" data-l1key="id" style="display : none;" value="' + init(_subElement.id) + '"/>';
  retour += '<input class="subElementAttr" data-l1key="scenarioElement_id" style="display : none;" value="' + init(_subElement.scenarioElement_id) + '"/>';
  retour += '<input class="subElementAttr" data-l1key="type" style="display : none;" value="' + init(_subElement.type) + '"/>';
  switch (_subElement.type) {
    case 'if' :
    retour += '  <input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '  <div style="display:table-cell; width: 15px;vertical-align: top; padding-top: 5px;">';
    retour += '     <i class="fa fa-arrows-v pull-left cursor bt_sortable"></i>';
    if(!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1){
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="Décocher pour désactiver l\'élément"/>';
    }else{
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="Décocher pour désactiver l\'élément"/>';
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 50px;vertical-align: top;">';
    retour += '  <legend style="margin-bottom: 0px; color : white;border : none;">{{SI}}</legend>';
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 35px;vertical-align: top; padding-top: 5px;">';
    if(!isset(_subElement.options) || !isset(_subElement.options.allowRepeatCondition) || _subElement.options.allowRepeatCondition == 0){
      retour += '<a style="height : 30px;" class="btn btn-default btn-xs cursor subElementAttr tooltips pull-right" title="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="0"><span class="fa-stack"><i class="fa fa-refresh fa-stack-1x"></i></span></a>';
    }else{
      retour += '<a style="height : 30px;" class="btn btn-default btn-xs cursor subElementAttr tooltips pull-right" title="{{Autoriser ou non la répétition des actions si l\'évaluation de la condition est la même que la précédente}}" data-l1key="options" data-l2key="allowRepeatCondition" value="1"><span class="fa-stack"><i class="fa fa-refresh fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></a>';
    }
    retour += '  </div>';
    retour += '  <div class="expressions" style="display:table-cell;">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"><i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i></div>';

    break;
    case 'then' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '  <div style="display:table-cell; width: 125px;vertical-align: top; padding-left: 15px;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{ALORS}}</legend>'; 
    retour += '       <button class="btn btn-xs btn-default bt_addSinon" type="button" id="addSinon" data-toggle="dropdown" title="{{Afficher/masquer le bloc Sinon}}" aria-haspopup="true" aria-expanded="true">';
    retour += '         <i class="fa fa-chevron-right"></i>';
    retour += '       </button>';
    retour += '     <div class="dropdown" style="display : inline-block;">';
    retour += '       <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
    retour += '         <i class="fa fa-plus-circle"></i> {{Ajouter}}';
    retour += '         <span class="caret"></span>';
    retour += '       </button>';
    retour += '       <ul class="dropdown-menu">';
    retour += '         <li><a class="bt_addScenarioElement fromSubElement tootlips" href="#" title="{{Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)}}">{{Bloc}}</a></li>';
    retour += '         <li><a class="bt_addAction" href="#">{{Action}}</a></li>';
    retour += '       </ul>';
    retour += '     </div><p> </p>';
    retour += '   </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px; background-color: ' + listColor[_pColor] + ';">';
    retour += '     <div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"> </div>';

    break;
    case 'else' :
    retour += '<input class="subElementAttr subElementElse" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '  <div style="display:table-cell; width: 125px; vertical-align: top; padding-left: 15px;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{SINON}}</legend>'; 
    retour += '     <div class="dropdown">';
    retour += '       <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
    retour += '         <i class="fa fa-plus-circle"></i> Ajouter';
    retour += '         <span class="caret"></span>';
    retour += '       </button>';
    retour += '       <ul class="dropdown-menu">';
    retour += '         <li><a class="bt_addScenarioElement fromSubElement tootlips" href="#" title="{{Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)}}">{{Bloc}}</a></li>';
    retour += '         <li><a class="bt_addAction" href="#">{{Action}}</a></li>';
    retour += '       </ul>';
    retour += '     </div><p> </p>';
    retour += '   </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px; background-color: ' + listColor[_pColor] + '; border-top :1px solid ' + listColorStrong[_pColor] + '">';
    retour += '     <div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"> </div>';

    break;
    case 'for' :
    retour += '  <input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '  <div style="display:table-cell; width: 15px;vertical-align: top; padding-top: 5px;">';
    retour += '     <i class="fa fa-arrows-v pull-left cursor bt_sortable"></i>';
    if(!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1){
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}"/>';
    }else{
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}"/>';
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 85px;vertical-align: top;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{DE 1 A}}</legend>';
    retour += '  </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px;">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"><i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i></div>';
    break;
    case 'in' :
    retour += '  <input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '  <div style="display:table-cell; width: 15px;vertical-align: top; padding-top: 5px;">';
    retour += '     <i class="fa fa-arrows-v pull-left cursor bt_sortable"></i>';
    if(!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1){
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}"/>';
    }else{
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}"/>';
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 85px;vertical-align: top;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{DANS (min)}}</legend>';
    retour += '  </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px;">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"><i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i></div>';

    break;
    case 'at' :
    retour += '  <input class="subElementAttr" data-l1key="subtype" style="display : none;" value="condition"/>';
    retour += '  <div style="display:table-cell; width: 15px;vertical-align: top; padding-top: 5px;">';
    retour += '     <i class="fa fa-arrows-v pull-left cursor bt_sortable"></i>';
    if(!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1){
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}"/>';
    }else{
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}"/>';
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 85px;vertical-align: top;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{A (Hmm)}}</legend>';
    retour += '  </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px;">';
    var expression = {type: 'condition'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"><i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i></div>';
    break;
    case 'do' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '  <div style="display:table-cell; width: 100px; vertical-align: top; padding-left: 15px;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{FAIRE}}</legend>'; 
    retour += '     <div class="dropdown">';
    retour += '       <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
    retour += '         <i class="fa fa-plus-circle"></i> Ajouter';
    retour += '         <span class="caret"></span>';
    retour += '       </button>';
    retour += '       <ul class="dropdown-menu">';
    retour += '         <li><a class="bt_addScenarioElement fromSubElement tootlips" href="#" title="{{Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)}}">{{Bloc}}</a></li>';
    retour += '         <li><a class="bt_addAction" href="#">{{Action}}</a></li>';
    retour += '       </ul>';
    retour += '     </div><p> </p>';
    retour += '   </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px; background-color: ' + listColor[_pColor] + ';">';
    retour += '     <div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"> </div>';

    break;
    case 'code' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '  <div style="display:table-cell; width: 15px;vertical-align: top; padding-top: 5px;">';
    retour += '     <i class="fa fa-arrows-v pull-left cursor bt_sortable"></i>';
    if(!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1){
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}"/>';
    }else{
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}"/>';
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 85px;vertical-align: top;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{CODE}}</legend>'; 
    retour += '  </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px; background-color: ' + listColor[_pColor] + ';">';
    retour += '     <div class="sortable empty" style="height : 30px;"></div>';
    var expression = {type: 'code'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; "><i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i></div>';
    break;
    case 'comment' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="comment"/>';
    retour += '  <div style="display:table-cell; width: 15px;vertical-align: top; padding-top: 5px;">';
    retour += '     <i class="fa fa-arrows-v pull-left cursor bt_sortable"></i>';
    retour += '  </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px; background-color: ' + listColor[_pColor] + ';">';
    retour += '     <div class="sortable empty" style="height : 30px;"></div>';
    var expression = {type: 'comment'};
    if (isset(_subElement.expressions) && isset(_subElement.expressions[0])) {
      expression = _subElement.expressions[0];
    }
    retour += addExpression(expression);
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"><i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i></div>';

    break;
    case 'action' :
    retour += '<input class="subElementAttr" data-l1key="subtype" style="display : none;" value="action"/>';
    retour += '  <div style="display:table-cell; width: 15px;vertical-align: top; padding-top: 5px;">';
    retour += '     <i class="fa fa-arrows-v pull-left cursor bt_sortable"></i>';
    if(!isset(_subElement.options) || !isset(_subElement.options.enable) || _subElement.options.enable == 1){
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" checked title="{{Décocher pour désactiver l\'élément}}"/>';
    }else{
      retour += '<input type="checkbox" class="subElementAttr" data-l1key="options" data-l2key="enable" title="{{Décocher pour désactiver l\'élément}}"/>';
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 85px;vertical-align: top;">';
    retour += '     <legend style="margin-bottom: 0px; color : white;border : none;">{{ACTION}}</legend><br/>';
    retour += '     <div class="dropdown">';
    retour += '       <button class="btn btn-xs btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
    retour += '         <i class="fa fa-plus-circle"></i> Ajouter';
    retour += '         <span class="caret"></span>';
    retour += '       </button>';
    retour += '       <ul class="dropdown-menu">';
    retour += '         <li><a class="bt_addScenarioElement fromSubElement tootlips" href="#" title="{{Permet d\'ajouter des éléments fonctionnels essentiels pour créer vos scénarios (Ex: SI/ALORS….)}}">{{Bloc}}</a></li>';
    retour += '         <li><a class="bt_addAction" href="#">{{Action}}</a></li>';
    retour += '       </ul>';
    retour += '     </div><p> </p>';
    retour += '   </div>';
    retour += '  <div class="expressions" style="display:table-cell; padding-bottom: 10px; background-color: ' + listColor[_pColor] + ';">';
    retour += '     <div class="sortable empty" style="height : 30px;"></div>';
    if (isset(_subElement.expressions)) {
      for (var k in _subElement.expressions) {
        retour += addExpression(_subElement.expressions[k]);
      }
    }
    retour += '  </div>';
    retour += '  <div style="display:table-cell; width: 15px; vertical-align: top;"><i class="fa fa-minus-circle pull-right cursor bt_removeElement" style="position : relative;z-index : 2;"></i></div>';
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

  pColor++;
  if (pColor > 4) {
    pColor = 0;
  }
  var color = pColor;

  var div = '<div class="element" style="color : white;padding-right : 7px;padding-left : 7px;padding-bottom : 0px;padding-top : 2px;margin-bottom : 0px;background-color : ' + listColorStrong[color] + '; border :1px solid ' + listColorStrong[color] + '">';
  div += '<input class="elementAttr" data-l1key="id" style="display : none;" value="' + init(_element.id) + '"/>';
  div += '<input class="elementAttr" data-l1key="type" style="display : none;" value="' + init(_element.type) + '"/>';
  switch (_element.type) {
    case 'if' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j], color);
      }
    } else {
      div += addSubElement({type: 'if'}, color);
      div += addSubElement({type: 'then'}, color);
      div += addSubElement({type: 'else'}, color);
    }
    break;
    case 'for' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j], color);
      }
    } else {
      div += addSubElement({type: 'for'}, color);
      div += addSubElement({type: 'do'}, color);
    }
    break;
    case 'in' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j], color);
      }
    } else {
      div += addSubElement({type: 'in'}, color);
      div += addSubElement({type: 'do'}, color);
    }
    break;
    case 'at' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j], color);
      }
    } else {
      div += addSubElement({type: 'at'}, color);
      div += addSubElement({type: 'do'}, color);
    }
    break;
    case 'code' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j], color);
      }
    } else {
      div += addSubElement({type: 'code'}, color);
    }
    break;
    case 'comment' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j], color);
      }
    } else {
      div += addSubElement({type: 'comment'}, color);
    }
    break;
    case 'action' :
    if (isset(_element.subElements) && isset(_element.subElements)) {
      for (var j in _element.subElements) {
        div += addSubElement(_element.subElements[j], color);
      }
    } else {
      div += addSubElement({type: 'action'}, color);
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
