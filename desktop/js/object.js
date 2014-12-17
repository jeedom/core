
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

$(".li_object").on('click', function(event) {
    $('#div_conf').show();
    $('.li_object').removeClass('active');
    $(this).addClass('active');
    jeedom.object.byId({
        id: $(this).attr('data-object_id'),
        cache: false,
        error: function(error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function(data) {
            $('.objectAttr').value('');
            $('.objectAttr[data-l1key=father_id] option').show();
            $('.object').setValues(data, '.objectAttr');
            $('.objectAttr[data-l1key=father_id] option[value=' + data.id + ']').hide();
            modifyWithoutSave = false;
        }
    });
    return false;
});

$("#bt_addObject").on('click', function(event) {
    bootbox.prompt("Nom de l'objet ?", function(result) {
        if (result !== null) {
            jeedom.object.save({
                object: {name: result, isVisible: 1},
                error: function(error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function(data) {
                    modifyWithoutSave = false;
                    window.location.replace('index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1');
                }
            });
        }
    });
});

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveObject").click();
});

$("#bt_saveObject").on('click', function(event) {
    if ($('.li_object.active').attr('data-object_id') != undefined) {
        jeedom.object.save({
            object: $('.object').getValues('.objectAttr')[0],
            error: function(error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            },
            success: function(data) {
                modifyWithoutSave = false;
                window.location.replace('index.php?v=d&p=object&id=' + data.id + '&saveSuccessFull=1');
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner un objet}}', level: 'danger'});
    }
    return false;
});

$("#bt_removeObject").on('click', function(event) {
    if ($('.li_object.active').attr('data-object_id') != undefined) {
        $.hideAlert();
        bootbox.confirm('{{Etes-vous sûr de vouloir supprimer l\'objet}} <span style="font-weight: bold ;">' + $('.li_object.active a').text() + '</span> ?', function(result) {
            if (result) {
                jeedom.object.remove({
                    id: $('.li_object.active').attr('data-object_id'),
                    error: function(error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function() {
                        modifyWithoutSave = false;
                        window.location.replace('index.php?v=d&p=object&removeSuccessFull=1');
                    }
                });
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner un objet}}', level: 'danger'});
    }
    return false;
});

$('body').delegate('.bt_sortable', 'mouseenter', function() {
    $("#ul_object").sortable({
        axis: "y",
        cursor: "move",
        items: ".li_object",
        placeholder: "ui-state-highlight",
        tolerance: "intersect",
        forcePlaceholderSize: true,
        dropOnEmpty: true,
        stop: function(event, ui) {
            var objects = [];
            $('#ul_object .li_object').each(function() {
                objects.push($(this).attr('data-object_id'));
            });
            jeedom.object.setOrder({
                objects: objects,
                error: function(error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                }
            });
        }
    });
    $("#ul_object").sortable("enable");
});

$('body').delegate('.bt_sortable', 'mouseout', function() {
    $("#ul_object").sortable("disable");
});

$('#bt_chooseIcon').on('click', function() {
    chooseIcon(function(_icon) {
        $('.objectAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
    });
});

if (is_numeric(getUrlVars('id'))) {
    if ($('#ul_object .li_object[data-object_id=' + getUrlVars('id') + ']').length != 0) {
        $('#ul_object .li_object[data-object_id=' + getUrlVars('id') + ']').click();
    } else {
        $('#ul_object .li_object:first').click();
    }
} else {
    $('#ul_object .li_object:first').click();
}

$('body').delegate('.objectAttr', 'change', function() {
    modifyWithoutSave = true;
});