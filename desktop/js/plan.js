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
var noBootstrapTooltips = true;
var grid = false;
var deviceInfo = getDeviceType();

$("#md_addViewData").dialog({
    autoOpen: false,
    modal: true,
    height: (jQuery(window).height() - 150),
    width: (jQuery(window).width() - 450)
});

/*****************************PLAN HEADER***********************************/
$('#bt_addPlanHeader').on('click', function () {
    bootbox.prompt("Nom du design ?", function (result) {
        if (result !== null) {
            jeedom.plan.saveHeader({
                planHeader: {name: result},
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    window.location.replace('index.php?v=d&p=plan&plan_id=' + data.id);
                }
            });
        }
    });
});

$('#bt_duplicatePlanHeader').on('click', function () {
    bootbox.prompt("{{Nom la copie du design ?}}", function (result) {
        if (result !== null) {
            jeedom.plan.copyHeader({
                name: result,
                id: $('#sel_planHeader').value(),
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    window.location.replace('index.php?v=d&p=plan&plan_id=' + data.id);
                },
            });
        }
    });
});

$('#sel_planHeader').on('change', function () {
    window.location.replace('index.php?v=d&p=plan&plan_id=' + $(this).value());
});

/*****************************PLAN***********************************/
$('#bt_addEqLogic').on('click', function () {
    jeedom.eqLogic.getSelectModal({}, function (data) {
        addEqLogic(data.id);
    });
});

$('#bt_addScenario').on('click', function () {
    jeedom.scenario.getSelectModal({}, function (data) {
        addScenario(data.id);
    });
});

$('#bt_addLink').on('click', function () {
    $('#md_selectLink').modal('show');
});

$('#bt_addGraph').on('click', function () {
    addGraph({});
    savePlan();
});

$('#bt_addTexte').on('click', function () {
    addText({display: {text: 'Texte à insérer ici'}});
    savePlan();
});

displayPlan();

$('#bt_savePlan').on('click', function () {
    savePlan();
});

$('#bt_configurePlanHeader').on('click', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        $('#md_modal').dialog({title: "{{Configuration du design}}"});
        $('#md_modal').load('index.php?v=d&modal=planHeader.configure&planHeader_id=' + planHeader_id).dialog('open');
    }
});

$('#div_displayObject').delegate('.eqLogic-widget', 'dblclick', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        $('#md_modal').dialog({title: "{{Configuration du widget}}"});
        $('#md_modal').load('index.php?v=d&modal=plan.configure&link_type=eqLogic&link_id=' + $(this).attr('data-eqLogic_id') + '&planHeader_id=' + planHeader_id).dialog('open');
    }
});

$('#div_displayObject').delegate('.scenario-widget', 'dblclick', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        $('#md_modal').dialog({title: "{{Configuration du scénario}}"});
        $('#md_modal').load('index.php?v=d&modal=plan.configure&link_type=scenario&link_id=' + $(this).attr('data-scenario_id') + '&planHeader_id=' + planHeader_id).dialog('open');
    }
});

$('#div_displayObject').delegate('.plan-link-widget', 'dblclick', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        $('#md_modal').dialog({title: "{{Configuration du lien}}"});
        $('#md_modal').load('index.php?v=d&modal=plan.configure&link_type=plan&link_id=' + $(this).attr('data-link_id') + '&planHeader_id=' + planHeader_id).dialog('open');
    }
});

$('#div_displayObject').delegate('.text-widget', 'dblclick', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        $('#md_modal').dialog({title: "{{Configuration du texte}}"});
        $('#md_modal').load('index.php?v=d&modal=plan.configure&link_type=text&link_id=' + $(this).attr('data-text_id') + '&planHeader_id=' + planHeader_id).dialog('open');
    }
});

$('#div_displayObject').delegate('.view-link-widget', 'dblclick', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        $('#md_modal').dialog({title: "{{Configuration du lien}}"});
        $('#md_modal').load('index.php?v=d&modal=plan.configure&link_type=view&link_id=' + $(this).attr('data-link_id') + '&planHeader_id=' + planHeader_id).dialog('open');
    }
});

$('#div_displayObject').delegate('.graph-widget', 'dblclick', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        $('#md_modal').dialog({title: "{{Configuration du graph}}"});
        $('#md_modal').load('index.php?v=d&modal=plan.configure&link_type=graph&link_id=' + $(this).attr('data-graph_id') + '&planHeader_id=' + planHeader_id).dialog('open');
    }
});

