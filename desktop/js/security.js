
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

$("#security_tab").delegate('a', 'click', function(event) {
    $(this).tab('show');
    $.hideAlert();
});

if (getUrlVars('removeSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'});
}

if (getUrlVars('saveSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
}

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $('#bt_saveSecurityConfig').click();
});

$('#bt_saveSecurityConfig').on('click', function() {
    jeedom.config.save({
        configuration: $('#config').getValues('.configKey')[0],
        error: function(error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function() {
            jeedom.config.load({
                configuration: $('#config').getValues('.configKey')[0],
                error: function(error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function(data) {
                    $('#config').setValues(data, '.configKey');
                    modifyWithoutSave = false;
                    $('#div_alert').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
                }
            });
        }
    });
});

$('#table_security').delegate('.remove', 'click', function() {
    var tr = $(this).closest('tr');
    bootbox.confirm("{{Etes-vous sûr de vouloir supprimer cette connexion ? Si l\'IP :}} " + tr.find('.ip').text() + " {{était banni celle-ci ne le sera plus}}", function(result) {
        if (result) {
            jeedom.security.remove({
                id: tr.attr('data-id'),
                error: function(error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function() {
                    modifyWithoutSave = false;
                    window.location.replace('index.php?v=d&p=security&removeSuccessFull=1');
                }
            });
        }
    });
});

$('#table_security').delegate('.ban', 'click', function() {
    var tr = $(this).closest('tr');
    bootbox.confirm("{{Etes-vous sûr de vouloir bannir cette IP  :}} " + tr.find('.ip').text() + " ?", function(result) {
        if (result) {
            jeedom.security.ban({
                id: tr.attr('data-id'),
                error: function(error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function() {
                    modifyWithoutSave = false;
                    window.location.replace('index.php?v=d&p=security&saveSuccessFull=1');
                }
            });
        }
    });
});

$.showLoading();
jeedom.config.load({
    configuration: $('#config').getValues('.configKey')[0],
    success: function(data) {
        $('#config').setValues(data, '.configKey');
        modifyWithoutSave = false;
    }
});

$('body').delegate('.configKey', 'change', function() {
    modifyWithoutSave = true;
});