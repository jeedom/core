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

//infos/actions tile signals:
$('body')
.on('mouseenter','div.eqLogic-widget .cmd-widget[data-type="action"][data-subtype!="select"]',function (event) {
  if(!isEditing) $(this).closest('.eqLogic-widget').addClass('eqSignalAction')
})
.on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"][data-subtype!="select"]',function (event) {
  if(!isEditing) $(this).closest('.eqLogic-widget').removeClass('eqSignalAction')
})
.on('mouseenter','div.eqLogic-widget .cmd-widget.history[data-type="info"]',function (event) {
  if(!isEditing) $(this).closest('.eqLogic-widget').addClass('eqSignalInfo')
})
.on('mouseleave','div.eqLogic-widget .cmd-widget.history[data-type="info"]',function (event) {
  if(!isEditing) $(this).closest('.eqLogic-widget').removeClass('eqSignalInfo')
})

/* v4.1
$('body')
.on('mouseenter','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd',function (event) {
if(!isEditing) $(this).closest('.eqLogic-widget').removeClass('eqSignalAction').addClass('eqSignalInfo')
})
.on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd',function (event) {
if(!isEditing) $(this).closest('.eqLogic-widget').removeClass('eqSignalInfo').addClass('eqSignalAction')
})
*/


$(function(){
  setTimeout(function(){
    if(typeof rootObjectId != 'undefined'){
      jeedom.object.getImgPath({
        id : rootObjectId,
        success : function(_path){
          setBackgroundImg(_path);
        }
      });
    }
  },1);
});

