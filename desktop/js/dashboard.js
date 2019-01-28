
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


$(function(){
  setTimeout(function(){
    if(typeof rootObjectId != 'undefined'){
      jeedom.object.getImgPath({
        id : rootObjectId,
        success : function(_path){
          $('.backgroundforJeedom').css('background-image','url("'+_path+'")');
        }
      });
    }
    
  },1);
});

$('#in_searchWidget').off('keyup').on('keyup',function(){
  var search = $(this).value();
  $('.div_object:not(.hideByObjectSel)').show();
  if(search == ''){
    $('.eqLogic-widget').show();
    $('.scenario-widget').show();
    $('.div_displayEquipement').packery();
    return;
  }
  $('.eqLogic-widget').each(function(){
    var match = false;
    if(match || $(this).find('.widget-name').text().toLowerCase().indexOf(search.toLowerCase()) >= 0){
      match = true;
    }
    if(match || ($(this).attr('data-tags') != undefined && $(this).attr('data-tags').toLowerCase().indexOf(search.toLowerCase()) >= 0)){
      match = true;
    }
    if(match ||($(this).attr('data-category') != undefined && $(this).attr('data-category').toLowerCase().indexOf(search.toLowerCase()) >= 0)){
      match = true;
    }
    if(match ||($(this).attr('data-eqType') != undefined && $(this).attr('data-eqType').toLowerCase().indexOf(search.toLowerCase()) >= 0)){
      match = true;
    }
    if(match){
      $(this).show();
    }else{
      $(this).hide();
    }
  });
  $('.scenario-widget').each(function(){
    var match = false;
    if(match || $(this).find('.widget-name').text().toLowerCase().indexOf(search.toLowerCase()) >= 0){
      match = true;
    }
    if(match){
      $(this).show();
    }else{
      $(this).hide();
    }
  });
  $('.div_displayEquipement').packery();
  $('.div_displayEquipement').each(function(){
    var count = $(this).find('.scenario-widget:visible').length + $(this).find('.eqLogic-widget:visible').length
    if(count == 0){
      $(this).closest('.div_object').hide();
    }
  })
});

$('#div_pageContainer').on( 'click','.eqLogic-widget .history', function () {
  $('#md_modal2').dialog({title: "Historique"});
  $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
});

$('#bt_displayObject').on('click', function () {
  if ($(this).attr('data-display') == 1) {
    $('#div_displayObjectList').hide();
    if ($('#bt_displayScenario').attr('data-display') == 1) {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-7');
    } else {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12');
    }
    $('.div_displayEquipement').each(function () {
      $(this).packery();
    });
    $(this).attr('data-display', 0);
  } else {
    $('#div_displayObjectList').show();
    if ($('#bt_displayScenario').attr('data-display') == 1) {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-8 col-md-7 col-sm-5');
    } else {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8');
    }
    $('.div_displayEquipement').packery();
    $(this).attr('data-display', 1);
  }
});

function editWidgetMode(_mode,_save){
  if(!isset(_mode)){
    if($('#bt_editDashboardWidgetOrder').attr('data-mode') != undefined && $('#bt_editDashboardWidgetOrder').attr('data-mode') == 1){
      editWidgetMode(0,false);
      editWidgetMode(1,false);
    }
    return;
  }
  if(_mode == 0){
    if(!isset(_save) || _save){
      saveWidgetDisplay({dashboard : 1});
    }
    if( $('.div_displayEquipement .eqLogic-widget.ui-resizable').length > 0){
      $('.div_displayEquipement .eqLogic-widget.allowResize').resizable('destroy');
    }
    if( $('.div_displayEquipement .eqLogic-widget.ui-draggable').length > 0){
      $('.div_displayEquipement .eqLogic-widget').draggable('disable');
    }
    $('.div_displayEquipement .eqLogic-widget').css('box-shadow','');
    
    if( $('.div_displayEquipement .scenario-widget.ui-resizable').length > 0){
      $('.div_displayEquipement .scenario-widget.allowResize').resizable('destroy');
    }
    if( $('.div_displayEquipement .scenario-widget.ui-draggable').length > 0){
      $('.div_displayEquipement .scenario-widget').draggable('disable');
    }
    $('.div_displayEquipement .scenario-widget').css('box-shadow','');
  }else{
    $('.div_displayEquipement .eqLogic-widget').css('box-shadow','0 0 4px rgba(147,204,1,.14), 0 10px 16px rgba(147,204,1,.30)');
    $('.div_displayEquipement .eqLogic-widget').draggable('enable');
    $( ".div_displayEquipement .eqLogic-widget.allowResize").resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'),false);
        ui.element.closest('.div_displayEquipement').packery();
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-eqlogic_id'),false);
        ui.element.closest('.div_displayEquipement').packery();
      }
    });
    $('.div_displayEquipement .scenario-widget').css('box-shadow','0 0 4px rgba(147,204,1,.14), 0 10px 16px rgba(147,204,1,.30)');
    $('.div_displayEquipement .scenario-widget').draggable('enable');
    $( ".div_displayEquipement .scenario-widget.allowResize").resizable({
      resize: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'),false,true);
        ui.element.closest('.div_displayEquipement').packery();
      },
      stop: function( event, ui ) {
        positionEqLogic(ui.element.attr('data-scenario_id'),false,true);
        ui.element.closest('.div_displayEquipement').packery();
      }
    });
  }
  editWidgetCmdMode(_mode);
}

