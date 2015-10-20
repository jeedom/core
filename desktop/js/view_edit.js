
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

 $('#bt_chooseIcon').on('click', function () {
    chooseIcon(function (_icon) {
        $('.viewAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
    });
});

 $('.viewAttr[data-l1key=display][data-l2key=icon]').on('dblclick',function(){
    $('.viewAttr[data-l1key=display][data-l2key=icon]').value('');
});

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
            $('#div_view').setValues(data,'.viewAttr');
            for (var i in data.viewZone) {
                var viewZone = data.viewZone[i];
                addEditviewZone(viewZone);
                for (var j in viewZone.viewData) {
                    var viewData = viewZone.viewData[j];
                    if (init(viewZone.type, 'widget') == 'graph') {
                     $('#div_viewZones .viewZone:last .div_viewData').append(addGraphService(viewData));
                 }else{
                     $('#div_viewZones .viewZone:last .div_viewData tbody').append(addWidgetService(viewData));
                 }
             }
         }
         initCheckBox();
         modifyWithoutSave = false;
     }
 });
    return false;
});

$('#bt_viewResult').on('click', function() {
   loadPage('index.php?v=d&p=view&view_id=' + $(".li_view.active").attr('data-view_id'));
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
    var view = $('#div_view').getValues('.viewAttr')[0];
    view.zones = [];
    $('.viewZone').each(function() {
        viewZoneInfo = {};
        var viewZoneInfo = $(this).getValues('.viewZoneAttr');
        viewZoneInfo = viewZoneInfo[0];
        viewZoneInfo.viewData = $(this).find('tr.viewData').getValues('.viewDataAttr');
        view.zones.push(viewZoneInfo);
    });
    jeedom.view.save({
        id: $(".li_view.active").attr('data-view_id'),
        view: view,
        error: function(error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function() {
            $('#div_alert').showAlert({message: '{{Modification enregistrée}}', level: 'success'});
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
                   loadPage('index.php?v=d&p=viewEdit');
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

$('body').delegate('#table_addViewData .enable','change switchChange.bootstrapSwitch', function() {
    var selectTr = $(this).closest('tr');
    if ($(this).value() == 1) {
        selectTr.find('div.option').show();
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
        alert('div_addEditviewZoneError', '{{Le nom de la viewZone ne peut être vide}}')
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

$('#div_viewZones').delegate('.bt_removeViewData', 'click', function() {
    $(this).closest('tr').remove();
});


$('body').delegate('.viewZoneAttr', 'change', function() {
    modifyWithoutSave = true;
});

$('body').delegate('.viewDataAttr', 'change', function() {
    modifyWithoutSave = true;
});

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
             loadPage('index.php?v=d&p=view_edit&view_id=' + data.result.id);
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
        var div = '<div class="viewZone" data-toggle="tab" id="div_viewZone'+id+'">';
        div += '<legend style="height: 35px;"><span class="viewZoneAttr" data-l1key="name"></span>';
        div += '<a class="btn btn-danger btn-xs pull-right bt_removeviewZone"><i class="fa fa-trash-o"></i> Supprimer</a>';
        div += ' <a class="btn btn-warning btn-xs pull-right bt_editviewZone"><i class="fa fa-pencil"></i> Editer</a>';
        if (init(_viewZone.type, 'widget') == 'graph') {
           div += '<a class="btn btn-primary btn-xs pull-right bt_addViewGraph"><i class="fa fa-plus-circle"></i> Ajouter courbe</a>';
       }else{
           div += '<a class="btn btn-primary btn-xs pull-right bt_addViewWidget"><i class="fa fa-plus-circle"></i> Ajouter Widget</a>';
       }
       div += '<select class="pull-right viewZoneAttr form-control input-sm" data-l1key="configuration" data-l2key="zoneCol" style="width : 200px;">';
       div += '<option value="12">{{Largeur de 1/1}}</option>';
       div += '<option value="6">{{Largeur de 1/2}}</option>';
       div += '<option value="4">{{Largeur de 1/3}}</option>';
       div += '<option value="3">{{Largeur de 1/4}}</option>';
       div += '</select>';
       if (init(_viewZone.type, 'widget') == 'graph') {
        div += '<select class="pull-right viewZoneAttr form-control input-sm" data-l1key="configuration" data-l2key="dateRange" style="width : 200px;">';
        div += '<option value="30 min">{{30 min}}</option>';
        div += '<option value="1 day">{{Jour}}</option>';
        div += '<option value="7 days">{{Semaine}}</option>';
        div += '<option value="1 month">{{Mois}}</option>';
        div += '<option value="1 year">{{Année}}</option>';
        div += '<option value="all">{{Tous}}</option>';
        div += '</select>';
    }
    div += '</legend>';
    div += '<input style="display : none;" class="viewZoneAttr" data-l1key="type">';
    if (init(_viewZone.type, 'widget') == 'graph') {
     div += '<table class="table table-condensed div_viewData">';
     div += '<thead>';
     div += '<tr><th></th><th>{{Nom}}</th><th>{{Couleur}}</th><th>{{Type}}</th><th>{{Echelle}}</th><th>{{Escalier}}</th><th>{{Empiler}}</th><th>{{Variation}}</th></tr>';
     div += '</thead>';
     div += '<tbody>';
     div += '</tbody>';
     div += '</table>';
 }else{
    div += '<table class="table table-condensed div_viewData">';
    div += '<thead>';
    div += '<tr><th></th><th>{{Nom}}</th></tr>';
    div += '</thead>';
    div += '<tbody>';
    div += '</tbody>';
    div += '</table>';
}
div += '</div>';
$('#div_viewZones').append(div);
$('#div_viewZones .viewZone:last').setValues(_viewZone,'.viewZoneAttr');
$("#div_viewZones .viewZone:last .div_viewData tbody").sortable({axis: "y", cursor: "move", items: ".viewData", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
} else {
    $('#' + _viewZone.emplacement).find('.viewZoneAttr[data-l1key=name]').text(_viewZone.name);
}
}

$('#div_viewZones').delegate('.bt_addViewGraph','click',function(){
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd : {isHistorized : 1}}, function (result) {
     el.closest('.viewZone').find('.div_viewData tbody').append( addGraphService({name : result.human,link_id : result.cmd.id,type : 'cmd'}));
     initCheckBox();
 });
});

$('#div_viewZones').delegate('.viewDataAttr[data-l1key=configuration][data-l2key=graphColor]','change',function(){
    $(this).css('background-color',$(this).value());
});

function addGraphService(_viewData){
    if (!isset(_viewData.configuration) || _viewData.configuration == '') {
        _viewData.configuration = {};
    }
    var tr = '<tr class="viewData">';
    tr += '<td><i class="fa fa-trash-o cursor bt_removeViewData"></i></td>';
    tr += '<td>';
    tr += '<input class="viewDataAttr" data-l1key="link_id" style="display  : none;"/>';
    tr += '<input class="viewDataAttr" data-l1key="type" style="display  : none;"/>';
    tr += '<span class="viewDataAttr" data-l1key="name"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphColor" style="background-color:#4572A7;color:white;">'
    tr += '<option value="#4572A7" style="background-color:#4572A7;color:white;">{{Bleu}}</option>'
    tr += '<option value="#AA4643" style="background-color:#AA4643;color:white;">{{Rouge}}</option>'
    tr += '<option value="#89A54E" style="background-color:#89A54E;color:white;">{{Vert}}</option>'
    tr += '<option value="#80699B" style="background-color:#80699B;color:white;">{{Violet}}</option>'
    tr += '<option value="#00FFFF" style="background-color:#00FFFF;color:white;">{{Bleu ciel}}</option>'
    tr += '<option value="#DB843D" style="background-color:#DB843D;color:white;">{{Orange}}</option>'
    tr += '<option value="#FFFF00" style="background-color:#FFFF00;color:white;">{{Jaune}}</option>'
    tr += '<option value="#FE2E9A" style="background-color:#FE2E9A;color:white;">{{Rose}}</option>'
    tr += '<option value="#000000" style="background-color:#000000;color:white;">{{Noir}}</option>'
    tr += '<option value="#3D96AE" style="background-color:#3D96AE;color:white;">{{Vert/Bleu}}</option>'
    tr += '</select>'
    tr += '</td>';
    tr += '<td>';
    tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphType">'
    tr +=  '<option value="line">{{Ligne}}</option>'
    tr +=  '<option value="area">{{Aire}}</option>'
    tr +=  '<option value="column">{{Colonne}}</option>'
    tr +=  '<option value="pie">{{Camembert}}</option>'
    tr +=  '</select>'
    tr += '</td>';
    tr += '<td>';
    tr += '<select class="viewDataAttr form-control input-sm" data-l1key="configuration" data-l2key="graphScale" style="width : 90px;">'
    tr += '<option value="0">{{Droite}}</option>'
    tr += '<option value="1">{{Gauche}}</option>'
    tr += '</select>'
    tr += '</td>';
    tr += '<td>';
    tr += '<input type="checkbox" data-size="mini" class="viewDataAttr bootstrapSwitch" data-l1key="configuration" data-l2key="graphStep">'
    tr += '</td>';
    tr += '<td>';
    tr += '<input type="checkbox" data-size="mini" class="viewDataAttr bootstrapSwitch" data-l1key="configuration" data-l2key="graphStack">'
    tr += '</td>';
    tr += '<td>';
    tr += '<input type="checkbox" data-size="mini" class="viewDataAttr bootstrapSwitch" data-l1key="configuration" data-l2key="derive">'
    tr += '</td>';
    tr += '</tr>';
    var result = $(tr);
    result.setValues(_viewData,'.viewDataAttr');
    result.find('.viewDataAttr[data-l1key=configuration][data-l2key=graphColor]').css('background-color',init(_viewData.configuration.graphColor,'#4572A7'));
    return result;
}

$('#div_viewZones').delegate('.bt_addViewWidget','click',function(){
    var el = $(this);
    jeedom.eqLogic.getSelectModal({}, function (result) {
     el.closest('.viewZone').find('.div_viewData tbody').append( addWidgetService({name : result.human.replace('#','').replace('#',''),link_id : result.id,type : 'eqLogic'}));
 });
});


function addWidgetService(_viewData){
    if (!isset(_viewData.configuration) || _viewData.configuration == '') {
        _viewData.configuration = {};
    }
    var tr = '<tr class="viewData">';
    tr += '<td><i class="fa fa-trash-o cursor bt_removeViewData"></i></td>';
    tr += '<td>';
    tr += '<input class="viewDataAttr" data-l1key="link_id" style="display  : none;"/>';
    tr += '<input class="viewDataAttr" data-l1key="type" style="display  : none;"/>';
    tr += '<span class="viewDataAttr" data-l1key="name"></span>';
    tr += '</td>';
    tr += '</tr>';
    var result = $(tr);
    result.setValues(_viewData,'.viewDataAttr')
    return result;
}
