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
  $(this).closest('.eqLogic-widget').addClass('eqSignalAction')
})
.on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"][data-subtype!="select"]',function (event) {
  $(this).closest('.eqLogic-widget').removeClass('eqSignalAction')
})
.on('mouseenter','div.eqLogic-widget .cmd-widget.history[data-type="info"]',function (event) {
  $(this).closest('.eqLogic-widget').addClass('eqSignalInfo')
})
.on('mouseleave','div.eqLogic-widget .cmd-widget.history[data-type="info"]',function (event) {
  $(this).closest('.eqLogic-widget').removeClass('eqSignalInfo')
})

/* v4.1
$('body')
.on('mouseenter','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd',function (event) {
$(this).closest('.eqLogic-widget').removeClass('eqSignalAction').addClass('eqSignalInfo')
})
.on('mouseleave','div.eqLogic-widget .cmd-widget[data-type="action"] .timeCmd',function (event) {
$(this).closest('.eqLogic-widget').removeClass('eqSignalInfo').addClass('eqSignalAction')
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

$('#div_pageContainer').on( 'click','.eqLogic-widget .history', function () {
  $('#md_modal2').dialog({title: "Historique"}).load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open')
});

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

function editWidgetMode(_mode,_save){
  if (!isset(_mode)) {
    if($('#bt_editDashboardWidgetOrder').attr('data-mode') != undefined && $('#bt_editDashboardWidgetOrder').attr('data-mode') == 1){
      editWidgetMode(0,false);
      editWidgetMode(1,false);
    }
    return;
  }
  if (_mode == 0) {
    jeedom.cmd.disableExecute = false;
    if (!isset(_save) || _save) {
      saveWidgetDisplay({dashboard : 1});
    }
    if( $('.div_displayEquipement .eqLogic-widget.ui-resizable').length > 0){
      $('.div_displayEquipement .eqLogic-widget.allowResize').resizable('destroy');
    }
    if( $('.div_displayEquipement .eqLogic-widget.ui-draggable').length > 0){
      $('.div_displayEquipement .eqLogic-widget').draggable('disable');
    }
    $('.div_displayEquipement .eqLogic-widget').removeClass('editingMode','');
    
    if( $('.div_displayEquipement .scenario-widget.ui-resizable').length > 0){
      $('.div_displayEquipement .scenario-widget.allowResize').resizable('destroy');
    }
    if( $('.div_displayEquipement .scenario-widget.ui-draggable').length > 0){
      $('.div_displayEquipement .scenario-widget').draggable('disable');
    }
    isEditing = false
    $('.div_displayEquipement .scenario-widget').removeClass('editingMode','')
    $('#div_displayObject .row').removeAttr('style')
    $('#dashTopBar').removeAttr('style')
    $('#in_searchWidget').removeAttr('style')
    $('#in_searchWidget').val('')
    $('#in_searchWidget').prop('readonly', false)
  } else {
    jeedom.cmd.disableExecute = true;
    $('.div_displayEquipement .eqLogic-widget').addClass('editingMode').draggable('enable');
    $('.div_displayEquipement .eqLogic-widget.allowResize').resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'),false);
        ui.element.closest('.div_displayEquipement').packery();
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'),false);
        ui.element.closest('.div_displayEquipement').packery();
      }
    });
    $('.div_displayEquipement .scenario-widget').addClass('editingMode').draggable('enable');
    $('.div_displayEquipement .scenario-widget.allowResize').resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'),false,true);
        ui.element.closest('.div_displayEquipement').packery();
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'),false,true);
        ui.element.closest('.div_displayEquipement').packery();
      }
    })
    isEditing = true
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
    error: function (error) {
      $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (html) {
      try {
        $('#div_ob'+_object_id).empty().html(html).parent().show();
      } catch(err) {
        console.log(err);
      }
      var $divDisplayEq = $('#div_ob'+_object_id+'.div_displayEquipement')
      if(SEL_SUMMARY != ''){
        var count = $divDisplayEq.find('.scenario-widget:visible').length + $divDisplayEq.find('.eqLogic-widget:visible').length
        if (count == 0) {
          $('#div_ob'+_object_id).closest('.div_object').remove();
          return;
        }
      }
      positionEqLogic();
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
        setTimeout(function(){
          $('.div_displayEquipement').packery();
        },1);
        var itemElems = container.packery('getItemElements');
        var isEditing = false;
        if($('#bt_editDashboardWidgetOrder').attr('data-mode') == 1) isEditing = true;
        $(itemElems).each( function( i, itemElem ) {
          $(itemElem).attr('data-order', i + 1 );
          value = i + 1;
          if (isEditing) {
            if ($(itemElem).find(".counterReorderJeedom").length) {
              $(itemElem).find(".counterReorderJeedom").text(value);
            } else {
              $(itemElem).prepend('<span class="counterReorderJeedom pull-left" style="margin-top: 3px;margin-left: 3px;">'+value+'</span>');
            }
          }
        });
      }
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
