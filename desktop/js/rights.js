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
loadRights();

$('#sel_userId').on('change', function () {
    loadRights();
});

$('#bt_saveRights').on('click', function () {
    console.log($('.rights').getValues('.rightsAttr'));
    jeedom.rights.save({
        rights: $('.rights').getValues('.rightsAttr'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alert').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
            loadRights();
        }
    });
});


function loadRights() {
    jeedom.rights.byUserId({
        user_id: $('#sel_userId').value(),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('.rightsAttr[data-l1key=right]').value(1);
            for (var i in data) {
                var rights = $('.rightsAttr[data-l1key=entity][value=' + data[i].entity + ']').closest('.rights');
                if (rights != undefined) {
                    rights.find('.rightsAttr[data-l1key=id]').value(data[i].id);
                    rights.find('.rightsAttr[data-l1key=right]').value(data[i].right);
                }
            }
            $('.rightsAttr[data-l1key=user_id]').value($('#sel_userId').value());
        }
    });
}