$('.planHeaderAttr').on('change', function () {
    var planHeader = $('#div_planHeader').getValues('.planHeaderAttr')[0];
    planHeader.id = planHeader_id;
    jeedom.plan.saveHeader({
        planHeader: planHeader,
        global: false,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {

        }
    });
});

function setColorSelect(_select) {
    _select.css('background-color', _select.find('option:selected').val());
}

$('.graphDataOption[data-l1key=configuration][data-l2key=graphColor]').on('change', function () {
    setColorSelect($(this).closest('select'));
});

$('#div_displayObject').delegate('.configureGraph', 'click', function () {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        var el = $(this).closest('.graph-widget');
        $("#md_addViewData").load('index.php?v=d&modal=cmd.graph.select', function () {
            $('#table_addViewData tbody tr .enable').prop('checked', false);
            var options = json_decode(el.find('.graphOptions').value());
            for (var i in options) {
                var tr = $('#table_addViewData tbody tr[data-link_id=' + options[i].link_id + ']');
                tr.find('.enable').value(1);
                tr.setValues(options[i], '.graphDataOption');
                setColorSelect(tr.find('.graphDataOption[data-l1key=configuration][data-l2key=graphColor]'));
            }

            $("#md_addViewData").dialog('option', 'buttons', {
                "Annuler": function () {
                    $(this).dialog("close");
                },
                "Valider": function () {
                    var tr = $('#table_addViewData tbody tr:first');
                    var options = [];
                    while (tr.attr('data-link_id') != undefined) {
                        if (tr.find('.enable').is(':checked')) {
                            var graphData = tr.getValues('.graphDataOption')[0];
                            graphData.link_id = tr.attr('data-link_id');
                            options.push(graphData);
                        }
                        tr = tr.next();
                    }
                    el.find('.graphOptions').empty().append(json_encode(options));
                    savePlan(true);
                    $(this).dialog('close');
                }
            });
            $('#md_addViewData').dialog('open');
        });
    }
});

$('#bt_editPlan').on('click', function () {
    if ($(this).attr('data-mode') == '0') {
        initDraggable(1);
        $('.editMode').show();
        $(this).html('<i class="fa fa-pencil"></i> {{Quitter le mode édition}}');
        $(this).attr('data-mode', '1');
        $('#div_displayObject').css('background-color', '#bdc3c7');
    } else {
        initDraggable(0);
        $('.editMode').hide();
        $(this).html('<i class="fa fa-pencil"></i> {{Mode édition}}');
        $(this).attr('data-mode', '0');
        $('#div_displayObject').css('background-color', 'transparent');
    }
});

$('#bt_switchFullScreen').on('click', function () {
    if ($('#bt_editPlan').attr('data-mode') == '0') {
        fullScreen('phone');
    }
});

function makeGrid(_x, _y) {
    if (_x === false) {
        $('#div_displayObject').css({
            'background-size': _x + 'px ' + _y + 'px',
            'background-image': 'none'
        });
    } else {
        $('#div_displayObject').css({
            'background-size': _x + 'px ' + _y + 'px',
            'background-position': '-4px -8px',
            'background-image': 'repeating-linear-gradient(0deg, silver, silver 1px, transparent 1px, transparent ' + _y + 'px),repeating-linear-gradient(-90deg, silver, silver 1px, transparent 1px, transparent ' + _x + 'px)'
        });
    }
}

function fullScreen(_version) {
    $('header').hide();
    $(function () {
        $('footer').hide();
    });
    $('#div_planHeader').hide();
    $('#div_mainContainer').css('margin-top', '-60px');
    $('#div_mainContainer').css('margin-left', '-15px');
    $('#div_mainContainer').append('<a class="btn btn-default" style="position : absolute; top : 10px; right : 10px;" id="bt_returnFullScreen"><i class="fa fa-level-up fa-rotate-270"></i></a>');
    $('#bt_returnFullScreen').on('click', function () {
        if (_version == 'phone' || _version == 'tablet') {
            window.location.href = "index.php?v=m&page=home";
        } else {
            $('header').show();
            $('footer').show();
            $('#div_planHeader').show();
            $('#div_mainContainer').css('margin-top', '0px');
            $('#div_mainContainer').css('margin-left', '0px');
            $('#bt_returnFullScreen').remove();
        }
    });
}