var isEditing = false
//searching
$ ('#in_searchWidget').off('keyup').on('keyup',function() {
  if (isEditing) return
  $('#bt_displaySummaries').attr('data-display', '0')
  var search = $(this).value()
  $('.div_object:not(.hideByObjectSel)').show()
  if (search == '') {
    $('.eqLogic-widget').show()
    $('.scenario-widget').show()
    $('.div_displayEquipement').packery()
    return
  }
  
  search = normTextLower(search)
  $('.eqLogic-widget').each(function() {
    var match = false
    text = normTextLower($(this).find('.widget-name').text())
    if (text.indexOf(search) >= 0) match = true
    
    if ($(this).attr('data-tags') != undefined) {
      text = normTextLower($(this).attr('data-tags'))
      if (text.indexOf(search) >= 0) match = true
    }
    if ($(this).attr('data-category') != undefined) {
      text = normTextLower($(this).attr('data-category'))
      if (text.indexOf(search) >= 0) match = true
    }
    if ($(this).attr('data-eqType') != undefined) {
      text = normTextLower($(this).attr('data-eqType'))
      if (text.indexOf(search) >= 0) match = true
    }
    if ($(this).attr('data-translate-category') != undefined) {
      text = normTextLower($(this).attr('data-translate-category'))
      if (text.indexOf(search) >= 0) match = true
    }
    
    if (match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  })
  $('.scenario-widget').each(function() {
    var match = false
    text = normTextLower($(this).find('.widget-name').text())
    if (text.indexOf(search) >= 0) match = true
    if (match) {
      $(this).show()
    } else {
      $(this).hide()
    }
  });
  $('.div_displayEquipement').packery()
  $('.div_displayEquipement').each(function() {
    var count = $(this).find('.scenario-widget:visible').length + $(this).find('.eqLogic-widget:visible').length
    if (count == 0) {
      $(this).closest('.div_object').hide()
    }
  })
})

$('#bt_displaySummaries').on('click', function () {
  if (isEditing) return
  $('.div_object').show()
  if ($(this).attr('data-display') == 0) {
    $('.eqLogic-widget').hide()
    $('.scenario-widget').hide()
    $('.div_displayEquipement').packery()
    $(this).attr('data-display', '1')
  } else {
    $('.eqLogic-widget').show()
    $('.scenario-widget').show()
    $('.div_displayEquipement').packery()
    $(this).attr('data-display', '0')
  }
})

$('#bt_resetDashboardSearch').on('click', function () {
  if (isEditing) return
  $('#in_searchWidget').val('').keyup()
})

$('#div_pageContainer').on( 'click','.eqLogic-widget .history', function (event) {
  if(isEditing) return false
  event.stopPropagation()
  let cmdIds = new Array()
  $(this).closest('.eqLogic.eqLogic-widget').find('.cmd.history').each(function () {
    cmdIds.push($(this).data('cmd_id'))
  })
  cmdIds = cmdIds.join('-')
  let cmdShow = $(this).closest('.cmd-widget').data('cmd_id')
  $('#md_modal2').dialog({title: "Historique"}).load('index.php?v=d&modal=cmd.history&id=' + cmdIds + '&showId=' + cmdShow).dialog('open')
})

$('#bt_displayObject').on('click', function () {
  if (isEditing) return
  if ($(this).attr('data-display') == 1) {
    $('#div_displayObjectList').hide();
    $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-9 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12');
    $('.div_displayEquipement').each(function () {
      $(this).packery();
    });
    $(this).attr('data-display', 0);
  } else {
    $('#div_displayObjectList').show();
    $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8');
    $('.div_displayEquipement').packery();
    $(this).attr('data-display', 1);
  }
});

var _draggingId = false
var _orders = {}
function editWidgetMode(_mode,_save){
  if (!isset(_mode)) {
    if($('#bt_editDashboardWidgetOrder').attr('data-mode') != undefined && $('#bt_editDashboardWidgetOrder').attr('data-mode') == 1){
      editWidgetMode(0,false);
      editWidgetMode(1,false);
    }
    return;
  }
  var divEquipements = $('.div_displayEquipement')
  if (_mode == 0) {
    jeedom.cmd.disableExecute = false
    isEditing = false
    if (!isset(_save) || _save) {
      saveWidgetDisplay({dashboard : 1})
    }
    
    divEquipements.find('.editingMode.allowResize').resizable('destroy')
    divEquipements.find('.editingMode').draggable('disable').removeClass('editingMode','').removeAttr('data-editId')
    
    $('#div_displayObject .row').removeAttr('style')
    $('#dashTopBar').removeAttr('style')
    $('#in_searchWidget').removeAttr('style').val('').prop('readonly', false)
    $.contextMenu('destroy');
  } else {
    jeedom.cmd.disableExecute = true
    isEditing = true
    
    $.contextMenu({
      selector: '.div_displayEquipement',
      zIndex: 9999,
      events: {
        show: function(opt) {
          $.contextMenu.setInputValues(opt, this.data());
        },
        hide: function(opt) {
          $.contextMenu.getInputValues(opt, this.data());
        }
      },
      items: {
        configuration: {
          name: "{{Ajout un bloc invisible}}",
          icon : 'fas fa-plus',
          callback: function(key, opt){
            var id = $(this).closest('.div_object').attr('data-object_id');
            
          }
        },
      }
    })
    
    //show orders:
    $('.ui-draggable').each( function() {
      var value = $(this).attr('data-order')
      if ($(this).find(".counterReorderJeedom").length) {
        $(this).find(".counterReorderJeedom").text(value)
      } else {
        $(this).prepend('<span class="counterReorderJeedom pull-left" style="margin-top: 3px;margin-left: 3px;">'+value+'</span>')
      }
    })
    
    //set unique id whatever we have:
    divEquipements.find('.eqLogic-widget,.scenario-widget').each(function(index) {
      $(this).addClass('editingMode')
      $(this).attr('data-editId', index)
    })
    
    //set draggables:
    divEquipements.find('.editingMode').draggable({
      disabled: false,
      start: function(event, ui) {
        _draggingId = $(this).attr('data-editId')
        _orders = {}
        $(this).parent().find('.ui-draggable').each( function( i, itemElem ) {
          _orders[_draggingId] = parseInt($(this).attr('data-order'))
        })
      }
    })
    //set resizables:
    divEquipements.find('.eqLogic-widget.allowResize').resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'),false);
        ui.element.closest('.div_displayEquipement').packery();
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'),false);
        ui.element.closest('.div_displayEquipement').packery();
      }
    })
    divEquipements.find('.scenario-widget.allowResize').resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'),false,true);
        ui.element.closest('.div_displayEquipement').packery();
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'),false,true);
        ui.element.closest('.div_displayEquipement').packery();
      }
    })
    
    $('#div_displayObject .row').css('margin-top', '27px')
    $('#dashTopBar').css({"position":"fixed","top":"55px","z-index":"5000","width":"calc(100% - "+($('body').width() - $('#dashTopBar').width())+'px)'});
    $('#in_searchWidget').style("background-color", "var(--al-info-color)", "important")
    .style("color", "var(--linkHoverLight-color)", "important")
    .val("{{Vous êtes en mode édition vous pouvez déplacer les widgets, les redimensionner et changer l'ordre des commandes dans les widgets. N'oubliez pas de quitter le mode édition pour sauvegarder}}")
    .prop('readonly', true)
  }
  editWidgetCmdMode(_mode);
}

