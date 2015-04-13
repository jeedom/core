
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
  $('.div_displayEquipement').packery();
}, 2);



 $('body').delegate('.eqLogic-widget .history', 'click', function () {
  $('#md_modal').dialog({title: "Historique"});
  $("#md_modal").load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
});


 if(!isset(userProfils.displayScenarioByDefault) || userProfils.displayScenarioByDefault != 1){
   $('#bt_displayScenario').on('mouseenter', function () {
     $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-7');
     $('#div_displayScenario').show();
     $('.div_displayEquipement').packery();
   });

   $('#div_displayScenario').on('mouseleave', function () {
    $('#div_displayScenario').hide();
    $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12');
    $('.div_displayEquipement').packery();
  });

 }

 if(!isset(userProfils.displayObjetByDefault) || userProfils.displayObjetByDefault != 1){
   $('#bt_displayObject').on('mouseenter', function () {
    $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8');
    $('#div_displayObjectList').show();
    $('.div_displayEquipement').packery();
  });
   $('#div_displayObjectList').on('mouseleave', function () {
    $('#div_displayObjectList').hide();
    $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12');
    $('.div_displayEquipement').packery();
  });
 }