function initDraggable(_state) {
    var dragOption = {};
    if (grid != false) {
        dragOption.grid = grid;
    }
    $('.plan-link-widget,.view-link-widget,.graph-widget,.eqLogic-widget,.scenario-widget,.text-widget').draggable({
        drag: function (evt, ui) {
            if ((ui.position.left + $(this).width()) >= $('#div_displayObject').width()) {
                ui.position.left = $('#div_displayObject').width() - $(this).width();
            }
            if ((ui.position.top + $(this).height()) >= $('#div_displayObject').height()) {
                ui.position.top = $('#div_displayObject').height() - $(this).height();
            }
            if (ui.position.top < 0) {
                ui.position.top = 0;
            }
            if (ui.position.left < 0) {
                ui.position.left = 0;
            }
            if (grid != false && grid[0] != false) {
                ui.position.top = Math.round(ui.position.top / grid[1]) * grid[1];
                ui.position.left = Math.round(ui.position.left / grid[0]) * grid[0];
            }

        },
    });
    $('.graph-widget').resizable();
    $('#div_displayObject a').each(function () {
        if ($(this).attr('href') != '#') {
            $(this).attr('data-href', $(this).attr('href'));
            $(this).attr('href', '#');
        }
    });
    if (_state != 1 && _state != '1') {
        $('.plan-link-widget,.view-link-widget,.graph-widget,.eqLogic-widget,.scenario-widget,.text-widget').draggable("destroy");
        $('.graph-widget').resizable("destroy");
        $('#div_displayObject a').each(function () {
            $(this).attr('href', $(this).attr('data-href'));
        });
    }
}

function displayPlan() {
    $.showLoading();
    jeedom.plan.getHeader({
        id: planHeader_id,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            var sizeSet = false;
            if (deviceInfo.type == 'desktop') {
                if (data.configuration != null && init(data.configuration.desktopSizeX) != '' && init(data.configuration.desktopSizeY) != '') {
                    $('#div_displayObject').height(data.configuration.desktopSizeY);
                    $('#div_displayObject').width(data.configuration.desktopSizeX);
                    $('#div_displayObject img').height(data.configuration.desktopSizeY);
                    $('#div_displayObject img').width(data.configuration.desktopSizeX);
                    sizeSet = true;
                }
            } else if (deviceInfo.type == 'tablet') {
                fullScreen('tablet');
                if (data.configuration != null && init(data.configuration.tabletSizeX) != '' && init(data.configuration.tabletSizeY) != '') {
                    $('#div_displayObject').height(data.configuration.tabletSizeY);
                    $('#div_displayObject').width(data.configuration.tabletSizeX);
                    $('#div_displayObject img').css('height', data.configuration.tabletSizeY + 'px');
                    $('#div_displayObject img').css('width', data.configuration.tabletSizeX + 'px');
                    sizeSet = true;
                }
            } else if (deviceInfo.type == 'phone') {
                fullScreen('phone');
                if (data.configuration != null && init(data.configuration.mobileSizeX) != '' && init(data.configuration.mobileSizeY) != '') {
                    $('#div_displayObject').height(data.configuration.mobileSizeY);
                    $('#div_displayObject').width(data.configuration.mobileSizeX);
                    $('#div_displayObject img').css('height', data.configuration.mobileSizeY + 'px');
                    $('#div_displayObject img').css('width', data.configuration.mobileSizeX + 'px');
                    sizeSet = true;
                }
            }
            if (!sizeSet) {
                $('#div_displayObject').width($('#div_displayObject img').attr('data-sixe_x'));
                $('#div_displayObject').height($('#div_displayObject img').attr('data-sixe_y'));
                $('#div_displayObject img').css('height', $('#div_displayObject img').attr('data-sixe_y') + 'px');
                $('#div_displayObject img').css('width', $('#div_displayObject img').attr('data-sixe_x') + 'px');
            }
            $('.eqLogic-widget,.scenario-widget,.plan-link-widget,.view-link-widget,.graph-widget,.text-widget').remove();

            grid = false;
            if (data.configuration != null && isset(data.configuration.gridX) && isset(data.configuration.gridY) && !isNaN(data.configuration.gridX) && !isNaN(data.configuration.gridY) && data.configuration.gridX > 0 && data.configuration.gridY > 0) {
                grid = [$('#div_displayObject').width() / data.configuration.gridX, $('#div_displayObject').height() / data.configuration.gridY];
                eqLogic_width_step = grid[0] - 5;
                eqLogic_height_step = grid[1] - 5;
            }
            if (planHeader_id != -1) {
                jeedom.plan.byPlanHeader({
                    id: planHeader_id,
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        for (var i in data) {
                            if (data[i].plan.link_type == 'graph') {
                                addGraph(data[i].plan);
                            } else {
                                displayObject(data[i].plan.link_type, data[i].plan.link_id, data[i].html, data[i].plan);
                            }
                        }
                    },
                });
            }
        },
    });
}

