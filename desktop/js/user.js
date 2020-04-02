
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

 printUsers();
 $("#bt_addUser").on('click', function (event) {
    $.hideAlert();
    $('#in_newUserLogin').value('');
    $('#in_newUserMdp').value('');
    $('#md_newUser').modal('show');
});

 $("#bt_newUserSave").on('click', function (event) {
    $.hideAlert();
    var user = [{login: $('#in_newUserLogin').value(), password: $('#in_newUserMdp').value()}];
    jeedom.user.save({
        users: user,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            printUsers();
            $('#div_alert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
            modifyWithoutSave = false;
            $('#md_newUser').modal('hide');
        }
    });
});

 jwerty.key('ctrl+s/⌘+s', function (e) {
    e.preventDefault();
    $('#bt_saveUser').click();
});

 $("#bt_saveUser").on('click', function (event) {
    jeedom.user.save({
        users: $('#table_user tbody tr').getValues('.userAttr'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            printUsers();
            $('#div_alert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
            modifyWithoutSave = false;
        }
    });
});

 $("#table_user").on('click',".bt_del_user",  function (event) {
    $.hideAlert();
    var user = {id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value()};
    bootbox.confirm('{{Etes-vous sûr de vouloir supprimer cet utilisateur ?}}', function (result) {
        if (result) {
            jeedom.user.remove({
                id: user.id,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    printUsers();
                    $('#div_alert').showAlert({message: '{{L\'utilisateur a bien été supprimé}}', level: 'success'});
                }
            });
        }
    });
});

 $("#table_user").on( 'click',".bt_change_mdp_user", function (event) {
    $.hideAlert();
    var user = {id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value(), login: $(this).closest('tr').find('.userAttr[data-l1key=login]').value()};
    bootbox.prompt("{{Quel est le nouveau mot de passe ?}}", function (result) {
        if (result !== null) {
            user.password = result;
            jeedom.user.save({
                users: [user],
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    printUsers();
                    $('#div_alert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
                    modifyWithoutSave = false;
                }
            });
        }
    });
});

 $("#table_user").on( 'click',".bt_changeHash", function (event) {
    $.hideAlert();
    var user = {id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value()};
    bootbox.confirm("{{Etes-vous sûr de vouloir changer la clef API de l\'utilisateur ?}}", function (result) {
        if (result) {
            user.hash = '';
            jeedom.user.save({
                users: [user],
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                    printUsers();
                    $('#div_alert').showAlert({message: '{{Modification effectuée}}', level: 'success'});
                    modifyWithoutSave = false;
                }
            });
        }
    });
});

 $('#div_pageContainer').on('change','.userAttr',  function () {
    modifyWithoutSave = true;
});

 $('#div_pageContainer').on('change','.configKey',  function () {
    modifyWithoutSave = true;
});

 $('#bt_supportAccess').on('click',function(){
    jeedom.user.supportAccess({
        enable : $(this).attr('data-enable'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            modifyWithoutSave = false;
            window.location.reload();
        }
    });
});

 function printUsers() {
    $.showLoading();
    jeedom.user.all({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('#table_user tbody').empty();
            var tr = [];
            for (var i in data) {
                var disable = '';
                if(data[i].login == 'internal_report' || data[i].login == 'jeedom_support'){
                    disable = 'disabled';
                }
                var ligne = '<tr><td class="login">';
                ligne += '<span class="userAttr" data-l1key="id" style="display : none;"/>';
                ligne += '<span class="userAttr" data-l1key="login" />';
                ligne += '</td>';
                ligne += '<td>';
                ligne += '<label><input type="checkbox" class="userAttr" data-l1key="enable" '+disable+' />{{Actif}}</label><br/>';
                ligne += '<label><input type="checkbox" class="userAttr" data-l1key="options" data-l2key="localOnly" '+disable+' />{{Local}}</label>';
                if(data[i].profils == 'admin'){
                    ligne += '<br/><label><input type="checkbox" class="userAttr" data-l1key="options" data-l2key="doNotRotateHash" '+disable+' />{{Ne pas faire de rotation clef api}}</label>';
                }
                ligne += '</td>';
                ligne += '<td style="width:175px;">';
                ligne += '<select class="userAttr form-control input-sm" data-l1key="profils" '+disable+'>';
                ligne += '<option value="admin">{{Administrateur}}</option>';
                ligne += '<option value="user">{{Utilisateur}}</option>';
                ligne += '<option value="restrict">{{Utilisateur limité}}</option>';
                ligne += '</select>';
                ligne += '</td>';
                ligne += '<td style="width:320px">';
                ligne += '<input class="userAttr form-control input-sm" data-l1key="hash" disabled />';
                ligne += '</td>';
                ligne += '<td>';
                if(isset(data[i].options) && isset(data[i].options.twoFactorAuthentification) && data[i].options.twoFactorAuthentification == 1 && isset(data[i].options.twoFactorAuthentificationSecret) && data[i].options.twoFactorAuthentificationSecret != ''){
                    ligne += '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
                    ligne += ' <a class="btn btn-danger btn-sm bt_disableTwoFactorAuthentification"><i class="fas fa-times"></i> {{Désactiver}}</span>';
                }else{
                   ligne += '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
               }
               ligne += '</td>';
               ligne += '<td>';
               ligne += '<span class="userAttr" data-l1key="options" data-l2key="lastConnection"></span>';
               ligne += '</td>';
               ligne += '<td>';
               if(disable == ''){
                   ligne += '<a class="cursor bt_changeHash btn btn-warning btn-xs pull-right" title="{{Renouveler la clef API}}"><i class="fas fa-refresh"></i> {{Régénérer API}}</a>';
                   if (ldapEnable != '1') {
                    ligne += '<a class="btn btn-xs btn-danger pull-right bt_del_user" style="margin-bottom : 5px;"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>';
                    ligne += '<a class="btn btn-xs btn-warning pull-right bt_change_mdp_user" style="margin-bottom : 5px;"><i class="fas fa-pencil-alt"></i> {{Mot de passe}}</a>';
                }
                ligne += '<a class="btn btn-xs btn-warning pull-right bt_manage_restrict_rights" style="margin-bottom : 5px;"><i class="fas fa-align-right"></i> {{Droits}}</a>';
            }
            ligne += '</td>';
            ligne += '</tr>';
            var result = $(ligne);
            result.setValues(data[i], '.userAttr');
            tr.push(result);
        }
        $('#table_user tbody').append(tr);
        modifyWithoutSave = false;
        $.hideLoading();
    }
});
}

