
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

if (getUrlVars('removeSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'});
}

if((!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1) && !jQuery.support.touch){
    $('#sd_jeeObjectList').hide();
    $('#div_resumeJeeObjectList').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
    $('#div_conf').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');

    $('#bt_displayJeeObject').on('mouseenter',function(){
       var timer = setTimeout(function(){
        $('#bt_displayJeeObject').find('i').hide();
        $('#div_resumeJeeObjectList').addClass('col-lg-10 col-md-10 col-sm-9').removeClass('col-lg-12');
        $('#div_conf').addClass('col-lg-10 col-md-10 col-sm-9').removeClass('col-lg-12');
        $('#sd_jeeObjectList').show();
        $('.jeeObjectListContainer').packery();
    }, 100);
       $(this).data('timerMouseleave', timer)
   }).on("mouseleave", function(){
      clearTimeout($(this).data('timerMouseleave'));
  });

   $('#sd_jeeObjectList').on('mouseleave',function(){
    var timer = setTimeout(function(){
       $('#sd_jeeObjectList').hide();
       $('#bt_displayJeeObject').find('i').show();
       $('#div_resumeJeeObjectList').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
       $('#div_conf').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
       $('.jeeObjectListContainer').packery();
   }, 300);
    $(this).data('timerMouseleave', timer);
}).on("mouseenter", function(){
  clearTimeout($(this).data('timerMouseleave'));
});
}

$('#bt_graphJeeObject').on('click', function () {
  $('#md_modal').dialog({title: "{{Graphique des liens}}"});
  $("#md_modal").load('index.php?v=d&modal=graph.link&filter_type=jeeObject&filter_id='+$('.jeeObjectAttr[data-l1key=id]').value()).dialog('open');
});

setTimeout(function(){
  $('.jeeObjectListContainer').packery();
},100);

$('#bt_returnToThumbnailDisplay').on('click',function(){
    $('#div_conf').hide();
    $('#div_resumeJee0bjectList').show();
    $('.jeeObjectListContainer').packery();
});

$(".li_jeeObject,.jeeObjectDisplayCard").on('click', function (event) {
   loadJeeObjectConfiguration($(this).attr('data-jeeObject_id'));
   $('.jeeObjectname_resume').empty().append($(this).attr('data-jeeObject_icon')+'  '+$(this).attr('data-jeeObject_name'));
   return false;
});

function loadJeeObjectConfiguration(_id){
    $('#div_conf').show();
    $('#div_resumeJeeObjectList').hide();
    $('.li_jeeObject').removeClass('active');
    $(this).addClass('active');
    $('.li_jeeObject[data-jeeObject_id='+_id+']').addClass('active');
    jeedom.jeeObject.byId({
        id: _id,
        cache: false,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.jeeObjectAttr').value('');
            $('.jeeObjectAttr[data-l1key=father_id] option').show();
            $('#summarytab input[type=checkbox]').value(0);
            $('.jeeObject').setValues(data, '.jeeObjectAttr');
            if(data['display'] == ''){
                $('.jeeObjectAttr[data-l1key=display][data-l2key=tagColor]').value('#9b59b6');
                $('.jeeObjectAttr[data-l1key=display][data-l2key=tagTextColor]').value('#ffffff');
            }
            $('.jeeObjectAttr[data-l1key=father_id] option[value=' + data.id + ']').hide();
            $('.div_summary').empty();
            $('.tabnumber').empty();
            if (isset(data.configuration) && isset(data.configuration.summary)) {
                for(var i in data.configuration.summary){
                    var el = $('.type'+i);
                    if(el != undefined){
                        for(var j in data.configuration.summary[i]){
                            addSummaryInfo(el,data.configuration.summary[i][j]);
                        }
                        if (data.configuration.summary[i].length != 0){
                            $('.summarytabnumber'+i).append('(' + data.configuration.summary[i].length + ')');
                        }
                    }

                }
            }
            modifyWithoutSave = false;
        }
    });
}

$("#bt_addJeeObject,#bt_addJeeObject2").on('click', function (event) {
    bootbox.prompt("Nom de l'objet ?", function (result) {
        if (result !== null) {
            jeedom.jeeObject.save({
                jeeObject: {name: result, isVisible: 1},
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    modifyWithoutSave = false;
                    loadPage('index.php?v=d&p=jeeObject&id=' + data.id + '&saveSuccessFull=1');
                    $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
                }
            });
        }
    });
});

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveJeeObject").click();
});

$('.jeeObjectAttr[data-l1key=display][data-l2key=icon]').on('dblclick',function(){
    $('.jeeObjectAttr[data-l1key=display][data-l2key=icon]').value('');
});