function savePlan(_refreshDisplay) {
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        var parent = {
            height: $('#div_displayObject').height(),
            width: $('#div_displayObject').width(),
        };
        var plans = [];
        $('.eqLogic-widget').each(function () {
            var plan = {};
            plan.position = {};
            plan.link_type = 'eqLogic';
            plan.link_id = $(this).attr('data-eqLogic_id');
            plan.planHeader_id = planHeader_id;
            var position = $(this).position();
            plan.position.top = (((position.top)) / parent.height) * 100;
            plan.position.left = (((position.left)) / parent.width) * 100;
            plans.push(plan);
        });
        $('.scenario-widget').each(function () {
            var plan = {};
            plan.position = {};
            plan.link_type = 'scenario';
            plan.link_id = $(this).attr('data-scenario_id');
            plan.planHeader_id = planHeader_id;
            var position = $(this).position();
            plan.position.top = (((position.top)) / parent.height) * 100;
            plan.position.left = (((position.left)) / parent.width) * 100;
            plans.push(plan);
        });
        $('.plan-link-widget').each(function () {
            var plan = {};
            plan.position = {};
            plan.link_type = 'plan';
            plan.link_id = $(this).attr('data-link_id');
            plan.planHeader_id = planHeader_id;
            var position = $(this).position();
            plan.position.top = ((position.top) / parent.height) * 100;
            plan.position.left = ((position.left) / parent.width) * 100;
            plans.push(plan);
        });
        $('.view-link-widget').each(function () {
            var plan = {};
            plan.position = {};
            plan.link_type = 'view';
            plan.link_id = $(this).attr('data-link_id');
            plan.planHeader_id = planHeader_id;
            var position = $(this).position();
            plan.position.top = ((position.top) / parent.height) * 100;
            plan.position.left = ((position.left) / parent.width) * 100;
            plans.push(plan);
        });
        $('.graph-widget').each(function () {
            var plan = {};
            plan.position = {};
            plan.display = {};
            plan.link_type = 'graph';
            plan.link_id = $(this).attr('data-graph_id');
            plan.planHeader_id = planHeader_id;
            plan.display.height = ($(this).height() / parent.height) * 100;
            plan.display.width = ($(this).width() / parent.width) * 100;
            plan.display.graph = json_decode($(this).find('.graphOptions').value());
            var position = $(this).position();
            plan.position.top = ((position.top) / parent.height) * 100;
            plan.position.left = ((position.left) / parent.width) * 100;
            plans.push(plan);
        });
        $('.text-widget').each(function () {
            var plan = {};
            plan.position = {};
            plan.display = {};
            plan.link_type = 'text';
            plan.link_id = $(this).attr('data-text_id');
            plan.planHeader_id = planHeader_id;
            plan.display.height = ($(this).height() / parent.height) * 100;
            plan.display.width = ($(this).width() / parent.width) * 100;
            var position = $(this).position();
            plan.position.top = ((position.top) / parent.height) * 100;
            plan.position.left = ((position.left) / parent.width) * 100;
            plans.push(plan);
        });
        jeedom.plan.save({
            plans: plans,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                if (init(_refreshDisplay, false)) {
                    displayPlan();
                }
            },
        });
    }
}

