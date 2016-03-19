
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

$('body').delegate('.eqLogic-widget .history', 'click', function () {
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

function editWidgetMode(_mode){
    if(!isset(_mode)){
        _mode = $('#bt_editDashboardWidgetOrder').attr('data-mode');
        if(_mode == undefined){
            return;
        }
    }
    if(_mode == 0){
     if( $('.div_displayEquipement .eqLogic-widget.ui-resizable').length > 0){
        $('.div_displayEquipement .eqLogic-widget.allowResize').resizable('destroy');
    }
    if( $('.div_displayEquipement .eqLogic-widget.ui-sortable').length > 0){
     $('.div_displayEquipement .eqLogic-widget.allowReorderCmd').sortable('destroy');
 }
 if( $('.div_displayEquipement .eqLogic-widget.ui-draggable').length > 0){
     $('.div_displayEquipement .eqLogic-widget').draggable('disable');
     $('.div_displayEquipement .eqLogic-widget.allowReorderCmd .cmd').off('mouseover');
     $('.div_displayEquipement .eqLogic-widget.allowReorderCmd .cmd').off('mouseleave');
 }
}else{
   $('.div_displayEquipement .eqLogic-widget').draggable('enable');

   $( ".div_displayEquipement .eqLogic-widget.allowResize").resizable({
      grid: [ 40, 80 ],
      resize: function( event, ui ) {
       var el = ui.element;
       el.closest('.div_displayEquipement').packery();
   },
   stop: function( event, ui ) {
    var el = ui.element;
    positionEqLogic(el.attr('data-eqlogic_id'));
    el.closest('.div_displayEquipement').packery();
    var eqLogic = {id : el.attr('data-eqlogic_id')}
    eqLogic.display = {};
    eqLogic.display.width =  Math.floor(el.width() / 40) * 40 + 'px';
    eqLogic.display.height = Math.floor(el.height() / 80) * 80+ 'px';
    jeedom.eqLogic.simpleSave({
        eqLogic : eqLogic,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        }
    });
}
});

   $( ".div_displayEquipement .eqLogic-widget.allowReorderCmd").sortable({
    items: ".cmd",
    stop: function (event, ui) {
        var cmds = [];
        var eqLogic = ui.item.closest('.eqLogic-widget');
        order = 1;
        eqLogic.find('.cmd').each(function(){
            cmd = {};
            cmd.id = $(this).attr('data-cmd_id');
            cmd.order = order;
            cmds.push(cmd);
            order++;
        });
        jeedom.cmd.setOrder({
            cmds: cmds,
            error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
            }
        });
    }
});

   $('.div_displayEquipement .eqLogic-widget.allowReorderCmd').on('mouseover','.cmd',function(){
    $('.div_displayEquipement .eqLogic-widget').draggable('disable');
});
   $('.div_displayEquipement .eqLogic-widget.allowReorderCmd').on('mouseleave','.cmd',function(){
    $('.div_displayEquipement .eqLogic-widget').draggable('enable');
});

}
}

function getObjectHtml(_object_id){
  jeedom.object.toHtml({
    id: _object_id,
    version: 'dashboard',
    category : category_dashabord,
    error: function (error) {
        $('#div_alert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (html) {
        try {
            $('#div_ob'+_object_id).empty().html(html);
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
                    columnWidth:40,
                    rowHeight: 80,
                    gutter : 2,
                });
                var itemElems =  container.find('.eqLogic-widget');
                itemElems.draggable();
                container.packery( 'bindUIDraggableEvents', itemElems );
                container.packery( 'on', 'dragItemPositioned',function(){
                    var itemElems = container.packery('getItemElements');
                    var eqLogics = [];
                    $(itemElems).each( function( i, itemElem ) {
                        if($(itemElem).attr('data-eqlogic_id') != undefined){
                            eqLogic = {};
                            eqLogic.id =  $(itemElem).attr('data-eqlogic_id');
                            eqLogic.order = i;
                            eqLogics.push(eqLogic);
                        }
                    });
                    jeedom.eqLogic.setOrder({
                        eqLogics: eqLogics,
                        error: function (error) {
                            $('#div_alert').showAlert({message: error.message, level: 'danger'});
                        }
                    });
                });
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
       $('#div_alert').showAlert({message: "{{Vous êtes en mode édition vous pouvez déplacer les widgets, les redimensionner et changer l'ordre des commandes dans les widgets}}", level: 'info'});
       $(this).attr('data-mode',1);
       editWidgetMode(1);
       $(this).css('color','rgb(46, 176, 75)');
   }
});