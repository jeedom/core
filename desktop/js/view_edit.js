
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



 $(".li_view").on('click', function(event) {
    $.hideAlert();
    $(".li_view").removeClass('active');
    $(this).addClass('active');
    $('#div_view').show();
    jeedom.view.get({
        id: $(this).attr('data-view_id'),
        error: function(error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function(data) {
            $('#div_viewZones').empty();
            for (var i in data.viewZone) {
                var viewZone = data.viewZone[i];
                addEditviewZone(viewZone);
                for (var j in viewZone.viewData) {
                    var viewData = viewZone.viewData[j];
                    var span = addServiceToviewZone(viewData);
                    $('#div_viewZones .viewZone:last .div_viewData').append(span);
                }
            }
            modifyWithoutSave = false;
        }
    });
    return false;
});

 $('.viewDataOption[data-l1key=configuration][data-l2key=graphColor]').on('change', function() {
    setColorSelect($(this).closest('select'));
});

 $('#bt_viewResult').on('click', function() {
    window.location.href = 'index.php?v=d&p=view&view_id=' + $(".li_view.active").attr('data-view_id');
});

 $("#bt_addView").on('click', function(event) {
    bootbox.prompt("{{Nom de la vue ?}}", function(result) {
        if (result !== null) {
            editView({id: '', name: result});
        }
    });
});

 $("#bt_editView").on('click', function(event) {
    bootbox.prompt("Nom de la vue ?", function(result) {
        if (result !== null) {
            editView({id: $('.li_view.active').attr('data-view_id'), name: result});
        }
    });
});

 jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $('#bt_saveView').click();
});

 $('#bt_saveView').on('click', function(event) {
    $.hideAlert();
    var viewZones = [];
    $('.viewZone').each(function() {
        viewZoneInfo = {};
        var viewZoneInfo = $(this).getValues('.viewZoneAttr');
        viewZoneInfo = viewZoneInfo[0];
        viewZoneInfo.viewData = $(this).find('span.viewData').getValues('.viewDataAttr');
        viewZones.push(viewZoneInfo);
    });

    jeedom.view.save({
        id: $(".li_view.active").attr('data-view_id'),
        viewZones: viewZones,
        error: function(error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function() {
            $('#div_alert').showAlert({message: '{{Modification enregistré}}', level: 'success'});
            modifyWithoutSave = false;
        }
    });
    return;
});

 $("#bt_removeView").on('click', function(event) {
    $.hideAlert();
    bootbox.confirm('{{Etes-vous sûr de vouloir supprimer la vue}} <span style="font-weight: bold ;">' + $(".li_view.active a").text() + '</span> ?', function(result) {
        if (result) {
            jeedom.view.remove({
                id: $(".li_view.active").attr('data-view_id'),
                error: function(error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function() {
                    modifyWithoutSave = false;
                    window.location.reload();
                }
            });
        }
    });
});

 if (is_numeric(getUrlVars('view_id'))) {
    if ($('#ul_view .li_view[data-view_id=' + getUrlVars('view_id') + ']').length != 0) {
        $('#ul_view .li_view[data-view_id=' + getUrlVars('view_id') + ']').click();
    } else {
        $('#ul_view .li_view:first').click();
    }
} else {
    $('#ul_view .li_view:first').click();
}

$("#div_viewZones").sortable({axis: "y", cursor: "move", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

$('.enable').on('click', function() {
    var selectTr = $(this).closest('tr');
    if ($(this).value() == 1) {
        selectTr.find('div.option').show();
        if (selectTr.find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor]').length) {
            var color = selectTr.find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor]').value();
            var colorChange = true;
            var colorNumberChange = 0;
            while (colorChange) {
                colorChange = false;
                $('#table_addViewData tbody tr').each(function() {
                    if ($(this).find('.enable').value() == 1 && color == $(this).closest('tr').find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor]').value()) {
                        color = selectTr.find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor] option[value=' + color + ']').next().value();
                        colorChange = true;
                        colorNumberChange++;
                    }
                });
                if (colorNumberChange > selectTr.find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor] option').length) {
                    return;
                }
            }
            selectTr.find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor] option[value=' + color + ']').prop('selected', true);
            setColorSelect(selectTr.find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor]'));
        }
    } else {
        selectTr.find('div.option').hide();
    }
});

/*****************************viewZone****************************************/
$('#bt_addviewZone').on('click', function() {
    $('#in_addEditviewZoneEmplacement').val('');
    $('#in_addEditviewZoneName').val('');
    $('#sel_addEditviewZoneType').prop('disabled', false);
    $('#md_addEditviewZone').modal('show');
});

