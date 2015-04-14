
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

setTimeout(function(){
  $('.scenarioListContainer').packery();
},100);


if(!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1){
    $('#div_listScenario').hide();
    $('#bt_displayScenarioList').on('mouseenter',function(){
        $('#div_listScenario').show();
        $('.scenarioListContainer').packery();
    });

    $('#div_listScenario').on('mouseleave',function(){
        $('#div_listScenario').hide();
        $('.scenarioListContainer').packery();
    });
}


$("#div_listScenario").trigger('resize');

$('.scenarioListContainer').packery();

$('#bt_scenarioThumbnailDisplay').on('click', function () {
    $('#div_editScenario').hide();
    $('#scenarioThumbnailDisplay').show();
    $('.li_scenario').removeClass('active');
});

$('.scenarioDisplayCard').on('click', function () {
    if ($(this).attr('data-type') == 'simple') {
        $('#div_tree').jstree('deselect_all');
        $('#div_tree').jstree('select_node', 'scenario' + $(this).attr('data-scenario_id'));
    } else {
        window.location.href = "index.php?v=d&p=scenario&id=" + $(this).attr('data-scenario_id');
    }
});

$('#div_tree').on('select_node.jstree', function (node, selected) {
    if (selected.node.a_attr.class == 'li_scenario') {
        $.hideAlert();
        $(".li_scenario").removeClass('active');
        $(this).addClass('active');
        $('#scenarioThumbnailDisplay').hide();

        if (selected.node.a_attr['data-type'] == 'simple') {
            printScenario(selected.node.a_attr['data-scenario_id']);
        } else {
            window.location.href = "index.php?v=d&p=scenario&id=" + selected.node.a_attr['data-scenario_id'];
        }
    }
});

$("#div_tree").jstree({
    "plugins": ["search"]
});
$('#in_treeSearch').keyup(function () {
    $('#div_tree').jstree(true).search($('#in_treeSearch').val());
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
                    window.location.replace('index.php?v=d&p=scenarioAssist&id=' + data.id);
                }
            });
        }
    });
});

$('#md_addScenario').modal('hide');

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

$('#bt_displayScenarioVariable').on('click', function () {
    $('#md_modal').closest('.ui-dialog').css('z-index', '1030');
    $('#md_modal').dialog({title: "{{Variables des scénarios}}"});
    $("#md_modal").load('index.php?v=d&modal=dataStore.management&type=scenario').dialog('open');
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
                    window.location.replace('index.php?v=d&p=scenarioAssist');
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

/**************** Ajout action **********************/
$('#bt_addAction').on('click', function () {
    addAction({});
});

$('body').delegate('.bt_removeExpression', 'click', function (event) {
    $(this).closest('.expression').remove();
});

$('.scenarioAttr[data-l1key=mode]').on('change', function () {
    $('.mode').hide();
    $('.mode.' + $(this).value()).show();
});

$('body').delegate('.bt_selectCmdExpression', 'click', function (event) {
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
            expression.find('.expressionAttr[data-l1key=expression]').atCaret('insert', result.human);
        }
    });
});

$('#bt_selectTrigger').on('click', function (event) {
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
        el.closest('.trigger').find('.scenarioAttr[data-l1key=trigger]').value(result.human);
        addCondition({expression: result.human});
    });
});

$('#bt_selectCondition').on('click', function (event) {
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
        addCondition({expression: result.human});
    });
});

$('#cb_conditionStart').on('change', function () {
    if ($(this).value() == 1) {
        $('.condition').show();
    } else {
        $('.condition').hide();
    }
});