$('#table_user').on( 'click','.bt_manage_restrict_rights', function () {
    $('#md_modal').dialog({title: "Gestion des droits"});
    $("#md_modal").load('index.php?v=d&modal=user.rights&id=' + $(this).closest('tr').find('.userAttr[data-l1key=id]').value()).dialog('open');
});


$('#table_user').on( 'click', '.bt_disableTwoFactorAuthentification',function () {
    jeedom.user.removeTwoFactorCode({
        id :  $(this).closest('tr').find('.userAttr[data-l1key=id]').value(),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            printUsers();
        }
    }); 

});

$('.bt_deleteSession').on('click',function(){
 var id = $(this).closest('tr').attr('data-id'); 
 jeedom.user.deleteSession({
    id : id,
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        window.location.reload();
    }
});
});


$('.bt_removeRegisterDevice').on('click',function(){
    var key = $(this).closest('tr').attr('data-key');
    var user_id = $(this).closest('tr').attr('data-user_id');
    jeedom.user.removeRegisterDevice({
        key : key,
        user_id : user_id,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            modifyWithoutSave = false;
            window.location.reload();
        }
    });
});

$('#bt_removeAllRegisterDevice').on('click',function(){
    jeedom.user.removeRegisterDevice({
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            modifyWithoutSave = false;
            window.location.reload();
        }
    });
});
