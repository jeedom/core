
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

$('#div_pageContainer').on( 'click','.eqLogic-widget .history', function () {
  $('#md_modal2').dialog({title: "Historique"});
  $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
});

$('#bt_displayScenario').on('click', function () {
  if ($(this).attr('data-display') == 1) {
    $('#div_displayScenario').hide();
    if ($('#bt_displayObject').attr('data-display') == 1) {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8');
    } else {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12');
    }
    $('.div_displayEquipement').each(function () {
      $(this).packery();
    });
    $(this).attr('data-display', 0);
  } else {
    $('#div_displayScenario').show();
    if ($('#bt_displayObject').attr('data-display') == 1) {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-8 col-md-7 col-sm-5');
    } else {
      $('#div_displayObject').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-7');
    }
    $('.div_displayEquipement').packery();
    $(this).attr('data-display', 1);
  }
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
  return;
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
      $('#div_ob'+_object_id).disableSelection();
      $("input").click(function() { $(this).focus(); });
      $("textarea").click(function() { $(this).focus(); });
      $("select").click(function() { $(this).focus(); });
      var auto_pos = [];
      $('#div_ob'+_object_id).find(".eqLogic-widget").each(function(){
        var width = ($(this).data('gs-width')!=undefined) ? $(this).data('gs-width') : 2;
        var height = ($(this).data('gs-height')!=undefined) ? $(this).data('gs-height') : 2;
        if($(this).data('gs-x') === '' || $(this).data('gs-y') === '' || $(this).data('gs-x') == undefined || $(this).data('gs-y') == undefined){
          $(this).wrap('<div class="grid-stack-item" data-gs-auto-position="1" data-gs-width="'+width+'" data-gs-height="'+ height +'"></div>');
        }else{
          $(this).wrap('<div class="grid-stack-item" data-gs-x="'+$(this).data('gs-x')+'" data-gs-y="'+$(this).data('gs-y')+'" data-gs-width="'+width+'" data-gs-height="'+height+'"></div>');
        }
      });
      $('.eqLogic-widget').css('height','auto').css('overflow','hidden').css('width','auto');
      $('.eqLogic-widget').addClass('grid-stack-item-content');
      $('#div_ob'+_object_id).gridstack();
      $('#div_ob'+_object_id).find('.grid-stack-item').data('gs-locked',0);
      $('#div_ob'+_object_id).off('change').on('change', function(event, items) {
        if(items == undefined || items.length == 0){
          return;
        }
        var eqLogics = [];
        for(var i in items){
          eqLogics.push({
            id :items[i].el.find('.eqLogic').attr('data-eqlogic_id'),
            display : {
              'dashboard-grid-width' : items[i].width,
              'dashboard-grid-height' :items[i].height,
              'dashboard-grid-pos-y' : items[i].y,
              'dashboard-grid-pos-x' :items[i].x
            }
          })
        }
        jeedom.eqLogic.setOrder({
          eqLogics: eqLogics,
          global : false,
          error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
          }
        });
      });
    }
  });
}

$('#bt_editDashboardWidgetOrder').on('click',function(){
  if($(this).attr('data-mode') == 1){
    $.hideAlert();
    $(this).attr('data-mode',0);
    editWidgetMode(0);
    $(this).css('color','black');
    $('.counterReorderJeedom').remove();
    $('.div_displayEquipement').packery();
  }else{
    $('#div_alert').showAlert({message: "{{Vous êtes en mode édition vous pouvez déplacer les widgets, les redimensionner et changer l'ordre des commandes dans les widgets. N'oubliez pas de quitter le mode édition pour sauvegarder}}", level: 'info'});
    $(this).attr('data-mode',1);
    editWidgetMode(1);
    $(this).css('color','rgb(46, 176, 75)');
  }
});


$('.li_object').on('click',function(){
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
    $('.div_object').hide();
  }
  $('.div_object[data-object_id='+_object_id+']').show({effect : 'drop',queue : false});
  $('.div_object[data-father_id='+_object_id+']').each(function(){
    $(this).show({effect : 'drop',queue : false});
    displayChildObject($(this).attr('data-object_id'),true);
  });
}