function getObjectHtml(_object_id){
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
      if($.trim(html) == ''){
        $('#div_ob'+_object_id).parent().remove();
        return;
      }
      try {
        $('#div_ob'+_object_id).empty().html(html).parent().show();
      }catch(err) {
        console.log(err);
      }
      setTimeout(function(){
        positionEqLogic();
        $('#div_ob'+_object_id+'.div_displayEquipement').disableSelection();
        $("input").click(function() { $(this).focus(); });
        $("textarea").click(function() { $(this).focus(); });
        $("select").click(function() { $(this).focus(); });
        
        $('#div_ob'+_object_id+'.div_displayEquipement').each(function(){
          var container = $(this).packery({
            itemSelector: ".eqLogic-widget,.scenario-widget",
            gutter : 0,
            columnWidth: parseInt(widget_width_step)
          });
          var itemElems =  container.find('.eqLogic-widget').draggable();
          container.packery('bindUIDraggableEvents',itemElems);
          var itemElems =  container.find('.scenario-widget').draggable();
          container.packery('bindUIDraggableEvents',itemElems);
          function orderItems() {
            setTimeout(function(){
              $('.div_displayEquipement').packery();
            },1);
            var itemElems = container.packery('getItemElements');
            $(itemElems).each( function( i, itemElem ) {
              $(itemElem).attr('data-order', i + 1 );
              value = i + 1;
              if($('#bt_editDashboardWidgetOrder').attr('data-mode') == 1){
                if ($(itemElem).find(".counterReorderJeedom").length) {
                  $(itemElem).find(".counterReorderJeedom").text(value);
                } else {
                  $(itemElem).prepend('<span class="counterReorderJeedom pull-left" style="margin-top: 3px;margin-left: 3px;">'+value+'</span>');
                }
              }
            });
          }
          container.on('dragItemPositioned',orderItems);
        });
        $('#div_ob'+_object_id+'.div_displayEquipement .eqLogic-widget').draggable('disable');
        $('#div_ob'+_object_id+'.div_displayEquipement .scenario-widget').draggable('disable');
      },10);
    }
  });
}

$('#bt_editDashboardWidgetOrder').on('click',function(){
  if($(this).attr('data-mode') == 1){
    $.hideAlert();
    $(this).attr('data-mode',0);
    editWidgetMode(0);
    $(this).css('color','black');
    $('.bt_editDashboardWidgetAutoResize').hide();
    $('.counterReorderJeedom').remove();
    $('.div_displayEquipement').packery();
  }else{
    $('#div_alert').showAlert({message: "{{Vous êtes en mode édition vous pouvez déplacer les widgets, les redimensionner et changer l'ordre des commandes dans les widgets. N'oubliez pas de quitter le mode édition pour sauvegarder}}", level: 'info'});
    $(this).attr('data-mode',1);
    $('.bt_editDashboardWidgetAutoResize').show();
    $('.bt_editDashboardWidgetAutoResize').off('click').on('click', function(){
      var id_object = $(this).attr('id');
      id_object = id_object.replace('edit_object_','');
      var heightObjectex = 0;
      $('#div_ob'+id_object+'.div_displayEquipement .eqLogic-widget,.scenario-widget').each(function(index, element){
        var heightObject = this.style.height;
        heightObject = eval(heightObject.replace('px',''));
        var valueAdd = eval(heightObject * 0.20);
        var valueRemove = eval(heightObject * 0.05);
        var heightObjectadd = eval(heightObject + valueAdd);
        var heightObjectremove = eval(heightObject - valueRemove);
        if(heightObjectadd >= heightObjectex && (heightObjectex > heightObject || heightObjectremove < heightObjectex)){
          if($(element).hasClass('allowResize')){
            $( element ).height(heightObjectex);
            heightObject = heightObjectex;
          }
        }
        heightObjectex = heightObject;
      });
    });
    editWidgetMode(1);
    $(this).css('color','rgb(46, 176, 75)');
  }
});

$('.li_object').on('click',function(){
  $('.div_object').removeClass('hideByObjectSel');
  var object_id = $(this).find('a').attr('data-object_id');
  if($('.div_object[data-object_id='+object_id+']').html() != undefined){
    jeedom.object.getImgPath({
      id : object_id,
      success : function(_path){
        $('.backgroundforJeedom').css('background-image','url("'+_path+'")');
      }
    });
    $('.li_object').removeClass('active');
    $(this).addClass('active');
    displayChildObject(object_id,false);
  }else{
    loadPage($(this).find('a').attr('data-href'));
  }
});

function displayChildObject(_object_id,_recursion){
  if(_recursion === false){
    $('.div_object').addClass('hideByObjectSel');
    $('.div_object').hide();
  }
  $('.div_object[data-object_id='+_object_id+']').show({effect : 'drop',queue : false});
  $('.div_object[data-father_id='+_object_id+']').each(function(){
    $(this).show({effect : 'drop',queue : false});
    $(this).find('.div_displayEquipement').packery();
    displayChildObject($(this).attr('data-object_id'),true);
  });
}
