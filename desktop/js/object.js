
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
    $('#sd_objectList').hide();
    $('#div_resumeObjectList').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
    $('#div_conf').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');

    $('#bt_displayObject').on('mouseenter',function(){
       var timer = setTimeout(function(){
        $('#bt_displayObject').find('i').hide();
        $('#div_resumeObjectList').addClass('col-lg-10 col-md-10 col-sm-9').removeClass('col-lg-12');
        $('#div_conf').addClass('col-lg-10 col-md-10 col-sm-9').removeClass('col-lg-12');
        $('#sd_objectList').show();
        $('.objectListContainer').packery();
    }, 100);
       $(this).data('timerMouseleave', timer)
   }).on("mouseleave", function(){
      clearTimeout($(this).data('timerMouseleave'));
  });

   $('#sd_objectList').on('mouseleave',function(){
    var timer = setTimeout(function(){
       $('#sd_objectList').hide();
       $('#bt_displayObject').find('i').show();
       $('#div_resumeObjectList').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
       $('#div_conf').removeClass('col-lg-10 col-md-10 col-sm-9').addClass('col-lg-12');
       $('.objectListContainer').packery();
   }, 300);
    $(this).data('timerMouseleave', timer);
}).on("mouseenter", function(){
  clearTimeout($(this).data('timerMouseleave'));
});
}



setTimeout(function(){
  $('.objectListContainer').packery();
},100);

$('#bt_returnToThumbnailDisplay').on('click',function(){
    $('#div_conf').hide();
    $('#div_resumeObjectList').show();
    $('.objectListContainer').packery();
});

$(".li_object,.objectDisplayCard").on('click', function (event) {
    $('#div_conf').show();
    $('#div_resumeObjectList').hide();
    $('.li_object').removeClass('active');
    $(this).addClass('active');
    $('.li_object[data-object_id='+$(this).attr('data-object_id')+']').addClass('active');
    jeedom.object.byId({
        id: $(this).attr('data-object_id'),
        cache: false,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.objectAttr').value('');
            $('.objectAttr[data-l1key=display][data-l2key=tagColor]').value('#9b59b6');
            $('.objectAttr[data-l1key=display][data-l2key=tagTextColor]').value('#ffffff');
            $('.objectAttr[data-l1key=father_id] option').show();
            $('.object').setValues(data, '.objectAttr');
            $('.objectAttr[data-l1key=father_id] option[value=' + data.id + ']').hide();
            modifyWithoutSave = false;
        }
    });
    return false;
});

$("#bt_addObject,#bt_addObject2").on('click', function (event) {
    bootbox.prompt("Nom de l'objet ?", function (result) {
        if (result !== null) {
            jeedom.object.save({
                object: {name: result, isVisible: 1},
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    modifyWithoutSave = false;
                     loadPage('index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1');
                }
            });
        }
    });
});

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveObject").click();
});

$('.objectAttr[data-l1key=display][data-l2key=icon]').on('dblclick',function(){
    $('.objectAttr[data-l1key=display][data-l2key=icon]').value('');
});

$("#bt_saveObject").on('click', function (event) {
    if ($('.li_object.active').attr('data-object_id') != undefined) {
        jeedom.object.save({
            object: $('.object').getValues('.objectAttr')[0],
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                modifyWithoutSave = false;
                 loadPage('index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1');
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner une pièce}}', level: 'danger'});
    }
    return false;
});

$("#bt_removeObject").on('click', function (event) {
    if ($('.li_object.active').attr('data-object_id') != undefined) {
        $.hideAlert();
        bootbox.confirm('{{Etes-vous sûr de vouloir supprimer la pièce}} <span style="font-weight: bold ;">' + $('.li_object.active a').text() + '</span> ?', function (result) {
            if (result) {
                jeedom.object.remove({
                    id: $('.li_object.active').attr('data-object_id'),
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function () {
                        modifyWithoutSave = false;
                         loadPage('index.php?v=d&p=object&removeSuccessFull=1');
                    }
                });
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner une pièce}}', level: 'danger'});
    }
    return false;
});

$("#ul_object").sortable({
    axis: "y",
    cursor: "move",
    items: ".li_object",
    placeholder: "ui-state-highlight",
    tolerance: "intersect",
    forcePlaceholderSize: true,
    dropOnEmpty: true,
    stop: function (event, ui) {
        var objects = [];
        $('#ul_object .li_object').each(function () {
            objects.push($(this).attr('data-object_id'));
        });
        jeedom.object.setOrder({
            objects: objects,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            }
        });
    }
});
$("#ul_object").sortable("enable");


$('#bt_chooseIcon').on('click', function () {
    chooseIcon(function (_icon) {
        $('.objectAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
    });
});

if (is_numeric(getUrlVars('id'))) {
    if ($('#ul_object .li_object[data-object_id=' + getUrlVars('id') + ']').length != 0) {
        $('#ul_object .li_object[data-object_id=' + getUrlVars('id') + ']').click();
    } else {
        $('#ul_object .li_object:first').click();
    }
} 

$('body').delegate('.objectAttr', 'change', function () {
    modifyWithoutSave = true;
});