
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
positionEqLogic();
setTimeout(function () {
    $('.div_displayEquipement').masonry({columnWidth: 1});
}, 2);

$('body').delegate('.eqLogic-widget .history', 'click', function () {
    $('#md_modal').dialog({title: "{{Historique}}"});
    $("#md_modal").load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
});

$('#bt_displayScenario').on('click', function () {
    if ($(this).attr('data-display') == 1) {
        $('#div_displayScenario').hide();
        if ($('#bt_displayObject').attr('data-display') == 1) {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-10');
        } else {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-12');
        }
        $('.div_displayEquipement').each(function () {
            $(this).masonry({columnWidth: 1});
        });
        $(this).attr('data-display', 0);
    } else {
        $('#div_displayScenario').show();
        if ($('#bt_displayObject').attr('data-display') == 1) {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-8');
        } else {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-10');
        }
        $('.div_displayEquipement').masonry({columnWidth: 1});
        $(this).attr('data-display', 1);
    }
});

$('#bt_displayObject').on('click', function () {
    if ($(this).attr('data-display') == 1) {
        $('#div_displayObjectList').hide();
        if ($('#bt_displayScenario').attr('data-display') == 1) {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-10');
        } else {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-12');
        }
        $('.div_displayEquipement').each(function () {
            $(this).masonry({columnWidth: 1});
        });
        $(this).attr('data-display', 0);
    } else {
        $('#div_displayObjectList').show();
        if ($('#bt_displayScenario').attr('data-display') == 1) {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-8');
        } else {
            $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12').addClass('col-lg-10');
        }
        $('.div_displayEquipement').masonry({columnWidth: 1});
        $(this).attr('data-display', 1);
    }
});