
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


$('#div_tree').on('select_node.jstree', function (node, selected) {
    if (selected.node.a_attr.class == 'infoObject') {
        displayObject(selected.node.a_attr['data-object_id']);
    }
    if (selected.node.a_attr.class == 'infoEqLogic') {
        displayEqLogic(selected.node.a_attr['data-eqlogic_id']);
    }
    if (selected.node.a_attr.class == 'infoCmd') {
        displayCmd(selected.node.a_attr['data-cmd_id']);
    }
});
$('#div_tree').jstree();


$("#bt_displayConfig").on('click', function (event) {
    $.hideAlert();
    saveConfiguration($('#display_configuration'));
});

$('.bt_resetColor').on('click', function () {
    var el = $(this);
    jeedom.getConfiguration({
        key: $(this).attr('data-l1key'),
        default: 1,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.configKey[data-l1key="' + el.attr('data-l1key') + '"]').value(data);
        }
    });
});


function saveConfiguration(_el) {
    jeedom.config.save({
        configuration: _el.getValues('.configKey')[0],
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
            modifyWithoutSave = false;
        }
    });
}

/***************************Commandes****************************/
function displayCmd(_cmd_id) {
    jeedom.cmd.byId({
        id: _cmd_id,
        noCache: true,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#div_displayInfo').empty();
            var div = '<div class="row">';
            div += '<div class="col-sm-6" >';
            div += '<form class="form-horizontal">';
            div += '<fieldset>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{ID}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="id"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Logical ID}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="logicalId"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Nom}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="name"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Type}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="type"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Sous-type}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="subType"></span>';
            div += '</div>';
            div += '</div>';

            div += '</fieldset>';
            div += '</form>';
            div += '</div>';
            div += '<div class="col-sm-6" >';


            div += '<form class="form-horizontal">';
            div += '<fieldset>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Unité}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="unite"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Commande déclenchant une mise à jour}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="value"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Memcache}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary tooltips" data-l1key="cache" data-l2key="enable" title="{{Actif}}"></span> ';
            div += '<span class="label label-default tooltips" title="{{Durée du cache}}"><span class="cmdAttr" data-l1key="cache" data-l2key="lifetime"></span> {{seconde(s)}}</span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-xs-4 control-label">{{Evenement seulement}}</label>';
            div += '<div class="col-xs-4">';
            div += '<span class="cmdAttr label label-primary" data-l1key="eventOnly"></span>';
            div += '</div>';
            div += '</div>';

            div += '</fieldset>';
            div += '</form>';
            div += '</div>';
            div += '</div>';


            div += '<div>';


            div += '<legend>{{Utilisé par}}</legend>';
            div += '<form class="form-horizontal">';
            div += '<fieldset id="fd_cmdUsedBy">';
            jeedom.cmd.usedBy({
                id: _cmd_id,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    var html = '';
                    html += '<div class="form-group">';
                    html += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Equipement}}</label>';
                    html += '<div class="col-sm-4">';
                    for (var i in data.eqLogic) {
                        html += ' <span class="label label-primary cursor"><a href="' + data.eqLogic[i].link + '" style="color : white;">' + data.eqLogic[i].humanName + '</a></span>';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="form-group">';
                    html += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Commandes}}</label>';
                    html += '<div class="col-sm-4">';
                    for (var i in data.cmd) {
                        html += ' <span class="label label-primary"><a href="' + data.cmd[i].link + '" style="color : white;">' + data.cmd[i].humanName + '</a></span>';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="form-group">';
                    html += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Scénario}}</label>';
                    html += '<div class="col-sm-4">';
                    for (var i in data.scenario) {
                        html += ' <span class="label label-primary"><a href="' + data.scenario[i].link + '" style="color : white;">' + data.scenario[i].humanName + '</a></span>';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="form-group">';
                    html += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Interaction}}</label>';
                    html += '<div class="col-sm-4">';
                    for (var i in data.interact) {
                        html += ' <span class="label label-primary"><a href="' + data.interact[i].link + '" style="color : white;">' + data.interact[i].humanName + '</a></span>';
                    }
                    html += '</div>';
                    html += '</div>';
                    $('#fd_cmdUsedBy').append(html);
                }
            });

            div += '</fieldset>';
            div += '</form>';

            div += '<legend>{{Configuration}}</legend>';
            div += '<form class="form-horizontal">';
            div += '<fieldset>';

            if (data.type == 'info' && data.subType == 'numeric') {
                div += '<div class="form-group">';
                div += '<label class="col-sm-6 control-label">{{Formule de calcul (utiliser #value# pour la valeur, ex #value# - 2)}}</label>';
                div += '<div class="col-sm-6">';
                div += '<input class="cmdAttr form-control" data-l1key="configuration" data-l2key="calculValueOffset" />';
                div += '</div>';
                div += '</div>';
            }

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Ne pas afficher le nom de la commande sur le dashboard}}</label>';
            div += '<div class="col-sm-1">';
            div += '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="doNotShowNameOnDashboard" />';
            div += '</div>';
            div += '<label class="col-sm-2 control-label">{{sur les vues}}</label>';
            div += '<div class="col-sm-1">';
            div += '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="doNotShowNameOnView" />';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Widget Desktop}}</label>';
            div += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
            div += '<select class="form-control cmdAttr" data-l1key="template" data-l2key="dashboard">';
            for (var i in cmd_widgetDashboard[data.type][data.subType]) {
                div += '<option>' + cmd_widgetDashboard[data.type][data.subType][i].name + '</option>';
            }
            div += '</select>';
            div += '</div>';
            div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Mobile}}</label>';
            div += '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">';
            div += '<select class="form-control cmdAttr" data-l1key="template" data-l2key="mobile">';
            for (var i in cmd_widgetMobile[data.type][data.subType]) {
                div += '<option>' + cmd_widgetMobile[data.type][data.subType][i].name + '</option>';
            }
            div += '</select>';
            div += '</div>';
            div += '</div>';

            jeedom.getConfiguration({
                key: 'cmd:type:' + data.type + ':subtype:' + data.subType,
                default: 0,
                async: false,
                error: function (error) {
                    _params.error(error);
                },
                success: function (subtype) {
                    if (isset(subtype.isHistorized) && subtype.isHistorized.visible) {
                        div += '<div class="form-group">';
                        div += '<label class="col-sm-2 control-label">{{Historiser}}</label>';
                        div += '<div class="col-sm-1">';
                        div += '<input type="checkbox" class="cmdAttr" data-l1key="isHistorized" />';
                        div += '</div>';
                        div += '<label class="col-sm-2 control-label">{{Mode de lissage}}</label>';
                        div += '<div class="col-sm-2">';
                        div += '<select class="form-control cmdAttr" data-l1key="configuration" data-l2key="historizeMode">';
                        div += '<option value="avg">Moyenne</option>';
                        div += '<option value="min">Minimum</option>';
                        div += '<option value="max">Maximum</option>';
                        div += '<option value="none">Aucun</option>';
                        div += '</select>';
                        div += '</div>';
                        div += '<label class="col-sm-2 control-label">{{Arrondi (nombre de chiffre après la virgule)}}</label>';
                        div += '<div class="col-sm-2">';
                        div += '<input class="cmdAttr form-control" data-l1key="configuration" data-l2key="historizeRound" />';

                        div += '</div>';
                        div += '</div>';
                        $('#div_displayInfo').setValues(data, '.cmdAttr');
                    }
                }
            });

            div += '</fieldset>';
            div += '</form>';

            div += '<legend>{{Paramètres optionnels widget}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>';
            div += '<table class="table table-bordered table-condensed" id="table_widgetParameters">';
            div += '<thead>';
            div += '<tr>';
            div += '<th>Nom</th>';
            div += '<th>Valeur</th>';
            div += '<th>Action</th>';
            div += '</tr>';
            div += '</thead>';
            div += '<tbody>';
            if (isset(data.display) && isset(data.display.parameters)) {
                for (var i in data.display.parameters) {
                    div += '<tr>';
                    div += '<td>';
                    div += '<input class="form-control key" value="' + i + '" />';
                    div += '</td>';
                    div += '<td>';
                    div += '<input class="form-control value" value="' + data.display.parameters[i] + '" />';
                    div += '</td>';
                    div += '<td>';
                    div += '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fa fa-times"></i> Supprimer</a>';
                    div += '</td>';
                    div += '</tr>';
                }
            }
            div += '</tbody>';
            div += '</table>';
            div += '<a class="btn btn-success" id="saveCmd"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>';

            $('#div_displayInfo').html(div);

            $('#table_widgetParameters').off().delegate('.removeWidgetParameter', 'click', function () {
                $(this).closest('tr').remove();
            });

            $('#bt_addWidgetParameters').off().on('click', function () {
                var tr = '<tr>';
                tr += '<td>';
                tr += '<input class="form-control key" />';
                tr += '</td>';
                tr += '<td>';
                tr += '<input class="form-control value" />';
                tr += '</td>';
                tr += '<td>';
                tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fa fa-times"></i> Supprimer</a>';
                tr += '</td>';
                tr += '</tr>';
                $('#table_widgetParameters tbody').append(tr);
            });


            $('#div_displayInfo').setValues(data, '.cmdAttr');
            $('#saveCmd').off().on('click', function () {
                var cmd = $('#div_displayInfo').getValues('.cmdAttr')[0];
                if (!isset(cmd.display)) {
                    cmd.display = {};
                }
                if (!isset(cmd.display.parameters)) {
                    cmd.display.parameters = {};
                }
                $('#table_widgetParameters tbody tr').each(function () {
                    cmd.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value();
                });

                jeedom.cmd.save({
                    cmd: cmd,
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function () {
                        $('#div_alert').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
                    }
                });
            });
        }
    });
}

