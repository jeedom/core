/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

$('.backgroundforJeedom').css({
  'background-position':'bottom right',
  'background-repeat':'no-repeat',
  'background-size':'auto'
})

$(function(){
  $('#display').show()
  $('#in_search').focus()
})

//searching
$('#in_search').on('keyup',function(){
  try {
    search = $(this).value()
    $(".accordion-toggle[aria-expanded='true']").click()
    $('.cmd').show().removeClass('alert-success').addClass('alert-info')
    $('.eqLogic').show()
    $('.cmdSortable').hide()
    if (!search.startsWith('*')) {
      if(search == '' || _nbCmd_ <= 1500 && search.length < 3 || _nbCmd_ > 1500 && search.length < 4) {
        return
      }
    } else {
      if(search == '*') return
      search = search.substr(1)
    }

    search = normTextLower(search)
    $('.eqLogic').each(function(){
      var eqLogic = $(this)
      var name = eqLogic.attr('data-name')
      name = normTextLower(name)
      var type = eqLogic.attr('data-type')
      type = normTextLower(type)
      eqParent = eqLogic.parents('.panel.panel-default').first()
      if (name.indexOf(search) < 0 && type.indexOf(search) < 0) {
        eqLogic.hide()
      } else {
        eqParent.find('.accordion-toggle[aria-expanded="false"]').click()
      }
      $(this).find('.cmd').each(function() {
        var cmd = $(this)
        var name = cmd.attr('data-name')
        name = normTextLower(name)
        if (name.indexOf(search) >= 0) {
          eqParent.find('.accordion-toggle[aria-expanded="false"]').click()
          eqLogic.show()
          eqLogic.find('.cmdSortable').show()
          cmd.removeClass('alert-warning').addClass('alert-success')
        }
      })
    })
  }
  catch(error) {
    console.error(error)
  }
})
$('#bt_resetdisplaySearch').on('click', function () {
  $('#in_search').val('').keyup()
})
$('#bt_openAll').off('click').on('click', function () {
  $(".accordion-toggle[aria-expanded='false']").click()
})
$('#bt_closeAll').off('click').on('click', function () {
  $(".accordion-toggle[aria-expanded='true']").click()
})

//Sorting:
$('#accordionObject').sortable({
  cursor: "move",
  items: ".objectSortable",
  stop: function (event, ui) {
    var objects = []
    $('.objectSortable .panel-heading').each(function () {
      objects.push($(this).attr('data-id'))
    })
    jeedom.object.setOrder({
      objects: objects,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      }
    })
  }
}).disableSelection()

$('.eqLogicSortable').sortable({
  cursor: "move",
  connectWith: ".eqLogicSortable",
  stop: function (event, ui) {
    var eqLogics = []
    var object = ui.item.closest('.objectSortable')
    objectId = object.find('.panel-heading').attr('data-id')
    order = 1
    object.find('.eqLogic').each(function(){
      eqLogic = {}
      eqLogic.object_id = objectId
      eqLogic.id = $(this).attr('data-id')
      eqLogic.order = order
      eqLogics.push(eqLogic)
      order++
    })
    jeedom.eqLogic.setOrder({
      eqLogics: eqLogics,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
        $( ".eqLogicSortable" ).sortable( "cancel" )
      }
    })
    $(event.originalEvent.target).click()
  }
}).disableSelection()

$('.cmdSortable').sortable({
  cursor: "move",
  stop: function (event, ui) {
    var cmds = []
    var eqLogic = ui.item.closest('.eqLogic')
    order = 1
    eqLogic.find('.cmd').each(function(){
      cmd = {}
      cmd.id = $(this).attr('data-id')
      cmd.order = order
      cmds.push(cmd)
      order++
    })
    jeedom.cmd.setOrder({
      cmds: cmds,
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      }
    })
  }
}).disableSelection()

//Modals:
$('.configureObject').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration de l'objet}}"})
  $('#md_modal').load('index.php?v=d&modal=object.configure&object_id=' + $(this).closest('.panel-heading').attr('data-id')).dialog('open')
})

