
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
 var changeLeftMenuObjectOrEqLogicName = false;


 if((!isset(userProfils.doNotAutoHideMenu) || userProfils.doNotAutoHideMenu != 1) && !jQuery.support.touch && $('.eqLogicThumbnailDisplay').html() != undefined){
    $('#div_mainContainer').append('<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" class="bt_pluginTemplateShowSidebar div_smallSideBar"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i><div>');
    $('#ul_eqLogic').closest('.bs-sidebar').parent().hide();
    $('#ul_eqLogic').closest('.bs-sidebar').parent().css('z-index','999');
    $('#ul_eqLogic').closest('.bs-sidebar').parent().removeClass().addClass('col-xs-2');
    $('.eqLogicThumbnailDisplay').removeClass().addClass('eqLogicThumbnailDisplay col-xs-12');
    $('.eqLogic').removeClass('col-xs-10 col-lg-10 col-md-9 col-sm-8 col-lg-9 col-md-8 col-sm-7').addClass('eqLogic col-xs-12');

    $('#ul_eqLogic').closest('.bs-sidebar').parent().on('mouseleave',function(){
        var timer = setTimeout(function(){
            $('#ul_eqLogic').closest('.bs-sidebar').parent().hide();
            $('.bt_pluginTemplateShowSidebar').find('i').show();
            $('.eqLogicThumbnailDisplay').removeClass().addClass('eqLogicThumbnailDisplay col-xs-12');
            $('.eqLogic').removeClass('col-xs-10 col-lg-10 col-md-9 col-sm-8 col-lg-9 col-md-8 col-sm-7').addClass('col-xs-12');
            $('.eqLogicThumbnailContainer').packery();
        }, 300);
        $(this).data('timerMouseleave', timer)
    }).on("mouseenter", function(){
      clearTimeout($(this).data('timerMouseleave'));
  });

    $('.bt_pluginTemplateShowSidebar').on('mouseenter',function(){
        var timer = setTimeout(function(){
            $('.eqLogicThumbnailDisplay').removeClass().addClass('eqLogicThumbnailDisplay col-xs-10');
            $('.bt_pluginTemplateShowSidebar').find('i').hide();
            $('.eqLogic').removeClass('col-xs-12').addClass('eqLogic col-xs-10');
            $('#ul_eqLogic').closest('.bs-sidebar').parent().show();
            $('.eqLogicThumbnailContainer').packery();
        }, 100);
        $(this).data('timerMouseleave', timer)
    }).off('mouseleave').on("mouseleave", function(){
      clearTimeout($(this).data('timerMouseleave'));
  });
}

var url = document.location.toString();
if (url.match('#')) {
    $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
} 
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
})

$('.eqLogicDisplayCard').on('click', function () {
    $('.li_eqLogic[data-eqLogic_id=' + $(this).attr('data-eqLogic_id') + ']').click();
});

$('.eqLogicAction[data-action=gotoPluginConf]').on('click', function () {
    $('#md_modal').dialog({title: "{{Configuration du plugin}}"});
    $("#md_modal").load('index.php?v=d&p=plugin&ajax=1&id='+eqType).dialog('open');
});

$('.eqLogicAction[data-action=returnToThumbnailDisplay]').on('click', function () {
    $('.eqLogic').hide();
    $('.eqLogicThumbnailDisplay').show();
    $('.li_eqLogic').removeClass('active');
    $('.eqLogicThumbnailContainer').packery();
});


$(".li_eqLogic").on('click', function () {
    jeedom.eqLogic.cache.getCmd = Array();
    if ($('.eqLogicThumbnailDisplay').html() != undefined) {
        $('.eqLogicThumbnailDisplay').hide();
    }

    $('.eqLogic').hide();
    if ('function' == typeof (prePrintEqLogic)) {
        prePrintEqLogic();
    }

    if (isset($(this).attr('data-eqLogic_type')) && isset($('.' + $(this).attr('data-eqLogic_type')))) {
        $('.' + $(this).attr('data-eqLogic_type')).show();
    } else {
        $('.eqLogic').show();
    }
    $('.li_eqLogic').removeClass('active');
    $(this).addClass('active');
    $.showLoading();

    jeedom.eqLogic.print({
        type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
        id: $(this).attr('data-eqLogic_id'),
        status : 1,
        error: function (error) {
            $.hideLoading();
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            $('body .eqLogicAttr').value('');
            if(isset(data) && isset(data.timeout) && data.timeout == 0){
                data.timeout = '';
            }
            $('body').setValues(data, '.eqLogicAttr');
            if ('function' == typeof (printEqLogic)) {
                printEqLogic(data);
            }
            if ('function' == typeof (addCmdToTable)) {
                $('.cmd').remove();
                for (var i in data.cmd) {
                    addCmdToTable(data.cmd[i]);
                }
            }
            modifyWithoutSave = false;
            $('body').delegate('.cmd .cmdAttr[data-l1key=type]', 'change', function () {
                jeedom.cmd.changeType($(this).closest('.cmd'));
            });

            $('body').delegate('.cmd .cmdAttr[data-l1key=subType]', 'change', function () {
                jeedom.cmd.changeSubType($(this).closest('.cmd'));
            });
            changeLeftMenuObjectOrEqLogicName = false;
            $.hideLoading();
        }
    });
    return false;
});