function displayObject(_type, _id, _html, _plan) {
    for (var i in jeedom.history.chart) {
        delete jeedom.history.chart[i];
    }
    _plan = init(_plan, {});
    _plan.position = init(_plan.position, {});
    _plan.css = init(_plan.css, {});
    var defaultZoom = 1;
    if (_type == 'eqLogic') {
        if (grid === false) {
            defaultZoom = 0.65;
        }
        $('.eqLogic-widget[data-eqLogic_id=' + _id + ']').remove();
    }
    if (_type == 'scenario') {
        $('.scenario-widget[data-scenario_id=' + _id + ']').remove();
    }
    if (_type == 'view') {
        $('.view-link-widget[data-link_id=' + _id + ']').remove();
    }
    if (_type == 'plan') {
        $('.plan-link-widget[data-link_id=' + _id + ']').remove();
    }
    if (_type == 'graph') {
        $('.graph-widget[data-graph_id=' + _id + ']').remove();
    }
    if (_type == 'text') {
        $('.graph-widget[data-text_id=' + _id + ']').remove();
    }
    var parent = {
        height: $('#div_displayObject').height(),
        width: $('#div_displayObject').width(),
    };

    var html = $(_html);
    $('#div_displayObject').append(html);


    for (var key in _plan.css) {
        if (_plan.css[key] != '' && key != 'zoom') {
            if (key == 'background-color') {
                if (!isset(_plan.display) || !isset(_plan.display['background-defaut']) || _plan.display['background-defaut'] != 1) {
                    html.css(key, _plan.css[key]);
                }
            } else if (key == 'background-color') {
                if (!isset(_plan.display) || !isset(_plan.display['color-defaut']) || _plan.display['color-defaut'] != 1) {
                    html.css(key, _plan.css[key]);
                }
            } else {
                html.css(key, _plan.css[key]);
            }
        }
        if (key == 'color') {
            html.find('.btn.btn-default').css("cssText", key + ': ' + _plan.css[key] + ' !important;border-color : ' + _plan.css[key] + ' !important');
            html.find('tspan').css('fill', _plan.css[key]);
            html.find('span').css(key, _plan.css[key]);
        }
    }
    if (!isset(_plan.display) || !isset(_plan.display['background-defaut']) || _plan.display['background-defaut'] != 1) {
        if (isset(_plan.display) && isset(_plan.display['background-transparent']) && _plan.display['background-transparent'] == 1) {
            html.css('background-color', 'transparent');
        }
    }

    html.css('position', 'absolute');
    html.css('transform-origin', '0 0');
    html.css('transform', 'scale(' + init(_plan.css.zoom, defaultZoom) + ')');
    html.css('-webkit-transform-origin', '0 0');
    html.css('-webkit-transform', 'scale(' + init(_plan.css.zoom, defaultZoom) + ')');
    html.css('-moz-transform-origin', '0 0');
    html.css('-moz-transform', 'scale(' + init(_plan.css.zoom, defaultZoom) + ')');
    var position = {
        top: init(_plan.position.top, '10') * parent.height / 100,
        left: init(_plan.position.left, '10') * parent.width / 100,
    };

    html.css('top', position.top);
    html.css('left', position.left);
    if (grid === false) {
        html.addClass('noResize');
    } else {
        html.css("max-width", "");
        html.css("min-width", eqLogic_width_step);
        html.css("min-height", eqLogic_height_step);
        if (!isset(_plan.display) || !isset(_plan.display.width)) {
            html.css('width', 'auto');
        }
        if (!isset(_plan.display) || !isset(_plan.display.height)) {
            html.css('height', 'auto');
        }
        if (_type == 'eqLogic') {
            positionEqLogic('', false, 'eqLogic-widget');
        }
        if (_type == 'scenario') {
            positionEqLogic('', false, 'scenario-widget');
        }
        if (_type == 'view') {
            positionEqLogic('', false, 'view-link-widget');
        }
        if (_type == 'plan') {
            positionEqLogic('', false, 'plan-link-widget');
        }
        if (_type == 'graph') {
            positionEqLogic('', false, 'graph-widget');
        }
    }


    if (_type == 'eqLogic') {
        if (isset(_plan.display) && isset(_plan.display.cmd)) {
            for (var id in _plan.display.cmd) {
                if (_plan.display.cmd[id] == 1) {
                    $('.cmd[data-cmd_id=' + id + ']').remove();
                }
            }
        }
        if (isset(_plan.display) && (isset(_plan.display.name) && _plan.display.name == 1)) {
            html.find('.widget-name').remove();
        }
    }
    initDraggable($('#bt_editPlan').attr('data-mode'));
}

/***************************EqLogic**************************************/
function addEqLogic(_id, _plan) {
    jeedom.eqLogic.toHtml({
        id: _id,
        version: 'dashboard',
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            displayObject('eqLogic', _id, data.html, _plan);
            savePlan();
        }
    })
}