$('#bt_addEditviewZoneSave').on('click', function() {
    if ($.trim($('#in_addEditviewZoneName').val()) != '') {
        var viewZone = {name: $('#in_addEditviewZoneName').value(), emplacement: $('#in_addEditviewZoneEmplacement').value(), type: $('#sel_addEditviewZoneType').value()};
        addEditviewZone(viewZone);
        $('#md_addEditviewZone').modal('hide');
    } else {
        alert('div_addEditviewZoneError', 'Le nom de la viewZone ne peut être vide')
    }
});

$('#div_viewZones').delegate('.bt_removeviewZone', 'click', function() {
    $(this).closest('.viewZone').remove();
});

$('#div_viewZones').delegate('.bt_editviewZone', 'click', function() {
    $('#md_addEditviewZone').modal('show');
    $('#in_addEditviewZoneName').val($(this).closest('.viewZone').find('.viewZoneAttr[data-l1key=name]').text());
    $('#sel_addEditviewZoneType').val($(this).closest('.viewZone').find('.viewZoneAttr[data-l1key=type]').val());
    $('#sel_addEditviewZoneType').prop('disabled', true);
    $('#in_addEditviewZoneEmplacement').val($(this).closest('.viewZone').attr('id'));
});

/*****************************DATA****************************************/

$('#div_viewZones').delegate('.bt_addViewData', 'click', function() {
    var bt = $(this);
    $("#md_modal").load('index.php?v=d&modal=view.widget',function() { 
        $('#table_addViewData .filter').value('');
        var viewZone =bt.closest('.viewZone');
        $('#table_addViewData tbody tr .enable').prop('checked', false);
        var type = viewZone.find('.viewZoneAttr[data-l1key=type]').value();
        if (type == 'graph') {
            $('#table_addViewDataHidden tbody').append($('#table_addViewData tr[data-type=widget]'));
            $('#table_addViewData tbody').append($('#table_addViewDataHidden tr[data-type=graph]'));
        }
        if (type == 'widget') {
            $('#table_addViewDataHidden tbody').append($('#table_addViewData tr[data-type=graph]'));
            $('#table_addViewData tbody').append($('#table_addViewDataHidden tr[data-type=widget]'));
        }
        $('#table_addViewData').trigger('update');
        $('#table_addViewData tbody tr div.option').hide();


        var viewDatas = [];
        viewZone.find('span.viewData').each(function() {
            viewDatas.push($(this));
        });
        for (var i = (viewDatas.length - 1); i >= 0; i--) {
            var viewData = $('#table_addViewData tbody tr[data-viewDataType=' + viewDatas[i].find('.viewDataAttr[data-l1key=type]').value() + '][data-link_id=' + viewDatas[i].find('.viewDataAttr[data-l1key=link_id]').value() + ']');
            if (viewData != null) {
                viewData.find('.enable').value(1);
                viewData.find('.option').show();
                viewDatas[i].find('.viewDataAttr').each(function() {
                    viewData.find('.viewDataOption[data-l1key=' + $(this).attr('data-l1key') + '][data-l2key=' + $(this).attr('data-l2key') + ']').value($(this).value());
                });
                setColorSelect(viewData.find('.viewDataOption[data-l1key=configuration][data-l2key=graphColor]'));
                $('#table_addViewData tbody').prepend(viewData);
            }
        }

        $("#md_modal").dialog('option', 'buttons', {
            "Annuler": function() {
                $(this).dialog("close");
            },
            "Valider": function() {
                var tr = $('#table_addViewData tbody tr:first');
                span = '';
                while (tr.attr('data-link_id') != undefined) {
                    if (tr.find('.enable').is(':checked')) {
                        var viewData = tr.getValues('.viewDataOption');
                        viewData = viewData[0];
                        viewData.link_id = tr.attr('data-link_id');
                        viewData.name = '';
                        if (tr.find('.object_name').text() != '') {
                            viewData.name += '[' + tr.find('.object_name').text() + ']';
                        } else {
                            if (tr.find('.type').text() == 'Scénario') {
                                viewData.name += '[Scénario]';
                            }
                            if (tr.find('.type').text() == 'Commande') {
                                viewData.name += '[Aucun]';
                            }
                        }
                        viewData.name += '[' + tr.find('.name').text() + ']';
                        span += addServiceToviewZone(viewData);
                    }
                    tr = tr.next();
                }
                viewZone.find('span.viewData').remove();
                viewZone.find('.div_viewData').append(span);
                $(this).dialog('close');
            }
        });
$("#md_modal").dialog('open');
});
});

$('#div_viewZones').delegate('.bt_removeViewData', 'click', function() {
    $(this).closest('span').remove();
});


$('body').delegate('.viewZoneAttr', 'change', function() {
    modifyWithoutSave = true;
});

$('body').delegate('.viewDataAttr', 'change', function() {
    modifyWithoutSave = true;
});

function setColorSelect(_select) {
    _select.css('background-color', _select.find('option:selected').val());
}

