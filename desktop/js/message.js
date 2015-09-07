
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


$("#sel_plugin").on('change', function() {
    window.location = 'index.php?v=d&p=message&plugin=' + $('#sel_plugin').value();
});

$("#bt_clearMessage").on('click', function(event) {
    jeedom.message.clear({
        plugin: $('#sel_plugin').value(),
        error: function(error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function() {
            $("#table_message tbody").remove();
        }
    });
});

$("#table_message").delegate(".removeMessage", 'click', function(event) {
    var tr = $(this).closest('tr');
    jeedom.message.remove({
        id: tr.attr('data-message_id'),
        error: function(error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function() {
            tr.remove();
            $("#table_message").trigger("update");
        }
    });
});