if (getUrlVars('saveSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
}

if (getUrlVars('removeSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Suppression effectuée avec succès}}', level: 'success'});
}

/**************************EqLogic*********************************************/
$('.eqLogicAction[data-action=copy]').on('click', function () {
    if ($('.li_eqLogic.active').attr('data-eqLogic_id') != undefined) {
        bootbox.prompt("{{Nom de la copie de l'équipement ?}}", function (result) {
            if (result !== null) {
                jeedom.eqLogic.copy({
                    id: $('.li_eqLogic.active').attr('data-eqLogic_id'),
                    name: result,
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        modifyWithoutSave = false;
                        var vars = getUrlVars();
                        var url = 'index.php?';
                        for (var i in vars) {
                            if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
                                url += i + '=' + vars[i].replace('#', '') + '&';
                            }
                        }
                        url += 'id=' + data.id + '&saveSuccessFull=1';
                        loadPage(url);
                        bootbox.hideAll();
                    }
                });
                return false;
            }
        });
    }
});

$('.eqLogicAction[data-action=export]').on('click', function () {
    window.open('core/php/export.php?type=eqLogic&id=' + $('.li_eqLogic.active').attr('data-eqLogic_id'), "_blank", null);
});

jwerty.key('ctrl+s', function (e) {
    e.preventDefault();
    $('.eqLogicAction[data-action=save]').click();
});

$('.eqLogicAction[data-action=save]').on('click', function () {
    var eqLogics = [];
    $('.eqLogic').each(function () {
        if ($(this).is(':visible')) {
            var eqLogic = $(this).getValues('.eqLogicAttr');
            eqLogic = eqLogic[0];
            eqLogic.cmd = $(this).find('.cmd').getValues('.cmdAttr');
            if ('function' == typeof (saveEqLogic)) {
                eqLogic = saveEqLogic(eqLogic);
            }
            eqLogics.push(eqLogic);
        }
    });
    jeedom.eqLogic.save({
        type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
        id: $(this).attr('data-eqLogic_id'),
        eqLogics: eqLogics,
        error: function (error) {
            $('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function (data) {
            modifyWithoutSave = false;
            var vars = getUrlVars();
            var url = 'index.php?';
            for (var i in vars) {
                if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
                    url += i + '=' + vars[i].replace('#', '') + '&';
                }
            }
            url += 'id=' + data.id + '&saveSuccessFull=1';
            if (document.location.toString().match('#')) {
                url += '#' + document.location.toString().split('#')[1];
            } 
            loadPage(url);
        }
    });
    return false;
});

$('.eqLogicAttr[data-l1key=name]').on('change', function () {
    changeLeftMenuObjectOrEqLogicName = true;
});

$('.eqLogicAttr[data-l1key=object_id]').on('change', function () {
    changeLeftMenuObjectOrEqLogicName = true;
});

$('.eqLogicAction[data-action=remove]').on('click', function () {
    if ($('.li_eqLogic.active').attr('data-eqLogic_id') != undefined) {
        bootbox.confirm('{{Etes-vous sûr de vouloir supprimer l\'équipement}} ' + eqType + ' <b>' + $('.li_eqLogic.active a:first').text() + '</b> ?', function (result) {
            if (result) {
                jeedom.eqLogic.remove({
                    type: isset($(this).attr('data-eqLogic_type')) ? $(this).attr('data-eqLogic_type') : eqType,
                    id: $('.li_eqLogic.active').attr('data-eqLogic_id'),
                    error: function (error) {
                        $('#div_alert').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function () {
                        var vars = getUrlVars();
                        var url = 'index.php?';
                        for (var i in vars) {
                            if (i != 'id' && i != 'removeSuccessFull' && i != 'saveSuccessFull') {
                                url += i + '=' + vars[i].replace('#', '') + '&';
                            }
                        }
                        modifyWithoutSave = false;
                        url += 'removeSuccessFull=1';
                        loadPage(url);
                    }
                });
            }
        });
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez d\'abord sélectionner un}} ' + eqType, level: 'danger'});
    }
});

$('.eqLogicAction[data-action=add]').on('click', function () {
    bootbox.prompt("{{Nom de l'équipement ?}}", function (result) {
        if (result !== null) {
            jeedom.eqLogic.save({
                type: eqType,
                eqLogics: [{name: result}],
                error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                },
                success: function (_data) {
                    var vars = getUrlVars();
                    var url = 'index.php?';
                    for (var i in vars) {
                        if (i != 'id' && i != 'saveSuccessFull' && i != 'removeSuccessFull') {
                            url += i + '=' + vars[i].replace('#', '') + '&';
                        }
                    }
                    modifyWithoutSave = false;
                    url += 'id=' + _data.id + '&saveSuccessFull=1';
                    loadPage(url);
                }
            });
        }
    });
});

