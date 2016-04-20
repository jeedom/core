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

 jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $("#bt_saveRights").click();
});


 $('#bt_saveRights').on('click', function () {
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

 $('.toggleRight').on('click', function () {
    var state = false;
    if ($(this).attr('data-state') == 0) {
        state = true;
        $(this).attr('data-state', 1);
        $(this).find('i').removeClass('fa-circle-o').addClass('fa-check-circle-o');
    } else {
        state = false;
        $(this).attr('data-state', 0);
        $(this).find('i').removeClass('fa-check-circle-o').addClass('fa-circle-o');
    }
    $('.tab-pane.active .rightsAttr[data-l1key=right][data-type='+$(this).attr('data-type')+']').each(function () {
        if ($(this).is(':visible')) {
            $(this).prop('checked', state);
        }
    });
});


 function loadRights() {
    $('.rightsAttr[data-l1key=id]').value('');
    $('.rightsAttr[data-l1key=right]').value(1);
    jeedom.rights.byUserId({
        user_id: $('#sel_userId').value(),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
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