/***************************Scenario**************************************/
function addScenario(_id, _plan) {
    jeedom.scenario.toHtml({
        id: _id,
        version: 'dashboard',
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            displayObject('scenario', _id, data, _plan);
            savePlan();
        }
    })
}

/**********************************GRAPH************************************/
function addGraph(_plan) {
    var parent = {
        height: $('#div_displayObject').height(),
        width: $('#div_displayObject').width(),
    };
    _plan = init(_plan, {});
    _plan.display = init(_plan.display, {});
    _plan.link_id = init(_plan.link_id, Math.round(Math.random() * 99999999) + 9999);
    var options = init(_plan.display.graph, '[]');
    var html = '<div class="graph-widget" data-graph_id="' + _plan.link_id + '" style="width : ' + (init(_plan.display.width, 10) * parent.width / 100) + 'px;height : ' + (init(_plan.display.height, 10) * parent.height / 100) + 'px;background-color : white;border : solid 1px black;">';
    if ($('#bt_editPlan').attr('data-mode') == "1") {
        html += '<i class="fa fa-cogs pull-right editMode configureGraph" style="margin-right : 5px;margin-top : 5px;"></i>';
    } else {
        html += '<i class="fa fa-cogs pull-right editMode configureGraph" style="margin-right : 5px;margin-top : 5px;display:none;"></i>';
    }
    html += '<span class="graphOptions" style="display:none;">' + json_encode(init(_plan.display.graph, '[]')) + '</span>';
    html += '<div class="graph" id="graph' + _plan.link_id + '" style="width : 100%;height : 100%;"></div>';
    html += '</div>';
    displayObject('graph', _plan.link_id, html, _plan);

    for (var i in options) {
        if (init(options[i].link_id) != '') {
            jeedom.history.drawChart({
                cmd_id: options[i].link_id,
                el: 'graph' + _plan.link_id,
                showLegend: init(_plan.display.showLegend, true),
                showTimeSelector: init(_plan.display.showTimeSelector, true),
                showScrollbar: init(_plan.display.showScrollbar, true),
                dateRange: init(_plan.display.dateRange, '7 days'),
                option: init(options[i].configuration, {}),
                global: false,
            });
        }
    }
}


$('#div_displayObject').delegate('.graph-widget', 'resize', function () {
    if (isset(jeedom.history.chart['graph' + $(this).attr('data-graph_id')])) {
        jeedom.history.chart['graph' + $(this).attr('data-graph_id')].chart.reflow();
    }
});
/**********************************LINK************************************/
$('#md_selectLink .linkType').on('change', function () {
    $('#md_selectLink .linkOption').hide();
    $('#md_selectLink .link' + $(this).value()).show();
});

$('#md_selectLink .validate').on('click', function () {
    var link = {};
    link.type = $('#md_selectLink .linkType').value();
    link.id = $('#md_selectLink .link' + link.type + ' .linkId').value();
    link.name = $('#md_selectLink .link' + link.type + ' .linkId option:selected').text();
    $('#md_selectLink').modal('hide');
    addLink(link);
});

function addLink(_link, _plan) {
    _plan = init(_plan, {});
    _plan.css = init(_plan.css, {});
    var link = '';
    var label = '';
    if (_link.type == 'plan') {
        link = 'index.php?v=d&p=plan&plan_id=' + _link.id;
        label = 'label-success';
    }
    if (_link.type == 'view') {
        link = 'index.php?v=d&p=view&view_id=' + _link.id;
        label = 'label-primary';
    }
    var html = '<span class="' + _link.type + '-link-widget label ' + label + '" data-link_id="' + _link.id + '" >';
    html += '<a href="' + link + '" style="color:' + init(_plan.css.color, 'white') + ';text-decoration:none;font-size : 1.5em;">';
    html += _link.name;
    html += '</a>';
    html += '</span>';
    displayObject(_link.type, _link.id, html, _plan);
    savePlan();
}

/*********************************TEXTE*************************************/
function addText(_plan) {
    _plan = init(_plan, {});
    _plan.css = init(_plan.css, {});
    _plan.link_id = init(_plan.link_id, Math.round(Math.random() * 99999999) + 9999);
    var html = '<span class="text-widget label label-default" data-text_id="' + _plan.link_id + '" style="color:' + init(_plan.css.color, 'white') + ';text-decoration:none;font-size : 1.5em;">';
    html += _plan.display.text;
    html += '</span>';
    displayObject('text', _plan.link_id, html, _plan);
}