function getObjectHtml(_object_id) {
  jeedom.object.toHtml({
    id: _object_id,
    version: 'dashboard',
    category : SEL_CATEGORY,
    summary : SEL_SUMMARY,
    tag : SEL_TAG,
    dummy : 1,
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (html) {
      try {
        $('#div_ob'+_object_id).empty().html(html).parent().show();
      } catch(err) {
        console.log(err);
      }
      
      positionEqLogic();
      var $divDisplayEq = $('#div_ob'+_object_id+'.div_displayEquipement')
      $divDisplayEq.disableSelection();
      $("input").click(function() { $(this).focus(); });
      $("textarea").click(function() { $(this).focus(); });
      $("select").click(function() { $(this).focus(); });
      
      var container = $divDisplayEq.packery();
      var packData = $divDisplayEq.data('packery');
      if (isset(packData) && packData.items.length == 1) {
        $divDisplayEq.packery('destroy').packery()
      }
      var itemElems =  container.find('.eqLogic-widget').draggable();
      container.packery('bindUIDraggableEvents',itemElems);
      var itemElems =  container.find('.scenario-widget').draggable();
      container.packery('bindUIDraggableEvents',itemElems);
      
      function orderItems() {
        var itemElems = container.packery('getItemElements');
        var isEditing = ($('#bt_editDashboardWidgetOrder').attr('data-mode') == 1) ? true : false
        
        var _draggingOrder = _orders[_draggingId]
        var _newOrders = {}
        $(itemElems).each( function( i, itemElem ) {
          _newOrders[$(this).attr('data-editId')] = i + 1
        })
        var _draggingNewOrder = _newOrders[_draggingId]
        //----->moved _draggingId from _draggingOrder to _draggingNewOrder
        
        //rearrange that better:
        var _finalOrder = {}
        for ([id, order] of Object.entries(_newOrders)) {
          if (order <= _draggingNewOrder) _finalOrder[id] = order
          if (order > _draggingNewOrder) _finalOrder[id] = _orders[id] + 1
        }
        
        //set dom positions:
        var arrKeys = Object.keys(_finalOrder)
        var arrLength = arrKeys.length
        var firstElId = arrKeys.find(key => _finalOrder[key] === 1)
        $('.ui-draggable[data-editId="'+firstElId+'"]').parent().prepend($('.ui-draggable[data-editId="'+firstElId+'"]'))
        
        for (var i = 2; i < arrLength + 1; i++) {
          var thisId = arrKeys.find(key => _finalOrder[key] === i)
          var prevId = arrKeys.find(key => _finalOrder[key] === i-1)
          $('.ui-draggable[data-editId="'+prevId+'"]').after($('.ui-draggable[data-editId="'+thisId+'"]'))
        }
        
        //reload from dom positions:
        $('.div_displayEquipement').packery('reloadItems')
        $('.div_displayEquipement').packery()
        
        itemElems = container.packery('getItemElements');
        $(itemElems).each( function( i, itemElem ) {
          $(itemElem).attr('data-order', i + 1 )
          value = i + 1
          if (isEditing) {
            if ($(itemElem).find(".counterReorderJeedom").length) {
              $(itemElem).find(".counterReorderJeedom").text(value)
            } else {
              $(itemElem).prepend('<span class="counterReorderJeedom pull-left" style="margin-top: 3px;margin-left: 3px;">'+value+'</span>')
            }
          }
        })
      }
      
      var itemElems = container.packery('getItemElements')
      $(itemElems).each( function( i, itemElem ) {
        $(itemElem).attr('data-order', i + 1 )
      })
      container.on('dragItemPositioned',orderItems);
      
      $('#div_ob'+_object_id+'.div_displayEquipement .eqLogic-widget').draggable('disable');
      $('#div_ob'+_object_id+'.div_displayEquipement .scenario-widget').draggable('disable');
    }
  });
}