$('.configureEqLogic').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration de l'équipement}}"})
  $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $(this).closest('.eqLogic').attr('data-id')).dialog('open')
})

$('.configureCmd').on('click',function() {
  $('#md_modal').dialog({title: "{{Configuration de la commande}}"})
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-id')).dialog('open')
})


//events:
$('.eqLogicSortable > li.eqLogic').on('click',function(event) {
  if (event.target.tagName.toUpperCase() == 'I') return
  //checkbox clicked:
  if (event.target.tagName.toUpperCase() == 'INPUT') return
  //cmd cliked inside li:
  if ($(event.target).hasClass('cmd')) {
    $(this).find('.configureCmd').click()
    return false
  }

  if (!$(event.target).hasClass('eqLogic')) {
    event.stopPropagation()
    return false
  }
  $el = $(this).find('ul.cmdSortable')
  if ($el.is(':visible')) {
    $el.hide()
  } else {
    $el.show()
  }
})

$('#cb_actifDisplay').on('change',function() {
  if ($(this).value() == 1) {
    $('.eqLogic[data-enable=0]').show()
  } else {
    $('.eqLogic[data-enable=0]').hide()
  }
})

$('.cb_selEqLogic').on('change',function(){
  var found = false
  $('.cb_selEqLogic').each(function() {
    if ($(this).value() == 1) {
      found = true
    }
  })
  if (found) {
    $('#bt_removeEqlogic').show()
    $('.bt_setIsVisible').show()
    $('.bt_setIsEnable').show()
  } else {
    $('#bt_removeEqlogic').hide()
    $('.bt_setIsVisible').hide()
    $('.bt_setIsEnable').hide()
  }
})

$('#bt_removeEqlogic').on('click',function(){
  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer tous ces équipements ?}}', function (result) {
    if (result) {
      var eqLogics = []
      $('.cb_selEqLogic').each(function(){
        if($(this).value() == 1){
          eqLogics.push($(this).closest('.eqLogic').attr('data-id'))
        }
      })
      jeedom.eqLogic.removes({
        eqLogics: eqLogics,
        error: function (error) {
          $('#div_alert').showAlert({message: error.message, level: 'danger'})
        },
        success : function(){
          loadPage('index.php?v=d&p=display')
        }
      })
    }
  })
})

$('.bt_setIsVisible').on('click',function(){
  var eqLogics = []
  $('.cb_selEqLogic').each(function(){
    if($(this).value() == 1){
      eqLogics.push($(this).closest('.eqLogic').attr('data-id'))
    }
  })
  jeedom.eqLogic.setIsVisibles({
    eqLogics: eqLogics,
    isVisible : $(this).attr('data-value'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success : function(){
      loadPage('index.php?v=d&p=display')
    }
  })
})

$('.bt_setIsEnable').on('click',function(){
  var eqLogics = []
  $('.cb_selEqLogic').each(function(){
    if($(this).value() == 1){
      eqLogics.push($(this).closest('.eqLogic').attr('data-id'))
    }
  })
  jeedom.eqLogic.setIsEnables({
    eqLogics: eqLogics,
    isEnable : $(this).attr('data-value'),
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'})
    },
    success : function(){
      loadPage('index.php?v=d&p=display')
    }
  })
})

$('#bt_removeHistory').on('click',function(){
  $('#md_modal').dialog({title: "{{Historique des suppressions}}"})
  $('#md_modal').load('index.php?v=d&modal=remove.history').dialog('open')
})

$('#bt_emptyRemoveHistory').on('click',function(){
  jeedom.emptyRemoveHistory({
    error: function (error) {
      $('#div_alertRemoveHistory').showAlert({message: error.message, level: 'danger'})
    },
    success: function (data) {
      $('#table_removeHistory tbody').empty()
      $('#div_alertRemoveHistory').showAlert({message: '{{Historique vidé avec succès}}', level: 'success'})
    }
  })
})
