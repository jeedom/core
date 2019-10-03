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
var GLOBAL_ACTION_MODE = null
$('.backgroundforJeedom').css({
  'background-position':'bottom right',
  'background-repeat':'no-repeat',
  'background-size':'auto'
})

$(function(){
  if (getDeviceType()['type'] == 'desktop') $('#in_search').focus()
})

//searching
$('#in_search').on('keyup',function() {
  try {
    var search = $(this).value()
    var searchID = search
    if (isNaN(search)) searchID = false
    
    $('div.panel-collapse').removeClass('in')
    $('.cmd').show().removeClass('alert-success').addClass('alert-info')
    $('.eqLogic').show()
    $('.cmdSortable').hide()
    if (!search.startsWith('*') && searchID == false) {
      if( (search == '' || _nbCmd_ <= 1500 && search.length < 3) || (_nbCmd_ > 1500 && search.length < 4) ) {
        return
      }
    } else {
      if(search == '*') return
      search = search.substr(1)
    }
    search = normTextLower(search)
    $('.eqLogic').each(function(){
      var eqLogic = $(this)
      var eqParent = eqLogic.parents('.panel.panel-default').first()
      if (searchID) {
        var eqId = eqLogic.attr('data-id')
        if (eqId != searchID) {
          eqLogic.hide()
        } else {
          eqParent.find('div.panel-collapse').addClass('in')
          return
        }
        $(this).find('.cmd').each(function() {
          var cmd = $(this)
          var cmdId = cmd.attr('data-id')
          if (cmdId == searchID) {
            eqParent.find('div.panel-collapse').addClass('in')
            eqLogic.show()
            eqLogic.find('.cmdSortable').show()
            cmd.removeClass('alert-warning').addClass('alert-success')
            return
          }
        })
      } else {
        var eqName = eqLogic.attr('data-name')
        eqName = normTextLower(eqName)
        var type = eqLogic.attr('data-type')
        type = normTextLower(type)
        if (eqName.indexOf(search) < 0 && type.indexOf(search) < 0) {
          eqLogic.hide()
        } else {
          eqParent.find('div.panel-collapse').addClass('in')
        }
        eqLogic.find('.cmd').each(function() {
          var cmd = $(this)
          var cmdName = cmd.attr('data-name')
          cmdName = normTextLower(cmdName)
          if (cmdName.indexOf(search) >= 0) {
            eqParent.find('div.panel-collapse').addClass('in')
            eqLogic.show()
            eqLogic.find('.cmdSortable').show()
            cmd.removeClass('alert-warning').addClass('alert-success')
          }
        })
      }
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
  if(event.ctrlKey) {
    $('.cmdSortable').show()
  }
})
$('#bt_closeAll').off('click').on('click', function () {
  $(".accordion-toggle[aria-expanded='true']").click()
  if(event.ctrlKey) {
    $('.cmdSortable').hide()
  }
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
$('.configureObject').off('click').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration de l'objet}}"})
  $('#md_modal').load('index.php?v=d&modal=object.configure&object_id=' + $(this).closest('.panel-heading').attr('data-id')).dialog('open')
})

$('.configureEqLogic').off('click').on('click',function(){
  $('#md_modal').dialog({title: "{{Configuration de l'équipement}}"})
  $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $(this).closest('.eqLogic').attr('data-id')).dialog('open')
})

$('.configureCmd').off('click').on('click',function() {
  $('#md_modal').dialog({title: "{{Configuration de la commande}}"})
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-id')).dialog('open')
})

$('.cmd').off('dblclick').on('dblclick',function() {
  $('#md_modal').dialog({title: "{{Configuration de la commande}}"})
  $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open')
})


//events:
$('.bt_exportcsv').on('click',function() {
  var fullFile = ''
  $('.eqLogic').each(function(){
    var eqLogic = $(this)
    var eqParent = eqLogic.parents('.panel.panel-default').first()
    eqParent = eqParent.find('a.accordion-toggle').text()
    fullFile += eqParent + ','  + eqLogic.attr('data-id') + ',' + eqLogic.attr('data-name') + ',' + eqLogic.attr('data-type') + "\n"
    eqLogic.find('.cmd').each(function() {
      var cmd = $(this)
      fullFile += "\t\t" + cmd.attr('data-id') + ',' + cmd.attr('data-name') + "\n"
    })
  })
  $('.bt_exportcsv').attr('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(fullFile))
})

$('.eqLogicSortable > li.eqLogic').on('click',function(event) {
  if (event.target.tagName.toUpperCase() == 'I') return
  //checkbox clicked:
  if (event.target.tagName.toUpperCase() == 'INPUT') return
  //cmd cliked inside li:
  if ($(event.target).hasClass('cmd')) {
    $(event.target).find('.configureCmd').click()
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

$('[aria-controls="history"]').on('click',function() {
  $('.eqActions').hide()
})
$('[aria-controls="display"]').on('click',function() {
  $('#display').show()
  $('.eqActions').show()
})

$('.cb_selEqLogic').on('change',function(){
  var found = false
  $('.cb_selEqLogic').each(function() {
    if ($(this).value() == 1) {
      found = true;
      return;
    }
  })
  if (found) {
    GLOBAL_ACTION_MODE = 'eqLogic';
    $('.cb_selCmd').hide();
    $('#bt_removeEqlogic').show();
    $('.bt_setIsVisible').show();
    $('.bt_setIsEnable').show();
  } else {
    GLOBAL_ACTION_MODE = null;
    $('.cb_selCmd').show();
    $('#bt_removeEqlogic').hide()
    $('.bt_setIsVisible').hide()
    $('.bt_setIsEnable').hide()
  }
})

$('.cb_selCmd').on('change',function(){
  var found = false
  $('.cb_selCmd').each(function() {
    if ($(this).value() == 1) {
      found = true;
      return;
    }
  })
  if (found) {
    GLOBAL_ACTION_MODE = 'cmd';
    $('.cb_selEqLogic').hide();
    $('.bt_setIsVisible').show();
  } else {
    GLOBAL_ACTION_MODE = null;
    $('.cb_selEqLogic').show();
    $('.bt_setIsVisible').hide();
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
  if(GLOBAL_ACTION_MODE == 'eqLogic'){
    var eqLogics = []
    $('.cb_selEqLogic').each(function(){
      if($(this).value() == 1){
        eqLogics.push($(this).closest('.eqLogic').attr('data-id'))
      }
    });
    jeedom.eqLogic.setIsVisibles({
      eqLogics: eqLogics,
      isVisible : $(this).attr('data-value'),
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      },
      success : function(){
        loadPage('index.php?v=d&p=display')
      }
    });
  }
  if(GLOBAL_ACTION_MODE == 'cmd'){
    var cmds = []
    $('.cb_selCmd').each(function(){
      if($(this).value() == 1){
        cmds.push($(this).closest('.cmd').attr('data-id'))
      }
    });
    jeedom.cmd.setIsVisibles({
      cmds: cmds,
      isVisible : $(this).attr('data-value'),
      error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'})
      },
      success : function(){
        loadPage('index.php?v=d&p=display')
      }
    });
  }
  
  
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