$('#sel_scheduleMode').on('change', function () {
    $('#div_scheduleConfig').empty();
    if ($(this).value() == 'once') {
        var html = '<label class="col-xs-3 control-label" >{{En date du}}</label>';
        html += '<div class="col-xs-9">';
        html += '<input class="form-control" id="in_dateScenarioTrigger">';
        html += '</div>';
        html += '<span class="scenarioAttr" data-l1key="schedule" id="span_cronResult" style="display: none;"></span>';
        $('#div_scheduleConfig').append(html);
        $('#in_dateScenarioTrigger').datetimepicker({lang: 'fr',
            i18n: {
                fr: {
                    months: [
                    'Janvier', 'Février', 'Mars', 'Avril',
                    'Mai', 'Juin', 'Juillet', 'Aout',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre',
                    ],
                    dayOfWeek: [
                    "Di", "Lu", "Ma", "Me",
                    "Je", "Ve", "Sa",
                    ]
                }
            },
            format: 'Y-m-d H:i:00',
            step: 15
        });
        $('#in_dateScenarioTrigger').on('change', function () {
            if ($(this).value() != '') {
                var a=$(this).value().split(" ");
                var d=a[0].split("-");
                var t=a[1].split(":");
                var date = new Date(d[0],(d[1]-1),d[2],t[0],t[1],t[2]);
                var minute = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes();
                var hour = (date.getHours() < 10 ? '0' : '') + date.getHours();
                var strdate = (date.getDate() < 10 ? '0' : '') + date.getDate();
                var month = ((date.getMonth() + 1) < 10 ? '0' : '') + (date.getMonth() + 1);
                var cron = minute + ' ' + hour + ' ' + strdate + ' ' + month + ' ' + date.getDay() + ' ' + date.getFullYear();
                $('#span_cronResult').value(cron);
            }
        });
    } else {
        var html = '<div class="col-xs-3"></div>';
        html += '<div id="div_cronGenerator"></div>';
        html += '<span class="scenarioAttr" data-l1key="schedule" id="span_cronResult" style="display: none;">* * * * *</span>';
        $('#div_scheduleConfig').append(html);
        $('#div_cronGenerator').empty().cron({
            initial: '* * * * *',
            onChange: function () {
                $('#span_cronResult').text($(this).cron("value"));
            }
        });
    }
});

/**************** Initialisation **********************/

if (is_numeric(getUrlVars('id'))) {
    $('#div_tree').jstree('deselect_all');
    $('#div_tree').jstree('select_node', 'scenario' + getUrlVars('id'));
}

function addAction(_action) {
    var retour = '<div class="container-fluid expression" style="margin-top : 4px;"><div class="col-xs-3">';
    retour += '<i class="fa fa-minus-circle pull-left cursor bt_removeExpression" style="margin-top : 9px;"></i>';
    retour += ' <a class="btn btn-default btn-sm cursor bt_selectCmdExpression cursor pull-right" ><i class="fa fa-list-alt"></i></a>';
    retour += '</div>';
    retour += '<div class="col-xs-8">';
    retour += '<span class="expressionAttr" data-l1key="type" style="display : none;" >action</span>';
    retour += '<input class="expressionAttr form-control input-sm" data-l1key="expression" value="' + init(_action.expression) + '" disabled />';
    retour += '</div>';
    retour += '<div class="col-xs-2"></div>';
    retour += '<div class="col-xs-10 expressionOptions" style="margin-top : 4px;">';
    retour += jeedom.cmd.displayActionOption(init(_action.expression), init(_action.options));
    retour += '</div></div>';
    $('#div_actionList').append(retour);
}

function addCondition(_condition) {
    $('#in_cmdCondition').value(_condition.expression);
    jeedom.cmd.byHumanName({
        humanName: _condition.expression,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.conditionOptions').removeClass('active')
            $('.conditionOptions').hide();
            $('.conditionOptions[data-subtype=' + data.subType + ']').show().addClass('active');
            if (isset(_condition.operator)) {
                $('.conditionOptions[data-subtype=' + data.subType + ']').find('.conditionAttr[data-l1key=operator]').value(_condition.operator);
            }
            if (isset(_condition.operande)) {
                $('.conditionOptions[data-subtype=' + data.subType + ']').find('.conditionAttr[data-l1key=operande]').value(_condition.operande);
            }
        }
    });
}