$('.eqLogic .eqLogicAction[data-action=configure]').on('click', function () {
    $('#md_modal').dialog({title: "{{Configuration de l'équipement}}"});
    $('#md_modal').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $('.li_eqLogic.active').attr('data-eqLogic_id')).dialog('open');
});

$('#in_searchEqlogic').off('keyup').keyup(function () {
  var search = $(this).value();
  if(search == ''){
    $('.eqLogicDisplayCard').show();
    $('.eqLogicThumbnailContainer').packery();
    return;
}
$('.eqLogicDisplayCard').hide();
$('.eqLogicDisplayCard .name').each(function(){
    var text = $(this).text().toLowerCase();
    if(text.indexOf(search.toLowerCase()) >= 0){
      $(this)
      $(this).closest('.eqLogicDisplayCard').show();
  }
});
$('.eqLogicThumbnailContainer').packery();
});

/**************************CMD*********************************************/
$('.cmdAction[data-action=add]').on('click', function () {
   modifyWithoutSave = true;
   addCmdToTable();
   $('.cmd:last .cmdAttr[data-l1key=type]').trigger('change');
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-l1key=chooseIcon]',function () {
   modifyWithoutSave = true;
   var cmd = $(this).closest('.cmd');
   chooseIcon(function (_icon) {
    cmd.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
});
});

$('#div_pageContainer').on( 'click','.cmd .cmdAttr[data-l1key=display][data-l2key=icon]', function () {
   modifyWithoutSave = true;
   $(this).empty();
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-action=remove]',function () {
   modifyWithoutSave = true;
   $(this).closest('tr').remove();
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-action=copy]',function () {
   modifyWithoutSave = true;
   var cmd = $(this).closest('.cmd').getValues('.cmdAttr')[0];
   cmd.id= '';
   addCmdToTable(cmd);
});

$('#div_pageContainer').on( 'click','.cmd .cmdAction[data-action=test]',function (event) {
    $.hideAlert();
    if ($('.eqLogicAttr[data-l1key=isEnable]').is(':checked')) {
        var id = $(this).closest('.cmd').attr('data-cmd_id');
        jeedom.cmd.test({id: id});
    } else {
        $('#div_alert').showAlert({message: '{{Veuillez activer l\'équipement avant de tester une de ses commandes}}', level: 'warning'});
    }

});

$('#div_pageContainer').on( 'dblclick','.cmd input,select,span,a', function (event) {
 event.stopPropagation();
});

$('#div_pageContainer').on( 'dblclick','.cmd', function () {
 $('#md_modal').dialog({title: "{{Configuration commande}}"});
 $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-cmd_id')).dialog('open');
});

$('#div_pageContainer').on( 'click', '.cmd .cmdAction[data-action=configure]',function () {
    $('#md_modal').dialog({title: "{{Configuration commande}}"});
    $('#md_modal').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).closest('.cmd').attr('data-cmd_id')).dialog('open');
});

$('.eqLogicThumbnailContainer').packery();

if (is_numeric(getUrlVars('id'))) {
    if ($('#ul_eqLogic .li_eqLogic[data-eqLogic_id=' + getUrlVars('id') + ']').length != 0) {
        $('#ul_eqLogic .li_eqLogic[data-eqLogic_id=' + getUrlVars('id') + ']').click();
    } else {
        if ($('.eqLogicThumbnailDisplay').html() == undefined) {
            $('#ul_eqLogic .li_eqLogic:first').click();
        }
    }
} else {
    if ($('.eqLogicThumbnailDisplay').html() == undefined) {
        $('#ul_eqLogic .li_eqLogic:first').click();
    }
}

$("img.lazy").lazyload({
    event: "sporty"
});

$("img.lazy").each(function () {
    var el = $(this);
    if (el.attr('data-original2') != undefined) {
        $("<img>", {
            src: el.attr('data-original'),
            error: function () {
                $("<img>", {
                    src: el.attr('data-original2'),
                    error: function () {
                        if (el.attr('data-original3') != undefined) {
                            $("<img>", {
                                src: el.attr('data-original3'),
                                error: function () {
                                    el.lazyload({
                                        event: "sporty"
                                    });
                                    el.trigger("sporty");
                                },
                                load: function () {
                                    el.attr("data-original", el.attr('data-original3'));
                                    el.lazyload({
                                        event: "sporty"
                                    });
                                    el.trigger("sporty");
                                }
                            });
                        } else {
                            el.lazyload({
                                event: "sporty"
                            });
                            el.trigger("sporty");
                        }
                    },
                    load: function () {
                        el.attr("data-original", el.attr('data-original2'));
                        el.lazyload({
                            event: "sporty"
                        });
                        el.trigger("sporty");
                    }
                });
            },
            load: function () {
                el.lazyload({
                    event: "sporty"
                });
                el.trigger("sporty");
            }
        });
    } else {
        el.lazyload({
            event: "sporty"
        });
        el.trigger("sporty");
    }
});

$('body').delegate('.cmdAttr', 'change', function () {
    modifyWithoutSave = true;
});

$('body').delegate('.eqLogicAttr', 'change', function () {
    modifyWithoutSave = true;
});