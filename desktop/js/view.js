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

 if (view_id != '') {
    jeedom.view.toHtml({
        id: view_id,
        version: 'dashboard',
        useCache: true,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (html) {
            $('.div_displayView:last').empty().html(html.html);
            setTimeout(function () {
                positionEqLogic();
                $('.div_displayEquipement').disableSelection();
                $( "input").click(function() { $(this).focus(); });
                $( "textarea").click(function() { $(this).focus(); });
                $('.eqLogicZone').each(function () {
                    var container = $(this).packery({
                        columnWidth: 40,
                        rowHeight: 80,
                        gutter : 2,
                    });
                });
                initTooltips();
            }, 10);
        }
    });
}

$('body').delegate('.eqLogic-widget .history', 'click', function () {
    $('#md_modal2').dialog({title: "Historique"});
    $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
});

$('.bt_displayView').on('click', function () {
    if ($(this).attr('data-display') == 1) {
        $(this).closest('.row').find('.div_displayViewList').hide();
        $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12');
        $(this).closest('.row').find('.div_displayEquipement').each(function () {
            $(this).packery();
        });
        $(this).attr('data-display', 0);
    } else {
        $(this).closest('.row').find('.div_displayViewList').show();
        $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8');
        $(this).closest('.row').find('.div_displayEquipement').packery();
        $(this).attr('data-display', 1);
    }
});