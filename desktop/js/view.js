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

 if (view_id != '') {
    jeedom.view.toHtml({
        id: view_id,
        version: 'dashboard',
        useCache: true,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (html) {
            try {
                $('.div_displayView:last').empty().html(html.html);
            }catch(err) {
                console.log(err);
            }
            setTimeout(function () {
                positionEqLogic();
                $('.eqLogicZone').disableSelection();
                $( "input").click(function() { $(this).focus(); });
                $( "textarea").click(function() { $(this).focus(); });
                $('.eqLogicZone').each(function () {
                    var container = $(this).packery({
                        columnWidth: 40,
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
                                eqLogic.viewZone_id = $(itemElem).closest('.eqLogicZone').attr('data-viewZone-id');
                                eqLogics.push(eqLogic);
                            }
                        });
                        jeedom.view.setEqLogicOrder({
                            eqLogics: eqLogics,
                            error: function (error) {
                                $('#div_alert').showAlert({message: error.message, level: 'danger'});
                            }
                        });
                    });
                });

                $('.eqLogicZone .eqLogic-widget').draggable('disable');
                $('#bt_editViewWidgetOrder').off('click').on('click',function(){
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


                initTooltips();
            }, 10);
        }
    });
}



$('body').delegate('.eqLogic-widget .history', 'click', function () {
    $('#md_modal2').dialog({title: "Historique"});
    $("#md_modal2").load('index.php?v=d&modal=cmd.history&id=' + $(this).data('cmd_id')).dialog('open');
});

$('.bt_displayView').on('click', function () {
    if ($(this).attr('data-display') == 1) {
        $(this).closest('.row').find('.div_displayViewList').hide();
        $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-12 col-md-12 col-sm-12');
        $('.eqLogicZone').each(function () {
            $(this).packery();
        });
        $(this).attr('data-display', 0);
    } else {
        $(this).closest('.row').find('.div_displayViewList').show();
        $(this).closest('.row').find('.div_displayViewContainer').removeClass('col-lg-8 col-lg-10 col-lg-12 col-lg-8 col-lg-10 col-lg-12 col-md-8 col-md-10 col-md-12 col-sm-8 col-sm-10 col-sm-12').addClass('col-lg-10 col-md-9 col-sm-8');
        $('.eqLogicZone').packery();
        $(this).attr('data-display', 1);
    }
});

function editWidgetMode(_mode){
    if(!isset(_mode)){
        _mode = $('#bt_editViewWidgetOrder').attr('data-mode');
        if(_mode == undefined){
            return;
        }
    }
    if(_mode == 0 || _mode == '0'){
        if( $('.eqLogicZone .eqLogic-widget.ui-draggable').length > 0){
           $('.eqLogicZone .eqLogic-widget').draggable('disable');
           $('.eqLogicZone .eqLogic-widget.allowResize').resizable('destroy');
           $('.eqLogicZone .eqLogic-widget.allowReorderCmd').sortable('destroy');
           $('.eqLogicZone .eqLogic-widget.allowReorderCmd .cmd').off('mouseover');
           $('.eqLogicZone .eqLogic-widget.allowReorderCmd .cmd').off('mouseleave');
       }
   }else{
     $('.eqLogicZone .eqLogic-widget').draggable('enable');

     $( ".eqLogicZone .eqLogic-widget.allowResize").resizable({
      grid: [ 40, 80 ],
      resize: function( event, ui ) {
         var el = ui.element;
         el.closest('.eqLogicZone').packery();
     },
     stop: function( event, ui ) {
        var el = ui.element;
        positionEqLogic(el.attr('data-eqlogic_id'));
        el.closest('.eqLogicZone').packery();
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

     $( ".eqLogicZone .eqLogic-widget.allowReorderCmd").sortable({
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

     $('.eqLogicZone .eqLogic-widget.allowReorderCmd').on('mouseover','.cmd',function(){
        $('.eqLogicZone .eqLogic-widget').draggable('disable');
    });
     $('.eqLogicZone .eqLogic-widget.allowReorderCmd').delegate('mouseleave','.cmd',function(){
        $('.eqLogicZone .eqLogic-widget').draggable('enable');
    });

 }
}