/***********************Objet***************************/
function displayObject(_object_id) {
    jeedom.object.byId({
        id: _object_id,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#div_displayInfo').empty();
            var div = '<div class="row">';
            div += '<form class="form-horizontal">';
            div += '<fieldset>';

            div += '<div class="form-group">';
            div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{ID}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="objectAttr label label-primary" data-l1key="id"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nom}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="objectAttr label label-primary" data-l1key="name"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Visible}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="objectAttr label label-primary" data-l1key="isVisible"></span>';
            div += '</div>';
            div += '</div>';

            div += '</fieldset>';
            div += '</form>';
            div += '</div>';
            $('#div_displayInfo').html(div);
            $('#div_displayInfo').setValues(data, '.objectAttr');

        }
    });
}

/***********************EqLogic***************************/
function displayEqLogic(_eqLogic_id) {
    $.hideAlert();
    jeedom.eqLogic.byId({
        id: _eqLogic_id,
        noCache: true,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#div_displayInfo').empty();
            var div = '<div class="row">';
            div += '<div class="col-sm-6" >';
            div += '<form class="form-horizontal">';
            div += '<fieldset>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{ID}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="id"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Nom}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="name"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Logical ID}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="logicalId"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Object ID}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="object_id"></span>';
            div += '</div>';
            div += '</div>';

            div += '</fieldset>';
            div += '</form>';
            div += '</div>';
            div += '<div class="col-sm-6" >';
            div += '<form class="form-horizontal">';
            div += '<fieldset>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Type}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="eqType_name"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Activer}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="isEnable"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Visible}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="isVisible"></span>';
            div += '</div>';
            div += '</div>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Tentative échouée}}</label>';
            div += '<div class="col-sm-4">';
            div += '<span class="eqLogicAttr label label-primary" data-l1key="status" data-l2key="numberTryWithoutSuccess"></span>';
            div += '</div>';
            div += '</div>';

            div += '</fieldset>';
            div += '</form>';

            div += '</div>';

            div += '<legend>Configuration</legend>';

            div += '<div class="form-group">';
            div += '<label class="col-sm-4 control-label">{{Ne pas afficher le nom de l\'équipement sur le dashboard}}</label>';
            div += '<div class="col-sm-1">';
            div += '<input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowNameOnDashboard" />';
            div += '</div>';
            div += '<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{sur les vues}}</label>';
            div += '<div class="col-sm-1">';
            div += '<input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowNameOnView" />';
            div += '</div>';
            div += '</div>';

            div += '<legend>{{Paramètres optionnels sur la tuile}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>';
            div += '<table class="table table-bordered table-condensed" id="table_widgetParameters">';
            div += '<thead>';
            div += '<tr>';
            div += '<th>Nom</th>';
            div += '<th>Valeur</th>';
            div += '<th>Action</th>';
            div += '</tr>';
            div += '</thead>';
            div += '<tbody>';
            if (isset(data.display) && isset(data.display.parameters)) {
                for (var i in data.display.parameters) {
                    div += '<tr>';
                    div += '<td>';
                    div += '<input class="form-control key" value="' + i + '" />';
                    div += '</td>';
                    div += '<td>';
                    div += '<input class="form-control value" value="' + data.display.parameters[i] + '" />';
                    div += '</td>';
                    div += '<td>';
                    div += '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fa fa-times"></i> Supprimer</a>';
                    div += '</td>';
                    div += '</tr>';
                }
            }
            div += '</tbody>';
            div += '</table>';

            div += '<a class="btn btn-success" id="saveEqLogic"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>';


            div += '</div>';
            $('#div_displayInfo').html(div);
            $('#div_displayInfo').setValues(data, '.eqLogicAttr');

            $('#table_widgetParameters').off().delegate('.removeWidgetParameter', 'click', function () {
                $(this).closest('tr').remove();
            });

            $('#bt_addWidgetParameters').off().on('click', function () {
                var tr = '<tr>';
                tr += '<td>';
                tr += '<input class="form-control key" />';
                tr += '</td>';
                tr += '<td>';
                tr += '<input class="form-control value" />';
                tr += '</td>';
                tr += '<td>';
                tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fa fa-times"></i> Supprimer</a>';
                tr += '</td>';
                tr += '</tr>';
                $('#table_widgetParameters tbody').append(tr);
            });

            $('#saveEqLogic').off().on('click', function () {
                var eqLogic = $('#div_displayInfo').getValues('.eqLogicAttr')[0];
                if (!isset(eqLogic.display)) {
                    eqLogic.display = {};
                }
                if (!isset(eqLogic.display.parameters)) {
                    eqLogic.display.parameters = {};
                }
                $('#table_widgetParameters tbody tr').each(function () {
                    eqLogic.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value();
                });


                jeedom.eqLogic.save({
                    eqLogics: [eqLogic],
                    type: eqLogic.eqType_name,
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function () {
                        $('#div_alert').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
                    }});
            });
        }
    });
}