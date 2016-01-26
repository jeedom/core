
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

 jwerty.key('ctrl+s', function (e) {
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

 $("#table_user").delegate(".del_user", 'click', function (event) {
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

 $("#table_user").delegate(".change_mdp_user", 'click', function (event) {
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

 $("#table_user").on('click',".bt_changeHash",  function (event) {
    $.hideAlert();
    var user = {id: $(this).closest('tr').find('.userAttr[data-l1key=id]').value()};
    bootbox.confirm("{{Etês vous sur de vouloir changer la clef API de l\'utilisateur ?}}", function (result) {
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

 $('body').delegate('.userAttr', 'change', function () {
    modifyWithoutSave = true;
});

 $('body').delegate('.configKey', 'change', function () {
    modifyWithoutSave = true;
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
                var ligne = '<tr><td class="login">';
                ligne += '<span class="userAttr" data-l1key="id" style="display : none;"/>';
                ligne += '<span class="userAttr" data-l1key="login" />';
                ligne += '</td>';
                ligne += '<td><center>';
                if (ldapEnable != '1') {
                    ligne += '<a class="btn btn-xs btn-danger pull-right del_user" style="margin-bottom : 5px;"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>';
                    ligne += '<a class="btn btn-xs btn-warning pull-right change_mdp_user" style="margin-bottom : 5px;"><i class="fa fa-pencil"></i> {{Changer le mot de passe}}</a>';
                }
                ligne += '</center></td>';
                ligne += '<td>';
                ligne += '<input type="checkbox" data-size="mini" class="userAttr bootstrapSwitch" data-l1key="enable" />';
                ligne += '</td>';
                ligne += '<td>';
                ligne += '<input type="checkbox" data-size="mini" class="userAttr bootstrapSwitch" data-label-text="{{Admin}}" data-l1key="rights" data-l2key="admin"/>';
                ligne += '</td>';
                ligne += '<td>';
                ligne += '<a class="cursor bt_changeHash" title="{{Renouveler la clef API}}"><i class="fa fa-refresh"></i></a> <textarea class="userAttr" data-l1key="hash" style="width:100%;" rows="7" disabled></textarea>';
                ligne += '</td>';
                ligne += '<td>';
                if(isset(data[i].options) && isset(data[i].options.twoFactorAuthentification) && data[i].options.twoFactorAuthentification == 1 && isset(data[i].options.twoFactorAuthentificationSecret) && data[i].options.twoFactorAuthentificationSecret != ''){
                    ligne += '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
                }else{
                   ligne += '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
               }
               ligne += '</td>';
               ligne += '<td>';
               ligne += '<span class="userAttr" data-l1key="options" data-l2key="lastConnection"></span>';
               ligne += '</td>';
               ligne += '</tr>';
               var result = $(ligne);
               result.setValues(data[i], '.userAttr');
               tr.push(result);
           }
           $('#table_user tbody').append(tr);
           modifyWithoutSave = false;
           initCheckBox();
           $.hideLoading();
       }
   });
}