$('#bt_editDashboardWidgetOrder').on('click',function() {
  if ($(this).attr('data-mode') == 1) {
    $('.tooltipstered').tooltipster('enable')
    $.hideAlert()
    $(this).attr('data-mode',0)
    editWidgetMode(0)
    $(this).css('color','black')
    $('.bt_editDashboardWidgetAutoResize').hide()
    $('.counterReorderJeedom').remove()
    $('.div_displayEquipement').packery()
  } else {
    $('.tooltipstered').tooltipster('disable')
    $(this).attr('data-mode',1)
    $('.bt_editDashboardWidgetAutoResize').show()
    $('.bt_editDashboardWidgetAutoResize').off('click').on('click', function() {
      var doesMin = false
      if (event.ctrlKey) doesMin = true
      var id_object = $(this).attr('id').replace('edit_object_','')
      var objectContainer = $('#div_ob'+id_object+'.div_displayEquipement')
      var arHeights = new Array()
      objectContainer.find('.eqLogic-widget,.scenario-widget').each(function(index, element) {
        var h = $(this).height()
        arHeights.push(h)
      })
      if (doesMin) {
        var maxHeight = Math.min(...arHeights)
      } else {
        var maxHeight = Math.max(...arHeights)
      }
      objectContainer.find('.eqLogic-widget,.scenario-widget').each(function(index, element) {
        $(this).height(maxHeight)
      })
      objectContainer.packery()
    });
    editWidgetMode(1)
  }
});

$('.li_object').on('click',function(){
  $('.div_object').removeClass('hideByObjectSel');
  var object_id = $(this).find('a').attr('data-object_id');
  if ($('.div_object[data-object_id='+object_id+']').html() != undefined) {
    jeedom.object.getImgPath({
      id : object_id,
      success : function(_path){
        setBackgroundImg(_path);
      }
    });
    $('.li_object').removeClass('active');
    $(this).addClass('active');
    displayChildObject(object_id,false);
    addOrUpdateUrl('object_id',object_id);
  } else {
    loadPage($(this).find('a').attr('data-href'));
  }
});

function displayChildObject(_object_id,_recursion){
  if(_recursion === false){
    $('.div_object').addClass('hideByObjectSel').hide()
  }
  $('.div_object[data-object_id='+_object_id+']').show({effect : 'drop',queue : false})
  $('.div_object[data-father_id='+_object_id+']').each(function() {
    $(this).show({effect : 'drop',queue : false}).find('.div_displayEquipement').packery()
    displayChildObject($(this).attr('data-object_id'),true)
  });
}