$("#bt_saveJeeObject").on('click', function (event) {
    if ($('.li_jeeObject.active').attr('data-jeeObject_id') != undefined) {
        var jeeObject = $('.jeeObject').getValues('.jeeObjectAttr')[0];
        if (!isset(jeeObject.configuration)) {
            jeeObject.configuration = {};
        }
        if (!isset(jeeObject.configuration.summary)) {
            jeeObject.configuration.summary = {};
        }
        $('.jeeObject .div_summary').each(function () {
            var type = $(this).attr('data-type');
            jeeObject.configuration.summary[type] = [];
            summaries = {};
            $(this).find('.summary').each(function () {
                var summary = $(this).getValues('.summaryAttr')[0];
                jeeObject.configuration.summary[type].push(summary);
            });
        });
        jeedom.jeeObject.save({
            jeeObject: jeeObject,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                modifyWithoutSave = false;
                loadJeeObjectConfiguration(data.id);
                $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner un objet}}', level: 'danger'});
    }
    return false;
});

$("#bt_removeJeeObject").on('click', function (event) {
    if ($('.li_jeeObject.active').attr('data-jeeObject_id') != undefined) {
        $.hideAlert();
        bootbox.confirm('{{Etes-vous sûr de vouloir supprimer l\'objet}} <span style="font-weight: bold ;">' + $('.li_object.active a').text() + '</span> ?', function (result) {
            if (result) {
                jeedom.jeeObject.remove({
                    id: $('.li_object.active').attr('data-jeeObject_id'),
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function () {
                        modifyWithoutSave = false;
                        loadPage('index.php?v=d&p=jeeObject&removeSuccessFull=1');
                    }
                });
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner un objet}}', level: 'danger'});
    }
    return false;
});

$("#ul_jeeObject").sortable({
    axis: "y",
    cursor: "move",
    items: ".li_jeeObject",
    placeholder: "ui-state-highlight",
    tolerance: "intersect",
    forcePlaceholderSize: true,
    dropOnEmpty: true,
    stop: function (event, ui) {
        var jeeObjects = [];
        $('#ul_jeeObject .li_jeeObject').each(function () {
            jeeObjects.push($(this).attr('data-jeeObject_id'));
        });
        jeedom.jeeObject.setOrder({
            jeeObjects: jeeObjects,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            }
        });
    }
});
$("#ul_jeeObject").sortable("enable");


$('#bt_chooseIcon').on('click', function () {
    chooseIcon(function (_icon) {
        $('.jeeObjectAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
    });
});

if (is_numeric(getUrlVars('id'))) {
    if ($('#ul_jeeObject .li_jeeObject[data-jeeObject_id=' + getUrlVars('id') + ']').length != 0) {
        $('#ul_jeeObject .li_jeeObject[data-jeeObject_id=' + getUrlVars('id') + ']').click();
    } else {
        $('#ul_jeeObject .li_jeeObject:first').click();
    }
} 

$('#div_pageContainer').delegate('.jeeObjectAttr', 'change', function () {
    modifyWithoutSave = true;
});

$('.addSummary').on('click',function(){
    var type = $(this).attr('data-type');
    var el = $('.type'+type);
    addSummaryInfo(el);
});

$('#div_pageContainer').delegate(".listCmdInfo", 'click', function () {
    var el = $(this).closest('.summary').find('.summaryAttr[data-l1key=cmd]');
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function (result) {
        el.value(result.human);
    });
});

$('#div_pageContainer').delegate('.bt_removeSummary', 'click', function () {
    $(this).closest('.summary').remove();
});


function addSummaryInfo(_el, _summary) {
    if (!isset(_summary)) {
        _summary = {};
    }
    var div = '<div class="summary">';
    div += '<div class="form-group">';
    div += '<label class="col-sm-1 control-label">{{Commande}}</label>';
    div += '<div class="col-sm-4 has-success">';
    div += '<div class="input-group">';
    div += '<span class="input-group-btn">';
    div += '<input type="checkbox" class="summaryAttr checkbox-inline" data-l1key="enable" checked title="{{Activer}}" />';
    div += '<a class="btn btn-default bt_removeSummary btn-sm"><i class="fa fa-minus-circle"></i></a>';
    div += '</span>';
    div += '<input class="summaryAttr form-control input-sm" data-l1key="cmd" />';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-sm listCmdInfo btn-success"><i class="fa fa-list-alt"></i></a>';
    div += '</span>';
    div += '</div>';
    div += '</div>';
    div += '<div class="col-sm-2 has-success">';
    div += '<label><input type="checkbox" class="summaryAttr checkbox-inline" data-l1key="invert" />{{Inverser}}</label>';
    div += '</div>';
    div += '</div>';
    _el.find('.div_summary').append(div);
    _el.find('.summary:last').setValues(_summary, '.summaryAttr');
}

$('.bt_showJeeObjectSummary').off('click').on('click', function () {
  $('#md_modal').dialog({title: "{{Résumé Objets}}"});
  $("#md_modal").load('index.php?v=d&modal=jeeObject.summary').dialog('open');
});
