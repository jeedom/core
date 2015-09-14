
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
 $( ".eqLogicSortable" ).sortable({
  connectWith: ".eqLogicSortable",
  stop: function (event, ui) {
    var eqLogics = [];
    var object = ui.item.closest('.object');
    order = 1;
    object.find('.eqLogic').each(function(){
        eqLogic = {};
        eqLogic.object_id = object.attr('data-id');
        eqLogic.id = $(this).attr('data-id');
        eqLogic.order = order;
        eqLogics.push(eqLogic);
        order++;
    });
    jeedom.eqLogic.setOrder({
        eqLogics: eqLogics,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
            $( ".eqLogicSortable" ).sortable( "cancel" );
        }
    });
}
}).disableSelection();


 $( ".cmdSortable" ).sortable({
  stop: function (event, ui) {
    var cmds = [];
    var eqLogic = ui.item.closest('.eqLogic');
    order = 1;
    eqLogic.find('.cmd').each(function(){
        cmd = {};
        cmd.id = $(this).attr('data-id');
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
}).disableSelection();

 $( ".eqLogic" ).on('dblclick',function(){
     $('#md_modal').dialog({title: "{{Configuration de l'équipement}}"});
     $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $(this).attr('data-id')).dialog('open');
 });

 $('.configureEqLogic').on('click',function(){
   $('#md_modal').dialog({title: "{{Configuration de l'équipement}}"});
   $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $(this).closest('.eqLogic').attr('data-id')).dialog('open');
});

 $('.configureObject').on('click',function(){
   $('#md_modal').dialog({title: "{{Configuration de l'objet}}"});
   $('#md_modal').load('index.php?v=d&modal=object.configure&object_id=' + $(this).closest('.object').attr('data-id')).dialog('open');
});

 $('.showCmd').on('click',function(){
    if($(this).hasClass('fa-chevron-right')){
        $(this).removeClass('fa-chevron-right').addClass('fa-chevron-down');
        $(this).closest('.eqLogic').find('.cmdSortable').show();
    }else{
        $(this).removeClass('fa-chevron-down').addClass('fa-chevron-right');
        $(this).closest('.eqLogic').find('.cmdSortable').hide();
    }
});

 $('.showEqLogic').on('click',function(){
    if($(this).hasClass('fa-chevron-right')){
        $(this).removeClass('fa-chevron-right').addClass('fa-chevron-down');
        $(this).closest('.object').find('.eqLogic').show();
    }else{
        $(this).removeClass('fa-chevron-down').addClass('fa-chevron-right');
        $(this).closest('.object').find('.eqLogic').hide();
    }
});

 $('#cb_actifDisplay').on('switchChange.bootstrapSwitch change',function(){
    if($(this).value() == 1){
$('.eqLogic[data-enable=0]').show();
    }else{
$('.eqLogic[data-enable=0]').hide();
    }
});

 $( ".cmd" ).on('dblclick',function(){
     $('#md_modal').dialog({title: "{{Configuration de la commande}}"});
     $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open');
 });

 $('.configureCmd').on('click',function(){
   $('#md_modal').dialog({title: "{{Configuration de la commande}}"});
   $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-id')).dialog('open');
});

 $('#in_search').on('keyup',function(){
    var search = $(this).value().toLowerCase();
    $('.cmd').show().removeClass('alert-success').addClass('alert-warning');
    $('.cmdSortable').hide();
    if(search == ''){
        return;
    }
    $('.eqLogic').each(function(){
        var eqLogic = $(this);
        var name = eqLogic.attr('data-name').toLowerCase();
        var type = eqLogic.attr('data-type').toLowerCase();
        if(name.indexOf(search) < 0 && type.indexOf(search) < 0){
            eqLogic.hide();
        }
        $(this).find('.cmd').each(function(){
            var cmd = $(this);
            var name = cmd.attr('data-name').toLowerCase();
            if(name.indexOf(search) >= 0){
                eqLogic.show();
                eqLogic.find('.cmdSortable').show();
                cmd.removeClass('alert-warning').addClass('alert-success'); 
            }
        });
    });
});

 $('.cb_selEqLogic').on('change',function(){
    var found = false;
    $('.cb_selEqLogic').each(function(){
        if($(this).value() == 1){
            found = true;
        }
    });
    if(found){
        $('#bt_removeEqlogic').show();
        $('.bt_setIsVisible').show();
        $('.bt_setIsEnable').show();
    }else{
        $('#bt_removeEqlogic').hide();
        $('.bt_setIsVisible').hide();
        $('.bt_setIsEnable').hide();
    }
});

 $('#bt_removeEqlogic').on('click',function(){
    bootbox.confirm('{{Etes-vous sûr de vouloir supprimer tous ces équipement ?}}', function (result) {
        if (result) {
            var eqLogics = [];
            $('.cb_selEqLogic').each(function(){
                if($(this).value() == 1){
                    eqLogics.push($(this).closest('.eqLogic').attr('data-id'));
                }
            });
            jeedom.eqLogic.removes({
                eqLogics: eqLogics,
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success : function(){
                    window.location.reload();
                }
            });
        }
    });
});

 $('.bt_setIsVisible').on('click',function(){
    var eqLogics = [];
    $('.cb_selEqLogic').each(function(){
        if($(this).value() == 1){
            eqLogics.push($(this).closest('.eqLogic').attr('data-id'));
        }
    });
    jeedom.eqLogic.setIsVisibles({
        eqLogics: eqLogics,
        isVisible : $(this).attr('data-value'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success : function(){
            window.location.reload();
        }
    });
});

 $('.bt_setIsEnable').on('click',function(){
    var eqLogics = [];
    $('.cb_selEqLogic').each(function(){
        if($(this).value() == 1){
            eqLogics.push($(this).closest('.eqLogic').attr('data-id'));
        }
    });
    jeedom.eqLogic.setIsEnables({
        eqLogics: eqLogics,
        isEnable : $(this).attr('data-value'),
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success : function(){
            window.location.reload();
        }
    });
});