
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

$('.alertListContainer .jeedomAlreadyPosition').removeClass('jeedomAlreadyPosition');
$('.batteryListContainer, .alertListContainer').packery({
  itemSelector: ".eqLogic-widget",
  gutter : 2
});

$('.alerts, .batteries').on('click',function(){
  setTimeout(function(){
    positionEqLogic();
    $('.batteryListContainer, .alertListContainer').packery({
      itemSelector: ".eqLogic-widget",
      gutter : 2
    });
  }, 10);
});

$('.cmdAction[data-action=configure]').on('click', function () {
  $('#md_modal').dialog({title: "{{Configuration commande}}"});
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-cmd_id')).dialog('open');
});

//searching
$('#in_search').off('keyup').on('keyup',function(){
  if ($('.batteryListContainer .eqLogic-widget').length == 0) {
    return
  }
  var search = $(this).value()
  if (search == '') {
    $('.batteryListContainer .eqLogic-widget').show()
    $('.batteryListContainer').packery()
    return
  }
  search = normTextLower(search)

  $('.batteryListContainer .eqLogic-widget').each(function() {
    var match = false

    text = normTextLower($(this).find('.widget-name').text())
    if (text.indexOf(search) >= 0) match = true

    text = normTextLower($(this).find('.widget-name span').text())
    if (text.indexOf(search) >= 0) match = true

    if(match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  });
  $('.batteryListContainer').packery();
});

$('#bt_resetSearch').on('click', function () {
  $('#in_search').val('')
  $('#in_search').keyup();
})


$(function() {
  //tabs icons colors:
  if ($('.batteryListContainer div.eqLogic-widget.critical').length) {
    $('a[href="#battery"] > i').addClass('danger')
  } else if ($('.batteryListContainer div.eqLogic-widget.warning').length) {
    $('a[href="#battery"] > i').addClass('warning')
  } else {
    $('a[href="#battery"] > i').addClass('success')
  }

  if ($('.alertListContainer div.eqLogic-widget').length) {
    $('a[href="#alertEqlogic"] > i').addClass('warning')
  }

  if ($('#deadCmd #table_deadCmd > tbody > tr').length) {
    $('a[href="#deadCmd"] > i').addClass('warning')
  }
})
