
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

 var category_dashabord = getUrlVars('category');
 if(category_dashabord == false){
    category_dashabord = 'all';
}

var summary_dashabord = getUrlVars('summary');
if(summary_dashabord == false){
    summary_dashabord = '';
}

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
}else{
   $('.div_displayEquipement .eqLogic-widget').draggable('enable');

   $( ".div_displayEquipement .eqLogic-widget.allowResize").resizable({
      grid: [ 2, 2 ],
      resize: function( event, ui ) {
       var el = ui.element;
       el.closest('.div_displayEquipement').packery();
   },
   stop: function( event, ui ) {
    var el = ui.element;
    positionEqLogic(el.attr('data-eqlogic_id'));
    el.closest('.div_displayEquipement').packery();
}
});
}
editWidgetCmdMode(_mode);
}

function getObjectHtml(_object_id){
  jeedom.object.toHtml({
    id: _object_id,
    version: 'dashboard',
    category : category_dashabord,
    summary : summary_dashabord,
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
                    itemSelector: ".eqLogic-widget",
                    gutter : 2
                });
                var itemElems =  container.find('.eqLogic-widget');
                itemElems.draggable();
                container.packery( 'bindUIDraggableEvents', itemElems );
                container.packery( 'on', 'dragItemPositioned',function(){
                    $('.div_displayEquipement').packery();
                });
                function orderItems() {
                  var itemElems = container.packery('getItemElements');
                  $( itemElems ).each( function( i, itemElem ) {
                    $( itemElem ).attr('data-order', i + 1 );
                });
              }
              container.on( 'layoutComplete', orderItems );
              container.on( 'dragItemPositioned', orderItems );
          });
            $('#div_ob'+_object_id+'.div_displayEquipement .eqLogic-widget').draggable('disable');
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
        $('.li_object').removeClass('active');
        $(this).addClass('active');
        var top = $('#div_displayObject').scrollTop()+ $('.div_object[data-object_id='+object_id+']').offset().top - 60;
        $('#div_displayObject').animate({ scrollTop: top}, 500);
    }else{
        loadPage($(this).find('a').attr('data-href'));
    }
});