function printScenario(_id) {
    $('#bt_switchToExpertMode').attr('href', 'index.php?v=d&p=scenario&id=' + _id)
    $.showLoading();
    jeedom.scenario.get({
        id: _id,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            if (data.type != 'simple') {
                window.location.href = "index.php?v=d&p=scenario&id=" + _id;
            }
            $('.scenarioAttr').value('');
            $('body').setValues(data, '.scenarioAttr');
            data.lastLaunch = (data.lastLaunch == null) ? '{{Jamais}}' : data.lastLaunch;
            $('#span_lastLaunch').text(data.lastLaunch);
            $('.provokeMode').empty();
            $('.scheduleMode').empty();
            $('.scenarioAttr[data-l1key=mode]').trigger('change');
            $('#div_cronGenerator').empty();
            if (data.schedule == '' || data.schedule == undefined) {
                $('#sel_scheduleMode').value('once');
            } else {
                if (data.schedule.indexOf('*') != -1) {
                    $('#sel_scheduleMode').value('repete');
                    $('#div_cronGenerator').empty().cron({
                        initial: data.schedule,
                        onChange: function () {
                            $('#span_cronResult').text($(this).cron("value"));
                        }
                    });
                } else {
                    $('#sel_scheduleMode').value('once');
                    var cron = data.schedule.split(' ');
                    $('#in_dateScenarioTrigger').value(cron[5] + '-' + cron[3] + '-' + cron[2] + ' ' + cron[1] + ':' + cron[0] + ':00');
                }
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

            $('#div_actionList').empty();

            scenarionDescription = {
                scenario_element_id: '',
                scenario_subelement_if_id: '',
                scenario_subelement_then_id: '',
                scenario_subelement_else_id: '',
            };

            var element = data.elements[0];
            if (element != undefined) {
                scenarionDescription.scenario_element_id = element.id;
                scenarionDescription.scenario_subelement_if_id = element.subElements[0].id;
                scenarionDescription.scenario_subelement_then_id = element.subElements[1].id;
                scenarionDescription.scenario_subelement_else_id = element.subElements[2].id;
                if (element.subElements[0].expressions[0].expression != '1=1') {
                    $('#cb_conditionStart').value(1);
                } else {
                    $('#cb_conditionStart').value(0);
                }
                var conditionStr = element.subElements[0].expressions[0].expression
                var firstArray = conditionStr.split(']#');
                if (firstArray.length == 2) {
                    var conditionArray = firstArray[1].split(' ');
                    if (conditionArray.length >= 3) {

                        if(conditionArray[0] == ''){
                            conditionArray.shift();
                        }
                        console.log(conditionArray);
                        var condition = {expression: firstArray[0]+']#', operator: conditionArray[0]};
                        conditionArray.shift();
                        condition.operande = conditionArray.join(' ');
                        addCondition(condition);
                    }
                }
                for (var i in element.subElements[1].expressions) {
                    addAction(element.subElements[1].expressions[i]);
                }

            } else {

            }

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
    var scenario = $('#div_editScenario').getValues('.scenarioAttr')[0];
    if ($('#cb_conditionStart').value() == 1) {
        var condition = $('#in_cmdCondition').value();
        var conditionOption = $('.conditionOptions.active');
        condition += ' ' + conditionOption.find('.conditionAttr[data-l1key=operator]').value();
        condition += ' ' + conditionOption.find('.conditionAttr[data-l1key=operande]').value();
    } else {
        var condition = '1=1';
    }
    var actions = $('#div_actionList .expression').getValues('.expressionAttr')
    var elements = [{
        id: scenarionDescription.scenario_element_id,
        type: 'if',
        subElements: [
        {
            id: scenarionDescription.scenario_subelement_if_id,
            scenarioElement_id: scenarionDescription.scenario_element_id,
            subtype: "condition",
            type: "if",
            expressions: [{expression: condition, type: 'condition'}]
        },
        {
            id: scenarionDescription.scenario_subelement_then_id,
            scenarioElement_id: scenarionDescription.scenario_element_id,
            subtype: "action",
            type: "then",
            expressions: actions
        },
        {
            id: scenarionDescription.scenario_subelement_else_id,
            scenarioElement_id: scenarionDescription.scenario_element_id,
            subtype: "action",
            type: "else",
            expressions: []
        }
        ]
    }];
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