function editView(_view) {
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "core/ajax/view.ajax.php", // url du fichier php
        data: {
            action: "edit",
            name: _view.name,
            id: _view.id,
        },
        dataType: 'json',
        error: function(request, status, error) {
            handleAjaxError(request, status, error, $('#div_addViewAlert'));
        },
        success: function(data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_addViewAlert').showAlert({message: data.result, level: 'danger'});
            return;
        }
        if ($('.li_view[data-view_id=' + data.result.id + ']').length != 0) {
            $('.li_view.active a').text($('#in_addViewName').value());
        } else {
            window.location.replace('index.php?v=d&p=view_edit&view_id=' + data.result.id);
        }
    }
});
}

function addEditviewZone(_viewZone) {
    if (!isset(_viewZone.configuration)) {
        _viewZone.configuration = {};
    }
    if (init(_viewZone.emplacement) == '') {
        var id = $('#div_viewZones .viewZone').length;
        var div = '<div id="viewZone' + id + '" class="viewZone" data-toggle="tab">';
        div += '<legend style="height: 35px;"><span class="viewZoneAttr" data-l1key="name">' + init(_viewZone.name) + '</span>';
        div += '<a class="btn btn-danger btn-xs pull-right bt_removeviewZone"><i class="fa fa-trash-o"></i> Supprimer</a>';
        div += ' <a class="btn btn-warning btn-xs pull-right bt_editviewZone"><i class="fa fa-pencil"></i> Editer</a>';
        div += '<a class="btn btn-primary btn-xs pull-right bt_addViewData"><i class="fa fa-plus-circle"></i> Ajouter/Editer ' + init(_viewZone.type, 'widget') + '</a>';

        if (init(_viewZone.type, 'widget') == 'graph') {
            div += '<select class="pull-right viewZoneAttr form-control input-sm" data-l1key="configuration" data-l2key="dateRange" style="width : 200px;">';
            if (init(_viewZone.configuration.dateRange) == "30 min") {
                div += '<option value="30 min" selected>{{30min}}</option>';
            } else {
                div += '<option value="30 min">{{30min}}</option>';
            }
            if (init(_viewZone.configuration.dateRange) == "1 day") {
                div += '<option value="1 day" selected>{{Jour}}</option>';
            } else {
                div += '<option value="1 day">{{Jour}}</option>';
            }
            if (init(_viewZone.configuration.dateRange, '7 days') == "7 days") {
                div += '<option value="7 days" selected>{{Semaine}}</option>';
            } else {
                div += '<option value="7 days">{{Semaine}}</option>';
            }
            if (init(_viewZone.configuration.dateRange) == "1 month") {
                div += '<option value="1 month" selected>{{Mois}}</option>';
            } else {
                div += '<option value="1 month">{{Mois}}</option>';
            }
            if (init(_viewZone.configuration.dateRange) == "1 year") {
                div += '<option value="1 year" selected>{{Année}}</option>';
            } else {
                div += '<option value="1 year">{{Année}}</option>';
            }
            if (init(_viewZone.configuration.dateRange) == "all") {
                div += '<option value="all" selected>{{Tous}}</option>';
            } else {
                div += '<option value="all">{{Tous}}</option>';
            }
            div += '</select>';
        }

        div += '</legend>';
        div += '<input style="display : none;" class="viewZoneAttr" data-l1key="type" value="' + init(_viewZone.type) + '">';
        div += '<div class="div_viewData"></div>';
        div += '</div>';
        $('#div_viewZones').append(div);
        $('#viewZone' + id + ' .div_viewData').sortable({axis: "y", cursor: "move", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
    } else {
        $('#' + _viewZone.emplacement).find('.viewZoneAttr[data-l1key=name]').text(_viewZone.name);
    }
}

function addServiceToviewZone(_viewData) {
    if (!isset(_viewData.configuration) || _viewData.configuration == '') {
        _viewData.configuration = {};
    }
    var span = '<div><span class="label label-default viewData cursor" style="background-color : ' + init(_viewData.configuration.graphColor) + '; font-size : 1.1em;margin-top:10px;">';
    span += '<i class="fa fa-trash-o cursor bt_removeViewData"></i> ';
    span += init(_viewData.name);
    span += '<input class="viewDataAttr" data-l1key="link_id" value="' + init(_viewData.link_id) + '" style="display  : none;"/>';
    span += '<input class="viewDataAttr" data-l1key="type" value="' + init(_viewData.type) + '" style="display  : none;"/>';
    for (var i in _viewData.configuration) {
        span += '<input class="viewDataAttr" data-l1key="configuration" data-l2key="' + i + '" value="' + init(_viewData.configuration[i]) + '" style="display  : none;"/>';
    }
    span += '</span><br/><br/></div>';
    return span;
}
